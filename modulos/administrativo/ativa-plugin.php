<?php
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$plugin = $_GET['slug'];
	$moduloID = $_GET['modulo-id'];

	if ($moduloID!=""){
		//ATUALIZAÇÃO EXCLUINDO PAGINAS ATUAIS
		mpress_query("delete from modulos_paginas where Modulo_ID = $moduloID");
		mpress_query("delete from modulos where Modulo_ID = $moduloID");
	}

	$conteudoArquivo = file_get_contents("../$plugin/setup.php");
	$conteudoArquivo = str_replace("*/","	Ativado:".date('d/m/Y H:i')."\n*/",$conteudoArquivo);
	$arquivo = fopen("../$plugin/setup.php", "w");
	fwrite($arquivo, $conteudoArquivo);
	fclose ($arquivo);
	$dadosPlugin = fopen("../$plugin/setup.php", "r");
	while(!feof($dadosPlugin)){
		$linha = fgets($dadosPlugin);
		if(strpos($linha, ":") != ""){
			$nomeCampo = trim(substr($linha,0,strpos($linha, ":")));
			$descCampo = trim(substr($linha,strpos($linha, ":")+1, strlen($linha)));
			$detalhes[$arquivo][$nomeCampo] = $descCampo;
			$detalhes[slug] = $detalhes[$arquivo][Slug];
			if($nomeCampo == "query"){
				$j++;
				$detalhes[query][$j] = $detalhes[$arquivo][query];
			}
			if($nomeCampo == "queryS")
				$detalhes[queryS][$j][] = $detalhes[$arquivo][queryS];
		}
	 }
	fclose($dadosPlugin);

	$resultado = mpress_query("select max(Posicao) Posicao from modulos m where Modulo_ID > 1");
	if($row = mpress_fetch_array($resultado))
		$posicaoModulo = $row[Posicao]+1;

	$resultado = mpress_query("select Modulo_ID from  modulos m where Slug = '".$detalhes[$arquivo][Slug]."'");
	if($row = mpress_fetch_array($resultado)){
		mpress_query("update modulos set Situacao_ID = 1, Data_Ativacao = now() where Slug = '".$detalhes[slug]."'");
		$idModulo = $row[Modulo_ID];
	}else{
		mpress_query("insert into modulos(Nome, Descricao, Slug, Posicao)values('".$detalhes[$arquivo][Titulo]."','".$detalhes[$arquivo][Descricao]."','".$detalhes[$arquivo][Slug]."','$posicaoModulo')");
		$idModulo = mpress_identity();
		for($i=1;$i<=count($detalhes[query]);$i++){
			mpress_query(str_replace('[modulo-id]',$idModulo,$detalhes[query][$i]));
			$idMenu = mpress_identity();
			for($s=0;$s<count($detalhes[queryS][$i]);$s++){
				mpress_query(str_replace('[sub-modulo-id]',$idMenu,str_replace('[modulo-id]',$idModulo,$detalhes[queryS][$i][$s])));
			}
		}
	}
?>