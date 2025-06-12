<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

$tpl_params['h1'] = 'Enheter';
$tpl_params['title'] = ' - Admin';
$tpl_params['admin_top_content'] = '
    ';

require_once(SITEDIR . "/tpl/tpl_header_guest.php");
require_once(SITEDIR . "/tpl/tpl_side.php");
require_once(SITEDIR . "/tpl/tpl_footer.php");

?>