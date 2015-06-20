<?php
//Stuff that Omeka builds upon:
require_once 'omeka/collection.php';
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
  /**
    Attribute for memoization of getSite().
  */
  private $site = null;
  /**
    Fetches basic Omeka site information.
    Expected fields are:
    {omeka_url, omeka_version, title, description, author, copyright}
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
    Fetches data from $endpoint
  */
  public function getResources(){
    if($this->resources === null){
      $this->resources = array();
      $res = $this->apiGet('resources');
      foreach($res as $name => $data){
        try{
          $r = new Resource($name, $data);
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
    @return resource Resource||null
    Tries to fetch the Resource for a given $name.
  */
  public function getResource($name){
    $res = $this->getResources();
    if(array_key_exists($name, $res)){
      return $res[$name];
    }
    return null;
  }
  /** Attribute for memoization for getCollections().  */
  private $collections = null;
  /**
    @return $collections [Collection]
    Returns the 'collections' Resource as instances of Collections
  */
  public function getCollections(){
    if($this->collections === null){
      $this->collections = array();
      $res = $this->getResource('collections');
      $cData = $this->httpGet($res->getUrl());
      foreach($cData as $col){
        array_push($this->collections, new Collection($col));
      }
    }
    return $this->collections;
  }
}
?>
