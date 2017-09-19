<?php
	include("functions.php");
	$produtoVariacaoID = $_POST["select-produtos"];
	echo utf8_encode(optionValueFornecedoresProduto($produtoVariacaoID, ""));
?>