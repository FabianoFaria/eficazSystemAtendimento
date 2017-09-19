<?php
	session_start();
	require_once("../../../../../wp-load.php");
	require_once("../../../../config.php");

	$fornecedorID = $_GET['revenda'];
	$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$fornecedorID' and meta_key = 'representantes_pagamento'");
	if($row = mpress_fetch_array($rs)){
		global $dadosPagamento;
		$dadosPagamento = unserialize(unserialize($row['meta_value']));
		$_SESSION[dadosPagamento] = $dadosPagamento;
	}

	$resultado = mpress_query("select sum(Valor_Venda_Unitario) Valor from telemarketing_workflows_produtos where Workflow_ID = ".$_GET['pid']);
	if($row = mpress_fetch_array($resultado))
		$valorPedido = number_format($row[Valor], 2, '', '');

	$dadosPagamento = $_SESSION[dadosPagamento];
	$idPedido  	 = $_GET['pid'];
	$_SESSION['idPedido'] = $idPedido;
	$formaPagamento = $_GET['parcelasPGTO'];

	$indicadorAutorizacao = 2;
	if($_GET['method']=='A')		$formaPagamento = "A";
	if($_GET['tp'] == "diners") 	$indicadorAutorizacao = 3;
	if($_GET['tp'] == "discover") 	$indicadorAutorizacao = 3;
	if($_GET['tp'] == "elo") 		$indicadorAutorizacao = 3;
	if($_GET['tp'] == "amex") 		$indicadorAutorizacao = 3;
	if($_GET['tp'] == "aura") 		$indicadorAutorizacao = 3;
	if($_GET['tp'] == "jcb") 		$indicadorAutorizacao = 3;
?>
	<div Style='display:block;'>
		<form name='frmPedido' action="novoPedidoAguarde.php" method="post" target = '_self'>
			<input type='text' name="pedido" 					value='<?php echo $idPedido?>'>
			<input type='text' name="produto" 					value='<?php echo $valorPedido?>'>
			<input type="text" name="codigoBandeira" 			value='<?php echo str_replace("cielo-","",$_GET['tp'])?>'>
			<input type="text" name="formaPagamento" 			value="<?php echo $formaPagamento?>">
			<input type='text' name="tipoParcelamento" 			value="2">
			<input type='text' name="capturarAutomaticamente" 	value="true">
			<input type='text' name="indicadorAutorizacao" 		value="<?php echo $indicadorAutorizacao?>">

			<input type='text' name="loja" 	value="<?php echo $dadosPagamento[cielo][loja]?>">
			<input type='text' name="chave" value="<?php echo $dadosPagamento[cielo][chave]?>">
		</form>
	</div>
	<script>
		document.frmPedido.submit()
		function abreCielo(){
			document.frmPedido.submit()
		}
	</script>
