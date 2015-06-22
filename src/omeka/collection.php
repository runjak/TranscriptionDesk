<?php
require_once 'displayInfo.php';
require_once 'item.php';
/**
  Describes an Omeka collection as returned by
  http://<host>/api/collections?key=…&pretty_print
*/
class OmekaCollection extends OmekaDisplayInfo {
  /**
    @return $id Integer
    Returns the id of an OmekaCollection;
    expected to be an int.
  */
  public function getId(){
    return $this->data['id'];
  }
  /**
    @return $url String/URL
    Returns the url that allows to fetch the owner of an OmekaCollection.
  */
  private function getOwnerUrl(){
    return $this->data['owner']['url'];
  }
  /**
    FIXME this shall build on top of OmekaCollection.getOwnerUrl,
    to return the owner directly.
    FIXME apparently we don't have access to user data as of now,
    bc. the API key is tied to lesser access rights.
    Maybe this should be a reason to not implement this method.
  */
  public function getOwner(){
    return null; // FIXME implement
  }
  /**
    @return $count Int
    Returns the number of items currently held in an OmekaCollection.
  */
  public function getItemCount(){
    return $this->data['items']['count'];
  }
  /** Attribute for memoization of getItems(). */
  private $items = null;
  /**
    @return items [OmekaItem]
    Returns an array of OmekaItems that belong to an OmekaCollection.
  */
  public function getItems(){
    if($this->items === null){
      $this->items = array();
      $url = $this->data['items']['url'];
      $items = Config::getOmeka()->httpGet($url);
      foreach($items as $i){
        array_push($this->items, new OmekaItem($i));
      }
    }
    return $this->items;
  }
  /**
    We overwrite parents update mathod to make sure memoization will be cleared.
  */
  public function update(){
    parent::update();
    $this->items = null;
  }
}
/*
Example data seen in the wild:

{
    "id":1,
    "url":"http:\/\/<host>\/api\/collections\/1",
    "public":false,
    "featured":true,
    "added":"2015-06-17T10:47:29+00:00",
    "modified":"2015-06-19T16:51:25+00:00",
    "owner":{
      "id":1,
      "url":"http:\/\/<host>\/api\/users\/1",
      "resource":"users"
    },
    "items":{
      "count":2,
      "url":"http:\/\/<host>\/api\/items?collection=1",
      "resource":"items"
    },
    "element_texts":[
      {
        "text":"Digital Petronius",
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
        "text":"DigitalPetronius",
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
      }
    ],
    "extended_resources":{
      "comments":{
        "count":0,
        "resource":"comments",
        "url":"http:\/\/<host>\/api\/comments?record_type=Collection&record_id=1"
      }
    }
  },
*/
?>
