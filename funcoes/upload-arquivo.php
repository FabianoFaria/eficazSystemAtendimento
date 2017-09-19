<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");

	$arquivo		= $_FILES['arquivo-upload'][name];
	$arquivoTmp		= $_FILES['arquivo-upload'][tmp_name];
	if($arquivo != ""){
		$nomeArquivo	= "{".date('Ymd_hms')."}_".retiraCaracteresEspeciais($arquivo);
		move_uploaded_file($arquivoTmp, "../uploads/$nomeArquivo");
	}
	echo $nomeArquivo;
?>
