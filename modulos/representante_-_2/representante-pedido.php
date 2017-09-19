<?php
$dadospagina = get_page_content();

$workflowID = $_POST['localiza-workflow-id'];
if ($workflowID!=""){
	$numeroPedido = "Nº $workflowID";
	$sql = "select Tipo_Workflow_ID, Codigo, Solicitante_ID, Fornecedor_ID, Data_Cadastro, Usuario_Cadastro_ID from telemarketing_workflows where Workflow_ID = $workflowID";
	$resultado = mpress_query($sql);
	if ($row = mpress_fetch_array($resultado)){
		$tipoWorkflowID = $row[Tipo_Workflow_ID];
		$codigo = $row[Codigo];
		//if ($codigo==""){ $codigo = "Não Informado";}
		$solicitanteID = $row[Solicitante_ID];
		$fornecedorID = $row[Fornecedor_ID];
		$dataCadastro = $row[Data_Cadastro];
		$readonlyAtualizar = " readonly ";
		$disabledAtualizar = " disabled ";
		$classeEscondeAtualizar = " esconde ";

		/* Fornecedor pega do site */
		$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$fornecedorID' and meta_key = 'representantes'");
		if($row = mpress_fetch_array($rs)){
			$dadosRepresentante = unserialize($row['meta_value']);
			$representante = $dadosRepresentante[dadosPrincipais][razaoSocial];
		}

		$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$fornecedorID' and meta_key = 'representantes_pagamento'");
		if($row = mpress_fetch_array($rs)){
			global $dadosPagamento;
			$dadosPagamento = unserialize(unserialize($row['meta_value']));
			$_SESSION[dadosPagamento] = $dadosPagamento;
		}
		/**/



		/* HISTÓRICO */
		$sql = "Select Follow_ID, Descricao, Dados, t.Descr_Tipo as Situacao, tf.Situacao_ID as Situacao_ID, DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro,
				tm.Descr_Tipo as Motivo, Dados as Outros, Motivo_ID,
				Responsabilidade_ID, cd.Nome as Usuario_Follow
				from telemarketing_follows tf
				inner join cadastros_dados cd on cd.Cadastro_ID = tf.Usuario_Cadastro_ID
				left join tipo t on t.Tipo_ID = tf.Situacao_ID
				left join tipo tm on tm.Tipo_ID = tf.Motivo_ID
				where Workflow_ID = $workflowID
				order by tf.Data_Cadastro desc ";
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			$descricaoMotivo = "";
			if ($i==1){
				$situacaoAtualID = $rs['Situacao_ID'];
				$situacaoAtual = $rs['Situacao'];
			}

			if ($rs['Situacao_ID']=="40"){
				$descricaoMotivo = "<p><b>Motivo Cancelamento:</b>&nbsp;".$rs['Motivo'];
				if ($rs['Motivo_ID']=="43"){
					$descricaoMotivo .= " - ".$rs['Outros'];
				}
				$descricaoMotivo .= "</p>";
			}

			$dadosFollow[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Descricao'].$descricaoMotivo."</p>";
			$dadosFollow[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dadosFollow[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
		}
		if($i>=1){
			$larguraFollow = "100%";
			$colunasFollow = "3";
			$dadosFollow[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
			$dadosFollow[colunas][tamanho][1] = "width=''";
			$dadosFollow[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
			$dadosFollow[colunas][tamanho][2] = "width='180px'";
			$dadosFollow[colunas][titulo][3] 	= "Data";
			$dadosFollow[colunas][tamanho][3] = "width='180px'";
		}

		/* PRODUTOS */
		$sql = "select Workflow_Produto_ID, pv.Produto_Variacao_ID as Produto_Variacao_ID, pd.Nome as Descricao_Produto, cd.Nome as Nome,
					pd.Codigo as Codigo, Quantidade as Quantidade, Valor_Venda_Unitario, Valor_Custo_Unitario
				from telemarketing_workflows_produtos twp
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = twp.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				inner join cadastros_dados cd on cd.Cadastro_ID = twp.Usuario_Cadastro_ID
					where Workflow_ID = '$workflowID' and twp.Situacao_ID = 1
				order by pd.Codigo";

		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;

			$dadosPagamento = unserialize($row[Dados]);

			$qtde = $row[Quantidade];
			$vendaUnit = $row[Valor_Venda_Unitario];
			$custoUnit = $row[Valor_Custo_Unitario];
			$totalVendaProduto = ($vendaUnit * $qtde);
			$totalVenda = ($totalVenda + $totalVendaProduto);
			$vendaUnit = number_format($vendaUnit, 2, ',', '.');
			$custoUnit = number_format($custoUnit, 2, ',', '.');
			$totalVendaProduto = number_format($totalVendaProduto, 2, ',', '.');
			$totalCustoProduto = number_format($totalCustoProduto, 2, ',', '.');

			$dadosProdutos[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".($row[Codigo])."</p>";
			$dadosProdutos[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".($row[Descricao_Produto])."</p>";
			$dadosProdutos[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$totalVendaProduto."</p>";
		}
		if ($i>0){
			$i++;
			$dadosProdutos[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'><b>TOTAL GERAL:</b></p>";
			$dadosProdutos[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'><b>".number_format($totalVenda, 2, ',', '.')."</b></p>";
		}

		$dadosProdutos[colunas][titulo][1] 	= "C&oacute;digo";
		$dadosProdutos[colunas][titulo][2] 	= "Descri&ccedil;&atilde;o";
		$dadosProdutos[colunas][titulo][3] 	= "Valor";
		$larguraProdutos = "100%";
		$colunasProdutos = "3";

		$dadosProdutos[colunas][tamanho][1] = "width='10%'";
		$dadosProdutos[colunas][tamanho][2] = "";
		$dadosProdutos[colunas][tamanho][3] = "width='180px'";
	}

}
?>
	<input type='hidden' id='workflow-id' name='workflow-id'  value='<?php echo $workflowID; ?>'/>
	<input type='hidden' id='localiza-workflow-id' name='localiza-workflow-id'  value='<?php echo $workflowID; ?>'/>
	<input type='hidden' id='tipo-processo-id' name='tipo-processo-id' value='37'/>
	<input type='hidden' id='localiza-cadastro-id' name='localiza-cadastro-id' value='<?php echo $solicitanteID; ?>'/>
	<input type='hidden' id='flag-pedido' name='flag-pedido' value='1'/>
	<div id='container-geral'>
		<div class='titulo-container'>
			<div class="titulo" style="min-height:25px">
				<p style="margin-top:2px;">
					Solicitante
					<!--<input type="button" value="Editar Solicitante" class='link-cadastro $classeEscondeInserir' cadastro-id='<?php echo $solicitanteID; ?>' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">-->
				</p>
			</div>

			<div class='conteudo-interno' id='conteudo-interno-solicitante'>
				<div id='div-solicitante' class='titulo-secundario uma-coluna'></div>
				<input type='hidden' id='quantidade-maxima-produtos-pedido' name='quantidade-maxima-produtos-pedido' value='2'/>
				<input type='hidden' id='solicitante-id' name='solicitante-id' value='<?php echo $solicitanteID; ?>'/>
				<input type='hidden' id='pedido-solicitante-id' name='pedido-solicitante-id' value='<?php echo $solicitanteID; ?>'/>
			</div>
		</div>
	</div>
	<div class="titulo-container <?php echo $classeEscondeInserir;?>" id='div-chamado-dados'>
		<div class='titulo' Style='min-height:25px'>
			<p style="margin-top:2px;">
				Pedido N&ordm; <?php echo $workflowID; ?> - <?php echo $situacaoAtual; ?>
			</p>
		</div>
		<div class='conteudo-interno titulo-secundario' id='conteudo-interno-chamado'>
			<div class='titulo-secundario' style='float:left;width:100%;'>
				<p><b>Produtos</b></p>
				<?php geraTabela($larguraProdutos,$colunasProdutos,$dadosProdutos); ?>
			</div>

			<div class='titulo-secundario' style='float:left;width:50%;'>
				<p><b>N&ordm; Protocolo: <?php echo $codigo; ?></b></p>
				<p></p>
				<input type="hidden" id="codigo-pedido" name="codigo-pedido" value="<?php echo utf8_encode($codigo); ?>"/>
				<input type="hidden" id="codigo-pedido-ant" name="codigo-pedido-ant" value="<?php echo utf8_encode($codigo);?>"/>
			</div>
			<div class='titulo-secundario' style='float:left;width:50%;'>
				<p><b>Representante</b></p>
				<p><?php echo $representante?></p><input type='hidden' name='fornecedor-id' id='fornecedor-id' value='<?php echo $fornecedorID;?>'>
			</div>

			<div class='titulo-secundario' style='float:left;width:100%;'>
				<p><b>Descri&ccedil;&atilde;o </b></p>
				<p class='omega'><textarea id='descricao-follow' name='descricao-follow' style='height:60px'><?php echo $descricaoCompleta; ?></textarea></p>
			</div>
			<?php
			if (($situacaoAtualID=="40")||($situacaoAtualID=="41")){
			?>
			<div class='titulo-secundario' style='width:85%;float:left;'>&nbsp;
				<input type='hidden' name='select-situacao-follow' id='select-situacao-follow' value='39'>
			</div>
			<div id='div-localizar-solicitante-cancelar' style='width:15%;float:left;'>
				<p>&nbsp;</p>
				<p><input type='button' value='Re-Abrir Pedido' id='botao-reabrir-pedido'  Style='width:95%'/></p>
			</div>
			<?php
			}
			else{
			?>
			<div class='titulo-secundario' style='width:85%;float:left;'>
				<p><b>Situa&ccedil;&atilde;o</b></p>
				<select name="select-situacao-follow" id="select-situacao-follow" style='width:98.5%'>
					<?php
						$optionValue = "<option value=''>Selecione</option>";
						$sql = "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 22 and Situacao_ID = 1 and Tipo_ID order by descr_tipo";
						$tipos = mpress_query($sql);
						while($tipo = mpress_fetch_array($tipos)){
							//if ($situacaoAtualID==$tipo['Tipo_ID']){$seleciona='selected';}else{$seleciona='';}
							$optionValue .= "<option value='".$tipo[Tipo_ID]."' $seleciona>".($tipo[Descr_Tipo])."</option>";
						}
						echo $optionValue;
					?>
				<select>
				</p>
			</div>
			<div id='div-select-motivo-follow' class='esconde' style='width:85%;float:left;'>
				<div style='width:50%;float:left;'>
					<p><b>Motivo</b></p>
					<p><select name="select-motivo-follow" id="select-motivo-follow" style='width:98.5%'><?php echo optionValueGrupo(23, $tipoEnderecoID); ?><select></p>
				</div>
				<div style='width:50%;float:left;' class='esconde' id='div-motivo-outros'>
					<p><b>Outros</b></p>
					<p><input type="text" id="motivo-outros" name="motivo-outros" value="" style='width:95%' maxlength='250'/></p>
				</div>
			</div>
			<div id='div-localizar-solicitante-cancelar' style='width:15%;float:left;'>
				<p>&nbsp;</p>
				<p><input type='button' value='Salvar' id='botao-cadastra-workflow'  Style='width:95%;'/></p>
			</div>
			<?php
			}
			?>
		</div>
	</div>
	<div class="titulo-container <?php echo $classeEscondeInserir;?>" id='div-chamado-dados'>
		<div class='titulo' Style='min-height:25px'>
			<p style="margin-top:2px;">
				Hist&oacute;rico
			</p>
		</div>
		<div class='titulo-secundario' style='width:100%;float:left;'>
			<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-historico'>
				<?php geraTabela($larguraFollow,$colunasFollow,$dadosFollow); ?>
			</div>
		</div>
	</div>
</div>
<script>
	$(".mascara-cep").mask("99999-999");
</script>