<?php
	include("../config.php");
	include("functions.gerais.php");
	$login = $_GET['login'];
	$resultado = mpress_query("select Cadastro_ID, Nome, Email, Senha from cadastros_dados where Email = '$login' and Situacao_ID = 1");
	if($row = mpress_fetch_array($resultado)){
		$mensagem = "Solicitação de senha:<br>
					 Nome:  ".$row['Nome']."<br>
					 Login: ".$row['Email']."<br>
					 Senha: ".$row['Senha'];
		enviaEmail($row['Email'],$row['Nome'], "Solicitação de senha", $mensagem, "A senha foi enviada para o e-mail solicitado!");
	}
	else{
		echo "<b Style='color:red;'>E-mail n&atilde;o localizado!</b>";
	}
?>