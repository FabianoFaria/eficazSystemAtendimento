<?php
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');

	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	require_once("functions.cadastros.php");
	require_once("functions.chamados.php");
	require_once("functions.produtos.php");

	$_POST['metodo'] = produtosLocaliza();
	// $_POST['metodo'] = chamadosLocaliza();
	if ($_POST['metodo']!=""){
		$$instrucao = $_POST['metodo'];
		$$instrucao($_POST);
	}
?>
