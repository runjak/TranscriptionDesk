<?php
require_once 'resource.php';
/**
  Describes an OmekaResource that has added and modified fields
  that carries timestamps.
  Currently these are Omeka{Collection,Item,File} and possibly more.
*/
class OmekaTimestamped extends OmekaResource {
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
}
?>
