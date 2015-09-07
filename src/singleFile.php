<?php
/**
    This file is concerend with displaying a single OmekaFile.
    To do so, it expects a urn GET parameter to be given.
    If the urn parameter is missing, errors/noGet.php will be required.
    If the urn parameter is invalid, errors/invalidUrn.php will be required.
    Else the page will be displayed as expected.
*/
if(!isset($_GET['urn']) || !$_GET['urn']){
    require('errors/noGet.php');
}else{
    require_once('config.php');
    $file = OmekaFile::getFileFromDb($_GET['urn']);
    if($file === null){
        require('errors/invalidUrn.php');
    }else{
        //We need to hand out some file info as JSON:
        $json = array();
        //Function to add an OmekaFile $file to a $field String in the $json array.
        $addFile = function($field, $file) use (&$json){
            $json[$field] = array(
                'urn' => $file->getUrn()
            ,   'img' => $file->getFullsizeFileUrl()
            );
        };
        //Adding {current, prev, main}:
        $addFile('current', $file);
        if($prev = $file->getPrev()){
            $addFile('prev', $prev);
        }
        if($next = $file->getNext()){
            $addFile('next', $next);
        }
?><!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Viewing item <?php echo $file->getUrn();?></title>
        <?php require_once 'head.php';?>
        <script src="js/singleFile.js"></script>
    </head>
    <body>
        <?php require_once('navbar.php'); ?>
        <div class="container">
            <h4>Mark an area of interest on this File:</h4>
            <div id="map" class="map"></div>
            <div id="scanData" class="hide"><?php echo json_encode($json);?></div>
        </div>
    </body
</html><?php
    }
}
