<?php
	$caminhoSistema = str_replace("?passo=3","","http://".str_replace("/manutencao.php","",$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]));
	$caminhoInclude = str_replace("?passo=3","",str_replace("/manutencao.php","",$_SERVER[REQUEST_URI]));
?>

<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Sistema de gest&atilde;o MPress</title>
			<link rel="stylesheet" href="<?php echo $caminhoSistema?>/css/install.css?ver=3.5.2" type="text/css" />
			<link rel="stylesheet" href="<?php echo $caminhoSistema?>/css/buttons.css?ver=3.5.2" type="text/css" />
			<script type='text/javascript' src='<?php echo $caminhoSistema?>/javascript/jquery-1.8.1.js'></script>
			<script type='text/javascript' src='<?php echo $caminhoSistema?>/javascript/configuracao-inicial.js'></script>
		</head>
		<body>
			<div>
				<h1 id="logo">
					<img src='<?php echo $caminhoSistema?>/images/geral/logo.png' Style='float:left;'>
					<br>Sistema de Gest&atilde;o MPress
				<h1>
			</div>
			<div id='passo-1'>
				<p Style='margin-top:25px; text-align:center;'>SISTEMA EM MANUTEN&Ccedil;&Atilde;O, AGUARDE</p>
				<p Style='text-align:center;'><img src='<?php echo $caminhoSistema?>/images/geral/ajax-loader-barra.gif'/></p>
			</div>
		</body>
	</html>
