<?php
//Stuff that Omeka builds upon:
require_once 'omeka/collection.php';
require_once 'omeka/elementSet.php';
require_once 'omeka/resource.php';
/**
    This file expects to be included by config.php.
    The Omeka class will be instantiated with a configuration array
    that provides the following fields:
    key      - the API key to use with omeka.
    endpoint - the API endpoint, usually this is something like http://$server/api
*/
class Omeka {
    /**
        Storage for configuration values:
    */
    private $config = array();
    /**
        Constructor; may throw exception on insufficient configuration.
    */
    public function __construct($config){
        //Validating $config:
        $wanted = array('key','endpoint');
        foreach($wanted as $key){
            if(!array_key_exists($key, $config)){
                throw new Exception('Malformed configuration for Omeka instance.');
            }
        }
        //Setting $config:
        $this->config = $config;
    }
    /**
        $dbUsage is a boolean flag.
        If it is true, Omeka and classes building on top of it
        shall try to use the database where possible instead of relying on the Omeka API.
        If it is false, Omeka and classes building on top of it
        shall always rely on the API and ignore the database where ever possible.
    */
    private $dbUsage = true;
    /**
      @param $dbUsage boolean
      @return $dbUsage boolean
      Set wether Omeka shall rely on the database instead of the API where possible.
    */
    public function setDbUsage($dbUsage){
        //Making sure $dbUsage is a boolean:
        $this->dbUsage = ($dbUsage == true);
        return $this->dbUsage;
    }
    /**
        @return $dbUsage boolean
        Returns true iff Omeka shall rely on the database where possible.
    */
    public function getDbUsage(){
        return $this->dbUsage;
    }
    /**
        @return key String
        Holds the API key to use with Omeka.
    */
    protected function getKey(){
        return $this->config['key'];
    }
    /**
      @return endpoint String
      Holds the API endpoint to use with Omeka.
    */
    public function getEndpoint(){
        return $this->config['endpoint'];
    }
    /**
        @param $url String/URL
        @param $params [String => String||'']
        @return JSON Array
        We're currently using file_get_contents here,
        but we can also switch to curl or something easily.
        The $params parameter is interpreted as wanted get parameters,
        and will be added to the given $url.
        Returns a php array with parsed JSON.
    */
    public function httpGet($url, $params = array()){
        //Making sure we add the API key to the request:
        $params['key'] = $this->getKey();
        //Building the $query to contain the query parameters from $params:
        $query = array();
        foreach($params as $k => $v){
            if($v === ''){
                array_push($query, urlencode($k));
            }else{
                array_push($query, urlencode($k).'='.urlencode($v));
            }
        }
        //Composing $url:
        $glue = preg_match('/\\?/', $url) ? '&' : '?';
        $get = $url.$glue.implode('&', $query);
        //Fetching the target:
        $data = file_get_contents($get);
        return json_decode($data, true);
    }
    /**
        @param resource String/URL suffix
        @return same as httpGet
        Perform a get request to the desired resource.
    */
    public function apiGet($resource){
        $e = $this->getEndpoint();
        return $this->httpGet("$e/$resource");
    }
    /** Attribute for memoization of getSite(). */
    private $site = null;
    /**
        @return $site array('omeka_url' => …,'omeka_version' => …,'title' => …,'description' => …,'author' => …,'copyright' => …)
        Fetches basic Omeka site information.
        Ignores $dbUsage by definition.
    */
    public function getSite(){
        if($this->site === null){
            $this->site = $this->apiGet('site');
        }
        return $this->site;
    }
    /** Attribute for memoization of getResources(). */
    private $resources = null;
    /**
        @return $resources array(…)
        Fetches data from $endpoint
        Ignores $dbUsage by definition.
    */
    public function getResources(){
        if($this->resources === null){
            $this->resources = array();
            $res = $this->apiGet('resources');
            foreach($res as $name => $data){
                try{
                    $r = new OmekaResource($data);
                    $this->resources[$name] = $r;
                }catch(Exception $e){
                    error_log("Could not create Resource for '$name': ".json_encode($data));
                }
            }
        }
        return $this->resources;
    }
    /**
        @param $name String
        @return resource OmekaResource||null
        Tries to fetch the OmekaResource for a given $name.
        Ignores $dbUsage by definition.
    */
    public function getResource($name){
        $res = $this->getResources();
        if(array_key_exists($name, $res)){
            return $res[$name];
        }
        return null;
    }
    /** Attribute for memoization for getCollections(). */
    private $collections = null;
    /**
        @return $collections [OmekaCollection]
        Returns the 'collections' Resource as instances of OmekaCollections.
        Ignores $dbUsage because OmekaCollection are not stored in the database.
    */
    public function getCollections(){
        if($this->collections === null){
            $this->collections = array();
            $res = $this->getResource('collections');
            $cData = $this->httpGet($res->getUrl());
            foreach($cData as $col){
                $c = new OmekaCollection($col);
                $this->collections[$c->getId()] = $c;
            }
        }
        return $this->collections;
    }
    /**
        @param $id id field of a collection
        @return $col OmekaCollection||null
        This method relies on Omeka->getCollections().
    */
    public function getCollection($id){
        if($this->collections === null){
            $this->getCollections();
        }
        if(array_key_exists($id, $this->collections)){
            return $this->collections[$id];
        }
        return null;
    }
    /** Attribute for memoization for getItems(). */
    private $items = null;
    /**
        @return $items [OmekaItem]
        Returns the 'items' Resource as instances of OmekaItems.
        This method obeys $dbUsage.
    */
    public function getItems(){
        if($this->items === null){
            if($this->getDbUsage()){
                $this->items = OmekaItem::getItemsFromDb();
            }else{
                $this->items = array();
                $res = $this->getResource('items');
                $is = $this->httpGet($res->getUrl());
                foreach($is as $i){
                    $item = new OmekaItem($i);
                    $this->items[$item->getUrn()] = $item;
                }
            }
        }
        return $this->items;
    }
    /**
        @param $urn String urn of an OmekaItem
        @return $item OmekaItem||null
        This method obeys $dbUsage.
        In case of $dbUsage getItem doesn't trigger all items to be fetched.
    */
    public function getItem($urn){
        if($this->items === null){
            if($this->getDbUsage()){
                return OmekaItem::getItemFromDb($urn);
            }else{
                $this->getItems();
            }
        }
        if(array_key_exists($urn, $this->items)){
            return $this->items[$urn];
        }
        return null;
    }
    /**
        Attribute for memoization for getElementSets().
        OmekaElementSet->getName() -> OmekaElementSet
    */
    private $elementSets = null;
    /**
        @return elementSets [OmekaElementSet]
        This method ignored $dbUsage
    */
    public function getElementSets(){
        if($this->elementSets === null){
            $this->elementSets = array();
            $res = $this->getResource('element_sets');
            $eSets = $this->httpGet($res->getUrl());
            foreach($eSets as $eData){
                $eSet = new OmekaElementSet($eData);
                $this->elementSets[$eSet->getName()] = $eSet;
            }
        }
        return $this->elementSets;
    }
    /**
        @return dcSet OmekaElementSet
        Returns the 'Dublin Core' OmekaElementSet, if possible.
        This method builds on top of Omeka->getElementSets().
    */
    public function getDublinCore(){
        if($this->elementSets === null){
            $this->getElementSets();
        }
        return $this->elementSets['Dublin Core'];
    }
}
