<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	session_start();
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	global $caminhoFisico, $caminhoSistema, $dadosUserLogin;

	$dados = array_values(array_filter(explode("/",str_replace($caminhoSistema,'',$_SERVER['HTTP_REFERER']))));
	$moduloAtual = $dados[0];
	$paginaAtual = $dados[count($dados)-1];

	$resultado = mpress_query("select m.Nome, mp.Titulo, m.Slug Slug_Modulo, mp.Slug Slug_Pagina, mpf.Titulo Titulo_Filho, mpf.Slug Slug_Pagina_Filho,mpf.Modulo_Pagina_ID Modulo_Pagina_Filho_ID, mp.Modulo_Pagina_ID, m.Modulo_ID, coalesce(mpf.Tipo_Grupo_ID, mp.Tipo_Grupo_ID) as Tipo_Grupo_ID, mp.Campos_Obrigatorios Campos_Obrigatorios_Pai, mpf.Campos_Obrigatorios Campos_Obrigatorios_Filho
							   from
							   modulos m
							   inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
							   left join modulos_paginas mpf on mpf.Modulo_ID = m.Modulo_ID and mpf.Pagina_Pai_ID  = mp.Modulo_pagina_ID
						   	   where m.slug='".$dados[0]."' and mp.slug='". $dados[count($dados)-1]."'");
	$dadospagina = mpress_fetch_array($resultado);
	bloqueiaRegistro($dadospagina);

	if($dadospagina['Slug_Modulo']=='cadastros'){
		if($paginaAtual != 'cadastro-dados'){
			mpress_query("delete from modulos_ajax where Usuario_ID = ".$dadosUserLogin['userID']." and modulo_id = ".$dadospagina[Modulo_ID]);
		}
	};

	if($dadospagina['Slug_Modulo']=='chamados'){
		if($paginaAtual != 'chamados-cadastro-chamado'){
			mpress_query("delete from modulos_ajax where Usuario_ID = ".$dadosUserLogin['userID']." and modulo_id = ".$dadospagina[Modulo_ID]);
		}
	};
?>
