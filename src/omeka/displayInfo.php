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
    /**
        @return should Boolean
        In [1] we noted, that things that can be public and/or featured should be displayed under the following conditions:
        * The thing is public and featured.
        * The thing is featured and a user is logged in.
        [1]: https://github.com/runjak/TranscriptionDesk/blob/master/notes/2015-06-19.md
    */
    public function shouldDisplay(){
        //¬featured -> ¬shouldDisplay():
        if(!$this->isFeatured()) return false;
        //public && featured -> shouldDisplay():
        if($this->isPublic()) return true;
        //Depends on login:
        $user = Config::getUserManager()->verify();
        return ($user !== null);
    }
}
