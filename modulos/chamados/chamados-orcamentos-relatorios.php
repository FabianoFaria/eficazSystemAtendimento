<?php
	include("functions.php");
	global $caminhoSistema;
	if ($filtroRelatorio=='menu-superior-1'){
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

			/* DADOS CRM - INICIO */
			$origem							= $_POST['localiza-oportunidade-origem'];
			$dataInicioPrevisao				= $_POST['localiza-data-inicio-previsao'];
			$dataFimPrevisao				= $_POST['localiza-data-fim-previsao'];
			$expectativaValorInicio			= $_POST['localiza-oportunidade-valor-inicio'];
			$expectativaValorFim			= $_POST['localiza-oportunidade-valor-fim'];
			$probabilidadeFechamentoInicio	= $_POST['localiza-oportunidade-probabilidade-fechamento-inicio'];
			$probabilidadeFechamentoFim		= $_POST['localiza-oportunidade-probabilidade-fechamento-fim'];
			/* DADOS CRM - FIM */
		}
?>
<div class="titulo-container">
	<div class="titulo">
		<p>Filtros de Pesquisa</p>
	</div>
	<div class="conteudo-interno">
		<div class="titulo-secundario" style='width:100%; float:left; min-height:52px'>
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
		</div>
		<div class='titulo-secundario' style='width:100%; float:left;'>
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
		</div>
<?php
		echo "		<div class='titulo-secundario' style='width:20%; float:left;'>
						<p>Origem</p>
						<p><select name='localiza-oportunidade-origem' id='localiza-oportunidade-origem'>".optionValueGrupo(76, $origem, "&nbsp;")."</select></p>
					</div>
					<div class='titulo-secundario' style='width:20%; float:left;'>
						<p>Previs&atilde;o Fechamento</p>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='localiza-data-inicio-previsao' id='localiza-data-inicio-previsao' class='formata-data' style='width:95%' maxlength='10' value='".$dataInicioPrevisao."'>&nbsp;&nbsp;</p>
						</div>
						<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='localiza-data-fim-previsao' id='localiza-data-fim-previsao' class='formata-data' style='width:95%' maxlength='10' value='".$dataFimPrevisao."'></p>
						</div>
					</div>
					<div class='titulo-secundario' style='width:20%; float:left;'>
						<p>Expectativa de Valor</p>
						<div style='width:43%;float:left;'>
							<p><input type='text' class='formata-valor dados-orc' id='localiza-oportunidade-valor-inicio' name='localiza-oportunidade-valor-inicio' style='width:98%;' maxlength='20' value='".$expectativaValorInicio."'/></p>
						</div>
						<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
						<div style='width:43%;float:left;'>
							<p><input type='text' class='formata-valor dados-orc' id='localiza-oportunidade-valor-fim' name='localiza-oportunidade-valor-fim' style='width:98%;' maxlength='20' value='".$expectativaValorFim."'/></p>
						</div>
					</div>
					<div class='titulo-secundario' style='width:20%; float:left;'>
						<p>Probabilidade fechamento</p>
						<div style='width:43%;float:left;'>
							<p><select id='localiza-oportunidade-probabilidade-fechamento-inicio' name='localiza-oportunidade-probabilidade-fechamento-inicio'><option value=''></option>".optionValueCountSelect(100, $probabilidadeFechamentoInicio, "&nbsp;", "", " %")."</select></p>
						</div>
						<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
						<div style='width:43%;float:left;'>
							<p><select id='localiza-oportunidade-probabilidade-fechamento-fim' name='localiza-oportunidade-probabilidade-fechamento-fim'><option value=''></option>".optionValueCountSelect(100, $probabilidadeFechamentoFim, "&nbsp;", "", " %")."</select></p>
						</div>
					</div>";
?>

		<div class='titulo-secundario' Style='margin-top:15px; width:10%; float:right;'>
			<p><input type='button' Style='width:90%; float:right;' value='Pesquisar' id='botao-localizar-orcamento'></p>
		</div>
	</div>
</div>
<input type='hidden' name='workflow-id' id='workflow-id' value=''/>
<?php
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
		if(($dataInicioInteracao!="")||($dataFimInteracao!="")){
			$dataInicioInteracao = implode('-',array_reverse(explode('/',$dataInicioInteracao)));
			if ($dataInicioInteracao=="") $dataInicioInteracao = "0000-00-00"; $dataInicioInteracao .= " 00:00";
			$dataFimInteracao = implode('-',array_reverse(explode('/',$dataFimInteracao)));
			if ($dataFimInteracao=="") $dataFimInteracao = "2100-01-01"; $dataFimInteracao .= " 23:59";
			$sqlCond .= " and of.Data_Cadastro between '$dataInicioInteracao' and '$dataFimInteracao'";
		}

		/* FILTROS CRM - INICIO */

		if($origem!='')				$sqlCond .= " and ow.Origem_ID in ($origem)";
		if(($dataInicioPrevisao!="")||($dataFimPrevisao!="")){
			$dataInicioPrevisao = implode('-',array_reverse(explode('/',$dataInicioPrevisao)));
			if ($dataInicioPrevisao=="") $dataInicioPrevisao = "0000-00-00"; $dataInicioPrevisao .= " 00:00";
			$dataFimPrevisao = implode('-',array_reverse(explode('/',$dataFimPrevisao)));
			if ($dataFimPrevisao=="") $dataFimPrevisao = "2100-01-01"; $dataFimPrevisao .= " 23:59";
			$sqlCond .= " and ow.Data_Previsao between '$dataInicioPrevisao' and '$dataFimPrevisao' ";
		}
		if($expectativaValorInicio!='')			$sqlCond .= " and ow.Expectativa_Valor >= ".formataValorBD($expectativaValorInicio);
		if($expectativaValorFim!='')			$sqlCond .= " and ow.Expectativa_Valor <= ".formataValorBD($expectativaValorFim);
		if($probabilidadeFechamentoInicio!='')	$sqlCond .= " and ow.Probabilidade_Fechamento >= $probabilidadeFechamentoInicio ";
		if($probabilidadeFechamentoFim!='')		$sqlCond .= " and ow.Probabilidade_Fechamento <= $probabilidadeFechamentoFim ";

		/* FILTROS CRM - FIM */


		if($_POST['ordena-tabela'] != ""){
			$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
		}
		else{
			$ordem = " order by o.Workflow_ID desc";
		}

		$sql = "select o.Workflow_ID, o.Codigo, o.Titulo, s.Nome as Solicitante, ts.Descr_Tipo as Situacao, o.Data_Abertura,
					o.Data_Finalizado, of.Data_Cadastro as Data_Interacao, r.Nome as Representante, ts.Tipo_Auxiliar as Situacao_Orcamento_Dados,
					op.Titulo as Titulo_Proposta, tp.Descr_Tipo as Situacao_Proposta, tp.Tipo_Auxiliar as Situacao_Proposta_Dados,
					op.Proposta_ID as Proposta_ID, SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor, fp.Descr_Tipo as Forma_Pagamento,
					ow.Origem_ID, ori.Descr_Tipo as Origem, ow.Expectativa_Valor, ow.Probabilidade_Fechamento, ow.Data_Previsao, ori.Descr_Tipo as Origem,
					ow.Orcamento_ID as Orcamento_ID
					from orcamentos_workflows o
					inner join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
					left join oportunidades_workflows ow on ow.Orcamento_ID = o.Workflow_ID
					left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
					left join tipo ts on ts.Tipo_ID = o.Situacao_ID
					left join orcamentos_follows of on o.Workflow_ID = of.Workflow_ID and of.Follow_ID = (select max(ofaux.Follow_ID) from orcamentos_follows ofaux where ofaux.Workflow_ID = o.Workflow_ID)
					left join orcamentos_propostas op on op.Workflow_ID = o.Workflow_ID and op.Situacao_ID = 1
					left join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
					left join tipo tp on tp.Tipo_ID =  op.Status_ID
					left join tipo ori on ori.Tipo_ID = ow.Origem_ID
					left join tipo fp on fp.Tipo_ID = op.Forma_Pagamento_ID
				where o.Workflow_ID > 0
					$sqlCond
					GROUP BY o.Workflow_ID, op.Proposta_ID
					$ordem";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$situacaoOrcamentoDados = unserialize($row['Situacao_Orcamento_Dados']);
			$dataPrevisao = substr(converteData($row['Data_Previsao']),0,10);
			if ($dataPrevisao=='00/00/0000') $dataPrevisao = "";
			$style = "";
			$c = 1;

			$dados[colunas]['tr'][$i] = " style='font-weight:bold; cursor:pointer;' class='orcamento-localiza lnk' workflow-id='".$row['Orcamento_ID']."'";
			if ($situacaoOrcamentoDados['cor-fundo']!='') $style .= "background-color: ".$situacaoOrcamentoDados['cor-fundo'].";";
			if ($situacaoOrcamentoDados['cor-texto']!='') $style .= "color: ".$situacaoOrcamentoDados['cor-texto'].";";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Workflow_ID]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Solicitante]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Titulo]."</p>";
			$dados[colunas][extras][$i][$c] = " style='".$style."'";
			$dados[colunas][conteudo][$i][$c] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
			$c++;
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Abertura]),0,10)."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Interacao]),0,10)."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Finalizado]),0,10)."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Representante]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 10px;float:left;'>".$row[Titulo_Proposta]."</p>";
			$situacaoPropostaDados = unserialize($row['Situacao_Proposta_Dados']);
			$style = "";
			if ($situacaoPropostaDados['cor-fundo']!='') $style .= "background-color: ".$situacaoPropostaDados['cor-fundo'].";";
			if ($situacaoPropostaDados['cor-texto']!='') $style .= "color: ".$situacaoPropostaDados['cor-texto'].";";
			$dados[colunas][extras][$i][$c] = " style='".$style."'";
			$dados[colunas][conteudo][$i][$c] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Situacao_Proposta']."</p>";
			$c++;
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>R$ ".number_format($row['Valor'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$row['Forma_Pagamento']."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$row['Origem']."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$dataPrevisao."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>R$ ".number_format($row['Expectativa_Valor'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$row['Probabilidade_Fechamento']." %</p>";

			$valorTotal += $row['Valor'];

		}
		$largura = "100%";
		$colunas = $c - 1;
		$c = 1;
		$dados[colunas][titulo][$c++] 	= "Or&ccedil;amento";
		$dados[colunas][titulo][$c++] 	= "Solicitante";
		$dados[colunas][titulo][$c++] 	= "T&iacute;tulo Or&ccedil;amento";
		$dados[colunas][titulo][$c++] 	= "Situa&ccedil;&atilde;o Or&ccedil;amento";
		$dados[colunas][titulo][$c++] 	= "Abertura";
		$dados[colunas][titulo][$c++]	= "Intera&ccedil;&atilde;o";
		$dados[colunas][titulo][$c++]	= "Finalizado";
		$dados[colunas][titulo][$c++]	= "Representante";
		$dados[colunas][titulo][$c++]	= "T&iacute;tulo Proposta";
		$dados[colunas][titulo][$c++] 	= "Situa&ccedil;&atilde;o Proposta";
		$dados[colunas][titulo][$c++] 	= "<center>Valor Proposta</center>";
		$dados[colunas][titulo][$c++] 	= "Forma Pagamento";

		$dados[colunas][titulo][$c++] 	= "Origem";
		$dados[colunas][titulo][$c++] 	= "Data Previs&atilde;o Fechamento";
		$dados[colunas][titulo][$c++] 	= "Expectativa de Valor";
		$dados[colunas][titulo][$c++] 	= "Probabilidade Fechamento";


		$c = 1;
		$dados[colunas][ordena][$c++] = "o.Workflow_ID";
		$dados[colunas][ordena][$c++] = "s.Nome";
		$dados[colunas][ordena][$c++] = "o.Titulo";
		$dados[colunas][ordena][$c++] = "ts.Descr_Tipo";
		$dados[colunas][ordena][$c++] = "o.Data_Abertura";
		$dados[colunas][ordena][$c++] = "of.Data_Cadastro";
		$dados[colunas][ordena][$c++] = "o.Data_Finalizado";
		$dados[colunas][ordena][$c++] = "r.Nome";

		$c = 1;
		/*
		$dados[colunas][tamanho][1] = "width='40px'";
		$dados[colunas][tamanho][2] = "width='90px'";
		$dados[colunas][tamanho][6] = "width='70px'";
		$dados[colunas][tamanho][7] = "width='70px'";
		$dados[colunas][tamanho][8] = "width='70px'";
		$dados[colunas][tamanho][11] = "width='100px'";
		*/
		$dados[colunas][tamanho][11] = "width='100px'";
		echo " <div class='titulo-container' id='localiza-orcamento-retorno'>
				<div class='titulo'>
					<p>Registros Localizados: $i</p>
				</div>
				<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
		geraTabela($largura, $colunas, $dados, null, 'propostas-relatorio-dinamico', 2, 2, 100,1);
		echo "		</div>
				</div>";
	}


	/*************************************/
	/******* POR REPRESENTANTE ***********/
	/*************************************/
	if ($filtroRelatorio=='menu-superior-2'){
		if ($_POST['hd-aux']=='2'){
			$dataInicioFinalizado = $_POST['a-localiza-data-inicio-finalizado'];
			$dataFimFinalizado = $_POST['a-localiza-data-fim-finalizado'];
		}
		else{
			$dataInicioFinalizado = "01/".date("m/Y");
			$ultimo_dia = date("t", mktime(0,0,0, date("m"),'01', date("Y")));
			$dataFimFinalizado = $ultimo_dia."/".date("m/Y");
		}
?>
	<input type='hidden' name='hd-aux' value='2'/>
	<div class="titulo-container">
		<div class="titulo">
			<p>Filtros de Pesquisa</p>
		</div>
		<div class="conteudo-interno">
			<div class='titulo-secundario' style='width:20%; float:left;'>
				<p>Data Finalizado</p>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='a-localiza-data-inicio-finalizado' id='a-localiza-data-inicio-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioFinalizado; ?>'>&nbsp;&nbsp;</p>
				</div>
				<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='a-localiza-data-fim-finalizado' id='a-localiza-data-fim-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimFinalizado; ?>'></p>
				</div>
			</div>
			<div class='titulo-secundario' Style='margin-top:15px; width:10%; float:right;'>
				<p><input type='button' Style='width:90%; float:right;' value='Pesquisar' id='botao-localizar-orcamento' name='botao-localizar-orcamento'></p>
			</div>
		</div>
	</div>
<?php
		if(($dataInicioFinalizado!="")||($dataFimFinalizado!="")){
			$dataInicioFinalizado = implode('-',array_reverse(explode('/',$dataInicioFinalizado)));
			if ($dataInicioFinalizado=="") $dataInicioFinalizado = "0000-00-00"; $dataInicioFinalizado .= " 00:00";
			$dataFimFinalizado = implode('-',array_reverse(explode('/',$dataFimFinalizado)));
			if ($dataFimFinalizado=="") $dataFimFinalizado = "2100-01-01"; $dataFimFinalizado .= " 23:59";
			$sqlCond .= " and o.Data_Finalizado between '$dataInicioFinalizado' and '$dataFimFinalizado' ";
		}

		$sql = "SELECT r.Nome AS Representante, o.Representante_ID,
				tp.Descr_Tipo AS Situacao_Proposta,
				op.Status_ID, tp.Tipo_Auxiliar AS Situacao_Proposta_Dados,
				coalesce(SUM(opp.Quantidade * opp.Valor_Venda_Unitario),0) AS Valor
				FROM orcamentos_workflows o
				INNER JOIN cadastros_dados s ON s.Cadastro_ID = o.Solicitante_ID
				LEFT JOIN cadastros_dados r ON r.Cadastro_ID = o.Representante_ID
				LEFT JOIN tipo ts ON ts.Tipo_ID = o.Situacao_ID
				LEFT JOIN orcamentos_follows of ON o.Workflow_ID = of.Workflow_ID AND of.Follow_ID = (
				SELECT MAX(ofaux.Follow_ID)
				FROM orcamentos_follows ofaux
				WHERE ofaux.Workflow_ID = o.Workflow_ID)
				LEFT JOIN orcamentos_propostas op ON op.Workflow_ID = o.Workflow_ID AND op.Situacao_ID = 1
				LEFT JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID AND opp.Situacao_ID = 1
				LEFT JOIN tipo tp ON tp.Tipo_ID = op.Status_ID
				WHERE op.Status_ID = 141
				$sqlCond
				GROUP BY r.Cadastro_ID, r.Nome, tp.Tipo_Auxiliar, tp.Tipo_ID
				HAVING Valor > 0
				ORDER BY r.Nome, o.Representante_ID, tp.Tipo_Auxiliar";
		//echo $sql;
		$i = 0;
		$resultado = mpress_query($sql);
		$h1 = "	<table style='margin-top:0px; border:1px solid silver; margin-bottom:2px; width:100%; max-width:1200px' align='center'>
					<tr>
						<td style='text-align:center; padding:20px 0 20px 0' class='tabela-fundo-escuro-titulo'>Representante</td>
						<td style='text-align:right;' class='tabela-fundo-escuro-titulo'>Valor</td>
					</tr>";
		while($rs = mpress_fetch_array($resultado)){
			$i++;
			$javascript .= "['".$rs['Representante']." - R$ ".number_format($rs['Valor'], 2, ',', '.')."',".$rs['Valor']."],";
			$h1 .= "<tr>
						<td class='tabela-fundo-claro' style='text-align:center;'>".$rs['Representante']."</td>
						<td class='tabela-fundo-claro' style='text-align:right;'>R$ ".number_format($rs['Valor'], 2, ',', '.')."</td>
					</tr>";
		}
		$h1 .= "	<tr>
						<td colspan='2'>
							<div id='div-pizza' style='width: 100%; float:left;' align='center'></div>
							<div id='div-barras' style='width: 100%; float:left;' align='center'></div>
						</td>
					</tr>";
		$h1 .= "</table>";
		echo " 	<div class='titulo-container' id='localiza-orcamento-retorno'>
					<div class='titulo'>
						<p>Neg&oacute;cios fechados no per&iacute;odo</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
		if ($i==0){
			echo "		<p align='center'>Nenhum registro localizado no per&iacute;odo selecionado</p>";
		}
		else{
			echo $h1;
			echo "		<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
			echo "		<script type='text/javascript'>
							google.load('visualization', '1', {packages:['corechart']});
							google.setOnLoadCallback(drawChart);
							function drawChart() {
								var data = google.visualization.arrayToDataTable([
									['Representante', 'R$ Valor'],
									".substr($javascript,0,-1)."
									]);
								var options = {title: '',is3D: true,};
								var grafico = new google.visualization.PieChart(document.getElementById('div-pizza'));
								grafico.draw(data, options);
							}
							google.load('visualization', '1', {packages:['corechart']});
							google.setOnLoadCallback(drawChart2);
							function drawChart2() {
								var data = google.visualization.arrayToDataTable([
									  ['Representante', 'Valor'], ".substr($javascript,0,-1)."
								]);
								var options = {
								  title: '',
								  is3D: true,
								};
								var chart = new google.visualization.ColumnChart(document.getElementById('div-barras'));
								chart.draw(data, options);
							}
						</script>";
		}
		echo "		</div>
				</div>";

	}


	/***********************************************/
	/******* PROPOSTAS EM ANDAMENTO ****************/
	/***********************************************/
	if ($filtroRelatorio=='menu-superior-3'){
		if ($_POST['hd-aux']=='3'){
			$dataInicioAbertura = $_POST['b-data-inicio'];
			$dataFimAbertura = $_POST['b-data-fim'];
		}
		//else{
			//$dataInicioAbertura = "01/".date("m/Y");
			//$ultimo_dia = date("t", mktime(0,0,0, date("m"),'01', date("Y")));
			//$dataFimAbertura = $ultimo_dia."/".date("m/Y");
		//}
?>
	<input type='hidden' name='hd-aux' value='3'/>
	<div class="titulo-container">
		<div class="titulo">
			<p>Filtros de Pesquisa</p>
		</div>
		<div class="conteudo-interno">
			<div class='titulo-secundario' style='width:20%; float:left;'>
				<p>Data Abertura</p>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='b-data-inicio' id='b-data-inicio' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioAbertura; ?>'>&nbsp;&nbsp;</p>
				</div>
				<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='b-data-fim' id='b-data-fim' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimAbertura; ?>'></p>
				</div>
			</div>
			<div class='titulo-secundario' Style='margin-top:15px; width:10%; float:right;'>
				<p><input type='button' Style='width:90%; float:right;' value='Pesquisar' id='botao-localizar-orcamento' name='botao-localizar-orcamento'></p>
			</div>
		</div>
	</div>
<?php
		if(($dataInicioAbertura!="")||($dataFimAbertura!="")){
			$dataInicioAbertura = implode('-',array_reverse(explode('/',$dataInicioAbertura)));
			if ($dataInicioAbertura=="") $dataInicioAbertura = "0000-00-00"; $dataInicioAbertura .= " 00:00";
			$dataFimAbertura = implode('-',array_reverse(explode('/',$dataFimAbertura)));
			if ($dataFimAbertura=="") $dataFimAbertura = "2100-01-01"; $dataFimAbertura .= " 23:59";
			$sqlCond .= " and o.Data_Abertura between '$dataInicioAbertura' and '$dataFimAbertura' ";
		}
		$sql = "SELECT r.Nome AS Representante, o.Representante_ID,
							COALESCE(SUM(opp.Quantidade * opp.Valor_Venda_Unitario),0) AS Valor
							FROM orcamentos_workflows o
							LEFT JOIN cadastros_dados r ON r.Cadastro_ID = o.Representante_ID
							LEFT JOIN orcamentos_follows of ON o.Workflow_ID = of.Workflow_ID AND of.Follow_ID = (
							SELECT MAX(ofaux.Follow_ID)
							FROM orcamentos_follows ofaux
							WHERE ofaux.Workflow_ID = o.Workflow_ID)
							LEFT JOIN orcamentos_propostas op ON op.Workflow_ID = o.Workflow_ID AND op.Situacao_ID = 1
							LEFT JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID AND opp.Situacao_ID = 1
							LEFT JOIN tipo tp ON tp.Tipo_ID = op.Status_ID
							WHERE op.Status_ID NOT IN (141,122,119)
							$sqlCond
							GROUP BY r.Cadastro_ID, r.Nome
							HAVING Valor > 0
							ORDER BY r.Nome, o.Representante_ID";
		//echo $sql;
		$i = 0;
		$resultado = mpress_query($sql);
		$h1 = "	<table style='margin-top:0px; border:1px solid silver; margin-bottom:2px; width:100%; max-width:1200px;' align='center'>
					<tr>
						<td style='text-align:center; padding:20px 0 20px 0' class='tabela-fundo-escuro-titulo'>Representante</td>
						<td style='text-align:right;' class='tabela-fundo-escuro-titulo'>Valor</td>
					</tr>";
		while($rs = mpress_fetch_array($resultado)){
			$i++;
			$h1 .= " <tr>
						<td class='tabela-fundo-claro' style='text-align:center;'>".$rs['Representante']."</td>
						<td class='tabela-fundo-claro' style='text-align:right;'>R$ ".number_format($rs['Valor'], 2, ',', '.')."</td>
					</tr>";
			$javascript .= "['".$rs['Representante']." - R$ ".number_format($rs['Valor'], 2, ',', '.')."',".$rs['Valor']."],";
		}
		$h1 .= " <tr>
					<td colspan='2'>
						<div id='div-pizza' style='width: 100%; float:left;' align='center'></div>
						<div id='div-barras' style='width: 100%; float:left;' align='center'></div>
					</td>
				</tr>";

		$h1 .= " </table>";
		echo " 	<div class='titulo-container' id='localiza-orcamento-retorno'>
					<div class='titulo'>
						<p>Propostas em aberto</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
		if ($i==0){
			echo "		<p align='center'>Nenhum registro localizado no per&iacute;odo selecionado</p>";
		}
		else{
			echo $h1;
			echo "			<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
			echo "			<script type='text/javascript'>
								google.load('visualization', '1', {packages:['corechart']});
								google.setOnLoadCallback(drawChart);
								function drawChart() {
									var data = google.visualization.arrayToDataTable([
										['Representante', 'R$ Valor'],
										".substr($javascript,0,-1)."
										]);
									var options = {title: '',is3D: true,};
									var grafico = new google.visualization.PieChart(document.getElementById('div-pizza'));
									grafico.draw(data, options);
								}
								google.load('visualization', '1', {packages:['corechart']});
								google.setOnLoadCallback(drawChart2);
								function drawChart2() {
									var data = google.visualization.arrayToDataTable([
										  ['Representante', 'Valor'], ".substr($javascript,0,-1)."
									]);
									var options = {
									  title: '',
									  is3D: true,
									};
									var chart = new google.visualization.ColumnChart(document.getElementById('div-barras'));
									chart.draw(data, options);
								}
							</script>";
		}
		echo "		</div>
				</div>";

		$sql = "SELECT r.Nome AS Representante, o.Representante_ID,
							COALESCE(SUM(opp.Quantidade * opp.Valor_Venda_Unitario),0) AS Valor, ow.Probabilidade_Fechamento
							FROM orcamentos_workflows o
							inner join oportunidades_workflows ow on ow.Orcamento_ID = o.Workflow_ID
							LEFT JOIN cadastros_dados r ON r.Cadastro_ID = o.Representante_ID
							LEFT JOIN orcamentos_propostas op ON op.Workflow_ID = o.Workflow_ID AND op.Situacao_ID = 1
							LEFT JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID AND opp.Situacao_ID = 1
							LEFT JOIN tipo tp ON tp.Tipo_ID = op.Status_ID
							WHERE op.Status_ID NOT IN (141,122,119)
							$sqlCond
							GROUP BY r.Cadastro_ID, r.Nome,  ow.Probabilidade_Fechamento
							HAVING Valor > 0
							ORDER BY r.Nome, o.Representante_ID, ow.Probabilidade_Fechamento ";
		//echo $sql;
		$ii = 0;
		$resultado = mpress_query($sql);
		$javascript = array();
		$h2 = "	<table style='margin-top:0px; border:1px solid silver; margin-bottom:2px; width:100%; max-width:1200px;' align='center'>";
		while($rs = mpress_fetch_array($resultado)){
			if ($rs['Representante_ID'] != $representanteIDAnt){
				if ($ii != 0){
					$h2 .= "	<tr>
									<td colspan='2' class='tabela-fundo-claro' style='text-align:center;'>
										<div id='div-barras-".$rs['Representante_ID']."' style='float:left;width:100%'></div>
										<div id='div-pizza-".$rs['Representante_ID']."' style='float:left;width:100%'></div>
									</td>
								</tr>";
				}
				$h2 .= " <tr>
							<td style='text-align:center; padding:20px 0 20px 0' colspan='2' class='tabela-fundo-escuro-titulo'>".$rs['Representante']."</td>
						</tr>
						<tr>
							<td class='tabela-fundo-escuro-titulo' style='text-align:center;'>Probabilidade</td>
							<td class='tabela-fundo-escuro-titulo' style='text-align:right;'>Valor</td>
						</tr>";
			}
			$h2 .= "	<tr>
							<td class='tabela-fundo-claro' style='text-align:center;'>".$rs['Probabilidade_Fechamento']." %</td>
							<td class='tabela-fundo-claro' style='text-align:right;'>R$ ".number_format($rs['Valor'], 2, ',', '.')."</td>
						</tr>";
			$ii++;
			$javascript[$rs['Representante_ID']] .= "['".$rs['Probabilidade_Fechamento']." %',".$rs['Valor']."],";
			$representanteIDAnt = $rs['Representante_ID'];
		}
		if ($ii > 0){
			$h2 .= "	<tr>
							<td colspan='2' class='tabela-fundo-claro' style='text-align:center;'>
								<div id='div-barras-".$representanteIDAnt."' style='float:left;width:100%'></div>
								<div id='div-pizza-".$representanteIDAnt."' style='float:left;width:100%'></div>
							</td>
						</tr>";
		}
		$h2 .= "	</table>";
		echo " 	<div class='titulo-container' id='localiza-orcamento-retorno'>
					<div class='titulo'>
						<p>Probabilidade Fechamento</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
		echo $h2;
		echo "		</div>
				</div>";
		foreach($javascript as $chave => $j){
			echo "		<script type='text/javascript'>
						google.load('visualization', '1', {packages:['corechart']});
						google.setOnLoadCallback(drawChartA".$chave.");
						function drawChartA".$chave."() {
							var data = google.visualization.arrayToDataTable([
								['Probabilidade', 'R$ Valor'],
								".substr($j,0,-1)."
								]);
							var options = {title: '',is3D: true,};
							var grafico = new google.visualization.PieChart(document.getElementById('div-pizza-".$chave."'));
							grafico.draw(data, options);
						}
						google.load('visualization', '1', {packages:['corechart']});
						google.setOnLoadCallback(drawChartB".$chave.");
						function drawChartB".$chave."() {
							var data = google.visualization.arrayToDataTable([
								  ['Probabilidade', 'Valor'], ".substr($j,0,-1)."
							]);
							var options = {
							  title: '',
							  is3D: true,
							};
							var chart = new google.visualization.ColumnChart(document.getElementById('div-barras-".$chave."'));
							chart.draw(data, options);
						}
					</script>";


		}

	}

	/*********************************************/
	/******* PREVISAO FECHAMENTO POR PERIODO *****/
	/*********************************************/
	if ($filtroRelatorio=='menu-superior-4'){
		if ($_POST['hd-aux']=='4'){
			$dataInicioAbertura = $_POST['c-data-inicio'];
			$dataFimAbertura = $_POST['c-data-fim'];
		}
		//else{
			//$dataInicioAbertura = "01/".date("m/Y");
			//$ultimo_dia = date("t", mktime(0,0,0, date("m"),'01', date("Y")));
			//$dataFimAbertura = $ultimo_dia."/".date("m/Y");
		//}
?>
	<input type='hidden' name='hd-aux' value='4'/>
	<div class="titulo-container">
		<div class="titulo">
			<p>Filtros de Pesquisa</p>
		</div>
		<div class="conteudo-interno">
			<div class='titulo-secundario' style='width:20%; float:left;'>
				<p>Data Abertura</p>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='c-data-inicio' id='c-data-inicio' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioAbertura; ?>'>&nbsp;&nbsp;</p>
				</div>
				<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='c-data-fim' id='c-data-fim' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimAbertura; ?>'></p>
				</div>
			</div>
			<div class='titulo-secundario' Style='margin-top:15px; width:10%; float:right;'>
				<p><input type='button' Style='width:90%; float:right;' value='Pesquisar' id='botao-localizar-orcamento' name='botao-localizar-orcamento'></p>
			</div>
		</div>
	</div>
<?php
		if(($dataInicioAbertura!="")||($dataFimAbertura!="")){
			$dataInicioAbertura = implode('-',array_reverse(explode('/',$dataInicioAbertura)));
			if ($dataInicioAbertura=="") $dataInicioAbertura = "0000-00-00"; $dataInicioAbertura .= " 00:00";
			$dataFimAbertura = implode('-',array_reverse(explode('/',$dataFimAbertura)));
			if ($dataFimAbertura=="") $dataFimAbertura = "2100-01-01"; $dataFimAbertura .= " 23:59";
			$sqlCond .= " and o.Data_Abertura between '$dataInicioAbertura' and '$dataFimAbertura' ";
		}
		$sql = "SELECT r.Nome AS Representante, o.Representante_ID,
							COALESCE(SUM(opp.Quantidade * opp.Valor_Venda_Unitario),0) AS Valor, ow.Probabilidade_Fechamento
							FROM orcamentos_workflows o
							inner join oportunidades_workflows ow on ow.Orcamento_ID = o.Workflow_ID
							LEFT JOIN cadastros_dados r ON r.Cadastro_ID = o.Representante_ID
							LEFT JOIN orcamentos_propostas op ON op.Workflow_ID = o.Workflow_ID AND op.Situacao_ID = 1
							LEFT JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID AND opp.Situacao_ID = 1
							LEFT JOIN tipo tp ON tp.Tipo_ID = op.Status_ID
							WHERE op.Status_ID NOT IN (141,122,119)
							$sqlCond
							GROUP BY r.Cadastro_ID, r.Nome,  ow.Probabilidade_Fechamento
							HAVING Valor > 0
							ORDER BY ow.Probabilidade_Fechamento, r.Nome, o.Representante_ID";
		//echo $sql;
		$i = 0;
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$i++;
			$javascript .= "['".$rs['Representante']." - R$ ".number_format($rs['Valor'], 2, ',', '.')."',".$rs['Valor']."],";
		}
		echo " 	<div class='titulo-container' id='localiza-orcamento-retorno'>
					<div class='titulo'>
						<p>Propostas em aberto</p>
					</div>
					<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
		if ($i==0){
			echo "		<p align='center'>Nenhum registro localizado no per&iacute;odo selecionado</p>";
		}
		else{
			echo "		<script type='text/javascript' src='https://www.google.com/jsapi'></script>";
			echo "		<div id='div-pizza' style='width: 100%; float:left;' align='center'></div>
						<div id='div-barras' style='width: 100%; float:left;' align='center'></div>";
			echo "		<script type='text/javascript'>
							google.load('visualization', '1', {packages:['corechart']});
							google.setOnLoadCallback(drawChart);
							function drawChart() {
								var data = google.visualization.arrayToDataTable([
									['Representante', 'R$ Valor'],
									".substr($javascript,0,-1)."
									]);
								var options = {title: '',is3D: true,};
								var grafico = new google.visualization.PieChart(document.getElementById('div-pizza'));
								grafico.draw(data, options);
							}
							google.load('visualization', '1', {packages:['corechart']});
							google.setOnLoadCallback(drawChart2);
							function drawChart2() {
								var data = google.visualization.arrayToDataTable([
									  ['Representante', 'Valor'], ".substr($javascript,0,-1)."
								]);
								var options = {
								  title: '',
								  is3D: true,
								};
								var chart = new google.visualization.ColumnChart(document.getElementById('div-barras'));
								chart.draw(data, options);
							}
						</script>";
		}
		echo "		</div>
				</div>";

	}

	/**************************************/
	/******* PROBABILIDADE FECHAMENTO *****/
	/**************************************/
	if ($filtroRelatorio=='menu-superior-5'){

	}
?>