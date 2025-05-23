<?php

// no access directly
if (!defined('APG'))
	header("location: ". SITEURL);

echo '
<!DOCTYPE html>
<html lang="en">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="' , SITEURL , '/ext/site_guest.css?v=2">
    <script type="text/javascript" src="' , SITEURL , '/ext/code.jquery.com_jquery-3.7.1.slim.min.js"></script>
	<meta charset="UTF-8">
	<link rel="apple-touch-icon" sizes="180x180" href="' . SITEURL . '/ext/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="' . SITEURL . '/ext/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="' . SITEURL . '/ext/favicon-16x16.png">
	<link rel="manifest" href="' . SITEURL . '/ext/site.webmanifest">
	<title>', $tpl_params['site'], $tpl_params['title'] , '</title>
</head>
<body id="frontpage">
	<div class="content">';

?>