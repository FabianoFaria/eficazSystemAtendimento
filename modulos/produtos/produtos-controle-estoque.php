<?php
if ($_POST){
	$codigo = $_POST['localiza-produto-codigo'];
	$descricao = $_POST['localiza-descricao-produto'];
	$numeroSerie = $_POST['numero-serie'];
	$tipoProdutoID = $_POST['localiza-tipo-produto'];
	if ($_POST['exibir-zerados']){
		$chkExibirZerados = "checked";
	}
}
else{
	$chkExibirZerados = "checked";
}

echo "	<div id='produtos-container'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Filtros de Pesquisa
						<input type='button' class='esconde botao-movimentacao-material' style='float:right;margin-right:0px; width:150px' value='Entrada / Sa&iacute;da Material'/>
					</p>
				</div>

				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left; width:10%'>
						<p>C&oacute;digo</p>
						<p><input type='text' id='localiza-produto-codigo' name='localiza-produto-codigo' value='$codigo' style='width:90%'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:40%'>
						<p>Descri&ccedil;&atilde;o Produto</p>
						<p><input type='text' id='localiza-descricao-produto' name='localiza-descricao-produto' value='$descricao' style='width:98.5%'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Tipo</p>
						<p><select id='localiza-tipo-produto' name='localiza-tipo-produto'>".optionValueGrupo(13, $tipoProdutoID,"&nbsp;", " and Tipo_ID in (30,175)")."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%'>
						<p>Número Série</p>
						<p><select id='numero-serie' name='numero-serie'><option></option>".optionValueSimNao($numeroSerie)."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%; margin-top:12px;'>
						<p align='center'><input type='checkbox' name='exibir-zerados' id='exibir-zerados' value='true' ".$chkExibirZerados." >Não exibir produtos com estoque zerado</p>
					</div>
					<div class='titulo-secundario' style='float:right; width:10%'>
						<p><input type='button' value='Pesquisar' id='botao-pesquisar-produtos' style='width:90%; margin-top:14px; float:right;'></p>
					</div>
				</div>
			</div>
		</div>
		<input type='hidden' name='produto-id' id='produto-id' value=''/>";

	if ($codigo != ""){ $sqlCond .= " and pv.Codigo = '$codigo' ";}
	if ($descricao != ""){ $sqlCond .= " and (concat(pd.Nome, ' ', pv.Descricao) like '%$descricao%' or pd.Descricao_Resumida like '%$descricao%')";}
	//if ($descricao != ""){ $sqlCond .= " and (pd.Nome like '%$descricao%' or pd.Descricao_Resumida like '%$descricao%' or pv.Descricao like '%$descricao%')";}
	if ($numeroSerie != ""){ $sqlCond .= " and pd.Numero_Serie = '$numeroSerie'";}
	if ($tipoProdutoID != ""){ $sqlCond .= " and pd.Tipo_Produto = '$tipoProdutoID'";}



	$sql = "select pd.Produto_ID as Produto_ID, pv.Codigo, concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Nome, Descricao_Resumida, Descricao_Completa, Tipo_Produto, Marca,
			Estoque_Minimo, Compra_Minima, Utilizacao_Media, Prazo_Medio_Entrega, Quantidade_Embalagem,";

	$resultado = mpress_query("SELECT CD_ID, Descricao FROM envios_centros_distribuicao where Situacao_ID = 1");
	while($rs = mpress_fetch_array($resultado)){
		$arrCD[$rs['CD_ID']][descricao] = $rs[Descricao];
		$cdID  = $rs['CD_ID'];
		$cdIDAux = str_replace("-", "_", $rs['CD_ID']);
		$sql .= " if (pd.Numero_Serie = 1,
						(SELECT SUM(pma.Quantidade) FROM produtos_movimentacoes pma WHERE pma.Produto_Variacao_ID = pv.Produto_Variacao_ID /* AND pma.Situacao_ID = 1*/ AND pma.Numero_Serie != '' AND pma.CD_ID = '$cdID'),
						(SELECT SUM(pmb.Quantidade) FROM produtos_movimentacoes pmb WHERE pmb.Produto_Variacao_ID = pv.Produto_Variacao_ID /* AND pmb.Situacao_ID = 1*/ AND pmb.CD_ID = '$cdID'))
					AS 'Quantidade_".$cdIDAux."',";
		if ($chkExibirZerados=='checked'){
			$sqlHaving .= $orHaving." Quantidade_".$cdIDAux." <> 0 ";
			$orHaving = " OR ";
		}
	}

	if ($chkExibirZerados=='checked'){
		$sqlHaving = " having (".$sqlHaving.")";
	}

	$sql .= " pd.Numero_Serie as Numero_Serie, mav.Nome_Arquivo as Nome_Arquivo
			from produtos_dados pd
			left join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
			left join produtos_estoque pe on pe.Produto_Variacao_ID = pv.Produto_Variacao_ID
			left join modulos_anexos mav on mav.Anexo_ID = pv.Imagem_ID
			where pd.Situacao_ID = 1 and Tipo_Produto in (30,175) and pv.Situacao_ID = 1
			$sqlCond
			$sqlHaving
			order by pv.Codigo, Nome";
	//echo $sql;

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
		if ($row[Nome_Arquivo]!="")
			$nomeArquivo = $caminhoSistema."/uploads/".$row[Nome_Arquivo];
		else{
			if ($rsAux = mpress_fetch_array(mpress_query("select Nome_Arquivo from modulos_anexos where Chave_Estrangeira = '".$row[Produto_ID]."' and Tabela_Estrangeira = 'produtos' and Situacao_ID = 1 limit 1"))){
				$nomeArquivo = $caminhoSistema."/uploads/".$rsAux[Nome_Arquivo];
			}
		}
		$imagemProduto = "<a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:25px; max-height:25px' align='center' src='$nomeArquivo'/></a>";

		if ($row['Numero_Serie']==1) $numeroSerie = "SIM"; else $numeroSerie = "N&Atilde;O";
		$c=1;
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left;' class='link produto-localiza' produto-id='".$row[Produto_ID]."'>".$row[Codigo]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:1px 1px 1px 1px; float:left;'> $imagemProduto </p><p Style='margin:2px 0px 0 0px;float>left;'	class='link produto-localiza' produto-id='".$row[Produto_ID]."'>".$row[Nome]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".$numeroSerie."</p>";

		$totQtdeCD = 0;
		foreach($arrCD as $chave => $cd){
			$chave = str_replace("-", "_", $chave);
			$qtdeCD = $row["Quantidade_".$chave];
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($qtdeCD, 2, ',', '.')."</p>";
			$totQtdeCD += $qtdeCD;
		}
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($totQtdeCD, 2, ',', '.')."</p>";
		/*
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($row[Estoque_Minimo],2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($row[Compra_Minima],2, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($row[Utilizacao_Media],2, ',', '.')." </p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($row[Prazo_Medio_Entrega],0, ',', '.')." </p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 0px 0 0px;text-align:center;'>".number_format($row[Quantidade_Embalagem],0, ',', '.')."</p>";
		$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 0px 0 0px;text-align:center;'><b>".number_format($row[Quantidade],2, ',', '.')."</b></p>";
		*/
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum produto localizado!</p>";
	}
	else{
		$largura = "100.2%";
		$dados[colunas][tamanho][1] = "width='70px'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='090px'";
		//$dados[colunas][tamanho][4] = "width='100px'";
		//$dados[colunas][tamanho][5] = "width='100px'";
		//$dados[colunas][tamanho][6] = "width='130px'";
		//$dados[colunas][tamanho][7] = "width='090px'";
		//$dados[colunas][tamanho][8] = "width='145px'";
		$c=1;
		$dados[colunas][titulo][$c++] 	= "<p Style='margin:2px 5px 0 5px;text-align:center;'>C&oacute;digo</p>";
		$dados[colunas][titulo][$c++] 	= "Descrição Produto";
		$dados[colunas][titulo][$c++] 	= "<center>Utiliza Número Série?</center>";
		foreach($arrCD as $chave => $cd){
			$dados[colunas][titulo][$c++] 	= "<center>".$cd[descricao]."</center>";
			$dados[colunas][tamanho][$c-1] = "width='090px'";
		}
		$dados[colunas][titulo][$c++] 	= "<center>Total Estoque</center>";

		/*
		$dados[colunas][titulo][$c++] 	= "<center>Estoque M&iacute;nimo</center>";
		$dados[colunas][titulo][5] 	= "<center>Compra M&iacute;nima</center>";
		$dados[colunas][titulo][6] 	= "<center>Utiliza&ccedil;&atilde;o M&eacute;dia M&ecirc;s (Unidades)</center>";
		$dados[colunas][titulo][7] 	= "<center>Prazo Entrega (Dias)</center>";
		$dados[colunas][titulo][8] 	= "<center>Quantidade Embalagem (Unidades)</center>";
		$dados[colunas][titulo][9] 	= "<center>Estoque Atual</center>";
		*/

		$colunas = $c - 1;
		echo "	<div id='produtos-container'>
					<div class='titulo-container'>
						<div class='titulo'>
							<p>Registros localizados: $i </p>
						</div>
						<div class='conteudo-interno'>";
		geraTabela($largura,$colunas,$dados, null, 'produtos-localiza-controle-estoque', 2, 2, 100,1);
		echo "			</div>
					</div>
				</div>";
	}
?>