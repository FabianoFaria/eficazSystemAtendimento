<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");
	$id 		= $_GET['id'];
	$bloco  	= $_GET['bloco'];
	$slug  		= $_GET['slug'];
	$principal 	= $_GET['principal'];
	$secundario = $_GET['secundario'];
	mpress_query("update modulos_campos_vinculos set Situacao_ID = 2 where Vinculo_Campo_ID = $id");
?>
	<form action='<?php echo $slug?>' method='post' name='retorno'>
		<input type='hidden' name='vinculo-principal'  value='<?php echo $principal?>'>
		<input type='hidden' name='vinculo-secundario' value='<?php echo $secundario?>'>
	</form>
	<script>document.retorno.submit();</script>