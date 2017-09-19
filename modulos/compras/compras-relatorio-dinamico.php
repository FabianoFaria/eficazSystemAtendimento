<?php
	global $caminhoSistema;
	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1) $escondeEmpresa = "esconde";

	$id 	= $_POST['localiza-compra-id'];
	$responsavelID = $_POST['localiza-responsavel-id'];
	$cadastroID = $_POST['localiza-cadastro-id'];


	$dataInicioCompra = $_POST['data-inicio-compra'];
	$dataFimCompra = $_POST['data-fim-compra'];
	$dataInicioLimite = $_POST['data-inicio-limite'];
	$dataFimLimite = $_POST['data-fim-limite'];

	for($i = 0; $i < count($_POST['localiza-compra-situacao']); $i++){
		$situacoes .= $virgula.$_POST['localiza-compra-situacao'][$i];
		$virgula = ",";
	}
	$localizaProduto = $_POST['localiza-produto'];

?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">Localizar Compras</p>
	</div>

	<input type='hidden' id='ordem-compra-id' name='ordem-compra-id' >
	<div class="conteudo-interno">
		<div class="titulo-secundario" style='width:100%;float:left;'>
			<div class="titulo-secundario" style='width:10%;float:left;'>
				<p>Ordem Compra ID:</p>
				<p><input type='text' name='localiza-compra-id' id='localiza-compra-id' class='formata-numero' value='<?php echo $id; ?>' style='width:85%;'></p>
			</div>
			<div class="titulo-secundario" style='width:25%;float:left;'>
				<p>Data Inclus&atilde;o O.C.:</p>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='data-inicio-compra' id='data-inicio-compra' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioCompra; ?>'>&nbsp;&nbsp;</p>
				</div>
				<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
				<div style='width:43%;float:left;'>
					<p><input type='text' name='data-fim-compra' id='data-fim-compra' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimCompra; ?>'></p>
				</div>
			</div>
			<div class="titulo-secundario" style='width:40%;float:left;'>
				<p>Situa&ccedil;&atilde;o Compra:</p>
				<p><select name="localiza-compra-situacao[]" id="localiza-compra-situacao" multiple><?php echo optionValueGrupoMultiplo(32, $situacoes);?><select></p>
			</div>
			<div class="titulo-secundario" style='width:25%;float:left;'>
				<p>Respons&aacute;vel:</p>
				<p><select name="localiza-responsavel-id" id="localiza-responsavel-id">
					<option value=''>Todos</option>
	<?php
		$resultado = mpress_query("select distinct cd.Cadastro_ID, cd.Nome from compras_ordem_compra c inner join cadastros_dados cd on cd.Cadastro_ID = c.Usuario_Cadastro_ID");
		while($row = mpress_fetch_array($resultado)){
			if ($responsavelID==$row[Cadastro_ID]) $selecionado = "selected"; else $selecionado = "";
			echo "	<option value='$row[Cadastro_ID]' $selecionado>$row[Nome]</option>";
		}
	?>
					<select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario" style='width:35%;float:left;'>
			<p>Produto:</p>
			<p><input type='text' name="localiza-produto" id="localiza-produto" value='<?php echo $_POST['localiza-produto'];?>' style='width:95%'></p>
		</div>
		<div class="titulo-secundario" style='float:left;width:40%;'>
			<div class='<?php echo $escondeEmpresa;?>'>
				<p>Empresa Compra:</p>
				<p>
					<select id='localiza-cadastro-id' name='localiza-cadastro-id' style='width:98.8%'><option value=''>Selecione</option>
					<?php echo optionValueEmpresas($cadastroID); ?>
					</select>
				</p>
			</div>
			&nbsp;
		</div>
		<div class="titulo-secundario" Style='width:20%; margin-top:15px; float:right;'>
			<p align='right'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-localizar-compras'></p>
		</div>
	</div>
</div>
<?php
if($_POST){
	$i = 0;
	if($id!="") 			$condicoes .= " and c.Ordem_Compra_ID = '$id' ";
	if($situacoes!="") 		$condicoes .= " and cf.Situacao_ID IN ($situacoes)";
	if($responsavelID!="") 	$condicoes .= " and c.Usuario_Cadastro_ID = '$responsavelID'";
	if($cadastroID!="") 	$condicoes .= " and c.Cadastro_ID = '$cadastroID'";
	if($localizaProduto!="") 	$condicoes .= " and concat(pd.Nome,' ',pv.Descricao) like '%$localizaProduto%'";


	if(($dataInicioCompra!="")||($dataFimCompra!="")){
		$dataInicioCompra = implode('-',array_reverse(explode('/',$dataInicioCompra)));
		if ($dataInicioCompra=="") $dataInicioCompra = "0000-00-00"; $dataInicioCompra .= " 00:00";
		$dataFimCompra = implode('-',array_reverse(explode('/',$dataFimCompra)));
		if ($dataFimCompra=="") $dataFimCompra = "2100-01-01"; $dataFimCompra .= " 23:59";
		$condicoes .= " and c.Data_Cadastro between '$dataInicioCompra' and '$dataFimCompra' ";
	}
	$sql = "select c.Ordem_Compra_ID,
				c.Data_Cadastro as Data_Cadastro, cd.Nome as Responsavel_Compra,
				cf.Situacao_ID, s.Descr_Tipo as Situacao, cocp.Compra_Solicitacao_ID, upper(cs.Tabela_Estrangeira) as Tabela_Estrangeira, cs.Chave_Estrangeira,
				concat(pd.Nome,' ',pv.Descricao) as Produto, cs.Quantidade as Quantidade,
				cocf.Quantidade_Aprovada, cocf.Valor_Aprovado as Valor_Unitario, cocp.Dados,
				cfa.Data_Cadastro as Data_Aprovacao
				from compras_ordem_compra c
				inner join cadastros_dados cd on cd.Cadastro_ID = c.Usuario_Cadastro_ID
				inner join compras_ordens_compras_produtos cocp on cocp.Ordem_Compra_ID = c.Ordem_Compra_ID
				inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cocp.Compra_Solicitacao_ID
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				left join compras_ordem_compras_finalizadas cocf on cocf.Ordem_Compra_ID = c.Ordem_Compra_ID and cocf.Ordem_Compra_Produto_ID = cocp.Ordens_Compras_Produtos_ID
				left join compras_ordem_compra_follows cf on cf.Ordem_Compra_ID = c.Ordem_Compra_ID
					and cf.Ordem_Compra_Follow_ID = (select max(cfaux.Ordem_Compra_Follow_ID) from compras_ordem_compra_follows cfaux where cfaux.Ordem_Compra_ID = c.Ordem_Compra_ID)
				left join compras_ordem_compra_follows cfa on cfa.Ordem_Compra_ID = c.Ordem_Compra_ID
					and cfa.Ordem_Compra_Follow_ID = (select max(cfaux2.Ordem_Compra_Follow_ID) from compras_ordem_compra_follows cfaux2 where cfaux2.Ordem_Compra_ID = c.Ordem_Compra_ID and cfaux2.Situacao_ID = 104)
				left join tipo s on s.Tipo_ID = cf.Situacao_ID
				where c.Ordem_Compra_ID > 0
				$condicoes
				order by c.Ordem_Compra_ID desc";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$d = unserialize($row['Dados']);
		$quantidade = $d['QuantidadeAprovada'];
		$valor = $d['ValorAprovado'];
		$observacao = $d['Observacao'];
		$fornecedor = $d['Fornecedor'];
		$linkcompra = " class='link workflow-compra' ordem-compra-id='$row[Ordem_Compra_ID]'";
		$chaveEstrangeira = $row[Chave_Estrangeira];
		if ($chaveEstrangeira==0) $chaveEstrangeira = "";
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:right;' $linkcompra>".$row[Ordem_Compra_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Responsavel_Compra]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Produto]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Tabela_Estrangeira]." ".$chaveEstrangeira."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($quantidade, 2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($valor, 2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format(($quantidade * $valor), 2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format(($row[Quantidade] * $row[Valor_Unitario]), 2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;'>".converteDataHora($row[Data_Cadastro],1)."</p>";
		$dados[colunas][conteudo][$i][11] = "<p Style='margin:2px 3px 0 3px;float:left;'>".converteDataHora($row[Data_Aprovacao],1)."</p>";
	}
	$largura = "100%";
	$colunas = "11";
	$dados[colunas][titulo][1] = "Ordem Compra";
	$dados[colunas][titulo][2] = "Respons&aacute;vel";
	$dados[colunas][titulo][3] = "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][4] = "Produto";
	$dados[colunas][titulo][5] = "Origem";
	$dados[colunas][titulo][6] = "<p align='right' Style='margin:2px 3px 0 3px;'>Quantidade</p>";
	$dados[colunas][titulo][7] = "<p align='right' Style='margin:2px 3px 0 3px;'>Valor Unit&aacute;rio Aprovado</p>";
	$dados[colunas][titulo][8] = "<p align='right' Style='margin:2px 3px 0 3px;'>Valor Total Aprovado</p>";
	$dados[colunas][titulo][9] = "<p align='right' Style='margin:2px 3px 0 3px;'>Valor Total Faturado</p>";
	$dados[colunas][titulo][10] = "Data Inclus&atilde;o";
	$dados[colunas][titulo][11] = "Data Aprova&ccedil;&atilde;o";

	$dados[colunas][tamanho][1] = "width='090px'";
	$dados[colunas][tamanho][2] = "";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][4] = "";
	$dados[colunas][tamanho][5] = "";
	$dados[colunas][tamanho][10] = "width='105px'";
	$dados[colunas][tamanho][11] = "width='105px'";

	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Registros Localizados: $i</p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
	geraTabela($largura, $colunas, $dados, "", "compras-relatorio-dinamico", 2, 2, "100", "1", '');
	echo "		</div>
			</div>";
}

?>