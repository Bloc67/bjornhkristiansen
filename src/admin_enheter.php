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

$taggfiler = hentjsonfiler('tagg');
$datofiler = hentjsonfiler('dato');
$statusfiler = hentjsonfiler('status');
$reboot = false;

// are we in edit mode?
if($param1 == 'edit' && !empty($param2)) {
    // fetch the user
    if(file_exists(SITEDIR.'/json/'.$param2.'.json')) {
        $row = json_decode(file_get_contents(SITEDIR.'/json/'.$param2.'.json'),true);
        $tpl_params['machine'] = $row;
        $tpl_params['machine']['path'] = $param2;

        $tpl_params['h1'] = 'Redigere "' . $row['tittel'] . '"';
        $tpl_params['title'] = ' - Admin';
        
        // Processing form data when form is submitted
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            foreach(array('tittel','status','tagg','aar','mnd','dag','tekst','materialer','added') as $e) {
                $tpl_params['machine'][$e] = isset($_POST[$e]) ? $_POST[$e] : $tpl_params['machine'][$e]; 
            }
            if (is_uploaded_file($_FILES['jpgimport']['tmp_name'])) {
                $sourcePath = $_FILES['jpgimport']['tmp_name'];
                $targetPath = SITEDIR."/ext/jpg/" . $_FILES['jpgimport']['name'];
                $targetUrl = SITEURL."/ext/jpg/" . $_FILES['jpgimport']['name'];
                if (move_uploaded_file($sourcePath, $targetPath)) {
                    // update the pre-existing with new pdf
                    $tpl_params['machine']['jpg'] = $_FILES['jpgimport']['name'];
                    // trigger dates too
                    // check
                    if(strtoupper(substr($tpl_params['machine']['jpg'],0,6)) == 'IMG_20') {
                        $tpl_params['machine']['aar'] = substr($tpl_params['machine']['jpg'],4,4);
                        $tpl_params['machine']['mnd'] = substr($tpl_params['machine']['jpg'],8,2);
                        $tpl_params['machine']['dag'] = substr($tpl_params['machine']['jpg'],10,2);
                    }
                }
            }
            // calculate new filename based on date
            $newdate = $tpl_params['machine']['aar'].$tpl_params['machine']['mnd'].$tpl_params['machine']['dag'].$tpl_params['machine']['added'];
            if($newdate != $param2) {
                unlink(SITEDIR.'/json/'.$param2.'.json');
                $param2 = $newdate;
                $reboot = true;
            }
            // write the new file
            file_put_contents(SITEDIR.'/json/'.$param2.'.json', json_encode($tpl_params['machine']));

            // update tags
            // get the tags for this
            $taggfiler = oppdaterejsonfiler('tagg',$taggfiler);
            // update dato
            $datofiler = oppdaterejsonfiler('dato',$datofiler,'aar');
            // update status
            $statusfiler = oppdaterejsonfiler('status',$statusfiler);
            if($reboot) {
                header("Location: " . SITEURL . "/admin/enheter/edit/".$param2);
            }
        }
        // get some choices!!
        $tpl_params['taggchoices'] = array();
        $tpl_params['typechoices'] = array();
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
    unlink(SITEDIR.'/json/'.$param2.'.json');
    header("Location: " . SITEURL . "/admin/enheter");
}
// show the machines as a list
else {
    $tpl_params['machines'] = array();
    $tpl_params['tagger'] = array();
    $tpl_params['status'] = $status;
    $tpl_params['healthstate'] = $healthstate;

    foreach (new DirectoryIterator(SITEDIR.'/json/') as $tfile) 
    {
      if ($tfile->isDot()) continue;
      $u = $tfile->getFilename();
      if (substr($u,strlen($u)-5,5) != '.json') {
        continue;
      }
      $uu = substr($u,0, strlen($u)-5);

      // a file or a dir
      if($tfile->isFile()) {
        $row = json_decode(file_get_contents(SITEDIR.'/json/'.$tfile),true);
        $tpl_params['machines'][$uu] = $row;
      }
    }    
    
    krsort($tpl_params['machines']);

    $tpl_params['h1'] = 'Enheter';
    $tpl_params['title'] = ' - Admin';
    $tpl_params['admin_top_content'] = '
    ';

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_home_admin.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}

?>