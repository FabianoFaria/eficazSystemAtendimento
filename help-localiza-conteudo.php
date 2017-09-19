<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("config.php");
	include("includes/functions.gerais.php");
	$webservice = mysql_connect("179.188.0.226","webservice","mwn@1209")or die("Nao foi possivel conectar o BD.");
	mysql_select_db("webservice") or die("Nao foi possivel selecionar o BD.");
	$rs = mysql_query("select count(distinct titulo) Total from help where Slug_Pagina = '".$_GET['pagina']."'");
	if($rowI = mysql_fetch_array($rs)){
		$porcentoMenu = (99-$rowI['Total'])/$rowI['Total']."%";
		if($rowI['Total']>=2){
			$rs = mysql_query("select distinct Titulo from help where Slug_Pagina = '".$_GET['pagina']."' order by titulo");
			while($row = mysql_fetch_array($rs)){
				$i++;
				$tituloOriginal = $row['Titulo'];
				if($i==1){$complementoClasse="menu-inicio";$tituloInicio = $row['Titulo'];}else if ($i==$rowI['Total']) $complementoClasse = "menu-final"; else $complementoClasse = "menu-centro";
				if($row['Titulo']==$_GET['titulo'])$classeSelecionada = "-selecionado";else $classeSelecionada = "";
				if($row['Titulo']=="")$row['Titulo'] = "Inicio";
				$menuHelp .= "<div class='menu-titulo$classeSelecionada $complementoClasse menu-superior-help' attr-titulo='$tituloOriginal' Style='width:$porcentoMenu'>".$row['Titulo']."</div>";
			}
		}
	}
	$rs = mysql_query("select Descricao from help where Slug_Pagina = '".$_GET['pagina']."' and Titulo = '".$_GET['titulo']."'", $webservice);
	if($row = mysql_fetch_array($rs))
		$descricaoHelp = $row['Descricao'];
?>
<div id='conteudo-menu'><?php echo $menuHelp?></div>
<div id='conteudo-descricao'><?php echo $descricaoHelp?></div>
