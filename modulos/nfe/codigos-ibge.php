<?php
	header('Content-Type: application/xml; charset=ISO8859-1');
	include("../../config.php");
	$estado = $_GET['uf'];
	$cidade = str_replace("-"," ",$_GET['cidade']);

	$resultado = mpress_fetch_array(mpress_query("select distinct * from codigos_ibge where upper(Abreviatura_UF) = upper('$estado') and upper(Nome_Municipio) = upper('$cidade')"));

	$arquivoXML="<?xml version='1.0' encoding='ISO-8859-1'?>
				<retorno>
					<dadosconsulta>
						<uf>$resultado[0]</uf>
						<abraviatura_uf>$resultado[1]</abraviatura_uf>
						<nome_uf>$resultado[2]</nome_uf>
						<mesorregiao_geografica>$resultado[3]</mesorregiao_geografica>
						<nome_mesorregiao>$resultado[4]</nome_mesorregiao>
						<microrregiao_geografica>$resultado[5]</microrregiao_geografica>
						<nome_microrregiao>$resultado[6]</nome_microrregiao>
						<municipio>$resultado[7]</municipio>
						<nome_municipio>$resultado[8]</nome_municipio>
						<distrito>$resultado[9]</distrito>
						<nome_distrito>$resultado[10]</nome_distrito>
						<codigo_unificado>$resultado[0]$resultado[7]</codigo_unificado>
					</dadosconsulta>
				</retorno>";
echo $arquivoXML;
?>