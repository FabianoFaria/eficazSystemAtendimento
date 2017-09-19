<?php
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$plugin = $_GET['slug'];

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
		}
	 }
	fclose($dadosPlugin);
	mpress_query("insert into modulos(Nome, Descricao, Slug)values('".$detalhes[$arquivo][Titulo]."','".$detalhes[$arquivo][Descricao]."','".$detalhes[$arquivo][Slug]."')");
	echo mpress_identity();

/*Localizar paginas dentro do diretirio e pegar o header, inserir as paginas localizadas e incluir as permissões par ao admin*/

	echo "Plugin ativado com sucesso!!"
?>