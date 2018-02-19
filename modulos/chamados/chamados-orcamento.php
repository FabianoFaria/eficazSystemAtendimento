<?php
	//include("functions.php");
	global $modulosAtivos, $caminhoSistema, $dadosUserLogin;
	$contEmpresas 		= verificaNumeroEmpresas();
	$workflowID 		= $_POST["workflow-id"];


	$condicaoSituacao 	= " and Tipo_ID != 111 ";
	$textoDescricao 	= "Descri&ccedil;&atilde;o";
	if ($workflowID!=""){
		$sql = "SELECT 
					w.Empresa_ID, 
					w.Solicitante_ID, 
					w.Representante_ID, 
					w.Situacao_ID, 
					w.Codigo, w.Titulo, 
					w.Data_Abertura, 
					w.Data_Finalizado,
					w.Data_Cadastro, 
					w.Usuario_Cadastro_ID,
					w.Origem_ID,
					w.Parceiro_ID,
					s.Descr_Tipo as Situacao
				FROM orcamentos_workflows w
				LEFT JOIN tipo s on s.Tipo_ID = w.Situacao_ID
				WHERE Workflow_ID = '$workflowID'";

		// echo $sql;
		// die();

		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$cadastro 			= $rs['Cadastro'];
			$empresaID 			= $rs['Empresa_ID'];
			$solicitanteID 		= $rs['Solicitante_ID'];
			$representanteID 	= $rs['Representante_ID'];
			$situacaoID 		= $rs['Situacao_ID'];
			$situacao 			= $rs['Situacao'];

			$dataAbertura 		= converteData($rs['Data_Abertura']);
			$dataFinalizado 	= converteData($rs['Data_Finalizado']);
			$tituloOrcamento	= $rs['Titulo'];
			$usuarioCadastroID 	= $rs['Usuario_Cadastro_ID'];
			$codigo 			= $rs['Codigo'];

			$origemOrc 			= $rs['Origem_ID'];
			$parceiroIndic 		= $rs['Usuario_Cadastro_ID'];

			$sql = "SELECT 
						Follow_ID, 
						Descricao, 
						Dados, 
						t.Descr_Tipo as Situacao, 
						f.Situacao_ID as Situacao_ID, 
						DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, 
						cd.Nome as Usuario_Follow
					FROM orcamentos_follows f
					LEFT JOIN cadastros_dados cd on cd.Cadastro_ID = f.Usuario_Cadastro_ID
					LEFT JOIN tipo t on t.Tipo_ID = f.Situacao_ID
					WHERE Workflow_ID = $workflowID
					ORDER BY f.Follow_ID desc";

			// echo $sql;
			// die();
			$query = mpress_query($sql);
			$i=0;
			while($rs = mpress_fetch_array($query)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br($rs['Descricao'])."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
			}
			if($i>=1){
				$largura = "99%";
				$dados[colunas][titulo][1] 	= "Observa&ccedil;&otilde;es Cadastradas";
				$dados[colunas][tamanho][1] = "width=''";
				$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
				$dados[colunas][tamanho][2] = "width='180px'";
				$dados[colunas][titulo][3] 	= "Data";
				$dados[colunas][tamanho][3] = "width='180px'";
			}
			$descricaoBotao = "Atualizar Or&ccedil;amento";
			$dadosOrcamento = "N&ordm; $workflowID - $situacao";

			if (($situacaoID=="112")||($situacaoID=="113")){
				$dadosReabertura 		= "<input type='button' value='Re-Abrir Or&ccedil;amento' id='botao-reabrir-orcamento'  Style='width:115px;'/>";
				$textoDescricao 		= "Descreva o motivo da re-abertura";
				$condicaoSituacao 		= " and Tipo_ID = 111";
				$escondeDadosFollows 	= "esconde";
				$situacaoIDAux 			= "111";

			}
			else{
				$dadosOrcamento .= "<input type='button' value='Atualizar Dados' class='botao-salvar-orcamento'  Style=''/>";
				$situacaoIDAux = $situacaoID;
				if ($situacaoID=="111"){
					$condicaoSituacao = "";
				}
			}


		}
	}
	else{
		$descricaoBotao = "Abrir Or&ccedil;amento";
		$classeEsconde 	= "esconde";
		$dataAbertura 	= retornaDataHora('d','d/m/Y H:i');
		if ($_POST['cadastro-id']!="") $solicitanteID = $_POST['cadastro-id'];
		//echo  $solicitanteID;
		$representanteID 	 = $dadosUserLogin[userID];
		$situacaoIDAux = 110;
	}


	if ($contEmpresas==1){
		if (($cadastroID=="")||($cadastroID=="0")){
			$empresaID = retornaCodigoEmpresa();
		}
		$empresasCadastradas = "<input type='hidden' id='empresa-id' name='empresa-id' value='$empresaID' class='dados-orc'/>";
	}
	else{
		$empresasCadastradas = "<div style='float:left;width:100%;'><b>Empresa Respons&aacute;vel</b></div>
								<div style='float:left;width:100%;margin-bottom:3px'><select id='empresa-id' name='empresa-id' class='dados-orc' style='width:99.7%'><option value=''>Selecione</option>".optionValueEmpresas($empresaID)."</select></div>";
	}

	$sql = "Select Email from cadastros_dados where Cadastro_ID = ".$dadosUserLogin['userID'];
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){

		$emailLogado = $rs[Email];
	}


	if (($dadosUserLogin['grupoID']==-3) || ($dadosUserLogin['grupoID']==-2)){
		
		$condicoesRepresentante = " and cd.Cadastro_ID = ".$dadosUserLogin['userID'];
	}

?>
	<input type='hidden' id='workflow-id' name='workflow-id' value='<?php echo $workflowID;?>' class='dados-orc'/>
	<input type='hidden' id='produto-id'  name='produto-id' value='' class='dados-orc'/>
	<input type='hidden' id='tipo-listagem'  name='tipo-listagem' value='<?php echo $configChamados['listagem-orcamento']; ?>' class='dados-orc'/>
	<input type='hidden' id='agrupar-produtos'  name='agrupar-produtos' value='<?php echo $configChamados['agrupar-produtos']; ?>'/>
	<input type='hidden' id='id-turma'  name='id-turma' value='' class='dados-orc'/>
	<input type='hidden' id='tabela-estrangeira'  name='tabela-estrangeira' value='chamados'/>


	<input type='hidden' class='email-workflow' id='email-workflow' value='<?php echo $emailLogado; ?>'/>
		<div id='div-retorno'></div>
		<div class="titulo-container conjunto1" id='div-solicitante-dados'>
			<div class="titulo">
				<p>	Dados do Solicitante
				<?php				
					
					if($dadosUserLogin['grupoID'] != -3){
						echo "<input type='button' class='editar-cadastro-generico' style='float:right;margin-right:0px; width:50px;' value='Alterar' id='botao-alterar-solicitante-id' campo-alvo='solicitante-id'>";
					}

				?>
				</p>
			</div>
			<div class='conteudo-interno' id='conteudo-interno-solicitante'>
				<?php 
					carregarBlocoCadastroGeral($solicitanteID, 'solicitante-id','Solicitante',1,'','','','required dados-orc'); 
				?>
			</div>
		</div>

		<div class="titulo-container conjunto1 <?php echo $classEsconde;?>" id='div-orcamento-dados' >
			<div class="titulo">
				<!--<div class='btn-retrair btn-expandir-retrair-orcamento' style='float:right;' title='Expandir'></div>-->
				<p>Dados Gerais <?php echo $dadosOrcamento; ?>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario' id='conteudo-interno-orcamento'>
				<?php 
					echo $empresasCadastradas;
				?>
				<div class='titulo-secundario' style='width:5%;float:left;'>
					<p><b>ID</b></p>
					<p><input type='text' value='<?php echo $workflowID; ?>' class='dados-orc' style='width:89%' readonly/></p>
				</div>
				<div class='titulo-secundario' style='width:10%;float:left;'>
					<p><b>C&oacute;digo</b></p>
					<p><input type="text" id="codigo-workflow" name="codigo-workflow" value='<?php echo $codigo; ?>' class='dados-orc' style='width:89%' maxlength='50'/></p>
				</div>

				<div class='titulo-secundario' style='width:15%;float:left;'>
					<p><b>Data Abertura</b></p>
					<p><input type="text" id="data-abertura-orcamento" name="data-abertura-orcamento" class='formata-data-meia-hora required dados-orc' value='<?php echo $dataAbertura; ?>' style='width:92%' maxlength='16'/></p>
				</div>

<?php
	echo "		<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Representante Respons&aacute;vel</b></p>
					<p><select name='representante-orcamento' id='representante-orcamento' class='dados-orc' style='width:98.5%'>".optionValueUsuarios($representanteID, unserialize($configChamados['orcamento-grupos-responsaveis']))."</select></p>
				</div>";
	echo "		<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>T&iacute;tulo</b></p>
					<p class='omega' Style='float:left;'><input type='text' name='titulo-orcamento' id='titulo-orcamento' class='dados-orc' value='".$tituloOrcamento."' Style='width:97.5%;'></p>
				</div>";

	if (($workflowID=="") && ($modulosAtivos[projetos])){
	echo "		<div class='titulo-secundario' style='width:100%;float:left;'>
					<p><b>Processo de Atendimento</b></p>
					<p class='omega' Style='float:left;'>
						<select name='projeto-id-orcamento' id='projeto-id-orcamento' style='width:100%;float:left;' class='required dados-orc'>
							<option value=''>Selecione</option>";
	echo optionValueProjetos($projetoID, "orcamentos_workflows");
	echo "				</select>
					</p>
				</div>";
	}

	/**************************************************/
	/********** IMPLEMENTACAO MODULO CRM **************/
	/**************************************************/
	if ($configChamados['crm']=='1'){
		$sql = "select tot.Descr_Tipo as Origem, Origem_ID as Origem, o.Expectativa_Valor, o.Data_Previsao, o.Probabilidade_Fechamento, o.Tipo_ID
					from oportunidades_workflows o
					left join tipo tot on tot.Tipo_ID = o.Origem_ID
					where Orcamento_ID = '".$workflowID."'";
		//echo $sql;
		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$origemID = $rs['Origem'];
			$tipoOportunidadeID = $rs['Tipo_ID'];
			$dataPrevisao = substr(converteData($rs['Data_Previsao'],1),0,10);
			$probabilidadeFechamento = $rs['Probabilidade_Fechamento'];
			$expectativaValor = number_format($rs['Expectativa_Valor'], 2, ',', '.');
		}
		if ($dataPrevisao=="00/00/0000") $dataPrevisao = "";


		/*
			<div class='titulo-secundario' style='width:15%; float:left;'>
				<p><b>Origem</b></p>
				<p><select name='oportunidade-origem' id='oportunidade-origem' class='dados-orc'>".optionValueGrupo(76, $origemID, "Selecione")."</select></p>
			</div>
		*/

		/*

		*/

		echo "	<div class='titulo-secundario' style='padding-top:50px;'>
					
					<div class='titulo-secundario' style='width:15%; float:left;'>
						<p><b>Previs&atilde;o Fechamento</b></p>
						<p><input type='text' class='formata-data dados-orc' id='oportunidade-data-previsao-fechamento' name='oportunidade-data-previsao-fechamento' style='width:92%;' maxlength='' value='".$dataPrevisao."'/></p>
					</div>
					<div class='titulo-secundario' style='width:20%; float:left;'>
						<p><b>Tipo Oportunidade</b></p>
						<p><select id='oportunidade-tipo' name='oportunidade-tipo' class='dados-orc'>".optionValueGrupo(77, $tipoOportunidadeID, "Selecione")."</select>
					</div>
					<div class='titulo-secundario' style='width:25%; float:left;'>
						<p><b>Expectativa de Valor R$</b></p>
						<p><input type='text' class='formata-valor dados-orc' id='oportunidade-valor' name='oportunidade-valor' style='width:98%;' maxlength='20' value='".$expectativaValor."'/></p>
					</div>
					<div class='titulo-secundario' style='width:25%; float:left;'>
						<p><b>Probabilidade fechamento</b></p>
						<p><select id='oportunidade-probabilidade-fechamento' name='oportunidade-probabilidade-fechamento' class='dados-orc'>".optionValueCountSelect(100, $probabilidadeFechamento, "&nbsp;", "", " %")."</select></p>
					</div>
				</div>";

		$sql = "select tot.Descr_Tipo as Origem, Origem_ID as Origem, o.Expectativa_Valor, o.Data_Previsao, o.Probabilidade_Fechamento
					from oportunidades_workflows o
					left join tipo tot on tot.Tipo_ID = o.Origem_ID
					where Orcamento_ID = '".$workflowID."'";
		//echo $sql;
		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$origemID 					= $rs['Origem'];
			$dataPrevisao 				= substr(converteData($rs['Data_Previsao'],1),0,10);
			$probabilidadeFechamento 	= $rs['Probabilidade_Fechamento'];
			$expectativaValor 			= number_format($rs['Expectativa_Valor'], 2, ',', '.');
		}


	/**************************************************/
	/********************* FIM CRM  *******************/
	/**************************************************/

	}
		?>
			
			<!-- <div class='titulo-secundario' style='width:50%;float:left;'>
				<p><b>Origem do or&ccedil;amento</b></p>
				<p style='margin-left:5px' align='//left'>
					&nbsp;
					<select name="situacao-origem-workflow" id="situacao-origem-workflow" style='width:98.5%' class='required dados-orc'>
								<?php 
									//echo optionValueGrupo(76, $origemOrc, "Selecione", '');
								?> 
					</select>
				</p>
			</div> -->

			<div id="lista-parceiros" class="titulo-secundario" style='width:50%;float:left;display:none;'>
				<p><b>Parceiro que indicou</b></p>
				<select name="situacao-parceiro-workflow" id="situacao-parceiro-workflow" style='width:98.5%' class=' dados-orc'>
							<?php 
								echo optionValueParceirosGrupo('', '', '');
							?>
				</select>

			</div>

		<?php


	if ($workflowID!=""){
		?>
			</div>
		</div>
		<div class='titulo-container conjunto1' id='div-orcamento-dados' >
			<div class='titulo'>
				<p>Hist&oacute;rico <?php echo $dadosReabertura;?></p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
		<?php
	}
		?>

				<div class='dados-follows-orcamento <?php echo $escondeDadosFollows;?>'>
					<div class='titulo-secundario' style='width:100%;'>
						<p><b><?php echo $textoDescricao; ?></b></p>
						<p class='omega'>
							<textarea id='descricao-follow' name='descricao-follow' class='dados-orc' style='height:60px;width:99.3%'>
								
							</textarea>
						</p>
					</div>
					<div class='titulo-secundario' style='width:50%;float:left;'>
						<p><b>Situa&ccedil;&atilde;o</b></p>
						<p>
							<input type='hidden' name="situacao-atual" id="situacao-atual" class='dados-orc' value='<?php echo $situacaoID; ?>'/>
							<select name="situacao-follow-orcamento" id="situacao-follow-orcamento" style='width:98.5%' class='required dados-orc'>
								<?php 
									echo optionValueGrupo(51, $situacaoIDAux, "Selecione", $condicaoSituacao);
								?>
							<select>
						</p>
					</div>

					<div class='titulo-secundario' style='width:20%;float:left;'>
						<p style='margin-top:22px; margin-left:5px' align='center'>
							&nbsp;
							<input type='checkbox' id='enviar-email' name='enviar-email' class='dados-orc' checked value='1'/>&nbsp;<label for="enviar-email" style='cursor:pointer;'><b>ENVIAR EMAIL</b></label>
						</p>
					</div>

					<div class='titulo-secundario' style='width:15%;float:left;'>
						<div class='div-data-finalizado esconde'>
							<p><b>Data Finaliza&ccedil;&atilde;o</b></p>
							<p><input type="text" id="data-finalizado-orcamento" name="data-finalizado-orcamento" class='formata-data-hora dados-orc' style='width:91%' maxlength='16' value='<?php echo $dataFinalizado;?>'/></p>
						</div>
						&nbsp;
					</div>

					<div class='titulo-secundario' style='width:15%;float:right;'>
						<p style='margin-top:22px; margin-left:5px'>
						<p><input type='button' value='<?php echo $descricaoBotao;?>' class='botao-salvar-orcamento' id='botao-atualizar-situacao' Style='width:95%;'/></p>
					</div>
				</div>
<?php
	if ($workflowID!=""){
		echo "	<div class='titulo-secundario uma-coluna' Style='margin-top:5px;padding:0 0 5px 5px;'>";
		geraTabela("99.4%","3",$dados);
		echo "	</div>";
	}
?>
			</div>
		</div>

		<div class="titulo-container esconde conjunto2" id='div-propostas'>
			<?php 
				carregarPropostasOrcamentos($workflowID, ""); 
			?>
		</div>

		<div class="titulo-container esconde conjunto5" id='div-financeiro-dados'>
			<div class="titulo">
				<p>Financeiro</p>
			</div>
			<div class='conteudo-interno' id='div-financeiro'>
				<?php 
					echo carregarFinanceiro($workflowID, 'orcamentos'); 
				?>		
			</div>
		</div>

		<!-- INICIO Bloco Upload usando PLUPLOAD -->

		<div id='div-documentos'></div>
		<div id="container">
			<input type="hidden" id="pickfiles"/>
			<input type="hidden" id="uploadfiles"/>
		</div>
		<!-- FIM Bloco Upload usando PLUPLOAD -->


		<div id='orcamentos-produtos-chamados'>
			<!--<iframe name='iframe-atualiza-proposta' id='iframe-atualiza-proposta'></iframe>-->
			<?php 
				carregarOrcamentosChamados($workflowID, "esconde");
			?>
		</div>

		<div class="titulo-container esconde conjunto6" id='div-tarefas-cadastradas-geral'>
			<?php 
				carregarTarefas($workflowID,'orcamentos_workflows', 'Workflow_ID', $solicitanteID);
			?>
		</div>
