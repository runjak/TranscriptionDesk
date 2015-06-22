<?php
require_once 'timestamped.php';
/**
  Describes an Omeka file as returned by
  http://<host>/api/files?item=5&key=…&pretty_print
*/
class OmekaFile extends OmekaTimestamped {
}
/*
Example data seen in the wild:

[
  {
    "id":31,
    "url":"http:\/\/<host>\/api\/files\/31",
    "file_urls":{
      "original":"http:\/\/<host>\/files\/original\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpeg",
      "fullsize":"http:\/\/<host>\/files\/fullsize\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg",
      "thumbnail":"http:\/\/<host>\/files\/thumbnails\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg",
      "square_thumbnail":"http:\/\/<host>\/files\/square_thumbnails\/DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpg"
    },
    "added":"2015-06-19T16:06:59+00:00",
    "modified":"2015-06-22T10:45:38+00:00",
    "filename":"DigitalPetronius\/urn_cite_ogl_bnf_7989\/8a702e8561d87f0a2ed54609058f9ae9.jpeg",
    "authentication":"0f7be474264c8f6dab5501666418ee31",
    "has_derivative_image":true,
    "mime_type":"image\/jpeg",
    "order":null,
    "original_filename":"bnf_lat7989_195.jpeg",
    "size":1708132,
    "stored":true,
    "type_os":"JPEG image data, JFIF standard 1.01, resolution (DPI), density 72x72, segment length 16, Exif Standard: [TIFF image data, big-endian, direntries=4, xresolution=62, yresolution=70, resolutionunit=2], baseline, precision 8, 2844x3855, frames 3",
    "metadata":{
      "mime_type":"image\/jpeg",
      "video":{
        "dataformat":"jpg",
        "lossless":false,
        "bits_per_sample":24,
        "pixel_aspect_ratio":1
      }
    }, …
]
*/
?>
