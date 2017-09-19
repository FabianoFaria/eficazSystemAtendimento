<?php
	session_start();
	include('../../config.php');
	$aceitas = $_POST['comprasAceitar'];
	$negadas = $_POST['comprasNegar'];
	foreach($negadas as $negada){
		mpress_query("update compras_solicitacoes set Situacao_ID = 64 where Compra_Solicitacao_ID = $negada");
	}
	if(count($aceitas)==0) header("location:../../compras/compras-requisicao");
	else{
		mpress_query("insert into compras_ordem_compra(Usuario_Cadastro_ID)values('".$_SESSION[dadosUserLogin][userID]."')");
		$ordemCompra = mpress_identity();
		mpress_query("insert into compras_ordem_compra_follows(Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompra','Gerado Ordem de Compra','63','	".$_SESSION[dadosUserLogin][userID]."')");
		foreach($aceitas as $aceita){
			mpress_query("insert into compras_ordens_compras_produtos(Ordem_Compra_ID,Compra_Solicitacao_ID)values($ordemCompra,$aceita)");
			mpress_query("update compras_solicitacoes set Situacao_ID = 63 where Compra_Solicitacao_ID = $aceita");
		}
		echo "<form name='frmOC' action='../../compras/compras-visualiza-ordem-gerada' method='post'><input type='hidden'name='ordem-compra-id' value='$ordemCompra'></form><script>document.frmOC.submit();</script>";
	}
?>