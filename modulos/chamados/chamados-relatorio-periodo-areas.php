<?php
	global $caminhoSistema;
	if ($_POST){
		$descSabado = $_POST["desconsiderar-sabado"];
		$descDomingo = $_POST["desconsiderar-domingo"];
	}
	else{
		$descSabado = 1;
		$descDomingo = 1;
	}

	$dadosChamados = "";
	$totalChamados = 0;

	$sql = "select cw.Workflow_ID, coalesce(r.Descr_Tipo,'N/A') as Regional, datediff(now(), cw.Data_Abertura) as Dias,  DATE_FORMAT(cw.Data_Abertura, '%d/%m/%Y') as Data_Inicio, DATE_FORMAT(now(), '%d/%m/%Y')as Data_Hoje
			from chamados_workflows cw
			inner join cadastros_dados cd on cd.Cadastro_ID = cw.Solicitante_ID
			inner join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID) and cf.Situacao_ID not in (33,34)
			left join tipo r on r.Tipo_ID = cd.Regional_ID
			where cw.Workflow_ID is not null
			order by r.Descr_Tipo";

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$totalChamados++;
		$regiao = $row[Regional];
		$sabados = $domingos = 0;
		if (($descSabado=="1") || ($descDomingo=="1")){
			$arrDias = retornaNumeroDias($row[Data_Inicio],$row[Data_Hoje]);
			$sabados = $arrDias[sabado];
			$domingos = $arrDias[domingo];
		}
		$dias = $row[Dias] - $sabados - $domingos;

		/*
		if (($row[UF]=="AM") || ($row[UF]=="RR") || ($row[UF]=="AP") || ($row[UF]=="PA") || ($row[UF]=="AC") || ($row[UF]=="RO") || ($row[UF]=="TO") || ($row[UF]=="MT") || ($row[UF]=="GO") || ($row[UF]=="DF") || ($row[UF]=="MS")){
			$regiao = "Centro Norte";
		}
		if (($row[UF]=="MG") || ($row[UF]=="ES") || ($row[UF]=="RJ")){
			$regiao = "Leste";
		}
		if (($row[UF]=="MA") || ($row[UF]=="PI") || ($row[UF]=="CE") || ($row[UF]=="RN") || ($row[UF]=="PB") || ($row[UF]=="PE") || ($row[UF]=="AL") || ($row[UF]=="SE") || ($row[UF]=="BA")){
			$regiao = "Nordeste";
		}
		if (($row[UF]=="SC") || ($row[UF]=="RG")){
			$regiao = "Sul";
		}
		if (($row[UF]=="PR")){
			$regiao = "Paraná";
		}
		if ($row[UF]=="SP"){
			if (($row['cidade']=="SAO PAULO") || ($row['cidade']=="SÃO PAULO")){
				$regiao = "São Paulo - Capital";
			}
			else{
				$regiao = "São Paulo - Interior";
			}
		}
		*/


		if ($dias >= 61){
			$dados[$regiao][5][quantidade] += 1;
			$dados[$regiao][5][workflows] .= ",".$row["Workflow_ID"];
			$virg5 = ",";
		}
		else
			$dados[$regiao][5][quantidade] += 0;

		if (($dias >= 31) && ($dias <= 60)){
			$dados[$regiao][4][quantidade] += 1;
			$dados[$regiao][4][workflows] .= ",".$row["Workflow_ID"];
			$virg4 = ",";
		}
		else
			$dados[$regiao][4][quantidade] += 0;

		if (($dias >= 13) && ($dias <= 30)){
			$dados[$regiao][3][quantidade] += 1;
			$dados[$regiao][3][workflows] .= ",".$row["Workflow_ID"];
			$virg3 = ",";
		}
		else
			$dados[$regiao][3][quantidade] += 0;


		if (($dias >= 4) && ($dias <= 12)){
			$dados[$regiao][2][quantidade] += 1;
			$dados[$regiao][2][workflows] .= ",".$row["Workflow_ID"];
			$virg2 = ",";
		}
		else
			$dados[$regiao][2][quantidade] += 0;

		if ($dias <= 3){
			$dados[$regiao][1][quantidade] += 1;
			$dados[$regiao][1][workflows] .= ",".$row["Workflow_ID"];
			$virg1 = ",";
		}
		else
			$dados[$regiao][1][quantidade] += 0;


	}
?>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p>
			Total de Chamados em ABERTO: <?php echo $totalChamados;?>
			<span style='float:right'>
				<input type='checkbox' name='desconsiderar-sabado' id='desconsiderar-sabado' value='1' <?php if ($descSabado==1) echo "checked"?>/> Desconsiderar Sabado
				<input type='checkbox' name='desconsiderar-domingo' id='desconsiderar-domingo' value='1' <?php if ($descDomingo==1) echo "checked"?>/> Desconsiderar Domingo
				<input type='checkbox' name='reload-relatorio' id='reload-relatorio' value='1' <?php if ($_POST["reload-relatorio"]==1) echo "checked"?>/> Atualizar Automaticamente
			</span>
		</p>
	</div>
	<input type='hidden' name='workflow-id' id='workflow-id'>
	<div class="conteudo-interno">
<?php
	$valoresGrafico = "";
	$colunas = count($dados) + 1;
	$tamanho = 100 / $colunas;

	$html .= "<table style='float:left; width:$tamanho%'>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>PERÍODO</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>Mais que 60</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>Entre 31 e 60 dias</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>Entre 13 e 30 dias</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>Entre 4 e 12 dias</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>Até 3 dias</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='right'>TOTAL:&nbsp;</td></tr>
				<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='right'>% DO TOTAL:&nbsp;</td></tr>
			 </table>";
	foreach($dados as $chave => $dado){
		$i++;
		$html .= "<table style='float:left; width:$tamanho%'>
					<tr height='30px'><td class='tabela-fundo-escuro-titulo' align='center'>$chave</td></tr>";
		$totalRegiao = 0;
		$valoresGrafico .= ",['$chave'";

		foreach($dado as $valor){
			$classe = "";
			$workflows = "";
			if ($valor[quantidade]>0){
				$classe = "link mostrar-workflows-relatorio";
				$workflows = substr($valor[workflows],1);
			}
			$html .= "<tr height='30px'><td class='tabela-fundo-claro $classe' align='center' workflows='$workflows'>".$valor[quantidade]."</td></tr>";
			$totalRegiao += $valor[quantidade];
			$valoresGrafico .= ",".$valor[quantidade];
		}
		$valoresGrafico .= "]";
		$html .= "<tr height='30px'><td class='tabela-fundo-claro' align='center'><b>".$totalRegiao."</b></td></tr>
				  <tr height='30px'><td class='tabela-fundo-claro' align='center'>".number_format((($totalRegiao / $totalChamados) * 100), 2, ',', '.')." %</td></tr>";
?>
<?php
		$html .= "</table>";
	}
?>
		<script type="text/javascript">
		google.load("visualization", "1", {packages:["corechart"]});
		google.setOnLoadCallback(drawChart);
		function drawChart() {
		  var data = google.visualization.arrayToDataTable([
			['Grupo', 'Mais que 60', 'Entre 31 e 60 dias', 'Entre 13 e 30 dias', 'Entre 4 e 12 dias', 'Até 3 dias']
			<?php echo $valoresGrafico;?>
		  ]);
		  var options = {
			legend: { position: 'top', maxLines: 3,alignment:'center' },
			chartArea:{left:0,top:50,width:'100%'},
			hAxis: {titleTextStyle: {color: 'red'}}
		  };

		  var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
		  chart.draw(data, options);

		}
		</script>
		<table style='float:left; width:100%' border='0'>
			<tr>
				<td style='width:<?php echo $tamanho;?>%'>&nbsp;</td>
				<td style='width:<?php echo (100 - $tamanho);?>%'> <div id='chart_div' style='width: 100%; float:left;'></div></td>
			</tr>
		</table>
<?php	echo $html; ?>
	</div>
</div>
<div id='exibir-lista-chamados'></div>


