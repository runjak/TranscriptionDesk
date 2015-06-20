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
//Testing Omeka.getResources():
echo "Omeka.getResources():\t";
$res = $omeka->getResources();
$resNames = array('site', 'resources', 'collections', 'items',
             'files', 'item_types', 'elements', 'element_sets',
             'users', 'tags', 'user_profiles_types', 'user_profiles',
             'user_profiles_multielements', 'user_profiles_multivalues',
             'simple_pages');
$resOk = $hasKeys($res, $resNames);
echo ($resOk ? 'ok' : 'broken')."\n";
$cols = $omeka->getResource('collections');
echo $cols->getUrl()."\n";
var_dump($omeka->httpGet($cols->getUrl()));
?>
