<?php
/**
  This file is meant to be executed via 'php -f <file>'.
  It will run some tests on the Omeka class,
  which it hopes to receive from config.php
  via the getOmeka method.
*/
$hasKeys = function($arr, $keys){
  foreach($keys as $k){
    if(!array_key_exists($k, $arr))
      return false;
  }
  return true;
};
/* Action below: */
echo "--------------------------------\n";
echo "Performing some tests for Omeka:\n";
echo "--------------------------------\n";
require_once 'config.php';
$omeka = Config::getOmeka();
//Testing Omeka.getSite():
echo "Omeka.getSite():\t";
$site = $omeka->getSite();
$siteKeys = array('omeka_url', 'omeka_version'
          , 'description', 'author', 'copyright');
$siteOk = $hasKeys($site, $siteKeys);
echo ($siteOk ? 'ok' : 'broken')."\n";
?>
