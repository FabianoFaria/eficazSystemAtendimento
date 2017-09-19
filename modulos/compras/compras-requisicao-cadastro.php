<?php
	global $modulosAtivos;
	$solicitacaoID = $_POST["localiza-solicitacao-id"];
	if ($solicitacaoID!=""){
		$sql = "Select Chave_Estrangeira, Tabela_Estrangeira, Produto_Variacao_ID, Quantidade, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID, Produto_Movimentacao_ID
					from compras_solicitacoes where Compra_Solicitacao_ID = '$solicitacaoID'";
		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$chaveEstrangeiras = $rs[Chave_Estrangeira];
			$tabelaEstrangeira = $rs[Tabela_Estrangeira];
			$produtoVariacaoID = $rs[Produto_Variacao_ID];
			$quantidade = $rs[Quantidade];
			$dataCadastro = $rs[Data_Cadastro];
			$usuarioCadastroID = $rs[Usuario_Cadastro_ID];
			$situacaoID = $rs[Situacao_ID];
			$produtoMovimentacaoID = $rs[Produto_Movimentacao_ID];
		}
	}

	$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
				pv.Forma_Cobranca_ID, f.Descr_Tipo as Forma_Cobranca, case pv.Forma_Cobranca_ID when 35 then pv.Valor_Venda else '' end as Valor_Venda, pd.Produto_ID as Produto_ID
				FROM produtos_dados pd
				INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
				inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
				WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
				and Tipo_Produto IN (30,175)
			ORDER BY Descricao_Produto";
	$selectProdutos = "<select id='select-produtos' name='select-produtos' Style='width:98.5%' data-placeholder='Selecione'>
							<option value='' produto-id=''>Selecione</option>";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($row['Valor_Venda']!="") $valorVenda = ":&nbsp;".number_format($row['Valor_Venda'], 2, ',', '.'); else $valorVenda = "";
		if ($row['Produto_Variacao_ID']==$produtoVariacaoID) $selecionado = "selected"; else $selecionado = "";
		$selectProdutos .= "<option value='".$row[Produto_Variacao_ID]."' produto-id='".$row[Produto_ID]."' $selecionado>".($row['Descricao_Produto'])."</option>";
	}
	$selectProdutos .= "</select>";

echo "	<input type='hidden' id='solicitacao-id'  name='solicitacao-id' value='$solicitacaoID'>
		<div id='chamados-container'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>Requisi&ccedil;&atilde;o de Produto</p>
				</div>
				<div class='conteudo-interno titulo-secundario' id='conteudo-interno-produtos'>
					<div id='div-produtos-incluir-editar' Style='float:left;width:100%;'>
						<div style='float:left; width:50%;'>
							<p>Selecione o Produto</p>
							<p>$selectProdutos</p>
						</div>
						<div style='float:left;width:25%;'>
							<p><b>Quantidade</b></p>
							<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='1,00' class='formata-valor' style='width:90%' maxlength='10'/></p>
						</div>
						<div id='div-produtos-incluir' style='width:12.5%; float:left;'>
							<p>&nbsp;</p>
							<input type='button' id='botao-incluir-produtos' name='botao-incluir-produtos' Style='width:95%' value='Incluir' >
						</div>
						<!--
						<div id='div-produtos-cancelar' style='width:12.5%; float:left;'>
							<p>&nbsp;</p>
							<input type='button' value='Cancelar' id='botao-cancelar-produtos' class='botao-cancelar-produtos' Style='width:95%'/>
						</div>
						-->
					</div>
					<div id='div-produtos' class='titulo-secundario uma-coluna' style='margin-top:5px;'></div>
				</div>
			</div>
		</div>";

?>
