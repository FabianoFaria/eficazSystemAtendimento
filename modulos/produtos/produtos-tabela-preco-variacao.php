<?php
session_start();
header("Cache-Control: no-cache");
header("Expires: -1");
header("Pragma: no-cache");
header("Content-Type: text/html; charset=ISO-8859-1",true);
include("functions.php");
$produtoVariacaoID =  $_GET['produto-variacao-id'];
global $caminhoSistema;

$sql = "SELECT concat(pd.Nome,' ', pv.Descricao) as Descricao 
		FROM produtos_variacoes pv
		INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID WHERE pv.Produto_Variacao_ID = '$produtoVariacaoID'";
//echo "<br>".$sql;
$resultado = mpress_query($sql);
if ($rs = mpress_fetch_array($resultado)){
	$descricaoProdutoVariacao = $rs['Descricao'];
}
/*
$sql = "SELECT ptp.Tabela_Preco_ID, ptp.Titulo_Tabela, ptpd.Valor_Custo, ptpd.Valor_Venda
		FROM produtos_variacoes pv 
		INNER JOIN produtos_tabelas_precos ptp
		LEFT JOIN produtos_tabelas_precos_detalhes ptpd on  ptpd.Produto_Variacao_ID = '$produtoVariacaoID' 
					and ptpd.Tabela_Preco_ID = ptp.Tabela_Preco_ID 
					and ptpd.Situacao_ID = 1 
					and ptp.Situacao_ID = 1 
					and pv.Forma_Cobranca_ID = 35";
*/
$sql = "select ptp.Tabela_Preco_ID, ptp.Titulo_Tabela 
		from produtos_tabelas_precos ptp
		where Situacao_ID = 1 ";
//echo "<br>".$sql;
$i = 0;
$resultado = mpress_query($sql);
while($rs = mpress_fetch_array($resultado)){
	$valorCusto = 0;	
	$valorVenda = 0;	
	$resultado2 = mpress_query("select ptpd.Valor_Custo, ptpd.Valor_Venda 
								from produtos_tabelas_precos_detalhes ptpd
								where ptpd.Situacao_ID = 1 and ptpd.Produto_Variacao_ID = '$produtoVariacaoID' and Tabela_Preco_ID = '".$rs['Tabela_Preco_ID']."'");
	if ($rs2 = mpress_fetch_array($resultado2)){
		$valorCusto = $rs2["Valor_Custo"];
		$valorVenda = $rs2["Valor_Venda"];
	}
	
	$i++;
	$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;".$rs["Titulo_Tabela"]."<input type='hidden' id='tabela-preco-id[]' name='tabela-preco-id[]' value='".$rs["Tabela_Preco_ID"]."'>";
	$dados[colunas][conteudo][$i][2] = "<span style='float:right;'><input type='text' name='valor-custo[]' value='".number_format($valorCusto, 2, ',', '.')."' class='formata-valor' style='text-align:right;'/></span>";
	$dados[colunas][conteudo][$i][3] = "<span style='float:right;'><input type='text' name='valor-venda[]' value='".number_format($valorVenda, 2, ',', '.')."' class='formata-valor' style='text-align:right;'/></span>";
}
if ($i==0){
	$dados[colunas][colspan][1][1] = 3;
	$dados[colunas][conteudo][1][1] = "<p style='margin:0px; color:red;' align='center'>Nenhum registro localizado</p>";
}

$dados[colunas][titulo][1] 	= "Tabela de Pre&ccedil;o";
$dados[colunas][titulo][2] 	= "<span style='float:right;'>Valor Custo</span>";
$dados[colunas][titulo][3] 	= "<span style='float:right;'>Valor Venda</span>";

$dados[colunas][tamanho][1] = "width='50%'";
$dados[colunas][tamanho][2] = "width='25%'";
$dados[colunas][tamanho][3] = "width='25%'";


echo "	<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>";
echo get_header();
echo "			<script type='text/javascript' src='$caminhoSistema/modulos/produtos/produtos.js'></script>
			</head>
		<body>";

echo "
		<form name='frmPrecos' id='frmPrecos' method='post' class='iframe-interno'>
			<input type='hidden' id='produto-variacao-id' name='produto-variacao-id' value='$produtoVariacaoID'/>
			<div class='titulo-container' style='width:99.8%;'>
				<div class='titulo'>
					<div class='titulo-secundario' class='uma-coluna'>
						<p>Produto</p>
					</div>
				</div>
				<div class='conteudo-interno'>
					<p>$descricaoProdutoVariacao</p>
				</div>
			</div>

			<div class='titulo-container' style='width:99.8%;'>
				<div class='titulo'>
					<p>
						Tabelas de Pre&ccedil;o
						<input type='button' class='atualizar-valores-produto-tabelas botoes-acoes' value='Atualizar'/>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' class='uma-coluna'>
						<p>";
geraTabela("100%", 3, $dados, '','tabelas-precos-variacao',2,2);
echo "					</p>
					</div>
				</div>
			</div>
			<div class='titulo-container' style='width:99.8%;'>
				<div class='titulo'>
					<p>
						Faixas de Pre&ccedil;o por Quantidade
						<input type='button' class='incluir-alterar-valores-faixas botoes-acoes' tipo-faixa='143' value='Incluir'/>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='bloco-incluir-alterar-preco-faixas faixas-143 uma-coluna titulo-secundario esconde' style='margin-bottom:5px;'></div>
					<div class='titulo-secundario' class='uma-coluna'>
						<p>";
carregarTabelaPrecosFaixas($produtoVariacaoID,143);
echo "					</p>
					</div>
				</div>
			</div>


			<div class='titulo-container' style='width:99.8%;'>
				<div class='titulo'>
					<p>
						Faixas de Pre&ccedil;o por Caracteristicas
						<input type='button' class='incluir-alterar-valores-faixas botoes-acoes' tipo-faixa='144' value='Incluir'/>
						<!--<input type='button' class='atualizar-valores-quantidade botoes-acoes' value='Atualizar'/>-->
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='bloco-incluir-alterar-preco-faixas faixas-144 uma-coluna titulo-secundario esconde' style='margin-bottom:5px;'></div>
					<div class='titulo-secundario' class='uma-coluna'>
						<p>";
carregarTabelaPrecosFaixas($produtoVariacaoID,144);
echo "					</p>
					</div>
				</div>
			</div>

		</form>";
echo "
		</body>
		</html>";