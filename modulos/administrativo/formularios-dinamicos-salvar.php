<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
include("functions.php");
global $dadosUserLogin;
$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

$formularioID = $_POST['formulario-id'];
$nome = $_POST['nome'];
$tabelaEstrangeira = $_POST['tabela-estrangeira'];
if ($formularioID==''){
	$sql = "INSERT INTO formularios
				(Nome, Tabela_Estrangeira, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
			VALUES
				('$nome', '$tabelaEstrangeira', 1, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
	mpress_query($sql);
	$formularioID = mpress_identity();
}
else{
	$sql = "UPDATE formularios SET Nome = '$nome', Tabela_Estrangeira = '$tabelaEstrangeira' where Formulario_ID = '$formularioID'";
	mpress_query($sql);
}
echo $formularioID;

?>