<?php
	header ('Content-type: text/html; charset=iso-8859-1');
	include("functions.php");
	$sqlCond = $_GET['cond'];
	$inicio = $_GET['pagina']*30;
	$sql = "select cd.Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
			Inscricao_Municipal, Inscricao_Estadual, Usuario_Cadastro_ID, Codigo
			from cadastros_dados cd
			left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
			left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
			where cd.Situacao_ID = 1 and trim(cd.Nome) <> ''
			$sqlCond
			limit $inicio, 30";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		if ($row[Nome_Fantasia]!=""){ $nome .= " / ".$row[Nome_Fantasia];}
		$dados[colunas][conteudo][$i][1] = "<a href='#' class='link cadastro-localiza' id='cadastro-localiza-".$row[Cadastro_ID]."' name='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p><a>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Tipo_Pessoa]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$nome."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:center;'><a href='#' class='link cadastro-localiza' id='cadastro-localiza-".$row[Cadastro_ID]."' name='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>visualizar</p><a></p>";
	}
	if($i>=1){
		$largura = "100.2%";
		$colunas = "5";
		$dados[colunas][tamanho][1] = "width='10%'";
		$dados[colunas][titulo][1] 	= "C&oacute;digo";
		$dados[colunas][tamanho][2] = "width='20%'";
		$dados[colunas][titulo][2] 	= "Tipo";
		$dados[colunas][tamanho][3] = "width=''";
		$dados[colunas][titulo][3] 	= "Nome";
		$dados[colunas][tamanho][4] = "width='30%'";
		$dados[colunas][titulo][4] 	= "Cpf / Cnpj";
		$dados[colunas][tamanho][5] = "width='60px'";
		$dados[colunas][titulo][5] 	= "";
		geraTabela($largura,$colunas,$dados);
	}
?>
