<?php
	global $caminhoSistema;
	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1) $escondeEmpresa = "esconde";

	$id 	= $_POST['localiza-compra-id'];
	$situacao = $_POST['localiza-compra-situacao'];
	$responsavelID = $_POST['localiza-responsavel-id'];
	$cadastroID = $_POST['localiza-cadastro-id'];


	$dataInicioCompra = $_POST['data-inicio-compra'];
	$dataFimCompra = $_POST['data-fim-compra'];
	$dataInicioLimite = $_POST['data-inicio-limite'];
	$dataFimLimite = $_POST['data-fim-limite'];

?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">Localizar Compras</p>
	</div>

	<input type='hidden' id='ordem-compra-id' name='ordem-compra-id' >
	<div class="conteudo-interno">
		<div class="titulo-secundario cinco-colunas">
			<div class="titulo-secundario" style='float:left;'>
				<p>Ordem Compra ID:</p>
				<p><input type='text' name='localiza-compra-id' id='localiza-compra-id' class='formata-numero' value='<?php echo $id; ?>' style='width:85%;'></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Data Cadastro:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-compra' id='data-inicio-compra' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioCompra; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-compra' id='data-fim-compra' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimCompra; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Data Limite:</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-limite' id='data-inicio-limite' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioLimite; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-limite' id='data-fim-limite' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimLimite; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Situa&ccedil;&atilde;o Compra:</p>
			<select name="localiza-compra-situacao" id="localiza-compra-situacao"><?php echo optionValueGrupo(32, $situacao, "Todas");?><select>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Respons&aacute;vel:</p>
			<select name="localiza-responsavel-id" id="localiza-responsavel-id">
				<option value=''>Todos</option>
<?php
	$resultado = mpress_query("select distinct cd.Cadastro_ID, cd.Nome from compras_ordem_compra c inner join cadastros_dados cd on cd.Cadastro_ID = c.Usuario_Cadastro_ID");
	while($row = mpress_fetch_array($resultado)){
		if ($responsavelID==$row[Cadastro_ID]) $selecionado = "selected"; else $selecionado = "";
		echo "	<option value='$row[Cadastro_ID]' $selecionado>$row[Nome]</option>";
	}
?>
			<select>
		</div>
		<div style='float:left;width:40%;'>&nbsp;</div>
		<div class="titulo-secundario" style='float:left;width:20%;'>
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
		<div class="titulo-secundario cinco-colunas">
			<p>Nota Fiscal Compra</p>
			<p><input type='text' name='localiza-nfe-compra' id='localiza-nfe-compra' style='width:95%' maxlength='20' value='<?php echo $_POST['localiza-nfe-compra']; ?>'></p>
		</div>
		<div class="titulo-secundario" Style='width:20%; margin-top:15px; float:left;'>
			<p align='right'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-localizar-compras'></p>
		</div>
	</div>
</div>
<?php
//if($_POST){
	$i = 0;
	if($id!="") 			$condicoes .= " and c.Ordem_Compra_ID = '$id' ";
	if($situacao!="") 		$condicoes .= " and cf.Situacao_ID = $situacao";
	if($responsavelID!="") 	$condicoes .= " and c.Usuario_Cadastro_ID = '$responsavelID'";
	if($cadastroID!="") 	$condicoes .= " and c.Cadastro_ID = '$cadastroID'";
	if($_POST['localiza-nfe-compra']!="") 	$condicoes .= " and pm.Nota_Fiscal like '".$_POST['localiza-nfe-compra']."'";


	if(($dataInicioCompra!="")||($dataFimCompra!="")){
		$dataInicioCompra = implode('-',array_reverse(explode('/',$dataInicioCompra)));
		if ($dataInicioCompra=="") $dataInicioCompra = "0000-00-00"; $dataInicioCompra .= " 00:00";
		$dataFimCompra = implode('-',array_reverse(explode('/',$dataFimCompra)));
		if ($dataFimCompra=="") $dataFimCompra = "2100-01-01"; $dataFimCompra .= " 23:59";
		$condicoes .= " and c.Data_Cadastro between '$dataInicioCompra' and '$dataFimCompra' ";
	}

	if(($dataInicioLimite!="")||($dataFimLimite!="")){
		$dataInicioLimite = implode('-',array_reverse(explode('/',$dataInicioLimite)));
		if ($dataInicioLimite=="") $dataInicioLimite = "0000-00-00"; $dataInicioLimite .= " 00:00";
		$dataFimLimite = implode('-',array_reverse(explode('/',$dataFimLimite)));
		if ($dataFimLimite=="") $dataFimLimite = "2100-01-01"; $dataFimLimite .= " 23:59";
		$condicoes .= " and Data_Limite_Retorno between '$dataInicioLimite' and '$dataFimLimite' ";
	}

	$sql = "select c.Ordem_Compra_ID,
				DATE_FORMAT(c.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro,
				DATE_FORMAT(Data_Limite_Retorno,'%d/%m/%Y') as Data_Limite_Retorno,
				cd.Nome as Responsavel, sol.Nome as Solicitante,
				cf.Situacao_ID, s.Descr_Tipo as Situacao,
				concat(pd.Nome, ' ', pv.Descricao) as Produto,
				pm.Nota_Fiscal as Nota_Fiscal, sum(pm.Quantidade) as Quantidade_Entregue
				from compras_ordem_compra c
				inner join cadastros_dados cd on cd.Cadastro_ID = c.Usuario_Cadastro_ID
				left join compras_ordens_compras_produtos cocp on cocp.Ordem_Compra_ID = c.Ordem_Compra_ID
				left join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cocp.Compra_Solicitacao_ID
				left join cadastros_dados sol on sol.Cadastro_ID = cs.Usuario_Cadastro_ID
				left join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
				left join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				left join compras_ordem_compra_follows cf on cf.Ordem_Compra_ID = c.Ordem_Compra_ID
					and cf.Ordem_Compra_Follow_ID = (select max(cfaux.Ordem_Compra_Follow_ID) from compras_ordem_compra_follows cfaux where cfaux.Ordem_Compra_ID = c.Ordem_Compra_ID)
				left join tipo s on s.Tipo_ID = cf.Situacao_ID
				left join compras_ordem_compras_finalizadas cocf ON cocf.Ordem_Compra_ID = c.Ordem_Compra_ID and cocf.Ordem_Compra_Produto_ID = cocp.Ordens_Compras_Produtos_ID
				left join produtos_movimentacoes pm ON pm.Tabela_Estrangeira = 'compras' AND pm.Chave_Estrangeira = cocf.Ordem_Compra_ID AND pm.Chave_Estrangeira_Produto = cocf.Ordem_Compra_Finalizada_ID
				where c.Ordem_Compra_ID > 0
				$condicoes
				group by Ordem_Compra_ID, c.Data_Cadastro, cd.Cadastro_ID, pm.Nota_Fiscal, cf.Situacao_ID, cocf.Ordem_Compra_ID, pd.Nome, pv.Descricao
				order by c.Ordem_Compra_ID desc";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($ordemCompraIDAnt!=$row[Ordem_Compra_ID]){
			$i++;
			$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='lnk workflow-compra' ordem-compra-id='$row[Ordem_Compra_ID]'";
			if ($row[Data_Limite_Retorno]=="00/00/0000")$dataLimite = "";else $dataLimite = $row['Data_Limite_Retorno'];
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px; text-align:center;' >".$row['Ordem_Compra_ID']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Responsavel']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Situacao']."</p>";
			$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$dataLimite."</p>";
			//$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Data_Cadastro']."</p>";
			$dados[colunas][classe][$i] = "tabela-fundo-escuro";
		}
		$i++;
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Produto']."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px; text-align:center;'>".number_format($row['Quantidade_Entregue'], 0,',','.')."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row['Solicitante']."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px; text-align:center;'>".$row['Nota_Fiscal']."</p>";
		$dados[colunas][classe][$i] = "tabela-fundo-claro";
		$ordemCompraIDAnt = $row[Ordem_Compra_ID];
	}
	$largura = "100%";
	$colunas = "8";
	$dados[colunas][titulo][1] = "Ordem Compra";
	$dados[colunas][titulo][2] = "Respons&aacute;vel";
	$dados[colunas][titulo][3] = "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][4] = "Produto";
	$dados[colunas][titulo][5] = "<p style='text-align:center; margin:2px 3px 0 3px; font-size:10px;'>Qtde.<br>Entregue</p>";
	$dados[colunas][titulo][6] = "Solicitante";
	//$dados[colunas][titulo][7] = "Limite Retorno";
	$dados[colunas][titulo][7] = "<center>Nota Fiscal</center>";
	$dados[colunas][titulo][8] = "Data Cadastro";

	$dados[colunas][tamanho][1] = "width='090px'";
	$dados[colunas][tamanho][2] = "";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][7] = "width='100px'";
	$dados[colunas][tamanho][8] = "width='100px'";

	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Registros Localizados: $i</p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
	geraTabela($largura,$colunas,$dados, null, 'compras-localiza-oc', 2, 2, 100,1);
	echo "		</div>
			</div>";
//}

?>