<?php
	include("functions.php");
	$tipo 				= $_GET['tipo'];
	$chaveEstrangeira 	= $_GET['chaveEstrangeira'];
	$solicitanteID 		= $_GET['solicitanteID'];
	carregarLocalizarProduto($tipo, $chaveEstrangeira, $solicitanteID);
?>