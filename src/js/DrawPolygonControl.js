"use strict";
define(['bootbox.min','aoiTypes','scanData'], function(bootbox, aoiTypes, scanData){
    //Controller and Handler for drawing the rectangle
    return function(ol, source){

        //Array of newly created rectangles:
        var newRects = [];

        //Function to enable drawing rectangles on a given map:
        var addInteraction = function(map){
            var value = 'LineString',
                maxPoints = 2,
                geometryFunction = function(coordinates, geometry){
                    if(!geometry){
                        geometry = new ol.geom.Polygon(null);
                        newRects.push(geometry);
                    }
                    var start = coordinates[0];
                    var end = coordinates[1];
                    geometry.setCoordinates([
                        [start, [start[0], end[1]], end, [end[0], start[1]], start]
                    ]);
                    return geometry;
                };
            var draw = new ol.interaction.Draw({
                source: source,
                type: /** @type {ol.geom.GeometryType} */ (value),
                geometryFunction: geometryFunction,
                maxPoints: maxPoints
            });
            map.addInteraction(draw);
            return draw;
        };

        //Function to be called with data from mkDialog callback:
        var withTypes = function(typeEnum, typeText){};//May be replaced by below code.

        //Dialog to fetch aoi type description from user.
        var aoiTypesDialog = aoiTypes.mkDialog(function(){withTypes.apply(this, arguments)});

        var draw;
        return function(opt_options){
            var options = opt_options || {}; //options for openlayer control

            var button = document.createElement('button'); //defining button
            button.innerHTML = '▢';
            var this_ = this;
            var handleResize = function(e){ //handler when openlayer viewport is being changed
                this_.getMap().updateSize();
            };
            var toggle = false; //toggle boolean for button
            var handleDrawPolygon = function(e){ //main handler function for drawing the rectangle
                toggle = !toggle;
                if(toggle){
                    draw = addInteraction(this_.getMap()); //drawing Interaction is being added to openlayers
                }else{
                    this_.getMap().removeInteraction(draw);
                    withTypes = function(typeEnum, typeText){
                        //Gather information about AOIs:
                        var abs = [];//Absolute rectangles
                        newRects.forEach(function(r){
                            //Finding min/max values for all coordinates in a rectangle:
                            var xmin = Number.MAX_SAFE_INTEGER
                              , xmax = Number.MIN_SAFE_INTEGER
                              , ymin = Number.MAX_SAFE_INTEGER
                              , ymax = Number.MIN_SAFE_INTEGER;
                            r.getCoordinates().forEach(function(cs){
                                cs.forEach(function(c){
                                    xmin = Math.min(xmin, c[0]);
                                    xmax = Math.max(xmax, c[0]);
                                    ymin = Math.min(ymin, c[1]);
                                    ymax = Math.max(ymax, c[1]);
                                });
                            });
                            //Push absolute rectangle:
                            abs.push({ x:      xmin
                                     , y:      ymin
                                     , width:  xmax - xmin
                                     , height: ymax - ymin
                                     });
                        });
                        //Clear newRects:
                        newRects = [];
                        //Remove rectangles from source layer:
                        source.getFeatures().forEach(function(f){
                            source.removeFeature(f);
                        });
                        /**
                            We need to translate absolute to relative rectangles.
                            To do this the following steps are done:
                            1. Iterate all rectangles.
                            2. Find the Image they overlay completely (if they don't display a warning and break).
                            3. Calculate rectangle coordinates relative to found image.
                            4. Add to scanRectangleMap.
                        */
                        var scanRectangleMap = {};
                        var allFound = abs.every(function(r){
                            var field  = '' // Field containing r
                              , extent = null; // [0, 0, img.width, img.height]
                            var found  = scanData.fields.some(function(f){
                                //Data to work with:
                                field  = f;
                                extent = scanData[f].extent;
                                //Test if r in extent:
                                var tests = [ r.x            >= extent[0]
                                            , r.y            >= extent[1]
                                            , r.x + r.width  <= extent[2]
                                            , r.y + r.height <= extent[3]
                                            ];
                                return tests.every(function(b){ return b; });
                            });
                            //Translating and storing rectangle:
                            if(found){
                                //Calculating relative rectangle:
                                var rRect = {
                                    x:      (r.x      - extent[0]) / extent[2]
                                ,   y:      (r.y      - extent[1]) / extent[3]
                                ,   width:  (r.width  - extent[0]) / extent[2]
                                ,   height: (r.height - extent[1]) / extent[3]
                                };
                                //Urn to store rectangle with:
                                var urn = scanData[field].urn;
                                //Storing relative rectangle:
                                if(!(urn in scanRectangleMap)){
                                    scanRectangleMap[urn] = [];
                                }
                                scanRectangleMap[urn].push(rRect);
                            }
                            //Continues iterating with every iff found.
                            return found;
                        });
                        if(!allFound){
                            //Display error.
                            bootbox.dialog({
                                title: 'Error saving rectangles!',
                                message: 'At least one of the rectangles appears to not have been on a single image.'
                            });
                        }else{
                            //Continue usual work.
                            //FIXME DEBUG
                            console.log(scanRectangleMap);
                            window.srm = scanRectangleMap;
                            //Save gathered information:
                            //TODO
                            //Update presentation:
                            //TODO
                        }
                    };
                    aoiTypesDialog.modal({show: true});
                }
            };
            button.addEventListener('click', handleDrawPolygon);
            button.addEventListener('touchstart', handleDrawPolygon);
            addEventListener('resize', handleResize);

            //Setting up the openlayer control element
            var element = document.createElement('div');
            element.className = 'draw-polygon ol-selectable ol-control';
            element.appendChild(button);
            ol.control.Control.call(this, {
                element: element,
                target: options.target
            });
        };
    };
});

