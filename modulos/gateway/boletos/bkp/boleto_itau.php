<?php
	session_start();
	require_once("../../../config.php");

	$resultado = mpress_query("select wp.Workflow_ID, wp.Valor_Venda_Unitario,cd.Nome, cd.Cpf_Cnpj, cd.Email,
							  ce.Cadastro_ID, ce.Cep, ce.Logradouro, ce.Numero, ce.Complemento, ce.Bairro, ce.Cidade, ce.UF
							  from telemarketing_workflows_produtos wp
							  inner join telemarketing_workflows w on w.Workflow_ID = wp.Workflow_ID
							  inner join cadastros_dados cd on cd.Cadastro_ID = w.Solicitante_ID
							  inner join cadastros_enderecos ce on ce.Cadastro_ID = cd.Cadastro_ID and tipo_endereco_id = 26
							  where wp.Workflow_ID = ".$_GET['pid']);
	if($row = mpress_fetch_array($resultado)){
		$nomeCliente 		= utf8_decode("$row[2]");
		$enderecoCliente	= utf8_decode("$row[7], $row[8] $row[9]");
		$enderecoComp 		= utf8_decode("$row[10] - $row[11] - $row[12] - $row[6]");
		$valorPedido 		= $row[1];
	}
	$idPedido  	 = $row[5].$_GET['pid'];


	$rs = mpress_query("select meta_value from wp_postmeta where post_id = '".$_GET['representante']."' and meta_key = 'representantes_pagamento'");
	if($row = mpress_fetch_array($rs))
		$configuracaoBoleto = unserialize(unserialize($row['meta_value']));
	$configuracaoBoleto = $configuracaoBoleto[boleto];

	if($configuracaoBoleto[posvcto] == 's'){
		$msgInstrucao1 = "- Receber at&eacute; ".$configuracaoBoleto[prazomaximo]." dias ap&oacute;s o vencimento";
		if($configuracaoBoleto[multa] >= 1){
			$msgInstrucao2 =  "- Sr. Caixa, cobrar multa de ".$configuracaoBoleto[multa]."% ap&oacute;s o vencimento";
			$msgInstrucao3 = "- Juros de ".$configuracaoBoleto[juros]."% por dia de atraso";
		}else{
			$msgInstrucao2 = "";
			$msgInstrucao3 = "";
		}
		$msgInstrucao4 = "- Em caso de d&uacute;vidas entre em contato conosco: $emailContato";
	}else{
		$msgInstrucao1 = "";
		$msgInstrucao2 = "- Sr. Caixa, n&atilde;o Receber ap&oacute;s o vencimento.";
		$msgInstrucao3 = "";
		$msgInstrucao4 = "- Em caso de d&uacute;vidas entre em contato conosco: $emailContato";
	}

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = $configuracaoBoleto['diasvcto'];
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));
$valor_cobrado = $valorPedido; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = "10$idPedido";  // Nosso numero - REGRA: Máximo de 8 caracteres!
$dadosboleto["numero_documento"] = "10$idPedido";	// Num do pedido ou nosso numero
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = htmlentities($nomeCliente);
$dadosboleto["endereco1"] = htmlentities($enderecoCliente);
$dadosboleto["endereco2"] = htmlentities($enderecoComp);

// INFORMACOES PARA O CLIENTE
//$dadosboleto["demonstrativo1"] = "- Pagamento de Compra ".get_bloginfo('name');
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = $msgInstrucao4;

$dadosboleto["instrucoes1"] = $msgInstrucao1;
$dadosboleto["instrucoes2"] = $msgInstrucao2;
$dadosboleto["instrucoes3"] = $msgInstrucao3;
$dadosboleto["instrucoes4"] = $msgInstrucao4;

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - ITAÚ
$dadosboleto["agencia"]  = $configuracaoBoleto[agencia];// Num da agencia, sem digito
$dadosboleto["conta"]    = $configuracaoBoleto[conta];	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $configuracaoBoleto[digito]; // Digito do Num da conta

// DADOS PERSONALIZADOS - ITAÚ
$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

// SEUS DADOS
$dadosboleto["identificacao"] = htmlentities(utf8_decode($configuracaoBoleto[razao]));
$dadosboleto["cpf_cnpj"]  = $configuracaoBoleto[cnpj];
$dadosboleto["endereco"]  = "";
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"]   = htmlentities(utf8_decode($configuracaoBoleto[razao]));

// NÃO ALTERAR!
include("include/funcoes_itau.php");
?>

<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.0 Transitional//EN'>
<HTML>
<HEAD>
<TITLE>Boleto Itau</TITLE>
<META http-equiv=Content-Type content=text/html charset=UTF-8>
<meta name="Generator" content="Projeto BoletoPHP - www.boletophp.com.br - Licen&ccedil;a GPL" />
<style type=text/css>
<!--.cp {  font: bold 10px Arial; color: black}
<!--.ti {  font: 9px Arial, Helvetica, sans-serif}
<!--.ld { font: bold 15px Arial; color: #000000}
<!--.ct { FONT: 9px "Arial Narrow"; COLOR: #000033}
<!--.cn { FONT: 9px Arial; COLOR: black }
<!--.bc { font: bold 20px Arial; color: #000000 }
<!--.ld2 { font: bold 12px Arial; color: #000000 }
--></style>
</head>

<BODY text=#000000 bgColor=#ffffff topMargin=0 rightMargin=0>

  <div class="campo" Style='margin-top:10px'>
  	<IMG src="imagens/logoitau.jpg" width="150" height="40" border=0>
  </div>

<p Style='margin-top:5px;'></p>
<span class="ld2">
</p>&nbsp;&nbsp;&nbsp;&nbsp;Representante: &nbsp;<?php echo $dadosboleto["identificacao"]." - ".$dadosboleto["cpf_cnpj"]?></p>
</p>&nbsp;&nbsp;&nbsp;&nbsp;Linha Digit&aacute;vel: &nbsp;<?php echo $dadosboleto["linha_digitavel"]?></p>
</p>&nbsp;&nbsp;&nbsp;&nbsp;Valor: &nbsp;&nbsp;R$ <?php echo $dadosboleto["valor_boleto"]?></p>
</span>

<table width=666 cellspacing=0 cellpadding=0 border=0><tr><td valign=top class=cp><DIV ALIGN="left" Style='font-size:15px;margin-top:10px;'>Instru&ccedil;&otilde;es
de Impress&atilde;o</DIV></TD></TR><TR><TD valign=top class=cp><DIV ALIGN="left">
<p Style='margin-top:10px;width:100%;'>
<li Style='height:20px;font-size:12px;width:100%;'>Imprima em impressora jato de tinta (ink jet) ou laser em qualidade normal ou alta (N&atilde;o use modo econ&ocirc;mico).<br>
<li Style='height:20px;font-size:12px;width:100%;'>Utilize folha A4 (210 x 297 mm) ou Carta (216 x 279 mm) e margens m&iacute;nimas &agrave; esquerda e &agrave; direita do formul&aacute;rio.<br>
<li Style='height:20px;font-size:12px;width:100%;'>Corte na linha indicada. N&atilde;o rasure, risque, fure ou dobre a regi&atilde;o onde se encontra o c&oacute;digo de barras.<br>
<li Style='height:20px;font-size:12px;width:100%;'>Caso n&atilde;o apare&ccedil;a o c&oacute;digo de barras no final, clique em F5 para atualizar a tela.
<li Style='height:20px;font-size:12px;width:900px;'>Caso tenha problemas ao imprimir, copie a seq&uuml;encia num&eacute;rica e pague no caixa eletr&ocirc;nico ou no internet banking:<br><br>
</p>