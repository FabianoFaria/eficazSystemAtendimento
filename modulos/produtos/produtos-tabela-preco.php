<?php
	if($_POST['nome-tabela']!=''){
		$rs = mpress_query("select * from produtos_tabelas_precos where Titulo_Tabela = '".$_POST['nome-tabela']."' and Situacao_ID = 1");
		if(!$row = mpress_fetch_array($rs)){
			mpress_query("insert into produtos_tabelas_precos(Titulo_Tabela)values('".$_POST['nome-tabela']."')");
			$tabelaID = mpress_identity();
		}
	}
	if($_POST['tabela-preco-seleciona'] >= 1) $tabelaID = $_POST['tabela-preco-seleciona'];

	if($_POST['acao-tabela']!=''){
		if($_POST['acao-tabela']=='e')mpress_query("update produtos_tabelas_precos set Situacao_ID = 3 where Tabela_Preco_ID = ".$_POST['tabela-preco-seleciona']);
		//if($_POST['acao-tabela']=='a') atualizaProdutosTabela($tabelaID);
	}
	echo "	<input type='hidden' id='acao-tabela' name='acao-tabela'>";
	echo "	<div id='tabela-container'>
				<div class='titulo-container grupo1' id='div-dados-gerais'>
					<div class='titulo'>
						<p>
							Tabela para Edição";
	if($tabelaID != ""){
	 	echo "				<!--<input type='button' value='Salvar'  id='botao-atualiza-tabela' class='botao-atualiza-tabela'/>-->
	 						<input type='button' value='Excluir' id='botao-excluir-tabela' class='botao-excluir-tabela' />";
	}
	echo "				</p>
					</div>
					<div class='conteudo-interno'>
					<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>
						<select name='tabela-preco-seleciona' id='tabela-preco-seleciona' Style='padding:5px;margin-top:7px;margin-left:3px'>
							<option value=''>Selecione</option>
							<option value='-1'>Cadastrar nova tabela</option>";

	$rs = mpress_query("select Tabela_Preco_ID, Titulo_Tabela from produtos_tabelas_precos where Situacao_ID = 1 order by Titulo_Tabela");
	while($row = mpress_fetch_array($rs)){
		if($row['Tabela_Preco_ID'] == $tabelaID) $selecionado = "selected"; else $selecionado = "";
		echo "				<option value='".$row['Tabela_Preco_ID']."' $selecionado>".$row['Titulo_Tabela']."</option>";
	}
	echo "				</select>
					</div>
					<div class='titulo-secundario uma-coluna esconde' id='div-cadastra-tabela'>
					<p>
						<table Style='width:99.5%;margin-top:8px;' align='center'>
							<tr>
								<td width='096'>Nome da Tabela:</td>
								<td><input type='text' name='nome-tabela' id='nome-tabela' Style='padding:4px;width:99%'></td>
								<td width='100'><input type='button' value='cadastrar' id='cadastra-tabela'></td>
								<td width='100'><input type='button' value='cancelar'  id='cancela-tabela'></td>
							</tr>
						</table>
					</p>
				</div>
			</div>
		</div>
	</div>";

	if($tabelaID != ""){?>
	<div id='tabela-container'>
		<div class='titulo-container grupo1' id='div-produtos-cadastrados'>
			<div class='titulo'>
				<p>Produtos Cadastrados</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>
<?php
	$rs = mpress_query("select d.Produto_Variacao_ID, d.Valor_Custo, d.Valor_Venda
						from produtos_tabelas_precos p
						inner join produtos_tabelas_precos_detalhes d on d.Tabela_Preco_ID = p.Tabela_Preco_ID
						where p.Tabela_Preco_ID = '$tabelaID' and p.Situacao_ID = 1 and d.Situacao_ID = 1");
	while($row = mpress_fetch_array($rs)){
		$valoresTabelaPreco[$row['Produto_Variacao_ID']][custo] = $row['Valor_Custo'];
		$valoresTabelaPreco[$row['Produto_Variacao_ID']][venda] = $row['Valor_Venda'];
	}

	$rs = mpress_query("select pv.Produto_Variacao_ID, trim(concat(coalesce(p.Nome,''), ' ', coalesce(pv.Descricao,''))) as Descricao, pv.Valor_Custo, pv.Valor_Venda, p.Tipo_Produto as Tipo_Produto, upper(tp.Descr_Tipo) as Tipo_Produto_Descr
						from produtos_dados p
						inner join produtos_variacoes pv on pv.Produto_ID = p.Produto_ID
						inner join tipo tp on tp.Tipo_ID = p.Tipo_Produto
						where p.Situacao_ID = 1 and pv.Situacao_ID = 1 /*and tipo_Produto = 31*/
						/*and pv.Forma_Cobranca_ID = 35*/
						order by Tipo_Produto, Descricao");
	while($row = mpress_fetch_array($rs)){
		$produtoVariacaoID = $row['Produto_Variacao_ID'];
		if ($row['Tipo_Produto']!=$tipoProdutoAnt){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p align='center' style='margin:5px;0;5px;0;'>".$row['Tipo_Produto_Descr']."</p>";
			$dados[colunas][colspan][$i][1] = "6";
			$dados[colunas][classe][$i] = "destaque-tabela";
		}
		$i++;
		$dados[colunas][conteudo][$i][1] = "<input type='checkbox' Style='margin-left:1px' checked name='seleciona-produto_tabela' value='".$row['Produto_Variacao_ID']."' class='seleciona-produto-tabela'>
									 <input type='hidden' name='produto-selecionado[]' id='produto-selecionado-".$row['Produto_Variacao_ID']."' value='".$row['Produto_Variacao_ID']."'>";
		$dados[colunas][conteudo][$i][2] = "&nbsp;&nbsp;".$row['Descricao']."<span id='mensagem-atualiza-$produtoVariacaoID'></span>";
		$dados[colunas][conteudo][$i][3] = "<center>".number_format($row['Valor_Custo'],2,',','.')."</center>";
		$dados[colunas][conteudo][$i][4] = "<center>".number_format($row['Valor_Venda'],2,',','.')."</center>";
		$dados[colunas][conteudo][$i][5] = "<center><input type='text' Style='margin-left:1px;width:90%;text-align:center;' name='preco-custo[]' id='pc-$produtoVariacaoID' value='".number_format($valoresTabelaPreco[$row['Produto_Variacao_ID']]['custo'], 2, ',','.')."' class='formata-valor'></center>";
		$dados[colunas][conteudo][$i][6] = "<center><input type='text' Style='margin-left:1px;width:90%;text-align:center;' name='preco-venda[]' id='pv-$produtoVariacaoID' value='".number_format($valoresTabelaPreco[$row['Produto_Variacao_ID']]['venda'], 2, ',','.')."' class='formata-valor'></center>";
		$dados[colunas][conteudo][$i][7] = "<center><input type='button' Style='width:50px; height:20px; text-align:center; font-size:9px' class='atualiza-valor-produto-tabela' prodVarID='$produtoVariacaoID' value='Atualizar'></center>";
		$tipoProdutoAnt = $row['Tipo_Produto'];
	}
	$largura = "100.2%";
	$colunas = "10";
	$dados[colunas][tamanho][1] = "width='20'";
	$dados[colunas][tamanho][2] = "";
	$dados[colunas][tamanho][3] = "width='100'";
	$dados[colunas][tamanho][4] = "width='100'";
	$dados[colunas][tamanho][5] = "width='125'";
	$dados[colunas][tamanho][6] = "width='125'";
	$dados[colunas][tamanho][7] = "width='60'";

	$dados[colunas][titulo][1] 	= "";
	$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;'>Produto</p>";
	$dados[colunas][titulo][3] 	= "<p Style='margin:2px 5px 0 13px;'>Valor Custo</p>";
	$dados[colunas][titulo][4] 	= "<p Style='margin:2px 5px 0 13px;'>Valor Venda</p>";
	$dados[colunas][titulo][5] 	= "<p Style='margin:2px 5px 0 5px;'>Valor Custo Tabela</p>";
	$dados[colunas][titulo][6]  = "<p Style='margin:2px 5px 0 4px;'>Valor Venda Tabela</p>";
	geraTabela('100.2%',7,$dados);
?>

				</div>
			</div>
		</div>
	</div>
<?php }?>
<?php
	/*
	function atualizaProdutosTabela($tabelaID){
		//echo "123";
		//exit();
		$tabela['produtoVariacaoID'] 	= $_POST['produto-selecionado'];
		$tabela['precoCusto'] 		= $_POST['preco-custo'];
		$tabela['precoVenda'] 		= $_POST['preco-venda'];
		$i=0;
		mpress_query("update produtos_tabelas_precos_detalhes set Situacao_ID = 2 where Tabela_Preco_ID = '$tabelaID'");
		foreach($tabela['produtoVariacaoID'] as $produto){
			if($produto != ""){
				if($tabela['precoCusto'][$i] == "") $tabela['precoCusto'][$i] = "0.00";
				if($tabela['precoVenda'][$i] == "") $tabela['precoVenda'][$i] = "0.00";
				$tabela['precoCusto'][$i] = str_replace(',','.',str_replace('.','',$tabela['precoCusto'][$i]));
				$tabela['precoVenda'][$i] = str_replace(',','.',str_replace('.','',$tabela['precoVenda'][$i]));
				//echo "insert into produtos_tabelas_precos_detalhes(Tabela_Preco_ID, Produto_Variacao_ID, Valor_Custo, Valor_Venda)values('$tabelaID', '$produto', '".$tabela['precoCusto'][$i]."','".$tabela['precoVenda'][$i]."')";
				//exit();
				mpress_query("insert into produtos_tabelas_precos_detalhes(Tabela_Preco_ID, Produto_Variacao_ID, Valor_Custo, Valor_Venda)values('$tabelaID', '$produto', '".$tabela['precoCusto'][$i]."','".$tabela['precoVenda'][$i]."')");
			}
			$i++;
		}
	}
	*/
?>
