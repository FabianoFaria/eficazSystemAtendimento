<?php
	include("functions.php");
	$propostaID = $_GET['proposta-id'];
	$orcamentoID = $_GET['orcamento-id'];
	orcamentoCopiarProposta($propostaID, $orcamentoID);
?>