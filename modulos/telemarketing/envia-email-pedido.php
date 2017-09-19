<?php
	require_once("../../config.php");

	$resultado = mpress_query("select *
							   from wp_produtos_cadastros_pedidos p
							   inner join wp_produtos_cadastros_pedidos_detalhes d on d.Produto_Cadastro_Pedido_ID = p.Produto_Cadastro_Pedido_ID
							   inner join wp_produtos_cadastros c on c.Produto_Cadastro_ID = p.Produto_Cadastro_ID
							   inner join wp_produtos_produtos_variacoes_valores vv on vv.Produto_Variacao_ID = d.Produto_Variacao_ID
							   inner join wp_produtos_produtos_variacoes v on v.Produto_Variacao_ID = vv.Produto_Variacao_ID
							   inner join wp_produtos_produtos pr on pr.Produto_ID = v.Produto_ID
							   where p.Produto_Cadastro_Pedido_ID = ".$_GET['workflowID']." and vv.Data_Final_Cadastro is null");
	if($row = mysql_fetch_array($resultado)){
		$idPedido 	= $row[0];
		$cadastroID = $row[1];
		$itens		= $row[2];
		$valor		= number_format($row[3], 2, ',', '.');
		$senha 		= $row[20];
	}


	$resultado = mysql_query("select d.Nome, d.Email,
							(select telefone from cadastros_telefones t where t.Cadastro_ID = d.Cadastro_ID and t.Tipo_Telefone_ID = 28) Celular,
							(select telefone from cadastros_telefones t where t.Cadastro_ID = d.Cadastro_ID and t.Tipo_Telefone_ID = 29) Residencial
							from cadastros_dados d
							where d.Cadastro_ID = '$cadastroID'");
	if($row = mysql_fetch_array($resultado)){
		$nome 		 = ($row[0]);
		$email 		 = $row[1];
		$foneRes 	 = $row[3];
		$foneCel 	 = $row[2];
		$cep 		 = $row[15];
		$logradouro  = ($row[16]);
		$numero 	 = $row[17];
		$complemento = ($row[18]);
		$bairro 	 = ($row[19]);
		$cidade 	 = ($row[20]);
		$uf 		 = $row[21];
		$referencia	 = ($row[22]);
	}

	$rsEnd = mysql_query("select * from cadastros_enderecos where Cadastro_ID = '$cadastroID' and Tipo_Endereco_ID = 38");
	if($rowEnd = mysql_fetch_array($rsEnd)){
		$cep  			= $rowEnd[3];
		$logradouro 	= ($rowEnd[4]);
		$numero  		= $rowEnd[5];
		$complemento	= ($rowEnd[6]);
		$bairro  		= $rowEnd[7];
		$cidade  		= ($rowEnd[8]);
		$uf  			= $rowEnd[9];
		$referencia 	= ($rowEnd[10]);
	}

	$siteName = "Claro Tv Livre";

	$msg  ="<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
					<head>
						<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
						<title>$siteName</title>
						<style type='text/css'>

							.pp-pedido-email p, .pp-pedido-email h1, .pp-pedido-email a { font-family:helvetica, arial;}
							.pp-pedido-email h1 { font-size:20px; text-align:center;}
							.pp-pedido-email p { font-size:12px; margin: 0px;}
							.pp-pedido-email p span {font-size:14px; font-weight:bold;}
							body {color:#000000;}

						</style>
					</head>
					<body>
						<table width='707' align='center' class='pp-pedido-email'>
							<tr>
								<td align='left' valign='middle' class='bordaTabela'>
								<img src='http://clarotvlivre.brasilsat.com.br/wp-content/images/layout/topo-email.jpg' width='707'/></td>
							</tr>
							<tr>
								<td align='left' valign='top' >
									<table cellpadding='0' align='center' cellspacing='0' width='95%' border='0'>
										<tr>
											<td width='21' style='width:21px;' nowrap>
											<img src='http://img.carrefour.com.br/mkt/new-news-carrefour/templates/transp.gif' width='21' height='1' style='display:block'>
											</td>
											<td>
												<h1 style='color:#333333' Style='margin-top:10px'> Confirma&ccedil;&atilde;o do seu pedido.</h1>
												<p style='font-size:18px;'>Ol&aacute; $nome, obrigado por escolher $siteName.</p>
												<br>
												<p>Veja abaixo o n&uacute;mero e detalhes do seu pedido, ele &eacute; a identifica&ccedil;&atilde;o necess&aacute;ria para voc&ecirc; obter qualquer informa&ccedil;&atilde;o a respeito.</p>
												<br>
												<p><strong>N&uacute;mero do pedido: $idPedido</strong></p>

												<br><b>Total de &Iacute;tens no Pedido:</b> $itens

												<br><b>Valor Total dos Produtos:</b> R$ $valor

												<br><br><strong>Dados Pessoais:</strong>
														<br>$nome
														<br>Email: $email
														<br>Tel. Residencial: $foneRes
														  | Tel. Celular: $foneCel
														<br>

														<br><strong>Endere&ccedil;o de Entrega:</strong>
														<br>$logradouro $numero $complemento
														<br>$bairro - $cidade - $uf
														<br>$cep<br>

														<br><strong>Dados para Acompanhamento do pedido:</strong>
														<br>Login: $email
														<br>Senha: $senha <br>

														<br><strong>Aten&ccedil;&atilde;o:</strong><br>
														- Se voc&ecirc; tiver qualquer d&uacute;vida sobre seu pedido, por favor sinta-se &agrave; vontade para contatar-nos atrav&eacute;s do nosso site <a href='http://clarotvlivre.brasilsat.com.br' Style='font-size:12px;color:blue;'>http://clarotvlivre.brasilsat.com.br</a><br>
														- A partir deste momento n&atilde;o &eacute; permitida a inclus&atilde;o de novos produtos ou altera&ccedil;&atilde;o do endere&ccedil;o de entrega neste pedido; <br>
														- Considera-se conclu&iacute;da a compra ap&oacute;s a confirma&ccedil;&atilde;o dos dados cadastrais e aprova&ccedil;&atilde;o dos meios de pagamento. <br>
														<br>Em breve entraremos em contato com informa&ccedil;&otilde;es sobre pagamento e envio do seu pedido.<br><br>
													</td>
												</tr>
											</table>
										</td>
									</tr>
								</table>
							</body>
						</html>";




	$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 7");
	if($row = mysql_fetch_array($resultado))
		$dadosEnvio = unserialize($row[descr_tipo]);

	$host 		= $dadosEnvio[servidor_smtp];
	$userName	= $dadosEnvio[usuario];
	$password	= $dadosEnvio[senha];
	$ssl		= "tsl";
	$nomeEnvio	= $dadosEnvio[nome_envio];
	$emailEnvio	= $dadosEnvio[email_envio];
	$porta		= $dadosEnvio[porta];

	require("../../includes/class.phpmailer.php");

	$mail = new PHPMailer();
	$mail->SetLanguage("br", "libs/");
	$mail->IsSMTP();
	$mail->SMTPAuth =  true;
	$mail->SMTP_PORT = $porta;

	if($ssl != ""){
		$mail->SMTPSecure = $ssl;
	}

	$mail->Host 	= $host;
	$mail->Username = $userName;
	$mail->Password = $password;
	$mail->From 	= $emailEnvio;
	$mail->Sender 	= $emailEnvio;
	$mail->FromName = ($nomeEnvio);
	$mail->WordWrap = 500;
	$mail->IsHTML(true);

	$emailPrincipal = "tonikuss@gmail.com";
	$emailPara	= $email;
	$nomePara	= $nome;
	$mail->AddAddress($emailPara,$nomePara);
	$mail->AddReplyTo($emailEnvio,($nomeEnvio));
	$mail->AddBCC($emailPrincipal, '');
	$mail->Port = $porta;

	$siteName	= ($row[1]);
	$mail->Body =  ($msg);
	$mail->Subject = "Recebemos seu pedido N. $idPedido";

	if(!$mail->Send()){
		echo "<p style='color:red'>".str_replace('Could not connect to SMTP host','Existem erros na configura&ccedil;&atilde;o e o e-mail n&atilde;o pode ser enviado!', $mail->ErrorInfo)."<br><br><br></p>";
	}else{
		echo "email enviado com sucesso";
	}
?>