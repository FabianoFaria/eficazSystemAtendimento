<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	$rs = mpress_query("select m.Nome, mp1.Titulo Titulo_Pai, mp.Titulo
						from modulos_paginas mp
						inner join modulos m on m.Modulo_ID = mp.Modulo_ID
						left join modulos_paginas mp1 on mp1.Modulo_Pagina_ID = mp.Pagina_Pai_ID
						where mp.Modulo_Pagina_ID = ".$_GET['pagina']);
	if($row = mpress_fetch_array($rs)){
		$modulo		 = $row['Nome'];
		$titulo		 = $row['Titulo_Pai'];
		$tituloFilho = $row['Titulo'];
		if($titulo != "") $titulo = "- $titulo";
		$titulo = "$modulo $titulo - $tituloFilho";
	}

	$rs = mpress_query("select Descricao from help where modulo_pagina_id = ".$_GET['pagina']);
	if($row=mpress_fetch_array($rs))
		$descricaoHelp = $row['Descricao'];
?>


<div id='conteudo-titulo'><?php echo $titulo?></div>
<div id='conteudo-descricao'><?php echo $descricaoHelp?>