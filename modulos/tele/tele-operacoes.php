<?php
include('functions.php');
if ($_POST){
	$operacao = $_POST['localiza-operacao'];
	$situacaoID = $_POST['localiza-situacao-id'];
}
else{
	$situacaoID = 1;
}

echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Filtros de pesquisa
						<input type='button' value='Incluir nova Opera&ccedil;&atilde;o' class='inc-alt-operacao' operacao-id='' style='width:150px;'>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='width:50%; float:left;'>
						<p>Nome Opera&ccedil;&atilde;o</p>
						<p><input type='text' name='localiza-operacao' id='localiza-operacao' value='$operacao' style='width:97%;'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:15%;'>
						<p>Situa&ccedil;&atilde;o Campanha</p>
						<p><select name='localiza-situacao-id' id='localiza-situacao-id'>".optionValueGrupo(1, $situacaoID, '&nbsp;', 'and Tipo_ID IN (1,2)')."</select></p>
					</div>
					<div class='titulo-secundario' style='float:right; width:10%;' align='right'>
						<p><input type='button' class='botao-localizar-operacao' value='Pesquisar' style='margin-top:15px; width:90%;'/></p>
					</div>
				</div>
			</div>";

echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Opera&ccedil;&otilde;es Cadastradas
					</p>
				</div>
				<div class='conteudo-interno'>";

	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by top.Nome";
	}
	echo "<input type='hidden' id='operacao-id' name='operacao-id' value=''>";

	if ($operacao !='') $sqlCond .= " and top.Nome like '%$operacao%' ";
	if ($situacaoID !='') $sqlCond .= " and top.Situacao_ID in ($situacaoID) ";

	$sql = "SELECT top.Operacao_ID, top.Nome, t.Descr_Tipo as Tipo_Operacao, top.Situacao_ID, s.Descr_Tipo as Situacao, em.Nome as Empresa
				FROM tele_operacoes top
				INNER JOIN cadastros_dados em ON em.Cadastro_ID = top.Empresa_ID
				INNER JOIN tipo s on s.Tipo_ID = top.Situacao_ID
				LEFT JOIN tipo t on t.Tipo_ID = top.Tipo_Operacao_ID
				WHERE top.Operacao_ID > 0
				$sqlCond
				$ordem";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-operacao link' operacao-id='".$row['Operacao_ID']."'>".$row['Operacao_ID']."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-operacao link' operacao-id='".$row['Operacao_ID']."'>".$row['Nome']."</p>";
		//$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Tipo_Operacao']."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Empresa']."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Situacao']."</p>";

	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma opera&ccedil;&atilde;o cadastrada</p>";
	}
	else{
		$largura = "100%";
		$dados[colunas][tamanho][1] = "width='6%'";
		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Opera&ccedil;&atilde;o";
		//$dados[colunas][titulo][3] 	= "Tipo";
		$dados[colunas][titulo][3] 	= "Empresa";
		$dados[colunas][titulo][4] = "Situação";

		$dados[colunas][ordena][1] = "Operacao_ID";
		$dados[colunas][ordena][2] = "top.Nome";
		//$dados[colunas][ordena][3] = "Tipo";
		$dados[colunas][ordena][3] = "Empresa";
		$dados[colunas][ordena][4] = "Situacao";
		geraTabela($largura,4,$dados, null, 'formulario-dinamico-localiza', 2, 2, '','');
	}
	echo "		</div>
			</div>";
?>