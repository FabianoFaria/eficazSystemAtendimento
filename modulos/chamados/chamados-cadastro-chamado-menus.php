<?php
	include('functions.php');
	global $configChamados, $modulosAtivos;
	$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-4' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='4'>
							Dados Gerais
						</div>";
	if ($_POST["workflow-id"]!=""){
		if ($modulosAtivos['produtos']){
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Produtos' attr-div='.conjunto2' attr-pos='2'>
									Produtos
								</div>";
		}
		if ($modulosAtivos['projetos']){
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-10' title='Visualizar Tarefas' attr-div='.conjunto10' attr-pos='10'>
									Tarefas
								</div>";
		}
		$menuSuperior .="		<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Documentos e Anexos' attr-div='.conjunto4' attr-pos='3'>
									Documentos
								</div>";
		if ($modulosAtivos['envios'])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-1' title='Visualizar Intera&ccedil;&atilde;o com Log&iacute;stica' attr-div='.conjunto3' attr-pos='1'>
									CD
								</div>";

		if ($modulosAtivos['compras'])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Compras' attr-div='.conjunto6' attr-pos='6'>
									Compras
							 	</div>";

		if ($modulosAtivos['financeiro']){
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-7' title='Visualizar Financeiro' attr-div='.conjunto7' attr-pos='7'>
									Financeiro
							 	</div>";
		}

		if (($_SESSION['dadosUserLogin']['grupoID'] != -2) && ($_SESSION['dadosUserLogin']['grupoID'] != -3))
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo mostra-localizacao' id='menu-superior-8' title='Visualizar Localização' attr-div='.conjunto8' attr-pos='8'>
									Localiza&ccedil;&atilde;o
								</div>";

	}
?>