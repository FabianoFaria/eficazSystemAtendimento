<?php
	global $configuracaoPagSeguro;
	global $pluginFrete;

	$email = $configuracaoPagSeguro[email];
	$token = $configuracaoPagSeguro[token];

	unset($data);

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

	if(strlen($foneClienteDDD)!=2) 			$foneClienteDDD = 41;
	if(is_numeric($foneClienteDDD)!=1)		$foneClienteDDD = 41;

	if(strlen($foneClienteNumero)!=8) 		$foneClienteNumero = 34567890;
	if(is_numeric($foneClienteNumero)!=1)	$foneClienteNumero = 34567890;

	$url = "https://ws.pagseguro.uol.com.br/v2/checkout";

	$data['email'] = $email;
	$data['token'] = $token;
	$data['currency'] = 'BRL';
	$data['senderName'] = $nomeCliente;
	$data['senderEmail'] = $emailCliente;
	$data['shippingType'] = '1';
	$data['senderAreaCode'] = $foneClienteDDD;
	$data['senderPhone'] = str_replace('-','',$foneClienteNumero);
	$data['shippingAddressCountry'] = 'BRA';

	$data['shippingAddressStreet'] 	= $logradouro;
	$data['shippingAddressNumber'] 	= $numero;
	$data['shippingAddressComplement'] = $complemento;
	$data['shippingAddressDistrict'] 	= $bairro;
	$data['shippingAddressPostalCode'] = $cep;
	$data['shippingAddressCity'] 		= $cidade;
	$data['shippingAddressState'] 		= $uf;

	$data['redirectURL'] = str_replace(get_bloginfo('wpurl'), 'server','http://www.interface1.com.br')."/produtos/?".$_SESSION['user_session']."&tp=agradecimento";

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

	 	$data["reference"] 	= "10$idPedido";

		$resultado = mysql_query("select Produto_Cadastro_Pedido_Pagamento_ID from wp_produtos_cadastros_pedidos_pagamentos where Produto_Cadastro_Pedido_ID = $idPedido");
		if(!$row = mysql_fetch_array($resultado))
			mysql_query("insert into wp_produtos_cadastros_pedidos_pagamentos(Produto_Cadastro_Pedido_ID,Tipo_Pagamento)values($idPedido, 'Boleto')");

		$produtos = mysql_query("select distinct p.produto_ID, p.Codigo, p.Descricao_Resumida, pv.Descricao, pd.Quantidade, pd.Valor_Unitario, pd.Valor_Total
								 from wp_produtos_produtos_variacoes pv
								 inner join wp_produtos_produtos_variacoes_valores vv on vv.produto_variacao_id = pv.produto_variacao_id
								 inner join wp_produtos_produtos p on p.produto_ID = pv.produto_ID
								 inner join wp_produtos_cadastros_pedidos_detalhes pd on pd.Produto_Variacao_ID = vv.Produto_Varicao_Valor_ID
								 where Produto_Varicao_Valor_ID in (select Produto_Variacao_ID from wp_produtos_cadastros_pedidos_detalhes where Produto_Cadastro_Pedido_ID = $idPedido)
								 and pd.Produto_Cadastro_Pedido_ID = $idPedido");

		while($prod = mysql_fetch_array($produtos)){
			$p++;
			 $data["itemId$p"] 			= $prod[1];
			 $data["itemDescription$p"] = retiraAcentosString(substr($prod[2], 0,100));
			 $data["itemAmount$p"] 		= $prod[5];
			 $data["itemQuantity$p"] 	= $prod[4];
			 $data["itemWeight$p"] 		= '0';
		}

		if($valorFrete != ""){
			if($valorFrete != "0.00"){
				$p++;
				$data["itemId$p"] 			= "999";
				$data["itemDescription$p"] = 'Valor Frete';
				$data["itemAmount$p"] 		= number_format($valorFrete,2, '.','');
				$data["itemQuantity$p"] 	= '1';
				$data["itemWeight$p"] 		= '0';
			}
		}
	}

	$data = http_build_query($data);

	$headers[] = "Host: " . $_SERVER["SERVER_NAME"];
	$headers[] = "Content-Length: " . strlen($data);
	$headers[] = "Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1";

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	$xml = curl_exec($curl);

	$erro = curl_error($curl);
	if($erro != ""){
		echo "<br><br>&nbsp;&nbsp;Ocorreu um erro na transa&ccedil;&atilde;o: $erro";
		exit;
	}

	if($xml == 'Unauthorized'){
		echo "<br><br><br><center>".__('Unauthorized Connection', TRADUTOR_PRODUTOS)."<br><br>";
		exit;
		header('Location: erro.php?tipo=autenticacao');
		exit;
	}
	curl_close($curl);

	$xml= simplexml_load_string($xml);

	if(count($xml -> error) > 0){
		header('Location: erro.php?tipo=dados Invalidos&erro='.$xml->error->message);
		exit;
	}
?>
<div Style='width:100%;text-align:center'>
<br><br>
	<input class='produtos-finaliza-pagseguro' style='margin:-3px 0 0 10px; *margin:0 10px;' type='button' value='Se o seu popup estiver bloqueado clique aqui para abrir o PagSeguro' onclick="abrepagSeguro()">
</div>
<script>

	open("https://pagseguro.uol.com.br/v2/checkout/payment.html?code=<?php echo $xml -> code?>", 'pagamento');

	function abrepagSeguro(){
		open("https://pagseguro.uol.com.br/v2/checkout/payment.html?code=<?php echo $xml -> code?>", 'pagamento');
	}
</script>
