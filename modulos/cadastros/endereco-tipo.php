<?php
	include("functions.php");
	$dadospagina = get_page_content();
	geraTelaTipos($dadospagina[Titulo],'cadastro-tipo',$dadospagina[Slug_Pagina]);
?>