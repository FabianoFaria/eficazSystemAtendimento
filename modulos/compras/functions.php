<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");
	/*
	function localizaProdutoSelect(){
		$descricao = $_POST['texto-localizar-produtos'];
		if ($descricao!=""){
			$sqlCond = " and (pd.Nome like '%$descricao%' or pv.codigo like '%$descricao%' or descricao_resumida like '%$descricao%' or pv.Descricao like '%$descricao%') ";
		}
		$sql = "select  pv.Produto_Variacao_ID as Produto_Variacao_ID, pd.Codigo as Codigo, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Nome
								from produtos_dados pd
								inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
								where pd.Situacao_ID = 1 and pv.Situacao_ID = 1
								and Tipo_Produto in (30)
								$sqlCond ";
		echo "<div style='float:left; width:70%;'>
					<p><b>Selecione o Produto:</b></p>
					<select id='select-produtos' name='select-produtos' Style='width:98.5%'>
						<option value=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			echo "		<option value='".$row[Produto_Variacao_ID]."'>".utf8_encode($row[Codigo])."&nbsp;-&nbsp;".utf8_encode($row[Nome])."</option>";
		}
		echo "		</select>
					</div>
					<div style='float:left;width:30%;'>
						<p><b>Quantidade</b></p>
						<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='1,00' class='formata-valor' style='width:90%' maxlength='10'/></p>
					</div>
					<script>
						$('.formata-valor').maskMoney({
							allowNegative: false, thousands:'.', decimal:',', affixesStay: false
						});
					</script>
				</p>";
	}
	*/

	function salvarRequisicao(){
		global $dadosUserLogin;
		if ($_POST['solicitacao-id']==""){
			$produtoVariacaoID = $_POST['select-produtos'];
			$quantidade = str_replace(",",".",str_replace(".","",$_POST['quantidade-produtos']));
			$dataHoraAtual = retornaDataHora('','Y-m-d H:i');
			mpress_query("insert into compras_solicitacoes (Produto_Variacao_ID, Tabela_Estrangeira, Chave_Estrangeira, Quantidade, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
													values ('$produtoVariacaoID', 'compras', 0, $quantidade, 60, '$dataHoraAtual', '".$dadosUserLogin['userID']."')");
		}
	}

	function excluirProdutoOrdemCompra(){
		$produtoVaricaoID  = $_POST['aux-produto-variacao-id'];
		$ordemCompraID = $_POST['ordem-compra-id'];
		$resultado = mpress_query("select p.Compra_Solicitacao_ID from compras_solicitacoes s inner join compras_ordens_compras_produtos p on p.Compra_Solicitacao_ID = s.Compra_Solicitacao_ID
							where p.Ordem_Compra_ID = $ordemCompraID and s.Produto_Variacao_ID = $produtoVaricaoID");
		while($rs = mpress_fetch_array($resultado)){
			mpress_query("update compras_solicitacoes set Situacao_ID = 60 where Compra_Solicitacao_ID = ".$rs['Compra_Solicitacao_ID']);
			mpress_query("delete from compras_ordens_compras_produtos where Compra_Solicitacao_ID = ".$rs['Compra_Solicitacao_ID']);
		}
		mpress_query("delete oc.* from compras_ordem_compra oc
						left join compras_ordens_compras_produtos ocp on ocp.Ordem_Compra_ID = oc.Ordem_Compra_ID
						where ocp.Ordens_Compras_Produtos_ID is null");
	}

	function excluirOrdemCompra(){
		$ordemCompraID = $_POST['ordem-compra-id'];
		$resultado = mpress_query("select p.Compra_Solicitacao_ID from compras_solicitacoes s inner join compras_ordens_compras_produtos p on p.Compra_Solicitacao_ID = s.Compra_Solicitacao_ID
							where p.Ordem_Compra_ID = $ordemCompraID");
		while($rs = mpress_fetch_array($resultado)){
			mpress_query("update compras_solicitacoes set Situacao_ID = 60 where Compra_Solicitacao_ID = ".$rs['Compra_Solicitacao_ID']);
		}
		mpress_query("delete from compras_ordens_compras_produtos where Ordem_Compra_ID = $ordemCompraID");
		mpress_query("delete oc.* from compras_ordem_compra oc
						left join compras_ordens_compras_produtos ocp on ocp.Ordem_Compra_ID = oc.Ordem_Compra_ID
						where ocp.Ordens_Compras_Produtos_ID is null");
	}


	function atualizarSituacaoSolicitacoes($ordemCompraID, $situacaoID){
		$resultado = mpress_query("select p.Compra_Solicitacao_ID from compras_solicitacoes s inner join compras_ordens_compras_produtos p on p.Compra_Solicitacao_ID = s.Compra_Solicitacao_ID where p.Ordem_Compra_ID = '$ordemCompraID'");
		while($rs = mpress_fetch_array($resultado)){
			mpress_query("update compras_solicitacoes set Situacao_ID = '$situacaoID' where Compra_Solicitacao_ID = ".$rs['Compra_Solicitacao_ID']);
		}
	}

	function salvarOrdemCompra(){
		global $dadosUserLogin, $caminhoSistema, $tituloSistema;
		$ordemCompraID = $_POST['ordem-compra-id'];
		$observacaoFollow = $_POST['observacao-follow'];
		$observacaoFinanceiro = utf8_decode($_POST['observacao-financeiro']);

		$cadastroID = $_POST['cadastro-id'];
		$dataLimiteRetorno  = implode('-',array_reverse(explode('/',$_POST['data-limite-retorno'])));
		if ($dataLimiteRetorno=="") $dataLimiteRetorno = "NULL"; else $dataLimiteRetorno = "'$dataLimiteRetorno 00:00'";
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i')."'";

		$sql = " update compras_ordem_compra set
						Cadastro_ID = '$cadastroID',
						Data_Alteracao = $dataHoraAtual,
						Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."',
						Data_Limite_Retorno = $dataLimiteRetorno,
						Observacao = '$observacaoFinanceiro'
						where Ordem_Compra_ID = '$ordemCompraID'";
		mpress_query($sql);

		if ($_POST['botao-clicado']=='botao-salvar-oc'){
			for($i=0;$i<count($_POST["ordemCompraProdutoID"]);$i++){
				$array[QuantidadeAprovada] = formataValorBD($_POST["txtQuantidadeAprovada"][$i]);
				$array[ValorAprovado] = formataValorBD($_POST["txtValorProduto"][$i]);
				$array[Observacao] = utf8_decode($_POST["observacaoCompraProd"][$i]);
				$array[Fornecedor] = $_POST["slcFornecedor"][$i];
				$sql = "update compras_ordens_compras_produtos set Dados = '".serialize($array)."' where Ordens_Compras_Produtos_ID = '".$_POST["ordemCompraProdutoID"][$i]."'";
				mpress_query($sql);
			}

			$resultado = mpress_query("select Situacao_ID from compras_ordem_compra_follows where Ordem_Compra_ID = '$ordemCompraID' order by Ordem_Compra_Follow_ID desc limit 1");
			if($rs = mpress_fetch_array($resultado))
			$ultimaSituacaoID = $rs[Situacao_ID];
			mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow <p><b>Ordem de compra atualizada</b></p>','$ultimaSituacaoID','".$dadosUserLogin['userID']."')");
		}

		if ($_POST['botao-clicado']=='botao-enviar-orcamento'){
			mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','61','".$dadosUserLogin['userID']."')");
			atualizarSituacaoSolicitacoes($ordemCompraID, "61");

			$query = mpress_query("	select cd.Cadastro_ID, cd.Nome, cd.Email
									from compras_ordem_compra c
									inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
									inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
									inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
									inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
									inner join produtos_fornecedores f on f.Produto_ID = pv.Produto_ID
									inner join cadastros_dados cd on cd.Cadastro_ID = f.Cadastro_ID
									where data_limite_retorno >= now() and c.Situacao_ID = 1 and cs.Situacao_ID = 61 and p.Situacao_ID = 1 and cd.Situacao_ID = 1
									and c.Ordem_Compra_ID  not in (select Ordem_Compra_ID from compras_ordens_compras_orcamentos) and c.Ordem_Compra_ID = $ordemCompraID");
			while($row = mpress_fetch_array($query)){
				$mensagem =" <html xmlns='http://www.w3.org/1999/xhtml'>
								<head>
								<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
								</head>
								<body style='margin: 0 auto;background-color:#fafafa'>
								<table border='0' align='center' cellpadding='0' cellspacing='0' id='mwn'>
									<tr>
										<td width='708' height='24' align='center'></td>
									</tr>
									<tr>
										<td width='708' height='111' align='center'><a href='#' target='_blank'><img src='http://www.meuwebsitenovo.com.br/wp-content/images/layout/topo-emailrr.jpg' width='708' height='138' border='0' id='r&r' style='display: block' /></a>
										</td>
									</tr>
									<tr>
										<td>
											<table width='708' border='0' align='center' cellpadding='0' cellspacing='0' >
												<tr>
													<td height='130' align='left'><p style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:normal; font-size:13px; color:#222222; margin:21px'>Prezado ".$row['Nome'].",<br>
													  voc&ecirc; est&aacute; recebendo uma solicita&ccedil;&atilde;o de or&ccedil;amento.<br>
													  Para respond&ecirc;-la, favor clicar abaixo e preencher as informa&ccedil;&otilde;es solicitadas.</p></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align='center'><br><table width='410' border='0' cellspacing='0' cellpadding='1' style='margin-left:21px'>
										  <tr>
											<td width='410' height='39' bgcolor='#f4da01' style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:bold; font-size:13px; color:#222222; margin:21px'>
												<a href='".$caminhoSistema."/?oc=".md5($row[Cadastro_ID])."' target='_blank' style='color: #222222; text-decoration: none'>
													<span style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:bold; font-size:13px; color:#222222; margin:21px' >
														Para responder &agrave; solicita&ccedil;&atilde;o de or&ccedil;amento, clique aqui
													</span>
												</a>
											</td>
										  </tr>
										</table></td>
									</tr>
								</table>
							</body>
						</html>";
						enviaEmail($row[Email],utf8_encode($row[Nome]),$tituloSistema." - Solicitação de orçamento",$mensagem,"<font color=red>Ordem de Compra $ordemCompraID enviada com sucesso.</font>");
			}
		}
	}


	/* ROTINA PARA ENVIAR PARA APROVAÇÂO OU FINALIZAR DIRETO SEM APROVACAO*/
	function atualizarOrdemCompra(){
		global $dadosUserLogin, $caminhoSistema, $tituloSistema;
		$ordemCompraID = $_POST['ordem-compra-id'];
		$cadastroID	= $_POST['cadastro-id'];
		$observacaoFollow = utf8_decode($_POST['observacao-follow']);
		$observacaoFinanceiro = utf8_decode($_POST['observacao-financeiro']);
		$enviarEmail 	= $_POST['enviar-email'];
		$emailsEnvio 	= $_POST['emails-envio'];

		mpress_query("update compras_ordem_compra set Cadastro_ID = '$cadastroID', Observacao = '$observacaoFinanceiro' where Ordem_Compra_ID = '$ordemCompraID'");

		for($i=0;$i<count($_POST["ordemCompraProdutoID"]);$i++){
			$array[QuantidadeAprovada] = formataValorBD($_POST["txtQuantidadeAprovada"][$i]);
			$array[ValorAprovado] = formataValorBD($_POST["txtValorProduto"][$i]);
			$array[Observacao] = utf8_decode($_POST["observacaoCompraProd"][$i]);
			$array[Fornecedor] = $_POST["slcFornecedor"][$i];
			mpress_query("update compras_ordens_compras_produtos set Dados = '".serialize($array)."' where Ordens_Compras_Produtos_ID = '".$_POST["ordemCompraProdutoID"][$i]."'");
		}

		if ($_POST['botao-clicado']=="botao-enviar-aprovacao"){
			$situacaoID = "64";
			mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','64','".$dadosUserLogin['userID']."')");
			atualizarSituacaoSolicitacoes($ordemCompraID, $situacaoID);
		}


		if ($_POST['botao-clicado']=='botao-aprovar-oc'){
			$situacaoID = "104";
			mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','104','".$dadosUserLogin['userID']."')");
			atualizarSituacaoSolicitacoes($ordemCompraID, $situacaoID);
		}

		if ($_POST['botao-clicado']=='botao-reprovar-oc'){
			$situacaoID = "105";
			mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','105','".$dadosUserLogin['userID']."')");
			atualizarSituacaoSolicitacoes($ordemCompraID, $situacaoID);
		}

		if ($_POST['botao-clicado']=="botao-finalizar-oc"){
			$ordemCompraID 	= $_POST['ordem-compra-id'];
			$produtoID		= $_POST['ordemCompraProdutosID'];
			$quantidade		= $_POST['txtQuantidadeAprovada'];
			$valor			= $_POST['txtValorProduto'];
			$fornecedor		= $_POST['slcFornecedor'];
			$userID 		= $dadosUserLogin['userID'];
			$situacaoID		= "65";

			$dataLimiteRetorno  = implode('-',array_reverse(explode('/',$_POST['data-limite-retorno'])));
			if ($dataLimiteRetorno=="") $dataLimiteRetorno = "NULL";
			else $dataLimiteRetorno = "'$dataLimiteRetorno 00:00'";
			$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i')."'";
			mpress_query("update compras_ordem_compra set Cadastro_ID = '$cadastroID', Data_Alteracao = $dataHoraAtual, Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."', Data_Limite_Retorno = $dataLimiteRetorno where Ordem_Compra_ID = '$ordemCompraID'");

			for($i=0;$i<$_POST['cont-prod'];$i++)
				mpress_query("insert into compras_ordem_compras_finalizadas(Ordem_Compra_ID, Ordem_Compra_Produto_ID, Quantidade_Aprovada, Valor_Aprovado, Usuario_Cadastro_ID, Fornecedor_ID)values('$ordemCompraID','".$produtoID[$i]."','".str_replace(",",".",str_replace(".","",$quantidade[$i]))."','".str_replace(",",".",str_replace(".","",$valor[$i]))."','$userID','".$fornecedor[$i]."')");

			mpress_query("insert into compras_ordem_compra_follows(Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','$situacaoID','$userID')");
			atualizarSituacaoSolicitacoes($ordemCompraID, $situacaoID);
		}
		if (($enviarEmail!="")&&($emailsEnvio!="")){
			enviarEmailOrdemCompra($ordemCompraID, $situacaoID, $_POST['observacao-follow'], $emailsEnvio);
		}
	}

	function reabrirOrdemCompra(){
		global $dadosUserLogin;
		$ordemCompraID 	= $_POST['ordem-compra-id'];
		$userID 		= $dadosUserLogin['userID'];
		$observacaoFollow = utf8_decode($_POST['observacao-follow']);
		$situacaoID = "62";
		$enviarEmail 	= $_POST['enviar-email'];
		$emailsEnvio 	= $_POST['emails-envio'];

		mpress_query("insert into compras_ordem_compra_follows(Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$ordemCompraID','$observacaoFollow','62','$userID')");
		atualizarSituacaoSolicitacoes($ordemCompraID, "62");
		mpress_query("delete from compras_ordem_compras_finalizadas where Ordem_Compra_ID = '$ordemCompraID'");

		if (($enviarEmail!="")&&($emailsEnvio!="")){
			enviarEmailOrdemCompra($ordemCompraID, $situacaoID, $_POST['observacao-follow'], $emailsEnvio);
		}
	}

	function repetirOrdemCompra(){
		global $dadosUserLogin;
		$ordemCompraID 	= $_POST['ordem-compra-id'];
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$userID = $dadosUserLogin['userID'];
		$cadastroID = $_POST['cadastro-id'];

		$virgula = "";

		mpress_query("insert into compras_ordem_compra (Cadastro_ID, Usuario_Cadastro_ID) values ('$cadastroID','".$userID."')");
		$novaOrdemCompra = mpress_identity();
		mpress_query("insert into compras_ordem_compra_follows (Ordem_Compra_ID, Descricao, Situacao_ID, Usuario_Cadastro_ID)values('$novaOrdemCompra','Gerado Ordem de Compra','63','".$userID."')");

		$resultado = mpress_query("select cs.Produto_Variacao_ID, cf.Quantidade_Aprovada from compras_ordem_compra c
									inner join compras_ordens_compras_produtos cp on c.Ordem_Compra_ID = cp.Ordem_Compra_ID
									inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
									inner join compras_ordem_compras_finalizadas cf on cf.Ordem_Compra_ID = c.Ordem_Compra_ID and cf.Ordem_Compra_Produto_ID = cp.Ordens_Compras_Produtos_ID
									where c.Ordem_Compra_ID = '$ordemCompraID'");
		while($rs = mpress_fetch_array($resultado)){
			mpress_query("insert into compras_solicitacoes (Tabela_Estrangeira, Chave_Estrangeira, Produto_Variacao_ID, Quantidade, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID)
													values ('compras','".$ordemCompraID."',".$rs[Produto_Variacao_ID].",".$rs[Quantidade_Aprovada].", '".$dataHoraAtual."', ".$userID.", 63)");
			$compraSolicitacaoID = mpress_identity();
			mpress_query("insert into compras_ordens_compras_produtos (Ordem_Compra_ID, Compra_Solicitacao_ID) values ('".$novaOrdemCompra."', ".$compraSolicitacaoID.")");
		}
		echo $novaOrdemCompra;
	}



	function enviarEmailOrdemCompra($ordemCompraID, $situacaoID, $observacaoFollow, $emailsEnvio){
		global $dadosUserLogin, $caminhoSistema, $tituloSistema;
		$i = 0;
		/*
		$emails = explode(";", $emailsEnvio);
		foreach ($emails as $email) {
			if ($email!=""){
				$i++;
				$dadosEmail[email][$i] = $email;
				$dadosEmail[nome][$i] = $email;
			}
		}
		*/
		$resultado = mpress_query("select Descr_Tipo from tipo where Tipo_ID = $situacaoID");
		if($rs = mpress_fetch_array($resultado))
			$descrSituacao = utf8_encode($rs[Descr_Tipo]);
		$titulo = $tituloSistema." - Ordem de Compra: ".$ordemCompraID;
		$conteudoEmail = "
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
			</head>
			<body style='margin: 0 auto; background-color:#ffffff'>
				<table border='0' align='center' cellpadding='0' cellspacing='0'>
					<tr>
						<td width='735' height='80' align='center'>
							<img src='".$caminhoSistema."/images/documentos/cabecalho.jpg' border='0' id='r&r' style='display: block' />
						</td>
					</tr>
					<tr>
						<td>
							<table width='735' border='0' align='center' cellpadding='5' cellspacing='0' style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight: normal; font-size:13px; color:#222222; margin:21px; border:0px;'>
								<tr>
									<td align='left' valign='top' width='160' style='border-bottom:1px solid #cccccc;'><b>Ordem de Compra:</b></td>
									<td align='left' valign='top' width='575' style='border-bottom:1px solid #cccccc;'>$ordemCompraID</td>
								</tr>
								<tr>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Situa&ccedil;&atilde;o:</b></td>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>$descrSituacao</td>
								</tr>
								<tr>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Observa&ccedil;&otilde;es:</b></td>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".nl2br($observacaoFollow)."</td>
								</tr>
								<tr>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'><b>Usu&aacute;rio:</b></td>
									<td align='left' valign='top' style='border-bottom:1px solid #cccccc;'>".$dadosUserLogin['nome']."</td>
								</tr>
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
		echo enviaEmails($emailsEnvio, $titulo, $conteudoEmail, "<p>Envio efetuado com successo</p>","");
	}

	function atualizarNFEMovimentacao(){
		$pmID = $_GET['pmID'];
		$nf = trim($_GET['nf']);
		mpress_query("update produtos_movimentacoes set Nota_Fiscal = '$nf' where Produto_Movimentacao_ID = '$pmID'");
	}

?>