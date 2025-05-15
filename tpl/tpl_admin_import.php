<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Importere</h2>
        <p>Vennlist last opp en XML fil + slette sertifkat.</p>';

if(!empty($id_err)) {
    echo '
        <p class="error">', $file_err , '</p>';
}
else {
    // render a form
    $target = SITEURL . '/admin/import';
    $elements = array(
        'xmlimport' => array('type' => 'file', 'label' => 'XML Fil', 'accept' => '.xml'),
        'txtimport' => array('type' => 'file', 'label' => 'Text Fil', 'accept' => '.txt'),
    );
    $submit_text = 'Last opp';
    $extra_text = '
        <input type="hidden" name="userid" value="1">';

    render_form($target, $elements, $submit_text, $extra_text); 
}
echo '
    </section>';

?>