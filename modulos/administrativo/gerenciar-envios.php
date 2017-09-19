<?php
	require_once("includes/functions.gerais.php");
	require_once("config.php");
	if($_POST['nome-envio'] != ""){
		$dadosEnvio[nome_envio] 	= $_POST['nome-envio'];
		$dadosEnvio[email_envio] 	= $_POST['email-envio'];
		$dadosEnvio[servidor_smtp] 	= $_POST['servidor-smtp'];
		$dadosEnvio[porta] 			= $_POST['porta-envio'];
		$dadosEnvio[usuario] 		= $_POST['usuario'];
		$dadosEnvio[senha] 			= $_POST['senha'];
		$dadosEnvio[certificado] 	= $_POST['tipo-certificado'];
		$dadosEnvio[sms_numero]		= $_POST['sms-numero'];
		$dadosEnvio[sms_nome] 		= $_POST['sms-nome'];
		$dadosEnvio[sms_usuario] 	= $_POST['sms-user'];
		$dadosEnvio[sms_senha] 		= $_POST['sms-pass'];
		$dadosEnvio[smtp]			= $_POST['tipo-autenticacao'];
		$dadosEnvio = serialize($dadosEnvio);
		mpress_query("update tipo set descr_tipo = '$dadosEnvio' where tipo_id = 7");
	}
	$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 7");
	if($row = mysql_fetch_array($resultado))
		$dadosEnvio = unserialize($row[descr_tipo]);
	$certificado[$dadosEnvio[certificado]] = "selected";
?>
	<div id='configuracoes-container'>
		<div class="titulo-container">
			<div class="titulo">
				<p>Configurações SMTP</p>
			</div>
			<div class='conteudo-interno'>
				<div class="titulo-secundario duas-colunas">
					<p>Nome Envio:</p>
					<p><input type='text' name='nome-envio' id='nome-envio' value='<?php echo $dadosEnvio[nome_envio]?>'></p>
				</div>
				<div class="titulo-secundario duas-colunas">
					E-mail envio:
					<p><input type='text' name='email-envio' id='email-envio'value='<?php echo $dadosEnvio[email_envio]?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas">
					Servidor SMTP:
					<p><input type='text' name='servidor-smtp' id='servidor-smtp'value='<?php echo $dadosEnvio[servidor_smtp]?>'></p>
				</div>
				<div class="titulo-secundario oito-colunas">
					Porta:
					<p><input type='text' name='porta-envio' id='porta-envio' value='<?php echo $dadosEnvio[porta]?>'></p>
				</div>
				<div class="titulo-secundario oito-colunas">
					Tipo Autenticação:
					<p>
						<select name='tipo-autenticacao' id='tipo-autenticacao' Style='width:92%;height:28px'>
							<option value=''>Normal</option>
							<option value='selected' <?php echo $dadosEnvio[smtp]?>>IsSMTP()</option>
						</select>
					</p>
				</div>
				<div class="titulo-secundario quatro-colunas">
					<div class="duas-colunas">
						Usuario:
						<p><input type='text' name='usuario' id='usuario' value='<?php echo $dadosEnvio[usuario]?>'></p>
					</div>
					<div class="duas-colunas">
						Senha:
						<p><input type='password' name='senha' id='senha' value='<?php echo $dadosEnvio[senha]?>'></p>
					</div>
				</div>
				<div class="titulo-secundario quatro-colunas">
					<div class="duas-colunas">
						Usar SSL or TLS?:
						<p>
							<select name='tipo-certificado' id='tipo-certificado'>
								<option value=''>Não</option>
								<option value='tls' <?php echo $certificado['tls']?>>TLS</option>
								<option value='ssl' <?php echo $certificado['ssl']?>>SSL</option>
							</select>
						</p>
					</div>
					<div class="duas-colunas">
						<p>&nbsp;</p>
						<input type='submit' class='botao' value='Salvar Configurações E-mail' id='salva-configuracao-email'>
					</div>
				</div>
			</div>
		</div>
		<div class="titulo-container">
			<div class="titulo">
				<p>Configurações SMS</p>
			</div>
			<div class='conteudo-interno'>
				<div class="titulo-secundario quatro-colunas">
					Número Celular:
					<p><input type='text' name='sms-numero' id='sms-numero' value='<?php echo $dadosEnvio[sms_numero]?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas">
					Nome envio SMS:
					<p><input type='text' name='sms-nome' id='sms-user' value='<?php echo $dadosEnvio[sms_nome]?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas">
					Usuário SMS:
					<p><input type='text' name='sms-user' id='sms-user' value='<?php echo $dadosEnvio[sms_usuario]?>'></p>
				</div>
				<div class="titulo-secundario quatro-colunas">
					<div class="duas-colunas">
						Senha SMS:
						<p><input type='password' name='sms-pass' id='sms-pass' value='<?php echo $dadosEnvio[sms_senha]?>'></p>
					</div>
					<div class="duas-colunas">
						<p>&nbsp;</p>
						<input type='submit' class='botao' value='Salvar Configurações SMS' id='salva-configuracao-email'>
					</div>
				</div>
			</div>
		</div>
		<div class="titulo-container">
			<div class="titulo">
				<p>Testar o envio de E-mail e SMS</p>
			</div>
			<div class='conteudo-interno'>
				<div class="titulo-secundario tres-colunas">
					<p>Endereço de E-mail para envio:</p>
					<p><input type='text' name='email-envio-teste' id='email-envio-teste'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>&nbsp;</p>
					<p><input type='submit' class='botao' value='Enviar teste de E-mail'></p>
				</div>

				<div class="titulo-secundario tres-colunas">
					<p>Número do celular para envio:</p>
					<p><input type='text' name='sms-envio-teste' id='sms-envio-teste'></p>
				</div>
				<div class="titulo-secundario seis-colunas">
					<p>&nbsp;</p>
					<p><input type='submit' class='botao' value='Enviar teste de SMS'></p>
				</div>

				<div class="titulo-secundario duas-colunas">
	<?php
		if($_POST['email-envio-teste'] != "")
			enviaEmail($_POST['email-envio-teste'],$dadosEnvio[nome_envio],"Teste de Configuração","Se recebeu este email &eacute; porque as configura&ccedil;&otilde;es de envio est&atilde;o corretas.","<p>Teste de envio efetuado com successo</p>");
	?>
					&nbsp;
				</div>
				<div class="titulo-secundario duas-colunas">
	<?php
		if($_POST['sms-envio-teste'] != "")
			enviaSMS("Configuracaoo de SMS efetuada com sucesso",$_POST['sms-envio-teste'],"<p>Teste de envio efetuado com successo</p>");
	?>
					&nbsp;
				</div>
			</div>
		</div>
	</div>