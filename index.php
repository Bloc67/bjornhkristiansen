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
        $year = date("Y",time());
        if($action=='tagg' && file_exists(SITEDIR.'/tagg/'.$subaction.'.json')){
            $json = json_decode(file_get_contents(SITEDIR.'/tagg/'.$subaction.'.json'),true);
            foreach ($json as $name => $is_active) {
                if (empty($is_active)) {
                  continue;
                }
                // a file or a dir
                if(file_exists(SITEDIR.'/json/'.$name.'.json')) {
                    $row = json_decode(file_get_contents(SITEDIR.'/json/'.$name.'.json'),true);
                    $row['aar'] = !empty($row['aar']) ? $row['aar'] : '0';
                    $row['jpg'] = !empty($row['jpg']) ? $row['jpg'] : '';
                    if(!empty($row['jpg']))
                        $tpl_params['machines'][$row['id']] = $row;

                    $tpl_params['machines'][$row['id']]['tagg'] = explode(",",$tpl_params['machines'][$row['id']]['tagg']);   
                }
            }    
            krsort($tpl_params['machines']);
            $tpl_params['h1'] = 'Bilder tagget med '. $subaction;
            $tpl_params['title'] = 'Bilder tagget med "'. $subaction.'"';
            $tpl_params['admin_top_content'] = '
            ';
            require_once(SITEDIR . "/tpl/tpl_header_guest.php");
            require_once(SITEDIR . "/tpl/tpl_home_guest.php");
            require_once(SITEDIR . "/tpl/tpl_footer.php");

        }
        else {
            $tpl_params['machines'] = array();
            // just guest frontpage
            foreach (new DirectoryIterator(SITEDIR.'/json/') as $tfile) {
                if ($tfile->isDot()) continue;
                $u = $tfile->getFilename();
                if (substr($u,strlen($u)-5,5) != '.json') {
                    continue;
                }
                // a file or a dir
                if($tfile->isFile()) {
                    $row = json_decode(file_get_contents(SITEDIR.'/json/'.$tfile),true);
                    $row['aar'] = !empty($row['aar']) ? $row['aar'] : '0';
                    $row['jpg'] = !empty($row['jpg']) ? $row['jpg'] : '';
                    if(!empty($row['status'])) {
                        $tid = intval(substr($u,strlen($u)-5)); 
                        if(!empty($row['jpg']))
                            $tpl_params['machines'][$tid] = $row;
                        else
                            $tpl_params['machines'][$row['id']] = array();
            
                        $tpl_params['machines'][$row['id']]['tagg'] = !empty($tpl_params['machines'][$row['id']]['tagg']) ? explode(",",$tpl_params['machines'][$row['id']]['tagg']) : array();   
                    }
                }
            }    
            krsort($tpl_params['machines']);
            $tpl_params['h1'] = 'Enheter';
            $tpl_params['title'] = 'Siste fra Galleriet';
            $tpl_params['admin_top_content'] = '
            ';

            require_once(SITEDIR . "/tpl/tpl_header_guest.php");
            require_once(SITEDIR . "/tpl/tpl_home_guest.php");
            require_once(SITEDIR . "/tpl/tpl_footer.php");

        }
    }
    exit;
}

?>
