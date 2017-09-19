<?php
	error_reporting(E_ERROR);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $configFinanceiro;
	$configFinanceiro = carregarConfiguracoesGeraisModulos('financeiro');


	function incluirClasseTools(){
		global $caminhoFisico;

		/********** VERSÃO 2.0 ***********/
		/*
		if (file_exists(dirname($caminhoFisico).'/webservice/includes/nfe/libs/ToolsNFePHP.class.php')) {
			require_once(dirname($caminhoFisico).'/webservice/includes/nfe/libs/ToolsNFePHP.class.php');
			return true;
		}
		else{
			return false;
		}
		*/

		$nfe310 = $caminhoFisico.'/includes/nfe3.10/libs/NFe/ToolsNFePHP.class.php';
		if (file_exists($nfe310)) {
			require_once($nfe310);
			return true;
		}
		else{
			return false;
		}

	}
	function retornaDiretorioBaseNFE(){
		global $caminhoFisico;
		//return dirname($caminhoFisico).'/webservice/includes/nfe/';
		//return $caminhoFisico.'/includes/nfe/';
		return $caminhoFisico.'/includes/nfe3.10/';
	}

	function incluirClasseDanfe(){
		global $caminhoFisico;
		/*
		if (file_exists(dirname($caminhoFisico).'/webservice/includes/nfe/libs/DanfeNFePHP.class.php')) {
			require_once(dirname($caminhoFisico).'/webservice/includes/nfe/libs/DanfeNFePHP.class.php');
			return true;
		}
		else{
			return false;
		}
		*/
		$nfe310 = $caminhoFisico.'/includes/nfe3.10/libs/NFe/DanfeNFePHP.class.php';
		if (file_exists($nfe310)) {
			require_once($nfe310);
			return true;
		}
		else{
			return false;
		}

	}

	function retornaArrayConfigNF($empresaID){
		$cUFlist = array("AC"=>"12", "AL"=>"27", "AM"=>"13", "AP"=>"16", "BA"=>"29", "CE"=>"23", "DF"=>"53", "ES"=>"32", "GO"=>"52",
						 "MA"=>"21", "MG"=>"31", "MS"=>"50", "MT"=>"51", "PA"=>"15", "PB"=>"25", "PE"=>"26", "PI"=>"22", "PR"=>"41",
					 "RJ"=>"33", "RN"=>"24", "RO"=>"11", "RR"=>"14", "RS"=>"43", "SC"=>"42", "SE"=>"28", "SP"=>"35", "TO"=>"17");

		if ($rs = mpress_fetch_array(mpress_query("select Config from nf_config where Empresa_ID = '$empresaID' and Situacao_ID = 1"))){
			$configNF = unserialize($rs[Config]);

			$rsEmpresa = mpress_query("select Cadastro_ID, upper(Nome) as Nome, upper(Nome_Fantasia) as Nome_Fantasia, Cpf_Cnpj, Inscricao_Estadual, Inscricao_Municipal from cadastros_dados where Cadastro_ID = '".$empresaID."'");
			if($rowEmpresa = mpress_fetch_array($rsEmpresa)){
				$configNF[empresa] = removeAcentos($rowEmpresa[Nome]);
				$configNF[fantasia] = removeAcentos($rowEmpresa[Nome_Fantasia]);
				$configNF[cnpj] = soNumeros($rowEmpresa[Cpf_Cnpj]);
				if ($rowEmpresa[Inscricao_Estadual]=="ISENTO") $configNF[inscricaoEstadual] = $rowEmpresa[Inscricao_Estadual]; else $configNF[inscricaoEstadual] = soNumeros($rowEmpresa[Inscricao_Estadual]);
				if ($rowEmpresa[Inscricao_Municipal]=="ISENTO") $configNF[inscricaoMunicipal] = $rowEmpresa[Inscricao_Municipal]; else $configNF[inscricaoMunicipal] = soNumeros($rowEmpresa[Inscricao_Municipal]);
			}

			$rsEmpresaEndereco = mpress_query("select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia  from cadastros_enderecos where Cadastro_Endereco_ID = '".$configNF[enderecoSelecionado]."'");
			if($rowEmpresaEndereco = mpress_fetch_array($rsEmpresaEndereco)){
				$configNF[CEP] = soNumeros($rowEmpresaEndereco[CEP]);
				$configNF[logradouro] = removeAcentos($rowEmpresaEndereco[Logradouro]);
				$configNF[numero] = removeAcentos($rowEmpresaEndereco[Numero]);
				$configNF[complemento] = removeAcentos($rowEmpresaEndereco[Complemento]);
				$configNF[bairro] = removeAcentos($rowEmpresaEndereco[Bairro]);
				$configNF[cidade] = removeAcentos($rowEmpresaEndereco[Cidade]);
				$configNF[referencia]= removeAcentos($rowEmpresaEndereco[Referencia]);
				$configNF[UF] = $rowEmpresaEndereco[UF];
				$configNF[cUF] = $cUFlist[$rowEmpresaEndereco[UF]];
			}

			$rsEmpresaTelefone = mpress_query("Select Cadastro_Telefone_ID, Telefone from cadastros_telefones where Cadastro_Telefone_ID = ".$configNF[telefoneSelecionado]);
			if($rowEmpresaTelefone = mpress_fetch_array($rsEmpresaTelefone))
				$configNF[telefone] = soNumeros($rowEmpresaTelefone[Telefone]);

		}
		return $configNF;
	}

	function carregarNotasFiscais($contaID){
		global $modulosAtivos;
		$retorno = " AQUI TELA COM NF`S GERADAS PARA AS CONTAS E TITULOS";
		echo $retorno;
	}

	function gerarXML($tipo){

	}

	function calculaDigitoVerificadorNFE($chave43) {
		$multiplicadores = array(2,3,4,5,6,7,8,9);
		$i = 42;
		while ($i >= 0) {
			for ($m=0; $m<count($multiplicadores) && $i>=0; $m++) {
				$soma_ponderada+= $chave43[$i] * $multiplicadores[$m];
				$i--;
			}
		}
		$resto = $soma_ponderada % 11;
		if ($resto == '0' || $resto == '1') {
			return 0;
		} else {
			return (11 - $resto);
	   }
	}

	function gerarNumeroNF($empresaID, $serie, $ambiente){
		$query = mpress_query("Select (coalesce(MAX(Numero_NF),0) + 1) as Numero_NF from nf_dados where Empresa_ID = '$empresaID' and Serie = $serie and Ambiente = '$ambiente'");
		if($rs = mpress_fetch_array($query)){
			$numeroNF = $rs[Numero_NF];
		}

		return $numeroNF;
	}

	function cancelarNFeModeloA1(){
		error_reporting(E_ERROR);
		ini_set('display_errors', 'On');

		global $caminhoFisico, $caminhoSistema, $dadosUserLogin;
		$contaID = $_POST['conta-id'];
		$empresaID = $_POST['cadastro-id-de'];
		$nfID = $_POST['nf-id'];

		if (incluirClasseTools()){
			$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
			$configNF = retornaArrayConfigNF($empresaID);
			$nfe = new ToolsNFePHP($configNF);
			$query = mpress_query("select Chave_Acesso, Recibo, Protocolo, Numero_NF, Serie, Ambiente from nf_dados nf where NF_ID = '".$nfID."' and Status_NF = '100'");
			if($rs = mpress_fetch_array($query)){
				$chNFe = $rs['Chave_Acesso'];
				$nProt = $rs['Protocolo'];
				$xJust = strtoupper(removeAcentos(utf8_decode($_POST["justificativa-cancelamento-nfe"])));
				$tpAmb = $configNF[ambiente];
				$modSOAP = '2';
				if ($resp = $nfe->cancelEvent($chNFe,$nProt,$xJust,$tpAmb,$modSOAP)){
					$sql = "insert into nf_canceladas (NF_ID, Justificativa, Retorno, Usuario_Cadastro_ID, Data_Cadastro, Erro)
												values('".$nfID."', '$xJust', '$resp', '".$dadosUserLogin[userID]."', '".$dataHoraAtual."', 0)";
					mpress_query($sql);
				}
				else{
					echo "<p><b>Erro ao cancelar a NF-e:</b>".utf8_decode($nfe->errMsg)."</p>";
					$sql = "insert into nf_canceladas (NF_ID, Justificativa, Retorno, Usuario_Cadastro_ID, Data_Cadastro, Erro)
												values('".$nfID."', '$xJust', '".str_replace("'","",utf8_decode($nfe->errMsg))."', '".$dadosUserLogin[userID]."', '".$dataHoraAtual."', 1)";
					mpress_query($sql);
				}
			}
		}
	}

	function gerarXMLModeloA1(){
		global $caminhoFisico, $caminhoSistema, $dadosUserLogin;
		require_once($caminhoFisico."/modulos/nfe/arrays-nfe.php");
		incluirClasseTools();

		// DADOS
		$contaID = $_POST['conta-id'];
		$naturezaOperacao = strtoupper(removeAcentos(utf8_decode($_POST['nfe-natureza-operacao'])));
		$serieNF = $_POST['nf-serie'];
		$empresaID = $_POST['cadastro-id-de'];
		$clienteID = $_POST['cadastro-id-para'];
		$dadosNF = $_POST['dados'];

		// Arquivo CONFIG da empresa com dados referente a ela

		$configNF = retornaArrayConfigNF($empresaID);
		$ambienteNF = $configNF[ambiente];
		$numeroNF = $_POST['nf-numero'];

		// TESTAR SE FOR RE-GERAR
		$regerada = false;
		$resultado = mpress_query("select count(*) as Cancelada from nf_dados n
									inner join nf_canceladas c on c.NF_ID = n.NF_ID
									where c.Erro = 0 and n.Conta_ID = '$contaID'
									and n.Ambiente = '$ambienteNF'
									and n.Numero_NF = '$numeroNF'");
		if ($rs = mpress_fetch_array($resultado)){
			if ($rs[Cancelada]>0){
				$regerada = true;
				$numeroNF = gerarNumeroNF($empresaID, $serieNF, $configNF[ambiente]);
			}
		}

		$tipoContaID = $_POST['tipo-conta-id'];
		if ($tipoContaID==45) $tipoConta = "1"; else $tipoConta = "0";
		$dataAtual = retornaDataHora('','Y-m-d');
		$horaAtual = retornaDataHora('','H:i:s');
		$dataChave = substr(retornaDataHora('','Ym'),-4);
		$modeloFiscal = "55";
		$tipoEmissao = "1";

		$codigoNumericoAleatorio = str_pad(mt_rand(1, 99999999), 8, "0", STR_PAD_LEFT);
		$chaveNFE = $configNF[cUF].$dataChave.$configNF[cnpj].$modeloFiscal.str_pad($serieNF, 3, "0", STR_PAD_LEFT).str_pad($numeroNF, 9, "0", STR_PAD_LEFT).$tipoEmissao.$codigoNumericoAleatorio;
		$digitoVerificadorChaveAcesso = calculaDigitoVerificadorNFE($chaveNFE);
		$chaveNFE = $chaveNFE.$digitoVerificadorChaveAcesso;

		$resultado = mpress_query("select NF_ID, Chave_Acesso, Recibo, Status_NF, NF_Array from nf_dados where Conta_ID = '$contaID' and Ambiente = '$ambienteNF'");
		$acao = "I";
		if ($rs = mpress_fetch_array($resultado)){
			$nfArray = unserialize($rs['NF_Array']);
			if ($regerada==false){
				$acao = "U";
				$nfID = $rs['NF_ID'];
				$statusNF = $rs['Status_NF'];
				if ($statusNF=="100"){
					$chaveNFE = $rs['Chave_Acesso'];
					$codigoNumericoAleatorio = substr($chaveNFE,35,8);
					$digitoVerificadorChaveAcesso = substr($chaveNFE,42,1);
				}
				else{
					mpress_query("Update nf_dados set Numero_NF = '$numeroNF' where Conta_ID = '$contaID' and Ambiente = '$ambienteNF'");
				}
			}
		}

		/* ATUALIZAR DADOS GERAIS DA NOTA - DADOS AVULSOS EXEMPLO: DADOS DE FRETE*/
		// verificar se precisa mesmo


		/*DESTINATARIO*/

		$sql = "Select upper(cd.Nome) as Nome, upper(cd.Nome_Fantasia) as Nome_Fantasia, cd.Cpf_Cnpj as Cpf_Cnpj, cd.Inscricao_Municipal, cd.Inscricao_Estadual, cd.Email, cd.Tipo_Pessoa,
					ce.CEP, upper(ce.Logradouro) as Logradouro, ce.Numero, upper(ce.Complemento) as Complemento, upper(ce.Bairro) as Bairro, upper(ce.Cidade) as Cidade, upper(ce.UF) as UF, ce.Referencia,
					ct.Telefone
					from cadastros_dados cd
					left join cadastros_enderecos ce ON ce.Cadastro_ID = cd.Cadastro_ID
					left join cadastros_telefones ct ON ct.Cadastro_ID = cd.Cadastro_ID
					where cd.Cadastro_ID = '$clienteID'
					order by ce.Tipo_Endereco_ID, ct.Tipo_Telefone_ID Limit 1";
		//echo $sql;

		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$nomeD = removeAcentos($rs[Nome]);
			$nomeFantasiaD = removeAcentos($rs[Nome_Fantasia]);
			$cpfCnpjD = soNumeros($rs[Cpf_Cnpj]);
			if ($rs[Inscricao_Municipal]=="ISENTO")
				$inscricaoMunicipalD = $rs[Inscricao_Municipal];
			else
				$inscricaoMunicipalD = soNumeros($rs[Inscricao_Municipal]);

			$inscricaoEstadualD = $rs[Inscricao_Estadual];
			/*
			if ($rs[Inscricao_Estadual]=="ISENTO")
				$inscricaoEstadualD = $rs[Inscricao_Estadual];
			else
				$inscricaoEstadualD = soNumeros($rs[Inscricao_Estadual]);
			*/
			$tipoPessoaD = $rs[Tipo_Pessoa];
			$CEPD = soNumeros($rs[CEP]);
			$logradouroD = removeAcentos($rs[Logradouro]);
			$numeroD = soNumeros($rs[Numero]);
			$complementoD = removeAcentos($rs[Complemento]);
			$bairroD = removeAcentos($rs[Bairro]);
			$cidadeD = removeAcentos($rs[Cidade]);
			$UFD = $rs[UF];
			$referenciaD = removeAcentos($rs[Referencia]);
			$codMunIBGED = localizaCodigoMunicipio($UFD, $cidadeD);
			$telefoneD = soNumeros($rs[Telefone]);
			$emailD = $rs[Email];
		}

		/**/


		$nomeArquivoXML = $chaveNFE."-nfe.xml";

		if ($i==1) $formaPagamento = 0; // pagamento a vista
		else $formaPagamento = 1; // pagamento a prazo


		//gerando XML #versao do encoding xml
		$dom = new DOMDocument("1.0", "UTF-8");
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;


		//$nfeProc = $dom->createElement("nfeProc");
		//$nfeProc->setAttribute("xmlns","http://www.portalfiscal.inf.br/nfe");
		//$nfeProc->setAttribute("versao","3.10");
		//$dom->appendChild($nfeProc);

		/*************************************************************************/
		$NFe = $dom->createElement("NFe");
		$NFe->setAttribute("xmlns","http://www.portalfiscal.inf.br/nfe");
		$dom->appendChild($NFe);


		/*************************************************************************/
		$infNFe = $dom->createElement("infNFe");
		$infNFe->setAttribute("Id","NFe".$chaveNFE);
		$infNFe->setAttribute("versao","3.10");
		$NFe->appendChild($infNFe);


		/****************************************************************************************************************************************************/
		/**************************************************				BLOCO IDE         			  *******************************************************/
		/****************************************************************************************************************************************************/
		$ide = $dom->createElement("ide");
		$infNFe->appendChild($ide);

		// CÓDIGO DA UF DO EMITENTE DO DOCUMENTO FISCAL
		$cUF = $dom->createElement("cUF", $configNF[cUF]);
		$ide->appendChild($cUF);

		// CÓDIGO NUMÉRICO QUE COMPOE A CHAVE DE ACESSO
		$cNF = $dom->createElement("cNF", $codigoNumericoAleatorio);
		$ide->appendChild($cNF);

		// CFOP - DESCRIÇÃO DA NATUREZA DA OPERAÇÃO
		$natOp = $dom->createElement("natOp", removeAcentos($naturezaOperacao));
		$ide->appendChild($natOp);

		// FORMA PAGAMENTO
		// 0 – pagamento à vista;
		// 1 – pagamento à prazo;
		// 2 - outros.
		$indPag = $dom->createElement("indPag", $formaPagamento);
		$ide->appendChild($indPag);

		// CÓDIGO DO MODELO DO DOCUMENTO FISCAL
		// Utilizar o código 55 para identificação da NF-e, emitida em substituição ao modelo 1 ou 1A.
		$mod = $dom->createElement("mod", $modeloFiscal);
		$ide->appendChild($mod);

		// SÉRIE DO DOCUMENTO FISCAL
		// Série do Documento Fiscal, preencher com zeros na hipótese de a NF-e não possuir série. (v2.0)
		// Série 890-899 de uso exclusivo para emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco (procEmi=2). (v2.0)
		// Serie 900-999 – uso exclusivo de NF-e emitidas no SCAN. (v2.0)
		$serie = $dom->createElement("serie", $serieNF);
		$ide->appendChild($serie);

		// NÚMERO DO DOCUMENTO FISCAL
		// Série do Documento Fiscal, preencher com zeros na hipótese de a NF-e não possuir série. (v2.0)
		// Série 890-899 de uso exclusivo para emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco (procEmi=2). (v2.0)
		// Serie 900-999 – uso exclusivo de NF-e emitidas no SCAN. (v2.0)
		$nNF = $dom->createElement("nNF", $numeroNF);
		$ide->appendChild($nNF);

		// DATA DE EMISSÃO DO DOCUMENTO
		$dhEmi = $dom->createElement("dhEmi",$dataAtual.'T'.$horaAtual.'-02:00');
		$ide->appendChild($dhEmi);

		// DATA DE SAÍDA OU DA ENTRADA DOS PRODUTOS
		$dhSaiEnt = $dom->createElement("dhSaiEnt",$dataAtual.'T'.$horaAtual.'-02:00');
		$ide->appendChild($dhSaiEnt);

		// HORA DE SAÍDA OU DA ENTRADA DOS PRODUTOS
		//$hSaiEnt = $dom->createElement("hSaiEnt",$horaAtual);
		//$ide->appendChild($hSaiEnt);

		// TIPO DE OPERAÇÃO
		// 0-entrada / 1-saída
		$tpNF = $dom->createElement("tpNF", $tipoConta);
		$ide->appendChild($tpNF);

		// IDENTIFICADOR DE LOCAL DE DESTINO DA OPERAÇÃO
		//1=Operação interna;
		//2=Operação interestadual;
		//3=Operação com exterior.
		$idDestino = "1";

		// REVISAR DEIXAR PARA ESCOLHA DE QUEM ESTA EMITINDO
		/*
		if ($UFD=='EX')
			$idDestino = "3";
		else{
			if ($configNF[UF]!=$UFD)
				$idDestino = "2";
		}
		*/
		$idDest = $dom->createElement("idDest", $idDestino);
		$ide->appendChild($idDest);


		// CÓDIGO DO MUNICÍPIO DE OCORRÊNCIA DO FATO GERADOR - IBGE
		$codMunIBGE = localizaCodigoMunicipio($configNF[UF], $configNF[cidade]);
		$cMunFG = $dom->createElement("cMunFG",$codMunIBGE);
		$ide->appendChild($cMunFG);


		// FORMATO DE IMPRESSÃO DO DANFE
		// 1-Retrato/ 2-Paisagem
		if ($configNF[danfeFormato]=='P') $impressao = "1"; else $impressao = "2";
		$tpImp = $dom->createElement("tpImp", $impressao);
		$ide->appendChild($tpImp);

		// TIPO DE EMISSÃO DA NF-E
		// 1 – Normal – emissão normal;
		// 2 – Contingência FS – emissão em contingência com impressão do DANFE em Formulário de Segurança;
		// 3 – Contingência SCAN – emissão em contingência no Sistema de Contingência do Ambiente Nacional – SCAN;
		// 4 – Contingência DPEC - emissão em contingência com envio da Declaração Prévia de Emissão em Contingência – DPEC;
		// 5 – Contingência FS-DA - emissão em contingência com impressão do DANFE em Formulário de Segurança para Impressão de Documento Auxiliar de Documento Fiscal Eletrônico (FS-DA);
		// 6 – Contingência SVC-AN, emissão em contingência na SEFAZ Virtual do Ambiente Nacional;
		// 7 – Contingência SVC-RS, emissão em contingência na SEFAZ Virtual do RS.

		$tpEmis = $dom->createElement("tpEmis", $tipoEmissao);
		$ide->appendChild($tpEmis);

		// DÍGITO VERIFICADOR DA CHAVE DE ACESSO DA NF-E
		// Informar o DV da Chave de Acesso da NF-e, o DV será calculado com a aplicação do algoritmo módulo 11 (base 2,9) da Chave de Acesso. (vide item 5 do Manual de Orientação)
		$cDV = $dom->createElement("cDV", $digitoVerificadorChaveAcesso);
		$ide->appendChild($cDV);

		// AMBIENTE
		// 1-Produção/ 2-Homologação
		$tpAmb = $dom->createElement("tpAmb", $configNF[ambiente]);
		$ide->appendChild($tpAmb);

		// FINALIDADE DE EMISSÃO DA NF-E
		// 1- NF-e normal/ 2-NF-e complementar / 3 – NF-e de ajuste
		$finNFe = $dom->createElement("finNFe", "1");
		$ide->appendChild($finNFe);


		// INDICA OPERAÇÃO COM CONSUMIDOR FINAL
		// 0 = Normal;
		// 1 = Consumidor final;
		$indFinal = $dom->createElement("indFinal", $dadosNF['indFinal']);
		$ide->appendChild($indFinal);

		// INDICADOR DE PRESENÇA DO COMPRADOR NO ESTABELECIMENTO COMERCIAL NO MOMENTO DA OPERAÇÃO
		// 1 = Operação presencial;
		// 2 = Operação não presencial, pela Internet;
		// 3 = Operação não presencial, Teleatendimento;
		// 4 = NFC-e em operação com entrega em domicílio;
		$indPres = $dom->createElement("indPres", $dadosNF['indPres']);
		$ide->appendChild($indPres);


		// PROCESSO DE EMISSÃO DA NF-E
		// Identificador do processo de emissão da NF-e:
		//	0 - emissão de NF-e com aplicativo do contribuinte;
		//	1 - emissão de NF-e avulsa pelo Fisco;
		//	2 - emissão de NF-e avulsa, pelo contribuinte com seu certificado digital, através do site do Fisco;
		//	3 - emissão NF-e pelo contribuinte com aplicativo fornecido pelo Fisco.
		$procEmi = $dom->createElement("procEmi", "0");
		$ide->appendChild($procEmi);

		// VERSÃO DO PROCESSO DE EMISSÃO DA NF-E
		// Identificador da versão do processo de emissão (informar a versão do aplicativo emissor de NF-e).
		//$verProc = $dom->createElement("verProc", "1.0.0.0");
		$verProc = $dom->createElement("verProc", "1.5");
		$ide->appendChild($verProc);



		/****************************************************************************************************************************************************/
		/**************************************************				BLOCO EMITENTE     			  *******************************************************/
		/****************************************************************************************************************************************************/

		$emit = $dom->createElement("emit");
		$infNFe->appendChild($emit);


		// CNPJ EMITENTE
		$CNPJ = $dom->createElement("CNPJ", $configNF[cnpj]);
		$emit->appendChild($CNPJ);

		// NOME EMITENTE
		$xNome = $dom->createElement("xNome", removeAcentos($configNF[empresa]));
		$emit->appendChild($xNome);


		// NOME FANTASIA EMITENTE
		if ($configNF[fantasia]!=""){
			$xFant = $dom->createElement("xFant", removeAcentos($configNF[fantasia]));
			$emit->appendChild($xFant);
		}

		/**************************************************				INICIO BLOCO ENDERECO EMITENTE     *******************************************************/
		$enderEmit = $dom->createElement("enderEmit");
		$emit->appendChild($enderEmit);

		// LOGRADOURO EMITENTE
		$xLgr = $dom->createElement("xLgr", removeAcentos($configNF[logradouro]));
		$enderEmit->appendChild($xLgr);

		// NUMERO EMITENTE
		$nro = $dom->createElement("nro", $configNF[numero]);
		$enderEmit->appendChild($nro);

		// BAIRRO EMITENTE
		$bairro = $dom->createElement("xBairro", removeAcentos($configNF[bairro]));
		$enderEmit->appendChild($bairro);

		// CÓDIGO CIDADE EMITENTE
		$codMunIBGE = localizaCodigoMunicipio($configNF[UF], $configNF[cidade]);
		$cMun = $dom->createElement("cMun", $codMunIBGE);
		$enderEmit->appendChild($cMun);

		// CIDADE EMITENTE
		$xMun = $dom->createElement("xMun", removeAcentos($configNF[cidade]));
		$enderEmit->appendChild($xMun);

		// UF EMITENTE
		$UF = $dom->createElement("UF", $configNF[UF]);
		$enderEmit->appendChild($UF);

		// CEP EMITENTE
		$CEP = $dom->createElement("CEP", $configNF[CEP]);
		$enderEmit->appendChild($CEP);

		// CODIGO PAIS
		$cPais = $dom->createElement("cPais", "1058");
		$enderEmit->appendChild($cPais);

		// NOME PAIS
		$xPais = $dom->createElement("xPais", removeAcentos("BRASIL"));
		$enderEmit->appendChild($xPais);

		// TELEFONE EMITENTE
		$fone = $dom->createElement("fone", $configNF[telefone]);
		$enderEmit->appendChild($fone);
		/**************************************************				FIM BLOCO ENDERECO EMITENTE     *******************************************************/



		// INSCRIÇÃO ESTADUAL
		// Campo de informação obrigatória nos casos de emissão própria (procEmi = 0, 2 ou 3).
		// A IE deve ser informada apenas com algarismos para destinatários contribuintes do ICMS, sem caracteres de formatação (ponto, barra, hífen, etc.);
		// O literal “ISENTO” deve ser informado apenas para contribuintes do ICMS que são isentos de inscrição no cadastro de contribuintes do ICMS e estejam emitindo NF-e avulsa;
		$IE = $dom->createElement("IE", $configNF[inscricaoEstadual]);
		$emit->appendChild($IE);

		if ($configNF[inscricaoMunicipal]!=""){
			// INSCRIÇÃO MUNICIPAL
			//Este campo deve ser informado, quando ocorrer a emissão de NF-e conjugada, com prestação de serviços sujeitos ao ' e fornecimento de peças sujeitos ao ICMS.
			$IM = $dom->createElement("IM", $configNF[inscricaoMunicipal]);
			$emit->appendChild($IM);

			// CNAE
			//Este campo deve ser informado quando o campo IM (C19) for informado.
			$CNAE = $dom->createElement("CNAE", $configNF[CNAE]);
			$emit->appendChild($CNAE);
		}


		// CRT - CÓDIGO DE REGIME TRIBUTÁRIO
		// Este campo será obrigatoriamente preenchido com:
		// 1 – Simples Nacional;
		// 2 – Simples Nacional – excesso de sublimite de receita bruta;
		// 3 – Regime Normal. (v2.0).
		$CRT = $dom->createElement("CRT", $configNF[CRT]);
		$emit->appendChild($CRT);



		/****************************************************************************************************************************************************/
		/**************************************************				BLOCO DESTINATARIO 			  *******************************************************/
		/****************************************************************************************************************************************************/

		$dest = $dom->createElement("dest");
		$infNFe->appendChild($dest);

		// CPF / CNPJ DESTINATARIO
		if ($tipoPessoaD==24) $tag = "CPF"; else $tag = "CNPJ";
		$tag = $dom->createElement($tag, $cpfCnpjD);
		$dest->appendChild($tag);


		if ($configNF[ambiente]=="2"){
			$nomeD = "NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL";
		}

		// NOME DESTINATARIO
		$xNome = $dom->createElement("xNome", removeAcentos($nomeD));
		$dest->appendChild($xNome);

		/**************************************************				BLOCO ENDERECO DESTINATARIO     *******************************************************/
		// ENDERECO DESTINATARIO
		$enderDest = $dom->createElement("enderDest");
		$dest->appendChild($enderDest);

		// LOGRADOURO DESTINATARIO
		$xLgr = $dom->createElement("xLgr", removeAcentos($logradouroD));
		$enderDest->appendChild($xLgr);

		// NUMERO DESTINATARIO
		$numeroDest = $dom->createElement("nro", str_pad($numeroD, (strlen($numeroD)+1), "0", STR_PAD_LEFT));
		$enderDest->appendChild($numeroDest);

		// BAIRRO DESTINATARIO
		$nro = $dom->createElement("xBairro", removeAcentos($bairroD));
		$enderDest->appendChild($nro);

		// CÓDIGO CIDADE DESTINATARIO
		$cMun = $dom->createElement("cMun", $codMunIBGED);
		$enderDest->appendChild($cMun);

		// CIDADE DESTINATARIO
		$xMun = $dom->createElement("xMun", removeAcentos($cidadeD));
		$enderDest->appendChild($xMun);

		// UF DESTINATARIO
		$UFD = $dom->createElement("UF", $UFD);
		$enderDest->appendChild($UFD);

		// CEP DESTINATARIO
		$CEPD = $dom->createElement("CEP", $CEPD);
		$enderDest->appendChild($CEPD);

		// CODIGO DESTINATARIO
		$cPaisD = $dom->createElement("cPais", "1058");
		$enderDest->appendChild($cPaisD);

		// NOME DESTINATARIO
		$xPais = $dom->createElement("xPais", removeAcentos("BRASIL"));
		$enderDest->appendChild($xPais);

		// FONE DESTINATARIO
		if ($telefoneD!=""){
			$fone = $dom->createElement("fone", "$telefoneD");
			$enderDest->appendChild($fone);
		}


		// INDICADOR DA IE DO DESTINATÁRIO



		// INDICADOR DA IE DO DESTINATÁRIO
		// 1=Contribuinte ICMS (informar a IE do destinatário);
		// 2=Contribuinte isento de Inscrição no cadastro de Contribuintes do ICMS;
		// 9=Não Contribuinte, que pode ou não possuir Inscrição Estadual no Cadastro de Contribuintes do ICMS;
		//	Nota 1: No caso de NFC-e informar indIEDest=9 e não informar a tag IE do destinatário;
		//	Nota 2: No caso de operação com o Exterior informar indIEDest=9 e não informar a tag IE do destinatário;
		//	Nota 3: No caso de Contribuinte Isento de Inscrição (indIEDest=2), não informar a tag IE do destinatário.

		$indIEDestinatario = "1";
		if ($inscricaoEstadualD=="ISENTO")
			$indIEDestinatario = "2";
		else{
			if (soNumeros($inscricaoEstadualD)=="")
				$indIEDestinatario = "9";
		}
		$indIEDestino = $dom->createElement("indIEDest", $indIEDestinatario);
		$dest->appendChild($indIEDestino);

		if ($indIEDestinatario==1){
			// INSCRIÇÃO ESTADUAL DESTINATARIO
			// Campo opcional. Informar somente os algarismos, sem os caracteres de formatação (ponto, barra, hífen, etc.)
			$IE = $dom->createElement("IE", soNumeros($inscricaoEstadualD));
			$dest->appendChild($IE);
		}


		// INSCRIÇÃO SUFRAMA DESTINATARIO
		// Obrigatório, nas operações que se beneficiam de incentivos fiscais existentes nas áreas sob controle da SUFRAMA.
		// A omissão da Inscrição SUFRAMA impede o processamento da operação pelo Sistema de Mercadoria Nacional da SUFRAMA e a liberação da Declaração de Ingresso, prejudicando a comprovação do ingresso/internamento da mercadoria nas áreas sob controle da SUFRAMA. (v2.0)
		if ($ISUF!=""){
			$ISUF = $dom->createElement("ISUF", $ISUF);
			$dest->appendChild($ISUF);
		}

		if ($emailD!=""){
			// EMAIL DESTINATARIO
			// Informar o e-mail do destinatário. O campo pode ser utilizado para informar o e-mail de recepção da NF-e indicada pelo destinatário (v2.0)
			$email = $dom->createElement("email", removeAcentos($emailD));
			$dest->appendChild($email);
		}


		/****************************************************************************************************************************************************/
		/**************************************************				BLOCO PRODUTOS	 			  *******************************************************/
		/****************************************************************************************************************************************************/

		$virgula = "";
		for($i = 0; $i < count($_POST['financeiro-produto-id']); $i++){
			$financeiroProdutoID = $_POST['financeiro-produto-id'][$i];

			$produtoID = $_POST['produto-id'][$i];
			$produtoVaricaoID = $_POST['produto-variacao-id'][$i];
			$ncmProduto = $_POST['ncm-produto'][$i];

			/* trecho referente a produtos */

			$infoNFE['cst-icms-produto'] = $_POST['cst-icms-produto'][$i];
			$infoNFE['cst-ipi-produto'] = $_POST['cst-ipi-produto'][$i];
			$infoNFE['cst-pis-produto'] = $_POST['cst-pis-produto'][$i];
			$infoNFE['cst-cofins-produto'] = $_POST['cst-cofins-produto'][$i];
			$infoNFE['percentual-icms-produto'] = formataValorBD($_POST['percentual-icms-produto'][$i]);
			$infoNFE['percentual-ipi-produto'] = formataValorBD($_POST['percentual-ipi-produto'][$i]);
			$infoNFE['percentual-pis-produto'] = formataValorBD($_POST['percentual-pis-produto'][$i]);
			$infoNFE['percentual-cofins-produto'] = formataValorBD($_POST['percentual-cofins-produto'][$i]);
			$infoNFE['cfop-produto'] = $_POST['cfop-produto'][$i];
			$infoNFE['base-calculo-icms-produto'] = formataValorBD($_POST['base-calculo-icms-produto'][$i]);
			$infoNFE['base-calculo-ipi-produto'] = formataValorBD($_POST['base-calculo-ipi-produto'][$i]);
			$infoNFE['base-calculo-pis-produto'] = formataValorBD($_POST['base-calculo-pis-produto'][$i]);
			$infoNFE['base-calculo-cofins-produto'] = formataValorBD($_POST['base-calculo-cofins-produto'][$i]);
			$infoNFE['valor-icms-produto'] = formataValorBD($_POST['valor-icms-produto'][$i]);
			$infoNFE['valor-ipi-produto'] = formataValorBD($_POST['valor-ipi-produto'][$i]);
			$infoNFE['valor-pis-produto'] = formataValorBD($_POST['valor-pis-produto'][$i]);
			$infoNFE['valor-cofins-produto'] = formataValorBD($_POST['valor-cofins-produto'][$i]);

			/* trecho referente a servicos */

			$infoNFE['lista-servico'] = $_POST['lista-servico'][$i];
			$infoNFE['cst-icsqn-servico'] = $_POST['cst-icsqn-servico'][$i];
			$infoNFE['percentual-issqn-servico'] = formataValorBD($_POST['percentual-issqn-servico'][$i]);
			$infoNFE['base-calculo-issqn-servico'] = formataValorBD($_POST['base-calculo-issqn-servico'][$i]);
			$infoNFE['valor-issqn-servico'] = formataValorBD($_POST['valor-issqn-servico'][$i]);
			$infoNFE['codigo-municipio-issqn'] = $_POST['codigo-municipio-issqn'][$i];

			$query = mpress_query("update financeiro_produtos set Info_NFE = '".serialize($infoNFE)."' where Financeiro_Produto_ID = '$financeiroProdutoID'");
			$query = mpress_query("update produtos_dados set NCM = '$ncmProduto' where Produto_ID = '$produtoID'");

		}

		//echo "<pre>";
		//print_r($infoNFE);
		//echo "</pre>";
		//exit();

		$sql = "select pv.Produto_Variacao_ID as Produto_Variacao_ID, concat (pd.Nome, pv.Descricao) as Produto, coalesce(pv.Unidade,'UN') as Unidade, pv.CEAN as CEAN,
					(case when pd.Tipo_Produto in (30,100,175) THEN pd.NCM else '00' end) as NCM, pd.Origem as Origem,
					fp.Quantidade as Quantidade, fp.Valor_Unitario as Valor, pd.Tipo_Produto as Tipo, fp.Info_NFE as Info_NFE, pd.Industrializado as Industrializado
				 from financeiro_produtos fp
					inner join produtos_variacoes pv on pv.Produto_Variacao_ID = fp.Produto_Variacao_ID
					inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				 where Conta_ID = '$contaID'
				 and fp.Situacao_ID = 1
				 order by fp.Financeiro_Produto_ID";
		//echo $sql;
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$ip++;

			/***** DETALHES DOS PRODUTOS *****/
			$arrInfoNFE = unserialize($rs[Info_NFE]);

			/*
			$arrInfoNFE['cst-icms-produto'];
			$arrInfoNFE['cst-ipi-produto'];
			$arrInfoNFE['cst-pis-produto'];
			$arrInfoNFE['cst-cofins-produto'];
			$arrInfoNFE['percentual-icms-produto'];
			$arrInfoNFE['percentual-ipi-produto'];
			$arrInfoNFE['percentual-pis-produto'];
			$arrInfoNFE['percentual-cofins-produto'];
			$arrInfoNFE['cfop-produto'];

			$arrInfoNFE['base-calculo-icms-produto'];
			$arrInfoNFE['base-calculo-ipi-produto'];
			$arrInfoNFE['base-calculo-pis-produto'];
			$arrInfoNFE['base-calculo-cofins-produto'];

			$arrInfoNFE['valor-icms-produto'];
			$arrInfoNFE['valor-ipi-produto'];
			$arrInfoNFE['valor-pis-produto'];
			$arrInfoNFE['valor-cofins-produto'];

			$arrInfoNFE['lista-servico'];
			$arrInfoNFE['cst-icsqn-servico'];
			$arrInfoNFE['percentual-issqn-servico'];
			$arrInfoNFE['base-calculo-issqn-servico'];
			$arrInfoNFE['valor-issqn-servico'];
			$arrInfoNFE['codigo-municipio-issqn'];
			*/

			// DETALHES DO PRODUTO
			$det = $dom->createElement("det");
			$det->setAttribute("nItem",$ip);
			$infNFe->appendChild($det);

			/**************************************************				BLOCO DADOS DO PRODUTO     *******************************************************/

			$prod = $dom->createElement("prod");
			$det->appendChild($prod);


			//CÓDIGO DO PRODUTO OU SERVIÇO
			//Preencher com CFOP, caso se trate de itens não relacionados com mercadorias/produtos e que o contribuinte não possua codificação própria. Formato ”CFOP9999”
			$cProd = $dom->createElement("cProd",str_pad($rs[Produto_Variacao_ID], 6, "0", STR_PAD_LEFT));
			$prod->appendChild($cProd);


			// CEAN GTIN (GLOBAL TRADE ITEM NUMBER) DO PRODUTO, ANTIGO CÓDIGO EAN OU CÓDIGO DE BARRAS
			// http://www.gs1br.org/
			// Preencher com o código GTIN-8, GTIN-12, GTIN-13 ou GTIN-14 (antigos códigos EAN, UPC e DUN-14), não informar o conteúdo da TAG em caso de o produto não possuir este código.
			if ($rs[CEAN]==0)
				$rs[CEAN] = '';
			$cEAN = $dom->createElement("cEAN",$rs[CEAN]);
			$prod->appendChild($cEAN);


			//DESCRIÇÃO DO PRODUTO OU SERVIÇO
			$xProd = $dom->createElement("xProd", trim(substr(removeAcentos($rs['Produto']),0,120)));
			$prod->appendChild($xProd);


			// CÓDIGO NCM COM 8 DÍGITOS OU 2 DÍGITOS (GÊNERO)
			// Código NCM (8 posições), informar o gênero (posição do
			// capítulo do NCM) quando a operação não for de comércio
			// exterior (importação/exportação) ou o produto não seja tributado pelo IPI.
			// Em caso de item de serviço ou item que não tenham produto (Ex. transferência de crédito, crédito do ativo imobilizado, etc.), informar o código 00 (zeros) (v2.0)
			$NCM = $dom->createElement("NCM",$rs[NCM]);
			$prod->appendChild($NCM);

			// CFOP - CÓDIGO FISCAL DE OPERAÇÕES E PRESTAÇÕES - Utilizar Tabela de CFOP.
			$CFOP = $dom->createElement("CFOP", $arrInfoNFE['cfop-produto']);
			$prod->appendChild($CFOP);

			// UNIDADE COMERCIAL
			$unidade = $rs[Unidade];
			if ($rs[Unidade]=='UN')
				$unidade = 'UNID';

			$uCom = $dom->createElement("uCom", strtoupper(removeAcentos($unidade)));
			$prod->appendChild($uCom);

			// QUANTIDADE COMERCIAL
			// Informar a quantidade de comercialização do produto (v2.0).
			$qCom = $dom->createElement("qCom", number_format($rs[Quantidade],4,".",""));
			$prod->appendChild($qCom);

			//VALOR UNITÁRIO DE COMERCIALIZAÇÃO
			//Informar o valor unitário de comercialização do produto, campo meramente informativo, o contribuinte pode utilizar a precisão desejada (0-10 decimais). Para efeitos de cálculo, o valor unitário será obtido pela divisão do valor do produto pela quantidade comercial. (v2.0)
			$vUnCom = $dom->createElement("vUnCom", number_format($rs[Valor],10,".",""));
			$prod->appendChild($vUnCom);

			// VALOR TOTAL BRUTO DOS PRODUTOS OU SERVIÇOS
			$vProd = $dom->createElement("vProd",number_format(($rs[Valor]*$rs[Quantidade]),2,".",""));
			$prod->appendChild($vProd);

			// GTIN (Global Trade Item Number) da unidade tributável, antigo código EAN ou código de barras
			// Preencher com o código GTIN-8, GTIN-12, GTIN-13 ou GTIN-14 (antigos códigos EAN, UPC e DUN-14) da unidade tributável do produto, não informar o conteúdo da TAG em caso de o produto não possuir este código.
			$vProd = $dom->createElement("cEANTrib","");
			$prod->appendChild($vProd);

			//UNIDADE TRIBUTÁVEL
			$uTrib = $dom->createElement("uTrib",$unidade);
			$prod->appendChild($uTrib);

			// QUANTIDADE TRIBUTÁVEL
			// Informar a quantidade de tributação do produto (v2.0).
			$qTrib = $dom->createElement("qTrib", number_format($rs[Quantidade],4,".",""));
			$prod->appendChild($qTrib);

			// VALOR UNITÁRIO DE TRIBUTAÇÃO
			// Informar o valor unitário de tributação do produto, campo meramente informativo, o contribuinte pode utilizar a precisão desejada (0-10 decimais). Para efeitos de cálculo, o valor unitário será obtido pela divisão do valor do produto pela quantidade tributável
			$vUnTrib = $dom->createElement("vUnTrib", number_format($rs[Valor],10,".",""));
			$prod->appendChild($vUnTrib);

			// VALOR DO DESCONTO
			if ($valorDesconto>0){
				$vDesc = $dom->createElement("vDesc", number_format($valorDesconto,2,".",""));
				$prod->appendChild($vDesc);
			}

			// INDICA SE VALOR DO ITEM (VPROD) ENTRA NO VALOR TOTAL DA NF-E (VPROD)
			// Este campo deverá ser preenchido com:
			// 0 – o valor do item (vProd) não compõe o valor total da NF-e (vProd)
			// 1 – o valor do item (vProd) compõe
			// A PRINCIPIO SEMPRE COMPÕEM VER EM QUE SITUAÇÃO NÃO COMPÕEM
			$indTot = $dom->createElement("indTot","1");
			$prod->appendChild($indTot);


			/**************************************************				BLOCO IMPOSTOS DO PRODUTO     *******************************************************/
			$imposto = $dom->createElement("imposto");
			$det->appendChild($imposto);

			// PRODUTO
			if (($rs[Tipo]=="30") || ($rs[Tipo]=="100") || ($rs[Tipo]=="175")){
				$flagICMS = 1;
				$vProdTot += ($rs[Valor]*$rs[Quantidade]);

				$ICMS = $dom->createElement("ICMS");
				$imposto->appendChild($ICMS);

				/***************************************************************************************************/
				/****************************************** CST REGIME NORMAL **************************************/
				/***************************************************************************************************/

				/****************************************** CST-CSOSN ICMS = 00 ******************************************/
				if ($arrInfoNFE['cst-icms-produto']==00){
					// ICMS PRODUTO
					$ICMSFilho = $dom->createElement("ICMS00");
					$ICMS->appendChild($ICMSFilho);

					// ORIGEM DA MERCADORIA:
					// 0 – Nacional; 1 – Estrangeira – Importação direta; 2 – Estrangeira – Adquirida no mercado interno.
					$orig = $dom->createElement("orig",$rs['Origem']);
					$ICMSFilho->appendChild($orig);

					// CST
					$CST = $dom->createElement("CST", $arrInfoNFE['cst-icms-produto']);
					$ICMSFilho->appendChild($CST);

					// MODALIDADE DE DETERMINAÇÃO DA BC DO ICMS  // 0 - Margem Valor Agregado (%); // 1 - Pauta (Valor); // 2 - Preço Tabelado Máx. (valor); // 3 - valor da operação.
					$modBC = $dom->createElement("modBC", "0");
					$ICMSFilho->appendChild($modBC);

					//VALOR DA BC DO ICMS
					$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-icms-produto']);
					$ICMSFilho->appendChild($vBC);
					$vBCTotICMS += $arrInfoNFE['base-calculo-icms-produto'];

					//ALÍQUOTA DO IMPOSTO
					$pICMS = $dom->createElement("pICMS", $arrInfoNFE['percentual-icms-produto']);
					$ICMSFilho->appendChild($pICMS);

					//VALOR DO ICMS
					$vICMS = $dom->createElement("vICMS", $arrInfoNFE['valor-icms-produto']);
					$ICMSFilho->appendChild($vICMS);
					$vICMSTotICMS += $arrInfoNFE['valor-icms-produto'];

				}


				/***************************************************************************************************/
				/****************************************** CSOSN SIMPLES NACIONAL *********************************/
				/***************************************************************************************************/
				if (($arrInfoNFE['cst-icms-produto']==101)){
					// ICMS PRODUTO
					$ICMSFilho = $dom->createElement("ICMSSN101");
					$ICMS->appendChild($ICMSFilho);

					// ORIGEM DA MERCADORIA:
					// 0 - Nacional, exceto as indicadas nos códigos 3, 4, 5 e 8;
					// 1 - Estrangeira - Importação direta, exceto a indicada no código 6;
					// 2 - Estrangeira - Adquirida no mercado interno, exceto a indicada no código 7;
					// 3 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 40% e inferior ou igual a 70%;
					// 4 - Nacional, cuja produção tenha sido feita em conformidade com os processos produtivos básicos de que tratam as legislações citadas nos Ajustes;
					// 5 - Nacional, mercadoria ou bem com Conteúdo de Importação inferior ou igual a 40%;
					// 6 - Estrangeira - Importação direta, sem similar nacional, constante em lista da CAMEX e gás natural;
					// 7 - Estrangeira - Adquirida no mercado interno, sem similar nacional, constante lista CAMEX e gás natural.
					// 8 - Nacional, mercadoria ou bem com Conteúdo de Importação superior a 70%;

					$orig = $dom->createElement("orig",$rs['Origem']);
					$ICMSFilho->appendChild($orig);

					// CSOSN
					$CSOSN = $dom->createElement("CSOSN", $arrInfoNFE['cst-icms-produto']);
					$ICMSFilho->appendChild($CSOSN);

					// ALÍQUOTA APLICÁVEL DE CÁLCULO DO CRÉDITO(SIMPLES NACIONAL).
					$pCredSN = $dom->createElement("pCredSN", 0);
					$ICMSFilho->appendChild($pCredSN);

					// VALOR CRÉDITO DO ICMS QUE PODE SER APROVEITADO NOS TERMOS DO ART. 23 DA LC 123 (SIMPLES NACIONAL)
					$vCredICMSSN = $dom->createElement("vCredICMSSN", 0);
					$ICMSFilho->appendChild($vCredICMSSN);

				}



				if (($arrInfoNFE['cst-icms-produto']==102) || ($arrInfoNFE['cst-icms-produto']==103) || ($arrInfoNFE['cst-icms-produto']==300) || ($arrInfoNFE['cst-icms-produto']==400)){
					// ICMS PRODUTO
					$ICMSFilho = $dom->createElement("ICMSSN102");
					$ICMS->appendChild($ICMSFilho);

					// ORIGEM DA MERCADORIA:
					// 0 – Nacional; 1 – Estrangeira – Importação direta; 2 – Estrangeira – Adquirida no mercado interno.
					$orig = $dom->createElement("orig",$rs['Origem']);
					$ICMSFilho->appendChild($orig);

					// CSOSN
					$CSOSN = $dom->createElement("CSOSN", $arrInfoNFE['cst-icms-produto']);
					$ICMSFilho->appendChild($CSOSN);
				}

				if ($arrInfoNFE['cst-icms-produto']==500){
					// ICMS PRODUTO
					$ICMSFilho = $dom->createElement("ICMSSN500");
					$ICMS->appendChild($ICMSFilho);

					// ORIGEM DA MERCADORIA:
					// 0 – Nacional; 1 – Estrangeira – Importação direta; 2 – Estrangeira – Adquirida no mercado interno.
					$orig = $dom->createElement("orig",$rs['Origem']);
					$ICMSFilho->appendChild($orig);

					// CSOSN
					$CSOSN = $dom->createElement("CSOSN", $arrInfoNFE['cst-icms-produto']);
					$ICMSFilho->appendChild($CSOSN);
					
					// CAMPOS ABAIXOS OMITIDOS
					//245.50 N25.1 -x- Sequência XML G N10g 0-1 Grupo opcional.
					//245.50 N26 vBCSTRet Valor da BC do ICMS ST retido E N12.1 N 1-1 13v2 Valor da BC do ICMS ST cobrado anteriormente por ST (v2.0). O valor pode ser omitido quando a legislação não exigir a suainformação. (NT 2011/004)
					//245.51 N27 vICMSSTRet Valor do ICMS ST retido E N12.1 N 1-1 13v2 Valor do ICMS ST cobrado anteriormente por ST (v2.0). O valor pode ser omitido quando a legislação não exigir a sua informação. (NT 2011/004)
					
				}
				
				
				/***************************************************************************************************/
				/******************************** IPI - IMPOSTO SOBRE PRODUTO INDUSTRIALIZADO **********************/
				/***************************************************************************************************/
				if ($rs[Industrializado]==1){

					$IPI = $dom->createElement("IPI");
					$imposto->appendChild($IPI);

					// CÓDIGO DE ENQUADRAMENTO LEGAL DO IPI
					// Tabela a ser criada pela RFB, informar 999 enquanto a tabela não for criada

					$cEnq = $dom->createElement("cEnq", "999");
					$IPI->appendChild($cEnq);

					/****************************** CST IPI = 00, 49, 50 e 99  *******************************/
					if (($arrInfoNFE['cst-ipi-produto']=="00") || ($arrInfoNFE['cst-ipi-produto']=="49") || ($arrInfoNFE['cst-ipi-produto']=="50") || ($arrInfoNFE['cst-ipi-produto']=="99")){

						$IPITrib = $dom->createElement("IPITrib");
						$IPI->appendChild($IPITrib);

						//CST - Código da situação tributária do IPI
						$CST = $dom->createElement("CST", $arrInfoNFE['cst-ipi-produto']);
						$IPITrib->appendChild($CST);

						//Valor da BC do IPI
						$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-ipi-produto']);
						$IPITrib->appendChild($vBC);

						//Alíquota do IPI
						$pIPI = $dom->createElement("pIPI", $arrInfoNFE['percentual-ipi-produto']);
						$IPITrib->appendChild($pIPI);

						//Valor do IPI
						$vIPI = $dom->createElement("vIPI", $arrInfoNFE['valor-ipi-produto']);
						$IPITrib->appendChild($vIPI);

						$vIPITot += $arrInfoNFE['valor-ipi-produto'];

						// dois campos abaixo não usados
						// qUnid - Quantidade total na unidade padrão para tributação (somente para os produtos tributados por unidade)
						// vUnid - Valor por Unidade Tributável
					}

					/****************************** CST IPI = 01, 02, 03, 04, 51, 52, 53, 54 e 55  *******************************/
					if (($arrInfoNFE['cst-ipi-produto']=="01") || ($arrInfoNFE['cst-ipi-produto']=="02") || ($arrInfoNFE['cst-ipi-produto']=="03") || ($arrInfoNFE['cst-ipi-produto']=="04") ||
						($arrInfoNFE['cst-ipi-produto']=="51") || ($arrInfoNFE['cst-ipi-produto']=="52") || ($arrInfoNFE['cst-ipi-produto']=="53") || ($arrInfoNFE['cst-ipi-produto']=="54") ||
						($arrInfoNFE['cst-ipi-produto']=="55")){

						$IPINT = $dom->createElement("IPINT");
						$IPI->appendChild($IPINT);

						//CST - Código da situação tributária do IPI
						$CST = $dom->createElement("CST", $arrInfoNFE['cst-ipi-produto']);
						$IPINT->appendChild($CST);
					}
				}
			}

			// SERVIÇO
			if ($rs[Tipo]=="31"){
				$flagISSQN = 1;
				$vServTot += ($rs[Valor]*$rs[Quantidade]);

				$ISSQN = $dom->createElement("ISSQN");
				$imposto->appendChild($ISSQN);

				//VALOR DA BASE DE CÁLCULO DO ISSQN
				$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-issqn-servico']);
				$ISSQN->appendChild($vBC);
				$vBCTotISSQN += $arrInfoNFE['base-calculo-issqn-servico'];

				//ALÍQUOTA DO ISSQN
				$vAliq = $dom->createElement("vAliq", $arrInfoNFE['percentual-issqn-servico']);
				$ISSQN->appendChild($vAliq );

				//VALOR DO ISSQN
				$vISSQN = $dom->createElement("vISSQN", $arrInfoNFE['valor-issqn-servico']);
				$ISSQN->appendChild($vISSQN);
				$vISSQNTot += $arrInfoNFE['valor-issqn-servico'];

				//CÓDIGO DO MUNICÍPIO DE OCORRÊNCIA DO FATO GERADOR DO ISSQN
				$cMunFG = $dom->createElement("cMunFG", $arrInfoNFE['codigo-municipio-issqn']);
				$ISSQN->appendChild($cMunFG);

				//ITEM DA LISTA DE SERVIÇO
				$cListServ = $dom->createElement("cListServ", soNumeros($arrInfoNFE['lista-servico']));
				$ISSQN->appendChild($cListServ);

				//CÓDIGO DE TRIBUTAÇÃO DO ISSQN
				$cSitTrib = $dom->createElement("cSitTrib", $arrInfoNFE['cst-icsqn-servico']);
				$ISSQN->appendChild($cSitTrib);

			}

			/***************************************************************************************************/
			/******************************** IMPOSTO DE IMPORTAÇÃO ********************************************/
			/***************************************************************************************************/
			// Não contemplado na 1ª versão - Esperar a necessidade

			/***************************************************************************************************/
			/********************************           PIS      ***********************************************/
			/***************************************************************************************************/

			$PIS = $dom->createElement("PIS");
			$imposto->appendChild($PIS);

			/****************************** CST PIS = 01 e 02 *******************************/
			if (($arrInfoNFE['cst-pis-produto']=="01") || ($arrInfoNFE['cst-pis-produto']=="02")){
				$PISAliq = $dom->createElement("PISAliq");
				$PIS->appendChild($PISAliq);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO PIS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-pis-produto']);
				$PISAliq->appendChild($CST);

				// VALOR DA BASE DE CÁLCULO DO PIS
				$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-pis-produto']);
				$PISAliq->appendChild($vBC);

				// ALÍQUOTA DO PIS (EM PERCENTUAL)
				$pPIS = $dom->createElement("pPIS", $arrInfoNFE['percentual-pis-produto']);
				$PISAliq->appendChild($pPIS);

				// VALOR DO PIS
				$vPIS = $dom->createElement("vPIS", $arrInfoNFE['valor-pis-produto']);
				$PISAliq->appendChild($vPIS);
				if (($rs[Tipo]=="30") || ($rs[Tipo]=="100") || ($rs[Tipo]=="175"))
					$vPISTotProd += $arrInfoNFE['valor-pis-produto'];
				else
					$vPISTotServ += $arrInfoNFE['valor-pis-produto'];
			}

			/****************************** CST PIS = 03 *******************************/
			if ($arrInfoNFE['cst-pis-produto']=="03"){
				// Não contemplado na 1ª versão - Esperar a necessidade
			}

			/****************************** CST PIS = 04, 06, 07, 08 ou 09 *******************************/
			if (($arrInfoNFE['cst-pis-produto']=="04") || ($arrInfoNFE['cst-pis-produto']=="06") || ($arrInfoNFE['cst-pis-produto']=="07") || ($arrInfoNFE['cst-pis-produto']=="08") || ($arrInfoNFE['cst-pis-produto']=="09")){
				$PISNT = $dom->createElement("PISNT");
				$PIS->appendChild($PISNT);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO PIS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-pis-produto']);
				$PISNT->appendChild($CST);
			}

			/****************************** CST PIS = 49, 50, 51, 52, 53, 54, 55, 56, 60, 61, 62, 63, 64, 65, 66, 67, 70, 71, 72, 73, 74, 75, 98, 99   *******************************/
			if (($arrInfoNFE['cst-pis-produto']=="49") || ($arrInfoNFE['cst-pis-produto']=="50") || ($arrInfoNFE['cst-pis-produto']=="51") || ($arrInfoNFE['cst-pis-produto']=="52") || ($arrInfoNFE['cst-pis-produto']=="53") ||
				($arrInfoNFE['cst-pis-produto']=="54") || ($arrInfoNFE['cst-pis-produto']=="55") || ($arrInfoNFE['cst-pis-produto']=="56") || ($arrInfoNFE['cst-pis-produto']=="60") || ($arrInfoNFE['cst-pis-produto']=="61") ||
				($arrInfoNFE['cst-pis-produto']=="62") || ($arrInfoNFE['cst-pis-produto']=="63") || ($arrInfoNFE['cst-pis-produto']=="64") || ($arrInfoNFE['cst-pis-produto']=="65") || ($arrInfoNFE['cst-pis-produto']=="66") ||
				($arrInfoNFE['cst-pis-produto']=="67") || ($arrInfoNFE['cst-pis-produto']=="70") || ($arrInfoNFE['cst-pis-produto']=="71") || ($arrInfoNFE['cst-pis-produto']=="72") || ($arrInfoNFE['cst-pis-produto']=="73") ||
				($arrInfoNFE['cst-pis-produto']=="74") || ($arrInfoNFE['cst-pis-produto']=="75") || ($arrInfoNFE['cst-pis-produto']=="98") || ($arrInfoNFE['cst-pis-produto']=="99")){

				$PISOutr = $dom->createElement("PISOutr");
				$PIS->appendChild($PISOutr);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO PIS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-pis-produto']);
				$PISOutr->appendChild($CST);

				// VALOR DA BASE DE CÁLCULO DO PIS
				$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-pis-produto']);
				$PISOutr->appendChild($vBC);

				// ALÍQUOTA DO PIS (EM PERCENTUAL)
				$pPIS = $dom->createElement("pPIS", $arrInfoNFE['percentual-pis-produto']);
				$PISOutr->appendChild($pPIS);

				// VALOR DO PIS
				$vPIS = $dom->createElement("vPIS", $arrInfoNFE['valor-pis-produto']);
				$PISOutr->appendChild($vPIS);

				if (($rs[Tipo]=="30") || ($rs[Tipo]=="100") || ($rs[Tipo]=="175"))
					$vPISTotProd += $arrInfoNFE['valor-pis-produto'];
				else
					$vPISTotServ += $arrInfoNFE['valor-pis-produto'];
			}


			/***************************************************************************************************/
			/********************************           COFINS      ********************************************/
			/***************************************************************************************************/

			$COFINS = $dom->createElement("COFINS");
			$imposto->appendChild($COFINS);

			/****************************** CST COFINS = 01 e 02 *******************************/
			if (($arrInfoNFE['cst-cofins-produto']=="01") || ($arrInfoNFE['cst-cofins-produto']=="02")){
				$COFINSAliq = $dom->createElement("COFINSAliq");
				$COFINS->appendChild($COFINSAliq);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO COFINS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-cofins-produto']);
				$COFINSAliq->appendChild($CST);

				// VALOR DA BASE DE CÁLCULO DO COFINS
				$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-cofins-produto']);
				$COFINSAliq->appendChild($vBC);

				// ALÍQUOTA DO COFINS (EM PERCENTUAL)
				$pCOFINS = $dom->createElement("pCOFINS", $arrInfoNFE['percentual-cofins-produto']);
				$COFINSAliq->appendChild($pCOFINS);

				// VALOR DO COFINS
				$vCOFINS = $dom->createElement("vCOFINS", $arrInfoNFE['valor-cofins-produto']);
				$COFINSAliq->appendChild($vCOFINS);

				if (($rs[Tipo]=="30") || ($rs[Tipo]=="100") || ($rs[Tipo]=="175"))
					$vCOFINSTotProd += $arrInfoNFE['valor-cofins-produto'];
				else
					$vCOFINSTotServ += $arrInfoNFE['valor-cofins-produto'];
			}

			/****************************** CST COFINS = 03 *******************************/
			if ($arrInfoNFE['cst-cofins-produto']=="03"){
				// Não contemplado na 1ª versão - Esperar a necessidade
			}

			/****************************** CST COFINS = 04, 06, 07, 08 ou 09 *******************************/
			if (($arrInfoNFE['cst-cofins-produto']=="04") || ($arrInfoNFE['cst-cofins-produto']=="06") || ($arrInfoNFE['cst-cofins-produto']=="07") || ($arrInfoNFE['cst-cofins-produto']=="08") || ($arrInfoNFE['cst-cofins-produto']=="09")){
				$COFINSNT = $dom->createElement("COFINSNT");
				$COFINS->appendChild($COFINSNT);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO COFINS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-cofins-produto']);
				$COFINSNT->appendChild($CST);
			}

			/****************************** CST COFINS = 49, 50, 51, 52, 53, 54, 55, 56, 60, 61, 62, 63, 64, 65, 66, 67, 70, 71, 72, 73, 74, 75, 98, 99   *******************************/
			if (($arrInfoNFE['cst-cofins-produto']=="49") || ($arrInfoNFE['cst-cofins-produto']=="50") || ($arrInfoNFE['cst-cofins-produto']=="51") || ($arrInfoNFE['cst-cofins-produto']=="52") || ($arrInfoNFE['cst-cofins-produto']=="53") ||
				($arrInfoNFE['cst-cofins-produto']=="54") || ($arrInfoNFE['cst-cofins-produto']=="55") || ($arrInfoNFE['cst-cofins-produto']=="56") || ($arrInfoNFE['cst-cofins-produto']=="60") || ($arrInfoNFE['cst-cofins-produto']=="61") ||
				($arrInfoNFE['cst-cofins-produto']=="62") || ($arrInfoNFE['cst-cofins-produto']=="63") || ($arrInfoNFE['cst-cofins-produto']=="64") || ($arrInfoNFE['cst-cofins-produto']=="65") || ($arrInfoNFE['cst-cofins-produto']=="66") ||
				($arrInfoNFE['cst-cofins-produto']=="67") || ($arrInfoNFE['cst-cofins-produto']=="70") || ($arrInfoNFE['cst-cofins-produto']=="71") || ($arrInfoNFE['cst-cofins-produto']=="72") || ($arrInfoNFE['cst-cofins-produto']=="73") ||
				($arrInfoNFE['cst-cofins-produto']=="74") || ($arrInfoNFE['cst-cofins-produto']=="75") || ($arrInfoNFE['cst-cofins-produto']=="98") || ($arrInfoNFE['cst-cofins-produto']=="99")){

				$COFINSOutr = $dom->createElement("COFINSOutr");
				$COFINS->appendChild($COFINSOutr);

				// CÓDIGO DE SITUAÇÃO TRIBUTÁRIA DO COFINS
				$CST = $dom->createElement("CST", $arrInfoNFE['cst-cofins-produto']);
				$COFINSOutr->appendChild($CST);

				// VALOR DA BASE DE CÁLCULO DO COFINS
				$vBC = $dom->createElement("vBC", $arrInfoNFE['base-calculo-cofins-produto']);
				$COFINSOutr->appendChild($vBC);

				// ALÍQUOTA DO COFINS (EM PERCENTUAL)
				$pCOFINS = $dom->createElement("pCOFINS", $arrInfoNFE['percentual-cofins-produto']);
				$COFINSOutr->appendChild($pCOFINS);

				// VALOR DO COFINS
				$vCOFINS = $dom->createElement("vCOFINS", $arrInfoNFE['valor-cofins-produto']);
				$COFINSOutr->appendChild($vCOFINS);

				if (($rs[Tipo]=="30") || ($rs[Tipo]=="100") || ($rs[Tipo]=="175"))
					$vCOFINSTotProd += $arrInfoNFE['valor-cofins-produto'];
				else
					$vCOFINSTotServ += $arrInfoNFE['valor-cofins-produto'];
			}
		}



		/***************************************************************************************************/
		/********************************     GRUPO TOTAIS      ********************************************/
		/***************************************************************************************************/

		$total = $dom->createElement("total");
		$infNFe->appendChild($total);

		//if ($flagICMS==1){
			/********************************     TOTAIS ICMS       ********************************************/
			$ICMSTot = $dom->createElement("ICMSTot");
			$total->appendChild($ICMSTot);

			// BASE DE CÁLCULO DO ICMS
			$vBC = $dom->createElement("vBC", number_format($vBCTotICMS,2,".",""));
			$ICMSTot->appendChild($vBC);

			// VALOR TOTAL DO ICMS
			$vICMS = $dom->createElement("vICMS", number_format($vICMSTotICMS,2,".",""));
			$ICMSTot->appendChild($vICMS);

			// VALOR TOTAL DO ICMS DESONERADO
			$vICMSDesonaracao = $dom->createElement("vICMSDeson", number_format($vICMSDeson,2,".",""));
			$ICMSTot->appendChild($vICMSDesonaracao);

			// VBCST BASE DE CÁLCULO DO ICMS ST
			$vBCST = $dom->createElement("vBCST", number_format($vBCSTTotICMS,2,".",""));
			$ICMSTot->appendChild($vBCST);

			// VALOR TOTAL DO ICMS ST
			$vST = $dom->createElement("vST", number_format($vSTTotICMS,2,".",""));
			$ICMSTot->appendChild($vST);

			// VALOR TOTAL DOS PRODUTOS E SERVIÇOS
			$vProd = $dom->createElement("vProd", number_format($vProdTot,2,".",""));
			$ICMSTot->appendChild($vProd);

			// VALOR TOTAL DO FRETE
			$vFrete = $dom->createElement("vFrete", number_format($vFreteTot,2,".",""));
			$ICMSTot->appendChild($vFrete);

			// VALOR TOTAL DO SEGURO
			$vSeg = $dom->createElement("vSeg", number_format($vSegTot,2,".",""));
			$ICMSTot->appendChild($vSeg);

			// VALOR TOTAL DO DESCONTO
			$vDesc = $dom->createElement("vDesc", number_format($vDescTot,2,".",""));
			$ICMSTot->appendChild($vDesc);

			// vII - VALOR TOTAL DO II
			$vII = $dom->createElement("vII", number_format($vIITot,2,".",""));
			$ICMSTot->appendChild($vII);

			// vIPI - VALOR TOTAL DO IPI
			$vIPI = $dom->createElement("vIPI", number_format($vIPITot,2,".",""));
			$ICMSTot->appendChild($vIPI);

			// vPIS - VALOR DO PIS
			$vPIS = $dom->createElement("vPIS", number_format($vPISTotProd,2,".",""));
			$ICMSTot->appendChild($vPIS);

			// vCOFINS - VALOR DO COFINS
			$vCOFINS = $dom->createElement("vCOFINS", number_format($vCOFINSTotProd,2,".",""));
			$ICMSTot->appendChild($vCOFINS);

			// vOutro - OUTRAS DESPESAS ACESSÓRIAS
			$vOutro = $dom->createElement("vOutro", number_format($vOutroTot,2,".",""));
			$ICMSTot->appendChild($vOutro);

			// vNF - Valor Total da NF-e
			// (+) vProd (id:W07)
			// (-) vDesc (id:W10)
			// (+) vICMSST (id:W06)
			// (+) vFrete (id:W09)
			// (+) vSeg (id:W10)
			// (+) vOutro (id:W15)
			// (+) vII (id:W11)
			// (+) vIPI (id:W12)
			// (+) vServ (id:W19) (NT 2011/004)

			$vTotNF = ($vProdTot - $vDescTot + $vSTTotICMS + $vFreteTot + $vSegTot + $vOutroTot + $vIITot + $vIPITot + $vServTot);
			$vNF = $dom->createElement("vNF", number_format($vTotNF,2,".",""));
			$ICMSTot->appendChild($vNF);
		//}

		/********************************     TOTAIS ISSQN ********************************************/
		// em desenvolvimento
		if ($flagISSQN==1){
			$ISSQNtot = $dom->createElement("ISSQNtot");
			$total->appendChild($ISSQNtot);

			//VALOR TOTAL DOS SERVIÇOS SOB NÃO-INCIDÊNCIA OU NÃO TRIBUTADOS PELO ICMS
			$vServ = $dom->createElement("vServ", number_format($vServTot,2,".",""));
			$ISSQNtot->appendChild($vServ);

			//BASE DE CÁLCULO DO ISS
			$vBC = $dom->createElement("vBC", number_format($vBCTotISSQN,2,".",""));
			$ISSQNtot->appendChild($vBC);

			//VALOR TOTAL DO ISS
			$vISS = $dom->createElement("vISS", number_format($vISSQNTot,2,".",""));
			$ISSQNtot->appendChild($vISS);

			//VALOR DO PIS SOBRE SERVIÇOS
			$vPIS = $dom->createElement("vPIS", number_format($vPISTotServ,2,".",""));
			$ISSQNtot->appendChild($vPIS);

			//VALOR DO COFINS SOBRE SERVIÇO
			$vCOFINS = $dom->createElement("vCOFINS", number_format($vCOFINSTotServ,2,".",""));
			$ISSQNtot->appendChild($vCOFINS);
		}

		/***************************************************************************************************/
		/********************************  BLOCO TRANSPORTADORA - FRETE ************************************/
		/***************************************************************************************************/

		$dadosNF['transportadora-id'] = $_POST['transportadora-id'];

		$transp = $dom->createElement("transp");
		$infNFe->appendChild($transp);

		$sql = "select cd.Tipo_Pessoa, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj, cd.RG, cd.Inscricao_Municipal, cd.Inscricao_Estadual, cd.Email,
					ce.Tipo_Endereco_ID, ce.CEP, ce.Logradouro, ce.Numero, ce.Complemento, ce.Bairro, ce.Cidade, ce.UF
					from cadastros_dados cd
					left join cadastros_enderecos ce on cd.Cadastro_ID = ce.Cadastro_ID and ce.Tipo_Endereco_ID = 26 and ce.Situacao_ID = 1
					where cd.Cadastro_ID = '".$dadosNF['transportadora-id']."'
					limit 1";
		$resultado = mpress_query($sql);
		$rs = mpress_fetch_array($resultado);

		// MODALIDADE DO FRETE
		// 0- Por conta do emitente;
		// 1- Por conta do destinatário/remetente;
		// 2- Por conta de terceiros;
		// 9- Sem frete. (V2.0)
		$modFrete = $dom->createElement("modFrete",$dadosNF['modFrete']);
		$transp->appendChild($modFrete);

		/*********************************/
		/* INICIO - BLOCO TRANSPORTADORA */
		/*********************************/

		if ($dadosNF['modFrete']!='9'){
			$transporta = $dom->createElement("transporta");
			$transp->appendChild($transporta);

			$CNPJ = $dom->createElement("CNPJ", soNumeros($rs['Cpf_Cnpj']));
			$transporta->appendChild($CNPJ);

			$xNome = $dom->createElement("xNome", removeAcentos($rs['Nome']));
			$transporta->appendChild($xNome);

			if ($rs['Inscricao_Estadual']!='ISENTO')
				$rs['Inscricao_Estadual'] = soNumeros($rs['Inscricao_Estadual']);
			$IE = $dom->createElement("IE", $rs['Inscricao_Estadual']);
			$transporta->appendChild($IE);
			if ($rs['Numero']!='') $rs['Logradouro'] = $rs['Logradouro'].", ".$rs['Numero'];
			$xEnder = $dom->createElement("xEnder", removeAcentos($rs['Logradouro']));
			$transporta->appendChild($xEnder);

			$xMun = $dom->createElement("xMun", removeAcentos($rs['Cidade']));
			$transporta->appendChild($xMun);

			$UF = $dom->createElement("UF", removeAcentos($rs['UF']));
			$transporta->appendChild($UF);

		/******************************/
		/* FIM - BLOCO TRANSPORTADORA */
		/******************************/

		/**************************/
		/* INICIO - BLOCO VOLUMES */
		/**************************/

			$vol = $dom->createElement("vol");
			$transp->appendChild($vol);

			$qVol = $dom->createElement("qVol", $dadosNF['qVol']);
			$vol->appendChild($qVol);

			$esp = $dom->createElement("esp", removeAcentos($dadosNF['esp']));
			$vol->appendChild($esp);

			$marca = $dom->createElement("marca", removeAcentos($dadosNF['marca']));
			$vol->appendChild($marca);

			$nVol = $dom->createElement("nVol", removeAcentos($dadosNF['nVol']));
			$vol->appendChild($nVol);

			$pesoL = $dom->createElement("pesoL", number_format($dadosNF['pesoL'],3,".",""));
			$vol->appendChild($pesoL);

			$pesoB = $dom->createElement("pesoB", number_format($dadosNF['pesoB'],3,".",""));
			$vol->appendChild($pesoB);
		}

		/***********************/
		/* FIM - BLOCO VOLUMES */
		/***********************/


		/***************************************************************************************************/
		/********************************  BLOCO - INFORMACOES ADICIONAIS DA NF-E **************************/
		/***************************************************************************************************/

		if (trim($dadosNF['infCpl'])!=''){
			$infAdic = $dom->createElement("infAdic");
			$infNFe->appendChild($infAdic);

			$infCpl = $dom->createElement("infCpl", removeAcentos(utf8_encode($dadosNF['infCpl'])));
			$infAdic->appendChild($infCpl);
		}

/*

			<transp>
				<modFrete>0</modFrete>
				<transporta>
					<CNPJ>10970887003624</CNPJ>
					<xNome>FEDEX BRASIL LOGISTICA E TRANSPORTE S.A</xNome>
					<IE>9019138116</IE>
					<xEnder>R PAUL GRAFUNKEL,1415 CIC</xEnder>
					<xMun>Curitiba</xMun>
					<UF>PR</UF>
				</transporta>
				<vol>
					<qVol>1</qVol>
					<esp>NO BREAK</esp>
					<marca>ENGETRON 3KVA</marca>
					<nVol>0270891</nVol>
					<pesoL>57.000</pesoL>
					<pesoB>57.000</pesoB>
				</vol>
			</transp>
			<infAdic>
				<infCpl>
				FRETE POR CONTA DE ALLAN ROBERTO SILVA LIMA ME.(LAB EFICAZ SYSTEM)"EMPRESA ENQUADRADA NO SIMPLES NACIONAL LEI 123/2006 ART.293 RIMS DESCRETO 1980/2007" "REMESSA DO PROPRIO IMOBILIZADO PARA A UTILIZAÇÃO NO SERVIÇO NA CIDADE DE TERESINA-PI"
				</infCpl>
			</infAdic>
													Razão social X06 0,64 11,56 2,92 8,69 60
													FRETE POR CONTA DE 0,64 2,79 14,48 8,69 Obs 8
													CÓDIGO ANTT X21 0,64 2,54 17,27 8,69 X25 20
													PLACA DO VEÍCULO X19 0,64 3,81 19,81 8,69 X23 8
													UF X20 0,64 1,02 23,62 8,69 X24 2
													CNPJ/CPF X04 0,64 4,83 24,64 8,69 14
													ENDEREÇO X08 0,64 11,56 2,92 9,33 60
													MUNICÍPIO X09 0,64 9,14 14,48 9,33 60
													UF X10 0,64 1,02 23,62 9,33 2
													INSCRIÇÃO ESTADUAL X07 0,64 4,83 24,64 9,33 14
													QUANTIDADE DE VOLUMES X27 0,64 3,56 2,92 9,97 15
													ESPÉCIE X28 0,64 3,81 6,48 9,97 60
													MARCA X29 0,64 4,19 10,29 9,97 60
													NUMERAÇÃO X30 0,64 5,08 14,48 9,97 60
													PESO BRUTO X32 0,64 5,08 19,56 9,97 15
													PESO LÍQUIDO
*/
		/***************************************************************************************************/
		/********************************  BLOCO DADOS DA COBRANÇA       *****************************************/
		/***************************************************************************************************/
		// analisando real necessidade... em desenvolvimento.....



		$retornoProtocolo = $retornoAssinatura = $retornoValidacao = $retornoTransmissao = "<p>N&atilde;o Realizado</p>";

		/***************************************************************************************************/
		/********************************  GERANDO ARQUIVO XML    *****************************************/
		/***************************************************************************************************/
        $sAmb = ($configNF[ambiente] == 2) ? 'homologacao' : 'producao';
		$caminhoFinal = $caminhoArquivoEntradas = $configNF[arquivosDir].$sAmb."/entradas/".$nomeArquivoXML;
		$dom->save($caminhoArquivoEntradas);
		//header("Content-Type: text/xml");
		$dom->saveXML();

		$nfe = new ToolsNFePHP($configNF);
		$conteudoXML = file_get_contents($caminhoFinal);

		if ($acao=="I"){
			$sql = "insert into nf_dados (Conta_ID, Numero_NF, Serie, Empresa_ID, Ambiente, NF_XML, NF_Dados, Chave_Acesso, Situacao_ID, Data_Emissao, Usuario_Emissao_ID)
						   				values ('$contaID', '$numeroNF', '$serieNF', '$empresaID', '$ambienteNF', '$conteudoXML', '".serialize($dadosNF)."', '$chaveNFE', 1, '$dataHoraAtual', '".$dadosUserLogin[userID]."')";
			//echo $sql;
			mpress_query($sql);
			$nfID = mysql_insert_id();
		}
		else{
			$sql = "update nf_dados set Empresa_ID = '$empresaID', Recibo = '',
									NF_XML = '$conteudoXML',
									Chave_Acesso = '$chaveNFE',
									Data_Emissao = '$dataHoraAtual',
									NF_Dados = '".serialize($dadosNF)."',
									Usuario_Emissao_ID = '".$dadosUserLogin[userID]."'
									where NF_ID = '$nfID'";
			//echo $sql;
			mpress_query($sql);
		}


		/***************************************************************************************************/
		/********************************  ASSINANDO NFE		   *****************************************/
		/***************************************************************************************************/
		$flagGerado = 0;

		if ($caminhoFinal == $caminhoArquivoEntradas){
			$caminhoArquivoAssinada = $configNF[arquivosDir].$sAmb."/assinadas/".$nomeArquivoXML;
			$arquivo = file_get_contents($caminhoArquivoEntradas);

			if ($xml = $nfe->signXML($arquivo, 'infNFe')){
				file_put_contents($caminhoArquivoAssinada, $xml);
				unlink($caminhoArquivoEntradas);
				$retornoAssinatura = "Gerado com sucesso!";
				$caminhoFinal = $caminhoArquivoAssinada;
				$flagGerado = 1;
			} else {
				$retornoAssinatura = "Problemas na assinatura da NF: ".$nfe->errMsg;
			}
		}




		/***************************************************************************************************/
		/********************************  VALIDANDO NFE		   *****************************************/
		/***************************************************************************************************/
		//echo "$caminhoFinal <br>$caminhoArquivoAssinada";

		if ($caminhoFinal == $caminhoArquivoAssinada){
			$retornoValidacao = "";
			$caminhoArquivoValidada = $configNF[arquivosDir].$sAmb."/validadas/".$nomeArquivoXML;
			$caminhoArquivoRejeitada = $configNF[arquivosDir].$sAmb."/rejeitadas/".$nomeArquivoXML;
			$docxml = file_get_contents($caminhoArquivoAssinada);
			$dirBase = retornaDiretorioBaseNFE();
			//$xsdFile = $dirBase."/schemes/PL_006u/nfe_v2.00.xsd";
			//$xsdFile = $dirBase."schemes/".$configNF['schema']."/nfe_v3.10.xsd";
			$xsdFile = $dirBase."/schemes/PL_008d/nfe_v3.10.xsd";
			$aErroValidar = '';
			$c = $nfe->validXML($docxml,$xsdFile,$aErroValidar);
			if (!$c){
				foreach ($aErroValidar as $er){
					$retornoValidacao .= "<p>".$er."</p>";
					//echo $retornoValidacao;
				}
				copy($caminhoArquivoAssinada, $caminhoArquivoRejeitada);
				$caminhoFinal = $caminhoArquivoRejeitada;
				unlink($caminhoArquivoAssinada);
			}
			else {
				$retornoValidacao = "<p>XML Validado</p>";
				copy($caminhoArquivoAssinada, $caminhoArquivoValidada);
				unlink($caminhoArquivoAssinada);
				$caminhoFinal = $caminhoArquivoValidada;
			}
		}

		/***************************************************************************************************/
		/********************************  ENVIANDO NFE		       *****************************************/
		/***************************************************************************************************/


		if ($caminhoFinal == $caminhoArquivoValidada){

			$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
			$caminhoArquivoEnviada = $configNF[arquivosDir].$sAmb."/enviadas/".$nomeArquivoXML;
			$caminhoArquivoAprovada = $configNF[arquivosDir].$sAmb."/enviadas/aprovadas/".$nomeArquivoXML;
			$caminhoArquivoDenegada = $configNF[arquivosDir].$sAmb."/enviadas/denegadas/".$nomeArquivoXML;
			$caminhoArquivoReprovada = $configNF[arquivosDir].$sAmb."/enviadas/reprovadas/".$nomeArquivoXML;

			//$modSOAP = '2'; //usando cURL
			$lote = substr(str_replace(',','',number_format(microtime(true)*1000000,0)),0,15); 		//obter um numero de lote
			//$aNFe = array(0=>file_get_contents($caminhoArquivoValidada)); 							// montar o array com a NFe
			//$aNFe = file_get_contents($caminhoArquivoValidada);
			$conteudoXML = file_get_contents($caminhoArquivoValidada);
			mpress_query("update nf_dados set Empresa_ID = '$empresaID', Recibo = '', NF_XML = '$conteudoXML', Situacao_ID = 1, Data_Emissao = '$dataHoraAtual' where NF_ID = '$nfID'");

			$recibo = "";
			// só faz o envio se não tiver sido aprovada
			if ($statusNF!="100"){
				if ($respAutoriza = $nfe->autoriza($conteudoXML, $lote, $aResp)){ 									//enviar o lote
					if ($aResp['bStat']){
						copy($caminhoArquivoValidada, $caminhoArquivoEnviada);
						unlink($caminhoArquivoValidada);
						$caminhoFinal = $caminhoArquivoEnviada;
						$recibo = $aResp['infRec']['nRec'];
						mpress_query("update nf_dados set Recibo = '$recibo' where NF_ID = '$nfID'");

						$retornoTransmissao = "N&uacute;mero do Recibo: $recibo Status:".$aResp['cStat']." - ".$aResp['xMotivo'];
					} else {
						copy($caminhoArquivoValidada, $caminhoArquivoReprovada);
						unlink($caminhoArquivoValidada);
						$caminhoFinal = $caminhoArquivoRejeitada;
						$retornoTransmissao = "<p>Erro</p><p>".$nfe->errMsg."</p>";

						$retornoTransmissao .= "<br><h1>DEBUG DA COMUNICACAO SOAP</h1><br>
											 <pre>".htmlspecialchars($nfe->soapDebug)."</pre><br>";
					}
				} else {
					copy($caminhoArquivoValidada, $caminhoArquivoReprovada);
					unlink($caminhoArquivoValidada);
					$caminhoFinal = $caminhoArquivoRejeitada;
					$retornoTransmissao = "<p>Erro na comunicacao com a receita</p><p>".$nfe->errMsg."</p>";

					$retornoTransmissao .= "<br><h1>DEBUG DA COMUNICACAO SOAP</h1><br>
											 <pre>".htmlspecialchars($nfe->soapDebug)."</pre><br>";
				}
			}

		}

		/***************************************************************************************************/
		/********************************  PEGAR DADOS DO PROTOCOLO  ***************************************/
		/***************************************************************************************************/
		/* Solicitação da situação da NFe atraves do numero do recibo de uma nota enviada e recebida com sucesso pelo SEFAZ */
		if (($caminhoFinal == $caminhoArquivoEnviada) && ($recibo!=="")){
			sleep(3);
			$chave = '';
			$tpAmb = $configNF[ambiente];
			if ($xmlResp = $nfe->getProtocol("", $chaveNFE, $tpAmb, $aResp)){
				$retornoProtocolo = "<p>Status: ".$aResp['cStat']." - ".$aResp['xMotivo']."</p>";
				// SE APROVADA
				if ($aResp['cStat']=="100"){
					$caminhoArquivoAprovada = $configNF[arquivosDir].$sAmb."/enviadas/aprovadas/".$nomeArquivoXML;
					$caminhoArquivoProtocolo = $configNF[arquivosDir].$sAmb."/temporarias/".$chaveNFE."-prot.xml";
					echo $caminhoArquivoProtocolo;
					if ($conteudoXML = $nfe->addProt($caminhoArquivoEnviada, $caminhoArquivoProtocolo)){

						$caminhoFinal = $caminhoArquivoAprovada;
						file_put_contents($caminhoArquivoAprovada, $conteudoXML);
						mpress_query("update nf_dados set NF_XML = '$conteudoXML', Data_Emissao = '$dataHoraAtual' where NF_ID = '$nfID'");
					}
					else{
						echo "TRATAR ERRO!";
					}
				}
				mpress_query("update nf_dados set Status_NF = '".$aResp[aProt][cStat]."', Protocolo = '".$aResp[aProt][nProt]."' where NF_ID = '$nfID'");

			} else {
				$retornoProtocolo = "<p>Erro na comunicacao com a receita</p><p>".$nfe->errMsg."</p>";
			}
		}

		/***************************************************************************************************/
		if ($flagGerado=="1"){
			$link = $caminhoSistema.substr($caminhoFinal, strlen($caminhoFisico) - (strlen($caminhoFinal)));
			$arrayRetorno[retornoArquivo] = $link;
		}
		$arrayRetorno[retornoAssinatura] = $retornoAssinatura;
		$arrayRetorno[retornoValidacao] = stripslashes(str_replace("'","&#39;", utf8_decode($retornoValidacao)));
		$arrayRetorno[retornoTransmissao] = stripslashes(str_replace("'","&#39;", utf8_decode($retornoTransmissao)));
		$arrayRetorno[retornoProtocolo] = stripslashes(str_replace("'","&#39;", utf8_decode($retornoProtocolo)));

		//echo $arrayRetorno[retornoValidacao];
		mpress_query("update nf_dados set NF_Array = '".serialize($arrayRetorno)."' where NF_ID = '$nfID'");
	}

	function imprimirDanfe(){
		global $caminhoFisico, $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');

		if (incluirClasseDanfe()){
			$nfID = $_POST["nf-id"];
			$contaID = $_POST['conta-id'];
			$sql = "select Chave_Acesso, Empresa_ID from nf_dados where NF_ID = '$nfID'";
			$resultado = mpress_query($sql);
			if ($rs = mpress_fetch_array($resultado)){
				$chaveNFE = $rs[Chave_Acesso];
				$empresaID = $rs[Empresa_ID];
				$nomeArquivoXML = $chaveNFE."-nfe.xml";
				$configNF = retornaArrayConfigNF($empresaID);
				$sAmb = ($configNF[ambiente] == 2) ? 'homologacao' : 'producao';
			}
			$caminhoArquivoAprovada = $configNF[arquivosDir].$sAmb."/enviadas/aprovadas/$nomeArquivoXML";
			/***************************************************************************************************/
			/*********************************       GERANDO PDF         ***************************************/
			/***************************************************************************************************/
			if (is_file($caminhoArquivoAprovada) ){
				$docxml = file_get_contents($caminhoArquivoAprovada);
				$danfe = new DanfeNFePHP($docxml, 'L', 'A4','../images/logo.jpg','I','');
				$id = $danfe->montaDANFE();

				$nomeArquivo = 'DANFE-'.$id.'.pdf';
				$danfe->printDANFE($nomeArquivo,'I');
				$danfe->printDANFE($caminhoFisico.'/uploads/'.$nomeArquivo,'F');

				$sql = "INSERT INTO modulos_anexos
									(Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
							VALUES ('', 					'$contaID', 		'financeiro', '$nomeArquivo', '$nomeArquivo', 1, '$dataHoraAtual', '".$dadosUserLogin[userID]."')";
				$resultado = mpress_query($sql);

				$sql = "	INSERT INTO modulos_anexos (Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Nome_Arquivo_Original, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
												SELECT 	distinct      '', Chave_Estrangeira, Tabela_Estrangeira, '$nomeArquivo', '$nomeArquivo', 				1, '$dataHoraAtual', '".$dadosUserLogin[userID]."'
													from financeiro_produtos where Conta_ID = '$contaID' and Situacao_ID = 1";
				$resultado = mpress_query($sql);
			}
		}
		else{
			echo "Problemas para localizar classe de impressão";
		}
	}
?>