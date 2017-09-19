<?php
	include("functions.php");
	
	if ($_GET['tipo-faixa']=="simples"){
		salvarTabelaPrecoVariacaoSimples();
	}		
	else{
		if ($_GET['tipo-faixa']=="")
			salvarTabelaPrecoVariacao();
		if (($_GET['tipo-faixa']=="143") || ($_GET['tipo-faixa']=="144"))
			salvarTabelaPrecoVariacaoFaixa();
	}
?>