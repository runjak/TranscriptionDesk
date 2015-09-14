<?php
require_once('config.php');
require_once('auth/user.php');
/**
    The CompletionVotesProjection projects onto a single vote in one of the {scan,aoi,transcription}Completeness tables.
    For each of these votes it will hand out various informations such as:
    - All users that voted
    - If the vote is completed
    - The vote outcome
    For all completion votes we abide by the following strategy:
    - If an admin casts a vote it is handled as an immidiate decision.
    - Each vote is an up or down vote, so essentially a triple of (urn, isGood, userId),
      where isGood is a boolean.
      We weigh up votes vs down votes, and once one kind is in the lead by a certain distance
      this is viewed as a decision.
      This lead is to be configured in config.php
*/
class CompletionVotesProjection {
    /**
        Returns the necessary lead required for up or down votes to win a vote.
    */
    public static function requiredLead(){
        return Config::getVotesConfig()['lead'];
    }
    /** $table is a String from {scan,aoi,transcription}Completeness. */
    private $table = null;
    /** $urn is a String describing the urn column in $table. */
    private $urn = null;
    /** $breakout is used for memoization and to hold aggregated vote info. */
    private $breakout = null;
    /**
        @return $breakout [
                'isGood' => Boolean || null - null for incomplete, true for good, false for bad.
            ,   'score' => Int - positives for good, negatives for bad, 0 for neutral.
            ,   'votes' => Int - number of votes cast on this $projection.
            ]
        Returns the vote $breakout for a projection.
        The breakout describes the current state of a vote.
    */
    public function getBreakout(){
        if($this->breakout === null){
            //Building $breakout:
            $this->breakout = array(
                'isGood' => null // True if vote decided good, false if vote decided bad.
            ,   'score' => 0 // Positives are good.
            ,   'votes' => 0 // Null per default.
            );
            //Gathering $userChoiceMap:
            $table = $this->table;
            $q = "SELECT userId, isGood FROM $table WHERE urn = ?";
            $stmt = Config::getDB()->prepare($q);
            $stmt->bind_param('s', $this->urn);
            $stmt->execute();
            $userChoiceMap = array();//[userId => isGood]
            $stmt->bind_result($userId, $isGood);
            while($stmt->fetch()){
                $userChoiceMap[$userId] = $isGood;
            }
            $stmt->close();
            //Setting votes:
            $this->breakout['votes'] = count($userChoiceMap);
            //Checking for admin decision:
            $q = "SELECT U.userId FROM users as U, $table as C WHERE U.isAdmin = 1 AND U.userId = C.userId AND C.urn = ?";
            $stmt = Config::getDB()->prepare($q);
            $stmt->bind_param('s', $this->urn);
            $stmt->execute();
            $stmt->bind_result($adminId);
            if($stmt->fetch()){
                $this->breakout['isGood'] = ($userChoiceMap[$adminId] != 0);
            }
            $stmt->close();
            //Computing score:
            foreach($userChoiceMap as $userId => $isGood){
                if($isGood){
                    $this->breakout['score']++;
                }else{
                    $this->breakout['score']--;
                }
            }
            //Setting isGood via score, iff possible:
            if($this->breakout['isGood'] === null){
                $abs = abs($this->breakout['score']);
                if($abs >= CompletionVotesProjection::requiredLead()){
                    $this->breakout['isGood'] = ($this->breakout['score'] > 0);
                }
            }
        }
        return $this->breakout;
    }
    /**
        @param $user User || Int
        @param $isGood Boolean
        @return $voted $this || Exception
        Casts a vote for the given $projection.
        $user may either be a User or a userId from the users table.
        $isGood must be a boolean.
        If the vote was successful, the object returns $this for possible chaining.
        In that case memoization for $breakout is cleared so that new results would be fetched.
        If the a problem arises, an Exception is returned.
    */
    public function vote($user, $isGood){
        //Fail helper:
        $fail = function($reason){
            return new Exception("Problem in CompletionVotesProjection.vote(): $reason");
        };
        //Sanity checks:
        if(!is_bool($isGood)){
            return $fail('$isGood must be a boolean!');
        }
        //Checks for $user:
        if($user instanceof User){
            $user = $user->getUserId();
        }
        if(!is_numeric($user)){
            return $fail('Invalid value for $user.');
        }
        //Making sure vote is not completed:
        $breakout = $this->getBreakout();
        if($breakout['isGood'] !== null){
            return $fail('Vote is already completed!');
        }
        //Inserting vote:
        $table = $this->table;
        $q = "INSERT INTO $table (urn, userId, isGood) VALUES (?,?,?)";
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('sii', $this->urn, $user, $isGood);
        $stmt->execute();
        $stmt->close();
        //Cleaning memoization and return:
        $this->breakout = null;
        return $this;
    }
    /**
        @param $urn String
        @return $projection CompletionVotesProjection || Exception
        Tries to create a CompletionVotesProjection for a Scan.
    */
    public static function projectScan($urn){
        //Fail helper:
        $fail = function($reason) use ($urn){
            return new Exception("CompletionVotesProjection::projectScan($urn) ran into a problem: $reason");
        };
        //Checking $urn:
        $q = 'SELECT COUNT(*) FROM scans WHERE urn = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $urn);
        if(!self::countIsOne($stmt)){
            return $fail('$urn not in database.');
        }
        //Building $projection:
        $projection = new CompletionVotesProjection();
        $projection->table = 'scanCompleteness';
        $projection->urn = $urn;
        //Done:
        return $projection;
    }
    /**
        @param $urn String
        @return $projection CompletionVotesProjection || Exception
        Tries to create a CompletionVotesProjection for an Area of Interest.
    */
    public static function projectAOI($urn){
        //Fail helper:
        $fail = function($reason) use ($urn){
            return new Exception("CompletionVotesProjection::projectAOI($urn) ran into a problem: $reason");
        };
        //Checking $urn:
        $q = 'SELECT COUNT(*) FROM areasOfInterest WHERE urn = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $urn);
        if(!self::countIsOne($stmt)){
            return $fail('$urn not in database.');
        }
        //Building $projection:
        $projection = new CompletionVotesProjection();
        $projection->table = 'aoiCompleteness';
        $projection->urn = $urn;
        //Done:
        return $projection;
    }
    /**
        @param $urn String
        @return $projection CompletionVotesProjection || Exception
        Tries to create a CompletionVotesProjection for a Transcription.
    */
    public static function projectTranscription($urn){
        //Fail helper:
        $fail = function($reason) use ($urn){
            return new Exception("CompletionVotesProjection::projectTranscription($urn) ran into a problem: $reason");
        };
        //Checking $urn:
        $q = 'SELECT COUNT(*) FROM transcriptions WHERE urn = ?';
        $stmt = Config::getDB()->prepare($q);
        $stmt->bind_param('s', $urn);
        if(!self::countIsOne($stmt)){
            return $fail('$urn not in database.');
        }
        //Building $projection:
        $projection = new CompletionVotesProjection();
        $projection->table = 'transcriptionCompleteness';
        $projection->urn = $urn;
        //Done:
        return $projection;
    }
    /**
        @param $stmt mysqli_stmt
        @return $isOne Boolean
        Helper method for self::project*().
        Executes and closes $stmt.
    */
    public static function countIsOne($stmt){
        $stmt->execute();
        $count = 0;
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();
        return ($count === 1);
    }
}
