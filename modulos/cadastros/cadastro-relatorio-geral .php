<?php
	//$dadospagina = get_page_content();
	//require_once("functions.gerais.php");
	global $caminhoSistema;
	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1) $classeEmpresasEsconde = " esconde ";

	if($_POST){
		$id = $_POST['localiza-cadastro-id'];
		$codigo = $_POST['localiza-codigo'];
		$nomeCompleto = $_POST['localiza-nome-completo'];
		$areasAtuacoes = $_POST['localiza-areas-atuacoes'];
		$cpf = $_POST['localiza-cpf'];
		$cnpj = $_POST['localiza-cnpj'];
		$email = $_POST['localiza-email'];
		$situacao = $_POST['localiza-situacao'];
		$cargos = $_POST['localiza-cargos'];
		$cidade = $_POST['localiza-cidade'];

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-vinculos-cadastros']); $i++){
			$vinculosCadastros .= $virgula.$_POST['localiza-vinculos-cadastros'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-vinculos']); $i++){
			$vinculos .= $virgula.$_POST['localiza-vinculos'][$i];
			$virgula = ",";
		}
		$virgula = "";

		if (count($_POST['localiza-tipo-cadastro'])>0){
			$sqlCondTipo = "and (";
			for($i = 0; $i < count($_POST['localiza-tipo-cadastro']); $i++){
				$tipoAux = $_POST['localiza-tipo-cadastro'][$i];
				$tipoCadastro .= $virgula.$_POST['localiza-tipo-cadastro'][$i];
				$sqlCondTipo .= $or." cd.Tipo_Cadastro like '%s:".strlen($tipoAux).":\"".$tipoAux."\"%'";
				$or = " or ";
				$virgula = ",";
			}
			$sqlCondTipo .= ")";
		}

		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-uf']); $i++){
			$ufs .= $virgula."'".$_POST['localiza-uf'][$i]."'";
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-centro-custo']); $i++){
			$centrosCustos .= $virgula.$_POST['localiza-centro-custo'][$i];
			$virgula = ",";
		}
	}
	else{
		$situacao = "1";
		$classeEsconde = "esconde";
	}


	$sql = "select distinct cd.Cadastro_ID, cd.Nome as Nome from cadastros_vinculos cv
			inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID and cd.Empresa = 1
			where cv.Situacao_ID = 1 and cd.Situacao_ID = 1
			order by cd.Nome ";
	$resultado = mpress_query($sql);
	$v = 0;
	while($row = mpress_fetch_array($resultado)){
		$v++;
		$arrayVinculosCadastros[$v]['value'] = $row[Cadastro_ID];
		$arrayVinculosCadastros[$v]['descricao'] = $row[Nome];
	}


	$sql = "select distinct cv.Tipo_Vinculo_ID, t.Descr_Tipo as Vinculo from cadastros_vinculos cv
		inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID and cd.Empresa = 1
		inner join tipo t on cv.Tipo_Vinculo_ID = t.Tipo_ID
		where cv.Situacao_ID = 1 and cd.Situacao_ID = 1 and t.Situacao_ID = 1
		order by t.Descr_Tipo ";
	$resultado = mpress_query($sql);
	$v = 0;
	while($row = mpress_fetch_array($resultado)){
		$v++;
		$arrayVinculos[$v]['value'] = $row[Tipo_Vinculo_ID];
		$arrayVinculos[$v]['descricao'] = $row[Vinculo];
	}



	if ($id != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
	if ($codigo != ""){ $sqlCond .= " and cd.Codigo like '%$codigo%' ";}
	if ($tipoCadastro != ""){ $sqlCond .= $sqlCondTipo;}
	if ($areasAtuacoes != ""){ $sqlCond .= " and cd.Areas_Atuacoes like '%s:".strlen($areasAtuacoes).":\"".$areasAtuacoes."\"%'";}
	if ($cargos != ""){ $sqlCond .= " and cd.Cargo_ID = '$cargos'";}
	if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
	if ($cpf != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cpf)))."' or cd.Cpf_Cnpj = '$cpf') ";}
	if ($cnpj != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cnpj)))."' or cd.Cpf_Cnpj = '$cnpj') ";}
	if ($email != ""){ $sqlCond .= " and cd.Email like '%$email%'";}
	if ($situacao != ""){ $sqlCond .= " and cd.Situacao_ID = '$situacao'";}
	if ($centrosCustos != ""){ $sqlCond .= " and cd.Centro_Custo_ID IN ($centrosCustos)";}


	if (($vinculosCadastros != "") && ($vinculos != "")){
		if ($vinculosCadastros != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID in ($vinculosCadastros) and Tipo_Vinculo_ID in ($vinculos) and Situacao_ID = 1)";}
	}
	else{
		if ($vinculosCadastros != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID in ($vinculosCadastros) and Situacao_ID = 1)";}
		if ($vinculos != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Tipo_Vinculo_ID in ($vinculos) and Situacao_ID = 1)";}
	}

	if (($cidade!="") || ($ufs!="")){
		$sqlCond .= " and cd.Cadastro_ID in (select Cadastro_ID from cadastros_enderecos ce where ce.Situacao_ID = 1";
		if ($cidade!=""){ $sqlCond .= " and ce.Cidade like '$cidade%'";}
		if ($ufs!=""){ $sqlCond  .= " and ce.UF in ($ufs)";}
		$sqlCond .= ")";
	}


	/*if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by Nome";
	}*/
	if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) or cd.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."')";
	if($_SESSION[dadosUserLogin][grupoID] == -3) $sqlCond .= " and cd.Cadastro_ID = ".$_SESSION[dadosUserLogin][userID]." ";
	echo "	<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Geral'>
			<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo' style='min-height:25px'>
						<p style='margin-top:2px;'>
							Filtros de Pesquisa
						</p>
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
						<div class='titulo-secundario quatro-colunas'>
							<p>Tipo Cadastro</p>
							<p><select name='localiza-tipo-cadastro[]' id='localiza-tipo-cadastro[]' multiple style='height:70px;'>".optionValueGrupoMultiplo(9, $tipoCadastro,'')."</select></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario duas-colunas'>
								<p>Email</p>
								<p><input type='text' id='localiza-email'  name='localiza-email' maxlength='200' value='".$email."'/></p>
							</div>
							<div class='titulo-secundario duas-colunas' Style='margin-top:15px;'>
								<p class='direita'><input type='button' value='Pesquisar' id='botao-pesquisar-cadastros' class='cadastra-grupos-produtos' onclick='pesquisar();'/></p>
							</div>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>CPF</p>
							<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' style='width:89%;' class='localiza-mascara-cpf' value='".$cpf."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>CNPJ</p>
							<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' style='width:89%;' class='localiza-mascara-cnpj' value='".$cnpj."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>&Aacute;rea de Atua&ccedil;&atilde;o</p>
							<p><select name='localiza-areas-atuacoes' id='localiza-areas-atuacoes'>".optionValueGrupo(34, $areasAtuacoes,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>Situa&ccedil;&atilde;o</p>
							<p><select name='localiza-situacao' id='localiza-situacao' style='width:92%;'>".optionValueGrupo(1, $situacao, 'Todos', 'and Tipo_ID IN(1,3)')."</select></p>
						</div>
						<div class='titulo-secundario quatro-colunas' style='margin-top:40px;height:5px;'>
							<p>&nbsp;</p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario duas-colunas'>
								<p>Separar por:</p>
								<p>
									<select id='separar-por' name='separar-por' style='width:92%'>
										<option value='cad' ".verificaSelected($_POST['separar-por'],'cad').">Cadastros</option>
										<option value='cc' ".verificaSelected($_POST['separar-por'],'cc').">Centros de Custo</option>
									</select>
								</p>
							</div>
							<div class='titulo-secundario duas-colunas'>
								<!--
								<div class='btn-excel $classeEsconde' id='botao-salvar-excel' style='float:left;' title='Gerar Excel'></div>&nbsp;
								<div class='btn-imprimir $classeEsconde' id='botao-imprimir' style='float:left;' title='Imprimir'></div>&nbsp;
								-->
							</div>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario' style='width:25%; float:left;'>
								<p>UF</p>
								<p><select name='localiza-uf[]' id='localiza-uf[]' multiple style='height:70px'>".optionValueGrupoMultiploUF(str_replace("'","",$ufs))."</select></p>
							</div>
							<div class='titulo-secundario' style='width:75%; float:left;'>
								<p>Cidade</p>
								<p><input type='text' id='localiza-cidade'  name='localiza-cidade' maxlength='200' style='width:92%;' value='".$cidade."'/></p>
							</div>
						</div>
						<div class='titulo-secundario quatro-colunas' style='height:90px'>
							<div class='$classeEmpresasEsconde'>
								<p>Centros de Custo:</p>
								<p><select name='localiza-centro-custo[]' id='localiza-centro-custo' multiple Style='height:70px'>".optionValueGrupoMultiplo(26, $centrosCustos)."</select></p>
							</div>
							&nbsp;
						</div>
						<div class='titulo-secundario quatro-colunas' style='height:90px'>
							<p>Vinculadas ao Cadastro</p>
							<p><select name='localiza-vinculos-cadastros[]' id='localiza-vinculos-cadastros[]' multiple style='height:70px'>".optionValueArrayMultiplo($arrayVinculosCadastros,$vinculosCadastros)."</select></p>
						</div>
						<div class='titulo-secundario oito-colunas' style='height:90px'>
							<p>Tipo Vinculo</p>
							<p><select name='localiza-vinculos[]' id='localiza-vinculos[]' multiple style='height:70px; width:92%;'>".optionValueArrayMultiplo($arrayVinculos,$vinculos)."</select></p>
						</div>
					</div>
				</div>
			</div>
			<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
	if($_POST){

		if ($_POST['separar-por']=='cc'){
			$ordem= " order by cd.Centro_Custo_ID ";
		}else{
			$ordem = " order by cd.Nome ";
		}

		$sql = "select cd.Cadastro_ID as Cadastro_ID, cd.Centro_Custo_ID, tcc.Descr_Tipo as Centro_Custo, cr.Nome as Responsavel, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, cd.Nome, cd.Nome_Fantasia, cd.Senha, cd.Data_Nascimento, cd.Cpf_Cnpj,
				cd.Inscricao_Municipal, cd.Inscricao_Estadual, cd.Usuario_Cadastro_ID, cd.Codigo, cd.Email as Email, cd.Situacao_ID as Situacao_ID, cd.Foto, tr.Descr_Tipo as Regional
				from cadastros_dados cd
				left join tipo tp on tp.Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				left join tipo tcc on tcc.Tipo_ID = cd.Centro_Custo_ID and tcc.Tipo_Grupo_ID = 26
				left join cadastros_dados cr on cr.Cadastro_ID = tcc.Tipo_Auxiliar
				left join tipo tr on tr.Tipo_ID = cd.Regional_ID
				where cd.Cadastro_ID > 0
				$sqlCond
				$ordem";

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row[Foto]!="")
				$arrayCadastros[$row[Cadastro_ID]]['Foto'] = "<img src='$caminhoSistema/uploads/".$row[Foto]."' width='20px' id='imagem-foto'>";
			else
				$arrayCadastros[$row[Cadastro_ID]]['Foto'] = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' width='20px' id='imagem-foto'>";

			$arrayCadastros[$row[Cadastro_ID]]['Codigo'] 		= $row[Codigo];
			$arrayCadastros[$row[Cadastro_ID]]['Nome'] 			= $row[Nome];
			$arrayCadastros[$row[Cadastro_ID]]['Tipo'] 			= $row[Tipo_Pessoa];
			$arrayCadastros[$row[Cadastro_ID]]['CpfCnpj'] 		= $row[Cpf_Cnpj];
			$arrayCadastros[$row[Cadastro_ID]]['Email'] 		= $row[Email];
			$arrayCadastros[$row[Cadastro_ID]]['CentroCustoID'] = $row[Centro_Custo_ID];
			$arrayCadastros[$row[Cadastro_ID]]['CentroCusto'] 	= $row[Centro_Custo];
			$arrayCadastros[$row[Cadastro_ID]]['Regional'] 		= $row[Regional];
			$arrayCadastros[$row[Cadastro_ID]]['Responsavel'] 	= $row[Responsavel];
			$arrayCadastros[$row[Cadastro_ID]]['Situacao'] 		= $row[Situacao_ID];

		}

		$classe = "link";
		$virgula = ", ";

		$sql = "select cd.Cadastro_ID, ct.Telefone
				from cadastros_dados cd
				left join cadastros_telefones ct on ct.Cadastro_ID = cd.Cadastro_ID
				left join tipo tp on tp.Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				left join tipo tcc on tcc.Tipo_ID = cd.Centro_Custo_ID and tcc.Tipo_Grupo_ID = 26
				left join cadastros_dados cr on cr.Cadastro_ID = tcc.Tipo_Auxiliar
				where cd.Cadastro_ID > 0
				and ct.Situacao_ID = 1
				$sqlCond $ordem";
		$resultado = mpress_query($sql);
		while($rowTel = mpress_fetch_array($resultado)){
			$arrayCadastros[$rowTel[Cadastro_ID]]['N_Fones']++;
			if($arrayCadastros[$rowTel[Cadastro_ID]]['N_Fones'] > 1)
				$arrayCadastros[$rowTel[Cadastro_ID]]['Telefones'] 	.= $virgula.$rowTel[Telefone];
			else
				$arrayCadastros[$rowTel[Cadastro_ID]]['Telefones'] 	.= $rowTel[Telefone];
		}

		$sql = "select cd.Cadastro_ID, concat(ce.Logradouro, ', ', ce.Numero, ', ', ce.Bairro, ' - ', ce.Cidade, '/', ce.UF) as Endereco
				from cadastros_dados cd
				left join cadastros_enderecos ce on ce.Cadastro_ID = cd.Cadastro_ID
				left join tipo tp on tp.Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				left join tipo tcc on tcc.Tipo_ID = cd.Centro_Custo_ID and tcc.Tipo_Grupo_ID = 26
				left join cadastros_dados cr on cr.Cadastro_ID = tcc.Tipo_Auxiliar
				where cd.Cadastro_ID > 0
				and ce.Situacao_ID = 1
				$sqlCond $ordem";
		$resultado = mpress_query($sql);
		while($rowEnd = mpress_fetch_array($resultado)){
			$arrayCadastros[$rowEnd[Cadastro_ID]]['N_End']++;
			if($arrayCadastros[$rowEnd[Cadastro_ID]]['N_End'] > 1)
				$arrayCadastros[$rowEnd[Cadastro_ID]]['Enderecos'] 	.= "<br>".$rowEnd[Endereco];
			else
				$arrayCadastros[$rowEnd[Cadastro_ID]]['Enderecos'] 	.= $rowEnd[Endereco];
		}

		$i=0;

		//$dados[colunas][tamanho][1] = "width='20px' height='30px'";
		//$dados[colunas][tamanho][2] = "width='6%'";
		//$dados[colunas][tamanho][3] = "width='10%'";
		//$dados[colunas][tamanho][4] = "width='100px'";

		$dados[colunas][titulo][classe] = "esconde";
		//$dados[colunas][titulo][1] 	= "Foto";
		$dados[colunas][titulo][1] 	= "Centro de Custo";
		$dados[colunas][titulo][2] 	= "ID";
		$dados[colunas][titulo][3] 	= "C&oacute;digo";
		$dados[colunas][titulo][4] 	= "Tipo";
		$dados[colunas][titulo][5] 	= "Nome";
		$dados[colunas][titulo][6] 	= "Cpf / Cnpj";
		$dados[colunas][titulo][7] 	= "Email";
		$dados[colunas][titulo][8] 	= "Telefone";
		$dados[colunas][titulo][9] 	= "Endereço";
		$dados[colunas][titulo][10] = "Regional";

		$dados[colunas][ordena][2] = "Cadastro_ID";
		$dados[colunas][ordena][3] = "cast(codigo AS SIGNED)";
		$dados[colunas][ordena][4] = "Tipo_Pessoa";
		$dados[colunas][ordena][5] = "Nome";
		$dados[colunas][ordena][6] = "Cpf_Cnpj";
		$dados[colunas][ordena][7] = "Email";


		foreach($arrayCadastros as $cadastro_ID => $dadosCadastro){
			if (($_POST['separar-por']=='cc') && ($dadosCadastro['CentroCustoID']!=$centroCustoIDAnt)){
				$i++;
				$dados[colunas][classe][$i] = "destaque-tabela";
				$dados[colunas][conteudo][$i][1] = "	<p align='center' style='margin:2px 2px 2px 2px;'>".$dadosCadastro['CentroCusto']."</p>
														<p align='center' style='margin:2px 2px 2px 2px;'>".$dadosCadastro['Responsavel']."</p>";
				$dados[colunas][colspan][$i][1] = "10";
			}
			$i++;
			$cont++;
			if ($dadosCadastro['Situacao']=="1") $classe = "link"; else $classe = "lixeira";
			//$dados[colunas][conteudo][$i][1] = "<span class='$classe cadastro-localiza' cadastro-id='".$cadastro_ID."'><p width='30px' height='40px' class='cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'>".$dadosCadastro['Foto']."</p></span>";
			$dados[colunas][conteudo][$i][1] = "&nbsp;";
			$dados[colunas][conteudo][$i][2] = "<span class='$classe cadastro-localiza' cadastro-id='".$cadastro_ID."'><p Style='margin:2px 2px 0 2px;'>".$cadastro_ID."</p></span>";
			$dados[colunas][conteudo][$i][3] = "<span class='$classe cadastro-localiza' cadastro-id='".$cadastro_ID."'><p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Codigo']."</p></span>";
			$dados[colunas][conteudo][$i][4] = "<p class='$classe' Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Tipo']."</p>";
			$dados[colunas][conteudo][$i][5] = "<span class='$classe cadastro-localiza' cadastro-id='".$cadastro_ID."'><p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Nome']."</p></span>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 2px 0 2px; '>".$dadosCadastro['CpfCnpj']."</p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Email']."</p>";
			$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Telefones']."</p>";
			$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Enderecos']."</p>";
			$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 2px 0 2px;'>".$dadosCadastro['Regional']."</p>";
			$centroCustoIDAnt = $dadosCadastro['CentroCustoID'];
		}

		echo "	<div id='cadastros-container'>
					<div class='titulo-container'>
						<div class='titulo' style='min-height:25px'>
							<p style='margin-top:2px;'>
								Registros localizados: $cont
							</p>
						</div>
						<div class='conteudo-interno'>
							<div class='titulo-secundario uma-coluna'>";

		//echo "<pre>";
		//print_r($dados);
		//echo "</pre>";

		geraTabela("100.2%", "10", $dados, null, 'cadastro-localiza', 2, 2, 500,1);
		echo "				</div>
						</div>
					</div>
				</div>";
	}
?>







