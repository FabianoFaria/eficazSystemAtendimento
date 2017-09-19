<?php
include("functions.php");
relatorioPropostaExecucao();
	
function relatorioPropostaExecucao(){
	global $caminhoSistema;
	$workFlowID = $_POST['workflow-id'];

	header("Content-type: application/vnd.ms-excel");
	header("Content-type: application/force-download");
	header("Content-Disposition: attachment; filename=Orcamento_-_".date('Ymd')."_".$workFlowID.".xls");
	header("Pragma: no-cache");

	$txtNaoInfo = "N&atilde;o Informado";
	$sql = "Select cw.Solicitante_ID, sol.Nome as Nome_Solicitante, sol.Nome_Fantasia as Nome_Fantasia_Solicitante, sol.Codigo as Codigo_Solicitante, sol.Email as Email_Solicitante, sol.Cpf_Cnpj as Cpf_Cnpj_Sol, pre.Cpf_Cnpj  as Cpf_Cnpj_Pre,
				   cw.Prestador_ID, pre.Nome as Nome_Prestador, pre.Nome_Fantasia as Nome_Fantasia_Prestador, pre.Codigo as Codigo_Prestador, pre.Email as Email_Prestador,
				cw.Codigo as Codigo_Chamado, cw.Tipo_Workflow_ID, DATE_FORMAT(cw.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, cw.Usuario_Cadastro_ID,	min(cf.Follow_ID), cf.Descricao as Descricao_Follow, tw.Descr_Tipo as Tipo_Chamado,
				ts.Descr_Tipo as Situacao_Chamado, tp.Descr_Tipo as Prioridade, DATE_FORMAT(cw.Data_Abertura, '%d/%m/%Y %H:%i') as Data_Abertura
				from chamados_workflows cw
				left join cadastros_dados sol on sol.Cadastro_ID = Solicitante_ID
				left join cadastros_dados pre on pre.Cadastro_ID = -1
				left join tipo tw on tw.Tipo_ID = cw.Tipo_Workflow_ID
				left join chamados_follows cf on cf.Workflow_ID =  cw.Workflow_ID
				left join tipo ts on ts.Tipo_ID = cf.Situacao_ID
				left join tipo tp on tp.Tipo_ID = cw.Prioridade_ID
				where cw.Workflow_ID = '$workFlowID'";
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){
		$codigoChamado = $rs[Codigo_Chamado];
		$tipoChamado = $rs[Tipo_Chamado];
		$prioridade = $rs[Prioridade];
		$situacaoChamado = $rs[Situacao_Chamado];
		$dataChamado = $rs[Data_Cadastro];
		$dataAbertura = $rs[Data_Abertura];
		$codigoSolicitante = preencheTextoSeVazio($txtNaoInfo,$rs[Codigo_Solicitante]);
		$nomeSolicitante = $rs[Nome_Solicitante];
		$cpfCnpjSolicitante = $rs[Cpf_Cnpj_Sol];
		$cpfCnpjPrestador = $rs[Cpf_Cnpj_Pre];
		$emailTelefone = preencheTextoSeVazio($txtNaoInfo,$rs[Email_Prestador]);
		$nomeFantasiaPrestador = $rs[Nome_Fantasia_Prestador];
		$emailTelefoneSolicitante = preencheTextoSeVazio($txtNaoInfo,$rs[Email_Solicitante]);
		$descricaoFollow = $rs[Descricao_Follow];
	}
	$styleTitulo = "font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC; text-align:center;";
	$styleDestaque = "font-family:arial;font-size:12px;solid #ccc; font-weight:bold; background-color: #f2f2f2;";
	$styleNormal = "font-family:arial;font-size:12px;solid #fff;";

	/*
	$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 7");
	if($row = mysql_fetch_array($resultado)){
		$dadosEnvio = unserialize($row[descr_tipo]);
		$emailEmpresa = $dadosEnvio[email_envio];
	}
	*/

	echo "
	<!--
	794.07874 px 	- 2 cm 76 px = 718
	logo 210 por 53
	-->
	<table style='border:1px solid silver;' cellpadding='4' cellspacing='0' width='718' border='0'>
		<tr>
			<td colspan='3' style='$styleTitulo'>PROPOSTA PARA EXECU&Ccedil;&Atilde;O DE SERVI&Ccedil;OS</td>
			<td colspan='3' rowspan='3' valign='top' align='right' width='210'><img src='$caminhoSistema/images/topo/logo.png';?></td>
		</tr>
		<tr>
			<td colspan='1' style='$styleDestaque' width='20px'>&nbsp;</td>
			<td colspan='1' style='$styleDestaque' width='170px'>C&oacute;digo ".$_SESSION['objeto']."</td>
			<td colspan='1' style='$styleDestaque' width='458px'>Tipo</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
			<td colspan='1' style='$styleNormal' align='left'>$codigoChamado</td>
			<td colspan='4' style='$styleNormal' align='left'>$tipoChamado</td>
		</tr>
		<tr>
			<td colspan='1' style='$styleDestaque' width='20px'>&nbsp;</td>
			<td colspan='1' style='$styleDestaque' align='left'>Data da Abertura</td>
			<td colspan='1' style='$styleDestaque' align='left'>&nbsp;</td>
			<td colspan='3' style='$styleDestaque' align='left'>Prioridade</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
			<td colspan='1' style='$styleNormal' align='left'>$dataAbertura</td>
			<td colspan='1' style='$styleNormal' align='left'>&nbsp;</td>
			<td colspan='3' style='$styleNormal' align='left'>$prioridade</td>
		</tr>
		<tr>
			<td colspan='6' style='$styleTitulo'>PRESTADOR</td>
		</tr>
		<tr>
			<td colspan='1' style='$styleDestaque'>&nbsp;</td>
			<td colspan='1' style='$styleDestaque'>&nbsp;</td>
			<td colspan='1' style='$styleDestaque'>Nome</td>
			<td colspan='3' style='$styleDestaque'>CNPJ</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
			<td colspan='1'>&nbsp;</td>
			<td colspan='1' style='$styleNormal'>$nomeFantasiaPrestador</td>
			<td colspan='3' style='$styleNormal'>$cpfCnpjPrestador</td>
		</tr>
		<tr>
			<td colspan='6' style='$styleTitulo'>SOLICITANTE</td>
		</tr>
		<tr>
			<td colspan='1' style='$styleDestaque'>&nbsp;</td>
			<td colspan='1' style='$styleDestaque'>C&oacute;digo Solicitante</td>
			<td colspan='1' style='$styleDestaque'>Nome</td>
			<td colspan='3' style='$styleDestaque'>CNPJ</td>
		</tr>
		<tr>
			<td colspan='1'>&nbsp;</td>
			<td colspan='1' style='$styleNormal' align='left'>$codigoSolicitante</td>
			<td colspan='1' style='$styleNormal' align='left'>$nomeSolicitante</td>
			<td colspan='3' style='$styleNormal' align='left'>$cpfCnpjSolicitante</td>
		</tr>
		<tr>
			<td colspan='1' style='$styleDestaque'>&nbsp;</td>
			<td colspan='5' style='$styleDestaque'>Contatos</td>
		</tr>
		<tr>
			<td colspan='1' style='$styleNormal'>&nbsp;</td>
			<td colspan='5' style='$styleNormal'>$emailTelefoneSolicitante</td>
		</tr>";

		$linhaAtual = 13;

		$sql = "select Tipo_ID, upper(Descr_Tipo) as Descr_Tipo from tipo where Tipo_Grupo_ID = 13";
		$query = mpress_query($sql);
		while($tipos = mysql_fetch_array($query)){

			$sql = "select Workflow_Produto_ID, pv.Produto_Variacao_ID as Produto_Variacao_ID, cwp.Cobranca_Cliente as Cobranca_Cliente, cwp.Pagamento_Prestador as Pagamento_Prestador,
						DATE_FORMAT(cwp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,''))  as Descricao_Produto, cd.Nome as Nome,
						pv.Codigo as Codigo, Quantidade as Quantidade, Valor_Venda_Unitario, Valor_Custo_Unitario
					from chamados_workflows_produtos cwp
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
					inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
					inner join cadastros_dados cd on cd.Cadastro_ID = cwp.Usuario_Cadastro_ID
						where Workflow_ID = '$workFlowID' and cwp.Situacao_ID = 1
						and pd.Tipo_Produto = '".$tipos[Tipo_ID]."'
					order by cwp.Data_Cadastro desc";
			$i=0;
			$resultado = mpress_query($sql);
			/*Tipo_Produto*/
			$i=0;
			$valorProdutoTot = number_format(0, 2, ',', '.');
			while($rs = mysql_fetch_array($resultado)){
				$i++;
				if ($i==1){
					$linhaAtual = $linhaAtual + 2;
					$inicioSoma = $linhaAtual + 1;
					echo "
						<tr>
							<td colspan='6' style='$styleTitulo'>".$tipos[Descr_Tipo]."S</td>
						</tr>
						<tr>
							<td colspan='1' style='$styleDestaque'>Nº</td>
							<td colspan='2' style='$styleDestaque'>Decri&ccedil;&atilde;o</td>
							<td colspan='1' style='$styleDestaque text-align:right;'>Qtde.</td>
							<td colspan='1' style='$styleDestaque text-align:right;'>Valor Un.</td>
							<td colspan='1' style='$styleDestaque text-align:right;'>Total R$</td>
						</tr>";
				}

				$quantidade = number_format($rs[Quantidade]);
				$valorUnit = number_format($rs[Valor_Venda_Unitario], 2, ',', '.');
				$valorProduto = number_format(($rs[Quantidade] * $rs[Valor_Venda_Unitario]), 2, ',', '.');
				$valorProdutoTot = ($valorProdutoTot + ($rs[Quantidade] * $rs[Valor_Venda_Unitario]));

				$totalGeral = ($totalGeral + ($rs[Quantidade] * $rs[Valor_Venda_Unitario]));

				$linhaAtual++;
				echo "	<tr>
							<td colspan='1' style='$styleNormal' valign='top'>".$i."</td>
							<td colspan='2' style='$styleNormal' valign='top'>".$rs[Descricao_Produto]."</td>
							<td colspan='1' style='$styleNormal text-align:right;' valign='top' width='50px'>".$quantidade."</td>
							<td colspan='1' style='$styleNormal text-align:right;' valign='top' width='60px'>".$valorUnit."</td>
							<td colspan='1' style='$styleNormal text-align:right;' valign='top' width='100px'>".$valorProduto."</td>
						</tr>";
			}
			if ($i>0){
				$fimSoma = $linhaAtual;
				$linhaAtual++;
				/* HTML */
				/*
				echo "	<tr>
							<td colspan='1' style='$styleDestaque'>&nbsp;</td>
							<td colspan='3' style='$styleDestaque'>TOTAL ".$tipos[Descr_Tipo]."S</td>
							<td colspan='1' style='$styleDestaque text-align:right;'>R$</td>
							<td colspan='1' style='$styleDestaque text-align:right;' width='100px'>".number_format($valorProdutoTot, 2, ',', '.')."</td>
						</tr>";
				*/
				/* EXCEL */

				echo "	<tr>
							<td colspan='4' style='$styleDestaque'>TOTAL ".$tipos[Descr_Tipo]."S</td>
							<td colspan='1' style='$styleDestaque text-align:right;'>R$</td>
							<td colspan='1' style='$styleDestaque text-align:right;' width='100px'>=SOMA(F$inicioSoma:F$fimSoma)</td>
						</tr>";
				$formulas .= "$mais SOMA(F$inicioSoma:F$fimSoma)";
				$mais = " + ";
			}
		}

		/* HTML */
		/*
		echo "	<tr>
					<td colspan='4' style='$styleTitulo'>TOTAL GERAL</td>
					<td colspan='1' style='$styleTitulo text-align:right;'>R$</td>
					<td colspan='1' style='$styleTitulo text-align:right;' width='100px'>".number_format($totalGeral, 2, ',', '.')."</td>
				</tr>";
		*/
		/* EXCEL */
		echo "	<tr>
					<td colspan='4' style='$styleTitulo'>TOTAL GERAL</td>
					<td colspan='1' style='$styleTitulo text-align:right;'>R$</td>
					<td colspan='1' style='$styleTitulo text-align:right;' width='100px'>=".$formulas."</td>
				</tr>";



		echo "
				<tr>
					<td colspan='6'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='6' style='$styleDestaque'>OBSERVA&Ccedil;&Otilde;ES GERAIS</td>
				</tr>
				<tr>
					<td colspan='6' style='$styleNormal' height='50px'></td>
				</tr>
				<tr>
					<td colspan='1' style='$styleDestaque'>&nbsp;</td>
					<td colspan='1' style='$styleDestaque'>&nbsp;</td>
					<td colspan='1' style='$styleDestaque'>GARANTIA DO SERVI&Ccedil;O</td>
					<td colspan='3' style='$styleDestaque'>PRAZO DE ENTREGA</td>
				</tr>
				<tr>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='3' style='$styleNormal'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='1' style='$styleDestaque'>&nbsp;</td>
					<td colspan='1' style='$styleDestaque'>&nbsp;</td>
					<td colspan='1' style='$styleDestaque'>VALIDADE DA PROPOSTA</td>
					<td colspan='3' style='$styleDestaque'>CONDI&Ccedil;&Atilde;O DE PAGAMENTO</td>
				</tr>
				<tr>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='1' style='$styleNormal'>&nbsp;</td>
					<td colspan='3' style='$styleNormal'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='6' style='$styleNormal'>&nbsp</td>
				</tr>
				<tr>
					<td colspan='6' style='$styleDestaque'>&nbsp;</td>
				</tr>
				<tr>
					<td colspan='2' style='$styleDestaque'>&nbsp;</td>
					<td colspan='4' style='$styleDestaque'>___________________________</td>
				</tr>
				<tr>
					<td colspan='2' style='$styleDestaque'>&nbsp;</td>
					<td colspan='4' style='$styleDestaque'>$nomeFantasiaPrestador</td>
				</tr>
	</table>";
}
	
	
?>
