<?php
global $modulosAtivos, $caminhoFisico, $modulosGeral;
$contaID = $_POST['localiza-conta-id'];
if ($contaID==""){
	$contaID = $_GET['localiza-conta-id'];
}
$menuSuperior .="	<div class='menu-interno-superior-selecionado menu-interno-modulo' id='menu-superior-1' title='Visualizar Dados Gerais' attr-div='.conjunto1' attr-pos='1'>
						Dados Gerais
					</div>";
if (($contaID!="") && ($modulosAtivos['nfe'])){
	$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-5' title='Visualizar Produtos' attr-div='.conjunto5' attr-pos='5'>
						Nota Fiscal
					  </div>";
}
if ($modulosAtivos['produtos']){
	$menuSuperior .= "<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-2' title='Visualizar Produtos' attr-div='.conjunto2' attr-pos='2'>
						Produtos
					  </div>";
}
if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
	$menuSuperior .="	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Membros' attr-div='.conjunto6' attr-pos='6'>
							Membros
						</div>";

	$menuSuperior .="	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-3' title='Visualizar Dados Relat&oacute;rio (Tecla de atalho: CTRL + Espaço)' attr-div='.conjunto3' attr-pos='3'>
							Relat&oacute;rio Resumo
						</div>";
}
if ($contaID!=""){
	$menuSuperior .="<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-4' title='Visualizar Documentos Cadastrados' attr-div='#div-documentos' attr-pos='4'>
						Documentos
					 </div>";
}
?>