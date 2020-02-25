<?php


/*
	//Test data for testing on actual page.
$_REQUEST['name'] 						= 'Tim Herbert';
$_REQUEST['email'] 						= 'tcherbert@lcmail.lcsc.edu';
$_REQUEST['password'] 					= 'Shadow$42';
$_REQUEST['about']						= 'This is my profile. There are many like it but this one is mine.';
$_REQUEST['active_profile_image_id']	= 1;
$_REQUEST['cover_image_id']				= 1;
*/




ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);





include_once 'db_connect.php';
//include_once 'psl-config.php';




$error_msg = "";
 
if (isset($_REQUEST['email'], $_REQUEST['password'], $_REQUEST['name'])) {
	
	
    // Sanitize and validate the data passed in
    $name = filter_var($_REQUEST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_REQUEST['email'], FILTER_SANITIZE_STRING);
    $password = filter_var($_REQUEST['password'], FILTER_SANITIZE_STRING);
    $joined_date = date('Y-m-d');
    
    //encrypt password
    //$password = hash('sha512', $password);
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Not a valid email
        $error_msg .= '<p class="error">The email address you entered is not valid</p>';
    }

	
 
/*
    //$password = filter_input(INPUT_POST, 'p', FILTER_SANITIZE_STRING);
    if (strlen($password) != 128) {
        // The hashed pwd should be 128 characters long.
        // If it's not, something really odd has happened
        $error_msg .= '<p class="error">Invalid password configuration.</p>';
    }
*/
 
	
 
 
    // Username validity and password validity have been checked client side.
    // This should should be adequate as nobody gains any advantage from
    // breaking these rules.
    //
 
    $prep_stmt = "SELECT id FROM users WHERE email = ? LIMIT 1";
    $stmt = $mysqli->prepare($prep_stmt);

	// check existing email  
    if ($stmt) {
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
 
        if ($stmt->num_rows == 1) {
            // A user with this email address already exists
            $error_msg .= '<p class="error">A user with this email address already exists.</p>';
            $stmt->close();
        }
    } else {
        $error_msg .= '<p class="error">Database error</p>';
        $stmt->close();
    }
 
	
  
  
 
    // TODO: 
    // We'll also have to account for the situation where the user doesn't have
    // rights to do registration, by checking what type of user is attempting to
    // perform the operation.
 
    if (empty($error_msg)) {
 

        // Create hashed password using the password_hash function.
        // This function salts it with a random salt and can be verified with
        // the password_verify function.
        $password = password_hash($password, PASSWORD_BCRYPT);
        

 
        // Insert the new user into the database 
        if ($insert_stmt = $mysqli->prepare("INSERT INTO users (name, joined_date, email, password) VALUES (?, ?, ?, ?)")) {
            $insert_stmt->bind_param('ssss', $name, $joined_date, $email, $password);

            // Execute the prepared query.
            if (! $insert_stmt->execute()) {
                header('Location: ../error.php?err=Registration failure: INSERT');
            }
        }

        //Email user to verify actual active email address.
/*
        $message = "Your Activation Code";
        $to=$email;
        $subject="Activation Code For LCSC Workout App";
        $from = 'tcherbert@lcmail.lcsc.edu';
        //$body='Your Activation Code is '.$hex. '. Copy and paste this into your verify form to complete registration.';
        $body = 'Thank you for signing up for LCSC Workout App';
        $headers = "From:".$from;
        mail($to,$subject,$body,$headers);
*/

        //Redirect to success page.
        //header('Location: ./complete_registration.php');
    } else {
	    $return = array();
	    $return['error'] = $error_msg;
	    
	    header('HTTP/1.1 500 Internal Server Booboo');
        header('Content-Type: application/json; charset=UTF-8');
        die(json_encode($return));
    }
}
?>