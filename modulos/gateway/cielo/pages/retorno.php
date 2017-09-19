<?php
	require '../../../../config.php';
	require '../includes/include.php';

	// Resgata último pedido feito da SESSION
	$ultimoPedido = $_SESSION["pedidos"]->count();

	$ultimoPedido -= 1;

	$Pedido = new Pedido();
	$Pedido->FromString($_SESSION["pedidos"]->offsetGet($ultimoPedido));

	// Consulta situação da transação
	$objResposta = $Pedido->RequisicaoConsulta();

	// Atualiza status
	$Pedido->status = $objResposta->status;

	if($Pedido->status == '4' || $Pedido->status == '6')
		$finalizacao = true;
	else
		$finalizacao = false;

	// Atualiza Pedido da SESSION
	$StrPedido = $Pedido->ToString();
	$_SESSION["pedidos"]->offsetSet($ultimoPedido, $StrPedido);

	$arquivo = fopen("../../../../../wp-content/plugins/gateway/cielo/pedidos/pedido_".$_SESSION['idPedido'].".xml", "w");
	$texto = $objResposta->asXML();
	fwrite($arquivo, str_replace('-','',$texto));
	fclose($arquivo);

	$arquivoRetorno = "../../../../../wp-content/plugins/gateway/cielo/pedidos/pedido_".$_SESSION['idPedido'].".xml";
	$xmlRetorno = simplexml_load_file($arquivoRetorno);
	$resultadoTransacao['tid'] = (string) $xmlRetorno->tid;

	foreach($xmlRetorno->dadospedido as $dados){
		$resultadoTransacao['dadospedido']['numeropedido'] 		= (string) $dados->numero;
		$resultadoTransacao['dadospedido']['valorpedido']  		= (string) $dados->valor;
	}
	foreach($xmlRetorno->formapagamento as $forma){
		$resultadoTransacao['formapagamento']['bandeira'] 		= (string) $forma->bandeira;
		$resultadoTransacao['formapagamento']['tipopagamento']  = (string) $forma->produto;
		$resultadoTransacao['formapagamento']['parcelas'] 		= (string) $forma->parcelas;
	}
	foreach($xmlRetorno->autenticacao as $autenticacao){
		$resultadoTransacao['autenticacao']['codigo'] 			= (string) $autenticacao->codigo;
		$resultadoTransacao['autenticacao']['mensagem'] 		= (string) $autenticacao->mensagem;
		$resultadoTransacao['autenticacao']['datahora'] 		= (string) $autenticacao->datahora;
		$resultadoTransacao['autenticacao']['valor'] 			= (string) $autenticacao->valor;
	}
	foreach($xmlRetorno->autorizacao as $autorizacao){
		$resultadoTransacao['autorizacao']['codigo'] 			= (string) $autorizacao->codigo;
		$resultadoTransacao['autorizacao']['mensagem'] 			= (string) $autorizacao->mensagem;
		$resultadoTransacao['autorizacao']['datahora'] 			= (string) $autorizacao->datahora;
		$resultadoTransacao['autorizacao']['valor'] 			= (string) $autorizacao->valor;
	}


	$dadosTransacao = $resultadoTransacao;


	$codigoRetorno   = $dadosTransacao['autorizacao']['codigo'];
	$mensagemRetorno = $dadosTransacao['autorizacao']['mensagem'];
	$valorPedido 	 = $dadosTransacao['dadospedido']['valorpedido']/100;
	$numeroPedido 	 = $dadosTransacao['dadospedido']['numeropedido'];
	$bandeira		 = $dadosTransacao['formapagamento']['bandeira'];
	$Npedido		 = $dadosTransacao['dadospedido']['numeropedido'];
	$parcelas		 = $dadosTransacao['formapagamento']['parcelas'];
	$tid			 = $dadosTransacao['tid'];


	if($codigoRetorno  =='')	$codigoRetorno 	= 5;
	if($mensagemRetorno=='')	$mensagemRetorno = 'AUTORIZACAO NEGADA';

	if($codigoRetorno == 5)
		$imgRetorno = "transacao-nao-autorizada.png";
	else
		$imgRetorno = "transacao-autorizacao.png";

	if($codigoRetorno == 6)
		mpress_query("insert into telemarketing_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Responsabilidade_ID, Usuario_Cadastro_ID  )
					  values('$numeroPedido', 'Pagamento Telemarketing Efetuado.', '', '42', '', '".$dadosUserLogin['userID']."')");
	else
		mpress_query("insert into telemarketing_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Responsabilidade_ID, Usuario_Cadastro_ID  )
					  values('$numeroPedido', 'Pagamento por Cartão Selecionado. Bandeira: ".strtoupper($bandeira)." - Parcelas: $parcelas - Status: AUTORIZACAO NEGADA. ', '', '39', '', '".$dadosUserLogin['userID']."')");






	echo "	<div Style='text-align:center; width:460px;margin:50px auto;'>
				<div style='width:140px; height:120px; float:left;'>
					<img style='display:block; margin:20px auto;' src='https://clarotvlivre.brasilsat.com.br/wp-content/images/layout/$imgRetorno' />
				</div>
				<div class='retorno-info' style='width:270px; margin-left:0px; float:left;'>
					<ul>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>Status: $codigoRetorno - ".utf8_decode($mensagemRetorno)."</li>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>Bandeira:$bandeira</li>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>Pedido:$Npedido</li>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>Parcelas:$parcelas</li>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>TID: $tid</li>
						<li class='retorno-titulo' Style='list-style-type:none;text-align:left;padding:5px;'>Valor R$ ".number_format($valorPedido, 2, ',', ' ')."</li>
					</ul>
				</div>
			</div>
			<script>
				parent.document.getElementById('frame-abre-pagamento-pedido').style.height = '300px';
				setTimeout(function(){parent.location.reload();}, 3000);
			</script>";
?>
