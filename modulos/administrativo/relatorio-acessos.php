<?php
	global $dadosUserLogin;
	if ($_POST){
		$usuarioID = $_POST['select-usuario'];
		$moduloPaginaID = $_POST['select-pagina-modulo'];

		$dataInicio = $_POST['data-inicio'];
		$dataFim = $_POST['data-fim'];
		$tipoRelatorio = $_POST['select-tipo-relatorio'];


	}
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Filtros de Pesquisa
		</p>
	</div>
	<div class="conteudo-interno">
		<div class="titulo-secundario" style="float:left;width:100%;">
			<p>Tipo Relat&oacute;rio</p>
			<p>
				<select name="select-tipo-relatorio" id="select-tipo-relatorio"  Style='width:99.5%'>
					<option value='geral'  <?php if ($tipoRelatorio == "geral") echo " selected ";?>>Listagem geral de acessos</option>
					<option value='usuario-pagina' <?php if ($tipoRelatorio == "usuario-pagina") echo " selected ";?>>Agrupar por usu&aacute;rio / p&aacute;gina</option>
				<select>
			</p>
		</div>
<!--
		<div class="titulo-secundario" style="float:left;width:60%;height:50px">
			<p>&nbsp;</p>
			<p class='exibe-graficos'><input type='checkbox' name='exibir-graficos' id='exibir-graficos' value='1' <?php if ($_POST['exibir-graficos']==1) echo " checked "; ?>/> Exibir Gr&aacute;ficos</p>
		</div>
-->


<?php
	echo "<div class='titulo-secundario' style='float:left;width:30%;'>
				<p>Usu&aacute;rio</p>";

	$sql = "select distinct c.Cadastro_ID, c.Nome from log_acessos l
			inner join cadastros_dados c on c.Cadastro_ID = l.Usuario_ID
			inner join modulos_paginas mp on mp.Modulo_Pagina_ID = l.Pagina_ID
			inner join modulos m on m.Modulo_ID = l.Modulo_ID
			order by c.Nome";
			if ($usuarioID=='')$selectedUsuario = "selected";
			echo "<select id='select-usuario' name='select-usuario' Style='width:99%'>
						<option value=''>Todos</option>";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$selecionado = ""; if ($usuarioID==$row[Cadastro_ID]) $selecionado = "selected";
				echo "<option value='".$row[Cadastro_ID]."' $selecionado>".$row['Nome']."</option>";
			}
			echo "</select>
		</div>";
?>

<?php
	echo "<div class='titulo-secundario' style='float:left;width:35%;'>
				<p>Pagina</p>";
	$sql = "select distinct m.Nome as Modulo, mp.Titulo, m.Modulo_ID, mp.Modulo_Pagina_ID from log_acessos l
			inner join modulos m on m.Modulo_ID = l.Modulo_ID
			inner join modulos_paginas mp on mp.Modulo_Pagina_ID = l.Pagina_ID
			inner join cadastros_dados c on c.Cadastro_ID = l.Usuario_ID
			order by m.Nome, mp.Titulo";
			if ($usuarioID=='')$selectedUsuario = "selected";
			echo "<select id='select-pagina-modulo' name='select-pagina-modulo' Style='width:99%'>
						<option value=''>Todas</option>";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){

				$selecionado = ""; if ($moduloPaginaID==($row[Modulo_ID]*-1)) $selecionado = "selected";
				if ($row[Modulo_ID]!=$moduloIDAnt) echo "<option value='-".$row[Modulo_ID]."' $selecionado>".$row['Modulo']."</option>";

				$selecionado = ""; if ($moduloPaginaID==$row[Modulo_Pagina_ID]) $selecionado = "selected";
				echo "<option value='".$row[Modulo_Pagina_ID]."' $selecionado>&nbsp;&nbsp;&nbsp;".$row['Titulo']."</option>";
				$moduloIDAnt = $row[Modulo_ID];
			}
			echo "</select>
		</div>";
?>

	<div class="titulo-secundario" style="float:left;width:25%;">
		<p>Data Acesso</p>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-inicio' id='data-inicio' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-inicio']; ?>'>&nbsp;&nbsp;</p>
		</div>
		<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-fim' id='data-fim' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $_POST['data-fim'] ?>'></p>
		</div>
	</div>
	<div class='titulo-secundario' Style='float:right; width:10%; margin-top:15px;'>
		<p align='right'><input type='button' Style='width:100%' value='Pesquisar' id='botao-pesquisar-acesso'></p>
	</div>
</div>
<?php
	if ($_POST){
		if ($usuarioID!="") $sqlCond .= " and l.Usuario_ID = '$usuarioID' ";

		if(($dataInicio!="")||($dataFim!="")){
			$dataInicio = implode('-',array_reverse(explode('/',$dataInicio))); if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
			$dataFim = implode('-',array_reverse(explode('/',$dataFim))); if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
			$sqlCond .= " and l.Data_Acesso between '$dataInicio' and '$dataFim' ";
		}

		if ($moduloPaginaID!=""){
			if ($moduloPaginaID < 0)
				$sqlCond .= " and m.Modulo_ID =".($moduloPaginaID * -1);
			else
				$sqlCond .= " and mp.Modulo_Pagina_ID = ".$moduloPaginaID;
		}


		if ($tipoRelatorio=="geral"){
			$colunas = "5";
			$sql = "select c.Nome as Usuario, m.Nome as Modulo, mp.Titulo as Pagina, l.Data_Acesso, l.IP_Acesso from log_acessos l
			inner join cadastros_dados c on c.Cadastro_ID = l.Usuario_ID
			inner join modulos_paginas mp on mp.Modulo_Pagina_ID = l.Pagina_ID
			inner join modulos m on m.Modulo_ID = l.Modulo_ID
			$sqlCond
			order by l.Data_Acesso desc";

			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Usuario']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Modulo']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Pagina']."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".converteDataHora($row['Data_Acesso'],1)."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['IP_Acesso']."</p>";
			}
			$dados[colunas][titulo][1] = "Usu&aacute;rio";
			$dados[colunas][titulo][2] = "M&oacute;dulo";
			$dados[colunas][titulo][3] = "Pagina Acessada";
			$dados[colunas][titulo][4] = "Data Acesso";
			$dados[colunas][titulo][5] = "IP Acesso";

			geraTabela("100%", $colunas, $dados, null, 'tabela-acessos', 2, 2, 100,1);

			if ($i==0){
				echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma registro encontrado</p>";
			}
		}
		if ($tipoRelatorio=="usuario-pagina"){
			$colunas = "4";
			$sql = "select c.Nome as Usuario, m.Nome as Modulo, mp.Titulo as Pagina, count(*) as QtdAcessos
			from log_acessos l
			inner join cadastros_dados c on c.Cadastro_ID = l.Usuario_ID
			inner join modulos_paginas mp on mp.Modulo_Pagina_ID = l.Pagina_ID
			inner join modulos m on m.Modulo_ID = l.Modulo_ID
			$sqlCond
			group by c.Nome, m.Nome, mp.Titulo
			order by c.Nome, m.Nome, mp.Titulo";

			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Usuario']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Modulo']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Pagina']."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;' align='center'>".$row['QtdAcessos']."</p>";
			}
			$dados[colunas][titulo][1] = "Usu&aacute;rio";
			$dados[colunas][titulo][2] = "M&oacute;dulo";
			$dados[colunas][titulo][3] = "Pagina Acessada";
			$dados[colunas][titulo][4] = "<p Style='margin:0px;' align='center'>Quantidade acessos na pagina</p>";

			geraTabela("100%", $colunas, $dados, null, 'tabela-acessos', 2, 2, 100,1);

			if ($i==0){
				echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma registro encontrado</p>";
			}
		}

/*
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
*/


	}

?>