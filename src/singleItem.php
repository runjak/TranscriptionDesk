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
    <style type=”text/css”>
        .thumbnail {
            margin-bottom:7px;
        }
    </style>
    <body>
        <?php require_once('navbar.php'); ?>
        <div class="container">
            <div class="row">
                <h3>Basic information about this Item:</h3>
                <table class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <tbody><?php
                        foreach($item->getDublinCore() as $k => $v){
                            echo "<tr><td>$k:</td><td>$v</td></tr>";
                        }
                    ?></tbody>
                </table>
            </div>
            <div class="row">
                <h4>Scans included in this item:</h4>
                <?php
                    foreach($item->getFiles() as $file){
                        $urn = $file->getUrn();
                        $thumb = $file->getThumbnailFileUrl();
                        echo '<div class="col-xs-3">'
                                . '<a href="singleFile.php?urn='.$urn.'" class="thumbnail">'
                                    . '<img src="'.$thumb.'" class="img-responsive">'
                                . '</a>'
                            .'</div>';
                    }
                ?>
            </div>
        </div>
    </body
</html><?php
    }
}
