<?php
	include('functions.php');
	global $configChamados, $modulosAtivos;

	$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
							Dados Gerais
						</div>";

	if ($_POST["workflow-id"]!=""){
		if ($modulosAtivos[produtos])
			$produtos = mpress_fetch_array(mpress_query("select count(*) Total from chamados_workflows_produtos t where t.Workflow_ID = '".$_POST["workflow-id"]."' and Situacao_ID = 1"));
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Propostas' attr-div='.conjunto2' attr-pos='2'>
									Propostas
								</div>";

		if ($modulosAtivos['projetos'])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Tarefas' attr-div='.conjunto6' attr-pos='6'>
									Tarefas
								</div>";

		$menuSuperior .="		<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Documentos e Anexos' attr-div='.conjunto3' attr-pos='3'>
									Documentos
								</div>";
		if ($configChamados['chamados']=='0')
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar ".$_SESSION['objeto']."' attr-div='.conjunto4' attr-pos='4'>
									".$_SESSION['objeto']."
								</div>";
		
		if ($modulosAtivos['financeiro'])
			$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-5' title='Visualizar Financeiro' attr-div='.conjunto5' attr-pos='5'>
									Financeiro
								</div>";
	}
?>