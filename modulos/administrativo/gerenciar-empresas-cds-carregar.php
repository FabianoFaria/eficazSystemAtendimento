<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	carregarCentroDistribuicao($_POST['cd-id'],$_POST['select-empresa']);
?>