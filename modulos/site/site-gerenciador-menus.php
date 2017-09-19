<?php
	$siteID = $_POST['site-seleciona'];
	if ($siteID=="") $siteID = $_POST['site-id'];
	global $modulosAtivos, $caminhoFisico;

	if ($siteID!=""){

		$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Configurações Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
								Dados Gerais
							</div>";

		if ($modulosAtivos[cadastros])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Configurações Produtos' attr-div='.conjunto2' attr-pos='2'>
									Cadastros
								</div>";

		if ($modulosAtivos[chamados])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Configurações Processos' attr-div='.conjunto3' attr-pos='3'>
									Processos
								</div>";

		if ($modulosAtivos[produtos])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Configurações Produtos' attr-div='.conjunto4' attr-pos='4'>
									Produtos
								</div>";

	}
?>