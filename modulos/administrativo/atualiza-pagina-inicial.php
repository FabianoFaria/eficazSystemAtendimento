<?php
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	$rs = mpress_query("select Descr_Tipo from tipo where Tipo_ID = 6 and Descr_Tipo <> ''");
	if($row = mpress_fetch_array($rs))
		$dadosPagina = unserialize($row['Descr_Tipo']);

	$dadosPagina[$_SESSION['dadosUserLogin']['userID']] = $_GET['pagina'];
	mpress_query("update tipo set Descr_Tipo = '".serialize($dadosPagina)."' where Tipo_ID = 6");
?>