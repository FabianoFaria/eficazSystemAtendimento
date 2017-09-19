<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
include('functions.php');
echo carregarMotivoCampanha($_GET['campanha-id'], $_GET['motivo-campanha-id']);
?>