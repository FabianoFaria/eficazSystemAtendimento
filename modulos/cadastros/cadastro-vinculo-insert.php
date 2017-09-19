<?php
	include("functions.php");
	$cadastroID = $_GET['cadastro-id'];
	$cadastroFilhoID = $_GET['cadastro-filho-id'];
	$tipoVinculoID = $_GET['tipo-vinculo-id'];
	inserirCadastroVinculo($cadastroID, $cadastroFilhoID, $tipoVinculoID);
?>