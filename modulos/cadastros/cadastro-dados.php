<?php
	include("functions.php");
	if ($_GET['tipo-fluxo']=='direto'){
		echo "	<style>
					#topo-container{display:none;}
					#menu-container{display:none;}
				</style>";
	}
	$dadospagina = get_page_content();
	global $modulosGeral, $dadosUserLogin, $caminhoSistema, $modulosAtivos;
	$cadastroID = $_POST['cadastroID'];
	if ($cadastroID=="") $cadastroID = $_GET['cadastroID'];
	if ($cadastroID!=""){
		$sql = "Select Tipo_Pessoa, Tipo_Cadastro, Grupo_ID, Codigo, Centro_Custo_ID, Nome, Nome_Fantasia, Senha, Email, Data_Nascimento,
					Foto, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Observacao, Usuario_Cadastro_ID, Situacao_ID, Areas_Atuacoes, Sexo, Regional_ID
					from cadastros_dados where Cadastro_ID = '$cadastroID'";
		$query = mpress_query($sql);
		if($cadastro = mpress_fetch_array($query)){
			$tipoPessoa  = $cadastro[Tipo_Pessoa];
			$tipoVinculo = $cadastro[Tipo_Vinculo];
			$grupoID 	 = $cadastro[Grupo_ID];
			$centroCustoID = $cadastro[Centro_Custo_ID];
			$codigo		 = $cadastro[Codigo];
			$areasAtuacoes	= $cadastro[Areas_Atuacoes];
			$dataNascimento = formataData($cadastro[Data_Nascimento]);
			$cargoID = formataData($cadastro[Cargo_ID]);
			$regionalID = $cadastro[Regional_ID];

			if ($tipoPessoa=="24"){
				$cpf = $cadastro[Cpf_Cnpj];
				$rg = $cadastro[RG];
				$nomeCompleto =  $cadastro[Nome];
				$sexo = $cadastro[Sexo];
			}
			if ($tipoPessoa=="25"){
				$cnpj = $cadastro[Cpf_Cnpj];
				$razaoSocial = ($cadastro[Nome]);
				$nomeFantasia = ($cadastro[Nome_Fantasia]);
				$inscricaoEstadual = $cadastro[Inscricao_Estadual];
				$inscricaoMunicipal = $cadastro[Inscricao_Municipal];
			}
			$tipoCadastro = $cadastro[Tipo_Cadastro];
			$login = ($cadastro[Login]);
			$senha = ($cadastro[Senha]);
			$foto = $cadastro[Foto];
			$observacao = ($cadastro[Observacao]);
			$email = ($cadastro[Email]);
			$situacaoID = $cadastro[Situacao_ID];
		}
	}
	else{
		$tipoPessoa = 24;
		$sexo = "M";
		$situacaoID = 1;
	}
	if ($foto!="")
		$imagemFoto = "<img src='$caminhoSistema/uploads/$foto' style='max-height:70px; margin:0 auto;'  id='imagem-foto' Style='cursor:pointer'><input type='hidden' name='arquivo-imagem' id='arquivo-imagem' value='$foto'>";
	else
		$imagemFoto = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' id='imagem-foto' style='max-height:70px; margin:0 auto;' style='cursor:pointer'>";


?>
<input type='hidden' name='workflow-id' id='workflow-id' value=''/>
<div id="container-geral">
	<div class="titulo-container grupo1" id='div-dados-gerais'>
		<div class="titulo">
			<p Style='margin-top:2px;'>
				Dados Principais
				<input type='button' value='Salvar Cadastro' id='botao-cadastro-novo' class='cadastro-novo btn-novo' Style='float:right;height:24px;font-size:10px;margin-top:-3px;'/>
			</p>
<?php
		/**************************/
		/* CLASSIFICACAO CLIENTES */
		/**************************/
		if ($configCadastros['classifica-clientes']==1){
				if (!in_array("153", unserialize($tipoCadastro))){
					$escondeClassificacao = 'esconde';
				}
				echo "<p id='cadastro-classificacao' class='".$escondeClassificacao."' align='center' style='width:300px; margin: -23px auto; position:relative border: 1px color red;'>";
				echo carregarClassificacao($cadastroID);
				echo "</p>";
		}
?>
		</div>

		<div class='conteudo-interno grupo1'>
			<input type="hidden" id="cadastroID" name="cadastroID" value="<?php echo $cadastroID; ?>">
			<div id="div-cadastro">
				<div class='titulo-secundario' Style='width:10%;float:left; min-height:70px'>
					<input type='file' name='arquivo-upload-cadastro' id='arquivo-upload-cadastro' class='esconde'/>
					<p><b>Foto</b></p>
					<p>
						<iframe name='iframe-upload-cadastro' id='iframe-upload-cadastro' class='esconde'/></iframe>
						<div id='div-foto' name='div-foto'><?php echo $imagemFoto; ?></div>
						<!--
						<font Style='width:50;' class='link'>upload</font>
						<font Style='width:50;' class='link'>download</font>
						-->
					</p>
				</div>
				<div class='titulo-secundario' Style='width:90%;float:left'>
					<div class='titulo-secundario quatro-colunas'>
						<p><b>Tipo Pessoa</b></p>
						<p><?php echo (montaRadioGrupo(8, $tipoPessoa)); ?></p>
					</div>
					<div class='div-pf titulo-secundario duas-colunas'>
						<p><b>Nome Completo</b></p>
						<p><input type='text' id='nome-completo' name='nome-completo'  maxlength='250' value='<?php echo $nomeCompleto; ?>' autocomplete='off' Style='width:99%'/></p>
					</div>
					<div class='div-pj titulo-secundario quatro-colunas'>
						<p><b>Raz&atilde;o social</b></p>
						<p><input type='text' id='razao-social' name='razao-social' value='<?php echo $razaoSocial; ?>' autocomplete='off' Style='width:98%'/></p>
					</div>
					<div class='div-pj titulo-secundario quatro-colunas'>
						<p><b>Nome Fantasia</b></p>
						<p><input type='text' id='nome-fantasia' name='nome-fantasia' value='<?php echo $nomeFantasia; ?>' autocomplete='off' Style='width:98%'/></p>
					</div>
					<div class='titulo-secundario quatro-colunas <?php if (($_SESSION[dadosUserLogin][grupoID]=='-2') || ($_SESSION[dadosUserLogin][grupoID]=='-3')) echo "esconde"; ?>'>
						<p><b>Situa&ccedil;&atilde;o</b></p>
						<p><select name='situacao-id' id='situacao-id'><?php echo optionValueGrupo(1, $situacaoID, '', 'and Tipo_ID IN (1,2,3,142)');?></select></p>
					</div>
				</div>
				<div class='titulo-secundario' Style='width:90%;float:left'>
					<div class='titulo-secundario quatro-colunas'>
						<div class='titulo-secundario duas-colunas'>
							<p><b>ID Cadastro</b></p>
							<p><input type='text' id='cadastro-id' name='cadastro-id'  maxlength='10' value='<?php echo $cadastroID;?>' readonly/></p>
						</div>
						<div class='titulo-secundario duas-colunas'>
							<p><b>C&oacute;digo</b></p>
							<p><input type='text' id='cadastro-codigo' name='cadastro-codigo'  maxlength='20' value='<?php echo $codigo;?>' autocomplete='off'/></p>
						</div>
					</div>
					<div class='div-pf titulo-secundario quatro-colunas'>
						<p><b>CPF</b></p>
						<p><input type='text' id='cpf' name='cpf' maxlength='14' class='mascara-cpf valida-cadastro-documento' excessao='<?php echo $cadastroID;?>' value='<?php echo $cpf; ?>' autocomplete='off'/></p>
					</div>
					<div class='div-pf titulo-secundario quatro-colunas'>
						<p><b>RG</b></p>
						<p><input type='text' id='rg' name='rg' maxlength='20' value='<?php echo $rg;?>' autocomplete='off' style='width:87%;'/></p>
					</div>
					<div class='div-pf titulo-secundario seis-colunas'>
						<p><b>Sexo</b></p>
						<p>
							<input type='radio' id='sexo-M' name='sexo' value='M' <?php if ($sexo=="M") echo "checked"; ?>/><label for='sexo-M'>M</label>
							<input type='radio' id='sexo-F' name='sexo' value='F' <?php if ($sexo=="F") echo "checked"; ?>/><label for='sexo-F'>F</label>
						</p>
					</div>
					<div class='div-pj titulo-secundario quatro-colunas'>
						<p><b>CNPJ</b></p>
						<p><input type='text' id='cnpj'  name='cnpj' maxlength='18' class='mascara-cnpj valida-cadastro-documento' excessao='<?php echo $cadastroID;?>' value='<?php echo formataCPF_CNPJ($cnpj); ?>' autocomplete='off' Style='width:96%'/>
					</div>
					<div class='div-pj titulo-secundario quatro-colunas'>
						<div class='div-pj titulo-secundario' style='float:left; width:83%;'>
							<p><b>Inscri&ccedil;&atilde;o Estadual</b></p>
							<p><input type='text' id='inscricao-estadual' name='inscricao-estadual' maxlength='15'  value='<?php echo $inscricaoEstadual; ?>' autocomplete='off'/></p>
						</div>
						<div class='div-pj titulo-secundario' style='float:left; width:17%; margin-top:10px;'>
							<p style='font-size:xx-small;' ><b>Isento</b></p>
							<p><input type='checkbox' id='ie-isento' name='ie-isento' <?php if ($inscricaoEstadual=="ISENTO") echo "checked"; ?>/></p>
						</div>
					</div>
					<div class='div-pj titulo-secundario quatro-colunas'>
						<div class='div-pj titulo-secundario' style='float:left; width:83%;'>
							<p><b>Inscri&ccedil;&atilde;o Municipal</b></p>
							<p><input type='text' id='inscricao-municipal' name='inscricao-municipal' maxlength='15'  value='<?php echo $inscricaoMunicipal; ?>' autocomplete='off'/></p>
						</div>
						<div class='div-pj titulo-secundario' style='float:left; width:17%; margin-top:10px;'>
							<p style='font-size:xx-small;' ><b>Isento</b></p>
							<p ><input type='checkbox' id='im-isento' name='im-isento'  <?php if ($inscricaoMunicipal=="ISENTO") echo "checked"; ?>/></p>
						</div>
					</div>
				</div>

				<div class='titulo-secundario' style='float:left; width:32.5%;'>
					<p><b>E-mail</b></p>
					<p><input type='text' id='cadastro-email' name='cadastro-email'  class='valida-email' value='<?php echo $email; ?>' autocomplete='off' Style='width:98%' /></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:22.5%;'>
					<p class='div-pf'><b>Data Nascimento</b></p>
					<p class='div-pj'><b>Data Abertura</b></p>
					<p><input type='text' id='data-nascimento' name='data-nascimento' maxlength='10' class='formata-data' value='<?php echo $dataNascimento; ?>' autocomplete='off' Style='width:96.5%'/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:45%; height:auto;'>
					<p><b>Tipo Cadastro</b></p>
					<p>
						<select name="check-tipo-grupo-9[]" id="check-tipo-grupo-9" multiple>
							<?php echo optionValueGrupoMultiplo(9, $tipoCadastro); ?>
						</select>
					</p>
				</div>
<?php
	// TRECHO ABAIXO RESPONSAVEL POR COLOCAR CAMPOS ADICIONAIS SE MODULO DE IGREJA ATIVO
	if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
		require_once($caminhoFisico."/modulos/igreja/functions.php");
		exibeDadosComplementaresCadastroIgreja($cadastroID);
	}


	$percAreas = "22.5%";
	if (($_SESSION[dadosUserLogin][grupoID]=='-2') || ($_SESSION[dadosUserLogin][grupoID]=='-3') || ($configCadastros['exibir-vinculos']==0)){
		$escondeVinculos = "esconde";
		$percAreas = "45%";
	}


	echo "		<div class='titulo-secundario' Style='width:100%;float:left'>
					<div class='titulo-secundario' Style='width:55%;float:left;'>
						<p><b>Observa&ccedil;&otilde;es</b></p>
						<p class='omega'><textarea id='observacao' name='observacao' Style='width:98.5%;height:57px;'>".$observacao."</textarea></p>
					</div>
					<div class='titulo-secundario' style='width:".$percAreas."; float:left;'>
						<p><b>&Aacute;reas de Atua&ccedil;&atilde;o</b></p>
						<p><select name='check-tipo-grupo-34[]' id='check-tipo-grupo-34' multiple>".optionValueGrupoMultiplo(34, $areasAtuacoes)."</select></p>
					</div>";

	echo "			<div class='titulo-secundario $escondeVinculos' style='width:22.5%; float:left; height:auto;' >
						<p><b>Tipos de V&iacute;nculos</b></p>
						<p><select name='check-tipo-grupo-12[]' id='check-tipo-grupo-12' multiple>".optionValueGrupoMultiplo(12, $tipoVinculo)."</select></p>
					</div>
				</div>";
	if ($configCadastros['exibir-regionais']==0)
		$escondeRegionais = "esconde";

	echo "		<div class='titulo-secundario ".$escondeRegionais."' style='float:left; width:22.5%;'>
					<p><b>Regional</b></p>
					<p><select name='regional-id' id='regional-id'>".optionValueGrupo(45, $regionalID)."</select></p>
				</div>";

	if ($modulosAtivos['produtos']){
		if ($configCadastros['exibir-produto-tabelas-precos']==0)
			$escondeTabelasPrecos = "esconde";

		echo "	<div class='titulo-secundario $escondeTabelasPrecos' style='float:left; width:22.5%;'>
					<p><b>Tabela de valores vinculada ao cadastro:</b></p>
					<p>
						<select Style='width:100%;' name='tabela-preco-cliente' id='tabela-preco-cliente'>
							<option value=''>Nenhuma tabela vinculada</option>";
		$rs = mpress_query("select tc.Tabela_Preco_ID, Titulo_Tabela, d.Tabela_Preco_ID Tabela_Cliente from produtos_tabelas_precos tc left join cadastros_dados d on d.Tabela_Preco_ID = tc.Tabela_Preco_ID and Cadastro_ID = ".$cadastroID." where tc.Situacao_ID = 1 order by Titulo_Tabela");
		while($row = mpress_fetch_array($rs)){
			if($row['Tabela_Preco_ID'] == $row['Tabela_Cliente']) $selecionado = "selected"; else $selecionado = "";
			echo "			<option value='".$row['Tabela_Preco_ID']."' $selecionado>".$row['Titulo_Tabela']."</option>";
		}
		echo "				</select>
					</p>
				</div>";
	}

?>
			</div>
		</div>
	</div>
	<div id='div-retorno'></div>

<?php
	$classeMostraGrupo = 'esconde';
	if (($_GET['editar-acessos']==1) || ($_POST['editar-acessos']==1)){
		$classeMostraGrupo = ' grupo1 ';
	}
	echo "	<div class='titulo-container ".$classeMostraGrupo."'>
				<div class='titulo'>
					<p>Acessos</p>
				</div>
				<div class='conteudo-interno grupo1'>
					<input type='hidden' name='editar-acessos' id='editar-acessos' value='$editarAcessos'/>
					<div class='titulo-secundario' style='float:left; width:50%;'>
						<p><b>Grupo Acesso</b></p>
						<p>
							<select name='grupo-id' id='grupo-id'>
								<option value=''>Sem Acesso</option>";
	$grupos = mpress_query("select Modulo_Acesso_ID, Titulo from modulos_acessos where Situacao_ID = 1 order by Titulo");
	while($row = mpress_fetch_array($grupos)){
		if($grupoID==$row['Modulo_Acesso_ID'])$selecionado = "selected"; else $selecionado = "";
		echo " 					<option value='".$row['Modulo_Acesso_ID']."' $selecionado>".($row['Titulo'])."</option>";
	}
	echo "					</select>
						</p>
					</div>";
	echo "			<div class='titulo-secundario' style='float:left; width:50%;'>
						<div class='div-acesso'>
							<p><b>Senha</b></p>
							<p><input type='password' id='cadastro-senha' name='cadastro-senha' value='".$senha."' Style='width:94%' autocomplete='off'/></p>
						</div>&nbsp;
					</div>
				</div>
			</div>";

	/**************************/
	/* FORMULARIOS DINAMICOS  */
	/**************************/
	if ($modulosAtivos['formularios-dinamicos']){
		echo carregarFormulariosTela('cadastros_dados', $cadastroID);
	}

?>


	<div class="titulo-container grupo1" id='div-cadastros-enderecos'>
		<div class="titulo">
			<p>
				Endere&ccedil;os
				<input type='button' value='Incluir' id='botao-novo-endereco' class='btn-novo' style='cursor:pointer;float:right'/>
			</p>
		</div>
		<div class="conteudo-interno" id='conteudo-interno-endereco'>
			<div id="div-cadastro-endereco"></div>
			<div id="div-enderecos-cadastrados">
				<?php carregarEnderecos($cadastroID);?>
			</div>
		</div>
	</div>

	<div class="titulo-container grupo1" id='div-cadastros-telefones'>
		<div class="titulo">
			<p>
				Telefones
				<input type='button' value='Incluir' id='botao-novo-telefone' class='btn-novo' style='cursor:pointer;float:right'/>
			</p>
		</div>
		<div class="conteudo-interno" id='conteudo-interno-telefones'>
			<div id="div-cadastro-telefone"></div>
			<div id="div-telefones-cadastrados">
				<?php carregarTelefones($cadastroID);?>
			</div>
		</div>
	</div>

	<!-- INICIO Bloco Upload usando PLUPLOAD -->
	<div id='div-documentos'></div>
	<div id="container">
		<input type="hidden" id="pickfiles"/>
		<input type="hidden" id="uploadfiles"/>
	</div>
	<!-- FIM Bloco Upload usando PLUPLOAD -->

<?php
	$sql = "Select Tipo_Vinculo from cadastros_dados where Cadastro_ID = '$cadastroID'";
	$vinculos = mpress_query($sql);
	if($rowVinc = mpress_fetch_array($vinculos)){
		$tipos = unserialize($rowVinc['Tipo_Vinculo']);
		foreach ($tipos as $tipo) {
			$sql = "Select Descr_Tipo from tipo where Tipo_ID = ".$tipo;
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$titulo = $row['Descr_Tipo'];
			}
			echo "	<div class='titulo-container div-cadastros-vinculos grupo$tipo esconde' id='div-cadastros-vinculos-$tipo'>
						<div class='titulo'>
							<p>
								$titulo
								<input type='button' class='botao-incluir-vinculo editar-cadastro-generico' style='float:right;margin-right:0px;' campo-alvo='novo-vinculo' value='Incluir' parametro='$tipo'>
							</p>
						</div>
						<div class='conteudo-interno conteudo-interno-vinculos' id='div-cadastro-vinculo-$tipo'></div>
					</div>";
		}
	}
?>
<!--
		<div class="titulo-container grupo0 esconde" id='div-cadastros-complemento'>
			<div class="titulo">
				<p>
					Dados Complementares
					<input type='button' value='Salvar Cadastro' id='botao-cadastro-novo' class='cadastro-novo' Style='float:right;height:24px;font-size:10px;margin-top:-3px;' />
				</p>
			</div>
			<div class="conteudo-interno" id='conteudo-interno-complemento'>
			</div>
		</div>
-->
<?php
	if ($modulosAtivos[chamados]){
?>
		<div class="titulo-container conjunto7 esconde">
			<div class="titulo">
				<p>
					Or&ccedil;amentos
					<input type="button" value="Incluir Novo" class="orcamento-localiza" workflow-id='' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
				</p>
			</div>
			<div class="conteudo-interno">
				<div class="titulo-secundario uma-coluna">
					<?php carregarOrcamentosCadastro($cadastroID);?>
				</div>
			</div>
		</div>

		<div class="titulo-container grupo4 esconde" id='div-cadastros-complemento'>
			<div class="titulo">
				<p>
					<?php echo $_SESSION['objeto'];?>
					<?php	if($_SESSION[dadosUserLogin][grupoID] != -3){?>
						<input type="button" value="Incluir Novo" class='workflow-localiza' name='' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
					<?php	}?>
				</p>
			</div>
			<div class="conteudo-interno" id='conteudo-interno-complemento'>
					<?php carregarChamados($cadastroID); ?>
			</div>
		</div>
<?php
	}
	if (($_SESSION[dadosUserLogin][grupoID]!='-2') && ($_SESSION[dadosUserLogin][grupoID]!='-3') && ($configCadastros['exibir-vinculos']==1)){
		echo "	<div class='titulo-container grupo1' id='div-cadastros-complemento'>
					<div class='titulo'>
						<p>V&iacute;nculos</p>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-complemento'>
						<div class='titulo-secundario' style='float:left; width:100%;'>";

		$rs = mpress_query("select tv.Descr_Tipo as Vinculo, cd.Nome as Nome, cd.Cadastro_ID as Cadastro_ID, cd.Codigo as Codigo, cd.Email as Email, ct.Telefone, cv.Tipo_Vinculo_ID as Tipo_Vinculo_ID
							from cadastros_vinculos cv
							inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID
							inner join tipo tv on tv.Tipo_ID = cv.Tipo_Vinculo_ID
							left join cadastros_telefones ct on ct.Cadastro_ID = cv.Cadastro_ID
							where Cadastro_Filho_ID = '$cadastroID'
							and cv.Situacao_ID = 1
							and tv.Situacao_ID in(1,3)
							and cd.Situacao_ID = 1");
		while($row = mpress_fetch_array($rs)){
			if (($row[Cadastro_ID] != $cadastroIDAnt)&&($row[Tipo_Vinculo_ID] != $tipoVinculoIDAnt)){
				$i++;
				$nome = $row[Nome];
				$dados[colunas][conteudo][$i][1] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Vinculo]."</p></span>";
				$dados[colunas][conteudo][$i][2] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$nome."</p></span>";
				$dados[colunas][conteudo][$i][3] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p></span>";
				$dados[colunas][conteudo][$i][4] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p></span>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Email]."</p>";
				$telefones = "";
			}
			$telefones .= "<span Style='margin:2px 5px 0 5px; float:left;'>".$row['Telefone']."</span>";
			$dados[colunas][conteudo][$i][7] = $telefones;
			$cadastroIDAnt = $row[Cadastro_ID];
			$tipoVinculoIDAnt = $row[Tipo_Vinculo_ID];
		}
		if($i==0){
			echo "<p Style='text-align:center'>Nenhum vinculo localizado</p>";
		}
		else{
			$largura = "100.2%";
			$colunas = "7";
			$dados[colunas][tamanho][3] = "width='6%'";
			$dados[colunas][tamanho][4] = "width='10%'";

			$dados[colunas][titulo][1] 	= "Vinculo";
			$dados[colunas][titulo][2] 	= "Nome";
			$dados[colunas][titulo][3] 	= "ID";
			$dados[colunas][titulo][4] 	= "C&oacute;digo";
			$dados[colunas][titulo][5] 	= "Cpf/Cnpj";
			$dados[colunas][titulo][6] 	= "Email";
			$dados[colunas][titulo][7] 	= "Telefones";
			geraTabela($largura,$colunas,$dados);
		}

		echo "			</div>
					</div>
				</div>
			</div>";
	}
	/*
	if($modulosAtivos[turmas]){
		echo "	<input type='hidden' id='id-turma' name='id-turma'>
				<div class='titulo-container grupo5 esconde' id='div-cadastros-dados-academicos'>
					<div class='titulo'>
						<p>
							Dados Acadêmicos
						</p>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-complemento'>
						<div class='titulo-secundario uma-coluna'>".vinculoAlunoTurmaCadastro($cadastroID)."</div>
					</div>
				</div>";
	}
	*/

	/*************/
	/**** CRM ****/
	/*************/
	if ($configChamados['crm']=='1'){
		if ($cadastroID!=''){
			if ($modulosAtivos['projetos']){
				echo "	<div class='titulo-container conjunto9 esconde' id='div-tarefas-cadastradas-geral'>";
				carregarTarefas($cadastroID, 'cadastros_dados', 'Cadastro_ID', $cadastroID);
				echo "	</div>";
			}
			echo "	<div class='titulo-container conjunto9 esconde'>
						<div class='titulo'>
							<p>Hist&oacute;rico</p>
						</div>
						<!--
						<div id='div-cadastrar-follow' class='conteudo-interno'>
							<div class='titulo-secundario' style='width:100%;'>
								<div class='titulo-secundario' style='width:100%;'>
									<p><b>Descri&ccedil;&atilde;o</b></p>
									<p class='omega'><textarea id='cadastro-descricao-follow' name='cadastro-descricao-follow' class='required' style='height:60px;width:99.3%'></textarea></p>
								</div>
							</div>
							<div style='width:100%;'>
								<div style='width:15%;float:right;'>
									<p><input type='button' value='Incluir Follow' class='salvar-cadastro-follow' Style='width:95%;'/></p>
								</div>
								<div style='width:15%;float:right;'>
									<p>
										<input type='checkbox' id='cadastro-follow-incluir-tarefa' name='cadastro-follow-incluir-tarefa' value='1' checked='checked'/>
										<b><label for='cadastro-follow-incluir-tarefa'>Incluir Tarefa?</label></b>
									</p>
								</div>
							</div>
						</div>
						-->
						<div class='conteudo-interno'>
							<div class='cadastro-follows-historico'>";
			echo carregarFollowsGerais($cadastroID);
			echo "
							</div>
						</div>
					</div>";
		}
	}
?>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
	<p>&nbsp;</p>
</div>
