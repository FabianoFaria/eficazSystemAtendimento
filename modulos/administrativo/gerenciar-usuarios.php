<?php

	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by Nome";
	}
	echo "<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
	$sql = "SELECT cd.Cadastro_ID AS Cadastro_ID, tp.Descr_Tipo AS Tipo_Pessoa, tc.Descr_Tipo AS Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
				cd.Usuario_Cadastro_ID, Codigo, COALESCE(cf.Telefone,'') AS Telefone, cf.Observacao, cd.Email AS Email, cd.Situacao_ID AS Situacao_ID,
				ma.Titulo as Grupo
				FROM cadastros_dados cd
				inner join modulos_acessos ma on ma.Modulo_Acesso_ID = cd.Grupo_ID
				LEFT JOIN cadastros_telefones cf ON cf.Cadastro_ID = cd.Cadastro_ID AND cf.Situacao_ID = 1
				LEFT JOIN tipo tp ON Tipo_ID = Tipo_Pessoa AND tp.Tipo_Grupo_ID = 8
				LEFT JOIN tipo tc ON tc.Tipo_ID = Tipo_Cadastro AND tc.Tipo_Grupo_ID = 9
				WHERE cd.Situacao_ID = 1 AND cd.Cadastro_ID > 0
				and cd.Grupo_ID <> -4
				$ordem";
	//echo $sql;
	$cadastroIDAnt = "";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($row[Cadastro_ID] != $cadastroIDAnt){
			if ($row[Situacao_ID]==3) $classe = "lixeira"; else $classe = "link";
			$i++;
			$nome = $row[Nome];
			$dados[colunas][conteudo][$i][1] = "<span class='$classe cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p></span>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Grupo]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Tipo_Pessoa]."</p>";
			$dados[colunas][conteudo][$i][4] = "<span class='$classe cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$nome."</p></span>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row[Cpf_Cnpj]."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Email]."</p>";

			$telefones = "";
		}
		$telefones .= "<span Style='margin:2px 5px 0 5px;float:left;'>".$row['Telefone']."</span>";
		$dados[colunas][conteudo][$i][7] = $telefones;
		$cadastroIDAnt = $row[Cadastro_ID];
	}
	if($i==0){
		$i++;
		$dados[colunas][colspan][$i][1] = "7";
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
	}
	$largura = "100.2%";
	$colunas = "7";
	$dados[colunas][tamanho][1] = "width='6%'";

	$dados[colunas][titulo][1] 	= "ID";
	$dados[colunas][titulo][2] 	= "Grupo";
	$dados[colunas][titulo][3] 	= "Tipo";
	$dados[colunas][titulo][4] 	= "Nome";
	$dados[colunas][titulo][5] 	= "Cpf / Cnpj";
	$dados[colunas][titulo][6] 	= "Email";
	$dados[colunas][titulo][7] 	= "Telefones";

	$dados[colunas][ordena][1] = "Cadastro_ID";
	$dados[colunas][ordena][2] = "Grupo";
	$dados[colunas][ordena][4] = "Nome";


	echo "	<div id='container-geral'>
				<div class='titulo-container conjunto1'>
					<div class='titulo' style='min-height:22px'>
						<p>
							Usu&aacute;rios
							<input type='button' value='Incluir Novo' class='incluir-novo-usuario' Style='width:100px'></p>
						</p>
					</div>
					<div class='conteudo-interno esconde conteudo-interno-usuario'>";
	carregarBlocoCadastroGeral('', 'cadastro-usuario-id','cadastro',1,'','','','required');
	echo "			</div>
					<p>&nbsp;</p>
					<div class='conteudo-interno'>
						<div class='titulo-secundario'>";
	geraTabela($largura,$colunas,$dados, null, 'cadastro-localiza', 2, 2, 100,1);
	echo "				</div>
					</div>
				</div>
			</div>";

?>