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
            scan.aois.forEach(function(aoi){
                //Checking if all data is known:
                var isKnown = Object.keys(aois.scanRectangleMap).every(function(urn){
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
                    rectangle.forEach(function(c, i){
                        projected.push(c * extent[extentTranslation[i]]);
                    });
                    rectangles.push(projected);
                });
            });
        });
        return rectangles;
    };
    // Function to render AOIs on a map:
    return function(map){
        var rectangles = getProjectedRectangles(getAOIs());
        /*
            Drawing the rectangles:
            https://gis.stackexchange.com/a/27392/23691
            https://gis.stackexchange.com/a/27395/23691
        */
        console.log('Rectangles are:', rectangles);
        //FIXME IMPLEMENT
    };
});
