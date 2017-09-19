<?php

	if($_POST){
		$dataInicio = $_POST['data-inicio'];
		$dataFim = $_POST['data-fim'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-situacao-conta']); $i++){
			$situacoes .= $virgula.$_POST['localiza-situacao-conta'][$i];
			$virgula = ",";
		}
	}
	else{
		$situacoes = "98";
	}

	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Filtros de Pesquisa</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left;width:20%' >
							<div class='titulo-secundario' style='float:left;width:98%;'>
								<p>Data Venda:</p>
								<div style='width:45%;float:left;'>
									<p><input type='text' name='data-inicio' id='data-inicio' class='formata-data' style='width:92%' maxlength='10' value='".$dataInicio."'>&nbsp;&nbsp;</p>
								</div>
								<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
								<div style='width:45%;float:left;'>
									<p><input type='text' name='data-fim' id='data-fim' class='formata-data' style='width:92%' maxlength='10' value='".$dataFim."'></p>
								</div>
							</div>
						</div>
						<div class='titulo-secundario' style='float:left;width:15%'>
							<p>Situa&ccedil;&atilde;o:</p>
							<p><select name='localiza-situacao-conta[]' id='localiza-situacao-conta' multiple Style='height:58px'".optionValueGrupoMultiplo(37, $situacoes,'')."</select></p>
						</div>
						<div class='titulo-secundario duas-colunas' Style='float: right; width:15%; margin-top:15px;'>
							<p class='direita'><input type='button' value='Pesquisar' class='botao-pesquisar-pdv'/></p>
						</div>
					</div>
				</div>
			</div>
			<input type='hidden' id='cadastroID' name='cadastroID' value=''>";


		if(($dataInicio!="")||($dataFim!="")){
			$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
			if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
			$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
			if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
			$sqlCond .= " and p.Data_Cadastro between '$dataInicio' and '$dataFim' ";
		}
		if ($situacoes != ""){
			$sqlCond .= " and p.Situacao_ID IN ($situacoes) ";
		}
		if($_POST['ordena-tabela'] != ""){
			$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
		}else{
			$ordem = " order by p.Data_Cadastro desc";
		}

		$sql = "SELECT p.PDV_ID, p.Caixa_Numero, p.Atendente_ID, a.Nome AS Atendente, p.Cliente_ID, c.Nome AS Cliente,
					COALESCE(SUM(pa.Valor),0) AS Valor_Pago, COALESCE(SUM(pb.Valor),0) AS Valor_Desconto, s.Descr_Tipo AS Situacao,
					pr.PDV_Produto_ID, concat(pd.Nome, ' ', pv.Descricao) as Produto, pr.Quantidade, p.Data_Cadastro as Data_Venda,
					pr.Valor_Unitario, pr.Valor_Desconto, p.Situacao_ID
					FROM pdv p
					INNER JOIN cadastros_dados a ON a.Cadastro_ID = p.Atendente_ID
					INNER JOIN pdv_produtos pr ON pr.PDV_ID = p.PDV_ID AND pr.Situacao_ID = 1
					INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = pr.Produto_Variacao_ID
					INNER JOIN produtos_dados pd on pd.Produto_ID = pv.Produto_ID
					LEFT JOIN pdv_pagamentos pa ON pa.PDV_ID = p.PDV_ID AND pa.Situacao_ID = 1 AND pa.Valor > 0
					LEFT JOIN pdv_pagamentos pb ON pb.PDV_ID = p.PDV_ID AND pb.Situacao_ID = 1 AND pa.Valor < 0
					LEFT JOIN tipo s ON s.Tipo_ID = p.Situacao_ID
					LEFT JOIN cadastros_dados c ON c.Cadastro_ID = p.Cliente_ID
					WHERE p.PDV_ID > 0
						$sqlCond
					GROUP BY p.PDV_ID, p.Caixa_numero, pr.PDV_Produto_ID
					$ordem, p.PDV_ID";
		//echo $sql;
		$cadastroIDAnt = "";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$pdvID = $row['PDV_ID'];

			// CANCELADO
			if ($row['Situacao_ID'] == 99){
				$cor = "color:red";
				$integraFinanceiro = "";
			}
			// FINALIZADO
			else{
				$cor = "color:blue";
				$integraFinanceiro = "<input type='checkbox' name='pdv-fat[$pdvID]' id='pdv-$pdvID' class='pdv-fat'/>";
			}
			if ($row['PDV_ID']!=$PDVIDAnt){

				if ($PDVIDAnt!=''){
					$i++;
					$dados[colunas][tr][$i] = " style='$cor'";
					$dados[colunas][conteudo][$i][1] = "<p class='$classe' Style='margin:2px 5px 0 5px; text-align:right;'><b>TOTAL:<b></p>";
					$dados[colunas][colspan][$i][1] = 9;
					$dados[colunas][conteudo][$i][10] = "<p class='$classe' Style='margin:2px 5px 0 0px;text-align:center;'><b>R$ ".number_format($totalProdutosVenda,2,',','.')."</b></p>";
					$totalProdutosVenda = 0;
				}
				/* TOTAIS */
				$totalProduto = (($row['Valor_Unitario'] * $row['Quantidade']) - $row['Valor_Desconto']);
				$totalProdutosVenda += $totalProduto;
				/**/
				$i++;
				if ($classeDestaque=='tabela-fundo-escuro') $classeDestaque = 'tabela-fundo-claro'; else $classeDestaque = 'tabela-fundo-escuro';
				$dados[colunas][tr][$i] = " style='$cor'";
				$dados[colunas][conteudo][$i][1] = "<p class='$classe' Style='margin:2px 5px 0 5px; text-align:center;'>".converteData($row['Data_Venda'],1)."</p>";
				$dados[colunas][conteudo][$i][2] = "<p class='$classe' Style='margin:2px 5px 0 5px; text-align:center;'>Caixa ".$row['Caixa_Numero']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p class='$classe' Style='margin:2px 5px 0 5px;float:left;'>".$row['Atendente']."</p>";
				$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row['Situacao']."</p>";
				$dados[colunas][conteudo][$i][9] = "<p class='$classe' Style='margin:2px 5px 0 5px;float:left;'>".$row['Cliente']."</p>";
				$dados[colunas][conteudo][$i][10] = "<!--<p class='$classe' Style='margin:2px 5px 0 5px; text-align:center;'>".$integraFinanceiro."</p>-->";
				$dados[colunas][classe][$i] = $classeDestaque;
			}

			$PDVIDAnt = $row['PDV_ID'];
			$i++;
			$dados[colunas][tr][$i] = " style='$cor'";
			$dados[colunas][colspan][$i][3] = "1";
			$dados[colunas][conteudo][$i][3] = "<p class='$classe' Style='margin:2px 5px 0 60px;float:left;'>".$row['Produto']."</p>";
			$dados[colunas][conteudo][$i][4] = "<p class='$classe' Style='margin:2px 5px 0 0px; text-align:center;'>".$row['Quantidade']."</p>";
			$dados[colunas][conteudo][$i][5] = "<p class='$classe' Style='margin:2px 5px 0 0px;float:right;'>R$ ".number_format($row['Valor_Unitario'],2,',','.')."</p>";
			$dados[colunas][conteudo][$i][6] = "<p class='$classe' Style='margin:2px 5px 0 0px;float:right;'>R$ ".number_format($row['Valor_Desconto'],2,',','.')."</p>";
			$dados[colunas][conteudo][$i][7] = "<p class='$classe' Style='margin:2px 5px 0 0px;float:right;'>R$ ".number_format($totalProduto,2,',','.')."</p>";
			$dados[colunas][classe][$i] = $classeDestaque;

		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma venda localizado</p>";
		}
		else{
			$largura = "100.2%";
			$colunas = "10";
			$dados[colunas][tamanho][1] = "width='110px'";
			//$dados[colunas][tamanho][2] = "width='6%'";
			//$dados[colunas][tamanho][4] = "width='100px'";

			$dados[colunas][titulo][1] 	= "<center>Data / Hora Venda</center>";
			$dados[colunas][titulo][2] 	= "<center>Caixa</center>";
			$dados[colunas][titulo][3] 	= "Atendente / Produtos";
			$dados[colunas][titulo][4] 	= "<center>Quantidade</center>";
			$dados[colunas][titulo][5] 	= "<center>Valor Unitário</center>";
			$dados[colunas][titulo][6] 	= "<center>Valor Desconto</center>";
			$dados[colunas][titulo][7] 	= "<center>Total Produto</center>";
			$dados[colunas][titulo][8] 	= "Situação";
			$dados[colunas][titulo][9] 	= "Cliente";
			$dados[colunas][titulo][10] = "<!--<center class='link faturar-vendas-pdv'>Faturar</center>-->";

			/*
			$dados[colunas][ordena][1] = "Cadastro_ID";
			$dados[colunas][ordena][2] = "cast(codigo AS SIGNED)";
			$dados[colunas][ordena][3] = "Nome";
			$dados[colunas][ordena][4] = "Tipo_Pessoa";
			$dados[colunas][ordena][5] = "Cpf_Cnpj";
			$dados[colunas][ordena][7] = "Email";
			*/
	echo "<div class='div-aguarde'></div>";
	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Registros Localizados: $i <input type='button' value='Executar Faturamento' class='executar-faturamento esconde' style='float:right;margin-right:0px; width:140px'/></p>
					</div>
					<div class='conteudo-interno'>";
	geraTabela($largura,$colunas,$dados, null, 'pdv-localiza', 2, 2, 100,1);
	echo "			</div>
				</div>
			</div>";
		}
	//}
?>
