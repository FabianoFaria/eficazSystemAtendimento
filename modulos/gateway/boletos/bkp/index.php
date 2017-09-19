<?php
	require_once("../../../../wp-load.php");
	require_once("../../../config.php");

	$pedido = $_GET['pid'];
	$representante = $_GET['representante'];

	$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$representante' and meta_key = 'representantes_pagamento'");
	if($row = mpress_fetch_array($rs))
		$configuracaoBoleto = unserialize(unserialize($row['meta_value']));

		 if($configuracaoBoleto[boleto][banco] == '001') $boletobanco = 'boleto_bb.php';
	else if($configuracaoBoleto[boleto][banco] == '237') $boletobanco = 'boleto_bradesco.php';
	else if($configuracaoBoleto[boleto][banco] == '104') $boletobanco = 'boleto_cef.php';
	else if($configuracaoBoleto[boleto][banco] == '399') $boletobanco = 'boleto_hsbc.php';
	else if($configuracaoBoleto[boleto][banco] == '341') $boletobanco = 'boleto_itau.php';
	else if($configuracaoBoleto[boleto][banco] == '033') $boletobanco = 'boleto_santander_banespa.php';

	header("location:./$boletobanco?pid=$pedido&representante=$representante&data=".$_GET['data']);
?>
