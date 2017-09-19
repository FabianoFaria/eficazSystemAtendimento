<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	$slug 		= str_replace("#","",$_GET['slug']);
	if(substr($slug, -1)=='/')$slug = substr($slug, 0,-1);
	$slugPagina = explode('/',$slug);
	$slugPagina = $slugPagina[count($slugPagina)-1];
	$consulta 	= mpress_query("select Modulo_ID from modulos_paginas where Slug = '$slugPagina'");
	$pagina 	= mpress_fetch_array($consulta);
	$titulo 	= $_POST['titulo'];
	$tipo 		= $_POST['tipo-envio'];
	$formulario = $_POST['formulario-superior'];
	$campos 	= serialize($GLOBALS[_POST][campo]);
	mpress_query("insert into modulos_formularios(Modulo_ID,Titulo,Formulario_Superior_ID,Tipo_Envio_ID, campos)values(".$pagina[Modulo_ID].",'$titulo','formulario','$tipo','$campos')");
	header("location:$slug");
?>