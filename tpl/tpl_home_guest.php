<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
    echo '
    <h2>Hei, <b>', htmlspecialchars($_SESSION["username"]) , '</b>.</h2>
    <p>
        <a href="' , SITEURL, '/logout">Logge ut</a>
    </p>
';
}
else {
    echo '
    <p class="floatright">
        <a href="' , SITEURL, '/login">Vil du logge inn?</a>
    </p>
';
}
require_once(SITEDIR.'/tpl/tpl_frontpage.php');


?>