<?php
	// error reporting is on
	ini_set('display_errors', 'On');
	error_reporting(E_ERROR);
	include 'connect.php';


	$tpl 	= 'includes/templates/'; // template directory
	$lang 	= 'includes/languages/'; // language directory
	$func	= 'includes/functions/'; // functions directory
	$css 	= 'layout/css/'; // css directory
	$js 	= 'layout/js/'; // js directory

	// include the important files
	include $func . 'functions.php';
	include $tpl . 'header.php';

	// include navbar on all pages expect the one with $nonavbar vairable
	if (!isset($noNavbar)) { include $tpl . 'navbar.php'; }
	

	