<?php
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');

	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	//echo "123";
	require_once("functions.cadastros.php");
	require_once("functions.chamados.php");
	require_once("functions.produtos.php");
	require_once("functions.administrativo.php");

	//echo "123";
	$dados['modulo'] = 'cadastros';
	$dados['slug'] = 'formulario-cadastro';
	$_POST['metodo'] = carregarFormulario($dados);

	if ($_POST['metodo']!=""){
		$$instrucao = $_POST['metodo'];
		$$instrucao($_POST);
	}
?>
