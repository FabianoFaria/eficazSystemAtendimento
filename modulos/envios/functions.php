<?php
	error_reporting(0);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");
	global $dadosUserLogin;

	global $configEnvios;
	$configEnvios = carregarConfiguracoesGeraisModulos('envios');

	function optionValueSolicitantes($selecionado, $textoPrimeiro){
		$optionValue = "<option value=''>$textoPrimeiro</option>";
		$rs = mpress_query("select distinct Cadastro_ID, Nome from cadastros_dados cd inner join envios_workflows ew on ew.Usuario_Cadastro_ID = cd.Cadastro_ID and Cadastro_ID > 1 order by Nome");
		while($row = mpress_fetch_array($rs)){
			if ($selecionado==$row['Cadastro_ID']){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$row['Cadastro_ID']."' $seleciona>".($row['Nome'])."</option>";
		}
		return $optionValue;
	}

	function carregarChamado(){
		$codigoWorkflow = $_GET['envio-codigo-chamado'];
		$idWorkflow = $_GET['envio-id-chamado'];

		$sql = " select cw.Workflow_ID, cw.Codigo as Codigo, tw.Descr_Tipo as Tipo_Chamado, cd1.Nome as Solicitante,
						cd1.email as Email_Solicitante, cd2.Nome as Prestador, cd2.email as Email_Prestador, t.Descr_Tipo as Situacao,
							DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura, DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado
					from chamados_workflows cw
					left join tipo tw on tw.Tipo_ID = cw.Tipo_WorkFlow_ID
					left join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
					left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
					left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
						and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
					left join tipo t on t.Tipo_ID = cf.Situacao_ID";
		$sql .= " where cw.Workflow_ID > 0 ";

		if ($idWorkflow!="") $sql .= " and cw.Workflow_ID = '$idWorkflow'";
		else{
			if ($codigoWorkflow!="") $sql .= " and cw.Codigo = '$codigoWorkflow'";
		}

		//echo $sql;
		$worflowIDAchou = "";
		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			$solicitante = $row[Solicitante];
			if ($row[Email_Solicitante]!=""){ $solicitante .= "&nbsp;(".$row[Email_Solicitante].")";}
			$prestador = $row[Prestador];
			if ($row[Email_Prestador]!=""){ $prestador .= "&nbsp;(".$row[Email_Prestador].")";}
			echo "
				<input type='hidden' name='chave-estrangeira' id='chave-estrangeira' value='".$row[Workflow_ID]."'/>
				<input type='hidden' name='tabela-estrangeira' id='tabela-estrangeira' value='chamados'/>
				<input type='hidden' name='codigo-estrangeira' id='codigo-estrangeira' value='".$row[Codigo]."'/>
				<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Situa&ccedil;&atilde;o</b></p>
					<p style='margin-top:5px;'>".($row[Situacao])."</p>
				</div>
				<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Tipo ".$_SESSION['objeto']."</b></p>
					<p style='margin-top:5px;'>".($row[Tipo_Chamado])."</p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>Solicitante</b></p>
					<p style='margin-top:5px;'>".($solicitante)."</p>
				</div>
				<div class='titulo-secundario link workflow-chamado' style='width:10%;float:left; margin-top:15px;' workflow-id='".$row[Workflow_ID]."'>
					Visualizar
				</div>
				";
		}
	}

	function envioSalvar(){
		global $dadosUserLogin;
		$workflowID = $_POST['workflow-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$empresaID = $_POST['empresa-id'];
		$cadastroIDde = $_POST['cadastro-id-de'];
		$cadastroIDpara = $_POST['cadastro-id-para'];
		$cadastroIDtrans = $_POST['cadastro-id-trans'];
		$formaEnvioID = $_POST['forma-envio'];
		$codigoRastreamento = $_POST['codigo-rastreamento'];
		$descricao = $_POST['descricao-follow'];
		$situacaoID = $_POST['select-situacao-follow'];
		$valorTransporte = str_replace(",",".",str_replace(".","",$_POST['valor-transporte']));
		$tabelaEstrangeira = $_POST['tabela-estrangeira'];
		$chaveEstrangeira = $_POST['chave-estrangeira'];
		$observacaoFinanceiro = utf8_decode($_POST['observacoes-financeiro']);
		$tipoEnvioID = $_POST['tipo-envio'];

		$cadastroIDDeEndereco 	= $_POST['cadastro-id-de-endereco-id'];
		$cadastroIDParaEndereco = $_POST['cadastro-id-para-endereco-id'];
		$radioTipoEnvio		= $_POST['radio-tipo-envio'];

		$dataEnvio = converteDataHora($_POST['data-envio']); if ($dataEnvio!=""){ $dataEnvio = "'$dataEnvio'";} else { $dataEnvio = " NULL ";}
		$dataPrevisao = converteDataHora($_POST['data-previsao-entrega']); if ($dataPrevisao!=""){ $dataPrevisao = "'$dataPrevisao'";} else { $dataPrevisao = " NULL ";}
		if ($situacaoID=="53") $dataEntrega = converteDataHora($_POST['data-entrega']); if ($dataEntrega!=""){ $dataEntrega = "'$dataEntrega'";} else { $dataEntrega = "NULL ";}

		if ($workflowID==""){
			$sql = "insert into envios_workflows (Empresa_ID, Tipo_Envio_ID, Cadastro_ID_de, Cadastro_ID_para, Transportadora_ID, Observacao_Financeiro, Tabela_Estrangeira, Chave_Estrangeira, Forma_Envio_ID, Codigo_Rastreamento, Valor_Transporte, Data_Envio, Data_Previsao, Data_Entrega, Data_Cadastro, Usuario_Cadastro_ID)
									   values ('$empresaID', '$tipoEnvioID', '$cadastroIDde', '$cadastroIDpara', '$cadastroIDtrans', '$observacaoFinanceiro', '$tabelaEstrangeira', '$chaveEstrangeira', '$formaEnvioID', '$codigoRastreamento', '$valorTransporte', $dataEnvio, $dataPrevisao, $dataEntrega, $dataHoraAtual,'".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$workflowID = mysql_insert_id();
			$workflowIDAux = ($dadosUserLogin['userID'] - 1000000);

			$sql = "update envios_workflows_produtos set Workflow_ID = '$workflowID' where Workflow_ID = '$workflowIDAux'";
			mpress_query($sql);

			$sql = "insert into envios_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
									   values ('$workflowID', '$descricao', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
		}
		else{
			$sql = "update envios_workflows set Empresa_ID = '$empresaID',
												Cadastro_ID_de = '$cadastroIDde',
												Cadastro_ID_para = '$cadastroIDpara',
												Tipo_Envio_ID = '$tipoEnvioID',
												Observacao_Financeiro = '$observacaoFinanceiro',
												Transportadora_ID = '$cadastroIDtrans',
												Tabela_Estrangeira = '$tabelaEstrangeira',
												Chave_Estrangeira = '$chaveEstrangeira',
												Forma_Envio_ID = '$formaEnvioID',
												Codigo_Rastreamento = '$codigoRastreamento',
												Data_Envio = $dataEnvio,
												Data_Previsao = $dataPrevisao,
												Data_Entrega = $dataEntrega,
												Valor_Transporte = '$valorTransporte',
												Cadastro_ID_para_Endereco = '$cadastroIDParaEndereco',
												Cadastro_ID_de_Endereco	  = '$cadastroIDDeEndereco',
												ID_Servico_Envio = '$radioTipoEnvio'
												where Workflow_ID = '$workflowID'";

			mpress_query($sql);

			if ($_POST['enviar-faturamento']=="1"){
				$descricao .= "<p><b>Enviada para faturamento</b><p>";
			}

			$sql = "insert into envios_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
									   values ('$workflowID', '$descricao', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);

			if ($_POST['enviar-faturamento']=="1"){
				$sql = "insert into financeiro_faturar (Tipo_ID, Empresa_ID, Cliente_Fornecedor_ID, Tabela_Estrangeira, Chave_Estrangeira, Quantidade, Valor_Unitario, Observacao, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID)
												   values ('44', '$empresaID', '$cadastroIDtrans', 'envios', '$workflowID', 1, '$valorTransporte', '$observacaoFinanceiro',$dataHoraAtual,'".$dadosUserLogin['userID']."',1)";
				mpress_query($sql);
			}
		}
		enviaEmailCD($workflowID);
		echo $workflowID;
	}



	function carregarProdutosWorkflow($workflowID, $enderecoRemetenteID, $enderecoDestinatarioID, $formaEnvioID, $tipoEnvioCorreio){
		global $dadosUserLogin, $caminhoSistema;
		if ($workflowID==""){
			$workflowID = $_POST['workflow-id'];
			$enderecoRemetenteID = $_POST['cadastro-id-de-endereco-id'];
			$enderecoDestinatarioID = $_POST['cadastro-id-para-endereco-id'];
			$formaEnvioID = $_POST['forma-envio'];
			$tipoEnvioCorreio = $_POST['radio-tipo-envio'];
		}

		$frete = mpress_query("select replace(CEP,'.','') CEP, 'De' Tipo from cadastros_enderecos where Cadastro_Endereco_ID = '".$enderecoRemetenteID."' union select replace(CEP,'.','') CEP, 'Para' Tipo from cadastros_enderecos where Cadastro_Endereco_ID = '".$enderecoDestinatarioID."'");
		while($row = mpress_fetch_array($frete))
			$cep[$row['Tipo']] = $row['CEP'];


		$resultado = mpress_query("select Situacao_ID from envios_follows where Workflow_ID = '$workflowID' order by Follow_ID desc limit 1");
		if($row = mpress_fetch_array($resultado))
			$situacaoID = $row['Situacao_ID'];


		$sql = "select Workflow_Produto_ID, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) as Descricao_Produto, ewp.Quantidade, ewp.Quantidade_Entregue, pv.Codigo as Codigo,
				cd.Nome as Autor, pv.Produto_Variacao_ID, DATE_FORMAT(ewp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, ewp.Retorna, ewp.Data_Retorno,
				ewp.Embalado as Embalado, ewp.Observacoes,
				pv.Altura as Altura, pv.Largura as Largura, pv.Comprimento as Comprimento, pv.Peso as Peso,
				ma.Nome_Arquivo as Nome_Arquivo, ew.Tipo_Envio_ID as Tipo_Envio_ID
				from envios_workflows_produtos ewp
				inner join envios_workflows ew on ew.Workflow_ID = ewp.Workflow_ID
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				inner join cadastros_dados cd on cd.Cadastro_ID = ewp.Usuario_Cadastro_ID
				left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
				where ewp.Workflow_ID = '$workflowID' and ewp.Situacao_ID = 1
				order by ewp.Data_Cadastro desc";
		//echo $sql;
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			if ($row[Nome_Arquivo]!="")
				$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
			else{
				if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1")))
					$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
			}
			$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:50px; max-height:50px' align='center' src='$nomeArquivo'/></a>";

			if($formaEnvioID=="54"){
				if($tipoEnvioCorreio!=""){
					$frete = calculaFrete($tipoEnvioCorreio,$cep['De'],$cep['Para'],(int)$row['Peso'],(int)$row['Altura'],(int)$row['Largura'],(int)$row['Comprimento']);
					$totalGeral += str_replace(',','.', substr($frete,0,strpos($frete,"|"))) * $row['Quantidade'];
					$freteValor = str_replace(',','.', substr($frete,0,strpos($frete,"|"))) * $row['Quantidade'];
					$fretePrazo = substr($frete,strpos($frete,"|")+1, 20);
					$dadosFrete = "R$ ".number_format($freteValor, 2, ",", "."). " ($fretePrazo Dias)";
					if(substr($frete,strpos($frete,"|")+1, 20)==-1) $dadosFrete = str_replace(',','.', substr($frete,0,strpos($frete,"|")));
				}
			}else if($formaEnvioID=="55"){
				$dadosFrete = "Transportadora";
			}else if($formaEnvioID==""){
				$dadosFrete = "Forma de envio n&atilde;o definida";
			}
			$escondeRetorna = "";
			$retorna = "";
			if ($row[Retorna]==1){
				$retorna = "SIM";
				$dataRetorno = substr(converteData($row[Data_Retorno]),0,10);
				if (($dataRetorno=="") || ($dataRetorno=="00/00/0000")) $dataRetorno = "N&atilde;o informado";
			}
			else{
				$retorna = "N&Atilde;O";
				$escondeRetorna = "esconde";
			}
			if ($row[Embalado]==1) $embalado = "SIM"; else $embalado = "N&Atilde;O";

			echo "	<div id='conteudo-produto-".$row[Workflow_Produto_ID]."'>
						<fieldset style='/*min-height:80px;*/ margin-bottom:2px;'>
							<!--<legend><b>".$row[Tipo]."</b></legend>-->
							<div style='float:left; width:100%'>
								<div class='titulo-secundario' style='float:left; width:05%' align='center'>$imagemProduto</div>
								<div class='titulo-secundario' style='float:left; width:35%'>
									<p>Descri&ccedil;&atilde;o:</p>
									<p><input type='text' value='".$row[Descricao_Produto]."' style='width:97%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:9%'>
									<p>Quantidade:</p>
									<p><input type='text' readonly value='".number_format($row[Quantidade], 2, ',', '.')."' style='width:85%'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:9%'>
									<p>Altura:</p>
									<p><input type='text' value='".number_format($row[Altura], 2, ',', '.')."' readonly style='width:85%'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:9%'>
									<p>Largura:</b></p>
									<p><input type='text' value='".number_format($row[Largura], 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:9%'>
									<p>Comprimento:</p>
									<p><input type='text' value='".number_format($row[Comprimento], 2, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:7%'>
									<p>Peso:</p>
									<p><input type='text' value='".number_format($row[Peso], 3, ',', '.')."' style='width:85%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:12%'>
									<p>Frete:</p>
									<p><!--<input type='text' value='$dadosFrete' style='width:99%' readonly='readonly'/>-->$dadosFrete</p>
								</div>
								<div class='titulo-secundario' style='float:right; width:05%'>
									<p>
										<div class='btn-excluir btn-excluir-produto-workflow' style='float:right; padding-right:1px' workflow-produto-id='".$row[Workflow_Produto_ID]."' title='Excluir'>&nbsp;</div>
										<div class='btn-editar btn-editar-produto-workflow' style='float:right; padding-right:1px' workflow-produto-id='".$row[Workflow_Produto_ID]."' title='Editar'>&nbsp;</div>
									</p>
								</div>
							</div>
							<div style='float:left; width:100%'>
								<div style='float:left; width:05%'>&nbsp;</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p>Retorna?</p>
									<p><input type='text' value='".$retorna."' style='width:85%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:10%'>
									<p>Embalado?</p>
									<p><input type='text' value='".$embalado."' style='width:85%' readonly='readonly'/></p>
								</div>
								<!--
								<div class='titulo-secundario' style='float:left; width:15%'>
									<div class='$escondeRetorna'>
										<p>Data Retorno:</p>
										<p><input type='text' value='".$dataRetorno."' style='width:85%' readonly='readonly'/></p>
									</div>&nbsp;
								</div>
								-->
								<div class='titulo-secundario' style='float:left; width:58%'>
									<p>Observa&ccedil;&atilde;o:</p>
									<p><input type='text' value='".$row["Observacoes"]."' style='width:97.5%' readonly='readonly'/></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:17%; margin-top:5px;'>
									<p>".$row[Autor]."</p>
									<p>".converteDataHora($row[Data_Cadastro],1)."</p>
								</div>
							</div>";
			$pendente = 0;
			if (($row['Quantidade_Entregue']<=$row['Quantidade']) && (($situacaoID=='52') || ($situacaoID=='53'))){
				if ($row['Tipo_Envio_ID'] == '129') $descricaoFluxo = "sa&iacute;da de estoque";
				if ($row['Tipo_Envio_ID'] == '130') $descricaoFluxo = "entrada em estoque";

				$pendente += ($row['Quantidade'] - $row['Quantidade_Entregue']);

				echo "		<div style='float:left; width:50%'>&nbsp;</div>
							<div style='float:left; width:50%'>
								<div style='float:left; width:50%; margin-top:10px;'><b>Status Movimenta&ccedil;&atilde;o</b></div>";
				$sql = "select DATE_FORMAT(pm.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Movimentacao, cd.Nome as Usuario, pm.Quantidade, pm.Produto_Variacao_ID
							from produtos_movimentacoes pm
							left join cadastros_dados cd on cd.Cadastro_ID = pm.Usuario_Cadastro_ID
							where pm.Chave_Estrangeira = '$workflowID' and pm.Tabela_Estrangeira = 'envios' and pm.Produto_Variacao_ID = '".$row[Produto_Variacao_ID]."'
							order by  pm.Data_Cadastro desc";
				//echo $sql;
				$im = 0;
				$intTabelas++;
				$resultSetStatus = mpress_query($sql);
				while($rs = mpress_fetch_array($resultSetStatus)){
					$im++;
					$dadosM[$intTabelas][colunas][conteudo][$im][1] = "<p style='margin:0px' align='right'>".number_format($rs[Quantidade], 2, ',', '.')."</p>";
					$dadosM[$intTabelas][colunas][conteudo][$im][2] = $rs['Data_Movimentacao'];
					$dadosM[$intTabelas][colunas][conteudo][$im][3] = $rs['Usuario'];
				}
				if ($im>0){
					$dadosM[$intTabelas][colunas][tamanho][1] = "width='100px'";
					$dadosM[$intTabelas][colunas][tamanho][2] = "width='165px'";
					$dadosM[$intTabelas][colunas][tamanho][3] = "";
					$dadosM[$intTabelas][colunas][titulo][1] 	= "<p style='margin:0px' align='right'>Quantidade</p>";
					$dadosM[$intTabelas][colunas][titulo][2] 	= "Data Movimenta&ccedil;&atilde;o";
					$dadosM[$intTabelas][colunas][titulo][3] 	= "Usu&aacute;rio Movimenta&ccedil;&atilde;o";
					geraTabela("100.2%","3",$dadosM[$intTabelas]);
				}
				else{
					echo "		<div style='color:red; margin-top:10px; float:left; width:50%;'><b>Aguardando realiza&ccedil;&atilde;o de $descricaoFluxo</b></div>";
				}
				echo "		</div>";

			}
			echo "		</fieldset>
					</div>";
			if ($pendente!=0){
				$botaoEstoque = "	<input type='button' value='Realizar $descricaoFluxo' class='atalho-estoque'/>
								<input type='hidden' id='tipo-entrada' name='tipo-entrada' value='cd'/>
								<input type='hidden' id='localiza-produto-envio-id' name='localiza-produto-envio-id' value='$workflowID'/>
								<input type='hidden' id='localiza-tipo-envio' name='localiza-tipo-envio' value='".$row['Tipo_Envio_ID']."'/>";
			}
		}
		if($i>0){
			echo "	<div style='float:left; width:68%'>&nbsp;</div>
					<div style='float:left; width:17%'>&nbsp;$botaoEstoque</div>
					<div style='float:left; width:3%'>&nbsp;</div>
					<div class='titulo-secundario' style='float:left; width:12%; margin-top:10px; background-color: #c9c9c9; border-radius:5px'>
						<p style='margin-top:5px' align='center'><b>Total Frete:</b></p>
						<p style='margin-top:5px;margin-bottom:5px' align='center'><b>R$ ".number_format($totalGeral, 2, ',', '.')."</b></p>
					</div>
					<div style='float:left; width:05%'>&nbsp;</div>";
		}
		else{
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum produto ou servi&ccedil;o cadastrado</p>";
		}

		/*
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$aux++;
			if($aux%2==0)$classe='tabela-fundo-claro'; else $classe='tabela-fundo-escuro';
			$dados[colunas][classe][$i] = $classe; $dados[colunas][classe][$i+1] = $classe;

			if($formaEnvioID=="54"){
				if($tipoEnvioCorreio!=""){
					$frete = calculaFrete($tipoEnvioCorreio,$cep['De'],$cep['Para'],(int)$row['Peso'],(int)$row['Altura'],(int)$row['Largura'],(int)$row['Comprimento']);
					$totalGeral += str_replace(',','.', substr($frete,0,strpos($frete,"|"))) * $row['Quantidade'];
					$freteValor = str_replace(',','.', substr($frete,0,strpos($frete,"|"))) * $row['Quantidade'];
					$fretePrazo = substr($frete,strpos($frete,"|")+1, 20);
					$dadosFrete = "R$ ".number_format($freteValor, 2, ",", "."). " ($fretePrazo Dias)";
					if(substr($frete,strpos($frete,"|")+1, 20)==-1) $dadosFrete = str_replace(',','.', substr($frete,0,strpos($frete,"|")));
				}
			}else if($formaEnvioID=="55"){
				$dadosFrete = "Transportadora";
			}else if($formaEnvioID==""){
				$dadosFrete = "Forma de envio n&atilde;o definida";
			}

			$qtde = $row[Quantidade];
			$dataRetorno = substr(converteData($row[Data_Retorno]),0,10);
			$dados[colunas][conteudo][$i][1] = "<p Style='margin-left:2px'>".($row[Codigo])." </p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin-left:2px'>".($row[Nome_Produto])."</p>";

			$dados[colunas][conteudo][$i][3] = "<p Style='margin:0px;' align='center'>".number_format($row[Altura], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:0px;' align='center'>".number_format($row[Largura], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:0px;' align='center'>".number_format($row[Comprimento], 2, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:0px;' align='center'>".number_format($row[Peso], 3, ',', '.')."</p>";

			$dados[colunas][conteudo][$i][7] = "<p Style='margin-left:2px;text-align:center'>$dadosFrete</p>";
			$dados[colunas][conteudo][$i][8] = "<p Style='margin-left:2px; text-align:center'>".number_format($row[Quantidade], 0, ',', '.')."</p>";
			$dados[colunas][conteudo][$i][9] = "<p Style='margin-left:2px; text-align:center;'>".($row[Usuario])."</p><p Style='margin:2px 5px 0 5px; text-align:center;'>".utf8_encode($row[Data_Cadastro])."</p>";
			$dados[colunas][conteudo][$i][10] = "<p>
													<div class='btn-editar btn-editar-produto-workflow' style='float:left;' workflow-produto-id='".$row[Workflow_Produto_ID]."' title='Editar'>&nbsp;</div>
													<div class='btn-excluir btn-excluir-produto-workflow' style='float:right;' workflow-produto-id='".$row[Workflow_Produto_ID]."' title='Excluir'>&nbsp;</div>
												</p>";
			$i++;

			$retorna = "";
			if ($row[Retorna]==1){
				if (($dataRetorno=="") || ($dataRetorno=="00/00/0000"))
					$retorna = "Retorna em: <b>*data n&atilde;o definida</b>";
				else
					$retorna = "Retorna em: <b>".substr(converteData($row[Data_Retorno]),0,10)."</b>";

				if ($row[Embalado]==1)
					$embalado = "Material Embalado: <b>SIM</b>";
				else
					$embalado = "Material Embalado: <b>N&Atilde;O</b>";
			}
			else{
				$retorna .= "N&atilde;o Retorna";
			}
			$dados[colunas][conteudo][$i][2] = "<p Style='margin-left:2px'>$retorna</p>";
			if ($row[Retorna]==1){
				$dados[colunas][conteudo][$i][2] .= "<p Style='margin-left:2px'>$embalado</p>";
				if (trim($row["Observacoes"])!="")
					$dados[colunas][conteudo][$i][2] .= "<p Style='margin-left:2px'>Observa&ccedil;&otilde;es: <b>".$row["Observacoes"]."</b></p>";
			}



		}
		$dados[colunas][titulo][1] 	= "<p Style='text-align:center;'>C&oacute;digo</p>";
		$dados[colunas][titulo][2] 	= "<p Style='text-align:center;'>Descri&ccedil;&atilde;o</p>";

		$dados[colunas][titulo][3] 	= "<p Style='text-align:center;'>Altura (cm)</p>";
		$dados[colunas][titulo][4] 	= "<p Style='text-align:center;'>Largura (cm)</p>";
		$dados[colunas][titulo][5] 	= "<p Style='text-align:center;'>Comprimento (cm)</p>";
		$dados[colunas][titulo][6] 	= "<p Style='text-align:center;'>Peso (kilos)</p>";

		$dados[colunas][titulo][7] 	= "<p Style='text-align:center;'>Frete</p>";
		$dados[colunas][titulo][8] 	= "<p Style='text-align:center;'>Qtde.</p>";
		$dados[colunas][titulo][9]  = "<p Style='text-align:center;'>Data</p>";
		$dados[colunas][titulo][10]  = "<!--<p align='center' class='link' id='btn-novo-produto' style='cursor:pointer; text-align:center;'>(+)</p>-->";
		$largura = "100%";
		$colunas = "10";

		$sql = "select Situacao_ID from chamados_follows where Workflow_ID = '$workflowID' order by Data_Cadastro desc limit 1";
		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			if(($row[Situacao_ID]==59)||($row[Situacao_ID]==53)){
				$colunas = "5";
			}
		}

		$dados[colunas][tamanho][1] = "";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "";
		$dados[colunas][tamanho][4] = "";
		$dados[colunas][tamanho][5] = "";
		$dados[colunas][tamanho][6] = "";
		$dados[colunas][tamanho][7] = "width='162px'";
		$dados[colunas][tamanho][8] = "width='80px'";
		$dados[colunas][tamanho][9] = "width='140px'";
		$dados[colunas][tamanho][10] = "width='40px'";
		if($i>0){
			$dados[colunas][conteudo][$i + 1][6] = "<p align='right'><b>TOTAL GERAL &nbsp;&nbsp;</b></p>";
			$dados[colunas][conteudo][$i + 1][7] = "<p style='background-color: #c9c9c9; height:25px;'><b id='total-geral' style='float:left; margin-top:5px;'> R$ ".number_format($totalGeral, 2, ',', '.')."</b></p>";
			geraTabela($largura,$colunas,$dados);
		}
		else{
			geraTabela($largura,$colunas,$dados);
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum produto ou servi&ccedil;o cadastrado</p>";
		}
		*/
	}


	function excluirProdutoWorkflow(){
		global $dadosUserLogin;
		$workflowProdutoID = $_GET['workflow-produto-id'];
		$sql = "update envios_workflows_produtos set Situacao_ID = 3, Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."' where Workflow_Produto_ID = '$workflowProdutoID'";
		$resultado = mpress_query($sql);
	}


	function carregarProdutoWorkflow($workflowProdutoID){
		global $caminhoSistema;

		//$escondeRetorno = "esconde";
		$textoBotaoIncAlt = "Incluir";
		$quantidade = 1;

		if ($workflowProdutoID!=""){
			$textoBotaoIncAlt = "Alterar";
			$resultado = mpress_query("SELECT Produto_Variacao_ID, Quantidade, Retorna, Embalado, Observacoes, Data_Retorno, Usuario_Cadastro_ID, Usuario_Alteracao_ID
									FROM envios_workflows_produtos where Workflow_Produto_ID = '$workflowProdutoID'");
			if ($row = mpress_fetch_array($resultado)){
				$produtoVariacaoID = $row[Produto_Variacao_ID];
				$quantidade = number_format($row[Quantidade], 0, ',', '.');
				if ($row[Retorna]=="0") {
					$selRetornoNAO = "checked";
				}
				if ($row[Retorna]=="1"){
					//$escondeRetorno = "";
					$selRetornoSIM = "checked";
					$dataRetorno = substr(converteData($row[Data_Retorno]),0,10);
					if ($dataRetorno == "00/00/0000") $dataRetorno = "";
				}
				if ($row[Embalado]=="1")
					$selEmbaladoSIM = "checked";
				else
					$selEmbaladoNAO = "checked";
				$observacoesMaterial = $row["Observacoes"];
			}
		}
		else{
			$selRetornoNAO = "checked";
			$selEmbaladoNAO = "checked";
		}

		$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo,
					CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto, pd.Produto_ID as Produto_ID,
					coalesce(pv.Altura,0) as Altura, coalesce(pv.Largura,0) as Largura, coalesce(pv.Comprimento,0) as Comprimento, coalesce(pv.Peso,0) as Peso
					FROM produtos_dados pd
					INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
					WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
					AND pd.Tipo_Produto = 30
				ORDER BY Descricao_Produto";
		//echo $sql;
		$selectProdutos = "<select id='select-produtos' name='select-produtos' Style='width:98.5%' data-placeholder='Selecione'>
								<option value='' produto-id='' altura='0' largura='0' comprimento='0' peso='0'>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row['Produto_Variacao_ID']==$produtoVariacaoID) {
				$selecionado = "selected";
				$alturaSel = $row[Altura];
				$pesoSel = $row[Peso];
				$comprimentoSel = $row[Comprimento];
				$larguraSel = $row[Largura];
			}
			else
				$selecionado = "";
			$selectProdutos .= "<option value='".$row[Produto_Variacao_ID]."'
												produto-id='".$row[Produto_ID]."'
												altura='".number_format($row[Altura], 2, ',', '.')."'
												largura='".number_format($row[Largura], 2, ',', '.')."'
												comprimento='".number_format($row[Comprimento], 2, ',', '.')."'
												peso='".number_format($row[Peso], 3, ',', '.')."'
												$selecionado>".$row['Descricao_Produto']."</option>";
		}
		$selectProdutos .= "</select>";
		echo "	<input type='hidden' name='workflow-produto-id' id='workflow-produto-id' value='$workflowProdutoID'/>
				<fieldset style='margin-top:5px;margin-bottom:5px;'>
					<div style='margin-top:5px; float:left; width:100%;'>
						<div id='div-produtos-select' style='float:left; width:50%;'>
							<p>Selecione o Produto / Servi&ccedil;o</p>
							<p>$selectProdutos</p>
						</div>
						<div style='width:25%;float:left;'>
							<p>Quantidade</p>
							<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='$quantidade' class='formata-numero' style='width:90%' maxlength='10'/></p>
						</div>
						<div id='div-produtos-incluir'  style='width:12.5%; float:left; margin-top:15px;'>
							<p><input type='button' id='botao-salvar-produto' name='botao-salvar-produto' class='botao-salvar-produto' Style='width:95%' value='$textoBotaoIncAlt'/></p>
						</div>
						<div id='div-produtos-cancelar' style='width:12.5%; float:left; margin-top:15px;'>
							<p><input type='button' value='Cancelar' id='botao-cancelar-produto' class='botao-cancelar-produto' Style='width:95%'/></p>
						</div>
					</div>
					<div id='div-detalhes-produto' style='width:100%; float:left; margin-top:3px'>";
		echo "			<div class='titulo-secundario quatro-colunas'>
							<p>Altura (cm)</p>
							<p><input type='text' id='altura-variacao' name='altura-variacao'  value='".number_format($alturaSel, 2, ',', '.')."'  class='formata-valor' maxlength='18'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Largura (cm)</p>
							<p><input type='text' id='largura-variacao' name='largura-variacao'  value='".number_format($larguraSel, 2, ',', '.')."'  class='formata-valor' maxlength='18'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Comprimento (cm)</p>
							<p><input type='text' id='comprimento-variacao' name='comprimento-variacao'  value='".number_format($comprimentoSel, 2, ',', '.')."'  class='formata-valor' maxlength='18'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Peso (kilos)</p>
							<p><input type='text' id='peso-variacao' name='peso-variacao'  value='".number_format($pesoSel, 3, ',', '.')."'  class='formata-valor-decimal-3' maxlength='18'/></p>
						</div>";
		echo "		</div>
					<div style='width:100%;float:left; margin-top:5px'>
						<div style='width:15%;float:left;'>
							<p>Retorno de Material?</p>
							<p>
								<input type='radio' class='radio-retorna' name='radio-retorna' id='radio-retorna' $selRetornoSIM value='1'/>Sim
								<input type='radio' class='radio-retorna' name='radio-retorna' id='radio-retorna' $selRetornoNAO value='0'/>N&atilde;o
							</p>
						</div>
						<div style='width:15%;float:left;'>
							<div class='envios-retorna'>
								<p>Material Embalado?</p>
								<p>
									<input type='radio' class='radio-embalado' name='radio-embalado' id='radio-embalado-1' $selEmbaladoSIM value='1'/>Sim
									<input type='radio' class='radio-embalado' name='radio-embalado' id='radio-embalado-0' $selEmbaladoNAO value='0'/>N&atilde;o
								</p>
							</div>&nbsp;
						</div>
						<!--
						<div style='width:20%;float:left;'>
							<div class='envios-retorna $escondeRetorno'>
								<p>Data Retorno</p>
								<p><input type='text' name='data-retorno' id='data-retorno' class='formata-data' value='$dataRetorno' style='width:90%'/></p>
							</div>&nbsp;
						</div>
						-->
						<div style='width:70%;float:left;' class=''>
							<p>Observa&ccedil;&otilde;es</p>
							<p><input type='text' name='observacoes-material' id='observacoes-material' class='' value='$observacoesMaterial' style='width:99%'/></p>
						</div>
					</div>
				</fieldset>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		/*
		echo "
				<div id='div-produtos-select' style='float:left; width:80%;' class='esconde'>
					<p>Produto</p>
					<p><select id='select-produtos' name='select-produtos' style='width:98.5%'><option value=''>Selecione</option></select></p>
				</div>
				<div id='div-produtos-texto' style='float:left; width:80%;'>
					<p><i>&nbsp;Localizar Produto por C&oacute;digo, Descri&ccedil;&atilde;o</i></p>
					<p><input type='text' id='texto-localizar-produtos' name='texto-localizar-produtos' style='width:98.5%'></p>
				</div>
				<div id='div-produtos-localizar'  style='width:10%; float:left;'>
					<p>&nbsp;</p>
					<p><input type='button' id='botao-localizar-produtos' name='botao-localizar-produtos' Style='width:95%' value='Localizar' ></p>
				</div>
				<div id='div-produtos-incluir'  style='width:10%; float:left;' class='esconde'>
					<p>&nbsp;</p>
					<p><input type='button' id='botao-incluir-produtos' name='botao-incluir-produtos' Style='width:95%' value='Incluir' ></p>
				</div>
				<div id='div-produtos-cancelar' style='width:10%; float:left;'>
					<p>&nbsp;</p>
					<p><input type='button' value='Cancelar' id='botao-cancelar-produtos' class='botao-cancelar-produtos' Style='width:95%'/></p>
				</div>
				<div id='div-detalhes-produto' style='width:100%; float:left;' class='esconde'></div>";
		*/
	}


	function salvarProdutoWorkflow($workflowProdutoID){
		global $dadosUserLogin;
		$produtoVariacaoID = $_POST['select-produtos'];
		$quantidadeProdutos = $_POST['quantidade-produtos'];
		$workflowID = $_POST['workflow-id'];
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i');
		$radioRetorna = $_POST['radio-retorna'];
		if ($radioRetorna=="1"){
			$radioEmbalado = $_POST['radio-embalado'];
			$dataRetorno = formataDataBD($_POST['data-retorno']);
		}
		$observacoes = $_POST['observacoes-material'];

		if ($workflowProdutoID==""){
			$sql = "insert into envios_workflows_produtos (Workflow_ID, Produto_Variacao_ID, Quantidade, Situacao_ID, Data_Cadastro, Retorna, Data_Retorno, Observacoes, Embalado, Usuario_Cadastro_ID)
					values ('$workflowID', '$produtoVariacaoID', '$quantidadeProdutos', 1, '$dataHoraAtual', '$radioRetorna','$dataRetorno', '$observacoes', '$radioEmbalado', '".$dadosUserLogin['userID']."')";
			$resultado = mpress_query($sql);
		}
		else{
			$sql = "update envios_workflows_produtos
						set Produto_Variacao_ID = '$produtoVariacaoID',
							Quantidade = '$quantidadeProdutos',
							Retorna = '$radioRetorna',
							Embalado = '$radioEmbalado',
							Data_Retorno = '$dataRetorno',
							Observacoes = '$observacoes',
							Data_Alteracao = '$dataHoraAtual',
							Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
					where Workflow_Produto_ID = '$workflowProdutoID'";
			$resultado = mpress_query($sql);
		}

		$altura 		= formataValorBD($_POST['altura-variacao']);
		$largura 		= formataValorBD($_POST['largura-variacao']);
		$comprimento 	= formataValorBD($_POST['comprimento-variacao']);
		$peso 			= formataValorBD($_POST['peso-variacao']);

		$sql = "update produtos_variacoes set Altura = '$altura', Largura= '$largura', Comprimento='$comprimento', Peso='$peso'
					where Produto_Variacao_ID = '$produtoVariacaoID'";
		mpress_query($sql);
	}


	function carregarFinanceiroEnvio($workflowID){
		global $modulosAtivos;
		if ($workflowID==""){
			$workflowID = $_POST['workflow-id'];
		}
		if ($workflowID!=""){
			$sql = "select Valor_Transporte, Observacao_Financeiro from envios_workflows where Workflow_ID = '$workflowID'";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$valorTransporte = number_format($row[Valor_Transporte], 2, ',', '.');
				$observacaoFinanceiro = $row[Observacao_Financeiro];
			}
		}
		//if ($modulosAtivos[financeiro]){
			$excluido=0;
			$faturado=0;
			$resultado = mpress_query("select ff.Situacao_ID as Situacao_ID, cd.Nome as Nome, ff.Data_Cadastro as Data_Cadastro from financeiro_faturar ff left join cadastros_dados cd on cd.Cadastro_ID = ff.Usuario_Cadastro_ID where ff.Tabela_Estrangeira = 'envios' and ff.Chave_Estrangeira = '$workflowID' order by ff.Faturar_ID");
			while($row = mpress_fetch_array($resultado)){
				if ($row[Situacao_ID]=="2") $excluido++;
				if ($row[Situacao_ID]=="1") $faturado++;
				$usuarioFaturamento = $row[Nome];
				$dataFaturamento = converteData($row[Data_Cadastro]);
				$ultimaSituacao = $row[Situacao_ID];
			}

			$situacaoFaturamento = "<div class='titulo-secundario' style='width:20%;float:left;'>
										<p><b>Situa&ccedil;&atilde;o:</b></p>
										<p>Aguardando Faturamento</p>
									</div>";
			$botaoFaturamento = "	<div class='titulo-secundario' style='width:20%;float:left;'>
										<p>&nbsp;</p>
										<p>
											<input type='button' id='botao-enviar-faturamento' style='max-width:200px' value='Enviar para Faturamento'/>
											<input type='hidden' id='enviar-faturamento' name='enviar-faturamento' value=''/>
										</p>
									</div>";

			if (($excluido>0) || ($faturado>0)){
				if ($ultimaSituacao=="2"){
					$situacaoFaturamento = "<div class='titulo-secundario' style='width:20%;float:left;'>
												<p><b>Situa&ccedil;&atilde;o:</b></p>
												<p>Cancelado - Aguardando Refaturar</p>
											</div>";
				}
				if ($ultimaSituacao=="1"){
					$situacaoFaturamento = "<div class='titulo-secundario' style='width:40%;float:left;'>
												<p><b>Situa&ccedil;&atilde;o:</b></p>
												<p>Enviado para faturamento <br> $usuarioFaturamento - $dataFaturamento</p>
											</div>";
					$botaoFaturamento = "";
					$readOnlyFinanceiro = "readonly='readonly'";
				}
			}
		//}

		echo "	<div class='titulo'>
				<p>
					Dados Financeiros
					<input type='button' class='botao-cadastra-envio' value='Atualizar Dados'>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario' id='conteudo-interno-financeiro'>
				<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Valor Transporte R$</b></p>
					<p><input type='text' name='valor-transporte' id='valor-transporte' style='width:90%;' class='formata-valor' value='$valorTransporte' $readOnlyFinanceiro/></p>
				</div>";
		echo "	$situacaoFaturamento
				$botaoFaturamento
				<div class='titulo-secundario' style='width:40%;float:left;'>
					<p><b>Observa&ccedil;&otilde;es para o Financeiro:</b></p>
					<p><textarea name='observacoes-financeiro' id='observacoes-financeiro' style='width:99%;heigth:50px'>$observacaoFinanceiro</textarea></p>
				</div>
			</div><pre>";
	}


/**/

	function envioCentroDistribuicao(){
		global $dadosUserLogin, $caminhoFisico;
		$chaveEstrangeira = $_POST['workflow-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$descricao = "Solicitação de ".$_POST['acao-cd'];
		$situacaoID = $_POST['tipo-cd'];
		if($_POST['acao-cd']=="retirada"){
			$cadastroIDde = $_POST['solicitante-id'];
			$cadastroIDpara = $_POST['cadastro-id'];
			$tipoEnvioID = "130";
		}
		else{
			$cadastroIDde = $_POST['cadastro-id'];
			$cadastroIDpara = $_POST['solicitante-id'];
			$tipoEnvioID = "129";
		}

		if($row = mpress_fetch_array(mpress_query("select Cadastro_Endereco_ID from cadastros_enderecos Where Cadastro_ID = '$cadastroIDde' and Situacao_ID = 1 order by Tipo_Endereco_ID limit 1")))
			$remetenteEnderecoID = $row[Cadastro_Endereco_ID];
		if($row = mpress_fetch_array(mpress_query("select Cadastro_Endereco_ID from cadastros_enderecos Where Cadastro_ID = '$cadastroIDpara' and Situacao_ID = 1 order by Tipo_Endereco_ID limit 1")))
			$destinatarioEnderecoID = $row[Cadastro_Endereco_ID];

		$sql = "insert into envios_workflows (Tipo_Envio_ID, Cadastro_ID_de, Cadastro_ID_de_Endereco, Cadastro_ID_para, Cadastro_ID_para_Endereco, Tabela_Estrangeira, Chave_Estrangeira, Data_Cadastro, Usuario_Cadastro_ID)
								   values ($tipoEnvioID, '$cadastroIDde', '$remetenteEnderecoID', '$cadastroIDpara', '$destinatarioEnderecoID' ,'chamados', '$chaveEstrangeira', $dataHoraAtual,'".$dadosUserLogin['userID']."')";

		mpress_query($sql);
		$workflowID = mysql_insert_id();

		for($i = 0; $i < count($_POST['check-envio']); $i++){
			$workflowProdutoID = $_POST['check-envio'][$i];
			$retorna = $_POST['radio-retorna-'.$workflowProdutoID];
			$embalado = $_POST['radio-embalado-'.$workflowProdutoID];
			$observacao = $_POST['observacao-envio-'.$workflowProdutoID];
			/*
			if (($retorna=="1") && ($_POST['data-retorno-'.$workflowProdutoID]!=""))
				$dataRetorno = "'".formataDataBD($_POST['data-retorno-'.$workflowProdutoID])."'";
			else
				$dataRetorno = "NULL";
			*/
			if($row = mpress_fetch_array(mpress_query("select Produto_Variacao_ID, Quantidade from chamados_workflows_produtos where Workflow_ID = '$chaveEstrangeira' and Workflow_Produto_ID = '$workflowProdutoID'"))){
				$sql = "insert into envios_workflows_produtos (Workflow_ID, Produto_Variacao_ID, Quantidade, Retorna, Embalado, Observacoes, Data_Cadastro, Usuario_Cadastro_ID)
											values ($workflowID, ".$row[Produto_Variacao_ID]." ,".$row[Quantidade].", '$retorna', '$embalado', '$observacao', $dataHoraAtual, ".$dadosUserLogin['userID'].")";
				mpress_query($sql);
			}
		}

		$sql = "insert into envios_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
								   values ('$workflowID', '$descricao', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		mpress_query($sql);
		enviaEmailCD($workflowID);
	}


/**/

	function calculaFrete($cod_servico, $cep_origem, $cep_destino, $peso, $altura='2', $largura='11', $comprimento='16', $valor_declarado='0.50'){
		global $cidadeCep;
		global $ufCep;
	    $correios = "http://ws.correios.com.br/calculador/CalcPrecoPrazo.aspx?nCdEmpresa=&sDsSenha=&sCepOrigem=".$cep_origem."&sCepDestino=".$cep_destino."&nVlPeso=".$peso."&nCdFormato=1&nVlComprimento=".$comprimento."&nVlAltura=".$altura."&nVlLargura=".$largura."&sCdMaoPropria=n&nVlValorDeclarado=".$valor_declarado."&sCdAvisoRecebimento=n&nCdServico=".$cod_servico."&nVlDiametro=0&StrRetorno=xml";
		$ch = curl_init($correios);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		$xml = new SimpleXmlElement($data, LIBXML_NOCDATA);

	    if($xml->cServico->MsgErro == '')
	        return $xml->cServico->Valor."|".$xml->cServico->PrazoEntrega;
		else
	        return $xml->cServico->MsgErro."|-1";
	}


	function enviaEmailCD($workflowID){
		global $dadosUserLogin, $caminhoSistema, $configEnvios;

		$sql = "select ew.Empresa_ID, ew.Cadastro_ID_de, ew.Cadastro_ID_para, ew.Transportadora_ID,
					ew.Tabela_Estrangeira, ew.Chave_Estrangeira, ew.Forma_Envio_ID, ew.Codigo_Rastreamento,
					coalesce(DATE_FORMAT(ew.Data_Cadastro, '%d/%m/%Y %H:%i'),'Não Infomado') as Data_Cadastro,
					coalesce(DATE_FORMAT(ew.Data_Envio, '%d/%m/%Y %H:%i'),'Não Infomado') as Data_Envio,
					coalesce(DATE_FORMAT(ew.Data_Previsao, '%d/%m/%Y %H:%i'),'Não Infomado') as Data_Previsao,
					coalesce(DATE_FORMAT(ew.Data_Entrega, '%d/%m/%Y %H:%i'),'Não Infomado') as Data_Entrega,
					cds.Nome as Solicitante, cds.Email as Email_Solicitante,
					coalesce(te.Descr_Tipo,'Não Infomado') as Tipo_Envio,
					coalesce(fe.Descr_Tipo,'Não Infomado') as Forma_Envio
					from envios_workflows ew
					left join tipo te on te.Tipo_ID = ew.Tipo_Envio_ID
					left join tipo fe on fe.Tipo_ID = ew.Forma_Envio_ID
					left join cadastros_dados cds on cds.Cadastro_ID = ew.Usuario_Cadastro_ID
					where ew.Workflow_ID = $workflowID";
		//echo $sql;

		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			$empresaID	 			= $row['Empresa_ID'];
			$solicitante				= $row['Solicitante'];
			$tipoEnvio	 			= $row['Tipo_Envio'];
			$tabelaEstrangeira 		= $row['Tabela_Estrangeira'];
			$chaveEstrangeira 		= $row['Chave_Estrangeira']; if ($chaveEstrangeira==0) $chaveEstrangeira = "";
			if ($tabelaEstrangeira=="chamados"){
				if($row2 = mpress_fetch_array(mpress_query("select Codigo from chamados_workflows where Workflow_ID = '$chaveEstrangeira'")))
					$codigoEstrangeira = $row2[Codigo];
			}
			$formaEnvio 			= $row[Forma_Envio];
			$dataEnvio 			= $row[Data_Envio]; if ($dataEnvio == "00/00/0000 00:00") $dataEnvio = "";
			$dataPrevisaoEntrega 	= $row[Data_Previsao]; if ($dataPrevisaoEntrega == "00/00/0000 00:00") $dataPrevisaoEntrega = "";
			$dataEntrega 			= $row[Data_Entrega]; if ($dataEntrega == "00/00/0000 00:00") $dataEnvio = "";
			$dataCadastro 			= $row[Data_Cadastro]; if ($dataCadastro == "00/00/0000 00:00") $dataCadastro = "";
			$emailSolicitante 		= $row['Email_Solicitante'];
			/**/

			$sql = "Select Follow_ID, Descricao, t.Descr_Tipo as Situacao, ef.Situacao_ID as Situacao_ID,
					DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, cd.Nome as Usuario_Follow
					from envios_follows ef
					left join tipo t on t.Tipo_ID = ef.Situacao_ID
					inner join cadastros_dados cd on cd.Cadastro_ID = ef.Usuario_Cadastro_ID
					where Workflow_ID = $workflowID
					order by ef.Follow_ID desc ";
			$query = mpress_query($sql);
			$i=0;
			while($rs = mpress_fetch_array($query)){
				$i++;
				if ($i==1){
					$situacaoAtualID = $rs['Situacao_ID'];
					$situacaoAtual = $rs['Situacao'];
				}
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Descricao']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
			}
			if($i>=1){
				$dados[colunas][titulo][1] 	= "Observa&ccedil;&atilde;o";
				$dados[colunas][tamanho][1] = "width=''";
				$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
				$dados[colunas][tamanho][2] = "width='180px'";
				$dados[colunas][titulo][3] 	= "Data";
				$dados[colunas][tamanho][3] = "width='180px'";
			}

			$h .= "	<div class='titulo'>
						<p><b>Centro de Distribui&ccedil;&atilde;o</b></p>
					</div>
					<table border='0' cellpadding='0' cellspacing='0' style='float:left; width:100%; margin-bottom:2px; border-radius: 5px; border:1px solid silver; padding:1.5px;' class='tabela-fundo-escuro'>
						<tr>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>ID</b></p>
								<p>$workflowID</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Tipo</b></p>
								<p>$tipoEnvio</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Forma de Envio</b></p>
								<p>$formaEnvio</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Data Solicitação</b></p>
								<p>$dataCadastro</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Solicitante</b></p>
								<p>$solicitante</p>
							</td>
						</tr>
						<tr>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Data Envio</b></p>
								<p>$dataEnvio</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Previsão entrega</b></p>
								<p>$dataPrevisaoEntrega</p>
							</td>
							<td class='titulo-secundario' style='width:20%;float:left;'>
								<p><b>Data Entrega</b></p>
								<p>$dataEntrega</p>
							</td>
							<td class='titulo-secundario' colspan='2' style='width:40%;float:left;'>
								<p><b>Origem</b></p>
								<p>".$_SESSION['objeto'].": $chaveEstrangeira - $codigoEstrangeira</p>
							</td>
						</tr>
						<tr>
							<td class='titulo-secundario' colspan='5' style='width:100%;float:left; margin-top:15px; margin-bottom:15px;'>
								<p align='center'><b>Situa&ccedil;&atilde;o Atual</b></p>
								<p align='center'>$situacaoAtual</p>
							</td>
						</tr>
					</table><br>";

			/**/


			/**/
			$sql = "select Workflow_Produto_ID, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) as Descricao_Produto, ewp.Quantidade, pv.Codigo as Codigo,
						cd.Nome as Autor, pv.Produto_Variacao_ID, DATE_FORMAT(ewp.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, ewp.Retorna, ewp.Data_Retorno,
						ewp.Embalado as Embalado, ewp.Observacoes,
						pv.Altura as Altura, pv.Largura as Largura, pv.Comprimento as Comprimento, pv.Peso as Peso,
						ma.Nome_Arquivo as Nome_Arquivo
						from envios_workflows_produtos ewp
						inner join produtos_variacoes pv on pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
						inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
						inner join cadastros_dados cd on cd.Cadastro_ID = ewp.Usuario_Cadastro_ID
						left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
						where Workflow_ID = '$workflowID' and ewp.Situacao_ID = 1
						order by ewp.Data_Cadastro desc";
			$resultado = mpress_query($sql);
			$i = 0;
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
				if ($row[Nome_Arquivo]!="")
					$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
				else{
					if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1")))
						$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
				}
				$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:50px; max-height:50px' align='center' src='$nomeArquivo'/></a>";
				$retorna = "";
				if ($row[Retorna]==1){
					$retorna = "SIM";
					$dataRetorno = substr(converteData($row[Data_Retorno]),0,10);
					if (($dataRetorno=="") || ($dataRetorno=="00/00/0000")) $dataRetorno = "N&atilde;o informado";
				}
				else{
					$retorna = "N&Atilde;O";
					$escondeRetorna = "esconde";
				}
				if ($row[Embalado]==1) $embalado = "SIM"; else $embalado = "N&Atilde;O";
				$h .=  "	<table border='0' cellpadding='0' cellspacing='0' style='float:left; width:100%; margin-bottom:2px; border-radius: 5px; border:1px solid silver; padding:1.5px;' class='tabela-fundo-escuro'>
							<tr>
								<td rowspan='2' style='width:20%; height:100px;' align='center'>$imagemProduto</td>
								<td style='float:left; width:65%' colspan='5'>
										<p><b>Descri&ccedil;&atilde;o:</b></p>
										<p>".$row[Descricao_Produto]."</p>
								</td>
								<td class='titulo-secundario' style='width:15%'>
									<p><b>Quantidade:</b></p>
									<p>".number_format($row[Quantidade], 2, ',', '.')."</p>
								</td>
							</tr>
							<tr>
								<td class='titulo-secundario'>
									<p><b>Retorna?</b></p>
									<p>".$retorna."</p>
								</td>
								<td class='titulo-secundario'>
									<p><b>Embalado?</b></p>
									<p>".$embalado."</p>
								</td>
								<td class='titulo-secundario'>
									<p><b>Altura:</b></p>
									<p>".number_format($row[Altura], 2, ',', '.')."</p>
								</td>
								<td class='titulo-secundario' >
									<p><b>Largura:</b></p>
									<p>".number_format($row[Largura], 2, ',', '.')."</p>
								</td>
								<td class='titulo-secundario'>
									<p><b>Comprimento:</b></p>
									<p>".number_format($row[Comprimento], 2, ',', '.')."</p>
								</td>
								<td class='titulo-secundario'>
									<p><b>Peso:</b></p>
									<p>".number_format($row[Peso], 3, ',', '.')."</p>
								</td>
							</tr>
							<tr>
								<td class='titulo-secundario' colspan='3'>
									<p><b>Observa&ccedil;&atilde;o:</b></p>
									<p>".$row["Observacoes"]."</p>
								</td>
								<td class='titulo-secundario' colspan='3'>
									<p><b>".$row[Autor]."</b></p>
									<p>".converteDataHora($row[Data_Cadastro],1)."</p>
								</td>
							</tr>
						</table>
						<br>";
			}
			$h .=  geraTabela('100%',3,$dados,'float:left; width:100%; margin-bottom:2px; border-radius: 5px; border:1px solid silver; padding:1.5px;','',2,2,'','', 'return');
		}
		$conteudo = geraEmailPadrao($h);
		$titulo = "CD Logistica - ID $workflowID - $situacaoAtual";
		$emailsGeral = $dadosUserLogin['email']; if ($configEnvios['emails-copia-envio']!="") $emailsGeral.= ";".$configEnvios['emails-copia-envio'].";".$emailSolicitante;
		enviaEmails($emailsGeral, $titulo, utf8_encode($conteudo), "<p>Envio efetuado com successo</p>");
	}

	function emissaoNFe(){
		echo "<center><br><br><br> REALIZAR CONFIGURAÇÃO BASICA PARA REALIZAR PROCEDIMENTO</center> <br><br><br><br>";
	}
?>