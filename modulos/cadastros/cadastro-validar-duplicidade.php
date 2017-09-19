<?php
	include("functions.php");
	$campo = soNumeros($_GET['campo']);
	$cadastroID = $_GET['cadastro-id'];
	if ($cadastroID!="")
		$condicao = " and Cadastro_ID <> $cadastroID";
	$sql = "select Cadastro_ID from cadastros_dados where Cpf_Cnpj = '$campo' $condicao";
	$resultado = mpress_query($sql);
	while ($row = mpress_fetch_array($resultado))
		$ids .= $row[Cadastro_ID].",";

	echo substr($ids,0,-1);
?>