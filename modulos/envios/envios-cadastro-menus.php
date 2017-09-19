<?php
global $modulosAtivos, $modulosGeral;
if ($_POST['localiza-workflow-id'] != ""){
	$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
							Dados Gerais
						</div>";

	$menuSuperior .="
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar Produtos' attr-div='.conjunto4' attr-pos='4'>
							Produtos
						</div>";

	$menuSuperior .="
					<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Documentos Cadastrados' attr-div='#div-documentos' attr-pos='3'>
						Documentos
					</div>";


	if ($modulosGeral[financeiro])
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-5' title='Visualizar Financeiro' attr-div='.conjunto5' attr-pos='5'>
							Financeiro
						 </div>";
	if ($modulosGeral[financeiro])
		$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-8' title='Visualizar Financeiro' attr-div='.conjunto8' attr-pos='8'>
							Emiss&atilde;o de NF-E
						 </div>";

	/*
	$menuSuperior .="
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-7' title='Visualizar Cota&ccedil;&otilde;es' attr-div='.conjunto7' attr-pos='7'>
							Cota&ccedil;&otilde;es
						</div>";
	*/

}
?>