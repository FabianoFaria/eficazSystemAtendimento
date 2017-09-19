<?php
	error_reporting(0);
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");

	$nomeCampo = $_GET["nome-campo"];
	$descricao = utf8_decode($_POST["texto-cadastro-localiza-".$nomeCampo]);
	$idAtual = $_POST[$nomeCampo];
	$sql = "select Cadastro_ID, Descr_Tipo, Nome, Cpf_Cnpj, Codigo
				from cadastros_dados cd
				inner join tipo tp on Tipo_ID = Tipo_Pessoa and Tipo_Grupo_ID = 8
				where cd.Situacao_ID = 1 and cd.Cadastro_ID > 0
				and (Nome like '%$descricao%' or Nome_Fantasia like '%$descricao%' or Cpf_Cnpj like '%$descricao%' or Codigo like '%$descricao%')
				order by Nome, Nome_Fantasia Limit 100";
	$resultado = mpress_query($sql);
	$i = 0;
	echo "<table width='100%' cellpadding='1' cellspacing='2' border='0'>
			<tr>
				<td class='fundo-escuro-titulo' width='10%'>ID</td>
				<td class='fundo-escuro-titulo' width='10%'>C&oacute;digo</td>
				<td class='fundo-escuro-titulo' width='40%'>Nome</td>
				<td class='fundo-escuro-titulo' width='20%'>Cpf / Cnpj</td>
				<td class='fundo-escuro-titulo' width='20%'>Tipo</td>
			</tr>";
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$estiloSelecionado = "";
		if ($idAtual==$row[Cadastro_ID])
			$estiloSelecionado = "style='background-color:#cccccc'";
		echo "	<tr style='cursor:pointer;' class='link seleciona-cadastro-geral' cadastro-id='".$row[Cadastro_ID]."' nome-campo='".$nomeCampo ."' >
					<td $estiloSelecionado class='titulo-secundario'>".$row[Cadastro_ID]."</td>
					<td $estiloSelecionado class='titulo-secundario'>".$row[Codigo]."</td>
					<td $estiloSelecionado class='titulo-secundario'>".utf8_encode($row[Nome])."</td>
					<td $estiloSelecionado class='titulo-secundario'>".$row[Cpf_Cnpj]."</td>
					<td $estiloSelecionado class='titulo-secundario'>".utf8_encode($row[Descr_Tipo])."</td>
				</tr>";
	}
	if ($i==0){
		echo "	<tr><td class='titulo-secundario'><p style='margin:2px 5px 0 50px; text-align:left; color:red;'>Nenhum cadastro encontrado</p></td></tr>";
	}
	echo "</table>";
?>