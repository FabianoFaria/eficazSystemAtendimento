<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
include("functions.php");
global $dadosUserLogin;
$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

$formularioID = $_POST['formulario-dinamico-id'];
$tabelaEstrangeira = $_POST['tabela-estrangeira-formulario-dinamico'];
$chaveEstrangeira = $_POST['chave-estrangeira-formulario-dinamico'];

//$arr = array_map('utf8_decode', $_POST['cfd']);
$arr = $_POST['cfd'];
/*
echo "<pre>";
print_r($_POST['cfd']);
print_r($arr);
echo "</pre>";
*/

$dados = serialize($arr);

$sql = "UPDATE formularios_respostas SET Situacao_ID = 2
					where Chave_Estrangeira = '$chaveEstrangeira'
					and Tabela_Estrangeira = '$tabelaEstrangeira'";
mpress_query($sql);

$sql = "INSERT INTO formularios_respostas
				(Formulario_ID, Chave_Estrangeira, Tabela_Estrangeira, Respostas, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
		VALUES
				($formularioID, '$chaveEstrangeira', '$tabelaEstrangeira', '$dados', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
mpress_query($sql);
//$formularioID = mpress_identity();
?>