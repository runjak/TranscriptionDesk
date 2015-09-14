<?php
/**
  Rename this file to 'config.php' and enter the necessary fields.
*/
chdir(dirname(__FILE__));
set_include_path(__DIR__);
require_once 'omeka.php';
require_once 'userManager.php';
restore_include_path();
/**
  The Config class to hopefully be useful for the rest of the site.
*/
class Config {
    /** Array of configuration values: */
    private static $config = array(
        'omeka' => array(
            'key'      => '' // Change to API Key
        ,   'endpoint' => 'http://localhost/api' // Change to Omeka API endpoint
        )
    ,   'database' => array( // These are the current docker defaults.
            'server' => '127.0.0.1' // Change to db server
        ,   'user'   => 'root' // Change to db user
        ,   'pass'   => '1234' // Change to db password
        ,   'db'     => 'TranscriptionDesk' // Change to db name
        )
    ,   'opauth' => array(
            'path' => '/auth/'
        ,   'callback_url' => '{path}callback.php'
            // Change the security_salt for each instance of the TranscriptionDesk.
        ,   'security_salt' => 'LDFmLudmillaIsMyFavouriteCowQCnpBzzpTBWA5vJidQKDx8pMJbmw28R1C4m'
        ,   'Strategy' => array(
                // Define strategies and their respective configs here
                'Facebook' => array(
                    'app_id' => '',
                    'app_secret' => ''
                )
            ,   'GitHub' => array(
                    'client_id' => '',
                    'client_secret' => ''
                )
            ,   'Twitter' => array(
                    'key' => '',
                    'secret' => ''
                )
            )
        )
        /*
            See [1] and database/completionVotesProjection.php for votes part.
            [1]: https://github.com/runjak/TranscriptionDesk/issues/12
        */
    ,   'votes' => array(
            'lead' => 5
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
            $config = self::$config['database'];
            $db = new mysqli(
                $config['server']
            ,   $config['user']
            ,   $config['pass']
            ,   $config['db']
            );
            $db->set_charset('utf8');
            self::$db = $db;
        }
        return self::$db;
    }
    /** Attribute for memoization of the UserManager. */
    private static $userManager = null;
    /***/
    public static function getUserManager(){
        if(self::$userManager === null){
            self::$userManager = new UserManager(self::$config['opauth']);
        }
        return self::$userManager;
    }
    /***/
    public static function getVotesConfig(){
        return self::$config['votes'];
    }
}
