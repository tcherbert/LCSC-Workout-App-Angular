<?php

 
function sec_session_start() {

    session_start(); // Start the PHP session 
    $session_name = uniqid();  // Set a custom session name
    ///var_dump($session_name);
    $secure = true;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure,$httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    
    //Not sure why this line is needed. It makes the sessions not work basically. Research when you have the time.
    //session_regenerate_id(true);    // regenerated the session, delete the old one. 

}

function login($email, $password, $mysqli) {
	

    // Using prepared statements means that SQL injection is not possible.
    
    $stmt = $mysqli->prepare("SELECT id, name, password 
        FROM users
        WHERE email = ?
        LIMIT 1");
        
       // var_dump(json_encode($mysqli));
/*
       echo '<pre>'; 
	   var_dump($stmt);
	   echo '</pre>';
        die;
*/ 
    if ($stmt) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
			/*
		//Commented out for now. Will try to implement later.
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked

                $bytes = random_bytes(20);
                $email_lock = bin2hex($bytes);

                $mysqli -> query("
                            UPDATE members
                            SET email_lock = '$email_lock'
                            WHERE email = '$email'
                    ");

                //Email user to inform user of locked email and send code to unlock
                $message = "Account Locked";
                $to=$email;
                $subject="Account Locked";
                $from = 'admin@knighteen.com';
                $body='Your account has been locked due to security reasons. 
                        http://timherbert.net/game/recover.php and input this code: 
                        '.$email_lock.'
                        to unlock your account';
                $headers = "From:".$from;
                mail($to,$subject,$body,$headers);

                return false;
                exit();
            } else {
			*/
                // Check if the password in the database matches
                // the password the user submitted. We are using
                // the password_verify function to avoid timing attacks.
                
                
/*
$response = array();
$response['message'] = "Hello Nurse!";

$encoded = json_encode($response);
echo $encoded;
die;
*/                



				$pw_result = password_verify($password, $db_password);
                if ($pw_result) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);

                    //var_dump($_SESSION);
                    // Login successful.
                    return true;
                    exit();
                } else {

echo 'sad panda!';
                    // Password is not correct
                    // We record this attempt in the database
/*
                    $now = time();
                    // 2hour expiration time for bad login attempts
                    $expires = time() + 2 * 60 * 60;
                    $mysqli->query("INSERT INTO login_attempts(user_id, time, expires)
                                    VALUES ('$user_id', '$now', $expires)");
*/
                    return false;
                }
            //} // end of bruteforce else
        } else {
            // No user exists.
            return false;
        }
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM members 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
                // If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                if (hash_equals($login_check, $login_string) ){
                    // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}


function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();
    //Delete expired attempts from database
    $mysqli -> query('
                DELETE FROM login_attempts
                WHERE expires < '.$now.'
        ');
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM login_attempts 
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 2 failed logins 
        if ($stmt->num_rows > 2) {
            return true;
        } else {
            return false;
        }
    }
}

// Secure registration function
function register_verify($email, $password, $email_code, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password, email_code 
        FROM members
       WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password, $db_email_code);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
            } else {
                // Check if the password in the database matches
                // the password the user submitted. We are using
                // the password_verify function to avoid timing attacks.
                if (password_verify($password, $db_password)) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $db_password . $user_browser);
                    
                    if($db_email_code == $email_code){
                        $email_update = 'UPDATE members
                                         SET email_verify = 1
                                         WHERE id = '.$user_id.'
                                        ';
                        $mysqli -> query($email_update);
                        // Login successful.
                        return true;
                    } else {
                        return false;
                    }
                    
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
            }
        } else {
            // No user exists.
            return false;
        }
    }
}

	
?>