<?php
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');
	include('../../../wp-config.php');
	include('../site/functions.php');
	global $caminhoSistema;
	$produtoID = $_POST['produto-id'];
	siteProdutosAtualizar();
	echo "<form action='$caminhoSistema/produtos/produtos-cadastrar' method='post' name='retorno'><input type='hidden' name='produto-id' value='$produtoID'/></form><script>document.retorno.submit();</script>";
?>