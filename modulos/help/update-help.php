<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");

	mpress_query("update help set Titulo = '".$_POST['titulo-help']."', Descricao = '".$_POST['help-descricao']."' where Help_ID = '".$_POST['pagina-help-id']."'");
	header("location:$caminhoSistema/help/gerenciar-help");
?>