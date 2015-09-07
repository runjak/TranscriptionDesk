require(['jquery','ol'], function($, ol){
    //See singleFile.php for structure of scanData.
    var scanData = $.parseJSON($('#scanData').text());
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
    //Waiting for proms to complete:
    $.when.apply($, proms).done(function(){
        //Init openlayers map:
        var img = scanData.current.img;
        var extent = [0, 0, img.width, img.height];
        var projection = new ol.proj.Projection({
            code: 'Transcription Picture',
            units: 'pixels',
            extent: extent
        });
        var map = new ol.Map({
            layers: [
                new ol.layer.Image({
                    source: new ol.source.ImageStatic({
                        url: img.src
                    ,   projection: projection
                    ,   imageExtent: extent
                    })
                })
            ]
        ,   target: 'map'
        ,   view: new ol.View({
                projection: projection
            ,   center: ol.extent.getCenter(extent)
            ,   zoom: 2
            })
        ,   controls: ol.control.defaults({
                attributionOptions: /** @type {olx.control.AttributionOptions} */ {
                    collapsible: false
                }
            })
        });
    });
});
