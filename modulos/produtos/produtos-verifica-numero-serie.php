<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("functions.php");
	$numeroSerie = trim($_GET['numero-serie']);
	$produtoVariacaoID = $_GET['produto-variacao-id'];
	$retorno = 0;
	if (($produtoVariacaoID!='') && ($numeroSerie != "")){
		$sql = "SELECT coalesce(SUM(Quantidade),0) AS Quantidade
					FROM produtos_movimentacoes
					WHERE Produto_Variacao_ID = '$produtoVariacaoID' and Numero_Serie = '$numeroSerie'";
		//echo $sql;
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$retorno = $rs['Quantidade'];
		}
	}
	echo $retorno;
?>