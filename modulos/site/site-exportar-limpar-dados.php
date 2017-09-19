<?php
	include("functions.php");
	$siteID = $_POST['site-id'];
	carregarConexaoSite($siteID);
	exportarLimparDadosSite($siteID);
?>