<?php
	$caminhoSistema = str_replace("?passo=3","","http://".str_replace("/configuracoes-iniciais.php","",$_SERVER[HTTP_HOST].$_SERVER[REQUEST_URI]));
	$caminhoInclude = str_replace("?passo=3","",str_replace("/configuracoes-iniciais.php","",$_SERVER[REQUEST_URI]));
?>
<!DOCTYPE html>
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
			<title>Sistema de gest&atilde;o MWN &rsaquo; Arquivo de Configuração da Instalação</title>
			<link rel="stylesheet" href="<?php echo $caminhoSistema?>/css/install.css?ver=3.5.2" type="text/css" />
			<script type='text/javascript' src='<?php echo $caminhoSistema?>/javascript/jquery-1.8.1.js'></script>
			<script type='text/javascript' src='<?php echo $caminhoSistema?>/javascript/configuracao-inicial.js'></script>
		</head>
		<body>
			<div>
				<h1 id="logo">
					<img src='<?php echo $caminhoSistema?>/images/geral/logo_mwn.jpg' Style='float:left;'>
					<br>Sistema de Gest&atilde;o MWN
				<h1>
			</div>

			<?php if($_GET['passo']==""){?>
				<div id='passo-1'>
					<p Style='margin-top:25px'>Bem-vindo ao Sistema de gest&atilde;o MWN. Antes de come&ccedil;ar, precisamos de algumas informa&ccedil;&otilde;es sobre o banco de dados e hospedagem.</p>
					<ol>
						<li>Nome do Banco de Dados</li>
						<li>Nome de Usu&aacuterio do Banco de Dados</li>
						<li>Senha do Banco de Dados</li>
						<li>Sevidor do Banco de Dados</li>
						<li>Nome da Empresa</li>
						<li>Descri&ccedil;&atilde;o da Empresa</li>
					</ol>

					<p>Esses itens foram fornecidos pelo seu servidor de hospedagem ou pelo seu gestor de TI. Se n&atilde;o tiver essa informa&ccedil;&atilde;o, ent&atilde;o voc&ecirc; precisa entrar em contato com eles antes de continuar.</p>

					<p Style='text-align:right;margin-top:25px;'><input type='button' value='Iniciar configura&ccedil;&atilde;o' style='font-size:15px;' id='configuracao-inicial-1'></p>
				</div>

				<div id='passo-2'>
					<form id='form-inicio' method="post" action="configuracoes-iniciais.php?passo=3">
						<p Style='margin-top:25px'>Abaixo voc&ecirc; deve digitar suas informa&ccedil;&otilde;es de conex&atilde;o com o banco de dados e hospedagem. Se n&atilde;o sabe quais s&atilde;o, contate sua hospedagem ou seu gestor de TI.</p>
						<table class="form-table" width='100%' border='0'>
							<tr>
								<th scope="row" Style='width:210px'><label for="dbname">Tipo da conex&atilde;o de Dados:</label></th>
								<td>
									<select name="dbtype" id="dbtype" Style='width:100%;height:26px;'>
										<option value=''></option>
										<option value='mysql_connect'>MySql ( function mysql_connect )</option>
										<option value='mssql_connect'>SqlServer ( function mssql_connect )</option>
										<option value='sqlsrv_connect'>SqlServer ( function sqlsrv_connect )</option>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row" Style='width:210px'><label for="dbname">Nome do Banco de Dados:</label></th>
								<td><input name="dbname" id="dbname" type="text" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="uname">Nome de usu&aacute;rio:</label></th>
								<td><input name="uname" id="uname" type="text" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="pwd">Senha:</label></th>
								<td><input name="pwd" id="pwd" type="text" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="dbhost">Servidor do banco de dados:</label></th>
								<td><input name="dbhost" id="dbhost" type="text" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="prefix">Titulo do Sistema:</label></th>
								<td><input name="prefix" id="prefix" type="text" /></td>
							</tr>
							<tr>
								<th scope="row"><label for="prefix">Descri&ccedil;&atilde;o do Sistema</label></th>
								<td><textarea name="descricao" id="descricao" Style='width:98.5%;height:50px;'/></textarea></td>
							</tr>
						</table>
						<p Style='text-align:right;margin-top:5px;'><input type='button' value='Finalizar configura&ccedil;&atilde;o' style='font-size:15px;' id='configuracao-inicial-2'></p>
					</form>

					<div id='div-erro'></div>
				</div>
			<?php
				}else if($_GET['passo']==3){
					$conteudoArquivo = file_get_contents("config-modelo.php");
					$config['type'] 	= $_POST['dbtype'];
					$config['banco'] 	= $_POST['dbname'];
					$config['user']	 	= $_POST['uname'];
					$config['pass']  	= $_POST['pwd'];
					$config['host']	 	= $_POST['dbhost'];
					$config['title']	= $_POST['prefix'];
					$config['descr']	= $_POST['descricao'];
					$caminhoFisico 	= str_replace("/configuracoes-iniciais.php","",$_SERVER["SCRIPT_FILENAME"]);
					$conteudoArquivo = str_replace("[caminho-include]", "$caminhoInclude",$conteudoArquivo);
					$conteudoArquivo = str_replace("[caminho-sistema]", "$caminhoSistema",$conteudoArquivo);
					$conteudoArquivo = str_replace("[caminho-fisico]", "$caminhoFisico", $conteudoArquivo);
					$conteudoArquivo = str_replace("[titulo-sistema]", $config['title'], $conteudoArquivo);
					$conteudoArquivo = str_replace("[descricao-sistema]", $config['descr'] ,$conteudoArquivo);
					if($config['type'] =='mysql_connect'){
						$conteudoArquivo = str_replace('[connect]','$'.'conn = mysql_connect("'.$config['host'].'","'.$config['user'].'","'.$config['pass'].'")or die("Nao foi possivel conectar o BD.");',$conteudoArquivo);
						$conteudoArquivo = str_replace('[select_db]','mysql_select_db("'.$config['banco'].'") or die("Nao foi possivel selecionar o BD.");',$conteudoArquivo);
						$conteudoArquivo = str_replace('[query]','mysql_query',$conteudoArquivo);
						$conteudoArquivo = str_replace('[fetch_array]','mysql_fetch_array',$conteudoArquivo);
					}
					if($config['type'] =='mssql_connect'){
						$conteudoArquivo = str_replace('[connect]','$'.'conn = mssql_connect("'.$config['host'].'","'.$config['user'].'","'.$config['pass'].'")or die("Nao foi possivel conectar o BD.");',$conteudoArquivo);
						$conteudoArquivo = str_replace('[select_db]','mssql_select_db("'.$config['banco'].'","$conn") or die("Nao foi possivel selecionar o BD.");',$conteudoArquivo);
						$conteudoArquivo = str_replace('[query]','mssql_query',$conteudoArquivo);
						$conteudoArquivo = str_replace('[fetch_array]','mssql_fetch_array',$conteudoArquivo);
					}
					if($config['type'] =='sqlsrv_connect'){
						$conteudoArquivo = str_replace('[connect]','$connectionInfo = array("UID"=>"'.$config['user'].'","PWD"=>"'.$config['pass'].'","Database"=>"'.$config['banco'].'");',$conteudoArquivo);
						$conteudoArquivo = str_replace('[select_db]','$conn = sqlsrv_connect("'.$config['host'].'", $connectionInfo);',$conteudoArquivo);
						$conteudoArquivo = str_replace('[query]($query, $conn)','sqlsrv_query($conn,$query)',$conteudoArquivo);
						$conteudoArquivo = str_replace('[fetch_array]','sqlsrv_fetch_array',$conteudoArquivo);
					}
					$arquivo = fopen("config.php", "w");
					fwrite($arquivo, "<"."?php\n".$conteudoArquivo."\n?>");
					fclose ($arquivo);
					$arquivo = fopen(".htaccess", "w");
					fwrite($arquivo, "# BEGIN infoSistem\n<IfModule mod_rewrite.c>\nRewriteEngine On\nRewriteBase ".str_replace("?passo=3","",str_replace("/configuracoes-iniciais.php","",$_SERVER[REQUEST_URI]))."/\nRewriteRule ^index\.php$ - [L]\nRewriteCond %{REQUEST_FILENAME} !-f\nRewriteCond %{REQUEST_FILENAME} !-d\nRewriteRule . ".str_replace("?passo=3","",str_replace("/configuracoes-iniciais.php","",$_SERVER[REQUEST_URI]))."/index.php [L]\n</IfModule>\n# END infoSistem");
					fclose ($arquivo);
				?>
				<div id='passo-3'>
					<p Style='margin-top:25px'>Configura&ccedil;&atilde;o executada com sucesso.</p>
						<p Style='text-align:right;margin-top:5px;'><input type='button' value='Acessar o Sistema' style='font-size:15px;' id='configuracao-inicial-3'></p>
				</div>
			<?php }?>
		</body>
	</html>
