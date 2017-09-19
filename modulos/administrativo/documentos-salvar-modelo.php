<?php
	include("functions.php");
	global $caminhoSistema;

	if($_POST['action-excluir-documento'] == "excluir"){
		mpress_query("update cadastros_documentos set Situacao_ID = 2 where Documento_ID = '".$_POST['documento-id']."'");
	}
	else{
		if ($_POST['documentos-disponiveis']=='-1'){
			mpress_query("insert into cadastros_documentos(titulo, Slug_Modulo, Cabecalho_Rodape)values('".utf8_decode($_POST['nome-documento'])."','".$_POST['destino-documento']."','CR')");
			$documentoID = mpress_identity();
		}
		else{
			$cabecalhoRodape = $_POST['chkCabecalho'].$_POST['chkRodape'];
			$textoDocumento = utf8_decode(str_replace("'","&#39;",$_POST['detalhes-documento']));
			mpress_query("update cadastros_documentos set Texto_Documento = '".$textoDocumento."', Titulo = '".utf8_decode($_POST['edita-nome-documento'])."', Cabecalho_Rodape = '$cabecalhoRodape', Slug_Modulo = '".$_POST['destino-documento']."' where Documento_ID = '".$_POST['documentos-disponiveis']."'");
			$documentoID = $_POST['documentos-disponiveis'];
		}
	}
	echo $documentoID;
?>