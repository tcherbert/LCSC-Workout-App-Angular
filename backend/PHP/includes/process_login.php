<?php
header('Access-Control-Allow-Origin: *'); 
//Handle AJAX Response to Login page -- Test





//Double check to make sure no one is accessing this page directly.. Insecure method but works for now.
if(!isset($_REQUEST['email'])){
	die();
}




	
include_once 'db_connect.php';
include_once 'functions.php';

 
 
if (isset($_REQUEST['email'], $_REQUEST['password'])) {
    $email = $_REQUEST['email'];
    $password = $_REQUEST['password']; // The hashed password.

	
/*
	$response = array();
$response['message'] = "Hello Nurse!";

$encoded = json_encode($response);
echo $encoded;
die;
*/


    if (login($email, $password, $mysqli) == true) {
        // Login success 
        
		$response = array();
		$response['message'] = 1;
		
		$encoded = json_encode($response);
		echo $encoded;
	        
        //header('Location: ../index.php');
        exit();
    } else {
        // Login failed 
        //header('Location: ../index.php?error=1');
        
    	$response = array();
		$response['message'] = 0;
		
		$encoded = json_encode($response);
		echo $encoded;
    }
} else {
    // The correct POST variables were not sent to this page. 
    echo 'Invalid Request';
}

?>