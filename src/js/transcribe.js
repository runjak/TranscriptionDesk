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
    var source = new ol.source.Vector({wrapX: false});

    var vector = new ol.layer.Vector({
        source: source,
        style: new ol.style.Style({
            fill: new ol.style.Fill({
                color: 'rgba(255, 255, 255, 0.2)'
            }),
            stroke: new ol.style.Stroke({
                color: '#ffcc33',
                width: 2
            }),
            image: new ol.style.Circle({
                radius: 7,
                fill: new ol.style.Fill({
                    color: '#ffcc33'
                })
            })
        })
    });
    var draw;
    function addInteraction(map){
        var value = 'LineString',
            maxPoints = 2,
            geometryFunction = function(coordinates, geometry) {
                if (!geometry) {
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
    app.DrawPolygonControl = function(opt_options) {

        var options = opt_options || {};

        var button = document.createElement('button');
        button.innerHTML = '▢';

        var this_ = this;

        var handleResize = function(e){
            this_.getMap().updateSize();
        };
        var toggle = false;
        var handleDrawPolygon = function(e) {
            toggle = !toggle;
            if(toggle) {
                addInteraction(this_.getMap());
            } else {
                this_.getMap().removeInteraction(draw);
            }
        };

        button.addEventListener('click', handleDrawPolygon);
        button.addEventListener('touchstart', handleDrawPolygon);
        addEventListener('resize', handleResize);

        var element = document.createElement('div');
        element.className = 'draw-polygon ol-selectable ol-control';
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
                }),
                vector
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
