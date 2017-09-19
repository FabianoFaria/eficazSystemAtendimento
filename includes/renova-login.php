<?php
	ob_start();
	session_start();
	global $dadosUserLogin, $modulosAtivos;
	include("../config.php");

	$lembrar 		= $_COOKIE["revalida"];
	$dados['user'] 	= strrev(substr($lembrar,4,strpos($lembrar,"apthlo")-4));
	$dados['pass'] 	= strrev(substr(substr($lembrar,strpos($lembrar,"apthlo")+6),0,strlen(substr($lembrar,strpos($lembrar,"apthlo")+6))-11));

	if(($dados['user'] != "")&&($dados['pass']!="")){
		$resultado = mpress_query("select Cadastro_ID, Grupo_ID, Nome, Email, Ultimo_Login from cadastros_dados where email = '".$dados['user']."' and senha = '".$dados['pass']."' and Situacao_ID IN (1,-1)");
		if($row = mpress_fetch_array($resultado)){
			$dadosUserLogin['userID']		= $row['Cadastro_ID'];
			$dadosUserLogin['grupoID']		= $row['Grupo_ID'];
			$dadosUserLogin['nome'] 		= $row['Nome'];
			$dadosUserLogin['email'] 	 	= $row['Email'];
			$dadosUserLogin['ultimoLogin'] 	= $row['Ultimo_Login'];
			$dadosUserLogin['renovacao']= date('Y-m-d H:i');

			$_SESSION['dadosUserLogin'] = $dadosUserLogin;
		}
	}
?>