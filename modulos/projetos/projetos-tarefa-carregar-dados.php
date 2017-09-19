<?php

header("Content-Type: text/html; charset=ISO-8859-1",true);

include("functions.php");

echo carregarTarefaDetalhes($_GET['tarefa-id']);

?>
