<?php
	session_start();
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	global $caminhoSistema;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
<?php
		get_header();
		if ($_GET['modulo']!="") echo "\n <script type='text/javascript' src='$caminhoSistema/modulos/".$_GET['modulo']."/".$_GET['modulo'].".js'></script>";
?>
		</head>
		<body>
		<form name='form-lista-cadastros' id='form-lista-cadastros' method='post'>
			<div id='cadastro-lista' style='width:95%' class='iframe-interno'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Cadastros</p>
					</div>
					<div class='conteudo-interno'>
<?php
	$ids = $_GET['ids'];
	if ($ids != ""){ $sqlCond .= " and cd.Cadastro_ID in ($ids)";}
	if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) or cd.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."')";
	if($_SESSION[dadosUserLogin][grupoID] == -3) $sqlCond .= " and cd.Cadastro_ID = ".$_SESSION[dadosUserLogin][userID]." ";
	echo "<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
	$sql = "select cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, tc.Descr_Tipo as Tipo_Cadastro_Descr, Nome, Nome_Fantasia, Senha, Data_Nascimento, Cpf_Cnpj,
			Inscricao_Municipal, Inscricao_Estadual, cd.Usuario_Cadastro_ID, Codigo, coalesce(cf.Telefone,'') as Telefone, cf.Observacao, cd.Email as Email, cd.Situacao_ID as Situacao_ID
			from cadastros_dados cd
			left join cadastros_telefones cf on cf.Cadastro_ID = cd.Cadastro_ID and cf.Situacao_ID = 1
			left join tipo tp on Tipo_ID = Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
			left join tipo tc on tc.Tipo_ID = Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
			where cd.Situacao_ID <> 2
			and cd.Cadastro_ID > 0
			$sqlCond $ordem";
	//echo $sql;
	$cadastroIDAnt = "";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($row[Cadastro_ID] != $cadastroIDAnt){
			if ($row[Situacao_ID]==3) $classe = "lixeira"; else $classe = "link";
			$i++;
			$nome = $row[Nome];
			$dados[colunas][conteudo][$i][1] = "<span class='$classe cadastro-lista' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p></span>";
			$dados[colunas][conteudo][$i][2] = "<span class='$classe cadastro-lista' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p></span>";
			$dados[colunas][conteudo][$i][3] = "<span class='$classe cadastro-lista' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$nome."</p></span>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left; white-space:nowrap;'>".$row[Cpf_Cnpj]."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Email]."</p>";
			$telefones = "";
		}
		$telefones .= "<span Style='margin:2px 5px 0 5px;float:left;'>".$row['Telefone']."</span>";
		$dados[colunas][conteudo][$i][6] = $telefones;
		$cadastroIDAnt = $row[Cadastro_ID];
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
	}
	else{
		$largura = "99%";
		$colunas = "6";
		$dados[colunas][tamanho][1] = "width='6%'";
		$dados[colunas][tamanho][2] = "width='10%'";
		$dados[colunas][tamanho][3] = "";
		$dados[colunas][tamanho][4] = "";
		$dados[colunas][tamanho][5] = "";
		$dados[colunas][tamanho][6] = "";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "C&oacute;digo";
		$dados[colunas][titulo][3] 	= "Nome";
		$dados[colunas][titulo][4] 	= "Cpf / Cnpj";
		$dados[colunas][titulo][5] 	= "Email";
		$dados[colunas][titulo][6] 	= "Telefones";
		geraTabela($largura,$colunas,$dados, null, 'cadastro-lista', 2, 2, "","");
	}
?>
					</div>
				</div>
			</div>
			</center>
		</form>
		</body>
	</html>