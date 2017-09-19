<?php
	function validaLogin($dados){
		if ($dados['cadastroID']!=""){
			$resultado = mpress_query("select Cadastro_ID, Grupo_ID, Nome, Nome_Fantasia,
									Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Email, Foto, Observacao, Usuario_Cadastro_ID, Usuario_Alteracao_ID,
									Data_Inclusao, Data_Alteracao, Ultimo_Login, Tabela_Preco_ID, Areas_Atuacoes, Regional_ID, Cargo_ID, Empresa, Situacao_ID
									from cadastros_dados where Cadastro_ID = '".$dados['cadastroID']."'");
		}
		else{
			$resultado = mpress_query("select Cadastro_ID, Grupo_ID, Nome, Nome_Fantasia,
								Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Email, Foto, Observacao, Usuario_Cadastro_ID, Usuario_Alteracao_ID,
								Data_Inclusao, Data_Alteracao, Ultimo_Login, Tabela_Preco_ID, Areas_Atuacoes, Regional_ID, Cargo_ID, Empresa, Situacao_ID
								from cadastros_dados where email = '".$dados['login']."' and senha = '".$dados['senha']."' and Situacao_ID IN (1,142,-1)");
		}
		if($row = mpress_fetch_array($resultado)){
			if ($row['Situacao_ID']=='142'){
				$dado['erro'] = "Cadastro pendente de aprova&ccedil;&atilde;o";
			}
			else{
				if ($row['Grupo_ID']==0){
					$dado['erro'] = "Cadastro sem permiss&atildeo de acesso";
				}
				else{
					$dado['dadoslogin']['userID']			= $row['Cadastro_ID'];
					$dado['dadoslogin']['grupoID']			= $row['Grupo_ID'];
					$dado['dadoslogin']['nome'] 			= $row['Nome'];
					$dado['dadoslogin']['nomeFantasia']		= $row['Nome_Fantasia'];
					$dado['dadoslogin']['email']			= $row['Email'];
					$dado['dadoslogin']['ultimoLogin']		= converteDataHora($row['Ultimo_Login'],1);


					$aux = carregarConfiguracoesSite();
					//$dado['chamados']['config']['padroes'] = $aux['chamados'];

					if ($dados['modulo'] == "chamados"){
						$dado['chamados']['config']['nome-modulo'] = $_SESSION['objeto'];
						$dado['chamados']['config']['tipos'] = carregarTiposNiveisSistema(19, $aux['chamados']['tipo-chamado-padrao']);
						$dado['chamados']['config']['situacoes'] = carregarTiposSistema(18);

						//$dado['chamados']['config']['tipo-padrao'] = carregarTiposSistema(18);
					}


					//$dado['modulos'] = carregarModulosSistema();
					//$dado['tipos'] = carregarTiposSistema();

					//$dado['cadastro']['dados'] 	= cadastrosLocaliza($row['Cadastro_ID']);
					//$dado['cadastro']['enderecos'] = enderecosLocaliza($row['Cadastro_ID']);
					//$dado['cadastro']['telefones'] = telefonesLocaliza($row['Cadastro_ID']);
					//$dado['cadastro']['vinculos'] = vinculosLocaliza($row['Cadastro_ID']);
				}
				//$dado['produtos']['categorias'] = vinculosLocaliza($row['Cadastro_ID']);
			}
		}
		else{
			$resultado = mpress_query("select Email from cadastros_dados where email = '".$dados['login']."' and Situacao_ID IN (1,-1)");
			if ($row = mpress_fetch_array($resultado))
				$dado['erro'] = "Senha n&atilde;o confere com e-mail cadastrado";
			else
				$dado['erro'] = "Seu login de acesso n&atilde;o  &eacute; valido. Por favor, verifique se voc&ecirc; escreveu corretamente e tente de novamente";
		}
		//echo "<pre>";
		//print_r($dado);
		//echo "</pre>";
		echo serialize($dado);
		return serialize($dado);
	}

	//function

	function cadastrosLocaliza($id){
		$i = 0;
		$resultado = mpress_query("select Cadastro_ID, Nome, Nome_Fantasia, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual,
								Tipo_Vinculo, Email, Foto, Observacao, Usuario_Cadastro_ID, Usuario_Alteracao_ID,
								Data_Inclusao, Data_Alteracao, Ultimo_Login, Tabela_Preco_ID, Areas_Atuacoes, Regional_ID, Cargo_ID, Empresa, Situacao_ID
								from cadastros_dados
								where Cadastro_ID = '$id'");
		if($row = mpress_fetch_array($resultado)){
			$i++;
			$dado[$i]['cadastroID'] = $row['Cadastro_ID'];
			$dado[$i]['nome'] 	= $row['Nome'];
			$dado[$i]['nomefantasia'] = $row['Nome_Fantasia'];
			$dado[$i]['email'] = $row['Email'];
			$dado[$i]['rg'] = $row['RG'];
			$dado[$i]['inscricaomunicipal'] = $row['Inscricao_Municipal'];
			$dado[$i]['inscricaoestadual'] = $row['Inscricao_Estadual'];
			$dado[$i]['cpfcnpj'] = $row['Cpf_Cnpj'];
			$dado[$i]['situacaoID']	= $row['Situacao_ID'];
		}
		return $dado;
	}

	function enderecosLocaliza($id){
		$i = 0;
		$resultado = mpress_query("select ce.Cadastro_Endereco_ID, ce.Cadastro_ID, ce.Tipo_Endereco_ID, t.Descr_Tipo as Tipo_Endereco, ce.CEP, ce.Logradouro, ce.Numero, ce.Complemento, ce.Bairro, ce.Cidade, ce.UF, ce.Referencia
								from cadastros_enderecos ce
								inner join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
								where ce.Cadastro_ID = '$id' and ce.Situacao_ID = 1");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dado[$i]['enderecoID'] = $row['Cadastro_Endereco_ID'];
			$dado[$i]['tipoendereco'] = $row['Tipo_Endereco'];
			$dado[$i]['tipoenderecoID'] = $row['Tipo_Endereco_ID'];
			$dado[$i]['cep'] = $row['CEP'];
			$dado[$i]['logradouro'] = $row['Logradouro'];
			$dado[$i]['numero']	= $row['Numero'];
			$dado[$i]['complemento']	= $row['Complemento'];
			$dado[$i]['bairro']	= $row['Bairro'];
			$dado[$i]['cidade']	= $row['Cidade'];
			$dado[$i]['uf']	= $row['UF'];
			$dado[$i]['referencia']	= $row['Referencia'];
		}
		return $dado;
	}

	function telefonesLocaliza($id){
		$i = 0;
		$resultado = mpress_query("select ct.Cadastro_Telefone_ID, ct.Telefone, ct.Tipo_Telefone_ID, ct.Observacao, t.Descr_Tipo as Tipo_Telefone
								from cadastros_telefones ct
								inner join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
								where ct.Cadastro_ID = '$id' and ct.Situacao_ID = 1");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dado[$i]['telefoneID'] = $row['Cadastro_Telefone_ID'];
			$dado[$i]['telefone'] = $row['Telefone'];
			$dado[$i]['tipotelefoneID'] = $row['Tipo_Telefone_ID'];
			$dado[$i]['tipotelefone'] = $row['Tipo_Telefone'];
			$dado[$i]['observacao'] = $row['Observacao'];
		}
		return $dado;
	}


	function vinculosLocaliza($id){
		global $caminhoSistema;
		$i = 0;
		$sql = "select cv.Cadastro_Vinculo_ID, cd.Cadastro_ID, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj, cd.Email as Email, tv.Descr_Tipo as Tipo_Vinculo, Foto
					from cadastros_dados cd
					inner join cadastros_vinculos cv on cv.Cadastro_Filho_ID = cd.Cadastro_ID
					inner join tipo tv on tv.Tipo_ID = cv.Tipo_Vinculo_ID
					where cv.Cadastro_ID = '$id'
						and cd.Situacao_ID = 1
						and cv.Situacao_ID = 1
					order by Tipo_Vinculo, Nome";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dado[$i]['cadastrovinculoID'] = $row['Cadastro_Vinculo_ID'];
			$dado[$i]['tipovinculo'] = $row['Tipo_Vinculo'];
			$dado[$i]['cadastroID'] = $row['Cadastro_ID'];
			$dado[$i]['nome'] = $row['Nome'];
			$dado[$i]['nomefantasia'] = $row['Nome_Fantasia'];
			$dado[$i]['email'] = $row['Email'];
			$dado[$i]['cpfcnpj'] = $row['Cpf_Cnpj'];
			if ($row['Foto']=="")
				$dado[$i]['foto'] = $caminhoSistema."/images/geral/imagem-usuario.jpg";
			else
				$dado[$i]['foto'] = $caminhoSistema."/uploads/".$row['Foto'];
		}
		return $dado;
	}


	function localizaTiposTelefones(){
		$sql = "select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = 11";
	}

	/*

	function cadastroIncluir($dados){
		//24 - Fisica
		//25 - Juridica
		$sql = "INSERT INTO cadastros_dados
							(Tipo_Pessoa, Nome, Nome_Fantasia, Senha, Data_Nascimento,
							Sexo, Cpf_Cnpj, RG, Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo,
							Email, Foto, Observacao, Usuario_Cadastro_ID, Data_Inclusao, Ultimo_Login, Situacao_ID)
				VALUES
					('".$dados['tipopessoa']."', '".$dados['nome']."', '".$dados['nomefantasia']."', '".$dados['nomefantasia']."', '".$dados['senha']."', '".$dados['datanascimento']."',
					'".$dados['sexo']."', '".$dados['cpfcnpj']."', '".$dados['rg']."', '".$dados['inscricaomunicipal']."', '".$dados['inscricaoestadual']."', '".$dados['tipovinculo']."',
					'".$dados['email']."', '".$dados['foto']."', '".$dados['observacao']."', '-1', NOW(), NOW(), '".$dados['situacaoID']."')";
		$resultado = mpress_query($sql);
		$cadastroID = mpress_identity();
		$dado = cadastrosLocaliza($cadastroID);


		$sql = "INSERT INTO cadastros_enderecos
					(Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Situacao_ID, Usuario_Cadastro_ID)
				VALUES ($cadastroID, '', '', '', '', '', '', '', '', '', '', 0)
				VALUES
					('".$dados['$cadastroID']."', '".$dados['nome']."', '".$dados['nomefantasia']."', '".$dados['nomefantasia']."', '".$dados['senha']."', '".$dados['datanascimento']."',
					'".$dados['sexo']."', '".$dados['cpfcnpj']."', '".$dados['rg']."', '".$dados['inscricaomunicipal']."', '".$dados['inscricaoestadual']."', '".$dados['tipovinculo']."',
					'".$dados['email']."', '".$dados['foto']."', '".$dados['observacao']."', '-1', NOW(), NOW(), '".$dados['situacaoID']."')";


		$sql = "INSERT INTO cadastros_telefones
					(Cadastro_ID, Telefone, Tipo_Telefone_ID, Observacao, Situacao_ID, Usuario_Cadastro_ID)
				VALUES ()";

		$dado['cadastro']['dados'] 	= cadastrosLocaliza($row['Cadastro_ID']);
		$dado['cadastro']['enderecos'] = enderecosLocaliza($row['Cadastro_ID']);
		$dado['cadastro']['telefones'] = telefonesLocaliza($row['Cadastro_ID']);
		 $dado['cadastro']['vinculos'] = vinculosLocaliza($row['Cadastro_ID']);
		echo serialize($dado);
		// $dado['sql'] = $sql;

		$resultado = mpress_query($sql);
		$id = mpress_identity();
		$dado = cadastrosLocaliza($id);
		$dado['cadastro']['dados'] 	= cadastrosLocaliza($row['Cadastro_ID']);
		$dado['cadastro']['enderecos'] = enderecosLocaliza($row['Cadastro_ID']);
		$dado['cadastro']['telefones'] = telefonesLocaliza($row['Cadastro_ID']);
		$dado['cadastro']['vinculos'] = vinculosLocaliza($row['Cadastro_ID']);
		echo serialize($dado);

	}
	*/
	/*
	function cadastroAtualizar($dados){
		$sql = "UPDATE cadastros_dados set Tipo_Pessoa = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									Nome_Fantasia = ".$dados['nomefantasia']."
									Senha = ".$dados['senha']."
									Nome = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									Nome = ".$dados['tipopessoa']."
									where Cadastro_ID = ".$dados['cadastroID']."";
	}
	*/

	function cadastroExcluir($dados){
		if ($dados['cadastroID']!=""){
			$sql = "update cadastros_dados set Situacao_ID = 2 where Cadastro_ID = '".$dados['cadastroID']."'";
			$resultado = mpress_query($sql);
		}
	}

	function vinculoExcluir($dados){
		if ($dados['cadastroVinculoID']!=""){
			$sql = "update cadastros_vinculos set Situacao_ID = 2 where Cadastro_Vinculo_ID = '".$dados['cadastrovinculoID']."'";
			$resultado = mpress_query($sql);
			validaLogin($dados['cadastroID']);
			//echo serialize($dado);
		}
	}
?>




