<?php
	include("functions.php");
	global $caminhoSistema;
	if($_POST){
		$id 					= $_POST['localiza-orcamento-id'];
		$codigo 				= $_POST['localiza-orcamento-codigo'];
		$titulo 				= $_POST['localiza-orcamento-titulo'];
		$nomeS 					= $_POST['localiza-orcamento-solicitante'];
		$email 					= $_POST['localiza-orcamento-email'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-orcamento-situacao']); $i++){
			$situacoes .= $virgula.$_POST['localiza-orcamento-situacao'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-proposta-situacao']); $i++){
			$situacoesProposta .= $virgula.$_POST['localiza-proposta-situacao'][$i];
			$virgula = ",";
		}


		$ufSolicitante 			= $_POST['localiza-orcamento-uf-solicitante'];
		$dataInicioAbertura 	= $_POST['data-inicio-abertura'];
		$dataFimAbertura		= $_POST['data-fim-abertura'];
		$dataInicioFinalizado 	= $_POST['data-inicio-finalizado'];
		$dataFimFinalizado 		= $_POST['data-fim-finalizado'];
		$dataInicioInteracao 	= $_POST['data-inicio-interacao'];
		$dataFimInteracao 		= $_POST['data-fim-interacao'];
		$dataInicioLimite 		= $_POST['data-inicio-limite'];
		$dataFimLimite 			= $_POST['data-fim-limite'];
		$usuarioResponsavel		= $_POST['localiza-orcamento-responsavel'];
		$titulo					= $_POST['localiza-orcamento-titulo'];
		if ($_POST['exibir-propostas']) $chkExibirPropostas = " checked ";
	}
	else{
		$_POST['exibir-propostas'] = 1;
		$chkExibirPropostas = " checked ";
	}

?>
<div class="titulo-container">
	<div class="titulo">
		<p>
		Filtros de Pesquisa
<?php	if($_SESSION[dadosUserLogin][grupoID] != -3){?>
			<input type="button" value="Incluir Novo" class='orcamento-localiza' workflow-id=''>
<?php	}?>
		</p>
	</div>

	<input type='hidden' name='workflow-id' id='workflow-id'>
	<div class="conteudo-interno">
		<div class="titulo-secundario" style='width:7%; float:left;'>
			<p>ID</p>
			<p><input type='text' name='localiza-orcamento-id' id='localiza-orcamento-id' class='formata-numero' value='<?php echo $id; ?>' style='width:80%;'></p>
		</div>
		<div class="titulo-secundario" style='width:13%; float:left;'>
			<p>C&oacute;digo</p>
			<p><input type='text' name='localiza-orcamento-codigo' id='localiza-orcamento-codigo' class='formata-campo' value='<?php echo $codigo; ?>' style='width:90%;'></p>
		</div>
		<div class="titulo-secundario" style='width:35%; float:left;'>
			<p>Solicitante</p>
			<p><input type='text' name='localiza-orcamento-solicitante' id='localiza-orcamento-solicitante' class='formata-campo' value='<?php echo $nomeS; ?>' style='width:97%;'></p>
		</div>
		<div class='titulo-secundario' style='width:5%; float:left;'>
			<div style='width:90%; float:left;'>
				<p>UF</p>
				<p><select name="localiza-orcamento-uf-solicitante" id="localiza-orcamento-uf-solicitante" style='width:95%;'><?php echo optionValueGrupoUF($ufSolicitante, "&nbsp;");?><select></p>
			</div>
		</div>
		<div class="titulo-secundario" style='width:20%; float:left;'>
			<p>Representante</p>
			<select name="localiza-orcamento-responsavel" id="localiza-orcamento-responsavel" style='width:98%'>
				<option value=''></option>
<?php
	$grupos = mpress_query("select distinct c.Cadastro_ID, c.Nome
							from cadastros_dados c
							inner join orcamentos_workflows w on w.Representante_ID = c.Cadastro_ID");
	while($row = mpress_fetch_array($grupos)){
		if($usuarioResponsavel==$row['Cadastro_ID']) $selecionado = "selected"; else $selecionado = "";
		echo " 	<option value='".$row['Cadastro_ID']."' $selecionado>".$row['Nome']."</option>";
	}
?>
			<select>
		</div>
		<div class='titulo-secundario' style='width:20%; float:left;'>
			<p>T&iacute;tulo</p>
			<p><input type='text' name='localiza-orcamento-titulo' id='localiza-orcamento-titulo' class='formata-campo' value='<?php echo $titulo; ?>' style='width:97%;'></p>
		</div>
		<div class='titulo-secundario' style='width:20%; float:left;'>
			<p>Data Abertura</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioAbertura; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimAbertura; ?>'></p>
			</div>
		</div>
		<div class='titulo-secundario' style='width:20%; float:left;'>
			<p>Data Finalizado</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-finalizado' id='data-inicio-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioFinalizado; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-finalizado' id='data-fim-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimFinalizado; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario" style='width:20%; float:left;'>
			<p>&Uacute;ltima Intera&ccedil;&atilde;o</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-interacao' id='data-inicio-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioInteracao; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-interacao' id='data-fim-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimInteracao; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario" style='width:20%; float:left;'>
			<p>Situa&ccedil;&atilde;o Or&ccedil;amento</p>
			<select name="localiza-orcamento-situacao[]" id="localiza-orcamento-situacao" multiple><?php echo optionValueGrupoMultiplo(51, $situacoes);?><select>
		</div>
		<div class="titulo-secundario" style='width:20%; float:left;'>
			<p>Situa&ccedil;&atilde;o Proposta</p>
			<select name="localiza-proposta-situacao[]" id="localiza-proposta-situacao" multiple><?php echo optionValueGrupoMultiplo(53, $situacoesProposta);?><select>
		</div>
		<div class='titulo-secundario' Style='margin-top:15px; width:10%; float:right;'>
			<p><input type='button' Style='width:90%; float:right;' value='Pesquisar' id='botao-localizar-orcamento' workflow-id=''></p>
		</div>
		<div class='titulo-secundario' style='float:right; width:10%;'>
			<p style='margin:15px 0 10px 0;' align='center'><input type='checkbox' id='exibir-propostas' name='exibir-propostas' <?php echo $chkExibirPropostas; ?>/><label for='exibir-propostas'>Exibir Propostas</label></p>
		</div>

	</div>
</div>
<?php
//if($_POST){
	$i = 0;
	if($id!="") 				$sqlCond .= " and o.Workflow_ID = '$id' ";
	if($codigo!="") 			$sqlCond .= " and o.Codigo like '$codigo%' ";
	if($nomeS!="") 				$sqlCond .= " and (s.Nome like '%$nomeS%' or s.Nome_Fantasia like '%$nomeS%')";
	if($ufSolicitante!="") 		$sqlCond .= " and s.Cadastro_ID IN (select Cadastro_ID from cadastros_enderecos where UF = '$ufSolicitante' )";
	if($usuarioResponsavel!="") $sqlCond .= " and o.Representante_ID = '$usuarioResponsavel'";
	if($titulo!="")				$sqlCond .= " and upper(o.Titulo) like upper('$titulo%')";
	if($situacoes!="") 			$sqlCond .= " and o.Situacao_ID in ($situacoes)";
	if($situacoesProposta!="") 	$sqlCond .= " and op.Status_ID in ($situacoesProposta)";

	if(($dataInicioAbertura!="")||($dataFimAbertura!="")){
		$dataInicioAbertura = implode('-',array_reverse(explode('/',$dataInicioAbertura)));
		if ($dataInicioAbertura=="") $dataInicioAbertura = "0000-00-00"; $dataInicioAbertura .= " 00:00";
		$dataFimAbertura = implode('-',array_reverse(explode('/',$dataFimAbertura)));
		if ($dataFimAbertura=="") $dataFimAbertura = "2100-01-01"; $dataFimAbertura .= " 23:59";
		$sqlCond .= " and o.Data_Abertura between '$dataInicioAbertura' and '$dataFimAbertura' ";
	}

	if(($dataInicioFinalizado!="")||($dataFimFinalizado!="")){
		$dataInicioFinalizado = implode('-',array_reverse(explode('/',$dataInicioFinalizado)));
		if ($dataInicioFinalizado=="") $dataInicioFinalizado = "0000-00-00"; $dataInicioFinalizado .= " 00:00";
		$dataFimFinalizado = implode('-',array_reverse(explode('/',$dataFimFinalizado)));
		if ($dataFimFinalizado=="") $dataFimFinalizado = "2100-01-01"; $dataFimFinalizado .= " 23:59";
		$sqlCond .= " and o.Data_Finalizado between '$dataInicioFinalizado' and '$dataFimFinalizado' and of.Situacao_ID = '34'";
	}
	if(($dataInicioInteracao!="")||($dataFimIneracao!="")){
		$dataInicioInteracao = implode('-',array_reverse(explode('/',$dataInicioInteracao)));
		if ($dataInicioInteracao=="") $dataInicioInteracao = "0000-00-00"; $dataInicioInteracao .= " 00:00";
		$dataFimInteracao = implode('-',array_reverse(explode('/',$dataFimInteracao)));
		if ($dataFimInteracao=="") $dataFimInteracao = "2100-01-01"; $dataFimInteracao .= " 23:59";
		$sqlCond .= " and of.Data_Cadastro between '$dataInicioInteracao' and '$dataFimInteracao'";
	}


	if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= " and o.Representante_ID = ".$_SESSION[dadosUserLogin][userID]." ";
	//if($_SESSION[dadosUserLogin][grupoID] == -3) $sqlCond .= "and cw.Solicitante_ID = ".$_SESSION[dadosUserLogin][userID];

	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}
	else{
		$ordem = " order by o.Workflow_ID desc";
	}

	if ($_POST['exibir-propostas']){
		$sqlCampos = ", op.Titulo as Titulo_Proposta, tp.Descr_Tipo as Situacao_Proposta, tp.Tipo_Auxiliar as Situacao_Proposta_Dados,
						op.Proposta_ID as Proposta_ID, SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor ";
		$sqlLeftJoin = "	left join orcamentos_propostas op on op.Workflow_ID = o.Workflow_ID and op.Situacao_ID = 1
			 		  		left join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
			 		  		left join tipo tp on tp.Tipo_ID =  op.Status_ID ";

		$sqlGroupBy = " GROUP BY o.Workflow_ID, op.Proposta_ID";
		$classeDestaque = "tabela-fundo-escuro";
	}


	$sql = "select o.Workflow_ID, o.Codigo, o.Titulo, s.Nome as Solicitante, ts.Descr_Tipo as Situacao, o.Data_Abertura,
				o.Data_Finalizado, of.Data_Cadastro as Data_Interacao, r.Nome as Representante, ts.Tipo_Auxiliar as Situacao_Orcamento_Dados
				$sqlCampos
				from orcamentos_workflows o
				inner join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
				left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
				left join tipo ts on ts.Tipo_ID = o.Situacao_ID
				left join orcamentos_follows of on o.Workflow_ID = of.Workflow_ID and of.Follow_ID = (select max(ofaux.Follow_ID) from orcamentos_follows ofaux where ofaux.Workflow_ID = o.Workflow_ID)
				$sqlLeftJoin
				where o.Workflow_ID > 0
				$sqlCond
				$sqlGroupBy
				$ordem";
	//echo $sql;

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($row[Workflow_ID]!=$workFlowIDAnt){
			$i++;
			$situacaoOrcamentoDados = unserialize($row['Situacao_Orcamento_Dados']);
			$style = "";
			if ($situacaoOrcamentoDados['cor-fundo']!='') $style .= "background-color: ".$situacaoOrcamentoDados['cor-fundo'].";";
			if ($situacaoOrcamentoDados['cor-texto']!='') $style .= "color: ".$situacaoOrcamentoDados['cor-texto'].";";

			$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='orcamento-localiza lnk' workflow-id='".$row[Workflow_ID]."'";
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Workflow_ID]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Solicitante]."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Titulo]."</p>";
			$dados[colunas][extras][$i][5] = " style='".$style."'";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Abertura]),0,10)."</p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Interacao]),0,10)."</p>";
			$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Finalizado]),0,10)."</p>";
			$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Representante]."</p>";
			$dados[colunas][classe][$i] = $classeDestaque;
		}
		if (($_POST['exibir-propostas']) && ($row['Proposta_ID']!="")){
			$i++;
			$situacaoPropostaDados = unserialize($row['Situacao_Proposta_Dados']);
			$style = "";
			if ($situacaoPropostaDados['cor-fundo']!='') $style .= "background-color: ".$situacaoPropostaDados['cor-fundo'].";";
			if ($situacaoPropostaDados['cor-texto']!='') $style .= "color: ".$situacaoPropostaDados['cor-texto'].";";


			$dados[colunas][colspan][$i][3] = "2";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 30px;float:left;'>".$row[Titulo_Proposta]."</p>";
			$dados[colunas][extras][$i][5] = " style='".$style."'";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao_Proposta]."</p>";
			//$dados[colunas][colspan][$i][5] = "4";
			$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:right;'>R$ ".number_format($row[Valor], 2, ',', '.')."</p>";

			$dados[colunas][classe][$i] = "tabela-fundo-claro";
		}

		$workFlowIDAnt = $row[Workflow_ID];
	}
	$largura = "100%";
	$colunas = "9";
	$dados[colunas][titulo][1] 	= "ID <!--Or&ccedil;amento-->";
	$dados[colunas][titulo][2] 	= "C&oacute;digo";
	$dados[colunas][titulo][3] 	= "Solicitante";
	$dados[colunas][titulo][4] 	= "Título";
	$dados[colunas][titulo][5] 	= "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][6] 	= "Abertura";
	$dados[colunas][titulo][7]	= "Intera&ccedil;&atilde;o";
	$dados[colunas][titulo][8]	= "Finalizado";
	$dados[colunas][titulo][9]	= "Representante";

	$dados[colunas][ordena][1] = "o.Workflow_ID";
	$dados[colunas][ordena][2] = "o.Codigo";
	$dados[colunas][ordena][3] = "s.Nome";
	$dados[colunas][ordena][4] = "o.Titulo";
	$dados[colunas][ordena][5] = "ts.Descr_Tipo";
	$dados[colunas][ordena][6] = "o.Data_Abertura";
	$dados[colunas][ordena][7] = "of.Data_Cadastro";
	$dados[colunas][ordena][8] = "o.Data_Finalizado";
	$dados[colunas][ordena][9] = "r.Nome";


	$dados[colunas][tamanho][1] = "width='40px'";
	$dados[colunas][tamanho][2] = "width='90px'";
	$dados[colunas][tamanho][6] = "width='70px'";
	$dados[colunas][tamanho][7] = "width='70px'";
	$dados[colunas][tamanho][8] = "width='70px'";

	echo " <div class='titulo-container' id='localiza-orcamento-retorno'>
			<div class='titulo'>
				<p>Registros Localizados: $i</p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";

	geraTabela($largura,$colunas,$dados, null, 'chamados-localiza', 2, 2, 100,1);
	echo "		</div>
			</div>";


//}
?>