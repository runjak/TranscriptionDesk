<?php
require_once 'resource.php';
/**
  Describes an element presented by the Omeka API
  at http://<host>/api/elements/1?key=…&pretty_print
*/
class OmekaElement extends OmekaResource {
  /***/
  public function getName(){
    return $this->data['name'];
  }
  /***/
  public function getDescription(){
    return $this->data['description'];
  }
}
/*
Example data seen in the wild:

{
  "id":1,
  "url":"http:\/\/<host>\/api\/elements\/1",
  "order":null,
  "name":"Text",
  "description":"Any textual data included in the document",
  "comment":"",
  "element_set":{
    "id":3,
    "url":"http:\/\/<host>\/api\/element_sets\/3",
    "resource":"element_sets"
  },
  "extended_resources":[

  ]
}
*/
