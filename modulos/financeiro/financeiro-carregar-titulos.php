<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	$contaID = $_POST['localiza-conta-id'];
	$tituloID = $_POST['localiza-titulo-id'];
	$situacaoTitulos = $_POST['situacao-titulos'];
	$tipo = $_POST['radio-tipo-grupo-27'];
	carregarTitulos($contaID, $tituloID, $situacaoTitulos, $tipo);
?>