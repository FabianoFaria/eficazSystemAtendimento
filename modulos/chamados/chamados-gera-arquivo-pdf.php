<?php
	include("functions.php");
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");
    require_once('../../includes/barcode/barcode.inc.php');

	$clienteID = $_GET['cliente'];
	$documentoID = $_GET['documento'];
	$nomeArquivoOriginal = $_GET['nomeArquivo'];

	/* endereços*/
	$pulaLinha = "";
	$query = mpress_query("Select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF from cadastros_enderecos where cadastro_id = $clienteID and Situacao_ID = 1");
	while($row = mpress_fetch_array($query)){
		$enderecos .= $pulaLinha.$row[Logradouro].", ".$row[Numero]." ".$row[Complemento]." - ".$row[Bairro]." - ".$row[Cidade]." - ".$row[UF]." - ".$row[CEP];
		$pulaLinha = "<br>";
	}
	/* endereço principal*/
	$resultado 		= mpress_query("Select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF from cadastros_enderecos where cadastro_id = $clienteID and tipo_endereco_id = 26 and Situacao_ID = 1");
	$dadosarquivo	= mpress_fetch_array($resultado);
	$enderecoPrincipal = $dadosarquivo[Logradouro].", ".$dadosarquivo[Numero]." ".$dadosarquivo[Complemento]." - ".$dadosarquivo[Bairro]." - ".$dadosarquivo[Cidade]." - ".$dadosarquivo[UF]." - ".$dadosarquivo[CEP];

	/* telefones*/
	$barra = "";
	$query = mpress_query("select Telefone from cadastros_telefones where Cadastro_ID = 1 and Situacao_ID = 1");
	while($row = mpress_fetch_array($query)){
		$telefones .= $barra.$row[Telefone];
		$barra = "&nbsp;/&nbsp;";
	}

	/* telefone - comercial */
	$barra = "";
	$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $clienteID and Tipo_Telefone_ID = 27");
	while($row = mpress_fetch_array($query)){
		$telefoneComercial .= $barra.$row[Telefone];
		$barra = "&nbsp;/&nbsp;";
	}

	/* telefone - celular */
	$barra = "";
	$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $clienteID and Tipo_Telefone_ID = 28");
	while($row = mpress_fetch_array($query)){
		$telefoneCelular .= $barra.$row[Telefone];
		$barra = "&nbsp;/&nbsp;";
	}

	/* telefone - residencial */
	$barra = "";
	$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $clienteID and Tipo_Telefone_ID = 29");
	while($row = mpress_fetch_array($query)){
		$telefoneResidencial .= $barra.$row[Telefone];
		$barra = "&nbsp;/&nbsp;";
	}



	$resultado 		= mpress_query("select titulo, texto_documento,Cabecalho_Rodape from cadastros_documentos where Documento_ID = $documentoID");
	$dadosarquivo 	= mpress_fetch_array($resultado);
	$titulo 		= $dadosarquivo['titulo'];
	$background		= $dadosarquivo['Cabecalho_Rodape'];
	$conteudo 		= $dadosarquivo['texto_documento'];
	$resultado 		= mpress_query("select codigo, nome, email, foto, cpf_cnpj, rg, observacao from cadastros_dados where Cadastro_ID = $clienteID");
	$dadosarquivo 	= mpress_fetch_array($resultado);

	$conteudo		= str_replace('[cabecalho]', '<img src="../../images/documentos/cabecalho.jpg">', $conteudo);
	$conteudo		= str_replace('[rodape]', '<img src="../../images/documentos/rodape.jpg">', $conteudo);
	$conteudo		= str_replace('[data-extenso]', dataDia(date('d/m/Y')), $conteudo);
	$conteudo		= str_replace('[data]', date('d/m/Y'), $conteudo);

	$conteudo		= str_replace('[nome]', utf8_encode($dadosarquivo['nome']), $conteudo);
	$conteudo		= str_replace('[codigo]',$dadosarquivo['codigo'], $conteudo);
	$conteudo		= str_replace('[email]', utf8_encode($dadosarquivo['email']), $conteudo);
	$conteudo		= str_replace('[foto]', '<img src="../../uploads/'.utf8_encode($dadosarquivo['foto']).'">', $conteudo);
	$conteudo		= str_replace('[logo]', '<img src="../../images/documentos/logo.jpg">', $conteudo);
	$conteudo		= str_replace('[cpf_cnpj]', $dadosarquivo[cpf_cnpj], $conteudo);
	$conteudo		= str_replace('[rg]', $dadosarquivo[rg], $conteudo);
	$conteudo		= str_replace('[observacao]', utf8_encode($dadosarquivo[observacao]), $conteudo);
	$conteudo		= str_replace('[enderecos]', utf8_encode($enderecos), $conteudo);
	$conteudo		= str_replace('[endereco-principal]', utf8_encode($enderecoPrincipal), $conteudo);
	$conteudo		= str_replace('[telefones]', $telefones, $conteudo);
	$conteudo		= str_replace('[telefone-comercial]', $telefoneComercial, $conteudo);
	$conteudo		= str_replace('[telefone-residencial]', $telefoneResidencial, $conteudo);
	$conteudo		= str_replace('[telefone-celular]', $telefoneCelular, $conteudo);

	if((string) strpos($conteudo, '[barcode]') != ""){
		for($i=strlen($clienteID);$i<=18;$i++) $codigobarras .= "0";
	    new barCodeGenrator("$codigobarras$clienteID",1,"../../uploads/barcode.gif");
		$conteudo	= str_replace('[barcode]', '<img src="../../uploads/barcode.gif" Style="width:150px">', $conteudo);
	}
	$nomeCliente	= retiraCaracteresEspeciais($dadosarquivo['nome']);
	$nomeArquivo 	= $clienteID.$documentoID."_".retiraCaracteresEspeciais($titulo)."_".$nomeCliente."-".date('ymdhis').".pdf";

	geraPDF($nomeArquivo, $conteudo, $background);
	vinculaArquivoCadastro($nomeArquivo, $clienteID, $documentoID, 55, $conteudo, $nomeArquivoOriginal);
?>