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
$aar= array(
    '2025' => '2025',
    '2024' => '2024',
    '2023' => '2023',
    '2022' => '2022',
    '2021' => '2021',
    '2020' => '2020',
    '2019' => '2019',
    '2018' => '2018',
    '2017' => '2017',
);
$mnd = array(
    '01' => 'Januar',
    '02' => 'Februar',
    '03' => 'Mars',
    '04' => 'April',
    '05' => 'Mai',
    '06' => 'Juni',
    '07' => 'Juli',
    '08' => 'August',
    '09' => 'September',
    '10' => 'Oktober',
    '11' => 'November',
    '12' => 'Desember',
);
$healthstate = array(
    '0' => 'Ikke vurdert',
    '1' => 'OK',
    '2' => 'Skader',
    '3' => 'Vrak',
);

?>
