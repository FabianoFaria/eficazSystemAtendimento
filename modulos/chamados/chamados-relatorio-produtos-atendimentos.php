<?php
	global $caminhoSistema;
	if ($_POST){
		$dataInicio = $_POST['data-inicio-abertura'];
		$dataFim = $_POST['data-fim-abertura'];
	}
	else{
		$dataInicio = "01/".date("m/Y");
		$dataFim = date("d/m/Y");
	}
?>
<!--
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
-->
<?php

$c=0;
$resultado = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = 19");
while($row = mpress_fetch_array($resultado)){
	$c++;
	$arrTipos[$c][id]= $row['Tipo_ID'];
	$arrTipos[$c][descricao] = $row['Descr_Tipo'];
}

$sql = "select pv.Produto_Variacao_ID, c.Tipo_Workflow_ID, count(distinct cp.Workflow_ID) as Quantidade
		from produtos_dados pd
		inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
		inner join chamados_workflows_produtos cp on pv.Produto_Variacao_ID = cp.Produto_Variacao_ID
		inner join chamados_workflows c on cp.Workflow_ID = c.Workflow_ID
		left join tipo t on t.Tipo_Grupo_ID = 19 and t.Tipo_ID = c.Tipo_Workflow_ID
		group by pd.Nome, pv.Descricao, pv.Produto_Variacao_ID, t.Descr_Tipo
		order by pv.Produto_Variacao_ID";
//echo $sql;
$resultado = mpress_query($sql);
while($row = mpress_fetch_array($resultado)){
	$arrAplicacao[$row['Produto_Variacao_ID']][$row['Tipo_Workflow_ID']][quantidade] = $row['Quantidade'];
}

$sql = "select pd.Codigo as Codigo, concat(pd.Nome, pv.Descricao) as Produto, pv.Produto_Variacao_ID,
		sum(coalesce(cp.Quantidade,0)) as Quantidade,
		count(distinct cp.Workflow_ID)	as TotalAtendimentos,
		count(Distinct c.Solicitante_ID) as SolicitantesDistintos
		from produtos_dados pd
		inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
		left join chamados_workflows_produtos cp on pv.Produto_Variacao_ID = cp.Produto_Variacao_ID
		left join chamados_workflows c on cp.Workflow_ID = c.Workflow_ID
		group by pd.Nome, pv.Descricao, pv.Produto_Variacao_ID
		order by Codigo";
//echo $sql;

$resultado = mpress_query($sql);
while($row = mpress_fetch_array($resultado)){
	$i++;
	$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo]."</p>";
	$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Produto]."</p>";
	$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($row[Quantidade], 0, ',', '.')."</p>";
	$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$row[SolicitantesDistintos]."</p>";
	$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$row[TotalAtendimentos]."</p>";
	$c = 6;
	foreach ($arrTipos as $tipo){
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($arrAplicacao[$row['Produto_Variacao_ID']][$tipo[id]][quantidade], 0, ',', '.')."</p>";
		$arrTotalTipos[$tipo[id]][tot] += $arrAplicacao[$row['Produto_Variacao_ID']][$tipo[id]][quantidade];
	}
	$totalQuantidade += $row[Quantidade];
	$totalTotalAtendimentos += $row[TotalAtendimentos];
	$totalSolicitantesDistintos += $row[SolicitantesDistintos];
}
$dados[colunas][titulo][1] 	= "Código";
$dados[colunas][titulo][2] 	= "Produto / Serviço";
$dados[colunas][titulo][3] 	= "<p Style='margin:2px 3px 0 3px;float:right;'>Total Produto/Serviço</p>";
$dados[colunas][titulo][4] 	= "<p Style='margin:2px 3px 0 3px;float:right;'>Total Solicitantes(Clientes)</p>";
$dados[colunas][titulo][5] 	= "<p Style='margin:2px 3px 0 3px;float:right;'>Total ".$_SESSION['objeto']."</p>";
$c = 6;
foreach ($arrTipos as $tipo){
	$dados[colunas][titulo][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$_SESSION['objeto']." ".$tipo[descricao]."</p>";
}
$largura = "100%";
$colunas = $c - 1;

/*
$dados[colunas][tamanho][1] = "width='40px'";
$dados[colunas][tamanho][2] = "width='90px'";
*/
$dados[colunas][conteudo][$i + 1][3] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalQuantidade, 0, ',', '.')."</b></p>";
$dados[colunas][conteudo][$i + 1][4] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".$totalSolicitantesDistintos."</b></p>";
$dados[colunas][conteudo][$i + 1][5] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".$totalTotalAtendimentos."</b></p>";
$c = 6;
foreach ($arrTipos as $tipo){
	$dados[colunas][conteudo][$i + 1][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".$arrTotalTipos[$tipo[id]][tot]."</b></p>";
	$dadosGerais .= $virgula."['".$tipo[descricao]." - ".$arrTotalTipos[$tipo[id]][tot]."',".$arrTotalTipos[$tipo[id]][tot]."]";
	$virgula = ",";
}

echo " 	<div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Relatório de ".$_SESSION['objeto']." / Produto Sintético</p>
			</div>
			<div class='conteudo-interno' id='conteudo-interno-retorno'>";
geraTabela($largura,$colunas,$dados, null, 'relatorio-atendimentos', 2, 2, 100,1);
echo "		</div>
		</div>";	
?>
		
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
	google.load("visualization", "1", {packages:["corechart"]});
	google.setOnLoadCallback(drawChart);
	function drawChart() {
		var data = google.visualization.arrayToDataTable([
		  ['Tipo', 'Quantidade'], <?php echo $dadosGerais; ?>
		]);
		var options = {
		  title: 'Tipos <?php echo ($_SESSION['objeto']);?>',
		  is3D: true,
		};
   var chart = new google.visualization.PieChart(document.getElementById('piechart_3d'));
   chart.draw(data, options);
 }
</script>	
<div id="piechart_3d" style="width: 100%; min-height:500px;float:left;" align='center'></div>