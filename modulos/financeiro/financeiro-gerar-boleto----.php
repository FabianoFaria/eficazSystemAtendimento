<?php
session_start();
header("Cache-Control: no-cache");
header("Expires: -1");
header("Pragma: no-cache");
header("Content-Type: text/html; charset=ISO-8859-1",true);
include("functions.php");
global $caminhoFisico, $modulosAtivos, $modulosGeral;
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml'>
	<head>
<?php get_header();?>
	</head>
		<body>
			<form name='frmAux' id='frmAux' method='post' class='iframe-interno'>
				<div class='titulo-container' id='' style='width:99.8%;'>
					<div class='titulo'>
						<p><input type='button' name='btn-imprimir' style='float:right;' value='Imprimir' onclick="$('.titulo').hide(); window.print(); $('.titulo').show(); "/></p>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-solicitante'>
						<center style='float:right;width:100%'>
<?php
	$tituloID = $_GET['titulo-id'];
	$bancoBoleto='itau';
	if ($bancoBoleto=='itau'){
		$sql = "select ft.Codigo as Codigo, date_format(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, ft.Valor_Titulo as Valor_Titulo,
					e.Cadastro_ID as Empresa_ID, e.Nome as Empresa, e.Nome_Fantasia as EmpresaFantasia, e.Email as Email_Empresa, e.Cpf_Cnpj as Cpf_Cnpj_Empresa,
					c.Cadastro_ID as Cliente_ID, c.Nome as Cliente, c.Nome_Fantasia as ClienteFantasia, c.Cpf_Cnpj as Cpf_Cnpj_Cliente
					from financeiro_contas fc
					inner join financeiro_titulos ft on ft.Conta_ID = fc.Conta_ID
					inner join cadastros_dados e on e.Cadastro_ID = fc.Cadastro_ID_de
					inner join cadastros_dados c on c.Cadastro_ID = fc.Cadastro_ID_para
					where ft.Titulo_ID IN ($tituloID) and fc.Tipo_ID = 45";

		//echo $sql;
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$empresaID = $rs[""];
			// DADOS DO BOLETO PARA O SEU CLIENTE
			$dias_de_prazo_para_pagamento = 5;
			$taxa_boleto = 3.90;
			//$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";

			$data_venc = date($dadosboleto["data_vencimento"], time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
			$valor_cobrado = str_replace(",", ".", $rs['Valor_Titulo']);
			$valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

			$dadosboleto["nosso_numero"] = $rs['Codigo'];  // Nosso numero - REGRA: Máximo de 8 caracteres!
			$dadosboleto["numero_documento"] = $tituloID;	// Num do pedido ou nosso numero
			$dadosboleto["data_vencimento"] = $rs['Data_Vencimento']; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
			$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
			$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
			$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

			// DADOS DO SEU CLIENTE
			$clienteID = $rs['Cliente_ID'];
			$cliente = $rs['Cliente'];
			if ($cliente==""){
				$cliente = $rs['ClienteFantasia']." ".$rs['Cpf_Cnpj_Cliente'];
			}
			$dadosboleto["sacado"] = $cliente;
			$resultado1 = mpress_query("select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia from cadastros_enderecos where Cadastro_ID = '$clienteID'");
			if($rs1 = mpress_fetch_array($resultado1)){
				$dadosboleto["endereco1"] = $rs1['Logradouro']." N&ordm;".$rs1['Numero']." - ".$rs1['Bairro']." ".$rs1['Complemento'];
				$dadosboleto["endereco2"] = $rs1['Cidade']." - ".$rs1['UF']." - ".$rs1['CEP'];
			}

			// INFORMACOES PARA O CLIENTE
			$dadosboleto["demonstrativo1"] = "Pagamento de Compra - ".$rs['Empresa'];
			//$dadosboleto["demonstrativo2"] = "Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
			//$dadosboleto["demonstrativo3"] = "BoletoPhp - http://www.boletophp.com.br";
			$dadosboleto["instrucoes1"] = "- Sr. Caixa, cobrar multa de 2% após o vencimento";
			$dadosboleto["instrucoes2"] = "- Receber até ".$dias_de_prazo_para_pagamento." dias após o vencimento";
			$dadosboleto["instrucoes3"] = "- Em caso de dúvidas entre em contato conosco: ".$rs['Email_Empresa'];
			//$dadosboleto["instrucoes4"] = "&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE

			$dadosboleto["quantidade"] = "";
			$dadosboleto["valor_unitario"] = "";
			$dadosboleto["aceite"] = "";
			$dadosboleto["especie"] = "R$";
			$dadosboleto["especie_doc"] = "";

			// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


			// DADOS DA SUA CONTA - ITAÚ
			$dadosboleto["agencia"] = "0655"; // Num da agencia, sem digito
			$dadosboleto["conta"] = "94603";	// Num da conta, sem digito
			$dadosboleto["conta_dv"] = "6"; 	// Digito do Num da conta

			// DADOS PERSONALIZADOS - ITAÚ
			$dadosboleto["carteira"] = "175";  // Código da Carteira: pode ser 175, 174, 104, 109, 178, ou 157

			// SEUS DADOS
			$dadosboleto["identificacao"] = $rs['Empresa'];
			$dadosboleto["cpf_cnpj"] = $rs['Cpf_Cnpj_Empresa'];

			$empresaID = $rs['Empresa_ID'];
			$resultado1 = mpress_query("select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia from cadastros_enderecos where Cadastro_ID = '$empresaID'");
			if($rs1 = mpress_fetch_array($resultado1)){
				$dadosboleto["endereco"] = $rs1['Logradouro']." N&ordm;".$rs1['Numero']." - ".$rs1['Bairro']." ".$rs1['Complemento'];
				$dadosboleto["cidade_uf"] = $rs1['Cidade']." - ".$rs1['UF']." - ".$rs1['CEP'];
			}
			$dadosboleto["cedente"] = $rs['Empresa']." ".$rs['Cpf_Cnpj_Empresa'];

			include("../../includes/boletos/include/funcoes_itau.php");
			include("../../includes/boletos/include/layout_itau.php");
		}
	}
?>
						</center>
					</div>
				</div>
			</form>
		</body>
	</html>