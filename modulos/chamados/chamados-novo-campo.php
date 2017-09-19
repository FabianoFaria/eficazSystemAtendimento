<?php
	include("functions.php");
	gerenciaCamposCadastraNovo();
	$dadospagina = get_page_content();

	$modulos = mpress_query("select Campo_ID, Titulo_Campo,Colunas_Campo, t2.Descr_Tipo Mascara_Campo, t1.Descr_Tipo Tipo_Campo
							 from modulos_campos mc
							 inner join tipo t1 on t1.tipo_id = mc.Tipo_Campo
							 inner join tipo t2 on t2.tipo_id = mc.Mascara_Campo
							 where mc.Situacao_ID = 1 and Modulo_ID = ".$dadospagina[Modulo_ID]);
	while($row = mpress_fetch_array($modulos)){
		$i++;
		$dados[colunas][conteudo][$i][1] = $row[Titulo_Campo];
		$dados[colunas][conteudo][$i][2] = "<center>".$row[Colunas_Campo];
		$dados[colunas][conteudo][$i][3] = "<center>".$row[Tipo_Campo];
		$dados[colunas][conteudo][$i][4] = "<center>".$row[Mascara_Campo];
		$dados[colunas][conteudo][$i][5] = "<center><a href='#' class='exclui exclui-campo-personalizado' id='exclui-campo-".$row[Campo_ID]."'>excluir</a>";
	}

	$largura = "100%";
	$colunas = "2";
	$colunas = "5";

	$dados[colunas][titulo][1] 		= "Titulo Campo";
	$dados[colunas][titulo][2] 		= "<center>Colunas";
	$dados[colunas][titulo][3] 		= "<center>Tipo";
	$dados[colunas][titulo][4] 		= "<center>Mascara";

	$dados[colunas][tamanho][2] = "width='10%'";
	$dados[colunas][tamanho][3] = "width='10%'";
	$dados[colunas][tamanho][4] = "width='10%'";
	$dados[colunas][tamanho][5] = "width='7%'";


	if(count($dados[colunas][conteudo])>=1)
 		geraTabela($largura, $colunas, $dados);
?>