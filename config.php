<?php
	//exit('ERRO - Contate o administrador do sistema');
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');

	//header("location:configuracoes-iniciais.php");
	//header("location:manutencao.php");
	global $conn, $caminhoSistema, $tituloSistema, $descricaoSistema, $tipoBaseDados, $dadosUserLogin, $modulosAtivos, $modulosGeral;

	// $caminhoSistema 		= "http://sistema.eficazsystem.com.br";
	// $caminhoFisico 		= "/home/storage/6/ca/a4/eficazsystem3/public_html/sistema";
	// $tituloSistema 		= "Eficaz System - Helpdesk";
	// $descricaoSistema 	= "";
	// $dataExpira		 	= "01/01/2030";

	$caminhoSistema 	= "http://eficazsystem.com.localsistema";
	$caminhoFisico 		= "C:/wamp64/www/sistemaEficaz";
	$tituloSistema 		= "Eficaz System - LocalHelpdesk";
	$descricaoSistema 	= "Backup do sistema de atendimento da Eficaz System";
	$dataExpira		 	= "01/01/2030";


	// $conn = mysql_connect("mysql01.eficazsystem3.hospedagemdesites.ws","eficazsystem3","fpinfo2981")or die("Nao foi possivel conectar o BD.");
	// mysql_select_db("eficazsystem3") or die("Nao foi possivel selecionar o BD.");


	$conn = mysql_connect("localhost","root","")or die("Nao foi possivel conectar o BD.");
	mysql_select_db("eficazsystem3") or die("Nao foi possivel selecionar o BD.");

	function mpress_query($query){
		global $conn;
		$query = mysql_query($query, $conn);
		return $query;
	}
	function mpress_fetch_array($query){
		global $conn;
		$row = mysql_fetch_array($query);
		return $row;
	}
	function mpress_fetch_object($query){
		global $conn;
		$row = mysql_fetch_object($query);
		return $row;
	}
	function mpress_identity(){
		global $conn;
		return mysql_insert_id();
	}
	function mpress_num_fields($resultSet){
		return mysql_num_fields($resultSet);
	}
	function mpress_count($query){
		global $conn;
		return mysql_num_rows($query);
	}
	$dadosUserLogin = $_SESSION['dadosUserLogin'];
	$modulosAtivos = $_SESSION['modulosAtivos'];
	$modulosGeral = $_SESSION['modulosGeral'];

	$_SESSION['objeto'] = "Chamado";
?>
