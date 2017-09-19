<?php
	session_start();
	include("../config.php");
	include("../includes/functions.gerais.php");
	$arquivo		= $_FILES['arquivo-upload-cadastro']['name'];
	$arquivoTmp		= $_FILES['arquivo-upload-cadastro']['tmp_name'];
	if($arquivo != ""){
		$nomeArquivo = retiraCaracteresEspeciais(date('Ymd_hms')."-".$arquivo);
		move_uploaded_file($arquivoTmp,  $caminhoFisico."/uploads/$nomeArquivo");
		echo converteImagem($nomeArquivo);
	}	
?>