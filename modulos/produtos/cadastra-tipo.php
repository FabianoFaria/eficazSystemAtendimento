<?php
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$valor 			 = $_GET['titulo'];
	$grupoPrincipal  = $_GET['principal'];
	$grupoSecundario = $_GET['secundario'];
	mpress_query("insert into tipo(tipo_grupo_id, descr_tipo)values('$grupoPrincipal','$valor')");
?>