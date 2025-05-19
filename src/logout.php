<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session.
session_destroy();
 
// Redirect to login page
header("location: ". SITEURL ."/login");
exit;

?>