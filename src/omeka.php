<?php
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
    @param url String/URL
    @return JSON Array
    We're currently using file_get_contents here,
    but we can also switch to curl or something easily.
    Returns a php array with parsed JSON.
  */
  public function httpGet($url){
    $data = file_get_contents($url);
    return json_decode($data);
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
      $this->resources = $this->apiGet('resources');
    }
    return $this->resources;
  }
}
?>
