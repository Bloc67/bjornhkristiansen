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

$title_err = $serial_err = $pctype_err = $status_err = $healthstate_err = $healthtext_err = $file_err = '';

// are we in edit mode?
if($param1 == 'edit' && is_numeric($param2) && $param2>0 && $param2<100000) {
    // fetch the user
    $sql = "SELECT * FROM machines WHERE id_machine = ". $param2; 
    $result = $mysqli->query($sql); 
    if($row = mysqli_fetch_assoc($result)) { 
        $tpl_params['machine'] = $row;
        $tpl_params['h1'] = 'Redigere "' . $row['title'] . '"';
        $tpl_params['title'] = ' - Admin';
        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            // Prepare an insert statement
            $sql = "UPDATE machines SET brand = ? , model = ? , type = ? , title = ? , serial = ? , pctype = ? , status = ? , healthstate = ? , healthtext = ? , memory = ? , price = ? , tagg = ? , os = ?  WHERE id_machine = ?";
            
            if($stmt = $mysqli->prepare($sql)){
                // Bind variables to the prepared statement as parameters
                $stmt->bind_param("ssssssiississi", $_POST["brand"], $_POST["model"], $_POST["type"], $_POST["title"], $_POST["serial"], $_POST["pctype"], $_POST["status"], $_POST["healthstate"], $_POST["healthtext"], $_POST["memory"], $_POST["price"], $_POST["tagg"], $_POST["os"], $param2);
                // Attempt to execute the prepared statement
                if(!$stmt->execute()){ $id_err = "Noe gikk galt. Vennligst prøv igjen senere."; }
                // Close statement
                $stmt->close();
                header("Location: ". SITEURL . '/admin/enheter/edit/'.$param2);
            }
            if (is_uploaded_file($_FILES['pdfimport']['tmp_name'])) {
                $sourcePath = $_FILES['pdfimport']['tmp_name'];
                $targetPath = SITEDIR."/ext/pdf/" . $_FILES['pdfimport']['name'];
                $targetUrl = SITEURL."/ext/pdf/" . $_FILES['pdfimport']['name'];
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    // update the pre-existing with new pdf
                    $sql = 'UPDATE machines set delpdf = "'. $_FILES['pdfimport']['name'] .'" WHERE id_machine = "' . $param2 . '"';
                    // Attempt to execute the prepared statement
                    $mysqli->query($sql); 
                }
            }
            if (is_uploaded_file($_FILES['jpgimport']['tmp_name'])) {
                $sourcePath = $_FILES['jpgimport']['tmp_name'];
                $targetPath = SITEDIR."/ext/jpg/" . $_FILES['jpgimport']['name'];
                $targetUrl = SITEURL."/ext/jpg/" . $_FILES['jpgimport']['name'];
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    // update the pre-existing with new pdf
                    $sql = 'UPDATE machines set picture = "'. $_FILES['jpgimport']['name'] .'" WHERE id_machine = "' . $param2 . '"';
                    // Attempt to execute the prepared statement
                    $mysqli->query($sql); 
                }
            }
        }
        // get some choices!!
        $tpl_params['taggchoices'] = array();
        $tpl_params['typechoices'] = array();
        $sql = "SELECT tagg,type FROM machines WHERE 1 ORDER BY id_machine DESC"; 
        $result = $mysqli->query($sql); 
        while($row = mysqli_fetch_assoc($result)){ 
            $tpl_params['taggchoices'][$row['tagg']] = $row['tagg'];
            $tpl_params['typechoices'][$row['type']] = $row['type'];
        } 

        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_admin_enhet_redigere.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    } 
    else {
        $tpl_params['h1'] = 'Feil';
        $tpl_params['title'] = ' - Admin';
        $tpl_params['error'] = 'Finner ikke enheten "' . $param2 . '". <a href="' . SITEURL . '/admin/enheter">Gå til oversikt over enheter</a>';
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_admin_error.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    }
}
// deleting?
elseif($param1 == 'slette' && is_numeric($param2) && $param2>0 && $param2<100000) {
    $sql = "DELETE FROM machines WHERE id_machine = ". $param2; 
    $result = $mysqli->query($sql); 
    header("Location: " . SITEURL . "/admin/enheter");
}
// are we in printout mode?
elseif($param1 == 'utskrift') {
        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            foreach($_POST as $i => $j) {
                $k = substr($i,0,5);
                $l = substr($i,5);
                if(is_numeric($l) && $k == 'print') {
                    if(!empty($_POST['check'.$l])) {
                        $sql = "UPDATE machines SET skrivut = '1' WHERE id_machine = '". $l ."'";
                        $mysqli->query($sql);
                    }
                    else {
                        $sql = "UPDATE machines SET skrivut = '0' WHERE id_machine = '". $l ."'";
                        $mysqli->query($sql);
                    }
                }
            }
            header("Location: ". SITEURL . '/admin/enheter/utskrift');
        }

        $tpl_params['machines'] = array();
        $tpl_params['status'] = $status;
        $tpl_params['healthstate'] = $healthstate;
        $tpl_params['print_items'] = 0;
    
        $sql = "SELECT * FROM machines WHERE 1 ORDER BY id_machine DESC"; 
        $result = $mysqli->query($sql); 
        while($row = mysqli_fetch_assoc($result)){ 
            $tpl_params['machines'][$row['id_machine']] = $row;
            $tpl_params['print_items'] = !empty($row['skrivut']) ? ($tpl_params['print_items']+1) : $tpl_params['print_items'];
        } 
    
        $tpl_params['h1'] = 'Enheter';
        $tpl_params['title'] = ' - Admin'; 
        $tpl_params['print'] = 1;
        $tpl_params['admin_top_content'] = '
        ' . $tpl_params['print_items'] . ' enheter er valgt for utskrift på etikett.';
        
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_home_admin.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
}
// salg
elseif($param1 == 'aktive') {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    $sql = "SELECT * FROM machines WHERE status=2 ORDER BY id_machine DESC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['machines'][$row['id_machine']] = $row;
        if(!isset($tpl_params['tagger'][$row['tagg']])) {
            $tpl_params['tagger'][$row['tagg']] = 0;
        }
        $tpl_params['tagger'][$row['tagg']]++;
    } 

    $tpl_params['h1'] = 'Aktive Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    <a href="' . SITEURL . '/admin/enheter/aktive"><b>Salg</b></a> |
    <a href="' . SITEURL . '/admin/enheter/lager">Lager</a> |
    <a href="' . SITEURL . '/admin/enheter/donert">Donert</a> |
    <a href="' . SITEURL . '/admin/enheter/ute">Arkiverte</a> |
    <a href="' . SITEURL . '/admin/enheter">Alle</a> |
    <a href="' . SITEURL . '/admin/enheter/utskrift">Utskriftmodus</a>
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");

}
// lager
elseif($param1 == 'lager') {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    $sql = "SELECT * FROM machines WHERE status IN(0,1,2) ORDER BY id_machine DESC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['machines'][$row['id_machine']] = $row;
        if(!isset($tpl_params['tagger'][$row['tagg']])) {
            $tpl_params['tagger'][$row['tagg']] = 0;
        }
        $tpl_params['tagger'][$row['tagg']]++;
    } 

    $tpl_params['h1'] = 'Aktive Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    <a href="' . SITEURL . '/admin/enheter/aktive">Salg</a> |
    <a href="' . SITEURL . '/admin/enheter/lager"><b>Lager</b></a> |
    <a href="' . SITEURL . '/admin/enheter/donert">Donert</a> |
    <a href="' . SITEURL . '/admin/enheter/ute">Arkiverte</a> |
    <a href="' . SITEURL . '/admin/enheter">Alle</a> |
    <a href="' . SITEURL . '/admin/enheter/utskrift">Utskriftmodus</a>
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");

}
// lager
elseif($param1 == 'donert') {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    $sql = "SELECT * FROM machines WHERE status IN(4,5) ORDER BY id_machine DESC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['machines'][$row['id_machine']] = $row;
        if(!isset($tpl_params['tagger'][$row['tagg']])) {
            $tpl_params['tagger'][$row['tagg']] = 0;
        }
        $tpl_params['tagger'][$row['tagg']]++;
    } 

    $tpl_params['h1'] = 'Donerte Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    <a href="' . SITEURL . '/admin/enheter/aktive">Salg</a> |
    <a href="' . SITEURL . '/admin/enheter/lager">Lager</a> |
    <a href="' . SITEURL . '/admin/enheter/donert"><b>Donert</b></a> |
    <a href="' . SITEURL . '/admin/enheter/ute">Arkiverte</a> |
    <a href="' . SITEURL . '/admin/enheter">Alle</a> |
    <a href="' . SITEURL . '/admin/enheter/utskrift">Utskriftmodus</a>
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");

}
// ute
elseif($param1 == 'ute') {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    $sql = "SELECT * FROM machines WHERE status IN(3,4,5) ORDER BY id_machine DESC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['machines'][$row['id_machine']] = $row;
        if(!isset($tpl_params['tagger'][$row['tagg']])) {
            $tpl_params['tagger'][$row['tagg']] = 0;
        }
        $tpl_params['tagger'][$row['tagg']]++;
    } 

    $tpl_params['h1'] = 'Arkiverte Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    <a href="' . SITEURL . '/admin/enheter/aktive">Salg</a> |
    <a href="' . SITEURL . '/admin/enheter/lager">Lager</a> |
    <a href="' . SITEURL . '/admin/enheter/donert">Donert</a> |
    <a href="' . SITEURL . '/admin/enheter/ute"><b>Arkiverte</b></a> |
    <a href="' . SITEURL . '/admin/enheter">Alle</a> |
    <a href="' . SITEURL . '/admin/enheter/utskrift">Utskriftmodus</a>
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");

}
// show the machines as a list
else {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    $sql = "SELECT * FROM machines WHERE 1 ORDER BY id_machine DESC"; 
    $result = $mysqli->query($sql); 
    while($row = mysqli_fetch_assoc($result)){ 
        $tpl_params['machines'][$row['id_machine']] = $row;
        if(!isset($tpl_params['tagger'][$row['tagg']])) {
            $tpl_params['tagger'][$row['tagg']] = 0;
        }
        $tpl_params['tagger'][$row['tagg']]++;
    } 

    $tpl_params['h1'] = 'Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    <a href="' . SITEURL . '/admin/enheter/aktive">Salg</a> |
    <a href="' . SITEURL . '/admin/enheter/lager">Lager</a> |
    <a href="' . SITEURL . '/admin/enheter/donert">Donert</a> |
    <a href="' . SITEURL . '/admin/enheter/ute">Arkiverte</a> |
    <a href="' . SITEURL . '/admin/enheter"><b>Alle</b></a> |
    <a href="' . SITEURL . '/admin/enheter/utskrift">Utskriftmodus</a>
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}

?>