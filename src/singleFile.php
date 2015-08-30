<?php
/**
    This file is concerend with displaying a single OmekaFile.
    To do so, it expects a urn GET parameter to be given.
    If the urn parameter is missing, errors/noGet.php will be required.
    If the urn parameter is invalid, errors/invalidUrn.php will be required.
    Else the page will be displayed as expected.
*/
if(!isset($_GET['urn']) || !$_GET['urn']){
    require('errors/noGet.php');//FIXME something general for URN parameter missing?
}else{
    require_once('config.php');
    $file = OmekaFile::getFileFromDb($_GET_['urn']);
    if($file === null){
        require('errors/invalidUrn.php');//FIXME General invalidUrn url?
    }else{
        //FIXME normal page here!
    }
}
