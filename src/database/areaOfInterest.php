<?php
require_once('areaOfInterestType.php');
require_once('areaOfInterestUrn.php');
require_once('omeka/file.php');
require_once('auth/user.php');
/**
    Describes an entry in the areasOfInterest (AOI) table.
    Despite a $type that is a key of AreaOfInterestType.types(),
    an AreaOfInterest carries the following informations:
    - A number of scans that belong to the same item.
    - A number of rectangles, each belonging to exactly one of the AOIs scans.
    - A timestamp
    - A URN following this schema:
        urn:cite:olg:leiden_vlf123_0001.tif@<double>,<double>,<double>,<double>
                                          +@<double>,<double>,<double>,<double>
                                          +leiden_vlf123_0002.tif@<double>,<double>,<double>,<double>
      The '<double>,<double>,<double>,<double>' here denote the percentual location and size of a rectangle as x,y,width,height.
      Note that later scan URNs don't repeat the common prefix!
*/
class AreaOfInterest {
    //The type realized by an AOI:
    private $type = null;//Uses AreaOfInterestType
    private $typeText = null;//Some types shall have text
    private $scanRectangleMap = null;//[scanUrn => [rectangle]] like produced by AreaOfInterestUrn::parseUrn()
    private $timestamp = null;
    private $userId = null;
    private $urn = null;
    /**
        @return $scanCount Int
        Returns the number of scans that an AreaOfInterest is related to.
    */
    public function getScanCount(){
        return count($this->getScanUrns());
    }
    /**
        @return $scanUrns [String]
        Returns an array holding the URNs of all scans that an AreaOfInterest is related to.
    */
    public function getScanUrns(){
        return array_keys($this->scanRectangleMap);
    }
    /**
        Attribute for memoization of getScans().
    */
    private $scans = null;
    /**
        @return $scans [OmekaFile]
        Returns an array of OmekaFile representing the scans an AOI belongs to.
    */
    public function getScans(){
        if($this->scans === null){
            $this->scans = array();
            foreach($this->getScanUrns() as $urn){
                $file = OmekaFile::getFileFromDb($urn);
                if($file !== null){
                    array_push($this->scans, $file);
                }
            }
        }
        return $this->scans;
    }
    /**
        @return $userId Int
        Returns the userId that is associated with an AreaOfInterest.
    */
    public function getUserId(){
        return intval($this->userId);
    }
    /**
        Attribute for memoization of getUser().
    */
    private $user = null;
    /**
        @return $user User
        Returns the User that is associated with an AreaOfInterest.
    */
    public function getUser(){
        if($this->user === null){
            $this->user = User::fromUserId($this->getUserId());
        }
        return $this->user;
    }
    /**
        @return $urn String
        Returns the URN for an AOI.
    */
    public function getUrn(){
        return $this->urn;
    }
    /**
        @return $timestamp String
        Returns the timestamp an AOI was created.
    */
    public function getTimestamp(){
        return $this->timestamp;
    }
    /**
        @return $type Int
        Returns the AreaOfInterestType const that describes the type of an AOI.
    */
    public function getType(){
        return intval($this->type);
    }
    /**
        @return $typeText String
        Returns the typeText that may accompany the AOIs type.
        Will be '' if type shouldn't have a text.
    */
    public function getTypeText(){
        if(!AreaOfInterestType::hasText($this->getType())){
            return '';
        }
        return $this->typeText;
    }
    /**
        @param $stmt mysqli_stmt
        @return $aois [AreaOfInterest]
        Helper method for self::getAOI*().
        Executes and closes $stmt.
    */
    private static function getAOIsFromStmt($stmt){
        $aois = array();
        $stmt->execute();
        $stmt->bind_result($urn, $timestamp, $userId, $typeEnum, $typeText);
        while($stmt->fetch()){
            $aoi = new AreaOfInterest();
            //Basic attributes:
            $aoi->type = $typeEnum;
            $aoi->typeText = $typeText;
            $aoi->timestamp = $timestamp;
            $aoi->userId = $userId;
            $aoi->urn = $urn;
            //Parsing URN:
            $aoi->scanRectangleMap = AreaOfInterestUrn::parseUrn($urn);
            if($aoi->scanRectangleMap === null){ continue; }
            //Pushing into return values:
            array_push($aois, $aoi);
        }
        $stmt->close();
        return $aois;
    }
    /**
        @param $urn String
        @return $aoi AreaOfInterest || Exception
    */
    public static function getAOIFromUrn($urn){
        $q = 'SELECT urn, timestamp, userId, typeEnum, typeText '
           . 'FROM areasOfInterest WHERE urn = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $urn);
        $aois = self::getAOIsFromStmt($stmt);
        if(count($aois) === 1){
            return current($aois);
        }
        return new Exception("Could not find distinct '$urn' in database. Sorry.");
    }
    /**
        @param $urns [String]
        @return $aois [AreaOfInterest] || Exception
    */
    public static function getAOIsFromUrns($urns){
        $aois = array();
        foreach($urns as $urn){
            $aoi = self::getAOIFromUrn($urn);
            if($aoi instanceof Exception){
                return $aoi;
            }else{
                array_push($aois, $aoi);
            }
        }
        return $aois;
    }
    /**
        @param $scanRectangleMap [scanUrn => [rectangle]]
        @param $user User, see auth/user.php
        @param $type int, see AreaOfInterestType
        @param $typeText String || null, see AreaOfInterestType
        @return $aoi AreaOfInterest || Exception
        Tries to create a new AreaOfInterest.
        Constraints:
        $scanUrn keys of $scanRectangleMap must be valid URNs for scans table.
        $scanRectangleMap must pass AreaOfInterestUrn::createUrn().
        $type must pass AreaOfInterestType::validType().
        $typeText may only be !== null, if AreaOfInterestType::hasText($type).
        Created urn may only have maximum length of 250.
    */
    public static function createAOI($scanRectangleMap, $user, $type, $typeText = null){
        $fail = function($reason){//Fail helper
            return new Exception("Problem in AreaOfInterest::createAOI(…). Reason: $reason");
        };
        //Checking some constraints:
        if(!is_array($scanRectangleMap)){
            return $fail('$scanRectangleMap must be an array.');
        }
        if(!($user instanceof User)){
            return $fail('$user must be instance of auth/user.php.');
        }
        if(!AreaOfInterestType::validType($type)){
            return $fail('$type has invalid/unexpected value.');
        }
        if($typeText !== null){
            if(is_string($typeText)){
                if(!AreaOfInterestType::hasText($type)){
                    return $fail('$typeText given when it was not allowed.');
                }else{
                    //Storing empty string in db instead of null:
                    $typeText = '';
                }
            }else{
                return $fail('Invalid value for $typeText: '.$typeText);
            }
        }else{ $typeText = ''; }
        //Checking scan URNs:
        $scanUrns = array_keys($scanRectangleMap);
        if(!OmekaFile::validateUrns($scanUrns)){
            return $fail('At least one urn was invalid. $urns: '.json_encode($scanUrns));
        }
        //Trying to create URN:
        $urn = AreaOfInterestUrn::createUrn($scanRectangleMap);
        if($urn instanceof Exception){
            $msg = $urn->getMessage();
            return $fail('Could not create URN. '.$msg);
        }
        if(strlen($urn) > 250){
            return $fail("\$urn is longer than 250 chars: '$urn'.");
        }
        //Input valid, can create AreaOfInterest:
        $q = 'INSERT INTO areasOfInterest (urn,userId,typeEnum,typeText) VALUES (?,?,?,?)';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('siis', $urn, $user->getUserId(), $type, $typeText);
        $stmt->execute();
        $stmt->close();
        //Creating entries for scanAoiMap:
        $q = 'INSERT INTO scanAoiMap (aoiUrn, scanUrn) VALUES (?,?)';
        foreach($scanUrns as $scanUrn){
            $stmt = Config::getDB()->prepare($q);
            $stmt->bind_param('ss', $urn, $scanUrn);
            $stmt->execute();
            $stmt->close();
        }
        //Return AOI, iff possible:
        return self::getAOIFromUrn($urn);
    }
    /**
        @return $ret []
        Creates an array representation of some data contained in an AOI.
        This is helpful for further serialization to JSON.
    */
    public function toArray(){
        return array(
            'scanRectangleMap' => $this->scanRectangleMap
        ,   'urn' => $this->getUrn()
        ,   'userId' => $this->getUserId()
        ,   'timestamp' => $this->getTimestamp()
        ,   'type' => $this->getType()
        ,   'typeText' => $this->getTypeText()
        );
    }
}
