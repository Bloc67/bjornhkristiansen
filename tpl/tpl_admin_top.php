<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '    
    <h2>Hei, <strong>', htmlspecialchars($_SESSION["username"]) , '</strong>.<small class="brukerinfo">Sist logget inn ' , floor((time() - $_SESSION['lastlogin'])/60) , ' minutter siden</small></h2>';

$now = time();

echo '
    <nav>
        <ul class="sitelinks">
            <li' , $subaction == 'enheter' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/enheter">Malerier</a></li>
            <li' , $subaction == 'ny' ? ' class="active"' : '' , '><a href="' , SITEURL, '/admin/ny">Legg til</a></li>
            <li><a href="' , SITEURL, '/logout" onclick="return confirm(\'Er du sikker på du vil logge ut?\');">Logge ut</a></li>
        </ul>
    </nav>';

if(empty($subaction)) {
    require_once(SITEDIR.'/tpl/tpl_frontpage.php');
}

?>