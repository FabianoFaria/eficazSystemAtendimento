<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
include('functions.php');
echo carregarSituacaoCampanha($_GET['campanha-id'], $_GET['situacao-campanha-id']);
?>