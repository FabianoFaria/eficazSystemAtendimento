<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'chamados-area',$dadospagina[Slug_Pagina]);
?>