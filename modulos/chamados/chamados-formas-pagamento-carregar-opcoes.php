
<?php
	include("functions.php");
	echo carregarFormaPagamentoOrcamento($_GET['proposta-id'],$_GET['forma-pagamento'], $_GET['valor-total-geral-geral']);
?>