<?php
require_once 'timestamped.php';
/**
  Describes an OmekaTimestamped that has public and featured fields
  that carry booleans.
  Since this information will be used to check for whom we can display
  which information, this class will be named OmekaDisplayInfo.
  Current children include Omeka{Collection,Item}.
*/
class OmekaDisplayInfo extends OmekaTimestamped {
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
}
?>
