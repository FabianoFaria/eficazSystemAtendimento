<?php
	session_start();
	global $dadosUserLogin;
	if ($_POST){
		$responsavelID = $_POST['select-responsavel'];
		$solicitanteID = $_POST['select-solicitante'];
		$chkAberto = $_POST['select-solicitante'];
		$situacaoID = $_POST['select-situacao'];
		$tipoTarefaID = $_POST['select-tipo-tarefa'];
		$dataInicio = $_POST['data-inicio-retorno'];
		$dataFim = $_POST['data-fim-retorno'];
		$dataInicioObservacao = $_POST['data-inicio-observacao'];
		$dataFimObservacao = $_POST['data-fim-observacao'];
	}else{
		$responsavelID = $dadosUserLogin['userID'];
		//$situacaoID = "83";
	}
	$aberto = "#CC0000;";
	$fechado = "#003300;";
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Filtros de Pesquisa
		</p>
	</div>
	<div class="conteudo-interno">
<?php
	echo "<div class='titulo-secundario' style='float:left;width:20%;'>
				<p>Respons&aacute;vel:</p>";

	$sql = "Select distinct r.Grupo_ID, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel, m.Modulo_Acesso_ID, m.Titulo as Grupo
					from chamados_workflows_tarefas t
					inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
					inner join cadastros_dados r on r.Cadastro_ID = t.Responsavel_ID
					inner join modulos_acessos m on m.Modulo_Acesso_ID = r.Grupo_ID
					where m.Situacao_ID = 1 and r.Situacao_ID = 1
					order by m.Titulo, m.Modulo_Acesso_ID, r.Nome";
			if ($responsavelID=='A')$selectedUsuario = "selected";
			echo "<select id='select-responsavel' name='select-responsavel' Style='width:98.5%'>
						<option value=''>Todos</option>
						<option value='A' ".$selectedUsuario.">Tarefas Abertas por ".$dadosUserLogin[nome]."</option>";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				if ($row[Grupo_ID]!=$grupoIDAnt){
					$selecionado = ""; if ($responsavelID=="G".$row[Grupo_ID]) $selecionado = "selected";
					echo "<option value='G".$row[Grupo_ID]."' $selecionado>".$row['Grupo']."</option>";
				}
				$selecionado = ""; if ($responsavelID==$row[Responsavel_ID]) $selecionado = "selected";
				echo "<option value='".$row[Responsavel_ID]."' $selecionado>&nbsp;&nbsp;&nbsp;".$row['Responsavel']."</option>";
				$grupoIDAnt = $row[Grupo_ID];
			}
			echo "</select>
		</div>

		<div class='titulo-secundario' style='float:left;width:20%;'>
			<p>Solicitante:</p>";

		$sql = "Select distinct w.Solicitante_ID as Solicitante_ID, c.Nome as Solicitante
					from chamados_workflows_tarefas t
					inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
					inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
					order by c.Nome";
		echo "<select id='select-solicitante' name='select-solicitante' Style='width:98.5%'>
					<option value=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$selecionado = "";
			if ($solicitanteID==$row[Solicitante_ID]) $selecionado = "selected";
			echo "<option value='".$row[Solicitante_ID]."' $selecionado>".$row['Solicitante']."</option>";
		}

		echo "</select>
		</div>";
?>
		<div class="titulo-secundario" style="float:left;width:60%;height:50px">
			<p>&nbsp;</p>
			<p class='exibe-graficos'><input type='checkbox' name='exibir-graficos' id='exibir-graficos' value='1' <?php if ($_POST['exibir-graficos']==1) echo " checked "; ?>/> Exibir Gr&aacute;ficos</p>
		</div>
		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Agrupar</p>
			<p>
				<select name="select-agrupar" id="select-agrupar">
					<option value='tarefa'  <?php if ($_POST['select-agrupar'] == "tarefa") echo " selected ";?>>Por Tarefa</option>
					<option value='chamado' <?php if ($_POST['select-agrupar'] == "chamado") echo " selected ";?>>Por <?php echo $_SESSION['objeto'];?></option>
					<option value='solicitante' <?php if ($_POST['select-agrupar'] == "solicitante") echo " selected ";?>>Por Solicitante</option>
					<option value='responsavel' <?php if ($_POST['select-agrupar'] == "responsavel") echo " selected ";?>>Por Respons&aacute;vel</option>
					<option value='solicitante-responsavel' <?php if ($_POST['select-agrupar'] == "solicitante-responsavel") echo " selected ";?>>Por Respons&aacute;vel / Solicitante</option>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Tarefa</p>
			<p>
				<select name="select-tipo-tarefa" id="select-tipo-tarefa">
				<?php echo optionValueGrupo(40, $tipoTarefaID, "Todos");?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Situa&ccedil;&atilde;o Tarefa</p>
			<p>
				<select name="select-situacao" id="select-situacao">
				<?php echo optionValueGrupo(41, $situacaoID, "Todos");?>
				<select>
			</p>
		</div>
		<!--
		<div class='titulo-secundario' style='float:left;width:20%; margin-top:20px'>
			<p>
				<span style='width:50px;background-color:<?php echo $aberto;?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				Aberta
				&nbsp;&nbsp;&nbsp;
				<span style='width:50px;background-color:<?php echo $fechado;?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				Finalizada
			</p>
			<p>&nbsp;</p>
		</div>
		-->

		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Data Retorno:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-retorno' id='data-inicio-retorno' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-inicio-retorno']; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-retorno' id='data-fim-retorno' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-fim-retorno'] ?>'></p>
			</div>
		</div>
		<!--
		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Data Tempo Gasto:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-observacao' id='data-inicio-observacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-inicio-observacao']; ?>'>&nbsp;&nbsp;</p>
			</div>

			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-observacao' id='data-fim-observacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-fim-observacao'] ?>'></p>
			</div>
		</div>
		-->
		<div class='titulo-secundario' Style='float:left;width:20%; margin-top:15px; margin-bottom:10px;'>
			<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-pesquisar-calendario'></p>
		</div>


	</div>
</div>
<input type='hidden' name='workflow-id' id='workflow-id'>
<?php
	global $dadosUserLogin;
	if ($_POST){
		if ($responsavelID!=""){
			if ($responsavelID=="A")
				$sqlCond = " and t.Usuario_cadastro_ID = '".$dadosUserLogin['userID']."' ";
			else{
				if (substr($responsavelID,0,1)=="G")
					$sqlCond = " and t.Grupo_Responsavel_ID = '".substr($responsavelID,1,strlen($responsavelID))."'";
				else
					$sqlCond = " and t.Responsavel_ID = '$responsavelID' ";
			}
		}

		if ($solicitanteID!="")
			$sqlCond .= " and w.Solicitante_ID = '$solicitanteID'";
		if ($situacaoID!="")
			$sqlCond .= " and t.Situacao_ID = '$situacaoID'";
		if ($tipoTarefaID!="")
			$sqlCond .= " and t.Tipo_Tarefa_ID = '$tipoTarefaID'";

		if(($dataInicio!="")||($dataFim!="")){
			$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
			if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
			$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
			if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
			$sqlCond .= " and t.Data_Retorno between '$dataInicio' and '$dataFim' ";
		}
		/*
		if(($dataInicioObservacao!="")||($dataFimObservacao!="")){
			$dataInicioObservacao = implode('-',array_reverse(explode('/',$dataInicioObservacao)));
			if ($dataInicioObservacao=="") $dataInicioObservacao = "0000-00-00"; $dataInicioObservacao .= " 00:00";
			$dataFimObservacao = implode('-',array_reverse(explode('/',$dataFimObservacao)));
			if ($dataFimObservacao=="") $dataFimObservacao = "2100-01-01"; $dataFimObservacao .= " 23:59";
			$sqlCond .= " and cf.Hora_Inicio > '$dataInicioObservacao' and cf.Hora_Fim < '$dataFimObservacao' ";
		}
		*/

		if ($_POST['select-agrupar'] == "tarefa"){
			$colunas = "7";
			$sql = "Select w.Workflow_ID as Workflow_ID, tf.Descr_Tipo as Tipo_Tarefa,
						DATE_FORMAT(t.Data_Retorno,'%d/%m/%Y %H:%i') as Data_Retorno,
						DATE_FORMAT(t.Data_Retorno,'%H:%i') as Hora_Retorno, c.Nome as Cadastro,
						coalesce(r.Nome,m.Titulo) as Responsavel, ts.Descr_Tipo as Situacao, t.Situacao_ID as Situacao_ID,
						SUM(TIME_TO_SEC(timediff(cf.Hora_Fim,cf.Hora_Inicio))) as Segundos_Diferenca
					from chamados_workflows_tarefas t
						inner join tipo tf on tf.Tipo_ID = t.Tipo_Tarefa_ID
						inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
						inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
						inner join tipo ts on ts.Tipo_ID = t.Situacao_ID
						left join cadastros_dados r on r.Cadastro_ID = t.Responsavel_ID
						left join modulos_acessos m on m.Modulo_Acesso_ID = t.Grupo_Responsavel_ID
						left join chamados_workflows_tarefas_follows cf on cf.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID
						where t.Workflow_ID > 0
						$sqlCond
					 group by w.Workflow_ID , tf.Descr_Tipo, t.Data_Retorno, c.Nome, r.Nome, m.Titulo,
							ts.Descr_Tipo, t.Situacao_ID
						order by t.Data_Retorno ";
			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' workflow-id='".$row['Workflow_ID']."' class='workflow-localiza link'>".$row['Workflow_ID']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Tipo_Tarefa']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Data_Retorno']."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Cadastro']."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Responsavel']."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Situacao']."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".retornaHorasMinutos($row['Segundos_Diferenca'])."</p>";
			}
			$dados[colunas][titulo][1] = $_SESSION['objeto']." ID";
			$dados[colunas][titulo][2] = "Tipo Tarefa";
			$dados[colunas][titulo][3] = "Data Limite";
			$dados[colunas][titulo][4] = "Solicitante";
			$dados[colunas][titulo][5] = "Respons&aacute;vel";
			$dados[colunas][titulo][6] = "Situa&ccedil;&atilde;o";
			$dados[colunas][titulo][7] = "Horas Trabalho";
		}
		if ($_POST['select-agrupar'] == "chamado"){
			$colunas = "3";
			$sql = "Select w.Workflow_ID as Workflow_ID, c.Nome as Cadastro, SUM(TIME_TO_SEC(timediff(cf.Hora_Fim,cf.Hora_Inicio))) as Segundos_Diferenca
					from chamados_workflows_tarefas t
						inner join tipo tf on tf.Tipo_ID = t.Tipo_Tarefa_ID
						inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
						inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
						left join chamados_workflows_tarefas_follows cf on cf.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID
						where t.Workflow_ID > 0
						$sqlCond
					 group by w.Workflow_ID, c.Nome
						order by w.Workflow_ID ";

			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$arrGrafico .= ",['".$_SESSION['objeto']." ".$row['Workflow_ID']."', ".($row['Segundos_Diferenca']/3600)."]";
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' workflow-id='".$row['Workflow_ID']."' class='workflow-localiza link'>".$row['Workflow_ID']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Cadastro']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".retornaHorasMinutos($row['Segundos_Diferenca'])."</p>";
			}
			$dados[colunas][titulo][1] = $_SESSION['objeto']." ID";
			$dados[colunas][titulo][2] = "Solicitante";
			$dados[colunas][titulo][3] = "Horas Trabalho";
		}

		/*******************************************/

		if ($_POST['select-agrupar'] == "solicitante"){
			$colunas = "2";
			$sql = "Select c.Nome as Cadastro, SUM(TIME_TO_SEC(timediff(cf.Hora_Fim,cf.Hora_Inicio))) as Segundos_Diferenca
					from chamados_workflows_tarefas t
						inner join tipo tf on tf.Tipo_ID = t.Tipo_Tarefa_ID
						inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
						inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
						left join chamados_workflows_tarefas_follows cf on cf.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID
						where t.Workflow_ID > 0
						$sqlCond
					 group by c.Nome
						order by c.Nome";

			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$arrGrafico .= ",['".$row['Cadastro']."', ".($row['Segundos_Diferenca']/3600)."]";
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Cadastro']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".retornaHorasMinutos($row['Segundos_Diferenca'])."</p>";
			}
			$dados[colunas][titulo][1] = "Solicitante";
			$dados[colunas][titulo][2] = "Horas Trabalho";
		}

		/*******************************************/

		if ($_POST['select-agrupar'] == "responsavel"){
			$colunas = "2";
			$sql = "SELECT coalesce(c.Nome, concat('Grupo ',m.Titulo)) AS Cadastro, SUM(TIME_TO_SEC(timediff(cf.Hora_Fim,cf.Hora_Inicio))) AS Segundos_Diferenca
						FROM chamados_workflows_tarefas t
						INNER JOIN tipo tf ON tf.Tipo_ID = t.Tipo_Tarefa_ID
						INNER JOIN chamados_workflows w ON t.Workflow_ID = w.Workflow_ID
						left JOIN cadastros_dados c ON c.Cadastro_ID = t.Responsavel_ID
						left JOIN modulos_acessos m ON m.Modulo_Acesso_ID = t.Grupo_Responsavel_ID
						LEFT JOIN chamados_workflows_tarefas_follows cf ON cf.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID
						WHERE t.Workflow_ID > 0
						$sqlCond
						GROUP BY c.Nome, m.Titulo
						ORDER BY Cadastro";

			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$arrGrafico .= ",['".$row['Cadastro']."', ".($row['Segundos_Diferenca']/3600)."]";
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Cadastro']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".retornaHorasMinutos($row['Segundos_Diferenca'])."</p>";
			}
			$dados[colunas][titulo][1] = "Respons&aacute;vel";
			$dados[colunas][titulo][2] = "Horas Trabalho";
		}

		/*******************************************/

		if ($_POST['select-agrupar'] == "solicitante-responsavel"){
			$colunas = "3";
			$sql = "select w.Solicitante_ID, s.Nome AS Solicitante, coalesce(r.Nome, concat('Grupo ',m.Titulo)) AS Responsavel, SUM(TIME_TO_SEC(timediff(cf.Hora_Fim,cf.Hora_Inicio))) AS Segundos_Diferenca
						from chamados_workflows_tarefas t
						inner join tipo tf ON tf.Tipo_ID = t.Tipo_Tarefa_ID
						inner join chamados_workflows w ON t.Workflow_ID = w.Workflow_ID
						left join cadastros_dados r ON r.Cadastro_ID = t.Responsavel_ID
						left join cadastros_dados s ON s.Cadastro_ID = w.Solicitante_ID
						left join modulos_acessos m ON m.Modulo_Acesso_ID = t.Grupo_Responsavel_ID
						left join chamados_workflows_tarefas_follows cf ON cf.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID
						where t.Workflow_ID > 0
						$sqlCond
						group by w.Solicitante_ID, s.Nome, r.Nome
						order by Solicitante, Responsavel";

			//echo $sql;
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				if ($row[Solicitante_ID]!=$solicitanteIDAnt){
					$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Solicitante']."</p>";
				}
				//$grafResp .= "'".$row['Responsavel']."',";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Responsavel']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".retornaHorasMinutos($row['Segundos_Diferenca'])."</p>";
				$solicitanteIDAnt = $row[Solicitante_ID];
			}
			$dados[colunas][titulo][1] = "Solicitante";
			$dados[colunas][titulo][2] = "Respons&aacute;vel";
			$dados[colunas][titulo][3] = "Horas Trabalho";
		}

		geraTabela("100%",$colunas,$dados);
		if ($i==0){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma registro encontrado</p>";
		}


		if (($i>0)&&($_POST['exibir-graficos']==1)){
			echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script>";

			if ($_POST['select-agrupar'] == "chamado"){
?>
				<script type="text/javascript">
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Solicitante', 'Horas'] <?php echo $arrGrafico;?>
					]);

					var options = {
					  title: 'Solicitante / Horas ',
					  hAxis: {title: 'Horas', titleTextStyle: {color: 'red'}}
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				  }
				</script>
				<div id="chart_div" align='center' style="border:1; width: 100%; height: 800px; float:left;"></div>
<?php
			}


			if ($_POST['select-agrupar'] == "solicitante"){
?>
				<script type="text/javascript">
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Solicitante', 'Horas'] <?php echo $arrGrafico;?>
					]);

					var options = {
					  title: 'Solicitante / Horas ',
					  hAxis: {title: 'Horas', titleTextStyle: {color: 'red'}}
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				  }
				</script>
				<div id="chart_div" align='center' style="border:1; width: 100%; height: 800px; float:left;"></div>
<?php
			}


			if ($_POST['select-agrupar'] == "responsavel"){
?>
				<script type="text/javascript">
				  google.load("visualization", "1", {packages:["corechart"]});
				  google.setOnLoadCallback(drawChart);
				  function drawChart() {
					var data = google.visualization.arrayToDataTable([
					  ['Respons&aacute;vel', 'Horas'] <?php echo $arrGrafico;?>
					]);

					var options = {
					  title: 'Responsável / Horas ',
					  hAxis: {title: 'Horas', titleTextStyle: {color: 'red'}}
					};

					var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
					chart.draw(data, options);
				  }
				</script>
				<div id="chart_div" align='center' style="border:1; width: 100%; height: 800px; float:left;"></div>
<?php
			}

/*
			if ($_POST['select-agrupar'] == "solicitante-responsavel"){
?>
			<script type="text/javascript">
			  google.load("visualization", "1", {packages:["corechart"]});
			  google.setOnLoadCallback(drawChart);
			  function drawChart() {
				 var data = google.visualization.arrayToDataTable(
				 		[['',<?php echo $grafResp;?> { role: 'annotation' } ],

						['2010', 0, 24, 20, 32, 32],
						['2020', 16, 22, 23, 30, 32],
						['2030', 28, 19, 29, 30, 32]
					  ]);
					  var options = {
						width: 800,
						height: 500,
						legend: {position: 'top', maxLines: 3 },
						bar: { groupWidth: '75%' },
							isStacked: true,
						  };
				var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
				chart.draw(data, options);
			  }
			</script>
			<div id="chart_div" align='center' style="border:1; width: 100%; height: 800px; float:left"></div>
<?php
			}
*/
		}


	}

function retornaHorasMinutos($segundos){
	$e = ""; $tempo = "";
	$horas =  (int)($segundos/ 3600);
	$minutos =  (int)(($segundos % 3600)/60);
	if (($horas==0) && ($minutos==0)) $tempo = "";
	else{
		if ($horas!=0){ $tempo .= "$horas horas"; $e = " e "; }
		if ($minutos!=0) $tempo .= $e."$minutos minutos";
	}
	return $tempo;
}
?>