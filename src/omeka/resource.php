<?php
/**
  Describes any single item presented by the Omeka API,
  that has got it's own URL.
*/
class OmekaResource {
  protected $data = null; // Data carried by the resource
  /**
    @param $data array of json decoded data.
    Create a new Resource from $name and $data.
  */
  public function __construct($data){
    if(!array_key_exists('url', $data)){
      throw new Exception('Malformed data for creation of OmekaResource.');
    }
    $this->data = $data;
  }
  /**
    @param $field String
    @return misc || null
    Generic getter for $data an OmekaResource was created with.
  */
  public function get($field){
    if(array_key_exists($field, $this->data)){
      return $this->data[$field];
    }
    return null;
  }
  /**
    @return $url String
    Returns the Url to query to retrieve a resource.
  */
  public function getUrl(){
    return $this->data['url'];
  }
}
?>
