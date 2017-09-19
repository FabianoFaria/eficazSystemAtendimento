<?php
	include("functions.php");
	global $caminhoSistema;

	$id 	= $_POST['localiza-envio-id'];
	$codigo 	= $_POST['localiza-codigo'];
	$rastreamento = $_POST['localiza-envio-rastreamento'];

	$envioDe 	= $_POST['localiza-envio-de'];
	$envioPara 	= $_POST['localiza-envio-para'];
	$envioTrans	= $_POST['localiza-envio-trans'];
	$formaEnvioID = $_POST['localiza-forma-envio'];
	//$situacao = $_POST['localiza-envio-situacao'];
	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-envio-situacao']); $i++){
		$situacao .= $virgula.$_POST['localiza-envio-situacao'][$i];
		$virgula = ",";
	}
	$dataInicioEnvio = $_POST['data-inicio-envio'];
	$dataFimEnvio = $_POST['data-fim-envio'];
	$dataInicioEntrega = $_POST['data-inicio-entrega'];
	$dataFimEntrega = $_POST['data-fim-entrega'];

	$solicitanteID = $_POST['localiza-envio-usuario'];
	$produto = $_POST['localiza-envio-produto'];
	$tipoEnvioID = $_POST['localiza-tipo-envio'];

	$dataInicioCadastro = $_POST['data-inicio-cadastro'];
	$dataFimCadastro = $_POST['data-fim-cadastro'];

	$dataInicioPrevisao = $_POST['data-inicio-previsao'];
	$dataFimPrevisao = $_POST['data-fim-previsao'];

	if($_POST)
		$classeEsconde = "";
	else
		$classeEsconde = "esconde";
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">
		Filtros de Pesquisa
		</p>
	</div>

	<input type='hidden' id='workflow-id' name='workflow-id' >
	<input type='hidden' id='localiza-workflow-id' name='localiza-workflow-id'/>
	<div class="conteudo-interno">
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:43%; float:left;'>
				<p>ID Envio</p>
				<p><input type='text' name='localiza-envio-id' id='localiza-envio-id' class='formata-numero' value='<?php echo $id; ?>' style='width:85%;'></p>
			</div>
			<div class="titulo-secundario" style='width:57%; float:left;'>
				<p><?php echo $_SESSION['objeto']; ?></p>
				<p><input type='text' name='localiza-codigo' id='localiza-codigo' class='formata-campo' value='<?php echo $codigo; ?>' style='width:85%;'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:95%; float:left;'>
				<p>Tipo</p>
				<p><select name="localiza-tipo-envio" id="localiza-tipo-envio"><?php echo optionValueGrupo(59, $tipoEnvioID, "&nbsp;");?><select></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Data Solicitação</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-cadastro' id='data-inicio-cadastro' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioCadastro; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-cadastro' id='data-fim-cadastro' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimCadastro; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Data Envio</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-envio' id='data-inicio-envio' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioEnvio; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-envio' id='data-fim-envio' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimEnvio; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Data Previsão</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-previsao' id='data-inicio-previsao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioPrevisao; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-previsao' id='data-fim-previsao' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimPrevisao; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Data Entrega</p>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-inicio-entrega' id='data-inicio-entrega' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataInicioEntrega; ?>'>&nbsp;&nbsp;</p>
			</div>
			<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
			<div style='width:43%;float:left;'>
				<p><input type='text' name='data-fim-entrega' id='data-fim-entrega' class='formata-data' style='width:95%' maxlength='10' value='<?php echo $dataFimEntrega; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>C&oacute;digo Rastreamento</p>
			<p><input type='text' name='localiza-envio-rastreamento' id='localiza-envio-rastreamento' class='formata-campo' value='<?php echo $rastreamento; ?>'></p>
		</div>
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:95%; float:left;'>
				<p>Forma de Envio</p>
				<p><select name="localiza-forma-envio" id="localiza-forma-envio"><?php echo optionValueGrupo(31, $formaEnvioID, "&nbsp;");?><select></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Remetente</p>
			<p><input type='text' name='localiza-envio-de' id='localiza-envio-de' class='formata-campo' value='<?php echo $envioDe; ?>'></p>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Destinat&aacute;rio</p>
			<p><input type='text' name='localiza-envio-para' id='localiza-envio-para' class='formata-campo' value='<?php echo $envioPara; ?>'></p>
		</div>
		<div class="titulo-secundario seis-colunas">
			<p>Transportador</p>
			<p><input type='text' name='localiza-envio-trans' id='localiza-envio-trans' class='formata-campo' value='<?php echo $envioTrans; ?>'></p>
		</div>
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:98%; float:left;'>
				<p>Produto</p>
				<p><input type='text' name='localiza-envio-produto' id='localiza-envio-produto' class='formata-campo' value='<?php echo $produto; ?>'></p>
			</div>
		</div>
		<div class="titulo-secundario seis-colunas">
			<div class="titulo-secundario" style='width:98%; float:left;'>
				<p>Usu&aacute;rio Solicitante:</p>
				<p><select name="localiza-envio-usuario" id="localiza-envio-usuario"><?php echo optionValueSolicitantes($solicitanteID, "");?><select></p>
			</div>
		</div>
		<div class="titulo-secundario tres-colunas">
			<div class="titulo-secundario" style='width:99%; float:left;'>
				<p>Situa&ccedil;&atilde;o Envio</p>
				<select name="localiza-envio-situacao[]" id="localiza-envio-situacao" multiple><?php echo optionValueGrupoMultiplo(30, $situacao);?><select>
			</div>
		</div>
		<div class="titulo-secundario seis-coluna" style='float:right'>
			<p style='float:rigth;margin-top:15px;'>
				<div class='btn-imprimir <?php echo $classeEsconde ;?>' id='botao-imprimir' style='float:left;' title='Imprimir'></div>&nbsp;
				<div class='btn-excel <?php echo $classeEsconde; ?>' id='botao-salvar-excel' style='float:left;' title='Gerar Excel'></div>&nbsp;
				<input type='button' Style='width:140px; float:right' value='Pesquisar' id='botao-localizar-envio'>
			</p>
		</div>
	</div>
</div>
<?php
if($_POST){
	$i = 0;
	if($id!="") 		$condicoes .= " and ew.Workflow_ID = '$id' ";
	if($codigo!="") 	$condicoes .= " and ew.Chave_Estrangeira in (select cw2.Workflow_ID from chamados_workflows cw2 where cw2.Codigo = '$codigo')";

	if($envioDe!="") 		$condicoes .= " and cd1.Nome like '%$envioDe%' ";
	if($envioPara!="") 		$condicoes .= " and cd2.Nome  like '%$envioPara%'";
	if($envioTrans!="") 	$condicoes .= " and cd3.Nome  like '%$envioTrans%'";

	if($rastreamento!="") 	$condicoes .= " and ew.Codigo_Rastreamento like '%$rastreamento%'";
	if($formaEnvioID!="") 	$condicoes .= " and ew.Forma_Envio_ID = '$formaEnvioID'";
	if($situacao!="") 		$condicoes .= " and ef.Situacao_ID in ($situacao)";
	if($tipoEnvioID!="") 	$condicoes .= " and ew.Tipo_Envio_ID = '$tipoEnvioID'";

	if($solicitanteID!="") 	$condicoes .= " and ew.Usuario_Cadastro_ID = '$solicitanteID'";

	if ($produto != ""){ $condicoes .= " and (pd.Nome like '%$produto%' or pd.Descricao_Resumida like '%$produto%' or pv.Descricao like '%$produto%' or concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) like '%$produto%')";}


	if(($dataInicioCadastro!="")||($dataFimCadastro!="")){
		$dataInicioCadastro = implode('-',array_reverse(explode('/',$dataInicioCadastro)));
		if ($dataInicioCadastro=="") $dataInicioCadastro = "0000-00-00"; $dataInicioCadastro .= " 00:00";
		$dataFimCadastro = implode('-',array_reverse(explode('/',$dataFimCadastro)));
		if ($dataFimCadastro=="") $dataFimCadastro = "2100-01-01"; $dataFimCadastro .= " 23:59";
		$condicoes .= " and ew.Data_Cadastro between '$dataInicioCadastro' and '$dataFimCadastro' ";
	}

	if(($dataInicioEnvio!="")||($dataFimEnvio!="")){
		$dataInicioEnvio = implode('-',array_reverse(explode('/',$dataInicioEnvio)));
		if ($dataInicioEnvio=="") $dataInicioEnvio = "0000-00-00"; $dataInicioEnvio .= " 00:00";
		$dataFimEnvio = implode('-',array_reverse(explode('/',$dataFimEnvio)));
		if ($dataFimEnvio=="") $dataFimEnvio = "2100-01-01"; $dataFimEnvio .= " 23:59";
		$condicoes .= " and ew.Data_Envio between '$dataInicioEnvio' and '$dataFimEnvio' ";
	}

	if(($dataInicioPrevisao!="")||($dataFimPrevisao!="")){
		$dataInicioPrevisao = implode('-',array_reverse(explode('/',$dataInicioPrevisao)));
		if ($dataInicioPrevisao=="") $dataInicioPrevisao = "0000-00-00"; $dataInicioPrevisao .= " 00:00";
		$dataFimPrevisao = implode('-',array_reverse(explode('/',$dataFimPrevisao)));
		if ($dataFimPrevisao=="") $dataFimPrevisao = "2100-01-01"; $dataFimPrevisao .= " 23:59";
		$condicoes .= " and ew.Data_Previsao between '$dataInicioPrevisao' and '$dataFimPrevisao' ";
	}

	if(($dataInicioEntrega!="")||($dataFimEntrega!="")){
		$dataInicioEntrega = implode('-',array_reverse(explode('/',$dataInicioEntrega)));
		if ($dataInicioEntrega=="") $dataInicioEntrega = "0000-00-00"; $dataInicioEntrega .= " 00:00";
		$dataFimEntrega = implode('-',array_reverse(explode('/',$dataFimEntrega)));
		if ($dataFimEntrega=="") $dataFimEntrega = "2100-01-01"; $dataFimEntrega .= " 23:59";
		$condicoes .= " and ew.Data_Entrega between '$dataInicioEntrega' and '$dataFimEntrega' and ef.Situacao_ID = '53'";
	}

	if($_POST['ordena-tabela'] != "")
		$strOrdem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'].", Workflow_ID";
	else
		$strOrdem = " order by ew.Workflow_ID desc, ef.Follow_ID";


	$sql = "select ew.Workflow_ID, ew.Cadastro_ID_de, cd1.Nome as Cadastro_de, ew.Cadastro_ID_para, cd2.Nome as Cadastro_para,
			pv.Valor_Custo, pv.Valor_Venda,
			ew.Transportadora_ID, cd3.Nome as Transportador, ew.Forma_Envio_ID, tf.Descr_Tipo as Forma_Envio, ew.Codigo_Rastreamento, t.Descr_Tipo as Situacao, te.Descr_Tipo as Tipo,
			DATE_FORMAT(ew.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro,
			DATE_FORMAT(ew.Data_Envio,'%d/%m/%Y') as Data_Envio,
			DATE_FORMAT(ew.Data_Previsao,'%d/%m/%Y') as Data_Previsao,
			DATE_FORMAT(ew.Data_Entrega,'%d/%m/%Y') as Data_Entrega,
			DATE_FORMAT(ewp.Data_Retorno,'%d/%m/%Y') as Data_Retorno,
		  	case ew.Tabela_Estrangeira when 'chamados' then (select Codigo from chamados_workflows cw where ew.Chave_Estrangeira = cw.Workflow_ID) end as Codigo_Estrangeiro,
		  	case ew.Tabela_Estrangeira when 'chamados' then (select Nome from chamados_workflows cw2 inner join cadastros_dados pre on pre.Cadastro_ID = cw2.Prestador_ID where ew.Chave_Estrangeira = cw2.Workflow_ID) end as Prestador,
			concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Nome_Produto, ewp.Quantidade as Quantidade, ew.Valor_Transporte, ce1.UF as UFde, ce2.UF UFpara,
			sol.Nome as Solicitante, ewp.Retorna as Retorna, ewp.Embalado as Embalado, ewp.Observacoes as Observacoes
			from envios_workflows ew
			inner join envios_follows ef on ew.Workflow_ID = ef.Workflow_ID
					and ef.Follow_ID = (select max(efaux.Follow_ID) from envios_follows efaux where ef.Workflow_ID = efaux.Workflow_ID)
			left join cadastros_dados cd1 on cd1.Cadastro_ID = ew.Cadastro_ID_de
			left join cadastros_dados cd2 on cd2.Cadastro_ID = ew.Cadastro_ID_para
			left join tipo t on t.Tipo_ID = ef.Situacao_ID
			left join tipo te on te.Tipo_ID = ew.Tipo_Envio_ID
			left join cadastros_dados cd3 on cd3.Cadastro_ID = ew.Transportadora_ID
			left join cadastros_dados sol on sol.Cadastro_ID = ew.Usuario_Cadastro_ID
			left join tipo tf on tf.Tipo_ID = ew.Forma_Envio_ID
			left join envios_workflows_produtos ewp on ewp.Workflow_ID = ew.Workflow_ID and ewp.Situacao_ID = 1
			left join produtos_variacoes pv on pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
			left join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
			left join cadastros_enderecos ce1 on ce1.Cadastro_Endereco_ID = ew.Cadastro_ID_de_Endereco
			left join cadastros_enderecos ce2 on ce2.Cadastro_Endereco_ID = ew.Cadastro_ID_para_Endereco
			where ew.Workflow_ID is not null
				$condicoes
				$strOrdem";
	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$c = 1;
		$cadastroDe = $row[Cadastro_de];
		$cadastroPara = $row[Cadastro_para];
		$transportador = $row[Transportador];
		$solicitante = $row[Solicitante];
		$linkEnvio = " class='link workflow-envio' workflow-id='$row[Workflow_ID]'";
		if ($workflowIDAnt!=$row[Workflow_ID]){
			$dados[colunas][classe][$i] = "tabela-fundo-escuro";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:right;' $linkEnvio>".$row[Workflow_ID]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;' $linkEnvio>".$row[Tipo]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Codigo_Estrangeiro]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$cadastroDe."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[UFde]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$cadastroPara."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[UFpara]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$transportador."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$solicitante."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Codigo_Rastreamento]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Forma_Envio]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Situacao]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Data_Cadastro]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Data_Envio]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Data_Previsao]."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Data_Entrega]." </p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:right;'>".number_format($row[Valor_Transporte], 2, ',', '.')." </p>";
			$totalValorTransporte += $row[Valor_Transporte];
		}
		else{
			$c = 18;
		}

		$dados[colunas][classe][$i] = "tabela-fundo-claro";
		$dados[colunas][conteudo][$i][$c++] = "<span Style='margin:2px 1px 0 1px;'>".$row[Nome_Produto]."</span>";
		$dados[colunas][conteudo][$i][$c++] = "<span Style='margin:2px 1px 0 1px;'>".number_format($row[Quantidade], 2, ',', '.')."</span>";
		//$dados[colunas][conteudo][$i][$c++] = "<span Style='margin:2px 1px 0 1px;'>".number_format($row[Valor_Custo], 2, ',', '.')."</span>";
		$dados[colunas][conteudo][$i][$c++] = "<span Style='margin:2px 1px 0 1px;'>".number_format($row[Valor_Venda], 2, ',', '.')."</span>";

		if ($row[Retorna]==1) $retorna = "SIM"; else $retorna = "NÃO";
		if ($row[Embalado]==1) $embalado = "SIM"; else $embalado = "NÃO";

		$dados[colunas][conteudo][$i][$c++] = "<p style='margin:0px;' align='center'>$retorna</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p style='margin:0px;' align='center'>$embalado</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Observacoes]." </p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 1px 0 1px;float:left;'>".$row[Prestador]." </p>";

		$workflowIDAnt = $row[Workflow_ID];
	}
	/*TOTAL*/
	$i++;
	$dados[colunas][conteudo][$i][1] = "<b>Totais</b>";
	$dados[colunas][conteudo][$i][15] = "<p Style='margin:2px 1px 0 1px;float:right;'>".number_format($totalValorTransporte, 2, ',', '.')." </p>";
	$largura = "100%";
	$c=1;
	$dados[colunas][titulo][$c++] 	= "ID Envio";
	$dados[colunas][titulo][$c++] 	= "Tipo";
	$dados[colunas][titulo][$c++] 	= $_SESSION['objeto'];
	$dados[colunas][titulo][$c++] 	= "Remetente";
	$dados[colunas][titulo][$c++] 	= "UF";
	$dados[colunas][titulo][$c++] 	= "Destinat&aacute;rio";
	$dados[colunas][titulo][$c++] 	= "UF";
	$dados[colunas][titulo][$c++] 	= "Transportador";
	$dados[colunas][titulo][$c++] 	= "Solicitante";
	$dados[colunas][titulo][$c++] 	= "Rastreamento";
	$dados[colunas][titulo][$c++] = "Forma Envio";
	$dados[colunas][titulo][$c++] = "Situa&ccedil;&atilde;o";
	$dados[colunas][titulo][$c++] = "Solicitação";
	$dados[colunas][titulo][$c++] = "Envio";
	$dados[colunas][titulo][$c++] = "Previs&atilde;o";
	$dados[colunas][titulo][$c++] = "Entrega";
	$dados[colunas][titulo][$c++] = "Valor Transporte";
	$dados[colunas][titulo][$c++] = "Produto";
	$dados[colunas][titulo][$c++] = "Qtd.";
	//$dados[colunas][titulo][$c++] = "Valor Custo";
	$dados[colunas][titulo][$c++] = "Valor";
	$dados[colunas][titulo][$c++] = "Retorna";
	$dados[colunas][titulo][$c++] = "Embalado";
	$dados[colunas][titulo][$c++] = "Observação";
	$dados[colunas][titulo][$c++] = "Prestador ".$_SESSION['objeto'];
	$colunas = $c - 1;

	$dados[colunas][tamanho][3] = "width='90px'";
	$c = 1;
	$dados[colunas][ordena][$c++] = " ew.Workflow_ID ";
	$dados[colunas][ordena][$c++] = " Tipo ";
	$dados[colunas][ordena][$c++] = " Codigo_Estrangeiro ";
	$dados[colunas][ordena][$c++] = " Cadastro_de ";
	$dados[colunas][ordena][$c++] = " UFde ";
	$dados[colunas][ordena][$c++] = " Cadastro_Para ";
	$dados[colunas][ordena][$c++] = " UFpara ";
	$dados[colunas][ordena][$c++] = " Transportador ";
	$dados[colunas][ordena][$c++] = " Solicitante ";
	$dados[colunas][ordena][$c++] = " Solicitante ";
	$dados[colunas][ordena][$c++] = " Codigo_Rastreamento ";
	$dados[colunas][ordena][$c++] = " Forma_Envio ";
	$dados[colunas][ordena][$c++] = " Situacao ";
	$dados[colunas][ordena][$c++] = " ew.Data_Cadastro ";
	$dados[colunas][ordena][$c++] = " ew.Data_Envio ";
	$dados[colunas][ordena][$c++] = " ew.Data_Previsao ";
	$dados[colunas][ordena][$c++] = " ew.Data_Entrega ";
	$dados[colunas][ordena][$c++] = " Valor_Transporte ";



	$html = "	<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>
					<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
						<tr class='esconde'>
							<td class='tabela-fundo-escuro-titulo' styletitulo valign='top' width='518' height='53' colspan='7'>RELAT&Oacute;RIO GERAL</td>
							<td class='tabela-fundo-escuro-titulo' styletitulo valign='middle' align='right' width='210' height='53' colspan='3' ><img src='$caminhoSistema/images/topo/logo.png';?></td>
						</tr>";

	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='titulo'>
				<p>Relat&oacute;rio Geral </p>
			</div>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";

	geraTabela($largura,$colunas,$dados, null, 'envios-localiza-geral', 2, 2, 100,1);
	echo "		</div>
			</div>";

	$html .= "			<tr><td align='left' stylenormal Style='margin:2px 5px 0 2px;' colspan='10'>".returnTabelaExcel($largura,$colunas,$dados)."</td></tr>
					</table>
				</div>";
	$html = str_replace("styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:10px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
	$html = str_replace("stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:10px;solid #fff;'", $html);
	$html = str_replace("100%","718", $html);
	$_SESSION["session-conteudo-relatorio"] = $html;
}

?>