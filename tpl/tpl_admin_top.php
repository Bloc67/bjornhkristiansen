<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '    
    <h2>Hei, <strong>', htmlspecialchars($_SESSION["username"]) , '</strong>.<small class="brukerinfo">Sist logget inn ' , floor((time() - $_SESSION['lastlogin'])/60) , ' minutter siden</small></h2>';

$now = time();
if($now - $settings['dbdate'] > (3600*24*5) ) {
    echo '
    <p class="error"><a href="' , SITEURL, '/admin/innstillinger">Det er over 5 dager siden database-backup ble gjort!</a></p>';
}

echo '
    <nav>
        <ul class="sitelinks">
            <li' , $subaction == 'enheter' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/enheter/lager">Enheter</a></li>
            <li' , $subaction == 'import' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/import">Importere</a></li>';

if($_SESSION["userlevel"] == 100) {
    echo '
            <li' , $subaction == 'brukere' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/brukere">Brukere</a></li>
            <li' , $subaction == 'innstillinger' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/innstillinger">Innstillinger</a></li>';
}
echo '
            <li><a href="' , SITEURL, '/logout" onclick="return confirm(\'Er du sikker på du vil logge ut?\');">Logge ut</a></li>
        </ul>
    </nav>';

if(empty($subaction)) {
    require_once(SITEDIR.'/tpl/tpl_frontpage.php');
}

?>