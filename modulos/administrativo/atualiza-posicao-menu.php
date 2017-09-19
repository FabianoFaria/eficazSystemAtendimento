<?php
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	mpress_query("update modulos set posicao = '".$_GET['posicao']."' where Modulo_ID = '".$_GET['modulo']."'");

echo "update modulos set posicao = '".$_GET['posicao']."' where Modulo_ID = '".$_GET['modulo']."'";
?>