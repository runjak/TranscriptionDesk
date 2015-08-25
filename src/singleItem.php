<?php
/**
    This file is concerend with displaying a single OmekaItem.
    To do so, it expects a urn GET parameter to be given.
    If the urn parameter is missing, singleItem/noGet.php will be required.
    If the urn parameter is invalid, singleItem/invalidUrn.php will be required.
    Else the page will be displayed as expected.
*/
if(!isset($_GET['urn']) || !$_GET['urn']){
    require('singleItem/noGet.php');
}else{
    require_once('config.php');
    $item = Config::getOmeka()->getItem($_GET['urn']);
    if($item === null){
        error_log('HERE!');
        require('singleItem/invalidUrn.php');
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
                    echo '<li class="media">'
                       . '<div class="media-left">'
                         . '<a href="#">'
                           . '<img class="media-object" src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9InllcyI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIHZpZXdCb3g9IjAgMCA2NCA2NCIgcHJlc2VydmVBc3BlY3RSYXRpbz0ibm9uZSI+PCEtLQpTb3VyY2UgVVJMOiBob2xkZXIuanMvNjR4NjQKQ3JlYXRlZCB3aXRoIEhvbGRlci5qcyAyLjYuMC4KTGVhcm4gbW9yZSBhdCBodHRwOi8vaG9sZGVyanMuY29tCihjKSAyMDEyLTIwMTUgSXZhbiBNYWxvcGluc2t5IC0gaHR0cDovL2ltc2t5LmNvCi0tPjxkZWZzPjxzdHlsZSB0eXBlPSJ0ZXh0L2NzcyI+PCFbQ0RBVEFbI2hvbGRlcl8xNGY2OWMyMmMwZSB0ZXh0IHsgZmlsbDojQUFBQUFBO2ZvbnQtd2VpZ2h0OmJvbGQ7Zm9udC1mYW1pbHk6QXJpYWwsIEhlbHZldGljYSwgT3BlbiBTYW5zLCBzYW5zLXNlcmlmLCBtb25vc3BhY2U7Zm9udC1zaXplOjEwcHQgfSBdXT48L3N0eWxlPjwvZGVmcz48ZyBpZD0iaG9sZGVyXzE0ZjY5YzIyYzBlIj48cmVjdCB3aWR0aD0iNjQiIGhlaWdodD0iNjQiIGZpbGw9IiNFRUVFRUUiLz48Zz48dGV4dCB4PSIxNCIgeT0iMzYuOCI+NjR4NjQ8L3RleHQ+PC9nPjwvZz48L3N2Zz4=">'
                         . '</a>'
                       . '</div><div class="media-body">'
                         . '<h4 class="media-heading">File x</h4>'
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
