<?php
	error_reporting(0);
	session_start();
	global $conn, $caminhoSistema, $caminhoFisico, $tituloSistema, $descricaoSistema, $tipoBaseDados, $dadosUserLogin, $modulosAtivos;

	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("config.php");
	include("includes/functions.gerais.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<?php get_header();?>
		</head>
		<body>
		<?php echo "<!-- aqui hs verao ".date("I")."-->"; ?>
			<form method="post" id="frmDefault" name="frmDefault" autocomplete="off">
				<?php get_url();?>
				<div id="container-geral">
					<div id='header'>
						<?php get_topo();?>
					</div>
					<div id='menu-container'>
						<?php get_menu();?>
					</div>
					<div id="conteudo">
						<?php get_content();?>
					</div>
					<div id="rodape-container">
						<?php get_fotter();?>
					</div>
				</div>
			</form>
		</body>
	</html>
