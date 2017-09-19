<?php
include('functions.php');
global $dadosUserLogin, $modulosGeral, $modulosAtivos;

$tipoFluxo = $_GET['tipo-fluxo'];
if ($tipoFluxo=='direto'){
	echo "	<style>
				#topo-container{display:none;}
				#menu-container{display:none;}
			</style>";
	$campanhaID = $_GET['campanha-id'];
	$tipoInteracao = $_GET['tipo-interacao'];
}
else{
	$campanhaID = $_POST['campanha-id'];
	$tipoInteracao = $_POST['tipo-interacao'];
}

// POG para acertar registros que vem dos relatórios de tarefas
if (($workflowID!='') && ($campanhaID=='')){
	$sql = "select Campanha_ID from tele_workflows where Workflow_ID = '$workflowID'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$campanhaID = $rs['Campanha_ID'];
	}
}

echo "	<input type='hidden' id='campanha-id' name='campanha-id' value='$campanhaID'>
		<input type='hidden' id='tipo-interacao' name='tipo-interacao' value='$tipoInteracao'>
		<input type='hidden' id='cadastroID' name='cadastroID' value=''>
		<div id='div-retorno'></div>";


// ABAIXO DEFINE SE É OPERADOR
if ($modulosAtivos['tele-operacoes-gerenciar']==''){
	$sqlOperador = " inner join tele_campanhas_operadores tco on tco.Campanha_ID = tc.Campanha_ID ";
	$escondeOperador = " esconde ";
}


if (($campanhaID=='') || ($tipoInteracao=='')){
	$i = 0;
	$sql = "select tc.Campanha_ID, tc.Operacao_ID, tc.Nome as Campanha, tc.Formulario_ID, tc.Situacao_ID,
					tc.Data_Cadastro, tc.Usuario_Cadastro_ID, s.Descr_Tipo AS Situacao,
					top.Nome as Operacao, tpop.Descr_Tipo as Tipo_Campanha
			from tele_campanhas tc
			inner join tipo s ON s.Tipo_ID = tc.Situacao_ID
			inner join tele_operacoes top on top.Operacao_ID = tc.Operacao_ID
			".$sqlOperador."
			left join tipo tpop on tpop.Tipo_ID = tc.Tipo_Campanha_ID
			where tc.Campanha_ID > 0 and tc.Situacao_ID = 161
			group by tc.Campanha_ID, tc.Operacao_ID
			order by tc.Nome, tc.Campanha_ID, tc.Operacao_ID";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($rs = mpress_fetch_array($resultado)){
		$i++;
		$html .= "	<table cellspacing='0' align='center' width='100%;' style='margin-bottom:10px;'>
						<tr>
							<td style='background-color:#E5ECF1; text-transform: uppercase; height:30px; font-size:17px' align='center' colspan='4'>
								<b>".$rs['Operacao']."</b>
							</td>
						</tr>";
		$html .= "		<tr style='background-color:#f9f9f9; font-size:17px'>
							<td align='center'><p>".$rs['Campanha']." - ".$rs['Tipo_Campanha']."</p></td>
							<td style='background-color:#f9f9f9; cursor:pointer;' align='center' width='10%' class='relatorios-campanha $escondeOperador' campanha-id='".$rs['Campanha_ID']."'>
								<img src='".$caminhoSistema."/images/geral/relatorio.png' height='30' />
								<p style='font-size:x-small'>Relat&oacute;rios</p>
							</td>
							<td style='background-color:#f9f9f9; cursor:pointer;' align='center' width='10%' class='operar-campanha' tipo-interacao='listagem' campanha-id='".$rs['Campanha_ID']."'>
								<img src='".$caminhoSistema."/images/geral/lista.png' height='30' />
								<p style='font-size:x-small'>Listagem</p>
							</td>
							<td style='background-color:#f9f9f9; cursor:pointer;' align='center' width='10%' class='operar-campanha' tipo-interacao='ativo' campanha-id='".$rs['Campanha_ID']."'>
								<img src='".$caminhoSistema."/images/geral/entrar-tele.png' height='30'/>
								<p style='font-size:x-small'>Ativo</p>
							</td>
						</tr>";
		$html .= "	</table>";
	}
	if ($i==0){
		$html = "<p style='font-size:17px' align='center'>Nenhuma opera&ccedil;&atilde;o cadastrada</p>";
	}
	echo $html;
}
else{
	/**************************/
	/* INICIO - TELA OPERACAO */
	/**************************/
	if (($tipoInteracao=='ativo') || ($tipoInteracao=='direta')){

		/* TRECHO PARA SELECIONAR UM WORKFLOW NOVO CASO NÃO TENHA NENHUM SOB A RESPONSABILIDADE DA PESSOA QUE ESTA INTERAGINDO
		campos que definem são chave
		SITUACOES
		164, 'Aberto'
		165, 'Em andamento'
		166, 'Cancelado'
		167, 'Finalizado'
		168, 'Re-aberto'
		*/
		if ($tipoInteracao=='ativo'){
			$sql = "select Workflow_ID from tele_workflows tw where Chave = '1' and Campanha_ID = '$campanhaID' and Responsavel_ID = '".$dadosUserLogin['userID']."'";
			$resultado = mpress_query($sql);
			if($rs = mpress_fetch_array($resultado)){
				$workflowID = $rs['Workflow_ID'];
			}
			else{
				$resultado1 = mpress_query("select Workflow_ID from tele_workflows tw where Chave = '0' and Campanha_ID = '$campanhaID' and Responsavel_ID = 0 order by Workflow_ID");
				if($rs1 = mpress_fetch_array($resultado1)){
					$workflowID = $rs1['Workflow_ID'];
					mpress_query("update tele_workflows set Responsavel_ID = '".$dadosUserLogin['userID']."', Chave = '1' where Workflow_ID = '$workflowID'");
					//mpress_query("update tele_workflows set Chave = '1' where Workflow_ID = '$workflowID'");
				}
				else{
					// AQUI TRECHO QUE FINALIZA A LISTAGEM ATIVA
					exit("<p align='center'>FIM DE LISTAGEM</p>");
				}
			}
		}
		if ($tipoInteracao=='direta'){
			//$workflowID = $_POST['workflow-id'];
			//$workflowID = $_GET['workflow-id'];
			//$botaoInteracao = "<input type='button' value='Voltar' class='voltar-listagem-campanha' Style='width:150px;'/>";
		}
		if ($tipoInteracao=='ativo'){
			$botaoInteracao = "	<input type='hidden' name='registro-atualizado' id='registro-atualizado' value='".$_POST['registro-atualizado']."'/>
								<input type='button' value='Localizar registro' class='localizar-registro-workflow' fluxo='frente' Style='font-size:11px; width:100px;'/>
								<input type='button' value='Ir pr&oacute;ximo registro' class='workflow-andar' fluxo='frente' Style='font-size:11px; width:100px;'/>";
		}
		$botaoInteracao .= "";



		/**********************************************************/
		/************* CARREGANDO DADOS DO WORKFLOW ***************/
		/**********************************************************/
		$sql = "select w.Cadastro_ID, w.Codigo, w.Responsavel_ID, w.Resumo, w.Situacao_ID,
										c.Nome as Campanha, o.Nome as Operacao, tc.Descr_Tipo as Tipo_Campanha,
										c.Campanha_Conta_ID, c.Tipo_Campanha_ID, c.Formulario_ID
										from tele_workflows w
										inner join tele_campanhas c on c.Campanha_ID = w.Campanha_ID
										inner join tele_operacoes o on o.Operacao_ID = c.Operacao_ID
										inner join tipo tc on tc.Tipo_ID = c.Tipo_Campanha_ID
									where w.Workflow_ID = '$workflowID'";
		//echo $sql;
		$resultSet = mpress_query($sql);
		if($rs = mpress_fetch_array($resultSet)){
			$cadastroID = $rs['Cadastro_ID'];
			$codigo = $rs['Codigo'];
			$responsavelID = $rs['Responsavel_ID'];
			$resumo = $rs['Resumo'];
			$situacaoID = $rs['Situacao_ID'];
			$campanhaContaID = $rs['Campanha_Conta_ID'];
			$tipoCampanhaID = $rs['Tipo_Campanha_ID'];
			$formularioID = $rs['Formulario_ID'];

			$condicaoSituacao .= " and Tipo_ID <> 168";

			$sql = "select f.Descricao, cd.Nome as Usuario, s.Descr_Tipo as Situacao, f.Situacao_ID, f.Data_Cadastro, tlc.Motivo_ID, tm.Descr_Tipo as Motivo, tlc.Descricao as Motivo_Outros
						FROM tele_follows f
						INNER JOIN tipo s ON s.Tipo_ID = f.Situacao_ID
						INNER JOIN cadastros_dados cd ON cd.Cadastro_ID = f.Usuario_Cadastro_ID
						LEFT JOIN tele_follows_cancelados tlc ON tlc.Follow_ID = f.Follow_ID AND tlc.Workflow_ID = f.Workflow_ID
						left join tipo tm on tm.Tipo_ID = tlc.Motivo_ID
						WHERE f.Workflow_ID = '$workflowID'
						ORDER BY f.Follow_ID DESC";
			//echo $sql;
			$query2 = mpress_query($sql);
			$f=0;
			while($rs2 = mpress_fetch_array($query2)){
				$f++;
				if ($f==1){
					$motivoOutros = $rs2['Motivo_Outros'];
					$motivoID = $rs2['Motivo_ID'];
				}
				$hCancelamento = "";
				if ($rs2['Situacao_ID']==166){
					if ($rs2['Motivo_Outros'] != ''){
						$hCancelamento = "	<br><b>Motivo:</b> ".$rs2['Motivo'];
						if ($rs2['Motivo_ID']==169){
							$hCancelamento .= " - ".$rs2['Motivo_Outros'];
						}
					}
				}


				$dadosFollow[colunas][conteudo][$f][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br($rs2['Descricao'])."</p>";
				$dadosFollow[colunas][conteudo][$f][2] = "	<div Style='margin:2px 5px 0 5px;float:left;'>
																".$rs2['Situacao']."
																".$hCancelamento."
															</div>";
				$dadosFollow[colunas][conteudo][$f][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs2['Usuario']."</p>";
				$dadosFollow[colunas][conteudo][$f][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($rs2['Data_Cadastro'],1)."</p>";
			}
			if($f>=1){
				$largura = "99%";
				$dadosFollow[colunas][titulo][1] 	= "Hist&oacute;rico";
				$dadosFollow[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
				$dadosFollow[colunas][titulo][3] 	= "Usu&aacute;rio";
				$dadosFollow[colunas][titulo][4] 	= "Data";

				$dadosFollow[colunas][tamanho][1] = "width=''";
				$dadosFollow[colunas][tamanho][2] = "width='20%'";
				$dadosFollow[colunas][tamanho][3] = "width='20%'";
				$dadosFollow[colunas][tamanho][4] = "width='10%'";

			}
		}

		echo "	<input type='hidden' id='workflow-id' name='workflow-id' value='$workflowID'/>
				<input type='hidden' class='email-workflow' id='email-workflow' value='<?php echo $emailLogado; ?>'/>
				<input type='hidden' class='confim-aux' id='confim-aux' name='confim-aux' value=''/>
				<div class='titulo-container conjunto1'>
					<div class='titulo' style='height:30px'>
						<div style='float:left; margin-top:7px; width:30%;'>
							".$rs['Operacao']." - $workflowID
						</div>
						<div style='float:left; margin-top:8px; text-align: center; width:40%;'>
							".$rs['Tipo_Campanha']." ".$rs['Campanha']."
						</div>
						<div style='float:right; margin-top:-20px; margin-right:1px; text-align: right; width:30%;'>
							<!--
							<img  src='".$caminhoSistema."/images/geral/next.png' style='cursor:pointer;' height='30' align='right' title='P&oacute;ximo registro'/>
							<input type='button' value='Atualizar' class='atualizar-workflow' Style='width:100px;'/>
								-->
							$botaoInteracao
						</div>
					</div>
					<div class='conteudo-interno div-dados-workflow-telemarketing' id='conteudo-interno-cliente'>";
		carregarBlocoCadastroGeral($cadastroID, 'cadastro-id','Solicitante',1,'','','','required');
		echo "		</div>";
		echo "		<div class='conteudo-interno titulo-secundario div-dados-workflow-telemarketing' style='margin-top:5px;'>
						<p><b>Descri&ccedil;&atilde;o</b></p>
						<div class='titulo-secundario' style='width:100%;'>
							<p><?php echo $textoDescricao; ?></p>
							<p class='omega'><textarea id='descricao-follow' name='descricao-follow' class='required' style='height:60px;width:99.3%'></textarea></p>
						</div>
						<div class='titulo-secundario' style='width:100%;float:left; margin-top:5px;'>
							<div class='titulo-secundario' style='width:50%;float:left;'>
								<p><b>Situa&ccedil;&atilde;o</b></p>
								<p>
									<input type='hidden' name='situacao-atual' id='situacao-atual' value='$situacaoID'/>
									<select name='situacao-id' id='situacao-id' class='required'>
										".optionValueGrupo(69, $situacaoID, 'Selecione', $condicaoSituacao)."
									<select>
									<!--optionValueSituacaoOperacao(69, $situacaoID, 'Selecione', $condicaoSituacao)-->
								</p>
							</div>
							<div class='titulo-secundario' style='width:50%;float:left;'>
								<p><b>Respons&aacute;vel</b></p>
								<p>
									<select name='responsavel-id' id='responsavel-id' style='width:98.5%' class='required'>
										".optionValueUsuarios($responsavelID,"", "")."
									</select>
								</p>
							</div>
						</div>";
		echo "			<div class='bloco-motivos-cancelmento titulo-secundario' style='width:100%;float:left; margin-top:5px;'>
							<div class='titulo-secundario' style='width:50%;float:left;'>
								<p><b>Motivo</b></p>
								<p>
									<select name='motivo-id' id='motivo-id' class='' Style='width:99%'>
										".optionValueGrupo(70, $motivoID, 'Selecione', '')."
									<select>
								</p>
							</div>
							<div class='bloco-motivo-outros esconde titulo-secundario' style='width:50%;float:left;'>
								<p><b>Qual?</b></p>
								<p><input type='text' name='motivo-outros' id='motivo-outros' value='$motivoOutros' Style='width:99%'/></p>
							</div>
						</div>
					</div>
					<div class='conteudo-interno titulo-secundario' style='margin-top:5px;'>";
		/* BLOCO FORMULARIOS DINAMICOS */
		if (($formularioID!=0) && ($formularioID!="")){
			echo "		<div class='titulo-secundario' style='width:100%;float:left; margin-top:25px;'>
							".montarFormularioDinamico($formularioID, 'cadastros_dados', $cadastroID, 'integrado')."
						</div>";
		}

		if (($modulosAtivos['tele-campanhas-gerenciar']) && ($f==0)){
			$btnExcluir = "<input type='button' value='Excluir' class='excluir-workflow' Style='font-size:11px; float:right; width:120px; margin-left:2px;'/>";
		}
		echo "			<div class='titulo-secundario' style='width:30%;float:right;'>
							<p style='margin-top:15px; margin-left:5px'>
							<p><input type='button' value='Atualizar' class='atualizar-workflow' Style='font-size:11px; float:right; width:120px; margin-left:2px;'/>$btnExcluir</p>
						</div>";


		// CAMPANHA DE PROSPECÇÃO
		if ($tipoCampanhaID=='157'){
			echo "		<div class='titulo-secundario' style='width:40%;float:right;'>
							<p style='margin-top:15px; margin-left:5px'>
							<p align='center'><input type='button' value='Incluir Oportunidade' class='incluir-oportunidade' Style='font-size:11px; width:120px; margin-left:2px;'/></p>
						</div>";
		}



		if ($f>0){
			echo "		<div class='titulo-secundario uma-coluna' Style='margin-top:30px;padding:0 0 5px 5px;'>";
			geraTabela("99.4%","4",$dadosFollow, null, 'tele-workflows-follows', 2, 2, 10,1);
			echo "		</div>";
		}

		echo "		</div>
				</div>";

		/* BLOCO COBRANÇAS */
		if (($tipoCampanhaID==154) && ($campanhaContaID <> "")){
			echo "	<div class='titulo-container conjunto1'>
						<div class='titulo' style='height:30px'>
							<div style='float:left; margin-top:7px;'>
								T&Iacute;tulos de Cobran&ccedil;a
							</div>
						</div>
						<div id='bloco-titulos-cadastro' class='conteudo-interno titulo-secundario' style='margin-top:5px;'>".carregarTitulosCadastro($cadastroID)."</div>
					</div>";
		}

		echo "	<!-- INICIO Bloco Upload usando PLUPLOAD -->
				<div id='div-documentos' class='div-tele-documentos'></div>
				<div id='container'>
					<input type='hidden' id='pickfiles'/>
					<input type='hidden' id='uploadfiles'/>
				</div>
				<!-- FIM Bloco Upload usando PLUPLOAD -->";
		echo "<div class='titulo-container esconde conjunto2' id='div-tarefas-cadastradas-geral'>";
		carregarTarefas($workflowID,'tele_workflows', 'Workflow_ID', $cadastroID);
		echo "</div>";

	}
	/***********************/
	/* FIM - TELA OPERACAO */
	/***********************/


	if ($tipoInteracao=='listagem'){

		$localizaWorkflowID = $_POST['localiza-workflow-id'];
		$localizaCadastro = $_POST['localiza-cadastro'];
		$localizaResponsavel = $_POST['localiza-responsavel'];
		$localizaEndereco = $_POST['localiza-endereco'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-situacao']); $i++){
			$localizaSituacao .= $virgula.$_POST['localiza-situacao'][$i];
			$virgula = ",";
		}

		$virgula = "";
		foreach($_POST['localiza-responsavel'] as $locRespID){
			$localizaResponsavel2 .= $virgula.$locRespID;
			$virgula = ",";
		}
		//echo $localizaResponsavel2;

		if ($tipoFluxo!='direto')
			$botaoVoltarListagem = "<input type='button' value='Voltar' class='voltar-listagem-operacoes' Style='width:150px;'/>";


		if ($localizaCadastro!='')
			$sqlCond .= " and (c.Nome like '$localizaCadastro%' or c.Nome like '$localizaCadastro%') ";

		if ($localizaResponsavel2!='')
			$sqlCond .= " and w.Responsavel_ID in ($localizaResponsavel2) ";

		if ($localizaWorkflowID!='')
			$sqlCond .= " and w.Workflow_ID = '$localizaWorkflowID' ";

		if ($localizaSituacao!="")
			$sqlCond .= " and w.Situacao_ID IN ($localizaSituacao) ";

		if ($localizaEndereco!="")
			$sqlCond .= " and concat(ce.Logradouro , ' ', ce.Numero, ' ',  ce.Complemento, ' ',  ce.Bairro, ' ', ce.Cidade, ' ', ce.UF, ' ', ce.Referencia) like '%$localizaEndereco%' ";

		if($_POST['ordena-tabela'] != ""){
			$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
		}else{
			$ordem = " order by w.Workflow_ID";
		}

		$sql = "SELECT w.Workflow_ID, c.Nome as Cadastro, w.Codigo, Chave, r.Nome as Responsavel, s.Descr_Tipo as Situacao, w.Resumo, count(tf.Follow_ID) as QtdFollows,
						concat(ce.Logradouro , ' ', ce.Numero, ' ',  ce.Complemento, ' ',  ce.Bairro, ' ', ce.Cidade, ' ', ce.UF, ' ', ce.Referencia) as Endereco,
						tf.Data_Cadastro as UltimaInteracao
					FROM tele_workflows w
					INNER JOIN tipo s on s.Tipo_ID = w.Situacao_ID
					INNER JOIN cadastros_dados c on c.Cadastro_ID = w.Cadastro_ID
					LEFT JOIN tele_follows tf on tf.Workflow_ID = w.Workflow_ID
										and tf.Follow_ID = (select max(tfe.Follow_ID) from tele_follows tfe where tfe.Workflow_ID = w.Workflow_ID)
					LEFT JOIN cadastros_dados r on r.Cadastro_ID = w.Responsavel_ID
					LEFT JOIN cadastros_enderecos ce ON ce.Cadastro_ID = w.Cadastro_ID
						AND ce.Cadastro_Endereco_ID = (select min(cee.Cadastro_Endereco_ID) from cadastros_enderecos cee where cee.Cadastro_ID = c.Cadastro_ID)
						WHERE w.Campanha_ID = '$campanhaID'
						$sqlCond
					GROUP BY w.Workflow_ID, c.Nome, w.Codigo, Chave, r.Nome, s.Descr_Tipo, w.Resumo
					$ordem";
		//echo $sql;
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			$dados[colunas][tr][$i] = "class='lnk link-workflow .lk-".$rs['Workflow_ID']."' style='font-weight: bold; cursor:pointer;' workflow-id='".$rs['Workflow_ID']."' ";
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Workflow_ID']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Cadastro']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Endereco']."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Responsavel']."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($rs['UltimaInteracao'],1)."</p>";
			//$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['QtdFollows']."</p>";
		}
		$largura = "99%";
		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Cadastro";
		$dados[colunas][titulo][3] 	= "Endere&ccedil;o";
		$dados[colunas][titulo][4] 	= "Respons&aacute;vel";
		$dados[colunas][titulo][5] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][6] 	= "&Uacute;ltima Intera&ccedil;&atilde;o";
		//$dados[colunas][titulo][7] 	= "Qtd. Follows";

		$colunas = "6";

		$dados[colunas][ordena][1] 	= " w.Workflow_ID";
		$dados[colunas][ordena][2] 	= " c.Nome";
		$dados[colunas][ordena][3] = " Endereco";
		$dados[colunas][ordena][4] 	= " r.Nome";
		$dados[colunas][ordena][5] 	= " s.Descr_Tipo";
		$dados[colunas][ordena][6] 	= " UltimaInteracao";
		//$dados[colunas][ordena][7] 	= " count(tf.Follow_ID)";
		echo "	<input type='hidden' id='workflow-id' name='workflow-id' value=''/>

				<div class='titulo-container'>
					<div class='titulo'>
						<p>Filtros de pesquisa $botaoVoltarListagem</p>
					</div>
					<div class='conteudo-interno titulo-secundario' style='margin-top:5px;'>
						<div class='titulo-secundario' Style='float:left;width:10%;'>
							<p>ID</p>
							<p><input type='text' id='localiza-workflow-id' name='localiza-workflow-id' value='".$localizaWorkflowID."' style='width:95%'/></p>
						</div>
						<div class='titulo-secundario' Style='float:left;width:20%'>
							<p>Cadastro</p>
							<p><input type='text' id='localiza-cadastro' name='localiza-cadastro' value='".$localizaCadastro."' style='width:95%'/></p>
						</div>
						<div class='titulo-secundario' Style='float:left;width:20%'>
							<p>Endere&ccedil;o</p>
							<p><input type='text' id='localiza-endereco' name='localiza-endereco' value='".$localizaEndereco."' style='width:95%'/></p>
						</div>
						<div class='titulo-secundario' Style='float:left;width:20%;'>
							<p>Respons&aacute;vel</p>
							<p>
								<select id='localiza-responsavel' name='localiza-responsavel[]' multiple>
									".optionValueUsuarios($localizaResponsavel,"", "")."
								</select>
							</p>
						</div>
						<div class='titulo-secundario' Style='float:left;width:20%;'>
							<p>Situa&ccedil;&atilde;o</p>
							<p>
								<select id='localiza-situacao' name='localiza-situacao[]' multiple>
									".optionValueGrupoMultiplo(69, $localizaSituacao, '')."
								</select>
							</p>
						</div>
						<div class='titulo-secundario' Style='float:left;width:10%;'>
							<p style='margin-top:15px;'>
								<input type='button' value='Pesquisar' class='localizar-workflow-tele' style='width:95%;float:right;'/>
							</p>
						</div>
					</div>
				</div>

				<div class='titulo-container'>
					<div class='titulo'>
						<p>N&ordm; de registros: $i</p>
					</div>
					<div class='conteudo-interno titulo-secundario' style='margin-top:5px;'>
						<div class='titulo-secundario uma-coluna' Style=''>";
		geraTabela("99.4%",$colunas,$dados, null, 'tele-workflows', 2, 2, 100,1);

		echo "			</div>
					</div>
				</div>";
	}
}
?>