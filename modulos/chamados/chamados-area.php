<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'',$dadospagina[Slug_Pagina]);
?>