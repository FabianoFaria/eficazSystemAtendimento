<?php
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../administrativo/functions.php");
	global $caminhoSistema, $caminhoFisico, $dadosUserLogin;
	$conteudo = $_POST['conteudo-geral-projecao'];

	$conteudo = " 	<html>
					<body>
					<style>
						div{
							font-family:arial;
							font-size:11px;
						}
						input[type='text']{
							font-family: arial;
							font-size: 13px;
							border-radius: 5px;
						}
					</style>
					<div>
					".$conteudo."
					</div>
					</body>
					</html>";

	$conteudo = str_replace("'","&#39;",$conteudo);
	echo $conteudo;


	$nomeArquivo = "Projecao_".date('Ymd_hms').".pdf";
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$id = $_POST['workflow-id'];
	$origem = 'orcamentos';
	$background = "CR";


	$sql = "insert into modulos_anexos(Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Observacao, Nome_Arquivo_Original, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$background', '$id','$origem','$nomeArquivo', '$conteudo','$nomeArquivo', $dataHoraAtual,'".$dadosUserLogin['userID']."')";
	mpress_query($sql);

	geraPDF($nomeArquivo, $conteudo, $background,'a4','landscape');
?>
