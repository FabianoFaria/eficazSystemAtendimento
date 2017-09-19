<?php
	global $modulosAtivos, $caminhoFisico, $configCadastros, $configChamados;
	$configCadastros = carregarConfiguracoesGeraisModulos('cadastros');
	$configChamados = carregarConfiguracoesGeraisModulos('chamados');

	$menuSuperior .= "	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='#div-dados-gerais' attr-pos='1'>
							Dados Gerais
						</div>";

	$cadastroID = $_POST['cadastroID'];
	if ($cadastroID=="") $cadastroID = $_GET['cadastroID'];
	if ($cadastroID !=""){
		if ($_SESSION[dadosUserLogin][grupoID] != -3){
			if($modulosAtivos['turmas']){
				include($caminhoFisico.'/modulos/turmas/functions.php');
				$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-5' title='Dados Acadêmicos' attr-div='#div-cadastros-dados-academicos'  attr-pos='5'>
										Dados Acadêmicos
								  </div>";
			}
			if ($configChamados['crm']=='1'){
				//$menuSuperior .= " <div class='menu-interno-superior menu-interno-modulo' id='menu-superior-8' title='Visualizar Oportunidades' attr-div='.conjunto8' attr-pos='8'>
				//						Oportunidades
				//				   </div>";
				//if ($modulosAtivos['projetos'])
				//	$strTarefaFollow = "Tarefas e";
				//$menuSuperior .= " <div class='menu-interno-superior menu-interno-modulo' id='menu-superior-9' title='Visualizar Follows' attr-div='.conjunto9' attr-pos='9'>
				//						$strTarefaFollow Follows
				//				   </div>";
				$menuSuperior .= " <div class='menu-interno-superior menu-interno-modulo' id='menu-superior-9' title='Visualizar Follows' attr-div='.conjunto9' attr-pos='9'>
										Tarefas
								   </div>";
			}
			if ($modulosAtivos['chamados']){
				$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-7' title='Visualizar Orçamentos' attr-div='.conjunto7' attr-pos='7'>
									Or&ccedil;amentos
								</div>";
				$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar ".$_SESSION['objeto']."' attr-div='.conjunto4' attr-pos='4'>
									".$_SESSION['objeto']."
								</div>";
			}

			$menuSuperior .= " <div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Documentos Cadastrados' attr-div='#div-documentos' attr-pos='2'>
									Documentos
							   </div>";
			if (($_SESSION[dadosUserLogin][grupoID]!='-2') && ($_SESSION[dadosUserLogin][grupoID]!='-3') && ($configCadastros['exibir-vinculos']==1)){
				$sql = "Select Tipo_Vinculo from cadastros_dados where Cadastro_ID = $cadastroID";
				$vinculos = mpress_query($sql);
				if($rowVinc = mpress_fetch_array($vinculos)){
					$tipos = unserialize($rowVinc['Tipo_Vinculo']);
					foreach ($tipos as $tipo) {
						$sql = "Select Descr_Tipo from tipo where Tipo_ID = ".$tipo." and Situacao_ID = 1";
						$resultado = mpress_query($sql);
						if($row = mpress_fetch_array($resultado)){
							$titulo = $row['Descr_Tipo'];
							$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo btn-vinculos' id='menu-superior-$tipo' tipo-vinculo-id='$tipo' title='Visualizar $vinculoNome' attr-div='#div-tipos-$tipo' attr-pos='$tipo'>
												".($titulo)."
											</div>";
						}
					}
				}
			}
		}
	}
?>