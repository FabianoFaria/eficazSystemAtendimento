<?php
	include("functions.php");
	if (($_GET['id']!="")||($_GET['origem']!="")){
		carregarDocumentos($_GET['id'],$_GET['origem'],$_GET['destino']);
	}
?>

