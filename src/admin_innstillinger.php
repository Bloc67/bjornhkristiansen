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

// fetch the settings
$sql = "SELECT * FROM settings WHERE 1"; 
$result = $mysqli->query($sql); 
$tpl_params['settings'] = array();
if($row = mysqli_fetch_assoc($result)) { 
    $tpl_params['settings'] = $row;
    $tpl_params['h1'] = 'Innstillinger';
    $tpl_params['title'] = ' - Admin';
    // Processing form data when form is submitted
    if($_SERVER["REQUEST_METHOD"] == "POST"){
        // Prepare an insert statement
        $sql = "UPDATE settings SET timeout = ? , dbdate = ? WHERE id_setting = 1";
        if($stmt = $mysqli->prepare($sql)){
            // was it triggered?
            if(!empty($_POST["dbtrigger"])) {
                $dbdate = time();
                $fil=date("YmdHis");
                // we also want to backup the database now!
                include_once(SITEDIR . '/mysqldump.php');
                $dump = new Ifsnop\Mysqldump\Mysqldump('mysql:host='.DB_SERVER.';dbname='.DB_NAME, DB_USERNAME, DB_PASSWORD);
                $dump->start(SITEDIR.'/src/db_'.$fil.'.sql');

                $id_err = !empty($output) ? $output : '';
            }
            else {
                $dbdate = $row['dbdate'];
            }
           
            // Bind variables to the prepared statement as parameters
            $stmt->bind_param("ii", $_POST["timeout"], $dbdate);
            // Attempt to execute the prepared statement
            if(!$stmt->execute()){ $id_err = "Noe gikk galt. Vennligst prøv igjen senere."; }
            // Close statement
            $stmt->close();
            header("Location: ". SITEURL . '/admin/innstillinger/');
        }
    }
    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_admin_innstillinger.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
} 
else {
    $tpl_params['h1'] = 'Feil';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['error'] = 'Finner ingen innstillinger i databasen.';
    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_admin_error.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}

?>