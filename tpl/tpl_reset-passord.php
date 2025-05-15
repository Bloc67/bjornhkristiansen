<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
        <h2>Endre Passord</h2>
        <p>Vennnligst fyll ut dette skjemaet for å endre passord.</p>
        <form action="' , SITEURL, '/reset-password" method="post"> 
            <div class="form-group">
                <label>Nytt Passord</label>
                <input type="password" name="new_password" class="bruker', (!empty($new_password_err)) ? ' is-invalid' : '' , '" value="' , $new_password, '">
                <span class="invalid-feedback">', $new_password_err, '</span>
            </div>
            <div class="form-group">
                <label>Bekreft Passord</label>
                <input type="password" name="confirm_password" class="bruker', (!empty($confirm_password_err)) ? ' is-invalid' : '', '">
                <span class="invalid-feedback">', $confirm_password_err, '</span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <a class="btn btn-link ml-2" href="' , SITEURL , '">Avbryt</a>
            </div>
        </form>
';
?>