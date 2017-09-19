<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	$tipo = $_POST['radio-tipo-grupo-27'];
	echo carregarTipoConta($tipo, "");
?>