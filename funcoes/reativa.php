<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");

	$tipoLixeira = $_POST['tipo-lixeira'];

	if ($tipoLixeira='tipos'){
		$slug = $_POST['slug'];
		$tipoID = $_POST['tipoID'];
		mpress_query("update tipo set Situacao_ID = 1 where Tipo_ID = $tipoID");
		header("location:$slug");
	}
	else{
		$tipoID = $_POST['tipoID'];
		$slug 	= $_POST['slug'];
		$principal 	= $_POST['principal'];
		$secundario = $_POST['secundario'];
		mpress_query("update modulos_campos_detalhes set Situacao_ID = 1 where Campo_Detalhe_ID = $tipoID");
?>
		<form action='<?php echo $slug?>' method='post' name='retorno'>
			<input type='hidden' name='vinculo-principal'  value='<?php echo $principal?>'>
			<input type='hidden' name='vinculo-secundario' value='<?php echo $secundario?>'>
		</form>
		<script>document.retorno.submit();</script>
<?php
	}
?>
