b<?php
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
	$localizaCadastroConta = $_POST['localiza-cadastro-conta'];


	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
	}
	else{
		$dataInicioVencimento = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");
	}
	echo " <input type='hidden' value='".$configFinanceiro['lancamento-fancybox']."' id='lancamento-fancybox'>";
?>
					<script type="text/javascript" src="https://www.google.com/jsapi"></script>
					<div class='titulo-container conjunto1'>
						<div class='titulo' style="min-height:25px">
							<p style="margin-top:2px;">Filtros de Pesquisa</p>
						</div>
						<div class='conteudo-interno'>
							<div class="titulo-secundario" style='float:left;width:25%' >
								<p><b>Data Vencimento</b></p>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
								</div>
								<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
								</div>
							</div>
<?php
		if ($configFinanceiro['exibe-conta']){
			echo "			<div class='titulo-secundario' style='float:left;width:25%'>
								<p><b>Conta</b></p>
								<p><select id='localiza-cadastro-conta' name='localiza-cadastro-conta' class='required'>
									<option value=''></option>
									".optionValueContas($localizaCadastroConta)."
									</select>
								</p>
							</div>";
		}
?>
							<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='div-normal'>
									<p><b>Tipo Conta</b></p>
									<p><select name='localiza-tipo-conta' id='localiza-tipo-conta'><option></option><?php echo optionValueTipoConta($_POST['localiza-tipo-conta']);?></select></p>
								</div>&nbsp;
							</div>
<?php
			if ($configFinanceiro['exibe-centro-custo']){
				echo "		<div class='titulo-secundario' style='float:left;width:20%'>
								<div class='div-normal'>
									<p><b>Centro de Custo</b></p>
									<p><select id='localiza-centro-custo' name='localiza-centro-custo'><option></option>".optionValueGrupoFilho(26, $_POST['localiza-centro-custo'], "","")."</select></p>
								</div>&nbsp;
							</div>";
			}
?>
							<div class='titulo-secundario duas-colunas' Style='margin-top:15px; float:right;width:10%' >
								<p align='right'>
									<input type='button' value='Pesquisar' id='botao-pesquisar-contas' style='width:100px:margin-right:2px'/>
<?php
			if (($filtroRelatorio=='menu-superior-5') || ($filtroRelatorio=='menu-superior-1')){
				echo "				<div class='btn-imprimir' id='botao-imprimir' style='margin-top:5px; float:right;' title='Imprimir'></div>";
			}
?>
								</p>
							</div>
						</div>
					</div>
				<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>
				<input type='hidden' id='localiza-titulo-id' name='localiza-titulo-id' value=''>
<?php

		if ($situacaoID != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID = '$situacaoID' ";}
		if ($filtroCadastroConta!="") $sqlCond .= " and (fc.Cadastro_Conta_ID_de = '$filtroCadastroConta' or fc.Cadastro_Conta_ID_para = '$filtroCadastroConta')";
		if ($tituloID!=""){ $sqlCond .= " and ft.Titulo_ID = '$tituloID' ";}
		if ($contaID!=""){ $sqlCond .= " and fc.Conta_ID = '$contaID' ";}

		if (($centrosCusto!="") || ($tipoContaID!="")){
			$sqlInnerjoinContabil = " inner join financeiro_contabil fco on fco.Conta_ID = fc.Conta_ID and fco.Situacao_ID = 1";
			if ($centrosCusto!="")
				$sqlCond .= " and fco.Centro_Custo_ID in ($centrosCusto)";
			if ($tipoContaID!="")
				$sqlCond .= " and fco.Tipo_Conta_ID in ($tipoContaID)";
			$sqlGroupBy = " group by fc.Conta_ID, ft.Titulo_ID ";
		}

		if($localizaCadastroConta!=""){
			$sqlCond .= " and fc.Cadastro_Conta_ID_de = $localizaCadastroConta";
		}

		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}


		/****************************************************/
		/****      DE QUEM RECEBO E PARA QUEM PAGO       ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-1'){
			$sql = "select fc.Tipo_ID as Tipo_ID, coalesce(cdd.Cadastro_ID,0) as Cadastro_ID, coalesce(cdd.Nome,' Não Definido') as Cadastro, sum(coalesce(ft.Valor_Pago,0)) as Valor_Pago, sum(coalesce(ft.Valor_Titulo,0)) as Valor_Titulo
						from financeiro_contas fc
						inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
						$sqlInnerjoinContabil
						left join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_Para
						where fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID = 49
						$sqlCond
						group by fc.Tipo_Conta_ID, fc.Tipo_ID, coalesce(cdd.Cadastro_ID,0)
						order by Valor_Pago desc";
			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			$virgulaE = ""; $virgulaS = ""; $dadosPizzaS = ""; $dadosPizzaE = "";
			$htmlS = $htmlE = "<table width='100%' align='center' style='float:left; margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2'>";
			while($rs = mpress_fetch_array($resultado)){
				if ($rs["Tipo_ID"]==44){
					$dadosPizzaS .= $virgulaS."['".$rs[Cadastro]." - R$ ".number_format($rs[Valor_Pago], 2, ',', '.')."',".$rs[Valor_Pago]."]";
					$virgulaS = ",";
					$htmlS .= "<tr class='tabela-fundo-escuro'><td width='75%'>".$rs[Cadastro]."</td><td>R$ ".number_format($rs[Valor_Pago], 2, ',', '.')."</td></tr>";
				}
				if ($rs["Tipo_ID"]==45){
					$dadosPizzaE .= $virgulaE."['".$rs[Cadastro]." - R$ ".number_format($rs[Valor_Pago], 2, ',', '.')."',".$rs[Valor_Pago]."]";
					$virgulaE = ",";
					$htmlE .= "<tr class='tabela-fundo-escuro'><td width='75%'>".$rs[Cadastro]."</td><td>R$ ".number_format($rs[Valor_Pago], 2, ',', '.')."</td></tr>";

				}
			}
			$htmlS .= "</table>"; $htmlE .= "</table>";
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChartS);

				function drawChartS() {
					var dataA = google.visualization.arrayToDataTable([['Cadastro', 'R$ Valor'], <?php echo $dadosPizzaS; ?>]);
					var optionsA = {title: 'PARA QUEM PAGO',is3D: true,};
					var graficoA = new google.visualization.PieChart(document.getElementById('div-pizza-s'));
					graficoA.draw(dataA, optionsA);
				}
				google.setOnLoadCallback(drawChartE);
				function drawChartE() {
					var dataB = google.visualization.arrayToDataTable([['Cadastro', 'R$ Valor'], <?php echo $dadosPizzaE; ?>]);
					var optionsB = {title: 'DE QUEM RECEBO',is3D: true,};
					var graficoB = new google.visualization.PieChart(document.getElementById('div-pizza-e'));
					graficoB.draw(dataB, optionsB);
				}
			</script>
			<?php echo "<h3><b>PERÍODO SELECIONADO: ".$_POST['data-inicio-vencimento']." ATÉ ".$_POST['data-fim-vencimento']."</b></h3>";?>
			<div id='div-pizza-e' style='width: 100%; min-height: 800px; float:left; min-width:800px;' align='center'></div>
			<?php echo $htmlE;?>
			<div id='div-pizza-s' style='width: 100%; min-height: 800px; float:left; min-width:800px;' align='center'></div>
			<?php echo $htmlS;?>
<?php
		}

		/****************************************************/
		/****    DA ONDE VEM O DINHERO E PARA ONDE VAI   ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-5'){
			$sql = "SELECT fc.Tipo_ID AS Tipo_ID, coalesce(tc.Descr_Tipo,' Não Definido') AS TipoConta, SUM(COALESCE(fco.Valor,0)) AS Valor
						FROM financeiro_contas fc
						INNER JOIN financeiro_titulos ft ON fc.Conta_ID = ft.Conta_ID
						INNER JOIN financeiro_contabil fco ON fco.Conta_ID = fc.Conta_ID AND fco.Situacao_ID = 1
						LEFT JOIN tipo tc ON tc.Tipo_ID = fco.Tipo_Conta_ID
						WHERE fc.Tipo_ID IN (44,45) AND ft.Situacao_Pagamento_ID = 49
						$sqlCond
						GROUP BY fco.Tipo_Conta_ID, fc.Tipo_ID
						ORDER BY Valor DESC";
			$resultado = mpress_query($sql);
			$i = 0;
			$virgulaE = ""; $virgulaS = ""; $dadosPizzaS = ""; $dadosPizzaE = "";
			$htmlS = $htmlE = "<table width='100%' align='center' style='float:left; margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2'>";
			while($rs = mpress_fetch_array($resultado)){
				if ($rs["Tipo_ID"]==44){
					$dadosPizzaS .= $virgulaS."['".$rs[TipoConta]." - R$ ".number_format($rs[Valor], 2, ',', '.')."',".$rs[Valor]."]";
					$virgulaS = ",";
					$htmlS .= "<tr class='tabela-fundo-escuro'><td width='75%'>".$rs[TipoConta]."</td><td>R$ ".number_format($rs[Valor], 2, ',', '.')."</td></tr>";
				}
				if ($rs["Tipo_ID"]==45){
					$dadosPizzaE .= $virgulaE."['".$rs[TipoConta]." - R$ ".number_format($rs[Valor], 2, ',', '.')."',".$rs[Valor]."]";
					$virgulaE = ",";
					$htmlE .= "<tr class='tabela-fundo-escuro'><td width='75%'>".$rs[TipoConta]."</td><td>R$ ".number_format($rs[Valor], 2, ',', '.')."</td></tr>";
				}
			}
			$htmlS .= "</table>"; $htmlE .= "</table>";
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChartE);
				function drawChartE() {
					var dataB = google.visualization.arrayToDataTable([['Cadastro', 'R$ Valor'], <?php echo $dadosPizzaE; ?>]);
					var optionsB = {title: 'DA ONDE VEM O DINHEIRO',is3D: true, legend: { position: 'right', maxLines: 10 }};
					var graficoB = new google.visualization.PieChart(document.getElementById('div-pizza-e'));
					graficoB.draw(dataB, optionsB);
				}
				google.setOnLoadCallback(drawChartS);
				function drawChartS() {
					var dataA = google.visualization.arrayToDataTable([['Cadastro', 'R$ Valor'], <?php echo $dadosPizzaS; ?>]);
					var optionsA = {title: 'PARA ONDE VAI O DINHEIRO',is3D: true, legend: { position: 'right', maxLines: 10 }};
					var graficoA = new google.visualization.PieChart(document.getElementById('div-pizza-s'));
					graficoA.draw(dataA, optionsA);
				}
			</script>
			<?php echo "<h3><b>PERÍODO SELECIONADO: ".$_POST['data-inicio-vencimento']." ATÉ ".$_POST['data-fim-vencimento']."</b></h3>";?>
			<div id='div-pizza-e' style='width: 100%; min-height: 800px; float:left; min-width:800px;' align='center'></div>
			<?php echo $htmlE;?>
			<div id='div-pizza-s' style='width: 100%; min-height: 800px; float:left; min-width:800px;' align='center'></div>
			<?php echo $htmlS;?>
<?php
		}


		/****************************************************/
		/****      RELATÓRIO POR CENTRO DE CUSTO         ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-8'){

			$sql = "select coalesce(cc1.Descr_Tipo, ' Não definido') as Centro_Custo_N1, coalesce(cc2.Descr_Tipo,' Não definido') as Centro_Custo_N2,
						concat(coalesce(cc1.Descr_Tipo, ' Não definido'), coalesce(cc2.Descr_Tipo, ' Não definido')) as CentroCusto,
						coalesce(tc1.Descr_Tipo, ' Não definido') as Tipo_Conta_N1, coalesce(tc2.Descr_Tipo, ' Não definido') as Tipo_Conta_N2, fco.Valor as Valor, cd.Nome as Cadastro, fc.Tipo_ID,
						DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, fc.Conta_ID, concat(fc.Observacao, ' ' ,fco.Observacao) as Historico
						from financeiro_contabil fco
						inner join financeiro_contas fc on fc.Conta_ID = fco.Conta_ID
						inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
						left join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_para
						left join tipo cc2 on cc2.Tipo_ID = fco.Centro_Custo_ID
						left join tipo cc1 on cc1.Tipo_ID = cc2.Tipo_Auxiliar
						left join tipo tc2 on tc2.Tipo_ID = fco.Tipo_Conta_ID
						left join tipo tc1 on tc1.Tipo_ID = tc2.Tipo_Auxiliar
						where fco.Situacao_ID = 1 and fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID = 49
						$sqlCond
						order by Centro_Custo_N1, Centro_Custo_N2, ft.Data_Vencimento, fc.Cadastro_ID_para";
			//echo $sql;
			$i=1;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				if ($rs['CentroCusto']!=$centroCustoAnt){
					if ($centroCustoAnt!=""){
					$i++;
						$sinal = "";
						if ($saldoParcial>=0){
							$sinal = "(+)";
							$estiloFonte = "color:#0047c9;";
						}
						if ($saldoParcial<0){
							$sinal = "(-)";
							$saldoParcial = $saldoParcial * (-1);
							$estiloFonte = "color:#FF4D4D;";
						}
						$dados[colunas][tr][$i] = " style='".$estiloFonte."'";
						$dados[colunas][colspan][$i][1] = "4";
						$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
						$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center; white-space: nowrap;'>".$sinal." ".number_format($saldoParcial,2,',','.')."</p>";
						$saldoParcial = 0;
					}
					$i++;
					$dados[colunas][classe][$i] = "destaque-tabela";
					$dados[colunas][colspan][$i][1] = "10";
					$dados[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px; text-align:center; height;30px;'><br>".$rs[Centro_Custo_N1].": &nbsp;&nbsp; ".$rs[Centro_Custo_N2]."<br><br></p>";
					$i++;
					$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";
					$dados[colunas][conteudo][$i][1] 	= "&nbsp;";
					$dados[colunas][conteudo][$i][2] 	= "<center>Data</center>";
					$dados[colunas][conteudo][$i][3] 	= "Tipo Conta";
					$dados[colunas][conteudo][$i][4] 	= "Cliente / Fornecedor";
					$dados[colunas][conteudo][$i][5] 	= "Hist&oacute;rico";
					$dados[colunas][conteudo][$i][6]	= "<center>Valor</center>";
				}
				$i++;
				if ($rs[Tipo_ID]=="44"){
					$estiloFonte = "color:#FF4D4D;";
					$icone = "<div style='float:left;' class='icone-saida localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = -1;
					$totalSaidas += $rs[Valor];
					$sinal = "";
				}
				if ($rs[Tipo_ID]=="45"){
					$estiloFonte = "color:#0047c9;";
					$icone = "<div style='float:left;' class='icone-entrada localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = 1;
					$totalEntradas += $rs[Valor];
					$sinal = "(+)";
				}
				$c = 1;
				$dados[colunas][tr][$i] = " style='".$estiloFonte."'";
				$dados[colunas][conteudo][$i][$c++] = $icone;
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".$rs[Data_Vencimento]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Tipo_Conta_N1]." :&nbsp;&nbsp; ".$rs[Tipo_Conta_N2]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Cadastro]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Historico]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'> $sinal ".number_format($rs[Valor], 2, ',', '.')."</p>";
				$centroCustoAnt = $rs['CentroCusto'];
				$saldoParcial += $rs[Valor] * $fator;
			}
			$i++;
			$sinal = "";
			if ($saldoParcial>=0){
				$sinal = "(+)";
				$estiloFonte = "color:#0047c9;";
			}
			if ($saldoParcial<0){
				$sinal = "(-)";
				$estiloFonte = "color:#FF4D4D;";
				$saldoParcial = $saldoParcial * (-1);
			}
			$dados[colunas][tr][$i] = " style='".$estiloFonte."'";
			$dados[colunas][colspan][$i][1] = "4";
			$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center; white-space: nowrap;'>".$sinal." ".number_format($saldoParcial,2,',','.')."</p>";
			$saldoParcial = 0;


			$dados[colunas][titulo][1] 	= "&nbsp;<span class='esconde' style='display:none;'>1</p>";
			$dados[colunas][titulo][2] 	= "&nbsp;<span class='esconde' style='display:none;'>2</p>";
			$dados[colunas][titulo][3] 	= "&nbsp;<span class='esconde' style='display:none;'>3</p>";
			$dados[colunas][titulo][4] 	= "&nbsp;<span class='esconde' style='display:none;'>4</p>";
			$dados[colunas][titulo][5] 	= "&nbsp;<span class='esconde' style='display:none;'>5</p>";
			$dados[colunas][titulo][6] 	= "&nbsp;<span class='esconde' style='display:none;'>6</p>";

			$dados[colunas][colspan][1][1] = "6";
			$dados[colunas][conteudo][1][1] = "	<div style='float:left; width:80%'>
													<p style='text-align:center; margin-left:20%;'>
														<b>
															RELAT&Oacute;RIO POR CENTRO DE CUSTO<br>
															PER&Iacute;ODO SELECIONADO: ".$_POST['data-inicio-vencimento']." ATÉ ".$_POST['data-fim-vencimento']."
														</b>
													</p>
												</div>
												<div style='float:right;'>
													<p style='margin:1px 5px 0 1px; text-align:right;'><b>Total Entradas: ".number_format($totalEntradas,2,',','.')."</b></p>
													<p style='margin:1px 5px 0 1px; text-align:right;'><b>Total Saídas: ".number_format($totalSaidas,2,',','.')."</b></p>
													<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo: ".number_format(($totalEntradas - $totalSaidas),2,',','.')."</b></p>
												</div>";
			geraTabela("100%", 6, $dados, null, 'financeiro-localiza-cadastros', 2, 2, '',1);
			echo "<p>&nbsp;</p>";
		}



		/****************************************************/
		/****      RELATÓRIO POR CLIENTE FORNECEDOR      ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-2'){

			$sql = "select coalesce(cc1.Descr_Tipo, ' Não definido') as Centro_Custo_N1, coalesce(cc2.Descr_Tipo,' Não definido') as Centro_Custo_N2, concat(cc1.Descr_Tipo, cc2.Descr_Tipo ) as CentroCusto,
						coalesce(tc1.Descr_Tipo, ' Não definido') as Tipo_Conta_N1, coalesce(tc2.Descr_Tipo, ' Não definido') as Tipo_Conta_N2, fco.Valor as Valor, cd.Nome as Cadastro, fc.Tipo_ID, fc.Cadastro_ID_para as Cadastro_ID_para,
						DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, fc.Conta_ID, concat(fc.Observacao, ' ' ,fco.Observacao) as Historico
						from financeiro_contabil fco
						inner join financeiro_contas fc on fc.Conta_ID = fco.Conta_ID
						inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
						left join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_para
						left join tipo cc2 on cc2.Tipo_ID = fco.Centro_Custo_ID
						left join tipo cc1 on cc1.Tipo_ID = cc2.Tipo_Auxiliar
						left join tipo tc2 on tc2.Tipo_ID = fco.Tipo_Conta_ID
						left join tipo tc1 on tc1.Tipo_ID = tc2.Tipo_Auxiliar
						where fco.Situacao_ID = 1 and fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID = 49
						$sqlCond
						order by cd.Nome, fc.Cadastro_ID_para, ft.Data_Vencimento";
			$query = mpress_query($sql);
			$i = 0;
			while($rs = mpress_fetch_array($query)){
				if ($rs['Cadastro_ID_para']!=$cadastroIDParaAnt){
					if ($cadastroIDParaAnt!=""){
						$i++;
						$dados[colunas][colspan][$i][1] = "4";
						$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
						$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($saldoParcial,2,',','.')."</p>";
						$saldoParcial = 0;
					}
					$i++;
					$dados[colunas][classe][$i] = "destaque-tabela";
					$dados[colunas][colspan][$i][1] = "10";
					$dados[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px; text-align:center; height;30px;'><br>".$rs[Cadastro]."<br><br></p>";
					$i++;
					$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";
					$dados[colunas][conteudo][$i][1] 	= "&nbsp;";
					$dados[colunas][conteudo][$i][2] 	= "<center>Data</center>";
					$dados[colunas][conteudo][$i][3] 	= "Tipo Conta";
					$dados[colunas][conteudo][$i][4] 	= "Centro de Custo";
					$dados[colunas][conteudo][$i][5] 	= "Hist&oacute;rico";
					$dados[colunas][conteudo][$i][6]	= "<center>Valor</center>";
				}
				$i++;
				if ($rs[Tipo_ID]=="44"){
					$estiloFonte = "color:#FF4D4D;";
					$icone = "<div style='float:left;' class='icone-saida localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = -1;
				}
				if ($rs[Tipo_ID]=="45"){
					$estiloFonte = "color:#0047c9;";
					$icone = "<div style='float:left;' class='icone-entrada localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = 1;
				}
				$c = 1;
				$dados[colunas][tr][$i] = " style='".$estiloFonte."'";
				$dados[colunas][conteudo][$i][$c++] = $icone;
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".$rs[Data_Vencimento]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Tipo_Conta_N1]." :&nbsp;&nbsp; ".$rs[Tipo_Conta_N2]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Centro_Custo_N1].": &nbsp;&nbsp; ".$rs[Centro_Custo_N2]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Historico]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($rs[Valor], 2, ',', '.')."</p>";
				$cadastroIDParaAnt = $rs['Cadastro_ID_para'];
				$saldoParcial += $rs[Valor] * $fator;
			}
			$i++;
			$dados[colunas][colspan][$i][1] = "4";
			$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($saldoParcial,2,',','.')."</p>";
			$saldoParcial = 0;

			/*
			$i++;
			$dados[colunas][classe][$i] = "destaque-tabela";
			$dados[colunas][colspan][$i][1] = "10";
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px; text-align:center; height;30px;'><br>".$rs[Cadastro]."<br><br></p>";
			*/

			$dados[colunas][titulo][1] 	= "&nbsp;<span class='esconde' style='display:none;'>1</p>";
			$dados[colunas][titulo][2] 	= "&nbsp;<span class='esconde' style='display:none;'>2</p>";
			$dados[colunas][titulo][3] 	= "&nbsp;<span class='esconde' style='display:none;' >3</p>";
			$dados[colunas][titulo][4] 	= "&nbsp;<span class='esconde' style='display:none;'>4</p>";
			$dados[colunas][titulo][5] 	= "&nbsp;<span class='esconde' style='display:none;'>5</p>";
			$dados[colunas][titulo][6] 	= "&nbsp;<span class='esconde' style='display:none;'>6</p>";

			geraTabela("100%", 6, $dados, null, 'financeiro-localiza-cadastros', 2, 2, '',1);
		}


		/****************************************************/
		/****      RELATÓRIO POR TIPO DE CONTA           ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-4'){

			$sql = "select coalesce(cc1.Descr_Tipo, ' Não definido') as Centro_Custo_N1, coalesce(cc2.Descr_Tipo,' Não definido') as Centro_Custo_N2,
									concat(coalesce(tc1.Descr_Tipo, ' Não definido'),coalesce(tc2.Descr_Tipo, ' Não definido')) as TipoConta,
									coalesce(tc1.Descr_Tipo, ' Não definido') as Tipo_Conta_N1, coalesce(tc2.Descr_Tipo, ' Não definido') as Tipo_Conta_N2, fco.Valor as Valor, cd.Nome as Cadastro, fc.Tipo_ID,
									DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento, fc.Conta_ID, concat(fc.Observacao, ' ' ,fco.Observacao) as Historico
									from financeiro_contabil fco
									inner join financeiro_contas fc on fc.Conta_ID = fco.Conta_ID
									inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
									left join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_para
									left join tipo cc2 on cc2.Tipo_ID = fco.Centro_Custo_ID
									left join tipo cc1 on cc1.Tipo_ID = cc2.Tipo_Auxiliar
									left join tipo tc2 on tc2.Tipo_ID = fco.Tipo_Conta_ID
									left join tipo tc1 on tc1.Tipo_ID = tc2.Tipo_Auxiliar
									where fco.Situacao_ID = 1 and fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID = 49
									$sqlCond
									order by Tipo_Conta_N1, Tipo_Conta_N2, ft.Data_Vencimento, fc.Cadastro_ID_para";
			//echo $sql;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				if ($rs['TipoConta']!=$tipoContaAnt){
					if ($tipoContaAnt!=""){
						$i++;
						$dados[colunas][colspan][$i][1] = "4";
						$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
						$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($saldoParcial,2,',','.')."</p>";
						$saldoParcial = 0;
					}
					$i++;
					$dados[colunas][classe][$i] = "destaque-tabela";
					$dados[colunas][colspan][$i][1] = "10";
					$dados[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px; text-align:center; height;30px;'><br>".$rs[Tipo_Conta_N1].": &nbsp;&nbsp; ".$rs[Tipo_Conta_N2]."<br><br></p>";
					$i++;
					$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";
					$dados[colunas][conteudo][$i][1] 	= "&nbsp;";
					$dados[colunas][conteudo][$i][2] 	= "<center>Data</center>";
					$dados[colunas][conteudo][$i][3] 	= "Centro de Custo";
					$dados[colunas][conteudo][$i][4] 	= "Cliente / Fornecedor";
					$dados[colunas][conteudo][$i][5] 	= "Hist&oacute;rico";
					$dados[colunas][conteudo][$i][6]	= "<center>Valor</center>";
				}
				$i++;
				if ($rs[Tipo_ID]=="44"){
					$estiloFonte = "color:#FF4D4D;";
					$icone = "<div style='float:left;' class='icone-saida localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = -1;
				}
				if ($rs[Tipo_ID]=="45"){
					$estiloFonte = "color:#0047c9;";
					$icone = "<div style='float:left;' class='icone-entrada localiza-conta link' conta-id='".$rs[Conta_ID]."' title='".$rs[Tipo]."'>&nbsp;</div>";
					$fator = 1;
				}
				$c = 1;
				$dados[colunas][tr][$i] = " style='".$estiloFonte."'";
				$dados[colunas][conteudo][$i][$c++] = $icone;
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".$rs[Data_Vencimento]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Centro_Custo_N1]." :&nbsp;&nbsp; ".$rs[Centro_Custo_N2]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Cadastro]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:left;'>".$rs[Historico]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($rs[Valor], 2, ',', '.')."</p>";
				$tipoContaAnt = $rs['TipoConta'];
				$saldoParcial += $rs[Valor] * $fator;
			}
			$i++;
			$dados[colunas][colspan][$i][1] = "4";
			$dados[colunas][conteudo][$i][5] = "<p style='margin:1px 5px 0 1px; text-align:right;'><b>Saldo:</b></p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px; text-align:center;'>".number_format($saldoParcial,2,',','.')."</p>";
			$saldoParcial = 0;

			$dados[colunas][titulo][1] 	= "&nbsp;<span class='esconde' style='display:none;'>1</p>";
			$dados[colunas][titulo][2] 	= "&nbsp;<span class='esconde' style='display:none;'>2</p>";
			$dados[colunas][titulo][3] 	= "&nbsp;<span class='esconde' style='display:none;'>3</p>";
			$dados[colunas][titulo][4] 	= "&nbsp;<span class='esconde' style='display:none;'>4</p>";
			$dados[colunas][titulo][5] 	= "&nbsp;<span class='esconde' style='display:none;'>5</p>";
			$dados[colunas][titulo][6] 	= "&nbsp;<span class='esconde' style='display:none;'>6</p>";

			geraTabela("100%", 6, $dados, null, 'financeiro-localiza-cadastros', 2, 2, '',1);

		}

		/****************************************************/
		/****      RECEITAS X DESPESAS     ****/
		/****************************************************/
		if ($filtroRelatorio=='menu-superior-9'){
				$sql = "select tc.Tipo_ID, tc.Descr_Tipo as Tipo,
							DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d') as mesano,
							month(ft.Data_Vencimento) as mes,
							year(ft.Data_Vencimento) as ano,
							day(ft.Data_Vencimento) as dia,
							sum(ft.Valor_Titulo) as Valor
							from cadastros_dados cdd
							inner join financeiro_contas fc on cdd.Cadastro_ID = fc.Cadastro_ID_de
							inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
							inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
							where cdd.Centro_Custo_ID is not null and fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID IN (48,49)
							$sqlCond
							group by tc.Tipo_ID, DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d')
							 order by ft.Data_Vencimento";
				$query = mpress_query($sql);
				while($rs = mpress_fetch_array($query)){
					$arrayGrafico[$rs[mesano]][Data] = "new Date(".$rs[ano].",".$rs[mes].",".$rs[dia].")";
					if ($rs[Tipo_ID]=="44")
						$arrayGrafico[$rs[mesano]][Saida] = $rs[Valor];
					if ($rs[Tipo_ID]=="45")
						$arrayGrafico[$rs[mesano]][Entrada] = $rs[Valor];
				}
				$virgula = "";
				foreach($arrayGrafico as $grafico){
					if ($grafico[Saida]=="")
						$grafico[Saida] = 0;
					if ($grafico[Entrada]=="")
						$grafico[Entrada] = 0;
					$stringArray .= $virgula."[".$grafico[Data].",".$grafico[Entrada].",".$grafico[Saida]."]";
					$virgula = ",";
				}
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart2);
				function drawChart2() {
					var dataL = new google.visualization.DataTable();
					dataL.addColumn('date', 'data');
					dataL.addColumn('number', 'ENTRADAS');
					dataL.addColumn('number', 'SAÍDAS');
					dataL.addRows([<?php echo $stringArray;?>]);
					var optionsL = {title: 'RECEITAS X DESPESAS', pointSize:5, curveType: 'function', legend: { position: 'bottom' }};
					var graficoL = new google.visualization.LineChart(document.getElementById('div-rel-linhas'));
					graficoL.draw(dataL, optionsL);
				}
			</script>
<?php
			$htmlGraficos = "	<div id='div-rel-linhas' style='width: 100%; height: 600px; float:left; min-width:400px;'></div>";
			echo $htmlGraficos;
		}
?>
