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
		$msgInstrucao1 = "- Receber até ".$configuracaoBoleto[prazomaximo]." dias após o vencimento";
		if($configuracaoBoleto[multa] >= 1){
			$msgInstrucao2 =  "- Sr. Caixa, cobrar multa de ".$configuracaoBoleto[multa]."% após o vencimento";
			$msgInstrucao3 = "- Juros de ".$configuracaoBoleto[juros]."% por dia de atraso";
		}else{
			$msgInstrucao2 = "";
			$msgInstrucao3 = "";
		}
		$msgInstrucao4 = "- Em caso de dúvidas entre em contato conosco: $emailContato";
	}else{
		$msgInstrucao1 = "";
		$msgInstrucao2 = "- Sr. Caixa, n&atilde;o Receber ap&oacute;s o vencimento.";
		$msgInstrucao3 = "";
		$msgInstrucao4 = "- Em caso de dúvidas entre em contato conosco: $emailContato";
	}

// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = $configuracaoBoleto['diasvcto'];
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));

$data_venc = $_GET['data'];

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
$dadosboleto["sacado"] = $nomeCliente;
$dadosboleto["endereco1"] = $enderecoCliente;
$dadosboleto["endereco2"] = $enderecoComp;

// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "";
$dadosboleto["demonstrativo2"] = "";
$dadosboleto["demonstrativo3"] = $msgInstrucao4;

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"] = $msgInstrucao1;
$dadosboleto["instrucoes2"] = $msgInstrucao2;
$dadosboleto["instrucoes3"] = $msgInstrucao3;
$dadosboleto["instrucoes4"] = $msgInstrucao4;

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = "";
$dadosboleto["valor_unitario"] = "";
$dadosboleto["aceite"] = "";
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


// DADOS DA SUA CONTA - BANCO DO BRASIL

$dadosboleto["agencia"]  = $configuracaoBoleto[agencia];// Num da agencia, sem digito
$dadosboleto["conta"]    = $configuracaoBoleto[conta];	// Num da conta, sem digito

// DADOS PERSONALIZADOS - BANCO DO BRASIL
$dadosboleto["convenio"] = "7777777";  // Num do convênio - REGRA: 6 ou 7 ou 8 dígitos
$dadosboleto["contrato"] = "999999"; // Num do seu contrato
$dadosboleto["carteira"] = "18";
$dadosboleto["variacao_carteira"] = "-019";  // Variação da Carteira, com traço (opcional)

// TIPO DO BOLETO
$dadosboleto["formatacao_convenio"] = "7"; // REGRA: 8 p/ Convênio c/ 8 dígitos, 7 p/ Convênio c/ 7 dígitos, ou 6 se Convênio c/ 6 dígitos
$dadosboleto["formatacao_nosso_numero"] = "2"; // REGRA: Usado apenas p/ Convênio c/ 6 dígitos: informe 1 se for NossoNúmero de até 5 dígitos ou 2 para opção de até 17 dígitos


// SEUS DADOS
$dadosboleto["identificacao"] = utf8_decode($configuracaoBoleto[razao]);
$dadosboleto["cpf_cnpj"]  = $configuracaoBoleto[cnpj];
$dadosboleto["endereco"]  = "";
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"]   = utf8_decode($configuracaoBoleto[razao]);


// NÃO ALTERAR!
	include("include/funcoes_bb.php");
	include("include/layout_bb.php");
?>
