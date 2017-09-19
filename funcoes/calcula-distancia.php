<?php
	require_once("../config.php");

	$origemID  =  10139;
	$destinoID =  1027;

	$origem 	= mpress_fetch_array(mpress_query("select concat(Logradouro,' ',Numero,' ', Cidade,' ', Uf) Endereco from cadastros_enderecos where Cadastro_id = $origemID and tipo_endereco_ID = 26 limit 1"));
	$origem		= str_replace(' ','%20',trim($origem['Endereco']));
	$destino 	= mpress_fetch_array(mpress_query("select concat(Logradouro,' ',Numero,' ', Cidade,' ', Uf) Endereco from cadastros_enderecos where Cadastro_id = $destinoID and tipo_endereco_ID = 26 limit 1"));
	$destino	= str_replace(' ','%20',trim($destino['Endereco']));

	$distancia = "http://maps.googleapis.com/maps/api/distancematrix/xml?origins=$origem&destinations=$destino&mode=driving&language=pt-BR&sensor=false";

	$ch = curl_init($distancia);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch);
	curl_close($ch);
	$xml = new SimpleXmlElement($data, LIBXML_NOCDATA);

	$erro 		= $xml->row->element->status;
	$tempo 		= $xml->row->element->duration->text;
	$distancia 	= $xml->row->element->distance->text;

	if($erro)
		echo "$distancia ($tempo)";
?>