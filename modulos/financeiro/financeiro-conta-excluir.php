<?php
include("functions.php");
$contaID = $_POST['localiza-conta-id'];
mpress_query("DELETE FROM financeiro_produtos WHERE Conta_ID = '".$contaID."'");
mpress_query("DELETE FROM financeiro_titulos WHERE Conta_ID = '".$contaID."'");
mpress_query("DELETE FROM financeiro_contas WHERE Conta_ID = '".$contaID."'");
mpress_query("DELETE FROM financeiro_movimentacoes WHERE Conta_ID = '".$contaID."'");
mpress_query("DELETE FROM financeiro_contabil WHERE Conta_ID = '".$contaID."'");

/*
echo ("DELETE FROM financeiro_produtos WHERE Conta_ID = '".$contaID."'");
echo ("DELETE FROM financeiro_titulos WHERE Conta_ID = '".$contaID."'");
echo ("DELETE FROM financeiro_contas WHERE Conta_ID = '".$contaID."'");
*/
?>


