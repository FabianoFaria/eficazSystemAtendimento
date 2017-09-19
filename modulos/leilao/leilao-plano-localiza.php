<?php
	echo "	<input type='hidden' id='plano-id' name='plano-id' value=''>";
	$sql = "SELECT pd.Produto_ID as Produto_ID, pv.Produto_Variacao_ID as Produto_Variacao_ID, COALESCE(pd.Nome,'') as Plano,
					COALESCE(pd.Descricao_Completa,'') as Descricao, COALESCE(pv.Descricao,'') AS Pacote,
					pv.Valor_Custo as Quantidade_Lances, pv.Valor_Venda as Valor_Venda
				FROM produtos_dados pd
				left join produtos_variacoes pv on pv.Produto_ID = pd.Produto_ID
					where pd.Tipo_Produto = 139
				order by pd.Produto_ID, pv.Valor_Venda ";
	//echo $sql;
	$resultado = mpress_query($sql);
	while ($rs = mpress_fetch_array($resultado)){
		if ($produtoIDAnt != $rs['Produto_ID']){
			$i++;
			$dados[colunas][extras][$i][1] = $dados[colunas][extras][$i][2] = $dados[colunas][extras][$i][3] = " valign='top' ";
			$dados[colunas][conteudo][$i][1] = "<span class='link plano-inc-alt' plano-id='".$rs[Produto_ID]."'>".$rs[Plano]."</span>";
			$dados[colunas][conteudo][$i][2] = nl2br($rs[Descricao]);
		}
		if ($rs['Pacote']!=""){
			$dados[colunas][conteudo][$i][3] .= "	<div style='margin:2px; float:left; width:33%'>".$rs['Pacote']."</div>
													<div style='margin:2px; float:left; width:30%' align='right'>".number_format($rs['Quantidade_Lances'], 0, ',', '.')."</div>
													<div style='margin:2px; float:left; width:30%' align='right'>".number_format($rs['Valor_Venda'], 2, ',', '.')."</div>";
		}
		$produtoIDAnt = $rs['Produto_ID'];
		$p++;
	}
	$largura = "100%";
	$colunas = "3";

	$dados[colunas][titulo][1] 	= "Plano";
	$dados[colunas][titulo][2] 	= "Descrição";
	$dados[colunas][titulo][3] .= "	<div style='margin:2px; float:left; width:33%'>Pacote</div>
											<div style='margin:2px; float:left; width:30%' align='right'>Lances</div>
											<div style='margin:2px; float:left; width:30%' align='right'>Valor R$</div>";

	$dados[colunas][tamanho][3] = "width='30%'";

	echo "	<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Planos: $i
						&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Pacotes: $p
						<input type='button' class='plano-inc-alt' leilao-id='' value='Incluir Novo'/>
					</p>
				</div>
				<div class='conteudo-interno'>";
	geraTabela($largura,$colunas,$dados, null, 'plano-localiza', 2, 2, 100,1);
	echo "		</div>
			 </div>";
//	}
?>