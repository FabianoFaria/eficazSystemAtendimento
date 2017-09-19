<?php
	global $modulosAtivos, $caminhoFisico;
	$leilaoID = $_POST['leilao-id'];
	
	if ($leilaoID !=""){
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Participantes leilão' attr-div='.div-participantes'  attr-pos='4'>
								Participantes
						  </div>";
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Pacotes vendidos para o leilão' attr-div='.div-pacotes'  attr-pos='3'>
								Pacotes
						  </div>";
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Lances realizados no leilão' attr-div='.div-lances'  attr-pos='2'>
								Lances
						  </div>";
	}
	$menuSuperior .= "	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.div-dados-leilao' attr-pos='1'>
							Dados Gerais
						</div>";
?>