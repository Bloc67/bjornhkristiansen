<?php


$path_parts = pathinfo(__FILE__);
$uri = 'http://localhost/bjornhkristiansen';

error_reporting(E_ALL);

// define this a s main php file
define('APG',1);
define('SITEURL',$uri);
define('SITEDIR',$path_parts['dirname']);

$getid = isset($_GET['id']) ? $_GET['id'] : ''; 
$url_data = explode("/", $getid);
$url_params = array();
foreach($url_data as $a) {
    $url_params[] = preg_replace("/[^a-zA-Z0-9]+/", "", $a);
}
$action = empty($url_params[0]) ? '' : $url_params[0];
$subaction = empty($url_params[1]) ? '' : $url_params[1];
$param1 = empty($url_params[2]) ? '' : $url_params[2];
$param2 = empty($url_params[3]) ? '' : $url_params[3];
$param3 = empty($url_params[4]) ? '' : $url_params[4];
$param4 = empty($url_params[5]) ? '' : $url_params[5];

// template parameters
$tpl_params = array(
    'site' => 'BHKristiansen',
    'title' => '',
); 

// Initialize the session
session_start();

// Include config file
require_once SITEDIR . "/config.php";
require_once SITEDIR . "/funcs.php";

$settings = array();

if(file_exists(SITEDIR.'/src/'.$action.'.php')) {
    // give control to source file
    require_once(SITEDIR.'/src/'.$action.'.php');
    exit;
}
else {
    // Check if the user is already logged in 
    if(is_logged_in()) {
        // show frontpage for members
        $tpl_params['h1'] = 'Medlems-område';
        $tpl_params['title'] = ' - Oversikt';
        // admin? Or levels later on
        if(!empty($_SESSION["userlevel"])) {
            if($_SESSION["userlevel"] == 100) {
                header("Location: ".SITEURL."/admin/enheter");
                exit;
            }
        }
        else {
            $is_guest = true;
        }        
    }    
    else {
        // just guest frontpage
        $tpl_params['machinemenu'] = array();
        $tpl_params['filters'] = array();
        $tpl_params['sort'] = array('memory' => 'Minne','disk0' => 'Diskstørrelse');
        $sort = 'id_machine';
        $sort_dir = 'DESC';
        $filter = '';

        // check if we got filters or sorting
        if($action=='filter' && !empty($subaction)) {
            //filtering out $subaction
            $what = filter_letter($subaction, true);
            $filter = ' AND tagg ="' . $subaction . '"';
            $tpl_params['filter_active'] = $subaction;
            $tpl_params['active_path'] = '/filter/'.$subaction;
            // do we have a sort?
            if($param1 == 'sort' && !empty($param2)) {
                // filter it
                $what = filter_letter($param2, true);
                $sort = $param2;
                $tpl_params['sort_active'] = $param2;
                $tpl_params['active_path'] = '/filter/'.$subaction.'/sort/'.$param2;
            }
        }
        // check if we got filters or sorting
        elseif($action=='sort' && !empty($subaction)) {
            //filtering out $subaction
            $what = filter_letter($subaction, true);
            $sort = $subaction;
            $sort_dir = 'ASC';
            $tpl_params['sort_active'] = $subaction;
            $tpl_params['active_path'] = '/sort/'.$subaction;
        }
        // get the records
        

        $sql = "SELECT * FROM machines WHERE status = '2' AND (healthstate = '1' OR healthstate = '2')" . $filter . " ORDER BY " . $sort . " ".$sort_dir; 
        $result = $mysqli->query($sql); 
        while($row = mysqli_fetch_assoc($result)){ 
            $tpl_params['machines'][$row['id_machine']] = $row;
        } 
        $sql = "SELECT * FROM machines WHERE status = '2' AND (healthstate = '1' OR healthstate = '2') ORDER BY id_machine DESC"; 
        $result = $mysqli->query($sql); 
        $tpl_params['filters_labels'] = array();
        while($row = mysqli_fetch_assoc($result)){ 
            $tpl_params['machinemenu'][$row['id_machine']] = $row;
            if(!isset($tpl_params['filters'][$row['tagg']])) {
                $tpl_params['filters'][$row['tagg']] = 1;
            }
            else {
                $tpl_params['filters'][$row['tagg']]++;
            }
            $tpl_params['filters_labels'][$row['tagg']] = $row['type'];
        } 
        $tpl_params['h1'] = 'Velkommen';
        require_once(SITEDIR . "/tpl/tpl_header.php");
        require_once(SITEDIR . "/tpl/tpl_home_guest.php");
        require_once(SITEDIR . "/tpl/tpl_footer.php");
    }

    // Close connection
    $mysqli->close();

    exit;
}

?>
