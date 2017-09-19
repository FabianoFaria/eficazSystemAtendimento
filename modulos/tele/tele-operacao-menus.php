<?php
	global $modulosAtivos;
	//$tipoFluxo = $_GET['tipo-fluxo'];
	//if ($tipoFluxo=='direto'){
	$workflowID = $_GET['workflow-id'];
	if ($workflowID=='') $workflowID = $_POST['workflow-id'];
	if ($workflowID!=''){
		$campanhaID = $_POST['campanha-id'];
		$tipoInteracao = $_POST['tipo-interacao'];
		$menuSuperior .="		<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
									Dados Gerais
								</div>";
		if ($modulosAtivos['projetos']){
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Tarefas' attr-div='.conjunto2' attr-pos='2'>
									Tarefas
								</div>";
		}
		$menuSuperior .="		<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Documentos e Anexos' attr-div='.conjunto3' attr-pos='3'>
									Documentos
								</div>";
	}
?>