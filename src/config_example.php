<?php
/**
  Rename this file to 'config.php' and enter the necessary fields.
*/
require_once 'omeka.php';
/**
  The Config class to hopefully be useful for the rest of the site.
*/
class Config {
  /** Array of configuration values: */
  private static $config = array(
    'omeka' => array(
      'key'      => '' // Change to API Key
    , 'endpoint' => 'http://localhost/api' // Change to Omeka API endpoint
    )
  , 'database' => array(
      'server' => '' // Change to db server
    , 'user'   => '' // Change to db user
    , 'pass'   => '' // Change to db password
    , 'db'     => '' // Change to db name
    )
  );
  /** Attribute for memoization of Omeka instance. */
  private static $omeka = null;
  /**
    @return omeka Omeka
  */
  public static function getOmeka(){
    if(self::$omeka === null){
      self::$omeka = new Omeka(self::$config['omeka']);
    }
    return self::$omeka;
  }
  /** Attribute for memoization of the database connection. */
  private static $db = null;
  /**
    Returns the memoized database connection or creates a new one..
  */
  public static function getDB(){
    if(self::$db === null){
      $conf = self::$config['database'];
      $db = new mysqli(
        self::$config['server']
      , self::$config['user']
      , self::$config['pass']
      , self::$config['db']
      );
      $db->set_charset('utf8');
      self::$db = $db;
    }
    return self::$db;
  }
}
?>
