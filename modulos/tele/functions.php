<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");


	function salvarOperacao(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$operacaoID = $_POST['operacao-id'];

		$nome = utf8_decode($_POST['nome']);
		$empresaID = $_POST['empresa-id'];
		$fluxoOperacao = $_POST['fluxo-operacao'];
		$tipoOperacaoID = $_POST['tipo-operacao-id'];
		$situacaoID = $_POST['situacao-id'];

		if ($operacaoID==''){
			$sql = "INSERT INTO tele_operacoes
						(Nome, Empresa_ID, Fluxo_Operacao, Tipo_Operacao_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
					VALUES
						('$nome', '$empresaID', '$fluxoOperacao', '$tipoOperacaoID', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$operacaoID = mpress_identity();
		}
		else{
			$sql = "UPDATE tele_operacoes
						set Nome = '$nome',
							Empresa_ID = '$empresaID',
							Fluxo_Operacao = '$fluxoOperacao',
							Tipo_Operacao_ID = '$tipoOperacaoID',
							Situacao_ID = '$situacaoID'
						where Operacao_ID = '$operacaoID'";
			mpress_query($sql);
		}
		//echo $sql."<br>";
		echo $operacaoID;
	}

	function optionValueListasCampanhas($listasID, $sqlCond){
		foreach($listasID as $listaID){
			$sel[$listaID] = " selected ";
		}
		$sql = "select l.Lista_ID, l.Descricao from modulos_listas l where l.Situacao_ID = 1";
		$resultset = mpress_query($sql);
		while($rs = mpress_fetch_array($resultset)){
			$optionValue .= "<option value='".$rs['Lista_ID']."' ".$sel[$rs['Lista_ID']].">".$rs['Descricao']."</option>";
		}
		return $optionValue;
	}


	function optionValueOperacoes($selecionado, $empresaID){
		if ($selecionado!=''){
			$sqlCond = " or em.Cadastro_ID = '$selecionado'";
		}
		$sel[$selecionado] = " selected ";
		$resultset = mpress_query("SELECT Operacao_ID, Nome FROM tele_operacoes where Situacao_ID = '1' /* and (Fluxo_Operacao = 'A' or Fluxo_Operacao = 'AR') */");
		while($rs = mpress_fetch_array($resultset)){
			$optionValue .= "<option value='".$rs['Operacao_ID']."' ".$sel[$rs['Operacao_ID']].">".$rs['Nome']."</option>";
		}
		return $optionValue;
	}


	function salvarCampanha(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$campanhaID = $_POST['campanha-id'];
		$operacaoID = $_POST['operacao-id'];
		$formularioID = $_POST['formulario-id'];
		$tipoCampanhaID = $_POST['tipo-campanha-id'];
		$nome = utf8_decode($_POST['nome']);
		$situacaoID = $_POST['situacao-id'];
		$campanhaContaID = $_POST['campanha-conta-id'];
		$dadosCampanha = serialize($_POST['config']);

		if ($campanhaID==''){
			$sql = "INSERT INTO tele_campanhas (Nome, Operacao_ID, Tipo_Campanha_ID, Formulario_ID, Campanha_Conta_ID, Dados_Campanha, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										VALUES ('$nome', '$operacaoID', '$tipoCampanhaID', '$formularioID', '$campanhaContaID', '$dadosCampanha', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$campanhaID = mpress_identity();
		}
		else{
			$sql = "UPDATE tele_campanhas
						set Nome = '$nome',
							Operacao_ID = '$operacaoID',
							Formulario_ID = '$formularioID',
							Tipo_Campanha_ID = '$tipoCampanhaID',
							Campanha_Conta_ID = '$campanhaContaID',
							Situacao_ID = '$situacaoID',
							Dados_Campanha = '$dadosCampanha'
						where Campanha_ID = '$campanhaID '";
			mpress_query($sql);

			$sql = "UPDATE tele_campanhas_listas set Situacao_ID = 3 where Campanha_ID = '$campanhaID'";
			//echo "<br>$sql;";
			mpress_query($sql);
		}

		foreach($_POST['listagem-campanha'] as $listaID){
			$sql = "INSERT INTO tele_campanhas_listas (Campanha_ID, Lista_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
												VALUES ($campanhaID, $listaID, 1,  $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			//echo "<br>$sql;";
			mpress_query($sql);
		}

		if ($situacaoID=='161'){
			$sql = "INSERT INTO tele_workflows (Campanha_ID, Cadastro_ID, Codigo, Responsavel_ID, Resumo, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
						select cl.Campanha_ID, lc.Cadastro_ID, '', 0, '', 164, $dataHoraAtual, '".$dadosUserLogin['userID']."'
						 from tele_campanhas_listas cl
						inner join modulos_listas_cadastros lc on lc.Lista_ID = cl.Lista_ID
						where cl.Campanha_ID = '$campanhaID' and cl.Situacao_ID = '1'
							and lc.Cadastro_ID not in (Select Cadastro_ID from tele_workflows tw where tw.Campanha_ID = '$campanhaID')";
			mpress_query($sql);
		}
		echo $campanhaID;
	}


	function campanhasCarregarIncluirUsuario($usuarioID){
		global $caminhoSistema;
		$campanhaID = $_POST['campanha-id'];
		$html = "<div class='titulo-secundario' style='float:left; width:70%; margin-bottom:15px;'>
					<p><b>Selecione o Usu&aacute;rio</b></p>
					<p>
						<select name='usuario-id' id='usuario-id' class='required'>
							<option value=''>Selecione</option>";
		$sel[$selecionado] = " selected ";
		$sql = "SELECT cd.Cadastro_ID, cd.Nome, tco.Campanha_Usuario_ID
						FROM cadastros_dados cd
						INNER JOIN modulos_acessos ma ON ma.Modulo_Acesso_ID = cd.Grupo_ID
						LEFT JOIN tele_campanhas_operadores tco ON tco.Operador_ID = cd.Cadastro_ID /* and tco.Campanha_ID = '$campanhaID' */
						WHERE cd.Cadastro_ID > 0 AND ma.Situacao_ID = 1 AND Grupo_ID <> -4
						group by cd.Cadastro_ID, cd.Nome
						ORDER BY cd.Nome";
		//echo $sql;
		$html .= "			<option value=''>$texto</option>";
		$resultSet = mpress_query($sql);
		while($row = mpress_fetch_array($resultSet)){
			$disabled = "";
			if ($row['Campanha_Usuario_ID']!='') $disabled = " disabled ";
			$html .= " 		<option value='".$row['Cadastro_ID']."' ".$sel[$row['Cadastro_ID']]." $disabled>".$row['Nome']."</option>";
		}
		$html .= "		</select>
					</p>
				 </div>
				 <div class='titulo-secundario' style='float:left; width:30%;'>
				 	<p style='margin-top:15px; text-align: right;'>
				 		<input type='button' class='botao-incluir-usuario-campanha' value='Incluir' style='margin-left:10px; width:40%;'/>
				 		<input type='button' class='botao-cancelar-usuario-campanha' value='Cancelar' style='margin-left:10px; width:40%'/>
				 	</p>
				 </div>
				 <script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		return $html;
	}

	function salvarCampanhaUsuario(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		if ($_GET['acao']=='D'){
			$sql = "update tele_campanhas_operadores set Situacao_ID = '2',
														Data_Alteracao = $dataHoraAtual,
														Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
						where Campanha_Usuario_ID = '".$_GET['campanha-usuario-id']."'";
			echo $sql;
			mpress_query($sql);
		}
		else{
			$campanhaID = $_POST['campanha-id'];
			$operadorID = $_POST['usuario-id'];
			$sql = "INSERT INTO tele_campanhas_operadores (Campanha_ID, Operador_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
													VALUES ('$campanhaID', '$operadorID', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
		}
	}

	function campanhasCarregarUsuarios(){
		$campanhaID = $_POST['campanha-id'];
		$sql = "SELECT tco.Campanha_Usuario_ID, cd.Nome as Usuario
					FROM tele_campanhas_operadores tco
					inner join cadastros_dados cd on cd.Cadastro_ID = tco.Operador_ID
					where tco.Campanha_ID = '$campanhaID' and tco.Situacao_ID = '1'
					order by cd.Nome";
		//echo $sql;
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Usuario]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'><div class='btn-excluir usuario-campanha-excluir' style='float:right;padding-right:10px' campanha-usuario-id='".$row[Campanha_Usuario_ID]."' title='Excluir'>&nbsp;</div></p>";
		}
		if($i==0){
			$html = "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum(a) operador(a) cadastrado(a)</p>";
		}
		else{
			$dados[colunas][tamanho][2] = "width='40px;'";
			$largura = "100%";
			$dados[colunas][titulo][1] 	= "Operadores";
			$html .= geraTabela($largura,2,$dados, null, 'tele-carregar-usuarios-campanhas', 2, 2, '','','return');
		}
		return $html;
	}


	/**/
	function campanhasCarregarMotivos(){
		$campanhaID = $_POST['campanha-id'];
		$sql = "SELECT Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = 70 and (Tipo_Auxiliar = '$campanhaID' or Tipo_Auxiliar = '') and Situacao_ID = 1";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Descr_Tipo]."</p>";
			if ($row['Tipo_ID']>1000){
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>
														<div class='btn-editar editar-motivo-campanha' style='float:right;padding-right:10px' motivo-campanha-id='".$row[Tipo_ID]."' title='Editar'>&nbsp;</div>
														<div class='btn-excluir excluir-motivo-campanha' style='float:right;padding-right:10px' motivo-campanha-id='".$row[Tipo_ID]."' title='Excluir'>&nbsp;</div>
													</p>";
			}
		}
		$dados[colunas][tamanho][2] = "width='60px;'";
		$largura = "100%";
		$dados[colunas][titulo][1] 	= "Motivos";
		$html .= geraTabela($largura, 2, $dados, null, 'tele-carregar-motivos-campanhas', 2, 2, '','','return');
		return $html;
	}

	function carregarMotivoCampanha($campanhaID, $motivoCampanhaID){
		if ($motivoCampanhaID!=''){
			$sql = "SELECT Tipo_ID, Descr_Tipo from tipo where Tipo_ID = '$motivoCampanhaID'";
			$resultado = mpress_query($sql);
			if($rs = mpress_fetch_array($resultado)){
				$descricaoMotivo = $rs[Descr_Tipo];
			}
		}

		$h = "	<input type='hidden' name='motivo-campanha-id' value='$motivoCampanhaID'/>
				<div id='div-motivo' class='titulo-secundario' style='float:left; width:100%; margin:10px 0 10px 0'>
					<div class='titulo-secundario' style='float:left; width:80%;'>
						<p>Descri&ccedil;&atilde;o Motivo</p>
						<p><input type='text' id='descricao-motivo' name='descricao-motivo' style='width:99%;' value='".$descricaoMotivo."'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px;'>
						<p><input type='button' id='cancelar-salvar-motivo-campanha' style='width:99%;' value='Cancelar'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px;'>
						<p><input type='button' id='salvar-motivo-campanha' style='width:99%;' value='Salvar'/></p>
					</div>
				</div>";
		return $h;
	}

	function salvarMotivoCampanha(){
		$motivoCampanhaID = $_POST['motivo-campanha-id'];
		$descricao = utf8_decode($_POST['descricao-motivo']);
		$campanhaID = $_POST['campanha-id'];

		if ($_GET['excluir-motivo-campanha-id']!=''){
			$sql = "update tipo set Situacao_ID = 2 where Tipo_ID = '".$_GET['excluir-motivo-campanha-id']."'";
			mpress_query($sql);
		}
		else{
			if ($motivoCampanhaID==''){
				$sql = "insert into tipo (Descr_Tipo, Tipo_Auxiliar, Tipo_Grupo_ID, Situacao_ID)
								values ('$descricao', '$campanhaID', 70,1)";
				mpress_query($sql);
			}
			else{
				$sql = "update tipo set Descr_Tipo = '$descricao' where Tipo_ID = '$motivoCampanhaID'";
				mpress_query($sql);
			}
		}
	}

	/**/

	function campanhasCarregarSituacoes(){

		$resultado = mpress_query("select Projeto_ID, Titulo from projetos");
		while($rs = mpress_fetch_array($resultado)){
			$arrProjetos[$rs['Projeto_ID']] = $rs['Titulo'];
		}
		$resultado = mpress_query("select Tarefa_ID, Titulo from tarefas");
		while($rs = mpress_fetch_array($resultado)){
			$arrTarefas[$rs['Tarefa_ID']] = $rs['Titulo'];
		}

		$campanhaID = $_POST['campanha-id'];
		$sql = "SELECT Tipo_ID, Descr_Tipo, Tipo_Auxiliar_Extra from tipo where Tipo_Grupo_ID = 69 and (Tipo_Auxiliar = '$campanhaID' or Tipo_Auxiliar = '') and Situacao_ID = 1";
		$resultado = mpress_query($sql);

		while($row = mpress_fetch_array($resultado)){
			$i++;

			$dadosAux = unserialize($row[Tipo_Auxiliar_Extra]);
			$descricao = "";
			if ($dadosAux['tipo']=='p'){
				$descricao = "Projeto - ".$arrProjetos[$dadosAux['id']];
			}
			if ($dadosAux['tipo']=='t'){
				$descricao = "Tarefa - ".$arrTarefas[$dadosAux['id']];
			}



			if ($row['Tipo_ID']>1000){
				$excluir = "<div class='btn-excluir excluir-situacao-campanha' style='float:right;padding-right:10px' situacao-campanha-id='".$row[Tipo_ID]."' title='Excluir'>&nbsp;</div>";
			}
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Descr_Tipo]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$descricao."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>
													<div class='btn-editar editar-situacao-campanha' style='float:right;padding-right:10px' situacao-campanha-id='".$row[Tipo_ID]."' title='Editar'>&nbsp;</div>
													".$excluir."
												</p>";
		}
		$dados[colunas][tamanho][3] = "width='60px;'";
		$largura = "100%";
		$dados[colunas][titulo][1] 	= "Situa&ccedil;&otilde;es";
		$dados[colunas][titulo][2] 	= "Projeto / Tarefa";
		$html .= geraTabela($largura,3,$dados, null, 'tele-carregar-situacoes-campanhas', 2, 2, '','','return');
		return $html;
	}

	function carregarSituacaoCampanha($campanhaID, $situacaoCampanhaID){
		global $caminhoSistema;
		if ($situacaoCampanhaID!=''){
			$sql = "SELECT Tipo_ID, Descr_Tipo, Tipo_Auxiliar, Tipo_Auxiliar_Extra from tipo where Tipo_ID = '$situacaoCampanhaID'";
			//echo $sql;
			$resultado = mpress_query($sql);
			if($rs = mpress_fetch_array($resultado)){
				$descricaoSituacao = $rs[Descr_Tipo];
				$dados = unserialize($rs['Tipo_Auxiliar_Extra']);
				//$dados['tipo'];
				//$dados['id'];
			}
		}

		$h = "	<input type='hidden' name='situacao-campanha-id' value='$situacaoCampanhaID'/>
				<div id='div-situacao' class='titulo-secundario' style='float:left; width:100%; margin:10px 0 10px 0'>
					<div class='titulo-secundario' style='float:left; width:40%;'>
						<p>Descri&ccedil;&atilde;o Situa&ccedil;&atilde;o</p>
						<p><input type='text' id='descricao-situacao' name='descricao-situacao' style='width:99%;' class='required' value='".$descricaoSituacao."'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:40%;'>
						<p>Tarefa(s) Vinculada(s) a Situa&ccedil;&atilde;o</p>
						<p><select name='projeto-tarefa-situacao' id='projeto-tarefa-situacao'>".optionValueProjetoTarefa($dados['tipo'], $dados['id'])."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px;'>
						<p><input type='button' id='cancelar-salvar-situacao-campanha' style='width:99%;' value='Cancelar'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px;'>
						<p><input type='button' id='salvar-situacao-campanha' style='width:99%;' value='Salvar'/></p>
					</div>
				</div>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		return $h;
	}

	function salvarSituacaoCampanha(){
		$situacaoCampanhaID = $_POST['situacao-campanha-id'];
		$descricao = utf8_decode($_POST['descricao-situacao']);
		$campanhaID = $_POST['campanha-id'];
		$projetoTarefa = $_POST['projeto-tarefa-situacao'];
		if ($projetoTarefa!=''){
			$array['id'] = substr($projetoTarefa, 1);
			$array['tipo'] = substr($projetoTarefa, 0, 1);
		}

		if ($_GET['excluir-situacao-campanha-id']!=''){
			$sql = "update tipo set Situacao_ID = 2 where Tipo_ID = '".$_GET['excluir-situacao-campanha-id']."'";
			mpress_query($sql);
		}
		else{
			if ($situacaoCampanhaID==''){
				$sql = "insert into tipo (Descr_Tipo, Tipo_Auxiliar, Tipo_Auxiliar_Extra, Tipo_Grupo_ID, Situacao_ID)
								values ('$descricao', '$campanhaID', '".serialize($array)."',69,1)";
				mpress_query($sql);
			}
			else{
				$sql = "update tipo set Descr_Tipo = '$descricao', Tipo_Auxiliar_Extra = '".serialize($array)."' where Tipo_ID = '$situacaoCampanhaID'";
				mpress_query($sql);
			}
		}
	}



	function campanhasCarregarHistorico(){
		return "<div align='center'>aguarde....</div>";
	}



	function salvarDadosWorkflow(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$workflowID = $_POST['workflow-id'];
		$descricao = utf8_decode($_POST['descricao-follow']);
		$situacaoID = $_POST['situacao-id'];
		$responsavelID = $_POST['responsavel-id'];
		if ($workflowID!=''){
			$sql = " UPDATE tele_workflows set Situacao_ID = '$situacaoID', Responsavel_ID = '$responsavelID' where Workflow_ID = '$workflowID'";
			$resultado = mpress_query($sql);
			$sql = " INSERT INTO tele_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										VALUES ('$workflowID', '$descricao', $situacaoID, $dataHoraAtual, '".$dadosUserLogin['userID']."')";

			$resultado = mpress_query($sql);
			$followID = mpress_identity();
			if ($situacaoID==166){
				$motivoID = $_POST['motivo-id'];
				if ($motivoID==169)
					$motivoOutros = utf8_decode($_POST['motivo-outros']);
				$sql = " INSERT INTO tele_follows_cancelados (Follow_ID, Workflow_ID, Motivo_ID, Descricao, Data_Cadastro, Usuario_Cadastro_ID)
												VALUES ('$followID', '$workflowID', '$motivoID', '$motivoOutros', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
				$resultado = mpress_query($sql);
			}
		}
		return $workflowID;
	}


	function atualizarChaveWorkflow(){
		$workflowID = $_POST['workflow-id'];
		$sql = " UPDATE tele_workflows set Chave = '0' where Workflow_ID = '$workflowID'";
		$resultado = mpress_query($sql);
	}

	function carregarTitulosCadastro($cadastroID){
		$sql = "select fc.Conta_ID as Conta_ID, fc.Codigo as Codigo, ft.Codigo as Codigo_Titulo, fc.Tipo_ID, tc.Descr_Tipo as Tipo, tcc.Descr_Tipo as Tipo_Conta,  fc.Valor_Total,
					ft.Titulo_ID, tfp.Descr_Tipo as Forma_Pagamento, ft.Situacao_Pagamento_ID, cdd.Nome as Nome_De, cdd.Nome_Fantasia as Nome_Fantasia_De,
					cdp.Nome as Nome_Para, cdp.Nome_Fantasia as Nome_Fantasia_Para, ft.Valor_Titulo, DATE_FORMAT(ft.Data_Vencimento, '%d/%m/%Y') as Data_Vencimento,
					IF (tsp.Tipo_ID = 49, ft.Valor_Pago,'') as Valor_Pago,
					IF (tsp.Tipo_ID = 49, DATE_FORMAT(ft.Data_Pago, '%d/%m/%Y'),'') as Data_Pago,
					IF (tsp.Tipo_ID = 48, DATEDIFF('".retornaDataHora('d','Y-m-d')."', DATE_FORMAT(ft.Data_Vencimento,'%Y-%m-%d')),'') as DiasAtraso,
					tsp.Descr_Tipo as Situacao_Pagamento, cc.Nome_Conta as Nome_Conta,
					fc.Cadastro_Conta_ID_de, fc.Cadastro_Conta_ID_para, fc.Observacao, fc.Cadastro_ID_para
					from financeiro_contas fc
					inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
					inner join tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
					inner join cadastros_dados cdd on cdd.Cadastro_ID = fc.Cadastro_ID_de
					left join cadastros_contas cc on cc.Cadastro_Conta_ID = fc.Cadastro_Conta_ID_de
					left join tipo tcc on tcc.Tipo_ID = fc.Tipo_Conta_ID and tcc.Tipo_Grupo_ID = 28
					left join cadastros_dados cdp on cdp.Cadastro_ID = fc.Cadastro_ID_para
					left join tipo tfp on tfp.Tipo_ID  = ft.Forma_Pagamento_ID and tfp.Tipo_Grupo_ID = 25
					left join tipo tsp on tsp.Tipo_ID  = ft.Situacao_Pagamento_ID and tsp.Tipo_Grupo_ID = 29
					where fc.Tipo_ID = 45 and fc.Cadastro_ID_para = '$cadastroID'
					and ft.Situacao_Pagamento_ID in (48,49)
					order by ft.Data_Vencimento, fc.Tipo_ID";
		//echo $sql;
		$i=0;
		unset($dados);
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			$situacao ="";
			$situacaoPagamento = $rs[Situacao_Pagamento];
			$valor = number_format($rs[Valor_Titulo], 2, ',', '.');
			$dataVencimento = $rs[Data_Vencimento]; if ($dataVencimento=="00/00/0000"){$dataVencimento="A definir";}

			if ($rs[Nome_De]==""){$nomeDe = "N&atilde;o Informado";}else{$nomeDe = $rs[Nome_De];}
			if ($rs[Nome_Para]==""){$nomePara = "N&atilde;o Informado";}else{$nomePara = $rs[Nome_Para];}

			$estiloFonte = "color:#0047c9;";
			$icone = "<div style='float:left;' class='icone-entrada' title='".$rs[Tipo]."'>&nbsp;</div>";

			$dados[colunas][tr][$i] = " style='".$estiloFonte." font-weight:bold; cursor:pointer;' class='localiza-conta lnk' conta-id='".$rs[Conta_ID]."'";
			if ($rs[Situacao_Pagamento_ID]=="48"){
				if ($rs[DiasAtraso]>0){
					$situacao = "<p class='mini-bola-vermelha localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='$rs[DiasAtraso] dia(s) em atraso'>&nbsp;</p>";
				}
				if ($rs[DiasAtraso]==0){
					$situacao = "<p class='mini-bola-amarela localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='O Vencimento &eacute; hoje'>&nbsp;</p>";
				}
				if ($rs[DiasAtraso]<0){
					$situacao = "<p class='mini-bola-azul localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo com vencimento para dia $rs[Data_Vencimento]'>&nbsp;</p>";
				}
			}
			if ($rs[Situacao_Pagamento_ID]=="49"){
				$situacao = "<p class='mini-bola-verde localiza-conta' conta-id='".$rs[Conta_ID]."' titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo pago dia $rs[Data_Pago]'>&nbsp;</p>";
			}

			/* BOLETO */
			$descricaoAcaoBoleto = "Gerar";
			$sql = "select Anexo_ID from modulos_anexos where Tabela_Estrangeira = 'financeiro' and Chave_Estrangeira = '".$rs[Conta_ID]."' and Complemento = '".$rs[Titulo_ID]."'";
			$resultado1 = mpress_query($sql);
			if($rs1 = mpress_fetch_array($resultado1)){
				$descricaoAcaoBoleto = "Re-Gerar";
			}
			$boletoGerar = "<input type='button' value='".$descricaoAcaoBoleto."' class='gerar-boleto' id='boleto-gerar-$i' titulo-id='".$rs['Titulo_ID']."'  cadastro-id='".$rs['Cadastro_ID_para']."' Style='margin-widht:auto; height:20px;font-size:9px;margin-top:5px;'/>";

			/******************************/
			$c=1;
			$dados[colunas][conteudo][$i][$c++] = "<center>".$icone." ".$rs[Conta_ID]."</center>";
			$dados[colunas][conteudo][$i][$c++] = "<center>".$dataVencimento."</center>";
			$dados[colunas][conteudo][$i][$c++] = "<center>$situacao".($situacaoPagamento)."</center></p>";
			$dados[colunas][conteudo][$i][$c++] = "<center>".$valor."</center>";
			$dados[colunas][conteudo][$i][$c++] = "<center>$boletoGerar</center>";
			/******************************/

		}
		$largura = "100%";
		$c=1;
		$dados[colunas][titulo][$c++] 	= "<center>Conta N&ordm;</center>";
		$dados[colunas][titulo][$c++] 	= "<center>Data Vencimento</center>";
		$dados[colunas][titulo][$c++] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][$c++] 	= "<center>Valor T&iacute;tulo</center>";
		$dados[colunas][titulo][$c++]	= "<center>Gerar boleto?</center>";

		if($i==0){
			$h .= "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum t&Iacute;tulo localizado</p>";
		}
		else{
			$h .= "<div class='titulo-container conjunto1'>";
			$h .= geraTabela($largura, 5, $dados, null, 'titulos-cadastros-operacao', 2, 2, '','','return');
			$h .= "</div>";
		}
		return $h;
	}

	function excluirWorkflowCampanha(){
		global $caminhoSistema;
		$workflowID = $_POST['workflow-id'];
		if ($workflowID!=""){
			$sql = " delete from tele_workflows where Workflow_ID = '$workflowID'";
			$resultado = mpress_query($sql);
		}
		echo "	<form method='post' name='frmDefault'>
					<script type='text/javascript' src='$caminhoSistema/javascript/jquery-1.8.1.js'></script>
					<script>
						$('#confim-aux, .lk-".$workflowID."').remove();
						parent.$.fancybox.close();
					</script>
				</form>";
	}

	/*
	function optionValueSituacaoOperacao($idGrupo, $selecionado, $textoPrimeiro, $condicao, $orderBy = "descr_tipo"){
		if ($selecionado!="")
			$condicao .= " or Tipo_ID = '$selecionado'";
		if ($textoPrimeiro=="") $textoPrimeiro="Selecione";
		$optionValue = "<option value=''>$textoPrimeiro</option>";
		$sql = "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = '$idGrupo' and Situacao_ID = 1 $condicao order by $orderBy";
		$tipos = mpress_query($sql);
		while($tipo = mpress_fetch_array($tipos)){
			if ($selecionado==$tipo['Tipo_ID']){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$tipo['Tipo_ID']."' $seleciona>".($tipo['Descr_Tipo'])."</option>";
		}
		return $optionValue;
	}
	*/


?>