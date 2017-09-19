<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	function carregarRepresentante(){
		$uf = $_GET['uf'];
		$cidade = $_GET['cidade'];
		$resultado = mpress_query("select post_id, meta_value from wp_postmeta where meta_key = 'representantes_regiao'");
		while($row = mpress_fetch_array($resultado)){
			$r++;
			$regioesRepresentantes[$r][id] 	  = $row[post_id];
			$resultado2 = mpress_query("select meta_value from wp_postmeta where post_id = '$row[post_id]' and meta_key = 'representantes_regiao'");
			if($row2 = mpress_fetch_array($resultado2)){
				$regioesRepresentantes[$r][dados] = unserialize(unserialize($row2['meta_value']));
				$regioesRepresentantes[$r][uf] 	  = $regioesRepresentantes[$r][dados][uf];
				$regioesRepresentantes[$r][cidade]= $regioesRepresentantes[$r][dados][cidade];
				foreach($regioesRepresentantes[$r][uf] as $ufs){
					if($ufs==strtoupper($uf)) {
						$idRepresentante = $regioesRepresentantes[$r][id];
					}
				}

				foreach($regioesRepresentantes[$r][cidade] as $cidades){
					for($c=1;$c<=count($cidades);$c++)
						if($cidades[$c]==strtoupper($cidade)) $idRepresentante = $regioesRepresentantes[$r][id];
				}

			}
		};
		if ($idRepresentante==""){
			echo "<p><font color='red'><b>Nenhum Representante Localizado</b></font></p><input type='hidden' name='fornecedor-id' id='fornecedor-id' value=''>";
		}
		else{
			$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$idRepresentante' and meta_key = 'representantes'");
			if($row = mpress_fetch_array($rs)){
				$dadosRepresentante = unserialize($row['meta_value']);
				echo "<p>".$dadosRepresentante[dadosPrincipais][razaoSocial]."</p><input type='hidden' name='fornecedor-id' id='fornecedor-id' value='$idRepresentante'>";
			}
		}

	}

	function localizaProdutoSelect(){
		$uf = $_GET['uf'];
		$cidade = $_GET['cidade'];

		$sql = "select  pv.Produto_Variacao_ID as Produto_Variacao_ID, pd.Codigo as Codigo, pd.Nome as Nome, Globo, Sbt
				from produtos_dados pd
				inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
				inner join cidades_ufs cu on upper(cu.Cidade) = upper('$cidade') and upper(cu.Uf) = upper('$uf') and pd.Codigo = cu.Tipo_Antena
				where pd.Situacao_ID = 1 and pv.Situacao_ID = 1";
		echo "<select id='select-produtos' name='select-produtos' Style='width:98.5%'>
						<option value=''></option>";

		global $programacaoRegional;
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if($row[Globo]!='sim')$programacaoRegional = "<br><br><span style='color: #bb0008; font-size: small;'>* Para sua regi&atilde;o n&atilde;o est&aacute; dispon&iacute;vel o canal GLOBO.</span>";
		  	if($row[Sbt]!='sim')$programacaoRegional = "<br><br><span style='color: #bb0008; font-size: small;'>* Para sua regi&atilde;o n&atilde;o est&aacute; dispon&iacute;vel o canal SBT.</span>";
  			if($row[Globo]!='sim')if($row[Sbt]!='sim')$programacaoRegional = "<br><br><span style='color: #bb0008; font-size: small;'>* Para sua regi&atilde;o n&atilde;o est&aacute; dispon&iacute;vel o canal SBT e o canal GLOBO.</span>";
		 	$_SESSION['programacaoregional'] = $programacaoRegional;


			echo "<option value='".$row[Produto_Variacao_ID]."' selected>".utf8_encode($row[Codigo])."&nbsp;-&nbsp;".utf8_encode($row[Nome])."</option>";
		}
		echo "</select>";
	}




	function salvarCadastro(){
		$cadastroID 	= $_POST['cadastro-id'];
		$tipoPessoa 	= $_POST['radio-tipo-grupo-8'];
		if ($tipoPessoa=="24"){
			$cpfCnpj = $_POST['cadastro-cpf'];
			$nome = utf8_decode($_POST['cadastro-nome']);
			$rg = utf8_decode($_POST['cadastro-rg']);
		}
		if ($tipoPessoa=="25"){
			$cpfCnpj = $_POST['cadastro-cnpj'];
			$nome = utf8_decode($_POST['cadastro-razao-social']);
			$nomeFantasia = utf8_decode($_POST['cadastro-nome-fantasia']);
			$inscricaoEstadual = $_POST['cadastro-inscricao-estadual'];
			$inscricaoMunicipal = $_POST['cadastro-inscricao-municipal'];
		}
		$email = utf8_decode($_POST['cadastro-email']);
		$observacao = utf8_decode($_POST['cadastro-observacao']);

		//INSERT
		if ($cadastroID==""){

			$telefoneRes 	= $_POST['cadastro-telefone-residencial'];
			$telefoneCom 	= $_POST['cadastro-telefone-comercial'];
			$telefoneCel 	= $_POST['cadastro-telefone-celular'];
			$senha = substr(md5($email), 1, 6);
			if($tipoPessoa=="25") $tipoPessoaSite = 'J'; else $tipoPessoaSite = 'F';

			mpress_query("insert into wp_produtos_cadastros(Nome_Completo, Sobrenome, Apelido, Email, Senha, DDD_Res, Fone_Res, DDD_Cel, Fone_Cel, DDD_Com, Fone_Com, Data_Nascimento, CPF, Sexo, Cep, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Inscricao_estadual, ie_isento, tipo_cadastro)
						 values('$nome','','$nomeFantasia','$email',upper('$senha'),'','$telefoneRes','','$telefoneCel','','$telefoneCom','','$cpfCnpj','','$cep','$rua','$numero','$complemento','$bairro','$cidade','$estado','$pontoRef','$inscricaoEstadual','$isentoIE','$tipoPessoaSite')");
			$cadastroID = mysql_insert_id();

			$sql = "Insert Into cadastros_dados (Cadastro_ID, Tipo_Pessoa, Codigo, Nome, Nome_Fantasia, Senha, Foto, Email, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Observacao, Usuario_Cadastro_ID, Situacao_ID)
										 Values ('$cadastroID', '$tipoPessoa', '$codigo', '$nome', '$nomeFantasia',  '$senha', '$foto', '$email', '$cpfCnpj', '$rg' ,'$inscricaoMunicipal', '$inscricaoEstadual', '$observacao','".$dadosUserLogin[userID]."', 1)";
			mpress_query($sql);
		}
		else{
			$sql = "Update cadastros_dados set 	Tipo_Pessoa			= '$tipoPessoa',
												Nome 				= '$nome',
												Nome_Fantasia 		= '$nomeFantasia',
												Senha 				= '$senha',
												Email 				= '$email',
												Foto 				= '$foto',
												Cpf_Cnpj 			= '$cpfCnpj',
												RG		 			= '$rg',
												Inscricao_Municipal = '$inscricaoMunicipal',
												Inscricao_Estadual 	= '$inscricaoEstadual',
												Observacao 			= '$observacao',
												Usuario_Cadastro_ID = '".$dadosUserLogin[userID]."',
												Situacao_ID 		= 1
					where Cadastro_ID = $cadastroID";
			mpress_query($sql);
		}

		/*************  ENDEREÇOS *****************/

		for ($i=0; $i < count($_POST["cadastro-endereco-id"]); $i++){
			$cadastroEnderecoID 	= $_POST['cadastro-endereco-id'][$i];
			$tipoEnderecoID = $_POST['tipo-endereco-id'][$i];
			$cep = $_POST['cep-endereco'][$i];
			$logradouro = utf8_decode($_POST['logradouro-endereco'][$i]);
			$numero = $_POST['numero-endereco'][$i];
			$complemento = utf8_decode($_POST['complemento-endereco'][$i]);
			$bairro = utf8_decode($_POST['bairro-endereco'][$i]);
			$cidade = utf8_decode($_POST['cidade-endereco'][$i]);
			$uf = $_POST['uf-endereco'][$i];
			$referencia = utf8_decode($_POST['referencia-endereco'][$i]);

			if ($cadastroEnderecoID==""){
				if($i==0)
					mpress_query("update wp_produtos_cadastros set  Cep 				= '$cep',
																	Logradouro 			= '$logradouro',
																	Numero				= '$numero',
																	Complemento			= '$complemento',
																	Bairro				= '$bairro',
																	Cidade				= '$cidade',
																	UF					= '$uf',
																	Referencia			= '$referencia'
								  where Produto_Cadastro_ID = $cadastroID");
				else
					mpress_query("insert into wp_produtos_cadastros_enderecos(Produto_Cadastro_ID, Cep, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia)
								  values('$cadastroID','$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$referencia')");

				$sql = "Insert Into cadastros_enderecos (Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Situacao_ID, Usuario_Cadastro_ID)
												 Values ('$cadastroID', '$tipoEnderecoID', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$referencia', 1, '".$dadosUserLogin[userID]."')";
				mpress_query($sql);
			}
			else{
				$sql = "Update cadastros_enderecos set Tipo_Endereco_ID = '$tipoEnderecoID',
												CEP = '$cep',
												Logradouro = '$logradouro',
												Numero = '$numero',
												Complemento = '$complemento',
												Bairro =  '$bairro',
												Cidade = '$cidade',
												UF = '$uf',
												Referencia = '$referencia',
												Usuario_Cadastro_ID = '".$dadosUserLogin[userID]."'
					where Cadastro_Endereco_ID = $cadastroEnderecoID";
				mpress_query($sql);
			}
		}

		/*************  TELEFONES *****************/
		$cadastroTelefoneResidencial 	= $_POST['cadastro-telefone-residencial'];
		$cadastroTelefoneComercial 	= $_POST['cadastro-telefone-comercial'];
		$cadastroTelefoneCelular 	= $_POST['cadastro-telefone-celular'];


		$sql = "delete from cadastros_telefones where Cadastro_ID = '$cadastroID'";
		mpress_query($sql);

		if ($cadastroTelefoneResidencial!=""){
			$sql = "Insert Into cadastros_telefones (Cadastro_ID, Tipo_Telefone_ID, Telefone, Observacao, Situacao_ID, Usuario_Cadastro_ID)
									 Values ('$cadastroID', '29', '$cadastroTelefoneResidencial', '', 1, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
		}
		if ($cadastroTelefoneComercial!=""){
			$sql = "Insert Into cadastros_telefones (Cadastro_ID, Tipo_Telefone_ID, Telefone, Observacao, Situacao_ID, Usuario_Cadastro_ID)
									 Values ('$cadastroID', '27', '$cadastroTelefoneComercial', '', 1, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
		}
		if ($cadastroTelefoneCelular!=""){
			$sql = "Insert Into cadastros_telefones (Cadastro_ID, Tipo_Telefone_ID, Telefone, Observacao, Situacao_ID, Usuario_Cadastro_ID)
									 Values ('$cadastroID', '28', '$cadastroTelefoneCelular', '', 1, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
		}
		echo $cadastroID;
	}


	function validarCadastro(){
		$campo = $_GET['campo'];
		$cadastroID = $_GET['cadastro-id'];
		if ($cadastroID!=""){ $condicao = " and Cadastro_ID <> $cadastroID";}
		$sql = "select Cadastro_ID from cadastros_dados where Cpf_Cnpj = '$campo' and Situacao_ID = 1 $condicao";
		$resultado = mpress_query($sql);
		if ($row = mpress_fetch_array($resultado))
			echo $row[Cadastro_ID];
	}

	function localizaPrestadorSelect(){
		$sql = "select Cadastro_ID, Nome, Nome_Fantasia from cadastros_dados cd ";

		echo "<select id='select-fornecedor' name='select-fornecedor' Style='width:98.5%'>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			echo "<option value='".$row[Cadastro_ID]."'>".utf8_encode($row[Nome])."</option>";
		}
		echo "</select>";
	}

	function carregarCadastroPedido(){
		global $caminhoSistema;
		$cadastroID = $_GET['cadastro-id'];
		$nomeCampo = $_GET['nome-campo'];
		$txtNaoInfo = "N&atilde;o Informado";

		if ($cadastroID!=""){
			$sql = "Select Codigo, Tipo_Pessoa, Tipo_Cadastro, Grupo_ID, Nome, Nome_Fantasia, Senha, Email, Data_Nascimento, Foto, Cpf_Cnpj, RG,
							Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Observacao, Usuario_Cadastro_ID, Situacao_ID
						from cadastros_dados where Cadastro_ID = $cadastroID";

			$query = mpress_query($sql);
			if($cadastro = mpress_fetch_array($query)){
				$tipoPessoa = $cadastro[Tipo_Pessoa];
				$codigo = preencheTextoSeVazio($txtNaoInfo,$cadastro[Codigo]);
				if ($tipoPessoa=="24"){
					$cpf = preencheTextoSeVazio($txtNaoInfo, $cadastro[Cpf_Cnpj]);
					$rg = preencheTextoSeVazio($txtNaoInfo, $cadastro[RG]);

					$nomeCompleto =  preencheTextoSeVazio($txtNaoInfo, utf8_encode($cadastro[Nome]));
					$dataNascimento = preencheTextoSeVazio($txtNaoInfo, $cadastro[Data_Nascimento]);
				}
				if ($tipoPessoa=="25"){
					$cnpj = preencheTextoSeVazio($txtNaoInfo, $cadastro[Cpf_Cnpj]);
					$razaoSocial = preencheTextoSeVazio($txtNaoInfo, utf8_encode($cadastro[Nome]));
					$nomeFantasia = preencheTextoSeVazio($txtNaoInfo, utf8_encode($cadastro[Nome_Fantasia]));
					$inscricaoEstadual = preencheTextoSeVazio($txtNaoInfo, $cadastro[Inscricao_Estadual]);
					$inscricaoMunicipal = preencheTextoSeVazio($txtNaoInfo, $cadastro[Inscricao_Municipal]);
				}
				$observacao = preencheTextoSeVazio($txtNaoInfo, utf8_encode($cadastro[Observacao]));
				$emailOrig = $cadastro[Email];
				$email = preencheTextoSeVazio($txtNaoInfo, utf8_encode($cadastro[Email]));
			}

			$sql = "select Cadastro_Telefone_ID, t.Descr_Tipo, Telefone, Observacao
							from cadastros_telefones ct
							left join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
					where  ct.Situacao_ID = 1
					and Cadastro_ID = $cadastroID";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$telefones .= "<b>".utf8_encode($row[Descr_Tipo]).":</b> ".$row[Telefone]."&nbsp;".$row[Numero]."&nbsp;&nbsp;&nbsp;";
			}

			echo "	<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center'>
						<tbody>
						<tr>";
			if ($tipoPessoa=="24"){
				echo "		<td colspan='2' class='fundo-escuro-titulo' width='50%'><p>Nome Completo</p></td>
							<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>CPF</p></td>
							<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>RG</p></td>
						</tr>
						<tr>
							<td colspan='2' class='fundo-claro'><p class='link-cadastro' cadastro-id='".$cadastroID."' style='cursor:pointer;'>$nomeCompleto</p></td>
							<td colspan='1' class='fundo-claro'><p>$cpf</p></td>
							<td colspan='1' class='fundo-claro'><p>$rg</p></td>
						</tr>";
			}

			if ($tipoPessoa=="25"){
				echo "		<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>Raz&atilde;o social</p></td>
							<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>Nome Fantasia</p></td>
							<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>CNPJ</p></td>
							<td colspan='1' class='fundo-escuro-titulo' width='25%'><p>Inscri&ccedil;&atilde;o Estadual</p></td>
						</tr>
						<tr>
							<td colspan='1' class='fundo-claro'><p class='link-cadastro' cadastro-id='".$cadastroID."' style='cursor:pointer;'>$razaoSocial</p></td>
							<td colspan='1' class='fundo-claro'><p class='link-cadastro' cadastro-id='".$cadastroID."' style='cursor:pointer;'>$nomeFantasia</p></td>
							<td colspan='1' class='fundo-claro'><p>$cnpj</p></td>
							<td colspan='1' class='fundo-claro'><p>$inscricaoEstadual</p></td>
						</tr>";
			}
			echo "		<tr>
							<td colspan='2' class='fundo-escuro-titulo'><p>E-mail</p></td>
							<td colspan='2' class='fundo-escuro-titulo'><p>Telefones</p></td>
						</tr>
						<tr>
							<td colspan='2' class='fundo-claro'><p>$email <input type='hidden' class='email-workflow' id='email-workflow' value='$emailOrig'></p></td>
							<td colspan='2' class='fundo-claro'><p>$telefones</p></td>
						</tr>
						</tbody>
				</table>

				<table width='100%' style='margin-top:0px;border:0px solid silver;margin-bottom:0px;' cellpadding='0' cellspacing='0' align='center'>
					<tbody>
						<tr>
							<td width='58%' align='left' valign='top'>
								<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center' id='tabela-endereco-$nomeCampo'>
									<tr>
										<thead>
											<td class='fundo-escuro-titulo'>
												Endere&ccedil;o Cadastrado
											</td>
											<!--
											<td class='fundo-escuro-titulo' align='center' nowrap>Instalar</td>
											<td class='fundo-escuro-titulo' align='center' nowrap>Faturar</td>
											-->
											<td class='fundo-escuro-titulo'>&nbsp;</td>
										</thead>
										<tbody>";

			$sql = "select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo, Tipo_Endereco_ID
					from cadastros_enderecos ce
					left join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
					where Cadastro_ID = $cadastroID
					and ce.Situacao_ID = 1
					and Tipo_Endereco_ID IN(26,38) order by Cadastro_Endereco_ID desc limit 2 ";
			//echo $sql;
			$i=0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				if ($i==1){ $selecionado = " checked ";} else { $selecionado = " "; }
				$caracteristicas = " cep-endereco='".$row[CEP]."' logradouro-endereco='".utf8_encode($row[Logradouro])."' numero-endereco='".$row[Numero]."' complemento-endereco='".utf8_encode($row[Complemento])."'
										bairro-endereco='".utf8_encode($row[Bairro])."' cidade-endereco='".utf8_encode($row[Cidade])."' uf-endereco='".$row[UF]."' referencia-endereco='".utf8_encode($row[Referencia])."'";


				echo "						<tr>
												<td class='fundo-claro'><p Style='margin:2px 5px 0 5px;float:left;width:100%;'>
													<b>".utf8_encode($row[Descr_Tipo]).":</b>
													".utf8_encode($row[Logradouro])."&nbsp;".$row[Numero]."&nbsp;".utf8_encode($row[Complemento])."
													&nbsp;&nbsp;CEP:".$row[CEP]."&nbsp;&nbsp;".utf8_encode($row[Referencia])."
													&nbsp;&nbsp;".utf8_encode($row[Bairro])."&nbsp;&nbsp;".utf8_encode($row[Cidade])."&nbsp;&nbsp;".$row[UF]." &nbsp;&nbsp;
													<a href='https://maps.google.com.br/?q=".utf8_encode($row[Logradouro]).", ".$row[Numero]." ".utf8_encode($row[CEP])." ".utf8_encode($row[Cidade])." ".utf8_encode($row[UF])."' target='_blank' >Mapa</a></p>
													<input type='hidden' class='endereco-".$row[Tipo_Endereco_ID]."' name='endereco-instalar' $caracteristicas/>
												</td>
											</tr>
											";
			}
			if($i==0){ echo "				<tr><td><p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum endere&ccedil;o cadastrado</p></td></tr>"; }
			echo "						</td>
									</tbody>
								</table>";
		}
	}



	function carregarProdutoDetalhes(){
		$programacaoRegional = $_SESSION['programacaoregional'];
		$produtoVariacaoID = $_POST['select-produtos'];
		$sql = "select pd.Descricao_Completa as Descricao_Completa, pd.Tipo_Produto as Tipo_Produto,
					pv.Valor_Custo as Valor_Custo, pv.Valor_Venda as Valor_Venda, pv.Valor_Promocao as Valor_Promocao,
					Forma_Cobranca_ID, Percentual_Venda, t.Descr_Tipo as Forma_Cobranca,
					pv.Data_Inicio_Promocao as Data_Inicio_Promocao, pv.Data_Fim_Promocao as Data_Fim_Promocao, pv.Saldo_Estoque as Saldo_Estoque,
					pv.Altura as Altura, pv.Largura as Largura, pv.Comprimento as Comprimento, pv.Comprimento as Comprimento, pv.Peso as Peso
				from produtos_dados pd
				inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
				inner join tipo t on t.Tipo_ID = Forma_Cobranca_ID
				where pv.Produto_Variacao_ID = $produtoVariacaoID ";
		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			echo "	<input type='hidden' name='forma-cobranca' id='forma-cobranca' value='".$row[Forma_Cobranca_ID]."'>
					<table width='100%' style='margin-top:5px;border:1px solid silver;margin-bottom:0px;' cellpadding='2' cellspacing='2' align='center'>
					<tbody>
						<tr>
							<td class='tabela-fundo-escuro-titulo' width='50%'><p>Descri&ccedil;&atilde;o</p></td>
							<td class='tabela-fundo-escuro-titulo' width='50%'><p>Venda Unint&aacute;rio</p></td>
						</tr>
						<tr>
							<td class='tabela-fundo-claro'>
								<p>".nl2br(utf8_encode($row[Descricao_Completa]))."</p>
								$programacaoRegional
							</td>
							<td class='tabela-fundo-claro'>
								<p>R$&nbsp;".number_format($row[Valor_Venda], 2, ',', '.')."</p>
								<input type='hidden' id='valor-venda-unitario' name='valor-venda-unitario' value='".str_replace(".",",",$row[Valor_Venda])."'/>
								<input type='hidden' id='valor-custo-unitario' name='valor-custo-unitario' value='".str_replace(".",",",$row[Valor_Custo])."'/>
							</td>
						</tr>
					</tbody>
				</table><br>";

		}
	}


	function salvarProcesso(){


		global $dadosUserLogin;
		$workflowID = $_POST['workflow-id'];
		$codigoPedidoAnt = $_POST['codigo-pedido-ant'];
		$codigoPedido  = $_POST['codigo-pedido'];

		if ($workflowID==""){
			$tipoProcessoID  = $_POST['tipo-processo-id'];
			$solicitanteID  = $_POST['solicitante-id'];
			$fornecedorID = $_POST['fornecedor-id'];

			$resultado = mpress_query("select Produto_Cadastro_Endereco_ID from wp_produtos_cadastros_enderecos where Produto_Cadastro_ID = $solicitanteID");
			if($row = mpress_fetch_array($resultado))
				$enderecoEntregaID = $row[0];

			mpress_query("insert into wp_produtos_cadastros_pedidos(Produto_Cadastro_ID, Situacao, Representante_ID, Endereco_Entrega_ID)
						  values('$solicitanteID','Aguardando Pagamento','$fornecedorID','$enderecoEntregaID')");
			$workflowID = mysql_insert_id();

			$sql = "insert into telemarketing_workflows (Workflow_ID,Tipo_Workflow_ID, Codigo, Solicitante_ID, Fornecedor_ID, Usuario_Cadastro_ID)
												values	('$workflowID','$tipoProcessoID', '$codigoPedido', '$solicitanteID', '$fornecedorID', '".$dadosUserLogin['userID']."')";

			mpress_query($sql);

			$sql = "insert into telemarketing_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Responsabilidade_ID, Usuario_Cadastro_ID  )
												values	('$workflowID', 'Pedido Telemarketing Aberto', '', '39', '', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);

			for ($i=0; $i < count($_POST["produto-variacao-id-pedido"]); $i++){
				$produtoVariacaoID = $_POST["produto-variacao-id-pedido"][$i];
				$quantidadeProduto = $_POST["quantidade-pedido"][$i];
				$valorCusto = $_POST["valor-custo-pedido"][$i];
				$valorVenda = $_POST["valor-venda-pedido"][$i];
				$descricao = utf8_decode($_POST["descricao-produto"][$i]);
				$valorTotal 		= number_format($valorVenda*$quantidadeProduto, 2, '.', '');
				$valorTotalGeral	+= $valorVenda;
				$quantidadeTotal 	+= $quantidadeProduto;

				$resultado = mpress_query(" select v.Produto_Variacao_ID
											from wp_produtos_produtos p
											inner join wp_produtos_produtos_variacoes v on v.Produto_ID = p.Produto_ID
											inner join produtos_dados d on d.Codigo = p.Codigo and d.Situacao_ID = 1
											inner join produtos_variacoes pv on pv.Produto_ID = d.Produto_ID and pv.Situacao_ID = 1
											where pv.Produto_Variacao_ID = $produtoVariacaoID and v.Situacao = 'A' and p.Situacao = 'A'");
				if($row = mpress_fetch_array($resultado))
					$produtoVariacaoSiteID = $row[0];

				mpress_query("insert into wp_produtos_cadastros_pedidos_detalhes(Produto_Cadastro_Pedido_ID, Produto_Variacao_ID, Quantidade, Valor_Unitario, Valor_Total)
							  values('$workflowID','$produtoVariacaoSiteID','$quantidadeProduto','$valorVenda','$valorTotal')");

				$sql = "insert into telemarketing_workflows_produtos (Workflow_ID, Produto_Variacao_ID, Quantidade, Valor_Custo_Unitario, Valor_Venda_Unitario, Situacao_ID, Usuario_Cadastro_ID)
															values ('$workflowID', '$produtoVariacaoID', '$quantidadeProduto', '$valorCusto', '$valorVenda', 1, '".$dadosUserLogin['userID']."')";
				mpress_query($sql);
			}
			mpress_query("update wp_produtos_cadastros_pedidos set Quantidade_Itens 	= '$quantidadeTotal',
																   Valor_Total 			= '".number_format($valorTotalGeral, 2, '.', '')."'
						  where Produto_Cadastro_ID = '$solicitanteID'");

		}
		else{
			$sql = "update telemarketing_workflows set Codigo = '$codigoPedido' where Workflow_ID = $workflowID";
			mpress_query($sql);

			$descricaoFollow  = utf8_decode($_POST['descricao-follow']);
			$situacaoID  = $_POST['select-situacao-follow'];
			$motivoID  = $_POST['select-motivo-follow'];
			$outros  = $_POST['motivo-outros'];

			if ($codigoPedidoAnt!=$codigoPedido){
				$descricaoFollow .= "<p><b>Protocolo Atualizado de $codigoPedidoAnt para $codigoPedido</b></p>";
			}

			$tipos = mpress_query(" select Descr_Tipo from tipo where tipo_id = $situacaoID");
			if($tipo = mpress_fetch_array($tipos)) $situacao = $tipo[0];
			mpress_query("update wp_produtos_cadastros_pedidos set Situacao = '$situacao' where Produto_Cadastro_Pedido_ID = $workflowID");

			$sql = "insert into telemarketing_follows (Workflow_ID, Descricao, Dados, Situacao_ID, Motivo_ID, Responsabilidade_ID, Usuario_Cadastro_ID  )
												values	('$workflowID', '$descricaoFollow', '$outros', '$situacaoID', '$motivoID', '', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
		}
		echo $workflowID;

	}
?>