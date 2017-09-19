<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	$slug 	= $_GET['slug'];
	$valor 	= $_POST['titulo'];
	$tipoID	= $_POST['tipo-id'];
	$tipoGrupo = $_POST['tipo-grupo'];

	if ($tipoGrupo=="21"){
		/*
		$dados['tempo'] = $_POST['tempo'];
		$dados['cor'] = $_POST['cor'];
		$dados['sabado'] = $_POST['sabado'];
		$dados['domingo'] = $_POST['domingo'];
		$tipoAuxiliar = serialize($dados);
		*/
		$tipoAuxiliar = serialize($_POST);
	}
	if ($tipoGrupo=="26"){
		$tipoAuxiliar = $_POST['responsavel-id'];
	}
	if ($tipoGrupo=="28"){
		$dados['saida'] = $_POST['saida'];
		$dados['entrada'] = $_POST['entrada'];
		if (($dados['saida']=="") && ($dados['entrada']=="")){
			$dados['entrada'] = "1";
			$dados['saida'] = "1";
		}
		$tipoAuxiliar = serialize($dados);
	}
	if ($tipoGrupo=="44"){
		$dados['codigo-cfop'] = $_POST['codigo-cfop'];
		$dados['tipo-cfop'] = $_POST['tipo-cfop'];
		$tipoAuxiliar = serialize($dados);
	}
	if ($tipoGrupo=="57"){
		$tipoAuxiliar = $_POST['categoria'];
	}

	if ($tipoGrupo=="65"){
		$dados['tempo'] = $_POST['tempo'];
		$dados['descricao'] = $_POST['descricao'];
		$dados['cronometro-pausa'] = $_POST['cronometro-pausa'];
		$dados['uso-tribuna'] = $_POST['uso-tribuna'];
		$dados['fluxo-tempo'] = $_POST['fluxo-tempo'];
		$dados['a-parte'] = $_POST['a-parte'];
		$tipoAuxiliar = serialize($dados);
	}

	if (($tipoGrupo=="72") || ($tipoGrupo=="74")){
		$tipoAuxiliar = serialize($_POST['dados-tipo']);
	}

	if (($tipoGrupo=='18') || ($tipoGrupo=='51') || ($tipoGrupo=='53') || ($tipoGrupo=='60')){
		$tipoAuxiliar = serialize($_POST);
		$tipoAuxiliarExtra = $_POST['posicao'];
	}

	if ($tipoID==""){
		$sql = "insert into tipo (Descr_Tipo, Tipo_Grupo_ID, Tipo_Auxiliar, Tipo_Auxiliar_Extra, Situacao_ID) values ('$valor','$tipoGrupo', '$tipoAuxiliar', '$tipoAuxiliarExtra', 1)";
		mpress_query($sql);
		$tipoID = mpress_identity();
	}
	else{
		$sql = "update tipo set Descr_Tipo = '$valor', Tipo_Auxiliar = '$tipoAuxiliar', Tipo_Auxiliar_Extra = '$tipoAuxiliarExtra' where Tipo_ID = '$tipoID'";
		mpress_query($sql);
	}
	if ($tipoGrupo=="47"){
		// INSTITUICAO:
		$instituicaoID = $_POST['instituicao'];
		mpress_query("delete from modulos_vinculos where Nome_Tabela = 'tipo' and Valor_Vinculo = 46 and Tipo_Secundario_ID = '$tipoID'") ;
		mpress_query("insert into modulos_vinculos (Nome_Tabela, Tipo_Principal_ID, Tipo_Secundario_ID, Valor_Vinculo, Situacao_ID)
								values ('tipo', '$instituicaoID', '$tipoID', '46', 1)");

		// CURSOS:
		mpress_query("delete from modulos_vinculos where Nome_Tabela = 'tipo' and Valor_Vinculo = 47 and Tipo_Principal_ID = '$tipoID'");
		for($i = 0; $i < count($_POST['cursos']); $i++){
			$cursoID = $_POST['cursos'][$i];
			mpress_query("insert into modulos_vinculos (Nome_Tabela, Tipo_Principal_ID, Tipo_Secundario_ID, Valor_Vinculo, Situacao_ID)
										values ('tipo', '$tipoID', '$cursoID', '47', 1)");
		}
	}
	header("location:$slug");
?>