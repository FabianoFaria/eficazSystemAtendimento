<?php
	if($tpPgto=='debito'){
		$tpPgto = 'visa';
		$metodo = "A";
	}
	header("location:cielo/pages/carrinho.php?method=$metodo&tp=$tpPgto&pid=".$_GET['pid']);
?>
