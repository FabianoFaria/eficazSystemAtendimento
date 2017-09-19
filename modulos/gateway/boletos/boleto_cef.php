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
$valor_cobrado = $valorPedido; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

for($len=strlen($idPedido);$len<9;$len++)
	$nossoNumeroComp .= "0";

// Composição Nosso Numero - CEF SIGCB
$dadosboleto["nosso_numero1"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const1"] = "2"; //constanto 1 , 1=registrada , 2=sem registro
$dadosboleto["nosso_numero2"] = "000"; // tamanho 3
$dadosboleto["nosso_numero_const2"] = "4"; //constanto 2 , 4=emitido pelo proprio cliente
$dadosboleto["nosso_numero3"] = "$nossoNumeroComp$idPedido"; // tamanho 9


$dadosboleto["numero_documento"] = "10$idPedido";	// Num do pedido ou do documento
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
$dadosboleto["especie_doc"] = "";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
/*
$rs = mysql_query("select meta_value from wp_postmeta where post_id = '".$_SESSION[idRepresentante]."' and meta_key = 'representantes_pagamento'");
if($row = mysql_fetch_array($rs))
	$configuracaoBoleto = unserialize(unserialize($row['meta_value']));
$configuracaoBoleto = $configuracaoBoleto[boleto];
*/
// DADOS DA SUA CONTA - CEF
$dadosboleto["agencia"]  = $configuracaoBoleto[agencia];// Num da agencia, sem digito
$dadosboleto["conta"]    = $configuracaoBoleto[conta];	// Num da conta, sem digito
$dadosboleto["conta_dv"] = $configuracaoBoleto[digito]; // Digito do Num da conta

// DADOS PERSONALIZADOS - CEF
$dadosboleto["conta_cedente"] = substr($configuracaoBoleto[cedente], 0, 6); // Código Cedente do Cliente, com 6 digitos (Somente Números)
$dadosboleto["carteira"] = "SR";  // Código da Carteira: pode ser SR (Sem Registro) ou CR (Com Registro) - (Confirmar com gerente qual usar)

// SEUS DADOS
$dadosboleto["identificacao"] = utf8_decode($configuracaoBoleto[razao]);
$dadosboleto["cpf_cnpj"]  = $configuracaoBoleto[cnpj];
$dadosboleto["endereco"]  = "";
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"]   = utf8_decode($configuracaoBoleto[razao]);

// NÃO ALTERAR!
include("include/funcoes_cef_sigcb.php");
include("include/layout_cef.php");
?>
