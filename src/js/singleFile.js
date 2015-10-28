"use strict";
require(['require','jquery','ol','bootbox.min','jquery-ui.min','DrawPolygonControl', 'ResetPolygonControl'], function(require, $, ol, bootbox){
    $(document).ready(function(){
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

        //Drawing rectangle function is being added to openlayers:
        var DrawPolygonControl = require('DrawPolygonControl')(ol, source);
        ol.inherits(DrawPolygonControl, ol.control.Control);

        //Main controller and handler to reset the latest drawn box, still WIP
        var ResetPolygonControl = require('ResetPolygonControl')(ol, source);
        ol.inherits(ResetPolygonControl, ol.control.Control);

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
                }).extend([new DrawPolygonControl(), new ResetPolygonControl()])
            });
        });
    });
});
