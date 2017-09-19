<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	require_once("functions.php");
	echo carregarSessaoFollows($_GET['sessaoID'],'');
?>