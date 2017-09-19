<?php
	session_start();
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("config.php");
	include("includes/functions.gerais.php");
	$webservice = mysql_connect("179.188.0.226","webservice","mwn@1209")or die("Nao foi possivel conectar o BD.");
	mysql_select_db("webservice") or die("Nao foi possivel selecionar o BD.");
	$dadosPagina = unserialize($_GET['pagina']);
	$modulo		 = $dadosPagina['Nome'];
	$titulo		 = $dadosPagina['Titulo'];
	$tituloFilho = $dadosPagina['Titulo_Filho'];
	if($dadosPagina['Slug_Pagina_Filho'] != "") $paginaConsulta = $dadosPagina['Slug_Pagina_Filho']; else $paginaConsulta = $dadosPagina['Slug_Pagina'];
	$rs = mpress_query("select Modulo_ID, Nome from modulos m where Situacao_ID = 1 and slug <> 'help' order by nome");
	while($row = mpress_fetch_array($rs)){
		$menu .= "	<div class='menu-principal'>".$row['Nome']."</div>";
		$rsMenu = mpress_query("select titulo, Modulo_Pagina_ID, Slug from modulos_paginas mp  where Situacao_ID = 1 and modulo_id = ".$row['Modulo_ID']." order by titulo");
		while($rowMenu = mpress_fetch_array($rsMenu)){
			if($titulo==$rowMenu['titulo']) $classeMenu = "secundario-selecionado"; else $classeMenu = "";
			$menu .= "	<div class='menu-secundario $classeMenu' attr-id='".$rowMenu['Slug']."' attr-titulo='".$row['Nome']. " - ".$rowMenu['titulo']."'>&nbsp;&nbsp;".$rowMenu['titulo']."</div>";
			$rsSubMenu = mpress_query("select titulo, Modulo_Pagina_ID,Slug from modulos_paginas mp  where Situacao_ID = 1 and Pagina_Pai_ID = ".$rowMenu['Modulo_Pagina_ID']." order by titulo");
			while($rowSubMenu = mpress_fetch_array($rsSubMenu)){
				if($tituloFilho==$rowSubMenu['titulo']) $classeMenu = "secundario-selecionado"; else $classeMenu = "";
				$menu .= "	<div class='menu-secundario-sub $classeMenu' attr-id='".$rowSubMenu['Slug']."' attr-titulo='".$row['Nome']. " - ".$rowMenu['titulo']." - ".$rowSubMenu['titulo']."'>&nbsp;&nbsp;&nbsp;&nbsp;".$rowSubMenu['titulo']."</div>";
			}
		}
	}
	if($tituloFilho != "") $titulo .= " - $tituloFilho";
	$titulo = $modulo." - ".$titulo;
	if($modulo=="") $titulo = "Módulo de ajuda do Sistema MPRess";
	$rs = mysql_query("select count(distinct titulo) Total from help where Slug_Pagina = '$paginaConsulta'");
	if($rowI = mysql_fetch_array($rs)){
		$porcentoMenu = (99-$rowI['Total'])/$rowI['Total']."%";
		if($rowI['Total']>=2){
			$rs = mysql_query("select distinct Titulo from help where Slug_Pagina = '$paginaConsulta' order by titulo");
			while($row = mysql_fetch_array($rs)){
				$i++;
				$tituloOriginal = $row['Titulo'];
				if($i==1){$complementoClasse="menu-inicio";$tituloInicio = $row['Titulo'];}else if ($i==$rowI['Total']) $complementoClasse = "menu-final"; else $complementoClasse = "menu-centro";
				if($row['Titulo']==""){$row['Titulo'] = "Inicio"; $classeSelecionada = "-selecionado";}else $classeSelecionada = "";
				$menuHelp .= "<div class='menu-titulo$classeSelecionada $complementoClasse menu-superior-help' attr-titulo='$tituloOriginal' Style='width:$porcentoMenu'>".$row['Titulo']."</div>";
			}
		}
	}
 	$rs = mysql_query("select Descricao from help where Slug_Pagina = '$paginaConsulta' and Titulo = ''", $webservice);
	if($row = mysql_fetch_array($rs))
		$descricaoHelp = $row['Descricao'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<?php get_header();?>
		</head>
		<body>
			<div id="container-help">
				<div id='menu-help'>
					<?php echo $menu;?>
				</div>
				<div id='conteudo-help'>
					<div id='conteudo-titulo'><?php echo $titulo?></div>
					<div id='conteudo-container'>
						<div id='conteudo-menu'><?php echo $menuHelp?></div>
						<div id='conteudo-descricao'><?php echo $descricaoHelp?></div>
					</div>
				</div>
				<input type='hidden' id='pagina-help-id' value='<?php echo $paginaConsulta;?>'>
			</div>
		</body>
	</html>