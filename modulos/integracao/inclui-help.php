<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	if($_POST['select-pagina-principal'][1] != "") $idPagina = $_POST['select-pagina-principal']['1']; else $idPagina = $_POST['select-pagina-principal']['0'];
	$descricao = $_POST['help-descricao'];
	$titulo = $_POST['titulo-help'];
	mpress_query("insert into help(Slug_pagina, Titulo, Descricao)values('$idPagina','$titulo','$descricao')");
	header("location:$caminhoSistema/help/gerenciar-help");
?>