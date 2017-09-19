<?php
include("functions.php");
global $caminhoFisico, $modulosAtivos, $modulosGeral;
if ($_GET['tipo']=='direto'){
	echo "	<style>
				#topo-container{display:none;}
				#menu-container{display:none;}
			</style>";
}

echo "		<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>Listas Importadas </p>
				</div>
				<div class='conteudo-interno'>";

	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by top.Nome";
	}
	echo "<input type='hidden' id='operacao-id' name='operacao-id' value=''>";

	$sql = "SELECT * from modulos_listas where Slug = 'modulos_listas_cadastros'";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Descricao]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>
												<!--<div class='btn-editar btn-editar-lista' style='float:right; padding-right:5px'>&nbsp;</div>-->
											</p>";
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma opera&ccedil;&atilde;o cadastrada</p>";
	}
	else{
		$largura = "100%";
		$dados[colunas][tamanho][1] = "width='95%'";
		$dados[colunas][tamanho][2] = "width='5%'";
		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "&nbsp;";
		geraTabela($largura,2,$dados, null, 'listas-cadastros', 2, 2, '','');
	}
	echo "		</div>
			</div>";


echo "		<!-- INICIO Bloco Upload usando PLUPLOAD -->
			<div id='div-documentos'></div>
			<div id='container'>
				<input type='hidden' id='pickfiles'/>
				<input type='hidden' id='uploadfiles'/>
			</div>
			<!-- FIM Bloco Upload usando PLUPLOAD -->";

?>