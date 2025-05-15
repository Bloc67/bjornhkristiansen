<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

$status= array(
    '0' => 'Registrert',
    '1' => 'Sjekket',
    '2' => 'Aktiv',
    '3' => 'Solgt',
    '4' => 'Donert',
    '5' => 'InternPC',
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
