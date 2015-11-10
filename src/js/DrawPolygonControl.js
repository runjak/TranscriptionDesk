"use strict";
define(['bootbox.min','aoiTypes'], function(bootbox, aoiTypes){
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
                        //var map = this_.getMap();
                        //Stop interacting:
                        //Gather information about AOIs:
                        var abs = [];//Absolute rectangles
                        newRects.forEach(function(r){
                            //Finding min/max values for all coordinates in a rectangle:
                            var xmin = Number.MAX_SAFE_INTEGER
                              , xmax = Number.MIN_SAFE_INTEGER
                              , ymin = Number.MAX_SAFE_INTEGER
                              , ymax = Number.MIN_SAFE_INTEGER;
                            r.getCoordinates().forEach(function(cs){
                                xmin = Math.min(xmin, cs[0]);
                                xmax = Math.max(xmax, cs[0]);
                                ymin = Math.min(ymin, cs[1]);
                                ymax = Math.max(ymax, cs[1]);
                            });
                            //Push absolute rectangle:
                            abs.push({
                                x:      xmin,
                                y:      ymin,
                                width:  xmax,
                                height: ymax
                            });
                        });
                        //Translate absolute to relative rectangles:
                        //TODO
                        //FIXME DEBUG:
                        window.abs = abs;
                        //Clear newRects:
                        newRects = [];
                        //Remove rectangles from source layer:
                        source.getFeatures().forEach(function(f){
                            source.removeFeature(f);
                        });
                        //Save gathered information:
                        //TODO
                        //Update presentation:
                        //TODO
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

