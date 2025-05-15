<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Utskrift</h2>';

if(!empty($id_err)) {
    echo '
        <p class="error">', $id_err , '</p>';
}
echo '
    </section>';


?>