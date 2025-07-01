<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

$now = time();

// Check if the user is already logged in, if yes then redirect him to welcome page
if(is_logged_in() && !empty($_SESSION["userlevel"]) && $_SESSION["userlevel"] == 100){
    // we are admin
    $is_admin = true;
    $now = time();
    $year = date("Y",$now);
    $maned = date("m",$now);
    // create a file
    $data = array(
        'id' => $now,
        'tittel' => 'Uten tittel',
        'tagg' => '',
        'status' => 0,
        'tekst' => '',
        'materialer' => '',
        'aar' => $year,
        'mnd' => $mnd,
        'added' => $now,
    );
    $filnavn = $year.$maned.'-'.$now;
    file_put_contents(SITEDIR.'/json/'.$filnavn.'.json', json_encode($data));
	header("location: ". SITEURL ."/enheter/edit/".$filnavn);
}
else {
	header("location: ". SITEURL ."/login");
}


?>