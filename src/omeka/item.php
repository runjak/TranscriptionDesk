<?php
require_once 'displayInfo.php';
/**
  Describes an Omeka item as returned by
  http://<host>/api/items/6?key=…&pretty_print
*/
class OmekaItem extends OmekaDisplayInfo {
}
/*
Example data seen in the wild:

{
  "id":6,
  "url":"http:\/\/<host>\/api\/items\/6",
  "public":false,
  "featured":true,
  "added":"2015-06-17T23:02:04+00:00",
  "modified":"2015-06-20T20:42:40+00:00",
  "item_type":{
    "id":6,
    "url":"http:\/\/<host>\/api\/item_types\/6",
    "name":"Still Image",
    "resource":"item_types"
  },
  "collection":{
    "id":1,
    "url":"http:\/\/<host>\/api\/collections\/1",
    "resource":"collections"
  },
  "owner":{
    "id":1,
    "url":"http:\/\/<host>\/api\/users\/1",
    "resource":"users"
  },
  "files":{
    "count":36,
    "url":"http:\/\/<host>\/api\/files?item=6",
    "resource":"files"
  },
  "tags":[

  ],
  "element_texts":[
    {
      "text":"Belluno, lol. 25, II, 8v-26r",
      "element_set":{
        "id":1,
        "url":"http:\/\/<host>\/api\/element_sets\/1",
        "name":"Dublin Core",
        "resource":"element_sets"
      },
      "element":{
        "id":50,
        "url":"http:\/\/<host>\/api\/elements\/50",
        "name":"Title",
        "resource":"elements"
      }
    },
    {
      "text":"Latin",
      "element_set":{
        "id":1,
        "url":"http:\/\/<host>\/api\/element_sets\/1",
        "name":"Dublin Core",
        "resource":"element_sets"
      },
      "element":{
        "id":44,
        "url":"http:\/\/<host>\/api\/elements\/44",
        "name":"Language",
        "resource":"elements"
      }
    },
    {
      "text":"urn:cite:ogl:belluno_lol25",
      "element_set":{
        "id":1,
        "url":"http:\/\/<host>\/api\/element_sets\/1",
        "name":"Dublin Core",
        "resource":"element_sets"
      },
      "element":{
        "id":43,
        "url":"http:\/\/<host>\/api\/elements\/43",
        "name":"Identifier",
        "resource":"elements"
      }
    },
    {
      "text":"Petronius'\u00a0<em>Satyrica<\/em><br \/>urn:cts:latinLit:phi0972.phi001",
      "element_set":{
        "id":1,
        "url":"http:\/\/<host>\/api\/element_sets\/1",
        "name":"Dublin Core",
        "resource":"element_sets"
      },
      "element":{
        "id":38,
        "url":"http:\/\/<host>\/api\/elements\/38",
        "name":"Coverage",
        "resource":"elements"
      }
    }
  ],
  "extended_resources":{
    "comments":{
      "count":0,
      "resource":"comments",
      "url":"http:\/\/<host>\/api\/comments?record_type=Item&record_id=6"
    },
    "exhibit_pages":{
      "count":0,
      "url":"http:\/\/<host>\/api\/exhibit_pages?item=6",
      "resource":"exhibit_pages"
    }
  }
}
*/
?>
