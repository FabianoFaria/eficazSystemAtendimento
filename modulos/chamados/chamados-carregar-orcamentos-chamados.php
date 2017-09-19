<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	$workflowID = $_POST['workflow-id'];
	$esconde = $_GET['esconde'];
	carregarOrcamentosChamados($workflowID, $esconde);
?>