<?php
	header ('Content-type: text/html; charset=ISO-8859-1');
	include("functions.php");
	global $dadosUserLogin;
	if ($_POST['produto-id']=="") $produtoID = $dadosUserLogin['userID'] * (-1); else $produtoID = $_POST['produto-id'];
	mpress_query("insert into produtos_compostos(Produto_Pai_ID,Produto_Variacao_ID,Quantidade,Usuario_Cadastro_ID)
				  values('".$produtoID."','".$_POST['select-produtos']."','".$_POST['quantidade-produtos']."','".$dadosUserLogin['userID']."')");

	carregaDetalhesProdutoComposto();
?>
