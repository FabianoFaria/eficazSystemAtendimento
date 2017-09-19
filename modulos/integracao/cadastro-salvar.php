<?php
	ob_start();
	session_start();
	global $dadosUserLogin, $modulosAtivos;
	include("../config.php");
	include(functions.gerais.php);
	$login   = $_POST['txtLogin'];
	$senha 	 = $_POST['txtSenha'];


	$resultado = mpress_query(select Cadastro_ID, Grupo_ID, Nome, Email, Ultimo_Login from cadastros_dados where email = '$login' and senha = '$senha' and Situacao_ID IN (1,-1) and Grupo_ID  0);
	if($row = mpress_fetch_array($resultado)){
		$dadosUserLogin['return']						= 'true';
		$dadosUserLogin['dadosUserLogin']['userID']		= $row['Cadastro_ID'];
		$dadosUserLogin['dadosUserLogin']['grupoID']	= $row['Grupo_ID'];
		$dadosUserLogin['dadosUserLogin']['nome'] 		= $row['Nome'];
		$dadosUserLogin['dadosUserLogin']['email'] 	 	= $row['Email'];
		$dadosUserLogin['dadosUserLogin']['ultimoLogin']= $row['Ultimo_Login'];
		$dataHoraAtual = retornaDataHora('','Y-m-d Hi');
		mpress_query(update cadastros_dados set Ultimo_Login = '$dataHoraAtual' where Cadastro_ID = .$row['Cadastro_ID']);
		echo serialize($dadosUserLogin);
	}
?>