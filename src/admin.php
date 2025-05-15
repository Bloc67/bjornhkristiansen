<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(is_logged_in() && !empty($_SESSION["userlevel"]) && $_SESSION["userlevel"] == 100){
    // we are admin
    $is_admin = true;
    if($_SESSION["userlevel"] > 99) {
        $is_superadmin = true;
    }
}
else {
	header("location: ". SITEURL ."/login");
}

// what section
if(file_exists(SITEDIR.'/src/admin_'.$subaction.'.php')) {
    // give control to source file
    require_once(SITEDIR.'/src/admin_'.$subaction.'.php');

    // Close connection
    $mysqli->close();
    exit;
}
else {

    // Close connection
    $mysqli->close();
    exit;
}

?>