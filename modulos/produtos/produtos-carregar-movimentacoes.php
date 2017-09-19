<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("functions.php");
	$produtoVariacaoID 			= $_GET['produtoVariacaoID'];
	carregarMovimentacoes($produtoVariacaoID);
?>

