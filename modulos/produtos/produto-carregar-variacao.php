<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	session_start();
	require_once("functions.php");
	$produtoID 			= $_GET['produtoID'];
	echo carregarVariacoesProduto($produtoID);
?>