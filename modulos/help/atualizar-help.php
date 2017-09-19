<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
?>
<div id='help-container'>
	<div class='titulo-container'>
		<div class='titulo' style='min-height:20px'>
			<p style='margin-top:2px;'>
				Dados Principais
				<input type='button' value='Atualizar registro' id='botao-atualizar-help' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
				<input type='hidden' name='pagina-help-id' id='pagina-help-id' value='<?php echo $_POST['pagina-help-id']?>'>
			</p>
		</div>
<?php
	$rs = mpress_query("SELECT h.Help_ID, h.Titulo, p1.Modulo_Nome, p1.Pagina_Nome, p1.Pagina_Secundaria_Nome, Descricao
						FROM `help` h
						INNER JOIN help_paginas p1 ON p1.Pagina_Slug = h.Slug_Pagina
						where Help_ID = ".$_POST['pagina-help-id']."
						UNION
						SELECT h.Help_ID, h.Titulo, p2.Modulo_Nome, p2.Pagina_Nome, p2.Pagina_Secundaria_Nome, Descricao
						FROM `help` h
						INNER JOIN help_paginas p2 ON p2.Pagina_Secundaria_Slug = h.Slug_Pagina
						where Help_ID = ".$_POST['pagina-help-id']."
						ORDER BY Modulo_Nome,Pagina_Nome,Pagina_Secundaria_Nome");
	while($row = mpress_fetch_array($rs)){
		$modulo 		= $row['Modulo_Nome'];
		$titulo 		= $row['Titulo'];
		$tituloPai		= $row['Pagina_Nome'];
		$tituloPagina 	= $row['Pagina_Secundaria_Nome'];
		$descricao 		= $row['Descricao'];
	}

$texto="<div class='conteudo-interno'>
			<div class='titulo-secundario quatro-colunas'>
				<p>Título do Módulo</p>
				<p><input type='text' name='titulo-help' id='titulo-help' class='mascara-campo' value='$titulo'></p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Módulo</p>
				<p><input type='text' name='modulo-help' id='modulo-help' class='mascara-campo' value='$modulo' disabled></p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Página Principal</p>
				<p><input type='text' name='modulo-pagina-help' id='modulo-pagina-help' class='mascara-campo' value ='$tituloPai' disabled></p>
			</div>
			<div class='titulo-secundario quatro-colunas'>
				<p>Página Secundária</p>
				<p class='omega'>
					<input type='text' name='pagina-secundaria-help' id='pagina-secundaria-help' class='mascara-campo' value='$tituloPagina' disabled>
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
					".tinyMCE('campo-teste-2')."
					<textarea name='help-descricao' id='campo-teste-2'>$descricao</textarea>
				</div>
			</div>";

	echo $texto;
?>
	</div>
</div>