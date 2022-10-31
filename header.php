<!DOCTYPE html>
<html lang="en">
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
    	<title><?=isset($PageTitle) ? $PageTitle : "TalkHub" ?></title>
	    <link rel="stylesheet" href="/css/styles.css">
	</head>

	<body>
	
		<img src="img/logo.png" id="logo" />
		
		<?php if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true): ?>
			<?php include_once('navbar.php'); ?>
		<?php endif ?>
				
			
