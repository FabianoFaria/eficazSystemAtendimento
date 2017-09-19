<?php
include('functions.php');


if ($_POST){
	$campanhaID = $_POST['localiza-campanha-id'];
	$campanha = $_POST['localiza-campanha'];
	$tipoCampanha = $_POST['localiza-tipo-campanha'];
	$operacaoID = $_POST['localiza-operacao-id'];
	$situacaoID = $_POST['localiza-situacao-id'];
}
else{
	$situacaoID = 161;
}


echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Filtros de pesquisa
						<input type='button' value='Incluir nova Campanha' class='inc-alt-campanha' campanha-id='' style='width:150px;'>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='width:5%; float:left;'>
						<p>ID</p>
						<p><input type='text' name='localiza-campanha-id' id='localiza-campanha-id' class='formata-numero' value='$campanhaID' style='width:80%;'></p>
					</div>
					<div class='titulo-secundario' style='width:35%; float:left;'>
						<p>Nome Campanha</p>
						<p><input type='text' name='localiza-campanha' id='localiza-campanha' value='$campanha' style='width:97%;'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%;'>
						<p>Opera&ccedil;&atilde;o</p>
						<p>
							<select name='localiza-operacao-id' id='localiza-operacao-id'>
								<option value=''>&nbsp;</option>
								".optionValueOperacoes($operacaoID, '')."
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='float:left; width:15%;'>
						<p>Tipo Campanha</p>
						<p><select name='localiza-tipo-campanha' id='localiza-tipo-campanha'>".optionValueGrupo(67, $tipoCampanha, '&nbsp;', '')."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:15%;'>
						<p>Situa&ccedil;&atilde;o Campanha</p>
						<p><select name='localiza-situacao-id' id='localiza-situacao-id'>".optionValueGrupo(68, $situacaoID, '&nbsp;', '')."</select></p>
					</div>


					<div class='titulo-secundario' style='float:left; width:10%;' align='right'>
						<p><input type='button' class='botao-localizar-campanha' value='Pesquisar' style='margin-top:15px; width:90%;'/></p>
					</div>

				</div>
			</div>";

echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Campanhas Cadastradas

					</p>
				</div>
				<div class='conteudo-interno'>";

	//if($_POST['ordena-tabela'] != ""){
	//	$ordem = " ORDER BY tc.Nome, Operador, ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	//}else{
	//	$ordem = " ";
	//}
	echo "<input type='hidden' id='campanha-id' name='campanha-id' value=''>";

	/*
	$sql = "SELECT tc.Campanha_ID, tc.Operacao_ID, tc.Nome, tc.Formulario_ID, tc.Situacao_ID, tc.Data_Cadastro, tc.Usuario_Cadastro_ID, s.Descr_Tipo as Situacao
				FROM tele_campanhas tc
				inner join tipo s where s.Tipo_ID = tc.Situacao_ID
				$ordem";
	echo $sql;
	*/


	if ($campanhaID!="") $sqlCond = " and tc.Campanha_ID = '$campanhaID' ";
	if ($campanha!="") $sqlCond = " and tc.Nome like '%$campanha%' ";
	if ($tipoCampanha!="") $sqlCond = " and tc.Tipo_Campanha_ID = '$tipoCampanha' ";
	if ($operacaoID!="") $sqlCond = " and top.Operacao_ID = '$operacaoID' ";
	if ($situacaoID!="") $sqlCond = " and tc.Situacao_ID = '$situacaoID' ";


	$sql = "select tc.Campanha_ID, tc.Operacao_ID, tc.Nome, tc.Formulario_ID, tc.Situacao_ID,
					tc.Data_Cadastro, tc.Usuario_Cadastro_ID, s.Descr_Tipo AS Situacao, cd.Nome AS Operador,
					top.Nome as Operacao, tpop.Descr_Tipo as Tipo_Campanha
			from tele_campanhas tc
			inner join tipo s ON s.Tipo_ID = tc.Situacao_ID
			inner join tele_operacoes top on top.Operacao_ID = tc.Operacao_ID
			left join tipo tpop on tpop.Tipo_ID = tc.Tipo_Campanha_ID
			left join tele_campanhas_operadores tco ON tco.Campanha_ID = tc.Campanha_ID and tco.Situacao_ID = 1
			left join cadastros_dados cd ON cd.Cadastro_ID = tco.Operador_ID
			where tc.Campanha_ID > 0
					$sqlCond
			order by tc.Nome, tc.Campanha_ID, Operador, tc.Operacao_ID";

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		if ($campanhaIDAnt!=$row[Campanha_ID]){
			if ($classe == "tabela-fundo-escuro")
				$classe = "tabela-fundo-claro";
			else
				$classe = "tabela-fundo-escuro";

			$i++;
			$nome = $row[Nome];
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-campanha link' campanha-id='".$row[Campanha_ID]."'>".$row[Campanha_ID]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-campanha link' campanha-id='".$row[Campanha_ID]."'>".$row[Nome]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Operacao]."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Tipo_Campanha]."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Situacao]."</p>";
			$dados[colunas][classe][$i] = $classe;
		}
		if ($row[Operador]!=''){
			$i++;
			$dados[colunas][classe][$i] = $classe;
			$dados[colunas][colspan][$i][1] = "5";
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left; width:10%'>&nbsp;</p><p Style='margin:2px 5px 0 5px; width:80%' campanha-id='".$row[Campanha_ID]."'>".$row[Operador]."</p>";
		}
		$campanhaIDAnt = $row[Campanha_ID];
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma campanha cadastrada</p>";
	}
	else{
		$largura = "100%";
		$dados[colunas][tamanho][1] = "width='6%'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Campanha";
		$dados[colunas][titulo][3] 	= "Opera&ccedil;&atilde;o";
		$dados[colunas][titulo][4] 	= "Tipo Campanha";
		$dados[colunas][titulo][5] 	= "Situa&ccedil;&atilde;o";

		/*
		$dados[colunas][ordena][1] = "Campanha_ID";
		$dados[colunas][ordena][2] = "Nome";
		$dados[colunas][ordena][3] = "Situacao_ID";
		*/
		geraTabela($largura,5,$dados, null, 'campanha-localiza', 2, 2, '','');
	}

	echo "		</div>
			</div>";
?>