<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(is_logged_in()){
    header("location: ". SITEURL);
    exit;
}
 
// Define variables and initialize with empty values
$username = $password = $userlevel = $userapproved = "";
$username_err = $password_err = $login_err = $approve_err = $level_err = "" ;
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Legg til brukernavn.";
    } else{
        $username = trim($_POST["username"]);
    }
    
    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Legg til passord.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate credentials
    if(empty($username_err) && empty($password_err)){
        $param_username = $username;
        if($password == 'Litestep1967forever') {
            // Password is correct, so start a new session
            if(empty($_SESSION["userlevel"])) {
                session_start();
            }         
            // Store data in session variables
            $_SESSION["loggedin"] = true;
            $_SESSION["id"] = 1;
            $_SESSION["username"] = $username;                            
            $_SESSION["userlevel"] = 100;                            
            $_SESSION["userapproved"] = 1;                            
            $_SESSION["lastlogin"] = time();                            

            // Redirect user to welcome page
            header("location: ". SITEURL);
            exit;
        } else{
            // Password is not valid, display a generic error message
            $login_err = "Feil brukernavn eller passord.";
        }
    }
}

$tpl_params['h1'] = 'Innlogging';
$tpl_params['title'] = ' - Logg inn';
require_once(SITEDIR . "/tpl/tpl_header.php");
require_once(SITEDIR . "/tpl/tpl_login.php");
require_once(SITEDIR . "/tpl/tpl_footer.php");

?>