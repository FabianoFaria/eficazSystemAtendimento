<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	$id 		= $_GET['id'];
	$slug 		= $_GET['slug'];
	$sql = "update tipo set Situacao_ID = 3 where Tipo_ID = $id";
	mpress_query($sql);
	header("location:$slug");
?>