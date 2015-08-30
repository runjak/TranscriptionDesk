<?php
/**
    This file is concerend with displaying a single OmekaItem.
    To do so, it expects a urn GET parameter to be given.
    If the urn parameter is missing, errors/noGet.php will be required.
    If the urn parameter is invalid, errors/invalidUrn.php will be required.
    Else the page will be displayed as expected.
*/
if(!isset($_GET['urn']) || !$_GET['urn']){
    require('errors/noGet.php');
}else{
    require_once('config.php');
    $item = Config::getOmeka()->getItem($_GET['urn']);
    if($item === null){
        require('errors/invalidUrn.php');
    }else{
?><!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>Viewing item <?php echo $item->getUrn();?></title>
        <?php require_once 'head.php';?>
    </head>
    <body>
        <?php require_once('navbar.php'); ?>
        <div class="container">
            Basic information about this Item:
            <table class="table table-bordered">
                <tbody><?php
                    foreach($item->getDublinCore() as $k => $v){
                        echo "<tr><td>$k:</td><td>$v</td></tr>";
                    }
                ?></tbody>
            </table>
            Scans included in this item:
            <ul class="media-list"><?php
                foreach($item->getFiles() as $file){
                    $urn = $file->getUrn();
                    $thumb = $file->getThumbnailFileUrl();
                    echo '<li class="media">'
                       . '<div class="media-left">'
                         . '<a href="singleFile.php?urn='.$urn.'">'
                           . '<img class="media-object" src="'.$thumb.'">'
                         . '</a>'
                       . '</div><div class="media-body">'
                         . '<h4 class="media-heading">'.$urn.'</h4>'
                         . 'Something goes here! Lorem Ipsum?'
                       . '</div>'
                       . '</li>';
                }
            ?></ul>
        </div>
    </body
</html><?php
    }
}