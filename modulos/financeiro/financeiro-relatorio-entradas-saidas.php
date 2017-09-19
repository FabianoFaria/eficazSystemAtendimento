<?php
	include('functions.php');
	global $configFinanceiro;

	function exibeTotaisCC($tipos, $a,$b, $c,$d){
		if ($tipos != "44"){
			$totais .= "<tr class='tabela-fundo-escuro-titulo'>
						<td colspan='6' align='right' styledestaque><b>Total Entrada CC</b></td>
						<td align='right' styledestaque><b>R$ ".number_format($c, 2, ',', '.')."</b></td>
						<td colspan='2' styledestaque>&nbsp;</td>
						<td align='right' styledestaque><b>R$ ".number_format($d, 2, ',', '.')."</b></td>
						<td colspan='1' styledestaque>&nbsp;</td>
					  </tr>";
		}
		if ($tipos != "45"){
			$totais .= "<tr class='tabela-fundo-escuro-titulo'>
						<td colspan='6' align='right' styledestaque><b>Total Sa&iacute;da CC</b></td>
						<td align='right' styledestaque><b>R$ ".number_format($a, 2, ',', '.')."</b></td>
						<td colspan='2' styledestaque>&nbsp;</td>
						<td align='right' styledestaque><b>R$ ".number_format($b, 2, ',', '.')."</b></td>
						<td colspan='1' styledestaque>&nbsp;</td>
					  </tr>";
		}
		return($totais);
	}

	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
		$colunaCabecalho = 7;
	}else{
		$colunaCabecalho = 8;
	}

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
	for($i = 0; $i < count($_POST['localiza-regionais']); $i++){
		$regionais .= $virgula.$_POST['localiza-regionais'][$i];
		$virgula = ",";
	}
	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
		$cadastrosDe .= $virgula.$_POST['localiza-cadastro-de'][$i];
		$virgula = ",";
	}
	$codigo = trim($_POST['localiza-codigo']);

	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$virgula = "";
		for($i = 0; $i < count($_POST['check-tipo-grupo-27']); $i++){
			$tipos .= $virgula.$_POST['check-tipo-grupo-27'][$i];
			$virgula = ",";
		}
		$localizaCadastroPara = trim($_POST['localiza-cadastro-para']);
	}
	else{
		$dataInicioVencimento = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");
		$classeEsconde = "esconde";
		$situacoes = "48,49";
	}

?>
				<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Transferecias'>
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
									<p><b>Data Vencimento</b></p>
									<div style='width:45%;float:left;'>
										<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
									</div>
									<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
									<div style='width:45%;float:left;'>
										<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
									</div>
								</div>
								<div style='width:100%;float:left;'>
									<p><b>Contas</b></p>
									<p><?php echo montaCheckboxGroupo(27, serialize($_POST['check-tipo-grupo-27']), " and Tipo_ID in(44,45) ");?></p>
								</div>
							</div>
							<div class='titulo-secundario' style='float:left;width:30%'>
								<div class='titulo-secundario' style='float:left;width:50%'>
									<p><b>Situa&ccedil;&atilde;o</b></p>
									<p><select name='localiza-situacao-conta[]' id='localiza-situacao-conta' multiple Style='height:58px'><?php echo optionValueGrupoMultiplo(29, $situacoes);?></select></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:50%'>
									<p><b>Tipo Conta</b></p>
									<p><select name='localiza-tipo-conta[]' id='localiza-tipo-conta'  multiple Style='height:58px'><?php echo optionValueGrupoMultiplo(28, $tiposContas);?></select></p>
								</div>
								<div class='titulo-secundario' style='float:left;width:100%;margin-top:3px'>
									<p><b>Favorecido / Pagador</b></p>
									<p><input type='text' id='localiza-cadastro-para' name='localiza-cadastro-para' maxlength='250' value='<?php echo $localizaCadastroPara;?>' style='width:95%'/></p>
								</div>
							</div>
							<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p><b>Regionais</b></p>
									<p><select name='localiza-regionais[]' id='localiza-regionais' multiple Style='height:102px'><?php echo optionValueGrupoMultiplo(26, $regionais);?></select></p>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p><b><?php echo $configFinanceiro['cadastro'];?></b></p>
									<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple Style='height:102px'><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario' style='float:left;width:10%;margin-top:15px'>
								<div style='width:100%;float:left;'>
									<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
								</div>
								<div style='width:100%;float:left; height:29px;margin-top:3px'>
									<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel' style="float:left;" title="Gerar Excel"></div>&nbsp;
									<div class="btn-imprimir <?php echo $classeEsconde; ?>" id='botao-imprimir' style="float:left;" title="Imprimir"></div>&nbsp;
								</div>
								<div class='titulo-secundario' style='float:left;width:100%'>
									<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<p><b>Separar por</b></p>
										<p>
											<select id='separar-por' name='separar-por' style='width:92%'>
												<option></option>
												<!--<option value='cad' <?php echo verificaSelected($_POST['separar-por'],'cad');?>><?php echo $configFinanceiro['cadastro'];?></option>-->
												<option value='reg' <?php echo verificaSelected($_POST['separar-por'],'reg');?>>Regionais</option>
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
		if ($cadastrosDe != ""){ $sqlCond .= " and cdd.Cadastro_ID IN ($cadastrosDe)";}
		if ($situacoes != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID IN ($situacoes)";}
		if ($tiposContas !=""){ $sqlCond .= " and fc.Tipo_Conta_ID IN ($tiposContas)";}
		if ($tipos != ""){ $sqlCond .= " and  fc.Tipo_ID IN ($tipos)";}

		if ($codigo != ""){ $sqlCond .= " and ft.Codigo like '%$codigo%'";}
		if ($localizaCadastroDe != ""){ $sqlCond .= " and (cdd.Nome like '%$localizaCadastroDe%'  or cdd.Nome_Fantasia like '%$localizaCadastroDe%')";}
		if ($regionais != ""){ $sqlCond .= " and cdd.Centro_Custo_ID IN ($regionais)";}
		if ($localizaCadastroPara != ""){ $sqlCond .= " and cdp.Nome like '$localizaCadastroPara%' ";}


		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}

		if ($_POST['separar-por']=='reg'){
			$sqlOrderBy = " order by cdd.Centro_Custo_ID, fc.Tipo_ID, ft.Data_Vencimento ";
		}else{
			$sqlOrderBy = " order by fc.Tipo_ID, ft.Data_Vencimento ";
		}

		$sql = "select fc.Conta_ID as Conta_ID, fc.Codigo as Codigo, ft.Codigo as Codigo_Titulo, fc.Tipo_ID as Tipo_ID, tc.Descr_Tipo as Tipo, tcc.Descr_Tipo as Tipo_Conta,  fc.Valor_Total,
					ft.Titulo_ID, tfp.Descr_Tipo as Forma_Pagamento, ft.Forma_Pagamento_ID, ft.Situacao_Pagamento_ID,
					cdd.Nome as Nome_De, cdd.Nome_Fantasia as Nome_Fantasia_De,
					cdp.Nome as Nome_Para, cdp.Nome_Fantasia as Nome_Fantasia_Para,
					ft.Valor_Titulo, cdd.Centro_Custo_ID as Centro_Custo_ID, tpcc.Descr_Tipo as Centro_Custo,
					DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento,
					IF (tsp.Tipo_ID = 49, ft.Valor_Pago,'') as Valor_Pago,
					IF (tsp.Tipo_ID = 49, DATE_FORMAT(ft.Data_Pago, '%d/%m/%Y'),'') as Data_Pago,
					IF (tsp.Tipo_ID = 48, DATEDIFF('".retornaDataHora('d','Y-m-d')."',DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d')),'') as DiasAtraso,
					tsp.Descr_Tipo as Situacao_Pagamento,
					coalesce(r.Nome,'N/A') as Responsavel
					from financeiro_contas fc
					inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
					inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
					inner join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_de
					left join tipo tcc on tcc.Tipo_ID = fc.Tipo_Conta_ID and tcc.Tipo_Grupo_ID = 28
					left join cadastros_dados cdp on cdp.Cadastro_ID = fc.Cadastro_ID_para
					left join tipo tfp on tfp.Tipo_ID  = ft.Forma_Pagamento_ID and tfp.Tipo_Grupo_ID = 25
					left join tipo tsp on tsp.Tipo_ID  = ft.Situacao_Pagamento_ID and tsp.Tipo_Grupo_ID = 29
					left join tipo tpcc on tpcc.Tipo_ID = cdd.Centro_Custo_ID and tpcc.Tipo_Grupo_ID = 26
					left join cadastros_dados r on r.Cadastro_ID = tpcc.Tipo_Auxiliar
					where fc.Tipo_ID in (44,45)
					$sqlCond
					$sqlOrderBy ";
		//echo $sql;
		$colunaEmpresa = "";
		if ($classeEmpresasEsconde==""){$colunaEmpresa = "<td class='tabela-fundo-escuro-titulo' styledestaque width='' align='left'><b>Cadastro</b></td>";}
		$cabecalho = "	<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='left'><b>T&iacute;tulo</b></td>
							$colunaEmpresa
							<td class='tabela-fundo-escuro-titulo' styledestaque width='' align='left'><b>Conta</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='' align='left'><b>Pagador / Emitente</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='' align='left'><b>Tipo</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='' align='left'><b>Situa&ccedil;&atilde;o</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='right'><b>Valor</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='right'><b>Data Vencimento</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='left'><b>Forma Pgto</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='right'><b>Valor Pago</b></td>
							<td class='tabela-fundo-escuro-titulo' styledestaque width='8%' align='right'><b>Data Pago</b></td>
						</tr>";
		$html = "<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>
				<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
					<tr class='esconde'>
						<td class='tabela-fundo-escuro-titulo' styletitulo valign='top' width='518' height='53' colspan='$colunaCabecalho'>RELAT&Oacute;RIO DE TRANSFER&Ecirc;CIAS</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo valign='middle' align='right' width='210' height='53' colspan='3' ><img src='$caminhoSistema/images/topo/logo.png';?></td>
					</tr>";
		if ($_POST['separar-por']!="reg"){
			$html .= $cabecalho;
		}


		$queryT = mpress_query("select Tipo_ID, upper(Descr_Tipo) as Descr_Tipo from tipo where Tipo_Grupo_ID = 25");
		while($tipo = mpress_fetch_array($queryT)){
			$arrayTotaisFP[$tipo[Tipo_ID]][45][Descricao] = $tipo[Descr_Tipo]." ENTRADAS";
			$arrayTotaisFP[$tipo[Tipo_ID]][44][Descricao] = $tipo[Descr_Tipo]." SA&Iacute;DAS";
			$arrayTotaisFP[$tipo[Tipo_ID]][45][TotalGeral] = 0;
			$arrayTotaisFP[$tipo[Tipo_ID]][44][TotalGeral] = 0;
			$arrayTotaisFP[$tipo[Tipo_ID]][45][TotalPago] = 0;
			$arrayTotaisFP[$tipo[Tipo_ID]][44][TotalPago] = 0;
		}

		$i=0;
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			if($i%2==0)$classe='tabela-fundo-escuro'; else $classe='tabela-fundo-claro';

			$arrayTotaisFP[$rs[Forma_Pagamento_ID]][$rs[Tipo_ID]][TotalGeral] += $rs[Valor_Titulo];
			$arrayTotaisFP[$rs[Forma_Pagamento_ID]][$rs[Tipo_ID]][TotalPago] += $rs[Valor_Pago];

			$situacao ="";
			if ($rs[Codigo_Titulo]==""){$codigoTitulo = "N/A";}else{$codigoTitulo = $rs[Codigo_Titulo];}
			if ($rs[Nome_De]==""){$nomeDe = "N&atilde;o Informado";}else{$nomeDe = $rs[Nome_De];}
			if ($rs[Nome_Para]==""){$nomePara = "N&atilde;o Informado";}else{$nomePara = $rs[Nome_Para];}
			$descricaoConta = $nomePara;
			if ($rs[Situacao_Pagamento_ID]=="48"){
				if ($rs[DiasAtraso]>0){$situacao = "<span class='mini-bola-vermelha localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='$rs[DiasAtraso] dia(s) em atraso'>&nbsp;</span>";}
				if ($rs[DiasAtraso]==0){$situacao = "<span class='mini-bola-amarela localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='O Vencimento &eacute; hoje'>&nbsp;</span>";}
				if ($rs[DiasAtraso]<0){$situacao = "<span class='mini-bola-azul localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo com vencimento para dia $rs[Data_Vencimento]'>&nbsp;</span>";}
			}
			if ($rs[Situacao_Pagamento_ID]=="49"){$situacao = "<span class='mini-bola-verde localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo pago dia $rs[Data_Pago]'>&nbsp;</span>";}
			if ($rs[Situacao_Pagamento_ID]=="50"){$situacao = "<span class='mini-bola-cinza localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='Cancelado'>&nbsp;</span>";}

			if (($_POST['separar-por']=="reg") && ($rs[Centro_Custo_ID]!=$ccIDAnt)){
				if ($rs[Centro_Custo]!="") $centroCusto = $rs[Centro_Custo]; else $centroCusto = "N/A";
				if ($rs[Responsavel]!="") $responsavel = $rs[Responsavel]; else $responsavel = "N/A";

				if ($i!=1){$html .= exibeTotaisCC($tipos, $totValorTituloGrupoS, $totValorPagoGrupoS, $totValorTituloGrupoE, $totValorPagoGrupoE);$totValorTituloGrupoE = 0;$totValorPagoGrupoE = 0; $totValorTituloGrupoS = 0;$totValorPagoGrupoS = 0;}
				$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='11'><b>Regional ".$centroCusto."</b><br>Respons&aacute;vel: ".$responsavel."</td></tr>$cabecalho";
			}

			$colunaEmpresa = "";
			if ($classeEmpresasEsconde==""){$colunaEmpresa = "<td align='left' stylenormal Style='margin:2px 5px 0 2px;'>".($nomeDe)."</td>";}

			$html .= "<tr class='$classe'>
						<td align='left' stylenormal Style='margin:2px 5px 0 2px; cursor:pointer;' class='link localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."'>".($codigoTitulo)."</td>
						$colunaEmpresa
						<td align='left' stylenormal Style='margin:2px 5px 0 2px;'>".$rs[Tipo]."</td>
						<td align='left' stylenormal Style='margin:2px 5px 0 2px;'>".$descricaoConta."</td>
						<td align='left' stylenormal Style='margin:2px 5px 0 2px;'>".$rs[Tipo_Conta]."</td>
						<td align='left' stylenormal Style='margin:2px 2px 0 2px;cursor:pointer' class='localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."'>$situacao".($rs[Situacao_Pagamento])."</td>
						<td align='right' stylenormal Style='margin:2px 5px 0 2px;'>".number_format($rs[Valor_Titulo], 2, ',', '.')."</td>
						<td align='right' stylenormal Style='margin:2px 5px 0 2px;'>".$rs[Data_Vencimento]."</td>
						<td align='left'  stylenormal Style='margin:2px 5px 0 2px;'>".$rs[Forma_Pagamento]."</td>
						<td align='right' stylenormal Style='margin:2px 5px 0 2px;'>".number_format($rs[Valor_Pago], 2, ',', '.')."</td>
						<td align='right' stylenormal Style='margin:2px 5px 0 2px;'>".$rs[Data_Pago]."</td>
					  </tr>";
			if ($rs['Tipo_ID']==44) { $totValorTituloS = $totValorTituloS + $rs[Valor_Titulo]; $totValorPagoS = $totValorPagoS + $rs[Valor_Pago]; }
			if ($rs['Tipo_ID']==45) { $totValorTituloE = $totValorTituloE + $rs[Valor_Titulo]; $totValorPagoE = $totValorPagoE + $rs[Valor_Pago]; }
			if ($rs['Tipo_ID']==44) { $totValorTituloGrupoS = $totValorTituloGrupoS + $rs[Valor_Titulo]; $totValorPagoGrupoS = $totValorPagoGrupoS + $rs[Valor_Pago];}
			if ($rs['Tipo_ID']==45) { $totValorTituloGrupoE = $totValorTituloGrupoE + $rs[Valor_Titulo]; $totValorPagoGrupoE = $totValorPagoGrupoE + $rs[Valor_Pago];}

			$ccIDAnt = $rs[Centro_Custo_ID];
		}
		if($i==0){
			$html .= "<tr><td Style='margin:2px 5px 0 5px; text-align:center' colspan='9'>Nenhuma transfer&ecirc;ncia encontrada</td></tr>";
		}else{
			if ($_POST['separar-por']=='reg')$html .= exibeTotaisCC($tipos, $totValorTituloGrupoS, $totValorPagoGrupoS, $totValorTituloGrupoE, $totValorPagoGrupoE);
			$colunas = "5";	if ($classeEmpresasEsconde==""){$colunas = "6";}
			$html .= "<tr><td colspan='".($colunas+3)."'>&nbsp;</td></tr><tr><td colspan='".($colunas+3)."'>&nbsp;</td></tr>";
			if ($tipos != "44"){
				$html .= "<tr class='$classe'>
							<td colspan='$colunas' align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>&nbsp;TOTAL GERAL ENTRADAS</b></td>
							<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($totValorTituloE, 2, ',', '.')."</b></td>
							<td colspan='2' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
							<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($totValorPagoE, 2, ',', '.')."</b></td>
							<td colspan='1' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
						  </tr>";
			}
			if ($tipos != "45"){
				$html .= "<tr class='$classe'>
							<td colspan='$colunas' align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>&nbsp;TOTAL GERAL SA&Iacute;DAS</b></td>
							<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($totValorTituloS, 2, ',', '.')."</b></td>
							<td colspan='2' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
							<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($totValorPagoS, 2, ',', '.')."</b></td>
							<td colspan='1' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
						  </tr>";
			}

			//echo "<pre>";
			//print_r($arrayTotaisFP);
			//echo "</pre>";
			$html .= "<tr class='$classe'><td>&nbsp;</td></tr>";

			foreach($arrayTotaisFP as $array){
				if (($array[45][TotalGeral]>0)||($array[45][TotalPago]>0)){
					$descricaoE = $array[45][Descricao];
					if ($descricaoE==""){$descricaoE = "N&Atilde;O INFORMADO ENTRADAS";}
						$html .= "<tr class='$classe'>
									<td colspan='$colunas' align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>&nbsp;TOTAL $descricaoE </b></td>
									<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($array[45][TotalGeral], 2, ',', '.')."</b></td>
									<td colspan='2' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
									<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($array[45][TotalPago], 2, ',', '.')."</b></td>
									<td colspan='1' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
								  </tr>";
				}
				if (($array[44][TotalGeral]>0)||($array[44][TotalPago]>0)){
					$descricaoS = $array[44][Descricao];
					if ($descricaoS==""){$descricaoS = "N&Atilde;O INFORMADO SA&Iacute;DAS";}
						$html .= "<tr class='$classe'>
									<td colspan='$colunas' align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>&nbsp;TOTAL $descricaoS </b></td>
									<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($array[44][TotalGeral], 2, ',', '.')."</b></td>
									<td colspan='2' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
									<td align='right' class='tabela-fundo-escuro-titulo' styledestaque><b>R$ ".number_format($array[44][TotalPago], 2, ',', '.')."</b></td>
									<td colspan='1' class='tabela-fundo-escuro-titulo' styledestaque>&nbsp;</td>
								  </tr>";
				}
			}


		}
		$html .= "</table>
			</div>";
	}

	echo $html;
	$html = str_replace("styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'", $html);
	$html = str_replace("100%","718", $html);
	$_SESSION["session-conteudo-relatorio"] = $html;
?>