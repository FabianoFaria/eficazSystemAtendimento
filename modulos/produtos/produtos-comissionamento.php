<?php
	
	function atualizaProdutosTabelaComissao($tabelaID){
		$i=0;
		mpress_query("update produtos_tabelas_comissoes_detalhes set Situacao_ID = 2 where Tabela_Comissao_ID = '$tabelaID'");
		foreach($_POST['produto-variacao-id'] as $produtoVariacaoID){
			$percentual = formataValorBD($_POST['percentual'][$i]);
			if ($percentual>0){
				mpress_query("insert into produtos_tabelas_comissoes_detalhes (Tabela_Comissao_ID, Produto_Variacao_ID,  Percentual_Comissao, Situacao_ID)
															values('$tabelaID', '$produtoVariacaoID', $percentual, 1)");
			}
			$i++;
		}
	}

	if($_POST['nome-tabela']!=''){
		$rs = mpress_query("select * from produtos_tabelas_comissoes where Titulo_Tabela = '".$_POST['nome-tabela']."' and Situacao_ID = 1");
		if(!$row = mpress_fetch_array($rs)){
			mpress_query("insert into produtos_tabelas_comissoes(Titulo_Tabela)values('".$_POST['nome-tabela']."')");
			$tabelaID = mpress_identity();
		}
	}
	if($_POST['tabela-comissao-seleciona'] > 0) $tabelaID = $_POST['tabela-comissao-seleciona'];
	if($_POST['acao-tabela']!=''){
		if($_POST['acao-tabela']=='e'){ 
			mpress_query("update produtos_tabelas_comissoes set Situacao_ID = 2 where Tabela_Comissao_ID = ".$_POST['tabela-comissao-seleciona']);
			mpress_query("update produtos_tabelas_comissoes_detalhes set Situacao_ID = 2 where Tabela_Comissao_ID = ".$_POST['tabela-comissao-seleciona']);
		}
		if($_POST['acao-tabela']=='a') 
			atualizaProdutosTabelaComissao($tabelaID);
	}
?>
<div id='tabela-container'>
	<div class='titulo-container grupo1' id='div-dados-gerais'>
		<div class='titulo'>
			<p>
				Tabela para Edição
<?php if($tabelaID != ""){?>
				<input type='button' value='Salvar'  id='botao-atualiza-tabela-comissao' class='botao-atualiza-tabela-comissao'/>
				<input type='button' value='Excluir' id='botao-excluir-tabela-comissao' class='botao-excluir-tabela-comissao lixeira' />
<?}?>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>
				<select name='tabela-comissao-seleciona' id='tabela-comissao-seleciona' Style='padding:5px;margin-top:7px;margin-left:3px'>
					<option value=''>Selecione</option>
					<option value='-1'>Cadastrar nova tabela</option>
<?php
		$rs = mpress_query("select Tabela_Comissao_ID, Titulo_Tabela from produtos_tabelas_comissoes where Situacao_ID = 1 order by Titulo_Tabela");
		while($row = mpress_fetch_array($rs)){
			if($row['Tabela_Comissao_ID'] == $tabelaID) $selecionado = "selected"; else $selecionado = "";
			echo "	<option value='".$row['Tabela_Comissao_ID']."' $selecionado>".$row['Titulo_Tabela']."</option>";
		}
?>
				</select>
			</div>
			<div class='titulo-secundario uma-coluna esconde' id='div-cadastra-tabela'>
				<p>
					<table Style='width:99.5%;margin-top:8px;' align='center'>
						<tr>
							<td width='096'>Nome da Tabela:</td>
							<td><input type='text' name='nome-tabela' id='nome-tabela' Style='padding:4px;width:99%'></td>
							<td width='100'><input type='button' value='Cadastrar' id='cadastra-tabela-comissao'></td>
							<td width='100'><input type='button' value='Cancelar'  id='cancela-tabela-comissao'></td>
						</tr>
					</table>
				</p>
			</div>
		</div>
	</div>
</div>

<?php if($tabelaID != ""){?>
	<div id='tabela-container'>
		<div class='titulo-container grupo1' id='div-produtos-cadastrados'>
			<div class='titulo'>
				<p>
					Produtos e Serviços Cadastrados
					<input type='text' value='0,00' style='float:right; width:80px;text-align:center; margin-top:-6px; margin-right:1px' id='comissao-geral' class='formata-valor' />
					<input type='button' value='Replicar'  id='replicar-comissao' class='replicar-comissao'/>
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>
<?php
	$rs = mpress_query("select Produto_Variacao_ID, Percentual_Comissao from produtos_tabelas_comissoes_detalhes where Tabela_Comissao_ID = '$tabelaID' and Situacao_ID = 1");
	while($row = mpress_fetch_array($rs))
		$prod[$row['Produto_Variacao_ID']][percentual] = $row['Percentual_Comissao'];
	
	$sql = "select pv.Produto_Variacao_ID, trim(concat(coalesce(p.Nome,''), ' ', coalesce(pv.Descricao,''))) as Descricao, t.Descr_Tipo as Tipo_Produto
			 from produtos_dados p 
			 inner join produtos_variacoes pv on pv.Produto_ID = p.Produto_ID 
			 inner join tipo t on t.Tipo_ID = p.Tipo_Produto 
			 inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
			 where p.Situacao_ID = 1 and pv.Situacao_ID = 1 and p.Situacao_ID = 1 order by Descricao";
	//echo $sql; 
	$rs = mpress_query($sql);
	while($row = mpress_fetch_array($rs)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;".$row[Descricao];
		$dados[colunas][conteudo][$i][2] = "&nbsp;&nbsp;".$row[Tipo_Produto];
		$dados[colunas][conteudo][$i][3] = "<center>
										<input type='hidden' name='produto-variacao-id[]' value='".$row['Produto_Variacao_ID']."'/>
										<input type='text' name='percentual[]' value='".number_format($prod[$row['Produto_Variacao_ID']][percentual], 2, ',','.')."' class='formata-valor comissao-lista' Style='width:80px;text-align:center;'/>
									</center>";
	}
	$largura = "100.2%";
	$colunas = "10";
	$dados[colunas][tamanho][1] = "";
	$dados[colunas][tamanho][2] = "width='100'";
	$dados[colunas][tamanho][3] = "width='100'";
	$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 5px;'>Produto</p>";
	$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;'>Tipo</p>";
	$dados[colunas][titulo][3] 	= "<p Style='margin:2px 5px 0 13px;'>Comissao %</p>";
	geraTabela('100.2%',3,$dados);
?>

				</div>
			</div>
		</div>
	</div>
<?php }?>
<input type='hidden' id='acao-tabela' name='acao-tabela'>
