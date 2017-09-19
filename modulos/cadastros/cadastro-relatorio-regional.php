<?php
	global $caminhoSistema;
	$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
	$areasAtuacoes = $_POST['localiza-areas-atuacoes'];
	$localizaUf = $_POST['localiza-uf'];

	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1) $classeEmpresasEsconde = " esconde ";



	echo "	<input type='hidden' id='cadastroID' name='cadastroID' value=''>
			<div class='titulo-container'>
				<div class='titulo' style='min-height:25px'>
					<p style='margin-top:2px;'>
					Filtros de Pesquisa
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario tres-colunas'>
						<p>Tipo Cadastro</p>
						<p><select name='localiza-tipo-cadastro-id' id='localiza-tipo-cadastro-id'>".optionValueGrupo(9, $tipoCadastro)."</select></p>
					</div>
					<div class='titulo-secundario tres-colunas'>
						<p>&Aacute;rea de Atua&ccedil;&atilde;o</p>
						<p><select name='localiza-areas-atuacoes' id='localiza-areas-atuacoes'>".optionValueGrupo(34, $areasAtuacoes)."</select></p>
					</div>
					<div class='titulo-secundario tres-colunas'>
						<div class='titulo-secundario' style='width:30%; float:left;'>
							<!--<p>UF:</p>
							<p><select name='localiza-uf' id='localiza-uf'>".optionValueGrupoUF($localizaUf, "Todos")."<select></p>--> &nbsp;
						</div>
						<div class='titulo-secundario' style='width:70%; float:left;'>
							<p style='margin-top:15px;' align='right'>
								<input type='button' Style='width:140px;' value='Pesquisar' id='botao-pesquisar-relatorio-regional'>
							</p>
						</div>
					</div>
				</div>

			</div>";

	if ($_POST){
		if ($tipoCadastro != ""){ $sqlCond .= " and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";}
		if ($areasAtuacoes != ""){ $sqlCond .= " and cd.Areas_Atuacoes like '%s:".strlen($areasAtuacoes).":\"".$areasAtuacoes."\"%'";}

		$sql = "select count(cd.Cadastro_ID) as Cont, ce.UF as UF, upper(ce.Cidade) as Cidade
					from cadastros_dados cd
					inner join cadastros_enderecos ce on cd.Cadastro_ID = ce.Cadastro_ID
					where ce.UF <> '' and Cidade <> '' and ce.Situacao_ID = 1 and cd.Situacao_ID = 1 and cd.Cadastro_ID > 0
					$sqlCond
					group by ce.UF, ce.Cidade order by Cont desc, ce.UF";

		$total = 0;
		$dadosCadastrosCidade = "";
		$virgula = "";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$total = $total + $row[Cont];
			$dadosCadastrosCidade .= $virgula."['".$row[Cidade]."', ".$row[Cont]."]";
			$virgula = ",";
		}

		if ($localizaUf==""){
			$sql = "select count(cd.Cadastro_ID) as Cont, ce.UF as UF
					from cadastros_dados cd
					inner join cadastros_enderecos ce on cd.Cadastro_ID = ce.Cadastro_ID
					where ce.UF <> '' and Cidade <> '' and ce.Situacao_ID = 1 and cd.Situacao_ID = 1 and cd.Cadastro_ID > 0
					$sqlCond
					group by ce.UF
					order by ce.UF";

			$virgula = "";
			$dadosCadastrosUF = "";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$dadosCadastrosUF .= $virgula."['".$row[UF]."',".$row[Cont]."]";
				$virgula = ",";
			}
		}

	?>
	<div class="titulo-container">
		<div class="titulo" style="min-height:25px">
			<p style="margin-top:2px;">
			Total de Cadastros: <?php echo $total;?>
			</p>
		</div>
		<div class="conteudo-interno">
			<script type='text/javascript' src='https://www.google.com/jsapi'></script>
<?php
	if ($localizaUf==""){
?>
			<script type="text/javascript">
				google.load("visualization", "1", {packages:["corechart"]});
				google.setOnLoadCallback(drawChart);
				function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Estado', 'Chamados'], <?php echo $dadosCadastrosUF; ?>
					]);
					var options = {
					  title: 'CADASTROS POR ESTADO',
					  is3D: true,
					};
				var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
				chart.draw(data, options);
			  }
			</script>
			<div id="piechart_3d" style="width: 50%; height: 600px;float:left;min-width:400px;" align='center'></div>
<?php
		$styleMapa = "style='width:50%; min-height:600px; float:left;min-width:400px;'";
	}else{
		$styleMapa = "style='width:100%; min-height:600px;'";
		//$visualizacaoMapa = "region: 'BR-PR',";
		//$visualizacaoMapa = "region: 'BR-".$localizaUf."',";
	}
	$visualizacaoMapa = "region: 'BR',";
?>
			<script type='text/javascript'>

				google.load('visualization', '1', {'packages': ['geochart']});
				google.setOnLoadCallback(drawRegionsMap);

				function drawRegionsMap() {
				var data = google.visualization.arrayToDataTable([
				  ['Cidade', 'Cadastros'], <?php echo $dadosCadastrosCidade;?>
				]);
				  var options = {
				  	<?php echo $visualizacaoMapa;?>
					displayMode: 'markers',
					resolution: 'provinces',
					enableRegionInteractivity: 'true',
					colorAxis: {colors: ['#299ece', '#003399']}
				  };

				var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
				google.visualization.events.addListener(chart, 'select', function() {
					var selection = chart.getSelection();
					if(typeof selection[0] !== "undefined") {
					  var value = data.getValue(selection[0].row, 0);
					  //alert('Cidade: ' + value);
					  carregarCadastrosCidade(value);
					}
				});
		        google.visualization.events.addListener(chart, 'regionClick', function(e){
		        	//alert(e['region']);
                });
				chart.draw(data, options);

				};
			</script>
			<div id="chart_div"  <?php echo $styleMapa;?> align='center'></div>
		</div>

		<div id='div-cadastros-listagem'></div>
	</div>
<?php
}
?>

