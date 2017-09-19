<?php
	global $caminhoSistema;
	if ($_POST){
		$dataInicio = $_POST['data-inicio-abertura'];
		$dataFim = $_POST['data-fim-abertura'];
		$situacaoID = $_POST['localiza-pedido-situacao'];
	}
	else{
		//$dataInicio = "01/".date("m/Y");
		//$dataFim = date("d/m/Y");
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
			<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-pesquisar-relatorio'></p>
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
		$condicoes .= " and tw.Data_Abertura between '$dataInicio' and '$dataFim' ";
	}
	if ($situacaoID!=""){ $condicoes = "";}

	$sql = "select count(*) as Cont, upper(t.Descr_Tipo) as Situacao
			from telemarketing_workflows tw
			left join telemarketing_follows tf on tw.Workflow_ID = tf.Workflow_ID and tf.Follow_ID = (select max(tfaux.Follow_ID) from telemarketing_follows tfaux where tf.Workflow_ID = tfaux.Workflow_ID)
			left join tipo t on t.Tipo_ID = tf.Situacao_ID
			where tw.Workflow_ID is not null
			$condicoes
			group by t.Descr_Tipo
			order by t.Descr_Tipo";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$totalChamados = $totalChamados + $row[Cont];
		$dadosChamados .= $virgula."['".$row[Situacao]."',".$row[Cont]."]";
		$virgula = ",";
	}
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Total de Chamados: <?php echo $totalChamados;?>
		</p>
	</div>
	<div class="conteudo-interno">
<?php
	if($dadosChamados!=""){
?>
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      	google.load("visualization", "1", {packages:["corechart"]});
      	google.setOnLoadCallback(drawChart);
      	function drawChart() {
			var data = google.visualization.arrayToDataTable([
			  ['Situacao', 'Quantidade.'], <?php echo $dadosChamados; ?>
			]);
			var options = {
			  title: 'SITUA��O GERAL CHAMADOS',
			  is3D: true,
		 	};
        var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
        chart.draw(data, options);
      }
    </script>
    <div id="piechart_3d" style="width: 50%; min-height:500px;float:left;min-width:400px;" align='center'></div>

	<script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
			  ['Situacao', 'Quantidade'], <?php echo $dadosChamados; ?>
        ]);

        var options = {
		  title: 'SITUA��O GERAL CHAMADOS',
		  is3D: true,
        };

        var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
    <div id="chart_div" style="width: 50%; min-height:500px;float:left;min-width:400px;" align='center'></div>
<?php
	}
?>