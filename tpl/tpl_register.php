<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <h2>Registrere ny bruker</h2>
    <p>Vennlist fyll ut dette skjemaet for å lage ny bruker.</p>';

// render a form
$target = SITEURL . '/register';
$elements = array(
    'username' => array('type' => 'text', 'label' => 'Brukernavn', 'err' => $username_err , 'value' => $username),
    'password' => array('type' => 'password', 'label' => 'Passord', 'err' => $password_err, 'value' => $password),
    'confirm_password' => array('type' => 'password', 'label' => 'Passord', 'err' => $confirm_password_err, 'value' => $confirm_password),
);
$submit_text = 'Registrere';
$extra_text = '
        <p>Har konto allerede? <a href="' . SITEURL . '/login">Logg inn</a></p>';

render_form($target, $elements, $submit_text, $extra_text); 

?>