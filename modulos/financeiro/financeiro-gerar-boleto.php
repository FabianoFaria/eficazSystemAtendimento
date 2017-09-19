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
			<form name='frmAux' id='frmAux' method='post'>
				<center style='float:right;width:100%'>
					<input type='button' name='btn-imprimir' class='esconde-campos-imp' value='Imprimir' onclick="$('.esconde-campos-imp').hide(); window.print(); $('.esconde-campos-imp').show();"/>

<?php
	$tituloID = $_GET['titulo-id'];
	$bancoBoleto='itau';


	if ($bancoBoleto=='itau'){
		$sql = "select fc.Conta_ID, ft.Codigo as Codigo, date_format(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, ft.Valor_Titulo as Valor_Titulo,
					e.Cadastro_ID as Empresa_ID, e.Nome as Empresa, e.Nome_Fantasia as EmpresaFantasia, e.Email as Email_Empresa, e.Cpf_Cnpj as Cpf_Cnpj_Empresa,
					c.Cadastro_ID as Cliente_ID, c.Nome as Cliente, c.Nome_Fantasia as ClienteFantasia, c.Cpf_Cnpj as Cpf_Cnpj_Cliente, cc.Dados
					from financeiro_contas fc
					inner join financeiro_titulos ft on ft.Conta_ID = fc.Conta_ID
					inner join cadastros_dados e on e.Cadastro_ID = fc.Cadastro_ID_de
					inner join cadastros_contas cc on cc.Cadastro_Conta_ID = fc.Cadastro_Conta_ID_de
					left join cadastros_dados c on c.Cadastro_ID = fc.Cadastro_ID_para
					where ft.Titulo_ID IN ($tituloID) and fc.Tipo_ID = 45";
		//echo $sql;
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$contaID = $rs['Conta_ID'];
			$dadosConta = unserialize($rs['Dados']);


			// DADOS DO BOLETO PARA O SEU CLIENTE
			$dias_de_prazo_para_pagamento = $dadosConta['prazo-maximo-pagamento'];
			//$taxa_boleto = 3.90;
			$taxa_boleto = formataValorBD($dadosConta['taxa-boleto']);
			//$data_venc = date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";

			$data_venc = date($dadosboleto["data_vencimento"], time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006";
			$valor_cobrado = str_replace(",", ".", $rs['Valor_Titulo']);
			$valor_boleto = number_format($valor_cobrado + $taxa_boleto, 2, ',', '');

			$dadosboleto["nosso_numero"] = $rs['Codigo'];  // Nosso numero - REGRA: Máximo de 8 caracteres!
			$dadosboleto["numero_documento"] = $rs['Codigo']; // $tituloID;	// Num do pedido ou nosso numero
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
				$dadosboleto["endereco1"] = $rs1['Logradouro']." n&ordm;".$rs1['Numero']." - ".$rs1['Bairro']." ".$rs1['Complemento'];
				$dadosboleto["endereco2"] = $rs1['Cidade']." - ".$rs1['UF']." - ".$rs1['CEP'];
			}

			// INFORMACOES PARA O CLIENTE
			$dadosboleto['texto-bloco-cabecalho'] = $dadosConta['texto-bloco-cabecalho'];
			$dadosboleto['texto-bloco-demonstrativo'] = $dadosConta['texto-bloco-demonstrativo'];
			$dadosboleto['texto-bloco-instrucoes'] = $dadosConta['texto-bloco-instrucoes'];

			// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
			$dadosboleto["quantidade"] = "";
			$dadosboleto["valor_unitario"] = "";
			$dadosboleto["aceite"] = "";
			$dadosboleto["especie"] = "R$";
			$dadosboleto["especie_doc"] = "";

			// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //


			// DADOS DA SUA CONTA - ITAÚ

			$dadosboleto["agencia"] = $dadosConta['agencia'];
			$dadosboleto["conta"] = $dadosConta['conta-corrente'];
			$dadosboleto["conta_dv"] = $dadosConta['digito'];

			// DADOS PERSONALIZADOS - ITAÚ
			$dadosboleto["carteira"] = $dadosConta['carteira'];

			// SEUS DADOS
			$dadosboleto["identificacao"] = $rs['Empresa'];
			$dadosboleto["cpf_cnpj"] = $rs['Cpf_Cnpj_Empresa'];

			$empresaID = $rs['Empresa_ID'];
			$resultado1 = mpress_query("select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia from cadastros_enderecos where Cadastro_ID = '$empresaID'");
			if($rs1 = mpress_fetch_array($resultado1)){
				$dadosboleto["endereco"] = $rs1['Logradouro']." n&ordm;".$rs1['Numero']." - ".$rs1['Bairro']." ".$rs1['Complemento'];
				$dadosboleto["cidade_uf"] = $rs1['Cidade']." - ".$rs1['UF']." - ".$rs1['CEP'];
			}
			$dadosboleto["cedente"] = $rs['Empresa'];

			ob_start();
			//ob_end_clean();
			include("../../includes/boletos/include/funcoes_itau.php");
			include("../../includes/boletos/include/layout_itau.php");
			$conteudo = ob_get_contents();
		}
	}
	$nomeArquivo = $dadosboleto["linha_digitavel"].".html";
	$f = fopen("../../uploads/".$nomeArquivo, "w");
	$conteudo = str_replace("'",'"',$conteudo);
	fwrite($f, $conteudo);
	fclose($f);

	$sql = "INSERT INTO modulos_anexos
						(Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Complemento, Nome_Arquivo, Observacao, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
				VALUES ('', 					'$contaID', 		'financeiro', $tituloID, '$nomeArquivo', '$conteudo', '$nomeArquivo', 1, '$dataHoraAtual', '".$dadosUserLogin[userID]."')";
	$resultado = mpress_query($sql);

	$sql = "	INSERT INTO modulos_anexos (Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Observacao, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
									SELECT 	distinct      '', Chave_Estrangeira, Tabela_Estrangeira, '$nomeArquivo', '$conteudo', '$nomeArquivo', 				1, '$dataHoraAtual', '".$dadosUserLogin[userID]."'
										from financeiro_produtos where Conta_ID = '$contaID' and Situacao_ID = 1";
	$resultado = mpress_query($sql);
?>
				</center>
					<!--
					</div>
				</div>
				-->
			</form>
		</body>
	</html>