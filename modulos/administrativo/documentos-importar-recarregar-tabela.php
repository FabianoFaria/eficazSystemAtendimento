<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
include('functions.php');
$origem = $_POST['origem-documento'];
echo carregarTabelaTemporariaImportada($origem);
?>