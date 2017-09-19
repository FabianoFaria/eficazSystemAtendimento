<?php
session_start();
global $caminhoSistema;

if ($_POST){
	$id 	= $_POST['localiza-chamado-id'];
	$codigo 	= $_POST['localiza-chamado-codigo'];
	$titulo 	= $_POST['localiza-chamado-titulo'];
	$nomeP 	= $_POST['localiza-chamado-prestador'];
	$nomeS 	= $_POST['localiza-chamado-solicitante'];
	$email = $_POST['localiza-chamado-email'];

	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-vinculos-cadastros']); $i++){
		$vinculosCadastros .= $virgula.$_POST['localiza-vinculos-cadastros'][$i];
		$virgula = ",";
	}

	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-chamado-situacao']); $i++){
		$situacoes .= $virgula.$_POST['localiza-chamado-situacao'][$i];
		$virgula = ",";
	}

	$prioridade = $_POST['localiza-chamado-prioridade'];
	$tipoChamado = $_POST['localiza-tipo-chamado'];

	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-chamado-uf-solicitante']); $i++){
		$ufSolicitante .= $virgula."'".$_POST['localiza-chamado-uf-solicitante'][$i]."'";
		$virgula = ",";
	}

	$dataInicio = $_POST['data-inicio-abertura'];
	$dataFim = $_POST['data-fim-abertura'];
	$dataInicioFinalizado = $_POST['data-inicio-finalizado'];
	$dataFimFinalizado = $_POST['data-fim-finalizado'];
	$dataInicioInteracao = $_POST['data-inicio-interacao'];
	$dataFimInteracao = $_POST['data-fim-interacao'];

	$arrTiposChamados = carregarArrayTipoTabela(19);
}
else{
	$classeEsconde = "esconde";
}


$sql = "select distinct cd.Cadastro_ID, cd.Nome as Nome
	from cadastros_vinculos cv
		inner join cadastros_dados cd on cd.Cadastro_ID = cv.Cadastro_ID and cd.Empresa <> 1
		where cv.Situacao_ID = 1 and cd.Situacao_ID = 1
		order by cd.Nome ";
$resultado = mpress_query($sql);
$v = 0;
while($row = mpress_fetch_array($resultado)){
	$v++;
	$arrayVinculosCadastros[$v]['value'] = $row[Cadastro_ID];
	$arrayVinculosCadastros[$v]['descricao'] = $row[Nome];
}
?>
<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_Dinamico'>
<input type='hidden' id='conteudo-relatorio' name='conteudo-relatorio' value=''>
<div class="titulo-container">
<div class="titulo">
	<p>Filtros de pesquisa</p>
</div>
<input type='hidden' name='workflow-id' id='workflow-id'>
<div class="conteudo-interno">
	<div class="titulo-secundario cinco-colunas">
		<div class="titulo-secundario" style='width:22%; float:left;'>
			<p>ID</p>
			<p><input type='text' name='localiza-chamado-id' id='localiza-chamado-id' class='formata-numero' value='<?php echo $id; ?>' style='width:80%;'></p>
		</div>
		<div class="titulo-secundario" style='width:77%; float:left;'>
			<p>C&oacute;digo</p>
			<p><input type='text' name='localiza-chamado-codigo' id='localiza-chamado-codigo' class='formata-campo' value='<?php echo $codigo; ?>'></p>
		</div>
	</div>
	<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:2;'>
		<p>Situa&ccedil;&atilde;o:</p>
		<select name="localiza-chamado-situacao[]" multiple id="localiza-chamado-situacao" style='height:71px;'><?php echo optionValueGrupoMultiplo(18, $situacoes);?><select>
	</div>
	<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:2;'>
		<div class="titulo-secundario" style='width:77%; float:left;'>
			<p>Solicitante:</p>
			<p><input type='text' name='localiza-chamado-solicitante' id='localiza-chamado-solicitante' class='formata-campo' value='<?php echo $nomeS; ?>'></p>
		</div>
		<div class="titulo-secundario" style='width:22%; float:left;'>
			<p>UF:</p>
			<p><select name="localiza-chamado-uf-solicitante[]" multiple style='height:71px;' id="localiza-chamado-uf-solicitante"><?php echo optionValueGrupoMultiploUF(str_replace("'","",$ufSolicitante));?><select></p>
		</div>
	</div>
	<div class="titulo-secundario cinco-colunas">
		<p>Data Abertura:</p>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicio; ?>'>&nbsp;&nbsp;</p>
		</div>
		<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFim; ?>'></p>
		</div>
	</div>

	<div class="titulo-secundario cinco-colunas">
		<p>Data Finalizado:</p>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-inicio-finalizado' id='data-inicio-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioFinalizado; ?>'>&nbsp;&nbsp;</p>
		</div>
		<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-fim-finalizado' id='data-fim-finalizado' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimFinalizado; ?>'></p>
		</div>
	</div>

	<div class="titulo-secundario cinco-colunas">
		<p>Tipo Chamado</p>
		<p><select name="localiza-tipo-chamado" id="localiza-tipo-chamado"><?php echo optionValueGrupoFilho(19, $tipoChamado, "&nbsp;");?></select></p>
	</div>

	<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:1;'>
		<p>Vinculadas ao Cadastro</p>
		<p><select name='localiza-vinculos-cadastros[]' id='localiza-vinculos-cadastros[]' multiple><?php echo optionValueArrayMultiplo($arrayVinculosCadastros,$vinculosCadastros);?>"</select></p>
	</div>
	<div class="titulo-secundario cinco-colunas" style='position:relative;z-index:1;'>&nbsp;</div>

	<div class="titulo-secundario cinco-colunas">
		<p>Prioridade:</p>
		<select name="localiza-chamado-prioridade" id="localiza-chamado-prioridade"><?php echo optionValueGrupo(21, $prioridade, "&nbsp;");?></select>
	</div>

	<div class="titulo-secundario cinco-colunas">
		<p>&Uacute;ltima Intera&ccedil;&atilde;o:</p>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-inicio-interacao' id='data-inicio-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioInteracao; ?>'>&nbsp;&nbsp;</p>
		</div>
		<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
		<div style='width:43%;float:left;'>
			<p><input type='text' name='data-fim-interacao' id='data-fim-interacao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimInteracao; ?>'></p>
		</div>
	</div>

	<div class='titulo-secundario' style='float:right;width:20%;margin-top:15px'>
		<div style='width:50%;float:left;'>
			<div class="btn-excel" id='botao-salvar-excel' style="float:right;" title="Gerar Excel"></div>&nbsp;
			<input type='hidden' name='flag-excel' id='flag-excel' value=''>
		</div>
		<div style='width:50%;float:right;'>
			<input type='button' value='Pesquisar' id='botao-localizar-chamado-relatorio' style='width:92%;margin-right:2px'/>&nbsp;
		</div>
	</div>
</div>
</div>
<?php
if($_POST){
	$i = 0;
	if($id!="") 		$condicoes .= " and cw.Workflow_ID = '$id' ";
	if($codigo!="") 	$condicoes .= " and cw.Codigo like '$codigo%' ";
	if($nomeP!="") 		$condicoes .= " and cd2.Nome like '%$nomeP%' ";
	if($nomeS!="") 		$condicoes .= " and cd1.Nome  like '%$nomeS%'";
	if($email!="") 		$condicoes .= " and (cd1.email like '$email%' or cd2.email like '$email%' )";
	if($situacoes!="") 	$condicoes .= " and cf.Situacao_ID IN ($situacoes)";
	if($prioridade!="") $condicoes .= " and cw.Prioridade_ID = '$prioridade'";

	if($titulo!="")		$condicoes .= " and upper(cw.Titulo) like upper('$titulo%')";
	if($tipoChamado!="") $condicoes .= " and cw.Tipo_WorkFlow_ID in (".$arrTiposChamados[familia][$tipoChamado].")";


	if($ufSolicitante!="") $condicoes .= " and cd1.Cadastro_ID IN (select Cadastro_ID from cadastros_enderecos where UF IN ($ufSolicitante))";

	if(($dataInicio!="")||($dataFim!="")){
		$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
		if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
		$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
		if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
		$condicoes .= " and cw.Data_Abertura between '$dataInicio' and '$dataFim' ";
	}
	if(($dataInicioFinalizado!="")||($dataFimFinalizado!="")){
		$dataInicioFinalizado = implode('-',array_reverse(explode('/',$dataInicioFinalizado)));
		if ($dataInicioFinalizado=="") $dataInicioFinalizado = "0000-00-00"; $dataInicioFinalizado .= " 00:00";
		$dataFimFinalizado = implode('-',array_reverse(explode('/',$dataFimFinalizado)));
		if ($dataFimFinalizado=="") $dataFimFinalizado = "2100-01-01"; $dataFimFinalizado .= " 23:59";
		$condicoes .= " and cw.Data_Finalizado between '$dataInicioFinalizado' and '$dataFimFinalizado' and cf.Situacao_ID = '34'";
	}
	if(($dataInicioInteracao!="")||($dataFimIneracao!="")){
		$dataInicioInteracao = implode('-',array_reverse(explode('/',$dataInicioInteracao)));
		if ($dataInicioInteracao=="") $dataInicioInteracao = "0000-00-00"; $dataInicioInteracao .= " 00:00";
		$dataFimInteracao = implode('-',array_reverse(explode('/',$dataFimInteracao)));
		if ($dataFimInteracao=="") $dataFimInteracao = "2100-01-01"; $dataFimInteracao .= " 23:59";
		$condicoes .= " and cf.Data_Cadastro between '$dataInicioInteracao' and '$dataFimInteracao'";
	}
	if ($vinculosCadastros!=''){
			$condicoes .= " and cw.Solicitante_ID in (select distinct Cadastro_Filho_ID from cadastros_vinculos where Cadastro_ID in ($vinculosCadastros) and Situacao_ID = 1)";
	}

	$sql = "select cw.Workflow_ID, cw.Codigo as Codigo_Chamado,
					tw1.Tipo_ID as Tipo_ID5, tw1.Descr_Tipo as Tipo5,
					tw2.Tipo_ID as Tipo_ID4, tw2.Descr_Tipo as Tipo4,
					tw3.Tipo_ID as Tipo_ID3, tw3.Descr_Tipo as Tipo3,
					tw4.Tipo_ID as Tipo_ID2, tw4.Descr_Tipo as Tipo2,
					tw5.Tipo_ID as Tipo_ID1, tw5.Descr_Tipo as Tipo1,
					cd1.Nome as Solicitante, cd1.Codigo as Codigo_Solicitante, cd1.email as Email_Solicitante, cd2.Nome as Prestador,
					cd2.email as Email_Prestador, t.Descr_Tipo as Situacao, tr.Descr_Tipo as Regional,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y %H:%i') as Data_Abertura,
					DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y %H:%i') as Data_Finalizado,
					DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y %H:%i') as Data_Interacao,
					coalesce(sum(cwp.Valor_Custo_Unitario * cwp.Quantidade),0) as Total_Custo,
					coalesce(sum(cwp.Valor_Venda_Unitario * cwp.Quantidade),0) as Total_Cobranca,
					cf.Descricao as Descricao_Follow, coalesce(upper(ce.UF),'') as UF, ce.Cidade as Cidade, r.Nome as Responsavel,
					coalesce((select sum(Quantidade) from chamados_workflows_produtos where Produto_Variacao_ID in (4) and Workflow_ID = cw.Workflow_ID and Situacao_ID = 1),0) as Total_KM,
					coalesce((select sum(Quantidade) / 2 from chamados_workflows_produtos where Produto_Variacao_ID in (1142) and Workflow_ID = cw.Workflow_ID and Situacao_ID = 1),0) +
					coalesce((select sum(Quantidade) / 4 from chamados_workflows_produtos where Produto_Variacao_ID in (1144) and Workflow_ID = cw.Workflow_ID and Situacao_ID = 1),0) +
					coalesce((select sum(Quantidade) from chamados_workflows_produtos where Produto_Variacao_ID in (1145) and Workflow_ID = cw.Workflow_ID and Situacao_ID = 1),0) as Total_Horas
			from chamados_workflows cw
			inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
			left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
			left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
				and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
			left join tipo t on t.Tipo_ID = cf.Situacao_ID
			left join chamados_workflows_produtos cwp on cwp.Workflow_ID = cw.Workflow_ID and cwp.Situacao_ID = 1
			left join tipo tr on tr.Tipo_ID = cd1.Regional_ID
			left join cadastros_enderecos ce on cw.Solicitante_ID = ce.Cadastro_ID and ce.Tipo_Endereco_ID = 26 and ce.Cadastro_Endereco_ID = (select ceaux.Cadastro_Endereco_ID from cadastros_enderecos ceaux where ceaux.Cadastro_ID = ce.Cadastro_ID limit 1)
			left join cadastros_dados r on r.Cadastro_ID = cw.Responsavel_ID
			LEFT JOIN tipo tw1 on tw1.Tipo_ID = cw.Tipo_WorkFlow_ID
			LEFT JOIN tipo tw2 on tw2.Tipo_ID = tw1.Tipo_Auxiliar
			LEFT JOIN tipo tw3 on tw3.Tipo_ID = tw2.Tipo_Auxiliar
			LEFT JOIN tipo tw4 on tw4.Tipo_ID = tw3.Tipo_Auxiliar
			LEFT JOIN tipo tw5 on tw5.Tipo_ID = tw4.Tipo_Auxiliar
			where cw.Workflow_ID is not null
			$condicoes
			group by cw.Workflow_ID, cw.Codigo, cw.Titulo, cd1.Nome, cd1.email, cd2.Nome,
					cd2.email, t.Descr_Tipo, cw.Data_Cadastro, tr.Descr_Tipo
			order by cw.Workflow_ID desc, cf.Follow_ID";
//echo $sql;
//exit();
$resultado = mpress_query($sql);
while($row = mpress_fetch_array($resultado)){
	$i++;
	$nome = $row[Nome];
	$solicitante = $row[Solicitante];
	$prestador = $row[Prestador];

	$totalGeralCusto += ($row['Total_Custo']);
	$totalGeralCobranca += ($row['Total_Cobranca']);
	$totalCusto = number_format($row['Total_Custo'], 2, ',', '.');
	$totalCobranca = number_format($row['Total_Cobranca'], 2, ',', '.');

	$c = 1;

	if ($row['Tipo4']==""){
		$tipo1 = $row['Tipo5'];
		$tipo2 = "";
	}
	else{
		$tipo1 = $row['Tipo4'];
		$tipo2 = $row['Tipo5'];
	}


	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Workflow_ID]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo_Chamado]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$tipo1."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$tipo2."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo_Solicitante]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$solicitante."</p>";

	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Cidade]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[UF]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Regional]."</p>";

	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$prestador."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".strip_tags($row[Descricao_Follow])."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Finalizado]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Interacao]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Responsavel]."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$totalCusto."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".$totalCobranca."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($row[Total_KM], 2, ',', '.')."</p>";
	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:right;'>".number_format($row[Total_Horas], 2, ',', '.')."</p>";
	//$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;' align='center'><a href='#' class='link workflow-localiza' id='cadastro-localiza-".$row[Workflow_ID]."' name='".$row[Workflow_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>visualizar</p><a></p>";
}
$largura = "100%";
$colunas = $c-1;
$c = 1;
$dados[colunas][titulo][$c++] = "ID";
$dados[colunas][titulo][$c++] = "Chamado";
$dados[colunas][titulo][$c++] = "Tipo Nivel 1";
$dados[colunas][titulo][$c++] = "Tipo Nivel 2";
$dados[colunas][titulo][$c++] = "C&oacute;d. Solicitante";
$dados[colunas][titulo][$c++] = "Solicitante";
$dados[colunas][titulo][$c++] = "Cidade";
$dados[colunas][titulo][$c++] = "UF";
$dados[colunas][titulo][$c++] = "Regional";
$dados[colunas][titulo][$c++] = "Prestador";
$dados[colunas][titulo][$c++] = "Situa&ccedil;&atilde;o";
$dados[colunas][titulo][$c++] = "&Uacute;ltima Intera&ccedil;&atilde;o";
$dados[colunas][titulo][$c++] = "Data Abertura";
$dados[colunas][titulo][$c++] = "Data Finalizado";
$dados[colunas][titulo][$c++] = "Data Intera&ccedil;&atilde;o";
$dados[colunas][titulo][$c++] = "Respons&aacute;vel";
$dados[colunas][titulo][$c++] = "Total Custo";
$dados[colunas][titulo][$c++] = "Total Cobran&ccedil;a";
$dados[colunas][titulo][$c++] = "KM";
$dados[colunas][titulo][$c++] = "HT";

$c = 1;
$dados[colunas][tamanho][$c++] = "width='40px'";
$dados[colunas][tamanho][$c++] = "width='90px'";
$c=12;
$dados[colunas][tamanho][$c++] = "width='102px'";
$dados[colunas][tamanho][$c++] = "width='102px'";
$dados[colunas][tamanho][$c++] = "width='102px'";
$dados[colunas][tamanho][$c++] = "width='95px'";
$dados[colunas][tamanho][$c++] = "width='95px'";
$dados[colunas][tamanho][$c++] = "width='55px'";
$dados[colunas][tamanho][$c++] = "width='55px'";

$dados[colunas][conteudo][$i + 1][$colunas-1] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalGeralCusto, 2, ',', '.')."</b></p>";
$dados[colunas][conteudo][$i + 1][$colunas] = "<p Style='margin:2px 3px 0 3px;float:right;'><b>".number_format($totalGeralCobranca, 2, ',', '.')."</b></p>";


echo " <div class='titulo-container' id='localiza-chamado-retorno'>
		<div class='titulo'>
			<p>Quantidade de Chamados: $i</p>
		</div>
		<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
geraTabela($largura,$colunas,$dados, null, 'relatorio-dinamico', 2, 2, 100,1);
echo "		</div>
		</div>";

$_SESSION["session-conteudo-relatorio"] = returnTabelaExcel($largura,$colunas,$dados);
}
?>