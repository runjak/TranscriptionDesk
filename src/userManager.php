<?php
require_once 'auth/user.php';
require_once 'auth/lib/Opauth/Opauth.php';
/**
    The UserManager deals with user related issues.
*/
class UserManager {
    /**
        The configuration array that will be passed to Opauth.
    */
    private $config = null;
    /**
        Produce a new UserManager.
    */
    public function __construct($config){
        $this->config = $config;
    }
    /** Attribute for memoization of Opauth instance. */
    private $opauth = null;
    /***/
    public function getOpauth(){
        if($this->opauth === null){
            $this->opauth = new Opauth($this->config, false);
        }
        return $this->opauth;
    }
    /**
        @return $user User || null
        Verifies the current session.
        A User is returned iff the session is valid.
    */
    public function verify(){
        //FIXME IMPLEMENT
    }
    /**
        @param $user User
        Marks the user as logged in.
    */
    public function login($user){
        //FIXME IMPLEMENT
    }
    /**
        Marks the current user as logged out.
    */
    public function logout(){
    }
}
