"use strict";
/*
    This module exports a function that takes an OpenLayers map and
    will draw on it all AOIs currently known to the scanData module.
*/
define(['scanData', 'ol'], function(scanData, ol){
    // Creating a map from URNs to the corresponding scan entries:
    var urnToScanMap = {};
    scanData.fields.forEach(function(f){
        var scan = scanData[f];
        urnToScanMap[scan.urn] = scan;
    });
    // Function to gather all AOIs that are completely represented on scanData:
    var getAOIs = function(){
        var aois = [];
        Object.keys(urnToScanMap).forEach(function(urn){
            var scan = urnToScanMap[urn];
            Object.keys(scan.aois).forEach(function(aoiUrn){
                var aoi = scan.aois[aoiUrn];
                //Checking if all data is known:
                var isKnown = Object.keys(aoi.scanRectangleMap).every(function(urn){
                    return (urn in urnToScanMap);
                });
                if(isKnown){ aois.push(aoi); }
            });
        });
        return aois;
    };
    /**
        @param aois [aoi entry]
        @return rectangles [[x,y,width,height]]
        Function to produce projected rectangles from AOIs.
    */
    var getProjectedRectangles = function(aois){
        var rectangles = [] // Array of rectangles to be returned.
          , extentTranslation = [2, 3, 2, 3]; // Used for projection.
        aois.forEach(function(aoi){
            /*
                For each entry in the scanRectangleMap:
                - Fetch the extent used by the scan belonging to that urn.
                - Iterate the rectangles, and
                - Project rectangles to the respective extent
                - push projected rectangles into the rectangles array.
            */
            Object.keys(aoi.scanRectangleMap).forEach(function(scanUrn){
                var extent = urnToScanMap[scanUrn].extent;
                aoi.scanRectangleMap[scanUrn].forEach(function(rectangle){
                    var projected = [];
                    ['x','y','width','height'].forEach(function(field, i){
                        var p = rectangle[field] * extent[extentTranslation[i]];
                        projected.push(p);
                    });
                    rectangles.push(projected);
                });
            });
        });
        return rectangles;
    };
    // Function to render AOIs on a map:
    return function(map){
        // Finding the vector layer:
        var layer = (function(){
            var ret = null;
            map.getLayers().getArray().some(function(layer){
                if(layer instanceof ol.layer.Vector){
                    ret = layer;
                    return true;
                }
                return false;
            });
            return ret;
        })();
        // Rectangles to draw:
        var rectangles = getProjectedRectangles(getAOIs());
        // Drawing the rectangles:
        rectangles.forEach(function(rect){
            var geometry = new ol.geom.Polygon(null)
              , coordinates = [
                [rect[0]          , rect[1]          ],
                [rect[0] + rect[2], rect[1]          ],
                [rect[0] + rect[2], rect[1] + rect[3]],
                [rect[0]          , rect[1] + rect[3]],
                [rect[0]          , rect[1]          ]
            ];
            geometry.setCoordinates([coordinates]);
            var feature = new ol.Feature({geometry: geometry});
            layer.getSource().addFeature(feature);
        });
    };
});
