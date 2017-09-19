<?php
	global $modulosAtivos;
	$menuSuperior .= "	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.grupo1' attr-pos='1'>
							Dados Gerais
						</div>
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Follows da OC' attr-div='.grupo2' attr-pos='2'>
							Orçamento
						</div>
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Documentos e Anexos' attr-div='.conjunto6' attr-pos='6'>
							Documentos
						</div>
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar Origem das Solicitações' attr-div='.grupo4' attr-pos='4'>
							Origem
						</div>";
	if ($modulosAtivos[financeiro])
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-5' title='Visualizar Financeiro' attr-div='.conjunto5' attr-pos='5'>
							Financeiro
						 </div>";
?>