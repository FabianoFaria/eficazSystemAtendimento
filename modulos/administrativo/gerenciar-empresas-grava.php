<?php
	global $caminhoSistema;
	include("functions.php");
	$empresaID = $empresaAuxID = $_POST['select-empresa'];
	$empresaSelecionada = $_POST['empresa-selecionada'];
	$centroCustoID = $_POST['centro-custo-id'];

	if ($empresaID=="-1") $empresaAuxID = $empresaSelecionada;

	if($mod = mpress_fetch_array(mpress_query("select count(*) as nfeAtivo from modulos where Slug = 'nfe'")))
		$moduloNfeAtivo = $mod[nfeAtivo];

	if ($empresaSelecionada=="D"){
		mpress_query("update cadastros_dados set Empresa = 0, Centro_Custo_ID = 0 where Cadastro_ID = '$empresaID'");
		mpress_query("delete from nf_config where Empresa_ID = '$empresaID'");
		echo "Empresa excluida com sucesso";
		exit();
	}
	else{
		// caso seja novo selecionar o primeiro endereco principal cadastrado para a empresa
		$rs = mpress_query("select Cadastro_Endereco_ID from cadastros_enderecos where Cadastro_ID = '$empresaSelecionada' and Situacao_ID = 1 order by Tipo_Endereco_ID, Cadastro_Endereco_ID");
		if($row = mpress_fetch_array($rs))
			$enderecoSelecionado = $row[Cadastro_Endereco_ID];
		if ($enderecoSelecionado=="") $enderecoSelecionado = 0;

		// caso seja novo selecionar o primeiro telefone cadastrado para a empresa
		$rs = mpress_query("select Cadastro_Telefone_ID from cadastros_telefones where Cadastro_ID = '$empresaSelecionada' and Situacao_ID = 1");
		if($row = mpress_fetch_array($rs))
			$telefoneSelecionado = $row[Cadastro_Telefone_ID];
		if ($telefoneSelecionado=="") $telefoneSelecionado = 0;

		$ambiente = 2;
		$arquivoURLxml = "nfe_ws3_mod55.xml";
		$arquivoURLxmlCTe = "cte_ws1.xml";
		$certsDir = $caminhoFisico."/uploads/certificados/";
		$arquivosDir = $caminhoFisico."/uploads/nfe/";
		$arquivosDirCTe = $caminhoFisico."/uploads/cte/";

		$baseurl = $caminhoSistema.'/includes/nfe/';

		$schemes = "PL_008d";
		$schemesCTe = "PL_CTe_200";

		$danfePapel = $dactePapel = "A4";
		$danfeFormato = $dacteFormato = "L";
		$danfeCanhoto = $dacteCanhoto = "1";
		$danfeLogo = $dacteLogo = $caminhoSistema."/images/topo/logo.png";
		$danfeLogoPos = $dacteLogoPos = "L";
		$danfeFonte = $dacteFonte = "Times";
		$danfePrinter = $dactePrinter = "";
		$CRT = "1";

		if ($empresaID=="-1"){
		}
		else{
			$ambiente = $_POST['ambiente'];
			if ($_POST['urlws']!="")
				$arquivoURLxml = $_POST['urlws'];
			if ($_POST['urlwscte']!="")
				$arquivoURLxmlCTe = $_POST['urlwscte'];
			if ($_POST['certsDir']!="")
				$certsDir = $_POST['certsDir'];
			if ($_POST['dirnfe']!="")
				$arquivosDir = $_POST['dirnfe'];

			if ($_POST['dircte']!="")
				$arquivosDirCTe = $_POST['dircte'];

			if ($_POST['urlapi']!="")
				$baseurl = $_POST['urlapi'];

			$certName = $_POST['pfx'];
			$keyPass = $_POST['keysenha'];

			if ($_POST['schema']!="")
				$schemes = $_POST['schema'];
			if ($_POST['schemacte']!="")
				$schemesCTe = $_POST['schemacte'];

			if ($_POST['papel']!="")
				$danfePapel = $_POST['papel'];

			if ($_POST['formato']!="")
				$danfeFormato = $_POST['formato'];

			if ($_POST['canhoto']!="")
				$danfeCanhoto = $_POST['canhoto'];

			if ($_POST['logo']!="")
				$danfeLogo = $_POST['logo'];
			if ($_POST['logopos']!="")
				$danfeLogoPos = $_POST['logopos'];
			if ($_POST['fontecte']!="")
				$danfeFonte = $_POST['fontecte'];
			if ($_POST['printer']!="")
				$danfePrinter = $_POST['printer'];

			if ($_POST['papelcte']!="")
				$dactePapel = $_POST['papelcte'];
			if ($_POST['formatocte']!="")
				$dacteFormato = $_POST['formatocte'];
			if ($_POST['canhotocte']!="")
				$dacteCanhoto = $_POST['canhotocte'];
			if ($_POST['logocte']!="")
				$dacteLogo = $_POST['logocte'];
			if ($_POST['logoposcte']!="")
				$dacteLogoPos = $_POST['logoposcte'];
			if ($_POST['fontecte']!="")
				$dacteFonte = $_POST['fontecte'];
			if ($_POST['printercte']!="")
				$dactePrinter = $_POST['printercte'];

			if ($_POST['enderecoemitente']!="")
				$enderecoSelecionado = $_POST['enderecoemitente'];
			if ($_POST['telefoneemitente']!="")
				$telefoneSelecionado = $_POST['telefoneemitente'];

			if ($_POST['cnaefiscal']!="")
				$CNAE = $_POST['cnaefiscal'];
			if ($_POST['regimetributario']!="")
				$CRT = $_POST['regimetributario'];

			$csticmspadraosaida 		= $_POST["cst-icms-padrao-saida"];
			$cstipipadraosaida 			= $_POST["cst-ipi-padrao-saida"];
			$cstpispadraosaida 			= $_POST["cst-pis-padrao-saida"];
			$cstcofinspadraosaida		= $_POST["cst-cofins-padrao-saida"];
			$percentualicmspadraosaida 	= formataValorBD($_POST["percentual-icms-padrao-saida"]);
			$percentualipipadraosaida 	= formataValorBD($_POST["percentual-ipi-padrao-saida"]);
			$percentualpispadraosaida 	= formataValorBD($_POST["percentual-pis-padrao-saida"]);
			$percentualcofinspadraosaida = formataValorBD($_POST["percentual-cofins-padrao-saida"]);

			$cfopdescrpadraosaida = $_POST["cfop-descr-padrao-saida"];
			$cfoppadraosaida = $_POST["cfop-padrao-saida"];

			$listaservicopadrao = $_POST["lista-servico-padrao"];
			$csticsqnservicopadrao = $_POST["cst-icsqn-servico-padrao"];
			$percentualissqnservicopadrao = formataValorBD($_POST["percentual-issqn-servico-padrao"]);
		}

		mpress_query("update cadastros_dados set Empresa = 1, Centro_Custo_ID = '$centroCustoID' where Cadastro_ID = '$empresaSelecionada'");

		if ($moduloNfeAtivo>0){
			$configNF[ambiente] = $ambiente;
			$configNF[empresa] = $empresa;
			$configNF[fantasia] = $fantasia;
			$configNF[cnpj] = $cnpj;
			$configNF[CEP] = $CEP;
			$configNF[logradouro] = $logradouro;
			$configNF[numero] = $numero;
			$configNF[complemento] = $complemento;
			$configNF[bairro] = $bairro;
			$configNF[cidade] = $cidade;
			$configNF[referencia] = $referencia;
			$configNF[UF] = $UF;
			$configNF[cUF] = $cUF;
			$configNF[telefone] = $telefone;
			$configNF[inscricaoEstadual] = $inscricaoEstadual;
			$configNF[inscricaoMunicipal] = $inscricaoMunicipal;
			$configNF[CNAE] = $CNAE;
			$configNF[CRT] = $CRT;
			$configNF[enderecoSelecionado] = $enderecoSelecionado;
			$configNF[telefoneSelecionado] = $telefoneSelecionado;

			$configNF[arquivosDir] = $arquivosDir;
			$configNF[certsDir] = $certsDir;
			$configNF[certName] = $certName;

			$configNF[keyPass] = $keyPass;
			$configNF[arquivosDir] = $arquivosDir;
			$configNF[arquivosDirCTe] = $arquivosDirCTe;
			$configNF[arquivoURLxml] = $arquivoURLxml;
			$configNF[arquivoURLxmlCTe] = $arquivoURLxmlCTe;
			$configNF[baseurl] = $baseurl;

			$configNF[danfeLogo] = $danfeLogo;
			$configNF[danfeLogoPos] = $danfeLogoPos;
			$configNF[danfeFormato] = $danfeFormato;
			$configNF[danfePapel] = $danfePapel;
			$configNF[danfeCanhoto] = $danfeCanhoto;
			$configNF[danfeFonte] = $danfeFonte;
			$configNF[danfePrinter] = $danfePrinter;

			$configNF[dacteLogo] = $dacteLogo;
			$configNF[dacteLogoPos] = $dacteLogoPos;
			$configNF[dacteFormato] = $dacteFormato;
			$configNF[dactePapel] = $dactePapel;
			$configNF[dacteCanhoto] = $dacteCanhoto;
			$configNF[dacteFonte] = $dacteFonte;
			$configNF[dactePrinter] = $dactePrinter;

			$configNF[schemes] = $schemes;
			$configNF[schemesCTe] = $schemesCTe;

			$configNF[cst_icms_padrao_saida] = $csticmspadraosaida;
			$configNF[cst_ipi_padrao_saida] = $cstipipadraosaida;
			$configNF[cst_pis_padrao_saida] = $cstpispadraosaida;
			$configNF[cst_cofins_padrao_saida] = $cstcofinspadraosaida;

			$configNF[percentual_icms_padrao_saida] = $percentualicmspadraosaida;
			$configNF[percentual_ipi_padrao_saida] = $percentualipipadraosaida;
			$configNF[percentual_pis_padrao_saida] = $percentualpispadraosaida;
			$configNF[percentual_cofins_padrao_saida] = $percentualcofinspadraosaida;

			$configNF[cfop_descr_padrao_saida] = $cfopdescrpadraosaida;
			$configNF[cfop_padrao_saida] = $cfoppadraosaida;

			$configNF[lista_servico_padrao] = $listaservicopadrao;
			$configNF[cst_icsqn_servico_padrao] = $csticsqnservicopadrao;
			$configNF[percentual_issqn_servico_padrao] = $percentualissqnservicopadrao;


			$row = mpress_fetch_array(mpress_query("select count(*) cont from nf_config where Empresa_ID = '$empresaSelecionada'"));
			if ($row[cont]==0){
				$sql = "insert into nf_config (Empresa_ID, Config, Situacao_ID, Usuario_Cadastro_ID)
										values ('$empresaSelecionada','".serialize($configNF)."', 1, '".$dadosUserLogin[userID]."')";
			}
			else{
				$sql = "update nf_config set Config = '".serialize($configNF)."' where Empresa_ID = '$empresaSelecionada'";
			}
			//echo $sql;
			mpress_query($sql);
			echo "Empresa salva com sucesso";
		}
	}

?>