<?php
/**
  Rename this file to 'config.php' and enter the necessary fields.
*/
class Config {
  /** Array of configuration values: */
  private static $config = array(
    'omeka' => array(
      'label' => '' // Change to API Key label
    , 'key'   => '' // Change to API Key
    )
  , 'database' => array(
      'server' => '' // Change to db server
    , 'user'   => '' // Change to db user
    , 'pass'   => '' // Change to db password
    , 'db'     => '' // Change to db name
    )
  );
  /**
    Return the Omeka part of the config
  */
  public static function getOmekaConfig(){
    return $this->config['omeka'];
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
