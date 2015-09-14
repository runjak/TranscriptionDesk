<?php
/**
    This file is concerend with creating new AOIs.
    To do this, it expects certain POST parameters to be given,
    and a user to be logged in.
    This file returns JSON as its expected to be used via AJAX calls.
*/
require_once('config.php');
//Fail method:
$fail = function($reason){
    echo json_encode(array('error' => $reason));
    die();
};
//Casual check of POST parameters:
$expecteds = array('scanRectangleMap','type','typeText');
foreach($expecteds as $expected){
    if(!isset($_POST[$expected])){
        $fail("Post parameter '$expected' is missing!");
    }
}
//Checking user login:
$user = Config::getUserManager()->verify();
if($user === null){ $fail('User not logged in.'); }
//Checking correct type and typeText parameters:
require_once('database/areaOfInterest.php');
$type = $_POST['type'];
$typeText = $_POST['typeText'];
if(!AreaOfInterestType::validType($type)){
    $fail("Invalid type: '$type'");
}
if(AreaOfInterestType::hasText($type)){
    if(!is_string($typeText) || $typeText === ''){
        $fail("Type text must be a non empty string, and not '$typeText'.");
    }
}else{ $typeText = null; }
//Checking scanRectangleMap:
$scanRectangleMap = json_decode($_POST['scanRectangleMap'], true);
if($scanRectangleMap === null){
    $scanRectangleMap = $_POST['scanRectangleMap'];
    $fail("Could not decode json: '$scanRectangleMap'.");
}
//Creating AOI:
$aoi = AreaOfInterest::createAOI($scanRectangleMap, $user, $type, $typeText);
if($aoi instanceof Exception){
    $msg = $aoi->getMessage();
    $fail("Ran into problems when trying to create AOI: $msg");
}
//Finishing:
echo json_encode($aoi->toArray());
