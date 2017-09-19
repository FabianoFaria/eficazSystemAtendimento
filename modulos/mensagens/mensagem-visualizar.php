<?php
	include("functions.php");
	$dadospagina = get_page_content();
	tinyMCE('mensagem-conteudo');
	$mensagemEdita = $_POST['edita-mensagem'];
	if($mensagemEdita != ""){
		$resultado = mpress_query("select * from mensagens where Mensagem_ID = $mensagemEdita");
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
				<input type="button" value="responder mensagem" id="responde-mensagem" Style='width:115px;float:right;' attr-id='<?php echo $mensagemEdita;?>'>
			</p>
		</div>
		<div id='div-retorno'></div>
		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<p class="omega" Style='margin-bottom:5px;'>
					<b>Destinatarios:</b>
					<?php selecionaDestinatariosVisualiza($destinatario);?>
				</p>
				<p class="omega">
					<b>Cópia para:</b>
					<?php selecionaDestinatariosVisualiza($copia);?>
				</p>
			</div>
		</div>

		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<p class="omega" Style='margin-bottom:5px;'>
					<b>Assunto da mensagem:</b>&nbsp;
					<?php echo $assunto;?>
				</p>
				<p class="omega">
					<?php echo $mensagem;?>
				</p>
			</div>
		</div>
	</div>
	<input type='hidden' name='mensagem-pai' id='mensagem-pai' value='<?php echo $mensagemEdita;?>'>
