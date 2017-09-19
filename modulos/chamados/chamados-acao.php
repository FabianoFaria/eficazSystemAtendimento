<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'chamados-sub-item',$dadospagina[Slug_Pagina]);
?>