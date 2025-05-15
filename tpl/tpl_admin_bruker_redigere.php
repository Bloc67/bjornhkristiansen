<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// render the top
require_once(SITEDIR.'/tpl/tpl_admin_top.php');
echo '
    <section>
        <h2>Endring av bruker</h2>
        <p>Vennlist fyll ut dette skjemaet for å endre brukeren <strong>' , $tpl_params['bruker']['username'] , '</strong>.</p>';

if(!empty($id_err)) {
    echo '
        <p class="error">', $id_err , '</p>';
}
else {
    // render a form
    $target = SITEURL . '/admin/brukere/edit/'.$tpl_params['bruker']['id']; 
    $elements = array(
        'username' => array('type' => 'text', 'label' => 'Brukernavn', 'err' => $username_err , 'value' => $tpl_params['bruker']['username']),
        'level_user' => array('type' => 'select', 'label' => 'Nivå', 'err' => $level_err, 'value' => $tpl_params['bruker']['level_user'], 'options' => array('0' => 'Gjest','100' => 'Admin'),),
        'email_user' => array('type' => 'text', 'label' => 'Epost', 'err' => $email_err, 'value' => $tpl_params['bruker']['email_user']),
        'verified_user' => array('type' => 'select', 'label' => 'Godkjent', 'err' => $verified_err, 'value' => $tpl_params['bruker']['verified_user'], 'options' => array('0' => 'Ikke godkjent','1' => 'Godkjent')),
        'passord' => array('type' => 'text', 'label' => 'Nytt passord','err' => '', 'value' => '' ),
    );
    $submit_text = 'Lagre';
    $extra_text = '
        <input type="hidden" name="userid" value="'. $tpl_params['bruker']['id'] . '">';

    render_form($target, $elements, $submit_text, $extra_text); 
}
echo '
    </section>';

?>