<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
    <h2>Logge inn</h2>
    <p>Vennligst fyll ut dine innloggingsopplysninger</p>';

if(!empty($login_err)){
    echo '
    <div class="alert alert-danger">' . $login_err . '</div>';
}        

// render a form
$target = SITEURL . '/login';
$elements = array(
    'username' => array('type' => 'text', 'label' => 'Brukernavn', 'err' => $username_err , 'value' => $username),
    'password' => array('type' => 'password', 'label' => 'Passord', 'err' => $password_err),
);
$submit_text = 'Login';
$extra_text = '
        <p>Har ikke konto? <a href="'. SITEURL . '/register">Registrer deg</a>.</p>';

render_form($target, $elements, $submit_text, $extra_text); 


?>