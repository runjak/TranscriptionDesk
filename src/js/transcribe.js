$(document).ready(function(){
    var img = new Image();
    window.app = {};
    var app = window.app;
    var editor = $('.editor'),
        markdown = $('.markdown'),
        content = $('.cont'),
        event = document.createEvent('Event');
    event.initEvent('resize', true, true);
    editor.resizable({
        handles: 'e',
        resize: function(e, ui){
            var w = content.width() - editor.width();
            markdown.css('width', (w-3)+'px');
            document.dispatchEvent(event);
        }
    });
    app.DrawPolygonControl = function(opt_options) {

        var options = opt_options || {};

        var button = document.createElement('button');
        button.innerHTML = '▢';

        var this_ = this;
        var handleResize = function(e){
          this_.getMap().updateSize();
        };
        var handleDrawPolygon = function(e) {
            //this_.getMap().updateSize();
        };

        button.addEventListener('click', handleDrawPolygon, false);
        button.addEventListener('touchstart', handleDrawPolygon, false);
        addEventListener('resize', handleResize);

        var element = document.createElement('div');
        element.className = 'draw-polygon ol-unselectable ol-control';
        element.appendChild(button);

        ol.control.Control.call(this, {
            element: element,
            target: options.target
        });
    };
    ol.inherits(app.DrawPolygonControl, ol.control.Control);

    img.onload = function() {
        var extent = [0, 0, this.width, this.height];
        var projection = new ol.proj.Projection({
            code: 'Transcription Picture',
            units: 'pixels',
            extent: extent
        });

        var map = new ol.Map({
            layers: [
                new ol.layer.Image({
                    source: new ol.source.ImageStatic({
                        url: img.src,
                        projection: projection,
                        imageExtent: extent
                    })
                })
            ],
            target: 'map',
            view: new ol.View({
                projection: projection,
                center: ol.extent.getCenter(extent),
                zoom: 2
            }),
            controls: ol.control.defaults({
                attributionOptions: /** @type {olx.control.AttributionOptions} */ ({
                    collapsible: false
                })
            }).extend([new app.DrawPolygonControl()])
        });
    };
    img.src = 'http://139.18.40.155/files/original/DigitalPetronius/urn_cite_ogl_bnf_7989/8a702e8561d87f0a2ed54609058f9ae9.jpeg';
});
