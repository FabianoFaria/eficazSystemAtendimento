<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaGruposDragDrop($dadospagina[Titulo],'chamados-acao',$dadospagina[Slug_Pagina]);
?>