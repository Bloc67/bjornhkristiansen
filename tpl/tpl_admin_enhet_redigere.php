<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
';

if(!empty($id_err)) {
    echo '
        <p class="error">', $id_err , '</p>';
}
else {
    $tpl_params['machine']['picture'] = !empty($tpl_params['machine']['picture']) ? $tpl_params['machine']['picture'] : '';
    $tpl_params['machine']['aar'] = !empty($tpl_params['machine']['aar']) ? $tpl_params['machine']['aar'] : '2025';
    $tpl_params['machine']['mnd'] = !empty($tpl_params['machine']['mnd']) ? $tpl_params['machine']['mnd'] : '1';
    // render a form
    $target = SITEURL . '/admin/enheter/edit/'.$tpl_params['machine']['path'];
    $elements = array(
        'tittel' => array('type' => 'text', 'label' => 'Tittel', 'err' => $title_err , 'value' => $tpl_params['machine']['tittel']),
        'status' => array('type' => 'select', 'label' => 'Status', 'err' => $status_err , 'value' => $tpl_params['machine']['status'], 
            'options' => $status,
        ),
        'jpgimport' => array('type' => 'file', 'label' => 'Bilde', 'err' => $file_err, 'accept' => '.jpg','var' => (!empty($tpl_params['machine']['jpg']) ? $tpl_params['machine']['jpg'] : ''), 'is_img' => 1),
        'added' => array('type' => 'lock', 'label' => 'Lagt til', 'err' => $title_err , 'value' => $tpl_params['machine']['added']),
        'divde2' => array('type' => 'divide'),
        'tekst' => array('type' => 'textarea', 'label' => 'Bakgrunnstekst', 'err' => $title_err , 'value' => $tpl_params['machine']['tekst']),
        'materialer' => array('type' => 'textarea', 'label' => 'Materialer', 'err' => $title_err , 'value' => $tpl_params['machine']['materialer']),
        'tagg' => array('type' => 'text', 'label' => 'Tagg', 'err' => $title_err , 'value' => $tpl_params['machine']['tagg'],'choices' => $tpl_params['taggchoices']),
        'aar' => array('type' => 'select', 'label' => 'Årstall', 'err' => $status_err , 'value' => $tpl_params['machine']['aar'], 
            'options' => $aar,'fifty' => true,
        ),
        'mnd' => array('type' => 'select', 'label' => 'Måned', 'err' => $status_err , 'value' => $tpl_params['machine']['mnd'], 
            'options' => $mnd, 'fifty' => true,
        ),
    );
    $submit_text = 'Lagre';
    $extra_text = '
        <input type="hidden" name="machineid" value="'. $tpl_params['machine']['path'] . '">
        ';

    render_form($target, $elements, $submit_text, $extra_text); 
}
echo '
    </section>';

?>