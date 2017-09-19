<?php
	global $formasPagamentoCadastradas;

	$configuracaoCielo = $wpdb->get_var("select option_value from wp_options where option_name 		= 'configuracao_Cielo'");
	$configuracaoCielo = unserialize($configuracaoCielo);
	if($configuracaoCielo['cielo'] == 's')
		$formasPagamentoCadastradas[1] = 'cielo';
	else
		$formasPagamentoCadastradas[1] = '';

	$configuracaoItau = $wpdb->get_var("select option_value from wp_options where option_name 		= 'configuracao_itau'");
	$configuracaoItau = unserialize($configuracaoItau);
	if($configuracaoItau['itau'] == 's')
		$formasPagamentoCadastradas[2] = 'itau';
	else
		$formasPagamentoCadastradas[2] = '';

	$configuracaoPagSeguro = $wpdb->get_var("select option_value from wp_options where option_name 	= 'configuracao_pagseguro'");
	$configuracaoPagSeguro = unserialize($configuracaoPagSeguro);
	if($configuracaoPagSeguro['pagseguro'] == 's')
		$formasPagamentoCadastradas[3] = 'pagseguro';
	else
		$formasPagamentoCadastradas[3] = '';

	$configuracaoPgtoDig = $wpdb->get_var("select option_value from wp_options where option_name 	= 'configuracao_pagamentodigital'");
	$configuracaoPgtoDig = unserialize($configuracaoPgtoDig);
	if($configuracaoPgtoDig['pagamentodigital'] == 's')
		$formasPagamentoCadastradas[4] = 'pagamentodigital';
	else
		$formasPagamentoCadastradas[4] = '';

	$configuracaoPayPal = $wpdb->get_var("select option_value from wp_options where option_name 	= 'configuracao_paypal'");
	$configuracaoPayPal = unserialize($configuracaoPayPal);
	if($configuracaoPayPal['paypal'] == 's')
		$formasPagamentoCadastradas[5] = 'paypal';
	else
		$formasPagamentoCadastradas[5] = '';

	$configuracaoBoleto = $wpdb->get_var("select option_value from wp_options where option_name 	= 'configuracao_boleto'");
	$configuracaoBoleto = unserialize($configuracaoBoleto);
	if($configuracaoBoleto['boleto'] == 's')
		$formasPagamentoCadastradas[6] = 'boleto';
	else
		$formasPagamentoCadastradas[6] = '';

	$_SESSION['cielo'] 		= $configuracaoCielo['codigo'];
	$_SESSION['cielochave'] = $configuracaoCielo['chave'];

	if($_GET['pg']!=''){
		if($_GET['pg']=='boleto'){
			if($configuracaoBoleto['banco']=='001') $boleto = 'boleto_bb.php';
			if($configuracaoBoleto['banco']=='237') $boleto = 'boleto_bradesco.php';
			if($configuracaoBoleto['banco']=='104') $boleto = 'boleto_cef.php';
			if($configuracaoBoleto['banco']=='399') $boleto = 'boleto_hsbc.php';
			if($configuracaoBoleto['banco']=='341') $boleto = 'boleto_itau.php';
			if($configuracaoBoleto['banco']=='356') $boleto = 'boleto_real.php';
			if($configuracaoBoleto['banco']=='033') $boleto = 'boleto_santander_banespa.php';
			if($configuracaoBoleto['banco']=='748') $boleto = 'boleto_sicredi.php';
			include("boletos/$boleto");
		}


		if($_GET['pg']=='pagseguro'){
			include("pagseguro/index.php");

		}

		if($_GET['pg']=='pagamentodigital'){
			include("pagamentodigital/pagamentodigital.php");
		}

		if($_GET['pg']=='paypal'){
			include("paypal/paypal.php");
		}



		if($_GET['pg']=='itau'){
			include("itau/itau.php");
		}

		$valor = $_GET['pg'];
		$pos = strpos($valor, '-');
		if($pos!=''){
			if(substr($valor, 0,$pos)=='cielo')
				$tpPgto = substr($valor, $pos+1, 100);
				$tpPgto = str_replace('credicard','mastercard', $tpPgto);
				include("cielo/index.php");
		}
	}


	function configuraPluginGateway($cielo, $itau, $pagseguro, $pagdigital, $paypal, $boleto){
			$texto = "	<div id='divGatewayInicio' Style='display:block;margin-top:15px'>
							<a onclick=\"mostraEsconde('divGateway','divGatewayInicio')\" Style='cursor:pointer'>
								<div class=\"sidebar-name\">
									<div class=\"sidebar-name-arrow\"><br /></div>
									<h3>".__('Payment', TRADUTOR_GATEWAY)."</h3>
								</div>
							</a>
						</div>

						<div id='divGateway' Style='display:none;margin-top:15px'>
							<a onclick=\"mostraEsconde('divGatewayInicio','divGateway')\" Style='cursor:pointer'>
								<div class=\"sidebar-name\">
									<div class=\"sidebar-name-arrow\"><br /></div>
									<h3>".__('Payment', TRADUTOR_GATEWAY)."</h3>
								</div>
							</a>

							<table width='100%' cellpadding='2' cellspacing='2' Style='border-radius: 3px;border-left:1px solid #dfdfdf;border-right:1px solid #dfdfdf;border-bottom:1px solid #dfdfdf;'>
								<tr valign='top'>
									<td width='100%'>
										<table id='divFreteGeral' border='0' Style='display:block'>
											<tr>
												<td>
													<div Style='margin: 20px 0;'>
													".__('Payment options', TRADUTOR_GATEWAY).":

													<input type='checkbox' id='pagCielo' name='chkGC'  value='checked' $cielo>
													<label for='pagCielo'>Cielo </label>&nbsp;&nbsp;&nbsp;&nbsp;

													<input type='checkbox' id='pagItau' name='chkGI'  value='checked' $itau>
													<label for='pagItau'>Itau </label>&nbsp;&nbsp;&nbsp;&nbsp;

													<input type='checkbox' id='pagPagseguro' name='chkGP'  value='checked' $pagseguro>
													<label for='pagPagseguro'>PagSeguro </label>&nbsp;&nbsp;&nbsp;&nbsp;

													<input type='checkbox' id='pagBcash' name='chkGD'  value='checked' $pagdigital>
													<label for='pagBcash'>BCash </label>&nbsp;&nbsp;&nbsp;&nbsp;

													<input type='checkbox' id='pagPaypal' name='chkGPP' value='checked' $paypal>
													<label for='pagPaypal'>PayPal </label>&nbsp;&nbsp;&nbsp;&nbsp;

													<input type='checkbox' id='pagBoleto' name='chkGB'  value='checked' $boleto>
													<label for='pagBoleto'>".__('Billet', TRADUTOR_GATEWAY)."</label>
													</div>
												</td>
											</tr>
										</table>
									</td>
								</tr>
							</table>
						</div>";
		echo $texto;
	}

?>
