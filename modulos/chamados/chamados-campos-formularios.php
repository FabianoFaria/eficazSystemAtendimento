<?php
	include("functions.gerais.php");
?>
	<div class="titulo-container">
		<div class="titulo">
			<p>Cadastrar Novo Formulário</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario duas-colunas">
				<p>Título</p>
				<p><input type="text" name="titulo" id="titulo"></p>
			</div>
			<div class="titulo-secundario seis-colunas">
				<p>Tipo de Envio</p>
				<p>
					<select name="tipo-envio" id="tipo-envio">
					<?php echo optionValueGrupo(7)?>
					</select>
				</p>
			</div>
			<div class="titulo-secundario tres-colunas">
				<p>Formulário Superior</p>
				<p>
					<select name="formulario-superior" id="formulario-superior">
					<?php echo optionValueForm()?>
					</select>
				</p>
			</div>
		</div>
	</div>
	<div class="titulo-container">
		<div class="titulo">
			<p>Campos disponíveis</p>
		</div>
		<div class="conteudo-interno">
<?php
 		$dadospagina = get_page_content();
		$modulos = mpress_query("select Campo_ID, Titulo_Campo from modulos_campos where Modulo_ID = ".$dadospagina[Modulo_ID]." and Situacao_ID = 1");
		while($row = mpress_fetch_array($modulos))
			echo "	<div class='item titulo-drag-drop' style='margin-bottom: 5px; margin-left: 5px;' id='item-".$row[Campo_ID]."'><p>$row[Titulo_Campo]</p><p class='esconde'>$row[Campo_ID]</p></div>";
?>
		</div>
	</div>

	<div class="titulo-container">
		<div class="titulo">
			<p>Campos do Formulário</p>
		</div>
		<div id='campos-formulario' class="conteudo-interno cart-formulario droppable" Style='min-height:70px'>

		</div>
	</div>

	<div class="titulo-secundario uma-coluna">
		<p class="direita"><input type="button" value="Cadastrar" id="cadastra-novo-formulario"></p>
	</div>