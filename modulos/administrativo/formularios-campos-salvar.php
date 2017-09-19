<?php
include("functions.php");
global $dadosUserLogin;
$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

$campoID = $_POST['campo-id'];
$nomeCampo = utf8_decode($_POST['nome-campo']);
$descricaoCampo = utf8_decode($_POST['descricao-campo']);
$tipoCampo = $_POST['tipo-campo-modelo'];
$percentualLinha = $_POST['percentual-linha'];

if ($campoID==''){
	$sql = "INSERT INTO formularios_campos
				(Nome, Descricao, Tipo_Campo, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
			VALUES
				('$nomeCampo', '$descricaoCampo', '$tipoCampo', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
	mpress_query($sql);
	$campoID = mpress_identity();

	if (($tipoCampo=='select')||($tipoCampo=='radio')||($tipoCampo=='checkbox')){
		$i = 0;
		foreach($_POST['multipla-opcao'] as $opcao){
			$i++;
			$sql = "INSERT INTO formularios_campos_opcoes
						(Campo_ID, Descricao, Posicao, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
					VALUES
						($campoID, '".utf8_decode($opcao)."', $i, 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			mpress_query($sql);
		}
	}
}
else{
	$sql = "UPDATE formularios_campos
				SET Nome = '$nomeCampo',
					Descricao = '$descricaoCampo',
					Tipo_Campo = '$tipoCampo'
			WHERE Campo_ID = '$campoID'";
	mpress_query($sql);

	if (($tipoCampo=='select')||($tipoCampo=='radio')||($tipoCampo=='checkbox')){
		$sql = "UPDATE formularios_campos_opcoes SET Situacao_ID = 2 where Campo_ID = '$campoID'";
		mpress_query($sql);
		$i = 0;
		foreach($_POST['multipla-opcao'] as $opcao){
			$i++;
			$campoOpcaoID = $_POST['campo-opcao'][$i];
			if ($campoOpcaoID==''){
				$sql = "INSERT INTO formularios_campos_opcoes
							(Campo_ID, Descricao, Posicao, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
						VALUES
							($campoID, '".utf8_decode($opcao)."', $i, 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
				mpress_query($sql);
			}
			else{
				$sql = "UPDATE formularios_campos_opcoes SET Descricao = '".utf8_decode($opcao)."', Situacao_ID = 1
							WHERE Campo_Opcao_ID = '$campoOpcaoID'";
				mpress_query($sql);
			}
		}
	}
}
echo $campoID;
?>