<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'chamados-item',$dadospagina[Slug_Pagina]);
?>