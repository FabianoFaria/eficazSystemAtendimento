<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	mpress_query("update produtos_imagens set Situacao_ID = 2 where Imagem_ID = ".$_GET['imagemID']);
	$query = mpress_query("select Imagem_ID, Posicao, Imagem3, Nome_Imagem from produtos_imagens where produto_ID = ".$_GET['produtoID']." and Situacao_ID = 1 order by posicao");
	while($row = mpress_fetch_array($query)){
		echo "	<div Style='height:180px;width:150px;outline:0px solid red;float:left;padding:5px;'>
					".$row["Posicao"]." - ".$row["Nome_Imagem"]."
					<div class='btn-excluir btn-excluir-produto' style='float:right; padding-right:0px' produto-imagem-id='".$row["Imagem_ID"]."' title='Excluir'>&nbsp;</div>
					<img src='../images/produtos/".$row["Imagem3"]."' Style='margin-top:2px;'>
				</div>";
	}
?>