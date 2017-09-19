<?php
include('functions.php');
$siteID = $_POST['site-seleciona'];
if ($siteID=="") $siteID = $_POST['site-id'];
echo "	<input type='hidden' id='acao-site' name='acao-site' value=''/>
		<input type='hidden' id='site-id' name='site-id' value='$siteID'/>
		<div id='tabela-container'>";
if($siteID == ""){
	echo "	<div class='titulo-container grupo1' id='div-dados-gerais'>
				<div class='titulo'>
					<p>Sites Cadastrados</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario uma-coluna omega'>
						<select name='site-seleciona' id='site-seleciona' Style='padding:5px;margin-top:7px;margin-left:3px'>
							<option value=''>Selecione</option>
							<option value='-1'>Cadastrar novo Site</option>";

			$rs = mpress_query("select Site_ID, URL, Empresa_ID from sites_dados where Situacao_ID = 1");
			while($row = mpress_fetch_array($rs)){
				if($row['Site_ID'] == $siteID) $selecionado = "selected"; else $selecionado = "";
				echo "	<option value='".$row['Site_ID']."' $selecionado>".$row['URL']."</option>";
			}
	echo "
						</select>
					</div>
				</div>
			</div>";
}
else{
	if ($siteID!="-1"){		$rs = mpress_query("select Site_ID, URL, Empresa_ID, Dados from sites_dados where Site_ID = $siteID");
		if($row = mpress_fetch_array($rs)){
			$url = $row['URL'];
			$empresaID = $row['Empresa_ID'];
			$dados = unserialize($row['Dados']);
		}
	}
	/*
	echo "<pre>";
	print_r($dados);
	echo "</pre>";
	*/
	$conexaoSite = carregarConexaoSite($siteID);
	if ($conexaoSite=="") $strConectado = "Conectado"; else $strConectado = $conexaoSite;
	echo "	<div class='titulo-container conjunto1 div-dados-site'>
				<div class='titulo'>
					<p>Site <input type='button' value='Salvar' class='cadastrar-dados-site'/> <input type='button' value='Voltar' id='cancelar-dados-site'/> <input type='button' value='Excluir' id='desativar-dados-site'/></p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left; width:50%'>
						<p>Site (URL):<p>
						<p><input type='text' name='site-url' id='site-url' Style='width:98%' class='required' value='".$url."'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:50%'>
						<p>Empresa:<p>
						<p><select name='site-empresa' id='site-empresa' class='required'>".optionValueEmpresas($empresaID)."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Nome do Banco de Dados:</label>
						<p><input name='config[db][dbname]' id='dbname' type='text' value='".$dados['db']['dbname']."' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Nome de usu&aacute;rio::</label>
						<p><input name='config[db][username]' id='username' type='text' value='".$dados['db']['username']."' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Senha:</label>
						<p><input name='config[db][pwd]' id='pwd' type='text' value='".$dados['db']['pwd']."' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Servidor do banco de dados:</label>
						<p><input name='config[db][host]' id='host' type='text' value='".$dados['db']['host']."' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Status:</p>
						<p>".$strConectado."</p>
					</div>
				</div>
			</div>";


	if ($modulosAtivos[cadastros]){
		echo "	<div class='titulo-container conjunto2 esconde div-dados-site'>
					<div class='titulo'>
						<p>Cadastros</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Ao cadastrar entra em qual situação?<p>
							<p><select name='config[cadastros][situacao-cadastro-padrao]' id='situacao-cadastro-padrao' class='required'>".optionValueGrupo(1, $dados['cadastros']['situacao-cadastro-padrao'])."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:75%; height:50px;'>
							<p>&nbsp;</p>
						</div>";

/*
		$c = 1;
		$formCadastro[colunas][tamanho][$c++] = "width='10%'";

		$c = 1;
		$formCadastro[colunas][titulo][$c++] 	= "Exibe";
		$formCadastro[colunas][titulo][$c++] 	= "Label PF";
		$formCadastro[colunas][titulo][$c++] 	= "Label PJ";
		$formCadastro[colunas][titulo][$c++] 	= "Name";
		$formCadastro[colunas][titulo][$c++] 	= "Class";
		$formCadastro[colunas][titulo][$c++] 	= "Colunas";
		$formCadastro[colunas][titulo][$c++] 	= "Type";
		$formCadastro[colunas][titulo][$c++] 	= "Options";
		$i = 0;

		//$campos = array();
		$arrTP[0]['value'] = '24';
		$arrTP[0]['text'] = 'Pessoa Física';
		$arrTP[1]['value'] = '25';
		$arrTP[1]['text'] = 'Pessoa Juridica';
		$blocos[0] = 'Dados Meliante';
		$blocos[1] = 'Endereço';		$blocos[2] = 'Login Senha';
		$campos[0][1] = array('checked', 'Pessoa', 'Pessoa', 'tipo-pessoa', 'required', '6', 'radio', $arrTP);
		$campos[0][2] = array('checked', 'Nome', 'Razao Social', 'razao-social', 'required', '3', 'text');
		$campos[0][3] = array('checked', 'Apelido', 'Nome Fantasia', 'nome-fantasia', 'required', '3', 'text');
		$campos[0][4] = array('checked', 'CPF', 'CNPJ', 'cpf-cnpj', 'required', '3', 'text');
		$campos[0][5] = array('checked', '', 'Inscricao Estadual', 'inscricao-estadual', 'required', '3', 'text');		$campos[0][6] = array('checked', 'Data Nascimento', 'Data Abertura', 'data-nascimento', 'required formata-data', '1', 'text');
		$arrSexo[0]['value'] = 'M';		$arrSexo[0]['text'] = 'Masculino';		$arrSexo[1]['value'] = 'F';		$arrSexo[1]['text'] = 'Feminino';
		$campos[0][7] = array('checked', 'Sexo', '', 'sexo', '', '6', 'radio', $arrSexo);
		$campos[0][8] = array('checked', 'Telefone Celular', 'Telefone Celular', 'telefone-celular', 'formata-telefone', '2', 'text');
		$campos[0][9] = array('checked', 'Telefone Residencial', '', 'telefone-residencial', 'formata-telefone', '2', 'text');
		$campos[0][10] = array('checked', 'Telefone Comercial', 'Telefone Comercial', 'telefone-comercial', 'formata-telefone', '2', 'text');
		$campos[1][1] = array('checked', 'Tipo Endereco', 'Tipo Endereco', 'tipo-endereco', '', '2', 'text');
		$campos[1][2] = array('checked', 'CEP', 'CEP', 'cep', 'cep', '6', 'text');
		$campos[1][3] = array('checked', 'Tipo Endereco', 'Tipo Endereco', 'tipo-endereco', '', '2', 'text');
		$campos[1][4] = array('checked', 'CEP', 'CEP', 'cep', 'cep', '6', 'text');

		$campos[2][1] = array('checked', 'Email', 'Email', 'email', 'required valida-email', '3', 'text');
		$campos[2][2] = array('checked', 'Confirma Email', 'Confirma Email', 'email', 'required valida-email', '3', 'text');
		$campos[2][3] = array('checked', 'Senha', 'Senha', 'senha', 'required', '3', 'password');
		$rs = mpress_query("select Dados from modulos_formularios where Modulo = 'cadastros' and Slug = 'formulario-cadastro'");
		if($row = mpress_fetch_array($rs)){
			$formularioCadastro = unserialize($row['Dados']);
		}

		//echo "<pre>";
		//print_r($formularioCadastro);
		//echo "</pre>";
		foreach($blocos as $chaveBloco => $bloco){
			$i++;
			$formCadastro[colunas][conteudo][$i][1] = "<p><input type='text' name='bloco[$chaveBloco]' value='$bloco'></p>";
			$formCadastro[colunas][colspan][$i][1] = 8;
			foreach ($campos[$chaveBloco] as $chave => $campo){
				$i++; $c = 1;
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='checkbox' name='exibe[$chaveBloco][$chave]' ".$campo[0]."/>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='text' name='labelpf[$chaveBloco][$chave]' value='".$campo[1]."'/>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='text' name='labelpj[$chaveBloco][$chave]' value='".$campo[2]."'/>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='text' name='name[$chaveBloco][$chave]' class='required' readonly value='".$campo[3]."'/>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='text' name='class[$chaveBloco][$chave]' value='".$campo[4]."'/>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<select name='column[$chaveBloco][$chave]' id='column-$chave' class='required'>".optionValueCountSelect(6, $campo[5],'N')."</select>";
				$formCadastro[colunas][conteudo][$i][$c++] = "<input type='text' name='type[$chaveBloco][$chave]' value='".$campo[6]."' readonly/>";
				foreach($campo[7] as $chave2 => $option){
					$formCadastro[colunas][conteudo][$i][$c] .= "<input type='text' name='value[$chaveBloco][$chave]' value='".$option['value']."' readonly/><input type='text' name='value[$i][]' value='".$option['text']."' readonly/><br>";
				}
			}
		}
		echo "			<div class='titulo-secundario' style='float:left; width:100%'>
							<p>";
		echo geraTabela("100%","8",$formCadastro);
		echo "				</p>
						</div>";
		*/
		echo "		</div>
				</div>";
	}


	if ($modulosAtivos[chamados]){
		echo "	<div class='titulo-container conjunto3 esconde div-dados-site'>
					<div class='titulo'>
						<p>
							Orçamentos
							<input type='button' class='cadastrar-dados-site' value='Salvar'>
						</p>
					</div>
					<div class='conteudo-interno'>

						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Ao cadastrar entra em qual situação?<p>
							<p><select name='situacao-chamado' id='situacao-chamado' class='required'>".optionValueGrupo(18,$situacaoChamado)."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Qual tipo ".$_SESSION['objeto']."?<p>
							<p><select name='tipo-chamado' id='tipo-chamado' class='required'>".optionValueGrupoFilho(19, $tipoWorkflowID, "Selecione")."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:50%; height:50px;'>
							<p>&nbsp;</p>
						</div>
					</div>
				</div>";

		echo "	<div class='titulo-container conjunto3 esconde div-dados-site'>
					<div class='titulo'>
						<p>
							".$_SESSION['objeto']."
							<input type='button' class='cadastrar-dados-site' value='Salvar'>
						</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Ao cadastrar entra em qual situação?<p>
							<p><select name='config[chamados][situacao-chamado-padrao]' id='situacao-chamado-padrao' class=''>".optionValueGrupo(18,$dados['chamados']['situacao-chamado-padrao'])."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Qual tipo ".$_SESSION['objeto']."?<p>
							<p><select name='config[chamados][tipo-chamado-padrao]' id='tipo-chamado-padrao' class=''>".optionValueGrupoFilho(19, $dados['chamados']['tipo-chamado-padrao'], "Selecione")."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:50%; height:50px;'>
							<p>&nbsp;</p>
						</div>
					</div>
				</div>";
	}

	if ($modulosAtivos[produtos]){
		$totProdSis = 0;
		$totVarSis = 0;
		$query = mpress_query("select (select count(*) from produtos_dados pd where pd.Situacao_ID = 1 and pd.Produto_ID > 0 and pd.Tipo_Produto IN (30,100)) as totProd,									(select count(*) from produtos_variacoes pv										inner join produtos_dados pd on pv.Produto_ID = pd.Produto_ID											where pv.Situacao_ID = 1 and pd.Situacao_ID = 1 and pv.Forma_Cobranca_ID IN(35,58)											and pd.Situacao_ID = 1 and pd.Produto_ID > 0 and pd.Tipo_Produto IN (30,100)) as totVar,									(select count(*) from modulos_anexos ma
													inner join produtos_dados pd on ma.Chave_Estrangeira = pd.Produto_ID
													where ma.tabela_estrangeira = 'produtos' and pd.Situacao_ID = 1
															  and ma.Situacao_ID = 1) as totImg");
			if($rs = mpress_fetch_array($query)){
				$totProdSis = $rs['totProd'];
				$totVarSis = $rs['totVar'];
				$totImgSis = $rs['totImg'];
		}
		$totProdSite = 0;
		$totVarSite = 0;
		$query = msite_query("select (select count(*) from wp_posts where post_type = 'produtos' ) as totPost,
								(select count(*) from wp_produtos_produtos) as totProd,
								(select count(*) from wp_produtos_produtos_variacoes) as totVar,
								(select count(*) from wp_produtos_produtos_variacoes_detalhes) as totVarDet,
								(select count(*) from wp_term_relationships where term_taxonomy_id in (select term_taxonomy_id from wp_term_taxonomy  where taxonomy = 'Categorias')) as totRel,
								(select count(*) from wp_produtos_produtos_variacoes_valores where Data_Final_Cadastro is null) as totVarValores,
								(select count(*) from wp_produtos_produtos_imagens) as totImages");
		if($rs = msite_fetch_array($query)){
			$totProdSite = $rs['totProd'];
			$totVarSite = $rs['totVar'];
			$totPostSite = $rs['totPost'];
			$totVarDetSite = $rs['totVarDet'];
			$totRelSite = $rs['totRel'];
			$totVarValoresSite = $rs['totVarValores'];
			$totImgSite = $rs['totImages'];
		}
		echo "	<div class='titulo-container conjunto4 esconde'>
					<div class='titulo'>
						<p> Produtos
							<input type='button' class='exportar-produtos-categorias' value='Exportar Produtos X Categorias para o Site' style='width:230px;'>
							<input type='button' class='exportar-produtos-variacoes-imagens' value='Exportar Imagens Produtos' style='width:230px;'>
							<input type='button' class='exportar-produtos-variacoes' value='Exportar Variações Site' style='width:230px;'>
							<input type='button' class='exportar-produtos' value='Exportar Produtos Site' style='width:230px;'>
							<input type='button' class='exportar-limpar-dados' value='Excluir Dados Site' style='width:230px;'>
						</p>
					</div>
					<div class='conteudo-interno conteudo-produtos-sie titulo-secundario'>
						<div class='titulo-secundario' style='float:left; width:100%;'>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Produtos Sistema</p>
							<p>$totProdSis</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Variações Sistema</p>
							<p>$totVarSis</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Imagens Sistema</p>
							<p>$totImgSis</p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Total Produtos (Site)</p>
							<p>$totProdSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Variações Site</p>
							<p>$totVarSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Variacoes Detalhes Site</p>
							<p>$totVarDetSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>POSTS Site</p>
							<p>$totPostSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Variações Valores Site</p>
							<p>$totVarValoresSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Rel. Produtos X Categorias</p>
							<p>$totRelSite</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p>Imagens Site</p>
							<p>$totImgSite</p>
						</div>
					</div>
				</div>
			</div>";
			/*****************************/
			$query = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome");
			while($categoria1 = mpress_fetch_array($query)){
				if(strlen(array_search($categoria1[Categoria_ID],$categorias))>=1){ $selecionado = " selected ";}else{$selecionado = "";}
				$categoriasSistema .= $categoria1[Categoria_ID]." - ".$categoria1[Nome]."<br>";
				$query2 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria1[Categoria_ID]."' order by nome");
				while($categoria2 = mpress_fetch_array($query2)){
					if(strlen(array_search($categoria2[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
					$categoriasSistema .= "&nbsp;&nbsp;&nbsp;&nbsp;".$categoria2[Categoria_ID]." - ".$categoria2[Nome]."<br>";
					$query3 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria2[Categoria_ID]."' order by nome");
					while($categoria3 = mpress_fetch_array($query3)){
						if(strlen(array_search($categoria3[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
						$categoriasSistema .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria3[Categoria_ID]." - ".$categoria3[Nome]."<br>";
						$query4 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria3[Categoria_ID]."' order by nome");
						while($categoria4 = mpress_fetch_array($query4)){
							if(strlen(array_search($categoria4[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
							$categoriasSistema .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria4[Categoria_ID]." - ".$categoria4[Nome]."<br>";
							$query5 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria4[Categoria_ID]."' order by nome");
							while($categoria5 = mpress_fetch_array($query5)){
								if(strlen(array_search($categoria5[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
								$categoriasSistema .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria5[Categoria_ID]." - ".$categoria5[Nome]."<br>";
							}
						}
					}
				}
			}
			if ($conexaoSite==""){
				$query = msite_query("SELECT t.term_id AS Categoria_ID, t.name AS Nome FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id AND taxonomy = 'Categorias' and parent = 0 order by t.name");
				while($categoria1 = msite_fetch_array($query)){
					$categoriasSite .= $categoria1[Categoria_ID]." - ".$categoria1[Nome]."<br>";
					$query2 = msite_query("SELECT t.term_id AS Categoria_ID, t.name AS Nome FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id AND taxonomy = 'Categorias' and parent = ".$categoria1[Categoria_ID]." order by t.name");
					while($categoria2 = msite_fetch_array($query2)){
						$categoriasSite .= "&nbsp;&nbsp;&nbsp;&nbsp;".$categoria2[Categoria_ID]." - ".$categoria2[Nome]."<br>";
						$query3 = msite_query("SELECT t.term_id AS Categoria_ID, t.name AS Nome FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id AND taxonomy = 'Categorias' and parent = ".$categoria2[Categoria_ID]." order by t.name");
						while($categoria3 = msite_fetch_array($query3)){
							$categoriasSite .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria3[Categoria_ID]." - ".$categoria3[Nome]."<br>";
							$query4 = msite_query("SELECT t.term_id AS Categoria_ID, t.name AS Nome FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id AND taxonomy = 'Categorias' and parent = ".$categoria3[Categoria_ID]." order by t.name");
							while($categoria4 = msite_fetch_array($query4)){
								$categoriasSite .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria4[Categoria_ID]." - ".$categoria4[Nome]."<br>";
								$query5 = msite_query("SELECT t.term_id AS Categoria_ID, t.name AS Nome FROM wp_terms t INNER JOIN wp_term_taxonomy tt ON t.term_id = tt.term_id AND taxonomy = 'Categorias' and parent = ".$categoria4[Categoria_ID]." order by t.name");
								while($categoria5 = msite_fetch_array($query5)){
									$categoriasSite .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria5[Categoria_ID]." - ".$categoria5[Nome]."<br>";
								}
							}
						}
					}
				}
			}
			echo "	<div class='titulo-container conjunto4 esconde'>
						<div class='titulo'>
							<p>
								Categorias
								<input type='button' class='exportar-categorias' value='Exportar Categorias para o Site' style='width:230px;'>
							</p>
						</div>
						<div class='conteudo-interno conteudo-categorias-site titulo-secundario'>
						<div class='titulo-secundario' style='float:left; width:22.5%;'>
							<p>Categorias Cadastradas (Sistema)</p>
							<p><div Style='width:99%;height:230px;overflow:auto;border: 1px solid #dddddd;background-color: #f6f6f6;'>$categoriasSistema</div></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:5%;'>&nbsp;</div>
						<div class='titulo-secundario' style='float:left; width:22.5%;'>
							<p>Categorias Cadastradas (Site)</p>
							<p><div Style='width:99%;height:230px;overflow:auto;border: 1px solid #dddddd;background-color: #f6f6f6;'>$categoriasSite</div></p>
							</div>
						</div>
					</div>";
		}
	}
echo "	</div>";
