<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Endring av enhet</h2>
        <p>Vennlist fyll ut dette skjemaet for å endre enheten <strong>' , $tpl_params['machine']['title'] , '</strong>.</p>';

if(!empty($id_err)) {
    echo '
        <p class="error">', $id_err , '</p>';
}
else {
    // render a form
    $target = SITEURL . '/admin/enheter/edit/'.$tpl_params['machine']['id_machine'];
    $elements = array(
        'title' => array('type' => 'text', 'label' => 'Tittel', 'err' => $title_err , 'value' => $tpl_params['machine']['title']),
        'tagg' => array('type' => 'text', 'label' => 'Tagg', 'err' => $title_err , 'value' => $tpl_params['machine']['tagg'],'choices' => $tpl_params['taggchoices']),
        'serial' => array('type' => 'text', 'label' => 'Serienummer', 'err' => $serial_err , 'value' => $tpl_params['machine']['serial']),
        'pctype' => array('type' => 'text', 'label' => 'Type', 'err' => $pctype_err , 'value' => $tpl_params['machine']['pctype']),
        'status' => array('type' => 'select', 'label' => 'Status', 'err' => $status_err , 'value' => $tpl_params['machine']['status'], 
            'options' => $status,
        ),
        'os' => array('type' => 'select', 'label' => 'OS', 'err' => $status_err , 'value' => $tpl_params['machine']['os'], 
            'options' => $os,
        ),
        'healthstate' => array('type' => 'select', 'label' => 'Tilstand', 'err' => $healthstate_err , 'value' => $tpl_params['machine']['healthstate'], 
            'options' => $healthstate,
        ),
        'healthtext' => array('type' => 'textarea', 'label' => 'Detaljer', 'err' => $healthtext_err , 'value' => $tpl_params['machine']['healthtext']),
        'memory' => array('type' => 'text', 'label' => 'Minne', 'value' => $tpl_params['machine']['memory']),
        'price' => array('type' => 'text', 'label' => 'Pris', 'value' => $tpl_params['machine']['price']),
        'pdfimport' => array('type' => 'file', 'label' => 'Sertifikat', 'err' => $file_err, 'accept' => '.pdf','var' => $tpl_params['machine']['delpdf']),
        'jpgimport' => array('type' => 'file', 'label' => 'Bilde', 'err' => $file_err, 'accept' => '.jpg','var' => $tpl_params['machine']['picture'], 'is_img' => 1),

        'divide' => array('type' => 'divide'), 

        'cpu' => array('type' => 'lock', 'label' => 'CPU', 'value' => $tpl_params['machine']['cpu']),
        'brand' => array('type' => 'text', 'label' => 'Produsent', 'value' => $tpl_params['machine']['brand']),
        'type' => array('type' => 'text', 'label' => 'Type', 'value' => $tpl_params['machine']['type'], 'choices' => $tpl_params['typechoices']),
        'model' => array('type' => 'text', 'label' => 'Modell', 'value' => $tpl_params['machine']['model']),
        'totaldisks' => array('type' => 'lock', 'label' => 'Antall disker', 'value' => $tpl_params['machine']['totaldisks']), 
        'deldate' => array('type' => 'lock', 'label' => 'Slettet', 'value' => $tpl_params['machine']['deldate']),
        'delmethod' => array('type' => 'lock', 'label' => 'Metode', 'value' => $tpl_params['machine']['delmethod']),
        'delhandler' => array('type' => 'lock', 'label' => 'Behandler', 'value' => $tpl_params['machine']['delhandler']),
        'delhandlerfirm' => array('type' => 'lock', 'label' => 'Behandler Firma', 'value' => $tpl_params['machine']['delhandlerfirm']),
        'delapprover' => array('type' => 'lock', 'label' => 'Godkjenner', 'value' => $tpl_params['machine']['delapprover']),
        'delapproverfirm' => array('type' => 'lock', 'label' => 'Godkjenner Firma', 'value' => (!empty($tpl_params['machine']['delapproverfirm']) ? $tpl_params['machine']['delapproverfirm'] : '')),
        'customer' => array('type' => 'lock', 'label' => 'Kunde', 'value' => $tpl_params['machine']['customer']),
        'customeraddr' => array('type' => 'lock', 'label' => 'Kunde', 'value' => $tpl_params['machine']['customeraddr']),
        'disk0' => array('type' => 'lock', 'label' => 'Disk 1', 'value' => $tpl_params['machine']['disk0']),
        'disk0model' => array('type' => 'lock', 'label' => 'Disk 1 Modell', 'value' => $tpl_params['machine']['disk0model']),
        'disk0type' => array('type' => 'lock', 'label' => 'Disk 1 type', 'value' => $tpl_params['machine']['disk0type']),
    );
    foreach(array(1,2,3) as $q) {
        if($tpl_params['machine']['totaldisks']>$q) {
            $elements['disk'.$q] = array('type' => 'lock', 'label' => 'Disk '.($q+1) , 'value' => $tpl_params['machine']['disk'.$q]);        
            $elements['disk'.$q.'model'] = array('type' => 'lock', 'label' => 'Disk '.($q+1).' Modell', 'value' => $tpl_params['machine']['disk'.$q.'model']);        
            $elements['disk'.$q.'type'] = array('type' => 'lock', 'label' => 'Disk '. ($q+1).' Type', 'value' => $tpl_params['machine']['disk'.$q.'type']);        
        }
    }
    $submit_text = 'Lagre';
    $extra_text = '
        <input type="hidden" name="machineid" value="'. $tpl_params['machine']['id_machine'] . '">
        ';

    render_form($target, $elements, $submit_text, $extra_text); 
}
echo '
    </section>';

?>