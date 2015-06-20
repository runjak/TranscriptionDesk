<?php
/**
  Describes an Omeka resource as returned by
  http://<host>/api/resources?pretty_print
*/
class Resource {
  private $data = null; // Array to map contained data
  private $name = null; // String resource name
  /**
    @param $data array of json decoded data.
    @return $valid Bool
    Checks if the given data is valid in the sense
    that Resource.get{Url,Actions,RecordType} methods will work.
  */
  private static function validate($data){
    $keys = array('record_type', 'actions', 'url');
    foreach($keys as $k){
      if(!array_key_exists($k, $data)){
        return false;
      }
    }
    return true;
  }
  /**
    @param $name String name of the resource.
    @param $data array of json decoded data.
    Create a new Resource from $name and $data.
  */
  public function __construct($name, $data){
    if($name !== 'site' && $name !== 'resources'){
      if(!self::validate($data)){
        throw new Exception('Malformed data for creation of Resource.');
      }
    }
    $this->name = $name;
    $this->data = $data;
  }
  /**
    @return $url String
    Returns the Url to query to retrieve a resource.
  */
  public function getUrl(){
    return $this->data['url'];
  }
  /**
    @return $actions [String]
    Returns a list of strings describing actions for a resource.
  */
  public function getActions(){
    return $this->data['actions'];
  }
  /**
    @return $recordType String
    A String describing the type of content
    described by the Resource.
  */
  public function getRecordType(){
    return $this->data['record_type'];
  }
  /**
    @return indesParams [String]||null
    Returns a list of strings describing the index parameters for a resource.
  */
  public function getIndexParams(){
    if(!array_key_exists('index_params', $this->data)){
      return null;
    }
    return $this->data['index_params'];
  }
}
?>
