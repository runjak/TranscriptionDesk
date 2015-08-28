<?php
require_once('areaOfInterestType.php');
require_once('../omeka/file.php');
require_once('../auth/user.php');
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
    private $scanRectangleMap = null;//[scanUrn => [rectangle]] like produced by self::parseUrn()
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
        @param $urn String
        @return $scanRectangleMap [scanUrn => [rectangle]] || String
        Parses a $urn for AOIs to build a map from scans to lists of rectangles.
    */
    public static function parseUrn($urn){
        //Function to log failures and return null:
        $fail = function($reason) use ($urn){
            return "Invalid URN in AreaOfInterest::parseUrn('$urn'). Reason: $reason";
        };
        //Making sure that $urn is a String:
        if(!is_string($urn)){ return $fail('Urn is not a string.'); }
        //Map to be returned:
        $scanRectangleMap = array();
        //Parsing magic:
        $lastScanUrn = null;//Expected to be String later.
        $urnPrefix = null;//Expected to be changed later.
        foreach(explode('+', $urn) as $part){
            //Check if $part start with '@':
            $rectangle = self::parseRectangle($part);
            if(is_array($rectangle)){
                if(is_string($lastScanUrn)){
                    array_push($scanRectangleMap[$lastScanUrn], $rectangle);
                }else{ return $fail('Parsed $rectangle before $lastScanUrn.'); }
            }else if(preg_match('/^([^@]+)(@.+)$/', $part, $matches)){//We have a case of 'scanUrn@rectangle'.
                if($lastScanUrn === null){
                    $lastScanUrn = $matches[1];
                    //First scan URN, fill prefix:
                    $parts = explode(':', $lastScanUrn);
                    array_pop($parts);//Removing last part
                    $urnPrefix = implode(':', $parts);
                }else{
                    if($urnPrefix === null){ return $fail('$urnPrefix wasn\'t set.'); }
                    $lastScanUrn = $urnPrefix.':'.$matches[1];
                }
                $rectangle = self::parseRectangle($matches[2]);
                if(is_array($rectangle)){
                    $scanRectangleMap[$lastScanUrn] = array($rectangle);
                }else{ return $fail('Could not parse $rectangle after $lastScanUrn.'); }
            }else{ return $fail("\$part appeared to be malformed: '$part'"); }
        }
        //Done:
        return $scanRectangleMap;
    }
    /**
        @param $s String
        @return $rectangle ['x' => Double,'y' => Double,'width' => Double,'height' => Double] || String
        The given String must start with '@', and contain 4 non negative double values that are separated by ','.
        This is mostly a helper method for AreaOfInterest::parseUrn().
        Constraints for doubles are:
        - All double values must be in [0,1]
        - x+width must be in [0,1]
        - y+height must be in [0,1]
    */
    public static function parseRectangle($s){
        //Function to log failures and return null:
        $fail = function($reason) use ($s){
            return "Invalid parameter in AreaOfInterest::parseRectangle('$s'). Reason: $reason";
        };
        if(!is_string($s)){ $fail('Not a string'); }
        //Dissecting $s:
        if(preg_match('/^@([\d\.]+),([\d\.]+),([\d\.]+),([\d\.]+)$/', $s, $matches)){
            $rectangle = array(
                'x'      => floatval($matches[1])
            ,   'y'      => floatval($matches[2])
            ,   'width'  => floatval($matches[3])
            ,   'height' => floatval($matches[4])
            );
            //Validating $rect values:
            $vals = $rectangle;
            $vals['x+width'] = $rectangle['x'] + $rectangle['width'];
            $vals['y+height'] = $rectangle['y'] + $rectangle['height'];
            foreach($vals as $k => $v){
                if($v < 0 || $v > 1){
                    return $fail($s, 'Invalid double for '.$k);
                }
            }
            //Returning valid $rectangle
            return $rectangle;
        }
        return $fail('Didn\'t match preg');
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
            $aoi->scanRectangleMap = self::parseUrn($urn);
            if($aoi->scanRectangleMap === null){ continue; }
            //Pushing into return values:
            array_push($aois, $aoi);
        }
        $stmt->close();
        return $aois;
    }
    /**
        @param $urn String
        @return $aoi AreaOfInterest || null
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
        return null;
    }
    /**
        @param $urns [String]
        @return $aois [AreaOfInterest]
    */
    public static function getAOIsFromUrns($urns){
        $aois = array();
        foreach($urns as $urn){
            $aoi = self::getAOIFromUrn($urn);
            if($aoi !== null){
                array_push($aois, $aoi);
            }
        }
        return $aois;
    }
}
