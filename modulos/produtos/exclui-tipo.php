<?php
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$tipoID = $_GET['tipoID'];
	mpress_query("update tipo set situacao_id = 2 where tipo_id = $tipoID");
	echo "update tipo set situacao_id = 2 where tipo_id = $tipoID";
?>