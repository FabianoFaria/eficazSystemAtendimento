<?php
	header("Content-type: text/html; charset=iso-8859-1");
	require_once('../../../wp-load.php');
?>
<br><br>
	<div id='pagina404' Style='text-align:center;'>
		<div Style='color:red;'><b>Erro ao conectar PagSeguro</b></div>
		<br><br><?php echo $_GET['erro'];?>
	</div>