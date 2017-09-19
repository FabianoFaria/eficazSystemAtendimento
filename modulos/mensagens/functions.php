<?php
	session_start();
	function selecionaDestinatarios($dados){
		if($_SESSION[dadosUserLogin]['grupoID'] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) )";
		if($_SESSION[dadosUserLogin]['grupoID'] == -3) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_ID from cadastros_vinculos vc where vc.Cadastro_Filho_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) )";
		$rs = mpress_query("select Cadastro_ID, Nome, email
							from cadastros_dados cd
							where email is not null and email <> '' and cadastro_id >= 1 $sqlCond
							order by nome");
		while($row = mpress_fetch_array($rs)){
			if(in_array($row['Cadastro_ID'],$dados,true)==1)$selecionado = "selected"; else $selecionado = "";
			echo "<option value='$row[Cadastro_ID]' $selecionado>".strtoupper($row[Nome])." $cadastrado</option>";
		}
	}

	function selecionaDestinatariosVisualiza($dados){
		if($_SESSION[dadosUserLogin]['grupoID'] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) or cd.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."')";
		if($_SESSION[dadosUserLogin]['grupoID'] == -3) $sqlCond .= " and cd.Cadastro_ID = ".$_SESSION[dadosUserLogin][userID]." ";
		$rs = mpress_query("select Cadastro_ID, Nome, email
							from cadastros_dados cd
							where email is not null and email <> '' and cadastro_id >=1 $sqlCond
							order by nome");
		while($row = mpress_fetch_array($rs)){
			if(in_array($row['Cadastro_ID'],$dados,true)==1){
				echo $pontoevirgula.$row[Nome];
				$pontoevirgula = "; ";
			}
		}
	}

	function salvaMensagem(){
		global $caminhoSistema,$tituloSistema;
		$remetente 		= $_SESSION[dadosUserLogin][userID];
		$mensagemID		= $_POST['edita-mensagem'];
		$situacao 		= $_GET['tipo'];
		$destinatario 	= serialize($_POST['mensagem-destinatario']);
		$copia 			= serialize($_POST['mensagem-copia']);
		$assunto 		= $_POST['assunto-mensagem'];
		$mensagem 		= $_POST['mensagem-conteudo'];
		mpress_query("delete from mensagens where mensagem_id = $mensagemID");
		mpress_query("insert into mensagens(Remetente_ID, Destinatarios, Destinatarios_Copia, Assunto, Mensagem, Situacao_Mensagem_ID)
					values($remetente,'$destinatario','$copia','$assunto','$mensagem','$situacao')");
		if($situacao == 68){

			foreach($_POST['mensagem-destinatario'] as $destinatario){
				$destinatarios .= $virgula.$destinatario;$virgula = ",";
			}
			foreach($_POST['mensagem-copia'] as $copia){
				$destinatarios .= $virgula.$copia;$virgula2 = ",";
			}

			$rs = mpress_query("select Cadastro_ID, Nome, Email from cadastros_dados where cadastro_id in ($destinatarios) order by nome");
			while($row = mpress_fetch_array($rs)){
				$mensagem =" <html xmlns='http://www.w3.org/1999/xhtml'>
								<head>
								<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
								</head>
								<body style='margin: 0 auto;background-color:#fafafa'>
								<table border='0' align='center' cellpadding='0' cellspacing='0' id='mwn'>
									<tr>
										<td width='708' height='24' align='center'></td>
									</tr>
									<tr>
										<td width='708' height='111' align='center'><a href='#' target='_blank'><img src='".$caminhoSistema."/images/geral/topo-email.jpg' width='708' height='138' border='0' id='r&r' style='display: block' /></a>
										</td>
									</tr>
									<tr>
										<td>
											<table width='708' border='0' align='center' cellpadding='0' cellspacing='0' >
												<tr>
													<td height='130' align='left'><p style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:normal; font-size:13px; color:#222222; margin:21px'>
													Prezado ".$row['Nome'].",<br>
													  Voc&ecirc; est&aacute; recebendo uma nova mensagem.<br>
													  Para visualiz&aacute;-la e respond&ecirc;-la, favor clicar abaixo e acessar sua caixa de mensagens.</p></td>
												</tr>
											</table>
										</td>
									</tr>
									<tr>
										<td align='center'><br><table width='330' border='0' cellspacing='0' cellpadding='1' style='margin-left:21px'>
										  <tr>
											<td width='300' height='39' bgcolor='#f0f0f0' style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:bold; font-size:13px; color:#222222; margin:21px'>
												<a href='".$caminhoSistema."/' target='_blank' style='color: f0f0f0; text-decoration: none'>
													<span style='font-family:Helvetica, Arial, Tahoma, Verdana, sans-serif; font-weight:bold; font-size:13px; color:#222222; margin:21px' >
														Para visualizar suas mensagens, clique aqui
													</span>
												</a>
											</td>
										  </tr>
										</table></td>
									</tr>
								</table>
							</body>
						</html>";
				enviaEmail($row[Email],utf8_encode($row[Nome]),$tituloSistema." - Nova mensagem.",$mensagem,"");
			}
			echo "Mensagem enviada com sucesso.";
		}
	}

	function mensagensSalvas(){
		$resultado = mpress_query("select Mensagem_ID, Data_Cadastro, Assunto from mensagens where remetente_id = ".$_SESSION[dadosUserLogin][userID]." and situacao_mensagem_id = 69 and situacao_id = 1 order by data_cadastro");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = implode("/",array_reverse(explode("-",substr($row['Data_Cadastro'], 0,10))))." ".substr($row['Data_Cadastro'], 11,5);
			$dados[colunas][conteudo][$i][2] = $row['Assunto'];
			$dados[colunas][conteudo][$i][3] = "<center><p class='link mensagem-rascunho-edita' attr-id='".$row['Mensagem_ID']."' >visualizar</a></center>";
			$dados[colunas][conteudo][$i][4] = "<center><img src='../images/geral/indisponivel.png' Style='cursor:pointer'></center>";
		}

		$dados[colunas][titulo][1] 	= "Data Cadastro";
		$dados[colunas][titulo][2] 	= "Assunto";
		$dados[colunas][titulo][3] 	= "";
		$dados[colunas][titulo][4] 	= "";

		$dados[colunas][tamanho][1] = "width='120px'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='70px'";
		$dados[colunas][tamanho][4] = "width='20px'";

		$largura = "100%";
		$colunas = "4";
		geraTabela($largura,$colunas,$dados);
		echo "<input type='hidden' name='edita-mensagem' id='edita-mensagem'>";
		if($i==0){
			echo "<p Style='margin:10px 5px 10px 5px; text-align:center'>Nenhuma mensagem aguardando envio</p>";
		}
	}

	function mensagensEnviadas(){
		$resultado = mpress_query("select Mensagem_ID, Data_Cadastro, Assunto from mensagens where remetente_id = ".$_SESSION[dadosUserLogin][userID]." and situacao_mensagem_id = 68 and situacao_id = 1 order by data_cadastro");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = implode("/",array_reverse(explode("-",substr($row['Data_Cadastro'], 0,10))))." ".substr($row['Data_Cadastro'], 11,5);
			$dados[colunas][conteudo][$i][2] = $row['Assunto'];
			$dados[colunas][conteudo][$i][3] = "<center><p class='link mensagem-enviada-visualiza' attr-id='".$row['Mensagem_ID']."' >visualizar</a></center>";
		}

		$dados[colunas][titulo][1] 	= "Data Cadastro";
		$dados[colunas][titulo][2] 	= "Assunto";
		$dados[colunas][titulo][3] 	= "";

		$dados[colunas][tamanho][1] = "width='120px'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='70px'";

		$largura = "100%";
		$colunas = "3";
		geraTabela($largura,$colunas,$dados);
		echo "<input type='hidden' name='edita-mensagem' id='edita-mensagem'>";
		if($i==0){
			echo "<p Style='margin:10px 5px 10px 5px; text-align:center'>Nenhuma mensagem na caixa de envios.</p>";
		}
	}

	function mensagensRecebidas(){
		$resultado = mpress_query("select Mensagem_ID, Data_Cadastro, Assunto from mensagens
		where destinatarios like '%\"".$_SESSION[dadosUserLogin][userID]."\"%' or destinatarios like '%\"".$_SESSION[dadosUserLogin][userID]."\"%'
		and situacao_mensagem_id = 68 and situacao_id = 1 order by data_cadastro");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = implode("/",array_reverse(explode("-",substr($row['Data_Cadastro'], 0,10))))." ".substr($row['Data_Cadastro'], 11,5);
			$dados[colunas][conteudo][$i][2] = $row['Assunto'];
			$dados[colunas][conteudo][$i][3] = "<center><p class='link mensagem-recebida-visualiza' attr-id='".$row['Mensagem_ID']."' >visualizar</a></center>";
		}

		$dados[colunas][titulo][1] 	= "Data Cadastro";
		$dados[colunas][titulo][2] 	= "Assunto";
		$dados[colunas][titulo][3] 	= "";

		$dados[colunas][tamanho][1] = "width='120px'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='70px'";

		$largura = "100%";
		$colunas = "3";
		geraTabela($largura,$colunas,$dados);
		echo "<input type='hidden' name='edita-mensagem' id='edita-mensagem'>";
		if($i==0){
			echo "<p Style='margin:10px 5px 10px 5px; text-align:center'>Nenhuma mensagem na caixa de entrada.</p>";
		}
	}


?>