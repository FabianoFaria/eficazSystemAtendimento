<?php
include("functions.php");
$perguntaID = $_POST['localiza-pergunta-id'];
if ($perguntaID!=""){
	$sql = "select Descricao, Situacao_ID, Ordem from faq_perguntas where Pergunta_ID = $perguntaID";
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$descricaoPergunta = $row[Descricao];
		$ordemPergunta = $row[Ordem];
		$situacaoID = $row[situacaoID];
	}
}
else{
	$classeInsert = "esconde";
	$sql = "select max(Ordem) as Ordem from faq_perguntas where Situacao_ID = 1";
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$ordemPergunta = $row[Ordem] + 1;
	}
}
?>
	<input type="hidden" id="pergunta-id" name="pergunta-id" value="<?php echo $perguntaID; ?>">
	<input type="hidden" id="localiza-pergunta-id" name="localiza-pergunta-id" value="<?php echo $perguntaID; ?>">
	<div id='cadastros-container'>
		<div class='titulo-container'>
			<div class='titulo' style="min-height:25px">
				<p style="margin-top:2px;">
				Pergunta
				<input type="button" value="Salvar" id="botao-salvar-pergunta" style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">
				<input type="button" value="Excluir" id="botao-excluir-pergunta" class='<?php echo $classeInsert; ?>' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>
					<?php //tinyMCE('descricao-pergunta');?>
					<div class="titulo-secundario" Style='float:left;width:90%'>
						<p>Descri&ccedil;&atilde;o Pergunta</p>
						<p><textarea id="pergunta-descricao" name="pergunta-descricao" class='pergunta-descricao' style='width:99%;height:120px'><?php echo $descricaoPergunta; ?></textarea></p>
					</div>
					<div class="titulo-secundario" Style='float:left;width:10%' align='center'>
						<p>Ordem</p>
						<p><input type='text' id="pergunta-ordem" name="pergunta-ordem" class='pergunta-ordem' class='formata-numero' value='<?php echo $ordemPergunta; ?>' style='width:20px'/></p>
					</div>
				</div>
			</div>
			<div id='div-retorno'></div>
		</div>
	</div>
<?php
if ($perguntaID!=""){
?>

	<div id='cadastros-container'>
		<div class='titulo-container'>
			<div class='titulo' style="min-height:25px">
				<p style="margin-top:2px;">
				Respostas
				<!--<input type="button" value="Salvar" id="botao-salvar-resposta" class="botao-savar-pergunta" style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">-->
				</p>
			</div>
			<div class='conteudo-interno esconde' id='div-incluir-alterar-respostas' id='div-incluir-alterar-respostas' Style='float:left;width:100%;margin-bottom:5px;'></div>
			<div id='div-respostas'></div>
		</div>
	</div>
<?php
}
?>