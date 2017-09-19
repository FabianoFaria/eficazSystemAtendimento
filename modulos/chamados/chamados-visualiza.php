<?php
	global $caminhoSistema;
	$id 	= $_POST['localiza-chamado-id'];
	$codigo 	= $_POST['localiza-chamado-codigo'];
	$titulo 	= $_POST['localiza-chamado-titulo'];
	$nomeP 	= $_POST['localiza-chamado-prestador'];
	$nomeS 	= $_POST['localiza-chamado-solicitante'];
	$email = $_POST['localiza-chamado-email'];
	$situacao = $_POST['localiza-chamado-situacao'];
	$prioridade = $_POST['localiza-chamado-prioridade'];
	$tipoChamado = $_POST['localiza-tipo-chamado'];
	$ufSolicitante = $_POST['localiza-chamado-uf-solicitante'];
	$dataInicio = $_POST['data-inicio-abertura'];
	$dataFim = $_POST['data-fim-abertura'];
	$dataInicioFinalizado = $_POST['data-inicio-finalizado'];
	$dataFimFinalizado = $_POST['data-fim-finalizado'];

?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Localizar Chamados
		<input type="button" value="Novo Chamado" class='workflow-localiza' name='' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
		</p>
	</div>

	<input type='hidden' name='workflow-id' id='workflow-id'>
	<div class="conteudo-interno">
		<div class="titulo-secundario cinco-colunas">
			<div class="titulo-secundario" style='width:22%; float:left;'>
				<p>ID</p>
				<p><input type='text' name='localiza-chamado-id' id='localiza-chamado-id' class='formata-numero' value='<?php echo $id; ?>' style='width:80%;'></p>
			</div>
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>C&oacute;digo Chamado</p>
				<p><input type='text' name='localiza-chamado-codigo' id='localiza-chamado-codigo' class='formata-campo' value='<?php echo $codigo; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>Solicitante:</p>
				<p><input type='text' name='localiza-chamado-solicitante' id='localiza-chamado-solicitante' class='formata-campo' value='<?php echo $nomeS; ?>'></p>
			</div>
			<div class="titulo-secundario" style='width:22%; float:left;'>
				<p>UF:</p>
				<p><select name="localiza-chamado-uf-solicitante" id="localiza-chamado-uf-solicitante"><?php echo optionValueGrupoUF($ufSolicitante, "&nbsp;");?><select></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Situa&ccedil;&atilde;o:</p>
			<select name="localiza-chamado-situacao" id="localiza-chamado-situacao"><?php echo optionValueGrupo(18, $situacao, "Todos");?><select>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Tipo Chamado</p>
			<p><select name="localiza-tipo-chamado" id="localiza-tipo-chamado"><?php echo optionValueGrupo(19, $tipoChamado, "Todos");?><select></p>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Prioridade:</p>
			<select name="localiza-chamado-prioridade" id="localiza-chamado-prioridade"><?php echo optionValueGrupo(21, $prioridade, "Todos");?><select>
		</div>
		<div class="titulo-secundario cinco-colunas">&nbsp;</div>
		<div class="titulo-secundario cinco-colunas">
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>Prestador:</p>
				<p><input type='text' name='localiza-chamado-prestador' id='localiza-chamado-prestador' class='formata-campo' value='<?php echo $nomeP; ?>'></p>
			</div>
			<div class="titulo-secundario" style='width:22%; float:left;'>
				<!--
				<p>UF:</p>
				<p><select name="localiza-chamado-uf-prestador" id="localiza-chamado-uf-prestador"><?php echo optionValueGrupoUF($ufPrestador, "&nbsp;");?><select></p>
				-->
			</div>
		</div>
		<!--
		<div class="titulo-secundario cinco-colunas">&nbsp;</div>
		<div class="titulo-secundario cinco-colunas">&nbsp;</div>
		-->
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
		<div class="titulo-secundario cinco-colunas">
			<p>Data Finalizado:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-finalizado' id='data-inicio-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioFinalizado; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-finalizado' id='data-fim-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimFinalizado; ?>'></p>
			</div>
		</div>
		<div class='titulo-secundario cinco-colunas' Style='margin-top:15px; float:left;'>
			<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-localizar-chamado' workflow-id=''></p>
		</div>
	</div>
</div>
<?php
if($_POST){
	$i = 0;
	if($id!="") 		$condicoes .= " and cw.Workflow_ID = '$id' ";
	if($codigo!="") 	$condicoes .= " and cw.Codigo like '$codigo%' ";
	if($nomeP!="") 		$condicoes .= " and cd2.Nome like '%$nomeP%' ";
	if($nomeS!="") 		$condicoes .= " and cd1.Nome  like '%$nomeS%'";
	if($email!="") 		$condicoes .= " and (cd1.email like '$email%' or cd2.email like '$email%' )";
	if($situacao!="") 	$condicoes .= " and cf.Situacao_ID = '$situacao'";
	if($prioridade!="") $condicoes .= " and cw.Prioridade_ID = '$prioridade'";

	if($titulo!="")		$condicoes .= " and upper(cw.Titulo) like upper('$titulo%')";
	if($tipoChamado!="") $condicoes .= " and tw.Tipo_ID = '$tipoChamado'";
	if($ufSolicitante!="") $condicoes .= " and cd1.Cadastro_ID IN (select Cadastro_ID from cadastros_enderecos where UF = '$ufSolicitante' )";

	if(($dataInicio!="")||($dataFim!="")){
		$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
		if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
		$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
		if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
		$condicoes .= " and cw.Data_Abertura between '$dataInicio' and '$dataFim' ";
	}
	if(($dataInicioFinalizado!="")||($dataFimFinalizado!="")){
		$dataInicioFinalizado = implode('-',array_reverse(explode('/',$dataInicioFinalizado)));
		if ($dataInicioFinalizado=="") $dataInicioFinalizado = "0000-00-00"; $dataInicioFinalizado .= " 00:00";
		$dataFimFinalizado = implode('-',array_reverse(explode('/',$dataFimFinalizado)));
		if ($dataFimFinalizado=="") $dataFimFinalizado = "2100-01-01"; $dataFimFinalizado .= " 23:59";
		$condicoes .= " and cw.Data_Finalizado between '$dataInicioFinalizado' and '$dataFimFinalizado' and cf.Situacao_ID = '34'";
		//$condicoes .= " and cf.Data_Cadastro between '$dataInicioFinalizado' and '$dataFimFinalizado' and cf.Situacao_ID = '34'";
	}
	// %H:%i
	//CASE cf.Situacao_ID when 34 THEN DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') else '' end as Data_Finalizado,

	$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, tw.Descr_Tipo as Tipo_Chamado, cd1.Nome as Solicitante, cd1.email as Email_Solicitante, cd2.Nome as Prestador,
					cd2.email as Email_Prestador, t.Descr_Tipo as Situacao,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
					DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
					(select count(*) from modulos_anexos where Chave_Estrangeira = cw.Workflow_ID and Tabela_Estrangeira = 'chamados') as arquivos
			from chamados_workflows cw
			left join tipo tw on tw.Tipo_ID = cw.Tipo_WorkFlow_ID
			left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
			left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
			left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
				and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
			left join tipo t on t.Tipo_ID = cf.Situacao_ID
			where cw.Workflow_ID is not null
				$condicoes
			order by cw.Workflow_ID, cf.Follow_ID";

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$solicitante = $row[Solicitante];
		if ($row[Email_Solicitante]!=""){ $solicitante .= "&nbsp;(".$row[Email_Solicitante].")";}
		$prestador = $row[Prestador];
		if ($row[Email_Prestador]!=""){ $prestador .= "&nbsp;(".$row[Email_Prestador].")";}

		$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-vazia.png' alt='Nenhum Arquivo Anexado'/>";
		if ($row[arquivos]>0){
			$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-cheia.png' alt='(".$row[arquivos].") Arquivo(s) Anexado(s)'/>";
		}

		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Workflow_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Tipo_Chamado]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$solicitante."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$prestador."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Finalizado]."</p>";
		$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>$arquivos</p>";
		$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:center;' class='link workflow-localiza' id='cadastro-localiza-".$row[Workflow_ID]."' workflow-id='".$row[Workflow_ID]."'>visualizar</p>";
	}
	$largura = "100%";
	$colunas = "10";
	$dados[colunas][titulo][1] 	= "ID";
	$dados[colunas][titulo][2] 	= "Chamado";
	$dados[colunas][titulo][3] 	= "Tipo";
	$dados[colunas][titulo][4] 	= "Solicitante";
	$dados[colunas][titulo][5] 	= "Prestador";
	$dados[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][7] 	= "Abertura";
	$dados[colunas][titulo][8] 	= "Finalizado";
	$dados[colunas][titulo][9] 	= "";
	$dados[colunas][titulo][10] 	= "";

	$dados[colunas][tamanho][1] = "width='40px'";
	$dados[colunas][tamanho][2] = "width='90px'";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][4] = "";
	$dados[colunas][tamanho][5] = "";
	$dados[colunas][tamanho][6] = "width='150px'";
	$dados[colunas][tamanho][7] = "width='110px'";
	$dados[colunas][tamanho][8] = "width='110px'";
	$dados[colunas][tamanho][9] = "width='20px'";
	$dados[colunas][tamanho][10] = "width='50px'";

	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Chamados Localizados: $i</p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";

	geraTabela($largura,$colunas,$dados);
	echo "		</div>
			</div>";
}
?>