<?php
	session_start();
	include("functions.php");
	// (97 - 'Aberta', 1)
	// (98 - 'Finalizada', 1)
	// (99 - 'Cancelada', 1);
	// ATUALIZANDO SITUACAO PARA 99 - CANCELADA
	$sql = "UPDATE pdv SET Situacao_ID = 99 WHERE PDV_ID = '".$_SESSION['pdv-id']."'";
	$rs = mpress_query($sql);
	unset($_SESSION['idCaixa']);
	unset($_SESSION['pdv-id']);
	$_SESSION['idCaixa'] = "";
	$_SESSION['idCaixa'] = "";
	header("location:../../pdv/pdv-inicio/");
?>