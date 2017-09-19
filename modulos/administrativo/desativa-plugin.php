<?php
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$plugin = $_GET['slug'];

	$conteudoArquivo = file_get_contents("../$plugin/setup.php");
	$posAtivacao 	 = strpos($conteudoArquivo,"	Ativado:");
	if($posAtivacao != ""){
		$desativa 	 	 = substr($conteudoArquivo,$posAtivacao,25);
		$conteudoArquivo = str_replace($desativa, '', $conteudoArquivo);

		$arquivo = fopen("../$plugin/setup.php", "w");
		fwrite($arquivo, $conteudoArquivo);
		fclose ($arquivo);
	}

	$dadosPlugin = fopen("../$plugin/setup.php", "r");
	while(!feof($dadosPlugin)){
		$linha = fgets($dadosPlugin);
		if(strpos($linha, ":") != ""){
			$nomeCampo = trim(substr($linha,0,strpos($linha, ":")));
			$descCampo = trim(substr($linha,strpos($linha, ":")+1, strlen($linha)));
			$detalhes[$plugin][$nomeCampo] = $descCampo;
		}
	 }
	fclose($dadosPlugin);
	mpress_query("update modulos set Situacao_ID = 2 where Slug = '".$detalhes[$plugin][Slug]."'");
?>