<?php
$turmaID = $_POST['id-turma'];

$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
						Dados Gerais
					</div>";


if ($turmaID!=""){
	$menuSuperior .="	<!--
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-8' title='Comissão de Formatura' attr-div='.conjunto8' attr-pos='8'>
							Comissão de Formatura
						</div>
						-->
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Documentos' attr-div='.conjunto3' attr-pos='3'>
							Documentos
						</div>";

	$menuSuperior .="	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-7' title='Visualizar Processos' attr-div='.conjunto7' attr-pos='7'>
							Or&ccedil;amentos
						</div>";
	$menuSuperior .="	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Processos' attr-div='.conjunto2' attr-pos='2'>
							".$_SESSION['objeto']."
						</div>";

	$menuSuperior .="	<!--
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Dados Financeiro' attr-div='.conjunto6' attr-pos='6'>
							Financeiro
						</div>
						-->";
	$menuSuperior .="	<!--
						<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar Produtos' attr-div='.conjunto4' attr-pos='4'>
							Produtos
						</div>
						-->";
}
?>