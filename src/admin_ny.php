<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(is_logged_in() && !empty($_SESSION["userlevel"]) && $_SESSION["userlevel"] == 100){
    // we are admin
    $is_admin = true;
    $now = time();
    // create a file
    $data = array(
        'id' => $now,
        'tittel' => '-no name-',
    );
    file_put_contents(SITEDIR.'/json/'.$now.'.json', json_encode($data));
	header("location: ". SITEURL ."/enheter/edit/".$now);
}
else {
	header("location: ". SITEURL ."/login");
}


?>