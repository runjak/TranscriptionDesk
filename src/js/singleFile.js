"use strict";
require(['require', 'jquery', 'ol', 'bootbox.min', 'jquery-ui.min',
    'DrawPolygonControl', 'ResetPolygonControl',
    'scanData', 'scanLayers', 'drawAOIs'],
    function(require, $, ol, bootbox){
    $(document).ready(function(){
        var source = new ol.source.Vector({wrapX: false});
        //See singleFile.php for structure of scanData.
        var scanData = require('scanData');
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

        var img = scanData.current.img;
        //Waiting for proms to complete:
        scanData.whenLoaded(function(){
            var scanLayers = require('scanLayers')();
            //Composing layers:
            var layers = scanLayers.layers;
            layers.push(vector);
            //Building map:
            var map = new ol.Map({
                layers: layers,
                target: 'map',
                view: new ol.View({
                    projection: new ol.proj.Projection({
                        code:  'Transcription Picture',
                        units: 'pixels',
                        extent: scanLayers.viewExtent
                    }),
                    center: ol.extent.getCenter(scanLayers.viewExtent),
                    zoom: 2
                    }),
                controls: ol.control.defaults({
                    attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                        collapsible: false
                    })
                }).extend([new DrawPolygonControl(), new ResetPolygonControl()])
            });
            //Drawing existing AOIs:
            require('drawAOIs')(map);
        });
    });
});
