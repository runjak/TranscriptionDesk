"use strict";
/**
    This module returns a function that builds the
    image layers for the scan images and the viewExtent.
    It may also build vector layers to overlay the prev and next scans.
    The function should only be called after scanData.whenLoaded triggered.
    The scanData scan entries also get extent fields added.
*/
define(['scanData','ol'], function(scanData, ol){
    /**
        @return {
            viewExtent: extent
            layers: [Layer]
        }
    */
    return function(){
        //Object to modify and return:
        var ret = {
            viewExtent: [0,0,0,0],
            layers: []
        };
        //Producing layers:
        scanData.fields.forEach(function(f){
            var img = scanData[f].img
              , extent = [0, 0, img.width, img.height];
            //Updating viewExtent:
            for(var i = 2; i <= 3; i++){
                ret.viewExtent[i] = Math.max(ret.viewExtent[i], extent[i]);
            }
            //Shifting extent for prev and next scans:
            if(f === 'prev'){
                //Shifting prev scan:
                extent[0] -= extent[2] + 20;
                ret.viewExtent[0] -= extent[2] + 20;
                ret.viewExtent[2] += extent[2] + 20;
            }else if(f === 'next'){
                //Shifting next scan:
                extent[0] += scanData.current.img.width +20;
                ret.viewExtent[2] += extent[2] + 20;
            }
            //Keeping the extent:
            scanData[f].extent = extent;
            //Pushing the image layer:
            ret.layers.push(
                new ol.layer.Image({
                    source: new ol.source.ImageStatic({
                        url: img.src,
                        projection: new ol.proj.Projection({
                                code: f,
                                units: 'pixels',
                                extent: extent
                            }),
                        imageExtent: extent
                    })
                })
            );
        });
        //Done.
        return ret;
    };
});
