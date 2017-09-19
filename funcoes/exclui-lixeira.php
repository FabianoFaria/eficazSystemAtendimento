<?php
	require_once("../includes/functions.gerais.php");
	require_once("../config.php");

	$tipoLixeira  = $_POST['tipo-lixeira'];

	$tipoID  = $_POST['tipoID'];
	$slug 	 = $_POST['slug'];
	$slugLixeira = $_POST['slug-lixeira'];
	$lixeira = $_POST['lixeira'];
	$tipoLixeira = $_POST['tipo-lixeira'];

	//echo "...:".$tipoLixeira;
	if ($tipoLixeira=='tipos'){
		mpress_query("update tipo set Situacao_ID = 2 where Tipo_ID = $tipoID");
	}
?>
<form action='<?php echo $slugLixeira;?>' name='frmDefault' method='post'>
	<input type='hidden' name='id-lixeira' value='<?php echo $lixeira;?>'>
	<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='<?php echo $tipoLixeira;?>'>
	<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='<?php echo $tipoLixeira;?>'>
	<input type='hidden' name='slug' id='slug' value='<?php echo $slug;?>'>
</form>
<script>document.frmDefault.submit();</script>
