<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposChamados($dadospagina[Titulo],$dadospagina[Tipo_Grupo_ID],'');
?>