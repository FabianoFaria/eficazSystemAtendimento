<?php
	$id		 	= $_POST['localiza-requisicao-id'];
	$codigo 	= $_POST['localiza-codigo'];
	$produto 	= $_POST['localiza-produto'];$seleciona[produto][$produto] = "selected";
	$categoria 	= $_POST['localiza-categoria'];$seleciona[categoria][$categoria] = "selected";
	$fornecedor = $_POST['localiza-fornecedor'];$seleciona[fornecedor][$fornecedor] = "selected";
	$usuario 	= $_POST['localiza-usuario'];$seleciona[usuario][$usuario] = "selected";
?>

<div class="titulo-container">
	<div class="titulo">
		<p>
			Filtrar Requisições
			<input type="button" value="Incluir Requisi&ccedil;&atilde;o" class='cadastra-solicitacao' solicitacao-id=''/>
			<input type="button" value="Atualizar requisições" id='compras-gerar-ordem-compra' Style='width:120px'/>
			<input type="hidden" value="" id="localiza-solicitacao-id" name="localiza-solicitacao-id"/>
			<input type="hidden" value="" id="workflow-id" name="workflow-id"/>
		</p>
	</div>
	<div class="conteudo-interno">
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:22%; float:left;'>
				<p>ID</p>
				<p><input type='text' name='localiza-requisicao-id' id='localiza-requisicao-id' class='formata-numero' value='<?php echo $id; ?>' style='width:80%;'/></p>
			</div>
			<div class="titulo-secundario" style='width:77%; float:left;'>
				<p>C&oacute;digo Refer&ecirc;ncia</p>
				<p><input type='text' name='localiza-codigo' id='localiza-codigo' class='formata-campo required' value='<?php echo $codigo; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario quatro-colunas">
			<div class="titulo-secundario">
				<p>Produto:</p>
				<p>
					<select name='localiza-produto' id='localiza-produto' class='formata-campo required'>
						<option value=''>Todos</option>
<?php
	$resultado = mpress_query("select distinct v.Produto_Variacao_ID, Codigo, Nome
							  from produtos_dados p
				  			  inner join produtos_variacoes v on v.Produto_ID = p.Produto_ID
							  inner join compras_solicitacoes s on s.Produto_Variacao_ID = v.Produto_Variacao_ID
							  where Tipo_Produto = 30 and p.Situacao_ID = 1 and v.Situacao_ID = 1 and s.Situacao_ID = 60
							  order by Nome");
	while($row = mpress_fetch_array($resultado))
		echo "			<option value='".$row['Produto_Variacao_ID']."' ".$seleciona[produto][$row['Produto_Variacao_ID']].">".($row['Nome'])."</option>";
?>
					</select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Categoria:</p>
			<p>
				<select name='localiza-categoria' id='localiza-categoria' class='formata-campo required'>
						<option value=''>Todas</option>
<?php
		$query = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome");
		while($categoria1 = mpress_fetch_array($query)){
			echo "<option value='".$categoria1[Categoria_ID]."' ".$seleciona[categoria][$categoria1[Categoria_ID]].">".($categoria1[Nome])."</option>";
			$query2 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria1[Categoria_ID]."' order by nome");
			while($categoria2 = mpress_fetch_array($query2)){
				echo "<option value='".$categoria2[Categoria_ID]."' ".$seleciona[categoria][$categoria2[Categoria_ID]].">&nbsp; ".($categoria2[Nome])."</option>";
				$query3 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria2[Categoria_ID]."' order by nome");
				while($categoria3 = mpress_fetch_array($query3)){
					echo "<option value='".$categoria3[Categoria_ID]."' ".$seleciona[categoria][$categoria3[Categoria_ID]].">&nbsp;&nbsp; ".($categoria3[Nome])."</option>";
					$query4 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria3[Categoria_ID]."' order by nome");
					while($categoria4 = mpress_fetch_array($query4)){
						echo "<option value='".$categoria4[Categoria_ID]."' ".$seleciona[categoria][$categoria4[Categoria_ID]].">&nbsp;&nbsp;&nbsp; ".($categoria4[Nome])."</option>";
						$query5 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria4[Categoria_ID]."' order by nome");
						while($categoria5 = mpress_fetch_array($query5))
							echo "<option value='".$categoria5[Categoria_ID]."' ".$seleciona[categoria][$categoria5[Categoria_ID]].">&nbsp;&nbsp;&nbsp;&nbsp; ".($categoria5[Nome])."</option>";
					}
				}
			}
		}
?>
				</select>
			</p>
		</div>

		<div class="titulo-secundario seis-colunas">
			<p>Fornecedor:</p>
			<p>
				<select name='localiza-fornecedor' id='localiza-fornecedor' class='formata-campo required'>
						<option value=''>Todos</option>
<?php
	$resultado = mpress_query("select distinct c.Cadastro_ID, c.Nome
							   from produtos_fornecedores f
							   inner join cadastros_dados c on c.Cadastro_ID = f.Cadastro_ID
							   inner join produtos_variacoes v on v.Produto_ID = f.Produto_ID
							   inner join compras_solicitacoes s on s.Produto_Variacao_ID = v.Produto_Variacao_ID
							   where s.Situacao_ID = 60
							   order by c.Nome");
	while($row = mpress_fetch_array($resultado))
		echo "			<option value='".$row['Cadastro_ID']."' ".$seleciona[fornecedor][$row['Cadastro_ID']].">".($row['Nome'])."</option>";
?>
				</select>
			</p>
		</div>

		<div class="titulo-secundario seis-colunas">
			<p>Usuário:</p>
			<p>
				<select name='localiza-usuario' id='localiza-usuario' class='formata-campo required'>
						<option value=''>Todos</option>
<?php
	$resultado = mpress_query("select distinct Cadastro_ID, Nome
							  from cadastros_dados d
							  inner join compras_solicitacoes s on s.Usuario_Cadastro_ID = d.Cadastro_ID
							  where s.Situacao_ID = 60
							  order by nome");
	while($row = mpress_fetch_array($resultado))
		echo "			<option value='".$row['Cadastro_ID']."' ".$seleciona[usuario][$row['Cadastro_ID']].">".($row['Nome'])."</option>";
?>
				</select>
			</p>
		</div>
		<div class='titulo-secundario onze-colunas' Style='margin-top:15px; '>
			<p class='direita'><input type='button' Style='width:100%;' value='Pesquisar' id='botao-localizar-requisicao'></p>
		</div>
	</div>
</div>
<?php
	if($id!="") 		$condicoes .= " and cs.Compra_Solicitacao_ID = '$id' ";
	if($codigo!="") 	$condicoes .= " and cs.Chave_Estrangeira = $codigo ";
	if($produto!="") 	$condicoes .= " and pv.Produto_Variacao_ID = $produto";
	if($categoria!="") 	$condicoes .= " and p.Categorias like '%s:".strlen($categoria).":\"".$categoria."\"%'";
	if($fornecedor!="") $condicoes .= " and p.Produto_ID in (select f.Produto_ID from produtos_fornecedores f where f.Cadastro_ID = '$fornecedor')";
	if($usuario!="") 	$condicoes .= " and cd.Cadastro_ID = '$usuario'";
	global $dadosUserLogin;

	$sql = "select cs.Compra_Solicitacao_ID, DATE_FORMAT(cs.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro, ts.Descr_Tipo as Situacao, cd.Nome as Solicitante, p.Nome as Produto, cs.Quantidade,
			(select Nome from modulos where slug = cs.Tabela_Estrangeira) Modulo, cs.Chave_Estrangeira, cs.Tabela_Estrangeira
			from compras_solicitacoes cs
			inner join cadastros_dados cd on cs.Usuario_Cadastro_ID = cd.Cadastro_ID
			inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
			inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
			inner join tipo ts on ts.Tipo_ID = cs.Situacao_ID
			where cs.Situacao_ID = 60
			$condicoes";
	//echo $sql;
	$query = mpress_query($sql);
	$i=0;
	while($row = mpress_fetch_array($query)){
		$i++;
		$link = "";
		if ($row[Tabela_Estrangeira]=='chamados'){
			$link = "- <span class='link link-chamados' workflow-id='$row[Chave_Estrangeira]'> ".$row[Chave_Estrangeira]."</span>";
		}
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Compra_Solicitacao_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Modulo]." $link</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Produto]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'><center>".number_format($row[Quantidade], 2, ',', '.')."</center></p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Solicitante]."</p>";
		$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Cadastro]."</p>";
		$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='compras-aceitar compras-validar' value='".$row['Compra_Solicitacao_ID']."' name='comprasAceitar[]' id='comprasAceitar[]' posicao='$i'></p>";
		$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='compras-negar compras-validar'   value='".$row['Compra_Solicitacao_ID']."' name='comprasNegar[]' id='comprasNegar[]'  posicao='$i'></p>";
	}
	$dados[colunas][titulo][1] = "ID Requisi&ccedil;&atilde;o";
	$dados[colunas][titulo][2] = "Modulo / C&oacute;digo Refer&ecirc;ncia";
	$dados[colunas][titulo][3] = "Produto";
	$dados[colunas][titulo][4] = "<center>Quantidade<center>";
	$dados[colunas][titulo][5] = "Solicitante";
	$dados[colunas][titulo][6] = "<center>Data</center>";
	$dados[colunas][titulo][7] = "<center><img src='../images/geral/disponivel.png' class='seleciona-todas-aceitar' style='cursor:pointer' title='Aceitar Todas'></center>";
	$dados[colunas][titulo][8] = "<center><img src='../images/geral/indisponivel.png' class='seleciona-todas-negar' style='cursor:pointer' title='Negar Todas'></center>";

	$dados[colunas][tamanho][1] = "width='80px'";
	$dados[colunas][tamanho][2] = "width='200px'";
	$dados[colunas][tamanho][3] = "";
	$dados[colunas][tamanho][4] = "width='075px'";
	$dados[colunas][tamanho][5] = "width='200px'";
	$dados[colunas][tamanho][6] = "width='065px'";
	$dados[colunas][tamanho][7] = "width='020px'";
	$dados[colunas][tamanho][8] = "width='020px'";
	echo "<div class='titulo-container'>
			<div class='titulo'>
			<p Style='float:left;'>Quantidade de Requisições Localizadas: $i</p>
			<div id='retorno-erro' Style='width:245px;float:right;height:21px;color:red;margin-top:5px;font-weight:normal'></div>
		</div>";
	echo "<div class='conteudo-interno titulo-secundario uma-coluna' id='conteudo-interno-compras'>";
	geraTabela("100%","8",$dados);
	echo "</div>";
	if ($i==0){
		echo "<p Style='margin:2px 5px 30px 5px; text-align:center'>Nenhuma requisição selecionada</p>";
	}
	echo "		</div>
			</div>";

?>