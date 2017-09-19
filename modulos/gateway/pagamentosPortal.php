<?php
	global $caminhoPluginGateway;
	global $configuracaoProdutos;
?>
	<link rel='stylesheet' type='text/css' href='<?php echo $caminhoPluginGateway?>gateway.css' media='all' />
	<script type='text/javascript' src='<?php echo $caminhoPluginGateway?>gateway.js'></script>
	<div class="wrap">
			<h2>
				<img src='<?php echo bloginfo('wpurl')?>/wp-content/images/admin/produtos1.png'>&nbsp;<?php _e('Products - Methods of Payments', TRADUTOR_GATEWAY)?>
				<a href="javascript:salvaconfiguracao('<?php echo $_GET['tp']?>')" class="add-new-h2"><?php _e('Save Settings', TRADUTOR_GATEWAY)?></a>
				<p Style='margin-bottom:0px;margin-top:30px;'>
					<?php if($configuracaoProdutos[gateway][cielo] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=cielo&acao=edita" class="add-new-h2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Cielo&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php }?>
					<?php if($configuracaoProdutos[gateway][itau] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=itau&acao=edita" class="add-new-h2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Itau&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php }?>
					<?php if($configuracaoProdutos[gateway][pagseguro] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=pagseguro&acao=edita" class="add-new-h2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;PagSeguro&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php }?>
					<?php if($configuracaoProdutos[gateway][pagdigital] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=pagamentodigital&acao=edita" class="add-new-h2"><?php _e('Digital Payment', TRADUTOR_GATEWAY)?></a>
					<?php }?>
					<?php if($configuracaoProdutos[gateway][paypal] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=paypal&acao=edita" class="add-new-h2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Paypal&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</a>
					<?php }?>
					<?php if($configuracaoProdutos[gateway][boleto] == 'checked'){?>
						<a href="?page=produtos_pagamento&tp=boleto&acao=edita" class="add-new-h2">&nbsp;<?php _e('Bank Transfer', TRADUTOR_GATEWAY)?>&nbsp;</a>
					<?php }?>
				</p>
			</h2>

<?php
	if($_GET['tp']=='boleto'){
		if($_GET['acao']=='salva')
			gravaBoleto();
		boleto();
	}
	if($_GET['tp']=='paypal'){
		if($_GET['acao']=='salva')
			gravaPaypal();
		paypal();
	}
	if($_GET['tp']=='pagamentodigital'){
		if($_GET['acao']=='salva')
			gravaPagamentoDigital();
		pagamentoDigital();
	}
	if($_GET['tp']=='pagseguro'){
		if($_GET['acao']=='salva')
			gravaPagSeguro();
		pagSeguro();
	}
	if($_GET['tp']=='itau'){
		if($_GET['acao']=='salva')
			gravaItau();
		itau();
	}
	if($_GET['tp']=='cielo'){
		if($_GET['acao']=='salva')
			gravacielo();
		cielo();
	}



	function boleto(){
		global $caminhoPluginGateway;
		global $configuracaoBoleto;
		global $wpdb;

		$configuracaoBoleto = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_boleto'");
		$configuracaoBoleto = unserialize($configuracaoBoleto);
		if($configuracaoBoleto['posvcto'] == 's') $checado = 'checked';
		if($configuracaoBoleto['boleto'] == 's')  $boleto = 'checked';

		$texto .= "	<h3>
						".__('Configure Bank Transfer', TRADUTOR_GATEWAY)."
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>

						".__('Using Pay Per Boleto', TRADUTOR_GATEWAY)."?&nbsp;&nbsp;
						<input type='radio' name='rdBoleto' value='n' checked> ".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdBoleto' value='s' $boleto> ".__('Yes', TRADUTOR_GATEWAY)."

						<table>
							<tr>
								<td width='30'>".__('Bank', TRADUTOR_GATEWAY).":</td>
								<td width='120'>
									<select name='slcBanco' id='slcBanco' Style='margin-top:3px;width:130px;'>
										<option value=''>".__('Select', TRADUTOR_GATEWAY).":</option>
										<option value='001'>Banco do Brasil</option>
										<option value='237'>Bradesco</option>
										<option value='104'>Caixa Economica</option>
										<option value='399'>Hsbc</option>
										<option value='341'>Ita&uacute; / Unibanco</option>
										<option value='033'>Santander</option>
									</select>
									<script>
										document.getElementById('slcBanco').value = '$configuracaoBoleto[banco]';
									</script>
								</td>
								<td width='045'>".__('agency', TRADUTOR_GATEWAY).":</td>
								<td width='060'><input type='text' name='txtAgencia' 		id='txtAgencia' 		Style='width:045px;' 	onkeypress='mascaraVal(this)' maxlength='6' value='$configuracaoBoleto[agencia]'></td>
								<td width='100'>".__('checking account', TRADUTOR_GATEWAY).":</td>
								<td width='080'><input type='text' name='txtContaCorrente' 	id='txtContaCorrente' 	Style='width:068px;' 	onkeypress='mascaraCampo(this)' maxlength='12' value='$configuracaoBoleto[conta]'></td>
								<td width='025'>".__('digit', TRADUTOR_GATEWAY).":</td>
								<td width='040'><input type='text' name='txtDigito' 		id='txtDigito' 			Style='width:025px;' 	onkeypress='mascaraCampo(this)' maxlength='12' value='$configuracaoBoleto[digito]'></td>
								<td width='90'>".__('corporate name', TRADUTOR_GATEWAY).":</td>
								<td width='160'><input type='text' name='txtRazaoSocial' 	id='txtRazaoSocial' 	Style='width:110px;' 	onkeypress='mascaraCampo(this)' maxlength='120' value='$configuracaoBoleto[razao]'></td>
								<td width='030'>Cnpj:</td>
								<td width='250'><input type='text' name='txtNumeroCnpj' 	id='txtNumeroCnpj' 		Style='width:110px;' 	onkeypress='mascaraVal(this)' onblur='validaCNPJ(this.value, this)' maxlength='14' value='$configuracaoBoleto[cnpj]'></td>
							</tr>
						</table>

						<table>
							<tr>
								<td width='125'>".__('Days to Maturity', TRADUTOR_GATEWAY).":</td>
								<td width='062'><input type='text' name='txtDiasVcto' 		id='txtDiasVcto' 		Style='width:038px;' 	onkeypress='mascaraVal(this)' maxlength='2' value='$configuracaoBoleto[diasvcto]'></td>
								<td width='140'>".__('Accept Winning Post', TRADUTOR_GATEWAY).":</td>
								<td width='020'><input type='radio' name='rdAceita' 		id='rdAceitan' value='n' checked></td>
								<td width='030'>".__('Not', TRADUTOR_GATEWAY)."</td>
								<td width='020'><input type='radio' name='rdAceita' 		id='rdAceitas' value='s' $checado></td>
								<td width='035' align='left'>".__('Yes', TRADUTOR_GATEWAY)."</td>
								<td width='175'>".__('Maximum Term for Payment', TRADUTOR_GATEWAY).":</td>
								<td width='055'><input type='text' name='txtPrazoMaximo' 	id='txtPrazoMaximo' 	Style='width:045px;' 	onkeypress='mascaraVal(this)' maxlength='4' value='$configuracaoBoleto[prazomaximo]'></td>
								<td width='100'>".__('Penalty Delay', TRADUTOR_GATEWAY)." (%):</td>
								<td width='048'><input type='text' name='txtMulta' 			id='txtMulta'		 	Style='width:037px;' 	onkeypress='mascaraVal(this)' onkeyup='formataValor(this)' maxlength='4' value='$configuracaoBoleto[multa]'></td>
								<td width='90'>".__('interest Day', TRADUTOR_GATEWAY)." (%):</td>
								<td><input type='text' name='txtJuros' 						id='txtJuros'		 	Style='width:050px;' 	onkeypress='mascaraVal(this)' onkeyup='formataValor(this)' maxlength='4' value='$configuracaoBoleto[juros]'></td>
							</tr>
						</table>

						<table>
							<tr>
								<td width='125'>".__('Text of Note', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtObservacao' id='txtObservacao' 	Style='width:690px;' onkeypress='mascaraCampo(this)' maxlength='250'  value='$configuracaoBoleto[observacao]'></td>
							</tr>
						</table>
					</form>";

		echo $texto;
	}

	function gravaBoleto(){
		global $configuracaoBoleto;
		global $wpdb;

		$configuracaoBoleto['boleto']	   = $_POST['rdBoleto'];
		$configuracaoBoleto['banco'] 	   = $_POST['slcBanco'];
		$configuracaoBoleto['agencia'] 	   = $_POST['txtAgencia'];
		$configuracaoBoleto['digito'] 	   = $_POST['txtDigito'];
		$configuracaoBoleto['conta'] 	   = $_POST['txtContaCorrente'];
		$configuracaoBoleto['razao'] 	   = $_POST['txtRazaoSocial'];
		$configuracaoBoleto['cnpj'] 	   = $_POST['txtNumeroCnpj'];
		$configuracaoBoleto['diasvcto']    = $_POST['txtDiasVcto'];
		$configuracaoBoleto['posvcto'] 	   = $_POST['rdAceita'];
		$configuracaoBoleto['prazomaximo'] = $_POST['txtPrazoMaximo'];
		$configuracaoBoleto['multa'] 	   = $_POST['txtMulta'];
		$configuracaoBoleto['juros'] 	   = $_POST['txtJuros'];
		$configuracaoBoleto['observacao'] = $_POST['txtObservacao'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_boleto'");
		if($boleto ==0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_boleto', '".serialize($configuracaoBoleto)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoBoleto)."' where option_name = 'configuracao_boleto'");
	}
	function paypal(){
		global $wpdb;
		global $configuracaoPaypal;

		$configuracaoPaypal = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_paypal'");
		$configuracaoPaypal = unserialize($configuracaoPaypal);
		if($configuracaoPaypal['paypal'] == 's')  $paypal = 'checked';

		$texto .= "	<h3>
						".__('Configure Payment', TRADUTOR_GATEWAY)." Paypal
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>
						".__('Pay per use', TRADUTOR_GATEWAY)." Paypal?&nbsp;&nbsp;
						<input type='radio' name='rdPaypal' value='n' checked>".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdPaypal' value='s' $paypal>".__('Yes', TRADUTOR_GATEWAY)."
						<p></p>
						<table>
							<tr>
								<td width='110'>".__('Email Address', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtEmail'	id='txtEmail' Style='width:350px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPaypal[email]'></td>
							</tr>
							<tr>
								<td>".__('Signature', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtAssinatura' id='txtAssinatura' Style='width:350px;' onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPaypal[assinatura]'></td>
							</tr>
						</table>
					</form>";
		echo $texto;
	}

	function gravaPaypal(){
		global $wpdb;
		global $configuracaoPaypal;

		$configuracaoPaypal['paypal']		= $_POST['rdPaypal'];
		$configuracaoPaypal['email']		= $_POST['txtEmail'];
		$configuracaoPaypal['assinatura']	= $_POST['txtAssinatura'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_paypal'");
		if($boleto ==0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_paypal', '".serialize($configuracaoPaypal)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoPaypal)."' where option_name = 'configuracao_paypal'");
	}

	function pagamentoDigital(){
		global $wpdb;
		global $configuracaoPgtoDig;

		$configuracaoPgtoDig = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_pagamentodigital'");
		$configuracaoPgtoDig = unserialize($configuracaoPgtoDig);
		if($configuracaoPgtoDig['pagamentodigital'] == 's')  $pagamentodigital = 'checked';

		$texto .= "	<h3>
						".__('Configure Digital Payment', TRADUTOR_GATEWAY)."
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>
						".__('Using Digital Payment Payment?', TRADUTOR_GATEWAY)."&nbsp;&nbsp;
						<input type='radio' name='rdPgtoDig' value='n' checked>".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdPgtoDig' value='s' $pagamentodigital>".__('Yes', TRADUTOR_GATEWAY)."
						<p></p>
						<table>
							<tr>
								<td width='110'>".__('Email Address', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtEmail'	id='txtEmail' Style='width:350px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPgtoDig[email]'></td>
							</tr>
						</table>
					</form>";
		echo $texto;
	}

	function gravaPagamentoDigital(){
		global $wpdb;
		global $configuracaoPgtoDig;

		$configuracaoPgtoDig['pagamentodigital'] = $_POST['rdPgtoDig'];
		$configuracaoPgtoDig['email'] 			 = $_POST['txtEmail'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_pagamentodigital'");
		if($boleto ==0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_pagamentodigital', '".serialize($configuracaoPgtoDig)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoPgtoDig)."' where option_name = 'configuracao_pagamentodigital'");
	}

	function pagSeguro(){
		global $wpdb;
		global $configuracaoPagSeguro;

		$configuracaoPagSeguro = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_pagseguro'");
		$configuracaoPagSeguro = unserialize($configuracaoPagSeguro);
		if($configuracaoPagSeguro['pagseguro'] == 's')  $pagseguro = 'checked';

		$texto .= "	<h3>
						".__('Configure Payment', TRADUTOR_GATEWAY)." PagSeguro
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>
						".__('Using Pay Per PagSeguro?', TRADUTOR_GATEWAY)."&nbsp;&nbsp;
						<input type='radio' name='rdPagSeguro' value='n' checked> ".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdPagSeguro' value='s' $pagseguro> ".__('Yes', TRADUTOR_GATEWAY)."
						<p></p>
						<table>
							<tr>
								<td width='110'>".__('Email Address', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtEmail'	id='txtEmail' Style='width:350px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPagSeguro[email]'></td>
							</tr>
							<tr>
								<td>".__('Token Code', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtToken' 	id='txtToken' Style='width:350px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPagSeguro[token]'></td>
							</tr>
						</table>
					</form>";
		echo $texto;
	}

	function gravaPagSeguro(){
		global $wpdb;
		global $configuracaoPagSeguro;

		$configuracaoPagSeguro['pagseguro'] = $_POST['rdPagSeguro'];
		$configuracaoPagSeguro['email'] 	= $_POST['txtEmail'];
		$configuracaoPagSeguro['token'] 	= $_POST['txtToken'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_pagseguro'");
		if($boleto ==0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_pagseguro', '".serialize($configuracaoPagSeguro)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoPagSeguro)."' where option_name = 'configuracao_pagseguro'");
	}

	function itau(){
		global $wpdb;
		global $configuracaoPgtoItau;

		$configuracaoPgtoItau = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_itau'");
		$configuracaoPgtoItau = unserialize($configuracaoPgtoItau);
		if($configuracaoPgtoItau['itau'] == 's')  $itau = 'checked';

		$texto .= "	<h3>
						".__('Configure Payment Itaú Bank Line', TRADUTOR_GATEWAY)."
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>
						".__('Using Pay Per Itaú Bank Line?', TRADUTOR_GATEWAY)."&nbsp;&nbsp;
						<input type='radio' name='rdItau' value='n' checked> ".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdItau' value='s' $itau> ".__('Yes', TRADUTOR_GATEWAY)."
						<p></p>
						<table>
							<tr>
								<td width='118'>".__('Company Code', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtCodigo'	id='txtCodigo' Style='width:350px;' onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPgtoItau[codigo]'></td>
							</tr>
							<tr>
								<td>".__('Key Itaú Bank Line', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtChave' 	id='txtChave' Style='width:350px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPgtoItau[chave]'></td>
							</tr>
						</table>
					</form>";
		echo $texto;
	}

	function gravaItau(){
		global $wpdb;
		global $configuracaoPgtoItau;

		$configuracaoPgtoItau['itau'] 	= $_POST['rdItau'];
		$configuracaoPgtoItau['codigo'] = $_POST['txtCodigo'];
		$configuracaoPgtoItau['chave'] 	= $_POST['txtChave'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_itau'");
		if($boleto ==0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_itau', '".serialize($configuracaoPgtoItau)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoPgtoItau)."' where option_name = 'configuracao_itau'");
	}

	function cielo(){
		global $wpdb;
		global $configuracaoPgtoCielo;
		global $configuracaoCieloParcelamento;

		$configuracaoPgtoCielo = $wpdb->get_var("select option_value from wp_options where option_name = 'configuracao_cielo'");
		$configuracaoPgtoCielo = unserialize($configuracaoPgtoCielo);
		if($configuracaoPgtoCielo['cielo'] == 's')  $cielo = 'checked';

		$resultado = mysql_query("select * from wp_gateway_regras_parcelamento");
		if($row = mysql_fetch_array($resultado)){
			$configuracaoCieloParcelamento[parcelar]		= $row[1];
			$configuracaoCieloParcelamento[parcelas]		= $row[2];
			$configuracaoCieloParcelamento[aplicarjuros]	= $row[3];
			$configuracaoCieloParcelamento[taxajuros]		= $row[4];
			$configuracaoCieloParcelamento[parcelainicial]	= $row[5];
		}


		$cieloParcelamento[$configuracaoCieloParcelamento['parcelar']] = "checked";
		$cieloParcelas[$configuracaoCieloParcelamento['parcelas']] = "selected";
		$cieloAplicaJuros[$configuracaoCieloParcelamento['aplicarjuros']] = "checked";
		$cieloJurosParcelaInicial[$configuracaoCieloParcelamento['parcelainicial']] = "selected";

		$texto .= "	<h3>
						".__('Configure Payment', TRADUTOR_GATEWAY)." Cielo
					</h3>";

		$texto .= "	<form name='frmPagamento' method='post'>
						".__('Using Payment Cielo?', TRADUTOR_GATEWAY)."&nbsp;&nbsp;
						<input type='radio' name='rdCielo' value='n' checked> ".__('Not', TRADUTOR_GATEWAY)."
						&nbsp;&nbsp;
						<input type='radio' name='rdCielo' value='s' $cielo> ".__('Yes', TRADUTOR_GATEWAY)."
						<p></p>
						<table>
							<tr>
								<td width='080'>".__('Key Store', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtCodigo'	id='txtCodigo' Style='width:450px;' onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPgtoCielo[codigo]'></td>
							</tr>
							<tr>
								<td>".__('Key Cielo', TRADUTOR_GATEWAY).":</td>
								<td><input type='text' name='txtChave' 	id='txtChave' Style='width:450px;' 	onkeypress='mascaraCampo(this)' maxlength='200' value='$configuracaoPgtoCielo[chave]'></td>
							</tr>
						</table>

						<table>
							<tr>
								<td width='130'>
									".__('Using Installment?', TRADUTOR_GATEWAY)."
								</td>
								<td width='10'>
									<input type='radio' name='rdCieloParcelamento' value='n' checked>
								</td>
								<td width='40'>
									".__('Not', TRADUTOR_GATEWAY)."
								</td>
								<td width='10'>
									<input type='radio' name='rdCieloParcelamento' value='s' $cieloParcelamento[s]>
								</td>
								<td width='40'>
									".__('Yes', TRADUTOR_GATEWAY)."
								</td>
								<td>
									".__('Quantity Plots', TRADUTOR_GATEWAY).":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								</td>
								<td>
									<select name='slcParcelas'>
										<option value='0'  $cieloParcelas[0]>0</option>
										<option value='1'  $cieloParcelas[1]>1</option>
										<option value='2'  $cieloParcelas[2]>2</option>
										<option value='3'  $cieloParcelas[3]>3</option>
										<option value='4'  $cieloParcelas[4]>4</option>
										<option value='5'  $cieloParcelas[5]>5</option>
										<option value='6'  $cieloParcelas[6]>6</option>
										<option value='7'  $cieloParcelas[7]>7</option>
										<option value='8'  $cieloParcelas[8]>8</option>
										<option value='9'  $cieloParcelas[9]>9</option>
										<option value='10' $cieloParcelas[10]>10</option>
										<option value='11' $cieloParcelas[11]>11</option>
										<option value='12' $cieloParcelas[12]>12</option>
									</select>
								</td>
							</tr>
						</table>

						<table border='0'>
							<tr>
								<td width='130'>
									".__('Apply Interest?', TRADUTOR_GATEWAY)."
								</td>
								<td width='10'>
									<input type='radio' name='rdCieloAplicaJuros' value='n' checked>
								</td>
								<td width='40'>
									".__('Not', TRADUTOR_GATEWAY)."
								</td>
								<td width='10'>
									<input type='radio' name='rdCieloAplicaJuros' value='s' $cieloAplicaJuros[s]>
								</td>
								<td width='40'>
									".__('Yes', TRADUTOR_GATEWAY)."
								</td>
								<td width='110'>
									".__('Interest rate', TRADUTOR_GATEWAY)." (%):
								</td>
								<td width='55'>
									<input type='text' name='txtTaxaJuros' Style='width:40px' value='$configuracaoCieloParcelamento[taxajuros]'>
								</td>
								<td>
									".__('Apply after Portion', TRADUTOR_GATEWAY).":&nbsp;&nbsp;
								</td>
								<td>
									<select name='slcParcelasJuros'>
										<option value='0'  $cieloJurosParcelaInicial[0]>0</option>
										<option value='1'  $cieloJurosParcelaInicial[1]>1</option>
										<option value='2'  $cieloJurosParcelaInicial[2]>2</option>
										<option value='3'  $cieloJurosParcelaInicial[3]>3</option>
										<option value='4'  $cieloJurosParcelaInicial[4]>4</option>
										<option value='5'  $cieloJurosParcelaInicial[5]>5</option>
										<option value='6'  $cieloJurosParcelaInicial[6]>6</option>
										<option value='7'  $cieloJurosParcelaInicial[7]>7</option>
										<option value='8'  $cieloJurosParcelaInicial[8]>8</option>
										<option value='9'  $cieloJurosParcelaInicial[9]>9</option>
										<option value='10' $cieloJurosParcelaInicial[10]>10</option>
										<option value='11' $cieloJurosParcelaInicial[11]>11</option>
										<option value='12' $cieloJurosParcelaInicial[12]>12</option>
									</select>
								</td>

							</tr>
						</table>

					</form>";
		echo $texto;
	}

	function gravaCielo(){
		global $wpdb;
		global $configuracaoPgtoCielo;
		global $configuracaoCieloParcelamento;

		$configuracaoPgtoCielo['cielo']  = $_POST['rdCielo'];
		$configuracaoPgtoCielo['codigo'] = $_POST['txtCodigo'];
		$configuracaoPgtoCielo['chave']  = $_POST['txtChave'];

		$configuracaoCieloParcelamento['parcelar']  		= $_POST['rdCieloParcelamento'];
		$configuracaoCieloParcelamento['parcelas']  		= $_POST['slcParcelas'];
		$configuracaoCieloParcelamento['aplicarjuros']  	= $_POST['rdCieloAplicaJuros'];
		$configuracaoCieloParcelamento['taxajuros']  		= $_POST['txtTaxaJuros'];
		$configuracaoCieloParcelamento['parcelainicial']  	= $_POST['slcParcelasJuros'];

		$parcelar 			= $_POST['rdCieloParcelamento'];
		$parcelas 			= $_POST['slcParcelas'];
		$aplicaJuros 		= $_POST['rdCieloAplicaJuros'];
		$taxaJuros 			= $_POST['txtTaxaJuros'];
		$parcelaInicialJuro = $_POST['slcParcelasJuros'];

		$boleto = (int) $wpdb->get_var("select option_id from wp_options where option_name = 'configuracao_cielo'");
		if($boleto == 0)
			$wpdb->query("insert into wp_options(option_name, option_value)values('configuracao_cielo', '".serialize($configuracaoPgtoCielo)."')");
		else
			$wpdb->query("update wp_options set option_value = '".serialize($configuracaoPgtoCielo)."' where option_name = 'configuracao_cielo'");

		$wpdb->query("delete from wp_gateway_regras_parcelamento");
		$wpdb->query("insert into wp_gateway_regras_parcelamento(Utilizar_Parcelamento,Parcelas,Aplicar_Juros,Taxa_Juros,parcela_Inicial_Juros)values('$parcelar','$parcelas','$aplicaJuros','$taxaJuros','$parcelaInicialJuro')");
	}
?>