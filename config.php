<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

$status= array(
    '0' => 'Aktiv',
    '1' => 'Ferdig',
    '2' => 'Ferniss',
    '3' => 'Solgt',
    '4' => 'Kassert',
    '5' => 'Gitt bort',
);
$os = array(
    '0' => '',
    '1' => 'Win10',
    '2' => 'Win11',
    '3' => 'Skolelinux',
);
$healthstate = array(
    '0' => 'Ikke vurdert',
    '1' => 'OK',
    '2' => 'Skader',
    '3' => 'Vrak',
);

?>
