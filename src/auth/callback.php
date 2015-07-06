<?php
require_once '../config.php';
//Making Opauth available in below code.
$Opauth = Config::getUserManager()->getOpauth();
//Fetch auth response, based on transport configuration for callback:
$response = null;
switch($Opauth->env['callback_transport']){	
	case 'session':
		session_start();
		$response = $_SESSION['opauth'];
		unset($_SESSION['opauth']);
		break;
	case 'post':
		$response = unserialize(base64_decode( $_POST['opauth'] ));
		break;
	case 'get':
		$response = unserialize(base64_decode( $_GET['opauth'] ));
		break;
	default:
        require_once 'callback/unsupportedTransport.php';
}
//Checking if something went wrong:
if(array_key_exists('error', $response)){
    require_once 'callback/authenticationError.php';
}else{//Validation of auth response:
	if(  empty($response['auth']) || empty($response['timestamp'])
      || empty($response['signature']) || empty($response['auth']['provider'])
      || empty($response['auth']['uid'])){//key auth response components are missing.
        require_once 'callback/componentsMissing.php';
	}elseif(!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)){
        require_once 'callback/invalidResponse.php';//Uses $reason
	}else{//We're good to go.
		echo '<strong style="color: green;">OK: </strong>Auth response is validated.'."<br>\n";
        /**
            TODO:
            We've a correctly authenticated user.
            There are several things we need to do now:
            1: Check if the User exists in our database.
            2: If the User doesn't exist, create an entry.
            3: Start a valid session for the User.
        */
	}
}


/**
* Auth response dump
*/
echo "<pre>";
print_r($response);
echo "</pre>";
