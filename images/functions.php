<?php
	session_start();
	add_shortcode('mpress-otica','integraOtica');

	require( dirname( __FILE__ ).'/widgets/functions.php');

	function integraOtica(){
		echo "	<script>
					var mpressUrl ='".plugin_dir_url(__FILE__)."';
				</script>";
		wp_enqueue_style('otica-mpress-css',plugin_dir_url(__FILE__)."css/style.css");
		wp_enqueue_style('chosen-css',plugin_dir_url(__FILE__)."css/chosen.css");
		wp_enqueue_style('datetimepicker-css',plugin_dir_url(__FILE__)."css/jquery.datetimepicker.css");
		wp_enqueue_style('ui-css',plugin_dir_url(__FILE__)."css/jquery-ui.css");

		wp_enqueue_script('ui',plugin_dir_url(__FILE__)."js/jquery-ui-1.10.4.custom.min.js");
		wp_enqueue_script('easyui',plugin_dir_url(__FILE__)."js/jquery.easyui.min.js");
		wp_enqueue_script('datetimepicker',plugin_dir_url(__FILE__)."js/jquery.datetimepicker.js");
		wp_enqueue_script('maskMoney',plugin_dir_url(__FILE__)."js/jquery.maskMoney.js");
		wp_enqueue_script('maskedinput',plugin_dir_url(__FILE__)."js/jquery.maskedinput.js");
		wp_enqueue_script('chosen',plugin_dir_url(__FILE__)."js/chosen.jquery.min.js");
		wp_enqueue_script('fancybox',plugin_dir_url(__FILE__)."js/jquery.fancybox.js");

		wp_enqueue_script('otica-mpress-funcoes',plugin_dir_url(__FILE__)."js/funcoes.js");
		wp_enqueue_script('mpress-formatacao',plugin_dir_url(__FILE__)."js/funcoes.formatacao.js");


		if($_SESSION['curl']['dadoslogin']['userID']=='')
			clienteLoginAreaRestrita();
		else{
			echo "<div id='chamados-cliente-container-geral'>";
			homeAreaRestrita();
			echo "</div>";
		}

		//echo "<pre Style='float:left;width:100%;'>";
		//print_r($_SESSION['curl']);
		//echo "</pre>";
	}

	function clienteLoginAreaRestrita(){
		$dadosIntegracao = get_option('integracao');
		echo "	<div Style='float:left; width:34%' >
					<form name='frmLogin' method='post' id='frmFormularios1'>
						<div id='login-mpress-container' Style='float:left;width:98%;border:0px solid silver;margin-bottom:10px;margin-top:5px;border-radius:5px;padding:0px'>
							<h2> J&aacute; sou cadastrado</h2>
							<div id='login-principal' style='margin-top:51px;'>
								<label Style='float:left;'>E-mail</label>
								<input type='text' name='login' id='txtLogin' Style='width:95%;' class='input-login-mpress'>
								<label Style='float:left;'>Senha</label>
								<input type='password' name='senha' id='txtSenha' Style='width:95%;' class='input-login-mpress'>
								<input class='botao-login-mpress' type='button' value='Login' Style='float:right;margin-top:8px;width:200px;'>
							</div>
							<div id='erro-login-mpress'></div>
							<input type='hidden' name='modulo' value='chamados'>
							<input type='hidden' name='metodo' value='validaLogin'>
							<input type='hidden' name='integracao' value='".$dadosIntegracao['url']."'>
						</div>
					</form>
				</div>";
		echo "
				<div Style='float:right; width:65%' class='bloco-novo-cadastro'>
					<form name='frmCadastro' method='post' id='frmFormularios2'>
						<div id='login-mpress-container' Style='float:right; border:0px solid silver;margin-bottom:10px;margin-top:5px;border-radius:5px;padding:0px;'>
							<h2> Fa&ccedil;a seu cadastro </h2>
							".carregarFormularioCadastro('')."
						</div>
					</form>
				</div>";


	}

	function homeAreaRestrita(){
		global $wpdb;
		$dadosIntegracao = get_option('integracao');
		echo "	<div Style='float:left;width:100%;'><input type='hidden' name='solicitante-id' id='solicitante-id' class='frm-orc' value='".$_SESSION['curl']['dadoslogin']['userID']."'/></div>";
		echo "	<div class='btn-menu btn-topo-otica' destino='div-vinculos' Style='float:left;'>Colaboradores</div>";
		echo "	<div class='btn-menu btn-topo-otica' destino='div-orcamentos' Style='float:left;margin-left:5px;'>Or&ccedil;amentos</div>";
		echo "	<div class='btn-menu btn-topo-otica' destino='div-chamados' Style='float:left; margin-left:5px;'>Andamento de Pedidos</div>";
		echo "	<!--<div class='btn-menu btn-topo-otica' destino='div-laboratorio' Style='float:left; margin-left:5px;'>Outras solicita&ccedil;&otilde;s</div>-->";
		echo "	<div Style='float:left;width:100%;'>&nbsp;</div>";
		echo "	<div class='div-bloco esconde div-cadastro'>".carregarFormularioCadastro($_SESSION['curl']['dadoslogin']['userID'])."</div>";
		echo "	<div class='div-bloco div-vinculos'>".carregarVinculos($_SESSION['curl']['dadoslogin']['userID'],151)."</div>";
		echo "	<div class='div-bloco esconde div-orcamentos'>".carregarOrcamentos($_SESSION['curl']['dadoslogin']['userID'])."</div>";
		echo "	<div class='div-bloco esconde div-chamados'>".carregarChamados($_SESSION['curl']['dadoslogin']['userID'])."</div>";
		echo "	<div class='div-bloco div-bem-vindo' Style='float:left; width:100%; height: 300px; background:url(".plugin_dir_url(__FILE__)."images/boasvindas.png) top center no-repeat;'>&nbsp;</div>";
		echo "	<div class='div-novo-orcamento esconde' style='float:left;width:950px'></div>";
	}

	function niveisArray($nivel){
		for($i=1;$i<=$nivel;$i++)
			$espaco .= "&nbsp;&nbsp;&nbsp;";
		return $espaco;
	}


	function converteData($date){
		$data = implode("/",array_reverse(explode("-",substr($date, 0,10))));
		$hora = substr($date, 11,5);
		return "$data $hora";
	}

	function carregarFormularioCadastro($cadastroID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");

		if ($cadastroID==""){
			$selTipo["25"] = " checked ";
			$escondePF = "esconde";
			$sexoSel['M'] = 'selected';
		}
		else{
			$sql = "SELECT Cadastro_ID, Grupo_ID, Centro_Custo_ID, Codigo, Tipo_Pessoa, Tipo_Cadastro, Nome, Nome_Fantasia,
										Senha, Data_Nascimento, Sexo, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual,
										Tipo_Vinculo, Email, Foto, Ultimo_Login, Tabela_Preco_ID, Areas_Atuacoes, Regional_ID, Situacao_ID,
										(SELECT Telefone FROM cadastros_telefones where Cadastro_ID = '$cadastroID' and Situacao_ID = 1 and Tipo_Telefone_ID = '27' limit 1) as Telefone_Comercial,
										(SELECT Telefone FROM cadastros_telefones where Cadastro_ID = '$cadastroID' and Situacao_ID = 1 and Tipo_Telefone_ID = '28' limit 1) as Telefone_Celular,
										(SELECT Telefone FROM cadastros_telefones where Cadastro_ID = '$cadastroID' and Situacao_ID = 1 and Tipo_Telefone_ID = '29' limit 1) as Telefone_Residencial
									FROM cadastros_dados
									WHERE Cadastro_ID = '$cadastroID'";
			//echo $sql;
			$resultSet = mysql_query($sql);
			if($rs = mysql_fetch_array($resultSet)){
				$selTipo[$rs['Tipo_Pessoa']] = " checked ";
				$nome = $rs['Nome'];
				$apelido = $rs['Nome_Fantasia'];
				$cpfCnpj = $rs['Cpf_Cnpj'];
				$rg = $rs['RG'];
				//$dataNascimento = converteData($rs['Data_Nascimento']);
				$selSexo[$rs['Sexo']] = 'selected';
				$inscricaoMunicipal = $rs['Inscricao_Municipal'];
				$inscricaoEstadual = $rs['Inscricao_Estadual'];
				$selIM['ISENTO'] = " checked ";
				$selIE['ISENTO'] = " checked ";
				if ($rs['Tipo_Pessoa'] == '24') $escondePJ = "esconde"; else $escondePF = "esconde";
				$comercial = $rs['Telefone_Comercial'];
				$celular = $rs['Telefone_Celular'];
				$residencial = $rs['Telefone_Residencial'];
				$email = $rs['Email'];
				$senha = $rs['Senha'];
			}
			$blocoAcoes = "<div style='float:right; width:20%; margin-top:10px;'>
							<input type='button' class='salvar-dados' value='Atualizar' style='float:right;'>
						</div>";
		}

		$h .= "		<div style='float:left; width:100%;'>
						<div style='float:left; width:100%; margin-top:0px;' align='left'><b>&nbsp;</b></div>
						<div style='float:left; width:80%;'>
							<p style='margin:1px; padding:0px'>
								<input type='radio' name='tipo-pessoa' class='tipo-pessoa' id='tipo-pessoa-j' value='25' ".$selTipo["25"]."/><label for='tipo-pessoa-j'>Pessoa Jur&iacute;dica</label>
								&nbsp;&nbsp;&nbsp;
								<input type='radio' name='tipo-pessoa' class='tipo-pessoa esconde' id='tipo-pessoa-f' value='24' ".$selTipo["24"]."/><label for='tipo-pessoa-f' class='esconde'>Pessoa F&iacute;sica</label>
							</p>
						</div>
						$blocoAcoes
					</div>
					<div class='div-pf $escondePF' style='float:left; width:97%; margin-top:15px;'>
						<div style='float:left; width:50%; margin-top:0px;'>
							<p>Nome Completo</p>
							<p><input type='text' id='nome-completo' name='nome-completo' class='' maxlength='250' value='$nome'  Style='width:95%'/></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Apelido</p>
							<p><input type='text' id='apelido' name='apelido' maxlength='250' value='$apelido'  Style='width:100%'/></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>CPF</p>
							<p><input type='text' id='cpf' name='cpf' maxlength='14' class='mascara-cpf' excessao='$cadastroID' value='$cpfCnpj' Style='width:95%'/></p>
						</div>
						<div style='float:left; width:25%;'>
							<p>RG</p>
							<p><input type='text' id='rg' name='rg' maxlength='20' value='$rg' Style='width:90%'/></p>
						</div>
						<div style='float:left; width:25%;'>
							<p>Sexo</p>
							<p>
								<input type='radio' id='sexo-M' name='sexo' value='M' ".$selSexo['M']."/><label style='font-size:xx-small;' for='sexo-M'>Masculino</label>
								<input type='radio' id='sexo-F' name='sexo' value='F' ".$selSexo['F']."/><label style='font-size:xx-small;' for='sexo-F'>Feminino</label>
							</p>
						</div>
					</div>
					<div class='div-pj $escondePJ' style='float:left; width:97%; margin-top:15px;'>
						<div style='float:left; width:50%;'>
							<p>Raz&atilde;o social</p>
							<p><input type='text' id='razao-social' name='razao-social' value='$nome' class='required' Style='width:95%'/></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Nome Fantasia</p>
							<p><input type='text' id='nome-fantasia' name='nome-fantasia' value='$apelido' class='required'  Style='width:100%'/></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>CNPJ</p>
							<p><input type='text' id='cnpj'  name='cnpj' maxlength='18' class='mascara-cnpj valida-cadastro-documento required' excessao='$cadastroID;' value='$cpfCnpj' Style='width:95%'/>
						</div>
						<div style='float:left; width:25%;'>
							<div style='float:left; width:80%;'>
								<p>Inscri&ccedil;&atilde;o Estadual</p>
								<p><input type='text' id='inscricao-estadual' name='inscricao-estadual' maxlength='15' Style='width:90%' value='$inscricaoEstadual' /></p>
							</div>
							<div style='float:left; width:20%; margin-top:10px;'>
								<p style='font-size:xx-small;'>Isento</p>
								<p><input type='checkbox' id='ie-isento' name='ie-isento' ".$selIE[$inscricaoEstadual]."/></p>
							</div>
						</div>
						<div style='float:left; width:25%;'>
							<div style='float:left; width:80%;'>
								<p>Inscri&ccedil;&atilde;o Municipal</p>
								<p><input type='text' id='inscricao-municipal' name='inscricao-municipal' maxlength='15' Style='width:90%' value='$inscricaoMunicipal' /></p>
							</div>
							<div style='float:left; width:20%; margin-top:10px;'>
								<p style='font-size:xx-small;' >Isento</p>
								<p><input type='checkbox' id='im-isento' name='im-isento' ".$selIM[$inscricaoEstadual]."/></p>
							</div>
						</div>
					</div>";
		$h .= "		<div class='div-telefones' style='width:97%;'>
						<div style='width:100%;float:left;margin-bottom:5px;' align='left'><b>Telefones</b></div>
						<div style='float:left; width:25%'>
							<p>Comercial</p>
							<p><input type='text' id='telefone-comercial' name='telefone-comercial' value='$comercial' class='formata-telefone required' maxlength='15' Style='width:90%'/></p>
						</div>
						<div style='float:left; width:25%; margin-top:0px;'>
							<p>Celular</p>
							<p><input type='text' id='telefone-celular' name='telefone-celular' value='$celular' class='formata-telefone' maxlength='15' Style='width:90%'/></p>
						</div>
						<div style='float:left; width:25%'>
							<p>Residencial</p>
							<p><input type='text' id='telefone-residencial' name='telefone-residencial' value='$residencial' class='formata-telefone' maxlength='15' Style='width:90%'/></p>
						</div>
					</div>";
		$h .= carregarEnderecos($cadastroID);
		if ($cadastroID==""){
			$h .= "	<div class='div-email-login' style='float:left; width:97%;'>
						<div style='width:100%;float:left; margin-bottom:5px;' align='left'><b>Dados Acesso</b></div>
						<div style='float:left; width:50%; margin-top:0px;'>
							<p>Email</p>
							<p><input type='text' id='cadastro-email' name='cadastro-email'  class='valida-email required' value='$email'  Style='width:95%' /></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Confirme seu Email</p>
							<p><input type='text' id='cadastro-email-confirma' name='cadastro-email-confirma'  class='valida-email required' value=''  Style='width:100%' /></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Senha</p>
							<p><input type='password' id='cadastro-senha' name='cadastro-senha'  class='required' value='$senha'  Style='width:95%' /></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Confirme sua Senha</p>
							<p><input type='password' id='cadastro-senha-confirma' name='cadastro-senha-confirma'  class='required' value=''  Style='width:100%' /></p>
						</div>
					</div>";
		}
		else{
			$h .= "	<div class='div-email-login' style='float:left; width:97%;'>
						<div style='width:100%;float:left; margin-bottom:5px;' align='left'><b>Dados Acesso</b></div>
						<div style='float:left; width:50%; margin-top:0px;'>
							<p>Email</p>
							<p><input type='text' id='cadastro-email' name='cadastro-email'  class='valida-email' value='$email'  Style='width:95%' /></p>
						</div>
						<div style='float:left; width:50%;'>
							<p>Senha</p>
							<p><input type='password' id='cadastro-senha' name='cadastro-senha'  class='required' value='$senha'  Style='width:100%' /></p>
						</div>
					</div>";
		}

		if ($cadastroID==""){
			$h .= "	<div style='float:right; width:100%; margin-top:0px;'>
						<input type='button' class='submeter-cadastro' value='Finalizar Cadastro' style='float:right; width:200px;'/>
					</div>";
		}

		return $h;
	}


	function carregarEnderecos($cadastroID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");

		if ($cadastroID!=""){
			$sql = "SELECT Cadastro_Endereco_ID, Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Situacao_ID, Usuario_Cadastro_ID
						FROM cadastros_enderecos
						where Cadastro_ID = '$cadastroID'
						and Situacao_ID = 1 and Tipo_Endereco_ID = '26' ORDER BY Cadastro_Endereco_ID  limit 1";
			//echo $sql;
			$resultSet = mysql_query($sql);
			if($rs = mysql_fetch_array($resultSet)){
				$cadastroEnderecoID = $rs['Cadastro_Endereco_ID'];
				$tipoEnderecoID = $rs['Tipo_Endereco_ID'];
				$logradouro = $rs['Logradouro'];
				$cep = $rs['CEP'];
				$numero = $rs['Numero'];
				$bairro = $rs['Bairro'];
				$cidade = $rs['Cidade'];
				$uf = $rs['UF'];
				$referencia = $rs['Referencia'];
				$complemento = $rs['Complemento'];
			}
		}

		$h = "	<div class='div-enderecos' style='width:97%;'>
					<div style='width:100%;float:left; margin-bottom:5px;' align='left'><b>Endere&ccedil;o Principal</b></div>
					<input type='hidden' id='cadastro-endereco-id' name='cadastro-endereco-id' value='$cadastroEnderecoID'>
					<input type='hidden' id='tipo-endereco-id' name='tipo-endereco-id' value='$tipoEnderecoID'>
					<div style='float:left; width:20%;'>
						<p>CEP</p>
						<p><input type='text' class='mascara-cep required' id='cep-endereco' name='cep-endereco' maxlength='9' value='$cep' style='width:88%'/></p>
					</div>
					<div style='float:left; width:70%;'>
						<p>Logradouro</p>
						<p><input type='text' class='required' id='logradouro-endereco' name='logradouro-endereco' maxlength='200' value='$logradouro' style='width:96.7%'/></p>
					</div>
					<div style='float:left; width:10%;'>
						<p>N&uacute;mero</p>
						<p><input type='text' class='required' id='numero-endereco' name='numero-endereco'  maxlength='20' value='$numero' style='width:100%'/></p>
					</div>
					<div style='float:left; width:20%;'>
						<p>Complemento</p>
						<p><input type='text' id='complemento-endereco' name='complemento-endereco' maxlength='100' value='$complemento' style='width:88%'/></p>
					</div>
					<div style='float:left; width:25%;'>
						<p>Bairro</p>
						<p><input type='text' class='required' id='bairro-endereco' name='bairro-endereco' maxlength='50' value='$bairro' style='width:90%'/></p>
					</div>
					<div style='float:left; width:25%;'>
						<p>Cidade</p>
						<p><input type='text' class='required' id='cidade-endereco' name='cidade-endereco'  maxlength='50'  value='$cidade' style='width:90%'/></p>
					</div>
					<div style='float:left; width:10%'>
						<p>UF</p>
						<p><input type='text'  id='uf-endereco' name='uf-endereco'  maxlength='2' class='required' value='$uf' style='width:75%'/></p>
					</div>
					<div style='float:left; width:20%;'>
						<p>Refer&ecirc;ncia</p>
						<p><input type='text' id='referencia-endereco' name='referencia-endereco' value='$referencia' style='width:100%'/></p>
					</div>
				</div>";
		return $h;
	}

	function carregarVinculos($cadastroID, $tipoVinculoID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");

		$sql = "SELECT cd.Cadastro_ID AS Cadastro_ID, tp.Descr_Tipo AS Tipo_Pessoa, Nome, Nome_Fantasia, Email, Data_Nascimento,
					 Cpf_Cnpj, Inscricao_Municipal, Inscricao_Estadual, Codigo,
					 COALESCE(cf.Telefone,'') AS Telefone, cd.Observacao, cd.Email AS Email, cv.Cadastro_Vinculo_ID AS Parametro,
					 cd.Foto
					FROM cadastros_dados cd
					inner join cadastros_vinculos cv on cv.Cadastro_Filho_ID = cd.Cadastro_ID and cv.Tipo_Vinculo_ID = '$tipoVinculoID' and cv.Cadastro_ID = '$cadastroID'
					LEFT JOIN cadastros_telefones cf ON cf.Cadastro_ID = cd.Cadastro_ID AND cf.Situacao_ID = 1
					LEFT JOIN tipo tp ON Tipo_ID = Tipo_Pessoa AND tp.Tipo_Grupo_ID = 8
					LEFT JOIN tipo tc ON tc.Tipo_ID = Tipo_Cadastro AND tc.Tipo_Grupo_ID = 9
					WHERE cd.Situacao_ID = 1 AND cv.Situacao_ID = 1
					ORDER BY Nome";
		//echo $sql;
		$resultSet = mysql_query($sql);
		$i = 0;
		while($rsA = mysql_fetch_array($resultSet)){
			$i++;
			$colaboradorID = $rsA['Cadastro_ID'];
			if ($rsA['Foto']=="")
				$foto = plugin_dir_url(__FILE__)."/images/imagem-usuario.jpg";
			else
				$foto = $dadosIntegracao['url']."/uploads/".$rsA['Foto'];
			$dados[colunas][conteudo][$i][1] = "<p align='center' style='margin: 0px auto; display: block;'><img src='$foto' height='60' align='center' /></p>";
			$dados[colunas][conteudo][$i][2] = $rsA['Nome']."		<input type='hidden' value='".$rsA['Nome']."' id='colaborador-nome-$colaboradorID'>";
			$dados[colunas][conteudo][$i][3] = $rsA['Email']."	<input type='hidden' value='".$rsA['Email']."' id='colaborador-email-$colaboradorID'>";
			$dados[colunas][conteudo][$i][4] = $rsA['Cpf_Cnpj']."	<input type='hidden' value='".$rsA['Cpf_Cnpj']."' id='colaborador-cpf-$colaboradorID'>";
			$dados[colunas][conteudo][$i][5] = $rsA['Telefone']."	<input type='hidden' value='".$rsA['Telefone']."' id='colaborador-telefone-$colaboradorID'>
														<input type='hidden' value='".$rsA['Observacao']."' id='colaborador-observacoes-$colaboradorID'>";
			$dados[colunas][conteudo][$i][6] = "<input type='button' class='incluir-vinculo' value='Alterar' colaborador-id='$colaboradorID' style='background-color: #f4ba2f; font-size:9px; width:60px; height:20px; margin:0; padding:0;'/>";
		}
		$dados[colunas][titulo][1] = "";
		$dados[colunas][titulo][2] = "Colaborador";
		$dados[colunas][titulo][3] = "Email";
		$dados[colunas][titulo][4] = "CPF";
		$dados[colunas][titulo][5] = "Telefone";
		$dados[colunas][tamanho][1] = "width='50px'";
		$dados[colunas][tamanho][1] = "width='30%'";
		$h .= "	<div style='float:right;text-align:right;' class='div-vinculos-lista'><input type='button' class='incluir-vinculo btn-geral-otica' value='Incluir Colaborador' colaborador-id=''></div>";
		if($i>=1){
			$h .= "	<div style='float:left; margin-top:5px; width:100%' class='div-vinculos-lista'>";
			$h .= geraTabela("100%", "6", $dados, "border:0px;", "tabela-lista-colaboradores", 2, 2, 24, "","return");
			$h .= "	</div>";
		}
		else{
			$h .= "	<p align='left' Style='float:left;' class='div-vinculos-lista'> - Nenhum Colaborador Cadastrado</p>";
		}
		$h .= "	<div style='float:left; width:100%;' id='form-novo-colaborador' class='div-novo-vinculo'>".exibirVinculo($empresaID,'')."
					<input type='button' class='btn-incluir-novo-vinculo btn-geral-otica' value='Incluir Colaborador' style='float:right; margin-left:5px;'>
					<input type='button' class='btn-cancelar-novo-vinculo btn-geral-otica' value='Cancelar' style='float:right; margin-left:5px;'>
				</div>
				<input type='hidden' name='colaborador-id' class='div-vinculos' id='colaborador-id' value=''>";
		return $h;
	}

	function exibirVinculo($empresaID, $cadastroID){
		if ($cadastroID!=""){
			$sql = "SELECT cd.Cadastro_ID AS Cadastro_ID, tp.Descr_Tipo AS Tipo_Pessoa, Nome, Nome_Fantasia, Email, Data_Nascimento,
							 Cpf_Cnpj, Inscricao_Municipal, Inscricao_Estadual, Codigo,
							 COALESCE(cf.Telefone,'') AS Telefone, cf.Observacao, cd.Email AS Email
							FROM cadastros_dados cd
							LEFT JOIN cadastros_telefones cf ON cf.Cadastro_ID = cd.Cadastro_ID AND cf.Situacao_ID = 1
							LEFT JOIN tipo tp ON Tipo_ID = Tipo_Pessoa AND tp.Tipo_Grupo_ID = 8
							LEFT JOIN tipo tc ON tc.Tipo_ID = Tipo_Cadastro AND tc.Tipo_Grupo_ID = 9
							WHERE cd.Cadastro_ID = '$cadastroID'
							ORDER BY Nome";
			//echo $sql;
			$resultSet = mysql_query($sql);
			if($rs = mysql_fetch_array($resultSet)){
				if ($rs['Foto']=="")
					$foto = plugin_dir_url(__FILE__)."/images/imagem-usuario.jpg";
				else
					$foto = $dadosIntegracao['url']."/uploads/".$rs['Foto'];

				$codigo = $rs['Codigo'];
				$cadastroID = $rs['Cadastro_ID'];
				$nome = $rs['Nome'];
				$cargo = $rs['Cargo'];
				$email = $rs['Email'];
				$telefone = $rs['Telefone'];
				$cpfCnpj = $rs['Cpf_Cnpj'];
			}
		}
		else{
			$i = 0;
		}  
		$caminhoMedidas = plugin_dir_url(__FILE__)."dados-medidas.php?cadastroID=".$cadastroID;
		$caminhoAnexos = plugin_dir_url(__FILE__)."dados-anexos.php?cadastroID=".$cadastroID;
		$tamanho = "90%";

		$h .= "	<fieldset class='div-vinculos' style='width:97%;'>
					<!--
					-	Nome do Colaborador
					-	Turno (primeiro, segundo ou terceiro)
					-	Número de matrícula/cadastro na empresa
					-	Função/ Seção na empresa
					-	Ramal da seção (caso haja)
					-	Centro de custo (caso haja)
					-->
					<input type='hidden' name='cadastro-vinculo[]' class='cadastro-vinculo frm-colab' value='$empresaID'/>
					<div style='float:left; width:10%;'>
						<p align='center' style='margin: 0px auto; display: block;'>
							<img src='$foto' height='60' align='center'/>
						</p>
						<div style='float:left; width:90%;'>
							<input type='button' id='botao-medida-colab' class='botao-medida-colab btn-fancybox btn-geral-otica fancybox' rel='fancybox' value='Medidas' href='$caminhoMedidas' style='border-radius:5px;width:100%; height:20px; margin:2px; text-align:center; padding:0px;'/>
							<input type='button' class='btn-fancybox btn-geral-otica' value='Receitas' href='$caminhoAnexos' style='border-radius:5px;width:100%; height:20px; margin:2px; text-align:center; padding:0px;'/>
						</div>
					</div>
					<div style='float:left; width:$tamanho; heigth:40px;'>
						<div style='float:left; width:50%'>
							<p>Nome</p>
							<p><input type='text' name='nome-vinculo[]' id='nome-vinculo-$i' class='required frm-colab' value='".$nome."' style='width:95%'/></p>
						</div>
						<div style='float:left; width:25%'>
							<p>N&ordm; de Registro ou Matricula</p>
							<p><input type='text' name='codigo-vinculo[]' id='codigo-vinculo-$i' class='required frm-colab' value='".$codigo."' style='width:90%'/></p>
						</div>
						<!--
						<div style='float:left; width:25%'>
							<p>Cargo</p>
							<p><input type='text' name='cargo-vinculo[]' value='".$cargo."' class='frm-colab' style='width:97%'/></p>
						</div>
						-->
						<div style='float:left; width:20%'>
							<p>Telefone do Setor</p>
							<p><input type='text' name='telefone-vinculo[]' id='telefone-vinculo-$i' class='formata-telefone frm-colab required' value='".$telefone."' style='width:90%'/></p>
						</div>
						<div style='float:left; width:05%'>
							<p>Ramal</p>
							<p><input type='text' name='ramal[]' id='ramal-$i' class='frm-colab' value='".$ramal."' style='width:100%'/></p>
						</div>

						<div style='float:left; width:25%'>
							<p>Email</p>
							<p><input type='text' name='email-vinculo[]' id='email-vinculo-$i' class='valida-email frm-colab' value='".$email."' style='width:90%'/></p>
						</div>
						<!--
						<div style='float:left; width:25%'>
							<p>CPF</p>
							<p><input type='text' name='cpf-vinculo[]' id='cpf-vinculo-$i' value='".$cpfCnpj."'  maxlength='14' class='frm-colab required mascara-numero' style='width:90%'/></p>
						</div>
						-->
						<div style='float:left; width:50%'>
							<p>Observa&ccedil;&atilde;o</p>
							<p><input type='text' name='observacoes-vinculo[]' id='observacoes-vinculo-$i' class='frm-colab' value='".$observacao."' style='width:100%'/></p>
						</div>
						<div style='float:left; width:50%'>
							<p>Centro de Custo</p>
							<p><input type='text' name='centro-custo-vinculo[]' id='observacoes-vinculo-$i' class='frm-colab' value='".$observacao."' style='width:100%'/></p>
						</div>
						<div style='float:left; width:25%'>
							<p>Turno</p>
							<p><select name='turno[]' id='turno-$i' class='required' style='width:100%'><option></option><option>Manhã</option><option>Tarde</option><option>Noite</option></select></p>
						</div>
						<div style='float:left; width:15%'>
							<p>Hor&aacute;rio inicio</p>
							<p><input type='text' style='width:50%'/></p>
						</div>
						<div style='float:left; width:10%'>
							<p>Hor&aacute;rio fim</p>
							<p><input type='text' style='width:50%'/></p>
						</div>
					</div>
			</fieldset>";
			$h .= "<script>alert(2); jQuery(document).trigger('gform_post_render'); alert(2);</script>";
			
			return $h;
	}

	function carregarOrcamentos($cadastroID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");

		$sql = "SELECT Workflow_ID, Empresa_ID, Solicitante_ID, coalesce(r.Nome,'N/A') as Representante, ow.Situacao_ID, coalesce(s.Descr_Tipo,'Pendente de Finaliza&ccedil;&atilde;o') as Situacao, Titulo, DATE_FORMAT(Data_Abertura,'%d/%m/%Y %H:%i') as Data_Abertura
					FROM orcamentos_workflows ow
					left join cadastros_dados r on r.Cadastro_ID = ow.Representante_ID
					left join tipo s on s.Tipo_ID = ow.Situacao_ID
					where Solicitante_ID = '$cadastroID' order by Data_Abertura desc";
		//echo $sql;
		$resultSet = mysql_query($sql);
		$i = 0;
		while($rs = mysql_fetch_array($resultSet)){
			$i++;
			$dados[colunas][conteudo][$i][1] = STR_PAD($rs['Workflow_ID'], 6 , "0","STR_PAD_LEFT");
			$dados[colunas][conteudo][$i][2] = $rs['Situacao'];
			$dados[colunas][conteudo][$i][3] = $rs['Titulo'];
			$dados[colunas][conteudo][$i][4] = $rs['Data_Abertura'];
			if ($rs['Situacao_ID']==-1)
				$dados[colunas][conteudo][$i][5] = "<input type='button' class='incluir-alterar-orcamento' value='Continuar' orcamento-id='".$rs['Workflow_ID']."' style='background-color: #f4ba2f; font-size:9px; width:60px; height:20px; margin:0; padding:0;'/>";
			else
				$dados[colunas][conteudo][$i][5] = "<a class='fancybox fancybox.iframe' href='".plugin_dir_url(__FILE__)."dados-pedido.php?workflowID=".$rs['Workflow_ID']."'>
														<input type='button' id='btn-visualizar-pedido' value='Visualizar' orcamento-id='".$rs['Workflow_ID']."' style='background-color: #f4ba2f; font-size:9px; width:60px; height:20px; margin:0; padding:0;'/>
													</a>";
		}
		$dados[colunas][titulo][1] = "N&ordm; Or&ccedil;amento";
		$dados[colunas][titulo][2] = "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][3] = "Titulo";
		$dados[colunas][titulo][4] = "Data Abertura";
		$dados[colunas][tamanho][5] = "width='50px'";
		$h .= "	<div style='float:right;text-align:right;'><input type='button' class='incluir-alterar-orcamento btn-geral-otica' value='Solicitar Novo Or&ccedil;amento' orcamento-id=''></div>";
		if($i>=1){
			$h .= "<p style='float:left; margin-top:5px; width:100%'>";
			$h .= geraTabela("99.4%", "5", $dados, "border:0px;", "tabela-lista-orcamentos", 2, 2, 24, "","return");
			$h .= "</p>";
		}
		else{
			$h .= "	<p align='left' Style='float:left;'> - Nenhum Or&ccedil;amento Cadastrado</p>";
		}
		$h .= "<input type='hidden' name='orcamento-id' class='frm-orc' id='orcamento-id' value=''/>";
		return $h;
	}

	function incluirAlterarOrcamento($orcamentoID){
		$sql = "select concat(pd.Nome,' ', pv.Descricao) as Produto, opp.Quantidade, co.Nome as Colaborador, ow.Workflow_ID AS Workflow_ID, op.Proposta_ID AS Proposta_ID
					from orcamentos_workflows ow
					inner join orcamentos_propostas op on op.Workflow_ID = ow.Workflow_ID
					inner join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID
					inner join cadastros_dados co on co.Cadastro_ID = opp.Cliente_Final_ID
					left join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
					left join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
					where ow.Situacao_ID = -1
					and op.Workflow_ID = '$orcamentoID'
					order by co.Nome";
		//echo $sql;
		$resultSet = mysql_query($sql);
		$i = 0;
		while($rs = mysql_fetch_array($resultSet)){
			$i++;
			$dados[colunas][conteudo][$i][1] = utf8_encode($rs['Produto']);
			$dados[colunas][conteudo][$i][2] = utf8_encode($rs['Colaborador']);
			$dados[colunas][conteudo][$i][3] = $rs['Quantidade'];

			$workflowID = $rs['Workflow_ID'];
			$propostaID = $rs['Proposta_ID'];
		}
		if ($i>0){
			$dados[colunas][titulo][1] = "Produto";
			$dados[colunas][titulo][2] = "Colaborador";
			$dados[colunas][titulo][3] = "Quantidade";
			$blocoOrcamentoEmAndamento .= "<div style='float:left; width:100%; margin-top:5px;'><p align='center'>Or&ccedil;amento em andamento</p></div>";
			$blocoOrcamentoEmAndamento .= "<div style='float:left; width:100%; margin-top:5px;'>".geraTabela("99.4%", "2", $dados, null, "tabela-lista-orcamentos", 2, 2, 24, "","return")."</div>
										<input type='hidden' name='workflow-id' id='workflow-id' class='frm-orc' value='$orcamentoID'/>
										<input type='hidden' name='proposta-id' id='proposta-id' class='frm-orc' value='$propostaID'/>";

		}
		$h .= "	<div style='float:left; width:100%;' id='form-orcamento' class='orcamento-editar'>
					<div style='float:left; width:50%;'>
						<p><b>Selecione o Colaborador</b></p>
						<p><select name='seleciona-colaborador' id='seleciona-colaborador' class='seleciona-colaborador frm-orc required'><option></option>".optionValueColaboradores($_SESSION['curl']['dadoslogin']['userID'],151)."</select></p>
					</div>
					<div style='float:left; width:10%;' class='detalhes-colaborador esconde'>
						<p>&nbsp;</p>
						<p>
							<a class='fancybox fancybox.iframe' href='".plugin_dir_url(__FILE__)."dados-medidas.php'>
							<input type='button' value='Medidas' class='btn-geral-otica' style=' width:90px; border-radius:5px; margin-left:5px; text-align:center;' style='float:right;'/>
							</a>
						</p>
					</div>
					<div style='float:left; width:10%;' class='detalhes-colaborador esconde'>
						<p>&nbsp;</p>
						<p>
							<a class='fancybox fancybox.iframe' href='".plugin_dir_url(__FILE__)."dados-anexos.php'>
								<input type='button' value='Receita' class='btn-geral-otica' style=' width:90px; border-radius:5px; margin-left:5px; text-align:center;' style='float:right;'/>
							</a>
						</p>
					</div>
					<div style='float:right; width:25%;'>
						<p>&nbsp;</p>
						<p>
							<div style='float:left; width:100%;'>
								<input type='button' class='btn-continuar-orcamento btn-geral-otica' value='Adicionar e Continuar Or&ccedil;amento' style='float:right; margin-left:5px;'>
							</div>
							<div style='float:left; width:100%; margin-top:5px;'>
								<input type='button' class='btn-finalizar-orcamento btn-geral-otica' value='Finalizar Or&ccedil;amento' style='float:right; margin-left:5px;'>
								<input type='button' class='cancelar-incluir-orcamento btn-geral-otica' value='Cancelar' style='float:right; margin-left:5px;'>
							</div>
						</p>
					</div>
					<div style='float:left; width:50%; margin-top:5px;'>
						<div class=''>
							<p>".listarProdutos()."</p>
						</div>
						&nbsp;
					</div>
					<div style='float:left; width:20%; margin-top:5px;' class='dados-medida-receita esconde'></div>
					$blocoOrcamentoEmAndamento
				</div>";
		return $h;
	}




	function listarProdutos(){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");
		$sql = "select pv.Produto_Variacao_ID, pd.Nome, pd.Tipo_Produto from produtos_dados pd
					inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
							where pv.Produto_Variacao_ID < 0";
		//echo $sql;
		$resultSet = mysql_query($sql);
		$checked = 'checked';
		while($rs = mysql_fetch_array($resultSet)){
			$h .= "	<div style='float:left; width:33%; margin-top:10px; height:100px.'>
						<p><input type='radio' $checked name='produto[]' class='frm-orc produto-selecionado' tipo-produto='".$rs['Tipo_Produto']."' value='".$rs['Produto_Variacao_ID']."'/>&nbsp;<b>".utf8_encode($rs['Nome'])."</b></p>
					</div>";
			$checked = "";
		}
		return $h;

	}
	function listarVariacoesProduto($produtoID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");
		$sql = "select pd.Produto_ID as Produto_ID, concat(pd.Nome, ' ' , pv.Descricao) as Produto,
				pd.Data_Cadastro as Data_Cadastro_Produto, pd.Codigo as Codigo, pv.Codigo as Codigo_Variacao,
				pv.Produto_Variacao_ID, pv.Codigo as Codigo_Variacao, pv.Valor_Venda, mav.Nome_Arquivo as Nome_Arquivo_Variacao, pv.Data_Cadastro as Data_Cadastro_Variacao
			from produtos_dados pd
			inner join produtos_variacoes pv on pd.Produto_ID = pv.Produto_ID
			left join modulos_anexos mav on mav.Anexo_ID = pv.Imagem_ID
			left join tipo tp on tp.Tipo_ID = pd.Tipo_Produto and tp.Tipo_Grupo_ID = 13
			where pd.Situacao_ID = 1 and pv.Situacao_ID = 1 and pd.Produto_ID = '$produtoID'";
		//echo "<br><br><br>".$sql;
		$resultSet = mysql_query($sql);
		while($rs = mysql_fetch_array($resultSet)){
			$foto = $dadosIntegracao['url']."/uploads/".$rs['Nome_Arquivo_Variacao'];
			$produto = $rs['Produto'];
			$h .= "	<div style='float:left; width:20%; height:100px.'>
						<img src='$foto' width='213.75' border='0'>
						<p><input type='radio' name='produto[$produtoID]' class='frm-orc' value='".$rs['Produto_Variacao_ID']."'/>&nbsp; &nbsp; $produto</p>
					</div>";
		}
		return $h;
	}

	function optionValueColaboradores($cadastroID, $tipoVinculoID){
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");
		$sql = "SELECT cd.Cadastro_ID AS Cadastro_ID, tp.Descr_Tipo AS Tipo_Pessoa, Nome, Nome_Fantasia, Email, Data_Nascimento,
					 Cpf_Cnpj, Inscricao_Municipal, Inscricao_Estadual, Codigo,
					 COALESCE(cf.Telefone,'') AS Telefone, cf.Observacao, cd.Email AS Email, cv.Cadastro_Vinculo_ID AS Parametro
					FROM cadastros_dados cd
					inner join cadastros_vinculos cv on cv.Cadastro_Filho_ID = cd.Cadastro_ID and cv.Tipo_Vinculo_ID = '$tipoVinculoID' and cv.Cadastro_ID = '$cadastroID'
					LEFT JOIN cadastros_telefones cf ON cf.Cadastro_ID = cd.Cadastro_ID AND cf.Situacao_ID = 1
					LEFT JOIN tipo tp ON Tipo_ID = Tipo_Pessoa AND tp.Tipo_Grupo_ID = 8
					LEFT JOIN tipo tc ON tc.Tipo_ID = Tipo_Cadastro AND tc.Tipo_Grupo_ID = 9
					WHERE cd.Situacao_ID = 1 AND cv.Situacao_ID = 1
					ORDER BY Nome";
		$resultSet = mysql_query($sql);
		while($rs = mysql_fetch_array($resultSet)){
			$h .= "<option value='".$rs['Cadastro_ID']."'>".$rs['Nome']."</option>";
		}
		return $h;
	}


	function carregarChamados($cadastroID){
		$h .= "<div style='float:left; width:100%;'>";
		$dadosIntegracao = get_option('integracao');
		$sistema = mysql_connect($dadosIntegracao[host],$dadosIntegracao[user],$dadosIntegracao[pass])or die("<font color='red'>Nao foi possivel conectar ao banco de dados.</font>");
		mysql_select_db($dadosIntegracao[db]) or die("<font color='red'>Nao foi possivel selecionar o banco de dados.</font>");
		$id->ID_Sistema = $_SESSION['curl']['dadoslogin']['userID'];
		$h .= "<div id='chamados-cliente-container' style='float:left;width:100%;min-height:350px;'>";
		$chamados = mysql_query("SELECT cw.Workflow_ID, tw.Descr_Tipo AS Tipo_Chamado, cw.Titulo, t.Descr_Tipo AS Situacao, cf.Situacao_ID AS Situacao_ID,
								DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y %H:%i') AS Data_Abertura, DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') AS Data_Finalizado,
								DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y %H:%i') AS Data_Interacao
								FROM chamados_workflows cw
								INNER JOIN cadastros_dados cd1 ON cd1.Cadastro_ID = cw.Solicitante_ID
								LEFT JOIN cadastros_dados cd2 ON cd2.Cadastro_ID = cw.Prestador_ID
								LEFT JOIN tipo tw ON tw.Tipo_ID = cw.Tipo_WorkFlow_ID AND tw.Tipo_Grupo_ID = 19
								LEFT JOIN chamados_follows cf ON cw.Workflow_ID = cf.Workflow_ID AND cf.Follow_ID = (
								SELECT MAX(cfaux.Follow_ID)
								FROM chamados_follows cfaux
								WHERE cf.Workflow_ID = cfaux.Workflow_ID)
								LEFT JOIN tipo t ON t.Tipo_ID = cf.Situacao_ID
								LEFT JOIN tipo p ON p.Tipo_ID = cw.Prioridade_ID
								LEFT JOIN cadastros_dados r ON r.Cadastro_ID = cw.Responsavel_ID
								LEFT JOIN modulos_acessos g ON g.Modulo_Acesso_ID = cw.Grupo_Responsavel_ID
								WHERE cw.Workflow_ID > 0 and Solicitante_ID = $id->ID_Sistema and tw.tipo_id >= 1
								ORDER BY cw.Workflow_ID DESC");
		while($row = mysql_fetch_array($chamados)){
			$i++;
			if($i%2==0) $corFundo = "Style='background-color:#f6f6f6';"; else $corFundo = "background-color:#f9f9f9;'";
			if($i==1){
				$chamadosLocalizados .= "	<div id='produto-plus-pedido' Style='margin-bottom:40px;'>
												<table width='940px' Style='margin-left:10px;font-size:13px;font-family:arial;' class='wp-list-table widefat fixed tags'>
													<tr height='25' Style='background-color:#f0f0f0';>
														<th scope='col' id='code' align='left' width='60' style=''>&nbsp;<b>ID</b></th>
														<th scope='col' id='code' align='left' style=''>&nbsp;<b>Tipo</b></th>
														<th scope='col' id='code' align='left' style=''>&nbsp;<b>Titulo</b></th>
														<th scope='col' id='code' align='left' style=''><b>Situa&ccedil;&atilde;o</b></th>
														<th scope='col' id='code' align='left' style='' width='130'><b>Data Abertura</b></th>
														<th scope='col' id='code' align='left' style='' width='120'><b>&Uacute;ltima Intera&ccedil;&atilde;o</b></th>
														<td Style='border-right:0px solid #ffffff' width='30'>&nbsp;</td>
													</tr>";
			}
			$chamadosLocalizados .= "				<tr height='25' $corFundo>
														<td align='left'>$row[Workflow_ID]</td>
														<td Style='text-align:left'>&nbsp;".utf8_encode($row[Tipo_Chamado])."</td>
														<td Style='text-align:left'>&nbsp;".utf8_encode($row[Titulo])."</td>
														<td>".utf8_encode($row[Situacao])."</td>
														<td align='left'>$row[Data_Abertura]</td>
														<td align='left'>$row[Data_Interacao]</td>
														<td class='chamado-mostra-detalhes' attr-id='$row[Workflow_ID]' Style='text-align:right;font-size:20px;'><a>+</a></td>
													</tr>
													<tr height='1' $corFundo>
														<td colspan='7' class='coluna-mostra-detalhes-chamado esconde' id='coluna-$row[Workflow_ID]' Style='text-align:right;'>";

			$follows = mysql_query("select Descricao, DATE_FORMAT(Data_cadastro,'%d/%m/%Y %H:%i') Data_cadastro, d.Nome
									from chamados_follows f
									left  join cadastros_dados d on d.Cadastro_ID = f.Usuario_Cadastro_ID
									where workflow_id = $row[Workflow_ID]
									order by Follow_ID desc");
			while($follow = mysql_fetch_array($follows))
				$chamadosLocalizados .= "							<table width='100%;' Style='margin-left:0px;border-top: dotted 1px #e3e3e3; margin-top:5px;'>
																	<tr height='27'>
																		<td align='left' Style='text-align:left;font-size:12px;' width='110px'>".$follow['Data_cadastro']."</td>
																		<td align='left' Style='text-align:left;font-size:12px;'>&nbsp;&nbsp;".utf8_encode($follow['Descricao'])."</td>
																		<td align='left' Style='text-align:left;font-size:12px;' width='160px'>&nbsp;&nbsp;".utf8_encode($follow['Nome'])."</td>
																	</tr>
																</table>";
			if(($row['Situacao_ID']!=33) && ($row['Situacao_ID']!=34))
				$chamadosLocalizados .= "						<table width='100%;' Style='margin-top:10px;'>
																<tr>
																	<td align='left' Style='text-align:left;'>
																		<form id='formulario-follow-$row[Workflow_ID]'>
																			<textarea id='descricao-follow-$row[Workflow_ID]' name='descricao-follow' style='height:60px;width:926px;margin:0px;' class='required-2'></textarea>
																			<div class='botoes-abre-follow'>
																				<div class='cadastra-follow' Style='float:right;margin-top:0px;padding:5px 10px 5px 10px;border-radius:5px;font-size:12px;cursor:pointer;border:1px solid silver;margin-bottom:10px;' attr='$row[Workflow_ID]'>Cadastrar Intera&ccedil;&atilde;o</div>
																			</div>
																			<input id='follows-$row[Workflow_ID]' name='follows' class='esconde'>
																		</form>
																	</td>
																</tr>
															</table>";

			$chamadosLocalizados .= "					</td>
													</tr>";
		}
		if($i>=1)
			$chamadosLocalizados .= "			</table>
											</div>";
		if($chamadosLocalizados=="")$chamadosLocalizados = "<span Style='font-size:13px;margin-left:10px;float:left;margin-top:8px;'><!--".$_SESSION['curl']['chamados']['config']['nome-modulo']."-->Nenhum pedido localizado</span>";
		else $chamadosLocalizados = "<span Style='font-size:14px;margin-left:10px;float:left;margin-top:8px;margin-bottom:20px;font-family:arial;'><b>Historico de ".$_SESSION['curl']['chamados']['config']['nome-modulo']."</b></span>".$chamadosLocalizados;

		//$h .= "	REFAZENDO!";

		$h .= "	<table Style='width:100%;margin-top:0px;float:left;' id='cliente-tabela-mostra-chamados'>
						<tr>
							<td align='left'>
								<div id='chamados-cadastrados-sistema'>
									<!--<div Style='float:right;margin-top:5px;padding:5px 10px 5px 10px;border-radius:5px;font-size:12px;cursor:pointer;border:1px solid silver;' id='cadastra-chamado'>Nova ".$_SESSION['curl']['chamados']['config']['nome-modulo']."</div>-->
									$chamadosLocalizados
								</div>
							</td>
						</tr>
					</table>

					<form name='form-novo-chamado' id='form-novo-chamado'>
						<table Style='width:100%;margin-top:10px;float:left;display:none;margin-bottom:40px;' id='cliente-tabela-novo-chamado'>
							<tr>
								<td align='left' Style='width:100%;text-align:left;font-family:arial;'>
									Tipo ".$_SESSION['curl']['chamados']['config']['nome-modulo']."
								</td>
							</tr>
							<tr>
								<td align='left;'>
									<select name='tipo-workflow' id='tipo-workflow' style='width:100%;padding:5px;' class='required'>
										<option value=''>Selecione</option>";
		foreach($_SESSION['curl']['chamados']['config']['tipos'] as $indice=>$tipos)
			$h .= "					<option value='$tipos[tipoID]'>".niveisArray($tipos[nivel]).UTF8_encode($tipos[descricao])."</option>";
		$h .= "					</select>
								</td>
							</tr>
							<tr>
								<td align='left' Style='text-align:left;font-family:arial;'>
									T&iacute;tulo ".$_SESSION['curl']['chamados']['config']['nome-modulo']."
								</td>
							</tr>
							<tr>
								<td>
									<input type='text' name='titulo-chamado' id='titulo-chamado' value='' style='width:100%;' class='required'>
								</td>
							</tr>
							<tr>
								<td align='left' Style='text-align:left;font-family:arial;'>
									Descri&ccedil;&atilde;o Inicial ".$_SESSION['curl']['chamados']['config']['nome-modulo']."
								</td>
							</tr>
							<tr>
								<td>
									<textarea id='descricao-follow' name='descricao-follow' style='height:60px;width:100%;' class='required'></textarea>
								</td>
							</tr>
							<tr>
								<td>
									<div id='botoes-abre'>
										<div Style='float:left;margin-top:0px;padding:5px 10px 5px 10px;border-radius:5px;font-size:12px;cursor:pointer;border:1px solid silver;' id='cancela-abre-chamado'>Cancelar</div>
										<div Style='float:right;margin-top:0px;padding:5px 10px 5px 10px;border-radius:5px;font-size:12px;cursor:pointer;border:1px solid silver;' id='abre-chamado'>Abrir ".$_SESSION['curl']['chamados']['config']['nome-modulo']."</div>
									</div>
									<div id='botoes-aguarde' Style='width:130px;float:right;margin-top:15px;display:none;'>
										<img src='".get_bloginfo('wpurl')."/wp-content/images/layout/ajax-loader.gif' style='width:90%;height:20px;margin-top:0px;margin-bottom:0px;'>
									</div>
								</td>
							</tr>
						</table>
						<input type='hidden' name='modulo' value='chamados'>
						<input type='hidden' name='metodo' value='chamadosSalvar'>
						<input type='hidden' name='integracao' value='".$dadosIntegracao['url']."'>
					</form>";
		$h .= "	</div>";
		$h .= "</div>";

		return $h;
	}


/**********************************************************************************************************************/

 	function geraTabela($largura, $colunas, $dados, $style = null, $idTabela = "tabela", $cellpadding = 2, $cellspacing = 2, $mostraRegistros, $mostraFiltro, $tipoRetorno){
		unset($_SESSION['campoVisivel']);
		$_SESSION['campoVisivel'] = "";
		//$registrosBloqueados = geraTabelaBloqueio($dados,$idTabela);
		if($mostraRegistros == "") $mostraRegistros = count($dados[colunas][conteudo]);
		if($_POST['posicao-paginador'] == "") $paginaAtual = 1; else $paginaAtual = $_POST['posicao-paginador'];
		$paginasTotais 	 = ceil(count($dados[colunas][conteudo])/$mostraRegistros);
		$registroInicial = (($mostraRegistros*$paginaAtual)+1)-$mostraRegistros;
		$registroFinal	 = $mostraRegistros*$paginaAtual;
		if($registroFinal > count($dados[colunas][conteudo])) $registroFinal = count($dados[colunas][conteudo]);
		if ($style==""){
			$style = "float:left;margin-top:0px; border:1px solid silver; margin-bottom:2px;";
		}
		$tabela = "	<div id='div-$idTabela' class='gera-tabela'>
						$filtroLateral
						<table width='$largura' Style='$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center' id='$idTabela'>
							<thead>
								<tr>";
		for($i=1;$i<=$colunas;$i++){
			$ordenatabela = "";
			$classeTitulo = "tabela-fundo-escuro-titulo";
			if ($dados[colunas][titulo][classe]!="") $classeTitulo = $dados[colunas][titulo][classe];
			if($dados[colunas][ordena][$i] != "")$ordenatabela = " class='ordena-tabela link' id='".$dados[colunas][ordena][$i]."' ";
			$tabela .= "				<td class='$classeTitulo coluna-$i ".$_SESSION['campoVisivel']['coluna-'.$i]."' ".$dados[colunas][tamanho][$i]."><a $ordenatabela>".$dados[colunas][titulo][$i]."</a></td>";
		}
		$tabela .= "			</tr>
							</thead>";
		for($i=$registroInicial;$i<=$registroFinal;$i++){
			$classe=$dados[colunas][classe][$i];
			if ($dados[colunas][classe][$i]==""){
				if($i%2==0)$classe='tabela-fundo-claro'; else $classe='tabela-fundo-escuro';
			}
			$tabela .= "	<tbody>
								<tr>";
			for($j=1;$j<=$colunas;$j++){
				$tabela .= "			<td class='$classe coluna-$j ".$_SESSION['campoVisivel']['coluna-'.$j]."' ".$dados[colunas][extras][$i][$j]." colspan='".$dados[colunas][colspan][$i][$j]."'>".$dados[colunas][conteudo][$i][$j]."</td>";
				if ($dados[colunas][colspan][$i][$j]!="") $j += $dados[colunas][colspan][$i][$j] - 1;
			}
			$tabela .= "		</tr>
							</tbody>";
		}
		$tabela .= "	</table>
					</div>
					<input type='hidden' id='ordena-tabela' name='ordena-tabela'>
					<input type='hidden' id='ordena-tipo' name='ordena-tipo' value='".$_POST['ordena-tipo']."'>";
		if($_SESSION['paginador'] == "")
			$tabela .= paginadorTabela($paginasTotais,$paginaAtual,$mostraRegistros,$dados,$idTabela,$largura, $colunas);
		$_SESSION['paginador'] = "";
		$tabela .= "<style>.esconde-campo-tabela{display:none;}</style>";
		if ($tipoRetorno=="return")
			return $tabela;
		else
			echo $tabela;

	}

	function paginadorTabela($paginasTotais, $paginaAtual, $quantidadeRegistros, $dados, $idTabela, $largura, $colunas){
		if($paginasTotais >= 2){
			$_SESSION[idTabela] 			= $idTabela;
			$_SESSION[$idTabela][dados] 	= $dados;
			$_SESSION[$idTabela][registros] = $quantidadeRegistros;
			$_SESSION[$idTabela][largura] 	= $largura;
			$_SESSION[$idTabela][colunas] 	= $colunas;
			for($i=1;$i<=$paginasTotais;$i++){
				if($i==$paginaAtual) $classe = "paginador-selecionado"; else $classe = "paginador";
				$paginador .= "<div class='$classe' attr-pagina='$i' id='paginador-$i' attr-tabela='div-$idTabela'>$i</div>";
			}
			return "<div id='paginador-container'>$paginador</div>";
		}
	}



?>
