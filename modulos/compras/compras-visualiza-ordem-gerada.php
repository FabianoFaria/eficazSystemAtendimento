<?php
	global $modulosAtivos, $caminhoSistema, $dadosUserLogin;
	$contEmpresas = verificaNumeroEmpresas();
	$qtdTitulos = 0;

	$ordemCompraID = $_POST['ordem-compra-id'];
	$rsFinalizada = mpress_fetch_array(mpress_query("select Ordem_Compra_Follow_ID from compras_ordem_compra_follows where Ordem_Compra_Follow_ID = (select max(Ordem_Compra_Follow_ID) from compras_ordem_compra_follows where Ordem_Compra_ID = $ordemCompraID) and Situacao_ID = 65"));
	$finalizada = $rsFinalizada[Ordem_Compra_Follow_ID] ;
	$flagNaoMostraReabrir = 0;
	if($finalizada != ""){
		$rs = mpress_query("select Ordem_Compra_Produto_ID, Quantidade_Aprovada, Quantidade_Entregue, Valor_Aprovado, Fornecedor_ID from compras_ordem_compras_finalizadas where Ordem_Compra_ID = $ordemCompraID");
		while($row = mpress_fetch_array($rs)){
			$ordemCompra[$row[Ordem_Compra_Produto_ID]][quantidade] = $row[Quantidade_Aprovada];
			$ordemCompra[$row[Ordem_Compra_Produto_ID]][quantidadeEntregue] = $row[Quantidade_Aprovada];
			$ordemCompra[$row[Ordem_Compra_Produto_ID]][valor] 		= $row[Valor_Aprovado];
			$ordemCompra[$row[Ordem_Compra_Produto_ID]][fornecedor]	= $row[Fornecedor_ID];
			if ($row[Quantidade_Entregue]>0) $flagNaoMostraReabrir = 1;
		}
		$sql = "select count(*) as Qtd_Titulos from financeiro_produtos fp
							inner join financeiro_titulos ft on ft.Conta_ID = fp.Conta_ID
							where fp.Tabela_Estrangeira = 'compras' and fp.Chave_Estrangeira = $ordemCompraID";
		//echo $sql;
		$query = mpress_query($sql);
		if ($rs = mpress_fetch_array($query)){
			$qtdTitulos = $rs[Qtd_Titulos];
		}
	}
	$sql = "select DATE_FORMAT(coc.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Cadastro, DATE_FORMAT(coc.Data_Alteracao,'%d/%m/%Y %H:%i') as Data_Alteracao,
				DATE_FORMAT(coc.Data_Limite_Retorno,'%d/%m/%Y') as Data_Limite_Retorno, coc.Cadastro_ID,
				cd.Usuario_Cadastro_ID, cd.Nome, cf.Situacao_ID as Situacao_ID, s.Descr_Tipo as Situacao, coc.Observacao as Observacao,
				cd.Email as Email
				from compras_ordem_compra coc
				inner join cadastros_dados cd on cd.Cadastro_ID = coc.Usuario_Cadastro_ID
				left join compras_ordem_compra_follows cf on cf.Ordem_Compra_ID = coc.Ordem_Compra_ID
									and cf.Ordem_Compra_Follow_ID = (select max(cfaux.Ordem_Compra_Follow_ID) from compras_ordem_compra_follows cfaux where cfaux.Ordem_Compra_ID = coc.Ordem_Compra_ID)
								left join tipo s on s.Tipo_ID = cf.Situacao_ID
				where coc.Ordem_Compra_ID = '$ordemCompraID'";
	$query = mpress_query($sql);
	$i=0;
	if ($rs = mpress_fetch_array($query)){
		$cadastroID = $rs[Cadastro_ID];
		$dataCadastro = $rs[Data_Cadastro];
		$dataAlteracao = $rs[Data_Alteracao];
		$dataLimiteRetorno = $rs[Data_Limite_Retorno];
		$situacao = $rs[Situacao];
		$situacaoID = $rs[Situacao_ID];
		$observacao = $rs[Observacao];
		$usuario = $rs[Nome];
		$usuarioEmail = $rs[Email];
	}

	if ($contEmpresas==1){
		if (($cadastroID=="")||($cadastroID=="0")){$cadastroID = retornaCodigoEmpresa();}
		$empresasCadastradas = "<input type='hidden' id='cadastro-id' name='cadastro-id' value='$cadastroID'>";
	}
	else{
		$empresasCadastradas = "<div style='float:left;width:100%;'><b>Cadastro Faturamento</b></div>
								<div style='float:left;width:100%;'><select id='cadastro-id' name='cadastro-id' style='width:98.8%'><option value=''>Selecione</option>".optionValueEmpresas($cadastroID)."</select></div>";
	}

	$query = mpress_query("select Email from cadastros_dados where Cadastro_ID = ".$dadosUserLogin['userID']."
							Union
						   select Descr_Tipo from tipo where Tipo_Grupo_ID = 43 and Situacao_ID = 1");
	while($rs = mpress_fetch_array($query))
		echo "<input type='hidden' class='email-workflow' value='".$rs[Email]."'/>";


	if($dataLimiteRetorno == "") $dataLimiteRetornoHoje = date('d/m/Y');
	echo "	<input type='hidden' id='ordem-compra-id'  name='ordem-compra-id' value='$ordemCompraID'>
			<input type='hidden' id='aux-produto-variacao-id'  name='aux-produto-variacao-id' value=''>
			<input type='hidden' id='localiza-conta-id'  name='localiza-conta-id' value=''>
			<input type='hidden' id='localiza-titulo-id'  name='localiza-titulo-id' value=''>
			<input type='hidden' id='workflow-id'  name='workflow-id' value=''>
			<input type='hidden' id='origem-faturamento'  name='origem-faturamento' value='compras'>
			<input type='hidden' id='produto-id' name='produto-id' value=''>
			<input type='hidden' class='email-workflow' value='$usuarioEmail'>
			<input type='hidden' name='botao-clicado' id='botao-clicado' value=''/>

			<div id='div-retorno'></div>
			<div id='compras-container' class='grupo1'>
				<div class='titulo-container grupo1'>
					<div class='titulo'>
						<p>
							Ordem de Compra Nº $ordemCompraID - $situacao";
	if($finalizada == ""){
		if (($situacaoID!="104")||($situacaoID!="65")){
			echo "			<input type='button' value='Atualizar'  id='botao-salvar-oc'/>";
		}
		if ($situacaoID=="65"){
			echo "			<input type='button' value='Excluir' id='botao-cancelar-oc' class='botao-cancelar-oc'/>";
		}
	}
	else{
			echo "			<input type='button' value='Repetir O.C.' id='botao-repetir-oc' class='botao-repetir-oc'/>";
	}
	echo "				</p>
					</div>
					<div class='conteudo-interno titulo-secundario' id='conteudo-interno-compras'>
						$empresasCadastradas
						<div style='width:20%; float:left;'>
							<p><b>Respons&aacute;vel:</b></p>
							<p style='margin-top:5px'>$usuario</p>
						</div>
						<div style='width:15%; float:left;'>
							<p><b>Data Inclus&atilde;o:</b></p>
							<p style='margin-top:5px'>$dataCadastro</p>
						</div>
						<div style='width:15%; float:left;'>
							<p><b>Limite Retorno Or&ccedil;amento:</b></p>";
	if($finalizada == "")
		echo "				<p><input type='text' name='data-limite-retorno' id='data-limite-retorno' class='formata-data' style='width:92%' maxlength='10' value='$dataLimiteRetorno$dataLimiteRetornoHoje'></p>";
	else
		echo "				<p style='margin-top:5px'>$dataLimiteRetorno</p>";
	echo "				</div>
						<div style='width:50%; float:left;'>
							<p><b>Observações para o Financeiro:</b></p>";
	if($finalizada == "")
		echo "				<p><textarea style='float:left; width: 98%; height: 40px' name='observacao-financeiro' id='observacao-financeiro'>$observacao</textarea></p>";
	else
		echo "				<p style='margin-top:5px'>".nl2br($observacao)."<input type='hidden' name='observacao-financeiro' id='observacao-financeiro' value='$observacao'/></p>";
	echo "				</div>
					</div>
				</div>";
	if (($dadosUserLogin[grupoID]!=1) && (($situacaoID == 61) || ($situacaoID == 62) || ($situacaoID == 63) || ($situacaoID == 105))){
		$ocultaOCAberto = "esconde";
	}

	echo "		<div class='titulo-container grupo1'>
					<div class='titulo'>
						<p>Produtos</p>
					</div>
					<div class='conteudo-interno'>
						<div style='width:100%; float:left;'>
							<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
								<tr>
									<td class='tabela-fundo-escuro-titulo' width='50' align='left'>&nbsp;</td>
									<td class='tabela-fundo-escuro-titulo' width='50' align='left'><b>C&oacute;digo</b></td>
									<td class='tabela-fundo-escuro-titulo' width='' align='left'><b>Produto</b></td>
									<td class='tabela-fundo-escuro-titulo' width='10'>&nbsp;</td>
									<td class='tabela-fundo-escuro-titulo' style='min-width:60px' align='right'><b>Solicitada</b>&nbsp;&nbsp;</td>
									<td class='tabela-fundo-escuro-titulo $ocultaOCAberto' style='min-width:60px; width:8%;' align='center'><b>Aprovada</b>&nbsp;&nbsp;</td>
									<!--<td class='tabela-fundo-escuro-titulo' style='min-width:60px; width:8%;' align='center'><b>Valor Custo</b></td>-->
									<td class='tabela-fundo-escuro-titulo' style='min-width:60px; width:8%;' align='right'><b>Compra M&iacute;nima</b>&nbsp;&nbsp;</td>
									<td class='tabela-fundo-escuro-titulo' style='min-width:60px; width:8%;' align='center'>Valor Unit.</td>
 									<td class='tabela-fundo-escuro-titulo' style='min-width:60px; width:8%;' align='center'>Total &Iacute;tem</td>";
		if($finalizada == "")
			echo "					<td class='tabela-fundo-escuro-titulo' width='030' align='left'>&nbsp;</td>";
		echo "					</tr>";

	$sql = "select p.Codigo, concat(coalesce(p.Nome,''),' ',coalesce(pv.Descricao,'')) Produto, pv.Produto_Variacao_ID, p.Produto_ID, sum(cs.Quantidade) as Quantidade,
				coalesce(e.Compra_Minima,0) as Compra_Minima, Valor_Custo, Ordens_Compras_Produtos_ID, ma.Nome_Arquivo as Nome_Arquivo, cp.Dados as Dados
				from compras_ordem_compra c
				inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
				inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
				inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
				left join produtos_estoque e on e.Produto_Variacao_ID = pv.Produto_Variacao_ID
				left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
				where c.Ordem_Compra_ID = $ordemCompraID and c.Situacao_ID = 1 and cs.Situacao_ID IN (61,62,63,64,65,104,105) and p.Situacao_ID = 1
				group by p.Codigo, p.Nome, pv.Produto_Variacao_ID
				order by p.Nome, p.Codigo";
	//echo $sql;
	$query = mpress_query($sql);
	$i=0;
	while ($rs = mpress_fetch_array($query)){
		$i++;
		if($i%2==0)$classe='tabela-fundo-escuro'; else $classe='tabela-fundo-claro';
		$botaoCancelar = "<div class='btn-cancelar btn-cancelar-compra-produto' style='float:right; padding-right:5px' produto-variacao-id='$rs[Produto_Variacao_ID]' title='Cancelar'>";
		$arrDados = unserialize($rs[Dados]);
		$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
		if ($rs[Nome_Arquivo]!="")
			$nomeArquivo = $caminhoSistema."/uploads/".$rs[Nome_Arquivo];
		else{
			if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$rs[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1")))
				$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
		}
		$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:50px; max-height:50px' src='$nomeArquivo'/></a>";

		if ($rs[Compra_Minima]==0) $compraMinima = "- &nbsp;- &nbsp;- &nbsp;"; else $compraMinima = number_format($rs[Compra_Minima], 2, ',', '.');
		echo "			<tr class='$classe'>
							<td rowspan='2' valign='top'>$imagemProduto</td>
							<td align='left' Style='margin:2px 5px 0 2px;'>
								".$rs[Codigo]."
								<input type='hidden' name='ordemCompraProdutoID[]' value='".$rs[Ordens_Compras_Produtos_ID]."'/>
							</td>
							<td align='left' Style='margin:2px 5px 0 2px;'>".$rs[Produto]."</td>
							<td align='center' Style='margin:2px 5px 0 2px;'><div class='btn-editar link-produto' produto-id='".$rs[Produto_ID]."' title='Editar Produto'>&nbsp;</div></td>
							<td align='right' Style='margin:2px 5px 0 2px;'>
								".number_format($rs[Quantidade], 2, ',', '.')."&nbsp;&nbsp;
								<span id='quantidade-item-$rs[Produto_Variacao_ID]' class='esconde'>$rs[Quantidade]</span>
							</td>
							<td align='center' Style='margin:2px 5px 0 2px;' class='$ocultaOCAberto'>";
		if($finalizada == ""){
			if ($arrDados[QuantidadeAprovada]==0) $quantidadeAprovada = $rs[Quantidade]; else $quantidadeAprovada = $arrDados[QuantidadeAprovada];
			if ($situacaoID == 104){
				echo "				<input type='hidden' name='txtQuantidadeAprovada[]' id='quantidade-aprovada-$rs[Produto_Variacao_ID]' class='formata-valor quantidade-aprovada-compra' attd-id='$rs[Produto_Variacao_ID]' value='".number_format($quantidadeAprovada, 2, ',', '.')."'>
									".number_format($quantidadeAprovada, 2, ',', '.');
			}
			else{
				echo "				<input type='text' Style='width:97%;text-align:center;' name='txtQuantidadeAprovada[]' id='quantidade-aprovada-$rs[Produto_Variacao_ID]' class='formata-valor quantidade-aprovada-compra' attd-id='$rs[Produto_Variacao_ID]' value='".number_format($quantidadeAprovada, 2, ',', '.')."'>";
			}
		}
		else
			echo 				number_format($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][quantidade], 2, ',', '.');
		echo "				</td>
							<!--
							<td align='center' Style='margin:2px 5px 0 2px;'>
								<span id='valor-custo-item-$rs[Produto_Variacao_ID]'>".number_format($rs[Valor_Custo], 2, ',', '.')."</span>
							</td>
							-->
							<td align='right' Style='margin:2px 5px 0 2px;'>$compraMinima&nbsp;&nbsp;</td>
							<td align='center' Style='margin:2px 5px 0 2px;'>";
		$escondeValorAprov = "class='esconde'";
		if($finalizada == ""){
			if ($arrDados[ValorAprovado]==0) $valorAprovado = $rs[Valor_Custo]; else $valorAprovado = $arrDados[ValorAprovado];
			if (($situacaoID == 104) || ($situacaoID == 65)){
				$tipoCampo = "hidden";
				$escondeValorAprov = "";
			}
			else{
				$tipoCampo = "text";
			}
			echo "				<input type='$tipoCampo' Style='width:97%;text-align:center;' name='txtValorProduto[]' id='valor-produto-item-$rs[Produto_Variacao_ID]' class='formata-valor valor-manual-compra' attd-id='$rs[Produto_Variacao_ID]' value='".number_format($valorAprovado, 2, ',', '.')."'>";
			echo "				<input type='hidden' name='hdProdutoVariacaoID' id='hdProdutoVariacaoID[]' value='$rs[Produto_Variacao_ID]'>";
		}
		else{
			echo 				number_format($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][valor], 2, ',', '.');
		}
		echo "					<span id='valor-custo-item-$rs[Produto_Variacao_ID]' $escondeValorAprov>".number_format($valorAprovado, 2, ',', '.')."</span>
							</td>";
		if($finalizada == ""){
			echo"			<td align='center' Style='margin:2px 5px 0 2px;'>
								<span id='valor-total-$rs[Produto_Variacao_ID]' class='valor-total-produto'>".number_format($valorAprovado * $quantidadeAprovada, 2, ',', '.')."</span>
							</td>
							<td align='center' Style='margin:2px 5px 0 2px;'>&nbsp;$botaoCancelar</td>
							<input type='hidden' name='ordemCompraProdutosID[]' value='$rs[Ordens_Compras_Produtos_ID]'>";
			$totalGeral += ($valorAprovado * $quantidadeAprovada);
		}
		else{
			echo"			<td align='center' Style='margin:2px 5px 0 2px;'>
								<span id='valor-total-$rs[Produto_Variacao_ID]' class='valor-total-produto'>".number_format($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][valor] * $ordemCompra[$rs[Ordens_Compras_Produtos_ID]][quantidade], 2, ',', '.')."</span>
							</td>";
			$totalGeral += ($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][valor] * $ordemCompra[$rs[Ordens_Compras_Produtos_ID]][quantidade]);
		}
		echo "			</tr>
						<tr class='$classe'>
							<td align='right'>&nbsp;</td>
							<td colspan='2'>";
		if(($finalizada == "")&&($situacaoID != 104))
			echo "				<input type='text' name='observacaoCompraProd[]' value='".$arrDados[Observacao]."' style='width:98.5%'/>";
		else
			echo "				<input type='hidden' name='observacaoCompraProd[]' value='".$arrDados[Observacao]."' style='width:98.5%'/>".$arrDados[Observacao];
		echo "				</td>
							<td colspan='7'>";
		if(($finalizada == "")&&($situacaoID != 104)){
			echo "			<select name='slcFornecedor[]' Style='width:99.5%' class='fornecedor-compras-produtos' produto-id='95'>
									<option value=''>Selecione o fornecedor</option>";
			$resultado = mpress_query("select Produto_Cadastro_ID, cd.Cadastro_ID as Cadastro_ID, Nome, Nome_Fantasia, Cpf_Cnpj, Email
									   from cadastros_dados cd
									   inner join produtos_fornecedores pf on pf.Cadastro_ID = cd.Cadastro_ID
									   inner join produtos_variacoes pv on pv.Produto_ID = pf.Produto_ID
									   where cd.Situacao_ID = 1 and pv.Produto_Variacao_ID = $rs[Produto_Variacao_ID]");
			while($row = mpress_fetch_array($resultado)){
				if ($arrDados[Fornecedor]==$row[Cadastro_ID]){ $optionSelected = "selected";} else { $optionSelected = "";}
				echo "					<option value='$row[Cadastro_ID]' $optionSelected>$row[Nome]</option>";
			}
			echo "					</select>";
		}else{
			$fornecedorID = $ordemCompra[$rs[Ordens_Compras_Produtos_ID]][fornecedor];
			if ($fornecedorID=="") $fornecedorID = $arrDados[Fornecedor];
			echo "<input type='hidden' name='slcFornecedor[]' value='$fornecedorID'/>";
			if ($fornecedorID=="") $fornecedorID = $arrDados[Fornecedor];
			$resultado = mpress_query("select Nome from cadastros_dados where Cadastro_ID = '$fornecedorID'");
			if($row = mpress_fetch_array($resultado)){
				echo $row[Nome];
			}
		}
		echo "				</td>
						</tr>";

		/**/


		/**/
		if ($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][quantidadeEntregue]>0){
			echo "		<tr class='$classe'>
							<td colspan='4' style='border-bottom:1px solid #c9c9c9'>&nbsp;</td>
							<td colspan='1' style='border-bottom:1px solid #c9c9c9' class='tabela-fundo-escuro-titulo' style='margin:1px; border:0px' align='center'><b>Status Entrega</b></td>
							<td colspan='4' style='border-bottom:1px solid #c9c9c9' class='tabela-fundo-escuro-titulo' style='margin:1px; border:0px'>";

			$sql = "select pm.Produto_Movimentacao_ID, DATE_FORMAT(pm.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Movimentacao, cd.Nome as Usuario,
						pm.Quantidade, pm.Produto_Variacao_ID, pm.Nota_Fiscal as Nota_Fiscal
						from produtos_movimentacoes pm
						left join cadastros_dados cd on cd.Cadastro_ID = pm.Usuario_Cadastro_ID
						where pm.Chave_Estrangeira = '$ordemCompraID' and pm.Tabela_Estrangeira = 'compras' and pm.Produto_Variacao_ID = '".$rs[Produto_Variacao_ID]."'
						order by pm.Data_Cadastro desc";
			//echo $sql;
			$im = 0;
			$resultado = mpress_query($sql);
			$qtdEntregue = 0;
			while($row = mpress_fetch_array($resultado)){
				$im++;
				$pmID = $row['Produto_Movimentacao_ID'];
				$qtdEntregue += $row[Quantidade];
				$dadosM[colunas][conteudo][$im][1] = "<p style='margin:0px' align='right'>".number_format($row[Quantidade], 2, ',', '.')."</p>";
				$dadosM[colunas][conteudo][$im][2] = $row['Data_Movimentacao'];
				$dadosM[colunas][conteudo][$im][3] = "	<center>
															<input type='text' name='nota-fiscal[]' id='nota-fiscal-".$pmID."' class='nota-fiscal-pm' value='".$row['Nota_Fiscal']."' pmID='".$pmID."' style='width:80px;'/>
															<div class='pm-nf pm-nf-".$pmID." esconde btn-cancelar btn-cancelar-nf' style='float:right;padding-right:7px' title='Cancelar'>&nbsp;</div>
															<div class='pm-nf pm-nf-".$pmID." esconde btn-atualizar btn-atualizar-nf' style='float:right;padding-right:3px' pmID='".$pmID."' title='Atualizar'>&nbsp;</div>
														</center>";
				$dadosM[colunas][conteudo][$im][4] = $row['Usuario'];
			}
			if ($im>0){
				$largura = "100.2%";
				$colunas = "4";
				$dadosM[colunas][tamanho][1] = "width='100px'";
				$dadosM[colunas][tamanho][2] = "width='100px'";
				$dadosM[colunas][titulo][1] 	= "<p style='margin:0px' align='right'>Qtd. Entregue</p>";
				$dadosM[colunas][titulo][2] 	= "Data Entrada";
				$dadosM[colunas][titulo][3] 	= "<center>Nota Fiscal</center>";
				$dadosM[colunas][titulo][4] 	= "Usu&aacute;rio Entrada";
				geraTabela($largura,$colunas,$dadosM);
			}

			if ($ordemCompra[$rs[Ordens_Compras_Produtos_ID]][quantidadeEntregue]> $qtdEntregue){
				echo "<p style='color:red; margin:1px' align='center'>Aguardando entrada em estoque</p>";
				if ($modulosAtivos['produtos-movimentacao-material']){
					$btnAlvoEntrada = "	<div style='width:180px; float:right;margin-bottom:5px;'>
											<input type='button' class='atalho-estoque' Style='width:100%' value='Realizar entrada em estoque'/>
										</div>";
				}
			}
			unset($dadosM);
			echo "			</td>
						</tr>";
		}
	}
	echo "
					<tr><td colspan='10'>&nbsp;</td></tr>
					<tr>
						<td colspan='6' align='right'>
							<input type='hidden' id='tipo-entrada' name='tipo-entrada' value='compras'/>
							<input type='hidden' id='localiza-produto-oc' name='localiza-produto-oc' value='$ordemCompraID'/>
							&nbsp;".$btnAlvoEntrada."
						</td>
						<td colspan='2' align='right'><b>TOTAL GERAL &nbsp;&nbsp;</b></td>
						<td align='center' style='background-color: #c9c9c9; height:22px'><b><span id='total-geral-compra'>".number_format($totalGeral, 2, ',', '.')."</span></b></td>
					</tr>
					</table>
					<input type='hidden' name='cont-prod' id='cont-prod' value='$i'/>";

	if ($qtdTitulos==0){
		echo "		<div class='titulo-secundario' style='float:right; width:100%; margin-top:5px'>
						<p><b>Observa&ccedil;&atilde;o para hist&oacute;rico:</b></p>
						<p><textarea style='float:left; width: 99.1%; height: 60px' name='observacao-follow' id='observacao-follow'></textarea></p>
						<p>&nbsp;</p>
					</div>";
	}
	echo "		</div>";
	if($finalizada == ""){
		if ($situacaoID!="105"){
			if (($dadosUserLogin[grupoID]==1) || ($situacaoID=="104")){
				echo "	<div style='width:175px; float:right;'><input type='button' id='botao-finalizar-oc' Style='width:95%' value='Enviar para Faturamento'></div>";
			}
		}
		if ($situacaoID=="64"){
			if ($modulosAtivos['compras-aprovar-recusar-compras']==1){
				echo "	<div style='width:175px; float:right;'><input type='button' id='botao-aprovar-oc' Style='width:95%' value='Aprovar' ></div>";
				echo "	<div style='width:175px; float:right;'><input type='button' id='botao-reprovar-oc' Style='width:95%' value='Recusar' ></div>";
			}
			else{
				echo "	<p align='center'>Aguardando usuário com permissão para aprovar compra</p>";
			}
		}
		else{
			if ($situacaoID!="104"){
				$textoEnvio = "Enviar para Aprova&ccedil;&atilde;o";
				if ($situacaoID=="105") $textoEnvio = "Re-enviar para Aprova&ccedil;&atilde;o";
				echo "	<div style='width:175px; float:right;'><input type='button' id='botao-enviar-aprovacao' Style='width:95%' value='$textoEnvio'/></div>";
			}
		}
		//echo "	<div style='width:175px; float:right;'><input type='button' id='botao-enviar-orcamento' Style='width:95%' value='Enviar para Or&ccedil;amento'></div>";
	}
	else{
		if (($qtdTitulos==0) && ($flagNaoMostraReabrir==0)){
			echo "	<div style='width:180px; float:right;margin-bottom:5px;'>
						<input type='button' id='botao-reabrir-oc' name='botao-reabrir-oc' Style='width:98%' value='Re-abrir Ordem de Compra' >
					</div>";
		}
	}
	if ($qtdTitulos==0){
		echo "		<div style='width:200px;float:right;'>
						<p style='margin-top:5px; margin-left:5px'>
							<label for='enviar-email' style='cursor:pointer;'><b>ENVIAR EMAIL</b></label>
							<input type='checkbox' id='enviar-email' name='enviar-email' checked>
						</p>
					</div>
				";
	}
	echo "		</div>
		  	</div>";

	$i=0;
	$query = mpress_query("select s.Descr_tipo Situacao, c.Nome, DATE_FORMAT(f.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Cadastro, f.Descricao as Observacao
						  from compras_ordem_compra_follows f
						  inner join cadastros_dados c on c.Cadastro_ID = f.Usuario_Cadastro_ID
						  left join tipo s on s.Tipo_ID = f.Situacao_ID
						  where f.Ordem_Compra_ID = '$ordemCompraID' order by f.Data_Cadastro desc");
	while($row = mpress_fetch_array($query)){
		$i++;
		$dados[colunas][conteudo][$i][1] = $row[Situacao];
		$dados[colunas][conteudo][$i][2] = nl2br($row[Observacao]);
		$dados[colunas][conteudo][$i][3] = "<p align='center'>".$row[Data_Cadastro]."</p>";
		$dados[colunas][conteudo][$i][4] = $row[Nome];
	}
	$largura = "99%";
	$colunas = "4";
	$dados[colunas][titulo][1] 	= "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][2] 	= "Observa&ccedil;&atilde;o";
	$dados[colunas][titulo][3] 	= "<p align='center'>Data</p>";
	$dados[colunas][titulo][4] 	= "Usu&aacute;rio";

	$dados[colunas][tamanho][1] = "width='180px'";
	$dados[colunas][tamanho][2] = "";
	$dados[colunas][tamanho][3] = "width='180px'";
	$dados[colunas][tamanho][4] = "width='180px'";

	echo" 	<div class='titulo-container grupo1'>
				<div class='titulo'>
					<p>Hist&oacute;rico Ordem de Compra</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='width:100%;'>";
	 				geraTabela($largura,$colunas,$dados);
	echo "			</div>
				</div>
			</div>";


		echo" <div class='titulo-container grupo2 esconde'>
				<div class='titulo'>
					<p>Orçamentos Recebidos</p>
				</div>
				<div class='conteudo-interno'>
					<div style='width:100%; float:left;'>";
		$queryP = mpress_query("select pv.Produto_Variacao_ID, pd.Nome
								from compras_ordem_compra oc
								inner join compras_ordens_compras_produtos op on op.Ordem_Compra_ID = oc.Ordem_Compra_ID
								inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = op.Compra_Solicitacao_ID
								inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
								inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
								where oc.Ordem_Compra_ID = $ordemCompraID and op.Situacao_ID = 1 order by pd.nome");
		while($produtos = mpress_fetch_array($queryP))
			$produto[$produtos[Produto_Variacao_ID]][nome] = $produtos[Nome];


		$query = mpress_query("	select distinct coc.Fornecedor_ID, cd.Nome Fornecedor
								from compras_ordem_compra	oc
								inner join compras_ordens_compras_produtos op on op.Ordem_Compra_ID = oc.Ordem_Compra_ID
								inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = op.Compra_Solicitacao_ID
								inner join compras_ordens_compras_orcamentos coc on coc.Produto_Variacao_ID = cs.Produto_Variacao_ID
								inner join produtos_fornecedores f on f.Cadastro_ID = coc.Fornecedor_ID
								inner join cadastros_dados cd on cd.Cadastro_ID = f.Cadastro_ID
								where oc.Ordem_Compra_ID = $ordemCompraID and op.Situacao_ID = 1
								order by cd.Nome");
		while($row = mpress_fetch_array($query)){
			$i=0;
			$dadosOC[$row[Fornecedor_ID]][nome] = $row['Fornecedor'];
			$subQuery = mpress_query("select coc.Produto_Variacao_ID, coc.Valor_Retorno, sum(cs.Quantidade) Quantidade
									  from compras_ordem_compra	oc
									  inner join compras_ordens_compras_produtos op on op.Ordem_Compra_ID = oc.Ordem_Compra_ID
									  inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = op.Compra_Solicitacao_ID
									  inner join compras_ordens_compras_orcamentos coc on coc.Produto_Variacao_ID = cs.Produto_Variacao_ID
									  where oc.Ordem_Compra_ID = $ordemCompraID and coc.Fornecedor_ID = ".$row['Fornecedor_ID']." and op.Situacao_ID = 1
									  group by coc.Produto_Variacao_ID, coc.Valor_Retorno");
			while($sub = mpress_fetch_array($subQuery)){
				$i++;
				$dadosOC[$row[Fornecedor_ID]][produtoVariacaoID][$sub['Produto_Variacao_ID']][quantidade] = $sub['Quantidade'];
				$dadosOC[$row[Fornecedor_ID]][produtoVariacaoID][$sub['Produto_Variacao_ID']][valorunitario] = number_format($sub['Valor_Retorno'], 2, ',', '.');
				$dadosOC[$row[Fornecedor_ID]][produtoVariacaoID][$sub['Produto_Variacao_ID']][valortotal] = number_format($sub['Valor_Retorno']*$sub['Quantidade'], 2, ',', '.');
			}
		}
		echo "	<style>
					.div-principal {
						border:1px solid silver;
						float:left;
						margin-right:5px;
						text-transform:uppercase;
						font-family:arial;
						font-size:11px;
					}
					.div-interna {
						float:left;
						margin-right:5px;
						text-transform:uppercase;
						font-family:arial;
						font-size:10px;
						height:15px;
						padding-top:3px;
						text-align:center;
					}
				</style>";

		if(count($dadosOC) == 0){
			echo "<p style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum orçamento recebido</p>";

		}else{
			echo "<div Style='width:400px;overflow:auto;' class='div-principal'>
					<div class='div-interna' Style='height:16px;'>&nbsp;</div>
					<div class='div-interna' Style='width:100%;text-align:left;'>&nbsp;</div>";
				foreach(array_keys($produto) as $chaveProduto){
					$l++;
					if($l%2==1)$class='tabela-fundo-claro';else$class='tabela-fundo-escuro';
					echo "<div class='div-interna $class' Style='text-align:left;width:99.5%;'>&nbsp;".substr(trim($produto[$chaveProduto][nome]), 0, 61)."</div>";
				}

			echo "</div>";
			foreach(array_keys($dadosOC) as $chaveArray){
				$m = 0;
				echo "	<div Style='width:200px;' class='div-principal'>
							<div Style='width:100%;margin-left:-1px;' class='div-interna tabela-fundo-escuro-titulo'>".$dadosOC[$chaveArray][nome]."</div>
							<div Style='width:22%' class='div-interna tabela-fundo-escuro'>Quant</div>
							<div Style='width:37%' class='div-interna tabela-fundo-escuro'>Val Unitário</div>
							<div Style='width:32%' class='div-interna tabela-fundo-escuro'>Val Total</div>";

				foreach(array_keys($produto) as $chaveProduto){
					$m++;
					if($m%2==1)$class='tabela-fundo-claro';else$class='tabela-fundo-escuro';
					echo "<div Style='width:22%' class='div-interna $class'>".$dadosOC[$chaveArray][produtoVariacaoID][$chaveProduto][quantidade]."</div>";
					echo "<div Style='width:35%' class='div-interna $class'>".$dadosOC[$chaveArray][produtoVariacaoID][$chaveProduto][valorunitario]."</div>";
					echo "<div Style='width:32%' class='div-interna $class'>".$dadosOC[$chaveArray][produtoVariacaoID][$chaveProduto][valortotal]."</div>";
				}

				echo "	</div>";
			}
		}
		echo "		</div>
				</div>
			</div>";


		// FINANCEIRO

		/**************************************/

		echo "<div class='titulo-container grupo5 esconde'>
				<div class='titulo'>
					<p>Financeiro</p>
				</div>
				<div class='conteudo-interno'>";
		if ($ordemCompraID!=""){

			$sql = "select p.Codigo, concat(coalesce(p.Nome,''),' ',coalesce(pv.Descricao,'')) Produto, pv.Produto_Variacao_ID, Ordens_Compras_Produtos_ID,
						 cpf.Fornecedor_ID, cf.Nome Fornecedor, ce.Nome Cadastro, cpf.Quantidade_Aprovada as Quantidade, cpf.Valor_Aprovado as Valor_Produto,
						 c.Ordem_Compra_ID, cpf.Ordem_Compra_Produto_ID,
						 fp.Financeiro_Produto_ID, fp.Conta_ID,
						 (select count(*) from financeiro_titulos ft where ft.Conta_ID = fp.Conta_ID and ft.Situacao_Pagamento_ID > 0) as Qtd_Titulos
						from compras_ordem_compra c
						inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
						inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
						inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
						inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
						inner join compras_ordem_compras_finalizadas cpf on cpf.Ordem_Compra_ID = c.Ordem_Compra_ID and cpf.Ordem_Compra_Produto_ID = cp.Ordens_Compras_Produtos_ID
						inner join cadastros_dados cf on cf.Cadastro_ID = cpf.Fornecedor_ID
						left join cadastros_dados ce on ce.Cadastro_ID = c.Cadastro_ID
						left join financeiro_produtos fp on fp.Produto_Referencia_ID = cpf.Ordem_Compra_Produto_ID and cpf.Ordem_Compra_ID = fp.Chave_Estrangeira and fp.Tabela_Estrangeira = 'compras'
						where c.Situacao_ID = 1 and p.Situacao_ID = 1
						and c.Ordem_Compra_ID = '$ordemCompraID'
						group by p.Codigo, p.Nome, pv.Produto_Variacao_ID
						order by ce.Nome, cf.Nome, p.Nome, p.Codigo";
			//echo $sql;

			$query = mpress_query($sql);
			$i=0;
			while($row = mpress_fetch_array($query)){
				$i++;
				$total += $row[Quantidade] * $row[Valor_Produto];
				if ($row[Financeiro_Produto_ID]=="")
					$situacaoFaturamento = "Aguardando Faturamento <span class='link link-aguardando-fat'>visualizar</span>";
				else{
					if ($row[Qtd_Titulos]==0)
						$situacaoFaturamento = "Faturado - Pendente Preenchimento | Conta ID: <span class='link link-conta' localiza-conta-id='$row[Conta_ID]'>$row[Conta_ID]</span>";
					else
						$situacaoFaturamento = "T&iacute;tulos Gerados | Conta ID: <span class='link link-conta' localiza-conta-id='$row[Conta_ID]'>$row[Conta_ID]</span>";
				}
				$dadosFat[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;'>".utf8_encode($row[Produto])."</p>";
				$dadosFat[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;' align='right'>".number_format($row[Quantidade], 2, ',', '.')."&nbsp;</p>";
				$dadosFat[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;' align='right'>".number_format($row[Valor_Produto], 2, ',', '.')."&nbsp;</p>";
				$dadosFat[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;' align='right'>".number_format(($row[Quantidade] * $row[Valor_Produto]), 2, ',', '.')."&nbsp;</p>";
				$dadosFat[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;'>$situacaoFaturamento</p>";
			}
			$dadosFat[colunas][titulo][1] = "Situa&ccedil;&atilde;o Faturamento Produtos";
			$dadosFat[colunas][titulo][2] = "<p Style='margin:2px 3px 0 3px;' align='right'>Quantidade</p>";
			$dadosFat[colunas][titulo][3] = "<p Style='margin:2px 3px 0 3px;' align='right'>Valor</p>";
			$dadosFat[colunas][titulo][4] = "<p Style='margin:2px 3px 0 3px;' align='right'>Total</p>";
			$dadosFat[colunas][titulo][5] = "<p Style='margin:2px 3px 0 3px;'>Situa&ccedil;&atilde;o</p>";

			$dadosFat[colunas][tamanho][1] = "width=''";
			$dadosFat[colunas][tamanho][2] = "width=''";
			$dadosFat[colunas][tamanho][3] = "width=''";
			$dadosFat[colunas][tamanho][4] = "width=''";
			$dadosFat[colunas][tamanho][5] = "width=''";
			if ($i>0){
				$i++;
				$dadosFat[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;' align='right'><b>TOTAL</b></p>";
				$dadosFat[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;' align='right'><b>".number_format($total, 2, ',', '.')."</b></p>";
			}
			geraTabela("100%",5,$dadosFat);

			echo "<p>&nbsp;</p><p>&nbsp;</p>";

			$sql = "select fc.Conta_ID, ft.Titulo_ID, ft.Codigo as Codigo_Titulo, ft.Forma_Pagamento_ID, ft.Situacao_Pagamento_ID as Situacao_Pagamento_ID,
						coalesce(tsp.Descr_Tipo,'Aguardando Faturamento') as Situacao_Pagamento,
						ft.Valor_Titulo, fc.Cadastro_ID_de, fc.Cadastro_ID_para, cd1.Nome as Nome_De, cd2.Nome as Nome_Para,
						tf.Descr_Tipo as Tipo, tf.Tipo_ID as Tipo_ID, DATE_FORMAT(ft.Data_Vencimento,'%d/%m/%Y') as Data_Vencimento, DATE_FORMAT(ft.Data_Pago,'%d/%m/%Y') as Data_Pago
						from financeiro_titulos ft
						inner join financeiro_contas fc on fc.Conta_ID = ft.Conta_ID
						inner join financeiro_produtos fp on ft.Conta_ID = fp.Conta_ID
						inner join compras_ordens_compras_produtos cp on cp.Ordens_Compras_Produtos_ID = fp.Produto_Referencia_ID
						inner join cadastros_dados cd1 on cd1.Cadastro_ID = fc.Cadastro_ID_de
						inner join tipo tf on tf.Tipo_ID = fc.Tipo_ID
						left join tipo tsp on tsp.Tipo_ID = ft.Situacao_Pagamento_ID
						left join cadastros_dados cd2 on cd2.Cadastro_ID = fc.Cadastro_ID_para
						where cp.Ordem_Compra_ID = '$ordemCompraID'
						and fp.Tabela_Estrangeira = 'compras'
						group by  ft.Titulo_ID, ft.Conta_ID, ft.Forma_Pagamento_ID, ft.Situacao_Pagamento_ID, tsp.Descr_Tipo, ft.Valor_Titulo,
						fc.Cadastro_ID_de, fc.Cadastro_ID_para, ft.Data_Vencimento, ft.Data_Pago";
				//echo $sql;
				$i=0;
				$query = mpress_query($sql);
				while($rs = mpress_fetch_array($query)){
					$i++;
					$situacao ="";
					//if ($rs[Codigo_Titulo]==""){$codigoTitulo = "N&atilde;o Informado";}else{$codigoTitulo = $rs[Codigo_Titulo];}
					if ($rs[Nome_De]==""){$nomeDe = "N&atilde;o Informado";}else{$nomeDe = utf8_encode($rs[Nome_De]);}
					if ($rs[Nome_Para]==""){$nomePara = "N&atilde;o Informado";}else{$nomePara = utf8_encode($rs[Nome_Para]);}
					if ($rs[Tipo_ID]=="44"){ $descricaoConta = "<font color='red'><b>".$rs[Tipo]."</b></font> para <i>".$nomePara."</i>"; $codigoTitulo=$rs[Codigo_Titulo];}
					//if ($rs[Tipo_ID]=="45"){ $descricaoConta = "<font color='blue'><b>".$rs[Tipo]."</b></font> de <i>".$nomePara."</i>"; $codigoTitulo=$rs[Titulo_ID];}
					if ($rs[Data_Vencimento]=="00/00/0000"){ $dataVencimento = 'A definir';} else { $dataVencimento = $rs[Data_Vencimento];}

					if ($rs[Situacao_Pagamento_ID]=="-1"){$situacao = "<span class='mini-bola-cinza link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='Aguardando Faturamento'>&nbsp;</span>";}
					if ($rs[Situacao_Pagamento_ID]=="48"){
						if ($rs[DiasAtraso]>0){$situacao = "<span class='mini-bola-vermelha link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='$rs[DiasAtraso] dia(s) em atraso'>&nbsp;</span>";}
						if ($rs[DiasAtraso]==0){$situacao = "<span class='mini-bola-amarela link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='O Vencimento &eacute; hoje'>&nbsp;</span>";}
						if ($rs[DiasAtraso]<0){$situacao = "<span class='mini-bola-azul link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo com vencimento para dia $rs[Data_Vencimento]'>&nbsp;</span>";}
					}
					if ($rs[Situacao_Pagamento_ID]=="49"){$situacao = "<span class='mini-bola-verde link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='T&iacute;tulo pago dia $rs[Data_Pago]'>&nbsp;</span>";}
					if ($rs[Situacao_Pagamento_ID]=="50"){$situacao = "<span class='mini-bola-cinza link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."' Style='margin:0px;float:left;cursor:pointer;' title='Cancelado'>&nbsp;</span>";}

					$dadosTit[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 2px;float:left;cursor:pointer;' class='link link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id=''>".($rs[Conta_ID])."</p>";
					$dadosTit[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 2px;float:left;cursor:pointer;' class='link link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."'>$rs[Titulo_ID]</p>";
					$dadosTit[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:left;'>".($nomeDe)."</p>";
					$dadosTit[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$descricaoConta."</p>";
					$dadosTit[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:left;'>R$ ".number_format($rs[Valor_Titulo], 2, ',', '.')."</p>";
					$dadosTit[colunas][conteudo][$i][6] = "$situacao<span Style='margin:2px 2px 0 2px;float:left;cursor:pointer' class='link-conta' localiza-conta-id='".$rs[Conta_ID]."' localiza-titulo-id='".$rs[Titulo_ID]."'>".($rs[Situacao_Pagamento])."</span>";
					$dadosTit[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$dataVencimento."</p>";
					$dadosTit[colunas][conteudo][$i][8] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Data_Pago]."</p>";
				}
				$largura = "100%";
				$dadosTit[colunas][tamanho][1] = "width='8%'";
				$dadosTit[colunas][tamanho][2] = "width='8%'";
				$dadosTit[colunas][tamanho][3] = "width='20%'";
				$dadosTit[colunas][tamanho][4] = "width='20%'";
				$dadosTit[colunas][tamanho][5] = "";
				$dadosTit[colunas][tamanho][6] = "";
				$dadosTit[colunas][tamanho][7] = "";
				$dadosTit[colunas][tamanho][8] = "";

				$dadosTit[colunas][titulo][1] 	= "Conta ID";
				$dadosTit[colunas][titulo][2] 	= "T&iacute;tulo";
				$dadosTit[colunas][titulo][3] 	= "Cadastro";
				$dadosTit[colunas][titulo][4] 	= "Conta";
				$dadosTit[colunas][titulo][5] 	= "Valor";
				$dadosTit[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
				$dadosTit[colunas][titulo][7] 	= "Data Vencimento";
				$dadosTit[colunas][titulo][8] 	= "Data Pago";
				echo "<p Style='margin:2px 5px 0 5px; text-align:left'><b>T&iacute;tulos Gerados para pagamento de ".$_SESSION['objeto']."</b></p>";
				geraTabela($largura,7,$dadosTit);
				if($i==0){
					echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum T&iacute;tulos encontrado</p>";
			}
		}
		echo "		</div>
			</div>";

		/***********************************************/


		echo "<div class='titulo-container grupo4 esconde'>
				<div class='titulo'>
					<p>Origem das Solicita&ccedil;&otilde;es</p>
				</div>
				<div class='conteudo-interno'>";

		$sql = "select p.Codigo, concat(coalesce(p.Nome,''),' ',coalesce(pv.Descricao,'')) as Produto, pv.Produto_Variacao_ID, cs.Quantidade as Quantidade,  Ordens_Compras_Produtos_ID,
					(select Nome from modulos where slug = cs.Tabela_Estrangeira) Modulo, cs.Chave_Estrangeira, cs.Tabela_Estrangeira,
					cs.Usuario_Cadastro_ID, cd.Nome as Solicitante, DATE_FORMAT(cs.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Solicitacao,
					cs.Compra_Solicitacao_ID
					from compras_ordem_compra c
					inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
					inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
					inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
					inner join cadastros_dados cd on cd.Cadastro_ID = cs.Usuario_Cadastro_ID
					left join produtos_estoque e on e.Produto_Variacao_ID = pv.Produto_Variacao_ID
					where c.Ordem_Compra_ID = $ordemCompraID and c.Situacao_ID = 1 and p.Situacao_ID = 1
					order by p.Nome, p.Codigo";
		//echo $sql;



		$query = mpress_query($sql);
		$i=0;
		while ($rs = mpress_fetch_array($query)){
			$i++;
			$origemSolicitacao = "&nbsp;";
			if ($rs[Tabela_Estrangeira]=='chamados')
				$origemSolicitacao = $rs[Modulo]." / <span class='link link-chamados' workflow-id='$rs[Chave_Estrangeira]'>".$rs[Chave_Estrangeira]."</span>";
			if ($rs[Tabela_Estrangeira]=='compras')
				$origemSolicitacao = $rs[Modulo];

			$dadosOrigem[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Compra_Solicitacao_ID']."</p>";
			$dadosOrigem[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Codigo']."</p>";
			$dadosOrigem[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Produto']."</p>";
			$dadosOrigem[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:right;'>".number_format($rs['Quantidade'], 2, ',', '.')."</p>";
			$dadosOrigem[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>$origemSolicitacao</p>";
			$dadosOrigem[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Solicitante']."</p>";
			$dadosOrigem[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Solicitacao']."</p>";
		}
		$dadosOrigem[colunas][titulo][1] 	= "Requisi&ccedil;&atilde;o";
		$dadosOrigem[colunas][titulo][2] 	= "C&oacute;digo";
		$dadosOrigem[colunas][titulo][3] 	= "Produto";
		$dadosOrigem[colunas][titulo][4] 	= "<p Style='margin:2px 5px 0 5px;float:right;'>Qtd. Solicitada</p>";
		$dadosOrigem[colunas][titulo][5] 	= "Origem";
		$dadosOrigem[colunas][titulo][6] 	= "Solicitante";
		$dadosOrigem[colunas][titulo][7] 	= "Data Solicita&ccedil;&atilde;o";

		//$dadosOrigem[colunas][tamanho][1] = "width=''";
		//$dadosOrigem[colunas][tamanho][2] = "width='180px'";
		//$dadosOrigem[colunas][tamanho][3] = "width='180px'";

	geraTabela("99%","7",$dadosOrigem);

	echo "		</div>
		  	</div>";

?>
		<!-- INICIO Bloco Upload usando PLUPLOAD -->
		<div id='div-documentos'></div>
		<div id="container">
			<input type="hidden" id="pickfiles"/>
			<input type="hidden" id="uploadfiles"/>
		</div>
		<!-- FIM Bloco Upload usando PLUPLOAD -->



		<div id='div-email' style='position:absolute; width:800px; height:250px; z-index:100; overflow-X:auto; display:none; border-radius:15px;'>
			<table width='100%' height='100%' border='0' cellspacing='10' cellpadding='4' bgcolor='#F9F9F9' style='border: black 1px solid'>
				<tr>
					<td colspan='2' valign='top'>
						<p style='margin:5px'><b>Enviar e-mail para:</b></p>
						<p style='margin:5px'><textarea id='emails-envio' name='emails-envio' style='height:110px;width:100%'></textarea></p>
						<p style='margin:5px'><b>* Separar e-mails com ponto e virgula <font color='red'>;</font></b></p>
					</td>
				</tr>
				<tr>
					<td align='center' width='50%'><input type='button' id='botao-submeter-email' name='botao-submeter-email' Style='width:150px' value='Enviar'></td>
					<td align='center' width='50%'><input type='button' id='botao-cancelar-email' name='botao-cancelar-email' Style='width:150px' value='Cancelar'></td>
				</tr>
			</table>
		</div>
