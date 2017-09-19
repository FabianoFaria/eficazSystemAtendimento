<?php
	include("functions.php");
	$dadospagina = get_page_content();
	tinyMCE('mensagem-conteudo');
	$mensagemEdita = $_POST['edita-mensagem'];
	$mensagemPai = $_POST['mensagem-pai'];

	if(($mensagemEdita != "")||($mensagemPai != "")){
		$resultado = mpress_query("select * from mensagens where Mensagem_ID in('$mensagemEdita','$mensagemPai')");
		if($row = mpress_fetch_array($resultado)){
			$destinatario 	= unserialize($row['Destinatarios']);
			$copia 		  	= unserialize($row['Destinatarios_Copia']);
			$assunto 		= $row['Assunto'];
			$mensagem 		= $row['Mensagem'];
		}
	}
?>
<div id="container-geral">
	<div class="titulo-container">
		<div class="titulo" style="min-height:25px">
			<p style="margin-top:2px;">Detalhes da Mensagem
				<input type="button" value="enviar mensagem" id="envia-mensagem" Style='width:105px;float:right;'>
				<input type="button" value="salvar mensagem" id="salva-mensagem" Style='width:105px;float:right;'>
			</p>

		</div>
		<div id='div-retorno'></div>
		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<p class="omega">
					Assunto da mensagem:
					<input type='text' name='assunto-mensagem' id='assunto-mensagem' class='required required-save' value='<?php echo $assunto;?>'>
				</p>
			</div>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario duas-colunas">
				<p class="omega">
					Destinatarios:
					<select name="mensagem-destinatario[]" id="mensagem-destinatario" class='mensagem-destinatario required' multiple Style='height:60px;font-size:10px;'>
						<?php selecionaDestinatarios($destinatario);?>
					</select>
				</p>
			</div>
			<div class="titulo-secundario duas-colunas">
				<p class="omega">
					Cópia para:
					<select name="mensagem-copia[]" id="mensagem-copia" class='mensagem-copia required' multiple Style='height:60px;font-size:10px;'>
						<?php selecionaDestinatarios($copia);?>
					</select>
				</p>
			</div>
		</div>

		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<p class="omega">
					<textarea name='mensagem-conteudo' id='mensagem-conteudo' class='required' Style='height:150px'><?php echo $mensagem;?></textarea>
				</p>
			</div>
		</div>
	</div>
	<input type='hidden' name='edita-mensagem' id='edita-mensagem' value='<?php echo $mensagemEdita;?>'>
	<input type='hidden' name='mensagem-pai' id='mensagem-pai' value='<?php echo $mensagemPai;?>'>
