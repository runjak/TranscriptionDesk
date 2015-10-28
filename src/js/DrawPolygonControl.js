"use strict";
define(function(){
    //Controller and Handler for drawing the rectangle
    return function(ol, source){

        var addInteraction = function(map){
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
            var draw = new ol.interaction.Draw({
                source: source,
                type: /** @type {ol.geom.GeometryType} */ (value),
                geometryFunction: geometryFunction,
                maxPoints: maxPoints
            });
            map.addInteraction(draw);
            return draw;
        };

        return function(opt_options){
            var options = opt_options || {};    //options for openlayer control

            var button = document.createElement('button');  //defining button
            button.innerHTML = '▢';
            var this_ = this;
            var handleResize = function(e){     //handler when openlayer viewport is being changed
                this_.getMap().updateSize();
            };
            var toggle = false;     //toggle boolean for button
            var handleDrawPolygon = function(e){   //main handler function for drawing the rectangle
                var draw;
                toggle = !toggle;
                if(toggle){
                    draw = addInteraction(this_.getMap()); //drawing Interaction is being added to openlayers
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
    };
});

