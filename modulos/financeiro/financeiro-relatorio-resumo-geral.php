<?php
	include('functions.php');
	global $configFinanceiro;

	function converteDataJavascript($data){
		$data = implode(',',array_reverse(explode('/',$data)));
		return "new Date($data)";
	}

	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
	}

	$virgula = "";
	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-regional']); $i++){
			$regionais .= $virgula.$_POST['localiza-regional'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
			$cadastrosDe .= $virgula.$_POST['localiza-cadastro-de'][$i];
			$virgula = ",";
		}
	}
	else{
		$dataInicioVencimento = "01/".date("m/").(date("Y")-1);
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");
		$classeEsconde = "esconde";
	}

?>
	    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Resumo'>
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
						<p><b>Data Vencimento:</b></p>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
						</div>
						<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:15%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<p><b>Exibir informações:</b></p>
							<p>
								<select id='exibir-informacoes' name='exibir-informacoes' style='width:92%'>
									<option value='ger' <?php echo verificaSelected($_POST['exibir-informacoes'],'ger');?>>Geral</option>
									<option value='cad' <?php echo verificaSelected($_POST['exibir-informacoes'],'cad');?>><?php echo $configFinanceiro['cadastro'];?></option>
									<option value='reg' <?php echo verificaSelected($_POST['exibir-informacoes'],'reg');?>>Regionais</option>
								</select>
							</p>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:45%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<div class='esconde div-filtros div-rel-reg'>
								<p>Regionais:</p>
								<p><select name='localiza-regional[]' id='localiza-regional' multiple Style='height:90px'><?php echo optionValueGrupoMultiplo(26, $regionais,'');?></select></p>
							</div>
							<div class='esconde div-filtros div-rel-cad'>
								<p><?php echo $configFinanceiro['cadastro'];?>:</p>
								<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple Style='height:90px'><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
							</div>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:20%;margin-top:15px'>
						<div style='width:50%;float:left;'>
							<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel' style="float:right;" title="Gerar Excel"></div>&nbsp;
						</div>
						<div style='width:50%;float:left;'>
							<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	if($_POST){
		/*CONDIÇÕES*/
		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}
		if ($cadastrosDe != ""){ $sqlCond .= " and cdd.Cadastro_ID IN ($cadastrosDe)";}
		if ($regionais != ""){ $sqlCond .= " and cdd.Centro_Custo_ID IN ($regionais)";}



		/*RESUMO SITUACAO PAGAMENTO*/
		$sql = "select Tipo_ID, upper(Descr_Tipo) as Descr_Tipo from tipo where Tipo_Grupo_ID = 29";
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			$dadosAux[$rs[Tipo_ID]][Situacao] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Descr_Tipo]."</p>";
			$dadosAux[$rs[Tipo_ID]][Entrada] = 0;
			$dadosAux[$rs[Tipo_ID]][Saida] = 0;
			$dadosAux[$rs[Tipo_ID]][Transferencia] = 0;
		}
		$largura = "100%";
		$colunas = "4";
		$dados[colunas][titulo][1] 	= "<b>SITUA&Ccedil;&Atilde;O PAGAMENTO</b>";
		$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;float:right;'>ENTRADAS</p>";
		$dados[colunas][titulo][3] 	= "<p Style='margin:2px 5px 0 5px;float:right;'>SA&Iacute;DAS</p>";
		$dados[colunas][titulo][4] 	= "<p Style='margin:2px 5px 0 5px;float:right;'>TRANSFER&Ecirc;NCIAS</p>";
		$dados[colunas][tamanho][1] = "";
		$dados[colunas][tamanho][2] = "width='20%'";
		$dados[colunas][tamanho][3] = "width='20%'";
		$dados[colunas][tamanho][4] = "width='20%'";

		$sql = "select tc.Tipo_ID, tc.Descr_Tipo as Tipo, ft.Situacao_Pagamento_ID, ts.Descr_Tipo as Situacao_Pagamento, sum(coalesce(ft.Valor_Titulo,0)) as Valor
				from cadastros_dados cdd
				inner join financeiro_contas fc on cdd.Cadastro_ID = fc.Cadastro_ID_de
				inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
				inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
				inner join tipo ts on ts.Tipo_ID = ft.Situacao_Pagamento_ID and ts.Tipo_Grupo_ID = 29
				where cdd.Centro_Custo_ID is not null
				$sqlCond
				group by tc.Tipo_ID, ft.Situacao_Pagamento_ID
				order by tc.Tipo_ID";
		//echo $sql;

		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			//echo "<br>".$rs[Situacao_Pagamento_ID];
			if ($rs[Tipo_ID]=="44")
				$dadosAux[$rs[Situacao_Pagamento_ID]][Saida] = $dadosAux[$rs[Situacao_Pagamento_ID]][Saida] + $rs[Valor];
			if ($rs[Tipo_ID]=="45")
				$dadosAux[$rs[Situacao_Pagamento_ID]][Entrada] = $dadosAux[$rs[Situacao_Pagamento_ID]][Entrada] + $rs[Valor];
			if ($rs[Tipo_ID]=="46")
				$dadosAux[$rs[Situacao_Pagamento_ID]][Transferencia] = $dadosAux[$rs[Situacao_Pagamento_ID]][Transferencia] + $rs[Valor];
		}
		$j = 0;
		foreach($dadosAux as $dado){
			$j++;
			$dados[colunas][conteudo][$j][1] = "<b>".$dado[Situacao]."</b>";
			$dados[colunas][conteudo][$j][2] = "<p Style='margin:2px 5px 0 5px;float:right;'>".number_format($dado[Entrada], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$j][3] = "<p Style='margin:2px 5px 0 5px;float:right;'>".number_format($dado[Saida], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$j][4] = "<p Style='margin:2px 5px 0 5px;float:right;'>".number_format($dado[Transferencia], 2, ',', '.')."</p>";
		}
		if($i==0)
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum registro encontrado</p>";
		else{
			geraTabela($largura,3,$dados);
			$htmlRelResumo = returnTabelaExcel($largura,3,$dados);
		}

		echo "<br>&nbsp;";
		/*  RELATORIO RESUMO ENTRADAS E SAIDAS (Situação Pago) */

		$sql = "select fc.Tipo_ID as Tipo_ID, coalesce(tc.Descr_Tipo, 'Não Informado') as Tipo_Conta, fc.Tipo_Conta_ID as Tipo_Conta_ID, sum(coalesce(ft.Valor_Pago,0)) as Valor_Pago, sum(coalesce(ft.Valor_Titulo,0)) as Valor_Titulo
							from financeiro_contas fc
							inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
							inner join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_de
							left join tipo tc on tc.Tipo_ID = fc.Tipo_Conta_ID
							where fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID = 49
							$sqlCond
							group by fc.Tipo_Conta_ID, tc.Descr_Tipo, fc.Tipo_ID order by fc.Tipo_ID desc";
		//echo $sql;
		$resultado = mpress_query($sql);
		$i = 0;
		$htmlES = "<table width='100%' style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2'>";
		$dadosPizzaS = ""; $dadosPizzaE = "";
		while($rs = mpress_fetch_array($resultado)){
			if ($rs["Tipo_ID"]==44){ $dadosPizzaS .= $virgulaS."['".$rs[Tipo_Conta]."',".$rs[Valor_Pago]."]"; $virgulaS = ","; $totalSaidas += $rs[Valor_Pago];}
			if ($rs["Tipo_ID"]==45){ $dadosPizzaE .= $virgulaE."['".$rs[Tipo_Conta]."',".$rs[Valor_Pago]."]"; $virgulaE = ","; $totalEntradas += $rs[Valor_Pago];}
			if ($rs["Tipo_ID"]!=$tipoIDAnt){
				if($i%2!=0){$htmlES .= "<td class='tabela-fundo-claro' styledestaque>&nbsp;</td><td class='tabela-fundo-claro' stylenormal>&nbsp;</td>";}
				if ($rs["Tipo_ID"]==44){$descricaoTipo = "SA&Iacute;DAS (Situa&ccedil;&atilde;o Pago)";}
				if ($rs["Tipo_ID"]==45){$descricaoTipo = "ENTRADAS (Situa&ccedil;&atilde;o Pago)";}
				$htmlES .= "<tr><td colspan='4' align='center' styletitulo class='tabela-fundo-escuro-titulo'>".$descricaoTipo."</td></tr>";
				$i = 0;
			}
			$total = $total + $rs[Valor_Pago];
			if($i%2==0){$htmlES .= "<tr>";}
			$htmlES .= "<td class='tabela-fundo-claro' styledestaque align='left'>".($rs[Tipo_Conta])."</td><td class='tabela-fundo-claro' stylenormal align='right' style='padding-right:20px'>".number_format($rs[Valor_Pago], 2, ',', '.')."</td>";
			if(($i+1)%2==0){$htmlES .= "</tr>";}
			$i++;
			$tipoIDAnt = $rs["Tipo_ID"];
		}
		$htmlES .= "</table>";

		$htmlES .= "<br>
				<table width='400px' style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
					<tr>
						<td width='33.33%' class='tabela-fundo-escuro-titulo' align='center'><b>TOTAL ENTRADAS</b></td>
						<td width='33.33%' class='tabela-fundo-escuro-titulo' align='center'><b>TOTAL SA&Iacute;DAS</b></td>
						<td width='33.34%' class='tabela-fundo-escuro-titulo' align='center'><b>SALDO</b></td>
					</tr>
					<tr>
						<td class='tabela-fundo-claro' align='right'>".number_format($totalEntradas, 2, ',', '.')."</td>
						<td class='tabela-fundo-claro' align='right'>".number_format($totalSaidas, 2, ',', '.')."</td>
						<td class='tabela-fundo-claro' align='right'>".number_format(($totalEntradas - $totalSaidas), 2, ',', '.')."</td>
					</tr>
				</table>
				<br>";
		echo $htmlES;



		//echo $dadosPizzaE;
		//echo $dadosPizzaS;
?>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChartS);
			function drawChartS() {
				var dataA = google.visualization.arrayToDataTable([['Tipo', 'R$ Valor'], <?php echo $dadosPizzaS; ?>]);
				var optionsA = {title: 'SAÍDAS',is3D: true,};
				var graficoA = new google.visualization.PieChart(document.getElementById('div-pizza-s'));
				graficoA.draw(dataA, optionsA);
		  	}
			google.setOnLoadCallback(drawChartE);
			function drawChartE() {
				var dataB = google.visualization.arrayToDataTable([['Tipo', 'R$ Valor'], <?php echo $dadosPizzaE; ?>]);
				var optionsB = {title: 'ENTRADAS',is3D: true,};
				var graficoB = new google.visualization.PieChart(document.getElementById('div-pizza-e'));
				graficoB.draw(dataB, optionsB);
		  	}

		</script>

<?php
		/*RELATÓRIO GRAFICO ENTRADAS X SAIDAS */

		//array[MesAno][Entrada];
		//array[MesAno][Saida];
		//array[MesAno][Transferencia];
		$sql = "select tc.Tipo_ID, tc.Descr_Tipo as Tipo,
					DATE_FORMAT(ft.Data_Vencimento,'%Y-%m') as mesano,
					month(ft.Data_Vencimento) as mes,
					year(ft.Data_Vencimento) as ano,
					sum(ft.Valor_Titulo) as Valor
					from cadastros_dados cdd
					inner join financeiro_contas fc on cdd.Cadastro_ID = fc.Cadastro_ID_de
					inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
					inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
					where cdd.Centro_Custo_ID is not null and fc.Tipo_ID in (44,45) and ft.Situacao_Pagamento_ID IN (48,49)
					$sqlCond
					group by tc.Tipo_ID, DATE_FORMAT(ft.Data_Vencimento,'%Y-%m')
					 order by ft.Data_Vencimento";
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$arrayGrafico[$rs[mesano]][Data] = "new Date(".$rs[ano].",".$rs[mes].",1)";
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
      google.setOnLoadCallback(drawChart2);
      function drawChart2() {
			var dataL = new google.visualization.DataTable();
			dataL.addColumn('date', 'data');
			dataL.addColumn('number', 'ENTRADAS');
			dataL.addColumn('number', 'SAÍDAS');
		  	dataL.addRows([<?php echo $stringArray;?>]);
			var optionsL = {title: 'ENTRADAS X SAÍDAS - (Situação ABERTO e PAGO)', pointSize:5, curveType: 'function', legend: { position: 'bottom' }};
			var graficoL = new google.visualization.LineChart(document.getElementById('div-rel-linhas'));
			graficoL.draw(dataL, optionsL);
      }
    </script>
<?php
		$htmlGraficos = "	<div id='div-pizza-e' style='width: 50%; height: 500px; float:left; min-width:400px;' align='center'></div>
							<div id='div-pizza-s' style='width: 50%; height: 500px; float:left; min-width:400px;' align='center'></div>
							<div id='div-rel-linhas' style='width: 100%; height: 600px; float:left; min-width:400px;'></div>";
		echo $htmlGraficos;
	}
	$cabecalho = "<table width='718' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
					<tr class='esconde'>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='3' valign='top' width='518' height='53'>RELAT&Oacute;RIO RESUMO</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo valign='middle' align='right' width='210' height='53'><img src='$caminhoSistema/images/topo/logo.png';?></td>
					</tr>
				  </table>";
	$html = $cabecalho."<br><br><br>".$htmlRelResumo."<br><br>".$htmlES."<br><br>";
	$html = str_replace("styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'", $html);
	$html = str_replace("100%","718", $html);
	$_SESSION["session-conteudo-relatorio"] = $html;

?>
