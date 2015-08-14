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
echo "Omeka.getCollections():\n";
$cols = $omeka->getCollections();
foreach($cols as $c){
    $url = $c->getUrl();
    $pub = $c->isPublic() ? ' true' : 'false';
    $fea = $c->isFeatured() ? ' true' : 'false';
    $itC = $c->getItemCount();
    echo "$url\tpublic: $pub, featured: $fea, items: $itC\n";
}
echo "OmekaCollection.getItems():\n";
$col = current($cols);
$items = $col->getItems();
foreach($items as $i){
    $url = $i->getUrl();
    echo "Got an Item:\t$url\nDublin Core data is:\n";
    $dc = $i->getDublinCore();
    foreach($dc as $key => $value){
        echo "  $key\t=> $value\n";
    }
}
