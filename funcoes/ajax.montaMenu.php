<?php
	session_start();
	include("../config.php");
	include("../includes/functions.menu.php");

	global $dadosUserLogin;
	$slug   = $_GET['slug'];
	$id		= $_GET['id'];
	$pos	= $_GET['pos'];
	$nivel	= $_GET['nivel'];

	echo dadosMenuNiveis($slug, $id, $pos, $nivel);
?>
