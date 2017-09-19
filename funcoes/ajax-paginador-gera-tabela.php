<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	session_start();
	include("../config.php");
	include("../includes/functions.gerais.php");
	$_SESSION['paginador'] = 1;
	$idTabela = $_SESSION[idTabela];
	geraTabela($_SESSION[$idTabela][largura],$_SESSION[$idTabela][colunas],$_SESSION[$idTabela][dados], null,  $_SESSION[idTabela], 2, 2,  $_SESSION[$idTabela][registros]);
?>