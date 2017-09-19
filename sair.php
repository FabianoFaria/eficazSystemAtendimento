<?php
	session_start();
	unset($dadosUserLogin);
	unset($modulosAtivos);
	unset($modulosGeral);
	unset($_SESSION['dadosUserLogin']);
	unset($_SESSION['modulosAtivos']);
	unset($_SESSION['modulosGeral']);

	header("location:./");
?>