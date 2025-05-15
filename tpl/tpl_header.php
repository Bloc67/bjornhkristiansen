<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
<!DOCTYPE html>
<html lang="en">
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
<body>
	<header>
		<a href="' , SITEURL , '"><img class="logo" src="' , SITEURL , '/ext/logo_white.svg" alt="" /></a>
		<h1>' , !empty($tpl_params['h1']) ? $tpl_params['h1'] : '' ,'</h1>
	</header>
	<div class="content">';

if(!empty($tpl_params['notice'])) {
	echo '
		<p class="notice">
			Din bruker er ikke godkjent ennå, innhold er begrenset. 
			Om du vil kan du <a href="' , SITEURL , '/logout">logge ut</a> nå og sjekke på nytt senere.
		</p>';
}

?>