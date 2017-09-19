<?php
include("functions.php");
echo "	<div id='div-retorno'></div>
		<div class='titulo-container' id='div-produto-dados' >
			<div class='titulo'>
				<p>
					Configura&ccedil;&otilde;es Gerais
					<input type='button' value='Salvar' class='botao-salvar-configuracoes-gerais'  Style='float:right;margin-right:0px;'/>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p>E-mail justificativa movimentação manual:</p>
					<p><input type='text' name='emails-justificativa-manual' style='width:98.5%' value='".$configProdutos['emails-justificativa-manual']."'/></p>
				</div>
			</div>
		</div>";
?>