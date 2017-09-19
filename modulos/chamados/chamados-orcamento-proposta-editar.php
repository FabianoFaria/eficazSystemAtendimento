<?php
	include("functions.php");
	carregarEditarNomeProposta($_GET['proposta-id'],utf8_encode($_GET['nome-atual']), $_GET['valor']);
?>