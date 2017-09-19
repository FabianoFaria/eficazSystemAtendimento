<?php
header("Cache-Control: no-cache");
header("Expires: -1");
header("Pragma: no-cache");
header("Content-Type: text/html; charset=ISO-8859-1",true);
include("../../config.php");
include("../../includes/functions.gerais.php");

$anexoID = $_GET['anexo-id'];
$sql = "Select Observacao from modulos_anexos where Anexo_id = '$anexoID'";
$resultado = mpress_query($sql);
if($rs = mpress_fetch_array($resultado)){
	echo utf8_decode($rs['Observacao']);
}
?>