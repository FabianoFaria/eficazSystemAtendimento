<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	$workflowID = $_POST['workflow-id'];
	$propostaID = $_POST['proposta-id'];
	carregarPropostasOrcamentos($workflowID,$propostaID);
	echo "	<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
?>