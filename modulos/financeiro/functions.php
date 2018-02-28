<?php
	error_reporting(E_ERROR);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");


	global $configFinanceiro;
	$configFinanceiro = carregarConfiguracoesGeraisModulos('financeiro');

	function carregarRelatorioEntradasSaidas(){

		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-centro-custo']); $i++){
			$centrosCustos .= $virgula.$_POST['localiza-centro-custo'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
			$cadastrosDe .= $virgula.$_POST['localiza-cadastro-de'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-situacao-conta']); $i++){
			$situacoes .= $virgula.$_POST['localiza-situacao-conta'][$i];
			$virgula = ",";
		}

		$tipo = $_POST['tipo-operacao'];
		if ($cadastrosDe!=""){ $sqlCond .= " and cd.Cadastro_ID IN($cadastrosDe)";}
		if ($centrosCustos!=""){ $sqlCond .= " and cd.Centro_Custo_ID IN($centrosCustos)";}
		if ($situacoes != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID IN ($situacoes)";}

		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}

		$sql = "SELECT coalesce(tc.Descr_Tipo, 'Nao Informado') as Tipo_Conta, sum(ft.Valor_Pago) as Valor_Pago, sum(ft.Valor_Titulo) as Valor_Titulo
									from financeiro_contas fc
									inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
									inner join cadastros_dados cd on fc.Cadastro_ID_de = cd.Cadastro_ID
									left join tipo tc on tc.Tipo_ID = fc.Tipo_Conta_ID
									where fc.Tipo_ID in ($tipo)
									$sqlCond
									group by fc.Tipo_Conta_ID, tc.Descr_Tipo";
		//echo $sql;

		$resultado = mpress_query($sql);
		$i = 0;
		echo "<table width='99%' Style='border:0px;' cellpadding='2' cellspacing='2' align='center'>";
		while($rs = mpress_fetch_array($resultado)){
			$total = $total + $rs[Valor_Pago];
			if($i%2==0){echo "<tr>";}
			echo "<td class='tabela-fundo-claro' stylenormal align='left' width='25%'>".utf8_encode($rs[Tipo_Conta])."</td><td class='tabela-fundo-claro' stylenormal align='right' width='25%'>".number_format($rs[Valor_Pago], 2, ',', '.')."</td>";
			if(($i+1)%2==0){echo "</tr>";}
			$i++;
		}
		echo "</table>";
		//echo "R$ ".$total;
	}


	function carregarVencimentos(){
		global $caminhoSistema, $configCob;
		$contaID 			= $_POST['localiza-conta-id'];
		$tituloID 			= $_POST['localiza-titulo-id'];
		$situacaoTitulos 	= $_POST['situacao-titulos'];


		echo "<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' border='0' cellpadding='0' cellspacing='0' align='center'>
				<tr height='30px'>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:8%'><b>T&iacute;tulo ID</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:10%'><b>C&oacute;digo T&iacute;tulo</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:15%'><b>Forma Pagamento</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos'>&nbsp;</td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:10%' align='center'><b>Data Vencimento</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:05%'>&nbsp;</td>
						<td class='titulo-secundario coluna-titulo-vencimentos'>&nbsp;</td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:12%'><b>Valor Vencimento</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:15%'><b>Situa&ccedil;&atilde;o</b></td>
						<td class='titulo-secundario coluna-titulo-vencimentos'>&nbsp;</td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:11%'><p class='info-pagamento esconde' align='center'><b>Data Pago</b></p></td>
						<td class='titulo-secundario coluna-titulo-vencimentos' style='width:11%'><p class='info-pagamento esconde'><b>Valor Pago</b></p></td>
				</tr>";
		if ($situacaoTitulos=='pendente'){
			$valorTotal 		= str_replace(",",".",str_replace(".","",$_POST['valor-total']));
			$qtdeParcelas 		= $_POST['qtde-parcelas'];
			$titulo 			= $_POST['titulo-lancamento'];
			$dataVencimento 	= retornaDataHora('d','d/m/Y');
			$dataPago 			= $dataVencimento;

			$parcela 			= ($valorTotal / $qtdeParcelas);
			$parcelaAux 		= number_format(($valorTotal / $qtdeParcelas), 2, '.', '');
			$situacaoVencimento = "48";

			if ($_POST['tipo-pgto']=="v"){
				if ($_POST['aux-forma-pagamento']!=""){$formaPagamento = $_POST['aux-forma-pagamento'];}
				if ($_POST['aux-data-vencimento']!=""){$dataVencimento = $_POST['aux-data-vencimento'];}
				if ($_POST['aux-data-pago']!=""){$dataPago = $_POST['aux-data-pago'];}
				if ($_POST['aux-situacao-vencimento']!=""){ $situacaoVencimento = $_POST['aux-situacao-vencimento'];}
			}
			else{

			}

			for ($i = 1; $i <= $qtdeParcelas; $i++){
				$popularFormaPagamento = "";
				$popularSituacaoVencimento = "";
				$popularDataVencimento = "";
				if (($i == 1)&&($qtdeParcelas>1)){
					$popularFormaPagamento = "<div class='btn-disseminar' title='Replicar' style='float:left;' tipo-grupo='forma-pagamento'>&nbsp;</div>";
					$popularSituacaoVencimento = "<div class='btn-disseminar' title='Replicar' style='float:left;' tipo-grupo='situacao-vencimento'>&nbsp;</div>";
					$popularDataVencimento = "<div class='btn-disseminar' title='Replicar' style='float:left;' tipo-grupo='data-vencimento'>&nbsp;</div>";
				}
				$totalParcelas += $parcelaAux;
				if (($qtdeParcelas>1)&&($i==$qtdeParcelas)){
					if ($totalParcelas > $valorTotal)
						$valorParcelaAjuste = ($totalParcelas - $valorTotal);
					if ($totalParcelas < $valorTotal)
						$valorParcelaAjuste = ($totalParcelas - $valorTotal);
					$parcela = $parcela - $valorParcelaAjuste;
				}

				if ($titulo!=""){$tituloParcela = $titulo.$i;}
				echo "	<tr height='30px'>
							<td class='titulo-secundario'>&nbsp;&nbsp;-&nbsp;-&nbsp;-&nbsp <input type='hidden' name='titulo-vencimento[]' id='titulo-vencimento-$i' style='width:75%;' class='titulo-vencimento formata-numero' maxlength='10' value=''/></td>
							<td class='titulo-secundario'><input type='text' name='codigo-vencimento[]' id='codigo-vencimento-$i' style='width:90%;' class='codigo-vencimento' maxlength='20' value=''/></td>
							<td class='titulo-secundario'><select name='forma-pagamento[]' id='forma-pagamento-$i' class='forma-pagamento' style='display:none; width:75%;float:left;'>".utf8_encode(optionValueGrupo(25, $formaPagamento))."</select></td>
							<td class='titulo-secundario'>$popularFormaPagamento</td>
							<td class='titulo-secundario'><input type='text' name='data-vencimento[]' id='data-vencimento-$i' style='width:95%;' class='data-vencimento formata-data required' value='$dataVencimento'/></td>
							<td class='titulo-secundario'>$popularDataVencimento</td>
							<td class='titulo-secundario'>$situacao</td>
							<td class='titulo-secundario'><input type='text' name='valor-vencimento[]' id='valor-vencimento-$i' style='width:75%;' class='valor-vencimento formata-valor' value='".number_format($parcela, 2, ',', '.')."'/></td>
							<td class='titulo-secundario'><select name='situacao-vencimento[]' id='situacao-vencimento-$i' class='situacao-vencimento' situacao-pagamento-id='' ordem='$i' style='width:75%;float:left;'>".utf8_encode(optionValueGrupo(29, $situacaoVencimento))."</select></td>
							<td class='titulo-secundario'>$popularSituacaoVencimento</td>
							<td class='titulo-secundario pago-$i esconde'><input type='text' name='data-pago[]' id='data-pago-$i' class='data-pago formata-data' maxlength='10' style='width:90%;' value='$dataPago'/></td>
							<td class='titulo-secundario pago-$i esconde'><input type='text' name='valor-pago[]' id='valor-pago-$i' class='valor-pago formata-valor' style='width:75%;' value='".number_format($parcela, 2, ',', '.')."'/></td>
						</tr>";

				$dataVencimento = somarData($dataVencimento, 0, 1, 0);
			}
		}
		else{
			/************************************************/
			/*	BUSCAR INFORMACOES DE COBRANCA DE JUROS     */
			/************************************************/
			$sql = "SELECT Cobranca_ID, Conta_ID, Titulo_ID, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID
						FROM financeiro_cobranca
						WHERE Situacao_ID = 1
						AND Conta_ID = '$contaID' order by Cobranca_ID DESC LIMIT 1";
			$resultado = mpress_query($sql);
			if ($rs = mpress_fetch_array($resultado)){
				$configCob = unserialize($rs['Dados']);
			}

			/************************************************/

			$sql = "SELECT Titulo_ID, Forma_Pagamento_ID, coalesce(tfp.Descr_Tipo,'Não Informado') as Forma_Pagamento, coalesce(tsp.Descr_Tipo,'Não Informado') as Situacao_Pagamento, ft.Codigo, Valor_Titulo,
						DATE_FORMAT(Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, DATE_FORMAT(Data_Vencimento, '%Y-%m-%d') as Data_Vencimento_Orig, Valor_Pago, DATE_FORMAT(Data_Pago, '%d/%m/%Y') as Data_Pago, Situacao_Pagamento_ID,
						DATE_FORMAT(ft.Data_Alteracao, '%d/%m/%Y') as Data_Alteracao, ft.Usuario_Alteracao_ID, DATE_FORMAT(ft.Data_Cadastro, '%d/%m/%Y') as Data_Cadastro, ft.Usuario_Cadastro_ID,
						IF (tsp.Tipo_ID = 48, DATEDIFF('".retornaDataHora('d','Y-m-d')."',DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d')),'') as DiasAtraso, cda.Nome as Usuario_Alteracao, fc.Tipo_ID
						from financeiro_titulos ft
						inner join financeiro_contas fc on fc.Conta_ID = ft.Conta_ID
						left join tipo tfp on tfp.Tipo_ID = Forma_Pagamento_ID
						left join tipo tsp on tsp.Tipo_ID = Situacao_Pagamento_ID
						left join cadastros_dados cda on cda.Cadastro_ID = ft.Usuario_Alteracao_ID
						where fc.Conta_ID = $contaID";
			//echo $sql;
			$i=0;
			$resultado = mpress_query($sql);
			//echo count($resultado);
			while($rs = mpress_fetch_array($resultado)){
				if ($tituloID==""){
					$colunaVencimentos = "coluna-vencimentos";
				}
				else{
					if ($tituloID==$rs[Titulo_ID]){$colunaVencimentos='coluna-vencimentos-destaque';}
					else{$colunaVencimentos = "coluna-vencimentos esconde";}
				}

				$i++;
				$codigoTitulo 				= $rs['Codigo'];
				$tituloVencimentoID 		= $rs['Titulo_ID'];
				$dataVencimento 			= $rs['Data_Vencimento'];
				$dataPago 					= $rs['Data_Pago'];
				$valorPago 					= number_format($rs['Valor_Pago'], 2, ',', '.');
				$parcela 					= number_format($rs['Valor_Titulo'], 2, ',', '.');
				$situacaoPagamentoID 		= $rs['Situacao_Pagamento_ID'];
				$situacaoPagamento 			= $rs['Situacao_Pagamento'];
				$formaPagamentoID 			= $rs['Forma_Pagamento_ID'];
				$formaPagamento 			= $rs['Forma_Pagamento'];
				$tipoID 					= $rs['Tipo_ID'];

				$campoFechado 				= "esconde";
				$campoAberto		 		= "";
				if ($rs['Situacao_Pagamento_ID']=="49"){
					$campoFechado 		= "";
					$campoAberto 		= "esconde";
				}
				else{
					$campoFechado 		= "esconde";
					$campoAberto 		= "";
					$valorPago 			= $parcela;
				}
				$boletoGerar = "";
				$descricaoAcaoBoleto = "Gerar";
				if (($formaPagamentoID=='47') && ($tipoID=='45')){
					$sql = "select Anexo_ID from modulos_anexos where Tabela_Estrangeira = 'financeiro' and Chave_Estrangeira = '$contaID' and Complemento = '".$rs[Titulo_ID]."'";
					$resultado1 = mpress_query($sql);
					if($rs1 = mpress_fetch_array($resultado1)){
						$descricaoAcaoBoleto = "Re-Gerar";
					}

					$boletoGerar = "<input type='button' value='".$descricaoAcaoBoleto."' class='gerar-boleto' id='boleto-gerar-$i' titulo-id='".$rs['Titulo_ID']."' Style='margin-widht:auto; height:20px;font-size:9px;margin-top:5px;'/>";
					//$boletoGerar = "<input type='checkbox' name='boleto-gerar[".$rs[Titulo_ID]."]' id='boleto-gerar-".$rs[Titulo_ID]."' class='gerar-boleto' titulo-id='".$rs['Titulo_ID']."' Style='margin-widht:auto; height:20px;font-size:11px;margin-top:1px;'/>";
				}

				$textoRegistradoPor = "Registrado pelo usu&aacute;rio ".utf8_encode($rs['Usuario_Alteracao'])." no dia ".$rs['Data_Alteracao'];
				$blocoJuros = "";
				if ($rs['Situacao_Pagamento_ID']=="48"){
					if ($rs['DiasAtraso']>0){
						$situacao = "<p class='mini-bola-vermelha' conta-id='".$rs['Conta_ID']."' titulo-id='".$rs['Titulo_ID']."' Style='' title='$rs[DiasAtraso] dia(s) em atraso'>&nbsp;</p>";

						/***********************************************************************************************/
						/* INICIO - TRECHO RESPONSAVEL POR REALIZAR CALCULOS DE JUROS PARA COBRANÇA DE TITULO ATRASADO */
						/***********************************************************************************************/
						if(($configCob['aplicar-juros']=='checked')||($configCob['aplicar-cobranca-honorarios']=='checked')||($configCob['aplicar-correcao-monetaria']=='checked')){

							$valorCorrigido = $rs['Valor_Titulo'];

							/* MESES DE DIFERENCA ENTRE DATA ATUAL E DATA VENCIMENTO */
							$dt2 = date_create(retornaDataHora('d','Y-m-d'));
							$dt1 = date_create($rs['Data_Vencimento_Orig']);

							/*
							$intervalo = date_diff($dt1, $dt2);
							$meses = $intervalo->format('%y') * 12;
							$meses += $intervalo->format('%m');
							*/

							/* REGRAS DE JUROS */
							if($configCob['aplicar-juros']=='checked'){
								$valorCorrigido = $rs['Valor_Titulo'] + (($rs['Valor_Titulo'] * formataValorBD($configCob['percentual-juros-multa'])) / 100);
								/* JUROS SIMPLES */
								if (formataValorBD($configCob['percentual-juros-mensal-simples']) > 0){
									$valorCorrigido += ((($rs['Valor_Titulo'] * formataValorBD($configCob['percentual-juros-mensal-simples']))/100) * $meses);
								}
								/* JUROS COMPOSTOS */
								if (formataValorBD($configCob['percentual-juros-mensal-composto']) > 0){
									$jurosCompostos = 0;
									$valorTituloComposto = $rs['Valor_Titulo'];
									for($m = 1; $m<=$meses; $m++){
										$valorTituloComposto += (($valorTituloComposto * formataValorBD($configCob['percentual-juros-mensal-composto']))/100) ;
									}
									$jurosCompostos = $valorTituloComposto - $rs['Valor_Titulo'];
									$valorCorrigido += $jurosCompostos;
								}
							}

							/* REGRAS DE HONORÁRIOS */
							if($configCob['aplicar-cobranca-honorarios']=='checked'){
								if (formataValorBD($configCob['percentual-honorarios']) > 0){
									$valorCorrigido += (($rs['Valor_Titulo'] * formataValorBD($configCob['percentual-honorarios']))/100);
								}
							}

							/* REGRAS DE ATUALIZAÇÃO COM INPC - INDICE NACIONAL DE PREÇOS AO CONSUMIDOR */
							if($configCob['aplicar-correcao-monetaria']=='checked'){
								if ($configCob['indice-correcao'] == 'inpc'){
									$regrasFinais = '';
								}
							}

							$blocoJuros = "	<p style='color:red;'><br>
												<b>VALOR CORRIGIDO<br>
													<span style='font-size:15px;'>R$ ".number_format($valorCorrigido,2,",",".")." <input type='hidden' id='valor-corrigido-$i' value='".number_format($valorCorrigido,2,",",".")."'/></span>
												</b>
												<br><br>
											</p>";
						}
						/***********************************************************************************************/
						/* FIM - TRECHO RESPONSAVEL POR REALIZAR CALCULOS DE JUROS PARA COBRANÇA DE TITULO ATRASADO    */
						/***********************************************************************************************/

					}
					if ($rs[DiasAtraso]==0){$situacao = "<p class='mini-bola-amarela' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='' title='O Vencimento &eacute; hoje'>&nbsp;</p>";}
					if ($rs[DiasAtraso]<0){$situacao = "<p class='mini-bola-azul' Style='' title='T&iacute;tulo com vencimento para dia $rs[Data_Vencimento]'>&nbsp;</p>";}
				}
				if ($rs[Situacao_Pagamento_ID]=="49"){$situacao = "<p class='mini-bola-verde' Style='margin:0px;' title='T&iacute;tulo pago dia $rs[Data_Pago] $textoRegistradoPor'>&nbsp;</p>";}
				if ($rs[Situacao_Pagamento_ID]=="50"){$situacao = "<p class='mini-bola-cinza' Style='margin:0px;' title='Cancelado $textoRegistradoPor'>&nbsp;</p>";}

				echo "	<tr height='30px' class='$colunaVencimentos'>
							<td class='titulo-secundario' valign='top'>
								<input type='hidden' name='titulo-id[]' id='titulo-id-$i' value='$tituloVencimentoID'/>
								<input type='hidden' name='titulo-vencimento[]' id='titulo-vencimento-$i' value='$tituloVencimentoID' class='formata-numero'/>
								<b>$tituloVencimentoID</b>
							</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoAberto aberto-$i'><input type='text' name='codigo-vencimento[]' id='codigo-vencimento-$i' style='width:90%;' maxlength='20' value='$codigoTitulo'/></p>
								<b class='$campoFechado fechado-$i'>".$codigoTitulo."</b>
							</td>
							<td class='titulo-secundario' valign='top'>
								<p style='float:left;width:67%' class='$campoAberto aberto-$i'>
									<select name='forma-pagamento[]' id='forma-pagamento-$i' posicao='$i' class='forma-pagamento' style='display:none;width:75%;float:left;'>".utf8_encode(optionValueGrupo(25, $formaPagamentoID))."</select>
								</p>
								<p style='float:left;width:33%' class='$campoFechado fechado-$i'>
									<b class='$campoFechado fechado-$i'>".utf8_encode($formaPagamento)."</b>
								</p>
								<p style='float:left;width:20%'>$boletoGerar</p>
							</td>
							<td class='titulo-secundario'>&nbsp;</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoAberto aberto-$i'><input type='text' name='data-vencimento[]' id='data-vencimento-$i' style='width:95%;' class='data-vencimento formata-data' maxlength='10' value='$dataVencimento'/></p>
								<p align='center'><b class='$campoFechado fechado-$i'>".utf8_encode($dataVencimento)."</b></p>
							</td>
							<td class='titulo-secundario' valign='top' style='padding-top:8px;'>$situacao</td>
							<td class='titulo-secundario'>&nbsp;</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoAberto aberto-$i'><input type='text' name='valor-vencimento[]' id='valor-vencimento-$i' style='width:75%;' class='valor-vencimento formata-valor' value='$parcela'/></p>
								<b class='$campoFechado fechado-$i'>".$parcela."</b>
								".$blocoJuros."
							</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoAberto aberto-$i'><select name='situacao-vencimento[]' id='situacao-vencimento-$i' class='situacao-vencimento' situacao-pagamento-id='$situacaoPagamentoID' ordem='$i' style='width:75%;float:left;'>".utf8_encode(optionValueGrupo(29, $situacaoPagamentoID))."</select></p>
								<input type='hidden' name='situacao-vencimento-atual[]' id='situacao-vencimento-atual-$i' value='$situacaoPagamentoID'/>
								<b class='$campoFechado fechado-$i'>".utf8_encode($situacaoPagamento)."</b>
							</td>
							<td class='titulo-secundario'>&nbsp;</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoFechado aberto-$i pago-$i'><input type='text' name='data-pago[]' id='data-pago-$i' class='data-pago formata-data' style='width:90%;' maxlength='10' value='".$dataPago."'/></p>
								<p class='$campoFechado fechado-$i' align='center'><b>".$dataPago."</b></p>
							</td>
							<td class='titulo-secundario' valign='top'>
								<p class='$campoAberto aberto-$i pago-$i'><input type='text' name='valor-pago[]' id='valor-pago-$i' class='valor-pago formata-valor' style='width:75%;' value='$valorPago'/></p>
								<b class='$campoFechado fechado-$i'>R$ ".$valorPago."</b>
								<div class='btn-editar alterar-vencimento $campoFechado' ordem='$i' style='float:right;padding-right:20px;margin-top:2px' title='Alterar '>&nbsp;</div>
							</td>
						</tr>";
			}
		}
		echo "</table>";
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function carregarTitulos($contaID, $tituloID, $situacaoTitulos, $tipo){

		global $configFinanceiro;
		$escondeExcluir = "esconde";
		if ($contaID != ""){
			$sql = "SELECT Tipo_ID, 
						Tipo_Conta_ID, 
						Cadastro_ID_de, 
						Cadastro_ID_para, 
						Codigo, Valor_Total,
						(SELECT COUNT(*) FROM financeiro_titulos WHERE Conta_ID = $contaID) as Qtde_Parcelas,
						(SELECT COUNT(*) FROM financeiro_titulos ft2 WHERE ft2.Conta_ID = $contaID and ft2.Situacao_Pagamento_ID = 50) AS Qtde_Cancelados
					FROM financeiro_contas WHERE Conta_ID = $contaID";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$tipoContaID 			= $row[Tipo_Conta_ID];
				$codigo 				= $row[Codigo];
				$valorTotal 			= number_format($row[Valor_Total], 2, ',', '.');
				$qtdeParcelas 			= $row[Qtde_Parcelas];
				$escondeSalvarContinuar = "esconde";
				if ($situacaoTitulos!='pendente'){
					$readOnly 	= "readonly='readonly'";
					$esconde 	= "esconde";
					$disabled 	= "disabled";
				}else{
					$escondeInsert 		= "esconde";
					$escondeAtualizar 	= "esconde";
				}
				if (($qtdeParcelas>1)&&($tituloID!="")){$botaoVisualizarParcelas = "<input type='button' value='Visualizar Todas Parcelas' id='botao-visualizar-parcelas' style='height:18px;font-size:10px;margin-top:5px;width:135px'>";}
				if ($qtdeParcelas>1){$checkedTipoPgtoP = "checked='checked'";}else{$checkedTipoPgtoV = "checked='checked'"; $escondeQtdeParcelas = "esconde";}
				if ($row[Qtde_Cancelados]==$row[Qtde_Parcelas])
					$escondeExcluir = "";

			}
		}
		else{
			$escondeInsert 			= "esconde";
			$escondeQtdeParcelas 	= "esconde";
			$escondeAtualizar 		= "esconde";
			$escondeSalvarContinuar = "";
			$qtdeParcelas 			= 1;
			$checkedTipoPgtoV 		= "checked='checked'";

			$formaPagamento 		= "";

			/* FATURAMENTO DIRETO */
			if ($_GET['tipo']=='direto'){
				if ($_GET['modulo']=='chamados'){
					// ENTRADA
					if (is_array($_GET['check-fat-receber'])){
						foreach($_GET['check-fat-receber'] as $workflowProdutoID){
							$condWorkflowProdutoID .= $workflowProdutoID.",";
						}
					}
					// SAIDA
					if (is_array($_GET['check-fat-pagar'])){
						foreach($_GET['check-fat-pagar'] as $workflowProdutoID){
							$condWorkflowProdutoID .= $workflowProdutoID.",";
						}
					}
					$condWorkflowProdutoID = substr($condWorkflowProdutoID, 0, -1);
					$worflowID = $_GET['workflow-id'];
					$sql = "SELECT Pagamento_Prestador, Valor_Custo_Unitario, Cobranca_Cliente, Valor_Venda_Unitario
								FROM chamados_workflows_produtos WHERE Workflow_ID = '$worflowID' AND Workflow_Produto_ID IN ($condWorkflowProdutoID)";
					$resultado = mpress_query($sql);
					while ($rs = mpress_fetch_array($resultado)){
						$valorTotal += $rs['Valor_Venda_Unitario'];
					}
					$valorTotal = number_format($valorTotal, 2, ',', '.');
					$faturamentoDireto = "S";
				}

				if ($_GET['modulo']=='orcamentos'){
					// ENTRADA e SAIDA
					foreach($_GET['produto-faturar'] as $workflowProdutoID){
						$idsProdutos .= ",".$workflowProdutoID;
					}
					$idsProdutos = substr($idsProdutos,1);


					// ADICIONADO A FORMA DE PAGAMENTO DO PRODUTO CASO O PRODUTO SEJA PARA PAGAR FORNECEDOR
					$sql = "SELECT SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor_Total_Venda,
									SUM(opp.Quantidade * opp.Valor_Custo_Unitario) as Valor_Total_Custo,
									opp.Faturamento_Direto,
									op.Proposta_ID as Proposta_ID,
									opp.Pagamento_Prestador,
									opp.Prestador_Forma_Pagamento_ID
								FROM orcamentos_propostas_produtos opp
								INNER JOIN orcamentos_propostas op on op.Proposta_ID = opp.Proposta_ID
								WHERE opp.Proposta_Produto_ID in ($idsProdutos)
								GROUP BY opp.Faturamento_Direto,op.Proposta_ID,opp.Pagamento_Prestador,opp.Prestador_Forma_Pagamento_ID";

					//var_dump($sql);

					//echo "-->".$_GET['tipo-id'];
					/*
						DEVIDO a diferença da verção dos bancos de dados,
						será necessario adaptar esse trecho de código para compensar
						o seguinte erro de MYSQL
						"#1140 - In aggregated query without GROUP BY, expression #3 of SELECT list contains nonaggregated column 'eficazsystem3.opp.Faturamento_Direto'; this is incompatible with sql_mode=only_full_group_by"
					*/

					//echo "<p>Teste Teste  query: ".$sql."</p>";

					$resultado = mpress_query($sql);
					while ($rs = mpress_fetch_array($resultado)){

						if ($_GET['tipo-id']=='44'){
							$valorTotal += $rs['Valor_Total_Custo'];
						}
						else{
							if ($rs['Faturamento_Direto']==1){
								$valorTotal += ($rs['Valor_Total_Venda'] - $rs['Valor_Total_Custo']);
							}
							else{
								$valorTotal += $rs['Valor_Total_Venda'];
							}
						}
						$propostaID = $rs['Proposta_ID'];

						$formaPagamento = $rs['Prestador_Forma_Pagamento_ID'];
					}


					/*
						TRECHO PARA VALIDAR SE O PRODUTO SERÁ FATURAMENTO OU PAGAMENTO A FORNECEDOR, ENTÃO SERÁ APLICADO O MODIFICADOR DE PREÇO FINAL.
					*/

					if($_GET['tipo-id'] == '44'){

						/*
							CASO O PRODUTO SEJA PARA PAGAMENTO DE FORNECEDOR, SERÁ NECESSARIO ALTERAR PARA A FORMA DE PAGAMENTO ESCOLHIDA PARA O PRODUTO.
						*/

						if(isset($formaPagamento)){

							$sqlFomaPagamento = "SELECT Descr_Tipo as Forma_Cobranca,
												Tipo_Auxiliar as Descricao
											FROM tipo
											WHERE tipo.Tipo_ID = $formaPagamento";

						}else{

							$sqlFomaPagamento = "SELECT op.Proposta_ID,
											fc.Descr_Tipo as Forma_Cobranca,
											fc.Tipo_Auxiliar as Descricao
											FROM orcamentos_propostas op
											INNER JOIN tipo fc ON fc.Tipo_ID = op.Forma_Pagamento_ID
											WHERE Proposta_ID = '$propostaID'";
						}


						$formaPgmtRaw 	= mpress_query($sqlFomaPagamento);

						$rst 			= mpress_fetch_array($formaPgmtRaw);

						$descricao 		= unserialize($rst['Descricao']);


						//CORRECAO DE PREÇOS COM A MODIFICAÇÂO ESPECIFICA

						$valorModificar = ($valorTotal / 100) * $descricao['valor_modificado'];

						if($descricao['tipo-bonus-disponivel'] == 'Desconto'){

							$novoValor 		= $valorTotal - $valorModificar;

							$valorTotal 	= $novoValor;

						}else{

							$novoValor 		= $valorTotal + $valorModificar;

							$valorTotal 	= $novoValor;
						}

						// CALCULA A QUANTIDADE DE PARCELAS A SEREM COBRADAS.
						$valorTotal = number_format($valorTotal, 2, ',', '.');
						$faturamentoDireto = "S";

						$qtdeParcelas = $descricao['quantidade-parcelas'];

						// $sql = "SELECT count(*) as Quantidade FROM orcamentos_propostas_vencimentos WHERE Proposta_ID = '".$propostaID."' and Situacao_ID = 1";
						// $resultado = mpress_query($sql);


						// while ($rs = mpress_fetch_array($resultado)){
						// 	$qtdeParcelas = $rs['Quantidade'];
						// }

					}else{

						//CARREGA A FORMA DE PAGAMENTO DO ORÇAMENTO NORMALMENTE

						// COM O ID DA PROPOSTA, BUSCA A FORMA DE PAGAMENTO PARA ALTERAR O VALOR FINAL

						$sqlFomaPagamento = "SELECT op.Proposta_ID,
											fc.Descr_Tipo as Forma_Cobranca,
											fc.Tipo_Auxiliar as Descricao
											FROM orcamentos_propostas op
											INNER JOIN tipo fc ON fc.Tipo_ID = op.Forma_Pagamento_ID
											WHERE Proposta_ID = '$propostaID'";



						$formaPgmtRaw 	= mpress_query($sqlFomaPagamento);

						$rst 			= mpress_fetch_array($formaPgmtRaw);

						//echo "teste de forma pagamento ".$rst['Forma_Cobranca']; Descricao

						$descricao 		= unserialize($rst['Descricao']);

						//var_dump($descricao[''], ['valor_modificado']);
						//echo "Tipo desconto ".$descricao['tipo-bonus-disponivel']." desconto ".$descricao['valor_modificado'];

						$valorModificar = ($valorTotal / 100) * $descricao['valor_modificado'];

						if($descricao['tipo-bonus-disponivel'] == 'Desconto'){

							$novoValor 		= $valorTotal - $valorModificar;

							$valorTotal 	= $novoValor;

						}else{

							$novoValor 		= $valorTotal + $valorModificar;

							$valorTotal 	= $novoValor;
						}


						// CALCULA A QUANTIDADE DE PARCELAS A SEREM COBRADAS.
						$valorTotal 		= number_format($valorTotal, 2, ',', '.');
						$faturamentoDireto 	= "S";

						$sql = "SELECT count(*) as Quantidade FROM orcamentos_propostas_vencimentos WHERE Proposta_ID = '".$propostaID."' and Situacao_ID = 1";
						$resultado = mpress_query($sql);
						while ($rs = mpress_fetch_array($resultado)){
							$qtdeParcelas = $rs['Quantidade'];
						}

					}


				}
				if ($qtdeParcelas==0) {
					$qtdeParcelas = 1;
				}

				if ($qtdeParcelas>1){
					$checkedTipoPgtoP = "checked='checked'";
				}else{ 
					$checkedTipoPgtoV = "checked='checked'"; 
					$escondeQtdeParcelas = "esconde";
				}
			}
		}


		/************************************************/
		/*	BUSCAR INFORMACOES DE COBRANCA DE JUROS     */
		/************************************************/
		$sql = "SELECT Cobranca_ID, Conta_ID, Titulo_ID, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID
					FROM financeiro_cobranca
					WHERE Situacao_ID = 1
					AND Conta_ID = '$contaID' order by Cobranca_ID DESC LIMIT 1";
		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$configCob = unserialize($rs['Dados']);
		}
		$selIndiceCorrecao[$configCob['indice-correcao']] = "selected";
		/************************************************/

		if ($valorTotal=="") $valorTotal = "0,00";
		echo "	<div class='titulo-container conjunto1'>
					<div class='titulo'>
						<p>T&iacute;tulos</p>
						<!--
						<p class='contas-a-pagar esconde'>Conta a Pagar</p>
						<p class='contas-a-receber esconde'>Conta a Receber</p>
						<p class='transferencias esconde'>Transfer&ecirc;ncia</p>
						-->
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario'  style='float:left;width:100%;'>
							<div class='titulo-secundario'  style='float:left;width:18%;'>
								<p><b>Pagamento:</b></p>
								<p><input type='radio' class='tipo-pgto' name='tipo-pgto' id='tipo-pgto-v' value='v' $checkedTipoPgtoV $disabled/>
									<label for='tipo-pgto-v'>A vista</label>&nbsp;
									<input type='radio' class='tipo-pgto' name='tipo-pgto' id='tipo-pgto-p' value='p' $checkedTipoPgtoP $disabled/>
									<label for='tipo-pgto-p'>Parcelado</label> &nbsp;
								</p>
							</div>
							<div class='titulo-secundario'  style='float:left;width:16%;'>
								<p><b>Valor:</b></p>
								<p><input type='text' class='formata-valor required' id='valor-total' name='valor-total' style='width:90%;' value='$valorTotal' /></p>
							</div>
							<div class='titulo-secundario'  style='float:left;width:12.5%;'>
								<div class='$escondeQtdeParcelas div-numero-parcelas'>
									<p><b>N&ordm; Parcelas:</b><b class='$escondeInsert'>$qtdeParcelas</b></p>
									<p>$botaoVisualizarParcelas<input type='text' class='formata-numero $esconde' id='qtde-parcelas' name='qtde-parcelas' style='width:80%;text-align:center;' value='$qtdeParcelas' maxlength='2'/></p>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario contas-a-receber esconde'  style='float:left;width:53.5%;'>
								<div class='titulo-secundario contas-a-receber esconde'  style='float:left;width:100%;'>
									<div class='titulo-secundario'  style='float:left;width:50%;margin-top:12px;'>
										<p>
											<input type='checkbox' name='configCob[aplicar-juros]' id='aplicar-juros' value='checked' ".$configCob['aplicar-juros']."/>
											<b><label for='aplicar-juros'>Aplicar juros</label></b>
										</p>
										<div class='titulo-secundario aplicar-juros esconde' style='float:left;width:33%;'>
											<p>% Multa</p>
											<p><input type='text' name='configCob[percentual-juros-multa]' id='percentual-juros-multa' class='formata-valor zero-nao' maxlength='5' value='".$configCob['percentual-juros-multa']."' style='width:90%'/></p>
										</div>
										<div class='titulo-secundario aplicar-juros esconde' style='float:left;width:33%;'>
											<p>% Mensal Simples</p>
											<p><input type='text' name='configCob[percentual-juros-mensal-simples]' id='percentual-juros-mensal-simples' class='formata-valor zero-nao' maxlength='5' value='".$configCob['percentual-juros-mensal-simples']."' style='width:90%'/></p>
										</div>
										<div class='titulo-secundario aplicar-juros esconde' style='float:left;width:33%;'>
											<p>% Mensal Composto</p>
											<p><input type='text' name='configCob[percentual-juros-mensal-composto]' id='percentual-juros-mensal-composto' class='formata-valor zero-nao' maxlength='5' value='".$configCob['percentual-juros-mensal-composto']."' style='width:90%'/></p>
										</div>
									</div>
									<div class='titulo-secundario'  style='float:left;width:20%;margin-top:12px;'>
										<p>
											<input type='checkbox' name='configCob[aplicar-cobranca-honorarios]' id='aplicar-cobranca-honorarios' value='checked' ".$configCob['aplicar-cobranca-honorarios']."/>
											<b><label for='aplicar-cobranca-honorarios'>Honor&aacute;rios</label></b>
										</p>
										<div class='titulo-secundario aplicar-cobranca-honorarios esconde' style='float:left;width:100%;'>
											<p>% Honor&aacute;rios</p>
											<p><input type='text' name='configCob[percentual-honorarios]' id='percentual-honorarios' class='formata-valor zero-nao' maxlength='5' value='".$configCob['percentual-honorarios']."' style='width:90%'/></p>
										</div>
									</div>
									<div class='titulo-secundario'  style='float:left;width:30%;margin-top:12px;'>
										<p>
											<input type='checkbox' name='configCob[aplicar-correcao-monetaria]' id='aplicar-correcao-monetaria' value='checked' ".$configCob['aplicar-correcao-monetaria']."/>
											<b><label for='aplicar-correcao-monetaria'>Corre&ccedil;&atilde;o Monet&aacute;ria</label></b>
										</p>
										<div class='titulo-secundario aplicar-correcao-monetaria esconde' style='float:left;width:100%;'>
											<p>&Iacute;ndice de atualiza&ccedil;&atilde;o</p>
											<p>
												<select id='indice-correcao' name='configCob[indice-correcao]'>
													<option></option>
													<option value='inpc' ".$selIndiceCorrecao['inpc'].">INPC - &Iacute;ndice Nacional de Pre&ccedil;os ao Consumidor</option>
												</select>
											</p>
										</div>
									</div>
								</div>
							</div>

						</div>
						<p>&nbsp;</p>
						<div id='div-titulos'></div>
						<div id='div-retorno'></div>
						<p>
							<input type='button' value='Atualizar Conta' class='botao-atualizar-conta $escondeAtualizar' fluxo='' Style='float:right;height:24px;font-size:10px;margin-top:1px;'/>
							<input type='button' value='Excluir Conta' class='botao-excluir-conta $escondeExcluir' fluxo='' Style='float:right;height:24px;font-size:10px;margin-top:1px; margin-right:10px;'/>";
		if (($faturamentoDireto!="S") && ($configFinanceiro['lancamento-exibir-botao-continuar']=='1')){
			echo "			<input type='button' value='Salvar Lan&ccedil;amento e Continuar' id='botao-salvar-conta-continuar' name='botao-salvar-conta-continuar' class='botao-salvar-conta $esconde $escondeSalvarContinuar' fluxo='continuar' Style='float:right;height:24px;font-size:10px;margin-top:1px;'/>";
		}
		echo "				<input type='button' value='Salvar Lan&ccedil;amento' id='botao-salvar-conta' name='botao-salvar-conta' class='botao-salvar-conta $esconde' fluxo='sair' Style='float:right;height:24px;font-size:10px;margin-top:1px;'/>
						</p>
						<p>&nbsp;</p>
						<p>&nbsp;</p>
					</div>
				</div>
				<script type='text/javascript'>$('.formata-valor').maskMoney({allowNegative: false, thousands:'.', decimal:',', affixesStay: false});</script>";

	}

	function optionValueTipoConta($selecionado){
		global $caminhoSistema;
		$idGrupo = "28";
		$tipo = "(44,45)";
		//echo "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 27 and Tipo_ID IN $tipo and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo";
		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 27 and Tipo_ID IN $tipo and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		while($categoria1 = mpress_fetch_array($query)){
			if ($selecionado==$categoria1['Tipo_ID']) $selecionado1 = "selected"; else $selecionado1 = "";
			//$optionValue .= "<option value='".$categoria1['Tipo_ID']."' $selecionado1>".$categoria1['Descr_Tipo']."</option>";
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($categoria2 = mpress_fetch_array($query2)){
				if ($selecionado==$categoria2['Tipo_ID']) $selecionado2 = "selected"; else $selecionado2 = "";
				$optionValue .= "<option value='".$categoria2['Tipo_ID']."' $selecionado2>".$categoria2['Descr_Tipo']."</option>";
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria3 = mpress_fetch_array($query3)){
					if ($selecionado==$categoria3['Tipo_ID']) $selecionado3 = "selected"; else $selecionado3 = "";
					$optionValue .= "<option value='".$categoria3['Tipo_ID']."' $selecionado3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria3['Descr_Tipo']."</option>";
					$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria4 = mpress_fetch_array($query4)){
						if ($selecionado==$categoria4['Tipo_ID']) $selecionado4 = "selected"; else $selecionado4 = "";
						$optionValue .= "<option value='".$categoria4['Tipo_ID']."' $selecionado4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria4['Descr_Tipo']."</option>";
						$query5 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria4['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						while($categoria5 = mpress_fetch_array($query5)){
							if ($selecionado==$categoria5['Tipo_ID']) $selecionado5 = "selected"; else $selecionado5 = "";
							$optionValue .= "<option value='".$categoria5['Tipo_ID']."' $selecionado5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria5['Descr_Tipo']."</option>";
						}
					}
				}
			}
		}
		return $optionValue;
	}

	function carregarTipoConta($tipo, $selecionado){
		global $caminhoSistema;
		$idGrupo = "28";
		if($textoPrimeiro==""){$textoPrimeiro="Selecione";}
		$optionValue = "<option value=''>$textoPrimeiro</option>";
		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 27 and Tipo_ID = $tipo and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		//echo "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 27 and Tipo_ID = $tipo and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo";
		while($categoria1 = mpress_fetch_array($query)){
			if ($selecionado==$categoria1['Tipo_ID']) $selecionado1 = "selected"; else $selecionado1 = "";
			//$optionValue .= "<option value='".$categoria1['Tipo_ID']."' $selecionado1>".$categoria1['Descr_Tipo']."</option>";
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($categoria2 = mpress_fetch_array($query2)){
				if ($selecionado==$categoria2['Tipo_ID']) $selecionado2 = "selected"; else $selecionado2 = "";
				$optionValue .= "<option value='".$categoria2['Tipo_ID']."' $selecionado2>".$categoria2['Descr_Tipo']."</option>";
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria3 = mpress_fetch_array($query3)){
					if ($selecionado==$categoria3['Tipo_ID']) $selecionado3 = "selected"; else $selecionado3 = "";
					$optionValue .= "<option value='".$categoria3['Tipo_ID']."' $selecionado3>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria3['Descr_Tipo']."</option>";
					$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria4 = mpress_fetch_array($query4)){
						if ($selecionado==$categoria4['Tipo_ID']) $selecionado4 = "selected"; else $selecionado4 = "";
						$optionValue .= "<option value='".$categoria4['Tipo_ID']."' $selecionado4>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria4['Descr_Tipo']."</option>";
						$query5 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria4['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						while($categoria5 = mpress_fetch_array($query5)){
							if ($selecionado==$categoria5['Tipo_ID']) $selecionado5 = "selected"; else $selecionado5 = "";
							$optionValue .= "<option value='".$categoria5['Tipo_ID']."' $selecionado5>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria5['Descr_Tipo']."</option>";
						}
					}
				}
			}
		}
		return $optionValue;
	}


	/*
	if ($tipo == 44) $descr = 's:5:"saida";s:1:"1";'; if ($tipo == 45) $descr = 's:7:"entrada";s:1:"1";';
	echo "
		<select name='lancamento-tipo-conta' id='lancamento-tipo-conta' class='required'>".optionValueGrupoFilho(28, $selecionado,""," and ((coalesce(Tipo_Auxiliar,'')='') or (Tipo_Auxiliar like '%$descr%')) ")."</select>
		<script type='text/javascript'>
			$('select').chosen({width: '99%', no_results_text: 'Nenhum registro encontrado!', placeholder_text_single: ' ', placeholder_text_multiple: ' ', allow_single_deselect: true });
		</script>";
	*/


	function salvarConta(){

		global $dadosUserLogin, $configFinanceiro;

		$situacaoTitulos 	= $_POST['situacao-titulos'];

		$contaID 			= $_POST['localiza-conta-id'];
		$dataHoraAtual 		= "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$valorTotal 		= str_replace(",",".",str_replace(".","",$_POST['valor-total']));
		$qtdeParcelas 		= $_POST['qtde-parcelas'];
		$titulo 			= $_POST['titulo-lancamento'];
		$tipoID 			= $_POST['radio-tipo-grupo-27'];


		// $modulo 			= $_POST['modulo'];
		// $chaveEstrangeira  	= $_POST['chave-estrangeira'];

		if(isset($_POST['modulo'])){
			$modulo 			= $_POST['modulo'];
		}else{
			$modulo 			= '';
		}

		if(isset($_POST['chave-estrangeira'])){
			$chaveEstrangeira  	= $_POST['chave-estrangeira'];
		}else{
			$chaveEstrangeira  	= '0';
		}

		if($_POST['lancamento-tipo-conta'][0] != ''){
			$tipoContaID 	= $_POST['lancamento-tipo-conta'][0];
		}else{
			$tipoContaID 	= 0;
		}

		if($_POST['lancamento-centro-custo'][0] != ''){
			$centroCustoID 	= $_POST['lancamento-centro-custo'][0];
		}else{
			$centroCustoID 	= 0;
		}

		$observacao 		= utf8_decode($_POST['observacao']);

		$cadastroIDde 		= $_POST['cadastro-id-de'];
		$cadastroContaIDde 	= $_POST['cadastro-conta-id-de'];

		if(!empty($_POST['cadastro-id-para'])){
			$cadastroIDpara = $_POST['cadastro-id-para'];
		}else{
			$cadastroIDpara = 0;
		}

		/* SE TRANSFERENCIA */
		if ($tipoID==46){
			
			if($_POST['cadastro-id-para-transf'] != ''){
				$cadastroIDpara = $_POST['cadastro-id-para-transf'];
			}else{
				$cadastroIDpara = 0;
			}

			if($_POST['cadastro-conta-id-para-transf'] != ''){
				$cadastroContaIDpara 	= $_POST['cadastro-conta-id-para-transf'];
			}else{
				$cadastroContaIDpara 	= 0;
			}
		}else{
			$cadastroContaIDpara 		= 0;
			//$cadastroIDpara 			= 0;
		}

		if ($situacaoTitulos=='pendente'){
			if ($contaID!=""){
				//$tipoID = $_POST['hidden-tipo-transacao'];
				mpress_query("UPDATE financeiro_contas SET Tipo_ID = '$tipoID', Tipo_Conta_ID = '$tipoContaID', Centro_Custo_ID = '$centroCustoID', Cadastro_ID_para = '$cadastroIDpara', Valor_Total = '$valorTotal' WHERE Conta_ID = '$contaID'");
				mpress_query("DELETE FROM financeiro_titulos WHERE Conta_ID = '$contaID'");
			}
			else{
				$sql = "INSERT INTO financeiro_contas (Tipo_ID, 
					Tipo_Conta_ID, 
					Centro_Custo_ID, 
					Cadastro_ID_de, 
					Cadastro_ID_para, 
					Cadastro_Conta_ID_de, 
					Cadastro_Conta_ID_para, 
					Codigo, 
					Tabela_Estrangeira, 
					Chave_Estrangeira, 
					Valor_Total, 
					Observacao, 
					Data_Cadastro, 
					Usuario_Cadastro_ID)
					VALUES ('$tipoID', 
					'$tipoContaID', 
					'$centroCustoID', 
					'$cadastroIDde', 
					'$cadastroIDpara', 
					'$cadastroContaIDde', 
					'$cadastroContaIDpara', 
					'$titulo', 
					'$modulo',
					'$chaveEstrangeira', 
					'$valorTotal', 
					'$observacao', 
					$dataHoraAtual,
					'".$dadosUserLogin['userID']."')";

				// echo($sql);

				// die();

				mpress_query($sql);
				$contaID = mysql_insert_id();

				if ($_POST['faturamento-direto']=='S'){


					$i = 0;

					/*
						A Versão original efetuava a busca do produto através de informações no _POST, mas como não estava funcionando de acordo com o esperado, foi alterado para buscar os detalhes do produtos através de consulta ao BD.

					*/

					//foreach ($_POST['financeiro-produto-id'] as $financeiroProdutoID){
					foreach ($_POST['produto-faturar'] as $financeiroProdutoID){
						// $tabelaEstrangeira 			= $_POST['tabela-estrangeira'][$i];
						// $chaveEstrangeira 			= $_POST['chave-estrangeira'][$i];
						// $chaveEstrangeiraProduto 	= $_POST['chave-estrangeira-produto'][$i];
						// $produtoVariacaoID 			= $_POST['produto-variacao-id'][$i];
						// $descricaoProduto 			= $_POST['descricao-produto'][$i];
						// $quantidadeProduto 			= formataValorBD($_POST['quantidade-produto'][$i]);
						// $valorUnitarioProduto 		= formataValorBD($_POST['valor-unitario-produto'][$i]);
						// $produtoReferenciaID 		= $_POST['produto-referencia-id'][$i];

						$sqlProduct 				= "SELECT Produto_Variacao_ID,
														Descricao,
														Quantidade,
														Valor_Custo_Unitario,
														Valor_Venda_Unitario
														FROM orcamentos_propostas_produtos
														WHERE Proposta_Produto_ID = '$financeiroProdutoID'";

						// echo($sqlProduct);

						// die();

						$resultadoProduto = mpress_query($sqlProduct);

						if ($rs = mpress_fetch_array($resultadoProduto)){

							$produtoVariacao 	= $rs[Produto_Variacao_ID];
							$descricao 			= $rs[Descricao];
							$quantidade 		= $rs[Quantidade];
							$valorCusto 		= $rs[Valor_Custo_Unitario];
							$valorVenda 		= $rs[Valor_Venda_Unitario];
						}else{
							$produtoVariacao 	= 0;
							$descricao 			= 0;
							$quantidade 		= 0;
							$valorCusto 		= 0;
							$valorVenda 		= 0;
						}

						$tabelaEstrangeira 			= $modulo;
						$chaveEstrangeira 			= $chaveEstrangeira;
						$chaveEstrangeiraProduto 	= $financeiroProdutoID;

						$produtoVariacaoID 			= $produtoVariacao;
						$descricaoProduto 			= $descricao;
						// $quantidadeProduto 			= formataValorBD($quantidade);
						$quantidadeProduto 			= $quantidade;

						
						
						if($tipoID==44){

							//$valorUnitarioProduto 		= formataValorBD($valorCusto);
							$valorUnitarioProduto 		= $valorCusto;

						}elseif($tipoID==45){

							//$valorUnitarioProduto 		= formataValorBD($valorVenda);
							$valorUnitarioProduto 		= $valorVenda;
						}


						$produtoReferenciaID 		= 0;


						$sql 						= "INSERT INTO financeiro_produtos (Produto_Referencia_ID,
														Conta_ID, 
														Tabela_Estrangeira, 
														Chave_Estrangeira, 
														Situacao_ID, 
														Usuario_Cadastro_ID, 
														Data_Cadastro, 
														Produto_Variacao_ID, 
														Produto_Descricao, 
														Quantidade, 
														Valor_Unitario,
														Info_NFE)
														VALUES ('$chaveEstrangeiraProduto', 
														'$contaID', 
														'$tabelaEstrangeira', 
														'$chaveEstrangeira', 
														1,
														'".$dadosUserLogin['userID']."', 
														$dataHoraAtual, 
														'$produtoVariacaoID', 
														'$descricaoProduto', 
														'$quantidadeProduto', 
														'$valorUnitarioProduto', 
														'')";
						mpress_query($sql);
						$i++;

					}
				}

			}
			for ($i=0; $i < count($_POST["titulo-vencimento"]); $i++){
				//$tituloVencimento = $_POST['titulo-vencimento'][$i];
				$codigoVencimento 		= $_POST['codigo-vencimento'][$i];
				$dataVencimento 		= "'".implode('-',array_reverse(explode('/',$_POST['data-vencimento'][$i])))." 00:00'";
				$valorVencimento 		= str_replace(",",".",str_replace(".","",$_POST['valor-vencimento'][$i]));
				$formaPagamento 		= $_POST['forma-pagamento'][$i];
				$situacaoVencimento 	= $_POST['situacao-vencimento'][$i];
				if ($situacaoVencimento == "49"){
					$valorPago 			= formataValorBD($_POST['valor-pago'][$i]);
					$dataPago 			= "'".implode('-',array_reverse(explode('/',$_POST['data-pago'][$i])))." 00:00'";
				}
				else{
					$valorPago 			= "0"; $dataPago = "NULL";
				}
				if($formaPagamento=='') $formaPagamento = 0;
				$sql = "INSERT INTO financeiro_titulos (Conta_ID, Forma_Pagamento_ID, Codigo, Valor_Titulo, Data_Vencimento, Valor_Pago, Data_Pago, Situacao_Pagamento_ID, Observacao, Data_Cadastro, Data_Alteracao, Usuario_Cadastro_ID, Usuario_Alteracao_ID)
										   VALUES ('$contaID', '$formaPagamento', '$codigoVencimento', '$valorVencimento', $dataVencimento, '$valorPago', $dataPago, '$situacaoVencimento', '', $dataHoraAtual, $dataHoraAtual,'".$dadosUserLogin['userID']."','".$dadosUserLogin['userID']."')";
				mpress_query($sql);
				$tituloVencimento = mysql_insert_id();
				if ($tipoID==45){
					if ($codigoVencimento==""){
						$codigoVencimento 	= $tituloVencimento;
						$sql 				= "UPDATE financeiro_titulos SET Codigo = '$codigoVencimento' WHERE Titulo_ID = $tituloVencimento";
						mpress_query($sql);
					}
				}

				/**/
				if ($configFinanceiro['exibe-conta']){
					$sql = "update financeiro_movimentacoes set Situacao_ID = 2 where Titulo_ID = '$tituloID'";
					mpress_query($sql);
					if ($situacaoVencimento=="49"){
						if (($tipoID==44) || ($tipoID==46))
							$valorMovimentacao = $valorPago * -1;
						else
							$valorMovimentacao = $valorPago;
						$sql = "INSERT INTO financeiro_movimentacoes (Conta_ID, Titulo_ID, Cadastro_Conta_ID, Data_Movimentacao, Valor, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
															VALUES ('$contaID', '$tituloVencimento', '$cadastroContaIDde', $dataPago, '$valorMovimentacao', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
						mpress_query($sql);
						if ($tipoID==46){
							$sql = "INSERT INTO financeiro_movimentacoes (Conta_ID, Titulo_ID, Cadastro_Conta_ID, Data_Movimentacao, Valor, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
																VALUES ('$contaID', '$tituloVencimento', '$cadastroContaIDpara', $dataPago, '$valorPago', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
							mpress_query($sql);
						}
					}
				}
				/**/
			}
		}
		else{
			/* POSTERIORMENTE DESCONTINUAR O IF ACIMA */

			mpress_query("UPDATE financeiro_contas SET Tipo_ID = '$tipoID',
														Tipo_Conta_ID = '$tipoContaID',
														Centro_Custo_ID = '$centroCustoID',
														Cadastro_ID_de = '$cadastroIDde',
														Cadastro_ID_para = '$cadastroIDpara',
														Cadastro_Conta_ID_de = '$cadastroContaIDde',
														Cadastro_Conta_ID_para = '$cadastroContaIDpara',
														Valor_Total = '$valorTotal',
														Observacao = '$observacao'
														WHERE Conta_ID = $contaID");
			for ($i=0; $i < count($_POST["titulo-vencimento"]); $i++){
				$tituloID = $_POST['titulo-id'][$i];
				$codigoVencimento = $_POST['codigo-vencimento'][$i];
				$dataVencimento = "'".implode('-',array_reverse(explode('/',$_POST['data-vencimento'][$i])))." 00:00'";
				$valorVencimento = str_replace(",",".",str_replace(".","",$_POST['valor-vencimento'][$i]));
				$formaPagamento = $_POST['forma-pagamento'][$i];
				$situacaoVencimentoNova = $_POST['situacao-vencimento'][$i];
				$situacaoVencimentoAtual = $_POST['situacao-vencimento-atual'][$i];
				if ($situacaoVencimentoNova == "49"){
					$valorPago= formataValorBD($_POST['valor-pago'][$i]);
					$dataPago = "'".implode('-',array_reverse(explode('/',$_POST['data-pago'][$i])))." 00:00'";
				}
				else{
					$valorPago= "0";
					$dataPago = "NULL";
				}
				$sql = "UPDATE financeiro_titulos SET Forma_Pagamento_ID = '$formaPagamento',
														Codigo = '$codigoVencimento',
														Valor_Titulo = '$valorVencimento',
														Data_Vencimento = $dataVencimento,
														Valor_Pago = '$valorPago',
														Data_Pago = $dataPago,
														Situacao_Pagamento_ID = '$situacaoVencimentoNova',
														Observacao = '',
														Data_Alteracao =  $dataHoraAtual,
														Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
														WHERE Titulo_ID = $tituloID";
				mpress_query($sql);

				/**/
				if ($configFinanceiro['exibe-conta']){
					$sql = "update financeiro_movimentacoes set Situacao_ID = 2 where Titulo_ID = '$tituloID'";
					mpress_query($sql);
					if ($situacaoVencimentoNova == "49"){
						if (($tipoID==44) || ($tipoID==46))
							$valorMovimentacao = $valorPago * -1;
						else
							$valorMovimentacao = $valorPago;

						$sql = "INSERT INTO financeiro_movimentacoes (Conta_ID, Titulo_ID, Cadastro_Conta_ID, Data_Movimentacao, Valor, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
															VALUES ('$contaID', '$tituloID', '$cadastroContaIDde', $dataPago, '$valorMovimentacao', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
						mpress_query($sql);
						if ($tipoID==46){
							$sql = "INSERT INTO financeiro_movimentacoes (Conta_ID, Titulo_ID, Cadastro_Conta_ID, Data_Movimentacao, Valor, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
																VALUES ('$contaID', '$tituloID', '$cadastroContaIDpara', $dataPago, '$valorPago', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
							mpress_query($sql);
						}
					}
				}
				/**/
			}
		}


		/* SALVANDO CENTRO DE CUSTO E TIPO DE CONTA  -  TABELA CONTABIL */

		$sql = "UPDATE financeiro_contabil set Situacao_ID = 2 where Conta_ID = '$contaID'";
		mpress_query($sql);
		foreach($_POST['lancamento-tipo-conta'] as $chave => $tipoContaID){
			$centroCustoID = $_POST['lancamento-centro-custo'][$chave];
			$observacao = utf8_decode($_POST['observacao-contabil'][$chave]);
			$valorContabil = formataValorBD($_POST['valor-contabil'][$chave]);
			if (count($_POST['lancamento-tipo-conta'])==1){
				$valorContabil = formataValorBD($_POST['valor-total']);
			}
			$sql = "INSERT INTO financeiro_contabil (Centro_Custo_ID, Tipo_Conta_ID, Conta_ID, Valor, Observacao, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
											VALUES ('$centroCustoID', '$tipoContaID', $contaID, $valorContabil, '$observacao', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual) ";
			mpress_query($sql);
		}




		/* SE ENTRADA */
		if ($tipoID==45){
			$sql = " update financeiro_cobranca set Situacao_ID = 2 where Conta_ID = '$contaID'";
			mpress_query($sql);

			$sql = " INSERT INTO financeiro_cobranca (Conta_ID, Titulo_ID, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
												VALUES ('$contaID', 0, '".serialize($_POST['configCob'])."', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."') ";
			mpress_query($sql);
		}

		//if ($_POST['faturamento-direto']=='S')
		//	echo "direto";
		//else
		//echo $contaID;
	}


	function atualizarProdutosFaturamento(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		if ($_POST['origem-selecionada']=="chamados"){
			$virgula = "";
			$prodCanc = "";
			for($i = 0; $i < count($_POST['produto-cancelar']); $i++){
				$prodCanc .= $virgula.$_POST['produto-cancelar'][$i];
				$virgula = ",";
			}
			if ($prodCanc!=""){
				$sql = "update financeiro_produtos set Situacao_ID = '2', Usuario_Alteracao_ID = '".$dadosUserLogin[userID]."', Data_Alteracao = $dataHoraAtual where Financeiro_Produto_ID in ($prodCanc)";
				mpress_query($sql);
			}
			$virgula = "";
			$prodFat = "";
			for($i = 0; $i < count($_POST['produto-faturar']); $i++){
				$prodFat .= $virgula.$_POST['produto-faturar'][$i];
				$virgula = ",";
			}

			$contCadastroPara = 0;
			$query = mpress_query("select distinct fc.Cadastro_ID_para from financeiro_produtos fp
									inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID
									where fp.Financeiro_Produto_ID in ($prodFat)");
			while($rs = mpress_fetch_array($query)){ $contCadastroPara++; $cadastroIDpara = $rs['Cadastro_ID_para'];}
			if ($contCadastroPara>1){ $cadastroIDpara = 0;}

			if ($prodFat!=""){
				$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i')."'";
				$sql = "insert into financeiro_contas
							(Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Valor_Total, Observacao, Data_Cadastro, Usuario_Cadastro_ID)
						select Tipo_ID, Cadastro_ID_de, '".$cadastroIDpara."', fc.Tabela_Estrangeira,
							(case fc.Tipo_ID when 45 then	sum(cwp.Quantidade * cwp.Valor_Venda_Unitario) when 44 then sum(cwp.Quantidade * cwp.Valor_Custo_Unitario) else 0 end),
							'Gerado Via Faturamento', $dataHoraAtual, '".$dadosUserLogin['userID']."'
							from financeiro_contas fc
							inner join financeiro_produtos fp on fp.Conta_ID = fc.Conta_ID and fp.Financeiro_Produto_ID in ($prodFat)
							inner join chamados_workflows_produtos cwp on cwp.Workflow_Produto_ID = fp.Produto_Referencia_ID and fp.Situacao_ID = 1 and cwp.Situacao_ID = 1
							group by Tipo_ID, Cadastro_ID_de, fc.Tabela_Estrangeira";
				mpress_query($sql);
				$contaID = mysql_insert_id();
				//echo "<br><br>$sql";

				$sql = "update financeiro_produtos set Conta_ID = '$contaID' where Financeiro_Produto_ID in ($prodFat)";
				mpress_query($sql);
				//echo "<br><br>$sql";

				$sql = "insert into financeiro_titulos (Conta_ID, Valor_Titulo, Situacao_Pagamento_ID, Data_Cadastro, Usuario_Cadastro_ID)
												values ('$contaID', 		 0, 				   -1, $dataHoraAtual, '".$dadosUserLogin['userID']."') ";
				mpress_query($sql);
				//echo "<br><br>$sql";
				echo $contaID;
			}
		}
		if ($_POST['origem-selecionada']=="compras"){

			/* CANCELAMENTO */
			$virgula = "";
			$prodCanc = "";
			for($i = 0; $i < count($_POST['produto-cancelar']); $i++){
				$prodCanc .= $virgula.$_POST['produto-cancelar'][$i];
				$virgula = ",";
			}
			if ($prodCanc!=""){
				$resultado = mpress_query("select p.Compra_Solicitacao_ID from compras_solicitacoes s inner join compras_ordens_compras_produtos p on p.Compra_Solicitacao_ID = s.Compra_Solicitacao_ID where p.Ordens_Compras_Produtos_ID IN ($prodCanc)");
				while($rs = mpress_fetch_array($resultado)){
					mpress_query("update compras_solicitacoes set Situacao_ID = 60 where Compra_Solicitacao_ID = ".$rs['Compra_Solicitacao_ID']);
				}
				mpress_query("delete from compras_ordens_compras_produtos where Ordens_Compras_Produtos_ID in ($prodCanc)");
				mpress_query("delete from compras_ordem_compras_finalizadas where Ordem_Compra_Produto_ID in ($prodCanc)");
				mpress_query("delete oc.* from compras_ordem_compra oc
								left join compras_ordens_compras_produtos ocp on ocp.Ordem_Compra_ID = oc.Ordem_Compra_ID
								where ocp.Ordens_Compras_Produtos_ID is null");
			}
			/* APROVAÇÃO */
			$chaveEstrangeira = "compras";
			$prodFat = "";
			for($i = 0; $i < count($_POST['produto-faturar']); $i++){
				$prodFat .= $virgula.$_POST['produto-faturar'][$i];
				$virgula = ",";
			}
			if ($prodFat!=""){

				//Busca Fornecedor
				$query = mpress_query("select distinct cpf.Fornecedor_ID from compras_ordem_compras_finalizadas cpf where cpf.Ordem_Compra_Produto_ID  in ($prodFat)");
				while($rs = mpress_fetch_array($query)){ $contForn++; $fornecedorID = $rs[Fornecedor_ID];}
				if ($contForn>1){ $fornecedorID = "";}

				//Busca Empresa Responsável
				$query = mpress_query("select distinct oc.Cadastro_ID from compras_ordem_compras_finalizadas cpf inner join compras_ordem_compra oc on oc.Ordem_Compra_ID = cpf.Ordem_Compra_ID where cpf.Ordem_Compra_Produto_ID in ($prodFat)");
				while($rs = mpress_fetch_array($query)){ $contCad++; $cadastroID = $rs[Cadastro_ID];}
				if ($contCad>1){ $cadastroID = "";}

				//Busca Observações
				$query = mpress_query("select distinct coc.Observacao as Observacao from compras_ordem_compra coc  inner join compras_ordem_compras_finalizadas cpf on coc.Ordem_Compra_ID = cpf.Ordem_Compra_ID where cpf.Ordem_Compra_Produto_ID  in ($prodFat)");
				while($rs = mpress_fetch_array($query)){
					$observacoes .= $rs[Observacao];
				}

				$sql = "insert into financeiro_contas (Tipo_ID, Observacao, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
												values ('44', 'Gerado Via Faturamento - Origem Compras \n".$observacoes."','$cadastroID', '$fornecedorID', 'compras', (select sum(cpf.Quantidade_Aprovada * cpf.Valor_Aprovado) from compras_ordem_compras_finalizadas cpf where cpf.Ordem_Compra_Produto_ID  in ($prodFat)), $dataHoraAtual, '".$dadosUserLogin['userID']."')";
				mpress_query($sql);
				$contaID = mysql_insert_id();

				$sql = "insert into financeiro_titulos (Conta_ID, Valor_Titulo, Situacao_Pagamento_ID, Data_Cadastro, Usuario_Cadastro_ID)
												values ('$contaID', 		 0, 				   -1, $dataHoraAtual, '".$dadosUserLogin['userID']."') ";
				mpress_query($sql);

				$sql = "insert into financeiro_produtos
										(Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
								select Ordem_Compra_Produto_ID, '$contaID', 'compras', cpf.Ordem_Compra_ID, cs.Produto_Variacao_ID, cpf.Quantidade_Aprovada, cpf.Valor_Aprovado, 1, '".$dadosUserLogin['userID']."', $dataHoraAtual
								from compras_ordem_compras_finalizadas cpf
								inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = cpf.Ordem_Compra_ID and cpf.Ordem_Compra_Produto_ID = cp.Ordens_Compras_Produtos_ID
								inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
								where cpf.Ordem_Compra_Produto_ID in ($prodFat)";
				mpress_query($sql);
				echo $contaID;
			}
		}

		if ($_POST['origem-selecionada']=="envios"){
			/* CANCELAMENTO */
			$virgula = "";
			$prodCanc = "";
			for($i = 0; $i < count($_POST['produto-cancelar']); $i++){
				$prodCanc .= $virgula.$_POST['produto-cancelar'][$i];
				$virgula = ",";
			}
			if ($prodCanc!=""){
				mpress_query("update financeiro_faturar set Situacao_ID = 2,
															Usuario_Cancelamento_ID = '".$dadosUserLogin['userID']."',
															Data_Cancelamento = $dataHoraAtual
								where Chave_Estrangeira in ($prodCanc) and Tabela_Estrangeira = 'envios'");
			}
			/* APROVAÇÃO */
			$chaveEstrangeira = "compras";
			$prodFat = "";
			for($i = 0; $i < count($_POST['produto-faturar']); $i++){
				$prodFat .= $virgula.$_POST['produto-faturar'][$i];
				$virgula = ",";
			}

			//Busca Fornecedores e Observacoes
			$query = mpress_query("select ff.Cliente_Fornecedor_ID as Fornecedor_ID, ff.Observacao as Observacao, (ff.Quantidade * ff.Valor_Unitario) as Valor_Total
										from financeiro_faturar ff
										where ff.Chave_Estrangeira IN ($prodFat) and ff.Tabela_Estrangeira = 'envios' and ff.Situacao_ID = 1
										order by ff.Cliente_Fornecedor_ID");
			while($rs = mpress_fetch_array($query)){
				if ($rs[Fornecedor_ID]!=$fornecedorID){$contForn++;}
				$fornecedorID = $rs[Fornecedor_ID];
				$observacoes .= $rs[Observacao];
				$valorTotal += $rs[Valor_Total];
			}
			if ($contForn>1){ $fornecedorID = "";}

			if ($prodFat!=""){
				$sql = "insert into financeiro_contas (Tipo_ID, Observacao, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
						select ff.Tipo_ID, 'Gerado Via Faturamento - Origem Envios\n".$observacoes."', ff.Empresa_ID, '$fornecedorID', ff.Tabela_Estrangeira, $valorTotal, $dataHoraAtual, '".$dadosUserLogin['userID']."'
											from financeiro_faturar ff where ff.Chave_Estrangeira in ($prodFat) and ff.Tabela_Estrangeira = 'envios' and Situacao_ID = 1
											group by ff.Tipo_ID, ff.Empresa_ID, ff.Tabela_Estrangeira";

				//echo $sql;
				mpress_query($sql);
				$contaID = mysql_insert_id();

				$sql = "insert into financeiro_titulos (Conta_ID, Valor_Titulo, Situacao_Pagamento_ID, Data_Cadastro, Usuario_Cadastro_ID)
												values ('$contaID', 		 0, 				   -1, $dataHoraAtual, '".$dadosUserLogin['userID']."') ";
				//echo $sql;
				mpress_query($sql);

				$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Produto_Descricao, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
												 select -2, '$contaID', 'envios', Chave_Estrangeira, -2,'Transporte', Quantidade, Valor_Unitario, 1, '".$dadosUserLogin['userID']."', $dataHoraAtual
											from financeiro_faturar ff where ff.Chave_Estrangeira in ($prodFat) and ff.Tabela_Estrangeira = 'envios' and Situacao_ID = 1";
				mpress_query($sql);
				echo $contaID;
			}
		}

	}


	function carregarLancamentosMes(){
		$cadastroID = $_POST['cadastro-id-de'];
		if (($cadastroID!="")&&(count($_POST["titulo-vencimento"])>0)){
			for ($i=0; $i < count($_POST["titulo-vencimento"]); $i++){
				$dataVencimento[$i] = implode('-',array_reverse(explode('/',$_POST['data-vencimento'][$i])));
				$datas .= substr($_POST['data-vencimento'][$i],3,7)."&nbsp;";
			}
			$sql = "select Cadastro_ID, Nome, Nome_Fantasia from cadastros_dados cd where cd.Situacao_ID = 1 and Cadastro_ID = $cadastroID";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$cadastroNome = utf8_encode($row[Nome]);
			}

			$mes = substr(max($dataVencimento),5,2);
			$ano = substr(max($dataVencimento),0,4);
			$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));

			$dataIni = "01/".substr(implode('/',array_reverse(explode('-',min($dataVencimento)))),3,7);
			$dataFim = $ultimo_dia."/".substr(implode('/',array_reverse(explode('-',max($dataVencimento)))),3,7);

			$dataIniBD = implode('-',array_reverse(explode('/',$dataIni)))." 00:00";
			$dataFimBD = implode('-',array_reverse(explode('/',$dataFim)))." 23:59";

			echo "	<div class='titulo'>
						<p>Lan&ccedil;amentos registrados entre as datas: $dataIni e $dataFim do cadastro ".$cadastroNome."</p>
					</div>
					<div class='conteudo-interno'>";
			$dadosAux[Saida][Descricao] = "<font color='#FF4D4D'>SA&Iacute;DAS</font>";
			$dadosAux[Entrada][Descricao] = "<font color='#0047c9'>ENTRADAS</font>";
			$dadosAux[TransferenciaS][Descricao] = "TRANSFER&Ecirc;CIAS ENVIADAS <font color='#FF4D4D'>(SA&Iacute;DAS)</font>";
			$dadosAux[TransferenciaE][Descricao] = "TRANSFER&Ecirc;CIAS RECEBIDAS <font color='#0047c9'>(ENTRADAS)</font>";

			$sql = "select cdd.Nome as Nome_De, cdp.Nome as Nome_Para, fc.Cadastro_ID_de, fc.Cadastro_ID_para, fc.Conta_ID as Conta_ID, fc.Codigo as Codigo, ft.Codigo as Codigo_Titulo, tc.Descr_Tipo as Tipo, fc.Tipo_ID as Tipo_ID, tcc.Descr_Tipo as Tipo_Conta,  fc.Valor_Total,
								ft.Titulo_ID, tfp.Descr_Tipo as Forma_Pagamento, ft.Valor_Titulo, DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, ft.Valor_Pago, tsp.Descr_Tipo as Situacao_Pagamento
								from financeiro_contas fc
								inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
								inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
								left join tipo tcc on tcc.Tipo_ID = fc.Tipo_Conta_ID and tcc.Tipo_Grupo_ID = 28
								inner join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_de
								left join cadastros_dados cdp on cdp.Cadastro_ID = fc.Cadastro_ID_para
								left join tipo tfp on tfp.Tipo_ID  = ft.Forma_Pagamento_ID and tfp.Tipo_Grupo_ID = 25
								left join tipo tsp on tsp.Tipo_ID  = ft.Situacao_Pagamento_ID and tsp.Tipo_Grupo_ID = 29
								where ft.Data_Vencimento between '$dataIniBD' and '$dataFimBD'
								and (Cadastro_ID_de = $cadastroID or Cadastro_ID_para = $cadastroID)
								and fc.Tipo_ID in (44,45,46)
								and ft.Situacao_Pagamento_ID IN (48,49)
								order by fc.Tipo_ID, ft.Data_Vencimento";
			//echo $sql;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$titulo = $rs[Titulo_ID];

				if ($titulo==""){$titulo="N/A";}
				if ($rs[Tipo_ID]==44){
					$array = "Saida";
				}
				if ($rs[Tipo_ID]==45){
					$array = "Entrada";
				}
				if ($rs[Tipo_ID]==46){
					if ($rs[Cadastro_ID_para]==$cadastroID){
						$array = "TransferenciaE";
					}
					if ($rs[Cadastro_ID_de]==$cadastroID){
						$array = "TransferenciaS";
					}
				}
				$dadosAux[$array][Titulo][] = $titulo;
				$dadosAux[$array][Tipo][] = $rs[Tipo_Conta];
				$dadosAux[$array][Situacao][] = $rs[Situacao_Pagamento];
				$dadosAux[$array][Valor][] = $rs[Valor_Titulo];
				$dadosAux[$array][DataVencimento][] = $rs[Data_Vencimento];
				$dadosAux[$array][NomeDe][] = $rs[Nome_De];
				$dadosAux[$array][NomePara][] = $rs[Nome_Para];
				$dadosAux[$array][ContaID][] = $rs[Conta_ID];
			}
			echo " <table width='100%' style='border:0px' cellpadding='0' cellspacing='2' align='center'>";
			$i=0;
			foreach($dadosAux as $chave => $dado){
				$i++;
				if (($i%2)!=0){echo "<tr>"; $totalVencimento=0;}
				echo "	<td width='50%' valign='top' style='border:1px solid silver;'>
							<div style='float:left;width:100%'>
								<table width='99.8%' style='margin-top:1px;border:0px solid silver;' cellpadding='2' cellspacing='2' align='center'>
									<tr><td class='fundo-escuro-titulo' align='center' colspan='5'>".utf8_encode($dado[Descricao])."</td></tr>
									<tr>
										<td class='fundo-escuro-titulo'>T&iacute;tulo</td>";
					if (($chave=="Entrada")||($chave=="Saida")){
						echo "			<td class='fundo-escuro-titulo'>Tipo</td>";
					}
					if ($chave=="TransferenciaS"){
						echo "			<td class='fundo-escuro-titulo'>Transfer&ecirc;ncia realizada para</td>";
					}
					if ($chave=="TransferenciaE"){
						echo "			<td class='fundo-escuro-titulo'>Transfer&ecirc;ncia recebida de</td>";
					}
					echo "				<td class='fundo-escuro-titulo'>Situa&ccedil;&atilde;o</td>
										<td class='fundo-escuro-titulo' align='right'>Valor T&iacute;tulo</td>
										<td class='fundo-escuro-titulo' align='center'>Data Vencimento</td>
									</tr>";
					foreach($dado[Titulo] as $chave2  => $titulo){
						echo "		<tr>
										<td Style='margin:2px 5px 0 5px;'><span class='link localiza-conta' conta-id='".$dadosAux[$chave][ContaID][$chave2]."' titulo-id=''>".utf8_encode($dadosAux[$chave][Titulo][$chave2])."</span></td>";
						if (($chave=="Entrada")||($chave=="Saida")){
							echo "		<td Style='margin:2px 5px 0 5px;'>".utf8_encode($dadosAux[$chave][Tipo][$chave2])."</td>";
						}
						if ($chave=="TransferenciaS"){
							echo "		<td Style='margin:2px 5px 0 5px;'>".utf8_encode($dadosAux[$chave][NomePara][$chave2])."</td>";
						}
						if ($chave=="TransferenciaE"){
							echo "		<td Style='margin:2px 5px 0 5px;'>".utf8_encode($dadosAux[$chave][NomeDe][$chave2])."</td>";
						}
						echo "			<td Style='margin:2px 5px 0 5px;'>".utf8_encode($dadosAux[$chave][Situacao][$chave2])."</td>
										<td Style='margin:2px 5px 0 5px;' align='right'>".number_format($dadosAux[$chave][Valor][$chave2], 2, ',', '.')."</td>
										<td Style='margin:2px 5px 0 5px;' align='center'>".utf8_encode($dadosAux[$chave][DataVencimento][$chave2])."</td>
									</tr>";
						$totalVencimento += $dadosAux[$chave][Valor][$chave2];
					}
					if ($chave=="Entrada"){$totEntrada = $totalVencimento;}
					if ($chave=="Saida"){$totSaida = $totalVencimento;}
					if ($chave=="TransferenciaS"){$totTransferenciaS = $totalVencimento;}
					if ($chave=="TransferenciaE"){$totTransferenciaE = $totalVencimento;}
					echo " 	<tr>
								<td class='fundo-escuro-titulo' colspan='3' align='center'><b>TOTAL</b></td>
								<td class='fundo-escuro-titulo' align='right'><b>".number_format($totalVencimento, 2, ',', '.')."</b></td>
								<td class='fundo-escuro-titulo'>&nbsp;</td>
							</tr>";
					$totalVencimento = 0;

					echo "		</table>
							</div>
						</td>";
					if ($i%2==0){echo "</tr>";}
					if ($i==2){
						echo "<tr>
								<td colspan='5'>
									<table width='100%' style='border:0px' cellpadding='3' cellspacing='3' align='center'>
										<tr>
											<td class='fundo-escuro-titulo' width='37.5%'>&nbsp;</td>
											<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b>SALDO PARCIAL</b><br><i>(<font color='#0047c9'>Entradas</font> - <font color='#FF4D4D'>Sa&iacute;das</font>)</i></td>
											<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b>R$ ".number_format(($totEntrada-$totSaida), 2, ',', '.')."</b></td>
											<td class='fundo-escuro-titulo' width='37.5%'>&nbsp;</td>
										</tr>
									</table>
								</td>
							  </tr>";
					}

			}
			echo "
				</table>
				<table width='100%' style='border:0px' cellpadding='3' cellspacing='3' align='center'>
					<tr>
						<td class='fundo-escuro-titulo' width='12.5'>&nbsp;</td>
						<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b><font color='#FF4D4D'>TOTAL SA&Iacute;DAS</font></b><br><i>(Sa&iacute;das + Transfer&ecirc;cias Realizadas)</i></td>
						<td class='fundo-escuro-titulo' width='12.5%' class='fundo-escuro-titulo' align='center' valign='middle'><b>R$ ".number_format(($totSaida+$totTransferenciaS), 2, ',', '.')."</b></td>
						<td class='fundo-escuro-titulo' width='12.5%'>&nbsp;</td>
						<td class='fundo-escuro-titulo' width='12.5%'>&nbsp;</td>
						<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b><font color='#0047c9'>TOTAL ENTRADAS</font></b><br><i>(Entradas + Transfer&ecirc;cias Recebidas)</i></td>
						<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b>R$ ".number_format(($totEntrada+$totTransferenciaE), 2, ',', '.')."</b></td>
						<td class='fundo-escuro-titulo' width='12.5%'>&nbsp;</td>
					</tr>
				</table>
				<table width='100%' style='border:0px' cellpadding='3' cellspacing='3' align='center'>
					<tr>
						<td class='fundo-escuro-titulo' width='37.5%'>&nbsp;</td>
						<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b>SALDO GERAL</b><br><i>(<font color='#0047c9'>Total Entradas</font> - <font color='#FF4D4D'>Total Sa&iacute;das</font>)</i></td>
						<td class='fundo-escuro-titulo' width='12.5%' align='center' valign='middle'><b>R$ ".number_format((($totEntrada+$totTransferenciaE)-($totSaida+$totTransferenciaS)), 2, ',', '.')."</b></td>
						<td class='fundo-escuro-titulo' width='37.5%'>&nbsp;</td>
					</tr>
				</table>

				</div>";
		}
		else{
			echo "<p align='center'> DADOS INCOMPLETOS PARA GERAR RESUMO </p>";

		}
	}



	function carregarLocalizarProdutoFinanceiro($contaID, $financeiroProdutoID){
		global $caminhoSistema;
		$quantidade = "1";
		$textoBotaoIncAlt = "Incluir";
		if ($financeiroProdutoID!=''){
			$textoBotaoIncAlt = "Alterar";
			$sql = "SELECT Produto_Variacao_ID, Produto_Descricao, Quantidade, Valor_Unitario
						FROM financeiro_produtos where Financeiro_Produto_ID = '$financeiroProdutoID'";
			//echo $sql;
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$produtoVariacaoID = $row[Produto_Variacao_ID];
				$produtoDescricao = utf8_encode($row[Produto_Descricao]);
				$quantidade = $row[Quantidade];
				$valorUnitario = $row[Valor_Unitario];
				$valorVendaTotal = $quantidade * $valorUnitario;
			}
		}
		$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
					pv.Forma_Cobranca_ID, f.Descr_Tipo as Forma_Cobranca, case pv.Forma_Cobranca_ID when 35 then pv.Valor_Venda else '' end as Valor_Venda, pd.Produto_ID as Produto_ID
					FROM produtos_dados pd
					INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
					inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
					WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
				ORDER BY Descricao_Produto";


		var_dump($sql);
		die();
		
		$selectProdutos = "<select id='select-produtos' name='select-produtos' class='required' Style='width:98.5%' data-placeholder='Selecione'>
								<option value='' produto-id=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row['Valor_Venda']!="") $valorVenda = ":&nbsp;".number_format($row['Valor_Venda'], 2, ',', '.'); else $valorVenda = "";
			if ($row['Produto_Variacao_ID']==$produtoVariacaoID) {
				$selecionado = "selected";
			}
			else {
				$selecionado = "";
			}
			$selectProdutos .= "<option value='".$row[Produto_Variacao_ID]."' produto-id='".$row[Produto_ID]."' $selecionado>".utf8_encode($row['Descricao_Produto'])."&nbsp;&nbsp;-&nbsp;&nbsp;".utf8_encode($row['Forma_Cobranca'])." $valorVenda</option>";
		}
		$selectProdutos .= "</select>";
		echo "	<fieldset style='margin-bottom:20px;' class='titulo-secundario' id='bloco-incluir-alterar-produto'>";
		echo "		<input type='hidden' name='financeiro-produto-id-aux' id='financeiro-produto-id-aux' value='$financeiroProdutoID'/>
					<div id='div-produtos-select' style='float:left; width:50%;'>
						<p><b>Selecione o Produto / Servi&ccedil;o</b></p>
						<p>$selectProdutos</p>
						<p class='esconde'><input type='text' id='descricao-produto-variacao-aux' name='descricao-produto-variacao-aux' value='$produtoDescricao' style='width:98.5%'/></p>
					</div>
					<div style='width:10%;float:left;'>
						<p><b>Quantidade</b></p>
						<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='".number_format($quantidade,2,",",".")."' class='formata-valor required' style='width:90%' maxlength='10'/></p>
					</div>
					<div style='width:10%; float:left;margin-top:3px'>
						<p><b>Total Parcial</b></p>
						<p><input type='text' id='total-venda-produtos' name='total-venda-produtos' style='width:95%' value='".number_format($valorVendaTotal,2,",",".")."' readonly/></p>
					</div>
					<div style='width:15%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' id='botao-salvar-produto' name='botao-salvar-produto' class='botao-salvar-produto' Style='width:95%' value='$textoBotaoIncAlt'/></p>
					</div>
					<div style='width:15%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' value='Cancelar' id='botao-cancelar-produto' class='botao-cancelar-produto' Style='width:95%'/></p>
					</div>
					<div id='div-detalhes-produto' style='width:100%; float:left; margin-bottom:10px;'>";
		if (($contaID!="") && ($produtoVariacaoID!="")){
			carregarProdutoDetalhesFinanceiro($contaID, $produtoVariacaoID);
		}
		echo "			&nbsp;
					</div>
				</fieldset>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function excluirProdutoFinanceiro($financeiroProdutoID){
		global $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$sql = "UPDATE financeiro_produtos SET Usuario_Alteracao_ID = ".$dadosUserLogin['userID'].",
							Data_Alteracao = '$dataHoraAtual',
							Situacao_ID = '2' WHERE Financeiro_Produto_ID = '$financeiroProdutoID'";
		echo $sql;
		$resultado = mpress_query($sql);
	}

	function salvarProdutoFinanceiro(){
		global $dadosUserLogin;
		$dataHoraAtual 			= retornaDataHora('','Y-m-d H:i:s');
		$financeiroProdutoID 	= $_POST['financeiro-produto-id-aux'];
		$contaID 				= $_POST['conta-id'];
		$produtoVariacaoID 		= $_POST['select-produtos'];
		$produtoDescricao 		= utf8_decode($_POST['descricao-produto-variacao-aux']);
		$quantidadeProdutos 	= str_replace(",",".",str_replace(".","",$_POST['quantidade-produtos']));
		$valorVendaUnitario 	= str_replace(",",".",str_replace(".","",$_POST['valor-venda-unitario']));

		//Produto referencia ID não foi definido aqui, sendo colocado manualmente no código até verificção futura.
		$produtoReferenciaID  	= 0;

		if ($financeiroProdutoID==""){
			$sql = "INSERT INTO financeiro_produtos
						(Conta_ID, Produto_Referencia_ID, Produto_Variacao_ID, Produto_Descricao, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
					VALUES
						($contaID, '$produtoReferenciaID', '$produtoVariacaoID', '$produtoDescricao',  '$quantidadeProdutos', '$valorVendaUnitario', 1, ".$dadosUserLogin['userID'].",'$dataHoraAtual')";

			// var_dump($sql);

			// die();
			$resultado = mpress_query($sql);
		}
		else{
			$sql = "UPDATE financeiro_produtos
						SET	Usuario_Alteracao_ID = ".$dadosUserLogin['userID'].",
							Data_Alteracao = '$dataHoraAtual',
							Produto_Variacao_ID = '$produtoVariacaoID',
							Produto_Descricao = '$produtoDescricao',
							Quantidade = '$quantidadeProdutos',
							Valor_Unitario = '$valorVendaUnitario'
						WHERE Financeiro_Produto_ID = '$financeiroProdutoID'";
			$resultado = mpress_query($sql);
		}
		//echo $sql;
	}

	function carregarProdutoDetalhesFinanceiro($contaID, $produtoVariacaoID){
		global $caminhoSistema;
		$sql = "select distinct pd.Tipo_Produto as Tipo_Produto, (tp.Descr_Tipo) as Tipo, pv.Valor_Custo as Valor_Custo, pv.Valor_Venda as Valor_Venda, pv.Valor_Promocao as Valor_Promocao, Forma_Cobranca_ID,
					Percentual_Venda, t.Descr_Tipo as Forma_Cobranca, pv.Data_Inicio_Promocao as Data_Inicio_Promocao, pv.Data_Fim_Promocao as Data_Fim_Promocao,
					pv.Saldo_Estoque as Saldo_Estoque, pv.Altura as Altura, pv.Largura as Largura, pv.Comprimento as Comprimento, pv.Comprimento as Comprimento, pv.Peso as Peso,
					sum(pm.Quantidade) as Quantidade_Estoque, pe.Estoque_Minimo,  ma.Nome_Arquivo as Nome_Arquivo, pd.Produto_ID as Produto_ID,
					concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Descricao_Produto, pd.Descricao_Completa
					from produtos_dados pd
					inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
					inner join tipo t on t.Tipo_ID = Forma_Cobranca_ID
					inner join tipo tp on tp.Tipo_ID = pd.Tipo_Produto
					left join produtos_movimentacoes pm on pm.Produto_Variacao_ID = pv.Produto_Variacao_ID
					left join produtos_estoque pe on pe.Produto_Estoque_ID = pv.Produto_Variacao_ID
					left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
					where pv.Produto_Variacao_ID = '$produtoVariacaoID'";
		//echo $sql;

		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			/*********************************************************/
			/********** Inicio Funçoes para tabela de preços *********/
			/*********************************************************/
			// ADAPTAR PARA O FINANCEIRO
			/*
			$rsTabela = mpress_query("select cd1.Tabela_Preco_ID Tabela_Preco_Solicitante, cd2.Tabela_Preco_ID Tabela_Preco_Prestador
									   from chamados_workflows cw
									   left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
									   left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
									   where Workflow_ID = $workflowID");
			if($tabela = mpress_fetch_array($rsTabela)){
				$tabelaSolicitante = $tabela['Tabela_Preco_Solicitante'];
				$tabelaPrestador   = $tabela['Tabela_Preco_Prestador'];
			}
			$produtoVariacaoID = $_GET['produto-variacao-id'];

			$rsTabela = mpress_query("select Valor_Custo  Valor_Tabela, 'Prestador' Tipo from produtos_tabelas_precos_detalhes where Tabela_Preco_ID = '$tabelaPrestador' and Situacao_ID = 1 and Produto_Variacao_ID = $produtoVariacaoID
									   union
									   select Valor_Venda Valor_Tabela, 'Solicitante' Tipo from produtos_tabelas_precos_detalhes where Tabela_Preco_ID = '$tabelaSolicitante' and Situacao_ID = 1 and Produto_Variacao_ID = $produtoVariacaoID;");
			while($tabela = mpress_fetch_array($rsTabela)){
				if(($tabela['Tipo'] == 'Prestador')&&($tabela['Valor_Tabela']>0))   $row['Valor_Custo'] = $tabela['Valor_Tabela'];
				if(($tabela['Tipo'] == 'Solicitante')&&($tabela['Valor_Tabela']>0)) $row['Valor_Venda'] = $tabela['Valor_Tabela'];
			}
			/*
			/*********************************************************/
			/********** Final Funçoes para tabela de preços *********/
			/*********************************************************/

			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			if ($row[Nome_Arquivo]!="")
				$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
			else{
				if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1")))
					$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
			}
			$imagemProduto = "<a href='$nomeArquivo' target='_blank'><img style='max-width:50px; max-height:50px' src='$nomeArquivo' align='center'/></a>";

			echo "	<input type='hidden' name='forma-cobranca' id='forma-cobranca' value='".$row[Forma_Cobranca_ID]."'>
					<div style='float:left; width:100%; margin-top:10px;'>
						<div style='float:left; width:50%; min-height:80px;'>
							<fieldset style='min-height:80px;'>
								<legend><b>".utf8_encode($row[Tipo])."</b></legend>
								<div style='float:left; width:10%' align='center'>
									<p align='center'>$imagemProduto</p>
								</div>
								<div style='float:left; margin-left:5%; width:85%'>
									<p>".utf8_encode($row[Descricao_Produto])."
										<input type='hidden' name='descricao-produto-variacao' id='descricao-produto-variacao' value='".utf8_encode($row[Descricao_Produto])."'/>
									</p>
									<p style='margin-top:5px;'>".utf8_encode($row[Descricao_Completa])."</p>
								</div>
							</fieldset>
						</div>
						<div style='float:left; width:50%; min-height:80px;'>
							<fieldset style='min-height:80px;'>
								<legend>Forma de Cobran&ccedil;a: <b>".strtoupper(utf8_encode($row[Forma_Cobranca]))."</b></legend>";
			if ($row[Forma_Cobranca_ID]=="35"){
				echo "			<div style='float:left; width:33.33%;'>
									<p>Venda Unint&aacute;rio</p>
									<p><input type='text' readonly value='R$ ".number_format($row[Valor_Venda], 2, ',', '.')."'/></p>
									<input type='hidden' id='valor-venda-unitario' name='valor-venda-unitario' value='".str_replace(".",",",$row[Valor_Venda])."'/>
								</div>
								<div style='float:left; width:33.33%;'>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
								</div>";
			}

			// ABAIXO PERCENTUAL DO CUSTO!
			if ($row[Forma_Cobranca_ID]=="36"){
				echo "			<div style='float:left; width:33.33%;'>
									<p>Informe o Custo Unint&aacute;rio</p>
									<p><input type='text' id='valor-custo-unitario' name='valor-custo-unitario' value='0,00' style='width:95%' class='formata-valor'/></p>
								</div>
								<div style='float:left; width:33.33%;'>
									<p>&nbsp;</p>
									<p>&nbsp;</p>
								</div>
								<div style='float:left; width:33.33%;'>
									<p>Venda Unint&aacute;rio acrecida de ".number_format($row[Percentual_Venda], 2, ',', '.')."&nbsp;%</p>
									<p><input type='text' id='valor-venda-unitario' name='valor-venda-unitario' value='0,00' readonly style='width:95%'/></p>
									<input type='hidden' id='percentual-venda' name='percentual-venda' value='".str_replace(".",",",$row[Percentual_Venda])."' class='formata-valor'/>
								</div>";
			}
			// VALORES ABERTOS
			if ($row[Forma_Cobranca_ID]=="58"){
				$width = "20%";
				echo "			<div style='float:left; width:33.33%;'>
									<p>Venda Unint&aacute;rio</p>
									<p><input type='text' id='valor-venda-unitario' name='valor-venda-unitario' class='formata-valor' style='width:95%' value='".number_format($row[Valor_Venda],2,",",".")."'/></p>
								</div>
								<div style='float:left; width:33.33%;'>
									<p>Valor de Venda M&iacute;nimo</p>
									<p><input type='text' readonly value='R$ ".number_format($row[Valor_Promocao], 2, ',', '.')."' style='width:95%' /></p>
									<input type='hidden' id='valor-venda-minima-unitario' name='valor-venda-minima-unitario' class='formata-valor' style='width:95%' value='".str_replace(".",",",$row[Valor_Promocao])."'/>
								</div>";
			}
			echo "			</fieldset>
						</div>
						<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
			// ESTOQUE
			if (($row['Tipo_Produto']=="30") || ($row['Tipo_Produto']=="100") || ($row['Tipo_Produto']=="175")){
				$echo = "<div style='float:left; width:100%; margin-bottom:10px'>
							<div style='float:left; width:25%;'>
								<p>Quantidade Estoque</p>
								<p><input type='text' readonly value='".number_format($row[Quantidade_Estoque], 2, ',', '.')."'/></p>
							</div>
							<div style='float:left; width:25%;'>
								<p>Estoque M&iacute;nimo</p>
								<p><input type='text' readonly value='".number_format($row[Estoque_Minimo], 2, ',', '.')."'/></p>
							</div>
						</div>";
			}
			echo "	</div>";
		}
	}


	function carregarProdutosConta($contaID){
		global $dadosUserLogin, $modulosAtivos, $caminhoSistema, $caminhoFisico;
		$exibirOpcoesAtualizacaoProduto = "esconde";

		//echo $contaID;

		//if ($contaID=="") $contaID = $_POST['localiza-conta-id'];
		$query = mpress_query("SELECT Cadastro_ID_de, Tipo_ID FROM financeiro_contas WHERE Conta_ID = '$contaID'");
		if($row = mpress_fetch_array($query)){
			$empresaID = $row[Cadastro_ID_de];
			$tipoID = $row[Tipo_ID];
		}

		if ($modulosAtivos[nfe]){
			require_once($caminhoFisico."/modulos/nfe/arrays-nfe.php");
			require_once($caminhoFisico."/modulos/nfe/functions.php");

			$sql = "SELECT (SELECT count(pd.Tipo_Produto) 
								FROM financeiro_produtos fp
								INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
								INNER JOIN produtos_dados pd on pd.Produto_ID = pv.Produto_ID
								WHERE fp.Conta_ID = '$contaID' and pd.Tipo_Produto not in (30,100,175) and fp.Situacao_ID = 1) as Servicos,
							(SELECT count(pd.Tipo_Produto) 
									FROM financeiro_produtos fp
									INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
									INNER JOIN produtos_dados pd on pd.Produto_ID = pv.Produto_ID
									WHERE fp.Conta_ID = '$contaID' and pd.Tipo_Produto in (30,100,175) and fp.Situacao_ID = 1) as Produtos";
			//echo $sql;
			$resultado = mpress_query($sql);
			if ($rs = mpress_fetch_array($resultado)){
				$servicos = $rs[Servicos];
				$produtos = $rs[Produtos];
			}
			// BUSCAR DADOS DA EMPRESA
			if (($produtos == 0) && ($servicos != 0)){
				$botaoSuperior = "<input type='button' style='float:right;margin-right:0px; width:120px' value='Emitir NFS-e (Servi&ccedil;os)' id='botao-gerar-xml-servicos'>";
			}
			else{
				$configNF = retornaArrayConfigNF($empresaID);
				if ($configNF[CRT]==3) $arrayICMS = $arrayICMSnormal; 				// SE REGIME NORMAL (3)
				else $arrayICMS = $arrayICMSsimples;								// SE SIMPLES NACIONAL (1,2)

				// IPI
				if ($tipoID==44) $arrayIPI = $arrayIPIentrada; 			// 44 - A PAGAR - NF de ENTRADA
				else $arrayIPI = $arrayIPIsaida; 						// 45 - A RECEBER - NF de SAIDA

				$query = mpress_query($sql);
				$i=0;
				$linhas = "";
				$sql = "select NF_ID, NF_XML, Status_NF from nf_dados where Conta_ID = '$contaID' order by NF_ID desc limit 1";
				//echo $sql;
				if($rs = mpress_fetch_array(mpress_query($sql))){
					$nfID = $rs[NF_ID];
					$statusNF = $rs['Status_NF'];
					$xmlGerado = simplexml_load_string($rs['NF_XML']);
				}
				//if ($statusNF=="100"){
				//	$botaoSuperior = "<input type='button' style='float:right;margin-right:0px;' value='Imprimir DANFE' class='botao-imprimir-danfe-a1'>";
				//}
				//else{
				//	$botaoSuperior = "<input type='button' style='float:right;margin-right:0px;' value='Emitir NF-e (A1)' class='botao-gerar-xml-a1'>";
				//}
			}
		}

		// FATURAMENTO DIRETO
		if ($_GET['modulo']=='chamados'){
			/* REVISAR CÓDIGO */
			// ENTRADA
			if (is_array($_GET['check-fat-receber'])){
				foreach($_GET['check-fat-receber'] as $workflowProdutoID){
					$condWorkflowProdutoID .= $workflowProdutoID.",";
				}
			}
			// SAIDA
			if (is_array($_GET['check-fat-pagar'])){
				foreach($_GET['check-fat-pagar'] as $workflowProdutoID){
					$condWorkflowProdutoID .= $workflowProdutoID.",";
				}
			}
			$condWorkflowProdutoID = substr($condWorkflowProdutoID, 0, -1);

			$sql = "SELECT 0 AS Financeiro_Produto_ID, 
						0 as Conta_ID, 
						'chamados' as Tabela_Estrangeira, 
						cwp.Workflow_ID as Referencia_ID,
						cwp.Produto_Variacao_ID as Produto_Variacao_ID, 
						cwp.Quantidade as Quantidade,
						cwp.Valor_Venda_Unitario as Valor_Produto,
						concat(pd.Nome,' ', pv.Descricao) as Produto, 
						'' as Origem, '' as Tipo_Conta_ID, 
						pd.Industrializado as Industrializado, 
						pd.NCM as NCM, pd.Produto_ID as Produto_ID,
						pv.Produto_Variacao_ID as Produto_Variacao_ID, 
						tp.Descr_Tipo AS Tipo, pd.Tipo_Produto AS Tipo_Produto, 
						concat(pd.Nome,' ', pv.Descricao) as Produto_Descricao,
						cwp.Workflow_Produto_ID as Chave_Estrangeira_Produto
						FROM chamados_workflows cw
						inner join chamados_workflows_produtos cwp on cw.Workflow_ID = cwp.Workflow_ID
						INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
						INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
						INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
						where cwp.Situacao_ID = 1
						and cwp.Workflow_Produto_ID in ($condWorkflowProdutoID)";
			//echo $sql;
			$query = mpress_query($sql);

		}
		elseif($_GET['modulo']=='orcamentos'){
			$tipo = $_GET['tipo-id'];
			foreach($_GET['produto-faturar'] as $produtoPropostaFaturarID){
				$produtoPropostaFaturarIDs .= $produtoPropostaFaturarID.",";
			}
			$condWorkflowProdutoID = substr($produtoPropostaFaturarIDs, 0, -1);
			$sql = "SELECT 0 AS Financeiro_Produto_ID, 
					0 AS Conta_ID, 
					'orcamentos' AS Tabela_Estrangeira, 
					op.Workflow_ID AS Referencia_ID,
					pp.Produto_Variacao_ID AS Produto_Variacao_ID, 
					opp.Quantidade AS Quantidade, 
					opp.Valor_Venda_Unitario AS Valor_Produto, 
					opp.Valor_Custo_Unitario AS Valor_Custo,
					concat(pd.Nome,
					' ', pv.Descricao) AS Produto,
					'' AS Origem,
					'' AS Tipo_Conta_ID,
					pv.Produto_Variacao_ID AS Produto_Variacao_ID, 
					tp.Descr_Tipo AS Tipo, pd.Tipo_Produto AS Tipo_Produto,
					fc.Descr_Tipo AS Forma_Cobranca, 
					concat(pd.Nome,' ', pv.Descricao) AS Produto_Descricao,	
					opp.Proposta_Produto_ID AS Chave_Estrangeira_Produto,
					opp.Faturamento_Direto,
					opp.Prestador_Forma_Pagamento_ID,
					fppf.Descr_Tipo AS Forma_Cobranca_Produto_Prestador,
					fppf.Tipo_Auxiliar AS Descricao_Pagamento_Prestador
					FROM orcamentos_propostas_produtos opp
					INNER JOIN orcamentos_propostas op ON op.Proposta_ID = opp.Proposta_ID
					INNER JOIN tipo fc ON fc.Tipo_ID = op.Forma_Pagamento_ID
					INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
					LEFT JOIN  tipo fppf ON fppf.Tipo_ID = opp.Forma_Pagamento_ID
					WHERE opp.Situacao_ID = 1
						AND opp.Proposta_Produto_ID IN (".$condWorkflowProdutoID.")";
			//echo $sql;
			$query = mpress_query($sql);

			// ADICIONADO NO SELECT O MODO DE PAGAMENTO DO ORÇAMENTO *
			// ADICIONADO NO SELECT O MODO DE PAGAMENTO DO PRODUTO, ESSENCIAL PARA PAGAMENTOS PARA FORNECEDORES
		}
		// FATURAMENTO ENVIO PARA FATURAMENTO
		else{
			$sql = "SELECT fp.Financeiro_Produto_ID as Financeiro_Produto_ID, 
					fp.Conta_ID, 
					fp.Tabela_Estrangeira, 
					fp.Chave_Estrangeira as Referencia_ID,
					fp.Produto_Variacao_ID, 
					fp.Quantidade, 
					fp.Valor_Unitario as Valor_Produto, 
					concat(pd.Nome, ' ', pv.Descricao) as Produto, 
					mo.Nome as Origem,
					fc.Tipo_Conta_ID as Tipo_Conta_ID, 
					pd.Industrializado as Industrializado,
					(case when pd.Tipo_Produto in (30,100,175) THEN pd.NCM else '00' end) as NCM, 
					pd.Produto_ID as Produto_ID, 
					pv.Produto_Variacao_ID as Produto_Variacao_ID,
					tp.Descr_Tipo as Tipo, pd.Tipo_Produto as Tipo_Produto, 
					fp.Produto_Descricao as Produto_Descricao,
					cd.Nome as Usuario_Inclusao, 
					fp.Data_Cadastro as Data_Inclusao
					from financeiro_produtos fp
					inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
					inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
					inner join tipo tp on tp.Tipo_ID = pd.Tipo_Produto
					left join modulos mo on mo.Slug = fp.Tabela_Estrangeira
					left join cadastros_dados cd on cd.Cadastro_ID = fp.Usuario_Cadastro_ID
					where fp.Conta_ID = '$contaID' and fp.Situacao_ID = 1
					order by fp.Financeiro_Produto_ID";
			//echo $sql;
			$query = mpress_query($sql);
		}

		$i=0;
		while($row = mpress_fetch_array($query)){
			if ($statusNF!="100"){
				$exibirOpcoesAtualizacaoProduto = "";
			}


			// TRATAR SAIDA (CUSTO PRODUTO)
			if ($_GET['tipo-id']=='44'){
				$total += $row[Quantidade] * $row[Valor_Custo];
				$row[Valor_Produto] = $row[Valor_Custo];
			}
			else{
				if ($row['Faturamento_Direto']==1){
					$total += $row[Quantidade] * ($row[Valor_Produto] - $row[Valor_Custo]);
					$row[Valor_Produto] = ($row[Valor_Produto] - $row[Valor_Custo]);
				}
				else{
					$total += $row[Quantidade] * $row[Valor_Produto];
				}
			}

			//$totalTemp = $total;

			// VERIFICA SE HÁ MODIFICADOR DE PREÇO NO MODO DE PAGAMENTO

			// if(!empty($row[Forma_Cobranca])){

			// 	$modoPagamento 		= $row[Forma_Cobranca];

			// 	if(!empty($modoPagamento['tipo-bonus-disponivel'])){

			// 		$valorMod = ($totalTemp / 100) * $modoPagamento['valor_modificado'];

			// 		if($modoPagamento['tipo-bonus-disponivel'] == 'Desconto'){

			// 			$valorVencimentoMod = $totalTemp - $valorMod;

			// 		}else{
			// 			$valorVencimentoMod = $totalTemp + $valorMod;
			// 		}

			// 		// APÓS APLICADA A MODIFICAÇÂO DO VALOR NO TOTAL DOS PRODUTOS, ALTERA O VALOR FINAL
			// 		$total = $valorVencimentoMod;

			// 		echo "TESTE DE VARIAVEL = ".$valorVencimentoMod;

			// 	}
			// }


			$financeiroProdutoID 	= $row[Financeiro_Produto_ID];
			$produtoVariacaoID 		= $row[Produto_Variacao_ID];
			$produtoID 				= $row[Produto_ID];
			$ncm 					= $row[NCM];
			$tipo 					= $row[Tipo];
			$tipoProduto 			= $row[Tipo_Produto];
			$industrializado 		= $row[Industrializado];
			if (($row[Produto_ID]=="-1") || ($row[Produto_ID]=="-2")) $descricaoProdutoServico = $row[Produto_Descricao]; else $descricaoProdutoServico = $row[Produto];

			$origem = "";
			if ($row['Tabela_Estrangeira']=="orcamentos") $origem = "Or&ccedil;amentos: <span class='link link-orcamento' proposta-id='$row[Referencia_ID]'>$row[Referencia_ID]</span>";
			if ($row['Tabela_Estrangeira']=="compras") $origem = "Compras: <span class='link link-ordem-compra' ordem-compra-id='$row[Referencia_ID]'>$row[Referencia_ID]</span>";
			if ($row['Tabela_Estrangeira']=="chamados") $origem = $_SESSION['objeto'].": <span class='link link-chamado' workflow-id='$row[Referencia_ID]'>$row[Referencia_ID]</span>";
			if ($origem == ""){
				$origem = "Financeiro";
			}
			else{
				$exibirOpcoesAtualizacaoProduto = "esconde";
			}
			//$linhas .= "<table width='100%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'><tr><td style='border:1px;'>";
			$linhas .= "<fieldset style='margin-bottom:15px;' class='bloco-produto-financeiro-$financeiroProdutoID'>
							<legend style='margin:5px;'><b> <span style='color:#1908EC'>".str_pad(($i+1), 2, "0", STR_PAD_LEFT)."</span> - $tipo</b></legend>";
			$linhas .= "	<div class='titulo-secundario' style='float:left; width:100%; height:50px'>
								<input type='hidden' name='produto-id[]' id='produto-id-$i' class='produto-id' value='$produtoID'/>
								<input type='hidden' name='financeiro-produto-id[]' class='financeiro-produto-id' value='$financeiroProdutoID'/>
								<input type='hidden' name='produto-variacao-id[]' class='produto-variacao-id' value='$produtoVariacaoID'/>
								<input type='hidden' name='tipo-produto[]' id='tipo-produto-$i' class='tipo-produto' value='$tipoProduto'/>
								<input type='hidden' name='industrializado' class='industrializado' value='$industrializado'/>
								<input type='hidden' name='tabela-estrangeira[]' value='".$row['Tabela_Estrangeira']."'/>
								<input type='hidden' name='chave-estrangeira[]' value='".$row['Referencia_ID']."'/>
								<input type='hidden' name='chave-estrangeira-produto[]' value='".$row['Chave_Estrangeira_Produto']."'/>

								<div class='titulo-secundario' style='float:left; width:50%'>
									<p><b>Descri&ccedil;&atilde;o:</b></p>
									<p><input type='text' name='descricao-produto[]' class='descricao-produto' maxlength='250' value='$descricaoProdutoServico' style='width:97%'/></p>
									<p style='margin-top:5px;' class='esconde'>".($row[Produto])."</p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>Quantidade:</b></p>
									<p><input type='text' name='quantidade-produto[]' class='quantidade-produto formata-valor' maxlength='25' value='".number_format($row[Quantidade], 2, ',', '.')."' style='width:85%'/></p>
									<p style='margin-top:5px;' class='esconde'>".number_format($row[Quantidade], 2, ',', '.')."</p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>Valor Unitario:</b></p>
									<p><input type='text' name='valor-unitario-produto[]' class='valor-unitario-produto formata-valor' maxlength='25' value='".number_format($row[Valor_Produto], 2, ',', '.')."' style='width:85%'/></p>
									<p style='margin-top:5px;' class='esconde'>".number_format($row[Valor_Produto], 2, ',', '.')."</p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>Valor Total:</b></p>
									<p><input type='text' name='valor-total-produto[]' class='valor-total-produto' maxlength='25' value='".number_format(($row[Quantidade] * $row[Valor_Produto]), 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									<p style='margin-top:5px;' class='esconde'>".number_format($total, 2, ',', '.')."</p>
								</div>
								<div class='titulo-secundario' style='float:left; width:7.5%'>
									<p><b>Origem:</b></p>
									<p style='margin-top:5px;'>".($origem)."</p>
								</div>
								<div class='titulo-secundario $exibirOpcoesAtualizacaoProduto' style='float:left; width:12.5%; position: relative;' >
									<div class='btn-editar botao-incluir-alterar-produto' style='position:absolute; top:0px; right:5px' financeiro-produto-id='$financeiroProdutoID' title='Editar'>&nbsp;</div>
									<div class='btn-excluir botao-excluir-produto' style='position:absolute; top:0px; right:25px' financeiro-produto-id='$financeiroProdutoID' title='Excluir'>&nbsp;</div>
									<p><b>Usu&aacute;rio:</b></p>
									<p style='margin-top:5px;'>".$row['Usuario_Inclusao']."<br>".converteDataHora($row['Data_Inclusao'],1)."</p>
								</div>
							</div>";
			if (($modulosAtivos['nfe']) && ($produtos>0)){
				if(($nfID!="")&&(count($xmlGerado)>0)){
					if ($statusNF=="100"){
						$xml = $xmlGerado->NFe;
					}
					else{
						$xml = $xmlGerado;
					}

					$json = json_encode($xml);
					$arr = json_decode($json,TRUE);
					//$arr = iterator_to_array($xml);

					if (($produtos + $servicos)>1){
						$det = $arr[infNFe][det][$i];
					}
					else{
						$det = $arr[infNFe][det];
					}
					$cfop = $det[prod][CFOP];
					$ncm = $det[prod][NCM];
					$descricaoProduto = $det[prod][xProd];
					$codigoProduto = $det[prod][cProd];

					/************* ISSQN - SERVIÇO *************/
					if ($det[imposto][ISSQN]){
						$baseCalculoISSQN = $det[imposto][ISSQN][vBC];
						$percentualISSQN = $det[imposto][ISSQN][vAliq];
						$valorISSQN = $det[imposto][ISSQN][vISSQN];
						$codigoMunicipioISSQN = $det[imposto][ISSQN][cMunFG];
						$listaServico = $det[imposto][ISSQN][cListServ];
						$cstISSQN = $det[imposto][ISSQN][cSitTrib];
					}


					/************* ICMS E ICMSSN - PRODUTOS *************/
					if ($det[imposto][ICMS]){
						$blocoCST = array_keys($det[imposto][ICMS]);
						if ($blocoCST[0]=="ICMS00"){
							$cstICMS 			= $det[imposto][ICMS][$blocoCST[0]][CST];
							$percentualICMS 	= $det[imposto][ICMS][$blocoCST[0]][pICMS];
							$baseCalculoICMS 	= $det[imposto][ICMS][$blocoCST[0]][vBC];
							$valorICMS 			= $det[imposto][ICMS][$blocoCST[0]][vICMS];
						}
						if (($blocoCST[0]=="ICMSSN102") || ($blocoCST[0]=="ICMSSN103") || ($blocoCST[0]=="ICMSSN300") || ($blocoCST[0]=="ICMSSN400")){
							$cstICMS = $det[imposto][ICMS][$blocoCST[0]][CSOSN];
						}
						if (($blocoCST[0]=="ICMSSN101")){
							$cstICMS = $det[imposto][ICMS][$blocoCST[0]][CSOSN];
						}
						if (($blocoCST[0]=="ICMSSN500")){
							$cstICMS = $det[imposto][ICMS][$blocoCST[0]][CSOSN];
						}
					}

					/************* IPI *************/
					if ($det[imposto][IPI]){
						if ($det[imposto][IPI][IPITrib]){
							$cstIPI = $det[imposto][IPI][IPITrib][CST];
							$baseCalculoIPI = $det[imposto][IPI][IPITrib][vBC];
							$percentualIPI = $det[imposto][IPI][IPITrib][pIPI];
							$valorIPI = $det[imposto][IPI][IPITrib][vIPI];
						}
						if ($det[imposto][IPI][IPINT]){
							$cstIPI = $det[imposto][IPI][IPINT][CST];
						}
					}


					/************* PIS *************/
					if ($det[imposto][PIS]){
						if ($det[imposto][PIS][PISAliq]){
							$cstPIS = $det[imposto][PIS][PISAliq][CST];
							$baseCalculoPIS = $det[imposto][PIS][PISAliq][vBC];
							$percentualPIS = $det[imposto][PIS][PISAliq][pPIS];
							$valorPIS = $det[imposto][PIS][PISAliq][vPIS];
						}
						if ($det[imposto][PIS][PISNT]){
							$cstPIS = $det[imposto][PIS][PISNT][CST];
						}
						if ($det[imposto][PIS][PISOutr]){
							$cstPIS = $det[imposto][PIS][PISOutr][CST];
							$baseCalculoPIS = $det[imposto][PIS][PISOutr][vBC];
							$percentualPIS = $det[imposto][PIS][PISOutr][pPIS];
							$valorPIS = $det[imposto][PIS][PISOutr][vPIS];
						}
					}

					/************* COFINS *************/
					//echo "<pre>";
					//print_r($det[imposto][COFINS]);
					//echo "</pre>";
					if ($det[imposto][COFINS]){
						if ($det[imposto][COFINS][COFINSAliq]){
							$cstCOFINS = $det[imposto][COFINS][COFINSAliq][CST];
							$baseCalculoCOFINS = $det[imposto][COFINS][COFINSAliq][vBC];
							$percentualCOFINS = $det[imposto][COFINS][COFINSAliq][pCOFINS];
							$valorCOFINS = $det[imposto][COFINS][COFINSAliq][vCOFINS];
						}
						if ($det[imposto][COFINS][COFINSNT]){
							$cstCOFINS = $det[imposto][COFINS][COFINSNT][CST];
						}
						if ($det[imposto][COFINS][COFINSOutr]){
							$cstCOFINS = $det[imposto][COFINS][COFINSOutr][CST];
							$baseCalculoCOFINS = $det[imposto][COFINS][COFINSOutr][vBC];
							$percentualCOFINS = $det[imposto][COFINS][COFINSOutr][pCOFINS];
							$valorCOFINS = $det[imposto][COFINS][COFINSOutr][vCOFINS];
						}
					}
				}
				//if (($statusNF!="100") && (!(is_array($xml)))){
				if (($statusNF!="100") && (!(count($xmlGerado)>0))){
					$percentualICMS = $configNF[percentual_icms_padrao_saida];
					$percentualIPI = $configNF[percentual_ipi_padrao_saida];
					$percentualPIS = $configNF[percentual_pis_padrao_saida];
					$percentualCOFINS = $configNF[percentual_cofins_padrao_saida];
					$cfop = $configNF[cfop_padrao_saida];

					// criar no arquivo de config da empresa
					$percentualISSQN = $configNF[percentual_issqn_servico_padrao];
					$listaServico = soNumeros($configNF[lista_servico_padrao]);
					$cstISSQN = $configNF[cst_icsqn_servico_padrao];

					$cstICMS = $configNF[cst_icms_padrao_saida];
					$cstIPI = $configNF[cst_ipi_padrao_saida];
					$cstPIS = $configNF[cst_pis_padrao_saida];
					$cstCOFINS = $configNF[cst_cofins_padrao_saida];

					$baseCalculoICMS = $row[Quantidade] * $row[Valor_Produto];
					$valorICMS = (($percentualICMS * $baseCalculoICMS)/100);

					$baseCalculoIPI = $row[Quantidade] * $row[Valor_Produto];
					$valorIPI = (($percentualIPI * $baseCalculoIPI)/100);

					$baseCalculoPIS = $row[Quantidade] * $row[Valor_Produto];
					$valorPIS = (($percentualPIS * $baseCalculoPIS)/100);

					$baseCalculoCOFINS = $row[Quantidade] * $row[Valor_Produto];
					$valorCOFINS = (($percentualCOFINS * $baseCalculoCOFINS)/100);

					$baseCalculoISSQN = $row[Quantidade] * $row[Valor_Produto];
					$valorISSQN = (($percentualISSQN * $baseCalculoISSQN)/100);

					$codigoMunicipioISSQN = localizaCodigoMunicipio($configNF[UF],$configNF[cidade]);
				}


				if (($tipoProduto=="30")||($tipoProduto=="100")||($tipoProduto=="175")){
					$linhas .= "<input type='hidden' name='lista-servico[]' class='lista-servico' value=''/>
								<input type='hidden' name='cst-icsqn-servico[]' class='cst-icsqn-servico' value=''/>
								<input type='hidden' name='percentual-issqn-servico[]' class='percentual-issqn-servico' value=''/>
								<input type='hidden' name='base-calculo-issqn-servico[]' class='base-calculo-issqn-servico' value=''/>
								<input type='hidden' name='valor-issqn-servico[]' class='valor-issqn-servico' value=''/>
								<input type='hidden' name='codigo-municipio-issqn[]' class='codigo-municipio-issqn' value=''/>";

					$linhas .= "<div class='titulo-secundario' style='float:left; width:100%'>
									<div class='titulo-secundario' style='float:left; width:100%'>
										<div class='titulo-secundario' style='float:left; width:40%'>
											<p><b>Tributa&ccedil;&atilde;o ICMS</b></p>
											<p><select name='cst-icms-produto[]' class='cst-icms-produto obrigatorio' bloco-pai='menu-superior-2'><option value=''></option>".(optionValueTributacao($arrayICMS, $cstICMS))."</select></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-aliq-icms-$i'>
											<p><b>Al&iacute;quota</b></p>
											<p><input type='text' name='percentual-icms-produto[]' class='percentual-icms-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='6' value='".number_format($percentualICMS, 2, ',', '.')."' style='width:85%'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-bc-icms-$i'>
											<p><b>BC ICMS</b></p>
											<p><input type='text' name='base-calculo-icms-produto[]' class='base-calculo-icms-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($baseCalculoICMS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-valor-icms-$i'>
											<p><b>Valor ICMS</b></p>
											<p><input type='text' name='valor-icms-produto[]' class='valor-icms-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorICMS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										<!--
										<div class='titulo-secundario' style='float:left; width:10%' id='div-valor-icms-$i'>
											<p><b>Percentual Aliq ICMS</b></p>
											<p><input type='text' name='valor-icms-produto[]' class='valor-icms-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorICMS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										-->
										<div class='titulo-secundario' style='float:left; width:10%' id='div-dados-cst500-$i'>
										
										
										</div>
									</div>";

					if ($row[Industrializado]==1){
						$linhas .= "
									<div class='titulo-secundario' style='float:left; width:100%'>
										<div class='titulo-secundario' style='float:left; width:40%'>
											<p><b>Tributa&ccedil;&atilde;o IPI</b></p>
											<p><select name='cst-ipi-produto[]' class='cst-ipi-produto obrigatorio' bloco-pai='menu-superior-2'><option value=''></option>".(optionValueTributacao($arrayIPI,$cstIPI))."</select></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-aliq-ipi-$i'>
											<p><b>Al&iacute;quota</b></p>
											<p><input type='text' name='percentual-ipi-produto[]' class='percentual-ipi-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='6' value='".number_format($percentualIPI, 2, ',', '.')."' style='width:85%'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-bc-ipi-$i'>
											<p><b>BC IPI</b></p>
											<p><input type='text' name='base-calculo-ipi-produto[]' class='base-calculo-ipi-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($baseCalculoIPI, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%' id='div-valor-ipi-$i'>
											<p><b>Valor IPI</b></p>
											<p><input type='text' name='valor-ipi-produto[]' class='valor-ipi-produto' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorIPI, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
									</div>";
					}
					else{
						$linhas .= "<input type='hidden' name='cst-ipi-produto[]' class='cst-ipi-produto' value=''/>
									<input type='hidden' name='percentual-ipi-produto[]' class='percentual-ipi-produto' value=''/>
									<input type='hidden' name='base-calculo-ipi-produto[]' class='base-calculo-ipi-produto' value=''/>
									<input type='hidden' name='valor-ipi-produto[]' class='valor-ipi-produto' value=''/>";
					}
				}
				if ($tipoProduto=="31"){
					$linhas .= "<input type='hidden' name='cst-icms-produto[]' class='cst-icms-produto' value=''/>
								<input type='hidden' name='percentual-icms-produto[]' class='percentual-icms-produto' value=''/>
								<input type='hidden' name='base-calculo-icms-produto[]' class='base-calculo-icms-produto' value=''/>
								<input type='hidden' name='valor-icms-produto[]' value='' class='valor-icms-produto'/>
								<input type='hidden' name='cst-ipi-produto[]' value='' class='cst-ipi-produto'/>
								<input type='hidden' name='percentual-ipi-produto[]' value='' class='percentual-ipi-produto'/>
								<input type='hidden' name='base-calculo-ipi-produto[]' value='' class='base-calculo-ipi-produto'/>
								<input type='hidden' name='valor-ipi-produto[]' value='' class='valor-ipi-produto'/>";

					$linhas .= "<div class='titulo-secundario' style='float:left; width:100%'>
									<div class='titulo-secundario' style='float:left; width:100%'>
										<div class='titulo-secundario' style='float:left; width:15%'>
											<p><b>Tributa&ccedil;&atilde;o ISSQN</b></p>
											<p><select name='cst-icsqn-servico[]' class='cst-icsqn-servico obrigatorio' bloco-pai='menu-superior-2' style='width:95%'><option value=''></option>".(optionValueTributacao($arrayISSQN, $cstISSQN))."</select></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:25%'>
											<p><b>Lista de Servi&ccedil;os</b></p>
											<p><select name='lista-servico[]' class='lista-servico obrigatorio' bloco-pai='menu-superior-2' style='width:95%'><option value=''></option>".(optionValueTributacao($arrayLC, $listaServico, 1))."</select></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%'>
											<p><b>Al&iacute;quota</b></p>
											<p><input type='text' name='percentual-issqn-servico[]' class='percentual-issqn-servico obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($percentualISSQN, 2, ',', '.')."'  style='width:85%'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%'>
											<p><b>BC ISSQN</b></p>
											<p><input type='text' name='base-calculo-issqn-servico[]' class='base-calculo-issqn-servico obrigatorio ' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($baseCalculoISSQN, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%'>
											<p><b>Valor ISSQN</b></p>
											<p><input type='text' name='valor-issqn-servico[]' class='valor-issqn-servico obrigatorio' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorISSQN, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
										</div>
										<div class='titulo-secundario' style='float:left; width:10%'>
											<p><b>Munic&iacute;pio</b></p>
											<p><input type='text' name='codigo-municipio-issqn[]' class='codigo-municipio-issqn obrigatorio' bloco-pai='menu-superior-2' maxlength='20' value='$codigoMunicipioISSQN' style='width:85%'/></p>
										</div>
									</div>";
				}
				$linhas .= "	<div class='titulo-secundario' style='float:left; width:100%'>
									<div class='titulo-secundario' style='float:left; width:40%'>
										<p><b>Tributa&ccedil;&atilde;o PIS</b></p>
										<p><select name='cst-pis-produto[]' class='cst-pis-produto obrigatorio' bloco-pai='menu-superior-2'><option value=''></option>".(optionValueTributacao($arrayPISCONFINS,$cstPIS))."</select></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-aliq-pis-$i'>
										<p><b>Al&iacute;quota</b></p>
										<p><input type='text' name='percentual-pis-produto[]' class='percentual-pis-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='6' value='".number_format($percentualPIS, 2, ',', '.')."' style='width:85%'/></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-bc-pis-$i'>
										<p><b>BC PIS</b></p>
										<p><input type='text' name='base-calculo-pis-produto[]' class='base-calculo-pis-produto' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($baseCalculoPIS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-valor-pis-$i'>
										<p><b>Valor PIS</b></p>
										<p><input type='text' name='valor-pis-produto[]' class='valor-pis-produto' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorPIS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									</div>
								</div>";

				$linhas .= "	<div class='titulo-secundario' style='float:left; width:100%'>
									<div class='titulo-secundario' style='float:left; width:40%'>
										<p><b>Tributa&ccedil;&atilde;o COFINS</b></p>
										<p><select name='cst-cofins-produto[]' class='cst-cofins-produto obrigatorio' bloco-pai='menu-superior-2'><option value=''></option>".(optionValueTributacao($arrayPISCONFINS,$cstCOFINS))."</select></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-aliq-cofins-$i'>
										<p><b>Al&iacute;quota</b></p>
										<p><input type='text' name='percentual-cofins-produto[]' class='percentual-cofins-produto obrigatorio formata-valor' bloco-pai='menu-superior-2' bloco-pai='menu-superior-2' maxlength='6' value='".number_format($percentualCOFINS, 2, ',', '.')."' style='width:85%'/></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-bc-cofins-$i'>
										<p><b>BC COFINS</b></p>
										<p><input type='text' name='base-calculo-cofins-produto[]' class='base-calculo-cofins-produto' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($baseCalculoCOFINS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									</div>
									<div class='titulo-secundario' style='float:left; width:10%' id='div-valor-cofins-$i'>
										<p><b>Valor COFINS</b></p>
										<p><input type='text' name='valor-cofins-produto[]' class='valor-cofins-produto' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($valorCOFINS, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									</div>
								</div>";

				if ($tipoID==44) $arrayCFOP = $arrayCFOPentrada;
				if ($tipoID==45) $arrayCFOP = $arrayCFOPsaida;
				$linhas .= "	<div class='titulo-secundario' style='float:left; width:50%'>
									<p><b>CFOP</b></p>
									<p><select name='cfop-produto[]' class='cfop-produto obrigatorio' id='cfop-produto-$i' indice='$i' bloco-pai='menu-superior-2' style='width:97.8%'><option value=''></option>".optionValueTributacao($arrayCFOP,$cfop,1)."</select></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>NCM</b></p>
									<p><input type='text' name='ncm-produto[]'  id='ncm-produto-$i' class='ncm-produto obrigatorio formata-numero' bloco-pai='menu-superior-2' maxlength='8' value='$ncm' indice='$i' style='width:85%'/></p>
								</div>
							</div>";
			}
			$linhas .= "</fieldset>";
			//$linhas .= "</td></tr></table>";
			$i++;
		}
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		echo "<div class='titulo'>
				<p>
					Produtos e Servi&ccedil;os
					<input type='button' style='float:right;margin-right:0px; width:130px' value='Incluir Produto ou Servi&ccedil;o' class='botao-incluir-alterar-produto' financeiro-produto-id=''>
					$botaoSuperior
				</p>
			  </div>
			  <div class='conteudo-interno'>
				<div class='titulo-secundario esconde' style='float:left; width:100%' id='incluir-produto-conta'></div>";
		echo $linhas;
		if ($i==-1){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum produto ou servi&ccedil;o cadastrado</p>";
		}
		echo "</div>";

	}

	function carregarEmissaoNF($contaID){
		global $dadosUserLogin, $modulosAtivos, $caminhoSistema, $caminhoFisico;

		require_once($caminhoFisico."/modulos/nfe/arrays-nfe.php");
		require_once($caminhoFisico."/modulos/nfe/functions.php");

		$sql = "select Tipo_ID, Cadastro_ID_de as Cadastro_ID_de from financeiro_contas where Conta_ID = '$contaID'";
		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$empresaID = $rs[Cadastro_ID_de];
			$tipo = $rs[Tipo_ID];
		}
		$sql = "select (select count(pd.Tipo_Produto) from financeiro_produtos fp
						inner join produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
						inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
						where fp.Conta_ID = '$contaID' and pd.Tipo_Produto NOT IN (30,100,175) and fp.Situacao_ID = 1) as Servicos,
						(select count(pd.Tipo_Produto) from financeiro_produtos fp
						inner join produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
						inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
						where fp.Conta_ID = '$contaID' and pd.Tipo_Produto IN (30,100,175) and fp.Situacao_ID = 1) as Produtos";
		//echo $sql;
		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$servicos = $rs[Servicos];
			$produtos = $rs[Produtos];
		}
		$configNF = retornaArrayConfigNF($empresaID);
		if ($tipo=="45"){
			$descricaoNF = "Nota Fiscal de SA&Iacute;DA";
			$cfopDescr = $configNF[cfop_descr_padrao_saida];
		}
		else{
			$descricaoNF = "Nota Fiscal de ENTRADA";
			$cfopDescr = $configNF[cfop_descr_padrao_entrada];
		}
		// se NF for de apenas SERVIÇOS
		//if ($produtos == 0){
			//if ($servicos>0){
			//	$botaoSuperior = "<input type='button' style='float:right;margin-right:0px; width:120px' value='Emitir NFS-e (Servi&ccedil;os)' id='botao-gerar-xml-servicos'/>";
			//}
?>
<!--
			<div class="titulo-container conjunto5">
				<div class="titulo">
					<p><b><?php echo $descricaoNF;?></b><?php echo $botaoSuperior;?></p>
				</div>
				<div class="conteudo-interno">
					<input type='hidden' id='nf-id' name='nf-id' value="<?php echo $nfID; ?>"/>
					<div class='titulo-secundario' style='float:left;width:100%;'>
					</div>
				</div>
			</div>
-->
<?php
		//}
		//else{
			if (($modulosAtivos[nfe])&&($contaID!="")&&($empresaID!="")){
				$retornoProtocolo = $retornoAssinatura = $retornoValidacao = $retornoTransmissao = "<p>N&atilde;o Realizado</p>";
				if ($tipo=="45"){ $serie = 1; } else{ $serie = 2; }
				$NFCancelada = false;
				$escondeCancelar = "esconde";
				$botaoCancelarNFE = "<input type='button' style='float:right;margin-right:0px; background-color:#E58989;'  value='Cancelar NF-E' class='botao-cancelar-nfe-a1'>";
				$justificativa = "<textarea name='justificativa-cancelamento-nfe' id='justificativa-cancelamento-nfe' style='width:99%;height:40px'></textarea>";

				$sql = "select NF_ID, Serie, Numero_NF, Status_NF, Chave_Acesso, Recibo, Protocolo, NF_XML, NF_Array, NF_Dados from nf_dados where Conta_ID = '$contaID' and Ambiente = '".$configNF[ambiente]."' order by NF_ID desc ";
				//echo $sql;
				$resultado = mpress_query($sql);
				if ($rs = mpress_fetch_array($resultado)){
					if ($rsCanc = mpress_fetch_array(mpress_query("select Justificativa, Retorno, Usuario_Cadastro_ID, Data_Cadastro from nf_canceladas where NF_ID = '".$rs[NF_ID]."' and Erro = 0"))){
						$NFCancelada = true;
						$escondeCancelar = "";
						$botaoCancelarNFE = "";
						$justificativa = $rsCanc['Justificativa'];
						$retorno = $rsCanc['Retorno'];
					}

					$nfID = $rs['NF_ID'];
					$numeroNF = $rs['Numero_NF'];
					$serie = $rs['Serie'];
					$statusNF = $rs['Status_NF'];
					$dadosNF = unserialize($rs['NF_Dados']);

					$xmlGerado = simplexml_load_string($rs[NF_XML]);
					if ($statusNF=="100") $xml = $xmlGerado->NFe; else $xml = $xmlGerado;
					$json = json_encode($xml);
					$arr = json_decode($json,TRUE);
					$cfopDescr = $arr[infNFe][ide][natOp];

					$retornos = unserialize($rs[NF_Array]);
					$retornoArquivo = $retornos[retornoArquivo];
					$retornoAssinatura = $retornos[retornoAssinatura];
					$retornoValidacao = $retornos[retornoValidacao];
					$retornoTransmissao = $retornos[retornoTransmissao];
					$retornoProtocolo = $retornos[retornoProtocolo];
					$retornoArquivo = "<a href='$retornoArquivo' target='_blank'> VISUALIZAR XML </a>";

					$chave = $rs[Chave_Acesso];
					$recibo = $rs[Recibo];
					$protocolo = $rs[Protocolo];

				}
				if ($statusNF=="100"){
					if ($NFCancelada==false){
						$botaoSuperior = "<input type='button' style='float:right;margin-right:0px;' value='Imprimir DANFE' class='botao-imprimir-danfe-a1'>";
						$botaoCancelar = "<input type='button' style='float:right;margin-right:0px; background-color:#E58989;'  value='Cancelar NF-E' class='botao-exibir-cancelar-nfe-a1'>";
						$blocoCFOP = "readonly='readonly'";
						$textoSuperior = " - <span class='destaque'>EMITIDA COM SUCESSO</span>";
					}
					else{
						$botaoSuperior = "<input type='button' style='float:right;margin-right:0px;' value='Reemitir NF-e (A1)' class='botao-gerar-xml-a1'>";
						$textoSuperior = " - <span class='lixeira'>CANCELADA</span>";

					}
				}
				else{
					$botaoSuperior = "<input type='button' style='float:right;margin-right:0px;' value='Emitir NF-e (A1)' class='botao-gerar-xml-a1'>";
				}

				if ($configNF[ambiente]==1) $ambiente = "Produ&ccedil;&atilde;o";
				if ($configNF[ambiente]==2) $ambiente = "Homologa&ccedil;&atilde;o";

				echo "	<div class='titulo-container conjunto5'>
							<div class='titulo'>
								<p><b>$descricaoNF $textoSuperior $botaoSuperior $botaoCancelar</b></p>
							</div>
							<div class='conteudo-interno'>
								<input type='hidden' id='nf-id' name='nf-id' value='$nfID'/>
								<div class='titulo-secundario' style='float:left;width:5%;'>
									<p><b>S&eacute;rie:</b><p>
									<p><input type='text' name='nf-serie' id='nf-serie' style='width:70%' maxlength='3' value='$serie' readonly/><p>
								</div>
								<div class='titulo-secundario' style='float:left;width:20%;'>
									<p><b>N&uacute;mero:</b><p>
									<p><input type='text' name='nf-numero' id='nf-numero' style='width:90%' maxlength='10' value='$numeroNF' /><p>
								</div>
								<div class='titulo-secundario' style='float:left;width:50%;'>
									<p><b>CFOP:</b><p>
									<p><input type='text' name='nfe-natureza-operacao' id='nfe-natureza-operacao' class='obrigatorio' bloco-pai='menu-superior-5' style='width:90%' value='$cfopDescr' $blocoCFOP/></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:25%;'>
									<p><b>Ambiente:</b><p>
									<p><input type='text' style='width:90%' value='$ambiente' readonly/></p>
									<input type='hidden' name='ambiente' id='ambiente' value='".$configNF[ambiente]."'/>
								</div>
							</div>
						</div>";

				$escondeFrete = "";
				$obrigatorio = "";
				if ($dadosNF['modFrete']==''){
					$dadosNF['modFrete'] = "9";
				}
				if ($dadosNF['modFrete']==9){
					$escondeFrete = "esconde";
					$obrigatorio = "obrigatorio";
				}
				$selFrete[$dadosNF['modFrete']] = "selected";
				echo "	<div class='titulo-container conjunto5'>
							<div class='titulo'>
								<p>Dados de Transporte<input type='button' class='editar-cadastro-generico' style='float:right;margin-right:0px; width:50px;' value='Alterar' id='botao-alterar-transportadora-id' campo-alvo='transportadora-id'></p>
							</div>
							<div class='conteudo-interno'>
								<div class='titulo-secundario' style='float:left;width:100%;'>
									<p><b>Modalidade do frete:</b><p>
									<p>
										<select name='dados[modFrete]' id='modFrete' class='obrigatorio'>
											<option value='9' ".$selFrete[9].">9 - Sem frete</option>
											<option value='0' ".$selFrete[0].">0 - Por conta do emitente</option>
											<option value='1' ".$selFrete[1].">1 - Por conta do destinat&aacute;rio / remetente</option>
											<option value='2' ".$selFrete[2].">2 - Por conta de terceiros</option>
										</select>
									</p>
								</div>";
				echo "			<div class='dados-transportadora ".$escondeFrete."'>
									<div class='titulo-secundario' style='float:left;width:100%;'>
										<span style='float:left;'><b>Transportadora:</b></span>";
				carregarBlocoCadastroGeral($dadosNF['transportadora-id'], 'transportadora-id','Transportadora',1,'','','','obrigatorio menu-superior-5 obriga-frete', "bloco-pai='menu-superior-5'");
				echo "				</div>";
				echo "				<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Esp&eacute;cie dos volumes transportados:</b><p>
										<p><input type='text' name='dados[esp]' id='esp' style='width:98%' value='".$dadosNF[esp]."' maxlength='60' class='".$obrigatorio." obriga-frete' bloco-pai='menu-superior-5'/></p>
									</div>
									<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Marca dos volumes transportados:</b><p>
										<p><input type='text' name='dados[marca]' id='marca' style='width:98%' value='".$dadosNF[marca]."' maxlength='60' class=''/></p>
									</div>
									<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Numera&ccedil;&atilde;o dos volumes transportados:</b><p>
										<p><input type='text' name='dados[nVol]' id='nVol' style='width:98%' value='".$dadosNF[nVol]."' maxlength='60'/></p>
									</div>
									<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Peso L&iacute;quido (em kg):</b><p>
										<p><input type='text' name='dados[pesoL]' id='pesoL' style='width:98%' value='".$dadosNF[pesoL]."' maxlength='60' class='formata-valor-decimal-3 ".$obrigatorio." obriga-frete' bloco-pai='menu-superior-5'/></p>
									</div>
									<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Peso Bruto (em kg):</b><p>
										<p><input type='text' name='dados[pesoB]' id='pesoB' style='width:98%' value='".$dadosNF[pesoB]."' maxlength='60' class='formata-valor-decimal-3 ".$obrigatorio." obriga-frete' bloco-pai='menu-superior-5'/></p>
									</div>
									<div class='titulo-secundario' style='float:left;width:33%;'>
										<p><b>Quantidade de volumes transportados:</b><p>
										<p><input type='text' name='dados[qVol]' id='qVol' style='width:98%' value='".$dadosNF[qVol]."' maxlength='60' class='".$obrigatorio." obriga-frete' readonly bloco-pai='menu-superior-5'/></p>
									</div>";
				echo "			</div>
							</div>
						</div>";


				$selConsumidorFinal[$dadosNF['indFinal']] = "selected";
				$selPresenca[$dadosNF['indPres']] = "selected";

				echo "	<div class='titulo-container conjunto5'>
							<div class='titulo'>
								<p>Informa&ccedil;&otilde;es Complementares</p>
							</div>
							<div class='conteudo-interno'>
								<div class='titulo-secundario' style='float:left;width:100%;'>
									<p><b>Informa&ccedil;&otilde;es Complementares de interesse do Contribuinte</b><p>
									<p><textarea name='dados[infCpl]' id='infCpl' style='width:98%; height:60px;' maxlength='2000'>".$dadosNF[infCpl]."</textarea></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:50%;'>
									<p><b>Opera&ccedil;&atilde;o com consumidor final:</b><p>
									<p>
										<select name='dados[indFinal]' id='indFinal' class='obrigatorio'>
											<option value='0' ".$selConsumidorFinal[0].">0 - Normal</option>
											<option value='1' ".$selConsumidorFinal[1].">1 - Consumidor final</option>
										</select>
									</p>
								</div>
								<div class='titulo-secundario' style='float:left;width:50%;'>
									<p><b>Presen&ccedil;a do comprador no estabelecimento </b><p>
									<p>
										<select name='dados[indPres]' id='indPres' class='obrigatorio'>
											<option value='1' ".$selPresenca[1].">1 - Opera&ccedil;&atilde;o presencial</option>
											<option value='2' ".$selPresenca[2].">2 - Opera&ccedil;&atilde;o n&atilde;o presencial, pela Internet</option>
											<option value='3' ".$selPresenca[3].">3 - Opera&ccedil;&atilde;o n&atilde;o presencial, Teleatendimento</option>
										</select>
									</p>
								</div>
							</div>
						</div>";

				echo "	<div class='titulo-container conjunto5'>
							<div class='titulo'>
								<p><b>Retorno Receita</b></p>
							</div>
							<div class='conteudo-interno'>
								<div class='div-mensagem-aguardando' style='float:left; width:100%;'></div>
								<div class='titulo-secundario' style='float:left;width:100%;'>
									<table style='width:100%;margin-top:10px;' cellpadding='5' cellspacing='3'>
										<tr>
											<td class='fundo-escuro-titulo'><p>Arquivo XML</p></td>
											<td class='fundo-claro'><p>$retornoAssinatura $retornoArquivo</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Valida&ccedil;&atilde;o estrutura do XML</p></td>
											<td class='fundo-claro'><p>$retornoValidacao</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Transmiss&atilde;o XML</p></td>
											<td class='fundo-claro'><p>$retornoTransmissao</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Retorno Receita </p></td>
											<td class='fundo-claro'><p>$retornoProtocolo</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Chave</p></td>
											<td class='fundo-claro'><p>$chave</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Recibo</p></td>
											<td class='fundo-claro'><p>$recibo</p></td>
										</tr>
										<tr>
											<td class='fundo-escuro-titulo'><p>Protocolo</p></td>
											<td class='fundo-claro'><p>$protocolo</p></td>
										</tr>
									</table>
								</div>
							</div>
						</div>";

				if ($statusNF=="100"){
					echo "<div class='$escondeCancelar bloco-cancelar-nfe-a1'>
							<div class='titulo-container conjunto5'>
								<div class='titulo'>
									<p>Cancelamento de NF-e ".$textoSuperior ." ".$botaoCancelarNFE."</p>
								</div>
								<div class='conteudo-interno'>
									<div class='titulo-secundario' style='float:left; width:100%; margin-top:5px'>
										<p>Justificativa:</p>
										<p style='margin-top:3px'><b>".$justificativa."</b></p>
									</div>
								</div>
						 	</div>
						  </div>";
				}

				//if (($statusNF=="100") && ($NFCancelada)){
				$i = 0;
				$sql = "SELECT n.NF_ID, n.Numero_NF, n.Serie, n.Empresa_ID, n.Chave_Acesso, n.Data_Emissao, emi.Nome as Emitente, c.Data_Cadastro as Data_Cancelamento, can.Nome as Usuario_Cancelamento
							from nf_dados n
							inner join nf_canceladas c on c.NF_ID = n.NF_ID and c.Erro = 0
							left join cadastros_dados emi on emi.Cadastro_ID = n.Usuario_Emissao_ID
							left join cadastros_dados can on can.Cadastro_ID = c.Usuario_Cadastro_ID
							where n.Conta_ID = '$contaID' and n.Ambiente = '".$configNF[ambiente]."' and n.Status_NF = '100'
							and n.NF_ID <> '$nfID'
							order by n.NF_ID";

				$resultado = mpress_query($sql);
				while ($rs = mpress_fetch_array($resultado)){
					$i++;
					$dados[colunas][conteudo][$i][1] = $rs[Serie];
					$dados[colunas][conteudo][$i][2] = $rs[Numero_NF];
					$dados[colunas][conteudo][$i][3] = $rs[Chave_Acesso];
					$dados[colunas][conteudo][$i][4] = $rs[Emitente];
					$dados[colunas][conteudo][$i][5] = converteDataHora($rs[Data_Emissao],1);
					$dados[colunas][conteudo][$i][6] = $rs[Usuario_Cancelamento];
					$dados[colunas][conteudo][$i][7] = converteDataHora($rs[Data_Emissao],1);
				}
				if ($i>0){
					$largura = "100%";
					$colunas = "7";
					$dados[colunas][titulo][1] 	= "S&eacute;rie";
					$dados[colunas][titulo][2] 	= "N&uacute;mero";
					$dados[colunas][titulo][3] 	= "Chave";
					$dados[colunas][titulo][4] 	= "Usu&aacute;rio Emissor";
					$dados[colunas][titulo][5] 	= "Data Emiss&atilde;o";
					$dados[colunas][titulo][6] 	= "Usu&aacute;rio Cancelamento";
					$dados[colunas][titulo][7] 	= "Data Cancelamento";

					echo "	<div class='titulo-container conjunto5'>
								<div class='titulo'>
									<p>Obseva&ccedil;&otilde;es</p>
								</div>
								<div class='conteudo-interno'>
									<div class='titulo-secundario' style='float:left; width:100%; margin-top:5px'>
										<p>Esta conta possui Notas Fiscais Eletr&ocirc;nicas emitidas e canceladas anteriormente:</p>
										<p>";
					geraTabela($largura, $colunas, $dados, "", "tabela-nfe-a1-canceladas", 2, 2, 100, "");
					echo "			</div>
								</div>
							 </div>";
				}
			//}
		}
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function salvarDadosConta(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$cadastroContaID = $_POST['cadastro-conta-id'];
		$nomeConta = utf8_decode($_POST['nome-conta']);
		$tipoConta = $_POST['tipo-conta'];
		$empresaID = $_POST['empresa-id'];
		$saldoInicial = formataValorBD($_POST['saldo-inicial']);
		$situacaoID = $_POST['situacao-conta'];
		$_POST['dados']['observacoes'] = utf8_decode($_POST['dados'][observacoes]);
		$_POST['dados']['texto-bloco-cabecalho'] = utf8_decode($_POST['dados']['texto-bloco-cabecalho']);
		$_POST['dados']['texto-bloco-demonstrativo'] = utf8_decode($_POST['dados']['texto-bloco-demonstrativo']);
		$_POST['dados']['texto-bloco-instrucoes']= utf8_decode($_POST['dados']['texto-bloco-instrucoes']);
		$dados = serialize($_POST['dados']);

		if ($cadastroContaID==""){
			$sql = "INSERT INTO cadastros_contas (Cadastro_ID, Nome_Conta, Tipo_Conta_ID, Dados, Saldo_Inicial, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
									VALUES ('".$empresaID."', '$nomeConta','".$tipoConta."', '".$dados."', '".$saldoInicial."', '$situacaoID', '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			mpress_query($sql);
			$cadastroContaID = mpress_identity();
		}
		else{
			$sql = "update cadastros_contas set Cadastro_ID = '".$empresaID."',
									Nome_Conta = '".$nomeConta."',
									Tipo_Conta_ID = '".$tipoConta."',
									Dados = '".$dados."',
									Saldo_Inicial = '".$saldoInicial."',
									Situacao_ID = '".$situacaoID."'
					where Cadastro_Conta_ID = '$cadastroContaID'";
			mpress_query($sql);

		}
		echo $cadastroContaID;
	}

	function carregarValoresCentroCusto(){
		global $caminhoSistema;
		$contaID = $_POST['conta-id'];

		$resultSet = mpress_query("select Centro_Custo_ID, Valor from financeiro_contas_centros_custos where Conta_ID = '$contaID' and Situacao_ID = 1");
		while($rs = mpress_fetch_array($resultSet)){
			$valor[$rs['Centro_Custo_ID']] = $rs['Valor'];
		}

		foreach($_POST['lancamento-centro-custo'] as $centroCusto){
			$resultSet = mpress_query("SELECT Descr_Tipo from tipo where Tipo_ID = ".$centroCusto);
			if($rs = mpress_fetch_array($resultSet))
				$centroCustoDesc = $rs['Descr_Tipo'];
			echo "	<div style='float:left; width:25%; margin-top:10px;'>
						<p><b>$centroCustoDesc</b></p>
						<p><input type='text' name='centro-custo-valor[$centroCusto]' id='centro-custo-valor-$centroCusto' class='formata-valor' value='".number_format($valor[$centroCusto], 2, ',', '.')."'></p>
					</div>
					<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		}
	}


	function optionValueBancos($selecionado){
		global $arrayBancos;
		if (!(is_array($arrayBancos))){
			include("arrays-bancos.php");
		}
		$sel[$selecionado] = " selected ";
		foreach ($arrayBancos as $chave => $campo){
			$optionValueBancos .= "<option value='".$chave."' ".$sel[$chave].">".$chave." - ".$arrayBancos[$chave]['descricao']."</option>";
		}
		return $optionValueBancos;
	}


	function carregarBlocoTipoContaCentroCusto($tipo, $contaID){
		global $configFinanceiro, $caminhoSistema;
		if ($_GET['acao']=='D'){
			$sqlLimit = " limit 1";
		}
		if ($configFinanceiro['exibe-centro-custo']==0) $escondeCentroCusto = " esconde ";
		$i = 0;
		$sql = "SELECT Contabil_ID, Centro_Custo_ID, Tipo_Conta_ID, Valor, Observacao FROM financeiro_contabil where Conta_ID = '$contaID' and Situacao_ID = 1 $sqlLimit";
		$resultado = mpress_query($sql);
		while ($rs = mpress_fetch_array($resultado)){
			$dados[$i]['Contabil_ID'] 		= $rs["Contabil_ID"];
			$dados[$i]['Centro_Custo_ID'] 	= $rs["Centro_Custo_ID"];
			$dados[$i]['Tipo_Conta_ID'] 	= $rs["Tipo_Conta_ID"];
			$dados[$i]['Valor'] 			= $rs["Valor"];
			$dados[$i]['Observacao'] 		= $rs["Observacao"];
			$i++;
		}
		if ($i==0)
			$i=1;

		if ($_POST['numero-opcoes'] > $i){
			$i = $_POST['numero-opcoes'];
			$valorTotal = formataValorBD($_POST['valor-total']);
		}
		for ($ii = 0; $ii<$i; $ii++){
			$totalParcelas += formataValorBD($_POST['valor-contabil'][$ii]);
			if($_POST['valor-contabil'][$ii]		!="") $dados[$ii]['Valor'] 				= formataValorBD($_POST['valor-contabil'][$ii]);
			if($_POST['observacao-contabil'][$ii]	!="") $dados[$ii]['Observacao'] 		= $_POST['observacao-contabil'][$ii];
			if($_POST['lancamento-tipo-conta'][$ii]	!="") $dados[$ii]['Tipo_Conta_ID']		= $_POST['lancamento-tipo-conta'][$ii];
			if($_POST['lancamento-centro-custo'][$ii]!="") $dados[$ii]['Centro_Custo_ID'] 	= $_POST['lancamento-centro-custo'][$ii];
			$tam = "50%";
			$hExtra1 = $hExtra2 = "";
			if ($i>1){
				$tam = "30%";
				$dados[$ii]['Valor'] += 0;
				$hExtra1 = "<div class='titulo-secundario' style='float:left;width:20%;'>
								<p><b>Valor</b></p>
								<p><input type='text' id='valor-contabil-$ii' name='valor-contabil[]' class='formata-valor totalizar-contabil required zero-nao' value='".number_format($dados[$ii]['Valor'], 2, ',', '.')."' style='width:95%'/></p>
							</div>";
				$hExtra2 = "<div class='titulo-secundario' style='float:left;width:15%;'>
								<p><b>Observa&ccedil;&atilde;o</b></p>
								<p><input type='text' id='observacao-contabil-$ii' name='observacao-contabil[]' value='".$dados[$ii]['Observacao']."' style='width:95%'/></p>
							</div>";
				if ($ii<>0){
					$hExtra2 .= "<div class='titulo-secundario' style='float:left;width:05%;'>
									<div class='btn-cancelar btn-excluir-contabil' style='margin:0 auto; margin-top: 20px' posicao='$ii' title='Editar'>&nbsp;</div>
								</div>";
				}
			}

			$h .= "		<div class='bloco-contabil-$ii' style='width:100%; padding-top:3px; float:left;'>
							<div class='titulo-secundario' style='float:left; width:".$tam.";'>
								<p><b>Tipo Conta</b></p>
								<p><select name='lancamento-tipo-conta[]' id='lancamento-tipo-conta' class='lancamento-tipo-conta'>".carregarTipoConta($tipo, $dados[$ii]['Tipo_Conta_ID'])."</select></p>
							</div>
							<div class='titulo-secundario <?php echo $escondeCentroCusto;?>'  style='float:left; width:".$tam.";'>
								<p><b>Centro de Custo</b></p>
								<p><select id='lancamento-centro-custo-$ii' name='lancamento-centro-custo[]'>".optionValueGrupoFilho(26, $dados[$ii]['Centro_Custo_ID'], "","")."</select></p>
							</div>
							".$hExtra1.$hExtra2;
			$h .= " 	</div>";
			$totalContabil += $dados[$ii]['Valor'];
		}
		if ($i>1){
			$h .= "	<div class='bloco-contabil-$ii' style='width:100%; padding-top:3px; float:left;'>
						<div class='titulo-secundario' style='float:left;width:60%;'>&nbsp;</div>
						<div class='titulo-secundario' style='float:left;width:20%;'>
							<p><b>Saldo</b></p>
							<p><input type='text' id='valor-total-contabil' name='valor-total-contabil' class='formata-valor' value='".number_format($totalContabil, 2, ',', '.')."' readonly style='width:95%'/></p>
							<p class='titulo-secundario mensagem-contabil' style='float:left;width:100%; text-align:center;'></p>
						</div>
					</div>";
		}

		$h .= "	<input type='hidden' name='numero-opcoes' id='numero-opcoes' value='$ii'/>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		return $h;
	}

	function salvarValoresIndice(){
		$tipoID = $_POST['tipo-id'];
		$sql = "select Tipo_Auxiliar from tipo where Tipo_ID = '$tipoID'";
		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$dados = unserialize($rs['Tipo_Auxiliar']);
		}
		//$dados[] = $_POST['mesano'];
		//$dados[$_POST['mesano']] = $_POST['indice'];
		$sql = "update tipo set Tipo_Auxiliar = '".serialize($dados)."'	 where Tipo_ID = '$tipoID'";
		$resultSet = mpress_query($sql);
		echo "	<form action='".$caminhoSistema."/financeiro/financeiro-inpc' method='post' name='retorno'></form>
				<script>document.retorno.submit();</script>";
	}

	function cancelarFaturarProdutos(){
		global $dadosUserLogin;
		$dataHoraAtual 		= "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$tipoID 			= $_POST['tipo-id'];
		$cadastroIDde 		= $_POST['empresa-id'];
		$cadastroIDpara 	= $_POST['cadastro-id'];
		$tabelaEstrangeira 	= $_POST['modulo'];
		$chaveEstrangeira 	= $_POST['chave-estrangeira'];

		$sql = "INSERT INTO financeiro_contas (Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Chave_Estrangeira, Valor_Total, Observacao, Data_Cadastro, Usuario_Cadastro_ID)
									VALUES ('$tipoID', '$cadastroIDde', '$cadastroIDpara', '$tabelaEstrangeira', '$chaveEstrangeira', 0, 'Cancelamento de Faturamento de Itens', $dataHoraAtual,'".$dadosUserLogin['userID']."')";
		//echo "<br>".$sql;

		// die();

		//var_dump($_POST['produto-cancelar']);

		mpress_query($sql);
		$contaID = mpress_identity();

		foreach($_POST['produto-cancelar'] as $chaveEstrangeiraProduto){
			$sql = "INSERT INTO financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
											VALUES ('$chaveEstrangeiraProduto', '$contaID', '$tabelaEstrangeira', '$chaveEstrangeira', 2,'".$dadosUserLogin['userID']."', $dataHoraAtual)";
			echo "<br>".$sql;
			mpress_query($sql);
		}

		//die();
	}

	function carregarProdutosFaturar($id, $tabelaEstrangeira){

		// var_dump($_POST);
		// var_dump($_GET);

		// die();

		if ($id==''){
			$sqlHaving .= " having Faturado = 0";
			$orcamentoID = $_POST['localiza-orcamento-id'];
			if ($orcamentoID!=''){ 
				$sqlCond .= " and o.Workflow_ID = '$orcamentoID'";
			}

			// IRÁ ADICIONAR OS PRODUTOS COM O SEGUINTE Status_ID:
			$extraCond = " opp.Status_ID = '137' ";
		}
		else{
			$sqlCond .= " and o.Workflow_ID = '$id'";

			// IRÁ ADICIONAR A CONDIÇÃO PARA UMA BUSCA DETALHADA DOS PRODUTOS DENTRO DE UMA DETERMINADA PROPOSTA
			$extraCond = " opp.Status_ID = '123' or opp.Status_ID = '137' or opp.Status_ID = '1385' or opp.Status_ID = '1387'";
		}


		/* A RECEBER */
		$sql = "
			SELECT emp.Cadastro_ID as Empresa_ID, 
			emp.Nome as Nome_De, 
			cli.Cadastro_ID, 
			cli.Nome as Nome_Para, 
			45 as Tipo_ID, 
			'A RECEBER' as Tipo, 
			op.Proposta_ID, op.Titulo as Titulo_Proposta, 
			opp.Proposta_Produto_ID as Workflow_Produto_ID, 
			pv.Produto_Variacao_ID as Produto_Variacao_ID, 
			pv.Codigo as Codigo_Variacao,
			DATE_FORMAT(opp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, 
			concat(coalesce(pd.Nome,''),
			' ',coalesce(pv.Descricao,'')) as Descricao_Produto,
			cd.Nome as Nome,
			(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor_Produto_Total, 
			pd.Tipo_Produto as Tipo_Produto, 
			opp.Data_Cadastro as Data_Ordena, 
			opp.Faturamento_Direto,
			opp.Status_ID,
			o.Workflow_ID as ID_Ref, 
			op.Forma_Pagamento_ID,
			tfp.Tipo_Auxiliar as Forma_auxiliar,
			tfp.Descr_tipo as Forma_Pagamento,
			o.Solicitante_ID as Faturar_Para_De_ID,
			opp.Prestador_Forma_Pagamento_ID,
			tpfp.Descr_tipo as Forma_Pagamento_Prestador,
			(SELECT count(*) 
				FROM financeiro_produtos fp
				INNER JOIN financeiro_contas fc on fp.Conta_ID = fc.Conta_ID and fc.Tipo_ID = 45
				WHERE fp.Tabela_Estrangeira = 'orcamentos' and fp.Produto_Referencia_ID = opp.Proposta_Produto_ID
				and fp.Situacao_ID IN (1,2)) as Faturado
			FROM orcamentos_propostas_produtos opp
			
			INNER JOIN orcamentos_propostas op 	on op.Proposta_ID = opp.Proposta_ID
			INNER JOIN orcamentos_workflows o 	on o.Workflow_ID = op.Workflow_ID
			inner join produtos_variacoes pv 	on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
			inner join produtos_dados pd 		on pd.Produto_ID = pv.Produto_ID
			inner join cadastros_dados cd 		on cd.Cadastro_ID = opp.Usuario_Cadastro_ID
			inner join cadastros_dados cli 		on cli.Cadastro_ID = o.Solicitante_ID
			inner join cadastros_dados emp 		on emp.Cadastro_ID = o.Empresa_ID
			left join tipo tfp 					on tfp.Tipo_ID = op.Forma_Pagamento_ID
			left join tipo tpfp on tpfp.Tipo_ID = opp.Prestador_Forma_Pagamento_ID
			where opp.Situacao_ID = 1
			and op.Situacao_ID = 1
			and op.Status_ID = 141
			and (opp.Valor_Venda_Unitario * opp.Quantidade) > 0 
			and opp.Cobranca_Cliente = 1
			and opp.Faturamento_Direto = 0
			and (".$extraCond." or opp.Status_ID = '1387')
			".$sqlCond."
			".$sqlHaving."
		
		union all
		/* a pagar */
		SELECT  emp.Cadastro_ID, 
		emp.Nome,  
		cli.Cadastro_ID, 
		cli.Nome, 
		44, 
		'A PAGAR' as Tipo, 
		op.Proposta_ID, 
		op.Titulo, 
		opp.Proposta_Produto_ID, 
		pv.Produto_Variacao_ID, 
		pv.Codigo,
		DATE_FORMAT(opp.Data_Cadastro, '%d/%m/%Y %H:%i'), 
		concat(coalesce(pd.Nome,''),
		' ',coalesce(pv.Descricao,'')), 
		cd.Nome,
		(opp.Quantidade * opp.Valor_Custo_Unitario),
		pd.Tipo_Produto, 
		opp.Data_Cadastro as Data_Ordena, 
		opp.Faturamento_Direto,
		opp.Status_ID,
		o.Workflow_ID, 
		op.Forma_Pagamento_ID,
		tfp.Tipo_Auxiliar as Forma_auxiliar,
		tfp.Descr_tipo,
		opp.Prestador_ID,
		opp.Prestador_Forma_Pagamento_ID,
		tpfp.Descr_tipo as Forma_Pagamento_Prestador,
		(select count(*) 
			from financeiro_produtos fp
			inner join financeiro_contas fc on fp.Conta_ID = fc.Conta_ID and fc.Tipo_ID = 44
			where fp.Tabela_Estrangeira = 'orcamentos' and fp.Produto_Referencia_ID = opp.Proposta_Produto_ID
			and fp.Situacao_ID IN (1,2)) as Faturado
		from orcamentos_propostas_produtos opp
		
		inner join orcamentos_propostas op on op.Proposta_ID = opp.Proposta_ID
		inner join orcamentos_workflows o on o.Workflow_ID = op.Workflow_ID
		inner join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
		inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
		inner join cadastros_dados cd on cd.Cadastro_ID = opp.Usuario_Cadastro_ID
		inner join cadastros_dados cli on cli.Cadastro_ID = opp.Prestador_ID
		inner join cadastros_dados emp on emp.Cadastro_ID = o.Empresa_ID
		left join tipo tfp on tfp.Tipo_ID = op.Forma_Pagamento_ID
		left join tipo tpfp on tpfp.Tipo_ID = opp.Prestador_Forma_Pagamento_ID
		where opp.Situacao_ID = 1
		and op.Situacao_ID = 1
		and op.Status_ID = 141
		and (opp.Valor_Custo_Unitario * opp.Quantidade) > 0 and opp.Pagamento_Prestador = 1
		and opp.Faturamento_Direto = 0
		and (".$extraCond." or opp.Status_ID = '1385')
							".$sqlCond." 
							".$sqlHaving."
		
		union all
		/* faturamento direto */
		SELECT emp.Cadastro_ID, 
		emp.Nome, 
		cli.Cadastro_ID, 
		cli.Nome, 
		45, 
		'A RECEBER', 
		op.Proposta_ID,
		op.Titulo as Titulo_Proposta, 
		opp.Proposta_Produto_ID, 
		pv.Produto_Variacao_ID, 
		pv.Codigo as Codigo_Variacao,
		DATE_FORMAT(opp.Data_Cadastro, '%d/%m/%Y %H:%i'), 
		concat(coalesce(pd.Nome,''),
		' ', 
		coalesce(pv.Descricao,'')), 
		cd.Nome,
		((opp.Valor_Venda_Unitario * opp.Quantidade) - (opp.Valor_Custo_Unitario * opp.Quantidade)),
		pd.Tipo_Produto, 
		opp.Data_Cadastro, 
		opp.Faturamento_Direto,
		opp.Status_ID,
		o.Workflow_ID as ID_Ref, 
		op.Forma_Pagamento_ID,
		tfp.Tipo_Auxiliar as Forma_auxiliar,
		tfp.Descr_tipo,
		opp.Prestador_ID,
		opp.Prestador_Forma_Pagamento_ID,
		tpfp.Descr_tipo as Forma_Pagamento_Prestador,
		(select count(*) from financeiro_produtos fp
			inner join financeiro_contas fc on fp.Conta_ID = fc.Conta_ID and fc.Tipo_ID = 45
			where fp.Tabela_Estrangeira = 'orcamentos' and fp.Produto_Referencia_ID = opp.Proposta_Produto_ID
			and fp.Situacao_ID IN (1,2)) as Faturado
		FROM orcamentos_propostas_produtos opp
		
		inner join orcamentos_propostas op on op.Proposta_ID = opp.Proposta_ID
		inner join orcamentos_workflows o on o.Workflow_ID = op.Workflow_ID
		inner join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
		inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
		inner join cadastros_dados cd on cd.Cadastro_ID = opp.Usuario_Cadastro_ID
		inner join cadastros_dados cli on cli.Cadastro_ID = opp.Prestador_ID
		inner join cadastros_dados emp on emp.Cadastro_ID = o.Empresa_ID
		left join tipo tfp on tfp.Tipo_ID = op.Forma_Pagamento_ID
		left join tipo tpfp on tpfp.Tipo_ID = opp.Prestador_Forma_Pagamento_ID
		where opp.Situacao_ID = 1
		and op.Situacao_ID = 1
		and op.Status_ID = 141
		and ((opp.Valor_Venda_Unitario * opp.Quantidade) - (opp.Valor_Custo_Unitario * opp.Quantidade)) > 0
		and opp.Cobranca_Cliente = 1
		and opp.Pagamento_Prestador = 1
		and opp.Faturamento_Direto = 1
		and (".$extraCond." or opp.Status_ID = '1387')
							".$sqlCond."
							".$sqlHaving."
		order by Tipo_ID, Faturar_Para_De_ID, ID_Ref, Proposta_ID, Data_Ordena desc";
		
		//echo $sql;

		$colAux = 6;
		if ($contEmpresas==1) $colAux--;

		//echo $sql;

		$query = mpress_query($sql);
		$indice = 0;
		$linhas = 0;
		while($rs = mpress_fetch_array($query)){

			$dadosFP = unserialize($rs['Forma_auxiliar']);

			$linhas++;
			if ($rs['Tipo_ID']!=$tipoIDAnt){
				$i++;
				if ($rs['Tipo_ID']=="44"){
					$descricaoConta = "<font style='color:red !important;'><b>".$rs['Tipo']." PARA</b></font>";
				}
				if ($rs['Tipo_ID']=="45"){
					$descricaoConta = "<font style='color:blue !important;'><b>".$rs['Tipo']." DE</b></font>";
				}
				$dados['colunas']['colspan'][$i][1] 	= $colAux;
				$dados['colunas']['classe'][$i] 		= 'destaque-tabela';
				$dados['colunas']['conteudo'][$i][1] 	= "<p Style='margin:2px 5px 0 2px;'>".$descricaoConta."</p>";
				$dados['colunas']['extras'][$i][1] 		= "align='center' height='25'";
				$cadastroIDAnt 							= "";
				$formaPagamentoIDAnt 					= "";
			}


			if (($rs['Cadastro_ID']!=$cadastroIDAnt) || ($rs['Forma_Pagamento_ID']!=$formaPagamentoIDAnt && $rs['Tipo_ID']=="44")){

				/*
					CONDIÇÃO '$rs['Tipo_ID']=="44"' PARA AGRUPAR OS PRODUTOS COM MESMA FORMA DE PAGAMENTO PARA OS PRODUTOS PARA FATURAR
				*/

				$pagamentoFornecedor = '';

				/*
					VERIFICA SE O PRODUTO TEM UMA FORMA DE PAGAMENTO ESPECIFICA, CASO O PRODUTO SEJA PARA PAGAMENTO DE FORNECEDOR.
				*/

				if($rs['Prestador_Forma_Pagamento_ID'] != 0 && $rs['Tipo_ID']=="44"){
					$pagamentoFornecedor = $rs['Forma_Pagamento_Prestador'];
				}else{
					$pagamentoFornecedor = $rs['Forma_Pagamento'];
				}

				$indice++;
				$i++;
				$dados[colunas][classe][$i] 		= 'tabela-fundo-escuro-titulo';
				$dados[colunas][colspan][$i][1] 	= $colAux;
				$dados[colunas][conteudo][$i][1] 	= "<p Style='margin:2px 5px 0 2px;'>".$rs['Nome_Para']."</p>
													<p Style='margin:2px 5px 0 2px;'>".$pagamentoFornecedor."</p>
													<p Style='margin:2px 5px 0 2px; text-align:right;'>
														<input type='button' value='' class='esconde botao-faturar-cancelar botao-faturar-cancelar-".$indice."' origem='$tabelaEstrangeira' tipo-id='".$rs['Tipo_ID']."' empresa-id='".$rs['Empresa_ID']."' cadastro-id='".$rs['Cadastro_ID']."' chave-estrangeira='".$rs['ID_Ref']."' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
													</p>";
				$dados[colunas][extras][$i][1] 		= " align='center' height='25' ";
				$c = 1;
				$i++;
				$dados[colunas][classe][$i] 		= "tabela-fundo-escuro-titulo";
				if ($contEmpresas>1){
					$dados[colunas][conteudo][$i][$c++] = "Empresa";
				}
				$dados[colunas][conteudo][$i][$c++] = "Or&ccedil;amento ID";
				$dados[colunas][conteudo][$i][$c++] = "Produto / Servi&ccedil;o";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:right;'>Valor</p>";
				$dados[colunas][conteudo][$i][$c++] = "<center><img src='$caminhoSistema/images/geral/disponivel.png' class='sel-todas-faturar' indice='$indice' tipo-id='".$rs['Tipo_ID']."' style='cursor:pointer' title='Aceitar Todas'></center>";
				$dados[colunas][conteudo][$i][$c++] = "<center><img src='$caminhoSistema/images/geral/indisponivel.png' class='sel-todas-cancelar' indice='$indice' tipo-id='".$rs['Tipo_ID']."' style='cursor:pointer' title='Negar Todas'></center>";
			}
			$c = 1;
			$i++;
			if($rs['Faturamento_Direto']=='1'){ 
				$faturamentoDireto 		= " <b><i>* Faturamento Direto</i></b>";
			}else{
				$faturamentoDireto 		= "";
			}

			$dados[colunas][classe][$i] = "tabela-fundo-claro";

			if ($contEmpresas>1){
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$nomeDe."</p>";
			}
			$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;' class='link link-orcamento' workflow-id='$rs[ID_Ref]'>".$rs['ID_Ref']."</p>";
			$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs['Descricao_Produto']." ".$faturamentoDireto."</p>";

			// $dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs['Valor_Produto_Total'], 2, ',', '.')."</p>";
			

			/* VALIDA SE O PRODUTO É PARA FATURAR OU PAGAR FORNECEDOR */

			if($rs['Tipo_ID'] == '44'){ // PRODUTO É PARA PAGAR FORNECEDOR

				$modoPagamento = '';

				if($rs['Prestador_Forma_Pagamento_ID'] > 0){

					$modoPagamento = $rs['Prestador_Forma_Pagamento_ID'];

				}else{

					$modoPagamento = $rs['Forma_Pagamento_ID'];
				}

				// CARREGA A FORMA DE PAGAMENTO
				$sqlFomaPagamento = "SELECT Descr_Tipo as Forma_Cobranca,
												Tipo_Auxiliar as Descricao
											FROM tipo
											WHERE tipo.Tipo_ID = $modoPagamento";

				if(!empty($sqlFomaPagamento)){

					$formaPgmtRaw 	= mpress_query($sqlFomaPagamento);

					$rst 			= mpress_fetch_array($formaPgmtRaw);

					$descricao 		= unserialize($rst['Descricao']);


					$valorMod = ($rs['Valor_Produto_Total'] / 100) * $descricao['valor_modificado'];

					if($descricao['tipo-bonus-disponivel'] == 'Desconto'){

						$valorVencimentoMod = $rs['Valor_Produto_Total'] - $valorMod;

					}else{
						$valorVencimentoMod = $rs['Valor_Produto_Total'] + $valorMod;
					}

					//$valorVencimento = $valorVencimentoMod / $dadosFP['quantidade-parcelas'];
					$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($valorVencimentoMod, 2, ',', '.')."</p>";


				}else{

					//$valorVencimento = $valorTotalProposta / $dadosFP['quantidade-parcelas'];
					$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs['Valor_Produto_Total'], 2, ',', '.')."</p>";

				}

			}else{ // PRODUTO PARA FATURAR

				/* VERIFICA SE EXISTE ALGUM DESCONTO ATRELADO A FORMA DE PAGAMENTO */

				if(!empty($dadosFP['tipo-bonus-disponivel'])){

					$valorMod = ($rs['Valor_Produto_Total'] / 100) * $dadosFP['valor_modificado'];

					if($dadosFP['tipo-bonus-disponivel'] == 'Desconto'){

						$valorVencimentoMod = $rs['Valor_Produto_Total'] - $valorMod;

					}else{
						$valorVencimentoMod = $rs['Valor_Produto_Total'] + $valorMod;
					}

					//$valorVencimento = $valorVencimentoMod / $dadosFP['quantidade-parcelas'];
					$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($valorVencimentoMod, 2, ',', '.')."</p>";

				}else{

					//$valorVencimento = $valorTotalProposta / $dadosFP['quantidade-parcelas'];
					$dados[colunas][conteudo][$i][$c++] 	= "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs['Valor_Produto_Total'], 2, ',', '.')."</p>";

				}

			}

			if($rs['Faturado']==0){

				// VERIFICA ESTÁ SENDO EXIBIDA NA TELA DE FINANCEIRO
				if($id!=''){

					// TRECHO PARA EXIBIR A OPÇÃO CORRETA PARA DETERMINADO TIPO DE OPERAÇÃO FINANCEIRA, SEJA NA TELA DE ORÇAMENTO OU SEJA NA TELA DE FINANCEIRO
					switch ($rs['Status_ID']) {
						case '137':
							$dados[colunas][colspan][$i][$c] 	= 2;
							$dados[colunas][conteudo][$i][$c] 	= "<center style='font-weight: bold;'><i>* Enviado para financeiro</i></center>";
							$c++;
						break;

						case '1385':

							if($rs['Tipo_ID'] == '44'){

								$dados[colunas][colspan][$i][$c] 	= 2;
								$dados[colunas][conteudo][$i][$c] 	= "<center style='font-weight: bold;'><i>* Enviado para pagamento</i></center>";
								$c++;

							}else{
								$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-faturar prod-faturar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."'  name='produto-faturar[]'></center>";
								$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-cancelar prod-cancelar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."' name='produto-cancelar[]'></center>";
							}

							
						break;

						case '1387':
							

							if($rs['Tipo_ID'] == '45'){

								$dados[colunas][colspan][$i][$c] 	= 2;
								$dados[colunas][conteudo][$i][$c] 	= "<center style='font-weight: bold;'><i>* Enviado para faturar</i></center>";
								$c++;

							}else{
								$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-faturar prod-faturar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."'  name='produto-faturar[]'></center>";
								$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-cancelar prod-cancelar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."' name='produto-cancelar[]'></center>";
							}

						break;
						
						default:
							
							//PRODUTO ESTÁ AGUARDANDO ENVIO PARA O FINANCEIRO

							$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-faturar prod-faturar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."'  name='produto-faturar[]'></center>";
							$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-cancelar prod-cancelar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."' name='produto-cancelar[]'></center>";

						break;
					}


				}else{

					$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-faturar prod-faturar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."'  name='produto-faturar[]'></center>";
					$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' class='prod-cancelar prod-cancelar-".$indice." prod-tipo-".$rs['Tipo_ID']."' value='".$rs['Workflow_Produto_ID']."' indice='$indice' tipo-id='".$rs['Tipo_ID']."' name='produto-cancelar[]'></center>";
				}

			}else{

				$dados[colunas][colspan][$i][$c] 	= 2;
				$dados[colunas][conteudo][$i][$c] 	= "<center style='font-weight: bold;'><i>* Faturado</i></center>";
				$c++;
			}


			$tipoIDAnt 				= $rs['Tipo_ID'];
			$cadastroIDAnt 			= $rs['Cadastro_ID'];
			$formaPagamentoIDAnt 	= $rs['Forma_Pagamento_ID'];

			//VERIFICA SE O PRODUTO TEM UMA FORMA DE PAGAMENTO ESPECIFICA.
			if($rs['Prestador_Forma_Pagamento_ID'] != 0){
				$formaPagamentoIDAnt = $rs['Prestador_Forma_Pagamento_ID'];
			}else{
				$formaPagamentoIDAnt = $rs['Forma_Pagamento_ID'];
			}

		}
		$largura = "100%";
		$colunas = $c - 1;
		$c = 1;
		if ($contEmpresas>1){
			$dados[colunas][tamanho][$c++] 	= "width='10%'";
		}
		$dados[colunas][tamanho][$c++] 		= "width='5%'";
		$c++;
		$c++;
		$dados[colunas][tamanho][$c++] 		= "width='30px'";
		$dados[colunas][tamanho][$c++] 		= "width='30px'";
		$dados[colunas][titulo][classe] 	= 'esconde';
		if ($contEmpresas>1){
			$dados[colunas][titulo][$c++] 	= "Empresa";
		}
		$dados[colunas][titulo][$c++] 		= "Or&ccedil;amento ID";
		$dados[colunas][titulo][$c++] 		= "Produto / Servi&ccedil;o";
		$dados[colunas][titulo][$c++] 		= "<p Style='margin:2px 5px 0 5px;float:right;'>Valor</p>";
		$dados[colunas][titulo][$c++] 		= "Aceitar";
		$dados[colunas][titulo][$c++] 		= "Negar";

		$retorno['colunas'] 		= $colunas;
		$retorno['largura'] 		= $largura;
		$retorno['dados'] 			= $dados;
		$retorno['linhas'] 			= $linhas;
		return $retorno;
	}


	/*
		FUNÇÃO CRIADA PARA ATUALIZAR OS STATUS DOS PRODUTOS QUE FORAM SELECIONADOS PARA FATURAMENTO
	*/

	function confirmarFaturarProdutos(){

		// var_dump($_POST);

		/*
			LISTAS DE STATUS PARA OS PRODUTOS

			123  - Item esperando confirmação para ir ao Financeiro
			1385 - Item aprovado apenas para pagamento
			1387 - Item aprovado apenas para faturamento
			137  - Item aprovado tanto para faturar e para pagar o fornecedor

		*/

		foreach($_POST['produto-faturar'] as $chaveEstrangeiraProduto){


			/*
				VERIFCA QUAL A SITUAÇÂO DO PRODUTO E QUAL O CORRETO STATUS A SER APLICADO
			*/

			$statusProduto = "SELECT Status_ID FROM orcamentos_propostas_produtos WHERE Proposta_Produto_ID = '$chaveEstrangeiraProduto'";

			$resultado 		= mpress_query($statusProduto);
			$rst 			= mpress_fetch_array($resultado);
			$statusProduto 	= $rst[0];

			if($_POST['tipo-id'] == 44){

				// MARCAR O PRODUTO PARA PAGAMENTO

				if($statusProduto == '123'){

					$sqlFaturarProduto = "UPDATE orcamentos_propostas_produtos
									SET Status_ID  = '1385'
									WHERE Proposta_Produto_ID = '$chaveEstrangeiraProduto'";

				}else{

					$sqlFaturarProduto = "UPDATE orcamentos_propostas_produtos
									SET Status_ID  = '137'
									WHERE Proposta_Produto_ID = '$chaveEstrangeiraProduto'";

				}

			}elseif($_POST['tipo-id'] == 45){

				// MARCAR O PRODUTO PARA FATURAMENTO

				if($statusProduto == '123'){

					$sqlFaturarProduto = "UPDATE orcamentos_propostas_produtos
									SET Status_ID  = '1387'
									WHERE Proposta_Produto_ID = '$chaveEstrangeiraProduto'";

				}else{

					$sqlFaturarProduto = "UPDATE orcamentos_propostas_produtos
									SET Status_ID  = '137'
									WHERE Proposta_Produto_ID = '$chaveEstrangeiraProduto'";

				}
			}

			mpress_query($sqlFaturarProduto);
		}

	}
?>