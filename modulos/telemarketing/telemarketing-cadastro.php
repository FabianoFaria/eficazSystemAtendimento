<?php
	$dadospagina = get_page_content();
	carregarCadastro();

	function carregarCadastro(){
		$cadastroID = $_POST['localiza-cadastro-id'];
		$flagPedido = $_POST['flag-pedido'];
		//echo $flagPedido;
		if ($cadastroID!=""){
			$sql = "Select Tipo_Pessoa, Tipo_Cadastro, Codigo, Nome, Nome_Fantasia, Email, Data_Nascimento, Cpf_Cnpj, RG, Observacao, Inscricao_Estadual, Inscricao_Municipal
						from cadastros_dados where Cadastro_ID = $cadastroID";
			//echo $sql;
			$query = mpress_query($sql);
			if($cadastro = mpress_fetch_array($query)){
				$tipoPessoa  = $cadastro[Tipo_Pessoa];
				if ($tipoPessoa=="24"){
					$cpf = $cadastro[Cpf_Cnpj];
					$nomeCompleto =  ($cadastro[Nome]);
					$dataNascimento = $cadastro[Data_Nascimento];
					$rg = $cadastro[RG];
				}
				if ($tipoPessoa=="25"){
					$cnpj = $cadastro[Cpf_Cnpj];
					$razaoSocial = ($cadastro[Nome]);
					$nomeFantasia = ($cadastro[Nome_Fantasia]);
					$inscricaoEstadual = $cadastro[Inscricao_Estadual];
					$inscricaoMunicipal = $cadastro[Inscricao_Municipal];
				}
				$tipoCadastro = $cadastro[Tipo_Cadastro];
				$observacao = $cadastro[Observacao];
				$email = ($cadastro[Email]);
			}

			$sql = "select Cadastro_Telefone_ID, t.Tipo_ID as Tipo_ID, t.Descr_Tipo, Telefone from cadastros_telefones ct left join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID where ct.Situacao_ID = 1 and Cadastro_ID = $cadastroID";
			$queryF = mpress_query($sql);
			while($fone = mpress_fetch_array($queryF)){
				if ($fone[Tipo_ID]=="27"){$telefoneComercial = $fone[Telefone];}
				if ($fone[Tipo_ID]=="28"){$telefoneCelular = $fone[Telefone];}
				if ($fone[Tipo_ID]=="29"){$telefoneResidencial = $fone[Telefone];}
			}

			$sql = "select Cadastro_Endereco_ID, Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia from cadastros_enderecos where Situacao_ID = 1 and Cadastro_ID = $cadastroID and Tipo_Endereco_ID IN(26,38) order by Cadastro_Endereco_ID desc limit 2 ";
			//echo $sql;
			$queryE = mpress_query($sql);
			while($endereco = mpress_fetch_array($queryE)){
					$cadastroEnderecoID[$endereco[Tipo_Endereco_ID]] = $endereco[Cadastro_Endereco_ID];
					$cep[$endereco[Tipo_Endereco_ID]] = $endereco[CEP];
					$logradouro[$endereco[Tipo_Endereco_ID]] = $endereco[Logradouro];
					$numero[$endereco[Tipo_Endereco_ID]] = $endereco[Numero];
					$complemento[$endereco[Tipo_Endereco_ID]] = $endereco[Complemento];
					$bairro[$endereco[Tipo_Endereco_ID]] = $endereco[Bairro];
					$cidade[$endereco[Tipo_Endereco_ID]] = $endereco[Cidade];
					$uf[$endereco[Tipo_Endereco_ID]] = $endereco[UF];
					$referencia[$endereco[Tipo_Endereco_ID]] = $endereco[Referencia];
			}


		}
		else{
			$cpf = $_POST['localiza-cpf'];
			$cnpj = $_POST['localiza-cnpj'];
			$tipoPessoa = 24;
			if ($cnpj !="")
				$tipoPessoa = 25;
		}
?>
				<input type='hidden' name='flag-pedido' id='flag-pedido' value='<?php echo $flagPedido; ?>'/>
				<input type='hidden' name='localiza-workflow-id' id='localiza-workflow-id' value=''/>

				<div id='cadastros-container' style='margin-top:0px'>
					<div class='titulo-container'>
						<div class="titulo" style="min-height:25px">
							<p style="margin-top:2px;">
								Cadastro
								<!--<input type="button" value="Excluir Cadastro" id="botao-excluir-cadastro" class="botao-excluir-produto" style="float: right; height: 24px; font-size: 10px; margin-top: -3px; margin-left: 3px; display: none;">-->
								<input type="button" value="Salvar Cadastro" id="botao-salvar-cadastro" class="cadastro-novo" style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;">
							</p>
						</div>
						<input type='hidden' id='localiza-cadastro-id' name='localiza-cadastro-id' value='<?php echo $cadastroID; ?>'/>
						<input type='hidden' id='cadastro-id' name='cadastro-id' value='<?php echo $cadastroID; ?>'/>

						<div class='conteudo-interno'>
							<div class='titulo-secundario quatro-colunas'>
								<p>Tipo Pessoa:<p>
								<p><?php echo (montaRadioGrupo(8, $tipoPessoa)); ?></p>
							</div>
							<!-- PF -->
							<div class='div-pf titulo-secundario quatro-colunas'>
								<p>CPF*</p>
								<p><input type='text' id='cadastro-cpf' name='cadastro-cpf'  maxlength='14' class='mascara-cpf validar-cpf' value='<?php echo $cpf; ?>'/></p>
							</div>
							<div class='div-pf titulo-secundario quatro-colunas'>
								<p>RG</p>
								<p><input type='text' id='cadastro-rg' name='cadastro-rg' maxlength='20' value='<?php echo $rg; ?>'/></p>
							</div>
							<div class='div-pf titulo-secundario quatro-colunas'>&nbsp;</div>
							<!-- PJ -->

							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>CNPJ*</p>
								<p><input type='text' id='cadastro-cnpj' name='cadastro-cnpj' maxlength='14' class='mascara-cnpj validar-cnpj' maxlength='250' value='<?php echo $cnpj; ?>'/></p>
							</div>
							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>Inscri&ccedil;&atilde;o Estadual</p>
								<p><input type='text' id='cadastro-inscricao-estadual' name='cadastro-inscricao-estadual' maxlength='20' maxlength='20' value='<?php echo $inscricaoEstadual; ?>'/></p>
							</div>
							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>Inscri&ccedil;&atilde;o Municpal</p>
								<p><input type='text' id='cadastro-inscricao-municipal' name='cadastro-inscricao-municipal' maxlength='14' class='mascara-cnpj' maxlength='20' value='<?php echo $inscricaoMunicipal; ?>'/></p>
							</div>

							<!-- PF -->
							<div class='div-pf titulo-secundario duas-colunas'>
								<p>Nome*</p>
								<p><input type='text' id='cadastro-nome' name='cadastro-nome' maxlength='250' value='<?php echo ($nomeCompleto); ?>'/></p>
							</div>


							<!-- PJ -->
							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>Nome Fantasia*</p>
								<p><input type='text' id='cadastro-nome-fantasia' name='cadastro-nome-fantasia'  maxlength='250' value='<?php echo $nomeFantasia; ?>'/></p>
							</div>
							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>Raz&atilde;o Social</p>
								<p><input type='text' id='cadastro-razao-social' name='cadastro-razao-social'  maxlength='250' value='<?php echo $razaoSocial; ?>'/></p>
							</div>

							<div class='titulo-secundario duas-colunas'>
								<p>E-mail</p>
								<p><input type='text' id='cadastro-email' name='cadastro-email'  maxlength='250' value='<?php echo $email; ?>' class='valida-email'/></p>
							</div>
							<!--
							<div class='titulo-secundario uma-coluna'>
								<p>Observa&ccedil;&atilde;o</p>
								<p><textarea id='cadastro-observacao' name='cadastro-observacao' Style='width:98.5%;height:30px;'><?php echo $observacao; ?></textarea></p>
							</div>
							-->
						</div>
					</div>
				</div>

				<div id='cadastros-container' style='margin-top:0px'>
					<div class='titulo-container'>
						<div class="titulo" style="min-height:25px">
							<p style="margin-top:2px;">
								Telefones
							</p>
						</div>
						<div class='conteudo-interno'>
							<div class='div-pf titulo-secundario quatro-colunas'>
								<p>Telefone Residencial* </p>
								<p><input type='text' id='cadastro-telefone-residencial' name='cadastro-telefone-residencial'  maxlength='15' value='<?php echo $telefoneResidencial; ?>' class='formata-telefone'/></p>
								</div>
							<div class='div-pj titulo-secundario quatro-colunas'>
								<p>Telefone Comercial*</p>
								<p><input type='text' id='cadastro-telefone-comercial' name='cadastro-telefone-comercial'  maxlength='15' value='<?php echo $telefoneComercial; ?>' class='formata-telefone'/></p>
							</div>
							<div class='titulo-secundario quatro-colunas'>
								<p>Telefone Celular*</p>
								<p><input type='text' id='cadastro-telefone-celular' name='cadastro-telefone-celular'  maxlength='15' value='<?php echo $telefoneCelular; ?>' class='formata-telefone'/></p>
							</div>
						</div>
					</div>
				</div>

				<div id='cadastros-container' style='margin-top:0px'>
					<div class='titulo-container'>
						<div class="titulo" style="min-height:25px">
							<p style="margin-top:2px;">
								Endere&ccedil;os
							</p>
						</div>
						<div class='conteudo-interno'>
							<input type='hidden' id='cadastro-endereco-id-26' name='cadastro-endereco-id[]' value='<?php echo $cadastroEnderecoID[26]; ?>'/>
							<p style='margin:2px;'><!--Tipo Endere&ccedil;o:-->
								<input type='hidden' id='tipo-endereco-id-26'  name='tipo-endereco-id[]' class='tipo-endereco' value='26'>
								<b style='margin-left:0px;'>PRINCIPAL</b>
							</p>
							<div class="titulo-secundario seis-colunas">
								<p>CEP*</p>
								<p><input type="text" class='mascara-cep' id="cep-endereco-26" name="cep-endereco[]" tipo-endereco='26' maxlength='9' value="<?php echo $cep[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario duas-colunas">
								<p>Logradouro*</p>
								<p><input type="text" id="logradouro-endereco-26" name="logradouro-endereco[]" maxlength='200' value="<?php echo $logradouro[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>N&uacute;mero*</p>
								<p><input type="text" id="numero-endereco-26" name="numero-endereco[]"  maxlength='20' value="<?php echo $numero[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Complemento</p>
								<p><input type="text" id="complemento-endereco-26" name="complemento-endereco[]" maxlength='100' value="<?php echo $complemento[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Bairro*</p>
								<p><input type="text" id="bairro-endereco-26" name="bairro-endereco[]" maxlength='50' value="<?php echo $bairro[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Cidade*</p>
								<p><input type="text"  id="cidade-endereco-26" name="cidade-endereco[]"  maxlength='50'  value="<?php echo $cidade[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>UF*</p>
								<p><input type="text" id="uf-endereco-26" name="uf-endereco[]"  maxlength='2'  value="<?php echo $uf[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Refer&ecirc;cia</p>
								<p><input type="text" id="referencia-endereco-26" name="referencia-endereco[]" value="<?php echo $referencia[26]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>&nbsp;</p>
								<p><div class='btn-retrair' id='btn-copiar-colar' tipo-endereco-de='26' tipo-endereco-para='38'   style='float:left;' title='Copiar Colar'></div></p>
							</div>
						</div>
						<div class='conteudo-interno'>
							<input type='hidden' id='cadastro-endereco-id' name='cadastro-endereco-id[]' value='<?php echo $cadastroEnderecoID[38]; ?>'/>
							<p style='margin:0px;'><!--Tipo Endere&ccedil;o:-->
								<input type='hidden' id='tipo-endereco-id-38'  name='tipo-endereco-id[]' class='tipo-endereco' value='38'>
								<b style='margin-left:2px'>INSTALA&Ccedil;&Atilde;O</b>
							</p>
							<div class="titulo-secundario seis-colunas">
								<p>CEP*</p>
								<p><input type="text" class='mascara-cep' id="cep-endereco-38" name="cep-endereco[]" tipo-endereco='38' maxlength='9' value="<?php echo $cep[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario duas-colunas">
								<p>Logradouro*</p>
								<p><input type="text" id="logradouro-endereco-38" name="logradouro-endereco[]" maxlength='200' value="<?php echo $logradouro[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>N&uacute;mero*</p>
								<p><input type="text" id="numero-endereco-38" name="numero-endereco[]"  maxlength='20' value="<?php echo $numero[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Complemento</p>
								<p><input type="text" id="complemento-endereco-38" name="complemento-endereco[]" maxlength='100' value="<?php echo $complemento[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Bairro*</p>
								<p><input type="text" id="bairro-endereco-38" name="bairro-endereco[]" maxlength='50' value="<?php echo $bairro[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Cidade*</p>
								<p><input type="text"  id="cidade-endereco-38" name="cidade-endereco[]"  maxlength='50'  value="<?php echo $cidade[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>UF*</p>
								<p><input type="text" id="uf-endereco-38" name="uf-endereco[]"  maxlength='2'  value="<?php echo $uf[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>Refer&ecirc;cia</p>
								<p><input type="text" id="referencia-endereco-38" name="referencia-endereco[]" value="<?php echo $referencia[38]; ?>"/></p>
							</div>
							<div class="titulo-secundario seis-colunas">
								<p>&nbsp;</p>
								<p><div class='btn-expandir' id='btn-copiar-colar' tipo-endereco-de='38' tipo-endereco-para='26'  style='float:left;' title='Copiar Colar'></div></p>
							</div>
						</div>
					</div>
				</div>
<?php
	if ($cadastroID!=""){
?>

				<div id='cadastros-container' style='margin-top:0px'>
					<div class='titulo-container'>
						<div class="titulo" style="min-height:25px">
							<p style="margin-top:2px;">
								Pedidos
								<input type="button" value="Novo Pedido" id='botao-novo-pedido' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;">
							</p>
						</div>
						<div class='conteudo-interno'>
<?php
		$sql = "select tw.Workflow_ID as Workflow_ID, tf.Descricao as Ultima_Interacao,
					DATE_FORMAT(tw.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Cadastro, coalesce(ts.Descr_Tipo,'N/A') as Situacao, usu.Nome as Usuario
						from cadastros_dados cd
						left join telemarketing_workflows tw on tw.Solicitante_ID = cd.Cadastro_ID
						left join cadastros_dados usu on usu.Cadastro_ID = tw.Usuario_Cadastro_ID
						left join telemarketing_follows tf on tw.Workflow_ID = tf.Workflow_ID
							and tf.Follow_ID = (select max(tfaux.Follow_ID) from telemarketing_follows tfaux where tf.Workflow_ID = tfaux.Workflow_ID)
						left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
						where cd.Situacao_ID = 1
						and tw.Solicitante_ID = $cadastroID
						order by cd.Cadastro_ID";

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$editarWorkflow = "<input type='button' value='Visualizar Pedido' class='editar-workflow' workflow-id='".$row[Workflow_ID]."' Style='height:20px;font-size:10px;margin-top:-3px;width:100px' />";

			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 10px;float:right;' class='link editar-workflow' workflow-id='".$row[Workflow_ID]."' >".$row[Workflow_ID]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Ultima_Interacao]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Situacao]."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 15px;float:left;'>".$row[Data_Cadastro]."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 15px;float:left;'>".$row[Usuario]."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 15px;float:left;'>".$editarWorkflow."</p>";
		}
		$largura = "100%";
		$colunas = "6";
		$dados[colunas][tamanho][1] = "width='80px'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='180px'";
		$dados[colunas][tamanho][4] = "width='150px'";
		$dados[colunas][tamanho][5] = "width='200px'";
		$dados[colunas][tamanho][6] = "width='80px'";

		$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 10px;float:left;'>N&ordm; Pedido</p>";
		$dados[colunas][titulo][2] 	= "&Uacute;ltima Intera&ccedil;&atilde;o";
		$dados[colunas][titulo][3] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][4] 	= "<p Style='margin:2px 5px 0 10px;float:left;'>Data Abertura</p>";
		$dados[colunas][titulo][5] 	= "<p Style='margin:2px 5px 0 10px;float:left;'>Usu&aacute;rio</p>";
		$dados[colunas][titulo][6] 	= "";
		geraTabela($largura,$colunas,$dados);
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum pedido realizado</p>";
		}
?>
					</div>
				</div>
			</div>
<?php
	}
?>
			<script>
				$('.mascara-cpf').mask('999.999.999-99');
				$('.mascara-cnpj').mask('99.999.999/9999-99');
				$(".mascara-cep").mask("99999-999");
			</script>
<?php
	}
?>