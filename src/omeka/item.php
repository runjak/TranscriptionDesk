<?php
require_once 'displayInfo.php';
require_once 'file.php';
/**
  Describes an Omeka item as returned by
  http://<host>/api/items/6?key=…&pretty_print
*/
class OmekaItem extends OmekaDisplayInfo {
  /** Attribute for memoization of getCollection(). */
  private $collection = null;
  /**
    @return $col OmekaCollection
    Returns the OmekaCollection an OmekaItem belongs to.
  */
  public function getCollection(){
    if($this->collection === null){
      $cId = $this->data['collection']['id'];
      $this->collection = Config::getOmeka()->getCollection($cId);
    }
    return $this->collection;
  }
  /**
    @return fileCount Int
  */
  public function getFileCount(){
    return $this->data['files']['count'];
  }
  /** Attribute for memoization of getFiles(). */
  private $files = null;
  /***/
  public function getFiles(){
    if($this->files === null){
      $this->files = array();
      $url = $this->data['files']['url'];
      foreach(Config::getOmeka()->httpGet($url) as $fData){
        array_push($this->files, new OmekaFile($fData));
      }
    }
    return $this->files;
  }
  /**
    Attribute for memoization of getDublinCore().
    OmekaElement->getName() -> String
  */
  private $dublinCore = null;
  /**
    Filters all element_texts of an OmekaItem for entries form 'Dublin Core'.
    The result is a map from OmekaElement->getName() to String
    for 'Dublin Core' entries.
    Keys like {'Title','Identifier','Language','Coverage'} have been seen.
  */
  public function getDublinCore(){
    if($this->dublinCore === null){
      $this->dublinCore = array();
      foreach($this->data['element_texts'] as $et){
        //Stuff to work with:
        $eSet = $et['element_set'];
        $el = $et['element'];
        //Check that the current element text belongs to Dublin Core:
        if($eSet['name'] !== 'Dublin Core'){
          continue;
        }
        //Addition of an entry:
        $this->dublinCore[$el['name']] = $et['text'];
      }
    }
    return $this->dublinCore;
  }
  /**
    @return $url String||null
    Returns the URN for an OmekaItem
    by returning the 'Dublin Core' Identifier, iff possible.
  */
  public function getUrn(){
    $dc = $this->getDublinCore();
    if(array_key_exists('Identifier', $dc)){
      return $dc['Identifier'];
    }
    return null;
  }
  /**
    We overwrite parents update mathod to make sure memoization will be cleared.
  */
  public function update(){
    parent::update();
    $this->collection = null;
    $this->files = null;
    $this->dublinCore = null;
  }
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
