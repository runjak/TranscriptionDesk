<?php
/**
Implements parsing and creation of URNs for AOIs.
- An AreaOfInterestUrn follows this schema:
    urn:cite:olg:leiden_vlf123_0001.tif@<double>,<double>,<double>,<double>
                                      +@<double>,<double>,<double>,<double>
                                      +leiden_vlf123_0002.tif@<double>,<double>,<double>,<double>
  The '<double>,<double>,<double>,<double>' here denote the percentual location and size of a rectangle as x,y,width,height.
  Note that later scan URNs don't repeat the common prefix!
*/
class AreaOfInterestUrn {
    /** Array of rectangle keys in order.  */
    private static $keys = array('x','y','width','height');
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
                }else{ return $fail('Could not parse $rectangle after $lastScanUrn. '.$rectangle); }
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
        //Function to return failures:
        $fail = function($reason) use ($s){
            return "Invalid parameter in AreaOfInterest::parseRectangle('$s'). Reason: $reason";
        };
        if(!is_string($s)){ return $fail('Not a string'); }
        //Dissecting $s:
        if(preg_match('/^@([\d\.]+),([\d\.]+),([\d\.]+),([\d\.]+)$/', $s, $matches)){
            $rectangle = array(
                'x'      => floatval($matches[1])
            ,   'y'      => floatval($matches[2])
            ,   'width'  => floatval($matches[3])
            ,   'height' => floatval($matches[4])
            );
            //Validating $rect values:
            $valid = self::validateRectangle($rectangle);
            if($valid !== null){ return $fail($valid); }
            //Returning valid $rectangle
            return $rectangle;
        }
        return $fail('Didn\'t match preg');
    }
    /**
        @param $rectangle ['x' => Double,'y' => Double,'width' => Double,'height' => Double]
        @return $valid null || String
        Returned String is error message.
        Checks the following constraints:
        - $rectangle must be an array
        - keys must include be {'x','y','width','height'}
        - all double values must be in [0,1]
        - x+width must be in [0,1]
        - y+height must be in [0,1]
    */
    public static function validateRectangle($rectangle){
        //Function to return failures:
        $fail = function($reason) use ($rectangle){
            $s = json_encode($rectangle);
            return "Invalid \$rectangle in AreaOfInterest::validateRectangle('$s'). Reason: $reason";
        };
        //Must be an array:
        if(!is_array($rectangle)){
            return $fail('Parameter is not an array.');
        }
        //Must have expected keys:
        $keys = array('x','y','width','height');
        foreach($keys as $k){
            if(!array_key_exists($k, $rectangle)){
                return $fail("Key '$k' missing!");
            }
        }
        //Double values as expected:
        $vals = $rectangle;
        $vals['x+width']  = $rectangle['x'] + $rectangle['width'];
        $vals['y+height'] = $rectangle['y'] + $rectangle['height'];
        foreach($vals as $k => $v){
            if($v < 0 || $v > 1){
                return $fail('Invalid double for '.$k);
            }
        }
        return null;
    }
    /**
        @param $scanRectangleMap [scanUrn => [rectangle]]
        @param $urn String || Exception
        Takes a scanRectangleMap and produces an $urn if possible.
        If validation of the rectangles failes, there will be an Exception returned instead of the $urn.
        Checks to see if given $scanUrn entries have a common prefix according to convention.
        Does not validate if $scanUrn entries exist in our database.
    */
    public static function createUrn($scanRectangleMap){
        //Setup:
        $urn = array();//Will be imploded with '+' for return
        $urnPrefix = null;//Common prefix of all $scanUrn keys.
        //Helper functions:
        $fail = function($reason){
            return new Exception("Problem in AreaOfInterest::createUrn(…). Reason: $reason");
        };
        $startsWith = function($haystack, $needle){//https://stackoverflow.com/a/10473026/448591
            return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
        };
        //Checking input:
        if(!is_array($scanRectangleMap)){ return $fail('$scanRectangleMap is not an array!'); }
        //Building $urn:
        if(count($scanRectangleMap) === 0){
            $fail('Cannot build $urn for empty $scanRectangleMap.');
        }else foreach($scanRectangleMap as $scanUrn => $rectangles){
            if($urnPrefix === null){//Need to discover the $urnPrefix:
                $parts = explode(':', $scanUrn);
                array_pop($parts);
                if(count($parts) === 0){
                    return $fail("Could not build \$urnPrefix from \$scanUrn: '$scanUrn'.");
                }
                $urnPrefix = implode(':', $parts).':';
            }else{//We already know the $urnPrefix:
                if(!$startsWith($scanUrn, $urnPrefix)){
                    return $fail("'$urnPrefix' is not a prefix of '$scanUrn'!");
                }
                //Rewrite $scanUrn to suffix:
                $scanUrn = substr($scanUrn, strlen($urnPrefix));
            }
            //Creating $elem to compose $urn from:
            $elem = $scanUrn;
            foreach($rectangles as $rectangle){
                $valid = self::validateRectangle($rectangle);
                if($valid !== null){ return $fail($valid); }
                $vals = array();
                foreach(self::$keys as $k){
                    $n = ''.$rectangle[$k];
                    if($n !== '0'){ $n = ltrim($n, '0'); }
                    array_push($vals, $n);
                }
                array_push($urn, $elem.'@'.implode(',', $vals));
                $elem = '';
            }
        }
        //Finish:
        if(count($urn) === 0){ return $fail('Empty $urn.'); }
        return implode('+',$urn);
    }
}
