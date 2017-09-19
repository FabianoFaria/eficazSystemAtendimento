<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	error_reporting(0);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	$cadastroID = $_GET['cadastro-id'];
	$nomeCampo = $_GET['nome-campo'];
	$descricaoCampo = $_GET['descricao-campo'];
	echo carregarCadastroGeral($cadastroID, $nomeCampo, $descricaoCampo);
?>