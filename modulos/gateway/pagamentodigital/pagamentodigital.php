<?php
	global $configuracaoPgtoDig;
	global $pluginFrete;

	$email = $configuracaoPgtoDig[email];

	$formulario = "";
	$html = "";


	$resultado = mysql_query("select * from  wp_produtos_cadastros where Produto_Cadastro_ID = ".$_SESSION['userID']);
	if($row = mysql_fetch_array($resultado)){
		$nomeCliente	= "$row[1] $row[2]";
		$emailCliente	= $row[4];

		$cep			= $row[15];
		$logradouro		= $row[16];
		$numero			= $row[17];
		$complemento	= $row[18];
		$bairro			= $row[19];
		$cidade			= $row[20];
		$uf				= $row[21];

		$foneCliente = $row[7];
		$foneCliente = str_replace(')','',str_replace('(','',$foneCliente));

		if($foneCliente == '') $foneCliente = $row[9];
		if($foneCliente == '') $foneCliente = $row[11];

		$foneClienteDDD 	= trim(substr($foneCliente, 0,2));
		$foneClienteNumero	= trim(substr($foneCliente, 3,10));
	}


	$formulario .= "<input name='email_loja' type='hidden' value='".$email."'>";


	if($pluginFrete == 'ativo')
		$campofrete = ", Valor_Frete ";


	$resultado = mysql_query("select Valor_Total, Produto_Cadastro_Pedido_ID $campofrete
							  from wp_produtos_cadastros_pedidos where Produto_Cadastro_Pedido_ID = (select max(Produto_Cadastro_Pedido_ID)
																									 from wp_produtos_cadastros_pedidos
																									 where Produto_Cadastro_ID = ".$_SESSION['userID'].")");
	if($row = mysql_fetch_array($resultado)){
		$idPedido  	 = $row[1];
		$valorPedido = $row[0];
		$valorFrete  = $row[2];


		$resultado = mysql_query("select Produto_Cadastro_Pedido_Pagamento_ID from wp_produtos_cadastros_pedidos_pagamentos where Produto_Cadastro_Pedido_ID = $idPedido");
		if(!$row = mysql_fetch_array($resultado))
			mysql_query("insert into wp_produtos_cadastros_pedidos_pagamentos(Produto_Cadastro_Pedido_ID,Tipo_Pagamento)values($idPedido, 'Pagamento Digital')");


		$produtos = mysql_query("select distinct p.produto_ID, p.Codigo, p.Descricao_Resumida, pv.Descricao, pd.Quantidade, pd.Valor_Unitario, pd.Valor_Total
								 from wp_produtos_produtos_variacoes pv
								 inner join wp_produtos_produtos_variacoes_valores vv on vv.produto_variacao_id = pv.produto_variacao_id
								 inner join wp_produtos_produtos p on p.produto_ID = pv.produto_ID
								 inner join wp_produtos_cadastros_pedidos_detalhes pd on pd.Produto_Variacao_ID = vv.Produto_Varicao_Valor_ID
								 where Produto_Varicao_Valor_ID in (select Produto_Variacao_ID from wp_produtos_cadastros_pedidos_detalhes where Produto_Cadastro_Pedido_ID = $idPedido)
								 and pd.Produto_Cadastro_Pedido_ID = $idPedido");

		while($prod = mysql_fetch_array($produtos)){
			$p++;
			$formulario .= " <input name='produto_codigo_".$p."' type='hidden' value='".$prod[1]."'>
							<input name='produto_descricao_".$p."' type='hidden' value='".retiraAcentosString($prod[2])."'>
							<input name='produto_qtde_".$p."' type='hidden' value='".$prod[4]."'>
							<input name='produto_valor_".$p."' type='hidden' value='".$prod[5]."'>";
		}
	}
?>
<div Style='width:100%;text-align:center'>
	<form name='bcash' action='https://www.bcash.com.br/checkout/pay/' method='post' target='_blank'>
		<input name='tipo_integracao' type='hidden' value='PAD'>
		<input name='frete' type='hidden' value='<?php echo $valorFrete;?>'>
		<?php echo $formulario; ?>
		Se o seu popup estiver bloqueado clique aqui para abrir o BCash<p/>
		<input type='image' src='https://a248.e.akamai.net/f/248/96284/12h/www.bcash.com.br/webroot/img/bt_comprar.gif' value='Comprar' alt='Comprar' border='0' align='absbottom'>
	</form>
</div>
<script>
	document.bcash.submit();
	function abreBcash(){
		document.bcash.submit()
	}
</script>