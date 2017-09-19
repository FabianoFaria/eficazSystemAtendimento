<?php
include("functions.php");
$igrejaID = $_POST['cadastro-id-de'];
$quantidade = $_POST['quantidade-membros'];
$dataLancamento = formataDataBD($_POST['data-lancamento-membros']);
salvarLancamentoMembros($igrejaID, $quantidade, $dataLancamento);
?>
