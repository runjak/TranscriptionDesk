"use strict";
define(['bootbox.min'],function(bootbox){
    return function(ol, source){
        return function(opt_options){

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
    };
});
