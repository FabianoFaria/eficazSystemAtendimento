<?php
	include('functions.php');
	global $configFinanceiro;
	$virgula = "";
	for($i = 0; $i < count($_POST['check-tipo-grupo-27']); $i++){
		$tipos .= $virgula.$_POST['check-tipo-grupo-27'][$i];
		$virgula = ",";
	}

	$centrosCusto = $_POST['localiza-centro-custo'];
	$tipoContaID = $_POST['localiza-tipo-conta'];
	$situacaoID = $_POST['localiza-situacao-conta'];
	$codigo = trim($_POST['localiza-codigo']);
	$localizaCadastroDe = trim($_POST['localiza-cadastro-de']);
	$localizaCadastroPara = trim($_POST['localiza-cadastro-para']);

	$tituloID = trim($_POST['localiza-titulo']);
	$contaID = trim($_POST['localiza-conta']);


	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$dataInicioLancamento = $_POST['data-inicio-lancamento'];
		$dataFimLancamento = $_POST['data-fim-lancamento'];
	}
	else{
		$dataInicioVencimento = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");
	}
	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1)
		$classeEscondeMultiEmpresa = " esconde ";

	if ($configFinanceiro['exibe-conta']==1){
		$classeEscondeMultiEmpresa = " esconde ";
	}

	/**/
	if ($dadospagina['Slug_Pagina']=='financeiro-contas'){
		$escondeTipoConta = '';
	}
	else{
		$escondeTipoConta = ' esconde ';
		if ($dadospagina['Slug_Pagina']=='financeiro-contas-pagar'){
			$filtroTipoConta = "44";
		}
		if ($dadospagina['Slug_Pagina']=='financeiro-contas-receber'){
			$filtroTipoConta = "45";
		}
		if ($dadospagina['Slug_Pagina']=='financeiro-contas-transferencias'){
			$filtroTipoConta = "46";
		}
	}
?>
					<div class='titulo-container conjunto1'>
						<div class='titulo' style="min-height:25px">
							<p style="margin-top:2px;">
							Filtros de Pesquisa
<?php
			echo "				<input type='button' value='Incluir Lan&ccedil;amento' id='botao-incluir-conta' class='botao-incluir-conta' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>";
			echo "				<input type='hidden' value='".$configFinanceiro['lancamento-fancybox']."' id='lancamento-fancybox'>";

?>
							</p>
						</div>
						<div class='conteudo-interno'>
							<div style='float:left; width:100%'>
								<div class='titulo-secundario <?php echo $escondeTipoConta;?>' style='float:left;width:30%'>
									<div class='div-normal'>
										<p><b>Contas</b></p>
										<p><?php echo montaCheckboxGroupo(27, serialize($_POST['check-tipo-grupo-27']));?></p>
									</div>&nbsp;
								</div>
								<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='titulo-secundario' style='float:left;width:50%'>
									<p><b>Conta ID</b></p>
									<p><input type='text' id='localiza-conta' name='localiza-conta' class='formata-numero'  maxlength='50' value='<?php echo $contaID;?>' style='width:90%'/></p>
								</div>
								</div>
								<!--
								<div class='titulo-secundario' style='float:left;width:15%'>
									<div class='div-normal'>
										<p>T&iacute;tulo ID</p>
										<p><input type='text' id='localiza-titulo' name='localiza-titulo' class='formata-numero'  maxlength='50' value='<?php echo $tituloID;?>' style='width:90%'/></p>
									</div>&nbsp;
								</div>
								-->
								<div class="titulo-secundario" style='float:left;width:40%' >
									<div class="titulo-secundario" style='float:left;width:50%' >
										<p><b>Data Vencimento</b></p>
										<div style='width:43%;float:left;'>
											<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
										</div>
										<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
										<div style='width:43%;float:left;'>
											<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
										</div>
									</div>
									<div class='titulo-secundario' style='float:left;width:50%'>
										<p><b>Data Lan&ccedil;amento</b></p>
										<div style='width:43%;float:left;'>
											<p><input type='text' name='data-inicio-lancamento' id='data-inicio-lancamento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioLancamento; ?>'>&nbsp;&nbsp;</p>
										</div>
										<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
										<div style='width:43%;float:left;'>
											<p><input type='text' name='data-fim-lancamento' id='data-fim-lancamento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimLancamento; ?>'></p>
										</div>
										<!--
										<p>C&oacute;digo T&iacute;tulo</p>
										<p><input type='text' id='localiza-codigo' name='localiza-codigo' class='formata-numero'  maxlength='50' value='<?php echo $codigo;?>' style='width:90%'/></p>
										-->
									</div>
								</div>
								<div class='titulo-secundario' style='float:left;width:20%'>
									<div class='div-normal'>
										<div class='<?php echo $classeEscondeMultiEmpresa;?>'>
											<p><b><?php echo $configFinanceiro['cadastro'];?></b></p>
											<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple><?php echo optionValueEmpresasMultiplo($localizaCadastroDe);?></select></p>
										</div>
									</div>
								</div>

								<div class='titulo-secundario' style='float:left;width:20%'>
									<div class='div-normal'>
										<p><b>Favorecido / Pagador</b></p>
										<p><input type='text' id='localiza-cadastro-para' name='localiza-cadastro-para' maxlength='250' value='<?php echo $localizaCadastroPara;?>' style='width:98.2%'/></p>
									</div>&nbsp;
								</div>
								<div class='titulo-secundario' style='float:left;width:20%'>
									<div class='div-normal'>
										<p><b>Tipo Conta</b></p>

										<p><select name='localiza-tipo-conta' id='localiza-tipo-conta'><option></option><?php echo optionValueTipoConta($_POST['localiza-tipo-conta']);?></select></p>
									</div>&nbsp;
								</div>
<?php
			if ($configFinanceiro['exibe-centro-custo']){
				echo "			<div class='titulo-secundario' style='float:left;width:20%'>
									<div class='div-normal'>
										<p><b>Centro de Custo</b></p>
										<p>
											<select id='localiza-centro-custo' name='localiza-centro-custo'><option></option>".optionValueGrupoFilho(26, $_POST['localiza-centro-custo'], "","")."</select>
										</p>
									</div>&nbsp;
								</div>";
			}
?>
								<div class='titulo-secundario' style='float:left;width:10%'>
									<p><b>Situa&ccedil;&atilde;o</b></p>
									<p><select name='localiza-situacao-conta' id='localiza-situacao-conta' >
									<?php echo optionValueGrupo(29, $situacaoID,'&nbsp;');?>
									<option value='-1' <?php if ($situacaoID=='-1'){ echo 'selected';}?>>Pendente</option>
									<option value='-2' <?php if ($situacaoID=='-2'){ echo 'selected';}?>>Atrasado</option>
									</select></p>
								</div>
								<div class='titulo-secundario duas-colunas' Style='margin-top:15px; float:right;width:10%' >
									<p align='right'><input type='button' value='Pesquisar' id='botao-pesquisar-contas' style='width:100px:margin-right:2px'/></p>
								</div>
							</div>
						</div>
					</div>
				<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>
				<input type='hidden' id='localiza-titulo-id' name='localiza-titulo-id' value=''>
<?php

		if ($situacaoID=="-1"){
			$sqlCond .= " and ft.Situacao_Pagamento_ID = '$situacaoID' ";
		}
		elseif ($situacaoID == "-2"){
			$sqlCond .= " and ft.Data_Vencimento < '".retornaDataHora('d','Y-m-d')." 00:00' and Situacao_Pagamento_ID = 49";
			if ($filtroCadastroConta!="") $sqlCond .= " and (fc.Cadastro_Conta_ID_de = '$filtroCadastroConta' or fc.Cadastro_Conta_ID_para = '$filtroCadastroConta')";
		}
		else{
			if ($situacaoID != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID = '$situacaoID' ";}
			if ($filtroCadastroConta!="") $sqlCond .= " and (fc.Cadastro_Conta_ID_de = '$filtroCadastroConta' or fc.Cadastro_Conta_ID_para = '$filtroCadastroConta')";
			if ($tituloID!=""){ $sqlCond .= " and ft.Titulo_ID = '$tituloID' ";}
			if ($contaID!=""){ $sqlCond .= " and fc.Conta_ID = '$contaID' ";}
			if ($codigo != ""){ $sqlCond .= " and ft.Codigo like '%$codigo%'";}
			//if ($localizaCadastroDe != ""){ $sqlCond .= " and (cdd.Nome like '%$localizaCadastroDe%'  or cdd.Nome_Fantasia like '%$localizaCadastroDe%')";}
			if ($tipos != ""){ $sqlCond .= " and  fc.Tipo_ID IN ($tipos)";}

			if (($centrosCusto!="") || ($tipoContaID!="")){
				$sqlInnerjoinContabil = " inner join financeiro_contabil fco on fco.Conta_ID = fc.Conta_ID and fco.Situacao_ID = 1";
				if ($centrosCusto!="")
					$sqlCond .= " and fco.Centro_Custo_ID in ($centrosCusto)";
				if ($tipoContaID!="")
					$sqlCond .= " and fco.Tipo_Conta_ID in ($tipoContaID)";
				$sqlGroupBy = " group by fc.Conta_ID, ft.Titulo_ID ";
			}

			if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
				$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
				if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
				$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
				if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
				$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
			}

			if(($dataInicioLancamento!="")||($dataFimLancamento!="")){
				$dataInicioLancamento = implode('-',array_reverse(explode('/',$dataInicioLancamento)));
				if ($dataInicioLancamento=="") $dataInicioLancamento = "0000-00-00"; $dataInicioLancamento .= " 00:00";
				$dataFimLancamento = implode('-',array_reverse(explode('/',$dataFimLancamento)));
				if ($dataFimLancamento=="") $dataFimLancamento = "2100-01-01"; $dataFimLancamento .= " 23:59";
				$sqlCond .= " and fc.Data_Cadastro between '$dataInicioLancamento' and '$dataFimLancamento' ";
			}
			if ($localizaCadastroPara != ""){ $sqlCond .= " and cdp.Nome like '%$localizaCadastroPara%' ";}
			if ($filtroTipoConta!=''){ $sqlCond .= " and fc.Tipo_ID = '$filtroTipoConta'";}
		}

		$sql = "select fc.Conta_ID as Conta_ID, fc.Codigo as Codigo, ft.Codigo as Codigo_Titulo, fc.Tipo_ID, tc.Descr_Tipo as Tipo, tcc.Descr_Tipo as Tipo_Conta,  fc.Valor_Total,
					ft.Titulo_ID, tfp.Descr_Tipo as Forma_Pagamento, ft.Situacao_Pagamento_ID,
					cdd.Nome as Nome_De, cdd.Nome_Fantasia as Nome_Fantasia_De,
					cdp.Nome as Nome_Para, cdp.Nome_Fantasia as Nome_Fantasia_Para,
					ft.Valor_Titulo,
					DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento,
					DATE_FORMAT(fc.Data_Cadastro, '%d/%m/%Y') as Data_Lancamento,
					IF (tsp.Tipo_ID = 49, ft.Valor_Pago,'') as Valor_Pago,
					IF (tsp.Tipo_ID = 49, DATE_FORMAT(ft.Data_Pago, '%d/%m/%Y'),'') as Data_Pago,
					IF (tsp.Tipo_ID = 48, DATEDIFF('".retornaDataHora('d','Y-m-d')."',DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d')),'') as DiasAtraso,
					tsp.Descr_Tipo as Situacao_Pagamento,
					cc.Nome_Conta as Nome_Conta,
					fc.Cadastro_Conta_ID_de, fc.Cadastro_Conta_ID_para, fc.Observacao,
					tccc.Descr_Tipo as Centro_Custo
					from financeiro_contas fc
					inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
					inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
					inner join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_de
					".$sqlInnerjoinContabil."
					left join cadastros_contas cc on cc.Cadastro_Conta_ID = fc.Cadastro_Conta_ID_de
					left join tipo tcc on tcc.Tipo_ID = fc.Tipo_Conta_ID and tcc.Tipo_Grupo_ID = 28
					left join tipo tccc on tccc.Tipo_ID = fc.Centro_Custo_ID
					left join cadastros_dados cdp on cdp.Cadastro_ID = fc.Cadastro_ID_para
					left join tipo tfp on tfp.Tipo_ID  = ft.Forma_Pagamento_ID and tfp.Tipo_Grupo_ID = 25
					left join tipo tsp on tsp.Tipo_ID  = ft.Situacao_Pagamento_ID and tsp.Tipo_Grupo_ID = 29
					where fc.Conta_ID is not null
					".$sqlCond."
					".$sqlGroupBy."
					order by ft.Data_Vencimento, fc.Tipo_ID";

		//echo $sql;
		$i=0;

		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			$situacao ="";
			$situacaoPagamento = $rs[Situacao_Pagamento];
			$valor = number_format($rs[Valor_Titulo], 2, ',', '.');
			$dataVencimento = $rs[Data_Vencimento]; if ($dataVencimento=="00/00/0000"){$dataVencimento="A definir";}

			if ($rs[Nome_De]==""){$nomeDe = "N&atilde;o Informado";}else{$nomeDe = $rs[Nome_De];}
			if ($rs[Nome_Para]==""){$nomePara = "N&atilde;o Informado";}else{$nomePara = $rs[Nome_Para];}


			$estiloFonte = "";
			if ($rs[Tipo_ID]=="44"){
				$estiloFonte = "color:#FF4D4D;";
				$icone = "<div style='float:left;' class='icone-saida' title='".$rs[Tipo]."'>&nbsp;</div>";
			}
			if ($rs[Tipo_ID]=="45"){
				$estiloFonte = "color:#0047c9;";
				$icone = "<div style='float:left;' class='icone-entrada' title='".$rs[Tipo]."'>&nbsp;</div>";
			}
			if ($rs[Tipo_ID]=="46"){
				$estiloFonte = "color:#4F4F4F;";
				if ($rs[Cadastro_Conta_ID_de]==$filtroCadastroConta)
					$estiloFonte = "color:#FF4D4D;";
				if ($rs[Cadastro_Conta_ID_para]==$filtroCadastroConta)
					$estiloFonte = "color:#0047c9;";
				$icone = "<div style='float:left;' class='icone-entrada-saida' title='".$rs[Tipo]."'>&nbsp;</div>";
			}

			$dados[colunas][tr][$i] = " style='".$estiloFonte." font-weight:bold; cursor:pointer;' class='localiza-conta lnk' conta-id='".$rs[Conta_ID]."'";
			if ($rs[Situacao_Pagamento_ID]=="48"){
				if ($rs[DiasAtraso]>0){$situacao = "<p class='mini-bola-vermelha' Style='margin:0px;float:left;cursor:pointer;' title='$rs[DiasAtraso] dia(s) em atraso'>&nbsp;</p>";}
				if ($rs[DiasAtraso]==0){$situacao = "<p class='mini-bola-amarela' Style='margin:0px;float:left;cursor:pointer;' title='O Vencimento &eacute; hoje'>&nbsp;</p>";}
				if ($rs[DiasAtraso]<0){$situacao = "<p class='mini-bola-azul' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo com vencimento para dia $rs[Data_Vencimento]'>&nbsp;</p>";}
			}
			if ($rs[Situacao_Pagamento_ID]=="49"){$situacao = "<p class='mini-bola-verde' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo pago dia $rs[Data_Pago]'>&nbsp;</p>";}
			if ($rs[Situacao_Pagamento_ID]=="50"){$situacao = "<p class='mini-bola-cinza' Style='margin:0px;float:left;cursor:pointer;' title='Cancelado'>&nbsp;</p>";}
			if ($rs[Situacao_Pagamento_ID]=="-1"){$situacao = "<p class='mini-bola-cinza' Style='margin:0px;float:left;cursor:pointer;' title='Faturado Pendente de Preenchimento'>&nbsp;</p>"; $situacaoPagamento="Faturado Pendente"; $valor = number_format($rs[Valor_Total], 2, ',', '.');}



			/******************************/
			$c=1;
			$dados[colunas][conteudo][$i][$c++] = $icone;
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".$rs[Conta_ID]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<center>".$dataVencimento."</center>";

			if (($configFinanceiro['exibe-conta']) && ($filtroCadastroConta=='')){
				$dados[colunas][conteudo][$i][$c++] = "<span>".$rs['Nome_Conta']."</span>";
			}
			if ((!($configFinanceiro['exibe-conta'])) && ($contEmpresas>1))
				$dados[colunas][conteudo][$i][$c++] = "<span>$nomeDe</span>";

			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$nomePara."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$rs[Tipo_Conta]."</p>";

			if ($configFinanceiro['exibe-centro-custo'])
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$rs['Centro_Custo']."</p>";

			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$rs['Observacao']."</p>";
			$dados[colunas][conteudo][$i][$c++] = "$situacao<p Style='margin:1px 1px 0 1px;float:left;'>".($situacaoPagamento)."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:right;'>".$valor."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$rs[Forma_Pagamento]."</p>";

			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format($rs[Valor_Pago], 2, ',', '.')."</p>";
			//$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:right;'>".$rs[Data_Pago]."</p>";
			//$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px;float:right;'>".$rs[Data_Lancamento]."</p>";

			/******************************/

		}
		$largura = "100%";
		$colunas = $c-1;


		$c=1;
		$dados[colunas][titulo][$c++] 	= " ";
		$dados[colunas][titulo][$c++] 	= "<center>Conta N&ordm;</center>";
		$dados[colunas][titulo][$c++] 	= "<center>Data Vencimento</center>";
		if (($configFinanceiro['exibe-conta']) && ($filtroCadastroConta=='')){
			$dados[colunas][titulo][$c++] 	= "Conta";
		}
		if ((!($configFinanceiro['exibe-conta'])) && ($contEmpresas>1))
				$dados[colunas][titulo][$c++] 	= $configFinanceiro['cadastro'];


		$dados[colunas][titulo][$c++] 	= "Favorecido / Pagador";
		$dados[colunas][titulo][$c++] 	= "Tipo Conta";

		if ($configFinanceiro['exibe-centro-custo'])
			$dados[colunas][titulo][$c++] 	= "Centro Custo";

		$dados[colunas][titulo][$c++] 	= "Hist&oacute;rico";
		$dados[colunas][titulo][$c++] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][$c++] 	= "<p Style='margin:2px 2px 0 2px;float:right;'>Valor T&iacute;tulo</p>";
		$dados[colunas][titulo][$c++]	= "<p Style='margin:2px 2px 0 2px;float:left;'>Forma Pgto</p>";
		$dados[colunas][titulo][$c++]	= "<p Style='margin:2px 2px 0 2px;float:left;'>Valor Pago</p>";



		$dados[colunas][cabecalho][conteudo] = "	<p style='text-align:center;'>$descrCadastroConta
								<br>Per&iacute;odo: ".$_POST['data-inicio-vencimento']." at&eacute; ".$_POST['data-fim-vencimento']."</p>";
		$dados[colunas][cabecalho][classe] = "tabela-fundo-escuro-titulo";


		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum lan&ccedil;amento localizado no per&iacute;odo informado</p>";
		}
		else{
			echo "<div class='titulo-container conjunto1'>";
			geraTabela($largura, $colunas, $dados, null, 'financeiro-localiza-cadastros', 2, 2, 500,1);
			echo "</div>";
		}

?>