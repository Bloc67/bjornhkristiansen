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
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (is_array($_FILES['xmlimport']) && $_FILES['xmlimport']['error'] == 0) {
        if (is_uploaded_file($_FILES['xmlimport']['tmp_name'])) {
            $sourcePath = $_FILES['xmlimport']['tmp_name'];
            $targetPath = SITEDIR."/ext/xml/" . $_FILES['xmlimport']['name'];
            $targetUrl = SITEURL."/ext/xml/" . $_FILES['xmlimport']['name'];
            if (move_uploaded_file($sourcePath, $targetPath)) {
                $xml = file_get_contents($targetPath);
                // mapping
                $map = array();
                $map['machine'] = array(
                    'title' => '',
                    'serial' => '',
                    'pctype' => '',
                    'memory' => '',
                    'deldate' => '',
                    'delmethod' => '',
                    'delhandler' => '',
                    'delhandlerfirm' => '',
                    'delapprover' => '',
                    'delapprovefirm' => '',
                ); 
                $map['machine']['serial'] = xml_rip($xml,'name="System Serial" type="string">','</Component>');
                $map['machine']['pctype'] = xml_rip($xml,'name="Chassis Type" type="string">','</Component>');
                $map['machine']['memory'] = xml_rip($xml,'name="RAM" type="string">','</Component>');
                if(empty($map['machine']['memory'])) {
                    $map['machine']['memory'] = xml_rip($xml,'name="Memory (RAM)" type="string">','</Component>');
                }
                $map['machine']['deldate'] = xml_rip($xml,'name="Report Date" type="string">','</Component>');
                $map['machine']['deltimestamp'] = convert2timestamp($map['machine']['deldate']);


                $map['machine']['delmethod'] = xml_rip($xml,'name="Selected Method" type="string">','</Component>');
                if(empty($map['machine']['delmethod'])) {
                    $map['machine']['delmethod'] = xml_rip($xml,'name="ErasureMethod" type="string">','</Component>');
                }
                 
                $map['machine']['delhandler'] = xml_rip($xml,'name="TechnicianName" type="string">','</Component>');
                $map['machine']['delhandlerfirm'] = xml_rip($xml,'name="Organization" type="string">','</Component>');
                $map['machine']['delapprover'] = xml_rip($xml,'name="ValidatorName" type="string">','</Component>');
                $map['machine']['delapprovefirm'] = xml_rip($xml,'name="Organization" type="string">','</Component>');
                $map['machine']['cpu'] = xml_rip($xml,'name="Description" type="string">','</Component>', '<Components name="Processor">');
                $map['machine']['model'] = xml_rip($xml,'name="Model" type="string">','</Component>','name="Hardware Information"');
                $map['machine']['model'] = xml_rip($xml,'name="Model" type="string">','</Component>','name="Hardware Information"');
                $temp = 

                $map['machine']['customer'] = xml_rip($xml,'name="Customer Name" type="string">','</Component>');
                $map['machine']['customeraddr'] = xml_rip($xml,'name="Customer Address" type="string">','</Component>');

                $map['machine']['totaldisks'] = xml_rip($xml,'name="Total Disks" type="string">','</Component>');
                $map['machine']['disk0'] = xml_rip($xml,'name="Size" type="string">','</Component>','name="Disk0">','name="Erasure Results">');
                $map['machine']['disk0model'] = xml_rip($xml,'name="Model" type="string">','</Component>','name="Disk0">','name="Erasure Results">');
                $map['machine']['disk0type'] = xml_rip($xml,'name="MediaType" type="string">','</Component>','name="Disk0">','name="Erasure Results">');

                $map['machine']['disk1'] = $map['machine']['disk2'] = $map['machine']['disk3'] = '';
                $map['machine']['disk1model'] = $map['machine']['disk2model'] = $map['machine']['disk3model'] = '';
                $map['machine']['disk1type'] = $map['machine']['disk2type'] = $map['machine']['disk3type'] = '';

                if($map['machine']['totaldisks']>1) {
                    $map['machine']['disk1'] = xml_rip($xml,'name="Size" type="string">','</Component>','<Components name="Disk1">','name="Erasure Results">');
                    $map['machine']['disk1model'] = xml_rip($xml,'name="Model" type="string">','</Component>','<Components name="Disk1">','name="Erasure Results">');
                    $map['machine']['disk1type'] = xml_rip($xml,'name="MediaType" type="string">','</Component>','<Components name="Disk1">','name="Erasure Results">');
                }    
                if($map['machine']['totaldisks']>2) {
                    $map['machine']['disk2'] = xml_rip($xml,'name="Size" type="string">','</Component>','<Components name="Disk2">','name="Erasure Results">');
                    $map['machine']['disk2model'] = xml_rip($xml,'name="Model" type="string">','</Component>','<Components name="Disk2">','name="Erasure Results">');
                    $map['machine']['disk2type'] = xml_rip($xml,'name="MediaType" type="string">','</Component>','<Components name="Disk2">','name="Erasure Results">');
                }    
                if($map['machine']['totaldisks']>3) {
                    $map['machine']['disk3'] = xml_rip($xml,'name="Size" type="string">','</Component>','<Components name="Disk3">','name="Erasure Results">');
                    $map['machine']['disk3model'] = xml_rip($xml,'name="Model" type="string">','</Component>','<Components name="Disk3">','name="Erasure Results">');
                    $map['machine']['disk3type'] = xml_rip($xml,'name="MediaType" type="string">','</Component>','<Components name="Disk3">','name="Erasure Results">');
                }    
                
                $title = xml_rip($xml,'name="SKU Number" type="string">','</Component>');
                if(!empty($title)) {
                    $t = explode(" ", $title);
                    $part = explode("_", $t[0]);

                    $type = $part[sizeof($part)-1] . ' ' . $t[sizeof($t)-1];
                    $manufacturer = xml_rip($xml,'name="Manufacturer" type="string">','</Component>','name="Hardware_Information"');
                    $map['machine']['type'] = $type; 
                    $map['machine']['title'] = $manufacturer. " " . $type; 
                    $map['machine']['brand'] = $manufacturer;
                }
                else {
                    $manufacturer = xml_rip($xml,'name="Manufacturer" type="string">','</Component>','name="Hardware Information"');
                    $map['machine']['type'] = ''; 
                    $map['machine']['title'] = $manufacturer; 
                    $map['machine']['brand'] = $manufacturer;
                }


                // check if we got the serial first
                $sql = "SELECT id_machine FROM machines WHERE serial = '". $map['machine']['serial'] ."'"; 
                $result = $mysqli->query($sql); 
                if($row = mysqli_fetch_assoc($result)) { 
                    $id_error = "Serienummer eksisterer fra før!";                    
                    $tpl_params['h1'] = 'Feil';
                    $tpl_params['title'] = ' - Admin';
                    $tpl_params['error'] = 'Serienummer eksisterer fra før! <a href="' . SITEURL . '/admin/enheter">Gå til oversikt over enheter</a>';
                    require_once(SITEDIR . "/tpl/tpl_header.php");
                    require_once(SITEDIR . "/tpl/tpl_admin_error.php");
                    require_once(SITEDIR . "/tpl/tpl_footer.php");
                    exit;
                }
                else {
                    // new machine
                    // Prepare an insert statement
                    $sql = 'INSERT INTO machines (title, pctype, memory, deldate, 
                    delmethod, delhandler, delhandlerfirm, delapprover, delapprovefirm, user, 
                    totaldisks, disk0, disk1, disk2, disk3, disk0model, disk1model, disk2model, disk3model,
                    disk0type, disk1type, disk2type, disk3type, serial, customer, customeraddr, type, 
                    cpu, model, brand, deltimestamp) 
                            VALUES("'. (stripstuff($map['machine']['title'])).'",
                            "'.(stripstuff($map['machine']['pctype'])).'",
                            "'.(stripstuff($map['machine']['memory'])).'",
                            "'.(stripstuff($map['machine']['deldate'])).'",
                            "'.(stripstuff($map['machine']['delmethod'])).'", 
                            "'.(stripstuff($map['machine']['delhandler'])).'",
                            "'.(stripstuff($map['machine']['delhandlerfirm'])).'",
                            "'.(stripstuff($map['machine']['delapprover'])).'",
                            "'.(stripstuff($map['machine']['delapprovefirm'])).'",
                            ' . $_SESSION["id"]. ',
                            '.(stripstuff($map['machine']['totaldisks'])).',
                            "'.(stripstuff($map['machine']['disk0'])).'",
                            "'.(stripstuff($map['machine']['disk1'])).'",
                            "'.(stripstuff($map['machine']['disk2'])).'",
                            "'.(stripstuff($map['machine']['disk3'])).'",
                            "'.(stripstuff($map['machine']['disk0model'])).'",
                            "'.(stripstuff($map['machine']['disk1model'])).'",
                            "'.(stripstuff($map['machine']['disk2model'])).'",
                            "'.(stripstuff($map['machine']['disk3model'])).'",
                            "'.(stripstuff($map['machine']['disk0type'])).'",
                            "'.(stripstuff($map['machine']['disk1type'])).'",
                            "'.(stripstuff($map['machine']['disk2type'])).'",
                            "'.(stripstuff($map['machine']['disk3type'])).'",
                            "'.(stripstuff($map['machine']['serial'])).'",
                            "'.(stripstuff($map['machine']['customer'])).'",
                            "'.(stripstuff($map['machine']['customeraddr'])).'",
                            "'.(stripstuff($map['machine']['type'])).'",

                            "'.(stripstuff($map['machine']['cpu'])).'",
                            "'.(stripstuff($map['machine']['model'])).'",
                            "'.(stripstuff($map['machine']['brand'])).'",

                            "'.(stripstuff($map['machine']['deltimestamp'])).'")';
                    // Attempt to execute the prepared statement
                    $mysqli->query($sql); 
                    $goto = $mysqli->insert_id;

                    if (is_uploaded_file($_FILES['pdfimport']['tmp_name'])) {
                        $sourcePath = $_FILES['pdfimport']['tmp_name'];
                        $targetPath = SITEDIR."/ext/pdf/" . $_FILES['pdfimport']['name'];
                        $targetUrl = SITEURL."/ext/pdf/" . $_FILES['pdfimport']['name'];
                        if (move_uploaded_file($sourcePath, $targetPath)) {
            
                            // check if we got the serial first
                            $sql = "SELECT id_machine FROM machines WHERE serial = '". $map['machine']['serial'] ."'"; 
                            $result = $mysqli->query($sql); 
                            if($row = mysqli_fetch_assoc($result)) { 
                                // extract the serial out of the name
                                $ba = explode("-", $_FILES['pdfimport']['name']);
                                // update the pre-existing with new pdf
                                $sql = 'UPDATE machines set delpdf = "'. $_FILES['pdfimport']['name'] .'" WHERE serial = "' . $ba[0] . '"';
                                // Attempt to execute the prepared statement
                                $mysqli->query($sql); 
                            }
                        }
                    }
                    header("Location: ". SITEURL . '/admin/enheter/edit/'.$goto);
                }
            }
        }
    } 
    if (is_array($_FILES['txtimport']) && $_FILES['txtimport']['error'] == 0) {
        if (is_uploaded_file($_FILES['txtimport']['tmp_name'])) {
            $sourcePath = $_FILES['txtimport']['tmp_name'];
            $targetPath = SITEDIR."/ext/xml/" . $_FILES['txtimport']['name'];
            $targetUrl = SITEURL."/ext/xml/" . $_FILES['txtimport']['name'];
            if (move_uploaded_file($sourcePath, $targetPath)) {
                $xml = file_get_contents($targetPath);
                // mapping
                $map = array();
                $map['machine'] = array(
                    'title' => '',
                    'serial' => '',
                    'pctype' => '',
                    'memory' => '',
                    'deldate' => '',
                    'delmethod' => '',
                    'delhandler' => '',
                    'delhandlerfirm' => '',
                    'delapprover' => '',
                    'delapprovefirm' => '',
                ); 
                $map['machine']['serial'] = xml_rip($xml,'System Serial: ',' USB');
                $map['machine']['pctype'] = 'Desktop';
                $map['machine']['memory'] = xml_rip($xml,'Memory (RAM): ',' GB');
                $map['machine']['deldate'] = xml_rip($xml,'Report Date: ',' CEST');
                $map['machine']['deltimestamp'] = convert2timestamp($map['machine']['deldate']);
                $map['machine']['delmethod'] = xml_rip($xml,'Erasure Method: ',' Purge Disks');
                $map['machine']['delhandler'] = 'Christer';
                $map['machine']['delhandlerfirm'] = 'Adcom';
                $map['machine']['delapprover'] = 'Christer';
                $map['machine']['delapprovefirm'] = 'Adcom';
                $map['machine']['cpu'] = xml_rip($xml,'Processor Intel(R) Corporation, ',', Status: ');
                $map['machine']['model'] = xml_rip($xml,'Model Name: ',' UUID:');
 
                $map['machine']['customer'] = 'Brunvoll';
                $map['machine']['customeraddr'] = '';

                $map['machine']['totaldisks'] = '1';
                $map['machine']['disk0'] = xml_rip($xml,', Size: ','</Component>',' GB, Total Sectors');
                $map['machine']['disk0model'] = xml_rip($xml,'Disk 0 Model: ',', Serial');
                $map['machine']['disk0type'] = xml_rip($xml,', Media Type: ',', SMART Status');

                $map['machine']['disk1'] = $map['machine']['disk2'] = $map['machine']['disk3'] = '';
                $map['machine']['disk1model'] = $map['machine']['disk2model'] = $map['machine']['disk3model'] = '';
                $map['machine']['disk1type'] = $map['machine']['disk2type'] = $map['machine']['disk3type'] = '';

                $manufacturer = 'Lenovo';
                $map['machine']['type'] = xml_rip($xml,'Model Name: ',' UUID:'); 
                if($map['machine']['type']=='2988B1G' || $map['machine']['type']=='2988D6G') {
                    $map['machine']['type'] = 'M92p';
                }
                elseif($map['machine']['type']=='4480B2G') {
                    $map['machine']['type'] = 'M91p';
                }
                elseif($map['machine']['type']=='10A90010MX' || $map['machine']['type']=='10A9003SMX') {
                    $map['machine']['type'] = 'M93p';
                }
                
                $map['machine']['title'] = $manufacturer; 
                $map['machine']['brand'] = $manufacturer;

                // check if we got the serial first
                $sql = "SELECT id_machine FROM machines WHERE serial = '". $map['machine']['serial'] ."'"; 
                $result = $mysqli->query($sql); 
                if($row = mysqli_fetch_assoc($result)) { 
                    $id_error = "Serienummer eksisterer fra før!";                    
                    $tpl_params['h1'] = 'Feil';
                    $tpl_params['title'] = ' - Admin';
                    $tpl_params['error'] = 'Serienummer eksisterer fra før! <a href="' . SITEURL . '/admin/enheter">Gå til oversikt over enheter</a>';
                    require_once(SITEDIR . "/tpl/tpl_header.php");
                    require_once(SITEDIR . "/tpl/tpl_admin_error.php");
                    require_once(SITEDIR . "/tpl/tpl_footer.php");
                    exit;
                }
                else {
                    // new machine
                    // Prepare an insert statement
                    $sql = 'INSERT INTO machines (title, pctype, memory, deldate, 
                    delmethod, delhandler, delhandlerfirm, delapprover, delapprovefirm, user, 
                    totaldisks, disk0, disk1, disk2, disk3, disk0model, disk1model, disk2model, disk3model,
                    disk0type, disk1type, disk2type, disk3type, serial, customer, customeraddr, type, 
                    cpu, model, brand, deltimestamp) 
                            VALUES("'. (stripstuff($map['machine']['title'])).'",
                            "'.(stripstuff($map['machine']['pctype'])).'",
                            "'.(stripstuff($map['machine']['memory'])).'",
                            "'.(stripstuff($map['machine']['deldate'])).'",
                            "'.(stripstuff($map['machine']['delmethod'])).'", 
                            "'.(stripstuff($map['machine']['delhandler'])).'",
                            "'.(stripstuff($map['machine']['delhandlerfirm'])).'",
                            "'.(stripstuff($map['machine']['delapprover'])).'",
                            "'.(stripstuff($map['machine']['delapprovefirm'])).'",
                            ' . $_SESSION["id"]. ',
                            '.(stripstuff($map['machine']['totaldisks'])).',
                            "'.(stripstuff($map['machine']['disk0'])).'",
                            "'.(stripstuff($map['machine']['disk1'])).'",
                            "'.(stripstuff($map['machine']['disk2'])).'",
                            "'.(stripstuff($map['machine']['disk3'])).'",
                            "'.(stripstuff($map['machine']['disk0model'])).'",
                            "'.(stripstuff($map['machine']['disk1model'])).'",
                            "'.(stripstuff($map['machine']['disk2model'])).'",
                            "'.(stripstuff($map['machine']['disk3model'])).'",
                            "'.(stripstuff($map['machine']['disk0type'])).'",
                            "'.(stripstuff($map['machine']['disk1type'])).'",
                            "'.(stripstuff($map['machine']['disk2type'])).'",
                            "'.(stripstuff($map['machine']['disk3type'])).'",
                            "'.(stripstuff($map['machine']['serial'])).'",
                            "'.(stripstuff($map['machine']['customer'])).'",
                            "'.(stripstuff($map['machine']['customeraddr'])).'",
                            "'.(stripstuff($map['machine']['type'])).'",

                            "'.(stripstuff($map['machine']['cpu'])).'",
                            "'.(stripstuff($map['machine']['model'])).'",
                            "'.(stripstuff($map['machine']['brand'])).'",

                            "'.(stripstuff($map['machine']['deltimestamp'])).'")';
                    // Attempt to execute the prepared statement
                    $mysqli->query($sql); 
                    $goto = $mysqli->insert_id;

                    if (is_uploaded_file($_FILES['pdfimport']['tmp_name'])) {
                        $sourcePath = $_FILES['pdfimport']['tmp_name'];
                        $targetPath = SITEDIR."/ext/pdf/" . $_FILES['pdfimport']['name'];
                        $targetUrl = SITEURL."/ext/pdf/" . $_FILES['pdfimport']['name'];
                        if (move_uploaded_file($sourcePath, $targetPath)) {
            
                            // check if we got the serial first
                            $sql = "SELECT id_machine FROM machines WHERE serial = '". $map['machine']['serial'] ."'"; 
                            $result = $mysqli->query($sql); 
                            if($row = mysqli_fetch_assoc($result)) { 
                                // extract the serial out of the name
                                $ba = explode("-", $_FILES['pdfimport']['name']);
                                // update the pre-existing with new pdf
                                $sql = 'UPDATE machines set delpdf = "'. $_FILES['pdfimport']['name'] .'" WHERE serial = "' . $ba[0] . '"';
                                // Attempt to execute the prepared statement
                                $mysqli->query($sql); 
                            }
                        }
                    }
                    header("Location: ". SITEURL . '/admin/enheter/edit/'.$goto);
                }
            }
        }
    } 

    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_admin_import.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}
// show the upload form
else {
    $tpl_params['h1'] = 'Importere';
    $tpl_params['title'] = ' - Admin';
    require_once(SITEDIR . "/tpl/tpl_header.php");
    require_once(SITEDIR . "/tpl/tpl_admin_import.php");
    require_once(SITEDIR . "/tpl/tpl_footer.php");
}

?>