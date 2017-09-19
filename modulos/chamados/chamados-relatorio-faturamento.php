<?php
	session_start();
	global $caminhoSistema;

	if ($_POST){
		$id 	= $_POST['localiza-chamado-id'];
		$codigo 	= $_POST['localiza-chamado-codigo'];
		$titulo 	= $_POST['localiza-chamado-titulo'];
		$nomeP 	= $_POST['localiza-chamado-prestador'];
		$nomeS 	= $_POST['localiza-chamado-solicitante'];
		$email = $_POST['localiza-chamado-email'];
		$exibirFaturaveis = $_POST['exibir-faturaveis'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-chamado-situacao']); $i++){
			$situacoes .= $virgula.$_POST['localiza-chamado-situacao'][$i];
			$virgula = ",";
		}

		$prioridade = $_POST['localiza-chamado-prioridade'];
		$tipoChamado = $_POST['localiza-tipo-chamado'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-chamado-uf-solicitante']); $i++){
			$ufSolicitante .= $virgula."'".$_POST['localiza-chamado-uf-solicitante'][$i]."'";
			$virgula = ",";
		}

		$dataInicio = $_POST['data-inicio-abertura'];
		$dataFim = $_POST['data-fim-abertura'];
		$dataInicioFinalizado = $_POST['data-inicio-finalizado'];
		$dataFimFinalizado = $_POST['data-fim-finalizado'];
		$dataInicioInteracao = $_POST['data-inicio-interacao'];
		$dataFimInteracao = $_POST['data-fim-interacao'];

		$tipoFat44 = $_POST['tipo-fat-44']; if ($tipoFat44=="44") $tipoFat44 = "checked";
		$tipoFat45 = $_POST['tipo-fat-45']; if ($tipoFat45=="45") $tipoFat45 = "checked";
		if (($tipoFat44=="")&&($tipoFat45=="")){
			$tipoFat45 = "checked";
			$tipoFat44 = "checked";
		}
		$arrTiposChamados = carregarArrayTipo(19);
	}
	else{
		$classeEsconde = "esconde";
		$tipoFat45 = "checked";
		$tipoFat44 = "checked";
	}
?>
<div id='div-retorno'></div>
<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Faturamento'>
<input type='hidden' id='conteudo-relatorio' name='conteudo-relatorio' value=''>
<input class='esconde' id='todas-faturar-rel-pagar' type='checkbox'/>
<input class='esconde' id='todas-faturar-rel-receber' type='checkbox'/>
<div class="titulo-container">
	<div class="titulo">
		<p>Filtros de pesquisa</p>
	</div>
	<input type='hidden' name='workflow-id' id='workflow-id'>
	<div class="conteudo-interno">
		<div class="titulo-secundario cinco-colunas">
			<div class="titulo-secundario" style='width:22%; float:left;'>
				<p>ID</p>
				<p><input type='text' name='localiza-chamado-id' id='localiza-chamado-id' class='formata-numero' value='<?php echo $id; ?>' style='width:80%;'></p>
			</div>
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>C&oacute;digo</p>
				<p><input type='text' name='localiza-chamado-codigo' id='localiza-chamado-codigo' class='formata-campo' value='<?php echo $codigo; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:2;'>
			<p>Situa&ccedil;&atilde;o:</p>
			<select name="localiza-chamado-situacao[]" multiple id="localiza-chamado-situacao" style='height:71px;'><?php echo optionValueGrupoMultiplo(18, $situacoes);?><select>
		</div>
		<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:2;'>
			<div class="titulo-secundario" style='width:22%; float:right;'>
				<p>UF:</p>
				<p><select name="localiza-chamado-uf-solicitante[]" multiple style='height:71px;' id="localiza-chamado-uf-solicitante"><?php echo optionValueGrupoMultiploUF(str_replace("'","",$ufSolicitante));?><select></p>
			</div>
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>Solicitante:</p>
				<p><input type='text' name='localiza-chamado-solicitante' id='localiza-chamado-solicitante' class='formata-campo' value='<?php echo $nomeS; ?>'></p>
			</div>
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>Prestador:</p>
				<p><input type='text' name='localiza-chamado-prestador' id='localiza-chamado-prestador' class='formata-campo' value='<?php echo $nomeP; ?>'></p>
			</div>
			<!--
			<div class="titulo-secundario" style='width:22%; float:left;'>

				<p>UF:</p>
				<p><select name="localiza-chamado-uf-prestador" id="localiza-chamado-uf-prestador"><?php echo optionValueGrupoUF($ufPrestador, "&nbsp;");?><select></p>

			</div>-->
		</div>
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

		<div class="titulo-secundario cinco-colunas">
			<p>Tipo Chamado</p>
			<p><select name="localiza-tipo-chamado" id="localiza-tipo-chamado"><?php echo optionValueGrupoFilho(19, $tipoChamado, "Todos");?><select></p>
		</div>

		<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:1;'>&nbsp;</div>
		<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:1;'>&nbsp;</div>

		<div class="titulo-secundario cinco-colunas">
			<p>Prioridade:</p>
			<select name="localiza-chamado-prioridade" id="localiza-chamado-prioridade"><?php echo optionValueGrupo(21, $prioridade, "Todos");?><select>
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


		<div class="titulo-secundario" style="float:left;width:20%">
			<div class="div-normal">
				<p>Contas</p>
				<div style="float:left">
					<input type="checkbox" name="tipo-fat-45" id="tipo-fat-45" value="45" <?php echo $tipoFat45; ?>/>
					<label for="tipo-fat-45"> A Receber </label>&nbsp;
				</div>
				<div style="float:left">
					<input type="checkbox" name="tipo-fat-44" id="tipo-fat-44" value="44" <?php echo $tipoFat44; ?>/>
					<label for="tipo-fat-44"> A Pagar </label>&nbsp;
				</div>
			</div>
		</div>

		<div class="titulo-secundario" style='float:left;width:60%'>
			<p>&nbsp;</p>
			<p><input type='checkbox' align='left' value='true' name='exibir-faturaveis' id='exibir-faturaveis' <?php if ($exibirFaturaveis) echo 'checked'; ?>/>
				Exibir apenas registros com valores a faturar</p>
		</div>

		<div class='titulo-secundario' style='float:left;width:20%'>
			<p>&nbsp;</p>
			<div style='width:40%;float:right;'>
				<input type='button' value='Pesquisar' id='botao-localizar-chamado-relatorio' style='width:92%;margin-right:2px'/>&nbsp;
			</div>
			<div style='width:40%;float:right;' class='esconde botao-faturar-relatorio'>
				<input type='button' value='Faturar' id='botao-faturar-relatorio' class='botao-faturar-relatorio' style='width:92%;margin-right:2px'/>&nbsp;
			</div>
			<div style='width:10%;float:right;'>
				<div class="btn-excel" id='botao-salvar-excel' style="float:left;" title="Gerar Excel"></div>&nbsp;
				<input type='hidden' name='flag-excel' id='flag-excel' value=''>
			</div>
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
		if($situacoes!="") 	$condicoes .= " and cf.Situacao_ID IN ('$situacoes')";
		if($prioridade!="") $condicoes .= " and cw.Prioridade_ID = '$prioridade'";

		if($titulo!="")		$condicoes .= " and upper(cw.Titulo) like upper('$titulo%')";
		//if($tipoChamado!="") $condicoes .= " and tw.Tipo_ID = '$tipoChamado'";
		if($tipoChamado!="") $condicoes .= " and cw.Tipo_WorkFlow_ID in (".$arrTiposChamados[familia][$tipoChamado].")";

		if($ufSolicitante!="") $condicoes .= " and cd1.Cadastro_ID IN (select Cadastro_ID from cadastros_enderecos where UF IN ($ufSolicitante))";

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
			//$condicoes .= " and cf.Data_Cadastro between '$dataInicioFinalizado' and '$dataFimFinalizado' and cf.Situacao_ID = '34'";
			$condicoes .= " and cw.Data_Finalizado between '$dataInicioFinalizado' and '$dataFimFinalizado' and cf.Situacao_ID = '34'";
		}
		if(($dataInicioInteracao!="")||($dataFimIneracao!="")){
			$dataInicioInteracao = implode('-',array_reverse(explode('/',$dataInicioInteracao)));
			if ($dataInicioInteracao=="") $dataInicioInteracao = "0000-00-00"; $dataInicioInteracao .= " 00:00";
			$dataFimInteracao = implode('-',array_reverse(explode('/',$dataFimInteracao)));
			if ($dataFimInteracao=="") $dataFimInteracao = "2100-01-01"; $dataFimInteracao .= " 23:59";
			$condicoes .= " and cf.Data_Cadastro between '$dataInicioInteracao' and '$dataFimInteracao'";
		}

		/**********************************  A RECEBER **********************************/
		$dados = "";
		if ($tipoFat45!=""){
			$totalGeral = 0;
			$totalFaturarado = 0;

			if ($exibirFaturaveis){
				$condicoesHaving = " HAVING ((Total- Total_Faturado) > 0)";
			}
			$sql = "select cw.Workflow_ID, cw.Codigo as Codigo_Chamado, cw.Tipo_WorkFlow_ID,
							cd1.Nome as Solicitante, cd1.Codigo as Codigo_Solicitante, cd1.email as Email_Solicitante,
							cd2.Nome as Prestador, cd2.Codigo as Codigo_Prestador, cd2.email as Email_Prestador,
							t.Descr_Tipo as Situacao,
							DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
							DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
							DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
							coalesce(sum(cwp.Valor_Venda_Unitario * cwp.Quantidade),0) as Total,
							(select coalesce(sum(fp.Quantidade * fp.Valor_Unitario),0)
								from financeiro_contas fc
								inner join financeiro_produtos fp on fp.Conta_ID = fc.Conta_ID and fp.Tabela_Estrangeira = 'chamados'
								where fc.Tabela_Estrangeira = 'chamados' and fp.Chave_Estrangeira = cw.Workflow_ID and fc.Tipo_ID = 45 and fp.Situacao_ID = 1)
									as Total_Faturado
					from chamados_workflows cw
					inner join chamados_workflows_produtos cwp on cwp.Workflow_ID = cw.Workflow_ID and cwp.Situacao_ID = 1
					left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
					left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
					left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
						and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
					left join tipo t on t.Tipo_ID = cf.Situacao_ID
					where cw.Workflow_ID is not null
					$condicoes
					group by cw.Workflow_ID, cw.Codigo, cw.Titulo, cd1.Nome, cd1.email, cd2.Nome,
							cd2.email, t.Descr_Tipo, cw.Data_Cadastro
					$condicoesHaving
					order by cw.Workflow_ID, cf.Follow_ID";
			//echo $sql;
			$i = 0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				if ($row[Total]>$row[Total_Faturado]) $type = "checkbox"; else $type = "hidden";

				$dadosE[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px;float:left;' class='link workflow-localiza' workflow-id='$row[Workflow_ID]'>".$row[Workflow_ID]."</p>";
				$dadosE[colunas][conteudo][$i][2] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Codigo_Chamado]."</p>";
				$dadosE[colunas][conteudo][$i][3] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$arrTiposChamados[descricao][$row[Tipo_WorkFlow_ID]]."</p>";
				$dadosE[colunas][conteudo][$i][4] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Codigo_Solicitante]."</p>";
				$dadosE[colunas][conteudo][$i][5] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Solicitante]."</p>";
				$dadosE[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Situacao]."</p>";
				$dadosE[colunas][conteudo][$i][7] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Abertura]."</p>";
				$dadosE[colunas][conteudo][$i][8] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Finalizado]."</p>";
				$dadosE[colunas][conteudo][$i][9] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Interacao]."</p>";
				$dadosE[colunas][conteudo][$i][10] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format($row[Total], 2, ',', '.')."</p>";
				$dadosE[colunas][conteudo][$i][11] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format($row[Total_Faturado], 2, ',', '.')."</p>";
				$dadosE[colunas][conteudo][$i][12] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format(($row[Total] - $row[Total_Faturado]), 2, ',', '.')."</p>";
				$dadosE[colunas][conteudo][$i][13] = "<center><input type='$type' name='faturar-receber[]' id='faturar-receber-$i' posicao='$i' value='$row[Workflow_ID]' title='faturar' class='faturar-rel-receber'/></center>";
			}
			$largura = "100%";
			$colunas = "13";
			$dadosE[colunas][titulo][1] 	= "ID";
			$dadosE[colunas][titulo][2] 	= $_SESSION['objeto'];
			$dadosE[colunas][titulo][3] 	= "Tipo ".$_SESSION['objeto'];
			$dadosE[colunas][titulo][4] 	= "C&oacute;digo";
			$dadosE[colunas][titulo][5] 	= "Solicitante";
			$dadosE[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
			$dadosE[colunas][titulo][7] 	= "Data Abertura";
			$dadosE[colunas][titulo][8]	= "Data Finalizado";
			$dadosE[colunas][titulo][9]	= "Data Intera&ccedil;&atilde;o";
			$dadosE[colunas][titulo][10] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>Total</p>";
			$dadosE[colunas][titulo][11] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>Faturado</p>";
			$dadosE[colunas][titulo][12] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>A Faturar</p>";
			$dadosE[colunas][titulo][13] = "<p Style='margin:2px 3px 0 3px; text-align:center;' class='selecionar-todas' tipo='receber'>Faturar</p> ";

			$dadosE[colunas][conteudo][$i + 1][10] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalGeral, 2, ',', '.')."</b></p>";
			$dadosE[colunas][conteudo][$i + 1][11] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalFaturarado, 2, ',', '.')."</b></p>";
			$dadosE[colunas][conteudo][$i + 1][12] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format(($totalGeral-$totalFaturarado) , 2, ',', '.')."</b></p>";


			echo " <div class='titulo-container' id='localiza-chamado-retorno'>
					<div class='titulo'>
						<p>A Receber (N&deg; de registros: $i)</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
			geraTabela($largura,$colunas,$dadosE);
			echo "		</div>
					</div>";
			//$_SESSION["session-conteudo-relatorio"] = returnTabelaExcel($largura,$colunas,$dadosE);
		}

		/**********************************  A PAGAR **********************************/

		$i = 0;
		if ($tipoFat44!=""){
			$totalGeral = 0;
			$totalFaturarado = 0;

			if ($exibirFaturaveis){
				$condicoesHaving = " HAVING ((Total- Total_Faturado) > 0)";
			}
			$sql = "select cw.Workflow_ID, cw.Codigo as Codigo_Chamado,  cw.Tipo_WorkFlow_ID,
							cd1.Nome as Solicitante, cd1.Codigo as Codigo_Solicitante, cd1.email as Email_Solicitante,
							cd2.Nome as Prestador, cd2.Codigo as Codigo_Prestador, cd2.email as Email_Prestador,
							t.Descr_Tipo as Situacao,
							DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
							DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
							DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
							coalesce(sum(cwp.Valor_Custo_Unitario * cwp.Quantidade),0) as Total,
							(select coalesce(sum(fp.Quantidade * fp.Valor_Unitario),0)
								from financeiro_contas fc
								inner join financeiro_produtos fp on fp.Conta_ID = fc.Conta_ID and fp.Tabela_Estrangeira = 'chamados'
								where fc.Tabela_Estrangeira = 'chamados' and fp.Chave_Estrangeira = cw.Workflow_ID and fc.Tipo_ID = 44 and fp.Situacao_ID = 1)
									as Total_Faturado
					from chamados_workflows cw
					inner join chamados_workflows_produtos cwp on cwp.Workflow_ID = cw.Workflow_ID and cwp.Situacao_ID = 1
					left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
					left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
					left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
						and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
					left join tipo t on t.Tipo_ID = cf.Situacao_ID
					where cw.Workflow_ID is not null
					$condicoes
					group by cw.Workflow_ID, cw.Codigo, cw.Titulo, cd1.Nome, cd1.email, cd2.Nome,
							cd2.email, t.Descr_Tipo, cw.Data_Cadastro
					$condicoesHaving
					order by cd2.Cadastro_ID, cw.Workflow_ID, cf.Follow_ID";

			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$totalGeral += ($row[Total]);
				$totalFaturarado += ($row[Total_Faturado]);
				if ($row[Total]>$row[Total_Faturado]) $type = "checkbox"; else $type = "hidden";

				$dadosS[colunas][conteudo][$i][1] = "<p Style='margin:1px 1px 0 1px;float:left;' class='link workflow-localiza' workflow-id='$row[Workflow_ID]'>".$row[Workflow_ID]."</p>";
				$dadosS[colunas][conteudo][$i][2] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Codigo_Chamado]."</p>";
				$dadosS[colunas][conteudo][$i][3] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$arrTiposChamados[descricao][$row[Tipo_WorkFlow_ID]]."</p>";
				$dadosS[colunas][conteudo][$i][4] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Codigo_Prestador]."</p>";
				$dadosS[colunas][conteudo][$i][5] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Prestador]."</p>";
				$dadosS[colunas][conteudo][$i][6] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Situacao]."</p>";
				$dadosS[colunas][conteudo][$i][7] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Abertura]."</p>";
				$dadosS[colunas][conteudo][$i][8] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Finalizado]."</p>";
				$dadosS[colunas][conteudo][$i][9] = "<p Style='margin:1px 1px 0 1px;float:left;'>".$row[Data_Interacao]."</p>";
				$dadosS[colunas][conteudo][$i][10] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format($row[Total], 2, ',', '.')."</p>";
				$dadosS[colunas][conteudo][$i][11] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format($row[Total_Faturado], 2, ',', '.')."</p>";
				$dadosS[colunas][conteudo][$i][12] = "<p Style='margin:1px 1px 0 1px;float:right;'>".number_format(($row[Total] - $row[Total_Faturado]), 2, ',', '.')."</p>";
				$dadosS[colunas][conteudo][$i][13] = "<center><input type='$type' name='faturar-pagar[]' id='faturar-pagar-$i' posicao='$i' value='$row[Workflow_ID]' title='faturar' class='faturar-rel-pagar'/></center>";
			}
			$largura = "100%";
			$colunas = "13";
			$dadosS[colunas][titulo][1] 	= "ID";
			$dadosS[colunas][titulo][2] 	= $_SESSION['objeto'];
			$dadosS[colunas][titulo][3] 	= "Tipo ".$_SESSION['objeto'];
			$dadosS[colunas][titulo][4] 	= "C&oacute;digo";
			$dadosS[colunas][titulo][5] 	= "Prestador";
			$dadosS[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
			$dadosS[colunas][titulo][7] 	= "Data Abertura";
			$dadosS[colunas][titulo][8]	= "Data Finalizado";
			$dadosS[colunas][titulo][9]	= "Data Intera&ccedil;&atilde;o";
			$dadosS[colunas][titulo][10] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>Total</p>";
			$dadosS[colunas][titulo][11] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>Faturado</p>";
			$dadosS[colunas][titulo][12] = "<p Style='margin:2px 3px 0 3px; text-align:right;'>A Faturar</p>";
			$dadosS[colunas][titulo][13] = "<p Style='margin:2px 3px 0 3px; text-align:center;' class='selecionar-todas' tipo='pagar'>Faturar</p> ";
			$dadosS[colunas][conteudo][$i + 1][10] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalGeral, 2, ',', '.')."</b></p>";
			$dadosS[colunas][conteudo][$i + 1][11] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalFaturarado, 2, ',', '.')."</b></p>";
			$dadosS[colunas][conteudo][$i + 1][12] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format(($totalGeral-$totalFaturarado) , 2, ',', '.')."</b></p>";


			echo " <div class='titulo-container' id='localiza-chamado-retorno'>
					<div class='titulo'>
						<p>A Pagar (N&deg; de registros: $i)</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
			geraTabela($largura,$colunas,$dadosS);
			echo "		</div>
					</div>";


			//$_SESSION["session-conteudo-relatorio"] = returnTabelaExcel($largura,$colunas,$dadosS);
		}


	}
?>