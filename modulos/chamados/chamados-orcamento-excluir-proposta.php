<?php
	include("functions.php");
	$propostaID = $_POST['proposta-id'];
	$situacaoAtualID  = $_POST['situacao-atual-proposta'][$propostaID];
	excluirOrcamentoProposta($propostaID, $situacaoAtualID);
?>