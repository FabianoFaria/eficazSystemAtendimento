<?php
	if($_POST){
		$id = $_POST['localiza-cadastro-id'];
		$tipoPessoa = $_POST['localiza-tipo-pessoa'];
		$codigo = $_POST['localiza-codigo'];
		$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
		$nomeCompleto = $_POST['localiza-nome-completo'];
		$areasAtuacoes = $_POST['localiza-areas-atuacoes'];
		$cpf = $_POST['localiza-cpf'];
		$cnpj = $_POST['localiza-cnpj'];
		$email = $_POST['localiza-email'];
		$situacao = $_POST['localiza-situacao'];
		$cargos = $_POST['localiza-cargos'];
	}
	else{
		$situacao = "1";
	}

	if ($id != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
	if ($codigo != ""){ $sqlCond .= " and cd.Codigo like '%$codigo%' ";}
	if ($tipoCadastro != ""){ $sqlCond .= " and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";}
	if ($areasAtuacoes != ""){ $sqlCond .= " and cd.Areas_Atuacoes like '%s:".strlen($areasAtuacoes).":\"".$areasAtuacoes."\"%'";}
	if ($cargos != ""){ $sqlCond .= " and cd.Cargo_ID = '$cargos'";}
	if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
	if ($cpf != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cpf)))."' or cd.Cpf_Cnpj = '$cpf') ";}
	if ($cnpj != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cnpj)))."' or cd.Cpf_Cnpj = '$cnpj') ";}
	if ($email != ""){ $sqlCond .= " and cd.Email like '%$email%'";}
	if ($situacao != ""){ $sqlCond .= " and cd.Situacao_ID = '$situacao'";}
	if ($tipoPessoa != ""){ $sqlCond .= " and cd.Tipo_Pessoa = '$tipoPessoa'";}

	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by Nome";
	}
	if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) or cd.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."')";
	if($_SESSION[dadosUserLogin][grupoID] == -3) $sqlCond .= " and cd.Cadastro_ID = ".$_SESSION[dadosUserLogin][userID]." ";
	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>
							Filtros de Pesquisa";
	if($_SESSION[dadosUserLogin][grupoID] != -3)
		echo "					<input type='button' value='Incluir Cadastro' name='' class='cadastro-localiza' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>";
	echo "				</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario' style='width:22%; float:left;'>
								<p>ID</p>
								<p><input type='text' name='localiza-cadastro-id' id='localiza-cadastro-id' class='formata-numero' value='".$id."' style='width:80%;'></p>
							</div>
							<div class='titulo-secundario' style='width:77%; float:left;'>
								<p>C&oacute;digo</p>
								<p><input type='text' id='localiza-codigo' name='localiza-codigo'  maxlength='10' value='".$codigo."'/></p>
							</div>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Nome Completo</p>
							<p><input type='text' id='localiza-nome-completo' name='localiza-nome-completo'  maxlength='250' value='".$nomeCompleto."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>Tipo Pessoa</p>
							<p><select name='localiza-tipo-pessoa' id='localiza-tipo-pessoa'>".optionValueGrupo(8, $tipoPessoa,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>Tipo Cadastro</p>
							<p><select name='localiza-tipo-cadastro-id' id='localiza-tipo-cadastro-id'>".optionValueGrupo(9, $tipoCadastro,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Email</p>
							<p><input type='text' id='localiza-email'  name='localiza-email' maxlength='200' value='".$email."'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>CPF</p>
							<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' class='localiza-mascara-cpf' value='".$cpf."'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>CNPJ</p>
							<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' class='localiza-mascara-cnpj' value='".$cnpj."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>&Aacute;rea de Atua&ccedil;&atilde;o</p>
							<p><select name='localiza-areas-atuacoes' id='localiza-areas-atuacoes'>".optionValueGrupo(34, $areasAtuacoes,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>Cargo</p>
							<p><select name='localiza-cargos' id='localiza-cargos'>".optionValueGrupo(42, $cargos,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario duas-colunas'>
								<p>Situa&ccedil;&atilde;o</p>
								<p><select name='localiza-situacao' id='localiza-situacao'>".optionValueGrupo(1, $situacao, 'Todos', 'and Tipo_ID IN(1,3,142)')."</select></p>
							</div>
							<div class='titulo-secundario duas-colunas' Style='margin-top:15px;'>
								<p class='direita'><input type='button' value='Pesquisar' id='botao-pesquisar-cadastros' class='cadastra-grupos-produtos'/></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
	//if($_POST){
		$arrTiposCadastros = carregarArrayTipo(9);

		$sql = "select cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
				Inscricao_Municipal, Inscricao_Estadual, cd.Usuario_Cadastro_ID, Codigo, coalesce(cf.Telefone,'') as Telefone, cf.Observacao, cd.Email as Email, cd.Situacao_ID as Situacao_ID,
				cd.Tipo_Cadastro as Tipo_Cadastro_Array
				from cadastros_dados cd
				left join cadastros_telefones cf on cf.Cadastro_ID = cd.Cadastro_ID and cf.Situacao_ID = 1
				left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				where cd.Situacao_ID <> 2
				and cd.Cadastro_ID > 0
				$sqlCond $ordem";
		//echo $sql;
		$cadastroIDAnt = "";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row[Cadastro_ID] != $cadastroIDAnt){
				if ($row[Situacao_ID]==3) $classe = "lixeira"; else $classe = "";
				$i++;
				$tipoCadastro = "";
				foreach(unserialize($row['Tipo_Cadastro_Array']) as $chave => $tipo){
					if ($arrTiposCadastros[descricao][$tipo]!=''){
						$tipoCadastro .= $arrTiposCadastros[descricao][$tipo].", ";
					}
				}
				$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='cadastro-localiza lnk' cadastro-id='".$row[Cadastro_ID]."'";

				$dados[colunas][conteudo][$i][1] = "<p class='$classe' Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p>";
				$dados[colunas][conteudo][$i][2] = "<p class='$classe' Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p class='$classe' Style='margin:2px 5px 0 5px;float:left;'>".$row[Nome]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row[Tipo_Pessoa]."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row[Cpf_Cnpj]."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 0 0 5px;float:left; font-size:10px;'>".substr($tipoCadastro,0,-2)."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Email]."</p>";
				$telefones = "";
			}
			$telefones .= "<span Style='margin:2px 5px 0 5px;float:left;'>".$row['Telefone']."</span>";
			$dados[colunas][conteudo][$i][8] = $telefones;
			$cadastroIDAnt = $row[Cadastro_ID];
		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
		}
		else{
			$largura = "100.2%";
			$colunas = "8";
			$dados[colunas][tamanho][1] = "width='6%'";
			$dados[colunas][tamanho][2] = "width='6%'";
			$dados[colunas][tamanho][4] = "width='100px'";

			$dados[colunas][titulo][1] 	= "ID";
			$dados[colunas][titulo][2] 	= "C&oacute;digo";
			$dados[colunas][titulo][3] 	= "Nome";
			$dados[colunas][titulo][4] 	= "Tipo";
			$dados[colunas][titulo][5] 	= "Cpf / Cnpj";
			$dados[colunas][titulo][6] 	= "Tipo Cadastro";
			$dados[colunas][titulo][7] 	= "Email";
			$dados[colunas][titulo][8] 	= "Telefones";

			$dados[colunas][ordena][1] = "Cadastro_ID";
			$dados[colunas][ordena][2] = "cast(codigo AS SIGNED)";
			$dados[colunas][ordena][3] = "Nome";
			$dados[colunas][ordena][4] = "Tipo_Pessoa";
			$dados[colunas][ordena][5] = "Cpf_Cnpj";
			$dados[colunas][ordena][7] = "Email";

	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Registros Localizados: $i</p>
					</div>
					<div class='conteudo-interno'>";
	geraTabela($largura,$colunas,$dados, null, 'cadastro-localiza', 2, 2, 100,1);
	echo "			</div>
				</div>
			</div>";
		}
	//}
?>