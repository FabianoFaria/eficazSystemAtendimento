<?php
	if ($_POST['projeto-id']!="")
		$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Tarefas do Projeto' attr-div='.grupo2' attr-pos='2'>
								Tarefas
							</div>";

	$menuSuperior .= "	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.grupo1' attr-pos='1'>
							Dados Gerais
						</div>";
?>