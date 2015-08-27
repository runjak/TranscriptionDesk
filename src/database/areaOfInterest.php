<?php
require_once('areaOfInterestType.php');
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
    /**
        @param $urn String
        @return $scanRectangleMap [scanUrn => [rectangle]] || null
        Parses a $urn for AOIs to build a map from scans to lists of rectangles.
    */
    public static function parseUrn($urn){
        //Function to log failures and return null:
        $fail = function($urn){
            error_log("Invalid URN in AreaOfInterest::parseUrn('$urn')");
            return null;
        };
        //Making sure that $urn is a String:
        if(!is_string($urn)){ return fail($urn); }
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
                }else{ return $fail($urn); }
            }else if(preg_match('/^([^@]+)(@.+)$/', $part, $matches)){//We have a case of 'scanUrn@rectangle'.
                if($lastScanUrn === null){
                    $lastScanUrn = $matches[1];
                    //First scan URN, fill prefix:
                    $parts = explode(':', $lastScanUrn);
                    array_pop($parts);//Removing last part
                    $urnPrefix = implode(':', $parts);
                }else{
                    if($urnPrefix === null){ return $fail($urn); }
                    $lastScanUrn = $urnPrefix.':'.$matches[1];
                }
                $rectangle = self::parseRectangle($matches[2]);
                if(is_array($rectangle)){
                    $scanRectangleMap[$lastScanUrn] = array($rectangle);
                }else{ return $fail($urn); }
            }else{ return $fail($urn); }
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
        $fail = function($s, $reason){
            error_log("Invalid parameter in AreaOfInterest::parseRectangle('$s'). Reason: $reason");
            return $reason;
        };
        if(!is_string($s)){ $fail($s,'Not a string'); }
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
        return 'Didn\'t match preg';
    }
}
