require(['jquery','ol','bootbox.min','jquery-ui.min'], function($, ol, bootbox){
    $(document).ready(function(){
        window.app = {};
        var source = new ol.source.Vector({wrapX: false});
        //See singleFile.php for structure of scanData.
        var scanData = $.parseJSON($('#scanData').text());
        var vector = new ol.layer.Vector({
            source: source,
            style: new ol.style.Style({
                fill: new ol.style.Fill({
                    color: 'rgba(255, 255, 255, 0.1)'
                }),
                stroke: new ol.style.Stroke({
                    color: '#FD7214',
                    width: 2
                }),
                image: new ol.style.Circle({
                    radius: 7,
                    fill: new ol.style.Fill({
                        color: '#FD7214'
                    })
                })
            })
        });
        var draw;
        function addInteraction(map){
            var value = 'LineString',
                maxPoints = 2,
                geometryFunction = function(coordinates, geometry){
                    if(!geometry){
                        geometry = new ol.geom.Polygon(null);
                    }
                    var start = coordinates[0];
                    var end = coordinates[1];
                    geometry.setCoordinates([
                        [start, [start[0], end[1]], end, [end[0], start[1]], start]
                    ]);
                    return geometry;
                };
            draw = new ol.interaction.Draw({
                source: source,
                type: /** @type {ol.geom.GeometryType} */ (value),
                geometryFunction: geometryFunction,
                maxPoints: maxPoints
            });
            map.addInteraction(draw);
        }

        //Controller and Handler for drawing the rectangle
        app.DrawPolygonControl = function(opt_options){

            var options = opt_options || {};    //options for openlayer control

            var button = document.createElement('button');  //defining button
            button.innerHTML = '▢';
            var this_ = this;
            var handleResize = function(e){     //handler when openlayer viewport is being changed
                this_.getMap().updateSize();
            };
            var toggle = false;     //toggle boolean for button
            var handleDrawPolygon = function(e){   //main handler function for drawing the rectangle
                toggle = !toggle;
                if(toggle){
                    addInteraction(this_.getMap()); //drawing Interaction is being added to openlayers
                }else{
                    bootbox.prompt({    //standart bootbox prompt, can be changed
                        title: "Are you done with your selection?",
                        value: "Name of selection",
                        callback: function(result){
                            if(result === null){
                                toggle = !toggle;
                            }else{
                                this_.getMap().removeInteraction(draw);
                                document.getElementById("markdown").innerHTML = result; //testdisplay, on trigger event spawn ace-editor
                            }
                        }
                    });
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
        ol.inherits(app.DrawPolygonControl, ol.control.Control);    //Drawing rectangle function is being added to openlayers

        //Main controller and handler to reset the latest drawn box, still WIP
        app.ResetPolygonControl = function(opt_options){

            var resetOptions = opt_options || {};

            var resetButton = document.createElement('button');
            resetButton.innerHTML = 'R';
            var handleResetPolygon = function(e){
                //default confirm bootbox, can be changed / extended
                bootbox.confirm("Do you really want to reset your latest selection?", function(result){
                    if(result){
                        // Definition of the Reset Function.

                        // Clears ALL Features of the Source Vector, thus removing every box
                        //source.clear(); //Resetfunction must be defined here, currently clear of ALL vectors

                        // Clears the newest box that has not been cleared yet. Does not, however, clear the entire last selection!
                        source.removeFeature(source.getFeatures()[source.getFeatures().length - 1]);
                    }
                });

            };
            resetButton.addEventListener('click', handleResetPolygon);
            resetButton.addEventListener('touchstart', handleResetPolygon);

            var resetElement = document.createElement('div');
            resetElement.className = 'reset-polygon ol-selectable ol-control';
            resetElement.appendChild(resetButton);
            ol.control.Control.call(this, {
                element: resetElement,
                target: resetOptions.target
            });
        };
        ol.inherits(app.ResetPolygonControl, ol.control.Control);

        //Preloading image data:
        var proms = [];
        $.each(scanData, function(key, scan){
            var def = $.Deferred(), img = new Image();
            //Handling promise for this scan:
            img.onload = function(){ def.resolve(); };
            proms.push(def.promise());
            //We exchange scan.img from String to Image:
            img.src = scan.img;
            scan.img = img;
        });
        var img = scanData.current.img;
        //Waiting for proms to complete:
        $.when.apply($, proms).done(function(){
            //Data to build view from:
            var viewExtent = null;
            /**
                @param img Image
                @param o {img: Image, direction: {'left','right'}, code: {'prev','current','next'}, key: String, scanData key}
            */
            var mkLayer = function(img, o){
                //Sanitizing o:
                o = o || {};
                if('direction' in o){
                    var dir = o.direction;
                    if(dir !== 'left' && dir !== 'right'){
                        o.direction = 'left';
                    }
                }else{ o.direction = 'left'; }
                if(!('code' in o)){
                    o.code = 'Transcription Picture';
                }
                //Calculating extent:
                var extent = [0, 0, img.width, img.height];
                //Adjust for relative image:
                if('img' in o){
                    var w = o.img.width;
                    if(o.direction === 'right'){
                        w *= -1;
                    }
                    extent[0] += w;
                    //Expand view extend:
                    if(viewExtent === null){
                        viewExtent = extent.slice();
                    }else{
                        //Grow width:
                        viewExtent[2] += extent[2];
                        if(w < 0){ viewExtent[0] += w;}
                    }
                }
                //Add extent to scanData:
                scanData[o.key].extent = extent;
                //Building Layer:
                return new ol.layer.Image({
                    source: new ol.source.ImageStatic({
                        url: img.src,
                        projection: new ol.proj.Projection({
                                code: o.code,
                                units: 'pixels',
                                extent: extent
                            }),
                        imageExtent: extent
                    })
                });
            };
            //Creating layers:
            var layers = [];
            ['prev','current','next'].forEach(function(k){
                if(k in scanData){
                    var img = scanData[k].img,
                        o = {code: 'Transcribe '+k, key: k};
                    if(k !== 'current'){
                        o.img = scanData['current'].img;
                        if(k === 'prev'){
                            o.direction = 'right';
                        }else{
                            o.direction = 'left';
                        }
                    }
                    layers.push(mkLayer(img, o));
                }
            });
            layers.push(vector);
            //Building map:
            var map = new ol.Map({
                layers: layers,
                target: 'map',
                view: new ol.View({
                    projection: new ol.proj.Projection({
                        code:  'Transcription Picture',
                        units: 'pixels',
                        extent: viewExtent
                    }),
                    center: ol.extent.getCenter(viewExtent),
                    zoom: 2
                    }),
                controls: ol.control.defaults({
                    attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                        collapsible: false
                    })
                }).extend([new app.DrawPolygonControl(), new app.ResetPolygonControl()])
            });
        });
    });
});
