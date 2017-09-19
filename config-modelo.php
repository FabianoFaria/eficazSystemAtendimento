	//error_reporting(E_ERROR);
	//ini_set('display_errors', 'On');

	//header("location:configuracoes-iniciais.php");
	//header("location:manutencao.php");
	global $conn, $caminhoSistema, $tituloSistema, $descricaoSistema, $tipoBaseDados, $dadosUserLogin, $modulosAtivos, $modulosGeral;

	$caminhoSistema 	= "[caminho-sistema]";
	$caminhoFisico 		= "[caminho-fisico]";
	$tituloSistema 		= "[titulo-sistema]";
	$descricaoSistema 	= "[descricao-sistema]";
	$dataExpira		 	= "[data-expira]";

	[connect]
	[select_db]



	function mpress_query($query){
		global $conn;
		$query = [query]($query, $conn);
		return $query;
	}
	function mpress_fetch_array($query){
		global $conn;
		$row = [fetch_array]($query);
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

	$_SESSION['objeto'] = "[objeto]";