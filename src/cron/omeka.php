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
echo "ItemCount:\t".count($items)."\n";
//Storing Items:
echo "Storing items:\n";
foreach($items as $item){
    $err = $item->store();
    if($err !== null){
        echo "\n\t$err\n";
    }else{
        echo '.';
    }
}
echo "\n";
//Fetching files:
echo "Fetching files:\n";
$fCount = 0;
foreach($items as $item){
    $files = $item->getFiles();
    $fCount += count($files);
    echo '.';
}
echo "\nFileCount:\t$fCount\n";
//Storing files:
echo "Storing files:\n";
foreach($items as $item){
    $iUrn = $item->getUrn();
    $files = $item->getFiles();
    foreach($files as $file){
        $err = $file->store();
        if($err !== null){
            echo "\n\t$err\n";
        }else{
            echo '.';
        }
    }
}
echo "\n";
