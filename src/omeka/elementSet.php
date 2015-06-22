<?php
require_once 'resource.php';
require_once 'element.php';
/**
  Describes an element set presented by the Omeka API
  at http://<host>/api/element_sets?key=…&pretty_print
*/
class OmekaElementSet extends OmekaResource {
  /**
    @return name String
  */
  public function getName(){
    return $this->data['name'];
  }
  /**
    @return description String
  */
  public function getDescription(){
    return $this->data['description'];
  }
  /**
    @return elementCount Int
  */
  public function getElementCount(){
    return $this->data['elements']['count'];
  }
  /**
    Attribute for memoization of getElements().
    OmekaElement->getUrl() -> OmekaElement
  */
  private $elements = null;
  /**
    @return elements [OmekaElement]
    Returns all elements gathered in an OmekaElementSet.
  */
  public function getElements(){
    if($this->elements === null){
      $this->elements = array();
      $url = $this->data['elements']['url'];
      $elements = Config::getOmeka()->httpGet($url);
      foreach($elements as $eData){
        $el = new OmekaElement($eData);
        $this->elements[$el->getUrl()] = $el;
      }
    }
    return $this->elements;
  }
  /**
    @param $url String/URL
    @return $element OmekaElement||null
    Returns an OmekaElement by its URL.
    This shall be especially useful to figure out
    what the corresponding OmekaElement is
    for a given 'element_texts' entry of an OmekaItem.
  */
  public function getElementByUrl($url){
    if($this->elements === null){
      $this->getElements();
    }
    if(array_key_exists($url, $this->elements)){
      return $this->elements[$url];
    }
    return null;
  }
  /**
    We overwrite parents update mathod to make sure memoization will be cleared.
  */
  public function update(){
    parent::update();
    $this->elements = null;
  }
}
/*
Example data seen in the wild:

[
  {
    "id":1,
    "url":"http:\/\/<host>\/api\/element_sets\/1",
    "name":"Dublin Core",
    "description":"The Dublin Core metadata element set is common to all Omeka records, including items, files, and collections. For more information see, http:\/\/dublincore.org\/documents\/dces\/.",
    "record_type":null,
    "elements":{
      "count":15,
      "url":"http:\/\/<host>\/api\/elements?element_set=1",
      "resource":"elements"
    },
    "extended_resources":[

    ]
  },
  {
    "id":3,
    "url":"http:\/\/<host>\/api\/element_sets\/3",
    "name":"Item Type Metadata",
    "description":"The item type metadata element set, consisting of all item type elements bundled with Omeka and all item type elements created by an administrator.",
    "record_type":"Item",
    "elements":{
      "count":34,
      "url":"http:\/\/<host>\/api\/elements?element_set=3",
      "resource":"elements"
    },
    "extended_resources":[

    ]
  },
  {
    "id":4,
    "url":"http:\/\/<host>\/api\/element_sets\/4",
    "name":"Scripto",
    "description":null,
    "record_type":null,
    "elements":{
      "count":1,
      "url":"http:\/\/<host>\/api\/elements?element_set=4",
      "resource":"elements"
    },
    "extended_resources":[

    ]
  }
]
*/
?>
