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
		$nomeCliente 		= ("$row[2]");
		$enderecoCliente	= ("$row[7], $row[8] $row[9]");
		$enderecoComp 		= ("$row[10] - $row[11] - $row[12] - $row[6]");
		$valorPedido 		= $row[1];
	}
	$idPedido  	 = $_GET['pid'];

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


	$rs = mpress_query("select option_value from wp_options where option_name= 'configuracao_boleto'");
	if($row = mpress_fetch_array($rs))
		$dadosBoletoPgto = unserialize($row[0]);
	$configuracaoBoleto['diasvcto']	= $dadosBoletoPgto[diasvcto];


	$descontoBoleto = $dadosBoletoPgto['desconto'];
	if($descontoBoleto >= 0.01)
		$valorPedido = number_format($valorPedido-($valorPedido*($descontoBoleto/100)), 2, '.', '');


// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = $configuracaoBoleto['diasvcto'];
$taxa_boleto = 0;
$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));

if($_GET['data'] != "")
	$data_venc = $_GET['data'];

$valor_cobrado = $valorPedido; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

$dadosboleto["nosso_numero"] = "10$idPedido";  // Nosso numero sem o DV - REGRA: Máximo de 7 caracteres!
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
$dadosboleto["demonstrativo3"] = "";

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

$dadosboleto["codigo_cliente"] = $configuracaoBoleto[conta]; // Código do Cliente (PSK) (Somente 7 digitos)
$dadosboleto["ponto_venda"] = $configuracaoBoleto[agencia]; // Ponto de Venda = Agencia
$dadosboleto["carteira"] = "102";  // Cobrança Simples - SEM Registro
$dadosboleto["carteira_descricao"] = "COBRANÇA SIMPLES - CSR";  // Descrição da Carteira

$dadosboleto["codigo_cedente"] = str_replace('-','',$configuracaoBoleto[cedente]); // Código do Cedente (Somente 7 digitos)
$dadosboleto["carteira"] = "102";  // Código da Carteira
$dadosboleto["quantidade"] = 1;

// SEUS DADOS
$dadosboleto["identificacao"] = ($configuracaoBoleto[razao]);
$dadosboleto["cpf_cnpj"]  = $configuracaoBoleto[cnpj];
$dadosboleto["endereco"]  = "";
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"]   = ($configuracaoBoleto[razao]);

// NÃO ALTERAR!
include("include/funcoes_santander_banespa.php");
include("include/layout_santander_banespa.php");
?>
