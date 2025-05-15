<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

// are we in single mode?
if(is_numeric($subaction) && $subaction>0 && $subaction<100000) {
    // fetch the enhet
    $sql = "SELECT * FROM machines WHERE id_machine = '". $subaction."' LIMIT 1"; 
    $result = $mysqli->query($sql); 
    if($row = mysqli_fetch_assoc($result)) { 
        $tpl_params['machine'] = $row;
        $tpl_params['h1'] = $row['title'];
        $tpl_params['title'] = ' - '.$row['title']; 

        // get simialr machines
        $sql = "SELECT * FROM machines WHERE tagg = '". $row['tagg']."' ORDER BY id_machine"; 
        $result = $mysqli->query($sql); 
        $tpl_params['similar'] = array();
        while($row = mysqli_fetch_assoc($result)) { 
            $tpl_params['similar'][$row['id_machine']] = $row;
        }
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_enhet.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    } 
    else {
        $tpl_params['h1'] = 'Feil';
        $tpl_params['title'] = ' - Feil';
        $tpl_params['error'] = 'Finner ikke enheten "' . $subaction . '". <a href="' . SITEURL . '/">Gå til oversikt over enheter</a>';
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_admin_error.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    }
}


?>