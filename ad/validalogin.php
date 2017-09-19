<?php
	ob_start();
	session_start();
	global $dadosUserLogin, $modulosAtivos;
	include("../config.php");
	include("functions.gerais.php");
	$login   = $_POST['login'];
	$senha 	 = $_POST['password'];
	$lembrar = $_POST['lembrar-credenciais'];
	setcookie("lembrarsenha_novo", "", time()+3600000,"/",$_SERVER['HTTP_HOST']);
	setcookie("revalida", "pswk".strrev($login)."apthlo".strrev($senha)."gyxpchecked", time()+3600000,"/",$_SERVER['HTTP_HOST']);

	if($_POST['get-hash'] != ""){
		$redireciona 	= redirecionaUrl($_POST);
		$caminhoSistema = $redireciona['url'];
		$campoForm 		= $redireciona['campo'];
	};

	include('ldap.php');

	if($_SESSION['dados-login']['name'] != ""){
		$loginLDAP = mpress_query("select email, senha from cadastros_dados where codigo ='".$_SESSION['dados-login']['name']."'");
		$dadosLDAP = mpress_fetch_array($loginLDAP);
		$login = $dadosLDAP['email'];
		$senha = $dadosLDAP['senha'];
	}else{
		$login = '#$%';
		$senha = '#$%';
	}
	$resultado = mpress_query("select Cadastro_ID, Grupo_ID, Nome, Email, Ultimo_Login from cadastros_dados where email = '$login' and senha = '$senha'");
	echo "	<form action='$caminhoSistema/' method='post' name='retorno'>
				$campoForm";
	if($row = mpress_fetch_array($resultado)){
		$dadosUserLogin['userID']		= $row['Cadastro_ID'];
		$dadosUserLogin['grupoID']		= $row['Grupo_ID'];
		$dadosUserLogin['nome'] 		= $row['Nome'];
		$dadosUserLogin['email'] 	 	= $row['Email'];
		$dadosUserLogin['ultimoLogin'] 	= $row['Ultimo_Login'];
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$_SESSION['dadosUserLogin'] = $dadosUserLogin;

		mpress_query("update cadastros_dados set Ultimo_Login = '$dataHoraAtual' where Cadastro_ID = ".$row['Cadastro_ID']);
		if($lembrar == "on"){
			setcookie("lembrarsenha_novo", "pswk".strrev($login)."apthlo".strrev($senha)."gyxpchecked", time()+3600000,"/",$_SERVER['HTTP_HOST']);
		}
	}
	else{
		echo "	<input type='hidden' id='erro-login' name='erro-login' value='Login e/ou senha nao conferem.'/>";
	}
	echo "	</form>
			<script>
				document.retorno.submit();
			</script>";
?>