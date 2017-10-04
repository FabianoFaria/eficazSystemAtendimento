<?php
	error_reporting(0);
	session_start();
	global $conn, $caminhoSistema, $caminhoFisico, $tituloSistema, $descricaoSistema, $tipoBaseDados, $dadosUserLogin, $modulosAtivos;

	var_dump($conn);

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
			<style>
				.fancybox-close{display:none;}
			</style>
			<script>
				$(document).bind('keydown keyup', function(e) {
				    if(e.which === 13){
				    	$("#login-expira").click();
				    	return false;
				    }
				});
				$(document).ready(function(){
					setTimeout(function(){
						parent.$(".fancybox-close").remove();
					}, 500);
				});
			</script>
		</head>
		<body>
			<form method="post" id="frmDefault" name="frmDefault">
				<div id="container-geral">
					<?php
						ob_start();
						$lembrar 		= $_COOKIE["lembrarsenha_novo"];
						$dados[user] 	= strrev(substr($lembrar,4,strpos($lembrar,"apthlo")-4));
						$dados[pass] 	= strrev(substr(substr($lembrar,strpos($lembrar,"apthlo")+6),0,strlen(substr($lembrar,strpos($lembrar,"apthlo")+6))-11));
						$dados[lembrar] = substr($lembrar, -7);
					?>
					<link rel='stylesheet' type='text/css' href='<?php echo $caminhoSistema?>/css/login.css' />
					<div id='login-container-sessao' style="width:90%">
						<table width="538" height='109' cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px'>
							<tr>
								<td align='center'><img src='<?php echo $caminhoSistema;?>/images/login/m-press-logo.png'></td>
								<td width='2' bgcolor='#ebebeb'></td>
								<td align='center'><img src='<?php echo $caminhoSistema;?>/images/login/sistema-gestao-versao.png'></td>
							</tr>
						</table>
						<table width="538" height='280' cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px' id='tabela-login'>
							<tr>
								<td align='center' background='<?php echo $caminhoSistema;?>/images/login/bg-campos.png'>
									<table width='460' border='0' height='100%' cellspacing='0' cellpadding='0'>
										<tr height='20'>
											<td colspan='2'>&nbsp;</td>
										</tr>
										<tr>
											<td colspan='2' id='texto-email'>Usu&aacute;rio ou E-mail</td>
										</tr>
										<tr height='48'>
											<td colspan='2' class='login-campo-login' id='login-campo-login'>
												<input type='text' name='login' id='login' value='<?php echo $dados[user]?>'>
											</td>
										</tr>
										<tr height='20'>
											<td colspan='2'>&nbsp;</td>
										</tr>
										<tr height='20' align='left'>
											<td colspan='2' id='texto-senha'>Senha</td>
										</tr>
										<tr height='48'>
											<td colspan='2' class='login-campo-senha' id='login-campo-senha'>
												<input type='password' name='password' id='password' value='<?php echo $dados[pass]?>'>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td rowspan='3' width='90px' valign='bottom' align='left'>
												<input type='button' id='login-expira' value='Login'>
											</td>
										</tr>
										<tr>
											<td id='lembrar-credencial'>
												<input type='checkbox' name='lembrar-credenciais' id='lembrar-credenciais' <?php echo $dados[lembrar];?>/>&nbsp;Lembrar minhas credenciais
											</td>
										</tr>
										<tr>
											<td id='esqueci-senha'>
												<a href='#' id='esqueci-senha-ref'>
													Esqueci minha senha
												</a>
											</td>
										</tr>
										<tr>
											<td colspan='2' height='25'><div id='erro-login' Style='display:none'>Login e/ou senha não conferem.&nbsp;</div></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>


						<table width="538" height='180' cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px' id='tabela-login-senha' class='esconde'>
							<tr>
								<td align='center' background='<?php echo $caminhoSistema;?>/images/login/bg-campos.png'>
									<table width='460' border='0' height='100%' cellspacing='0' cellpadding='0'>
										<tr height='20'>
											<td colspan='2'>&nbsp;</td>
										</tr>
										<tr>
											<td colspan='2' id='texto-email'>Usu&aacute;rio ou E-mail</td>
										</tr>
										<tr height='48'>
											<td colspan='2' class='login-campo-login' id='login-campo-login-senha'>
												<input type='text' name='loginS' id='loginS'>
											</td>
										</tr>
										<tr>
											<td>&nbsp;</td>
											<td rowspan='2' width='90px' valign='bottom' align='left'>
												<input type='button' id='botao-recupera-senha' value='Enviar'>
											</td>
										</tr>
										<tr>
											<td colspan='2'><div id='recupera-senha'></div></td>
										</tr>
										<tr height='20'>
											<td colspan='2'>&nbsp;</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</body>
	</html>