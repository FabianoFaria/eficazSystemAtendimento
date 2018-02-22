<?php header("Content-Type: text/html; charset=ISO-8859-1",true) ?>
<meta http-equiv="Content-type" content="text/html; charset=ISO-8859-1" />
<?php
	include("functions.php");
	global $configChamados;
	$propostaID = $_POST['proposta-id'];

	orcamentoProdutosPropostaSalvar();

	if ($configChamados['listagem-orcamento']=="completa"){
		carregarProdutosOrcamentoCompleta($propostaID);
		carregarSituacaoProposta($propostaID);
	}
	if ($configChamados['listagem-orcamento']=="procura"){
		carregarProdutos($propostaID,'orcamento');
		carregarSituacaoProposta($propostaID);


	}

	echo "	<div id='bloco-follows-proposta-$proposta' style='float:left; width:100%; margin-top:10px;'>";
	carregarFollowsOrcamentosPropostas($propostaID);

	//echo "<input type='hidden' id='resultadoProposta' value='".$resultadoProposta."' />";

	// var_dump($resultadoProposta);

	// die();

	echo "	</div>";
	echo "	<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
?>