<?php
/*
	function produtosLocaliza($dados){
		if ($dados['tipoProdutoID']!=""){
			$sqlCond .= " and pd.Tipo_Produto in (".$dados['tipoProdutoID'].")";
		}
		$sql = "SELECT pd.Produto_ID AS Produto_ID, pd.Nome, pd.Descricao_Resumida, pd.Descricao_Completa, pd.Tipo_Produto, pd.Marca, tp.Descr_Tipo AS Tipo, pd.Data_Cadastro AS Data_Cadastro_Produto, map.Nome_Arquivo AS Nome_Arquivo,
										pv.Produto_Variacao_ID, pv.Descricao, pv.Codigo AS Codigo_Variacao, pv.Valor_Venda, mav.Nome_Arquivo AS Nome_Arquivo_Variacao, pv.Data_Cadastro AS Data_Cadastro_Variacao
										FROM produtos_dados pd
										INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
										LEFT JOIN modulos_anexos mav ON mav.Anexo_ID = pv.Imagem_ID
										LEFT JOIN modulos_anexos map ON map.Anexo_ID = (SELECT MIN(mapaux.Anexo_ID) FROM modulos_anexos mapaux WHERE mapaux.Tabela_Estrangeira = 'produtos' AND mapaux.Chave_Estrangeira = pd.Produto_ID AND mapaux.Situacao_ID = 1 LIMIT 1)
										LEFT JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto AND tp.Tipo_Grupo_ID = 13
										WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
										$sqlCond
										GROUP BY pd.Produto_ID, pv.Produto_Variacao_ID, pd.Nome, pd.Descricao_Resumida, pd.Descricao_Completa, pd.Tipo_Produto, pd.Marca, tp.Descr_Tipo, map.Nome_Arquivo
										ORDER BY Nome, pd.Produto_ID";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			if ($produtoIDAnt!=$row['Produto_ID']){
				$i++;
				$dado['produtos'][$i]['id'] = $row['Produto_ID'];
				$dado['produtos'][$i]['nome'] = $row['Nome'];
				$dado['produtos'][$i]['tipo'] = $row['Tipo'];
				$dado['produtos'][$i]['tipoproduto'] = $row['Tipo_Produto'];
				$dado['produtos'][$i]['marca'] = $row['Marca'];
				$dado['produtos'][$i]['descricaoresumida'] = $row['Descricao_Resumida'];
				$dado['produtos'][$i]['descricaocompleta'] = $row['Descricao_Completa'];
				$dado['produtos'][$i]['imagemproduto'] = $row['Nome_Arquivo'];
				$ii = 0;
			}
			$ii++;
			$dado['produtos'][$i]['variacoes'][$ii]['id'] = $row['Produto_Variacao_ID'];
			$dado['produtos'][$i]['variacoes'][$ii]['descricao'] = $row['Descricao'];
			$dado['produtos'][$i]['variacoes'][$ii]['imagemvariacao'] = $row['Nome_Arquivo'];
			$dado['produtos'][$i]['variacoes'][$ii]['valorvenda'] = $row['Valor_Venda'];
			$produtoIDAnt = $row['Produto_ID'];

		}
		echo "<pre>";
		print_r($dado);
		echo "</pre>";
		echo serialize($dado);
	}
*/
?>