<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposProdutos($dadospagina[Titulo],$dadospagina[Tipo_Grupo_ID],'');
?>