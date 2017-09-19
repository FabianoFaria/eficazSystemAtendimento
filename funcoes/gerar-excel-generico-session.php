<?php
session_start();
header("Content-type: application/vnd.ms-excel");
header("Content-type: application/force-download");
header("Content-Disposition: attachment; filename=".$_POST["nome-relatorio"]."_".date('Ymd').".xls");
header("Pragma: no-cache");
echo $_SESSION["session-conteudo-relatorio"];
//unset($_SESSION["session-conteudo-relatorio"])
?>