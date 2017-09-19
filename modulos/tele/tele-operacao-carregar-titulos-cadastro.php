<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
include('functions.php');
echo carregarTitulosCadastro($_GET['cadastro-id']);
?>