<?php
	include('../../../wp-config.php');
	include('functions.php');
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');
	$siteID = $_POST['site-id'];
	global $caminhoSistema;
	carregarConexaoSite($siteID);
	exportarProdutosImagens($siteID,'');
	echo "<form action='".$caminhoSistema."/site/site-gerenciador#menu-superior-4' method='post' name='retorno'> <input type='hidden' name='site-seleciona' value='$siteID'></form><script>document.retorno.submit();</script>";
?>
