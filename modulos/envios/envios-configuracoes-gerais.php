<?php
include("functions.php");
echo "	<div id='div-retorno'></div>
		<div class='titulo-container' id='div-chamado-dados' >
			<div class='titulo'>
				<p>
					Configura&ccedil;&otilde;es Gerais
					<input type='button' value='Salvar' class='botao-salvar-configuracoes-gerais'  Style='float:right;margin-right:0px;'/>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p>E-mail em cópia para envios:</p>
					<p><input type='text' name='emails-copia-envio' style='width:98.5%' value='".$configEnvios['emails-copia-envio']."'/></p>
				</div>
			</div>
		</div>";
?>