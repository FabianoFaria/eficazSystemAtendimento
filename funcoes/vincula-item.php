<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	$id 		= $_GET['id'];
	$slug 		= $_GET['slug'];
	$bloco  	= str_replace('interno-','',$_GET['bloco']);
	$principal 	= $_GET['principal'];
	$secundario = $_GET['secundario'];
	if(substr($slug, -1)=='/')$slug = substr($slug, 0,-1);
	$slugPagina = explode('/',$slug);
	$slugPagina = $slugPagina[count($slugPagina)-1];
	$consulta = mpress_query("select Modulo_Pagina_ID from modulos_paginas where Slug = '$slugPagina'");
	$pagina = mpress_fetch_array($consulta); $pagina = $pagina[Modulo_Pagina_ID];
	mpress_query("insert into modulos_campos_vinculos(Modulo_Pagina_ID, Tipo_Principal_ID, Tipo_Secundario_ID)values($pagina,$bloco,$id)");
?>
	<form action='<?php echo $slug?>' method='post' name='retorno'>
		<input type='hidden' name='vinculo-principal'  value='<?php echo $principal?>'>
		<input type='hidden' name='vinculo-secundario' value='<?php echo $secundario?>'>
	</form>
	<script>document.retorno.submit();</script>
