<?php
	$dadospagina = get_page_content();
	$id = $_POST['localiza-id'];
	$codigo = $_POST['localiza-codigo'];
	$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
	$nomeCompleto = $_POST['localiza-nome-completo'];
	$cpf = $_POST['localiza-cpf'];
	$cnpj = $_POST['localiza-cnpj'];
	$operadorID = $_POST['operador-processo'];
	$tipoPedido = $_POST['localiza-tipo-pedido'];

	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-pedido-situacao']); $i++){
		$situacoes .= $virgula.$_POST['localiza-pedido-situacao'][$i];
		$virgula = ",";
	}
	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-representante']); $i++){
		$representantes .= $virgula.$_POST['localiza-representante'][$i];
		$virgula = ",";
	}
	$dataInicio = $_POST['data-inicio-abertura'];
	$dataFim = $_POST['data-fim-abertura'];
	$dataInicioFinalizado = $_POST['data-inicio-finalizado'];
	$dataFimFinalizado = $_POST['data-fim-finalizado'];


	// "and cd.Cadastro_ID > 0 ";

	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Localizar Cadastro</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left;width:12.5%'>
							<p>Nº Pedido</p>
							<p><input type='text' id='localiza-id' name='localiza-id'  maxlength='10' style='width:90%' class='formata-numero' value='".$id."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:12.5%'>
							<p>Protocolo (Ticket)</p>
							<p><input type='text' id='localiza-codigo' name='localiza-codigo'  maxlength='20' style='width:90%' class='formata-numero' value='".$codigo."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							<p>Nome Cliente</p>
							<p><input type='text' id='localiza-nome-completo' name='localiza-nome-completo'  style='width:90%' maxlength='250' value='".$nomeCompleto."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:12.5%'>
							<p>CPF</p>
							<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' style='width:90%' class='mascara-cpf' value='".$cpf."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:12.5%'>
							<p>CNPJ</p>
							<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' style='width:90%' class='mascara-cnpj' value='".$cnpj."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							<p>Data Abertura:</p>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='".$dataInicio."'>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='".$dataFim."'></p>
							</div>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%; position:relative;z-index:2;'>
							<p>Situa&ccedil;&atilde;o:</p>
							<p><select name='localiza-pedido-situacao[]' multiple id='localiza-pedido-situacao' style='height:83px;'>".optionValueGrupoMultiplo(22, $situacoes)."<select></p>
						</div>

						<div class='titulo-secundario' style='float:left;width:25%; position:relative;z-index:2;'>
							<p>Representante</p>
							<p>
							<select name='localiza-representante[]' multiple id='localiza-representante' style='height:83px;'>";
		$arrSelecionados = explode(",",$representantes);
		$resultado = mpress_query("select Cadastro_ID, Nome from cadastros_dados where cadastro_ID < -1");
		while($row = mpress_fetch_array($resultado)){
			if (strlen(array_search($row[Cadastro_ID],$arrSelecionados))>=1){$selecionado='selected';}else{$selecionado='';}
			echo "				<option value='".$row[Cadastro_ID]."' $selecionado>".$row[Nome]."</option>";
		}
		echo "				</select></p>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							<p>Operador</p>
							<p>
							<select name='operador-processo' id='operador-processo'>
								<option value=''></option>";
		$sql = "select distinct cd.Cadastro_ID, cd.Nome from cadastros_dados cd inner join telemarketing_workflows tw on tw.Usuario_Cadastro_ID = cd.Cadastro_ID order by cd.Nome";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$selecionado = "";
			if ($operadorID == $row[Cadastro_ID]){$selecionado = " selected ";}
			echo "				<option value='".$row[Cadastro_ID]."' $selecionado>".$row[Nome]."</option>";
		}
		echo "				</select></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							<p>Data Finalizado:</p>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-inicio-finalizado' id='data-inicio-finalizado' class='formata-data' style='width:95%' maxlength='10' value='".$dataInicioFinalizado."'>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-fim-finalizado' id='data-fim-finalizado' class='formata-data' style='width:95%' maxlength='10' value='".$dataFimFinalizado."'></p>
							</div>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							&nbsp;
							<!--
							<p>Tipo Processo</p>
							<p><select name='localiza-tipo-pedido' id='localiza-tipo-pedido'>".optionValueGrupo(25, $tipoPedido, "Todos")."<select></p>
							-->
						</div>
						<div class='titulo-secundario' style='float:right;width:12.5%; margin-top:15px'>
							<p><input type='button' value='Pesquisar' id='botao-relatorio-dinamico' style='width:90%'/></p>
						</div>
						<div class='titulo-secundario' style='float:right;width:12.5%; margin-top:15px'>
							<p><input type='button' value='Excel' id='botao-relatorio-dinamico-excel' style='width:90%' class='esconde'/></p>
						</div>
					</div>
				</div>
			</div>
			<input type='hidden' id='localiza-cadastro-id' name='localiza-cadastro-id' value=''>
			<input type='hidden' id='localiza-workflow-id' name='localiza-workflow-id' value=''>";

if($_POST){
	echo "<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center' id='$idTabela'>
			<thead>
				<tr>
					<td class='tabela-fundo-escuro-titulo' width='090px'>Nº Pedido</td>
					<td class='tabela-fundo-escuro-titulo' width='090px'>Protocolo</td>
					<td class='tabela-fundo-escuro-titulo' width=''>Cliente</td>
					<td class='tabela-fundo-escuro-titulo' width='130px'>Tipo Processo</td>
					<td class='tabela-fundo-escuro-titulo' width='130px'>CPF/CNPJ</td>
					<td class='tabela-fundo-escuro-titulo' width=''>Situa&ccedil;&atilde;o</td>
					<td class='tabela-fundo-escuro-titulo' width='090px'>Data Abertura</td>
					<td class='tabela-fundo-escuro-titulo' width='090px'>Data Finalizado</td>
					<td class='tabela-fundo-escuro-titulo' width='150px'>Operador</td>
					<td class='tabela-fundo-escuro-titulo' width='150px'>Representante</td>
					<td class='tabela-fundo-escuro-titulo' width='080px' align='center'>Valor</td>
				</tr>
			</thead>
			<tbody>";

	if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
	if ($cpf != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cpf%'";}
	if ($cnpj != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cnpj%'";}
	if ($id != ""){ $sqlCond .= " and tw.Workflow_ID = '$id'";}
	if ($codigo != ""){ $sqlCond .= " and tw.Codigo = '$codigo'";}
	if ($operadorID != "") $sqlCond .= " and tw.Usuario_Cadastro_ID = '$operadorID'";
	if ($situacoes!="") $sqlCond .= " and tf.Situacao_ID IN ($situacoes)";
	if ($representantes!="") $sqlCond .= " and (tw.Fornecedor_ID * -1) IN ($representantes)";

	if(($dataInicio!="")||($dataFim!="")){
		$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
		if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
		$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
		if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
		$sqlCond .= " and tw.Data_Cadastro between '$dataInicio' and '$dataFim' ";
	}
	if(($dataInicioFinalizado!="")||($dataFimFinalizado!="")){
		$dataInicioFinalizado = implode('-',array_reverse(explode('/',$dataInicioFinalizado)));
		if ($dataInicioFinalizado=="") $dataInicioFinalizado = "0000-00-00"; $dataInicioFinalizado .= " 00:00";
		$dataFimFinalizado = implode('-',array_reverse(explode('/',$dataFimFinalizado)));
		if ($dataFimFinalizado=="") $dataFimFinalizado = "2100-01-01"; $dataFimFinalizado .= " 23:59";
		$sqlCond .= " and tf.Data_Cadastro between '$dataInicioFinalizado' and '$dataFimFinalizado' and tf.Situacao_ID = 41 ";
	}


	$sql = "select tw.Workflow_ID as Workflow_ID, tw.Codigo as Codigo, cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj,
			cd.Inscricao_Municipal, cd.Inscricao_Estadual, COALESCE(twp.Descr_Tipo,'Nenhum processo cadastrado') as Tipo_Workflow,
			DATE_FORMAT(tw.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro, coalesce(ts.Descr_Tipo,'N/A') as Situacao, usu.Nome as Usuario,
			if(tf.Situacao_ID=41, DATE_FORMAT(tf.Data_Cadastro,'%d/%m/%Y'),'') as Data_Finalizado, rep.Nome as Representante,
			coalesce(sum(twpp.Valor_Venda_Unitario  * twpp.Quantidade),0) as Total_Venda
			from telemarketing_workflows tw
			inner join cadastros_dados cd on tw.Solicitante_ID = cd.Cadastro_ID
			left join cadastros_dados usu on usu.Cadastro_ID = tw.Usuario_Cadastro_ID
			left join cadastros_dados rep on (rep.Cadastro_ID * -1) = tw.Fornecedor_ID
			left join tipo twp on twp.Tipo_ID = tw.Tipo_Workflow_ID
			left join tipo tp on tp.Tipo_ID = cd.Tipo_Pessoa
			left join telemarketing_workflows_produtos twpp on twpp.Workflow_ID = tw.Workflow_ID and twpp.Situacao_ID = 1
			inner join telemarketing_follows tf on tw.Workflow_ID = tf.Workflow_ID
				and tf.Follow_ID = (select max(tfaux.Follow_ID) from telemarketing_follows tfaux where tf.Workflow_ID = tfaux.Workflow_ID)
			inner join tipo ts on ts.Tipo_ID = tf.Situacao_ID
			where cd.Situacao_ID = 1
			$sqlCond
			group by tw.Workflow_ID, tw.Codigo, cd.Cadastro_ID, tp.Descr_Tipo, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj,
			cd.Inscricao_Municipal, cd.Inscricao_Estadual, twp.Descr_Tipo, tw.Data_Cadastro, ts.Descr_Tipo, usu.Nome,
			tf.Situacao_ID, tf.Data_Cadastro, rep.Nome
			order by tw.Workflow_ID";

	//echo $sql;
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome]; if ($row[Nome_Fantasia]!=""){ $nome .= " / ".$row[Nome_Fantasia];}
		echo "	<tr>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;'>".$row[Workflow_ID]."</p></td>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;'>".$row[Codigo]."</p></td>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$nome."</b></p></td>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Tipo_Workflow]."</p></td>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</td>
					<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Situacao]."</td>
					<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Data_Cadastro]."</p></td>
					<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Data_Finalizado]."</p></td>
					<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Usuario]."</p></td>
					<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Representante]."</p></td>
					<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".number_format($row[Total_Venda], 2, ',', '.')."</p></td>
				</tr>";
	}
	echo "	</tbody>
		</table>";
	if($i==0){
		echo "	<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro/processo localizado</p>";
	}
}
?>