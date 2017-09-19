<?php
	include("functions.php");
	$dadospagina = get_page_content();
?>
<div id="container-geral">
	<div class="titulo-container">
		<div class="titulo" style="min-height:25px">
			<p style="margin-top:2px;">Mensagens Enviadas
			</p>

		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<p class="omega">
					<?php mensagensEnviadas();?>
				</p>
			</div>
		</div>

	</div>