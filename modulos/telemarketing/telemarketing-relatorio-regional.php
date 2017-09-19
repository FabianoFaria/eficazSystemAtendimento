<?php
	global $caminhoSistema;
	global $dadosUserLogin;
	if ($_POST){
		$dataInicio = $_POST['data-inicio-abertura'];
		$dataFim = $_POST['data-fim-abertura'];
	}
	else{
		$dataInicio = "01/".date("m/Y");
		$dataFim = date("d/m/Y");
	}
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Filtros de Pesquisa
		</p>
	</div>
	<div class="conteudo-interno">
		<div class="titulo-secundario cinco-colunas">
			<p>Data Abertura:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicio; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFim; ?>'></p>
			</div>
		</div>
		<div class='titulo-secundario cinco-colunas' Style='margin-top:15px; float:left;'>
			<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-pesquisar-relatorio-situacao'></p>
		</div>
	</div>
</div>
<?php
	$dadosChamados = "";
	if(($dataInicio!="")||($dataFim!="")){
		$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
		if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
		$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
		if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
		$condicoes .= " and tw.Data_Cadastro between '$dataInicio' and '$dataFim' ";
	}

	$sql = "select count(tw.Workflow_ID) as Cont, coalesce(ce.UF,'N/A') as UF, upper(ce.Cidade) as Cidade
		from telemarketing_workflows tw
			inner join cadastros_enderecos ce on tw.Solicitante_ID = ce.Cadastro_ID and ce.Tipo_Endereco_ID = 38
			where tw.Workflow_ID is not null
			$condicoes
			group by ce.UF, ce.Cidade
			order by ce.UF";
	//echo $sql;
	//					and ce.Cadastro_Endereco_ID = (select ceaux.Cadastro_Endereco_ID from cadastros_enderecos ceaux where ceaux.Cadastro_ID = ce.Cadastro_ID limit 1  )

	$totalChamados = 0;
	$dadosChamadosCidade = "";
	$virgula = "";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$totalChamados = $totalChamados + $row[Cont];
		$dadosChamadosCidade .= $virgula."['".$row[Cidade]."', ".$row[Cont]."]";
		$virgula = ",";
	}

	$representanteID = ($dadosUserLogin['userID'] * -1);

	$sql = "select count(tw.Workflow_ID) as Cont, coalesce(ce.UF,'N/A') as UF
		from telemarketing_workflows tw
			inner join cadastros_enderecos ce on tw.Solicitante_ID = ce.Cadastro_ID
						and ce.Tipo_Endereco_ID = 38
			where tw.Workflow_ID is not null
			$condicoes
			group by ce.UF
			order by ce.UF";
	//echo $sql;
	//					and ce.Cadastro_Endereco_ID = (select ceaux.Cadastro_Endereco_ID from cadastros_enderecos ceaux where ceaux.Cadastro_ID = ce.Cadastro_ID limit 1  )

	$virgula = "";
	$dadosChamadosUF = "";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$dadosChamadosUF .= $virgula."['".$row[UF]."',".$row[Cont]."]";
		$virgula = ",";
	}

?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Total de Pedidos: <?php echo $totalChamados;?>
		</p>
	</div>
	<div class="conteudo-interno">
		<script type='text/javascript' src='https://www.google.com/jsapi'></script>
		<script type="text/javascript">
			google.load("visualization", "1", {packages:["corechart"]});
			google.setOnLoadCallback(drawChart);
			function drawChart() {
				var data = google.visualization.arrayToDataTable([
				  ['Estado', 'Chamados'], <?php echo $dadosChamadosUF; ?>
				]);
				var options = {
				  title: 'PEDIDOS POR ESTADO',
				  is3D: true,
				};
			var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
			chart.draw(data, options);
		  }
		</script>
		<div id="piechart_3d" style="width: 50%; height: 600px;float:left;min-width:400px;" align='center'></div>
		<script type='text/javascript'>

			google.load('visualization', '1', {'packages': ['geochart']});
			google.setOnLoadCallback(drawRegionsMap);

			function drawRegionsMap() {
			var data = google.visualization.arrayToDataTable([
			  ['Cidade', 'Chamados'], <?php echo $dadosChamadosCidade;?>
			]);

			  var options = {
				region: 'BR',
				displayMode: 'markers',
				resolution: 'provinces',
				colorAxis: {colors: ['#299ece', '#003399']}
			  };

			var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
			chart.draw(data, options);
			};
		</script>
		<div id="chart_div" style="width:50%; min-height:600px; float:left;min-width:400px;" align='center'></div>
	</div>
</div>


