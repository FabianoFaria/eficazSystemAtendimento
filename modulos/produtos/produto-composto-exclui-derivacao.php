<?php
	header ('Content-type: text/html; charset=ISO-8859-1');
	include("functions.php");
	global $dadosUserLogin;

	mpress_query("update produtos_compostos set Situacao_ID = 3 where Produto_Composto_ID = ".$_POST['composicao-id']);

	carregaDetalhesProdutoComposto();
?>
