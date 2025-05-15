<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

if(is_logged_in() && !empty($_SESSION["userlevel"]) && $_SESSION["userlevel"] == 100){
    // render the top
    require_once(SITEDIR.'/tpl/tpl_admin_top.php');
    
    echo '
    <section>';
    require_once(SITEDIR.'/tpl/tpl_admin_'.$subaction.'.php');
    echo '
    </section>';
}
else {
    require_once(SITEDIR.'/tpl/tpl_frontpage.php');
}

?>