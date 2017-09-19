<?php
	if($_POST){
		$id = $_POST['localiza-cadastro-id'];
		$codigo = $_POST['localiza-codigo'];

		//$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-tipo-cadastro-id']); $i++){
			$tiposCadastros .= $virgula.$_POST['localiza-tipo-cadastro-id'][$i];
			$virgula = ",";
		}

		$cargos = $_POST['localiza-cargos'];
		$dataInicio = $_POST['data-inicio'];
		$dataFim = $_POST['data-fim'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-vinculos-cadastros']); $i++){
			$vinculosCadastros .= $virgula.$_POST['localiza-vinculos-cadastros'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-vinculos']); $i++){
			$vinculos .= $virgula.$_POST['localiza-vinculos'][$i];
			$virgula = ",";
		}
	}
	else{
		$situacao = "1";
		$dataInicio = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFim = $ultimo_dia."/".date("m/Y");
		$classeEsconde = "esconde";
	}

	$sql = "select distinct cd.Cadastro_ID, cd.Nome as Nome from cadastros_vinculos cv
			inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID and cd.Empresa = 1
			where cv.Situacao_ID = 1 and cd.Situacao_ID = 1
			order by cd.Nome ";
	$resultado = mpress_query($sql);
	$v = 0;
	while($row = mpress_fetch_array($resultado)){
		$v++;
		$arrayVinculosCadastros[$v]['value'] = $row[Cadastro_ID];
		$arrayVinculosCadastros[$v]['descricao'] = $row[Nome];
	}

	$sql = "select distinct cv.Tipo_Vinculo_ID, t.Descr_Tipo as Vinculo from cadastros_vinculos cv
		inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID and cd.Empresa = 1
		inner join tipo t on cv.Tipo_Vinculo_ID = t.Tipo_ID
		where cv.Situacao_ID = 1 and cd.Situacao_ID = 1 and t.Situacao_ID = 1
		order by t.Descr_Tipo ";
	$resultado = mpress_query($sql);
	$v = 0;
	while($row = mpress_fetch_array($resultado)){
		$v++;
		$arrayVinculos[$v]['value'] = $row[Tipo_Vinculo_ID];
		$arrayVinculos[$v]['descricao'] = $row[Vinculo];
	}

	echo "	<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Aniversariantes'>
			<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo' style='min-height:25px'>
						<p style='margin-top:2px;'>
							Aniversariantes
						</p>
					</div>
					<div class='conteudo-interno'>

					<div class='titulo-secundario quatro-colunas'>
						<div class='div-normal'>
							<p>Entre o Periodo:</p>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-inicio' id='data-inicio' class='formata-data' style='width:92%' maxlength='10' value='$dataInicio'>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-fim' id='data-fim' class='formata-data' style='width:92%' maxlength='10' value='$dataFim'></p>
							</div>
						</div>&nbsp;
					</div>
					<div class='titulo-secundario quatro-colunas'>
						<p>Tipo Cadastro</p>
						<p><select name='localiza-tipo-cadastro-id[]' id='localiza-tipo-cadastro-id[]' multiple>".optionValueGrupoMultiplo(9, $tiposCadastros,'')."</select></p>
					</div>
					<!--
					<div class='titulo-secundario quatro-colunas'>
						&nbsp;
						<p>Cargo</p>
						<p><select name='localiza-cargos' id='localiza-cargos'>".optionValueGrupo(42, $cargos,'&nbsp;')."</select></p>
					</div>
					-->
					<div class='titulo-secundario quatro-colunas' style='height:90px'>
						<p>Tipo Vinculo</p>
						<p><select name='localiza-vinculos[]' id='localiza-vinculos[]' multiple style='height:70px'>".optionValueArrayMultiplo($arrayVinculos,$vinculos)."</select></p>
					</div>

					<div class='titulo-secundario quatro-colunas'>
						<p>Vinculadas ao Cadastro</p>
						<p><select name='localiza-vinculos-cadastros[]' id='localiza-vinculos-cadastros[]' multiple style='height:70px'>".optionValueArrayMultiplo($arrayVinculosCadastros,$vinculosCadastros)."</select></p>
					</div>
					<div style='float:right;'>
						<p><input type='button' value='Pesquisar' id='botao-pesquisar-aniver' style='width:150px;'/></p>
					</div>
				</div>
			</div>
			<input type='hidden' id='cadastroID' name='cadastroID' value=''>";
	if($_POST){
		$arrTiposCadastros = carregarArrayTipo(9);

		if ($id != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
		if ($codigo != ""){ $sqlCond .= " and cd.Codigo like '%$codigo%' ";}

		if ($tiposCadastros!=''){
			//$sqlCond .= "and (";
			//$and = "";
			for($i = 0; $i < count($_POST['localiza-tipo-cadastro-id']); $i++){
				$tipoCadastro = $_POST['localiza-tipo-cadastro-id'][$i];
				//$sqlCond .= "$and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";
				$sqlCond .= "and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";
				//$and = " and ";
			}
			//$sqlCond .= ")";
		}

		//if ($tipoCadastro != ""){ $sqlCond .= " and cd.Tipo_Cadastro like '%s:".strlen($tipoCadastro).":\"".$tipoCadastro."\"%'";}

		if ($cargos != ""){ $sqlCond .= " and cd.Cargo_ID = '$cargos'";}
		if (($dataInicio!="")&&($dataFim!="")){
			$dataInicio = "2000".substr(implode('-',array_reverse(explode('/',$dataInicio))),4,6);
			$dataFim = "2000".substr(implode('-',array_reverse(explode('/',$dataFim))),4,6);
			$sqlHaving .= " having data_aux BETWEEN '$dataInicio' AND '$dataFim' ";
		}

		if (($vinculosCadastros != "") && ($vinculos != "")){
			if ($vinculosCadastros != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID in ($vinculosCadastros) and Tipo_Vinculo_ID in ($vinculos) and Situacao_ID = 1)";}
		}
		else{
			if ($vinculosCadastros != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID in ($vinculosCadastros) and Situacao_ID = 1)";}
			if ($vinculos != ""){ $sqlCond .= " and cd.Cadastro_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Tipo_Vinculo_ID in ($vinculos) and Situacao_ID = 1)";}
		}

		/*
		if($_POST['ordena-tabela'] != ""){
			$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
		}else{
			$ordem = " order by Nome";
		}
		*/

		$sql = "SELECT Cadastro_ID AS Cadastro_ID, Nome, Data_Nascimento, Codigo, Email AS Email, Foto, DATE_FORMAT(Data_Nascimento, '%d/%m') AS Aniversario,
				 str_to_date(concat('2000-',DATE_FORMAT(Data_Nascimento, '%m-%d')),'%Y-%m-%d') as data_aux, cd.Tipo_Cadastro as Tipo_Cadastro
				FROM cadastros_dados cd
				WHERE Situacao_ID = 1 AND Tipo_Pessoa = 24 AND Cadastro_ID > 0 AND Data_Nascimento
				IS NOT NULL AND Data_Nascimento <> '0000-00-00'
				$sqlCond
				$sqlHaving
				ORDER BY data_aux";
		//echo $sql;
		$i = 0;
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$tipoCadastro = "";
			foreach((unserialize($row['Tipo_Cadastro'])) as $chave => $tipo){
				if ($arrTiposCadastros[descricao][$tipo]!=''){
					$tipoCadastro .= $arrTiposCadastros[descricao][$tipo].", ";
				}
			}

			$foto = $row[Foto];
			$nome = $row[Nome];
			$aniver = $row[Aniversario];
			if ($foto!="") $imagemFoto = "<img src='$caminhoSistema/uploads/$foto' width='30px' height='40px' id='imagem-foto'>";
			else $imagemFoto = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' width='40px' id='imagem-foto'>";
			$dados[colunas][conteudo][$i][1] = "<p stylenormal width='30px' height='40px' class='cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."' style='cursor:pointer;' Style='margin:2px 5px 0 5px;float:left;'>".$imagemFoto."</p>";
			$dados[colunas][conteudo][$i][2] = "<p stylenormal Style='margin:1px; float:left;'><b>".$nome."</b></p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 0 0 5px;float:left; font-size:10px;'>".substr($tipoCadastro,0,-2)."</p>";
			$dados[colunas][conteudo][$i][4] = "<p stylenormal Style='margin:1px;' align='center'><b>".$aniver."</b></p>";
		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum aniversariante localizado</p>";
		}
		else{
			$largura = "100.2%";
			$colunas = "4";

			$dados[colunas][extras][1] = "width='30px' height='40px'";
			$dados[colunas][tamanho][2] = "";
			$dados[colunas][tamanho][4] = "align='center'";

			$dados[colunas][titulo][1] 	= "Foto";
			$dados[colunas][titulo][2] 	= "<span stylenormal>Nome</span>";
			$dados[colunas][titulo][3] 	= "<!--<span stylenormal>Tipo</span>-->";
			$dados[colunas][titulo][4] 	= "<span stylenormal>Anivers&aacute;rio</span>";

			geraTabela($largura,$colunas,$dados, null, 'cadastro-localiza', 2, 2, 500,1);
		}
	}
?>