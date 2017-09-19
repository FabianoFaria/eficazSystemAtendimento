<?php
	function exibeTotaisCC($totECC, $totSCC, $totMCC, $totTCC, $totTCCB, $moduloIgrejaAtivo, $totAtivas, $totInativas){
		if ($moduloIgrejaAtivo){
			$colunaTotalMembroCC = "<td align='right' stylenormal><b>".number_format($totMCC, 0, ',', '.')."</b></td>";
			$totaisAtivasInativas = "	<span align='left' style='font-weight: normal;'>Ativas: ".number_format($totAtivas, 0, ',', '.')."</span>
										<span align='left' style='font-weight: normal;'>Inativas: ".number_format($totInativas, 0, ',', '.')."</span>";
		}

		return "<tr class='tabela-fundo-escuro-titulo'>
				<td stylenormal>
					$totaisAtivasInativas
					<span style='float:right'>Total</span>
				$colunaTotalMembroCC
				<td align='right' stylenormal><b>".number_format($totECC, 2, ',', '.')."</b></td>
				<td align='right' stylenormal><b>".number_format($totSCC, 2, ',', '.')."</b></td>
				<td align='right' stylenormal><b>".number_format(($totECC - $totSCC), 2, ',', '.')."</b></td>
				<td align='right' stylenormal><b>".number_format($totTCCB, 2, ',', '.')."</b></td>
				<td align='right' stylenormal><b>".number_format($totTCC, 2, ',', '.')."</b></td>
				<td align='right' stylenormal><b>".number_format(($totECC - $totSCC) + $totTCCB - $totTCC, 2, ',', '.')."</b></td>
			  </tr>";
	}

	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
	}

	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$codigo = trim($_POST['localiza-codigo']);
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-tipo-conta']); $i++){
			$tiposContas .= $virgula.$_POST['localiza-tipo-conta'][$i];
			$virgula = ",";
		}

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-situacao-conta']); $i++){
			$situacoes .= $virgula.$_POST['localiza-situacao-conta'][$i];
			$virgula = ",";
		}
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
		for($i = 0; $i < count($_POST['check-tipo-grupo-27']); $i++){
			$tipos .= $virgula.$_POST['check-tipo-grupo-27'][$i];
			$virgula = ",";
		}
		$cadastroSituacao = $_POST['localiza-cadastro-situacao'];
	}
	else{
		$dataInicioVencimento = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");
		$classeEsconde = "esconde";
		$separar = "checked";
		$situacoes = "48,49";
		$cadastroSituacao = 1;
	}

	$query = mpress_query("Select count(*) as igrejaAtivo from modulos where slug = 'igreja'");
	if($rs = mpress_fetch_array($query))
		$moduloIgrejaAtivo = $rs[igrejaAtivo];

?>
				<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Dinamico'>
				<input type='hidden' id='conteudo-relatorio' name='conteudo-relatorio' value=''>
				<div id='cadastros-container'>
					<div class='titulo-container'>
						<div class='titulo' style="min-height:25px">
							<p style="margin-top:2px;">
							Filtros de Pesquisa
							</p>
						</div>
						<div class='conteudo-interno'>
							<div class="titulo-secundario" style='float:left;width:20%' >
								<div class='titulo-secundario' style='float:left;width:98%;'>
									<p>Data Vencimento:</p>
									<div style='width:45%;float:left;'>
										<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
									</div>
									<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
									<div style='width:45%;float:left;'>
										<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
									</div>
								</div>
							</div>
							<div class='titulo-secundario' style='float:left;width:15%'>
								<p>Situa&ccedil;&atilde;o:</p>
								<p><select name='localiza-situacao-conta[]' id='localiza-situacao-conta' multiple Style='height:58px'><?php echo optionValueGrupoMultiplo(29, $situacoes,'');?></select></p>
							</div>
							<div class='titulo-secundario' style='float:left;width:15%;'>
								<p>Filtrar:</p>
								<p>
									<select name='filtro-dinamico' id='filtro-dinamico'>
										<option></option>
										<option value='exibir-zerados' 		<?php echo verificaSelected($_POST['filtro-dinamico'],'exibir-zerados');?>>Exibir apenas zerados</option>
										<option value='nao-exibir-zerados'	<?php echo verificaSelected($_POST['filtro-dinamico'],'nao-exibir-zerados');?>>N&atilde;o exibir zerados</option>
									</select>
								</p>
								<!--
								<input type='checkbox' name='exibir-zerados' id='exibir-zerados' value='1' <?php if ($_POST['exibir-zerados']=='1') echo "checked"; ?>/>
								<label for='exibir-zerados'>Exibir zerados</label>
								-->
								<!--
								<div class='<?php echo $classeEmpresasEsconde; ?>' style='width:100%'>
									<p>Valores de Entrada:</p>
									<div style='width:40%;float:left;'>
										<p><input type='text' name='valor-entrada-ini' id='valor-entrada-ini' class='formata-numero' style='width:92%;text-align:right;' maxlength='10' value='<?php echo $_POST['valor-entrada-ini']; ?>'>&nbsp;&nbsp;</p>
									</div>
									<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
									<div style='width:40%;float:left;'>
										<p><input type='text' name='valor-entrada-fim' id='valor-entrada-fim' class='formata-numero' style='width:92%;text-align:right;' maxlength='10' value='<?php echo $_POST['valor-entrada-fim']; ?>'></p>
									</div>
								</div>
								<div class='<?php echo $classeEmpresasEsconde; ?>' style='width:100%'>
									<p>Valores de Sa&iacute;da:</p>
									<div style='width:40%;float:left;'>
										<p><input type='text' name='valor-saida-ini' id='valor-saida-ini' class='formata-numero' style='width:92%;text-align:right;' maxlength='10' value='<?php echo $_POST['valor-saida-ini']; ?>'>&nbsp;&nbsp;</p>
									</div>
									<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
									<div style='width:40%;float:left;'>
										<p><input type='text' name='valor-saida-fim' id='valor-saida-fim' class='formata-numero' style='width:92%;text-align:right;' maxlength='10' value='<?php echo $_POST['valor-saida-fim']; ?>'></p>
									</div>
								</div>
								-->
								&nbsp;
								<!--
								<p>Tipo Conta:</p>
								<p><select name='localiza-tipo-conta[]' id='localiza-tipo-conta' multiple Style='height:102px'><?php echo optionValueGrupoMultiplo(28, $tiposContas,'');?></select></p>
								-->
							</div>
							<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p>Centros de Custo:</p>
									<p><select name='localiza-centro-custo[]' id='localiza-centro-custo' multiple><?php echo optionValueGrupoMultiplo(26, $centrosCustos,'');?></select></p>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p>Cadastros:</p>
									<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
								</div>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p>Situa&ccedil;&atilde;o:</p>
									<p><select name='localiza-cadastro-situacao' id='localiza-cadastro-situacao'>
										<?php echo optionValueGrupo(1, $cadastroSituacao, "Todos"," and Tipo_ID not in (2,142)");?></select></p>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario' style='float:left;width:10%;margin-top:16px;'>
								<div style='width:60%;float:right;margin-bottom:3px'>
									<p><input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;</p>
								</div>
								<div style='width:40%;float:left; height:29px;margin-top:3px'>
									<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel' style="float:left;" title="Gerar Excel"></div>&nbsp;
									<div class="btn-imprimir <?php echo $classeEsconde; ?>" id='botao-imprimir' style="float:left;" title="Imprimir"></div>&nbsp;
								</div>

								<div class='titulo-secundario' style='float:left;width:100%'>
									<div class='<?php echo $classeEmpresasEsconde; ?>'>
										<p>Separar por:</p>
										<p>
											<select id='separar-por' name='separar-por' style='width:92%'>
												<option value='cc' <?php echo verificaSelected($_POST['separar-por'],'cc');?>>Centros de Custo</option>
												<!--<option value='cad' <?php echo verificaSelected($_POST['separar-por'],'cad');?>>Cadastros</option>-->
											</select>
										</p>
									</div>
									&nbsp;
								</div>
							</div>

						</div>
					</div>
				</div>
				<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>
				<input type='hidden' id='localiza-titulo-id' name='localiza-titulo-id' value=''>
<?php
	if($_POST){
		if ($tiposContas != ""){ $sqlCond .= " and  fc.Tipo_Conta_ID IN ($tiposContas)";}
		if ($situacoes != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID IN ($situacoes)";}
		if ($centrosCustos != ""){ $sqlCondCadastro .= " and cdd.Centro_Custo_ID IN ($centrosCustos)";}
		if ($cadastrosDe != ""){ $sqlCondCadastro .= " and cdd.Cadastro_ID IN ($cadastrosDe)";}
		if ($tipos != ""){ $sqlCond .= " and  fc.Tipo_ID IN ($tipos)";}

		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
			$sqlCondIgreja .= " and il.Data_Lancamento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}

		if ($cadastroSituacao!=""){
			$sqlCondCadastro .= " and cdd.Situacao_ID in ($cadastroSituacao)";
		}

		if ($_POST['separar-por']=='cc'){
			$sqlOrderBy = " order by cdd.Centro_Custo_ID ";
		}else{
			$sqlOrderBy = " order by cdd.Nome ";
		}
		$sql = "select cdd.Cadastro_ID, cdd.Nome, cdd.Centro_Custo_ID, tcc.Descr_Tipo as Centro_Custo, coalesce(r.Nome,'N/A') as Responsavel, cdd.Situacao_ID
					from cadastros_dados cdd
					left join tipo tcc on tcc.Tipo_ID = cdd.Centro_Custo_ID and Tipo_Grupo_ID = 26
					left join cadastros_dados r on r.Cadastro_ID = tcc.Tipo_Auxiliar
					where cdd.Empresa = 1
					$sqlCondCadastro
					$sqlOrderBy";
		//echo $sql;
		//exit();
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			$dadosAux[$rs[Cadastro_ID]][Nome] = $rs[Nome];
			$dadosAux[$rs[Cadastro_ID]][Entrada] = 0;
			$dadosAux[$rs[Cadastro_ID]][Saida] = 0;
			$dadosAux[$rs[Cadastro_ID]][Transferencia] = 0;
			$dadosAux[$rs[Cadastro_ID]][Membro] = 0;
			$dadosAux[$rs[Cadastro_ID]][Centro_Custo] = $rs[Centro_Custo];
			$dadosAux[$rs[Cadastro_ID]][Centro_Custo_ID] = $rs[Centro_Custo_ID];
			$dadosAux[$rs[Cadastro_ID]][Responsavel] = $rs[Responsavel];
			$dadosAux[$rs[Cadastro_ID]][Situacao_ID] = $rs[Situacao_ID];
		}
		/*
		$sql = "select cdd.Cadastro_ID as Cadastro_ID, tc.Tipo_ID, tc.Descr_Tipo as Tipo, coalesce(sum(ft.Valor_Titulo),0) as Valor
				from cadastros_dados cdd
				inner join  financeiro_contas fc on cdd.Cadastro_ID = fc.Cadastro_ID_de
				inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
				inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
				where cdd.Empresa = 1
				$sqlCond
				$sqlCondCadastro
				group by cdd.Cadastro_ID, cdd.Nome, tc.Tipo_ID
				order by cdd.Nome, cdd.Cadastro_ID, tc.Tipo_ID";
		*/
		$sql = "SELECT cdd.Cadastro_ID AS Cadastro_ID, tc.Tipo_ID, tc.Descr_Tipo AS Tipo, COALESCE(SUM(ft.Valor_Titulo),0) AS Valor
					FROM cadastros_dados cdd
					INNER JOIN financeiro_contas fc ON cdd.Cadastro_ID = fc.Cadastro_ID_de
					INNER JOIN financeiro_titulos ft ON fc.Conta_ID = ft.Conta_ID
					INNER JOIN tipo tc ON tc.Tipo_ID = fc.Tipo_ID AND tc.Tipo_Grupo_ID = 27
					WHERE cdd.Empresa = 1
					$sqlCond
					$sqlCondCadastro
					GROUP BY cdd.Cadastro_ID, cdd.Nome, tc.Tipo_ID
				union all
				SELECT cdd.Cadastro_ID AS Cadastro_ID, '46B' as Tipo_ID, 'Transferência Entrada' AS Tipo, COALESCE(SUM(ft.Valor_Titulo),0) AS Valor
					FROM cadastros_dados cdd
					INNER JOIN financeiro_contas fc ON cdd.Cadastro_ID = fc.Cadastro_ID_para
					INNER JOIN financeiro_titulos ft ON fc.Conta_ID = ft.Conta_ID
					INNER JOIN tipo tc ON tc.Tipo_ID = fc.Tipo_ID AND tc.Tipo_Grupo_ID = 27
					WHERE cdd.Empresa = 1 and fc.Tipo_ID = 46
					$sqlCond
					$sqlCondCadastro
					GROUP BY cdd.Cadastro_ID, cdd.Nome, tc.Tipo_ID
				ORDER BY 1";

		//echo $sql;
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			if ($rs[Tipo_ID]=="44")
				$dadosAux[$rs[Cadastro_ID]][Saida] = $dadosAux[$rs[Cadastro_ID]][Saida] + $rs[Valor];
			if ($rs[Tipo_ID]=="45")
				$dadosAux[$rs[Cadastro_ID]][Entrada] = $dadosAux[$rs[Cadastro_ID]][Entrada] + $rs[Valor];
			if ($rs[Tipo_ID]=="46")
				$dadosAux[$rs[Cadastro_ID]][Transferencia] = $dadosAux[$rs[Cadastro_ID]][Transferencia] + $rs[Valor];
			if ($rs[Tipo_ID]=="46B")
				$dadosAux[$rs[Cadastro_ID]][TransferenciaEntrada] = $dadosAux[$rs[Cadastro_ID]][TransferenciaEntrada] + $rs[Valor];
		}
		$j = 0;
		$colunas = "4";
		if ($moduloIgrejaAtivo){
			$colunas = "5";
			$colunaMembros = "<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Membros</b></td>";
			$sql = "select il.Igreja_ID as Cadastro_ID, sum(il.Quantidade) as Quantidade
					from igreja_lancamento il
					inner join cadastros_dados cdd on cdd.Cadastro_ID = il.Igreja_ID
					where cdd.Empresa = 1
					$sqlCondCadastro
					$sqlCondIgreja
					group by il.Igreja_ID
					order by il.Igreja_ID";
			//echo $sql;
			//exit();

			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$dadosAux[$rs[Cadastro_ID]][Membro] += $rs[Quantidade];
			}

		}

		$cabecalho = "	<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque ><b>Cadastro</b></td>
							$colunaMembros
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Entradas</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Sa&iacute;das</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Saldo</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Tranfer&ecirc;ncias entradas</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Tranfer&ecirc;ncias sa&iacute;das</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Saldo Geral</b></td>
						</tr>";

		$html .= "<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
					<tr class='esconde'>
						<td class='tabela-fundo-escuro-titulo' styletitulo valign='top' width='518' height='53' align='center'>RELAT&Oacute;RIO ENTRADAS X SA&Iacute;DAS</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='7' valign='middle' align='right' width='210' height='53'><img src='$caminhoSistema/images/topo/logo.png';?></td>
					</tr>";
		if ($_POST['separar-por']!='cc'){
			$html .= $cabecalho;
		}

		foreach($dadosAux as $chave => $dado){
			if ($dado[Nome]!=""){
				$pula = 0;
				if ($_POST['filtro-dinamico']=="exibir-zerados"){
					if (($dado[Entrada]>0) || ($dado[Saida]>0)) $pula=1;
				}
				if ($_POST['filtro-dinamico']=="nao-exibir-zerados"){
					if (($dado[Entrada]==0) && ($dado[Saida]==0)) $pula=1;
				}
				if ($pula==0){
					$j++;
					if($j%2==0)$classe='tabela-fundo-escuro'; else $classe='tabela-fundo-claro';
					$saldoParcial = ($dado[Entrada] - $dado[Saida]);
					$saldoGeral = ($dado[Entrada] - $dado[Saida] - $dado[Transferencia]);

					if (($_POST['separar-por']=='cc') && ($dado[Centro_Custo_ID]!=$ccIDAnt)){
						if ($j!=1){$html .= exibeTotaisCC($totECC,$totSCC,$totMCC, $totTCC, $totTCCB, $moduloIgrejaAtivo, $ativasCC, $inativasCC); $totECC = 0; $totSCC = 0; $totTCC = 0; $totTCCB = 0; $totMCC=0; $ativasCC = 0; $inativasCC = 0;}
						$html .= "<tr>
									<td class='tabela-fundo-escuro-titulo' styledestaque align='left'><b>Centro de Custo ".$dado[Centro_Custo]."</b><br>Respons&aacute;vel: ".$dado[Responsavel]."</td>
									$colunaMembros
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Entradas</b></td>
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Sa&iacute;das</b></td>
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Saldo</b></td>
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Tranfer&ecirc;ncias entradas</b></td>
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Tranfer&ecirc;ncias sa&iacute;das</b></td>
									<td class='tabela-fundo-escuro-titulo' styledestaque width='10%' align='right'><b>Saldo Geral (Reten&ccedil;&atilde;o)</b></td>
								  </tr>";
					}
					if ($moduloIgrejaAtivo)
						$colunaMembroLinha = "<td align='right' stylenormal>&nbsp;".number_format($dado[Membro], 0, ',', '.')."</td>";
					$estilo = "";
					if ($dado[Situacao_ID]==3) $estilo = "lixeira";

					$html .= "<tr class='$classe'>
								<td stylenormal class='$estilo'>&nbsp;".$dado[Nome]."</td>
								$colunaMembroLinha
								<td align='right' stylenormal>&nbsp;".number_format($dado[Entrada], 2, ',', '.')."</td>
								<td align='right' stylenormal>&nbsp;".number_format($dado[Saida], 2, ',', '.')."</td>
								<td align='right' stylenormal>&nbsp;".number_format($saldoParcial, 2, ',', '.')."</td>
								<td align='right' stylenormal>&nbsp;".number_format($dado[TransferenciaEntrada], 2, ',', '.')."</td>
								<td align='right' stylenormal>&nbsp;".number_format($dado[Transferencia], 2, ',', '.')."</td>
								<td align='right' stylenormal>&nbsp;".number_format($saldoParcial + $dado[TransferenciaEntrada] -  $dado[Transferencia], 2, ',', '.')."</td>
							  </tr>";

					$totE = $totE + $dado[Entrada];
					$totS = $totS + $dado[Saida];
					$totT = $totT + $dado[Transferencia];
					$totTB = $totTB + $dado[TransferenciaEntrada];
					$totM = $totM + $dado[Membro];
					$totECC = $totECC + $dado[Entrada];
					$totSCC = $totSCC + $dado[Saida];
					$totTCC = $totTCC + $dado[Transferencia];
					$totTCCB = $totTCCB + $dado[TransferenciaEntrada];
					$totMCC = $totMCC + $dado[Membro];
					$ccIDAnt = $dado[Centro_Custo_ID];
				}
				if ($dado[Situacao_ID]==1){
					$ativas++;
					$ativasCC++;
				}
				else{
					$inativas++;
					$inativasCC++;
				}

			}
		}
		if($i==0){
			$html .= "<tr><td Style='margin:2px 5px 0 5px; text-align:center' colspan='$colunas'>Nenhum registro encontrado</td></tr>";
		}
		else{
			if ($_POST['separar-por']=='cc'){$html .= exibeTotaisCC($totECC, $totSCC, $totMCC, $totTCC, $totTCCB, $moduloIgrejaAtivo, $ativasCC, $inativasCC);}
			if ($moduloIgrejaAtivo)
				$colunaMembroLinhaTotal = "<td align='right' stylenormal><b>".number_format($totM, 0, ',', '.')."</b></td>";


			$html .=  "<tr class='tabela-fundo-escuro-titulo'>
						<td align='right' stylenormal><b>TOTAL GERAL</b>
						$colunaMembroLinhaTotal
						<td align='right' stylenormal><b>".number_format($totE, 2, ',', '.')."</b></td>
						<td align='right' stylenormal><b>".number_format($totS, 2, ',', '.')."</b></td>
						<td align='right' stylenormal><b>".number_format(($totE - $totS), 2, ',', '.')."</b></td>
					  </tr>";
		}

		$html .= "
				</table>
			</div>";
	}

	echo $html;

	$html = str_replace("styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'", $html);
	$html = str_replace("100%","718", $html);
	$_SESSION["session-conteudo-relatorio"] = $html;
?>