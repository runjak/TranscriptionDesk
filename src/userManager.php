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
        @param $user User
        @return $secret String
        We can compute a users session secret as a hash of the following data:
        - userId
        - authenticationMethod
        - lastLogin
        - Opauth security_salt
        Since the session secret changes regularly
        it will be sufficient to use a regular cryptographic hash function
        like SHA* instead of scrypt, bcrypt or similarly sophisticated algorithms.
        See [1] for documentation of hash().
        [1]: https://secure.php.net/manual/en/function.hash.php
    */
    private function sessionSecret($user){
        $data  = $user->getUserId();
        $data .= $user->getAuthenticationMethod();
        $data .= $user->getLastLogin();
        $data .= $this->config['security_salt'];
        return hash('sha512', $data, false);
    }
    /**
        @return $user User || null
        Verifies the current session.
        A User is returned iff the session is valid.
    */
    public function verify(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        if(!array_key_exists('userId', $_SESSION)
         ||!array_key_exists('secret', $_SESSION)){
            return null;
        }
        $userId = $_SESSION['userId'];
        $secret = $_SESSION['secret'];
        $user = User::fromUserId($userId);
        if($user === null){ return null; }
        if($secret === $this->sessionSecret($user)){
            return $user;
        }
        return null;
    }
    /**
        @param $user User
        Marks the user as logged in.
    */
    public function login($user){
        /*
          First thing to do is update the users timestamp.
          This is necessary to integrate it into the session secret.
        */
        $user->updateLastLogin();
        $secret = $this->sessionSecret($user);
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        $_SESSION['userId'] = $user->getUserId();
        $_SESSION['secret'] = $secret;
    }
    /**
        Marks the current user as logged out.
    */
    public function logout(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        session_destroy();
    }
}
