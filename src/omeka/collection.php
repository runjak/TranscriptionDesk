<?php
/**
  Describes an Omeka collection as returned by
  http://<host>/api/collections?key=…&pretty_print
*/
class Collection {
  /**
    Storage for data:
  */
  private $data = null;
  /**
    This constructor doesn't perform any validation.
  */
  public function __construct($data){
    $this->data = $data;
  }
  /**
    @return $id Integer
    Returns the id of a Collection;
    expected to be an int.
  */
  public function getId(){
    return $this->data['id'];
  }
  /**
    @return $url URL/String
    Returns the URL to query for
    the current data of a Collection.
  */
  public function getUrl(){
    return $this->data['url'];
  }
  /**
    @return $public Bool
  */
  public function isPublic(){
    return $this->data['public'];
  }
  /**
    @return $featured Bool
  */
  public function isFeatured(){
    return $this->data['featured'];
  }
  /**
    @return $added String, ISO date
    In the format of 2015-06-17T10:47:29+00:00
  */
  public function getAdded(){
    return $this->data['added'];
  }
  /**
    @return $modified String, ISO date
    In the format of 2015-06-19T16:51:25+00:00
  */
  public function getModified(){
    return $this->data['modified'];
  }
  /**
    @return $url String/URL
    Returns the url that allows to fetch the owner of a Collection.
  */
  private function getOwnerUrl(){
    return $this->data['owner']['url'];
  }
  /**
    FIXME this shall build on top of Collection.getOwnerUrl,
    to return the owner directly.
  */
  public function getOwner(){
    return null; // FIXME implement
  }
  /**
    @return $count Int
    Returns the number of items currently held in a Collection.
  */
  public function getItemCount(){
    return $this->data['items']['count'];
  }
  /**
    @return $url String/URL
    Returns the URl that can be used to fetch all items in a Collection.
  */
  private function getItemsUrl(){
    return $this->data['items']['url'];
  }
  /**
    FIXME this shall build on top of Collection.getItemsUrl,
    to return the Items directly.
  */
  public function getItems(){
    return null; // FIXME implement
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
