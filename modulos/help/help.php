<?php
	session_start();
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	$dadosPagina = unserialize($_GET['pagina']);
	$modulo		 = $dadosPagina['Nome'];
	$titulo		 = $dadosPagina['Titulo'];
	$tituloFilho = $dadosPagina['Titulo_Filho'];
	if($dadosPagina['Modulo_Pagina_Filho_ID'] != "") $paginaConsulta = $dadosPagina['Modulo_Pagina_Filho_ID']; else $paginaConsulta = $dadosPagina['Modulo_Pagina_ID'];
	$rs = mpress_query("select Modulo_ID, Nome from modulos m where Situacao_ID = 1 and slug <> 'help' order by nome");
	while($row = mpress_fetch_array($rs)){
		$menu .= "	<div class='menu-principal'>".$row['Nome']."</div>";
		$rsMenu = mpress_query("select titulo, Modulo_Pagina_ID from modulos_paginas mp  where Situacao_ID = 1 and modulo_id = ".$row['Modulo_ID']." order by titulo");
		while($rowMenu = mpress_fetch_array($rsMenu)){
			if($titulo==$rowMenu['titulo']) $classeMenu = "secundario-selecionado"; else $classeMenu = "";
			$menu .= "	<div class='menu-secundario $classeMenu' attr-id='".$rowMenu['Modulo_Pagina_ID']."'>&nbsp;&nbsp;".$rowMenu['titulo']."</div>";
			$rsSubMenu = mpress_query("select titulo, Modulo_Pagina_ID from modulos_paginas mp  where Situacao_ID = 1 and Pagina_Pai_ID = ".$rowMenu['Modulo_Pagina_ID']." order by titulo");
			while($rowSubMenu = mpress_fetch_array($rsSubMenu)){
				if($tituloFilho==$rowSubMenu['titulo']) $classeMenu = "secundario-selecionado"; else $classeMenu = "";
				$menu .= "	<div class='menu-secundario-sub $classeMenu' attr-id='".$rowSubMenu['Modulo_Pagina_ID']."'>&nbsp;&nbsp;&nbsp;&nbsp;".$rowSubMenu['titulo']."</div>";
			}
		}
	}
	if($tituloFilho != "") $titulo .= " - $tituloFilho";
	$titulo = $modulo." - ".$titulo;
	if($modulo=="") $titulo = "Módulo de ajuda do Sistema MPress";
	$rs = mpress_query("select count(distinct titulo) Total from help where modulo_pagina_id = $paginaConsulta");
	if($rowI = mpress_fetch_array($rs)){
		$porcentoMenu = (99-$rowI['Total'])/$rowI['Total']."%";
		if($rowI['Total']>=2){
			$rs = mpress_query("select distinct Titulo from help where modulo_pagina_id = $paginaConsulta order by titulo");
			while($row = mpress_fetch_array($rs)){
				$i++;
				$tituloOriginal = $row['Titulo'];
				if($i==1){$complementoClasse="menu-inicio";$tituloInicio = $row['Titulo'];}else if ($i==$rowI['Total']) $complementoClasse = "menu-final"; else $complementoClasse = "menu-centro";
				if($row['Titulo']==""){$row['Titulo'] = "Inicio"; $classeSelecionada = "-selecionado";}else $classeSelecionada = "";
				$menuHelp .= "<div class='menu-titulo$classeSelecionada $complementoClasse menu-superior-help' attr-titulo='$tituloOriginal' Style='width:$porcentoMenu'>".$row['Titulo']."</div>";
			}
		}
	}
 	$rs = mpress_query("select Descricao from help where modulo_pagina_id = $paginaConsulta and Titulo = '$tituloInicio'");
	if($row = mpress_fetch_array($rs))
		$descricaoHelp = $row['Descricao'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<?php get_header();?>
			<link rel='stylesheet' type='text/css' href='<?php echo $caminhoSistema?>/modulos/help/help.css' media='screen' />
			<script type='text/javascript' src='<?php echo $caminhoSistema?>/modulos/help/help.js'></script>
		</head>
		<body>
			<div id="container-help">
				<div id='menu-help'>
					<?php echo $menu;?>
				</div>
				<div id='conteudo-help'>
					<div id='conteudo-titulo'><?php echo $titulo?></div>
					<div id='conteudo-menu'><?php echo $menuHelp?></div>
					<div id='conteudo-descricao'><?php echo $descricaoHelp?>
				</div>

			</div>
		</body>
	</html>