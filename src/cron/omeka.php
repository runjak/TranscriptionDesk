<?php
/**
    This script crawls the Omeka API to fetch information on items and scans,
    and inserts this info into the database.
    It is intended to run this script via cron periodically.
*/
//First some include magic:
//Making sure execution directory is same as this file:
chdir(dirname(__FILE__));
//Adjusting include path so that it works with config.php:
set_include_path('..');
include_once '../config.php';
//Restoring include path:
restore_include_path();
//Getting the omeka instance:
$omeka = Config::getOmeka();
//Making sure we fetch from API:
$omeka->setDbUsage(false);
//Fetching items:
$items = $omeka->getItems();
$iCount = count($items);
echo "Fetched $iCount Omeka items, storing…\n";
//Storing Items:
foreach($items as $item){
    $err = $item->store();
    if($err !== null){
        echo "$err\n";
    }
}
//Fetching files:
$fCount = 0;
foreach($items as $item){
    $files = $item->getFiles();
    $fCount += count($files);
}
echo "Fetched $fCount files, storing…\n";
//Storing files:
foreach($items as $item){
    $iUrn = $item->getUrn();
    $files = $item->getFiles();
    foreach($files as $file){
        $err = $file->store();
        if($err !== null){
            echo "$err\n";
        }
    }
}
