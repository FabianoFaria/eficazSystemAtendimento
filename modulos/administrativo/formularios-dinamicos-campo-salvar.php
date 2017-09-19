<?php
include("functions.php");
global $dadosUserLogin;
$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

$formularioID = $_POST['formulario-id'];

if ($_GET['acao']=='d'){
	$sql = "update formularios_formulario_campo set Situacao_ID = 3 where Formulario_Campo_ID = '".$_GET['formulario-campo-id']."'";
	mpress_query($sql);

	/* ROTINA PARA REORGANIZAR AS POSICOES*/
	$sql = "select Formulario_Campo_ID from formularios_formulario_campo
				where Situacao_ID = 1 and Formulario_ID = '$formularioID'
				and Campo_ID <> 0
			order by Posicao";
	$i = 1;
	$resultado = mpress_query($sql);
	while($rs = mpress_fetch_array($resultado)){
		$arrayFomularioCampoID[$i++] = $rs['Formulario_Campo_ID'];
	}

	foreach($arrayFomularioCampoID as $chave => $fomularioCampoID){
		$sql = "update formularios_formulario_campo set Posicao = '$chave' where Formulario_Campo_ID = '$fomularioCampoID'";
		mpress_query($sql);
	}

}
else{
	$campoID = $_POST['campo-formulario-dinamico'];
	$largura = $_POST['percentual-linha'];
	$posicao = $_POST['posicao-campo'];
	$obrigatorio = $_POST['preenchimento-obrigatorio'];
	$percentualLinha = $_POST['percentual-linha'];
	$altura = $_POST['altura-linha'];

	$formularioCampoID = $_GET['formulario-campo-id'];

	if ($formularioCampoID==''){
		$sql = "UPDATE formularios_formulario_campo set Posicao = (Posicao + 1) where Formulario_ID = '$formularioID' and Posicao >= '$posicao'";
		mpress_query($sql);

		$sql = "INSERT INTO formularios_formulario_campo
					(Formulario_ID, Campo_ID, Posicao, Largura, Altura, Obrigatorio, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
				VALUES
					('$formularioID', '$campoID', '$posicao', '$largura', '$altura', '$obrigatorio', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
		mpress_query($sql);
	}
	else{
		$i = 1;

		$sql = "select Posicao from formularios_formulario_campo WHERE Formulario_Campo_ID = '$formularioCampoID'";
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$posicaoAtual = $rs['Posicao'];
		}

		if ($posicaoAtual>$posicao){
			$sql = "UPDATE formularios_formulario_campo set Posicao = (Posicao + 1) where Formulario_ID = '$formularioID' and Posicao > '".($posicao-1)."'";
			mpress_query($sql);
		}
		if ($posicaoAtual<$posicao){
			$sql = "UPDATE formularios_formulario_campo set Posicao = (Posicao - 1) where Formulario_ID = '$formularioID' and Posicao < '".($posicao+1)."'";
			mpress_query($sql);
		}

		$sql = "UPDATE formularios_formulario_campo
					set Campo_ID = '$campoID',
					Posicao = '$posicao',
					Largura = '$largura',
					Altura = '$altura',
					Obrigatorio = '$obrigatorio'
				WHERE Formulario_Campo_ID = '$formularioCampoID' ";
		mpress_query($sql);


		/* ROTINA PARA REORGANIZAR AS POSICOES*/

		$sql = "select Formulario_Campo_ID from formularios_formulario_campo where Situacao_ID = 1 and Formulario_ID = '$formularioID' and Campo_ID <> 0 order by Posicao";
		$i = 1;
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$arrayFomularioCampoID[$i++] = $rs['Formulario_Campo_ID'];
		}

		foreach($arrayFomularioCampoID as $chave => $fomularioCampoID){
			$sql = "update formularios_formulario_campo set Posicao = '$chave' where Formulario_Campo_ID = '$fomularioCampoID'";
			mpress_query($sql);
		}



	}
}
?>