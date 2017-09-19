<?php
include('config.php');
include('includes/functions.gerais.php');
echo removeAcentos(utf8_decode('FRETE POR CONTA DE EFICAZ SYSTEM EIRELLI - EPP)"EMPRESA ENQUADRADA NO SIMPLES NACIONAL LEI 123/2006 ART.293 RIMS DESCRETO 1980/2007" "REMESSA DO PROPRIO IMOBILIZADO PARA A UTILIZAÇÃO NO SERVIÇO NA CIDADE DE SÃO JOSÉ DOS PINHAIS - PR" "MERCADORIA DEVERÁ SER ENVIADA PARA NOSSA UNIDADE DE SÃO JOSÉ DOS PINHAIS - LOCALIZADA NO ENDEREÇO: R. NORBERTO DE BRITO, N°1131 - CENTRO - SÃO JOSÉ DOS PINHAIS - PR"'));
/*
$memcache = memcache_connect('localhost', 11211);

if ($memcache) {
	$memcache->set("str_key", "String to store in memcached");
	$memcache->set("num_key", 123);

	$object = new StdClass;
	$object->attribute = 'test';
	$memcache->set("obj_key", $object);

	$array = Array('assoc'=>123, 345, 567);
	$memcache->set("arr_key", $array);

	var_dump($memcache->get('str_key'));
	var_dump($memcache->get('num_key'));
	var_dump($memcache->get('obj_key'));
}
else {
	echo "Connection to memcached failed";
}
*/
?>

