<?php$dadospagina = get_page_content();if ($_POST){	$id 			= $_POST['localiza-produto-id'];	$codigo 		= $_POST['localiza-produto-codigo'];	$tipoProdutoID 	= $_POST['localiza-tipo-produto'];	$descricao 		= $_POST['localiza-descricao-produto'];	$fornecedor 	= $_POST['localiza-fornecedor'];	$formaCobranca	= $_POST['localiza-forma-cobranca'];	$categorias 	= $_POST['localiza-categorias'];	$numeroSerie 	= $_POST['localiza-numero-serie'];	$situacaoID 	= $_POST['localiza-situacao-id'];	$exibirVariacoes = $_POST['exibir-variacoes'];	if ($_POST['exibir-variacoes']) $chkExibirVariacoes = "checked"; else $chkExibirVariacoes = "";	if ($_POST['exibir-lancamentos']) $chkExibirLancamentos = "checked"; else $chkExibirLancamentos = "";	if ($_POST['exibir-destaques']) $chkExibirDestaques = "checked"; else $chkExibirDestaques = "";}else{	$exibirVariacoes = '1';	$chkExibirVariacoes = "checked";	$situacaoID = '1';}echo "	<div id='cadastros-container'>			<div class='titulo-container'>				<div class='titulo'>					<p>Filtros de Pesquisa					<input type='button' value='Incluir Novo Produto' class='produto-localiza' produto-id='' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>					</p>				</div>				<div class='conteudo-interno'>					<div class='titulo-secundario' style='float:left; width:10%;'>						<p>ID Produto</p>						<p><input type='text' name='localiza-produto-id' id='localiza-produto-id' class='formata-numero' value='".$id."' style='width:93%;'></p>					</div>					<div class='titulo-secundario' style='float:left; width:10%;'>						<p>C�digo</p>						<p><input type='text' name='localiza-produto-codigo' id='localiza-produto-codigo' value='".$codigo."' style='width:93%;'></p>					</div>					<div class='titulo-secundario' style='float:left; width:30%'>						<p>Descri&ccedil;&atilde;o</p>						<p><input type='text' id='localiza-descricao-produto' name='localiza-descricao-produto' value='".$descricao."' style='width:98.5%'/></p>					</div>					<div class='titulo-secundario' style='float:left; width:25%'>						<p>Tipo</p>						<p><select id='localiza-tipo-produto' name='localiza-tipo-produto'>".optionValueGrupo(13, $tipoProdutoID,"&nbsp;")."</select></p>					</div>					<!--					<div class='titulo-secundario' style='float:left; width:25%'>						<p>Forma Pagamento</p>						<p><select id='localiza-forma-cobranca' name='localiza-forma-cobranca'>".optionValueGrupo(20, $formaCobranca,"&nbsp;")."</select></p>					</div>					-->					<div class='titulo-secundario' style='float:left; width:25%;'>						<p>Situa&ccedil;&atilde;o:</p>						<p><select name='localiza-situacao-id' id='localiza-situacao-id' class='required'>".optionValueGrupo(1, $situacaoID, '', 'and Tipo_ID IN (1,2)')."</select></p>					</div>					<div class='titulo-secundario' style='float:left; width:10%'>						<div class='titulo-secundario' style='float:left; width:96%'>							<p>Numero S�rie</p>							<p><select id='localiza-numero-serie' name='localiza-numero-serie'>".optionValueSimNao($numeroSerie,'&nbsp;')."</select></p>						</div>					</div>					<div class='titulo-secundario' style='float:left; width:20%'>						<p>Fornecedor</p>						<p><input type='text' id='localiza-fornecedor' name='localiza-fornecedor' value='".$fornecedor."' style='width:97%'/></p>					</div>					<div class='titulo-secundario' style='float:left; width:20%'>						<p>Categoria</p>						<p><select multiple id='localiza-categorias' name='localiza-categorias[]'>".optionValueCategorias($categorias)."</select></p>					</div>					<div class='titulo-secundario' style='float:left; width:25%'>						<p style='margin:10px 0 0 0;'><input type='checkbox' id='exibir-destaques' name='exibir-destaques' $chkExibirDestaques/><label for='exibir-destaques'>Filtrar Destaques</label></p>						<p><input type='checkbox' id='exibir-lancamentos' name='exibir-lancamentos' $chkExibirLancamentos/><label for='exibir-lancamentos'>Filtrar Lan&ccedil;amentos</label></p>					</div>					<div class='titulo-secundario' style='float:left; width:12.5%'>						<p style='margin:15px 0 10px 0;'><input type='checkbox' id='exibir-variacoes' name='exibir-variacoes' $chkExibirVariacoes/><label for='exibir-variacoes'>Exibir Varia��es</label></p>					</div>					<div class='titulo-secundario' style='float:left; width:12.5%'>						<p><input type='button' value='Pesquisar' id='botao-pesquisar-produtos' style='width:90%; margin-top:15px; margin-bottom:5px'/></p>					</div>				</div>			</div>		</div>		<input type='hidden' id='produto-id' name='produto-id' value=''/>		<input type='hidden' id='select-tipo-grupo-13' name='select-tipo-grupo-13' value=''/>";//if($_POST){	if ($id != ""){ $sqlCond .= " and pd.Produto_ID = $id ";}	if ($codigo != ""){ $sqlCond .= " and pd.Codigo like '$codigo' ";}	if ($tipoProdutoID != ""){ $sqlCond .= " and pd.Tipo_Produto = $tipoProdutoID ";}	if ($formaCobranca != ""){ $sqlCond .= " and pv.Forma_Cobranca_ID = $formaCobranca ";}	if ($descricao != ""){ $sqlCond .= " and (pd.Nome like '%$descricao%' or pd.Descricao_Resumida like '%$descricao%' or pv.Descricao like '%$descricao%' or concat(pd.Nome, ' ', pv.Descricao) like '%$descricao%')";}	if ($fornecedor != ""){ $sqlCond .= " and pd.Produto_ID IN (Select Produto_ID from produtos_fornecedores pf inner join cadastros_dados c on c.Cadastro_ID = pf.Cadastro_ID where Nome like '%$fornecedor%')";}	if ($numeroSerie != ""){ $sqlCond .= " and pd.Numero_Serie = '$numeroSerie'";}	if ($_POST['exibir-lancamentos'] != ""){ $sqlCond .= " and pd.Lancamento= '1'";}	if ($_POST['exibir-destaques'] != ""){ $sqlCond .= " and pd.Destaque = '1'";}	$virgula = "";	foreach ($categorias as $categoria){		$categoriaSQL .= $virgula.$categoria;		$virgula = ",";	}	if ($categoriaSQL!=""){ $sqlInnerCategorias = " inner join produtos_dados_categorias pdc on pdc.Produto_ID = pd.Produto_ID and pdc.Categoria_ID IN ($categoriaSQL)";}	if ($exibirVariacoes){		$sqlGroupBy = " group by pd.Produto_ID, pv.Produto_Variacao_ID, pd.Nome, pd.Descricao_Resumida, pd.Descricao_Completa, pd.Tipo_Produto, pd.Marca, tp.Descr_Tipo, map.Nome_Arquivo ";		$sqlCamposVariacao = ", pv.Produto_Variacao_ID, pv.Descricao, pv.Codigo as Codigo_Variacao, pv.Valor_Venda, mav.Nome_Arquivo as Nome_Arquivo_Variacao, pv.Data_Cadastro as Data_Cadastro_Variacao ";		$sqlLeftImagemVariacao = " left join modulos_anexos mav on mav.Anexo_ID = pv.Imagem_ID ";	}	else		$sqlGroupBy = "group by pd.Produto_ID, pd.Nome, pd.Descricao_Resumida, pd.Descricao_Completa, pd.Tipo_Produto, pd.Marca, tp.Descr_Tipo, map.Nome_Arquivo";	if($_POST['ordena-tabela'] != "")		$sqlOrdem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'].", pd.Produto_ID";	else		$sqlOrdem = " order by Nome, pd.Produto_ID ";	$sqlCat = "select distinct pd.Produto_ID, pc.Nome, pc.Categoria_ID				from produtos_dados pd				inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID				inner join produtos_dados_categorias pdc on pdc.Produto_ID = pd.Produto_ID and pdc.Situacao_ID = 1				inner join produtos_categorias pc on pc.Categoria_ID = pdc.Categoria_ID				where pd.Situacao_ID = 1 and pv.Situacao_ID = 1 and pd.Produto_ID > 0 and pv.Produto_Variacao_ID > 0 and pc.Situacao_ID = 1				$sqlCond ";	//echo $sqlCat;	$resultado = mpress_query($sqlCat);	while($rs = mpress_fetch_array($resultado)){		$arrayCategoriasProdutos[$rs[Produto_ID]] .= $rs[Nome].", ";	}	$sql = "select pd.Produto_ID as Produto_ID, pd.Nome, pd.Descricao_Resumida, pd.Descricao_Completa, pd.Tipo_Produto, pd.Marca, tp.Descr_Tipo as Tipo,				pd.Data_Cadastro as Data_Cadastro_Produto, map.Nome_Arquivo as Nome_Arquivo, pd.Codigo as Codigo, pv.Codigo as Codigo_Variacao,				(select count(*) from produtos_fornecedores pf where pf.Produto_ID = pd.Produto_ID AND pf.Situacao_ID = 1) as Qtd_Fornecedor,				pd.Destaque, pd.Lancamento				$sqlCamposVariacao			from produtos_dados pd			inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID			$sqlInnerCategorias			$sqlLeftImagemVariacao			left join modulos_anexos map ON map.Anexo_ID = (Select min(mapaux.Anexo_ID) from modulos_anexos mapaux where mapaux.Tabela_Estrangeira = 'produtos' AND mapaux.Chave_Estrangeira = pd.Produto_ID and mapaux.Situacao_ID = 1 limit 1)			left join tipo tp on tp.Tipo_ID = pd.Tipo_Produto and tp.Tipo_Grupo_ID = 13			where pd.Situacao_ID = 1 and pv.Situacao_ID = 1 and pd.Produto_ID > 0 and pv.Produto_Variacao_ID > 0			$sqlCond			$sqlGroupBy			$sqlOrdem";	//echo $sql;	$resultado = mpress_query($sql);	while($row = mpress_fetch_array($resultado)){		if ($row['Produto_ID'] != $produtoIDAnt){			$i++;			if ($row[Destaque]=="1") $destaque = "Sim"; else $destaque = "N&atilde;o";			if ($row[Lancamento]=="1") $lancamento = "Sim"; else $lancamento = "N&atilde;o";			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";			if ($row[Nome_Arquivo]!="") $nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];			$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='lnk produto-localiza' produto-id='".$row[Produto_ID]."'";			$dados[colunas][conteudo][$i][1] = "<p style='margin:1px;'><a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:20px; max-height:20px;' src='$nomeArquivo'></a></p>";			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Produto_ID]."</p>";			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p>";			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 0 0 5px;float:left;'>".$row[Nome]."</p>";			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 0 0 5px;float:left; font-size:10px;'>".substr($arrayCategoriasProdutos[$row[Produto_ID]],0,-2)."</p>";			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 0 0 5px;float:left;'>".$row[Tipo]."</p>";			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 10px 0 5px;float:right;'>".$row[Qtd_Fornecedor]."</p>";			$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 0 0 5px;' align='center'>".$destaque."</p>";			$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 0 0 5px;' align='center'>".$lancamento."</p>";			$dados[colunas][conteudo][$i][10] = "<center>".substr(converteData($row[Data_Cadastro_Produto],1),0,10)."</center>";		}		if ($exibirVariacoes){			$i++;			//$sqlCamposVariacao = ", pv.Produto_Variacao_ID, pv.Descricao, pv.Codigo as Codigo_Variacao, pv.Valor_Venda, mav.Nome_Arquivo as Nome_Arquivo_Variacao, pv.Data_Cadastro as Data_Cadastro_Variacao ";			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";			if ($row[Nome_Arquivo_Variacao]!="") $nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo_Variacao];			$dados[colunas][conteudo][$i][1] = "<p style='margin:1px;'><a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:20px; max-height:20px;' src='$nomeArquivo'></a></p>";			//$dados[colunas][conteudo][$i][2] = "<p Style='margin:1px 5px 0 5px;float:left;'>".$row[Produto_Variacao_ID]."</p>";			$dados[colunas][conteudo][$i][3] = "<p Style='margin:1px 5px 0 5px;float:left;'>".$row[Codigo_Variacao]."</p>";			//$dados[colunas][conteudo][$i][4] = "<p Style='margin:1px 0 0 30px;float:left;'>".$row[Codigo_Variacao]."</p>";			$dados[colunas][conteudo][$i][4] = "<p Style='margin:1px 0 0 30px;float:left;'>".$row[Nome]." ".$row[Descricao]."</p>";			//$dados[colunas][conteudo][$i][5] = "<p Style='margin:1px 0 0 5px;float:left;'>".$row[Tipo]."</p>";			//$dados[colunas][conteudo][$i][6] = "";			$dados[colunas][conteudo][$i][10] = "<center>".substr(converteData($row[Data_Cadastro_Variacao],1),0,10)."</center>";			$dados[colunas][classe][$i] = "tabela-fundo-claro";			$dados[colunas][classe][$i+1] = "tabela-fundo-escuro";		}		$produtoIDAnt = $row['Produto_ID'];	}	if($i==0){		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum produto localizado</p>";	}	else{		$largura = "100.2%";		$colunas = "10";		$dados[colunas][tamanho][1] = "width='30px'";		$dados[colunas][tamanho][2] = "width='5%'";		$dados[colunas][tamanho][3] = "width='5%'";		$dados[colunas][tamanho][4] = "";		$dados[colunas][tamanho][5] = "";		$dados[colunas][tamanho][6] = "";		$dados[colunas][tamanho][7] = "width='90px%'";		$dados[colunas][tamanho][8] = "width='90px'";		$dados[colunas][tamanho][9] = "width='90px'";		$dados[colunas][tamanho][10] = "width='90px'";		$dados[colunas][titulo][1] 	= "&nbsp;";		$dados[colunas][titulo][2] 	= "ID";		$dados[colunas][titulo][3] 	= "C�digo";		$dados[colunas][titulo][4] 	= "Nome";		$dados[colunas][titulo][5] 	= "Categorias";		$dados[colunas][titulo][6] 	= "Tipo";		$dados[colunas][titulo][7] 	= "<p Style='margin:2px 5px 0 5px;float:right;'>Fornecedores</p>";		$dados[colunas][titulo][8] 	= "<center>Destaque</center>";		$dados[colunas][titulo][9] 	= "<center>Lan&ccedil;amento</center>";		$dados[colunas][titulo][10] 	= "<center>Data Cadastro</center>";		$dados[colunas][ordena][2] = "pd.Produto_ID";		$dados[colunas][ordena][4] = "pd.Nome";		$dados[colunas][ordena][6] = "tp.Descr_Tipo";		$dados[colunas][ordena][7] = "Qtd_Fornecedor";		$dados[colunas][ordena][8] = "pd.Desquaque";		$dados[colunas][ordena][9] = "pd.Lancamento";		$dados[colunas][ordena][10] = "pd.Data_Cadastro";		echo "	<div class='titulo-container'>					<div class='titulo'>						<p>Registros localizados: $i</p>					</div>					<div class='conteudo-interno'>";		geraTabela($largura,$colunas,$dados, null, 'produtos-localiza', 2, 2, 100,1);		echo "		</div>				</div>";	}//}