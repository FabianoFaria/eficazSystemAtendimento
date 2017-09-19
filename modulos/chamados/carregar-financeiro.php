<?php
	include("functions.php");
	$workflowID = $_GET['workflow-id'];
	$tabelaEstrangeira = $_GET['tabela-estrangeira'];
	echo utf8_encode(carregarFinanceiro($workflowID, $tabelaEstrangeira));
?>