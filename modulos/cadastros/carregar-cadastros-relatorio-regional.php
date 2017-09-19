<?php
	include("functions.php");
	carregarCadastrosRelatorioRegional();
	function carregarCadastrosRelatorioRegional(){
		$cidade = $_GET['cidade'];
		$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
		$areasAtuacoes = $_POST['localiza-areas-atuacoes'];
		if ($tipoCadastro != ""){ $sqlCond .= " and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";}
		if ($areasAtuacoes != ""){ $sqlCond .= " and cd.Areas_Atuacoes like '%s:".strlen($areasAtuacoes).":\"".$areasAtuacoes."\"%'";}

		$i = 0;
		$sql = "select cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
					Inscricao_Municipal, Inscricao_Estadual, cd.Usuario_Cadastro_ID, Codigo, coalesce(cf.Telefone,'') as Telefone, cf.Observacao
					from cadastros_dados cd
					left join cadastros_enderecos ce on ce.Cadastro_ID = cd.Cadastro_ID and ce.Situacao_ID = 1
					left join cadastros_telefones cf on cf.Cadastro_ID = cd.Cadastro_ID and cf.Situacao_ID = 1
					left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
					left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
					where cd.Situacao_ID = 1 and ce.UF <> '' and Cidade <> '' and cd.Cadastro_ID > 0
					and upper(ce.cidade) = upper('$cidade')
					$sqlCond
					group by cd.Cadastro_ID, tp.Descr_Tipo, tc.Descr_Tipo, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
					Inscricao_Municipal, Inscricao_Estadual, cd.Usuario_Cadastro_ID, Codigo, cf.Telefone, cf.Observacao
					order by Nome";
			//echo $sql;
			$cadastroIDAnt = "";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				if ($row[Cadastro_ID] != $cadastroIDAnt){
					$i++;
					$nome = $row[Nome];
					$dados[colunas][conteudo][$i][1] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p></span>";
					$dados[colunas][conteudo][$i][2] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p></span>";
					$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".utf8_encode($row[Tipo_Pessoa])."</p>";
					$dados[colunas][conteudo][$i][4] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".utf8_encode($nome)."</p></span>";
					$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</p>";
					$telefones = "";
				}
				$telefones .= "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Telefone']."</p>";
				$dados[colunas][conteudo][$i][6] = $telefones;
				$cadastroIDAnt = $row[Cadastro_ID];
			}
			if($i==0){
				echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
			}
			else{
				$largura = "100.2%";
				$colunas = "6";
				$dados[colunas][tamanho][1] = "width='6%'";
				$dados[colunas][titulo][1] 	= "ID";

				$dados[colunas][tamanho][3] = "width='10%'";
				$dados[colunas][titulo][2] 	= "C&oacute;digo";
				$dados[colunas][tamanho][3] = "width='20%'";
				$dados[colunas][titulo][3] 	= "Tipo";
				$dados[colunas][tamanho][4] = "width=''";
				$dados[colunas][titulo][4] 	= "Nome";
				$dados[colunas][tamanho][5] = "width='30%'";
				$dados[colunas][titulo][5] 	= "Cpf / Cnpj";
				$dados[colunas][tamanho][6] = "";
				$dados[colunas][titulo][6] 	= "Telefones";

				$dados[colunas][ordena][1] = "Cadastro_ID";
				$dados[colunas][ordena][2] = "cast(codigo AS SIGNED)";
				$dados[colunas][ordena][3] = "Tipo_Pessoa";
				$dados[colunas][ordena][4] = "Nome";
				$dados[colunas][ordena][5] = "Cpf_Cnpj";

				geraTabela($largura,$colunas,$dados);
			}
		//echo "<p Style='margin:2px 5px 0 5px; text-align:left'>Total: $i</p>";
	}
?>