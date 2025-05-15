<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// Check if the user is already logged in, if yes then redirect him to welcome page
if(is_logged_in() && !empty($_SESSION["userlevel"]) && $_SESSION["userlevel"] == 100){
    // we are admin
    $is_admin = true;
}
else {
	header("location: ". SITEURL ."/login");
}
// are we in edit mode?
if($param1 == 'edit' && is_numeric($param2) && $param2>0 && $param2<100000) {
    // fetch the user
    $username_err = $level_err = $email_err = $verified_err = '';

    $tpl_params['bruker'] = array();
    $sql = "SELECT * FROM users WHERE id = ". $param2; 
    $result = $mysqli->query($sql); 
    if($row = mysqli_fetch_assoc($result)) { 
        $tpl_params['bruker'] = $row;
        $tpl_params['h1'] = 'Redigere "' . $row['username'] . '"';
        $tpl_params['title'] = ' - Admin';
        

        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            
            // Validate user id
            if((!empty($_POST["userid"]) && !is_numeric($_POST["userid"])) || empty($_POST["userid"])) {
                $id_err = 'Ukjent feil, skjema ble ikke lagret.';
            } 
            // Validate username
            if(empty(trim($_POST["username"]))){
                $username_err = "Vennligst legg inn et brukernavn.";
            } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
                $username_err = "Brukernavn kan bare ha bokstaver, tall og understrek i seg.";
            } 
            
            // Validate email
            if(!empty(trim($_POST["email_user"])) && !filter_var($_POST["email_user"], FILTER_VALIDATE_EMAIL)) {
                $email_err = 'Feil ved angitt epost - sjekk adressen.';
            } 
            // Validate level
            if(!is_numeric($_POST["level_user"]) && in_array($_POST["level_user"], array(0,1,50,100)) ) {
                $level_err = 'Feil verdi angitt for brukernivå.';
            } 
            // Validate level
            if(!is_numeric($_POST["verified_user"]) && in_array($_POST["level_user"], array(0,1)) ) {
                $verified_err = 'Feil verdi angitt for brukernivå.';
            } 
            
            // Check input errors before inserting in database
            if(empty($username_err) && empty($email_err) && empty($level_err) && empty($verified_err) && empty($id_err)){
                
                // Prepare an insert statement
                $sql = "UPDATE users SET username = ? , email_user = ? , level_user = ? , verified_user = ? WHERE id = ?";
                if($stmt = $mysqli->prepare($sql)){
                    // Bind variables to the prepared statement as parameters
                    $stmt->bind_param("ssiii", $_POST["username"], $_POST["email_user"], $_POST["level_user"], $_POST["verified_user"], $_POST["userid"]);
                    
                    // Attempt to execute the prepared statement
                    if(!$stmt->execute()){
                        $id_err = "Noe gikk galt. Vennligst prøv igjen senere.";
                    }

                    // Close statement
                    $stmt->close();
                    // password
                    if(!empty($_POST['passord'])) {
                        $sql = "UPDATE users SET password = ? WHERE id = ?";
                        if($stmt = $mysqli->prepare($sql)){
                            // Bind variables to the prepared statement as parameters
                            $param_password = password_hash($_POST['passord'], PASSWORD_DEFAULT);
                            $stmt->bind_param("si", $param_password, $_POST["userid"]);
                            // Attempt to execute the prepared statement
                            if(!$stmt->execute()){
                                $id_err = "Noe gikk galt. Vennligst prøv igjen senere.";
                            }
                            // Close statement
                            $stmt->close();
                        }
                    }
                }
                header("Location: ". SITEURL . '/admin/brukere');
            }
        }
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_admin_bruker_redigere.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    } 
    else {
        $tpl_params['h1'] = 'Feil';
        $tpl_params['title'] = ' - Admin';
        $tpl_params['error'] = 'Finner ikke bruker "' . $param2 . '". <a href="' . SITEURL . '/admin/brukere">Gå til oversikt over brukere</a>';
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_admin_error.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    }
}
// are we in edit mode?
elseif($param1 == 'ny') {
    $now = time();
    $sql = "INSERT INTO users (username, password, lastlogin) VALUES('bruker','', '" . $now . "')"; 
    $result = $mysqli->query($sql); 
    header("Location: " . SITEURL . "/admin/brukere");
}
// are we in delete mode?
elseif($param1 == 'slette' && is_numeric($param2) && $param2>0 && $param2<100000) {
    $now = time();
    $sql = "DELETE FROM users WHERE id = '" . $param2 . "'"; 
    $result = $mysqli->query($sql); 
    header("Location: " . SITEURL . "/admin/brukere");
}
// show the users as a list
else {
    // fetch the users
    $tpl_params['brukere'] = array();
    $sql = "SELECT * FROM users WHERE 1 ORDER BY username ASC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['brukere'][$row['id']] = $row;
    } 

    $tpl_params['h1'] = 'Brukere';
    $tpl_params['title'] = ' - Admin';
    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}

?>