<?php
	error_reporting(E_ERROR);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $configCadastros;
	$configCadastros = carregarConfiguracoesGeraisModulos('cadastros');
	global $configChamados;
	$configChamados = carregarConfiguracoesGeraisModulos('chamados');

	/********************************* Inicio Funções Cadastros *********************************/

	function validarCadastro(){
		$campo = $_GET['campo'];
		$sql = "select count(*) as Cont from cadastros_dados where Cpf_Cnpj = '$campo' where Situacao = 1";
		$resultado = mpress_query($sql);
		if ($row = mpress_fetch_array($resultado))
			echo $row[0];
	}

	function localizaCadastros(){
		$sql = "select Cadastro_ID, Descr_Tipo, Codigo, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj, Inscricao_Municipal, Inscricao_Estadual, Usuario_Cadastro_ID
					from cadastros_dados cd
					left join tipo tp on Tipo_ID = Tipo_Pessoa and Tipo_Grupo_ID = 8
					where cd.Situacao_ID = 1";
		//echo $sql;
		echo "<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;

			$dados[colunas][conteudo][$i][1] = "<a href='#' class='link cadastro-localiza' id='cadastro-localiza-".$row[Cadastro_ID]."' name='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p><a>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".utf8_encode($row[Descr_Tipo])."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".utf8_encode($row[Nome])." / ".utf8_encode($row[Nome_Fantasia])."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</p>";
		}
		if($i>=1){
			$largura = "99%";
			$colunas = "4";
			$dados[colunas][tamanho][1] = "width='10%'";
			$dados[colunas][titulo][1] 	= "C&oacute;digo";
			$dados[colunas][tamanho][2] = "width='20%'";
			$dados[colunas][titulo][2] 	= "Tipo";
			$dados[colunas][tamanho][3] = "width=''";
			$dados[colunas][titulo][3] 	= "Nome";
			$dados[colunas][tamanho][4] = "width='30%'";
			$dados[colunas][titulo][4] 	= "Cpf / Cnpj";
			geraTabela($largura,$colunas,$dados);
		}
	}

	function localizaCadastrosSelect(){
		$descricao = $_GET['descricao'];
		$cadastroID = $_GET['cadastro-id'];
		$tipoVinculoID = $_GET['tipo-vinculo-id'];

		if ($descricao!=""){
			$sqlCond = " and (Nome like '%$descricao%' or Nome_Fantasia like '%$descricao%' or Cpf_Cnpj like '%$descricao%')";
		}
		if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= "and (cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos where cadastro_id = ".$_SESSION[dadosUserLogin][userID].") or cadastro_ID = ".$_SESSION[dadosUserLogin][userID].")";

		$sql = "select Cadastro_ID, Descr_Tipo, Codigo, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj, Inscricao_Municipal, Inscricao_Estadual, Usuario_Cadastro_ID
					from cadastros_dados cd
					inner join tipo tp on Tipo_ID = Tipo_Pessoa and Tipo_Grupo_ID = 8
					where cd.Situacao_ID = 1
					and Cadastro_ID <> $cadastroID
					and Cadastro_ID NOT IN (select Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID = $cadastroID and Tipo_Vinculo_ID = $tipoVinculoID)
					$sqlCond ";
		echo "<select id='select-novo-vinculo-$tipoVinculoID' name='select-novo-vinculo-$tipoVinculoID' Style='width:98.5%'>
					<option value=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			echo "<option value='".$row[Cadastro_ID]."'>".utf8_encode($row[Nome])."</option>";
		}
		echo "</select>";
	}

	/********************************* Fim Funções Cadastros *********************************/


	/********************************* Inicio Funções Endereço *********************************/

	function carregarEnderecos($cadastroID){
		global $caminhoSistema;
		//$cadastroID  = $_GET['cadastroID'];
		$sql = "select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
				from cadastros_enderecos ce
				left join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
				where Cadastro_ID = '$cadastroID'
				and ce.Situacao_ID = 1
				order by Descr_Tipo";
		$i = 0;
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;width:60%;'>
												<b>".($row[Descr_Tipo]).":</b>
												".($row[Logradouro])."&nbsp;".$row[Numero]."&nbsp;".($row[Complemento])."
												&nbsp;&nbsp;CEP:".$row[CEP]."&nbsp;&nbsp;".($row[Referencia])."
												&nbsp;&nbsp;".($row[Bairro])."&nbsp;&nbsp;".($row[Cidade])."&nbsp;&nbsp;".$row[UF]." &nbsp;&nbsp;
												<a href='https://maps.google.com.br/?q=".($row[Logradouro]).", ".$row[Numero]." ".($row[CEP])." ".($row[Cidade])." ".($row[UF])."' target='_blank' >Ver Mapa</a>
													<div class='direita'>
														<div class='btn-excluir btn-excluir-endereco' style='float:right; padding-right:10px' cadastro-endereco-id='".$row[Cadastro_Endereco_ID]."' title='Excluir'>&nbsp;</div>
														<div class='btn-editar btn-editar-endereco' style='float:right;padding-right:10px' cadastro-endereco-id='".$row[Cadastro_Endereco_ID]."' title='Editar'>&nbsp;</div>
													</div>
												</p>";
		}
		$largura = "100%";
		$colunas = "1";
		$dados[colunas][tamanho][1] = "width='100%'";
		$dados[colunas][titulo][1] 	= "&nbsp;Endere&ccedil;os Cadastrados <!--<a class='link btn-novo' style='cursor:pointer;float:right' id='botao-novo-endereco'>(+)</a>-->";
		geraTabela($largura, $colunas, $dados, "", "tabela-enderecos-cadastros", 2, 2, 100, "");
		/*
		if($i==0){
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum endere&ccedil;o cadastrado</p>";
		}
		*/
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}


	function carregarEndereco(){
		global $caminhoSistema;
		$enderecoID = $_GET['enderecoID'];
		if ($enderecoID!=""){
			$sql = "select Cadastro_Endereco_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia
					from cadastros_enderecos
					where Cadastro_Endereco_ID = $enderecoID";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$cadastroEnderecoID = $row[Cadastro_Endereco_ID];
				$tipoEnderecoID = $row[Tipo_Endereco_ID];
				$cep = $row[CEP];
				$logradouro = $row[Logradouro];
				$numero = $row[Numero];
				$complemento = $row[Complemento];
				$bairro = $row[Bairro];
				$cidade = $row[Cidade];
				$uf = $row[UF];
				$referencia = $row[Referencia];
				$tipoID = $row[Tipo_ID];
			}
		}
?>
		<div Style='margin-top:5px;float:left; width:100%;' id='endereco'>
			<input type="hidden" id="cadastro-endereco-id" name="cadastro-endereco-id" value="<?php echo $cadastroEnderecoID; ?>">
			<div Style='margin-top:5px; width:100%;'>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>Tipo Endere&ccedil;o</p>
					<p><select name='tipo-endereco-id' id='tipo-endereco-id' class='required'><?php echo utf8_encode(optionValueGrupo(10, $tipoEnderecoID)); ?></select></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>CEP</p>
					<p><input type="text" class='mascara-cep' id="cep-endereco" style='width:97%;' name="cep-endereco" maxlength='9' value="<?php echo $cep; ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:49.98%; float:left;'>
					<p>Logradouro</p>
					<p><input type="text" id="logradouro-endereco" name="logradouro-endereco" style='width:97%;' class='required' maxlength='200' value="<?php echo utf8_encode($logradouro); ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>N&uacute;mero</p>
					<p><input type="text" id="numero-endereco" name="numero-endereco" class='required' style='width:97%;' maxlength='20' value="<?php echo $numero; ?>"/></p>
				</div>
			</div>
			<div Style='margin-top:5px;'>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>Complemento</p>
					<p><input type="text" id="complemento-endereco" name="complemento-endereco" style='width:97%;' maxlength='100' value="<?php echo utf8_encode($complemento); ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>Bairro</p>
					<p><input type="text" id="bairro-endereco" name="bairro-endereco" style='width:97%;' maxlength='50' value="<?php echo utf8_encode($bairro); ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>Cidade</p>
					<p><input type="text"  id="cidade-endereco" name="cidade-endereco" style='width:97%;'  maxlength='50' class='required'  value="<?php echo utf8_encode($cidade); ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>UF</p>
					<p><input type="text" id="uf-endereco" name="uf-endereco"  style='width:97%;' maxlength='2' class='required' value="<?php echo $uf; ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:left;'>
					<p>Refer&ecirc;ncia</p>
					<p><input type="text" id="referencia-endereco" name="referencia-endereco" style='width:97%;' value="<?php echo utf8_encode($referencia); ?>"/></p>
				</div>
				<div class="titulo-secundario" style='width:16.66%; float:right; margin-top:15px;'>
					<p class='direita'>
						<input type='button' value='Salvar' id='botao-salvar-endereco' class='botao-salvar-endereco' Style='width:47%;float:left;margin-left:5px;'/>
						<input type='button' value='Cancelar' id='botao-cancelar-endereco' class='botao-cancelar-endereco' Style='width:47%;float:right;margin-right:5px;'/>
					</p>
				</div>
			</div>
		</div>
		<script>
			$(".mascara-cep").mask("99999-999");
		</script>
<?php
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	/********************************* Fim Funções Endereço *********************************/



	/********************************* Inicio Funções Telefones *********************************/

	function carregarTelefones($cadastroID){
		//$cadastroID  = $_GET['cadastroID'];
		$sql = "select Cadastro_Telefone_ID, t.Descr_Tipo, Telefone, Observacao
						from cadastros_telefones ct
						left join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
				where  ct.Situacao_ID = 1
				and Cadastro_ID = $cadastroID
				order by  t.Descr_Tipo, Telefone";

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;width:60%;'>
												<b>".($row[Descr_Tipo]).":</b>
												".$row[Telefone]."&nbsp;".$row[Numero]."&nbsp;".($row[Observacao])."
												</p>
													<div class='direita'>
														<div class='btn-excluir' style='float:right; padding-right:10px' onclick='excluirCadastroTelefone(".$row[Cadastro_Telefone_ID].")' title='Excluir'>&nbsp;</div>
														<div class='btn-editar' style='float:right;padding-right:10px' onclick='carregarCadastroTelefone(".$row[Cadastro_Telefone_ID].")' title='Editar'>&nbsp;</div>
													</div>
												</p>";
		}
		$largura = "100%";
		$colunas = "1";
		$dados[colunas][tamanho][1] = "width='100%'";
		$dados[colunas][titulo][1] 	= "&nbsp;Telefones Cadastrados <!--<a class='link btn-novo' style='cursor:pointer;float:right' id='botao-novo-telefone'>(+)</a>-->";
		//geraTabela($largura,$colunas,$dados);
		geraTabela($largura, $colunas, $dados, "", "tabela-telefones-cadastros", 2, 2, 100, "");
		//if($i==0){
		//	echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum telefone cadastrado</p>";
		//}
	}


	function carregarTelefone(){
		global $caminhoSistema;
		$telefoneID = $_GET['telefoneID'];
		if ($telefoneID!=""){
			$sql = "select Cadastro_Telefone_ID, Tipo_Telefone_ID, Telefone, Observacao
					from cadastros_telefones
					where Cadastro_Telefone_ID = $telefoneID";

			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$cadastroTelefoneID = $row[Cadastro_Telefone_ID];
				$tipoTelefoneID = $row[Tipo_Telefone_ID];
				$telefone = $row[Telefone];
				$observacao = $row[Observacao];
			}
		}
?>
		<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>
			<div class="titulo-secundario quatro-colunas">
				<input type="hidden" id="cadastro-telefone-id" name="cadastro-telefone-id" value="<?php echo $cadastroTelefoneID; ?>">
				<p>Tipo Telefone</p>
				<p><select name='tipo-telefone-id' id='tipo-telefone-id' class='required'><?php echo utf8_encode(optionValueGrupo(11, $tipoTelefoneID)); ?></select></p>
			</div>
			<div class="titulo-secundario tres-colunas">
				<p>Telefone</p>
				<p><input type="text" id="telefone-telefone" name="telefone-telefone" value="<?php echo $telefone; ?>" class='formata-telefone required' maxlength="15"/></p>
			</div>
			<div class="titulo-secundario quatro-colunas">
				<p>Observa&ccedil;&atilde;o</p>
				<p><input type="text" id="observacao-telefone" name="observacao-telefone" maxlength='200' value="<?php echo utf8_encode($observacao);  ?>"/></p>
			</div>
			<div class='titulo-secundario seis-colunas' Style='margin-top:15px;'>
				<p class='direita'>
					<input type='button' value='Salvar' id='botao-salvar-telefone' class='botao-salvar-telefone' Style='width:48%;float:left;'/>
					<input type='button' value='Cancelar' id='botao-cancelar-telefone' class='botao-cancelar-telefone' Style='width:48%'/>
				</p>
			</div>
		</div>
	<?php
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}
	/********************************* Fim Funções Telefones *********************************/

	function carregarVinculo(){
		$cadastroID = $_GET['cadastroID'];
		$tipoVinculoID = $_GET['tipoVinculoID'];

		echo "<div class='div-vincular-usuarios' Style='float:left;width:100%;margin-bottom:5px;'>";
		carregarBlocoCadastroGeral("", 'novo-vinculo', '', 0,$tipoVinculoID);
		echo "</div>
			  <div class='div-usuarios-vinculados'>";

		$sql = "select cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
				Inscricao_Municipal, Inscricao_Estadual, cd.Usuario_Cadastro_ID, Codigo, coalesce(cf.Telefone,'') as Telefone, cf.Observacao, cd.Email as Email,
				cv.Cadastro_Vinculo_ID as Parametro
				from cadastros_dados cd
				inner join cadastros_vinculos cv on cv.Cadastro_Filho_ID = cd.Cadastro_ID and cv.Tipo_Vinculo_ID = '$tipoVinculoID' and cv.Cadastro_ID = $cadastroID
				left join cadastros_telefones cf on cf.Cadastro_ID = cd.Cadastro_ID and cf.Situacao_ID = 1
				left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				where cd.Situacao_ID = 1
				and cv.Situacao_ID = 1
				order by Nome, cd.Cadastro_ID";
		carregarListagemCadastrosGeral($sql, $tipoVinculoID);
		echo "</div>";
	}


	/********************************* Fim Funções Vinculos *********************************/


	/********************************* Inicio Funções de BANCO DE DADOS Cadastros *********************************/

	function inserirCadastro(){
		global $dadosUserLogin, $caminhoFisico, $modulosGeral, $configCadastros;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$cadastroID 	= $_POST['cadastro-id'];
		$tipoPessoa 	= $_POST['radio-tipo-grupo-8'];
		$tiposVinculos 	= serialize($_POST['check-tipo-grupo-12']);
		$tipoCadastro 	= serialize($_POST['check-tipo-grupo-9']);
		$areasAtuacoes 	= serialize($_POST['check-tipo-grupo-34']);
		$cargoID = $_POST['cargo-id'];
		$codigo 		= $_POST['cadastro-codigo'];
		$centroCustoID	= $_POST['centro-custo-id'];
		$tabelaPrecoID	= $_POST['tabela-preco-cliente'];
		$regionalID		= $_POST['regional-id'];
		$situacaoID = $_POST['situacao-id'];


		$dataNascimento = formataDataBD($_POST['data-nascimento']);
		$sexo			= $_POST['sexo'];

		if ($tipoPessoa=="24"){
			$cpfCnpj = str_replace('/','',str_replace('-','',str_replace('.','',$_POST['cnpj'])));
			$rg = $_POST['rg'];
			$nome = utf8_decode($_POST['nome-completo']);
		}
		if ($tipoPessoa=="25"){
			$cpfCnpj = str_replace('/','',str_replace('-','',str_replace('.','',$_POST['cnpj'])));
			$nome = utf8_decode($_POST['razao-social']);
			$nomeFantasia = utf8_decode($_POST['nome-fantasia']);
			$inscricaoEstadual = $_POST['inscricao-estadual'];
			$inscricaoMunicipal = $_POST['inscricao-municipal'];
		}
		$email = utf8_decode($_POST['cadastro-email']);
		$senha = utf8_decode($_POST['cadastro-senha']);
		$foto = $_POST['arquivo-imagem'];
		$observacao = utf8_decode($_POST['observacao']);
		$grupoID = $_POST['grupo-id'];

		$sql = "Insert Into cadastros_dados (Tipo_Pessoa, Grupo_ID, Sexo, Codigo, Tipo_Cadastro, Nome, Nome_Fantasia, Senha, Foto, Email, Data_Nascimento, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Observacao, Usuario_Cadastro_ID, Data_Inclusao, Situacao_ID, Tabela_Preco_ID, Areas_Atuacoes, Regional_ID, Cargo_ID)
									Values ('$tipoPessoa','$grupoID', '$sexo' ,'$codigo', '$tipoCadastro', '$nome', '$nomeFantasia',  '$senha', '$foto', '$email', '$dataNascimento', '$cpfCnpj', '$rg', '$inscricaoMunicipal', '$inscricaoEstadual', '$tiposVinculos', '$observacao','".$dadosUserLogin[userID]."', $dataHoraAtual, '$situacaoID' , '$tabelaPrecoID', '$areasAtuacoes', '$regionalID', '$cargoID')";

		mpress_query($sql);
		$cadastroID = mysql_insert_id();

		if ($configCadastros['classifica-clientes']==1){
			$sql = "UPDATE cadastros_classificacoes SET Situacao_ID = 2 where Cadastro_ID = '$cadastroID'";
			mpress_query($sql);
			$classificacaoID = $_POST['classificacao-id'];
			$sql = " INSERT INTO cadastros_classificacoes (Cadastro_ID, Classificacao_ID, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
													VALUES ('$cadastroID', '$classificacaoID', 1, '".$dadosUserLogin[userID]."', $dataHoraAtual)";
			mpress_query($sql);
		}

		if($_SESSION[dadosUserLogin][grupoID] == -2){
			inserirCadastroVinculo($_SESSION[dadosUserLogin][userID], $cadastroID, "101");
		}

		// TRECHO ABAIXO RESPONSAVEL POR COLOCAR CAMPOS ADICIONAIS SE MODULO DE IGREJA ATIVO
		if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
			require_once($caminhoFisico."/modulos/igreja/functions.php");
			salvarDadosComplementaresIgreja($cadastroID);
		}

		if ($_GET['acaoExtra']=='atualizaFoto'){
			$sql = " insert into modulos_anexos (Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$cadastroID', 'cadastros', '$foto', '$foto', 1, $dataHoraAtual, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
		}
		echo $cadastroID;
	}

	function atualizarCadastro(){
		global $dadosUserLogin, $caminhoFisico, $modulosGeral, $configCadastros;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$cadastroID 	= $_POST['cadastro-id'];
		$tipoPessoa 	= $_POST['radio-tipo-grupo-8'];

		$tiposVinculos 	= serialize($_POST['check-tipo-grupo-12']);
		$tipoCadastro 	= serialize($_POST['check-tipo-grupo-9']);
		$areasAtuacoes 	= serialize($_POST['check-tipo-grupo-34']);
		$cargoID = $_POST['cargo-id'];
		$sexo			= $_POST['sexo'];

		$codigo 		= $_POST['cadastro-codigo'];
		$centroCustoID	= $_POST['centro-custo-id'];
		$regionalID		= $_POST['regional-id'];
		$tabelaPrecoID	= $_POST['tabela-preco-cliente'];
		$dataNascimento = formataDataBD($_POST['data-nascimento']);



		if ($tipoPessoa=="24"){
			$cpfCnpj = str_replace('/','',str_replace('-','',str_replace('.','',$_POST['cnpj'])));
			$rg = $_POST['rg'];
			$nome = utf8_decode($_POST['nome-completo']);
		}
		if ($tipoPessoa=="25"){
			$cpfCnpj = str_replace('/','',str_replace('-','',str_replace('.','',$_POST['cnpj'])));
			$nome = utf8_decode($_POST['razao-social']);
			$nomeFantasia = utf8_decode($_POST['nome-fantasia']);
			$inscricaoEstadual = $_POST['inscricao-estadual'];
			$inscricaoMunicipal = $_POST['inscricao-municipal'];
		}
		$email = utf8_decode($_POST['cadastro-email']);
		$senha = utf8_decode($_POST['cadastro-senha']);
		$foto = $_POST['arquivo-imagem'];
		$observacao = utf8_decode($_POST['observacao']);
		$grupoID = $_POST['grupo-id'];
		$situacaoID = $_POST['situacao-id'];
		$sql = "Update cadastros_dados set 	Tipo_Pessoa			= '$tipoPessoa',
											Grupo_ID 			= '$grupoID',
											Sexo				= '$sexo',
											Codigo 				= '$codigo',
											Nome 				= '$nome',
											Nome_Fantasia 		= '$nomeFantasia',
											Senha 				= '$senha',
											Email 				= '$email',
											Tipo_Cadastro 		= '$tipoCadastro',
											Foto 				= '$foto',
											Data_Nascimento 	= '$dataNascimento',
											Cpf_Cnpj 			= '$cpfCnpj',
											RG		 			= '$rg',
											Inscricao_Municipal = '$inscricaoMunicipal',
											Inscricao_Estadual 	= '$inscricaoEstadual',
											Tabela_Preco_ID		= '$tabelaPrecoID',
											Observacao 			= '$observacao',
											Areas_Atuacoes		= '$areasAtuacoes',
											Regional_ID			= '$regionalID',
											Cargo_ID			= '$cargoID',
											Usuario_Alteracao_ID = '".$dadosUserLogin[userID]."',
											Data_Alteracao 		= $dataHoraAtual,
											Tipo_Vinculo 		= '$tiposVinculos',
											Situacao_ID 		= '$situacaoID'
				where Cadastro_ID = $cadastroID";
		mpress_query($sql);
		echo $cadastroID;

		// TRECHO ABAIXO RESPONSAVEL POR COLOCAR CAMPOS ADICIONAIS SE MODULO DE IGREJA ATIVO
		if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
			require_once($caminhoFisico."/modulos/igreja/functions.php");
			salvarDadosComplementaresIgreja($cadastroID);
		}

		if ($_GET['acaoExtra']=='atualizaFoto'){
			$sql = " insert into modulos_anexos (Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$cadastroID', 'cadastros', '$foto', '$foto', 1, $dataHoraAtual, '".$dadosUserLogin[userID]."')";
			mpress_query($sql);
		}
		if ($configCadastros['classifica-clientes']==1){
			$sql = "UPDATE cadastros_classificacoes SET Situacao_ID = 2 where Cadastro_ID = '$cadastroID'";
			mpress_query($sql);
			$classificacaoID = $_POST['classificacao-id'];
			$sql = " INSERT INTO cadastros_classificacoes (Cadastro_ID, Classificacao_ID, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
													VALUES ('$cadastroID', '$classificacaoID', 1, '".$dadosUserLogin[userID]."', $dataHoraAtual)";
			mpress_query($sql);
		}
	}

	function atualizarCadastroSituacao(){
		$cadastroID = $_GET['cadastro-id'];
		$situacaoID = $_GET['situacao-id'];
		$sql = "Update cadastros_dados set Situacao_ID = '$situacaoID' where Cadastro_ID = $cadastroID";
		mpress_query($sql);
	}



	/********************************* Fim Funções de BANCO DE DADOS Cadastros *********************************/


	/********************************* Inicio Funções de BANCO DE DADOS Enderecos *********************************/

	function inserirCadastroEndereco(){
		$cadastroID = $_POST['cadastro-id'];
		$tipoEnderecoID = $_POST['tipo-endereco-id'];
		$cep = $_POST['cep-endereco'];
		$logradouro = utf8_decode($_POST['logradouro-endereco']);
		$numero = $_POST['numero-endereco'];
		$complemento = utf8_decode($_POST['complemento-endereco']);
		$bairro = utf8_decode($_POST['bairro-endereco']);
		$cidade = utf8_decode($_POST['cidade-endereco']);
		$uf = $_POST['uf-endereco'];
		$referencia = utf8_decode($_POST['referencia-endereco']);

		$sql = "Insert Into cadastros_enderecos (Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Situacao_ID, Usuario_Cadastro_ID)
									     Values ('$cadastroID', '$tipoEnderecoID', '$cep', '$logradouro', '$numero', '$complemento', '$bairro', '$cidade', '$uf', '$referencia', 1, '".$dadosUserLogin[userID]."')";
		echo $sql;
		mpress_query($sql);
		//echo mysql_insert_id();
	}

	function atualizarCadastroEndereco(){
		global $caminhoFisico;

		$cadastroEnderecoID = $_POST['cadastro-endereco-id'];
		$tipoEnderecoID = $_POST['tipo-endereco-id'];
		$cep = $_POST['cep-endereco'];
		$logradouro = utf8_decode($_POST['logradouro-endereco']);
		$numero = $_POST['numero-endereco'];
		$complemento = utf8_decode($_POST['complemento-endereco']);
		$bairro = utf8_decode($_POST['bairro-endereco']);
		$cidade = utf8_decode($_POST['cidade-endereco']);
		$uf = $_POST['uf-endereco'];
		$referencia = utf8_decode($_POST['referencia-endereco']);



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

		//echo $sql;
		mpress_query($sql);
		//echo $cadastroID;
	}


	function excluirCadastroEndereco(){
		$cadastroEnderecoID = $_GET['enderecoID'];
		$sql = "Update cadastros_enderecos set Situacao_ID = 3 where Cadastro_Endereco_ID = $cadastroEnderecoID";
		echo $sql;
		mpress_query($sql);
	}
	/********************************* Inicio Funções de BANCO DE DADOS Enderecos *********************************/

	/********************************* Inicio Funções de BANCO DE DADOS Telefones *********************************/

	function inserirCadastroTelefone(){
		$cadastroID = $_POST['cadastro-id'];
		$tipoTelefoneID = $_POST['tipo-telefone-id'];
		$telefone = $_POST['telefone-telefone'];
		$observacao = utf8_decode($_POST['observacao-telefone']);
		$sql = "Insert Into cadastros_telefones (Cadastro_ID, Tipo_Telefone_ID, Telefone, Observacao, Situacao_ID, Usuario_Cadastro_ID)
									     Values ('$cadastroID', '$tipoTelefoneID', '$telefone', '$observacao', 1, '".$dadosUserLogin[userID]."')";

		//echo $sql;
		mpress_query($sql);
	}

	function atualizarCadastroTelefone(){
		$cadastroID = $_POST['cadastro-id'];
		$cadastroTelefoneID = $_POST['cadastro-telefone-id'];
		$tipoTelefoneID = $_POST['tipo-telefone-id'];
		$telefone = $_POST['telefone-telefone'];
		$observacao = utf8_decode($_POST['observacao-telefone']);

		$sql = "Update cadastros_telefones set Tipo_Telefone_ID = '$tipoTelefoneID',
											Telefone = '$telefone',
											Observacao = '$observacao',
											Usuario_Cadastro_ID = '".$dadosUserLogin[userID]."'
				where Cadastro_Telefone_ID = $cadastroTelefoneID";

		mpress_query($sql);
	}


	function excluirCadastroTelefone(){
		$telefoneID = $_GET['telefoneID'];
		$sql = "Update cadastros_telefones set Situacao_ID = 3 where Cadastro_Telefone_ID = $telefoneID";
		//echo $sql;
		mpress_query($sql);
	}




	/********************************* Inicio Funções de BANCO DE DADOS Vinculos *********************************/


	function inserirCadastroVinculo($cadastroID, $cadastroFilhoID, $tipoVinculoID){
		$sql = "select count(*) as cont from cadastros_vinculos where Tipo_Vinculo_ID = '$tipoVinculoID' and Cadastro_ID = '$cadastroID' and Cadastro_Filho_ID = '$cadastroFilhoID'";
		$resultado = mpress_query($sql);
		if ($row = mpress_fetch_array($resultado)){
			if ($row[cont]==0){
				$sql = "Insert Into cadastros_vinculos (Tipo_Vinculo_ID, Cadastro_ID, Cadastro_Filho_ID, Situacao_ID)
												 Values ('$tipoVinculoID', '$cadastroID', '$cadastroFilhoID', 1)";
				mpress_query($sql);
			}
		}
	}


	function excluirCadastroVinculo(){
		global $caminhoFisico;
		$cadastroVinculoID = $_GET['cadastro-vinculo-id'];
		mpress_query("delete from cadastros_vinculos where Cadastro_Vinculo_ID = $cadastroVinculoID");
	}


	/********************************* Fim Funções de BANCO DE DADOS Vinculos *********************************/

	function carregarOrcamentosCadastro($solicitanteID){
		if ($solicitanteID!=""){
			global $caminhoSistema;
			$sql = "select o.Workflow_ID, o.Codigo, o.Titulo, s.Nome as Solicitante, ts.Descr_Tipo as Situacao, o.Data_Abertura,
						o.Data_Finalizado, of.Data_Cadastro as Data_Interacao, r.Nome as Representante,
						op.Titulo as Titulo_Proposta, tp.Descr_Tipo as Situacao_Proposta, op.Proposta_ID as Proposta_ID, s.Cadastro_ID,
						SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor
						from orcamentos_workflows o
						inner join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
						inner join tipo ts on ts.Tipo_ID = o.Situacao_ID
						inner join orcamentos_follows of on o.Workflow_ID = of.Workflow_ID and of.Follow_ID = (select max(ofaux.Follow_ID) from orcamentos_follows ofaux where ofaux.Workflow_ID = o.Workflow_ID limit 1)
						left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
						left join orcamentos_propostas op on op.Workflow_ID = o.Workflow_ID and op.Situacao_ID = 1
						left join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
						left join tipo tp on tp.Tipo_ID = op.Status_ID
						where o.Workflow_ID > 0
						and o.Solicitante_ID = '$solicitanteID'
						group by o.Workflow_ID, o.Codigo, o.Titulo, s.Nome, ts.Descr_Tipo, o.Data_Abertura, o.Data_Finalizado,
						of.Data_Cadastro, r.Nome, op.Titulo, tp.Descr_Tipo, op.Proposta_ID, s.Cadastro_ID
						order by o.Workflow_ID desc";
			//echo $sql;

			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				if ($row[Workflow_ID]!=$workFlowIDAnt){
					$i++;
					$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link orcamento-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Workflow_ID]."</p>";
					$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link orcamento-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Solicitante]."</p>";
					$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Titulo]."</p>";
					$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
					$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Abertura]),0,10)."</p>";
					$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Interacao]),0,10)."</p>";
					$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Representante]."</p>";
					$dados[colunas][classe][$i] = "tabela-fundo-escuro";
				}
				if ($row['Proposta_ID']!=""){
					$i++;
					$dados[colunas][colspan][$i][2] = "2";
					$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 30px;float:left;'>".$row[Titulo_Proposta]."</p>";
					$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao_Proposta]."</p>";
					$dados[colunas][colspan][$i][5] = "2";
					$dados[colunas][conteudo][$i][5] = "<p align='right'>R$ ".number_format($row[Valor], 2, ',', '.')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>";

					$dados[colunas][conteudo][$i][7] = "<div align='center'><a title='Proje&ccedil;&atilde;o de valores' class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/turmas/projecao.php?orcamento=$row[Proposta_ID]&titulo=$row[Titulo_Proposta]&cadastro=$row[Cadastro_ID]'><div class='btn-grafico'>&nbsp;</div></a></div>";
					$dados[colunas][classe][$i] = "tabela-fundo-claro";
				}
				$workFlowIDAnt = $row[Workflow_ID];
			}
			$largura = "100%";
			$colunas = "7";
			$dados[colunas][titulo][1] 	= "ID <!--Or&ccedil;amento-->";
			$dados[colunas][titulo][2] 	= "Solicitante";
			$dados[colunas][titulo][3] 	= "T&iacute;tulo";
			$dados[colunas][titulo][4] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][titulo][5] 	= "Abertura";
			$dados[colunas][titulo][6]	= "Intera&ccedil;&atilde;o";
			$dados[colunas][titulo][7]	= "Representante";

			$dados[colunas][tamanho][1] = "width='40px'";
			$dados[colunas][tamanho][5] = "width='70px'";
			$dados[colunas][tamanho][6] = "width='70px'";
			$dados[colunas][tamanho][7] = "width='70px'";
			geraTabela($largura,$colunas,$dados, null, 'orcamentos-localizar', 2, 2, 100,"","");
			if($i==0) echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum registro localizado</p>";
		}
	}


	function carregarChamados($cadastroID){
		global $caminhoSistema;
		$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, tw.Descr_Tipo as Tipo_Chamado,
							cd1.Nome as Solicitante, cd1.Nome_Fantasia as Solicitante_Fantasia, cd1.email as Email_Solicitante, cw.Titulo as Titulo,
							t.Descr_Tipo as Situacao,
							DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
							DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
							DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
							(select count(*) from modulos_anexos a where a.Chave_Estrangeira = cw.Workflow_ID and a.Tabela_Estrangeira = 'chamados' and a.Situacao_ID = 1) as arquivos,
							Data_Hora_Retorno
					from chamados_workflows cw
					inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
					left join tipo tw on tw.Tipo_ID = cw.Tipo_WorkFlow_ID and tw.Tipo_Grupo_ID = 19
					left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
						and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
					left join tipo t on t.Tipo_ID = cf.Situacao_ID
					left join modulos_acessos ma on ma.Modulo_Acesso_ID = cf.Responsabilidade_ID
					where cw.Solicitante_ID = '$cadastroID'
					order by cw.Workflow_ID desc";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$nome = $row[Nome];
			$solicitante = $row[Solicitante];
			if ($row[Email_Solicitante]!=""){ $solicitante .= "&nbsp;(".$row[Email_Solicitante].")";}

			$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-vazia.png' alt='Nenhum Arquivo Anexado'/>";
			if ($row[arquivos]>0){
				$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-cheia.png' alt='(".$row[arquivos].") Arquivo(s) Anexado(s)'/>";
			}
			$dadosChamado[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Workflow_ID]."</p>";
			$dadosChamado[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Codigo]."</p>";
			$dadosChamado[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Tipo_Chamado]."</p>";
			$dadosChamado[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."' title='".$row[Solicitante_Fantasia]."'>".$solicitante."</p>";
			$dadosChamado[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Titulo]."</p>";
			$dadosChamado[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao].formataDataHoraRelatorio($row['Data_Hora_Retorno'])."</p>";
			$dadosChamado[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
			$dadosChamado[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Interacao]."</p>";
			$dadosChamado[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Finalizado]."</p>";
			$dadosChamado[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>$arquivos</p>";
		}
		$largura = "100%";
		$colunas = "10";
		$dadosChamado[colunas][titulo][1] 	= "ID";
		$dadosChamado[colunas][titulo][2] 	= $_SESSION['objeto'];
		$dadosChamado[colunas][titulo][3] 	= "Tipo";
		$dadosChamado[colunas][titulo][4] 	= "Solicitante";
		$dadosChamado[colunas][titulo][5] 	= "Titulo";
		$dadosChamado[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
		$dadosChamado[colunas][titulo][7] 	= "Abertura";
		$dadosChamado[colunas][titulo][8]	= "Intera&ccedil;&atilde;o";
		$dadosChamado[colunas][titulo][9] 	= "Finalizado";
		$dadosChamado[colunas][titulo][10]	= "";

		$dadosChamado[colunas][tamanho][1] = "width='40px'";
		$dadosChamado[colunas][tamanho][2] = "width='90px'";
		$dadosChamado[colunas][tamanho][3] = "";
		$dadosChamado[colunas][tamanho][4] = "";
		$dadosChamado[colunas][tamanho][5] = "";
		$dadosChamado[colunas][tamanho][6] = "width='150px'";
		$dadosChamado[colunas][tamanho][7] = "width='90px'";
		$dadosChamado[colunas][tamanho][8] = "width='90px'";
		$dadosChamado[colunas][tamanho][9] = "width='90px'";
		$dadosChamado[colunas][tamanho][10] = "width='20px'";
		geraTabela($largura,$colunas,$dadosChamado);
	}


	function vinculoAlunoTurmaCadastro($cadastroID){
		$resultado = mpress_query(" select Turma_ID, Nome_Turma, t1.Descr_Tipo Instituicao, t2.Descr_Tipo Campus, t3.Descr_Tipo Curso,
									t4.Descr_Tipo Periodo, d.Data_Cadastro, t5.Descr_Tipo Cargo
									from turmas_dados d
									inner join tipo t1 on t1.Tipo_ID = d.Instituicao_ID
									inner join tipo t2 on t2.Tipo_ID = d.Campus_ID
									inner join tipo t3 on t3.Tipo_ID = d.Curso_ID
									inner join tipo t4 on t4.Tipo_ID = d.Periodo_ID
									inner join cadastros_vinculos v on v.Cadastro_ID = d.Cadastro_ID and Tipo_Vinculo_ID = 109
									inner join cadastros_vinculos v1 on v1.Cadastro_Filho_ID = $cadastroID
									inner join tipo t5 on t5.Tipo_ID = v1.Tipo_Vinculo_ID and t5.Tipo_Grupo_ID = 50
									where v.cadastro_filho_id = $cadastroID");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<span class='turma-mostrar-detalhes' turma-id='".$row[Turma_ID]."'><p Style='margin:2px 5px 0 5px;float:left;' class='link'>".$row[Turma_ID]."</p></span>";
			$dados[colunas][conteudo][$i][2] = $row['Instituicao'];
			$dados[colunas][conteudo][$i][3] = $row['Campus'];
			$dados[colunas][conteudo][$i][4] = $row['Curso'];
			$dados[colunas][conteudo][$i][5] = $row['Periodo'];
			$dados[colunas][conteudo][$i][6] = $row['Nome_Turma'];
			$dados[colunas][conteudo][$i][7] = $row['Cargo'];
			$dados[colunas][conteudo][$i][8] = "<span style='text-align:center;width:100%;float:left;'>".formataData($row['Data_Cadastro']."</span>");
		}
		$largura = "100.2%";
		$colunas = "8";
		$dados[colunas][tamanho][1] = "width='5%'";
		$dados[colunas][tamanho][2] = "width='15%'";
		$dados[colunas][tamanho][3] = "width='15%'";
		$dados[colunas][tamanho][4] = "width='15%'";
		$dados[colunas][tamanho][5] = "width='15%'";
		$dados[colunas][tamanho][6] = "";
		$dados[colunas][tamanho][8] = "width='100px'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Instituição";
		$dados[colunas][titulo][3] 	= "Campus";
		$dados[colunas][titulo][4] 	= "Curso";
		$dados[colunas][titulo][5] 	= "Período";
		$dados[colunas][titulo][6] 	= "Nome da Turma";
		$dados[colunas][titulo][7] 	= "Cargo";
		$dados[colunas][titulo][8] 	= "<span style='text-align:center;width:100%;float:left;'>Data Cadastro</span>";

		geraTabela($largura,$colunas,$dados, null, 'lista-turma-localiza', 2, 2, 100,null);
	}

	function carregarClassificacao($cadastroID){
		global $caminhoSistema;
		//$h .= "		<span><b>Classifica&ccedil;&atilde;o</b>";
		$classificacaoAtual = 0;
		$sql = "select coalesce(t.Tipo_Auxiliar,0) as Classificacao_Atual, cc.Classificacao_ID as Classificacao_ID
							from cadastros_classificacoes cc
							inner join tipo t on t.Tipo_ID = cc.Classificacao_ID
						where cc.Situacao_ID = 1 and cc.Cadastro_ID = '$cadastroID' order by cc.Cadastro_Classificacao_ID limit 1";
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$classificacaoAtual = $rs['Classificacao_Atual'];
			$classificacaoID = $rs['Classificacao_ID'];
		}
		$h .= "			<input type='hidden' name='classificacao-cadastro' id='classificacao-cadastro' value='$classificacaoAtual'/>
						<input type='hidden' name='classificacao-id' id='classificacao-id' value='$classificacaoID'/>";
		$resultado = mpress_query("SELECT Tipo_ID, Descr_Tipo, Tipo_Auxiliar FROM tipo WHERE Tipo_Grupo_ID = 74 AND Situacao_ID = 1");
		while($rs = mpress_fetch_array($resultado)){
			$dados = unserialize($rs['Tipo_Auxiliar']);
			$valor = $dados['classificacao'];
			$tipoID = $rs['Tipo_ID'];
			if ($classificacaoAtual>=$rs['Tipo_Auxiliar']){
				$h .= "<img class='estrela-ativa clasifica-valor clasifica-valor-$valor' valor='$valor' classificacao-id='$tipoID'>";
			}
			else{
				$h .= "<img class='estrela-inativa clasifica-valor clasifica-valor-$valor' valor='$valor' classificacao-id='$tipoID'>";
			}
		}
		$h .= "	</span>";
		return $h;
	}


	function carregarFollowsGerais($cadastroID){
		$sql = 	"	select 'Cadastros' as Modulo, '' as ID, '' as Situacao, cf.Data_Cadastro as Data_Cadastro, cf.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from cadastros_follows cf
						inner join cadastros_dados r on r.Cadastro_ID = cf.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = '".$cadastroID."'

					union all

					select 'Telemarketing' as Modulo, tf.Workflow_ID as ID, ts.Descr_Tipo as Situacao, tf.Data_Cadastro as Data_Cadastro, tf.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from tele_follows tf
						inner join tele_workflows tw on tw.Workflow_ID = tf.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = tw.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = tw.Cadastro_ID
						left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
						where c.Cadastro_ID = '".$cadastroID."'

					union all

					select 'Orcamentos' as Modulo, of.Workflow_ID as ID, tf.Descr_Tipo as Situacao, of.Data_Cadastro as Data_Cadastro, of.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from orcamentos_follows of
						inner join orcamentos_workflows ow on ow.Workflow_ID = of.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = of.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = ow.Solicitante_ID
						left join tipo tf on tf.Tipo_ID = of.Situacao_ID
						where c.Cadastro_ID = '".$cadastroID."'

					union all

					select 'Chamados' as Modulo, cf.Workflow_ID as ID, tf.Descr_Tipo as Situacao, cf.Data_Cadastro as Data_Cadastro, cf.Descricao as Descricao,
							r.Nome as Responsavel, c.Cadastro_ID, c.Nome as Cadastro
						from chamados_follows cf
						inner join chamados_workflows cw on cw.Workflow_ID = cf.Workflow_ID
						inner join cadastros_dados r on r.Cadastro_ID = cf.Usuario_Cadastro_ID
						inner join cadastros_dados c on c.Cadastro_ID = cw.Solicitante_ID
						left join tipo tf on tf.Tipo_ID = cf.Situacao_ID
						where c.Cadastro_ID = '".$cadastroID."'

					order by Data_Cadastro desc";
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			$c = 1;
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;text-align:center;'>".converteDataHora($rs['Data_Cadastro'],1)."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Responsavel']."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br($rs['Descricao'])."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Modulo']."</p>";
		}
		if($i>=1){
			$largura = "99%";
			$c = 1;
			$dados[colunas][titulo][$c++] 	= "<center>Data Follow</center>";
			$dados[colunas][titulo][$c++] 	= "Usu&aacute;rio";
			$dados[colunas][titulo][$c++] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][titulo][$c++] 	= "Observa&ccedil;&otilde;es Cadastradas";
			$dados[colunas][titulo][$c++] 	= "M&oacute;dulo";

			$c = 1;
			$dados[colunas][tamanho][$c++] = "";
			$dados[colunas][tamanho][$c++] = "";
			$dados[colunas][tamanho][$c++] = "width='230px'";
			$dados[colunas][tamanho][$c++] = "";
			$dados[colunas][tamanho][$c++] = "";
			$h .= geraTabela("99.4%",($c-1),$dados, null, 'cadastros-follows-gerais', 2, 2, "","", "return");
		}
		else{
			echo "<p Style='text-align:center;color:red;'>Nenhum follow cadastrado</p>";
		}
		return $h;
	}

	function salvarCadastroFollow(){
		global $dadosUserLogin;
		$cadastroID = $_POST['cadastro-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$descricaoFollow = utf8_decode($_POST['cadastro-descricao-follow']);
		$situacaoID = $_POST['cadastro-situacao-follow'];
		$sql = "INSERT INTO cadastros_follows (Workflow_ID, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										VALUES ('$cadastroID', '$descricaoFollow', '$situacaoID', $dataHoraAtual, '".$dadosUserLogin[userID]."')";
		mpress_query($sql);
		$h = utf8_encode(carregarFollowsGerais($cadastroID));
		return $h;
	}
?>