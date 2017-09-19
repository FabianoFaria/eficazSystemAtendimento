<?php
	global $modulosGeral;
	$empresaID = $_POST['select-empresa'];
	if ($_POST['empresa-id-nova']!=''){
		$empresaID = $_POST['empresa-id-nova'];
	}
	$menuSuperior .= "	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
							Dados Gerais
					</div>";

	if (($empresaID !="") && ($empresaID !="-1")){
		if ($modulosGeral['nfe']){
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Configurar dados da Nota fiscal eletrônica' attr-div='.conjunto2' attr-pos='2'>Nota Fiscal Eletrônica</div>";
		}
		$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Configurar Centros de Distribuição' attr-div='.conjunto3' attr-pos='3'>Centros de Distribuição</div>";

	}
?>