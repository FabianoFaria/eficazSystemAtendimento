<?php
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	session_start();

	require "../includes/include.php";

	$pedidoID 	= $_GET['pedidoID'];
	$valorPlano	= $_GET['val'];

	$formaPagamento = substr($_GET['forma'],0,1);
	$quantParcelas 	= substr($_GET['forma'],1,1);

	if($formaPagamento=="M")  $codigoBandeira = "mastercard";
	if($formaPagamento=="V")  $codigoBandeira = "visa";

	if($formaPagamento=="D"){
		$formaPagamento = "A";
		$codigoBandeira = "visa";
	}else
		$formaPagamento = $quantParcelas;
	$capturar 	= "true";
	$autorizar 	= "1";

	$Pedido = new Pedido();

	$Pedido->formaPagamentoBandeira = $codigoBandeira;
	if($formaPagamento != "A" && $quantParcelas != "1")
	{
		$Pedido->formaPagamentoProduto = 2;
		$Pedido->formaPagamentoParcelas = $quantParcelas;
	}
	else
	{
		$Pedido->formaPagamentoProduto = $formaPagamento;
		$Pedido->formaPagamentoParcelas = 1;
	}

	$Pedido->dadosEcNumero = CIELO;
	$Pedido->dadosEcChave = CIELO_CHAVE;

	$Pedido->capturar 	= $capturar;
	$Pedido->autorizar 	= $autorizar;

	$Pedido->dadosPedidoNumero = $pedidoID;
	$Pedido->dadosPedidoValor = $valorPlano;

	$Pedido->urlRetorno = ReturnURL();

	// ENVIA REQUISIÇÃO SITE CIELO
	$objResposta = $Pedido->RequisicaoTransacao(false);

	$Pedido->tid = $objResposta->tid;
	$Pedido->pan = $objResposta->pan;
	$Pedido->status = $objResposta->status;

	$urlAutenticacao = "url-autenticacao";
	$Pedido->urlAutenticacao = $objResposta->$urlAutenticacao;

	// Serializa Pedido e guarda na SESSION
	$StrPedido = $Pedido->ToString();
	$_SESSION["pedidos"]->append($StrPedido);

print_r($Pedido)."*****";


	echo '<script type="text/javascript">
			window.location.href = "' . $Pedido->urlAutenticacao . '"
		 </script>';
?>