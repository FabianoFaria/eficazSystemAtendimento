<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");
	carregarOportunidade($_GET["oportunidadeID"],$_GET["cadastroID"]);
?>