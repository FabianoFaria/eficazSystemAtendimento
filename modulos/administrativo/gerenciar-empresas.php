<?php
	//error_reporting(E_ERROR);
	//session_start();
	include("functions.php");

	global $caminhoFisico, $modulosAtivos, $modulosGeral;
	global $arrayICMSsimples, $arrayICMSnormal, $arrayIPIentrada, $arrayIPIsaida, $arrayPISCONFINS, $arrayCRT, $arrayAmbiente;

	//cores
	$cRed = '#FF0000';
	$cGreen = '#00CC00';
	$empresaID = $_POST['select-empresa'];
	if ($_POST['empresa-id-nova']!=''){
		$empresaID = $_POST['empresa-id-nova'];
	}

	if ($empresaID==""){
		$classeEsconde = " esconde";
	}
	else{
		if ($empresaID=="-1")
			$classeEscondeNovo = " esconde";
		else{
			if ($modulosGeral['nfe']){
				require_once($caminhoFisico.'/modulos/nfe/arrays-nfe.php');
				require_once($caminhoFisico.'/modulos/nfe/functions.php');

				$configNF = retornaArrayConfigNF($empresaID);

				$ambiente = $configNF[ambiente];
				$empresa = $configNF[empresa];
				$fantasia = $configNF[fantasia];
				$cnpj = $configNF[cnpj];
				$enderecoSelecionado = $configNF[enderecoSelecionado];
				$telefoneSelecionado = $configNF[telefoneSelecionado];

				$certName = $configNF[certName];
				$keyPass = $configNF[keyPass];
				$arquivosDir = $configNF[arquivosDir];
				$arquivosDirCTe = $configNF[arquivosDirCTe];
				$arquivoURLxml = $configNF[arquivoURLxml];
				$baseurl = $configNF[baseurl];
				$CRT = $configNF[CRT];


				$danfeLogo = $configNF[danfeLogo];
				$danfeLogoPos = $configNF[danfeLogoPos];
				$danfeFormato = $configNF[danfeFormato];
				$danfePapel = $configNF[danfePapel];
				$danfeCanhoto = $configNF[danfeCanhoto];
				$danfeFonte = $configNF[danfeFonte];
				$danfePrinter = $configNF[danfePrinter];

				$dactePapel = $configNF[dactePapel];
				$dacteFormato = $configNF[dacteFormato];
				$dacteCanhoto =  $configNF[dacteCanhoto];
				$dacteLogo =  $configNF[dacteLogo];
				$dacteLogoPos =  $configNF[dacteLogoPos];
				$dacteFonte =  $configNF[dacteFonte];
				$dactePrinter =  $configNF[dactePrinter];

				$schemes = $configNF[schemes];
				$schemesCTe = $configNF[schemesCTe];
				$certsDir = $configNF[certsDir];

				//echo "<pre>";
				//print_r($configNF);
				//echo "</pre>";
			}
		}
	}

	echo "	<div id='div-retorno'></div>
			<input type='hidden' id='cadastroID' name='cadastroID'/>
			<input type='hidden' id='empresa-id-nova' name='empresa-id-nova'/>
			<div id='container-geral'>
				<div class='titulo-container conjunto1'>
					<div class='titulo' style='min-height:22px'>
						<p style='margin-top:2px;float:left;'>Empresas</p>
						<p style='margin-top:2px;float:right;' class='$classeEsconde'><input type='button' value='Salvar' class='salvar-empresa' Style='width:100px' destino='menu-superior-1'></p>
						<p style='margin-top:2px;float:right;' class='$classeEsconde $classeEscondeNovo'><input type='button' value='Excluir' id='exclui-empresa' destino='menu-superior-1' Style='width:100px'></p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario Style='width:100%' id='seleciona-empresa'>
							<p class='omega' Style='height:34px'>
								<select name='select-empresa' id='select-empresa' Style='margin-top:5px;'>";
	if($empresaID=="-1") $selecionado = "selected"; else $selecionado = "";
	echo "							<option value=''>Selecione</option>
									<option value='-1' $selecionado>Cadastrar Nova</option>";
	$rs = mpress_query("select Cadastro_ID, Nome, Nome_Fantasia, Cpf_Cnpj, Centro_Custo_ID from cadastros_dados where Situacao_ID = 1 and Empresa = 1");
	while($row = mpress_fetch_array($rs)){

		if($empresaID==$row['Cadastro_ID']){
			$selecionado = "selected";
			$empresa = $row['Nome'];
			$cnpj = $row['Cpf_Cnpj'];
			$centroCustoID = $row['Centro_Custo_ID'];

			/***** INICIO ENDERECOS ******/
			$resultado = mpress_query("select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
										from cadastros_enderecos ce left join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
										where Cadastro_ID = '$empresaID' and ce.Situacao_ID = 1");
			while($rowE = mpress_fetch_array($resultado)){
				if ($configNF[enderecoSelecionado]==$rowE['Cadastro_Endereco_ID']) $enderecoSelected = "selected"; else $enderecoSelected = "";
				$optionEnderecos .= "<option value='".$rowE['Cadastro_Endereco_ID']."' $enderecoSelected>".($rowE[Logradouro])."&nbsp;".$rowE[Numero]."&nbsp;".($rowE[Complemento])." &nbsp;&nbsp;CEP:".$rowE[CEP]."&nbsp;&nbsp;".($rowE[Referencia])." &nbsp;&nbsp;".($rowE[Bairro])."&nbsp;&nbsp;".($rowE[Cidade])."&nbsp;&nbsp;".$rowE[UF]."</option>";
			}
			/***** FIM ENDERECOS ******/

			/***** INICIO TELEFONES ******/
			$resultado = mpress_query("select Cadastro_Telefone_ID, Telefone from cadastros_telefones where Cadastro_ID = '$empresaID' and Situacao_ID = 1");
			while($rowT = mpress_fetch_array($resultado)){
				if ($telefoneSelecionado==$rowT['Cadastro_Telefone_ID']) $telefoneSelected = "selected"; else $telefoneSelected = "";
				$optionTelefones .= "<option value='".$rowT['Cadastro_Telefone_ID']."' $telefoneSelected>".$rowT[Telefone]."</option>";
			}
			/***** FIM TELEFONES ******/

		}
		else{
			$selecionado = "";
		}
		echo "						<option value='".$row['Cadastro_ID']."' $selecionado>".$row['Nome']." - ".$row['Nome_Fantasia']." - ".$row['Cpf_Cnpj']."</option>";
	}
	echo "						</select>
							</p>
						</div>
						";

	if($empresaID!=""){
		$empresaCarregaID = $empresaID;
		if ($empresaID=="-1") $empresaCarregaID = "";
		echo "			<div class='titulo-secundario'>".carregarBlocoCadastroGeral($empresaCarregaID, 'empresa-selecionada','Empresa',1,'')."</div>";
		if ($modulosAtivos[financeiro]){
			echo "		<div class='titulo-secundario' style='margin-top:10px;'>
							<p><b>Centro de Custo</b></p>
							<p><select name='centro-custo-id' id='centro-custo-id' style='width:99.5%'>".optionValueGrupo(26, $centroCustoID)."</select></p>
						</div>";
		}
	}
	echo "			</div>
				</div>";

	if (($empresaID>0) && ($modulosGeral['nfe'])){
		//Verificação da validade do certificado
		if (incluirClasseTools()){
			$nfe = new ToolsNFePHP($configNF, 0);
			if ($nfe->certDaysToExpire > 0) {
				$certVal = "<font color='blue'>Certificado v&aacute;lido (+" . $nfe->certDaysToExpire . ' dias)</font>';
			} else {
				$certVal = "<font color='red'>Certificado inv&aacute;lido ".$nfe->errMsg."</font>";
			}
		}
		else{
			$certVal = "Diretorio com biblioteca NFE não encontrado !!!";
		}
		//echo "<pre>";
		//print_r($nfe);
		//echo "</pre>";

		//Tipo de ambiente
		if ($ambiente == 1) {
			$selAmb2 = '';
			$selAmb1 = 'selected';}
		else {
			$selAmb1 = '';
			$selAmb2 = 'selected';}

		//Fontes básicas compiladas no FPDF
		$aFontes = explode('.', 'Times.Helvetica.Corrier');
		$i = 0;
		foreach ($aFontes as $f) {
			if ($danfeFonte == $f) {
				$dfont = "\$selFont{$i} = \"".'selected=\"selected\"'."\";";
			} else {
				$dfont = "\$selFont{$i} = '';";
			}
			eval($dfont);
			$i++;
		}

		//Danfe formato
		if ($danfeFormato=='P') {
			$selFormP = 'selected';
			$selFormL = '';
		} else {
			$selFormL = 'selected';
			$selFormP = '';
		}

		//Danfe canhoto
		if ($danfeCanhoto) {
			$selCanh1 = 'selected';
			$selCanh0 = '';
		} else {
			$selCanh0 = 'selected';
			$selCanh1 = '';
		}

		//Danfe posicao logo
		if ($danfeLogoPos == 'L') {
			$seldposL = 'selected';
			$seldposC = '';
			$seldposR = '';
		}
		if ($danfeLogoPos == 'C') {
			$seldposC = 'selected';
			$seldposL = '';
			$seldposR = '';
		}
		if ($danfeLogoPos == 'R') {
			$seldposR = 'selected';
			$seldposC = '';
			$seldposL = '';
		}

		//Dacte formato
		if ($dacteFormato=='P') {
			$selcteFormP = 'selected';
			$selcteFormL = '';
		} else {
			$selcteFormL = 'selected';
			$selcteFormP = '';
		}

		//Dacte canhoto
		if ($dacteCanhoto) {
			$selcteCanh1 = 'selected';
			$selcteCanh0 = '';
		} else {
			$selcteCanh0 = 'selected';
			$selcteCanh1 = '';
		}

		//Dacte posicao logo
		if ($dacteLogoPos == 'L') {
			$selctedposL = 'selected';
			$selctedposC = '';
			$selctedposR = '';
		}
		if ($dacteLogoPos == 'C') {
			$selctedposC = 'selected';
			$selctedposL = '';
			$selctedposR = '';
		}
		if ($dacteLogoPos == 'R') {
			$selctedposR = 'selected';
			$selctedposC = '';
			$selctedposL = '';
		}

		//Autenticação obrigatória para email
		if ($mailAuth == 1) {
			$selMAuthS = 'selected';
			$selMAuthN = '';
		} else {
			$selMAuthN = 'selected';
			$selMAuthS = '';
		}
		if ($mailPROTOCOL == 'ssl') {
			$selMprotS = 'selected';
			$selMprotT = '';
			$selMprotN = '';
		}
		if ($mailPROTOCOL == 'tls') {
			$selMprotT = 'selected';
			$selMprotS = '';
			$selMprotN = '';
		}
		if ($mailPROTOCOL == '') {
			$selMprotN = 'selected';
			$selMprotS = '';
			$selMprotT = '';
		}
		//url
		$guessedUrl = 'http://'.$_SERVER["SERVER_NAME"].$_SERVER["SCRIPT_NAME"];
		$guessed_url = rtrim(dirname($guessedUrl), 'index.php');

		if ($CRT=="1") $CRTSel1 = 'selected'; if ($CRT=="2") $CRTSel2 = 'selected'; if ($CRT=="3") $CRTSel3 = 'selected';
		if ($CRT=="3") $arrayICMS = $arrayICMSnormal; else $arrayICMS = $arrayICMSsimples;

		//echo "<pre>";
		//print_r($configNF);
		//echo "</pre>";


		$diretorio = $certsDir;
		$ponteiro  = opendir($diretorio);
		while ($nomeItem = readdir($ponteiro)) {
			if ($nomeItem != "." && $nomeItem != ".." && (strtolower(end(explode(".", $nomeItem)))=='pfx')) {
				if ($certName == $nomeItem) $selecionado = "selected"; else $selecionado = "";
				$optionValueCertificados .= "<option value='$nomeItem' $selecionado>$nomeItem</option>";
			}
		}

		echo "
				<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p>Configurador NF-E / PHP <input type='button' value='Salvar' class='salvar-empresa' Style='width:100px' destino='menu-superior-2'></p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' Style='float:left; width:15%; margin-top:5px;'>
							<p><b>Ambiente</b></p>
							<p>
								<select name='ambiente' size='1' id='ambiente'>
    		    		          <option value='1' $selAmb1>Produ&ccedil;&atilde;o</option>
    		    		          <option value='2' $selAmb2>Homologa&ccedil;&atilde;o</option>
    		    		        </select>
    		    		    </p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:35%; margin-top:5px;'>
							<p><b>Caminho certificado</b></p>
							<p><input name='certsDir' type='text' id='certsDir' value='$certsDir'  maxlength='200' style='width:97%;'/></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Certificado Digital </b><i>$certVal</i></p>
							<p>
								<select name='pfx' id='pfx'>
									<option value=''></option>
									$optionValueCertificados
								</select>
							</p>

<!--						<p><input name='pfx' type='text' id='pfx' value='$certName' maxlength='200' style='width:96%;'></p>-->
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Senha da chave privada</b></p>
							<p><input name='keysenha' type='text' id='keysenha' value='$keyPass' maxlength='30' style='width:95%;' ></p>
							<input type='hidden' name='passe' id='passe' value='$passPhrase' size='20' maxlength='30'>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Diret&oacute;rio de arquivo das NFe XML</b></p>
							<p><input name='dirnfe' type='text' id='dirnfe' value='$arquivosDir' maxlength='200' style='width:96%;'></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Diret&oacute;rio de arquivo das CTe</b></p>
							<p><input name='dircte' type='text' id='dircte' value='$arquivosDirCTe' maxlength='200' style='width:95%;'></p>
    		    		</div>

    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p class='link' title='$guessed_url'><b>URL base da API</b></p>
							<p><input name='urlapi' type='text' id='urlapi' value='$baseurl'  maxlength='200' style='width:97%;'/></p>
    		    		</div>

    		    		<div class='titulo-secundario' Style='float:left; width:12.5%; margin-top:5px;'>
							<p><b>WebServices NFe</b></p>
							<p><input type='text' name='urlws'  id='urlws' value='$arquivoURLxml'  maxlength='200' style='width:89%;'/></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:12.5%; margin-top:5px;'>
							<p><b>WebServices CTe</b></p>
							<p><input type='text' name='urlwscte' id='urlwscte' value='".$configNF[arquivoURLxmlCTe]."' maxlength='200' style='width:89%;'/></p>
    		    		</div>

						<div class='titulo-secundario' Style='float:left; width:50%; margin-top:5px;'>
							<p><b>Endere&ccedil;o do Emitente</b></p>
							<p>
								<select name='enderecoemitente' size='1' id='enderecoemitente' style='width:99%;'>
									$optionEnderecos
    		    		        </select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:15%; margin-top:5px;'>
							<p><b>Telefone Emitente</b></p>
							<p>
								<select name='telefoneemitente' size='1' id='telefoneemitente' style='width:99%;'>
									$optionTelefones
    		    		        </select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>CNAE Fiscal</b></p>
							<p><input type='text' name='cnaefiscal' id='cnaefiscal' value='".$configNF[CNAE]."' maxlength='7' style='width:90%;' class='formata-numero'/></p></p>
    		    		</div>

						<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Regime Tribut&aacute;rio</b></p>
							<p><select name='regimetributario' id='regimetributario' style='width:96.5%;'>
								<option value='1' $CRTSel1>Simples Nacional</option>
								<option value='2' $CRTSel2>Simples Nacional – excesso de sublimite de receita bruta</option>
								<option value='3' $CRTSel3>Regime Normal. (v2.0)</option>
    		    		       </select>
    		    		</div>
    		    	</div>
    		    </div>


				<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p>Padr&otilde;es de Tributa&ccedil;&atilde;o Sa&iacute;da</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Tributa&ccedil;&atilde;o ICMS Padr&atilde;o Sa&iacute;da</b></p>
							<p><select name='cst-icms-padrao-saida' id='cst-icms-padrao-saida' style='width:95%;'>
								<option value=''></option>
								".optionValueTributacao($arrayICMS, $configNF[cst_icms_padrao_saida])."
								</select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:5%; margin-top:5px;'>
							<p align='center'><b>%</b></p>
							<p><input type='text' name='percentual-icms-padrao-saida' id='percentual-icms-padrao-saida' value='".number_format($configNF[percentual_icms_padrao_saida],2,",","")."' style='width:80%;' class='formata-valor'/></p>
						</div>


						<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Tributa&ccedil;&atilde;o IPI Padr&atilde;o Sa&iacute;da</b></p>
							<p><select name='cst-ipi-padrao-saida' id='cst-ipi-padrao-saida' style='width:95%;'>
								<option value=''></option>
								".optionValueTributacao($arrayIPIsaida, $configNF[cst_ipi_padrao_saida])."
								</select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:5%; margin-top:5px;'>
							<p align='center'><b>%</b></p>
							<p><input type='text' name='percentual-ipi-padrao-saida' id='percentual-ipi-padrao-saida' value='".number_format($configNF[percentual_ipi_padrao_saida],2,",","")."' style='width:80%;' class='formata-valor'/></p>
						</div>


						<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Tributa&ccedil;&atilde;o PIS Padr&atilde;o Sa&iacute;da</b></p>
							<p><select name='cst-pis-padrao-saida' id='cst-pis-padrao-saida' style='width:95%;'>
								<option value=''></option>
								".optionValueTributacao($arrayPISCONFINS, $configNF[cst_pis_padrao_saida])."
								</select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:5%; margin-top:5px;'>
							<p align='center'><b>%</b></p>
							<p><input type='text' name='percentual-pis-padrao-saida' id='percentual-pis-padrao-saida' value='".number_format($configNF[percentual_pis_padrao_saida],2,",","")."' style='width:80%;' class='formata-valor'/></p>
						</div>



						<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Tributa&ccedil;&atilde;o COFINS Padr&atilde;o Sa&iacute;da</b></p>
							<p><select name='cst-cofins-padrao-saida' id='cst-cofins-padrao-saida' style='width:95%;'>
								<option value=''></option>
								".optionValueTributacao($arrayPISCONFINS, $configNF[cst_cofins_padrao_saida])."
								</select>
							</p>
    		    		</div>
						<div class='titulo-secundario' Style='float:left; width:5%; margin-top:5px;'>
							<p align='center'><b>%</b></p>
							<p><input type='text' name='percentual-cofins-padrao-saida' id='percentual-cofins-padrao-saida' value='".number_format($configNF[percentual_cofins_padrao_saida],2,",","")."' style='width:80%;' class='formata-valor'/></p>
						</div>


						<div class='titulo-secundario' style='float:left; width:25%'>
							<p><b>Lista de Servi&ccedil;os</b></p>
							<p><select name='lista-servico-padrao' class='obrigatorio' bloco-pai='menu-superior-2' style='width:99%'>
								<option value=''></option>
								".optionValueTributacao($arrayLC,$configNF[lista_servico_padrao])."
								</select>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:20%'>
							<p><b>Tributa&ccedil;&atilde;o ISSQN</b></p>
							<p><select name='cst-icsqn-servico-padrao' class='obrigatorio' bloco-pai='menu-superior-2' style='width:99%'>
									<option value=''></option>
									".optionValueTributacao($arrayISSQN, $configNF[cst_icsqn_servico_padrao])."
								</select></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:5%'>
							<p align='center'><b>%</b></p>
							<p><input type='text' name='percentual-issqn-servico-padrao' class='obrigatorio formata-valor' bloco-pai='menu-superior-2' maxlength='20' value='".number_format($configNF[percentual_issqn_servico_padrao], 2, ',', '.')."'  style='width:85%'/></p>
						</div>

						<div class='titulo-secundario' Style='float:left; width:25%;'>
							<p><b>CFOP Descritivo</b></p>
							<p><input type='text' name='cfop-descr-padrao-saida' id='cfop-descr-padrao-saida' value='".$configNF["cfop_descr_padrao_saida"]."' style='width:97.5%;' class=''/></p>
						</div>
						<div class='titulo-secundario' Style='float:left; width:25%;'>
							<p><b>CFOP Padr&atilde;o Sa&iacute;da</b></p>
							<p><select name='cfop-padrao-saida' id='cfop-padrao-saida' class='cfop-padrao-saida' style='width:97.8%'><option value=''></option>".optionValueTributacao($arrayCFOPsaida,$configNF['cfop_padrao_saida'],1)."</select></p>
						</div>
						<!--  colocar aqui as tributações padrão da empresa IPI ICMS -->
   		    		</div>
		    	</div>";

		echo "	<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p style='margin-top:2px;float:left;'>Schemas</p>
					</div>
					<div class='conteudo-interno'>
    		    		<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Vers&atilde;o (veja pasta schemes)</b></p>
							<p><input name='schema' type='text' id='schema' value='$schemes' maxlength='200' style='width:95%;'></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Vers&atilde;o CTe (veja pasta schemes)</b></p>
							<p><input name='schemacte' type='text' id='schemacte' value='$schemesCTe' maxlength='200' style='width:95%;'></p>
    		    		</div>
   		    		</div>
	    		</div>";

		echo "	<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p style='margin-top:2px;float:left;'>Configura&ccedil;&atilde;o da DANFE</p>
					</div>
					<div class='conteudo-interno'>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Formato</b></p>
							<p>
								<select name='formato' id='formato' style='width:90%;'>
									<option value='P' $selFormP>Portraite</option>
									<option value='L' $selFormL>Landscape</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Papel</b></p>
							<p><input name='papel' type='text' id='papel' value='$danfePapel' style='width:90%;' maxlength='2'></p>
    		    		</div>

    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Canhoto</b></p>
							<p>
								<select name='canhoto' style='width:90%;' id='canhoto'>
									<option value='1' $selCanh1>SIM</option>
									<option value='0' $selCanh0>N&Atilde;O</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Posi&ccedil;&atilde;o da Logo na DANFE</b></p>
							<p>
								<select name='logopos' style='width:90%;' id='logopos'>
									<option value='L' $seldposL>Esquerda</option>
									<option value='C' $seldposC>Centro</option>
									<option value='R' $seldposR>Direita</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Fonte</b></p>
							<p>
								<select name='fonte' style='width:90%;' id='fonte'>
									<option value='Times' $selFont0>Times</option>
									<option value='Helvetica' $selFont1>Helvetica</option>
									<option value='Corrier' $selFont2>Corrier</option>
			                    </select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:15%; margin-top:5px;'>
							<p><b>Impressora Padr&atilde;o </b></p>
							<p><input name='printer' type='text' id='printer' value='$danfePrinter'style='width:90%;' maxlength='40'></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Logo</b></p>
							<p><input name='logo' type='text' id='logo' value='$danfeLogo' style='width:90%;' maxlength='200'></p>
    		    		</div>
   		    		</div>
	    		</div>";

		echo "	<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p style='margin-top:2px;float:left;'>Configura&ccedil;&atilde;o do DACTE</p>
					</div>
					<div class='conteudo-interno'>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Formato</b></p>
							<p>
								<select name='formatocte' id='formatocte' style='width:90%;'>
									<option value='P' $selcteFormP>Portraite</option>
									<option value='L' $selcteFormL>Landscape</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Papel</b></p>
							<p><input name='papelcte' type='text' id='papelcte' value='$dactePapel' style='width:90%;' maxlength='2'></p>
    		    		</div>

    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Canhoto</b></p>
							<p>
								<select name='canhotocte' style='width:90%;' id='canhotocte'>
									<option value='1' $selcteCanh1>SIM</option>
									<option value='0' $selcteCanh0>N&Atilde;O</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:20%; margin-top:5px;'>
							<p><b>Posi&ccedil;&atilde;o da Logo na DACTE</b></p>
							<p>
								<select name='logoposcte' style='width:90%;' id='logoposcte'>
									<option value='L' $selctedposL>Esquerda</option>
									<option value='C' $selctedposC>Centro</option>
									<option value='R' $selctedposR>Direita</option>
								</select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:10%; margin-top:5px;'>
							<p><b>Fonte</b></p>
							<p>
								<select name='fontecte' style='width:90%;' id='fontecte'>
									<option value='Times' $selcteFont0>Times</option>
									<option value='Helvetica' $selcteFont1>Helvetica</option>
									<option value='Corrier' $selcteFont2>Corrier</option>
			                    </select>
							</p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:15%; margin-top:5px;'>
							<p><b>Impressora Padr&atilde;o </b></p>
							<p><input name='printercte' type='text' id='printercte' value='$dactePrinter' style='width:90%;' maxlength='40'></p>
    		    		</div>
    		    		<div class='titulo-secundario' Style='float:left; width:25%; margin-top:5px;'>
							<p><b>Logo</b></p>
							<p><input name='logocte' type='text' id='logocte' value='$dacteLogo' style='width:90%;' maxlength='200'></p>
    		    		</div>
    		    	</div>
				</div>
			</div>";



		//versão do php
		$phpversion = str_replace('-', '', substr(PHP_VERSION, 0, 6));
		$phpver = convVer($phpversion);
		if ($phpver > '050200')
			$phpcor = $cGreen;
		else
			$phpcor = $cRed;

		//path
		$pathdir = dirname(__FILE__);

		//teste dos modulos
		$modules = new moduleCheck();

		//Testa modulo cURL
		$modcurl = false;
		if ($modcurl = $modules->isLoaded('curl')) {
			$modcurl_ver = $modules->getModuleSetting('curl', 'cURL Information');
			$modcurl_ssl = $modules->getModuleSetting('curl', 'SSL Version');
		}
		$cCurl = $cRed;
		$curlver = ' N&atilde;o instalado !!!';
		if ($modcurl) {
			$curlver = convVer($modcurl_ver);
			if ($curlver > '071002') {
				$curlver = ' vers&atilde;o ' . $modcurl_ver;
				$cCurl = $cGreen;
			}
		}
		//Testa modulo OpenSSL
		$modssl = $modules->isLoaded('openssl');
		if ($modssl) {
			$modssl_ver = $modules->getModuleSetting('openssl', 'OpenSSL Library Version');
			$modssl_enable = $modules->getModuleSetting('openssl', 'OpenSSL support');
		}
		$cSSL = $cRed;
		$sslver = ' N&atilde;o instalado !!!';
		if ($modssl) {
			if ($modssl_enable=='enabled') {
				$cSSL = $cGreen;
				$sslver = $modssl_ver;
			}
		}

		//Testa modulo DOM
		$moddom = $modules->isLoaded('dom');
		if ($moddom) {
			$moddom_enable = $modules->getModuleSetting('dom', 'DOM/XML');
			$moddom_libxml = $modules->getModuleSetting('dom', 'libxml Version');
		}
		$cDOM = $cRed;
		$domver = ' N&atilde;o instalado !!!';
		if ($moddom) {
			$domver = convVer($moddom_libxml);
			if ($domver > '020600' && $moddom_enable=='enabled') {
				$domver = ' libxml vers&atilde;o ' . $moddom_libxml;
				$cDOM = $cGreen;
			} else {
				$domver = '';
			}
		}

		//Testa modulo gd
		$modgd = $modules->isLoaded('gd');
		if ($modgd) {
			$modgd_ver = $modules->getModuleSetting('gd', 'GD Version');
		}
		$cgd = $cRed;
		$gdver = ' N&atilde;o instalado !!!';
		if ($modgd) {
			$gdver = convVer($modgd_ver);
			if ($gdver  > '010101') {
				$cgd = $cGreen;
				$gdver = ' vers&atilde;o ' . $modgd_ver;
			}
		}

		//Testa modulo SOAP
		$modsoap = $modules->isLoaded('soap');
		if ($modsoap) {
			$modsoap_enable = $modules->getModuleSetting('soap', 'Soap Client');
		}
		$cSOAP = $cRed;
		$soapver = ' N&atilde;o instalado !!!';
		if ($modsoap) {
			if ($modsoap_enable=='enabled') {
				$cSOAP = $cGreen;
				$soapver = $modsoap_enable;
			}
		}

		//Testa modulo zip
		$modzip = $modules->isLoaded('zip');
		if ($modzip) {
			$modzip_enable = $modules->getModuleSetting('zip', 'Zip');
			$modzip_ver = $modules->getModuleSetting('zip', 'Zip version');
		}
		$cZIP = $cRed;
		$zipver = ' N&atilde;o instalado !!!';
		if ($modzip) {
			if ($modzip_enable=='enabled') {
				$cZIP = $cGreen;
				$zipver = ' vers&atilde;o ' . $modzip_ver;
			}
		}

		/* CARREGAR DADOS DA EMPRESA SELECIONADA */

		echo "	<div class='titulo-container conjunto2 esconde'>
					<div class='titulo' style='min-height:22px'>
						<p style='margin-top:2px;float:left;'>Validador</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' Style='float:left; width:100%'>
							<p><b>Requisitos B&aacute;sicos</b></p>
							<p>
								<table width='99%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
									<tr>
									  <td class='tabela-fundo-escuro-titulo'> <div align='center'><b>M&oacute;dulos</b></td>
									  <td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Status</b></td>
									  <td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Comentario</b></td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>PHP vers&atilde;o $phpversion</td>
									  <td class='tabela-fundo-claro' style='background-color:$phpcor'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro' >A vers&atilde;o do PHP deve ser 5.2 ou maior</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>cURL $curlver [$modcurl_ssl]</td>
									  <td class='tabela-fundo-claro' style='background-color:$cCurl'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>A vers&atilde;o do cURL deve ser 7.10.2 ou maior</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>SSL $sslver</td>
									  <td class='tabela-fundo-claro' style='background-color:$cSSL'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>A vers&atilde;o do OpenSSL deve ser 0.9.0 ou maior</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>DOM $domver</td>
									  <td class='tabela-fundo-claro'style='background-color:$cDOM'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>O vers&atilde;o do libxml deve ser 2.7.0 ou maior</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>SOAP </td>
									  <td class='tabela-fundo-claro' style='background-color:$cSOAP'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>$soapver</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>GD $gdver</td>
									  <td class='tabela-fundo-claro' style='background-color:$cgd'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>gd &eacute; necess&aacute;rio para DANFE</td>
									</tr>
									<tr>
									  <td class='tabela-fundo-claro'>ZIP $zipver</td>
									  <td class='tabela-fundo-claro' style='background-color:$cZIP'><div align='center'>ok</div></td>
									  <td class='tabela-fundo-claro'>ZIP necess&aacute;rio para download da NFe</td>
									</tr>
								</table>
							</p>
						</div>";



						//Teste de escrita no diretorio dos certificados
						$dirCerts 		= $configNF[certsDir];
						$filen 			= $dirCerts."/teste.txt";
						$cdCerts = $cRed;
						$wdCerts = ' Sem permiss&atilde;o !!';
						if (!(is_dir($dirCerts))){
							if (!(mkdir($dirCerts, 0777))){
								$wdCerts= ' Sem permiss&atilde;o para criar diretorio!!';
							}
						}
						if (file_put_contents($filen, "teste\r\n")) {
							$cdCerts = $cGreen;
							$wdCerts = ' Permiss&atilde;o OK';
							unlink($filen);
						}

						//Teste de escrita no arquivo config/numloteenvio.xml e config/config.php
						$filen = $caminhoFisico.'/modulos/nfe/config/numloteenvio.xml';
						if (file_exists($filen)) {
							//copia o conteudo
							if ($conteudo = file_get_contents($filen)) {
								if (file_put_contents($filen, "teste\r\n")) {
									file_put_contents($filen, $conteudo);
								} else {
									//falhou Sem permissão
									$cdConf = $cRed;
									$wdConf .= ' Sem permiss&atilde;o escrita config/numloteenvio.xml !!';
								}
							}
						}
						//Teste permissão de escrita em config
						$filen = $caminhoFisico.'/modulos/nfe/config/config.php';
						if (file_exists($filen)) {
							//copia o conteudo
							if ($conteudo = file_get_contents($filen)) {
								if (file_put_contents($filen, "teste\r\n")) {
									file_put_contents($filen, $conteudo);
								} else {
									//falhou Sem permissão
									$cdConf = $cRed;
									$wdConf .= ' Sem permiss&atilde;o escrita config/config.php !!';
								}
							}
						}


						$dirNFE = $arquivosDir;
						//Teste do diretorio de arquivo dos xml NFe
						$cDir = $cRed;
						$wdDir = 'FALHA';
						if (!(is_dir($arquivosDir))){
							if (!(mkdir($arquivosDir, 0777))){
								$obsDir= ' Sem permiss&atilde;o para criar diretorio!!';
							}
						}
						if (is_dir($arquivosDir)) {
							if (mkdir($arquivosDir."teste", 0777)) {
								rmdir($arquivosDir."teste");
								$cDir = $cGreen;
								$wdDir= ' Permiss&atilde;o OK';
								$obsDir = $arquivosDir;
							} else {
								$obsDir= ' Sem permiss&atilde;o !!';
							}
						}

						//Teste do diretorio de arquivo dos xml CTe
						$dirCTE = $arquivosDirCTe;

						$ccteDir = $cRed;
						$wctedDir = 'FALHA';

						if (!(is_dir($arquivosDirCTe))){
							if (!(mkdir($arquivosDirCTe, 0777))){
								$obscteDir= ' Sem permiss&atilde;o para criar diretorio!!';
							}
						}
						if (is_dir($arquivosDirCTe)) {
							if (mkdir($arquivosDirCTe."/teste", 0777)) {
								rmdir($arquivosDirCTe."/teste");
								$ccteDir = $cGreen;
								$wctedDir= ' Permiss&atilde;o OK';
								$obscteDir = $arquivosDirCTe;
							} else {
								//sem permissao
								$obscteDir= ' Sem permiss&atilde;o !!';
							}
						}

		echo "			<div class='titulo-secundario' Style='float:left; width:100%; margin-top:5px;'>
							<p><b>Permiss&otilde;es de Escrita</b></p>
							<p>
								<table width='99%' style='float:left;margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
									<tr>
										<td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Pasta</b></td>
										<td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Diret&oacute;rio</b></td>
										<td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Status</b></td>
										<td class='tabela-fundo-escuro-titulo'> <div align='center'><b>Coment&aacute;rio</b></td>
									</tr>
									<tr>
										<td class='tabela-fundo-claro'>Certs</td>
										<td class='tabela-fundo-claro'>$dirCerts</td>
										<td class='tabela-fundo-claro' bgcolor='$cdCerts'><div align='center'>$wdCerts</div></td>
										<td class='tabela-fundo-claro'>O diret&oacute;rio deve ter permiss&atilde;o de escrita</td>
									</tr>
									<tr>
										<td class='tabela-fundo-claro'>NFe</td>
										<td class='tabela-fundo-claro'>$dirNFE</td>
										<td class='tabela-fundo-claro' bgcolor='$cDir'><div align='center'>$wdDir</div></td>
										<td class='tabela-fundo-claro'>$obsDir</td>
									</tr>
									<tr>
										<td class='tabela-fundo-claro'>CTe</td>
										<td class='tabela-fundo-claro'>$dirCTE</td>
										<td class='tabela-fundo-claro' bgcolor='echo $ccteDir'><div align='center'>$wctedDir</div></td>
										<td class='tabela-fundo-claro'>$obscteDir</td>
									</tr>
								</table>
							</p>
						</div>
					</div>
				</div>";

		}

//Função para padronização do numero de versões de 2.7.2 para 020702
function convVer($ver)
{
    $ver = preg_replace('/[^\d.]/', '', $ver);
    $aVer = explode('.', $ver);
    $nver = str_pad($aVer[0], 2, "0", STR_PAD_LEFT) .
    str_pad(isset($aVer[1]) ? $aVer[1] : '', 2, "0", STR_PAD_LEFT) .
    str_pad(isset($aVer[2]) ? $aVer[2] : '', 2, "0", STR_PAD_LEFT);
    return $nver;
}

//classe de verificação dos modulos instalados no PHP
class moduleCheck
{
    public $Modules;

    //function parseModules() {
    public function __construct()
    {
        ob_start();
        phpinfo(INFO_MODULES);
        $data0 = ob_get_contents();
        ob_end_clean();
        $data1 = strip_tags($data0, '<h2><th><td>');
        $data2 = preg_replace('/<th[^>]*>([^<]+)<\/th>/', "<info>\\1</info>", $data1);
        $data = preg_replace('/<td[^>]*>([^<]+)<\/td>/', "<info>\\1</info>", $data2);
        // Split the data into an array
        $vTmp = preg_split('/(<h2>[^<]+<\/h2>)/', $data, -1, PREG_SPLIT_DELIM_CAPTURE);
        $vModules = array();
        $count = count($vTmp);
        for ($i = 1; $i < $count; $i += 2) {
            if (preg_match('/<h2>([^<]+)<\/h2>/', $vTmp[$i], $vMat)) {
                $moduleName = trim($vMat[1]);
                $vTmp2 = explode("\n", $vTmp[$i+1]);
                foreach ($vTmp2 as $vOne) {
                    $vPat = '<info>([^<]+)<\/info>';
                    $vPat3 = "/$vPat\s*$vPat\s*$vPat/";
                    $vPat2 = "/$vPat\s*$vPat/";
                    if (preg_match($vPat3, $vOne, $vMat)) {
                        $vModules[$moduleName][trim($vMat[1])] = array(trim($vMat[2]),trim($vMat[3]));
                    } elseif (preg_match($vPat2, $vOne, $vMat)) {
                        $vModules[$moduleName][trim($vMat[1])] = trim($vMat[2]);
                    }
                }
            }
        }
        $this->Modules = $vModules;
    }

    /**
     * Quick check if module is loaded
     * Returns true if loaded, false if not
     *
     * @param type $moduleName
     * @return boolean
     */
    public function isLoaded($moduleName)
    {
        if ($this->Modules[$moduleName]) {
            return true;
        }
        return false;
    }

    /**
     * Get a module setting
     * Can be a single setting by specifying $setting value or all settings by not specifying $setting value
     * @param type $moduleName
     * @param type $setting
     * @return string
     */
    public function getModuleSetting($moduleName, $setting = '')
    {
        // check if module is loaded before continuing
        if ($this->isLoaded($moduleName)==false) {
            return 'Modulo não carregado';
        }
        if ($this->Modules[$moduleName][$setting]) {
            return $this->Modules[$moduleName][$setting];
        } elseif (empty($setting)) {
            return $this->Modules[$moduleName];
        }
        // If setting specified and no value found return error
        return 'Setting not found';
    }

    // List all php modules installed with no settings
    public function listModules()
    {
        foreach (array_keys($this->Modules) as $moduleName) {
            // $moduleName is the key of $this->Modules, which is also module name
            $onlyModules[] = $moduleName;
        }
        return $onlyModules;
    }
}


if ($empresaID>0){
	echo "	<div class='titulo-container conjunto3 esconde'>
				<div class='titulo'>
					<p>
						Centros de Distribui&ccedil;&atilde;o
						<input type='button' value='Incluir' class='cd-inc-alt' cd-id=''/>
					</p>
				</div>
				<input type='hidden' id='cd-id' name='cd-id' value=''>
				<div id='incluir-editar-cd'></div>

				<div class='conteudo-interno'>
					<div class='titulo-secundario'  id='listagem-cds'>";
	carregarCDs($empresaID);
	echo "			</div>
				</div>
			</div>";
}



if ($empresaID>0){
	echo "	<div class='titulo-container conjunto4 esconde'>
				<div class='titulo'>
					<p>
						Formas de Cobran&ccedil;a
						<input type='button' value='Incluir Forma de Cobran&ccedil;a' class='financeiro-incluir-cobranca' cd-id=''/>
					</p>
				</div>
				<!--
				<input type='hidden' id='cd-id' name='cd-id' value=''>
				<div id='incluir-editar-cd'></div>

				<div class='conteudo-interno'>
					<div class='titulo-secundario'  id='listagem-cds'>";
	carregarCDs($empresaID);
	echo "			</div>
				</div>
				-->
			</div>";
}
?>