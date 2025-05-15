<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Feil</h2>
        <p class="error">' , $tpl_params['error'] , '</p>
    </section>';


?>