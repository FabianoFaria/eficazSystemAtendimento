<?php
	include("functions.php");
	global $caminhoSistema, $dadosUserLogin;
	if($_POST){
		$id 					= $_POST['localiza-chamado-id'];
		$codigo 				= $_POST['localiza-chamado-codigo'];
		$titulo 				= $_POST['localiza-chamado-titulo'];
		$nomeP 					= $_POST['localiza-chamado-prestador'];
		$nomeS 					= $_POST['localiza-chamado-solicitante'];
		$email 					= $_POST['localiza-chamado-email'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-chamado-situacao']); $i++){
			$situacoes .= $virgula.$_POST['localiza-chamado-situacao'][$i];
			$virgula = ",";
		}

		$prioridade 			= $_POST['localiza-chamado-prioridade'];
		$tipoChamado 			= $_POST['localiza-tipo-chamado'];
		$ufSolicitante 			= $_POST['localiza-chamado-uf-solicitante'];
		$dataInicio 			= $_POST['data-inicio-abertura'];
		$dataFim 				= $_POST['data-fim-abertura'];
		$dataInicioFinalizado 	= $_POST['data-inicio-finalizado'];
		$dataFimFinalizado 		= $_POST['data-fim-finalizado'];
		$dataInicioInteracao 	= $_POST['data-inicio-interacao'];
		$dataFimInteracao 		= $_POST['data-fim-interacao'];
		$dataInicioLimite 		= $_POST['data-inicio-limite'];
		$dataFimLimite 			= $_POST['data-fim-limite'];
		$usuarioResponsavel		= $_POST['localiza-chamado-responsavel'];
		$grupoResponsavel 		= $_POST['localiza-chamado-grupo-responsavel'];
		$titulo					= $_POST['localiza-chamado-titulo'];

		$arrTiposChamados = carregarArrayTipo(19);
	}
	else{
/*
		$virgula = ",";
		$rs = mpress_query("select Tipo_ID from tipo where Tipo_ID not in (33,34) and Tipo_Grupo_ID = 18 and Situacao_ID = 1");
		while($row = mpress_fetch_array($rs)){
			$situacoes .= $virgula.$row['Tipo_ID'];
			$virgula = ",";
		}
*/
	}

?>
<div class="titulo-container">
	<div class="titulo">
		<p>
		Filtros de pesquisa
<?php	if($dadosUserLogin[grupoID] != -3) echo "<input type='button' value='Incluir ".$_SESSION['objeto']."' class='workflow-localiza' workflow-id=''>"; ?>
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
				<p>C&oacute;digo <?php echo $_SESSION['objeto'];?></p>
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
<?php	if($dadosUserLogin[grupoID] == -3) $classeEsconde = "esconde"; ?>
		<div class="titulo-secundario cinco-colunas <?php echo $classeEsconde; ?>">
			<p>Prestador:</p>
			<p><input type='text' name='localiza-chamado-prestador' id='localiza-chamado-prestador' class='formata-campo' value='<?php echo $nomeP; ?>'></p>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Tipo <?php echo $_SESSION['objeto'];?></p>
			<p><select name="localiza-tipo-chamado" id="localiza-tipo-chamado"><?php echo optionValueGrupoFilho(19, $tipoChamado, "&nbsp;");?><select></p>
		</div>
		<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:2;'>
			<p>Situa&ccedil;&atilde;o:</p>
			<select name="localiza-chamado-situacao[]" multiple id="localiza-chamado-situacao" style='height:71px;'><?php echo optionValueGrupoMultiplo(18, $situacoes);?><select>
		</div>

		<div class="titulo-secundario cinco-colunas" id='data-abertura'>
			<p>Data Abertura:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicio; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFim; ?>'></p>
			</div>
		</div>

		<div class="titulo-secundario cinco-colunas" id='data-abertura'>
			<p>Data Limite:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-limite' id='data-inicio-limite' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioLimite; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-limite' id='data-fim-limite' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimLimite; ?>'></p>
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

		<div class="titulo-secundario cinco-colunas">
			<p>&Uacute;ltima Intera&ccedil;&atilde;o:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-interacao' id='data-inicio-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioInteracao; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-interacao' id='data-fim-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimInteracao; ?>'></p>
			</div>
		</div>

		<div class="titulo-secundario cinco-colunas" id='data-abertura'>&nbsp;</div>

		<div class="titulo-secundario cinco-colunas">
			<p>Prioridade:</p>
			<select name="localiza-chamado-prioridade" id="localiza-chamado-prioridade">
				<option value=''></option>
				<?php echo optionValuePrioridade($prioridade);?>
			<select>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>T&iacute;tulo:</p>
			<p><input type='text' name='localiza-chamado-titulo' id='localiza-chamado-titulo' class='formata-campo' value='<?php echo $titulo; ?>'></p>
		</div>


		<div class="titulo-secundario cinco-colunas">
			<p>Grupo Respons&aacute;vel:</p>
			<select name="localiza-chamado-grupo-responsavel" id="localiza-chamado-grupo-responsavel" >
				<option value=''></option>
<?php
	$grupos = mpress_query("select distinct m.Modulo_Acesso_ID, m.Titulo from modulos_acessos m
							inner join chamados_workflows w on w.Grupo_Responsavel_ID = m.Modulo_Acesso_ID
							where m.Situacao_ID = 1 order by m.Titulo");
	while($row = mpress_fetch_array($grupos)){
		if($grupoResponsavel==$row['Modulo_Acesso_ID']) $selecionado = "selected"; else $selecionado = "";
		echo " 	<option value='".$row['Modulo_Acesso_ID']."' $selecionado>".$row['Titulo']."</option>";
	}
?>
			<select>
		</div>

		<div class="titulo-secundario cinco-colunas">
			<p>Respons&aacute;vel:</p>
			<select name="localiza-chamado-responsavel" id="localiza-chamado-responsavel" >
				<option value=''></option>
<?php
	$grupos = mpress_query("select distinct c.Cadastro_ID, c.Nome
							from cadastros_dados c
							inner join chamados_workflows w on w.Responsavel_ID = c.Cadastro_ID");
	while($row = mpress_fetch_array($grupos)){
		if($usuarioResponsavel==$row['Cadastro_ID']) $selecionado = "selected"; else $selecionado = "";
		echo " 	<option value='".$row['Cadastro_ID']."' $selecionado>".$row['Nome']."</option>";
	}
?>
			<select>
		</div>

		<div class='titulo-secundario' Style='margin-top:15px;float:right; width:10%'>
			<p class='direita'><input type='button' Style='width:90%;float:right;' value='Pesquisar' id='botao-localizar-chamado'></p>
		</div>
	</div>
</div>
<?php
//if($_POST){
	$i = 0;
	if($id!="") 				$condicoes .= " and cw.Workflow_ID = '$id' ";
	if($codigo!="") 			$condicoes .= " and cw.Codigo like '$codigo%' ";
	if($nomeP!="") 				$condicoes .= " and (cd2.Nome like '%$nomeP%' or cd2.Nome_Fantasia like '%$nomeP%') ";
	if($nomeS!="") 				$condicoes .= " and (cd1.Nome like '%$nomeS%' or cd1.Nome_Fantasia like '%$nomeS%')";
	if($email!="") 				$condicoes .= " and (cd1.email like '$email%' or cd2.email like '$email%' )";
	if($situacoes!="") 			$condicoes .= " and cf.Situacao_ID in ($situacoes)";
	if($prioridade!="") 		$condicoes .= " and cw.Prioridade_ID = '$prioridade'";
	if($usuarioResponsavel!="") $condicoes .= " and cw.Responsavel_ID = '$usuarioResponsavel'";
	if($grupoResponsavel!="") 	$condicoes .= " and cw.Grupo_Responsavel_ID = '$grupoResponsavel'";
	if($titulo!="")				$condicoes .= " and upper(cw.Titulo) like upper('$titulo%')";

	if($dadosUserLogin[grupoID] == -2) $condicoes .= "and ((cd1.cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos where cadastro_id = ".$_SESSION[dadosUserLogin][userID].") or cd1.cadastro_ID = ".$_SESSION[dadosUserLogin][userID]."
																   or (cd2.cadastro_ID = ".$_SESSION[dadosUserLogin][userID].")))";
	if($dadosUserLogin[grupoID] == -3) $condicoes .= "and cw.Solicitante_ID = ".$_SESSION[dadosUserLogin][userID];
	if($tipoChamado!="") $condicoes .= " and cw.Tipo_WorkFlow_ID in (".$arrTiposChamados[familia][$tipoChamado].")";

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
	}
	if(($dataInicioInteracao!="")||($dataFimIneracao!="")){
		$dataInicioInteracao = implode('-',array_reverse(explode('/',$dataInicioInteracao)));
		if ($dataInicioInteracao=="") $dataInicioInteracao = "0000-00-00"; $dataInicioInteracao .= " 00:00";
		$dataFimInteracao = implode('-',array_reverse(explode('/',$dataFimInteracao)));
		if ($dataFimInteracao=="") $dataFimInteracao = "2100-01-01"; $dataFimInteracao .= " 23:59";
		$condicoes .= " and cf.Data_Cadastro between '$dataInicioInteracao' and '$dataFimInteracao'";
	}

	if(($dataInicioLimite!="")||($dataFimLimite!="")){
		$dataInicioLimite = implode('-',array_reverse(explode('/',$dataInicioLimite)));
		if ($dataInicioLimite=="") $dataInicioLimite = "0000-00-00"; $dataInicioLimite .= " 00:00";
		$dataFimLimite = implode('-',array_reverse(explode('/',$dataFimLimite)));
		if ($dataFimLimite=="") $dataFimLimite = "2100-01-01"; $dataFimLimite .= " 23:59";
		$condicoes .= " and cw.Data_Limite between '$dataInicioLimite' and '$dataFimLimite'";
	}


	// %H:%i

	$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, cw.Tipo_WorkFlow_ID, cw.Titulo,
					cd1.Nome as Solicitante, cd1.Nome_Fantasia as Solicitante_Fantasia, cd1.email as Email_Solicitante,
					cd2.Nome as Prestador, cd2.Nome_Fantasia as Prestador_Fantasia, cd2.email as Email_Prestador,
					t.Descr_Tipo as Situacao, cf.Situacao_ID as Situacao_ID,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y %H:%i') as Data_Hora_Abertura,
					DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
					DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
					DATE_FORMAT(cw.Data_Limite,'%d/%m/%Y') as Data_Limite,
					(select count(*) from modulos_anexos a where a.Chave_Estrangeira = cw.Workflow_ID and a.Tabela_Estrangeira = 'chamados' and a.Situacao_ID = 1) as Arquivos,
					/*(select count(*) from chamados_workflows_tarefas cwt where cwt.Workflow_ID = cw.Workflow_ID) as Tarefas,*/
					p.Descr_Tipo as Prioridade, p.Tipo_Auxiliar, r.Nome as Responsavel, g.Titulo as Grupo_Responsavel
			from chamados_workflows cw
			inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
			inner join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
				and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
			left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
			left join tipo t on t.Tipo_ID = cf.Situacao_ID
			left join tipo p on p.Tipo_ID = cw.Prioridade_ID
			left join cadastros_dados r on r.Cadastro_ID = cw.Responsavel_ID
			left join modulos_acessos g on g.Modulo_Acesso_ID = cw.Grupo_Responsavel_ID
			where cw.Workflow_ID > 0
				$condicoes
			order by cw.Workflow_ID desc";
	//echo $sql;
	//exit();

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$solicitante = $row[Solicitante];
		$arrayPrioridades = unserialize($row[Tipo_Auxiliar]);
		$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-vazia.png' title='Nenhum Arquivo Anexado'/>";
		if ($row[Arquivos]>0)
			$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-cheia.png' title='".$row[Arquivos]." arquivo(s) anexado(s)'/>";
		$tarefas = "";
		if ($row[Tarefas]>0)
			$tarefas = "<span class='link exibir-tarefas' workflow-id='".$row[Workflow_ID]."'>$row[Tarefas]</span>";

		$dataLimite = "";
		if (($row[Data_Limite]!="")&&(substr($row[Data_Limite],0,10)!="00/00/0000")){
			if ($row[Responsavel]!="") $traco = " - "; else $traco = "";
			$dataLimite = "<span title='Responsável: ".$row[Responsavel].$traco.$row[Grupo_Responsavel]."' style='cursor:pointer;'>".$row[Data_Limite]."</span>";
		}
		$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='workflow-localiza lnk' workflow-id='".$row[Workflow_ID]."'";
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Workflow_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$arrTiposChamados[descricao][$row[Tipo_WorkFlow_ID]]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Titulo]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;' title='".$row[Solicitante_Fantasia]."'>".$solicitante."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Interacao]."</p>";
		$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;' align='center'>".$row[Prioridade]."</p>";
		$dados[colunas][extras][$i][9] = " style='color:".$arrayPrioridades['cor-texto']."; background-color:".$arrayPrioridades['cor-fundo']."'";
		$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$dataLimite."</p>";
		$dados[colunas][conteudo][$i][11] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Finalizado]."</p>";
		$dados[colunas][conteudo][$i][12] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>$arquivos</p>";
		$dados[colunas][conteudo][$i][13] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Responsavel']."</p>";

	}
	$largura = "100%";
	$colunas = "13";
	$dados[colunas][titulo][1] 	= "ID";
	$dados[colunas][titulo][2] 	= $_SESSION['objeto'];
	$dados[colunas][titulo][3] 	= "Tipo";
	$dados[colunas][titulo][4] 	= "T&iacute;tulo";
	$dados[colunas][titulo][5] 	= "Solicitante";
	$dados[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][7] 	= "Abertura";
	$dados[colunas][titulo][8]	= "Intera&ccedil;&atilde;o";
	$dados[colunas][titulo][9] 	= "Prioridade";
	$dados[colunas][titulo][10] = "Data Limite";
	$dados[colunas][titulo][11]	= "Finalizado";
	$dados[colunas][titulo][13]	= "Respons&aacute;vel";

	$dados[colunas][tamanho][1] = "width='40px'";
	$dados[colunas][tamanho][2] = "width='90px'";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][4] = "";
	$dados[colunas][tamanho][5] = "";
	$dados[colunas][tamanho][6] = "";
	$dados[colunas][tamanho][7] = "width='70px'";
	$dados[colunas][tamanho][8] = "width='70px'";
	$dados[colunas][tamanho][9] = "";
	$dados[colunas][tamanho][10] = "width='70px'";
	$dados[colunas][tamanho][11] = "width='70px'";
	$dados[colunas][tamanho][12] = "width='20px'";

	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Registros Localizados: $i</p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";

	geraTabela($largura,$colunas,$dados, null, 'cahamados-localiza', 2, 2, 100,1);
	echo "		</div>
			</div>";
//}
?>