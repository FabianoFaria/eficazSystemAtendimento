<?php
	include("functions.php");
	global $configChamados;
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
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-classificacoes']); $i++){
			$classificacoes .= $virgula.$_POST['localiza-classificacoes'][$i];
			$virgula = ",";
		}		
		for($i = 0; $i < count($_POST['localiza-representantes']); $i++){
			$filtroRepresentantes .= $virgula.$_POST['localiza-representantes'][$i];
			$virgula = ",";
		}		
	}
	else{
		$situacao = "1";
	}

	//if ($id != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
	//if ($codigo != ""){ $sqlCond .= " and cd.Codigo like '%$codigo%' ";}
	//if ($tipoCadastro != ""){ $sqlCond .= " and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";}
	if ($areasAtuacoes != ""){ $sqlCond .= " and cd.Areas_Atuacoes like '%s:".strlen($areasAtuacoes).":\"".$areasAtuacoes."\"%'";}
	//if ($cargos != ""){ $sqlCond .= " and cd.Cargo_ID = '$cargos'";}
	if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
	if ($cpf != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cpf)))."' or cd.Cpf_Cnpj = '$cpf') ";}
	if ($cnpj != ""){ $sqlCond .= " and (cd.Cpf_Cnpj = '".str_replace('/','',str_replace('-','',str_replace('.','',$cnpj)))."' or cd.Cpf_Cnpj = '$cnpj') ";}
	if ($email != ""){ $sqlCond .= " and cd.Email like '%$email%'";}
	if ($classificacoes != ""){ $sqlCond .= " and cc.Classificacao_ID in (".$classificacoes.")";}
	
	if ($filtroRepresentantes!=""){ $sqlCond .= " and cr.Cadastro_ID in (".$filtroRepresentantes.")";}
	
	//if ($situacao != ""){ $sqlCond .= " and cd.Situacao_ID = '$situacao'";}
	//if ($tipoPessoa != ""){ $sqlCond .= " and cd.Tipo_Pessoa = '$tipoPessoa'";}

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
											<!--

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
						-->
						<div class='titulo-secundario quatro-colunas'>
							<p>Nome Completo (Cliente)</p>
							<p><input type='text' id='localiza-nome-completo' name='localiza-nome-completo'  maxlength='250' value='".$nomeCompleto."' style='width:97.5%;'/></p>
						</div>
						<!--
						<div class='titulo-secundario oito-colunas'>
							<p>Tipo Pessoa</p>
							<p><select name='localiza-tipo-pessoa' id='localiza-tipo-pessoa'>".optionValueGrupo(8, $tipoPessoa,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>Tipo Cadastro</p>
							<p><select name='localiza-tipo-cadastro-id' id='localiza-tipo-cadastro-id'>".optionValueGrupo(9, $tipoCadastro,'&nbsp;')."</select></p>
						</div>
						-->						
						<div class='titulo-secundario quatro-colunas'>
							<p>Email</p>
							<p><input type='text' id='localiza-email'  name='localiza-email' maxlength='200' value='".$email."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>CPF</p>
							<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' class='localiza-mascara-cpf' value='".$cpf."'/></p>
						</div>
						<div class='titulo-secundario oito-colunas'>
							<p>CNPJ</p>
							<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' class='localiza-mascara-cnpj' value='".$cnpj."'/></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>&Aacute;reas de Atua&ccedil;&atilde;o</p>
							<p><select name='localiza-areas-atuacoes' id='localiza-areas-atuacoes'>".optionValueGrupo(34, $areasAtuacoes,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Classifica&ccedil;&atilde;o</p>
							<p><select name='localiza-classificacoes[]' id='localiza-classificacoes[]' multiple='multiple'>".optionValueGrupoMultiplo(74, $classificacoes,'')."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:50%'>
							<p>Gerente de Conta</p>
							<p>
								<select name='localiza-representantes[]' id='localiza-representantes' class='dados-orc' style='width:98.5%' multiple='multiple'>
								".optionValueUsuarios($_POST['localiza-representantes'], unserialize($configChamados['orcamento-grupos-responsaveis']))."
								</select>
							</p>
						</div>
						<!--
						<div class='titulo-secundario oito-colunas'>
							<p>Cargo</p>
							<p><select name='localiza-cargos' id='localiza-cargos'>".optionValueGrupo(42, $cargos,'&nbsp;')."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:20%;'>
							<p>Situa&ccedil;&atilde;o</p>
							<p><select name='localiza-situacao' id='localiza-situacao'>".optionValueGrupo(1, $situacao, 'Todos', 'and Tipo_ID IN(1,3,142)')."</select></p>
						</div>
						-->
						<div class='titulo-secundario' Style='float:right; width:20%; margin-top:15px;'>
							<p class='direita'><input type='button' value='Pesquisar' id='botao-pesquisar-clientes' style='width:99%;'/></p>
						</div>
					</div>
				</div>
			</div>
			<!--
			<font style='font-size:30px;'>
			Criar funcionalidade acima descrita juntamente com o relatório de clientes com filtros:<br>
			consultor dono, última interação (dias), com possibilidade de ordenação de todas as colunas.<br> 
			Nome do cliente, telefone, consultor, atividades executadas ou não. Filtro de atividade executas ou não executadas.
			</font>
			-->
			
			";


/*bloco de classificacao*/

		$i = 0;
		$resultado = mpress_query("SELECT Tipo_ID, Descr_Tipo, Tipo_Auxiliar FROM tipo WHERE Tipo_Grupo_ID = 74 AND Situacao_ID = 1");
		while($rs = mpress_fetch_array($resultado)){
			$i++;
			$dados = unserialize($rs['Tipo_Auxiliar']);
			$tipoID = $rs['Tipo_ID'];
			$arrClassificacao[$i]['valor'] = $dados['classificacao'];
			$arrClassificacao[$i]['tipoID'] = $tipoID;
			$arrDados[$tipoID]['valor'] = $dados['classificacao'];
			$arrDados[$tipoID]['dia'] = $dados['dia'];
			$arrDados[$tipoID]['mes'] = $dados['mes'];
		}

		echo "<input type='hidden' id='cadastroID' name='cadastroID' value=''>";

		$sql = "select cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj,
				cd.Usuario_Cadastro_ID, cd.Codigo, coalesce(cf.Telefone,'') as Telefone, cf.Observacao, cd.Email as Email, cd.Situacao_ID as Situacao_ID,
				cd.Tipo_Cadastro as Tipo_Cadastro_Array, coalesce(cr.Nome,'') as Representante, cc.Classificacao_ID,
				v.Data_Cadastro as Data_Interacao
				from cadastros_dados cd
				left join cadastros_telefones cf on cf.Cadastro_ID = cd.Cadastro_ID and cf.Situacao_ID = 1
				left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				left join cadastros_vinculos cv on cv.Tipo_Vinculo_ID = 101 and cv.Cadastro_ID = cd.Cadastro_ID
				left join cadastros_dados cr on cr.Cadastro_ID = cv.Cadastro_Filho_ID
				left join cadastros_classificacoes cc on cc.Cadastro_ID = cd.Cadastro_ID and cc.Situacao_ID = 1
				/*left join tipo tcc on tcc.Tipo_ID = cc.Classificacao_ID*/
				left join v_follows v on v.Cadastro_ID = cd.Cadastro_ID
				where cd.Situacao_ID = 1
				and cd.Cadastro_ID > 0
				and cd.Tipo_Cadastro like '%s:3:\"153\"%'
				$sqlCond 
				group by cd.Cadastro_ID, cv.Cadastro_Vinculo_ID
				$ordem";
		//echo $sql;
		$cadastroIDAnt = "";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$c = 1;
			if ($row['Cadastro_ID'] != $cadastroIDAnt){
				//if ($row[Situacao_ID]==3) $classe = "lixeira"; else $classe = "";
				$i++;
				$dados[colunas][tr][$i] = " style='font-weight:bold;'";
				//$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='cadastro-localiza lnk' cadastro-id='".$row[Cadastro_ID]."'";
				$dados[colunas][conteudo][$i][$c++] = "<p class='$classe link-geral-cadastro' Style='margin:2px 5px 0 5px;float:left; cursor:pointer;' cadastro-id='".$row[Cadastro_ID]."'>".$row[Nome]."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row['Cpf_Cnpj']."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row['Telefone']."</p>";
				$htmlClassificacao = "";
				
				//$row['Classificacao']
				foreach($arrClassificacao as $classificacao){
					//echo "<pre>";
					//print_r($classificacao);
					//echo "</pre>";
					//echo "<br>|".$arrDados[$row['Classificacao_ID']]['valor'].'-'.$classificacao['valor']."|";
					
					if ($arrDados[$row['Classificacao_ID']]['valor'] >= $classificacao['valor']){
						$htmlClassificacao .= "<img class='estrela-ativa classifica-valor classifica-valor-cad-".$row['Cadastro_ID']." classifica-valor-$valor' valor='".$classificacao['valor']."' classificacao-id='".$classificacao['tipoID']."' cadastro-id='".$row['Cadastro_ID']."' linha='$i'>";
					}
					else{
						$htmlClassificacao .= "<img class='estrela-inativa classifica-valor classifica-valor-cad-".$row['Cadastro_ID']." classifica-valor-$valor' valor='".$classificacao['valor']."' classificacao-id='".$classificacao['tipoID']."' cadastro-id='".$row['Cadastro_ID']."' linha='$i'>";
					}
				}
				$dados[colunas][conteudo][$i][$c++] = " <p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>
															".$htmlClassificacao."
															<input type='hidden' name='classificacao-cliente-".$row['Cadastro_ID']."' id='classificacao-cadastro-".$row['Cadastro_ID']."' value='".$row['Classificacao']."'/>
															<input type='hidden' name='classificacao-id-".$row['Cadastro_ID']."' id='classificacao-id-".$row['Cadastro_ID']."' value='".$row['Classificacao_ID']."'/>
														</p>";
				$representantes = "";
				$c++;
				/* Calculando retorno de data */
				//$row['Data_Interacao']
				$dia = $arrDados[$row['Classificacao_ID']]['dia'];
				$mes = $arrDados[$row['Classificacao_ID']]['mes'];
				
				$dataInteracao = date_create($row['Data_Interacao']);
				if ($dia!=''){
					date_add($dataInteracao, date_interval_create_from_date_string("$dia days"));
				}
				if ($mes!=''){
					date_add($dataInteracao, date_interval_create_from_date_string("$mes months"));
				}				
				$dataProxima = date_format($dataInteracao, 'd/m/Y');
				$intervalo = date_diff(date_create(retornaDataHora('d','Y-m-d')), date_create(date_format($dataInteracao, 'Y-m-d')));
				
				$situacao = "";
				if ($intervalo->format('%y')>0){
					$s = '';
					if ($intervalo->format('%y')>1) $s = 's';
					$situacao .= $intervalo->format('%y')." ano".$s." ";
				}
				if ($intervalo->format('%m')>0){
					$s = '';
					if ($intervalo->format('%m')>1) $s = 'es';
					if ($situacao!='') $situacao .= " e ";
					$situacao .= $intervalo->format('%m')." mes".$s." ";
				}
				if ($intervalo->format('%d')>0){
					$s = '';
					if ($intervalo->format('%d')>1) $s = 's';
					if ($situacao!='') $situacao .= " e ";
					$situacao .= $intervalo->format('%d')." dia".$s." ";
				}
				if ($situacao!=''){
					if (($intervalo->invert)==0){
						$situacao = "<p style='color:blue;' align='center'>Em ".$situacao."</p>";
					}
					else{
						$situacao = "<p style='color:red;' align='center'>".$situacao." atrasado</p>";
					}
				}
				$dados[colunas][conteudo][$i][$c++] = "<p Style='white-space:nowrap;' align='center'>".converteDataHora($row['Data_Interacao'],1)."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='white-space:nowrap;' align='center'>".$dataProxima."</p>";
				$dados[colunas][conteudo][$i][$c++] = "<p Style='white-space:nowrap;' align='center'>".$situacao."</p>";
			}
			//if ($row['Representante'])
			if ($row['Representante']!=''){
				$representantes .= "<span class='trocar-rep'>".$row['Representante']."</span>";
			}
			if ($representantes==''){	
				$representantes .= "<span class='trocar-rep' style='color:red;'>N&atilde;o definido</span>";
			}
			$dados[colunas][conteudo][$i][5] = "<span id='select-rep-".$i."'></span>
												<span class='redefinir-representante' title='Clique para redefinir' id='rep-".$i."' cadastro-id='".$row['Cadastro_ID']."' linha='".$i."'>".$representantes."</span>";
			$cadastroIDAnt = $row[Cadastro_ID];
		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
		}
		else{
			$largura = "100.2%";
			//$dados[colunas][tamanho][1] = "width='6%'";
			$c = 1;
			$dados[colunas][tamanho][$c++] = "width='30%'";
			$dados[colunas][tamanho][$c++] = "width='80px'";
			$dados[colunas][tamanho][$c++] = "width='10%'";

			$c = 1;
			$dados[colunas][titulo][$c++] 	= "Nome";
			$dados[colunas][titulo][$c++] 	= "Cpf / Cnpj";
			$dados[colunas][titulo][$c++] 	= "Telefone";
			$dados[colunas][titulo][$c++] 	= "Classifica&ccedil;&atilde;o";
			$dados[colunas][titulo][$c++] 	= "Gerente de Conta";
			$dados[colunas][titulo][$c++] 	= "<center>&Uacute;ltima Intera&ccedil;&atilde;o</center>";
			$dados[colunas][titulo][$c++] 	= "<center>Pr&oacute;xima Intera&ccedil;&atilde;o Necess&aacute;ria</center>";
			$dados[colunas][titulo][$c++] 	= "<center>Tempo para executar</center>";
			$dados[colunas][titulo][$c++] 	= "<center>Tarefas</center>";

			//$dados[colunas][ordena][1] = "Cadastro_ID";
			//$dados[colunas][ordena][2] = "cast(codigo AS SIGNED)";
			$c = 1;
			$dados[colunas][ordena][$c++] = "Nome";
			$c++;
			$c++;
			$dados[colunas][ordena][$c++] = "coalesce(tcc.Tipo_Auxiliar,0)";
			$dados[colunas][ordena][$c++] = "Representante";
			//$dados[colunas][ordena][$c++] = "Tipo_Pessoa";
			//$dados[colunas][ordena][$c++] = "Cpf_Cnpj";
			//$dados[colunas][ordena][$c++] = "Email";

	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Registros Localizados: $i</p>
					</div>
					<div class='conteudo-interno'>";
	geraTabela($largura,9,$dados, null, 'relatorio-acompanhamento-clientes', 2, 2, 100,1);
	echo "			</div>
				</div>
			</div>";
		}
	//}
?>