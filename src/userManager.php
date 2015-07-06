<?php
require_once 'auth/user.php';
/**
    The UserManager deals with user related issues.
*/
class UserManager {
    /***/
    private $config = null;
    /***/
    public function __construct($config){
        $this->config = config;
        //FIXME IMPLEMENT
    }
    /**
        @param $userId Int
        @return $user User || null
    */
    public static function getUser($userId){
        //FIXME IMPLEMENT
    }
    /**
        @return $user User || null
        Verifies the current session.
        A User is returned iff the session is valid.
    */
    public static function verify(){
        //FIXME IMPLEMENT
    }
}
