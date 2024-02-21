<?php

	// error reporting is on
	ini_set('display_errors', 'On');
	error_reporting(E_ERROR);

	include 'admin/connect.php';

	$sessionUser = '';
	
	if (isset($_SESSION['user'])) {
		$sessionUser = $_SESSION['user'];
	}

	$tpl 	= 'includes/templates/'; // template directory
	$lang 	= 'includes/languages/'; // language directory
	$func	= 'includes/functions/'; // functions directory
	$css 	= 'layout/css/'; // css directory
	$js 	= 'layout/js/'; // js directory


	include $func . 'functions.php';
	include $tpl . 'header.php';
	

	