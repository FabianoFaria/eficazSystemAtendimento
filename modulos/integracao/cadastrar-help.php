<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
?>
<div id='help-container'>
	<div class='titulo-container'>
		<div class='titulo' style='min-height:20px'>
			<p style='margin-top:2px;'>
				Dados Principais
				<input type='button' value='Incluir registro' class='help-inclui-novo' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario quatro-colunas'>
				<p>Título do Módulo</p>
				<p><input type='text' name='titulo-help' id='titulo-help' class='mascara-campo'></p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Módulo</p>
				<p>
					<select name='modulo-selecionado' id='modulo-selecionado' class='required'>
						<option value=''>selecione</option>
<?php
	$rs = mpress_query("SELECT DISTINCT Modulo_Nome, Modulo_Slug FROM help_paginas order by Modulo_Nome");
	while($row = mpress_fetch_array($rs))
		echo  "			<option value='".$row['Modulo_Slug']."'>".$row['Modulo_Nome']."</option>";
?>
					</select>
				</p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Página Principal</p>
				<p id='select-pagina-principal-inclui'>
					<select name='select-pagina-principal[]' id='select-pagina-principal' disabled  class='required'>
						<option value=''>selecione</option>;
					</select>
				</p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Página Secundária</p>
				<p class='omega' id='select-pagina-secundaria-inclui'>
					<select select-pagina-principal[] id='' disabled>
						<option value=''>selecione</option>

					</select>
				</p>
			</div>
		</div>
	</div>
	<div class='titulo-container'>
			<div class='titulo' style='min-height:20px'>
				<p style='margin-top:2px;'>
					Descrição do help
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna'>
					<?php tinyMCE('campo-teste-2');?>
					<textarea name='help-descricao' id='campo-teste-2'></textarea>
				</div>
			</div>
	</div>
</div>