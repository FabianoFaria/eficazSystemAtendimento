<?php
	include("functions.php");
	$descricaoPergunta = $_POST['localiza-descricao-pergunta'];
?>
	<div id='cadastros-container'>
		<div class='titulo-container'>
			<div class='titulo' style="min-height:25px">
				<p style="margin-top:2px;">
				Filtros de Pesquisa
				<input type="button" value="Incluir Pergunta" id="botao-incluir-pergunta" class="botao-incluir-pergunta" style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario' style='float:left;width:80%'>
					<p>Descri&ccedil;&atilde;o</p>
					<p><input type='text' id='localiza-descricao-pergunta' name='localiza-descricao-pergunta'  maxlength='500' value='<?php echo $descricaoPergunta;?>' style='width:90%'/></p>
				</div>
				<div class='titulo-secundario duas-colunas' Style='margin-top:15px; float:left;width:20%' >
					<p class='direita' align='right'><input type='button' value='Pesquisar' id='botao-pesquisar-perguntas' style='width:100px;margin-right:-5px;'/></p>
				</div>
			</div>
		</div>
	</div>
	<input type='hidden' id='localiza-pergunta-id' name='localiza-pergunta-id' value=''>

<?php
	if ($descricaoPergunta != ""){ $sqlCond .= " and p.Descricao like '%$descricaoPergunta%' ";}
	$sql = "select p.Pergunta_ID, p.Descricao, p.Situacao_ID, p.Ordem
			from faq_perguntas p
			where p.Pergunta_ID is not null
			and Situacao_ID = 1
			$sqlCond Order by Ordem";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br($row[Descricao])."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'><input type='text' class='pergunta-ordem-localiza formata-numero' value='".$row[Ordem]."' pergunta-id='".$row[Pergunta_ID]."' style='width:20px'/></p></p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:center;'><p Style='margin:2px 5px 0 5px;float:left;' class='link pergunta-localiza' pergunta-id='".$row[Pergunta_ID]."'>visualizar</p><a></p>";
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma pergunta localizada</p>";
	}
	else{
		$largura = "100.2%";
		$colunas = "3";
		$dados[colunas][titulo][1] 	= "Pergunta";
		$dados[colunas][tamanho][1] = "width=''";
		$dados[colunas][titulo][2] 	= "Ordem";
		$dados[colunas][tamanho][2] = "width='40px'";
		$dados[colunas][titulo][3] 	= "";
		$dados[colunas][tamanho][3] = "width='60px'";
		geraTabela($largura,$colunas,$dados);
	}
?>