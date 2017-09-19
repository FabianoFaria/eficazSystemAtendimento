<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'chamados-grupo',$dadospagina[Slug_Pagina]);
?>