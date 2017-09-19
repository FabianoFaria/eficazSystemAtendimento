<?php
global $modulosAtivos, $caminhoFisico;

$filtroRelatorio = $_POST['filtro-relatorio'];

$menuInterno['menu-superior-1'] = "menu-interno-superior";
$menuInterno['menu-superior-2'] = "menu-interno-superior";
$menuInterno['menu-superior-3'] = "menu-interno-superior";
$menuInterno['menu-superior-4'] = "menu-interno-superior";
$menuInterno['menu-superior-5'] = "menu-interno-superior";
$menuInterno['menu-superior-6'] = "menu-interno-superior";
$menuInterno['menu-superior-7'] = "menu-interno-superior";
$menuInterno['menu-superior-8'] = "menu-interno-superior";
$menuInterno['menu-superior-9'] = "menu-interno-superior";
$menuInterno['menu-superior-10'] = "menu-interno-superior";

if ($filtroRelatorio==''){
	$filtroRelatorio = 'menu-superior-1';
}
$menuInterno[$filtroRelatorio] = "menu-interno-superior-selecionado";

$menuSuperior .= "	<div class='".$menuInterno['menu-superior-1']." menu-interno-modulo menu-relatorio-min' id='menu-superior-1' title='' attr-div='.bloco-1' attr-pos='1'>
						Propostas Din&acirc;mico
				  	</div>";
$menuSuperior .= "	<div class='".$menuInterno['menu-superior-2']." menu-interno-modulo menu-relatorio-min' id='menu-superior-2' title='' attr-div='.bloco-2' attr-pos='2'>
						Fechadas no Per&iacute;odo
				  	</div>";
$menuSuperior .= "	<div class='".$menuInterno['menu-superior-3']." menu-interno-modulo menu-relatorio-min' id='menu-superior-3' title='' attr-div='.bloco-3' attr-pos='3'>
						Em andamento
				  	</div>";
//$menuSuperior .= "	<div class='".$menuInterno['menu-superior-4']." menu-interno-modulo menu-relatorio-min' id='menu-superior-4' title='' attr-div='.bloco-4' attr-pos='4'>
//						???
//				  	</div>";
//$menuSuperior .= "	<div class='".$menuInterno['menu-superior-5']." menu-interno-modulo menu-relatorio-min' id='menu-superior-5' title='' attr-div='.bloco-5' attr-pos='5'>
//						Probabilidade Fechamento
//				  	</div>";
//$menuSuperior .= "	<div class='".$menuInterno['menu-superior-6']." menu-interno-modulo menu-relatorio-min' id='menu-superior-6' title='' attr-div='.bloco-6' attr-pos='6'>
//						Tempo de fechamento X Valor Proposta
//				  	</div>";
//$menuSuperior .= "	<div class='".$menuInterno['menu-superior-7']." menu-interno-modulo menu-relatorio-min' id='menu-superior-7' title='' attr-div='.bloco-7' attr-pos='7'>
//						Pipeline
//				  	</div>";
//$menuSuperior .= "	<div class='".$menuInterno['menu-superior-8']." menu-interno-modulo menu-relatorio-min' id='menu-superior-8' title='' attr-div='.bloco-8' attr-pos='8'>
//						Forecast
//				  	</div>";
echo "<input type='hidden' id='filtro-relatorio' name='filtro-relatorio' value='".$filtroRelatorio."'>";
?>