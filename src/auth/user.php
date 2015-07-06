<?php
/**
    The User class represents a single entry
    from the users table.
*/
class User {
    private $row = null;
    /**
        @return $isEmpty Bool
        Predicate to check if $row is null.
    */
    private function isEmpty(){
        return $this->row === null;
    }
    /**
        @param $q mysqli_stmt
        Fetches the row that corresponds to the query $q.
        See [1] for documentation of mysqli_stmt.
        [1]: https://secure.php.net/manual/en/class.mysqli-stmt.php
    */
    private function __construct($stmt){
        $stmt->execute();
        $stmt->bind_result($userId, $authenticationMethod, $displayName, $avatarUrl, $lastLogin, $tasksCompleted);
        if($stmt->fetch()){
            $this->row = array(
                'userId' => $userId,
                'authenticationMethod' => $authenticationMethod,
                'displayName' => $displayName,
                'avatarUrl' => $avatarUrl,
                'lastLogin' => $lastLogin,
                'tasksCompleted' => $tasksCompleted
            );
            self::$userIdMap[$userId] = $this;
        }
        $stmt->close();
    }
    /**
        $userIdMap is used for memoization by fromUserId.
        $userIdMap :: userId -> User
    */
    private static $userIdMap = array();
    /**
        @param $userId SQL Serial
        @return $user User || null
        Tries to fetch a User by its userId field.
    */
    public static function fromUserId($userId){
        //Handling memoization:
        if(array_key_exists($userId, self::$userIdMap)){
            return self::$userIdMap[$userId];
        }
        //Preparing query to fetch User data:
        $stmt = Config::getDB()->prepare('SELECT * FROM users WHERE userId = ?');
        $stmt->bind_param('i', $userId);
        //Fetching and checking User:
        $user = new User($stmt);
        if($user->isEmpty()){
            return null;
        }
        return $user;
    }
    /**
        @param $method String
        @return $user User || null
        Tries to fetch a User by its authenticationMethod field.
    */
    public static function fromAuthenticationMethod($method){
        //Preparing query to fetch User data:
        $stmt = Config::getDB()->prepare('SELECT * FROM users WHERE authenticationMethod = ?');
        $stmt->bind_param('s', $method);
        //Fetching and checking User:
        $user = new User($stmt);
        if($user->isEmpty()){
            return null;
        }
        return $user;
    }
    /**
        @param $authenticationMethod String
        @param $displayName String
        @param $avatarUrl String || null
        @return $user User || null
        Tries to create a new User in the database,
        using the given parameters.
    */
    public static function registerNew($authenticationMethod, $displayName, $avatarUrl = null){
        //Sanitizing inputs:
        if(empty($authenticationMethod)){
            return null;
        }
        if(empty($displayName)){
            $displayName = 'Unnamed user';
        }
        if($avatarUrl === ''){
            $avatarUrl = null;
        }
        //Creating new entry for User:
        $db = Config::getDB();
        $stmt = $db->prepare('INSERT INTO users(authenticationMethod, displayName, avatarUrl) VALUES (?,?,?)');
        $stmt->bind_param('sss', $authenticationMethod, $displayName, $avatarUrl);
        $stmt->execute();
        $userId = $stmt->insert_id;
        $stmt->close();
        //Returning newly created User:
        return self::fromUserId($userId);
    }
    /**
        @return $userId Int
    */
    public function getUserId(){
        return $this->row['userId'];
    }
    /**
        @return $authenticationMethod String
    */
    public function getAuthenticationMethod(){
        return $this->row['authenticationMethod'];
    }
    /**
        @return $displayName String
    */
    public function getDisplayName(){
        return $this->row['displayName'];
    }
    /**
        @return $avatarUrl String || null
    */
    public function getAvatarUrl(){
        return $this->row['avatarUrl'];
    }
    /**
        @return $lastLogin Timestamp
    */
    public function getLastLogin(){
        return $this->row['lastLogin'];
    }
    /**
        @return $tasksCompleted Int
    */
    public function getTasksCompleted(){
        return $this->row['tasksCompleted'];
    }
    /**
        @param $add Integer
        @return $completed Integer
        Adds $add to the tasksCompleted count for a user,
        and returns the resulting number of tasksCompleted.
    */
    public function addTasksCompleted($add){
        $completed = $this->getTasksCompleted() + $add;
        $stmt = Config::getDB()->prepare('UPDATE users SET tasksCompleted = ? WHERE userId = ?');
        $stmt->bind_param('ii', $completed, $this->getUserId());
        $stmt->execute();
        $stmt->close();
        $this->row['tasksCompleted'] = $completed;
        return $completed;
    }
    /**
        @param $name String
        @return $this User
        Changes the displayName of a User to the given one.
        Returns self for chaining.
    */
    public function setDisplayName($name){
        $stmt = Config::getDB()->prepare('UPDATE users SET displayName = ? WHERE userId = ?');
        $stmt->bind_param('si', $name, $this->getUserId());
        $stmt->execute();
        $stmt->close();
        $this->row['displayName'] = $name;
        return $this;
    }
    /**
        @return $lastLogin Timestamp
        Updates the lastLogin field of a User to the current time.
    */
    public function updateLastLogin(){
        $userId = $this->getUserId();
        $db = Config::getDB();
        //Updating timestamp:
        $stmt = $db->prepare('UPDATE users SET lastLogin=CURRENT_TIMESTAMP WHERE userId = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $stmt->close();
        //Fetching timestamp:
        $stmt = $db->prepare('SELECT lastLogin FROM users WHERE userId = ?');
        $stmt->bind_param('i', $userId);
        $stmt->execute;
        $stmt->bind_result($lastLogin);
        $stmt->fetch();
        $this->row['lastLogin'] = $lastLogin;
        $stmt->close();
        return $lastLogin;
    }
}
