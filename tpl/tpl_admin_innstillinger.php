<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Innstillinger</h2>';

if(!empty($id_err)) {
    echo '
        <p class="error">', $id_err , '</p>';
}
else {
    // render a form
    $target = SITEURL . '/admin/innstillinger/';
    $elements = array(
        'timeout' => array('type' => 'text', 'label' => 'Utloggingstid (sek)', 'value' => $tpl_params['settings']['timeout']),
        'dbdate' => array('type' => 'lock', 'func' => 'timestamp2date', 'label' => 'Siste DB backup', 'value' => $tpl_params['settings']['dbdate']),
        'dbtrigger' => array('type' => 'radio', 'label' => 'DB backup utført?' , 'options' => array('0' => 'Nei', '1' => 'Ja'), 'value' => 0),
    );
    $submit_text = 'Lagre'; 
    $extra_text = '';
    render_form($target, $elements, $submit_text, $extra_text); 
}
echo '
    </section>';

?>