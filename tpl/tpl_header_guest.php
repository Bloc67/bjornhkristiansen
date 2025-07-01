<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
<!DOCTYPE html>
<html lang="en" id="gall">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="' , SITEURL , '/ext/site.css?v=2">
    <script type="text/javascript" src="' , SITEURL , '/ext/code.jquery.com_jquery-3.7.1.slim.min.js"></script>
	<meta charset="UTF-8">
	<link rel="apple-touch-icon" sizes="180x180" href="' . SITEURL . '/ext/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="' . SITEURL . '/ext/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="' . SITEURL . '/ext/favicon-16x16.png">
	<link rel="manifest" href="' . SITEURL . '/ext/site.webmanifest">
	<title>', $tpl_params['site'], $tpl_params['title'] , '</title>
</head>
<body id="gallery">
	<header id="gheader"><div>
		<h1><a href="' , SITEURL , '">BHKristiansen</a></h1>
		<ul class="menylinker">
				<li><a href="' . SITEURL . '"' , $action=='side' && $subaction=='' ? ' class="active"' : '' , '>Galleri</a></li>
				<li><a href="' . SITEURL . '/side/om"' , $action=='side' && $subaction=='om' ? ' class="active"' : '' , '>Om</a></li>
				<li><a href="' . SITEURL . '/side/kontakt"' , $action=='side' && $subaction=='kontakt' ? ' class="active"' : '' , '>Kontakt</a></li>
		</ul>
	</div></header>
	<div class="content" id="gcontent">';

?>