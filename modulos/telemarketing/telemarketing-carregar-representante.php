<?php
	global $wpdb;
	include('../../../sistema/config.php');
	include('../../../wp-load.php');

	$uf = $_GET['uf'];
	$cidade = $_GET['cidade'];

	$resultado = mysql_query("select post_id, meta_value from wp_postmeta where meta_key = 'representantes_regiao'");
	while($row = mysql_fetch_array($resultado)){
		if($idRepresentante == ""){
			$r++;
			$regioesRepresentantes[$r][id] 	  = $row[post_id];
			$regioesRepresentantes[$r][dados] = get_post_meta($row[post_id], 'representantes_regiao');
			$regioesRepresentantes[$r][dados] = unserialize($regioesRepresentantes[$r][dados][0]);
			$regioesRepresentantes[$r][uf] 	  = $regioesRepresentantes[$r][dados][uf];
			$regioesRepresentantes[$r][cidade]= $regioesRepresentantes[$r][dados][cidade];
			unset($regioesRepresentantes[$r][dados]);

			foreach($regioesRepresentantes[$r][uf] as $ufs){
				if($ufs==strtoupper($uf)) $idRepresentante = $regioesRepresentantes[$r][id];
			}
			if($idRepresentante == ""){
				foreach($regioesRepresentantes[$r][cidade] as $cidades){
					for($c=1;$c<=count($cidades);$c++)
						if($cidades[$c]==strtoupper($cidade)) $idRepresentante = $regioesRepresentantes[$r][id];
				}
			}
		}
	}

	if ($idRepresentante==""){
		echo "<p><font color='red'><b>Nenhum Representante Localizado</b></font></p><input type='hidden' name='fornecedor-id' id='fornecedor-id' value=''>";
	}
	else{
		$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$idRepresentante' and meta_key = 'representantes'");
		if($row = mpress_fetch_array($rs)){
			$dadosRepresentante = unserialize($row['meta_value']);
			echo "<p>".$dadosRepresentante[dadosPrincipais][razaoSocial]."</p><input type='hidden' name='fornecedor-id' id='fornecedor-id' value='$idRepresentante'>";
		}
	}
?>