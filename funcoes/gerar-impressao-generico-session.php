<?php
session_start();
include("../config.php");
include("../includes/functions.gerais.php");
global $caminhoSistema;
echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head><link rel='stylesheet' type='text/css' href='$caminhoSistema/css/print.css'/></head>
			<body>
				<header class='header'>&nbsp;</header>
				".$_SESSION["session-conteudo-relatorio"]."
				<footer class='footer'>&nbsp;</footer>
			</body>
			<script>window.print();</script>
		</html>";
?>