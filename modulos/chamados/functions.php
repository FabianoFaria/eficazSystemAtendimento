<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $configChamados;
	$configChamados = carregarConfiguracoesGeraisModulos('chamados');
	//echo "<pre>";
	//print_r($configChamados);
	//echo "</pre>";

	function carregarEnviosCD($workflowID, $visualizar){
		global $modulosAtivos, $dadosUserLogin, $caminhoSistema;
		if ($workflowID=="") $workflowID = $_GET['workflow-id'];
		echo "	<div class='titulo-container $visualizar conjunto3'>
					<div class='titulo'>
						<p>Controle de Distribui&ccedil;&atilde;o - Produtos para Envio / Recebimento</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>";

		$sql = "SELECT Workflow_Produto_ID, pv.Produto_Variacao_ID AS Produto_Variacao_ID, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto, pv.Codigo AS Codigo,
					Quantidade AS Quantidade, pd.Tipo_Produto AS Tipo_Produto, pv.Altura, pv.Largura, pv.Comprimento, pv.Peso
					FROM chamados_workflows_produtos cwp
					INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					LEFT JOIN cadastros_dados cd ON cd.Cadastro_ID = cwp.Usuario_Cadastro_ID
					WHERE Workflow_ID = '$workflowID' AND cwp.Situacao_ID = 1 and Tipo_Produto = 30
					ORDER BY cwp.Data_Cadastro DESC";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin-left:2px'>".($row['Codigo'])."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin-left:2px'>".($row['Descricao_Produto'])."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin-right:5px; text-align:right;'>".number_format($row['Altura'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin-right:5px; text-align:right;'>".number_format($row['Largura'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin-right:5px; text-align:right;'>".number_format($row['Comprimento'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin-right:5px; text-align:right;'>".number_format($row['Peso'], 3, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin-right:5px; text-align:right;'>".number_format($row['Quantidade'], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][8] = "<p Style='margint:0px;' align='center'><input type='checkbox' class='check-envio' name='check-envio[]' title='Enviar para Centro de Distribui&ccedil;&atilde;o' id='check-envio-".$row[Workflow_Produto_ID]."' workflow-produto-id='".$row[Workflow_Produto_ID]."' value='".$row[Workflow_Produto_ID]."'/></p>";
			$i++;
			$dados[colunas][classe][$i] = "esconde tabela-fundo-claro envios-dados-".$row[Workflow_Produto_ID];
			$dados[colunas][colspan][$i][3] = "6";
			$dados[colunas][conteudo][$i][3] = "<div Style='margin: 8px 0 65px 2px'>
											<div class='titulo-escundario' style='float:left; width:20%'>
												<p>Retorna?</p>
												<p class='envios-retorna-".$row[Workflow_Produto_ID]."' style='width:90%'>
													<input type='radio' class='radio-retorna' name='radio-retorna-".$row[Workflow_Produto_ID]."' id='radio-retorna-".$row[Workflow_Produto_ID]."' value='1' workflow-produto-id='".$row[Workflow_Produto_ID]."'/>SIM
													<input type='radio' class='radio-retorna' name='radio-retorna-".$row[Workflow_Produto_ID]."' id='radio-retorna-".$row[Workflow_Produto_ID]."' value='0' workflow-produto-id='".$row[Workflow_Produto_ID]."'/>N&Atilde;O
												</p>
											</div>
											<div class='titulo-secundario' style='float:left; width:20%'>
												<p>Embalado?</p>
												<p class='envios-embalado-".$row[Workflow_Produto_ID]."' style='width:90%'>
													<input type='radio' class='radio-embalado' name='radio-embalado-".$row[Workflow_Produto_ID]."' id='radio-embalado-".$row[Workflow_Produto_ID]."' value='1' workflow-produto-id='".$row[Workflow_Produto_ID]."'/>SIM
													<input type='radio' class='radio-embalado' name='radio-embalado-".$row[Workflow_Produto_ID]."' id='radio-embalado-".$row[Workflow_Produto_ID]."' value='0' workflow-produto-id='".$row[Workflow_Produto_ID]."'/>N&Atilde;O
												</p>
											</div>
											<div style='float:left; width:60%'>
												Observa&ccedil;&atilde;o:<br>
												<input type='text' name='observacao-envio-".$row[Workflow_Produto_ID]."' id='observacao-envio-".$row[Workflow_Produto_ID]."' style='width:98%'/>
											</div>
											<!--
											<div style='float:left; width:20%' class='esconde envios-exibe-campos-".$row[Workflow_Produto_ID]."'>
												Data Retorno<br>
												<input type='text' name='data-retorno-".$row[Workflow_Produto_ID]."' id='data-retorno-".$row[Workflow_Produto_ID]."' class='formata-data' style='width:90%'/>
											</div>
											-->
										</div>";
		}

		$dados[colunas][titulo][1] 	= "<p Style='text-align:center;'>C&oacute;digo</p>";
		$dados[colunas][titulo][2] 	= "<p Style='text-align:center;'>Descri&ccedil;&atilde;o</p>";
		$dados[colunas][titulo][3] 	= "<p Style='text-align:center;'>Altura (cm)</p>";
		$dados[colunas][titulo][4] 	= "<p Style='text-align:center;'>Largura (cm)</p>";
		$dados[colunas][titulo][5] 	= "<p Style='text-align:center;'>Comprimento (cm)</p>";
		$dados[colunas][titulo][6] 	= "<p Style='text-align:center;'>Peso (kilos)</p>";
		$dados[colunas][titulo][7] 	= "<p Style='text-align:center;'>Quantidade</p>";
		//$dados[colunas][titulo][8] 	= "<p Style='text-align:center;' class='link todos-cd' title='Selecionar todos os produtos para gerar Ordem de Envio'>Retorna</p>";
		$dados[colunas][titulo][8] 	= "<p Style='text-align:center;' class='link todos-cd' title='Selecionar todos os produtos para gerar Envio'>Enviar / Receber</p>";
		$dados[colunas][tamanho][1] = "width='05%'";
		$dados[colunas][tamanho][7] = "width='35px'";
		//$dados[colunas][tamanho][8] = "width='100px'";
		$dados[colunas][tamanho][8] = "width='100px'";
		geraTabela("100%",8,$dados);

		if($i==0)
			echo "			<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum produto ou servi&ccedil;o cadastrado</p>";
		else{
			echo "			<div style='width:100%;float:right;margin-top:0px'>
								<p align='right' class='btn-envios'>
									<!--<input type='checkbox' name='enviar-email-cd' id='enviar-email-cd' value='1' checked='checked'/>Enviar Email &nbsp; &nbsp; &nbsp;-->
									<input type='hidden' id='acao-cd' name='acao-cd' value=''/> <input type='hidden' id='tipo-cd' name='tipo-cd' value=''/>
									<input type='button' value='Solicitar Envio' id='botao-solicitacao-envio' acao='envio' tipo='56' style='font-size: 10px; width: 105px; margin-top: 2px;'>
									<input type='button' value='Solicitar Retirada' id='botao-solicitacao-retirada'  acao='retirada' tipo='57' style='font-size: 10px; width: 105px; margin-top: 2px;'>
								</p>
							</div>";
		}
		echo " 			</div>
					</div>
				</div>

				<div class='titulo-container $visualizar conjunto3'>
					<div class='titulo'>
						<p>Solicita&ccedil;&otilde;es Realizadas</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>";

		$sql = "select ew.Workflow_ID, cd1.Nome as Cadastro_de, cd2.Nome as Cadastro_para, cd3.Nome as Trasportadora, ef.Situacao_ID as Situacao_ID, tf.Descr_Tipo as Forma_Envio, ew.Codigo_Rastreamento, t.Descr_Tipo as Situacao,
					DATE_FORMAT(ew.Data_Envio,'%d/%m/%Y') as Data_Envio, DATE_FORMAT(ew.Data_Previsao,'%d/%m/%Y') as Data_Previsao,  DATE_FORMAT(ew.Data_Entrega,'%d/%m/%Y') as Data_Entrega, DATE_FORMAT(ef.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
					ew.Usuario_Cadastro_ID as Usuario_Cadastro_ID,  CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) as Produto, ewp.Quantidade, sol.Nome as Solicitante, ewp.Retorna as Retorna, ewp.Data_Retorno as Data_Retorno, ewp.Embalado, ewp.Observacoes as Observacoes
					from envios_workflows ew
					inner join envios_workflows_produtos ewp on ewp.Workflow_ID = ew.Workflow_ID
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
					inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
					inner join cadastros_dados sol on sol.Cadastro_ID = ew.Usuario_Cadastro_ID
					inner join envios_follows ef on ew.Workflow_ID = ef.Workflow_ID and ef.Follow_ID = (select max(efaux.Follow_ID) from envios_follows efaux where ef.Workflow_ID = efaux.Workflow_ID)
					inner join tipo t on t.Tipo_ID = ef.Situacao_ID
					left join cadastros_dados cd1 on cd1.Cadastro_ID = ew.Cadastro_ID_de
					left join cadastros_dados cd3 on cd3.Cadastro_ID = ew.Transportadora_ID
					left join tipo tf on tf.Tipo_ID = ew.Forma_Envio_ID
					left join cadastros_dados cd2 on cd2.Cadastro_ID = ew.Cadastro_ID_para
					where ew.Workflow_ID is not null
					and ew.Tabela_Estrangeira = 'chamados'
					and ew.Chave_Estrangeira = '$workflowID'
					order by ew.Workflow_ID desc, ef.Follow_ID";
		//echo $sql;

		$query2 = mpress_query($sql);
		$i=0;
		while($row = mpress_fetch_array($query2)){
			$botaoCancelar = "";
			if ($row[Workflow_ID]!=$workflowIDAnt){
				$i++;
				$dadosEnvio[colunas][classe][$i] = "tabela-fundo-escuro";
				//"""
				if ((($row[Situacao_ID]==56)||($row[Situacao_ID]==57))&&($row[Data_Envio]=="")&&($dadosUserLogin['userID']==$row[Usuario_Cadastro_ID]))$botaoCancelar = "<div class='btn-cancelar btn-excluir-envio' style='float:right; padding-right:5px' workflow-id='$row[Workflow_ID]' title='Cancelar'>";
				$dadosEnvio[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-envio' workflow-id='$row[Workflow_ID]'>".$row[Workflow_ID]."</p>";
				$dadosEnvio[colunas][colspan][$i][2] = "2";
				$dadosEnvio[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Cadastro_de])."</p>";
				$dadosEnvio[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Cadastro_para])."</p>";
				$dadosEnvio[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Forma_Envio])."</p>";
				$dadosEnvio[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Situacao])."</p>";
				$dadosEnvio[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Solicitante])."</p>";
				$dadosEnvio[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Envio]."</p>";
				$dadosEnvio[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Previsao]."</p>";
				$dadosEnvio[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Entrega]."</p>";
				$dadosEnvio[colunas][conteudo][$i][11] = "<p Style='margin:2px 3px 0 3px;float:left;'>$botaoCancelar&nbsp;</div></p>";
			}
			$i++;
			$dadosEnvio[colunas][classe][$i] = "tabela-fundo-claro";
			$dadosEnvio[colunas][conteudo][$i][2] = "<p Style='margin:5px 3px 3px 15px;float:left;'>".($row[Produto])."</p>";
			$dadosEnvio[colunas][conteudo][$i][3] = "<p Style='margin:0px;' align='center'>".number_format($row[Quantidade], 2, ',', '.')."</p>";
			if ($row[Retorna]==1) $retorna = "SIM"; else $retorna = "N&Atilde;O";
			if ($row[Embalado]==1) $embalado = "SIM"; else $embalado = "N&Atilde;O";

			//if ($row[Data_Retorno]!="") $retorna .= " em ".substr(converteData($row[Data_Retorno]),0,10);
			$dadosEnvio[colunas][colspan][$i][4] = 2;
			$dadosEnvio[colunas][conteudo][$i][4] = "<p Style='margin:5px 3px 3px 15px;float:left;'>
												<div style='float:left; width:50%;'>Retorna:<b>$retorna</b></div>
												<div style='float:left; width:50%;'>Embalado:<b>$embalado</b></div>
											</p>";
			$dadosEnvio[colunas][colspan][$i][6] = 5;
			$observacoes = "";
			$dadosEnvio[colunas][conteudo][$i][6] = "<p Style='margin:5px 3px 3px 15px;float:left;'>".$row['Observacoes']."</p>";
			$workflowIDAnt = $row[Workflow_ID];
		}
		$dadosEnvio[colunas][titulo][1] = "ID Envio";
		$dadosEnvio[colunas][titulo][2] = "Remetente";
		$dadosEnvio[colunas][titulo][3] = "<p Style='margin:0px' align='center'>Qtde.</p>";
		$dadosEnvio[colunas][titulo][4] = "Destinat&aacute;rio";
		$dadosEnvio[colunas][titulo][5] = "Forma Envio";
		$dadosEnvio[colunas][titulo][6] = "Situa&ccedil;&atilde;o";
		$dadosEnvio[colunas][titulo][7] = "Solicitante";
		$dadosEnvio[colunas][titulo][8] = "Envio";
		$dadosEnvio[colunas][titulo][9] = "Previs&atilde;o";
		$dadosEnvio[colunas][titulo][10] = "Entrega";
		$dadosEnvio[colunas][titulo][11] = "&nbsp;";

		$dadosEnvio[colunas][tamanho][1] = "width='80px'";
		$dadosEnvio[colunas][tamanho][2] = "";
		$dadosEnvio[colunas][tamanho][3] = "";
		$dadosEnvio[colunas][tamanho][4] = "";
		$dadosEnvio[colunas][tamanho][5] = "";
		$dadosEnvio[colunas][tamanho][6] = "";
		$dadosEnvio[colunas][tamanho][7] = "";
		$dadosEnvio[colunas][tamanho][8] = "width='075px'";
		$dadosEnvio[colunas][tamanho][9] = "width='075px'";
		$dadosEnvio[colunas][tamanho][10] = "width='075px'";
		$dadosEnvio[colunas][tamanho][11] = "width='030px';";
		geraTabela("100%","11",$dadosEnvio);
		if ($i==0){
			echo "		<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum esolicita&ccedil;&atilde;o realizada</p>";
		}
		echo "
						</div>
					</div>
				</div>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}


	function envioCentroDistribuicaoCancelar(){
		global $dadosUserLogin;
		$workflowID = $_GET['workflow-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$sql = "insert into envios_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
								   values ('$workflowID', 'Cancelado por Operador Solicitante', '59', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		mpress_query($sql);
	}



	function carregarCompras(){
		global $dadosUserLogin;
		$workflowID = $_POST['workflow-id'];
		$sql = "select cs.Compra_Solicitacao_ID, DATE_FORMAT(cs.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro, ts.Descr_Tipo as Situacao, cd.Nome as Solicitante, p.Nome as Produto, cs.Quantidade,ts.Tipo_ID Situacao_ID,
					(select Ordem_Compra_ID from compras_ordens_compras_produtos where Compra_solicitacao_ID = cs.Compra_Solicitacao_ID limit 1) Numero_OC
					from compras_solicitacoes cs
					inner join cadastros_dados cd on cs.Usuario_Cadastro_ID = cd.Cadastro_ID
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
					inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
					inner join tipo ts on ts.Tipo_ID = cs.Situacao_ID
					where cs.Tabela_Estrangeira = 'chamados' and cs.Chave_Estrangeira = '$workflowID'";
		$query = mpress_query($sql);
		$i=0;
		while($row = mpress_fetch_array($query)){
			$i++;
			$linkRequisicoes = "";
			if ($row[Numero_OC]==""){
				$linkRequisicoes = "class='link link-requisicao' requisicao-id='$row[Compra_Solicitacao_ID]'";
			}
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' $linkRequisicoes>".$row[Compra_Solicitacao_ID]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link link-ordem-compra' ordem-compra-id='$row[Numero_OC]'>".$row[Numero_OC]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Produto])."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".number_format($row[Quantidade], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Situacao])."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Solicitante])."</p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'><center>".$row[Data_Cadastro]."</center></p>";
		}
		$dados[colunas][titulo][1] = "Requisi&ccedil;&atilde;o";
		$dados[colunas][titulo][2] = "O.C";
		$dados[colunas][titulo][3] = "Produto";
		$dados[colunas][titulo][4] = "Quantidade";
		$dados[colunas][titulo][5] = "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][6] = "Solicitante";
		$dados[colunas][titulo][7] = "Data Requisi&ccedil;&atilde;o";

		$dados[colunas][tamanho][1] = "width='80px'";
		$dados[colunas][tamanho][2] = "width='80px'";
		$dados[colunas][tamanho][3] = "";
		$dados[colunas][tamanho][4] = "";
		$dados[colunas][tamanho][5] = "";
		$dados[colunas][tamanho][6] = "";
		$dados[colunas][tamanho][7] = "width='095px'";
		echo "<div class='titulo'><p>Requisi&ccedil;&otilde;es de Compras</p></div>";
		echo "<div class='conteudo-interno titulo-secundario uma-coluna' id='conteudo-interno-compras'>";
		geraTabela("100%","6",$dados);
		echo "</div>";
		if ($i==0){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma Solicita&ccedil;&atilde;o envio cadastrado</p>";
		}
	}


	function faturarProdutosServicos(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		/* FATURAMENTO DE CHAMADOS */
		/*
		if ($_POST['slug-pagina']=='chamados-cadastro-chamado'){
			$cadastroID = $_POST['cadastro-id'];
			$solicitanteID = $_POST['solicitante-id'];
			$prestadorID = $_POST['prestador-id'];
			$chaveEstrangeira = $_POST['workflow-id'];
			//$tipoContaID = $_POST['tipo-workflow'];
		*/
			/***************** A RECEBER ********************/
			/*
			$virgula = "";
			$workflowProdutosID = "";
			for($i = 0; $i < count($_POST['check-fat-receber']); $i++){$workflowProdutosID .= $virgula.$_POST['check-fat-receber'][$i];$virgula = ",";}
			if ($workflowProdutosID!=""){
				$sql = "insert into financeiro_contas (Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Chave_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
							select distinct '45', '$cadastroID', '$solicitanteID', 'chamados', '$chaveEstrangeira', sum(Valor_Venda_Unitario * Quantidade), $dataHoraAtual, '".$dadosUserLogin['userID']."'
								from chamados_workflows_produtos where Workflow_ID = '$chaveEstrangeira' and Workflow_Produto_ID in ($workflowProdutosID)
								and Situacao_ID = 1 and Cobranca_Cliente = 1 and (Valor_Venda_Unitario * Quantidade) > 0";
				mpress_query($sql);
				$contaID = mysql_insert_id();
				$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
							select  Workflow_Produto_ID, '$contaID', 'chamados', '$chaveEstrangeira', Produto_Variacao_ID, Quantidade, Valor_Venda_Unitario, 1 ,'".$dadosUserLogin['userID']."', $dataHoraAtual
								from chamados_workflows_produtos where Workflow_ID = '$chaveEstrangeira' and Workflow_Produto_ID in ($workflowProdutosID) and Situacao_ID = 1 and Cobranca_Cliente = 1";
				mpress_query($sql);
			}
			*/
			/***************** A PAGAR ********************/
			/*
			$virgula = "";
			$workflowProdutosID = "";
			for($i = 0; $i < count($_POST['check-fat-pagar']); $i++){$workflowProdutosID .= $virgula.$_POST['check-fat-pagar'][$i];$virgula = ",";}
			if ($workflowProdutosID!=""){
				$sql = "insert into financeiro_contas (Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Chave_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
							select distinct '44', '$cadastroID', '$prestadorID', 'chamados', '$chaveEstrangeira', sum(Valor_Custo_Unitario * Quantidade), $dataHoraAtual, '".$dadosUserLogin['userID']."'
								from chamados_workflows_produtos where Workflow_ID = '$chaveEstrangeira' and Workflow_Produto_ID in ($workflowProdutosID) and Situacao_ID = 1
								and Pagamento_Prestador = 1 and (Valor_Custo_Unitario * Quantidade) > 0";
				mpress_query($sql);
				//echo "<br><br>".$sql;

				$contaID = mysql_insert_id();
				$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
							select  Workflow_Produto_ID, '$contaID', 'chamados', '$chaveEstrangeira', Produto_Variacao_ID, Quantidade, Valor_Custo_Unitario, 1, '".$dadosUserLogin['userID']."', $dataHoraAtual
								from chamados_workflows_produtos where Workflow_ID = '$chaveEstrangeira' and Workflow_Produto_ID in ($workflowProdutosID) and Situacao_ID = 1 and Pagamento_Prestador = 1";
				mpress_query($sql);
				//echo "<br><br>".$sql;
			/*
			}
		}
		*/
		/* FATURAMENTO DE CHAMADOS RELATORIO */
		/*
		if ($_POST['slug-pagina']=='chamados-relatorio-faturamento'){
		*/
			/***************** A RECEBER ********************/
			/*
			$workflows = "";
			$virgula = ""; for($i = 0; $i < count($_POST['faturar-receber']); $i++){ $workflows .= $virgula.$_POST['faturar-receber'][$i];$virgula = ","; }
			if ($workflows!=""){
				$sql = "select cw.Cadastro_ID AS Cadastro_ID_de, cw.Solicitante_ID AS Cadastro_ID_para, cw.Workflow_ID AS Workflow_ID, SUM(cwp.Valor_Venda_Unitario * cwp.Quantidade) AS Valor
						from chamados_workflows_produtos cwp
						inner join chamados_workflows cw ON cw.Workflow_ID = cwp.Workflow_ID
						where cwp.Workflow_ID IN ($workflows) AND cwp.Situacao_ID = 1 AND cwp.Cobranca_Cliente = 1 AND (cwp.Valor_Venda_Unitario * cwp.Quantidade) > 0
						and not exists (select * from financeiro_produtos fp
											inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID and fc.Tipo_ID = 45
											where fp.Produto_Referencia_ID = cwp.Workflow_Produto_ID AND fp.Tabela_Estrangeira = 'chamados' AND fp.Situacao_ID = 1)
						group by cw.Cadastro_ID, cw.Solicitante_ID, cw.Workflow_ID
						order by cw.Workflow_ID";

				$query = mpress_query($sql);
				while($row = mpress_fetch_array($query)){
					$sql = "insert into financeiro_contas (Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Chave_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
											values ('45', '".$row[Cadastro_ID_de]."','".$row[Cadastro_ID_para]."','chamados', '".$row[Workflow_ID]."', '".$row[Valor]."', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
					mpress_query($sql);
					$contaID = mysql_insert_id();
					$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
							select cp.Workflow_Produto_ID, '$contaID', 'chamados', '".$row[Workflow_ID]."', cp.Produto_Variacao_ID, cp.Quantidade, cp.Valor_Venda_Unitario, 1 ,'".$dadosUserLogin['userID']."', $dataHoraAtual
							from chamados_workflows_produtos cp
							where cp.Workflow_ID = '".$row[Workflow_ID]."' AND cp.Situacao_ID = 1 AND cp.Cobranca_Cliente = 1 AND (cp.Quantidade * cp.Valor_Venda_Unitario) > 0
							and not exists (select * from financeiro_produtos fp
														inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID and fc.Tipo_ID = 45
									where fp.Produto_Referencia_ID = cp.Workflow_Produto_ID AND fp.Tabela_Estrangeira = 'chamados' AND fp.Situacao_ID = 1)";
					mpress_query($sql);
				}
			}
			*/
			/***************** A PAGAR ********************/
			/*
			$workflows = "";
			$virgula = ""; for($i = 0; $i < count($_POST['faturar-pagar']); $i++){ $workflows .= $virgula.$_POST['faturar-pagar'][$i];$virgula = ","; }
			if ($workflows!=""){
				$sql = "select cw.Cadastro_ID AS Cadastro_ID_de, cw.Prestador_ID AS Cadastro_ID_para, cw.Workflow_ID AS Workflow_ID, SUM(cwp.Valor_Custo_Unitario * cwp.Quantidade) AS Valor
						from chamados_workflows_produtos cwp
						inner join chamados_workflows cw ON cw.Workflow_ID = cwp.Workflow_ID
						where cwp.Workflow_ID IN ($workflows) AND cwp.Situacao_ID = 1 AND cwp.Pagamento_Prestador = 1 AND (cwp.Valor_Custo_Unitario * cwp.Quantidade) > 0
						and not exists (select * from financeiro_produtos fp
											inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID and fc.Tipo_ID = 44
											where fp.Produto_Referencia_ID = cwp.Workflow_Produto_ID AND fp.Tabela_Estrangeira = 'chamados' AND fp.Situacao_ID = 1)
						group by cw.Cadastro_ID, cw.Solicitante_ID, cw.Workflow_ID
						order by cw.Workflow_ID";
				$query = mpress_query($sql);
				while($row = mpress_fetch_array($query)){
					$sql = "insert into financeiro_contas (Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Tabela_Estrangeira, Chave_Estrangeira, Valor_Total, Data_Cadastro, Usuario_Cadastro_ID)
											values ('44', '".$row[Cadastro_ID_de]."','".$row[Cadastro_ID_para]."','chamados', '".$row[Workflow_ID]."', '".$row[Valor]."', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
					mpress_query($sql);
					$contaID = mysql_insert_id();
					$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Valor_Unitario, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
							select cp.Workflow_Produto_ID, '$contaID', 'chamados', '".$row[Workflow_ID]."', cp.Produto_Variacao_ID, cp.Quantidade, cp.Valor_Custo_Unitario, 1 ,'".$dadosUserLogin['userID']."', $dataHoraAtual
							from chamados_workflows_produtos cp
							where cp.Workflow_ID = '".$row[Workflow_ID]."' AND cp.Situacao_ID = 1 AND cp.Pagamento_Prestador = 1 AND (cp.Quantidade * cp.Valor_Custo_Unitario) > 0
							and not exists (select * from financeiro_produtos fp
														inner join financeiro_contas fc on fc.Conta_ID = fp.Conta_ID and fc.Tipo_ID = 44
									where fp.Produto_Referencia_ID = cp.Workflow_Produto_ID AND fp.Tabela_Estrangeira = 'chamados' AND fp.Situacao_ID = 1)";


					mpress_query($sql);
				}
			}
		}
		*/
		/* FATURAMENTO DE ORCAMENTO */
		/*
		if ($_POST['slug-pagina']=='chamados-orcamento'){
			echo "PAREI AQUI!!!!";
		}
		*/
	}


	function refaturarProdutoChamado(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$financeiroProdutoID = $_GET['financeiro-produto-id'];
		$sql = "insert into financeiro_produtos (Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
					select Produto_Referencia_ID, Conta_ID, Tabela_Estrangeira, Chave_Estrangeira, 1, $dataHoraAtual, '".$dadosUserLogin['userID']."' from financeiro_produtos where Financeiro_Produto_ID = '$financeiroProdutoID'";
		//echo $sql;
		mpress_query($sql);
	}


	function carregarFinanceiro($workflowID, $tabelaEstrangeira){
		global $dadosUserLogin, $modulosAtivos, $caminhoFisico;
		if (!function_exists("carregarProdutosFaturar"))
			require_once($caminhoFisico."/modulos/financeiro/functions.php");

		if ($workflowID=='') $workflowID = $_GET['workflow-id'];
		$array = carregarProdutosFaturar($workflowID, 'orcamentos');
		$dados = $array['dados'];
		$largura = $array['largura'];
		$colunas = $array['colunas'];
		$linhas = $array['linhas'];
		if($linhas==0){
			$h .= "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum registro faturado ou aguardando faturamento encontrado para este or&ccedil;amento</p>";
		}
		else{
			$h .= geraTabela($largura,$colunas,$dados, null, 'financeiro-aguardando-faturamento', 2, 2, "","", "return");
		}
		return $h;
	}

	function carregarHistoricoFinanceiroProduto($workflowProdutoID,$tipo){
		$sql = " Select cd1.Nome as Usuario_Envio, DATE_FORMAT(fp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Envio,
					cd2.Nome as Usuario_Cancelamento, DATE_FORMAT(fp.Data_Alteracao, '%d/%m/%Y %H:%i') as Data_Cancelamento,
					case fp.Situacao_ID when 1 then 'Faturado' when 2 then 'Cancelado' else '---' end as Situacao, fp.Situacao_ID
					from financeiro_produtos fp
					inner join financeiro_contas fc ON fp.Conta_ID = fc.Conta_ID and fc.Tipo_ID = '$tipo'
					inner join cadastros_dados cd1 on cd1.Cadastro_ID = fp.Usuario_Cadastro_ID
					left join cadastros_dados cd2 on cd2.Cadastro_ID = fp.Usuario_Alteracao_ID
					where fp.Tabela_Estrangeira = 'chamados' and fp.Produto_Referencia_ID = '$workflowProdutoID'
					order by fp.Data_Cadastro";
		$i=0;
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px; color:#0022a8;'>Enviado para Faturamento</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;'>".$rs['Usuario_Envio']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;'>".$rs['Data_Envio']."</p>";
			if ($rs[Situacao_ID]=='2'){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px; color:#FF4D4D;'>Cancelado Faturamento</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;'>".$rs['Usuario_Cancelamento']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;'>".$rs['Data_Cancelamento']."</p>";
			}
		}
		$dados[colunas][titulo][1] = "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][2] = "Usu&aacute;rio";
		$dados[colunas][titulo][3] = "Data";
		echo "<div style='float:right; width:500px'>";
		geraTabela('100%',3,$dados);
		echo "</div>";
		//echo $sql;
	}


	function salvarChamado(){
		global $dadosUserLogin;
		$workflowID 		= $_POST['workflow-id'];
		$tipoWorkflowID 	= $_POST['tipo-workflow'];
		$tipoWorkflowIDAnt 	= $_POST['tipo-workflow-ant'];
		$codigo 			= $_POST['codigo-workflow'];
		$titulo 			= $_POST['titulo-chamado'];
		$descricaoFollow 	= $_POST['descricao-follow'];
		$projetoID			= $_POST['projeto-id-chamado'];

		if ($descricaoFollow=="")
			$situacaoFollowID 	= $_POST['situacao-atual-chamado'];
		else
			$situacaoFollowID 	= $_POST['select-situacao-follow'];

		$prioridadeID 			= $_POST['select-prioridade'];
		$grupoResponsavel 		= $_POST['select-grupo-chamado'];
		$usuarioResponsavel 	= $_POST['select-usuario-chamado'];
		$tarefaInicio			= $_POST['select-tipo-tarefa-inicial'];

		$dataHoraAtual 			= retornaDataHora('','Y-m-d H:i:s');
		$dataHoraAtual 			= "'".$dataHoraAtual."'";
		$dataAbertura 			= "'".converteDataHora($_POST['data-abertura-chamado'])."'";
		$dataLimite 			= "'".converteDataHora($_POST['data-limite'])."'";

		if ($situacaoFollowID==34){
			$dataHoraFinalizado = "'".converteDataHora($_POST['data-finalizado-chamado'])."'";
		}
		else{
			$dataHoraFinalizado = "NULL";
		}
		$cadastroID		= $_POST['cadastro-id'];

		$solicitanteID 	= $_POST['solicitante-id'];
		$prestadorID 	= $_POST['prestador-id'];
		$prestadorIDAnt = $_POST['prestador-id-ant'];


		$enviarEmail 	= $_POST['enviar-email'];
		$emailsEnvio 	= $_POST['emails-envio'];


		if (($solicitanteID!="")&&($solicitanteID!="0")){
			if ($solicitanteID!=""){
				$sql = "select Nome from cadastros_dados where Cadastro_ID = '$solicitanteID'";
				$resultado = mpress_query($sql);
				if($row = mpress_fetch_array($resultado))
					$nomeSolicitante =  $row[Nome];
				$campoSolicitante = ", Solicitante_ID = '$solicitanteID'";
			}
		}

		if (($prestadorID!="")&&($prestadorID!="0")){
			if ($prestadorID!=""){
				$sql = "select Nome from cadastros_dados where Cadastro_ID = '$prestadorID'";
				$resultado = mpress_query($sql);
				if($row = mpress_fetch_array($resultado))
					$nomePrestador =  $row[Nome];
				$campoPrestador = ", Prestador_ID = '$prestadorID'";
				if ($prestadorID!=$prestadorIDAnt)
					$descricaoFollow .= "<p><b>Prestador Atualizado:</b> $nomePrestador</p>";
			}
		}

		if ($workflowID==""){
			$sql = "Insert Into chamados_workflows (Codigo, Cadastro_ID, Solicitante_ID, Prestador_ID, Responsavel_ID, Grupo_Responsavel_ID, Data_Abertura, Data_Finalizado, Tipo_Workflow_ID, Prioridade_ID, Data_Cadastro, Usuario_Cadastro_ID, Titulo, Data_Limite)
											 Values ('$codigo', '$cadastroID', '$solicitanteID', '$prestadorID', '$usuarioResponsavel','$grupoResponsavel', $dataAbertura, $dataHoraFinalizado, '$tipoWorkflowID', '$prioridadeID', $dataHoraAtual, '".$dadosUserLogin['userID']."','$titulo', $dataLimite)";

			// var_dump($sql);

			// die();

			mpress_query($sql);
			$workflowID = mysql_insert_id();
			$sql = "Insert Into chamados_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
											 Values ('$workflowID', '$descricaoFollow', '', '$situacaoFollowID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";

			mpress_query($sql);



			/* chamada para função de criação do projeto */
			if ($projetoID!=""){
				salvarProjeto($projetoID, $workflowID, 'Workflow_ID' , 'chamados_workflows', '', $solicitanteID);
			}
		}
		else{
			if ($tipoWorkflowID != $tipoWorkflowIDAnt){
				$sql = "select Descr_tipo from tipo where Tipo_ID = '$tipoWorkflowIDAnt'";
				$resultado = mpress_query($sql);
				if($row = mpress_fetch_array($resultado))
					$descrTipoAnterior =  $row[Descr_tipo];
				$sql = "select Descr_tipo from tipo where Tipo_ID = '$tipoWorkflowID'";
				$resultado = mpress_query($sql);
				if($row = mpress_fetch_array($resultado))
					$descrTipoAtual =  $row[Descr_tipo];
				$descricaoFollow .= "<p><b>Tipo de ".$_SESSION['objeto']." atualizado de:</b> $descrTipoAnterior <b> para: </b>$descrTipoAtual</p>";
			}

			if($dadosUserLogin['grupoID'] != -3){
				$sql = "Update chamados_workflows set Codigo = '$codigo',
														Prioridade_ID = '$prioridadeID',
														Data_Abertura = $dataAbertura,
														Cadastro_ID = '$cadastroID',
														Data_Finalizado = $dataHoraFinalizado,
														Data_Limite = $dataLimite,
														Responsavel_ID = '$usuarioResponsavel',
														Grupo_Responsavel_ID = '$grupoResponsavel',
														Titulo = '$titulo',
														Tipo_Workflow_ID = '$tipoWorkflowID'
														$campoPrestador
														$campoSolicitante
														where Workflow_ID = '$workflowID'";
				mpress_query($sql);
			}

			if($descricaoFollow != ""){
				$sql = "Insert Into chamados_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
											 Values ('$workflowID', '$descricaoFollow', '', '$situacaoFollowID', $dataHoraAtual,'".$dadosUserLogin['userID']."')";
				mpress_query($sql);
			}
		}

		if(($situacaoFollowID != 34)&&($situacaoFollowID != 33)){
			mpress_query("insert into chamados_workflows_tarefas(Workflow_ID, Tipo_Tarefa_ID, Descricao, Grupo_Responsavel_ID, Responsavel_ID, Situacao_ID, Usuario_Cadastro_ID,Data_Retorno)
						 values('$workflowID','$tarefaInicio','$descricaoFollow','$grupoResponsavel','$usuarioResponsavel','83','".$dadosUserLogin['userID']."',$dataLimite)");
		}

		if (($enviarEmail!="")&&($emailsEnvio!="")){
			$i = 0;
			$emails = explode(";", $emailsEnvio);
			foreach ($emails as $email) {
				if ($email!=""){
					$i++;
					$dadosEmail[email][$i] = $email;
					$dadosEmail[nome][$i] = $email;
				}
			}
			$tituloChamado = $_SESSION['objeto']." ".$workflowID;
			if ($codigo!=""){ $tituloChamado = $tituloChamado." - (".$codigo.")";}
			$conteudoChamado = gerarConteudoEmailChamado($workflowID);
			echo enviaEmails($dadosEmail, $tituloChamado, geraEmailPadrao($conteudoChamado), "<p>Envio efetuado com successo</p>");
		}

?>
		<form action='../../chamados/chamados-cadastro-chamado' method='post' name='retorno'>
			<input type='hidden' name='workflow-id'  value='<?php echo $workflowID?>'>
		</form>
		<script>document.retorno.submit();</script>
<?php
}


function gerarConteudoEmailChamado($workFlowID){
	$txtNaoInfo = "N&atilde;o Informado";

	$sql = "Select cw.Solicitante_ID, sol.Nome as Nome_Solicitante, sol.Nome_Fantasia as Nome_Fantasia_Solicitante, sol.Codigo as Codigo_Solicitante, sol.Email as Email_Solicitante,
				   cw.Prestador_ID, pre.Nome as Nome_Prestador, pre.Nome_Fantasia as Nome_Fantasia_Prestador, pre.Codigo as Codigo_Prestador, pre.Email as Email_Prestador,
				cw.Codigo as Codigo_Chamado, cw.Tipo_Workflow_ID, cw.Data_Cadastro, cw.Usuario_Cadastro_ID,	max(cf.Follow_ID), cf.Descricao as Descricao_Follow, tw.Descr_Tipo as Tipo_Chamado,
				ts.Descr_Tipo as Situacao_Chamado
				from chamados_workflows cw
				left join cadastros_dados sol on sol.Cadastro_ID = Solicitante_ID
				left join cadastros_dados pre on pre.Cadastro_ID = Prestador_ID
				left join tipo tw on tw.Tipo_ID = cw.Tipo_Workflow_ID
				left join chamados_follows cf on cf.Workflow_ID =  cw.Workflow_ID
				left join tipo ts on ts.Tipo_ID = cf.Situacao_ID
				where cw.Workflow_ID = $workFlowID";
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){
		$codigoChamado = $workFlowID." (".$rs[Codigo_Chamado].")";
		$tipoChamado = $rs[Tipo_Chamado];
		$situacaoChamado = $rs[Situacao_Chamado];
		$solicitanteID = $rs[Solicitante_ID];
		$prestadorID = $rs[Prestador_ID];

		$codigoSolicitante = preencheTextoSeVazio($txtNaoInfo,$rs[Codigo_Solicitante]);
		$nomeSolicitante = $rs[Nome_Solicitante];
		if ($rs[Nome_Fantasia_Solicitante]!=""){$nomeSolicitante .= " / ".$rs[Nome_Fantasia_Solicitante];}
		$emailSolicitante = preencheTextoSeVazio($txtNaoInfo,$rs[Email_Solicitante]);


		$codigoPrestador = preencheTextoSeVazio($txtNaoInfo,$rs[Codigo_Prestador]);
		$nomePrestador = $rs[Nome_Prestador];
		if ($rs[Nome_Fantasia_Prestador]!=""){$nomePrestador .= " / ".$rs[Nome_Fantasia_Prestador];}
		$emailPrestador = preencheTextoSeVazio($txtNaoInfo,$rs[Email_Prestador]);

		$descricaoFollow = $rs[Descricao_Follow];

		$conteudoEmail = "
		<table width='100%' cellpadding='0' cellspacing='0' align='center' style='border:2px solid #FFFFFF; width:100%;'>
			<tr>
				<td align='center' valign='middle' colspan='4' style='font-family:arial;font-size:12px;color:#FFFFFF;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #c9c9c9;'>".$_SESSION['objeto']." $workFlowID </td>
			</tr>
			<tr>
				<td style='width:25%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>C&oacute;digo ".$_SESSION['objeto']."</td>
				<td style='width:25%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>Tipo ".$_SESSION['objeto']."</td>
				<td style='width:25%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>Situa&ccedil;&atilde;o</td>
				<td style='width:25%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>Prioridade</td>
			</tr>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($codigoChamado)."</td>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($tipoChamado)."</td>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($situacaoChamado)."</td>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($txtNaoInfo)."</td>
			</tr>
			<tr>
				<td style='font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' colspan='4' align='left' valign='middle'>Descri&ccedil;&atilde;o</td>
			</tr>
			<tr>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' colspan='4' align='left' valign='middle'>".utf8_encode($descricaoFollow)."</td>
			</tr>
		</table>
		<table width='100%' cellpadding='0' cellspacing='0' align='center' style='border:2px solid #FFFFFF;width:100%;'>
			<tr>
				<td align='center' valign='middle' colspan='3' style='font-family:arial;font-size:12px;color:#FFFFFF;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #c9c9c9;'>DADOS SOLICITANTE</td>
			</tr>
			<tr>
				<td style='width:10%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>C&oacute;digo Cliente</td>
				<td style='width:60%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>Nome / Raz&atilde;o Social</td>
				<td style='width:30%; font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='middle'>E-mail</td>
			</tr>
			<tr>
				<td style='width:10%; font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($codigoSolicitante)."</td>
				<td style='width:60%; font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($nomeSolicitante)."</td>
				<td style='width:30%; font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' align='left' valign='middle'>".utf8_encode($emailSolicitante)."</td>
			</tr>
			<tr>
				<td style='font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='top' colspan='2'>Endere&ccedil;o</td>
				<td style='font-family:arial;font-size:12px;border:0px solid #ccc;padding:2px;float:left;font-weight:bold;background-color: #DCDCDC;' align='left' valign='top' >Telefone</td>
			</tr>
			<tr>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' colspan='2' valign='top'>";

			$sql = "select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
					from cadastros_enderecos ce
					inner join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
					where Cadastro_ID = $solicitanteID
					and ce.Situacao_ID = 1 ";
			$i=0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$conteudoEmail .= "<p Style='margin:2px 5px 0 5px;float:left;width:100%;'>
									<b>".utf8_encode($row[Descr_Tipo]).":</b>
									".utf8_encode($row[Logradouro])."&nbsp;".$row[Numero]."&nbsp;".utf8_encode($row[Complemento])."
									&nbsp;&nbsp;CEP:".$row[CEP]."&nbsp;&nbsp;".utf8_encode($row[Referencia])."
									&nbsp;&nbsp;".utf8_encode($row[Bairro])."&nbsp;&nbsp;".utf8_encode($row[Cidade])."&nbsp;&nbsp;".$row[UF]." &nbsp;&nbsp;
									<a href='https://maps.google.com.br/?q=".utf8_encode($row[Logradouro]).", ".$row[Numero]." ".utf8_encode($row[CEP])." ".utf8_encode($row[Cidade])." ".utf8_encode($row[UF])."' target='_blank' >Ver Mapa</a></p>";
			}
			if($i==0){
				$conteudoEmail .= $txtNaoInfo;
			}
			$conteudoEmail .= "
				</td>
				<td style='font-family:arial;font-size:12px;border:0px solid #DCDCDC;padding:2px;float:left;background-color: #FFFFFF;' valign='top'>";

			$sql = "select Cadastro_Telefone_ID, t.Descr_Tipo, Telefone, Observacao
							from cadastros_telefones ct
							inner join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
					where  ct.Situacao_ID = 1
					and Cadastro_ID = $solicitanteID";

			$i=0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$conteudoEmail .= "<p Style='margin:0px;float:left;width:100%;'><b>".utf8_encode($row[Descr_Tipo]).":</b>".$row[Telefone]."&nbsp;".$row[Numero]."&nbsp;".utf8_encode($row[Observacao])."</p>";
			}
			if($i==0){
				$conteudoEmail .= $txtNaoInfo;
			}
			$conteudoEmail .= "
				</td>
		</table>";
		return($conteudoEmail);
	}
}

function localizaProdutoSelect(){
	$descricao = $_GET['descricao'];
	$workflowID = $_GET['workflow-id'];

	if ($descricao!=""){
		$sqlCond = " and (pd.Nome like '%$descricao%' or pd.Codigo like '%$descricao%' or pd.Descricao_Resumida like '%$descricao%' or pv.Codigo like '%$descricao%' or pv.Descricao like '%$descricao%') ";
	}
	$sql = "select pv.Produto_Variacao_ID as Produto_Variacao_ID, pv.Codigo as Codigo_Variacao, pd.Codigo as Codigo, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Descricao_Produto
				from produtos_dados pd
							inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
							where pd.Situacao_ID = 1 and pv.Situacao_ID = 1
							$sqlCond
							order by pd.Produto_ID, pv.Produto_Variacao_ID";
	echo "<select id='select-produtos' name='select-produtos' Style='width:98.5%'>
				<option value=''>Selecione</option>";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		echo "<option value='".$row[Produto_Variacao_ID]."'>".utf8_encode($row['Descricao_Produto'])."</option>";
	}
	echo "</select>";
}


function validarCodigoChamado(){
	$workflowID = $_GET['workflow-id'];
	$codigoWorkflow = $_GET['codigo-workflow'];
	$sql = "select Workflow_ID from chamados_workflows where Workflow_ID <> '$workflowID' and Codigo = '$codigoWorkflow'";
	$worflowIDAchou = "";
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$worflowIDAchou = $row[Workflow_ID];
	}
	echo $worflowIDAchou;
}

function carregarProdutoWorkflow(){
	$workflowProdutoID = $_GET['workflow-produto-id'];
	echo "	<div id='div-produtos-select' style='float:left; width:80%;' class='esconde'>
				<select id='select-produtos' name='select-produtos' style='width:98.5%'><option value=''>Selecione</option></select>
			</div>
			<div id='div-produtos-texto' style='float:left; width:80%;'>
				<input type='text' id='texto-localizar-produtos' name='texto-localizar-produtos' style='width:98.5%'>
			</div>
			<div id='div-produtos-localizar'  style='width:10%; float:left;'>
				<input type='button' id='botao-localizar-produtos' name='botao-localizar-produtos' Style='width:95%' value='Localizar' >
			</div>
			<div id='div-produtos-incluir'  style='width:10%; float:left;' class='esconde'>
				<input type='button' id='botao-incluir-produtos' name='botao-incluir-produtos' Style='width:95%' value='Incluir' >
			</div>
			<div id='div-produtos-cancelar' style='width:10%; float:left;'>
				<input type='button' value='Cancelar' id='botao-cancelar-produtos' class='botao-cancelar-produtos' Style='width:95%'/>
			</div>
			<div id='div-detalhes-produto' style='width:100%; float:left;'>&nbsp;</div>
			<div style='width:15%;float:left;margin-top:3px'>
				<p><b>Quantidade</b></p>
				<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='1' class='formata-numero' style='width:90%' maxlength='10'/></p>
			</div>
			<div style='width:15%;float:left;margin-top:3px'>
				<p><b>Total Custo</b>
				<p><input type='text' id='total-custo-produtos' name='total-custo-produtos' value='0,00' style='width:90%' readonly/></p>
			</div>
			<div style='width:15%;float:left;margin-top:3px'>
				<p><b>Total Venda</b>
				<p><input type='text' id='total-venda-produtos' name='total-venda-produtos' value='0,00' style='width:90%' readonly/></p>
			</div>
			<div style='float:left;margin-top:6px; margin-left:10px'>
				<p>&nbsp;</p>
				<p id='pagamento-prestador'><input type='checkbox' value='1' id='checkbox-pagamento-prestador' name='checkbox-pagamento-prestador' checked/>Pagamento Prestador</p>
			</div>
			<div style='float:left;margin-top:6px; margin-left:10px'>
				<p>&nbsp;</p>
				<p id='cobranca-cliente'><input type='checkbox' value='1' id='checkbox-cobranca-cliente' name='checkbox-cobranca-cliente' checked/>Cobran&ccedil;a Cliente</p>
			</div>
			<p>&nbsp;</p><p>&nbsp;</p>";
}


function carregarProdutoDetalhes($workflowID, $produtoVariacaoID, $tipo, $chaveEstrangeira){
	global $caminhoSistema;
	$sql = "select distinct pd.Tipo_Produto as Tipo_Produto, (tp.Descr_Tipo) as Tipo, pv.Valor_Custo as Valor_Custo, pv.Valor_Venda as Valor_Venda, pv.Valor_Promocao as Valor_Promocao, Forma_Cobranca_ID,
				Percentual_Venda, t.Descr_Tipo as Forma_Cobranca, pv.Data_Inicio_Promocao as Data_Inicio_Promocao, pv.Data_Fim_Promocao as Data_Fim_Promocao,
				pv.Saldo_Estoque as Saldo_Estoque, pv.Altura as Altura, pv.Largura as Largura, pv.Comprimento as Comprimento, pv.Comprimento as Comprimento, pv.Peso as Peso,
				sum(pm.Quantidade) as Quantidade_Estoque, pe.Estoque_Minimo,  ma.Nome_Arquivo as Nome_Arquivo, pd.Produto_ID as Produto_ID,
				concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Descricao_Produto, pd.Descricao_Completa, pd.Faturamento_Direto
				from produtos_dados pd
				inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
				inner join tipo t on t.Tipo_ID = Forma_Cobranca_ID
				inner join tipo tp on tp.Tipo_ID = pd.Tipo_Produto
				left join produtos_movimentacoes pm on pm.Produto_Variacao_ID = pv.Produto_Variacao_ID
				left join produtos_estoque pe on pe.Produto_Estoque_ID = pv.Produto_Variacao_ID
				left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
				where pv.Produto_Variacao_ID = $produtoVariacaoID ";
	//echo $sql;
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		/*********************************************************/
		/********** Inicio Funçoes para tabela de preços *********/
		/*********************************************************/
		$rsTabela = mpress_query("select cd1.Tabela_Preco_ID Tabela_Preco_Solicitante, cd2.Tabela_Preco_ID Tabela_Preco_Prestador
								   from chamados_workflows cw
								   left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
								   left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
								   where Workflow_ID = $workflowID");
		if($tabela = mpress_fetch_array($rsTabela)){
			$tabelaSolicitante = $tabela['Tabela_Preco_Solicitante'];
			$tabelaPrestador   = $tabela['Tabela_Preco_Prestador'];
		}
		//$produtoVariacaoID = $_GET['produto-variacao-id'];
		$rsTabela = mpress_query("select Valor_Custo  Valor_Tabela, 'Prestador' Tipo from produtos_tabelas_precos_detalhes where Tabela_Preco_ID = '$tabelaPrestador' and Situacao_ID = 1 and Produto_Variacao_ID = $produtoVariacaoID
								   union
								   select Valor_Venda Valor_Tabela, 'Solicitante' Tipo from produtos_tabelas_precos_detalhes where Tabela_Preco_ID = '$tabelaSolicitante' and Situacao_ID = 1 and Produto_Variacao_ID = $produtoVariacaoID;");
		while($tabela = mpress_fetch_array($rsTabela)){
			if(($tabela['Tipo'] == 'Prestador')&&($tabela['Valor_Tabela']>0))   $row['Valor_Custo'] = $tabela['Valor_Tabela'];
			if(($tabela['Tipo'] == 'Solicitante')&&($tabela['Valor_Tabela']>0)) $row['Valor_Venda'] = $tabela['Valor_Tabela'];
		}

		/*********************************************************/
		/********** Final Funçoes para tabela de preços *********/
		/*********************************************************/


		/*********************************************************/
		/********** Inicio Função pegar preco original ***********/
		/*********************************************************/
		if ($chaveEstrangeira!=''){
			if ($tipo=='chamado')
				$sqlOrig 				= "select Valor_Custo_Unitario, Valor_Venda_Unitario from chamados_workflows_produtos where Workflow_Produto_ID = '$chaveEstrangeira'";
			if ($tipo=='orcamento')
				$sqlOrig 				= "select Valor_Custo_Unitario, Valor_Venda_Unitario from orcamentos_propostas_produtos where Proposta_Produto_ID = '$chaveEstrangeira'";
			if ($sqlOrig!=''){
				$resultadoOrig 			= mpress_query($sqlOrig);
				if ($rsOrig = mpress_fetch_array($resultadoOrig)){
					$row['Valor_Custo'] = $rsOrig['Valor_Custo_Unitario'];
					$row['Valor_Venda'] = $rsOrig['Valor_Venda_Unitario'];
				}
			}
		}
		/*********************************************************/
		/********** Final Função pegar preco original ************/
		/*********************************************************/


		$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
		if ($row[Nome_Arquivo]!="")
			$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
		else{
			if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1")))
				$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
		}
		$imagemProduto = "<a href='$nomeArquivo' target='_blank'><img style='max-width:50px; max-height:50px' src='$nomeArquivo' align='center'/></a>";

		echo "	<input type='hidden' name='forma-cobranca' id='forma-cobranca' value='".$row[Forma_Cobranca_ID]."'>
				<div style='float:left; width:100%; margin-top:10px;'>
					<div style='float:left; width:50%; min-height:80px;'>
						<fieldset style='min-height:80px;'>
							<legend><b>".utf8_encode($row[Tipo])."</b></legend>
							<div style='float:left; width:10%' align='center'>
								<p align='center'>$imagemProduto</p>
							</div>
							<div style='float:left; margin-left:5%; width:85%'>
								<p>".utf8_encode($row[Descricao_Produto])."
									<input type='hidden' name='descricao-produto-variacao' id='descricao-produto-variacao' value='".utf8_encode($row[Descricao_Produto])."'/>
								</p>
								<p style='margin-top:5px;'>".utf8_encode($row['Descricao_Completa'])."</p>
							</div>
						</fieldset>
					</div>
					<div style='float:left; width:50%; min-height:80px;'>
						<fieldset style='min-height:80px;'>
							<legend>Forma de Cobran&ccedil;a: <b>".strtoupper(utf8_encode($row['Forma_Cobranca']))."</b></legend>";
		if ($row[Forma_Cobranca_ID]=="35"){
			echo "			<div style='float:left; width:33.33%;'>
								<p>Venda Unint&aacute;rio</p>
								<p><input type='text' readonly value='R$ ".number_format($row['Valor_Venda'], 2, ',', '.')."'/></p>
								<input type='hidden' id='valor-venda-unitario' name='valor-venda-unitario' value='".str_replace(".",",",$row[Valor_Venda])."'/>
							</div>
							<div style='float:left; width:33.33%;'>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
							</div>
							<div style='float:left; width:33.33%;'>
								<div style='float:left; width:100%' class='exibir-terceiro'>
									<p>Custo Unint&aacute;rio</p>
									<p><input type='text' readonly value='R$ ".number_format($row['Valor_Custo'], 2, ',', '.')."'/></p>
									<input type='hidden' id='valor-custo-unitario' name='valor-custo-unitario' value='".str_replace(".",",",$row['Valor_Custo'])."'/>
								</div>
								&nbsp;
							</div>";
		}

		// ABAIXO PERCENTUAL DO CUSTO!
		if ($row[Forma_Cobranca_ID]=="36"){
			echo "			<div style='float:left; width:33.33%;'>
								<p>Informe o Custo Unint&aacute;rio</p>
								<p><input type='text' id='valor-custo-unitario' name='valor-custo-unitario' value='0,00' style='width:95%' class='formata-valor'/></p>
							</div>
							<div style='float:left; width:33.33%;'>
								<p>&nbsp;</p>
								<p>&nbsp;</p>
							</div>
							<div style='float:left; width:33.33%;'>
								<p>Venda Unint&aacute;rio acrecida de ".number_format($row[Percentual_Venda], 2, ',', '.')."&nbsp;%</p>
								<p><input type='text' id='valor-venda-unitario' name='valor-venda-unitario' value='0,00' readonly style='width:95%'/></p>
								<input type='hidden' id='percentual-venda' name='percentual-venda' value='".str_replace(".",",",$row[Percentual_Venda])."' class='formata-valor'/>
							</div>";
		}
		// VALORES ABERTOS
		if ($row[Forma_Cobranca_ID]=="58"){
			$width = "20%";
			echo "			<div style='float:left; width:33.33%;'>
								<p>Venda Unint&aacute;rio</p>
								<p><input type='text' id='valor-venda-unitario' name='valor-venda-unitario' class='formata-valor' style='width:95%' value='".number_format($row['Valor_Venda'], 2, ',', '.')."'/></p>
							</div>
							<div style='float:left; width:33.33%;'>
								<p>Valor de Venda M&iacute;nimo</p>
								<p><input type='text' readonly value='R$ ".number_format($row['Valor_Promocao'], 2, ',', '.')."' style='width:95%' /></p>
								<input type='hidden' id='valor-venda-minima-unitario' name='valor-venda-minima-unitario' class='formata-valor' style='width:95%' value='".number_format($row['Valor_Promocao'], 2, ',', '.')."'/>
							</div>
							<div style='float:left; width:33.33%;'>
								<div style='float:left; width:100%;'>
									<p>Custo Unint&aacute;rio</p>
									<p><input type='text' id='valor-custo-unitario' name='valor-custo-unitario' class='formata-valor' style='width:95%' value='".number_format($row['Valor_Custo'], 2, ',', '.')."'/></p>
								</div>
							</div>";
		}
		echo "			</fieldset>
					</div>
					<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";

		// ESTOQUE
		if ($row['Tipo_Produto']=="30"){
			$echo = "<div style='float:left; width:100%; margin-bottom:10px'>
						<div style='float:left; width:25%;'>
							<p>Quantidade Estoque</p>
							<p><input type='text' readonly value='".number_format($row['Quantidade_Estoque'], 2, ',', '.')."'/></p>
						</div>
						<div style='float:left; width:25%;'>
							<p>Estoque M&iacute;nimo</p>
							<p><input type='text' readonly value='".number_format($row['Estoque_Minimo'], 2, ',', '.')."'/></p>
						</div>
					</div>";
		}
		echo "	</div>";
	}
}

function salvarProdutoChamado(){
	global $dadosUserLogin;
	$dataHoraAtual 			= retornaDataHora('','Y-m-d H:i:s');
	$produtoVariacaoID 		= $_POST['select-produtos'];
	$quantidadeProdutos 	= $_POST['quantidade-produtos'];
	$valorCustoUnitario 	= str_replace(",",".",str_replace(".","",$_POST['valor-custo-unitario']));
	$valorVendaUnitario 	= str_replace(",",".",str_replace(".","",$_POST['valor-venda-unitario']));
	$workflowID 			= $_POST['workflow-id'];
	$descricao 				= $_POST['descricao-produto-variacao'];
	$pagamento 				= $_POST['checkbox-pagamento-prestador'];
	$cobranca 				= $_POST['checkbox-cobranca-cliente'];
	$faturamentoDireto 		= $_POST['checkbox-faturamento-direto'];

	$produtoObservacao 		= utf8_decode($_POST['observacao_produto']);

	if($faturamentoDireto==""){
		$faturamentoDireto 	= 	0;
	}

	if ($pagamento==""){
		$valorCustoUnitario = 	0;
		$prestadorID 		=	0;
		$pagamento 			= 	0;
	}else{
		$prestadorID 		= 	$_POST['select-prestador'];
		// $valorCustoUnitario = 	$_POST['valor-custo-unitario'];
	}

	if ($cobranca==""){
		$valorVendaUnitario=0;
	}else{
		// $valorVendaUnitario = $_POST['valor-venda-unitario'];
	}

	$workflowProdutoID = $_POST['chave-primaria-id'];
	if ($workflowProdutoID==""){
		$sql = "insert into chamados_workflows_produtos
						(Workflow_ID, Produto_Variacao_ID, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario, Cobranca_Cliente, Pagamento_Prestador, Faturamento_Direto, Prestador_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID, Observacao_Produtos)
				values
						('$workflowID', '$produtoVariacaoID', '$quantidadeProdutos', '$valorCustoUnitario', '$valorVendaUnitario', '$cobranca', '$pagamento', '$faturamentoDireto', '$prestadorID', 1, '$dataHoraAtual', '".$dadosUserLogin['userID']."', '$produtoObservacao')";
		var_dump($sql);

		die();

		$resultado = mpress_query($sql);

		$sql = "select pd.Tipo_Produto as Tipo_Produto from produtos_dados pd inner join produtos_variacoes pv on pv.Produto_ID = pd.Produto_ID where pv.Produto_Variacao_ID = '$produtoVariacaoID'";
		$query = mpress_query($sql);
	}
	else{
		$sql = "update chamados_workflows_produtos
					set Produto_Variacao_ID = '$produtoVariacaoID',
						Quantidade = '$quantidadeProdutos',
						Valor_Custo_Unitario = '$valorCustoUnitario',
						Valor_Venda_Unitario = '$valorVendaUnitario',
						Cobranca_Cliente = '$cobranca',
						Pagamento_Prestador = '$pagamento',
						Faturamento_Direto = '$faturamentoDireto',
						Prestador_ID = '$prestadorID',
						Usuario_Alteracao_ID = ".$dadosUserLogin['userID'].",
						Observacao_Produtos = '$produtoObservacao'
					where Workflow_Produto_ID = '$workflowProdutoID'";
		$resultado = mpress_query($sql);
	}
	// echo "-->".$sql;
	// TRECHO ABAIXO FAZ DEDUÇÃO DO ESTOQUE, DEVE SER ALTERADO PARA ACEITAR A PARTE DE ATUALIZAÇÃO
	// E VERIFICAR A ANALISE EM RELAÇÃO AO USO DO ESTOQUE PARA TODOS OS PRODUTOS
	// POSSIBILIDADE DE REALIZAR CONTROLE DE ESTOQUE DE APENAS ALGUNS PRODUTOS E TRABALHHAR COM RESERVA AO INVÉZ DA SAIDA
	// POIS A SAIDA É CONTRLADA PELO MODULO DE LOGISTICA QUE NO MOMENTO NÃO INTERAGE COM ESTOQUE
	/*
	if($rs = mpress_fetch_array($query)){
		if ($rs['Tipo_Produto']=='30'){
			$quantidadeProdutos *= (-1);
			$sql = "insert into produtos_movimentacoes (Chave_Estrangeira, Tabela_Estrangeira, Produto_Variacao_ID, Tipo_Movimentacao_ID, Quantidade, Nota_Fiscal, Data_Cadastro, Usuario_Cadastro_ID)
											values ('$workflowID', 'chamados', '$produtoVariacaoID', 67, '$quantidadeProdutos', '','$dataHoraAtual', '".$dadosUserLogin['userID']."')";
			$resultado = mpress_query($sql);
		}
	}
	*/
}

function excluirProdutoWorkflow(){
	global $dadosUserLogin;
	$workflowProdutoID = $_GET['workflow-produto-id'];
	$sql = "update chamados_workflows_produtos set Situacao_ID = 3, Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."' where Workflow_Produto_ID = '$workflowProdutoID'";
	$resultado = mpress_query($sql);


	$sql = "select pd.Tipo_Produto, cwp.Workflow_ID, cwp.Produto_Variacao_ID from chamados_workflows_produtos cwp
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
				inner join produtos_dados pd on pv.Produto_ID = pd.Produto_ID
			where Workflow_Produto_ID = $workflowProdutoID";
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){
		if ($rs[Tipo_Produto]=='30'){
			$quantidadeProdutos *= (-1);
			mpress_query("delete from produtos_movimentacoes where Tabela_Estrangeira = 'chamados' and Chave_Estrangeira = '$rs[Workflow_ID]' and Produto_Variacao_ID = '$rs[Produto_Variacao_ID]'");
		}
	}
}

/*
function localizaUsuariosGrupo(){
	$rs = mpress_query("select Cadastro_ID, Nome from cadastros_dados where Grupo_ID = '".$_GET['grupo']."' and Situacao_ID = 1");
	while($row = mpress_fetch_array($rs))
		$optionValue .= "<option value='".$row['Cadastro_ID']."'>".utf8_encode($row['Nome'])."</option>";
	if($_GET['grupo']<="") $desabilita = "disabled";
	echo "	<select name='".$_GET['campo']."' id='".$_GET['campo']."' style='width:98.5%' $desabilita>
				<option value=''></option>
				$optionValue
			<select>";
}
*/

function mostraDadosFolowsTarefas($workflowID){
	global $caminhoSistema;
	$rs = mpress_query("select t.Workflow_Tarefa_ID, t.Descricao, t.Data_Cadastro , m.Titulo Grupo, t1.Descr_Tipo Tipo_Tarefa, t2.Tipo_ID Situacao_Tarefa,
						Data_Retorno, cd1.Nome Responsavel
						from chamados_workflows_tarefas t
						inner join modulos_acessos m on m.Modulo_Acesso_ID = t.Grupo_Responsavel_ID
						inner join tipo t1 on t1.Tipo_ID = t.Tipo_Tarefa_ID
						inner join tipo t2 on t2.Tipo_ID = t.Situacao_ID
						left join cadastros_dados cd1 on cd1.Cadastro_ID = t.Responsavel_ID
						where Workflow_ID = $workflowID order by Workflow_Tarefa_ID desc");

	while($row = mpress_fetch_array($rs)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<p Style='text-align:left;font-size:11px;'>".converteDataHora(substr($row['Data_Cadastro'],0,10),1)." - ".$row['Tipo_Tarefa']."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".$row['Descricao']."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".$row['Grupo']."&nbsp;<br>".$row['Responsavel']."&nbsp;</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='text-align:center;font-size:11px;'>".converteDataHora($row['Data_Retorno'],1)."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='text-align:center;font-size:11px;'>".calculaHorasUtilizadasTarefas($row['Workflow_Tarefa_ID'])."</p>";

		if($row['Situacao_Tarefa']==83)
			$dados[colunas][conteudo][$i][6] = "<p Style='font-size:11px;'><center><a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/chamados/chamados-follows-tarefas.php?wID=$workflowID&t=".$row['Workflow_Tarefa_ID']."'><img src='../images/geral/ico-editar-var-produto.png' class='finaliza-tarefa-chamado' title='Editar tarefa' Style='cursor:pointer'></a></center></p>";
		else
			$dados[colunas][conteudo][$i][6] = "<p Style='font-size:11px;'><center><a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/chamados/chamados-follows-tarefas.php?wID=$workflowID&t=".$row['Workflow_Tarefa_ID']."'><img src='../images/geral/disponivel.png' class='tarefa-finalizada' title='Visualizar Tarefa Finalizada'></a></center></p>";

	}
	if(mpress_count($rs)>=1){
		$dados[colunas][titulo][1] 	= "Tarefa";
		$dados[colunas][titulo][2] 	= "Descri&ccedil;&atilde;o da Tarefa";
		$dados[colunas][titulo][3] 	= "Responsabilidade";
		$dados[colunas][titulo][4] 	= "<center>Data Retorno</center>";
		$dados[colunas][titulo][5] 	= "&nbsp;Horas Utilizadas";

		$dados[colunas][tamanho][1] = "width='250px'";
		$dados[colunas][tamanho][3] = "width='160px'";
		$dados[colunas][tamanho][4] = "width='90px'";
		$dados[colunas][tamanho][5] = "width='120px'";
		$dados[colunas][tamanho][6] = "width='30px'";

		geraTabela("99.3%","6",$dados);
	}else{
		echo "<p Style='margin:15px 5px 0 5px;color:red; text-align:center'>Nenhuma tarefa cadastrada.</p>";
	}
}

function cadastraTarefaChamado(){
	$workflowID		= $_POST['workflow-id'];
	$tipoTarefa 	= $_POST['select-tipo-tarefa'];
	$descTarefa 	= utf8_decode($_POST['descricao-tarefa']);
	$grupoTarefa 	= $_POST['select-grupo-tarefa'];
	$userTarefa 	= $_POST['select-usuario-tarefa'];
	$situacaoTarefa = $_POST['select-situacao-tarefa'];
	$userID			= $_SESSION['dadosUserLogin']['userID'];
	$dataLimite		= converteDataHora($_POST['tarefa-data-limite']);

	if($_GET['encaminhada']=='s'){
		$descTarefa 	= utf8_decode($_POST['descricao-tarefa-follow']);
		$situacaoTarefa = 83;
	}
	mpress_query("insert into chamados_workflows_tarefas(Workflow_ID, Tipo_Tarefa_ID, Descricao, Grupo_Responsavel_ID, Responsavel_ID, Situacao_ID, Usuario_Cadastro_ID,Data_Retorno)
				 values('$workflowID','$tipoTarefa','$descTarefa','$grupoTarefa','$userTarefa','$situacaoTarefa','$userID','$dataLimite')");
	mostraDadosFolowsTarefas($workflowID);
}

function cadastraTarefaFollowChamado(){
	$userID			= $_SESSION['dadosUserLogin']['userID'];
	$observacao		= utf8_decode($_POST['descricao-tarefa-follow']);
	$horaInicio		= converteDataHora($_POST['horas-inicio-tarefa-follow']);
	$horaFinal		= converteDataHora($_POST['horas-final-tarefa-follow']);
	$idTarefa		= $_POST['tarefa-id'];
	$idWorkflow		= $_POST['workflow-id'];

	if($_GET['encaminhada']=='s'){
		$novaTarefa = mpress_fetch_array(mpress_query("select Descr_Tipo from tipo where Tipo_ID = ".$_POST['select-tipo-tarefa']));
		$novoGrupo 	= mpress_fetch_array(mpress_query("select Titulo from modulos_acessos where Modulo_Acesso_ID = ".$_POST['select-grupo-tarefa']));;
		$novoUser 	= mpress_fetch_array(mpress_query("select Nome from cadastros_dados where cadastro_id = ".$_POST['select-usuario-tarefa']));;
		$observacao = "Tarefa Alterada para <b>$novaTarefa[Descr_Tipo]</b> e encaminhada para <b>$novoGrupo[Titulo] $novoUser[Nome]</b>";
	 	mpress_query("insert into chamados_workflows_tarefas_follows(Workflow_Tarefa_ID, Descricao, Hora_Inicio, Hora_Fim, Usuario_Cadastro_ID)values('$idTarefa','$observacao',now(),now(),'$userID')");
	}else{
		mpress_query("insert into chamados_workflows_tarefas_follows(Workflow_Tarefa_ID, Descricao, Hora_Inicio, Hora_Fim, Usuario_Cadastro_ID)values('$idTarefa','$observacao','$horaInicio','$horaFinal','$userID')");
	}
	if($_GET['finaliza']=='s') mpress_query("update chamados_workflows_tarefas set Situacao_ID = 84 where Workflow_Tarefa_ID = '$idTarefa'");

	$erro = mysql_error();
	if($erro=="")
		mostraDadosFolowsTarefas($idWorkflow);
	else echo $erro;
}

function calculaHorasUtilizadasTarefas($workflowTarefaID){
	$rs = mpress_query("select timediff(f.Hora_Fim,f.Hora_Inicio) Horas_Utilizadas from chamados_workflows_tarefas t inner join chamados_workflows_tarefas_follows f on f.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID and t.Workflow_Tarefa_ID = $workflowTarefaID");
	while($row = mpress_fetch_array($rs)){
		$horasInicio 	= substr($row['Horas_Utilizadas'], 0, strlen($row['Horas_Utilizadas'])-3);
		$horasTarefa 	= substr($horasInicio, 0, strlen($horasInicio)-3);
		$minutosTarefa 	= substr($horasInicio, -2);
		$horasTotais 	+= $horasTarefa;
		$minutosTotais 	+= $minutosTarefa;
	}
	$horasMinutos 	= (int)($minutosTotais/60);
	$minutosTotais  = $minutosTotais%60;
	$horasTotais	+= $horasMinutos;

	return "$horasTotais horas e $minutosTotais minutos";
}

function calculaHorasUtilizadasWorkflow($workflowID){
	$rs = mpress_query("select timediff(f.Hora_Fim,f.Hora_Inicio) Horas_Utilizadas from chamados_workflows_tarefas t inner join chamados_workflows_tarefas_follows f on f.Workflow_Tarefa_ID = t.Workflow_Tarefa_ID where t.Workflow_ID = $workflowID");
	while($row = mpress_fetch_array($rs)){
		$horasInicio 	= substr($row['Horas_Utilizadas'], 0, strlen($row['Horas_Utilizadas'])-3);
		$horasTarefa 	= substr($horasInicio, 0, strlen($horasInicio)-3);
		$minutosTarefa 	= substr($horasInicio, -2);
		$horasTotais 	+= $horasTarefa;
		$minutosTotais 	+= $minutosTarefa;

	}

	$horasMinutos 	= (int)($minutosTotais/60);
	$minutosTotais  = $minutosTotais%60;
	$horasTotais	+= $horasMinutos;

	return "$horasTotais horas e $minutosTotais minutos";
}

function cancelaTarefaChamado($idWorkflow){
	$idTarefa		= $_POST['tarefa-id'];
	mpress_query("delete from chamados_workflows_tarefas where Workflow_Tarefa_ID = '$idTarefa'");
	mostraDadosFolowsTarefas($idWorkflow);
}

/*
function alteraTarefaDataRetorno($idTarefa){
	$userID			= $_SESSION['dadosUserLogin']['userID'];
	$dataAlterada 	= implode('-',array_reverse(explode('/',substr($_POST['data-retorno-altera'],0,10))));
	mpress_query("update chamados_workflows_tarefas set Data_Retorno = '$dataAlterada 18:00' where Workflow_Tarefa_ID = '".$_POST['tarefa-id']."'");
	mpress_query("insert into chamados_workflows_tarefas_follows(Workflow_Tarefa_ID, Descricao, Hora_Inicio, Hora_Fim, Usuario_Cadastro_ID)values('".$_POST['tarefa-id']."','Data de retorno alterada de ".$_POST['data-retorno-atual']." para ".$_POST['data-retorno-altera']."',now(),now(),'$userID')");
	echo $_POST['data-retorno-altera'];
}
*/

function calculaDataLimite(){
	$dataAbertura = $_POST['data-abertura-chamado'];
	$prioridade = $_POST['select-prioridade'];
	if (($prioridade!="")&&($dataAbertura!="")){
		$resultado = mpress_query("select Tipo_Auxiliar from tipo where Tipo_ID = '$prioridade'");
		if ($row = mpress_fetch_array($resultado)){
			$arrayPrioridades = unserialize($row[Tipo_Auxiliar]);
			$dias = 0;

			if ($arrayPrioridades[tempo]!=""){
				$horas = explode(":", $arrayPrioridades[tempo]);
				$dataLimite = somarData($dataAbertura, 0, 0, 0, $horas[0], $horas[1]);
			}

			if (($arrayPrioridades['sabado']=='1') || ($arrayPrioridades['domingo']=='1')){
				$retorno = retornaNumeroDias($dataAbertura,$dataLimite);

				if ($arrayPrioridades['sabado']=='1')
					$dias += $retorno[sabado];
				if ($arrayPrioridades['domingo']=='1')
					$dias += $retorno[domingo];
				$dataLimiteAux = somarData($dataLimite, $dias, 0, 0, 0, 0);

				$retorno = retornaNumeroDias($dataLimite,$dataLimiteAux);
				if ($arrayPrioridades['sabado']=='1')
					$dias += $retorno[sabado];
				if ($arrayPrioridades['domingo']=='1')
					$dias += $retorno[domingo];

				$dataLimite = somarData($dataLimite, $dias, 0, 0, 0, 0);

				if ($arrayPrioridades['sabado']=='1'){
					if (date('w',strtotime(implode('-',array_reverse(explode('/',substr($dataLimite,0,10))))))==0)
						$dataLimite = somarData($dataLimite, 1, 0, 0, 0, 0);
				}
				if ($arrayPrioridades['domingo']=='1'){
					if (date('w',strtotime(implode('-',array_reverse(explode('/',substr($dataLimite,0,10))))))==6)
						$dataLimite = somarData($dataLimite, 1, 0, 0, 0, 0);
				}

			}
		}
	}
	echo $dataLimite;
}

function optionValuePrioridade($prioridade){
	$tipos = mpress_query("select Tipo_ID, Descr_Tipo, Tipo_Auxiliar from tipo where tipo_grupo_id = '21' and Situacao_ID = 1 order by descr_tipo");
	while($tipo = mpress_fetch_array($tipos)){
		$array = unserialize($tipo[Tipo_Auxiliar]);
		if ($prioridade==$tipo['Tipo_ID']){$seleciona='selected';}else{$seleciona='';}
		$optionValue .= "<option value='".$tipo['Tipo_ID']."' $seleciona style='font-weight:bold; color:".$array[cor-texto].";'>".$tipo['Descr_Tipo']."</option>";
	}
	return $optionValue;
}


function excluiFollowTarefa($idTarefa){
	mpress_query("delete from chamados_workflows_tarefas_follows where Workflow_Tarefa_Follow_ID = $idTarefa");
}


function chamadosCarregarChamadosRelatorio(){
	$workflowID = $_POST['workflow-id'];
	$descSabado = $_POST["desconsiderar-sabado"];
	$descDomingo = $_POST["desconsiderar-domingo"];

	if($workflowID!="") $condicoes .= " and cw.Workflow_ID IN ($workflowID)";
	$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, tw.Descr_Tipo as Tipo_Chamado, cw.Titulo,
						cd1.Nome as Solicitante, cd1.Nome_Fantasia as Solicitante_Fantasia, cd1.email as Email_Solicitante,
						cd2.Nome as Prestador, cd2.Nome_Fantasia as Prestador_Fantasia, cd2.email as Email_Prestador,
						t.Descr_Tipo as Situacao, cf.Situacao_ID as Situacao_ID,
						DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
						DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y %H:%i') as Data_Hora_Abertura,
						DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
						DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
						DATE_FORMAT(cw.Data_Limite,'%d/%m/%Y') as Data_Limite,
						DATE_FORMAT(now(), '%d/%m/%Y') as Data_Hoje,
						p.Descr_Tipo as Prioridade, p.Tipo_Auxiliar, r.Nome as Responsavel, g.Titulo as Grupo_Responsavel,
						datediff(now(), cw.Data_Abertura) as Dias
				from chamados_workflows cw
				inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
				left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
				left join tipo tw on tw.Tipo_ID = cw.Tipo_WorkFlow_ID and tw.Tipo_Grupo_ID = 19
				left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
					and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
				left join tipo t on t.Tipo_ID = cf.Situacao_ID
				left join tipo p on p.Tipo_ID = cw.Prioridade_ID
				left join cadastros_dados r on r.Cadastro_ID = cw.Responsavel_ID
				left join modulos_acessos g on g.Modulo_Acesso_ID = cw.Grupo_Responsavel_ID
				where cw.Workflow_ID > 0
					$condicoes
				order by cw.Workflow_ID desc";
		//echo $sql;

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$solicitante = $row[Solicitante];
		$arrayPrioridades = unserialize($row[Tipo_Auxiliar]);
		if ($row[Email_Solicitante]!=""){ $solicitante .= "&nbsp;(".$row[Email_Solicitante].")";}
		$prestador = $row[Prestador];
		if ($row[Email_Prestador]!=""){ $prestador .= "&nbsp;(".$row[Email_Prestador].")";}
		$dataLimite = "";
		if (($row[Data_Limite]!="")&&(substr($row[Data_Limite],0,10)!="00/00/0000")){
			if ($row[Responsavel]!="") $traco = " - "; else $traco = "";
			$dataLimite = "<span title='Responsável: ".$row[Responsavel].$traco.$row[Grupo_Responsavel]."' style='cursor:pointer;'>".$row[Data_Limite]."</span>";
		}


		$sabados = $domingos = 0;
		if (($descSabado=="1") || ($descDomingo=="1")){
			$arrDias = retornaNumeroDias($row[Data_Abertura],$row[Data_Hoje]);
			$sabados = $arrDias[sabado];
			$domingos = $arrDias[domingo];
		}
		$dias = $row[Dias] - $sabados - $domingos;

		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Workflow_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Codigo]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Tipo_Chamado]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Titulo]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."' title='".$row[Solicitante_Fantasia]."'>".$solicitante."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Interacao]."</p>";
		$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Responsavel]."</p>";
		$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;color:".$arrayPrioridades[cor]."'>".$row[Prioridade]."</p>";
		$dados[colunas][conteudo][$i][11] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$dataLimite."</p>";
		$dados[colunas][conteudo][$i][12] = "<p Style='margin:2px 3px 0 3px;' align='center'>".$dias."</p>";
	}

	$largura = "100%";
	$colunas = "12";
	$dados[colunas][titulo][1] 	= "ID";
	$dados[colunas][titulo][2] 	= $_SESSION['objeto'];
	$dados[colunas][titulo][3] 	= "Tipo";
	$dados[colunas][titulo][4] 	= "T&iacute;tulo";
	$dados[colunas][titulo][5] 	= "Solicitante";
	$dados[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][7] 	= "Abertura";
	$dados[colunas][titulo][8]	= "Intera&ccedil;&atilde;o";
	$dados[colunas][titulo][9] 	= "Respons&aacute;vel";
	$dados[colunas][titulo][10] = "Prioridade";
	$dados[colunas][titulo][11] = "Limite";
	$dados[colunas][titulo][12]	= "Aberto (Dias)";

	$dados[colunas][tamanho][1] = "width='40px'";
	$dados[colunas][tamanho][2] = "width='90px'";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][4] = "";
	$dados[colunas][tamanho][5] = "";
	$dados[colunas][tamanho][6] = "";
	$dados[colunas][tamanho][7] = "width='70px'";
	$dados[colunas][tamanho][8] = "width='70px'";
	$dados[colunas][tamanho][9] = "";


	echo " <div class='titulo-container'>
				<div class='titulo'>
					<p>Registros Localizados: $i</p>
				</div>
				<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
	geraTabela($largura,$colunas,$dados, null, 'cahamados-relatorio-regional', 2, 2, 100,"");
	echo "		</div>
			</div>";
}


function orcamentoSalvar(){
	global $dadosUserLogin, $configChamados, $caminhoSistema;

	//var_dump($_POST);
	// die();

	$workflowID 		= $_POST['workflow-id'];
	$solicitanteID 		= $_POST['solicitante-id'];
	$empresaID			= $_POST['empresa-id'];
	$projetoID			= $_POST['projeto-id-orcamento'];
	$titulo 			= utf8_decode($_POST['titulo-orcamento']);
	$codigo 			= utf8_decode($_POST['codigo-workflow']);
	$descricao		 	= utf8_decode($_POST['descricao-follow']);
	$situacaoID 		= $_POST['situacao-follow-orcamento'];
	$representanteID 	= $_POST['representante-orcamento'];
	$dataHoraAtual 	= "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$dataAbertura = "'".converteDataHora($_POST['data-abertura-orcamento'])."'";

	//Adição de campos para referenciar a origem do orçamento e o possivel parceiro que efetuou a indicação
	$origemOrcamento 	= $_POST['situacao-origem-workflow'];
	if($origemOrcamento == "") $origemOrcamento = "0"; 

	if(isset($_POST['situacao-parceiro-workflow'])){
		$identificacaoParceiro = $_POST['situacao-parceiro-workflow'];
	}else{
		$identificacaoParceiro = '0';
	}

	if ($situacaoID==113)
		$dataHoraFinalizado = "'".converteDataHora($_POST['data-finalizado-orcamento'])."'";
	else
		$dataHoraFinalizado = "NULL";

	if (($workflowID=="")||($workflowID=="0")){
		//$sql = "Insert Into orcamentos_workflows (Empresa_ID, Solicitante_ID, Representante_ID, Situacao_ID, Codigo, Titulo, Data_Abertura, Data_Finalizado, Data_Cadastro, Usuario_Cadastro_ID)
										  //Values ('$empresaID', '$solicitanteID', '$representanteID', '$situacaoID', '$codigo', '$titulo', $dataAbertura, $dataHoraFinalizado, $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		//QUERY ATUALIZADA PARA SALVAR O ORÇAMENTO COM A ID DE ORIGEM E DO POSSIVEL PARCEIRO.
		$sql = "INSERT INTO orcamentos_workflows (
					Empresa_ID,
					Solicitante_ID,
					Representante_ID,
					Situacao_ID,
					Codigo,
					Titulo,
					Data_Abertura,
					Data_Finalizado,
					Data_Cadastro,
					Usuario_Cadastro_ID,
					Origem_ID,
					Parceiro_ID
				)VALUES(
					'$empresaID',
					'$solicitanteID',
					'$representanteID',
					'$situacaoID',
					'$codigo',
					'$titulo',
					$dataAbertura,
					$dataHoraFinalizado,
					$dataHoraAtual,
					'".$dadosUserLogin['userID']."',
					'$origemOrcamento',
					'$identificacaoParceiro'
				)";

		//var_dump($_POST['situacao-origem-workflow'], $sql);

		mpress_query($sql);
		$workflowID = mysql_insert_id();

		/* chamada para função de criação do projeto */
		if ($projetoID!=""){
			salvarProjeto($projetoID, $workflowID, 'Workflow_ID' , 'orcamentos_workflows', $representanteID, $solicitanteID);
		}

	}
	else{
		/**/
		$sql = "SELECT o.Empresa_ID, o.Solicitante_ID, o.Representante_ID, r.Nome AS Representante_Antigo, rn.Nome AS Representante, o.Codigo, o.Titulo, o.Data_Abertura, o.Data_Finalizado
				FROM orcamentos_workflows o
				LEFT JOIN tipo org_t ON org_t.Tipo_ID = o.Origem_ID
				LEFT JOIN sistema_parceiros sPar ON sPar.Parceiro_ID = o.Parceiro_ID
				LEFT JOIN cadastros_dados r ON r.Cadastro_ID = o.Representante_ID
				LEFT JOIN cadastros_dados rn ON rn.Cadastro_ID = '$representanteID'
				WHERE Workflow_ID = '$workflowID'";
		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			if ($row['Representante_ID']!=$representanteID){
				$descricao .= "<p>Atualizado representante  de <b>".$row[Representante_Antigo]."</b> para <b>".$row[Representante]."</b><p>";
				if ($representanteID!=0){
					atualizarTarefasUsuarioProjeto($workflowID, 'Workflow_ID', 'orcamentos_workflows', $row['Representante_ID'], $representanteID);
				}

			}
		}

		/**/


		//$sql = "Update orcamentos_workflows set Empresa_ID = '$empresaID', Solicitante_ID = '$solicitanteID', Representante_ID = '$representanteID', Situacao_ID = '$situacaoID',
		//										Codigo = '$codigo', Titulo = '$titulo', Data_Abertura = $dataAbertura, Data_Finalizado = $dataHoraFinalizado where Workflow_ID = '$workflowID'";
		
		//Atualização contando com Id de origem do orçamento e do parceiro que indicou
		$sql = "UPDATE
				orcamentos_workflows
				SET
				Empresa_ID 			= '$empresaID',
				Solicitante_ID 		= '$solicitanteID',
				Representante_ID 	= '$representanteID',
				Situacao_ID 		= '$situacaoID',
				Codigo 				= '$codigo',
				Titulo 				= '$titulo',
				Data_Abertura 		= $dataAbertura,
				Data_Finalizado 	= $dataHoraFinalizado,
				Origem_ID 			= '$origemOrcamento',
				Parceiro_ID 		= '$identificacaoParceiro'
				WHERE
				Workflow_ID 		= '$workflowID'";

		// var_dump($_POST);
		// die();

		mpress_query($sql);
	}


	/* ATUALIZAR TABELA DE CRM PARA DEPOIS FAZER O RELATÓRIOS PIPELINE */
	if ($configChamados['crm']==1){
		$origemID 	= $_POST['oportunidade-origem'];
		$valorExpectativa = formataValorBD($_POST['oportunidade-valor']);
		$probabilidadeFechamento = $_POST['oportunidade-probabilidade-fechamento'];
		$dataPrevisaoFechamento = formataDataBD($_POST['oportunidade-data-previsao-fechamento']);
		$tipoOportunidade = $_POST['oportunidade-tipo'];

		$sql = "select Oportunidade_ID from oportunidades_workflows where Orcamento_ID = '".$workflowID."'";
		$resultado = mpress_query($sql);
		$oportunidadeID = "";
		if($row = mpress_fetch_array($resultado)){
			$oportunidadeID = $row['Oportunidade_ID'];
		}
		if ($oportunidadeID==''){
			$sql = "INSERT INTO oportunidades_workflows
							(Orcamento_ID, Tipo_ID, Cadastro_ID, Empresa_ID, Origem_ID, Titulo, Descricao, Expectativa_Valor, Probabilidade_Fechamento, Data_Previsao, Situacao_ID, Status_ID, Responsavel_ID, Usuario_Cadastro_ID, Data_Cadastro)
					VALUES ('$workflowID', '$tipoOportunidade', '$solicitanteID', '$empresaID', '$origemID', '$titulo', '$descricao', '$valorExpectativa', '$probabilidadeFechamento', '$dataPrevisaoFechamento','$situacaoID', 1, '$representanteID', ".$dadosUserLogin['userID'].", $dataHoraAtual)";
			//echo "<br>".$sql;
			mpress_query($sql);
			$oportunidadeID = mpress_identity();
		}
		else{
			$sql = "UPDATE oportunidades_workflows SET
						Cadastro_ID = '$solicitanteID',
						Empresa_ID = '$empresaID',
						Origem_ID = '$origemID',
						Tipo_ID = '$tipoOportunidade',
						Titulo = '$titulo',
						Descricao = '$descricao',
						Probabilidade_Fechamento = '$probabilidadeFechamento',
						Data_Previsao = '$dataPrevisaoFechamento',
						Expectativa_Valor = '$valorExpectativa',
						Situacao_ID = '$situacaoID',
						Responsavel_ID = '$representanteID'
					WHERE Oportunidade_ID = '$oportunidadeID'";
			//echo "<br>".$sql;
			mpress_query($sql);
		}
	}

	/**/

	//if ($descricao!=""){
	$sql = "Insert Into orcamentos_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
									 Values ('$workflowID', '$descricao', '', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
	//}
	mpress_query($sql);

	$emailsEnviar = $configChamados['emails-copia-orcamento'];
	$sql = "select email from cadastros_dados where Cadastro_ID IN ('".$dadosUserLogin['userID']."', '$representanteID')";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$emailsEnviar .= $row['email'].";";
	}
	if ($_POST['enviar-email']=="1"){
		enviarEmailOrcamentoProposta($workflowID, "", $situacaoID, $descricao, $emailsEnviar);
	}
	/*
	echo "	<form action='".$caminhoSistema."/chamados/chamados-orcamento' method='post' name='retorno'>
				<input type='' name='workflow-id' value='$workflowID'>
			</form>
			<script>
				// document.retorno.submit();
			</script>";
	*/
	echo $workflowID;
}

function enviarEmailOrcamentoProposta($workflowID, $propostaID, $situacaoID, $observacaoFollow, $emailsEnvio){
	global $dadosUserLogin, $caminhoSistema, $tituloSistema;
	$i = 0;
	$emails = explode(";", $emailsEnvio);
	foreach ($emails as $email) {
		if ($email!=""){
			$i++;
			$dadosEmail[email][$i] = $email;
			$dadosEmail[nome][$i] = $email;
		}
	}
	$resultado = mpress_query("select Descr_Tipo from tipo where Tipo_ID = $situacaoID");
	if($rs = mpress_fetch_array($resultado))
		$descrSituacao = utf8_encode($rs[Descr_Tipo]);
	$titulo = $tituloSistema." - Orçamento: ".$workflowID;


	$resultado = mpress_query("select Solicitante_ID, r.Nome as Representante from orcamentos_workflows ow
							left join cadastros_dados r on r.Cadastro_ID = ow.Representante_ID
							where ow.Workflow_ID = '$workflowID'");
	if($rs = mpress_fetch_array($resultado)){
		$solicitanteID = $rs['Solicitante_ID'];
		$representante = $rs['Representante'];
		//$telefone .= utf8_encode($rs[$telefone])."&nbsp;&nbsp;&nbsp;";

		if ($representante!=""){
			$htmlRepresentante = "	<tr>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Representante</b></td>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".$representante."</td>
								</tr>";
		}
	}

	if ($propostaID!=""){
		$resultado = mpress_query("select Titulo from orcamentos_propostas where Proposta_ID = '$propostaID'");
		while($rs = mpress_fetch_array($resultado)){
			$titulo = utf8_encode($rs[Titulo]);
		}
		$htmlProposta = "		<tr>
								<td align='left' valign='top' width='160' style='border-bottom:1px solid #cccccc;'><b>Or&ccedil;amento:</b></td>
								<td align='left' valign='top' width='575' style='border-bottom:1px solid #cccccc;'>$titulo</td>
							</tr>";
	}

	$conteudoEmail = "
	<html xmlns='http://www.w3.org/1999/xhtml'>
		<head>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
		</head>
		<body style='margin: 0 auto; background-color:#ffffff'>
			<style>
				table {
					font-family: Arial, Verdana, Tahoma, sans-serif; font-weight: normal; font-size:13px; color:#222222; margin:21px; border:0px;
				}
				.tabela-fundo-escuro-titulo{
					border-left:1px solid #cccccc;
					border-bottom:1px solid #cccccc;
					font-weight:bold;
					background-color:#f1f1f1;
					font-size:12px;
				}

				.tabela-fundo-escuro{
					border-left:1px solid #cccccc;
					border-bottom:1px solid #cccccc;
					background-color:#f9f9f9;
				}
				.tabela-fundo-claro{
					border-left:1px solid #cccccc;
					border-bottom:1px solid #cccccc;
					background-color:#ffffff;
				}


				.fundo-escuro-titulo{
					border:0px;
					font-weight:bold;
					background-color:#f1f1f1;
				}

				.fundo-escuro{
					border:0px;
					background-color:#f9f9f9;
				}
				.fundo-claro{
					border:0px;
					background-color:#ffffff;
				}
			</style>
			<table border='0' align='center' cellpadding='0' cellspacing='0'>
				<tr>
					<td width='735' height='80' align='center'>
						<img src='".$caminhoSistema."/images/documentos/cabecalho.jpg' border='0' id='r&r' style='display: block' />
					</td>
				</tr>
				<tr>
					<td>
						<table width='735' border='0' align='center' cellpadding='5' cellspacing='0' style=''>
							<tr>
								<td align='left' valign='top' width='160' style='border-bottom:1px solid #cccccc;'><b>Or&ccedil;amento:</b></td>
								<td align='left' valign='top' width='575' style='border-bottom:1px solid #cccccc;'>$workflowID</td>
							</tr>
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;' colspan='2'>
									<b>Solicitante:</b><br>
									".utf8_encode(carregarCadastroGeral($solicitanteID, 'solicitante-id', 'Solicitante', '','',''))."
								</td>
							</tr>
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Situa&ccedil;&atilde;o:</b></td>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>$descrSituacao</td>
							</tr>
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Observa&ccedil;&otilde;es:</b></td>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".nl2br(utf8_encode($observacaoFollow))."</td>
							</tr>
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Usu&aacute;rio:</b></td>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".$dadosUserLogin['nome']."</td>
							</tr>
							$htmlRepresentante
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Data:</b></td>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".retornaDataHora('','d/m/Y H:i')."</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td width='735' height='55' align='center'>
						<img src='".$caminhoSistema."/images/documentos/rodape.jpg' border='0' style='display: block' />
					</td>
				</tr>
			</table>
		</body>
	</html>";
	//echo $conteudoEmail;
	enviaEmails($dadosEmail, $titulo, $conteudoEmail, "<p>Envio efetuado com successo</p>");
}



/* aqui revolucao*/
function carregarProdutos($chaveID, $tipo){
	if ($chaveID !=""){
		global $modulosAtivos, $caminhoSistema, $caminhoFisico, $dadosUserLogin, $configChamados;
		if ($configChamados['agrupar-produtos']=="agrupar-por-cliente"){
			$condOrder = " cf.Nome, ";
		}
		if ($tipo=="orcamento"){
			$complemento = "-".$chaveID;
			$sql = "SELECT opp.Proposta_Produto_ID as Chave_Primaria_ID, 
						pv.Produto_Variacao_ID, CONCAT(COALESCE(pd.Nome,''),
						' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
						opp.Observacao_Produtos,
						opp.Valor_Venda_Unitario, 
						opp.Valor_Custo_Unitario, 
						opp.Faturamento_Direto,
						opp.Prestador_ID, re.Nome as Prestador,
						opp.Cliente_Final_ID, cf.Nome as Cliente_Final, 
						cf.Foto as Foto_Cliente_Final,
						opp.Quantidade as Quantidade, 
						opp.Cobranca_Cliente, 
						opp.Pagamento_Prestador,
						opp.Data_Cadastro, cd.Nome as Autor, 
						ma.Nome_Arquivo as Nome_Arquivo, 
						tp.Descr_Tipo as Tipo, 
						fc.Descr_Tipo as Forma_Cobranca, 
						op.Proposta_ID as Proposta_ID,
						pd.Produto_ID as Produto_ID, 
						ow.Solicitante_ID as Solicitante_ID,
						op.Forma_Pagamento_ID as Forma_Pagamento_ID
					FROM orcamentos_propostas_produtos opp
					INNER JOIN orcamentos_propostas op ON op.Proposta_ID = opp.Proposta_ID
					INNER JOIN orcamentos_workflows ow on ow.Workflow_ID = op.Workflow_ID
					INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
					INNER JOIN tipo fc ON fc.Tipo_ID = pv.Forma_Cobranca_ID
					LEFT JOIN modulos_anexos ma ON ma.Anexo_ID = pv.Imagem_ID
					LEFT JOIN cadastros_dados cd ON cd.Cadastro_ID = opp.Usuario_Cadastro_ID
					LEFT JOIN cadastros_dados re ON re.Cadastro_ID = opp.Prestador_ID
					LEFT JOIN cadastros_dados cf ON cf.Cadastro_ID = opp.Cliente_Final_ID
					WHERE opp.Proposta_ID = '$chaveID' AND opp.Situacao_ID = 1
					ORDER BY $condOrder opp.Data_Cadastro DESC";
			//echo $sql;
		}
		if ($tipo=="chamado"){
			//$botaoGerarProposta = "<div style='float:left;margin-top:20px; width:25%;' id='proposta-gerar' class='btn-excel' title='Expandir'><span style='margin-left:18px;'><b>GERAR PROPOSTA</b></span></div>";
			$sql = "SELECT cwp.Workflow_Produto_ID as Chave_Primaria_ID, 
							pv.Produto_Variacao_ID as Produto_Variacao_ID, 
							cwp.Cobranca_Cliente as Cobranca_Cliente,
							cwp.Pagamento_Prestador as Pagamento_Prestador, 
							re.Nome as Prestador,
							DATE_FORMAT(cwp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, 
							concat(coalesce(pd.Nome,''),
							' ',coalesce(pv.Descricao,'')) as Descricao_Produto,
							 cd.Nome as Autor,
							pv.Codigo as Codigo, 
							Quantidade as Quantidade,
							cwp.Valor_Venda_Unitario, 
							cwp.Valor_Custo_Unitario, 
							cwp.Faturamento_Direto,
							cwp.Observacao_Produtos,
							pd.Tipo_Produto as Tipo_Produto,
							cwp.Cliente_Final_ID, 
							cf.Nome as Cliente_Final, 
							cf.Foto as Foto_Cliente_Final,
							fc.Descr_Tipo as Forma_Cobranca, 
							tp.Descr_Tipo AS Tipo, 
							ma.Nome_Arquivo as Nome_Arquivo, 
							pd.Produto_ID as Produto_ID,
							(SELECT count(*) FROM financeiro_produtos fp1
							INNER JOIN financeiro_contas fc1 on fc1.Conta_ID = fp1.Conta_ID
							WHERE fp1.Tabela_Estrangeira = 'chamados' and fp1.Produto_Referencia_ID = cwp.Workflow_Produto_ID and fc1.Tipo_ID = '45' and fp1.Situacao_ID = 1) as A_Pagar,
								(SELECT count(*) FROM financeiro_produtos fp2
											INNER JOIN financeiro_contas fc2 on fc2.Conta_ID = fp2.Conta_ID
											WHERE fp2.Tabela_Estrangeira = 'chamados' and fp2.Produto_Referencia_ID = cwp.Workflow_Produto_ID   and fc2.Tipo_ID = '44' and fp2.Situacao_ID = 1) as A_Receber,

							oc.Chamado_ID as Chamado_ID

							FROM chamados_workflows_produtos cwp
							LEFT JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
							LEFT JOIN produtos_dados pd on pd.Produto_ID = pv.Produto_ID
							LEFT JOIN tipo fc ON fc.Tipo_ID = pv.Forma_Cobranca_ID
							LEFT JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
							LEFT JOIN modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
							LEFT JOIN cadastros_dados cd on cd.Cadastro_ID = cwp.Usuario_Cadastro_ID
							LEFT JOIN cadastros_dados re ON re.Cadastro_ID = cwp.Prestador_ID
							LEFT JOIN cadastros_dados cf ON cf.Cadastro_ID = cwp.Cliente_Final_ID
							LEFT JOIN orcamentos_produtos_chamados_produtos opcp on opcp.Chamado_ID = cwp.Workflow_ID and opcp.Chamado_Produto_ID = cwp.Workflow_Produto_ID
							LEFT JOIN orcamentos_chamados oc on oc.Chamado_ID = opcp.Chamado_ID
							WHERE Workflow_ID = '$chaveID' and cwp.Situacao_ID = 1
					ORDER BY $condOrder cwp.Data_Cadastro desc";
			// echo $sql;
		}

		
		//echo $sql;
		//die();
		
		$resultado = mpress_query($sql);
		$i = 0;
		$clienteFinalIDAnt = -1;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$prestadorNome = preencheTextoSeVazio("N&atilde;o Selecionado", $row[Prestador]);
			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			if ($row[Nome_Arquivo]!="")
				$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
			else{
				if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1"))){
					$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
				}
			}
			$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:50px; max-height:50px' align='center' src='$nomeArquivo'/></a>";
			$totalProdutos += $row['Quantidade'] * $row['Valor_Venda_Unitario'];
			$totalCusto += $row['Quantidade'] * $row['Valor_Custo_Unitario'];

			if (($configChamados['agrupar-produtos']=="agrupar-por-cliente") && ($clienteFinalIDAnt!=$row['Cliente_Final_ID'])){
				$foto = $row['Foto_Cliente_Final'];
				if (($foto!="") && (file_exists("$caminhoFisico/uploads/$foto")))
					$imagemFoto = "<img src='$caminhoSistema/uploads/$foto' style='height:40px; cursor:pointer;'>";
				else
					$imagemFoto = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' style='height:40px; cursor:pointer;'>";


				echo "	<div class='destaque-tabela titulo-secundario' style='float:left; width:100%;' align='center'>
							<div style='float:left; width:50%;' align='right'>
								<p> $imagemFoto </p>
							</div>
							<div style='float:left; width:50%;' align='left'>
								<p style='margin-left:10px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$row['Cliente_Final']."</p>
								<p style='margin-left:28px;'>
									<a class='fancybox fancybox.iframe' href='http://seguranca.oticasdiorama.com.br/wp-content/plugins/otica-mpress/dados-medidas.php?cadastroID=".$row['Cliente_Final_ID']."'>
										<input type='button' value='Medidas e\n Receitas' class='medidas-otica' style='font-size:9px; width:100px; height:28px; padding-left:1px;' >
									</a>
								</p>
							</div>
						</div>";
			}
			$hOrigem = "";
			if ($row['Chamado_ID']!=''){
				$hOrigem = "<p><b>Origem:</b></p><p>Or&ccedil;amento <span class='link link-orcamento' workflow-id='".$row['Chamado_ID']."'>".$row['Chamado_ID']."</span></p>";
			}

			$observacaoProd = "";

			if(!empty($row[Observacao_Produtos])){
				$observacaoProd = "<p><b>Observa&ccedil;&atilde;o</b></p><p><textarea rows='4' cols='50' name='observacao-produto[]' class='descricao-produto' style='width:97%' readonly='readonly'>".$row[Observacao_Produtos]."</textarea></p>";
			}

			echo "	<div id='conteudo-produto-".$row['Chave_Primaria_ID']."'>
						<fieldset style='margin-bottom:2px;'>
							<legend><b>".$row['Tipo']."</b></legend>";
			echo "			<div class='titulo-secundario' style='float:left; width:05%' align='center'>$imagemProduto</div>
							<div class='titulo-secundario' style='float:left; width:40%'>
								<p><b>Descri&ccedil;&atilde;o:</b></p>
								<p><input type='text' name='descricao-produto[]' class='descricao-produto' value='".$row[Descricao_Produto]."' style='width:97%' readonly='readonly'/></p>
								".$observacaoProd."
							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>
								<p><b>Quantidade:</b></p>
								<p><input type='text' name='quantidade-produto[]' class='quantidade-produto formata-valor' readonly value='".number_format($row['Quantidade'], 2, ',', '.')."' style='width:85%'/></p>

							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>
								<p><b>Valor Unit&aacute;rio:</b></p>
								<p><input type='text' name='valor-unitario-produto[]' class='valor-unitario-produto formata-valor' value='".number_format($row['Valor_Venda_Unitario'], 2, ',', '.')."' readonly style='width:85%'/></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>
								<p><b>Valor Total:</b></p>
								<p><input type='text' name='valor-total-produto[]' class='valor-total-produto' id='valor-total-produto".$complemento."' value='".number_format(($row[Quantidade] * $row[Valor_Venda_Unitario]), 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>
								<p><b>Cobran&ccedil;a:</b></p>
								<p><input type='text' name='forma-cobranca[]' class='forma-cobranca'  value='".$row['Forma_Cobranca']."' style='width:85%' readonly='readonly'/></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>
								<p><b>".$row['Autor']."</b></p>
								<p>".converteDataHora($row['Data_Cadastro'],1)."</p>
								$hOrigem
							</div>
							<div class='titulo-secundario' style='float:right; width:05%'>
								<p>
									<div class='btn-excluir btn-excluir-produto-$tipo' style='float:right; padding-right:1px' chave-primaria-id='".$row['Chave_Primaria_ID']."' title='Excluir'>&nbsp;</div>
									<div class='btn-editar btn-editar-produto-$tipo' style='float:right; padding-right:1px' chave-primaria-id='".$row['Chave_Primaria_ID']."' title='Editar'>&nbsp;</div>
									<!--<div><input type='button' class='obs-produto' value='Obs.' style='width:30px;height:20px; font-size:9px' class='fancybox' rel='fancybox' href='' /></div>-->
								</p>
							</div>";
			if ($row['Pagamento_Prestador']=="1"){
				echo "		<div style='float:left; width:100%'>
								<div class='titulo-secundario' style='float:left; width:05%' align='center'>&nbsp;</div>
								<div class='titulo-secundario' style='float:left; width:40%'>
									<p><b>Fornecedor:</b></p>
									<p><input type='text' name='prestador-produto[]' class='prestador-produto' value='$prestadorNome' style='width:97%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'><p>&nbsp;</p></div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>Custo Unit&aacute;rio:</b></p>
									<p><input type='text' name='valor-custo-produto[]' class='valor-custo-produto formata-valor' value='".number_format($row['Valor_Custo_Unitario'], 2, ',', '.')."' readonly style='width:85%'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p><b>Custo Total:</b></p>
									<p><input type='text' name='valor-custo-total[]' class='valor-custo-total' value='".number_format(($row['Quantidade'] * $row['Valor_Custo_Unitario']), 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>";
				if ($row['Faturamento_Direto']=="1"){
					echo "		<div class='titulo-secundario' style='float:left; width:10%; margin-top:18px;'>
									<p><b>* Faturamento Direto</b></p>
								</div>";
				}
				echo "		</div>";
			}
			echo "		</fieldset>
					</div>";
			$clienteFinalIDAnt 	= $row['Cliente_Final_ID'];
			$solicitanteID 		= $row['Solicitante_ID'];
			$formaPagamentoID 	= $row['Forma_Pagamento_ID'];

			//echo "teste de variavel !!! ".$clienteFinalIDAnt." solicitante ".$solicitanteID." forma ".$formaPagamentoID;
		}


		if($i==0){
			echo "<fieldset style='margin-bottom:15px; margin-top:5px;'>";
			echo "	<p Style='margin:20px 5px 20px 5px; text-align:center'>Nenhum produto ou servi&ccedil;o cadastrado</p>";
			echo "</fieldset>";
		}
		echo "	<div style='margin-top:16px'>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<div class='titulo-secundario' style='float:left; width:100%;'>";

		if (($configChamados['exibe-bloco-frete']) && ($tipo=="orcamento")){
			$resultado = mpress_query("SELECT Tipo_Frete, Forma_Envio_ID, Endereco_Entrega_ID, Valor_Frete, Valor_Seguro FROM orcamentos_propostas_envios where Proposta_ID = '$chaveID' and Situacao_ID = 1");
			if($rsFrete = mpress_fetch_array($resultado)){
				$tipoFrete 			= $rsFrete['Tipo_Frete'];
				$valorFrete 		= $rsFrete['Valor_Frete'];
				$valorSeguro 		= $rsFrete['Valor_Seguro'];
				$totalFrete 		= $valorFrete + $valorSeguro;
				//$totalCusto += $valorFrete + $valorSeguro;
				$enderecoEntregaID 	= $rsFrete['Endereco_Entrega_ID'];
				$formaEnvioID 		= $rsFrete['Forma_Envio_ID'];
			}

			if ($tipoFrete!='CIF') $escondeValoresFrete = "esconde";
			echo "			<fieldset style='margin-bottom:2px; width:98%; min-height:80px;'>
								<legend><b>Frete</b></legend>
								<div style='float:left; width:40%; margin-top: 10px;'>
									<div style='float:left; width:99%;'>
										<p><b>Tipo Frete:</b></p>
										<p><select name='tipo-frete[".$chaveID."]' id='tipo-frete-".$chaveID."' class='tipo-frete flag-frete'><option value=''>Selecione</option>".optionValueFrete($tipoFrete)."</select></p>
									</div>
								</div>";
			echo "				<div style='float:left; width:60%; margin-top: 10px;'>
									<p><b>Endere&ccedil;o entrega:</b></p>
									<p><select name='endereco-entrega[".$chaveID."]' id='endereco-entrega-".$chaveID."' class='flag-frete'><option value=''>Selecione</option>".optionValueCadastrosEnderecos($solicitanteID, $enderecoEntregaID)."</select></p>
								</div>";
			echo "				<div style='float:left; width:40%; margin-top: 10px;' class='exibe-valores-frete ".$escondeValoresFrete."'>
									<div style='float:left; width:99%;'>
										<p><b>Forma Envio:</b></p>
										<p><select name='forma-envio[".$chaveID."]' id='forma-envio-".$chaveID."' class='flag-frete'>".optionValueGrupo(31, $formaEnvioID, "Selecione")."<select></p>
									</div>
								</div>";

			echo "				<div style='float:left; width:20%; margin-top: 10px;' class='exibe-valores-frete ".$escondeValoresFrete."'>
									<p><b>Valor Frete:</b></p>
									<p><input type='text' name='valor-frete[".$chaveID."]' id='valor-frete-".$chaveID."' class='formata-valor flag-frete'  style='width:90%' value='".number_format($valorFrete, 2, ',', '.')."'/></p>
								</div>";

			echo "				<div style='float:left; width:20%; margin-top: 10px;' class='exibe-valores-frete ".$escondeValoresFrete."'>
									<p><b>Valor Seguro:</b></p>
									<p><input type='text' name='valor-seguro[".$chaveID."]' id='valor-seguro-".$chaveID."' class='formata-valor flag-frete' style='width:90%' value='".number_format($valorSeguro, 2, ',', '.')."'/></p>
								</div>";
			echo "				<div style='float:right; width:20%; margin-top: 10px;'>
									<p style='margin-top:15px; text-align:center;' align='right'><input type='button' class='atualizar-dados-frete' style='width:110px; height:30px; font-size:10px; float:right;' value='Salvar Frete'/></p>
								</div>";


			echo "			</fieldset>";
		}

		$totalGeral = $totalProdutos + $valorFrete + $valorSeguro;

		echo "				&nbsp;
						</div>
						<!--$botaoGerarProposta-->
						<div class='titulo-secundario' style='float:right; width:50%; margin-bottom: 10px;'>
							<fieldset style='margin-bottom:2px; width:98%; min-height:130px;'>
								<legend><b>Totais</b></legend>";
		echo "					<div class='titulo-secundario' style='float:left; width:33%; margin-top: 10px;'>
									<p><b>Total Produtos:</b></p>
									<p><input type='text' id='valor-total-geral-produto-".$chaveID."' value='".number_format($totalProdutos, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>";
		if (($configChamados['exibe-bloco-frete']) && ($tipo=="orcamento")){
			echo "					<div class='titulo-secundario' style='float:left; width:33%; margin-top: 10px;'>
										<p><b>Total Frete:</b></p>
										<p><input type='text' id='valor-total-geral-frete-".$chaveID."' value='".number_format($totalFrete, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
									</div>";
		}
		echo "					<div class='titulo-secundario' style='float:left; width:34%; margin-top: 10px;'>
									<p><b>Total Geral:</b></p>
									<p><input type='text' id='valor-total-geral-geral-".$chaveID."' value='".number_format($totalGeral, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>";
		//if ($modulosAtivos['financeiro']){
			echo "				<div class='titulo-secundario' style='float:left; width:100%; height: 0px;'>&nbsp;</div>";
			echo "				<div class='titulo-secundario' style='float:left; width:33%; margin-top: 10px;'>&nbsp;</div>";
			echo "				<div class='titulo-secundario' style='float:left; width:33%; margin-top: 10px;'>
									<p><b>Total Custo Fornecedores:</b></p>
									<p><input type='text' id='valor-total-custo-geral-".$chaveID."' class='valor-custo-geral' value='".number_format($totalCusto, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>";
			$saldoGeral = $totalProdutos - $totalCusto - $totalFrete;
			echo "				<div class='titulo-secundario' style='float:left; width:34%; margin-top: 10px;'>
									<p><b>Saldo:</b></p>
									<p><input type='text' id='valor-total-saldo-".$chaveID."' value='".number_format($saldoGeral, 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>";
		//}

		echo "				</fieldset>
						</div>";

		if (($configChamados['exibe-bloco-forma-pagamento']) && ($tipo=="orcamento")){
			echo "		<div class='titulo-secundario' style='float:right; width:50%; margin-bottom: 10px;'>
							<fieldset style='margin-bottom:2px; width:98%;'>
								<legend><b>Forma de Pagamento</b></legend>
								<div class='titulo-secundario' style='float:left; width:100%; margin-top: 10px;'>
									<p><select name='forma-pagamento[".$chaveID."]' id='forma-pagamento-".$chaveID."' class='forma-pagamento vencimentos'>".optionValueGrupo(72, $formaPagamentoID, "Selecione")."</select></p>
								</div>
								<div id='exibir-campos-forma-pagamento-".$chaveID."' class='titulo-secundario' style='float:left; width:100%; margin-top: 10px;'>".carregarFormaPagamentoOrcamento($chaveID, $formaPagamentoID, $totalGeral)."</div>
								<div style='float:right; width:100%; margin-top: 10px;'>
									<p align='right'><input type='button' class='atualizar-dados-vencimento' style='width:110px; height:30px; font-size:10px;' value='Salvar Vencimentos'/></p>
								</div>
							</fieldset>
						</div>";
		}

		echo "		</div>
				</div>";

	}
}

function salvarPropostaDadosVencimentos(){
	global $dadosUserLogin;
	$dataHoraAtual 		= "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$propostaID 		= $_POST['proposta-id'];
	$formaPagamentoID 	= $_POST['forma-pagamento'][$propostaID];
	mpress_query("update orcamentos_propostas set Forma_Pagamento_ID = '$formaPagamentoID' where Proposta_ID = '$propostaID'");
	mpress_query("update orcamentos_propostas_vencimentos set Situacao_ID = 2 where Proposta_ID = '$propostaID'");
	$i = 0;
	foreach ($_POST['vencimento-dias'][$propostaID] as $vencimentoDias){
		$vencimentoValor = formataValorBD($_POST['vencimento-valor'][$propostaID][$i]);
		$sql = "INSERT INTO orcamentos_propostas_vencimentos (
							Proposta_ID, 
							Dias_Vencimento, 
							Valor_Vencimento, 
							Data_Cadastro, 
							Usuario_Cadastro_ID, 
							Situacao_ID)
				VALUES	 ('$propostaID', '$vencimentoDias', '$vencimentoValor', $dataHoraAtual, '".$dadosUserLogin['userID']."', 1)";
		$resultado = mpress_query($sql);
		$i++;
	}
}

function carregarFormaPagamentoOrcamento($propostaID, $formaPagamentoID, $valorTotalProposta){
	global $caminhoSistema, $dadosUserLogin;
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

	$sql 		= "SELECT Forma_Pagamento_ID FROM orcamentos_propostas where Proposta_ID = '$propostaID'";
	$resultado 	= mpress_query($sql);
	if($rs = mpress_fetch_array($resultado))
		$formaPagamentoIDAnt = $rs['Forma_Pagamento_ID'];

	$sql = "select sum(Valor_Vencimento) as Total_Vencimento from orcamentos_propostas_vencimentos where Proposta_ID = 144 and Situacao_ID = 1";
	$resultado = mpress_query($sql);

	if($rs = mpress_fetch_array($resultado))
		$valorTotalPropostaAnt = $rs['Total_Vencimento'];

	if ($formaPagamentoID!=''){
		if (($formaPagamentoIDAnt==$formaPagamentoID) && ($valorTotalPropostaAnt==$valorTotalProposta)){
			$flag = 0;
			$resultado = mpress_query("SELECT Dias_Vencimento, Valor_Vencimento FROM orcamentos_propostas_vencimentos where Proposta_ID = '$propostaID' and Situacao_ID = 1 order by Dias_Vencimento");
			while($rs = mpress_fetch_array($resultado)){
				$flag++;
				$h .= "	<div style='float:left; width:100%;'>
							<div class='titulo-secundario' style='float:left; width:30%;'>&nbsp;&nbsp;&nbsp;&nbsp;Parcela $flag</div>
							<div class='titulo-secundario' style='float:left; width:30%;'><input type='text' name='vencimento-dias[$propostaID][]' id='vencimento-dias-$propostaID' value='".$rs['Dias_Vencimento']."' class='formata-numero vencimentos' style='width:50px;'/></div>
							<div class='titulo-secundario' style='float:left; width:30%;'><input type='text' name='vencimento-valor[$propostaID][]' id='vencimento-valor-$propostaID' value='".number_format($rs['Valor_Vencimento'], 2, ',', '.')."' class='formata-valor vencimentos' style='width:95%; text-align:center;'/></div>
							<div class='titulo-secundario' style='float:left; width:10%;'>&nbsp;</div>
						</div>";
			}
		}
		else{
			$resultado = mpress_query("SELECT Tipo_Auxiliar FROM tipo where Tipo_ID = '$formaPagamentoID'");
			if($rs = mpress_fetch_array($resultado)){

				// ATUALIZAR DADOS DE VENCIMENTO NO BD
				mpress_query("update orcamentos_propostas_vencimentos set Situacao_ID = 2 where Proposta_ID = '$propostaID'");

				$dadosFP = unserialize($rs['Tipo_Auxiliar']);
				$i = 0;

				//EFETUA A APLICAÇÃO DA MODIFICAÇÃO DO VALOR DA PROPOSTA
				if(!empty($dadosFP['tipo-bonus-disponivel'])){

					$valorMod = ($valorTotalProposta / 100) * $dadosFP['valor_modificado'];

					if($dadosFP['tipo-bonus-disponivel'] == 'Desconto'){

						$valorVencimentoMod = $valorTotalProposta - $valorMod;

					}else{
						$valorVencimentoMod = $valorTotalProposta + $valorMod;
					}

					$valorVencimento = $valorVencimentoMod / $dadosFP['quantidade-parcelas'];

				}else{

					$valorVencimento = $valorTotalProposta / $dadosFP['quantidade-parcelas'];

				}

				foreach($dadosFP['dias'] as $dias){
					$i++;
					$diaVencimento = $dias;
					$h .= "	<div style='float:left; width:100%;'>
								<div class='titulo-secundario' style='float:left; width:30%;'>&nbsp;&nbsp;&nbsp;&nbsp;Parcela $i</div>
								<div class='titulo-secundario' style='float:left; width:30%;'><input type='text' name='vencimento-dias[$propostaID][]' id='vencimento-dias-$propostaID' value='".$diaVencimento."' class='formata-numero vencimentos' style='width:50px;'/></div>
								<div class='titulo-secundario' style='float:left; width:30%;'><input type='text' name='vencimento-valor[$propostaID][]' id='vencimento-valor-$propostaID' value='".number_format($valorVencimento, 2, ',', '.')."' class='formata-valor vencimentos' style='width:95%; text-align:center;'/></div>
								<div class='titulo-secundario' style='float:left; width:10%;'>&nbsp;</div>
							</div>";
					// ATUALIZAR DADOS DE VENCIMENTO NO BD
					$sql = "INSERT INTO orcamentos_propostas_vencimentos (Proposta_ID, Dias_Vencimento, Valor_Vencimento, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID)
																VALUES	 ('$propostaID', '$diaVencimento', '$valorVencimento', $dataHoraAtual, '".$dadosUserLogin['userID']."', 1)";
					$resultado = mpress_query($sql);
				}

				//EXIBIÇÃO DOS VALORES DE DESCONTO E ACRESCIMO
				if(!empty($dadosFP['tipo-bonus-disponivel'])){
					$h .= " <hr><div style='float:left; width:100%; margin-top:15px;'>
							<div class='titulo-secundario' style='float:left; width:25%;'>&nbsp;&nbsp;&nbsp;&nbsp;".$dadosFP['tipo-bonus-disponivel']." em %</div>
							<div class='titulo-secundario' style='float:left; width:25%;'>

								<input type='text' name='' id='' value='".$dadosFP['valor_modificado']." %' class='formata-numero vencimentos' style='width:50px;' readonly='readonly'/>
							</div>

							<div class='titulo-secundario' style='float:left; width:20%;'>
								&nbsp;&nbsp;&nbsp;&nbsp; Total:
							</div>

							<div class='titulo-secundario' style='float:left; width:20%;'>

								<input type='text' name='' id='' value=' R$ ".number_format($valorVencimentoMod, 2, ',', '.')."' class='formata-numero vencimentos' style='width:150px;' readonly='readonly'/>
							</div>

						</div>";
				}
				
			}
		}
	}
	if ($h!=''){
		$h = "	<div style='float:left; width:100%;margin-bottom:10px;'>
					<div class='titulo-secundario' style='float:left; width:30%;'><b>&nbsp;&nbsp;Vencimento</b></div>
					<div class='titulo-secundario' style='float:left; width:30%;'><b>Dias</b></div>
					<div class='titulo-secundario' style='float:left; width:30%;'><b>Valor</b></div>
					<div class='titulo-secundario' style='float:left; width:10%;'>&nbsp;</div>
				</div>
				".$h."
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}
	return $h;
}

function salvarOrcamentoPropostaFrete(){
	global $dadosUserLogin;
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

	$propostaID = $_POST['proposta-id'];
	$tipofrete = $_POST['tipo-frete'][$propostaID];
	$valorFrete = formataValorBD($_POST['valor-frete'][$propostaID]);
	$valorSeguro = formataValorBD($_POST['valor-seguro'][$propostaID]);
	$enderecoEntregaID = formataValorBD($_POST['endereco-entrega'][$propostaID]);
	$formaEnvioID = formataValorBD($_POST['forma-envio'][$propostaID]);
	$sql = "update orcamentos_propostas_envios set Situacao_ID = 2 where Proposta_ID = '$propostaID' and Situacao_ID = 1";
	mpress_query($sql);

	$sql = "INSERT INTO orcamentos_propostas_envios (Proposta_ID, Tipo_Frete, Forma_Envio_ID, Endereco_Entrega_ID, Valor_Frete, Valor_Seguro, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID)
											VALUES ('$propostaID', '$tipofrete', '$formaEnvioID', '$enderecoEntregaID', '$valorFrete', '$valorSeguro',".$dataHoraAtual.", '".$_SESSION['dadosUserLogin']['userID']."', 1)";

	mpress_query($sql);
}


function carregarProdutosOrcamentoCompleta($propostaID){
	global $modulosAtivos, $caminhoSistema;
	if ($propostaID !=""){
		if ($rs = mpress_fetch_array(mpress_query("select Status_ID, Tabela_Preco_ID from orcamentos_propostas where Proposta_ID = '$propostaID'"))){
			$statusPropostaID = $rs[Status_ID];
			$tabelaPrecoID = $rs[Tabela_Preco_ID];
		}

		$sql = "SELECT Produto_Variacao_ID, Produto_Categoria_ID, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario, Status_ID
					FROM orcamentos_propostas_produtos
					where Proposta_ID = '$propostaID' and Situacao_ID = 1";
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][Quantidade] = $rs[Quantidade];
			$valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][ValorUnitario] = $rs[Valor_Venda_Unitario];
			$valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][StatusID] = $rs[Status_ID];
		}
		//echo "<pre>";
		//print_r($valoresPreenchidos);
		//echo "</pre>";

		$resultado = mpress_query("select Tipo_Auxiliar from tipo where Tipo_Grupo_ID = 57");
		while($rs = mpress_fetch_array($resultado))
			$catEvento[$rs['Tipo_Auxiliar']] = true;


		// Produtos Compostos
		$sql = "select pvPai.Produto_Variacao_ID as Produto_Variacao_Pai_ID, concat(pdPai.Nome,' ', pvPai.Descricao) as Composto,  pvFilho.Produto_Variacao_ID, concat(pdFilho.Nome,' ', pvFilho.Descricao) as Descricao_Produto_Filho,
				pc.Quantidade as Quantidade
				from produtos_dados pdPai
				inner join produtos_variacoes pvPai on pvPai.Produto_ID = pdPai.Produto_ID and pdPai.Tipo_Produto = 100 and pvPai.Situacao_ID = 1
				inner join produtos_compostos pc on pc.Produto_Variacao_Pai_ID = pvPai.Produto_Variacao_ID and pc.Situacao_ID = 1
				inner join produtos_variacoes pvFilho on pvFilho.Produto_Variacao_ID = pc.Produto_Variacao_ID
				inner join produtos_dados pdFilho on pdFilho.Produto_ID = pvFilho.Produto_ID
				order by pvPai.Produto_Variacao_ID";
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$arrayCompostos[$rs[Produto_Variacao_Pai_ID]][descricao] .= "<p style='margin: 3px 0 3px 30px;'>".$rs[Quantidade]."  X ".$rs[Descricao_Produto_Filho]."<p>";
			//$arrayCompostos[$rs[Produto_Variacao_ID]][quantidade] .= "<p style='margin-left:10px;'>".$rs[Quantidade]."<p>";
		}
		if (($statusPropostaID=="117")||($statusPropostaID=="118")||($statusPropostaID=="120")||($statusPropostaID=="121")||($statusPropostaID=="122")){
			$sqlInnerStatus = "LEFT JOIN orcamentos_propostas_produtos opp on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID and opp.Proposta_ID = '$propostaID' and opp.Situacao_ID = 1 and opp.Quantidade > 0 ";
			//$ocultarColunaPre = "esconde";
		}
		//else{
		$tabRef = "pv";
		$sqlInner = "";
		if (($tabelaPrecoID!="") && ($tabelaPrecoID!="0")){
			$sqlInner = " inner join produtos_tabelas_precos_detalhes ptpd on ptpd.Tabela_Preco_ID = '$tabelaPrecoID' and ptpd.Produto_Variacao_ID = pv.Produto_Variacao_ID and ptpd.Situacao_ID = 1 ";
			$tabRef = "ptpd";
		}


		$sql = "select opp.Produto_Variacao_ID, opp.Produto_Categoria_ID, pdc.Categoria_ID, opp.Valor_Venda_Unitario, ope.Participantes as Participantes, ope.Data_Evento as Data_Evento
					from orcamentos_propostas_produtos opp
					inner join orcamentos_propostas_eventos ope on ope.Proposta_Produto_ID = opp.Proposta_Produto_ID
					inner join produtos_dados_categorias pdc on pdc.Produto_Categoria_ID = opp.Produto_Categoria_ID
					where opp.Proposta_ID = '$propostaID' and opp.Situacao_ID = 1 and ope.Situacao_ID = 1";

		//echo "<br>".$sql;
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$arrLocais[$propostaID][$rs['Categoria_ID']][ProdutoVariacaoID] 	= $rs['Produto_Variacao_ID'];
			$arrLocais[$propostaID][$rs['Categoria_ID']][DataEvento] 			= $rs['Data_Evento'];
			$arrLocais[$propostaID][$rs['Categoria_ID']][Participantes] 		= $rs['Participantes'];
			$arrLocais[$propostaID][$rs['Categoria_ID']][Valor] 				= $rs['Valor_Venda_Unitario'];
			$arrLocais[$propostaID][$rs['Categoria_ID']][ProdutoCategoriaID] 	= $rs['Produto_Categoria_ID'];

		}


		//echo "...:<pre>";
		//print_r($arrLocais);
		//echo "</pre>";


		$sql = "select coalesce(pcPai.Categoria_ID, 0) AS Categoria_ID, upper(pcPai.Nome) AS Categoria,
					coalesce(pc.Categoria_ID, 0) AS Categoria_Filho_ID, upper(pc.Nome) AS Categoria_Filho,
					pd.Produto_ID,  TRIM(CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,''))) AS Descricao_Produto,
					pdc.Produto_Categoria_ID as Produto_Categoria_ID,
					pv.Forma_Cobranca_ID, COALESCE(ma.Nome_Arquivo,'') AS Nome_Arquivo, tp.Descr_Tipo AS Tipo, pd.Tipo_Produto, pv.Produto_Variacao_ID as Produto_Variacao_ID, fc.Descr_Tipo AS Forma_Cobranca,
					$tabRef.Valor_Custo, $tabRef.Valor_Venda
				FROM produtos_variacoes pv
				INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
				INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
				INNER JOIN tipo fc ON fc.Tipo_ID = pv.Forma_Cobranca_ID
				$sqlInnerStatus
				$sqlInner
				inner JOIN produtos_dados_categorias pdc ON pdc.Produto_ID = pd.Produto_ID AND pdc.Situacao_ID = 1
				inner JOIN produtos_categorias pc ON pc.Categoria_ID = pdc.Categoria_ID AND pc.Categoria_Pai_ID in (select pcaux.Categoria_ID from produtos_categorias pcaux where pcaux.Categoria_Pai_ID = 0)
				inner join produtos_categorias pcPai on pc.Categoria_Pai_ID = pcPai.Categoria_ID
				left join modulos_anexos ma ON ma.Anexo_ID = pv.Imagem_ID
				WHERE pv.Situacao_ID = 1 AND pd.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
						and coalesce(pc.Categoria_ID, 0) = coalesce(pdc.Categoria_ID, 0)
				ORDER BY Categoria, Categoria_Filho, Descricao_Produto";

		//echo "<br>".$sql;
		//$ocultarColunaPre = "";
		//}
		//echo $sql;
		//exit();
		$resultado = mpress_query($sql);
		$i = 0;
		$posicao = 0;
		while($rs = mpress_fetch_array($resultado)){
			if ($rs[Categoria_ID]!=$categoriaIDAnt){
				$i = 0;
				$cat++;
				$arrCategoria[$cat] = $rs[Categoria];
				$arrCategoriaID[$cat] = $rs[Categoria_ID];
				$categoriaID = $rs[Categoria_ID];
				//$arrProdutoCategoriaID[$cat] = $rs[Produto_Categoria_ID];
			}
			if ($rs[Categoria_Filho_ID]!=$categoriaFilhoIDAnt){
				$i++;
				$dados[$cat][colunas][conteudo][$i][1] = "<p style='margin:5px 0 5px 40px;'>".$rs[Categoria_Filho]."</p>";
				$dados[$cat][colunas][colspan][$i][1] = "7";
				$dados[$cat][colunas][classe][$i] = "destaque-tabela";
				//$categoriaID = $rs[Categoria_Filho];
			}
			$i++;
			$posicao++;
			$produtoVariacaoID = $rs[Produto_Variacao_ID];
			$statusProdutoID = $valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][StatusID];
			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			if ($rs[Nome_Arquivo]!="") $nomeArquivo = $caminhoSistema."/uploads/".$rs[Nome_Arquivo];
			$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:30px; max-height:30px' src='$nomeArquivo'/></a>";
			$dados[$cat][colunas][conteudo][$i][1] = "<p Style='margin:1px;'>$imagemProduto</p>";
			$dados[$cat][colunas][conteudo][$i][2] = $rs[Descricao_Produto].$arrayCompostos[$rs[Produto_Variacao_ID]][descricao];
			$dados[$cat][colunas][conteudo][$i][3] = $rs[Tipo];
			$tipoCampo = "checkbox";
			$imagemSelecao = "";
			$selecionadoSelecao = "";
			if ($statusProdutoID=="123") { $selecionadoSelecao = "checked"; }
			if ($statusProdutoID=="124") { $imagemSelecao = "btn-disponivel"; $textoSelecao = "Pr&eacute;&ccedil;cionado"; $tipoCampo = "hidden";}
			if ($statusProdutoID=="125") { $imagemSelecao = "btn-indisponivel"; $textoSelecao = "Recusado"; $tipoCampo = "hidden";}
			if (($statusProdutoID==0) || ($statusProdutoID=="")) { $statusProdutoID = "123";}
			$dados[$cat][colunas][extras][$i][4] = "style='display:none;'";
			$dados[$cat][colunas][conteudo][$i][4] = "<p align='center' Style='margin:1px;' class='$ocultarColunaPre'><div class='$imagemSelecao' title='$textoSelecao' style='float:right;margin-right:5px'>&nbsp;</div></p>";

			if ($rs[Forma_Cobranca_ID]=='35'){
				$valorFixo = "readonly='readonly'";
				$formataValor = "";
			}
			if (($rs[Forma_Cobranca_ID]=='58') || ($rs[Forma_Cobranca_ID]=='36')){
				$valorFixo = '';
				$formataValor = "formata-valor";
			}
			if (($statusPropostaID=="117")||($statusPropostaID=="118")||($statusPropostaID=="120")||($statusPropostaID=="121")||($statusPropostaID=="122")){
				//$valorFixo = "readonly='readonly'";
				$formataValor = "";
				//$apenasLeitura = "readonly='readonly'";
			}

			if (($valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][Quantidade]!="0") && ($valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][Quantidade]!="")){
				$quantidade = $valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][Quantidade];
				$valorVenda = $valoresPreenchidos[$propostaID][$rs[Produto_Variacao_ID]][$rs[Produto_Categoria_ID]][ValorUnitario];
				$totalItensCategoria[$cat] += 1;
			}
			else{
				$quantidade = 0;
				$valorVenda = $rs[Valor_Venda];
			}
			$valorTotalProduto = $valorVenda * $quantidade;
			$valorTotalCategoria[$cat] += $valorTotalProduto;
			$quantidadeCategoria[$cat] += $quantidade;

			if($modulosAtivos['chamados-orcamentos-aprovar-recusar'])
				$valorFixo = '';

			$dados[$cat][colunas][conteudo][$i][5] = "<input type='text' name='q[$propostaID][".$rs[Produto_Categoria_ID]."][".$rs[Produto_Variacao_ID]."][]' tabindex='$posicao' id='quantidade-produto-$propostaID-$posicao' posicao='$posicao' proposta='$propostaID' categoria='".$rs[Categoria_ID]."' $apenasLeitura  value='".number_format($quantidade,0,"","")."' class='calcular-total-produto qtde-produto quantidade-produto-variacao-$propostaID-".$rs[Categoria_ID]." formata-numero cp-orc-$propostaID' style='width:50px; text-align:center;'/>";
			$dados[$cat][colunas][conteudo][$i][6] = "<input type='text' name='v[$propostaID][".$rs[Produto_Categoria_ID]."][".$rs[Produto_Variacao_ID]."]' id='valor-produto-variacao-$propostaID-$posicao' posicao='$posicao' proposta='$propostaID' categoria='".$rs[Categoria_ID]."' $valorFixo value='".number_format($valorVenda,2,",",".")."' class='$formataValor calcular-total-produto valor-produto-variacao-$propostaID-".$rs[Categoria_ID]." cp-orc-$propostaID' style='width:100px; text-align:center;'/>";
			$dados[$cat][colunas][conteudo][$i][7] = "<p align='center' Style='margin:1px;' name='valor-total-variacao[$propostaID][]' id='valor-total-variacao-$propostaID-$posicao' posicao='$posicao' proposta='$propostaID' categoria='".$rs[Categoria_ID]."' class='valor-total-produto-$propostaID-".$rs[Categoria_ID]."' style='width:100px; text-align:center;'>".number_format($valorTotalProduto,2,",",".")."</p>";
			$categoriaIDAnt = $rs[Categoria_ID];
			$categoriaFilhoIDAnt = $rs[Categoria_Filho_ID];
		}
		for ($catCont = 1; $catCont <= $cat; $catCont++) {
			$largura = "100%";
			$colunas = "7";
			$dados[$catCont][colunas][titulo][1] 	= "";
			$dados[$catCont][colunas][titulo][2] 	= "Produto / Servi&ccedil;o";
			$dados[$catCont][colunas][titulo][3] 	= "Tipo";
			$dados[$catCont][colunas][titulo][4] 	= "<p align='center' class='pre-selecao-selecionar link $ocultarColunaPre' proposta='$propostaID' categoria='".$arrCategoriaID[$catCont]."'>Pr&eacute; Sele&ccedil;&atilde;o</p>";
			$dados[$catCont][colunas][titulo][5] 	= "Quantidade";
			$dados[$catCont][colunas][titulo][6] 	= "<center>Valor</center>";
			$dados[$catCont][colunas][titulo][7] 	= "Total Parcial";
			$dados[$catCont][colunas][tamanho][1] = "width='30px'";
			$dados[$catCont][colunas][tamanho][2] = "width=''";
			$dados[$catCont][colunas][tamanho][3] = "width='100px'";
			$dados[$catCont][colunas][tamanho][4] = "style='display:none;'";
			$dados[$catCont][colunas][tamanho][5] = "width='007%'";
			$dados[$catCont][colunas][tamanho][6] = "width='010%'";
			$dados[$catCont][colunas][tamanho][7] = "width='010%'";

			$valorTotalCategoria[$catCont] += $arrLocais[$propostaID][$arrCategoriaID[$catCont]][Valor];
			echo "	<input type='hidden' id='flag-proposta-$propostaID' value='1'/>
					<div class='titulo-container-aux bloco-categoria-$propostaID-$catCont' style='border-bottom:0px; '>
						<div class='titulo' style='height:auto;'>
							<p>
								<span style='width:100%;'>
									<span style='width:35%; float:left; margin-bottom:1px;'>".$arrCategoria[$catCont]."</span>
									<span style='width:20%; float:left; margin-bottom:1px; font-weight: normal;'>
										<span>Produtos selecionados: </span>
										<span id='produtos-selecionados-categoria-$propostaID-".$arrCategoriaID[$catCont]."' class='produtos-selecionados-categoria-$propostaID' categoria='".$arrCategoriaID[$catCont]."'>".number_format($totalItensCategoria[$catCont],0,"",".")."</span>
									</span>
									<span style='width:20%; float:left; margin-bottom:1px; font-weight: normal;'>
										<span>Quantidade de Itens:</span>
										<span id='quantidade-itens-categoria-$propostaID-".$arrCategoriaID[$catCont]."'>".number_format($quantidadeCategoria[$catCont],0,"",".")."</span>
									</span>
									<span style='width:20%; float:left; margin-bottom:1px;margin-right:10px; font-weight: normal;'>
										<span>Total Categoria:</span>
										<span id='valor-total-categoria-$propostaID-".$arrCategoriaID[$catCont]."'>".number_format($valorTotalCategoria[$catCont],2,",",".")."</span>
									</span>
									<span style='float:right;' class='btn-retrair exibir-produtos-categoria' posicao='$catCont' proposta='$propostaID'>&nbsp;</span>
								</span>";

			if ($catEvento[$arrCategoriaID[$catCont]]==1){
				$valorEvento = "0,00";
				$dataEvento = "";
				$participantes = "";
				if ($arrLocais[$propostaID][$arrCategoriaID[$catCont]][Valor]!="")
					$valorEvento = number_format($arrLocais[$propostaID][$arrCategoriaID[$catCont]][Valor],2,",",".");
				if ($arrLocais[$propostaID][$arrCategoriaID[$catCont]][DataEvento]!=""){
					$dataEvento = substr(converteData($arrLocais[$propostaID][$arrCategoriaID[$catCont]][DataEvento],1),0,10);
					if ($dataEvento == "00/00/0000") $dataEvento = "";
				}
				if ($arrLocais[$propostaID][$arrCategoriaID[$catCont]][Participantes]!="")
					$participantes = $arrLocais[$propostaID][$arrCategoriaID[$catCont]][Participantes];


				echo "			<div style='width:100%; font-weight: normal;'>
									<div style='width:65%; float:left;'>
										<p>Local Evento:</p>
										<p>
											<select name='local-evento[$propostaID][]' id='local-evento-$propostaID-".$arrCategoriaID[$catCont]."' class='local-evento calcular-total-categoria cp-orc-$propostaID' proposta='$propostaID' categoria='".$arrCategoriaID[$catCont]."'> ".optionValueProdutos($arrLocais[$propostaID][$arrCategoriaID[$catCont]][ProdutoVariacaoID], " and pd.Tipo_Produto = 140 ", $tabelaPrecoID, $arrCategoriaID[$catCont])."</select>
											<!--<input type='hidden' name='produto-categoria-evento[$propostaID][]' value='".$arrCategoriaID[$catCont]."' class='cp-orc-$propostaID'/>-->
											<input type='hidden' name='produto-categoria-id[$propostaID][]'  id='produto-categoria-id-$propostaID-".$arrCategoriaID[$catCont]."' value='".$arrLocais[$propostaID][$arrCategoriaID[$catCont]][ProdutoCategoriaID]."' class='cp-orc-$propostaID'/>
										</p>
									</div>
									<div style='width:05%; float:left;'>&nbsp;</div>
									<div style='width:10%; float:left;'>
										<p>Data Evento:</p>
										<p><input type='text' name='data-evento[$propostaID][]' id='data-evento-$propostaID-".$arrCategoriaID[$catCont]."' class='formata-data cp-orc-$propostaID' value='$dataEvento' style='width:90%;' proposta='$propostaID' categoria='".$arrCategoriaID[$catCont]."'/></p>
									</div>
									<div style='width:10%; float:left;'>
										<p>Participantes:</p>
										<p><input type='text' name='participantes[$propostaID][]' id='participantes-$propostaID-".$arrCategoriaID[$catCont]."' class='cp-orc-$propostaID' value='$participantes' style='width:90%;' proposta='$propostaID' categoria='".$arrCategoriaID[$catCont]."'/></p>
									</div>
									<div style='width:10%; float:left;'>
										<p>Valor Local:</p>
										<p><input type='text' name='valor-local[$propostaID][]' id='valor-local-$propostaID-".$arrCategoriaID[$catCont]."' value='$valorEvento'  class='cp-orc-$propostaID formata-valor calcular-total-categoria' style='width:90%;' proposta='$propostaID' categoria='".$arrCategoriaID[$catCont]."'/></p>
									</div>
								</div>";
			}

			echo "			</p>
						</div>
						<div class='conteudo-interno esconde blocos-categorias conteudo-interno-categoria-$propostaID-$catCont'>";
			geraTabela($largura,$colunas,$dados[$catCont], null, "tabela-produtos-$propostaID-$catCont", 1, 1, "","");
			echo "		</div>
					</div>";
		}
	}
}

function optionValueProdutos($selecionado, $condicoes, $tabelaPrecoID, $categoria){
	if ($tabelaPrecoID==""){
		$camposSelect = " pv.Valor_Venda as Valor_Venda";
	}
	else{
		$innerJoinTabelaPreco = " left join produtos_tabelas_precos_detalhes ptpd on ptpd.Produto_Variacao_ID = pv.Produto_Variacao_ID and ptpd.Tabela_Preco_ID = '$tabelaPrecoID' and ptpd.Situacao_ID = 1";
		$camposSelect = " case pv.Forma_Cobranca_ID when 35 then ptpd.Valor_Venda else pv.Valor_Venda end as Valor_Venda ";
	}

	$sel[$selecionado] = " selected ";
	$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
				pd.Produto_ID as Produto_ID, f.Descr_Tipo as Forma_Cobranca, pv.Forma_Cobranca_ID, pdc.Produto_Categoria_ID as Produto_Categoria_ID,
				$camposSelect
				FROM produtos_dados pd
				INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
				inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
				inner join produtos_dados_categorias pdc on pdc.Produto_ID = pd.Produto_ID and pdc.Situacao_ID = 1 and pdc.Categoria_ID = '$categoria'
				$innerJoinTabelaPreco
				WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
					 $condicoes
			ORDER BY Descricao_Produto";
	//echo $sql;

	$optionValueProdutos .= "<option value='' produto-id='' produto-categoria-id=''>Selecione</option>";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado))
		$optionValueProdutos .= "<option value='".$row[Produto_Variacao_ID]."' produto-id='".$row[Produto_ID]."' produto-categoria-id='".$row['Produto_Categoria_ID']."' forma-cobranca='".$row[Forma_Cobranca_ID]."' valor-produto='".number_format($row['Valor_Venda'],2,",",".")."' ".$sel[$row['Produto_Variacao_ID']].">".$row['Descricao_Produto']." - ".$row['Forma_Cobranca']." - ".number_format($row['Valor_Venda'],2,",",".")."</option>";
	return $optionValueProdutos;
}

function carregarLocalizarProduto($tipo, $chaveEstrangeira, $solicitanteID){
	global $caminhoSistema, $configChamados;
	$textoBotaoIncAlt 	= "Incluir";
	$quantidade 		= 1;
	$valorVendaTotal 	= "0,00";
	$valorCustoTotal 	= "0,00";
	$selecionadoCobrancaCliente = "checked";

	$selecionadoPagamentoPrestador = "";
	$exibirTerceiro = "esconde";

	if ($tipo=="orcamento"){
		$sql = "select opp.Proposta_ID as Chave_ID, opp.Produto_Variacao_ID, opp.Descricao, opp.Observacao_Produtos, opp.Quantidade, opp.Valor_Custo_Unitario, opp.Valor_Venda_Unitario,
						opp.Cobranca_Cliente, opp.Pagamento_Prestador, opp.Prestador_ID, opp.Situacao_ID, opp.Cliente_Final_ID as Cliente_Final_ID, opp.Faturamento_Direto
						from orcamentos_propostas_produtos opp
						where opp.Proposta_Produto_ID = '$chaveEstrangeira'";
		//echo $sql;
	}
	if ($tipo=="chamado"){
		$sql = "select Workflow_ID as Chave_ID, Produto_Variacao_ID, Descricao_Produto, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario,
						Cobranca_Cliente, Pagamento_Prestador, Prestador_ID, Situacao_ID, '' as Cliente_Final_ID, '' as Solicitante_ID, Faturamento_Direto, Observacao_Produtos
					from chamados_workflows_produtos where Workflow_Produto_ID = '$chaveEstrangeira'";
					//echo $sql;
	}
	//echo $sql;
	$resultado = mpress_query($sql);
	if ($row = mpress_fetch_array($resultado)){
		$chaveID 				= $row['Chave_ID'];
		$produtoVariacaoID 		= $row['Produto_Variacao_ID'];
		$clienteFinalID 		= $row['Cliente_Final_ID'];

		$quantidade 			= number_format($row[Quantidade],0,"","");
		$valorVendaTotal 		= number_format(($row['Quantidade'] * $row['Valor_Venda_Unitario']),2,",",".");
		$valorCustoTotal 		= number_format(($row['Quantidade'] * $row['Valor_Custo_Unitario']),2,".",".");
		$textoBotaoIncAlt 		= "Alterar";
		$observacaoOpp 			= utf8_encode($row['Observacao_Produtos']);

		if ($row['Cobranca_Cliente']==1){
			$selecionadoCobrancaCliente = "checked";
		}

		if ($row['Pagamento_Prestador']==1){
			$prestadorID = $row['Prestador_ID'];
			$selecionadoPagamentoPrestador = "checked";
			$exibirTerceiro = "";
		}
		if ($row['Faturamento_Direto']==1){
			$selecionadoFaturamentoDireto = "checked";
		}
	}


	$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
				pv.Forma_Cobranca_ID, f.Descr_Tipo as Forma_Cobranca, case pv.Forma_Cobranca_ID when 35 then pv.Valor_Venda else '' end as Valor_Venda, pd.Produto_ID as Produto_ID,
				pd.Faturamento_Direto
				FROM produtos_dados pd
				INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
				INNER JOIN tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
				WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0 and pd.Tipo_Produto <> 175
			ORDER BY Descricao_Produto";
	$selectProdutos = "<select id='select-produtos' name='select-produtos' Style='width:98.5%' data-placeholder='Selecione'>
							<option value='' produto-id=''>Selecione</option>";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($row['Valor_Venda']!="") $valorVenda = ":&nbsp;".number_format($row['Valor_Venda'], 2, ',', '.'); else $valorVenda = "";
		if ($row['Produto_Variacao_ID']==$produtoVariacaoID) $selecionado = "selected"; else $selecionado = "";
		$selectProdutos .= "<option value='".$row['Produto_Variacao_ID']."' produto-id='".$row['Produto_ID']."' faturamento-direto='".$row['Faturamento_Direto']."' $selecionado>".utf8_encode($row['Descricao_Produto'])."&nbsp;&nbsp;-&nbsp;&nbsp;".utf8_encode($row['Forma_Cobranca'])." $valorVenda</option>";
	}
	$selectProdutos .= "			</select>";

	echo "	<input type='hidden' name='chave-primaria-id' id='chave-primaria-id' value='$chaveEstrangeira'/>
			<fieldset style='margin-bottom:2px;'>";

	if ($configChamados['agrupar-produtos']=="agrupar-por-cliente"){
		echo "	<div style='width:100%;float:left;'>
					<div style='width:100%;float:left; margin-top:3px;'>
						<p>Cliente Final</p>
						<p>
							<select id='select-cliente-final' name='select-cliente-final' Style='width:98.5%' data-placeholder='Selecione'>
								<option value=''></option>";
								echo utf8_encode(optionValueColaboradores($solicitanteID, $clienteFinalID));
		echo "				</select>
						</p>
					</div>
					&nbsp;
				</div>";
	}

	echo "		<div id='div-produtos-select' style='float:left; width:50%;'>
					<p>Selecione o Produto / Servi&ccedil;o</p>
					<p>$selectProdutos</p>
				</div>
				<div style='width:3%;float:left; margin-top: 15px;'>
					<div style='float:right;margin-top:2px;margin-right:5px;' class='btn-mais btn-incluir-novo-produto' title='Incluir novo produto' >&nbsp;</div>
				</div>
				<div style='width:15%;float:left;'>
					<p>Quantidade</p>
					<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='$quantidade' class='formata-numero' style='width:90%;text-align:center;' maxlength='10'/></p>
				</div>
				<div style='width:25%;float:right;'>
					<div id='div-produtos-incluir'  style='width:50%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' id='botao-salvar-produto' name='botao-salvar-produto' class='botao-salvar-produto' Style='width:95%' value='$textoBotaoIncAlt'/></p>
					</div>
					<div id='div-produtos-cancelar' style='width:50%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' value='Cancelar' id='botao-cancelar-produto' class='botao-cancelar-produto' Style='width:95%'/></p>
					</div>
				</div>
				<div id='div-detalhes-produto' style='width:100%; float:left; margin-bottom:10px;'>";
	if (($chaveID!="") && ($produtoVariacaoID!="")){
		carregarProdutoDetalhes($chaveID, $produtoVariacaoID, $tipo, $chaveEstrangeira);
	}
	echo "			&nbsp;
				</div>
				<div style='width:100%; float:left;'>
					<div style='width:20%;float:left;'>
						<p id='cobranca-cliente'><input type='checkbox' value='1' id='checkbox-cobranca-cliente' name='checkbox-cobranca-cliente' $selecionadoCobrancaCliente/><label for='checkbox-cobranca-cliente'>Cobran&ccedil;a Cliente</label></p>
						<p id='pagamento-prestador'><input type='checkbox' value='1' id='checkbox-pagamento-prestador' name='checkbox-pagamento-prestador' $selecionadoPagamentoPrestador/><label for='checkbox-pagamento-prestador'>Fornecedor</label></p>
						<p id='faturamento-direto'><input type='checkbox' value='1' id='checkbox-faturamento-direto' name='checkbox-faturamento-direto' $selecionadoFaturamentoDireto/><label for='checkbox-faturamento-direto'>Faturamento Direto</label></p>
					</div>
					<div style='width:30%;float:left;'>
						<div class='exibir-terceiro $exibirTerceiro' style='width:100%;float:left;'>
							<p>Fornecedor
								<input type='button' class='link incluir-prestador-produto' style='font-weight: bold; border-radius: 10px; height: 15px; width: 15px; margin-top: 0px; padding: 0px;' valign='bottom' value='+'>
							</p>
							<p>
								<select id='select-prestador' name='select-prestador' Style='width:98.5%' data-placeholder='Selecione'>";
									echo utf8_encode(optionValueFornecedoresProduto($produtoVariacaoID, $prestadorID));
	echo "						</select>
							</p>
						</div>
						&nbsp;
					</div>
					<div style='width:25%;float:left;'>
						<!--<div class='exibir-terceiro esconde' style='width:100%;float:left;'>-->
						<div class='exibir-terceiro $exibirTerceiro' style='width:100%;float:left;'>
							<p>Total Custo</p>
							<p><input type='text' id='total-custo-produtos' name='total-custo-produtos' style='width:95%'  value='$valorCustoTotal' readonly/></p>
						</div>
						&nbsp;
					</div>
					<div style='width:25%;float:left;margin-top:3px'>
						<p>Total Produto / Servi&ccedil;o</p>
						<p><input type='text' id='total-venda-produtos' name='total-venda-produtos' style='width:95%' value='$valorVendaTotal' readonly/></p>
					</div>
				</div>

				<div style='width:100%; float:left;'>
					<div style='width:20%;float:left;margin-top:3px'>
					</div>
					<div style='width:80%;float:left;margin-top:3px'>
						<p>Observa&ccedil;&atilde;o</p>
						<p><textarea rows='4' cols='50' id='observacao_produto' name='observacao_produto' style='width:95%'>$observacaoOpp</textarea>
						</p>
					</div>
				</div>
			</fieldset>
			<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
}

function optionValueColaboradores($solicitanteID, $selecionado){
	$sql = "SELECT cd.Cadastro_ID AS Cadastro_ID, cd.Nome
				FROM cadastros_dados cd
				INNER JOIN cadastros_vinculos cv on cv.Cadastro_Filho_ID = cd.Cadastro_ID
				WHERE cd.Situacao_ID = 1 AND cv.Situacao_ID = 1 and cv.Cadastro_ID = '$solicitanteID'
				ORDER BY Nome";
	$sel[$selecionado] = "selected";
	$resultSet = mpress_query($sql);
	while($rs = mpress_fetch_array($resultSet)){
		$h .= "<option value='".$rs['Cadastro_ID']."' ".$sel[$rs['Cadastro_ID']].">".$rs['Nome']."</option>";
	}
	return $h;
}


function optionValueFornecedoresProduto($produtoVariacaoID, $selecionado){
	$sql = "select distinct pf.Cadastro_ID, cd.Nome
							from produtos_fornecedores pf
							inner join produtos_variacoes pv on pv.Produto_ID = pf.Produto_ID
							inner join cadastros_dados cd on cd.Cadastro_ID = pf.Cadastro_ID
								where pf.Situacao_ID = 1
								and pv.Produto_Variacao_ID = '$produtoVariacaoID'";
	$resultset = mpress_query($sql);
	$optionValueFornecedoresProduto .= "<option value=''>Selecione</option>";
	while($rs = mpress_fetch_array($resultset)){
		if ($selecionado==$rs['Cadastro_ID']){$seleciona='selected';}else{$seleciona='';}
		$optionValueFornecedoresProduto .= "<option value='".$rs['Cadastro_ID']."' $seleciona>".($rs['Nome'])."</option>";
	}
	return $optionValueFornecedoresProduto;
}

function salvarProdutoOrcamento(){
	global $dadosUserLogin;
	$dataHoraAtual 			= retornaDataHora('','Y-m-d H:i:s');

	$propostaProdutoID 		= $_POST['chave-primaria-id'];
	$propostaID 			= $_POST['proposta-id'];
	$workflowID 			= $_POST['workflow-id'];

	$produtoVariacaoID 		= $_POST['select-produtos'];
	$prestadorID 			= $_POST['select-prestador'];
	$clienteFinalID 		= $_POST['select-cliente-final'];

	$produtoObservacao 		= utf8_decode($_POST['observacao_produto']);

	if ($prestadorID == '') $prestadorID = "0";
	if ($clienteFinalID == '') $clienteFinalID = "0";


	$quantidade 			= $_POST['quantidade-produtos'];
	$valorCustoUnitario 	= str_replace(",",".",str_replace(".","",$_POST['valor-custo-unitario']));
	$valorVendaUnitario 	= str_replace(",",".",str_replace(".","",$_POST['valor-venda-unitario']));
	$descricao 				= utf8_decode($_POST['descricao-produto-variacao']);

	$pagamento 				= $_POST['checkbox-pagamento-prestador'];
	$cobranca 				= $_POST['checkbox-cobranca-cliente'];
	$faturamentoDireto 		= $_POST['checkbox-faturamento-direto'];


	if ($pagamento ==""){ $valorCustoUnitario=0; $pagamento="0";}
	if ($cobranca ==""){ $valorVendaUnitario=0; $cobranca="0";}
	if ($faturamentoDireto == ""){ $faturamentoDireto = "0";}

	if ($propostaProdutoID==""){
		$sql = "insert into orcamentos_propostas_produtos (Proposta_ID, Produto_Variacao_ID, Descricao, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario, Cobranca_Cliente, Pagamento_Prestador, Faturamento_Direto, Prestador_ID, Cliente_Final_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID, Observacao_Produtos)
												values ('$propostaID', '$produtoVariacaoID', '$descricao', '$quantidade', '$valorCustoUnitario', '$valorVendaUnitario', '$cobranca', '$pagamento', '$faturamentoDireto', '$prestadorID', '$clienteFinalID', 1, '$dataHoraAtual', '".$dadosUserLogin['userID']."', '$produtoObservacao')";
	}else{
		$sql = "update orcamentos_propostas_produtos
					set Produto_Variacao_ID = '$produtoVariacaoID',
						Descricao = '$descricao',
						Quantidade = '$quantidade',
						Valor_Custo_Unitario = '$valorCustoUnitario',
						Valor_Venda_Unitario = '$valorVendaUnitario',
						Cobranca_Cliente = '$cobranca',
						Pagamento_Prestador = '$pagamento',
						Faturamento_Direto = '$faturamentoDireto',
						Prestador_ID = '$prestadorID',
						Cliente_Final_ID = '$clienteFinalID',
						Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."',
						Observacao_Produtos = '$produtoObservacao'
						where Proposta_Produto_ID = '$propostaProdutoID'";
	}
	$resultado = mpress_query($sql);

	echo $resultado;
	echo $propostaID;
}

function excluirProdutoOrcamento($propostaProdutoID){
	global $dadosUserLogin;
	$sql = "update orcamentos_propostas_produtos set Situacao_ID = 2,
											Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
										where Proposta_Produto_ID = '$propostaProdutoID'";
	mpress_query($sql);
}


function carregarEditarNomeProposta($propostaID,$nomeAtual,$valor){
	echo " &nbsp;&nbsp;	<input type='text' id='editar-titulo' name='editar-titulo' value='$nomeAtual' style='width:150px;' class='editar-titulo'/>
						<input type='button' class='atualizar-proposta' value='Atualizar' style='width:60px;'/>
						<input type='button' class='cancelar-atualizar-proposta' value='Cancelar' style='width:60px;'/>";
	if ($valor==0){
		echo "	<input type='button' class='botao-excluir-proposta' style='font-weight: bold; border-radius: 10px; height: 20px; width: 20px; margin-top: 6px; display: inline-block;' valign='bottom' value='X'>";
	}
	echo "	&nbsp;&nbsp;";
}

function carregarPropostasOrcamentos($workflowID, $propostaID){
	global $modulosAtivos, $caminhoSistema, $configChamados;
	if ($workflowID!=""){
		$sql = "SELECT op.Proposta_ID, op.Workflow_ID, op.Titulo, op.Data_Cadastro, op.Usuario_Cadastro_ID, u.Nome AS Usuario,
					SUM(opp.Quantidade) as Quantidade_Total_Proposta, SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor_Total_Proposta,
					count(opp.Proposta_Produto_ID) as Total_Itens_Proposta, op.Status_ID as Status_ID, upper(t.Descr_Tipo) as Status,
					ow.Situacao_ID as Situacao_ID, coalesce(tp.Titulo_Tabela,'Tabela Padrão') as Tabela_Preco
				FROM orcamentos_propostas op
					inner join orcamentos_workflows ow on ow.Workflow_ID = op.Workflow_ID
					left join produtos_tabelas_precos tp on tp.Tabela_Preco_ID = op.Tabela_Preco_ID
					left join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
					left join cadastros_dados u ON u.Cadastro_ID = op.Usuario_Cadastro_ID
					left join tipo t on t.Tipo_ID = op.Status_ID
					where op.Workflow_ID = '$workflowID' and op.Situacao_ID = 1
				GROUP by op.Proposta_ID, op.Workflow_ID, op.Titulo, op.Data_Cadastro, op.Usuario_Cadastro_ID, u.Nome";
		//echo $sql;
		$resultado 	= mpress_query($sql);

		while($rs 	= mpress_fetch_array($resultado)){
			$i++;

			$propostas[] 								= $rs[Proposta_ID];
			$dados['usuario'][$rs[Proposta_ID]] 		= $rs[Usuario];
			$dados['data'][$rs[Proposta_ID]] 			= $rs[Data_Cadastro];
			//$dados['totalItens'][$rs[Proposta_ID]] = $rs[Total_Itens_Proposta];
			//$dados['totalQuantidade'][$rs[Proposta_ID]] = $rs[Quantidade_Total_Proposta];
			$dados['totalValor'][$rs[Proposta_ID]] 		= $rs[Valor_Total_Proposta];
			$dados['statusID'][$rs[Proposta_ID]] 		= $rs[Status_ID];
			$dados['status'][$rs[Proposta_ID]] 			= $rs[Status];
			$dados['tabelaPreco'][$rs[Proposta_ID]] 	= $rs[Tabela_Preco];
			$situacaoGeralOrcamento 					= $rs[Situacao_ID];
			$classeAba 									= "aba-normal";


			$fundo = "";
			$editavel = 'true';
			if (($rs['Status_ID']=='119') || ($rs['Status_ID']=='122')) {
				$fundo = "<span class='mini-bola-vermelha'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
				$editavel = 'false';
			}
			if ($rs['Status_ID']=='141'){
				$fundo = "<span class='mini-bola-verde'>&nbsp;&nbsp;&nbsp;&nbsp;</span>";
				$editavel = 'false';
			}
			$escondeIncluir = "";
			if ($propostaID==""){
				if (($rs['Status_ID']!='119')&&($rs['Status_ID']!='122')&&($propostaAuxID=="")){
					$classeAba = "aba-selecionada";
					$propostaAuxID = $rs['Proposta_ID'];
				}
			}
			else{
				if ($propostaID==$rs['Proposta_ID']){
					$classeAba = "aba-selecionada";
				}
			}
			$abasHtml .= "	<div class='abas-geral ".$classeAba."' editavel='".$editavel."' id='aba-".$rs['Proposta_ID']."' proposta-id='".$rs['Proposta_ID']."'>
								<span id='titulo-".$rs['Proposta_ID']."' proposta-id='".$rs['Proposta_ID']."' class='titulo-fixo' tit='".$rs['Titulo']."'>".$rs['Titulo']."</span>
								".$fundo."
							</div>";
		}
		if ($propostaID=="") $propostaID = $propostaAuxID;

		if ($responsaveisAprovacao=="") $responsaveisAprovacao = "<span class='lixeira' align='center'>Atualmente o sistema não possui usuários com permissão para aprovação</span>";

		$tabelasPrecos = optionValueTabelaPreco("","");
		echo "	<div class='titulo-abas'>
					<div style='float:left; width:100%' class='esconde' id='incluir-proposta'>
						<table style='width:600px; margin-top:3px;' align='center'>
							<tr class='opcoes-proposta'>
								<td colspan='3' align='center'>
									<p>Selecione: </p>
									<p>
										<input type='button' class='botao-nova-proposta' value='Nova Proposta' style='width:120px;'/>
										<input type='button' class='botao-copiar-proposta' value='Copiar Proposta' style='width:120px;'/>
										<input type='button' class='cancelar-proposta' value='Cancelar' style='width:120px;'/>
									</p>
								</td>
							</tr>
							<tr class='esconde incluir-nova-proposta'>
								<td>
									T&iacute;tulo:<br>
									<input type='text' id='titulo-proposta' name='titulo-proposta' style='width:90%;'/>
								</td>";
								if ($tabelasPrecos!=''){
									echo "				<td width='33%'>
															Tabela R$:<br>
															<select id='tabela-preco-proposta' name='tabela-preco-proposta'>$tabelasPrecos</select>
														</td>";
								}
		echo "					<td width='33%' valign='bottom'>
									<input type='button' class='salvar-proposta' value='Incluir' style='width:60px;'/>
									<input type='button' class='cancelar-proposta' value='Cancelar' style='width:60px;'/>
								</td>
							</tr>
						</table>
					</div>";
		echo $abasHtml;

		// Caso cancelamento de proposta na aprovação de uma delas ativa
		if ($configChamados['cancelar-propostas']=='1'){
			if (!(in_array(141, $dados['statusID']))){
				echo " <input type='button' class='botao-exibir-proposta' style='font-weight: bold; border-radius: 10px; height:20px; width:20px; margin-top:6px' valign='bottom' value='+'/>";
				if (($configChamados['listagem-orcamento']=="procura")&&($i>0)){
					echo " <input type='button' class='botao-exibir-produto' value='Incluir Produto' style='width:120px; float:right; margin-top:1px;'/>";
				}
			}
		}
		else{
			echo " <input type='button' class='botao-exibir-proposta' style='font-weight: bold; border-radius: 10px; height:20px; width:20px; margin-top:6px' valign='bottom' value='+'/>";
			if (($configChamados['listagem-orcamento']=="procura")&&($i>0)){
				echo " <input type='button' class='botao-exibir-produto' value='Incluir Produto' style='width:120px; float:right; margin-top:1px;'/>";
			}
		}
		echo "	</div>
				<input type='hidden' id='proposta-id' name='proposta-id' value='$propostaID'/>
				<input type='hidden' name='situacao-auxiliar' id='situacao-auxiliar' value=''/>
				<div id='div-produtos-incluir-editar' Style='float:left; width:100%; margin-top:5px;' class='titulo-secundario'></div>
				<div class='conteudo-interno titulo-secundario'>
					<div id='div-aux-aguarde'></div>";
		foreach ($propostas as $proposta) {
			if ($propostaID==$proposta) $esconde = ""; else $esconde = "esconde";
			if (($modulosAtivos['turmas']) && ($configChamados['listagem-orcamento']=="completa")){
				$blocoProjecao = "	<a title='Proje&ccedil;&atilde;o de valores' class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/turmas/projecao.php?orcamento=$proposta'>
									<div style='float:right;' class='btn-grafico'>&nbsp;</div>
								</a>";
			}
			echo "	<div id='div-propostas-$proposta' class='titulo-secundario uma-coluna $esconde blocos-propostas' style='margin-top:5px;'>
						<div style='float:left; width:20%'>
							<p><b>Criado por:</b></p>
							<p style='margin:5px;'>".$dados['usuario'][$proposta]."</p>
						</div>
						<div style='float:left; width:15%'>
							<p><b>Tabela de pre&ccedil;o:</b></p>
							<p style='margin:5px;'>".$dados['tabelaPreco'][$proposta]."</p>
						</div>
						<div style='float:left; width:20%'>
							&nbsp;
							<b class='esconde'>Total Produtos Selecionados:
								<span id='total-selecionados-$proposta'>".number_format($dados['totalItens'][$proposta], 0, '', '.')."</span>
							</b>
						</div>
						<div style='float:left; width:20%'>
							&nbsp;
							<b class='esconde'>
								Quantidade Total de Itens:
								<span id='total-quantidade-$proposta'>".number_format($dados['totalQuantidade'][$proposta], 0, '', '.')."</span>
							</b>
						</div>";
			if ($configChamados['listagem-orcamento']=="completa"){
				echo "	<div style='float:left; width:20%'>
							<b>Valor Total Proposta:
								$blocoProjecao
								<span id='total-proposta-$proposta'>".number_format($dados['totalValor'][$proposta], 2, ',', '.')."</span>
							</b>
						</div>
						<div style='float:left; width:5%'>&nbsp;</div>";
			}
			echo "		<div id='bloco-proposta-$proposta' style='float:left; width:100%; margin-top:5px;margin-bottom:15px;' class='titulo-secundario'>";
			if ($configChamados['listagem-orcamento']=="completa"){
				if ($propostaID==$proposta){
					carregarProdutosOrcamentoCompleta($proposta);
				}
				carregarSituacaoProposta($proposta);
			}
			if ($configChamados['listagem-orcamento']=="procura"){
				carregarProdutos($proposta,'orcamento'); // Carregar produtos e formas de pagamentos
				carregarSituacaoProposta($proposta);
			}
			echo "			<div id='bloco-follows-proposta-$proposta' style='float:left; width:100%; margin-top:10px;'>";
								carregarFollowsOrcamentosPropostas($proposta);
			echo "			</div>";
			echo "		</div>";
			echo "	</div>";
		}
		echo "	</div>";
	}
}


/****************************** PAREI AQUI **************************/

function carregarSituacaoProposta($propostaID){
	global $configChamados, $modulosAtivos;
	$resultado = mpress_query("select t.Descr_Tipo as StatusProposta, Status_ID from orcamentos_propostas op
								inner join tipo t on t.Tipo_ID = op.Status_ID
								where op.Proposta_ID = '$propostaID'");
	if($rs = mpress_fetch_array($resultado)){
		$statusID = $rs['Status_ID'];
		$descricaoStatus = $rs['StatusProposta'];

		echo "		<div style='float:left; width:20%;'>
						<p align='left'>SITUA&Ccedil;&Atilde;O PROPOSTA: </p><p><b class='destaque'>$descricaoStatus</b></p>
						<input type='hidden' name='situacao-atual-proposta[$propostaID]' value='$statusID'/>
					</div>";
		echo "		<div style='float:left; width:80%'>";

		if ($configChamados['listagem-orcamento']=="completa"){
			$botaoAtualizarProposta = "<input type='button' class='botao-salvar-proposta-completo' tipo='114' style='float:right; max-width:150px; margin-left:5px;' value='Atualizar proposta'/>";
		}
		if ($configChamados['fluxo-aprovacao']=="fluxo-aprovacao-simples"){
			if ($statusID!="141"){
				echo "		<input type='button' class='botao-salvar-proposta-completo' tipo='141' style='float:right; max-width:200px; margin-left:5px;' value='Proposta Aprovada (Finalizada)'/>";
				echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='122' style='float:right; max-width:200px; margin-left:5px;' value='Recusada pelo cliente'/>";
				if ($configChamados['exibe-envia-proposta-email']){
					echo "		<input type='button' class='botao-salvar-proposta-completo' tipo='120' style='float:right; max-width:200px; margin-left:5px;' value='Enviar para aprovação cliente'/>";
				}
			}
		}

		if ($configChamados['fluxo-aprovacao']=="fluxo-aprovacao-pos"){
			if (($statusID==114) || ($statusID==120)){
				echo $botaoAtualizarProposta;
				echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='121' style='float:right; max-width:200px; margin-left:5px;' value='Aprovado pelo cliente'/>";
				echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='122' style='float:right; max-width:200px; margin-left:5px;' value='Recusada pelo cliente'/>";
				if ($configChamados['exibe-envia-proposta-email']){
					echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='120' style='float:right; max-width:200px; margin-left:5px;' value='Enviar para aprovação cliente'/>";
				}
			}
			if ($statusID==121){
				echo "		<input type='button' class='botao-salvar-proposta-completo' tipo='117' style='float:right; max-width:200px; margin-left:5px;' value='Enviar para aprovação interna'/>";
				if ($modulosAtivos['chamados-orcamentos-aprovar-recusar']=="1"){
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='118' style='float:right; max-width:200px; margin-left:5px;' value='Aprovar proposta'/>";
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='119' style='float:right; max-width:200px; margin-left:5px;' value='Recusar proposta'/>";
				}
			}
			if ($statusID==117){
				if ($modulosAtivos['chamados-orcamentos-aprovar-recusar']=="1"){
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='118' style='float:right; max-width:200px; margin-left:5px;' value='Aprovar proposta'/>";
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='119' style='float:right; max-width:200px; margin-left:5px;' value='Recusar proposta'/>";
				}
				else{
					echo "	<p align='center' style='margin-top:5px' class='lixeira'>Aguardando usuário com permissão para aprovar orçamento</p>";
				}
			}
			if ($statusID==118){
				echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='141' style='float:right; max-width:200px; margin-left:5px;' value='Proposta Aprovada (Finalizada)'/>";
			}
			echo  "		<input type='button' class='botao-cancelar-acoes observacao-proposta-$propostaID esconde' style='float:right; max-width:200px; margin-left:5px;' value='Cancelar'/>";
		}

		if ($configChamados['fluxo-aprovacao']=="fluxo-aprovacao-pre"){
			if (($statusID==114) || ($statusID==115) || ($statusID==116)){
				if ($statusID==115) $descricaoEnvio = "Re-enviar"; else $descricaoEnvio = "Enviar";
				echo $botaoAtualizarProposta;
				echo "		<input type='button' class='botao-salvar-proposta-completo' tipo='117' style='float:right; max-width:200px; margin-left:5px;' value='Enviar para aprovação interna'/>";
			}
			if ($statusID==117){
				if ($modulosAtivos['chamados-orcamentos-aprovar-recusar']=="1"){
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='118' style='float:right; max-width:200px; margin-left:5px;' value='Aprovar proposta'/>";
					echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='119' style='float:right; max-width:200px; margin-left:5px;' value='Recusar proposta'/>";
				}
				else{
					echo "	<p align='center' style='margin-top:5px' class='lixeira'>Aguardando usuário com permissão para aprovar orçamento</p>";
				}
			}
			if (($statusID==118)||($statusID==120)){
				echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='121' style='float:right; max-width:200px; margin-left:5px;' value='Aprovado pelo cliente'/>";
				echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='122' style='float:right; max-width:200px; margin-left:5px;' value='Recusada pelo cliente'/>";
				if ($configChamados['exibe-envia-proposta-email']){
					echo  "		<input type='button' class='botao-salvar-proposta-completo' tipo='120' style='float:right; max-width:200px; margin-left:5px;' value='Enviar para aprovação cliente'/>";
				}
			}
			if ($statusID==121){
				echo  "	<input type='button' class='botao-salvar-proposta-completo' tipo='141' style='float:right; max-width:200px; margin-left:5px;' value='Proposta Aprovada (Finalizada)'/>";
			}
			echo  "			<input type='button' class='botao-cancelar-acoes observacao-proposta-$propostaID esconde' style='float:right; max-width:200px; margin-left:5px;' value='Cancelar'/>";
		}
		echo "		</div>";
		echo "		<div style='float:right; width:70%' class='observacao-proposta-$propostaID esconde'>
						<p id='texto-observacao-$propostaID'></p>
						<p><textarea style='width:98%;height:50px' name='observacao-proposta-$propostaID' id='observacao-proposta-$propostaID' class='observacao-proposta-$propostaID'></textarea></p>
					</div>";
	}

	/*
	AQUI!!!! Enviar Email !!!!!!!!!!
	$emailsEnviar = $configChamados['emails-copia-orcamento'];
	$sql = "select email from cadastros_dados where Cadastro_ID IN ('".$dadosUserLogin['userID']."', '$representanteID')";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$emailsEnviar .= $row['email'].";";
	}
	enviarEmailTarefa($workflowID, $situacaoID, $descricao, $emailsEnviar);
	*/
}

function orcamentoProdutosPropostaSalvar(){
	global $dadosUserLogin, $configChamados;
	$propostaID = $_POST['proposta-id'];
	$workflowID = $_POST['workflow-id'];
	$acao = $_POST['situacao-auxiliar'];

	$resultado = mpress_query("select Status_ID from orcamentos_propostas where Proposta_ID = '$propostaID'");
	if ($rs = mpress_fetch_array($resultado)){
		$situacaoAtualID = $rs['Status_ID'];
	}
	$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');

	//Salvar proposta ou enviar para recebimento
	if (($acao=="114")||($acao=="115")||($acao=="back114")||($acao=="117")||($acao=="118")||($acao=="119")||($acao=="120")||($acao=="121")||($acao=="122")||($acao=="141")){

		//echo "<br>PROPOSTA:".$propostaID;
		//echo "<br>TOTAL:".count($_POST['q'][$propostaID]);
		/*
		echo "<pre>";
		print_r($_POST['q'][$propostaID]);
		echo "</pre>";
		*/

		if ($acao=="114"){
			mpress_query("update orcamentos_propostas_produtos set Situacao_ID = 2 where Proposta_ID = '$propostaID'");
			mpress_query("update orcamentos_propostas_eventos set Situacao_ID = 2 where Proposta_ID = '$propostaID'");
			foreach($_POST['q'][$propostaID] as $produtoCategoriaID=>$item){
				foreach($_POST['q'][$propostaID][$produtoCategoriaID] as $produtoVariacaoID=>$quantidade){
					$qtd = $quantidade[0];
					//echo "<br> $produtoVariacaoID - $produtoCategoriaID - ".$quantidade[0];
					$vendaUnit = formataValorBD($_POST['v'][$propostaID][$produtoCategoriaID][$produtoVariacaoID]);
					$statusProduto = 123;
					if ($qtd>0){
						mpress_query("INSERT INTO orcamentos_propostas_produtos
									(Proposta_ID, 
									Produto_Variacao_ID, 
									Produto_Categoria_ID, 
									Quantidade, 
									Valor_Venda_Unitario,
									Cobranca_Cliente, 
									Pagamento_Prestador, 
									Prestador_ID, 
									Situacao_ID, 
									Status_ID, 
									Data_Cadastro, 
									Usuario_Cadastro_ID)
								VALUES ('$propostaID', 
								'$produtoVariacaoID', 
								'$produtoCategoriaID', 
								'$qtd', 
								'$vendaUnit',
								1, 				
								0, 			
								0, 			
								1, 
								'$statusProduto', 
								'".$dataHoraAtual."', 
								'".$dadosUserLogin['userID']."')"
							);
					}
				}
			}
			for($i = 0; $i < count($_POST['local-evento'][$propostaID]); $i++){
				$qtd = "1";
				$produtoVariacaoID = $_POST['local-evento'][$propostaID][$i];
				//$produtoCategoriaID = $_POST['produto-categoria-evento'][$propostaID][$i];
				$produtoCategoriaID = $_POST['produto-categoria-id'][$propostaID][$i];
				$vendaUnit = formataValorBD($_POST['valor-local'][$propostaID][$i]);
				$dataEvento = "'".converteDataHora($_POST['data-evento'][$propostaID][$i])."'";
				$participantes = $_POST['participantes'][$propostaID][$i];
				$statusProduto = "123";
				if (($produtoVariacaoID!="") || ($vendaUnit>0) || ($dataEvento!="") || ($participantes!="")){
					$sqlLocal = "INSERT INTO orcamentos_propostas_produtos
								(Proposta_ID, 
								Produto_Variacao_ID, 
								Produto_Categoria_ID, 
								Quantidade, 
								Valor_Venda_Unitario,
								Cobranca_Cliente, 
								Pagamento_Prestador,
								Prestador_ID, 
								Situacao_ID, 
								Status_ID, 
								Data_Cadastro, 
								Usuario_Cadastro_ID)
							VALUES ('$propostaID', 
							'$produtoVariacaoID', 
							'$produtoCategoriaID', 
							'$qtd', 
							'$vendaUnit',
							1, 				
							0, 			
							0, 			
							1, 
							'$statusProduto', 
							'".$dataHoraAtual."', 
							'".$dadosUserLogin['userID']."')"
							;
					//echo $sqlLocal;
					mpress_query($sqlLocal);
					$propostaProdutoID = mpress_identity();
					$sqlLocal = "INSERT INTO orcamentos_propostas_eventos (Proposta_Produto_ID, Proposta_ID, Participantes, Data_Evento, Situacao_ID, Data_Cadastro)
															VALUES ('$propostaProdutoID', '$propostaID', '$participantes', $dataEvento, 1,'".$dataHoraAtual."')";
					mpress_query($sqlLocal);
				}
			}
		}
		$descricaoAux = "Proposta atualizada";
		if ($acao=="115"){
			$descricaoAux = "Proposta enviada para pr&eacute;-sele&ccedil;&atilde;o de produtos e servi&ccedil;os, aguardando intera&ccedil;&atilde;o do cliente";
			$situacaoAtualID = "115";
			mpress_query("update orcamentos_propostas set Status_ID = '115' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="back114"){
			$descricaoAux = "Proposta atualizada para em aberto";
			$situacaoAtualID = "114";
			mpress_query("update orcamentos_propostas set Status_ID = '114' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="117"){
			$descricaoAux = "Proposta enviada para aprovação interna";
			$situacaoAtualID = "117";
			mpress_query("update orcamentos_propostas set Status_ID = '117' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="118"){
			$descricaoAux = "Proposta aprovada, encaminhar para o cliente, observa&ccedil;&otilde;es: <b>".utf8_decode($_POST["observacao-proposta-".$propostaID])."</b>";
			$situacaoAtualID = "118";
			mpress_query("update orcamentos_propostas set Status_ID = '118' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="119"){
			$descricaoAux = "Proposta recusada, motivo: <b>".utf8_decode($_POST["observacao-proposta-".$propostaID])."</b>";
			$situacaoAtualID = "119";
			mpress_query("update orcamentos_propostas set Status_ID = '119' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="120"){
			$descricaoAux = "Proposta enviada para o cliente, aguardando aprovação";
			$situacaoAtualID = "120";
			mpress_query("update orcamentos_propostas set Status_ID = '120' where Workflow_ID = '$workflowID' and Status_ID = 114 and Situacao_ID = 1");
			mpress_query("update orcamentos_workflows set Situacao_ID = '120' where Workflow_ID = '$workflowID'");

			/* AQUI MANDAR EMAIL PARA O CLIENTE PEDINDO APROVAÇÂO*/
			enviarEmailAprovacaoOrcamentos($workflowID);
		}
		if ($acao=="121"){
			$descricaoAux = "Proposta aprovada pelo cliente, observa&ccedil;&otilde;es: <b>".utf8_decode($_POST["observacao-proposta-".$propostaID])."</b>";
			$situacaoAtualID = "121";
			mpress_query("update orcamentos_propostas set Status_ID = '121' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="122"){
			$descricaoAux = "Proposta recusada pelo cliente, motivo: <b>".utf8_decode($_POST["observacao-proposta-".$propostaID])."</b>";
			$situacaoAtualID = "122";
			mpress_query("update orcamentos_propostas set Status_ID = '122' where Proposta_ID = '$propostaID'");
		}
		if ($acao=="141"){
			$descricaoAux = "Proposta Aprovada e Finalizada";
			$situacaoAtualID = "141";

			// CASO FINALIZADO - ATUALIZANDO AS OUTRAS PROPOSTAS PARA RECUSADAS SE CONFIGURADO PARA ATUALIZAR
			if ($configChamados['cancelar-propostas']=='1'){
				mpress_query("update orcamentos_propostas set Status_ID = '122' where Workflow_ID = '$workflowID' and Proposta_ID <> '$propostaID'");
				mpress_query("insert into orcamentos_propostas_follows (Proposta_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
							select Proposta_ID, concat('Proposta recusada, fechado com a opção: <b>',Titulo,'</b>'),'122', '$dataHoraAtual', '".$dadosUserLogin['userID']."' from orcamentos_propostas where Workflow_ID = '$workflowID' and Proposta_ID <> '$propostaID'");
			}
			mpress_query("update orcamentos_propostas set Status_ID = '141' where Proposta_ID = '$propostaID'");
		}
		mpress_query("insert into orcamentos_propostas_follows (Proposta_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$propostaID', '$descricaoAux', '$situacaoAtualID', '$dataHoraAtual', '".$dadosUserLogin['userID']."')");

		/******/

		if ($acao!="114"){
			$emailsEnviar = $configChamados['emails-copia-orcamento'];
			if ($acao==141){
				$emailsEnviar .= $configChamados['emails-copia-orcamento-finalizado'];
			}
			$sql = "select email from cadastros_dados where Cadastro_ID IN ('".$dadosUserLogin['userID']."')";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$emailsEnviar .= $row['email'].";";
			}
			enviarEmailOrcamentoProposta($workflowID, $propostaID, $acao, $descricaoAux, $emailsEnviar);
		}
	}
}

function enviarEmailAprovacaoOrcamentos($workflowID){
	global $dadosUserLogin, $configChamados, $tituloSistema;

	/* ALTERAR PARA PEGAR UM DOS FEITO ATRAVEZ DO GERENCIADOR DE DOCUMENTOS */

	$sql = "SELECT CONCAT(pd.Nome,' ', pv.Descricao) AS Produto, opp.Quantidade, opp.Valor_Venda_Unitario, co.Nome AS Colaborador,
				 co.Codigo, co.Foto AS FotoColaborador, opp.Cliente_Final_ID, ow.Workflow_ID AS Workflow_ID,
				 op.Proposta_ID AS Proposta_ID, op.Titulo, mf.Dados, ow.Situacao_ID, op.Status_ID, opp.Proposta_Produto_ID,
				 ow.Solicitante_ID as Solicitante_ID, s.Nome as Solicitante, s.Email as Email, ts.Descr_Tipo as Situacao
				FROM orcamentos_workflows ow
				INNER JOIN orcamentos_propostas op ON op.Workflow_ID = ow.Workflow_ID
				INNER JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID
				INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
				INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
				INNER JOIN cadastros_dados s on s.Cadastro_ID = ow.Solicitante_ID
				INNER JOIN tipo ts on ts.Tipo_ID = ow.Situacao_ID
				LEFT JOIN cadastros_dados co ON co.Cadastro_ID = opp.Cliente_Final_ID
				LEFT JOIN modulos_formularios mf ON mf.Slug = 'formulario-otica-colaborador' AND mf.Tabela_Estrangeira = 'cadastros_dados' AND mf.Chave_Estrangeira = opp.Cliente_Final_ID AND mf.Situacao_ID = 1
				WHERE ow.Workflow_ID = '$workflowID' AND opp.Situacao_ID = 1
				ORDER BY op.Proposta_ID, co.Nome, opp.Cliente_Final_ID";

	//echo $sql;
	$flag = "";
	$resultSet = mpress_query($sql);
	while($rs = mpress_fetch_array($resultSet)){
		$solicitanteID = $rs['Solicitante_ID'];
		$emailSolicitante = $rs['Email'];
		$solicitante = utf8_encode($rs['Solicitante']);
		$descrSituacao = utf8_encode($rs['Situacao']);
		if ($propostaIDAnt!=$rs['Proposta_ID']){
			if ($flag=="1"){
				$i++;
				$dados[$propostaID][colunas][conteudo][$i][1] = "<p style='text-align:right; margin-right:5px;'><b>TOTAL:</b></p>";
				$dados[$propostaID][colunas][colspan][$i][1] = "3";
				$dados[$propostaID][colunas][conteudo][$i][4] = "<p style='text-align:right; margin-right:5px;' id='valor-total-proposta-$propostaID'>R$ ".number_format($tot[$propostaID], 2, ',', '.')."</p><input type='hidden' id='total-proposta-$propostaID' value='".$tot[$propostaID]."'/>";
			}
			$flag = "1";
			$i = 0;
			$clienteFinalIDAnt = "";
		}
		$workflowID = $rs['Workflow_ID'];
		$propostaID = $rs['Proposta_ID'];
		$valorVenda = $rs['Valor_Venda_Unitario'] * $rs['Quantidade'];
		$foto = $row['FotoColaborador'];
		if ($foto!="")
			$imagemFoto = "<img src='$caminhoSistema/uploads/$foto' style='height:40px; cursor:pointer;'>";
		else
			$imagemFoto = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' style='height:40px; cursor:pointer;'>";

		$i++;

		$dadosFormAux = unserialize($rs['Dados']);
		$turno = $dadosFormAux['turno'];
		unset($selTurno);
		$selTurno[$dadosFormAux['turno']] 				= " selected ";

		$dados[$propostaID][colunas][conteudo][$i][1] 	= "	<p style='text-align:left;'><b>".utf8_encode($rs['Colaborador'])."</b></p>";
		$dados[$propostaID][colunas][conteudo][$i][2] 	= utf8_encode($rs['Codigo']);
		$dados[$propostaID][colunas][conteudo][$i][3] 	= utf8_encode($rs['Produto']);
		$dados[$propostaID][colunas][conteudo][$i][4] 	= "	<p style='text-align:right; margin-right:5px;'>R$ ".number_format($valorVenda, 2, ',', '.')."</p>
															<input type='hidden' id='valor-produto-$propostaID-$i' class='valor-produto'  pos='$i' value='$valorVenda'/>";
		$dados[$propostaID][colunas][classe][$i][1] = "tabela-fundo-escuro";

		$i++;
		$dados[$propostaID][colunas][classe][$i][1] = "tabela-fundo-escuro";
		$dados[$propostaID][colunas][colspan][$i][1] = "4";
		$dados[$propostaID][colunas][conteudo][$i][1] = "";
		$dados[$propostaID][tituloproposta] = $rs['Titulo'];
		$tot[$propostaID] += $valorVenda;
		$propostaIDAnt = $rs['Proposta_ID'];
	}

	$i++;
	$dados[$propostaID][colunas][conteudo][$i][1] = "<p style='text-align:right; margin-right:5px;'><b>TOTAL:</b></p>";
	$dados[$propostaID][colunas][colspan][$i][1] = "3";
	$dados[$propostaID][colunas][conteudo][$i][4] = "<p style='text-align:right; margin-right:5px;'  id='valor-total-proposta-$propostaID'>R$ ".number_format($tot[$propostaID], 2, ',', '.')."</p>";

	$conteudoEmail = "	<table width='780' border='0' align='center' cellpadding='0' cellspacing='0' style=''>
							<tr>
								<td align='left' style='border-bottom:1px solid #cccccc;' colspan='2'><b><center>OR&Ccedil;AMENTO DE &Oacute;CULOS DE SEGURAN&Ccedil;A GRADUADOS</center></td>
							</tr>
							<tr>
						</table>
						<table width='780' border='0' align='center' cellpadding='0' cellspacing='0' style=''>
							<tr>
								<td align='left' style='border-bottom:1px solid #cccccc;'>
									".carregarCadastroGeral($solicitanteID)."
								</td>
							</tr>
						</table>
						<table width='780' border='0' align='center' cellpadding='0' cellspacing='0' style=''>
							<tr>
								<td align='left' style='border-bottom:1px solid #cccccc;' colspan='2'><b>Or&ccedil;amento N&ordm;: $workflowID</td>
							</tr>
							<tr>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Situa&ccedil;&atilde;o:</b></td>
								<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>$descrSituacao</td>
							</tr>";

	foreach ($dados as $chave => $dado){
		$dados[$chave][colunas][titulo][1] = "Colaborador";
		$dados[$chave][colunas][titulo][2] = "N&ordm; de Registro";
		$dados[$chave][colunas][titulo][3] = "Produto";
		$dados[$chave][colunas][titulo][4] = "<p style='text-align:right; margin-right:5px;'>Valor</p>";
		$conteudoEmail .= "	<tr>
								<td colspan='2'>
									<h1 style='font-size:15px;' align='center'>".$dados[$chave][tituloproposta]."</h1>
									".geraTabela("100%", "4", $dados[$chave], ' margin:0 auto; border:0px solid silver;', "tabela-lista-orcamentos-$propostaID", 2, 2, 24, "","return")."
								</td>
							</tr>";
	}
	$conteudoEmail .= "
							<tr>
								<td colspan='2' style='margin-top:50px;'>
									<a href='http://seguranca.oticasdiorama.com.br/area-restrita/'>
										<div style='background-color: #f4ba2f; border:0px; border-radius: 5px; height:25px; color:#000000; float:right; text-align:center; padding:3px 3px 3px 3px;'>Clique aqui para a aprovar a proposta desejada.</div>
									</a>
								</td>
							</tr>
						</table>";
	$dadosEmail = $configChamados['emails-copia-orcamento'].";".$emailSolicitante;
	//echo geraEmailPadrao($conteudoEmail);
	enviaEmails($dadosEmail, "$tituloSistema - Orçamento nº$workflowID", geraEmailPadrao($conteudoEmail), "");
}

function carregarFollowsOrcamentosPropostas($propostaID){
	$sql = "SELECT o.Descricao, o.Situacao_ID, o.Data_Cadastro, cd.Nome, t.Descr_Tipo as Situacao
			FROM orcamentos_propostas_follows o
			left join tipo t on t.Tipo_ID = o.Situacao_ID
			left join cadastros_dados cd on cd.Cadastro_ID = o.Usuario_Cadastro_ID
			where o.Proposta_ID = '$propostaID'
			order by o.Follow_ID desc";
	//echo $sql;
	$queryF = mpress_query($sql);
	$i=0;
	while($rsF = mpress_fetch_array($queryF)){
		$i++;
		$dadosFollows[colunas][conteudo][$i][1] = "<p Style='margin:2px;float:left;'>".nl2br($rsF['Descricao'])."</p>";
		$dadosFollows[colunas][conteudo][$i][2] = "<p Style='margin:2px;float:left;'>".$rsF['Situacao']."</p>";
		$dadosFollows[colunas][conteudo][$i][3] = "<p Style='margin:2px;float:left;'>".$rsF['Nome']."</p>";
		$dadosFollows[colunas][conteudo][$i][4] = "<p align='center' Style='margin:2px;float:left;'>".converteDataHora($rsF['Data_Cadastro'],1)."</p>";
	}
	if($i>=1){
		$dadosFollows[colunas][titulo][1] 	= "Hist&oacute;rico";
		$dadosFollows[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
		$dadosFollows[colunas][titulo][3] 	= "Usu&aacute;rio";
		$dadosFollows[colunas][titulo][4] 	= "Data";

		$dadosFollows[colunas][tamanho][1] = "width='40%'";
		$dadosFollows[colunas][tamanho][2] = "width='20%'";
		$dadosFollows[colunas][tamanho][3] = "width='20%'";
		$dadosFollows[colunas][tamanho][4] = "width='20%' align='center'";
		geraTabela("99.4%","4",$dadosFollows,null,"tabela-historico-follows-propostas-$propostaID", 2, 2, 50, "");
	}
}

function salvarOrcamentoProposta(){
	global $dadosUserLogin;
	$dataHoraAtual 			= retornaDataHora('','Y-m-d H:i:s');
	$workflowID 			= $_POST['workflow-id'];
	$propostaID 			= $_POST['proposta-id'];
	$tituloProposta 		= utf8_decode($_POST['titulo-proposta']);
	$tabelaPrecoID 			= $_POST['tabela-preco-proposta'];

	if ($tabelaPrecoID == '') $tabelaPrecoID = "0";

	if ($propostaID==""){

		mpress_query("insert into orcamentos_propostas (Workflow_ID, Titulo, Tabela_Preco_ID, Data_Cadastro, Usuario_Cadastro_ID, Status_ID, Situacao_ID)
										values ('$workflowID', '$tituloProposta', '$tabelaPrecoID', '$dataHoraAtual', '".$_SESSION['dadosUserLogin']['userID']."', '114', 1)");
		$propostaID = mysql_insert_id();

		$sqlProposta = "insert into orcamentos_propostas_follows (Proposta_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$propostaID', 'Proposta Criada', '114', '$dataHoraAtual', '".$_SESSION['dadosUserLogin']['userID']."')";

		mpress_query("insert into orcamentos_propostas_follows (Proposta_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$propostaID', 'Proposta Criada', '114', '$dataHoraAtual', '".$_SESSION['dadosUserLogin']['userID']."')");

		var_dump($sqlProposta, $propostaID);
	}
	if ($_GET['proposta-id']!=''){
		$tituloProposta = ($_GET['editar-titulo']);
		mpress_query("update orcamentos_propostas set Titulo = '$tituloProposta' where Proposta_ID = '".$_GET['proposta-id']."'");
	}
	echo $propostaID;
}



function excluirOrcamentoProposta($propostaID,$situacaoAtualID){
	global $dadosUserLogin;
	$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
	if ($propostaID!=""){
		mpress_query("update orcamentos_propostas set Situacao_ID = 2 where Proposta_ID = '$propostaID'");
		mpress_query("insert into orcamentos_propostas_follows (Proposta_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$propostaID', 'Proposta Excluida', '$situacaoAtualID', '$dataHoraAtual', '".$dadosUserLogin['userID']."')");
	}
	//echo $sql;
}



function salvarConfiguracoesGeraisProcessos(){
	if ($_POST['orcamentos']=='1') $orcamentos = 0; else $orcamentos = 1;
	if ($_POST['chamados']=='1') $chamados = 0; else $chamados = 1;
	if ($_POST['comissionamento-orcamento']=='') $comissao = 1; else $comissao = 0;
	if ($_POST['crm']=='1') {
		$crm = 0;
	}
	else{
		$crm = 1;
	}

	mpress_query("update modulos_paginas set Oculta_Menu = '$orcamentos' where slug = 'chamados-orcamento-localizar'");
	mpress_query("update modulos_paginas set Oculta_Menu = '$chamados' where slug = 'chamados-localizar-chamado'");
	mpress_query("update modulos_paginas set Oculta_Menu = '$comissao' where slug = 'financeiro-comissionamento'");

	mpress_query("update modulos_paginas set Oculta_Menu = '$crm' where slug = 'oportunidades'");
	mpress_query("update modulos_paginas set Oculta_Menu = '$crm' where slug = 'relatorio-pipeline'");
	mpress_query("update modulos_paginas set Oculta_Menu = '$crm' where slug = 'relatorio-forecast'");


	$array['listagem-orcamento'] = $_POST['listagem-produtos-orcamento'];
	$array['comissionamento-orcamento'] = $_POST['comissionamento-orcamento'];
	$array['listagem-chamado'] = $_POST['listagem-produtos-chamado'];
	$array['fluxo-aprovacao'] = $_POST['fluxo-aprovacao'];
	$array['orcamento-grupos-responsaveis'] = serialize($_POST['orcamento-grupos-responsaveis']);
	$array['emails-copia-orcamento'] = $_POST['emails-copia-orcamento'];
	$array['emails-copia-orcamento-finalizado'] = $_POST['emails-copia-orcamento-finalizado'];
	$array['agrupar-produtos'] = $_POST['agrupar-produtos'];
	$array['exibe-bloco-prestador'] = $_POST['exibe-bloco-prestador'];
	$array['exibe-bloco-frete'] = $_POST['exibe-bloco-frete'];
	$array['exibe-bloco-forma-pagamento'] = $_POST['exibe-bloco-forma-pagamento'];
	$array['exibe-envia-proposta-email'] = $_POST['exibe-envia-proposta-email'];
	$array['cancelar-propostas'] = $_POST['cancelar-propostas'];
	$array['crm'] = $_POST['crm'];

	mpress_query("update tipo set Descr_Tipo = '".serialize($array)."', Tipo_Auxiliar = 'chamados' where Tipo_ID = 106");
	mpress_query("update modulos_paginas set Campos_Obrigatorios = '".serialize($_POST['campos-obrigatorios-orcamento'])."' where Slug = 'chamados-orcamento'");
	mpress_query("update modulos_paginas set Campos_Obrigatorios = '".serialize($_POST['campos-obrigatorios-chamado'])."' where Slug = 'chamados-cadastro-chamado'");
}


function carregarOrcamentosChamados($workflowID, $esconder){
	global $caminhoSistema;
	echo "	<div class='titulo-container $esconder conjunto4'>
				<div class='titulo'>
					<p>	Gerar ".$_SESSION['objeto']." para o Or&ccedil;amento
						<input type='button' class='botao-gerar-os campos-gerar-os' style='float:right;margin-right:0px;width:120px' value='Gerar ".$_SESSION['objeto']."'>
					</p>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div style='width:100%;float:left;' class='campos-gerar-os'>";

	echo "				<div style='width:30%;float:left;'>
							<p><b>Projeto ".$_SESSION['objeto']." </b></p>
							<p class='omega' Style='float:left;'>
								<!--<select name='projeto-id-os' id='projeto-id-os' style='width:100%;float:left;' class='required'>-->
								<select name='projeto-id-os' id='projeto-id-os' style='width:100%;float:left;'>
									<option value=''>Selecione</option>";
	echo optionValueProjetos($projetoID, "chamados_workflows");
	echo "						</select>
							</p>
						</div>";

	echo "				<div class='titulo-secundario' style='width:20%;float:left;'>
							<p><b>Tipo ".$_SESSION['objeto']."</b></p>
							<p><select name='tipo-id-os' id='tipo-id-os' style='width:97%'>".optionValueGrupoFilho(19, "", "Selecione")."</select></p>
						</div>";

	echo "				<div class='titulo-secundario' style='width:20%;float:left;'>
							<p><b>Grupo Respons&aacute;vel</b></p>
							<p><select name='select-grupo-os' id='select-grupo-os' class='required' style='width:98.5%' campo='select-usuario-os'>".optionValueGruposAcessos('', ' and Modulo_Acesso_ID <> -4','')."</select></p>
						</div>";
	echo "				<div class='titulo-secundario' style='width:30%;float:left;'>
							<p><b>Usuário Respons&aacute;vel</b>
							<p><select name='select-usuario-os' id='select-usuario-os' style='width:98.5%' class='required'>".optionValueUsuarios('', '', '')."<select></p>
						</div>";
	echo "				<div style='width:100%;float:left;'>&nbsp;</div>
					</div>";

	if ($workflowID!=""){
		$sql = "SELECT op.Proposta_ID, op.Titulo, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Descricao_Produto, pv.Codigo as Codigo, opp.Quantidade, opp.Valor_Venda_Unitario,
					coalesce(pc.Nome,'Sem categoria') as Categoria, coalesce(pc.Categoria_ID,0) as Categoria_ID,
					opp.Proposta_Produto_ID, opcp.Chamado_ID as Chamado_ID
				FROM orcamentos_propostas_produtos opp
				INNER JOIN orcamentos_propostas op ON op.Proposta_ID = opp.Proposta_ID
				INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
				INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
				INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
				LEFT JOIN orcamentos_produtos_chamados_produtos opcp on opcp.Proposta_Produto_ID = opp.Proposta_Produto_ID
				left join produtos_dados_categorias pdc on pdc.Produto_Categoria_ID = opp.Produto_Categoria_ID
				LEFT JOIN produtos_categorias pc ON pc.Categoria_ID = pdc.Categoria_ID
				LEFT JOIN modulos_anexos ma ON ma.Anexo_ID = pv.Imagem_ID
				WHERE op.Workflow_ID = '$workflowID' AND opp.Situacao_ID = 1 AND opp.Quantidade > 0 AND op.Status_ID = 141
				ORDER BY op.Proposta_ID, Categoria, Descricao_Produto ";

		//echo $sql;
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($rs[Codigo]!="") $codigo = $rs[Codigo]." - "; else $codigo = "";
			//$dados[colunas][conteudo][$i][1] = $rs['Titulo'];
			//$dados[colunas][conteudo][$i][2] = $rs['Categoria'];
			$dados[colunas][conteudo][$i][1] = $codigo.$rs['Descricao_Produto'];
			$dados[colunas][conteudo][$i][2] = "<p align='center'>".number_format($rs['Quantidade'], 0, '', '.')."</p>";
			$dados[colunas][conteudo][$i][3] = $rs['Categoria'];
			if ($rs[Chamado_ID]==""){
				$dados[colunas][conteudo][$i][4] = "Aguardando Gerar ".$_SESSION['objeto'];
				$dados[colunas][conteudo][$i][5] = "<p align='center'><input type='checkbox' id='gerar-os-$i' name='gerar-os[]' class='gerar-os' value='".$rs[Proposta_Produto_ID]."'/></p>";
			}
			else{
				$dados[colunas][conteudo][$i][4] = $_SESSION['objeto']." gerada: <span class='link link-chamado' chamado-id='".$rs["Chamado_ID"]."'>".$rs["Chamado_ID"]."</span>";
				$dados[colunas][conteudo][$i][5] = "<div class='btn-disponivel' title='".$_SESSION['objeto']." Gerada' style='float:right;margin-right:5px'>&nbsp;</div>";
			}
		}
		if ($i==0){
			echo "<p align='center'>Nenhum produto/servi&ccedil;o para gerar ".$_SESSION['objeto']."</p>";
		}
		else{
			//$dados[colunas][titulo][1] 	= "Proposta";
			$dados[colunas][titulo][1] 	= "Produto / Serviço";
			$dados[colunas][titulo][2] 	= "<center>Quantidade</center>";
			$dados[colunas][titulo][3] 	= "Categoria";
			$dados[colunas][titulo][4] 	= "Situação";
			$dados[colunas][titulo][5] 	= "<center class='sel-todas-prod-os link'>Selecionar</center>";

			//$dados[colunas][tamanho][1] = "width='20%'";
			//$dados[colunas][tamanho][2] = "width='40%'";
			//$dados[colunas][tamanho][3] = "width='20%'";
			//$dados[colunas][tamanho][4] = "width='20%'";
			$dados[colunas][tamanho][5] = "width='16px'";
			geraTabela("100%","5",$dados , null, "tabela-produtos-os-$workflowID", 2, 2, "", "");
		}

	}
	echo "		</div>
			</div>";
	unset($dados);
	echo "	<div class='titulo-container $esconder conjunto4'>
				<div class='titulo'>
					<p>".$_SESSION['objeto']." geradas vinculadas ao Or&ccedil;amento</p>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div style='width:100%;float:left;'>";

	$sql = "	SELECT cw.Workflow_ID as Chamado_ID, cw.Titulo, tw.Descr_Tipo as Tipo, ma.Titulo as Grupo_Responsavel, r.Nome as Responsavel, cw.Data_Cadastro, u.Nome as Usuario_Cadastro
				FROM orcamentos_chamados oc
				INNER JOIN chamados_workflows cw on cw.Workflow_ID = oc.Chamado_ID
				INNER JOIN modulos_acessos ma on ma.Modulo_Acesso_ID = cw.Grupo_Responsavel_ID
				LEFT JOIN cadastros_dados r on r.Cadastro_ID = cw.Responsavel_ID
				LEFT JOIN cadastros_dados u on u.Cadastro_ID = cw.Usuario_Cadastro_ID
				LEFT JOIN tipo tw on tw.Tipo_ID = cw.Tipo_Workflow_ID
				WHERE oc.Orcamento_ID = '$workflowID'
				and oc.Situacao_ID = 1";
	//echo $sql;
	$query = mpress_query($sql);
	$i=0;
	while($rs = mpress_fetch_array($query)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<p align='center' class='link workflow-localiza' workflow-id='".$rs[Chamado_ID]."'>".$rs['Chamado_ID']."</p>";
		$dados[colunas][conteudo][$i][2] = $rs['Titulo'];
		$dados[colunas][conteudo][$i][3] = $rs['Tipo'];
		$dados[colunas][conteudo][$i][4] = $rs['Grupo_Responsavel'];
		$dados[colunas][conteudo][$i][5] = $rs['Responsavel'];
		$dados[colunas][conteudo][$i][6] = "<p align='center'>".$rs['Usuario_Cadastro']."<br>".converteDataHora($rs['Data_Cadastro'],1)."</p>";
	}
	if ($i>0){
		$dados[colunas][titulo][1] 	= "<p align='center'>".$_SESSION['objeto']."</p>";
		$dados[colunas][titulo][2] 	= "T&Iacute;tulo";
		$dados[colunas][titulo][3] 	= "Tipo ".$_SESSION['objeto'];
		$dados[colunas][titulo][4] 	= "Usu&aacute;rio Respon&aacute;vel";
		$dados[colunas][titulo][5] 	= "Grupo Respons&aacute;vel";
		$dados[colunas][titulo][6] 	= "Usu&aacute;rio / Data Cadastro";

		$dados[colunas][tamanho][1] = "width='30px'";
		//$dados[colunas][tamanho][2] = "width='20%'";
		///$dados[colunas][tamanho][3] = "width='20%'";
		//$dados[colunas][tamanho][4] = "width='20%' align='center'";
		geraTabela("99.4%","6",$dados, null, "tabela-chamados-orcamento", 2, 2, 50, "");
	}
	else{
		echo "			<p Style='margin:15px 5px 0 5px;color:red; text-align:center'>".$_SESSION['objeto']." não gerado para este orçamento.</p>";
	}
	echo "
					</div>
				</div>
			</div>";
	echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
}


function gerarChamadosOrcamentos(){
	global $dadosUserLogin;
	$dataHoraAtual 		= "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$orcamentoID 		= $_POST['workflow-id'];
	$usuarioChamadoID 	= $_POST['select-usuario-os'];
	$grupoChamadoID 	= $_POST['select-grupo-os'];
	$projetoID 			= $_POST['projeto-id-os'];
	$tipoID 			= $_POST['tipo-id-os'];
	$solicitanteID 		= $_POST['solicitante-id'];
					
	$temp = "SELECT 
				Empresa_ID, 
				Solicitante_ID, 
				Codigo, 
				Titulo
				FROM orcamentos_workflows 
				WHERE Workflow_ID = '$orcamentoID'";

	/*
			$dataHoraAtual, 
			$dataHoraAtual, 
			'".$dadosUserLogin['userID']."', 
			'$usuarioChamadoID', 
			'$grupoChamadoID', 
			'$tipoID' 
	*/

	$query = mpress_query($temp);

	while($rs = mpress_fetch_array($query)){

		$empresaId 		= $rs['Empresa_ID'];
		$solicitanteId 	= $rs['Solicitante_ID'];
		
		$titulo 		= $rs['Titulo'];

		//if( !empty($rs['Codigo'])){
			$codigo 	= $rs['Codigo'];
		//}else{
			//$codigo 	= 0;
		//}
	}

	if( $tipoID == ''){
		$tipoID 	= 0;
	}


	/* É efetuado uma busca por informações do orçamento para posteriormente
		inserir essas informações nas tabelas de chamados seguintes */


	$sql = "INSERT INTO chamados_workflows (Cadastro_ID, 
		Solicitante_ID, 
		Codigo, 
		Titulo, 
		Data_Cadastro, 
		Data_Abertura, 
		Usuario_Cadastro_ID,
		Responsavel_ID, 
		Grupo_Responsavel_ID, 
		Tipo_Workflow_ID)
		VALUES ('$empresaId',
		 	'$solicitanteId', 
		 	'$codigo',
		 	'$titulo',
		 	$dataHoraAtual, 
			$dataHoraAtual,
		 	'".$dadosUserLogin['userID']."',
		 	'$usuarioChamadoID',
		 	'$grupoChamadoID',
			'$tipoID')";

	//echo "<br><br>".$sql.";";

	// echo $sql;

	// die();

	mpress_query($sql);
	$chamadoID = mysql_insert_id();

	$sql = "INSERT INTO orcamentos_chamados (Orcamento_ID, Chamado_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID) VALUES ('$orcamentoID', '$chamadoID', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."')";

	//echo "<br><br>".$sql.";";
	mpress_query($sql);

	$sql = "INSERT INTO chamados_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID) VALUES ('$chamadoID', '".$_SESSION['objeto']." aberto(a), referente ao orçamento $orcamentoID', 32, $dataHoraAtual, '".$dadosUserLogin['userID']."')";

	mpress_query($sql);
	//echo "<br><br>".$sql.";";

	if ($projetoID!=""){
		salvarProjeto($projetoID, $chamadoID, 'Workflow_ID' , 'chamados_workflows', $usuarioChamadoID, $solicitanteID);
	}

	foreach ($_POST['gerar-os'] as $propostaProdutoID) {
		// $sql = "INSERT INTO chamados_workflows_produtos 
		// 			(Workflow_ID, 
		// 			Produto_Variacao_ID, 
		// 			Quantidade, 
		// 			Valor_Custo_Unitario, 
		// 			Valor_Venda_Unitario, 
		// 			Cobranca_Cliente, 
		// 			Pagamento_Prestador, 
		// 			Cliente_Final_ID, 
		// 			Situacao_ID, 
		// 			Data_Cadastro, 
		// 			Usuario_Cadastro_ID)
		// 												select '$chamadoID', 
		// 												Produto_Variacao_ID, 
		// 												Quantidade, 
		// 												Valor_Custo_Unitario, Valor_Venda_Unitario, Cobranca_Cliente, 
		// 												Pagamento_Prestador, 
		// 												Cliente_Final_ID, 
		// 												Situacao_ID, 
		// 												$dataHoraAtual, 
		// 												'".$dadosUserLogin['userID']."'
		// 												from orcamentos_propostas_produtos where Proposta_Produto_ID = '$propostaProdutoID'";

		/* 
			Atualizando as queries para contornar possiveis erros de versão de Mysql
		*/

			//Procura pelos dados dos produtos adicionados na propostas
		$tempSql = "SELECT 	Produto_Variacao_ID,
							Quantidade,
							Valor_Custo_Unitario,
							Valor_Venda_Unitario,
							Cobranca_Cliente,
							Pagamento_Prestador,
							Cliente_Final_ID,
							Situacao_ID
					FROM orcamentos_propostas_produtos
					WHERE Proposta_Produto_ID = '$propostaProdutoID'";


		$query = mpress_query($tempSql);

		//$rs = mpress_fetch_array($query);

		while($rs = mpress_fetch_array($query)){
			$produtoVariacao 	= $rs['Produto_Variacao_ID'];
			$quantidade 		= $rs['Quantidade'];
			$valorCustoUnitario = $rs['Valor_Custo_Unitario'];
			$valorVendaUnitario = $rs['Valor_Venda_Unitario'];
			$cobrancaCliente 	= $rs['Cobranca_Cliente'];
			$prestadorPrestador = $rs['Pagamento_Prestador'];
			$clienteFinalId 	= $rs['Cliente_Final_ID'];
			$situacaoId 		= $rs['Situacao_ID'];
		}

			//Salva as informações coletadas nos produtos das chamadas

		$sql = "INSERT INTO chamados_workflows_produtos(
					Workflow_ID, 
					Produto_Variacao_ID, 
					Quantidade,
					Observacao_Produtos,
					Valor_Custo_Unitario, 
					Valor_Venda_Unitario, 
					Cobranca_Cliente, 
					Pagamento_Prestador, 
					Cliente_Final_ID, 
					Situacao_ID, 
					Data_Cadastro, 
					Usuario_Cadastro_ID
				)
				VALUES(
					'$chamadoID',
					'$produtoVariacao',
					'$quantidade',
					'',
					'$valorCustoUnitario',
					'$valorVendaUnitario',
					'$cobrancaCliente',
					'$prestadorPrestador',
					'$clienteFinalId',
					'$situacaoId',
					$dataHoraAtual,
					'".$dadosUserLogin['userID']."'
				)";

		//echo $sql;
		//var_dump($_POST['gerar-os']);
		//var_dump($sql);
		//die();

		mpress_query($sql);
		//echo "<br><br>".$sql.";";
		$chamadoProdutoID = mysql_insert_id();
		$sql = "INSERT INTO orcamentos_produtos_chamados_produtos
					(Proposta_Produto_ID, Chamado_Produto_ID, Chamado_ID, Orcamento_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
				VALUES ('$propostaProdutoID', '$chamadoProdutoID', '$chamadoID', '$orcamentoID', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		mpress_query($sql);
		//echo "<br><br>".$sql.";";
	}
}

function orcamentoCopiarProposta($propostaID, $orcamentoID){

	global $dadosUserLogin;
	$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
	$sql = "insert into orcamentos_propostas (Workflow_ID, Titulo, Tabela_Preco_ID, Data_Cadastro, Usuario_Cadastro_ID, Status_ID, Situacao_ID)
							select $orcamentoID, concat(oppOrig.Titulo,' ***(C&oacute;pia)'), oppOrig.Tabela_Preco_ID, '".$dataHoraAtual ."', '".$dadosUserLogin['userID']."',114,1 from orcamentos_propostas oppOrig where oppOrig.Proposta_ID = '$propostaID'";
	$resultado = mpress_query($sql);
	$propostaNovaID = mysql_insert_id();


	$sql = "INSERT INTO orcamentos_propostas_produtos
					(Proposta_ID, Produto_Variacao_ID, Produto_Categoria_ID, Descricao, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario, Cobranca_Cliente, Pagamento_Prestador, Situacao_ID, Status_ID, Data_Cadastro, Usuario_Cadastro_ID)
			select $propostaNovaID, opp.Produto_Variacao_ID, opp.Produto_Categoria_ID, opp.Descricao, opp.Quantidade, opp.Valor_Custo_Unitario, opp.Valor_Venda_Unitario, opp.Cobranca_Cliente, opp.Pagamento_Prestador, 1, 123, '".$dataHoraAtual ."', '".$dadosUserLogin['userID']."'
				 from orcamentos_propostas_produtos opp
				 inner join orcamentos_propostas op on op.Proposta_ID = opp.Proposta_ID
				 inner join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
				 inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				 where op.Proposta_ID = '$propostaID' and opp.Situacao_ID = 1 and pd.Situacao_ID = 1 and pv.Situacao_ID = 1 and opp.Quantidade > 0";
	//echo $sql;
	mpress_query($sql);

	$sql = " insert into orcamentos_propostas_eventos
				(Proposta_Produto_ID, Proposta_ID, Participantes, Data_Evento, Situacao_ID, Data_Cadastro)
				select ope.Proposta_Produto_ID, '$propostaNovaID', 0, null, 1, '".$dataHoraAtual ."'
				 from orcamentos_propostas_eventos ope
				inner join orcamentos_propostas_produtos opp on opp.Proposta_Produto_ID = ope.Proposta_Produto_ID
				inner join produtos_variacoes pv on opp.Produto_Variacao_ID = pv.Produto_Variacao_ID and pv.Situacao_ID = 1
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID and pd.Situacao_ID = 1
			where ope.Proposta_ID = '$propostaID' and opp.Situacao_ID = 1 and opp.Quantidade > 0";
	mpress_query($sql);
}




/*****************************************/
/***** INICIO - BLOCO OPORTUNIDADES ******/
/*****************************************/
function carregarOportunidades($cadastroID){
	$h =  "	<div class='titulo-container grupo8' id='div-oportunidades'>
				<div class='titulo'>
					<p>
						Oportunidades
						<input type='button' value='Incluir Oportunidade' class='botao-nova-oportunidade' oportunidade-id='' cadastro-id='$cadastroID' style='cursor:pointer;float:right; width: 120px;'/>
					</p>
				</div>
				<div class='conteudo-interno' id='conteudo-interno-oportunidade'>
					<div class='div-oportunidade-'></div>
					<div id='div-oportunidades-cadastradas'>";
	$h .= carregarListaOportunidades($cadastroID);
	$h .= "			</div>
				</div>
			</div>";
	return $h;
}

function carregarListaOportunidades($cadastroID){
	$sql = "SELECT Cadastro_ID, Oportunidade_ID, Classificacao, Data_Retorno, Descricao, Tarefa_ID, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro
				FROM oportunidades where Cadastro_ID = '$cadastroID' AND Situacao_ID = 1
				order by Data_Retorno";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<span class=''><p Style='margin:2px 5px 0 5px;float:left;'>".$row['Descricao']."</p></span>";
		//$dados[colunas][conteudo][$i][2] = "<span class=''><p Style='margin:2px 5px 0 5px;float:left;'></p></span>";
		$dados[colunas][conteudo][$i][2] = "<span class=''><p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($row['Data_Retorno'],1)."</p></span>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;'>
												<div class='btn-editar botao-editar-oportunidade' oportunidade-id='".$row['Oportunidade_ID']."' cadastro-id='".$row[Cadastro_ID]."' style='float:right; padding-right:10px' title='Editar'>&nbsp;</div></div>
												<div class='btn-excluir botao-excluir-oportunidade' oportunidade-id='".$row['Oportunidade_ID']."' cadastro-id='".$row[Cadastro_ID]."' style='float:right; padding-right:10px' title='Excluir'>&nbsp;</div></div>
											</p>";
	}
	$largura = "100.2%";
	$colunas = "3";
	$dados[colunas][tamanho][1] = "width=''";
	//$dados[colunas][tamanho][2] = "width='20%'";
	$dados[colunas][tamanho][2] = "width='150px'";
	$dados[colunas][tamanho][3] = "width='100px'";

	$dados[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
	//$dados[colunas][titulo][2] 	= "Classifica&ccedil;&atilde;o";
	//$dados[colunas][titulo][2] 	= "Respons&aacute;vel";
	$dados[colunas][titulo][2] 	= "Data Retorno";
	if($i==0){
		$h .= "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma oportunidade cadastrada!</p>";
	}
	else{
		$h = geraTabela($largura, $colunas, $dados, "margin-top:0px;border:0px solid silver;margin-bottom:0px;", 'lista-oportunidades', 4, 0, 100,'','return');
	}
	return $h;
}


function salvarOportunidade(){
	global $modulosAtivos, $dadosUserLogin, $caminhoSistema;
	//$dadosUserLogin = $_SESSION['dadosUserLogin'];
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";


	$oportunidadeID = $_POST['oportunidade-id'];
	$empresaID = $_POST['oportunidade-empresa-id'];
	$cadastroID = $_POST['oportunidade-cadastro-id'];
	$titulo = utf8_decode($_POST['oportunidade-nome']);
	$descricao = utf8_decode($_POST['oportunidade-descricao']);
	$origemID = $_POST['oportunidade-origem'];
	$dataCadastro = $_POST['oportunidade-data-cadastro'];
	$expectativaValor = $_POST['oportunidade-expectativa-valor'];
	$dataPrevisaoFechamento = formataDataBD($_POST['oportunidade-data-previsao-fechamento']);
	$probabilidadeFechamento = $_POST['oportunidade-probabilidade-fechamento'];
	$responsavelID = $_POST['oportunidade-responsavel'];
	$situacaoID = $_POST['oportunidade-situacao-funil'];
	$valor = formataValorBD($_POST['oportunidade-valor']);
	$tipoID = $_POST['oportunidade-tipo'];

	if ($oportunidadeID==''){
		$sql = "INSERT INTO oportunidades_workflows
						(Cadastro_ID, Tipo_ID, Empresa_ID, Origem_ID, Titulo, Descricao, Expectativa_Valor, Probabilidade_Fechamento, Data_Previsao, Situacao_ID, Status_ID, Responsavel_ID, Usuario_Cadastro_ID, Data_Cadastro)
				VALUES ('$cadastroID', '$tipoID', '$empresaID', '$origemID', '$titulo', '$descricao', '$valor', '$probabilidadeFechamento', '$dataPrevisaoFechamento','$situacaoID', 1, '$responsavelID', ".$dadosUserLogin['userID'].", $dataHoraAtual)";
		$resultado = mpress_query($sql);
		$oportunidadeID = mpress_identity();
	}
	else{
		$sql = "UPDATE oportunidades_workflows SET
					Cadastro_ID = '$cadastroID',
					Tipo_ID = '$tipoID',
					Empresa_ID = '$empresaID',
					Origem_ID = '$origemID',
					Titulo = '$titulo',
					Descricao = '$descricao',
					Probabilidade_Fechamento = '$probabilidadeFechamento',
					Data_Previsao = '$dataPrevisaoFechamento',
					Expectativa_Valor = '$valor',
					Situacao_ID = '$situacaoID',
					Responsavel_ID = '$responsavelID'
				WHERE Oportunidade_ID = '$oportunidadeID'";
		//exit($sql);
		$resultado = mpress_query($sql);
	}


	if ($situacaoID=='183'){
		$sql = "INSERT INTO orcamentos_workflows (Empresa_ID, Solicitante_ID, Representante_ID, Situacao_ID, Codigo, Titulo, Data_Abertura, Data_Finalizado, Data_Cadastro, Usuario_Cadastro_ID)
										  VALUES ('$empresaID', '$cadastroID', '$responsavelID', '184', '', '$titulo', $dataHoraAtual, NULL, $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		mpress_query($sql);
		$orcamentoID = mysql_insert_id();

		$sql = "INSERT INTO orcamentos_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										 VALUES ('$orcamentoID', '$descricao', '', '184', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		mpress_query($sql);

		$sql = "UPDATE oportunidades_workflows SET Orcamento_ID = '$orcamentoID', Situacao_ID = '184' where Oportunidade_ID = '$oportunidadeID'";
		mpress_query($sql);
	}
	echo $oportunidadeID;
}

function excluirOportunidade($oportunidadeID){
	$sql = "UPDATE oportunidades SET Status_ID = 2 where Oportunidade_ID = '$oportunidadeID' ";
	mpress_query($sql);
}

/*****************************************/
/***** FIM - BLOCO OPORTUNIDADES *********/
/*****************************************/


function carregarRepresentantes(){
	global $caminhoSistema, $configChamados;
	$cadastroID = $_GET['cadastro-id'];
	//echo "-->".$cadastroID;
	$linha = $_GET['linha'];
	//echo "-->".$linha;

	/*
	$sql = "select pv.Produto_Variacao_ID as Produto_Variacao_ID, pv.Codigo as Codigo_Variacao, pd.Codigo as Codigo, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Descricao_Produto
				from produtos_dados pd
							inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
							where pd.Situacao_ID = 1 and pv.Situacao_ID = 1
							$sqlCond
							order by pd.Produto_ID, pv.Produto_Variacao_ID";
	$h = "	<select id='select-representantes' name='select-representantes' Style='width:98.5%'>
				<option value=''>Selecione</option>";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$h .= "	<option value='".$row[Produto_Variacao_ID]."'>".utf8_encode($row['Descricao_Produto'])."</option>";
	}
	*/
	$sql = "select Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID = '$cadastroID' and Situacao_ID = 1 and Tipo_Vinculo_ID = 101";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$representantes[] = $row['Cadastro_Filho_ID'];
	}
	//echo "<pre>";
	//print_r($representantes);
	//echo "</pre>";

	$h = "	<p style='float:left; width:100%;'>
				<select name='representante-cliente-".$cadastroID."' id='representante-cliente-".$cadastroID."' class='dados-orc' style='width:98.5%' multiple='multiple'>
				".optionValueUsuarios($representantes, unserialize($configChamados['orcamento-grupos-responsaveis']))."
				</select>
			</p>
			<p style='float:right; width:100%;'>
				<input type='button' value='Atualizar' style='width:45%' class='atualizar-representante' cadastro-id='".$cadastroID."' linha='".$linha."'/>
				<input type='button' value='Cancelar' style='width:45%' class='cancelar-representante' cadastro-id='".$cadastroID."' linha='".$linha."'/>
			</p>
			<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	return $h;
}

function atualizarRepresentantes(){
	global $dadosUserLogin;
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$representantes = $_GET['representantes'];
    $cadastroID = $_GET['cadastro-id'];
	$classificacaoID = $_GET['classificacao'];

	$sql = "UPDATE cadastros_classificacoes SET Situacao_ID = 2 where Cadastro_ID = '$cadastroID'";
	mpress_query($sql);
	$sql = " INSERT INTO cadastros_classificacoes (Cadastro_ID, Classificacao_ID, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
											VALUES ('$cadastroID', '$classificacaoID', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
	mpress_query($sql);

	mpress_query("delete from cadastros_vinculos where Cadastro_ID = '$cadastroID' and Tipo_Vinculo_ID = '101'");

	foreach(explode(',',$representantes) as $representanteID){
		$sql = "Insert Into cadastros_vinculos (Tipo_Vinculo_ID, Cadastro_ID, Cadastro_Filho_ID, Situacao_ID)
										 Values ('101', '$cadastroID', '$representanteID', 1)";
		//echo $sql;
		mpress_query($sql);
	}
	$sql = "select Nome from cadastros_dados where Cadastro_ID in (".$representantes.") order by Nome";
	$resultado = mpress_query($sql);
	$h = "";
	while($row = mpress_fetch_array($resultado)){
		$h .= "<span class='trocar-rep'>".$row['Nome']."</span>";
	}
	if ($h==''){
		$h .= "<span class='trocar-rep' style='color:red;'>N&atilde;o definido</span>";
	}
	return $h;
}
?>
