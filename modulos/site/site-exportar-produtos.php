<?php
	include("functions.php");
	$siteID = $_POST['site-id'];
	carregarConexaoSite($siteID);
	exportarProdutosSite($siteID,'');
	echo "<form action='".$caminhoSistema."/site/site-gerenciador#menu-superior-4' method='post' name='retorno'> <input type='hidden' name='site-seleciona' value='$siteID'></form><script>document.retorno.submit();</script>";
?>