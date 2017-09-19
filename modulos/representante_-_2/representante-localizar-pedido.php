<?php
	$dadospagina = get_page_content();

	global $dadosUserLogin;

	$id = $_POST['localiza-id'];
	$codigo = $_POST['localiza-codigo'];
	$tipoCadastro = $_POST['localiza-tipo-cadastro-id'];
	$nomeCompleto = $_POST['localiza-nome-completo'];
	$cpf = $_POST['localiza-cpf'];
	$cnpj = $_POST['localiza-cnpj'];

	$dataInicio = $_POST['data-inicio-abertura'];
	$dataFim = $_POST['data-fim-abertura'];

	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-chamado-situacao']); $i++){
		$situacoes .= $virgula.$_POST['localiza-chamado-situacao'][$i];
		$virgula = ",";
	}


	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Localizar Pedidos do Representante ".$dadosUserLogin['nome']."</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario quatro-colunas'>
							<div class='titulo-secundario' style='float:left;width:50%'>
								<p>Nº Pedido</p>
								<p><input type='text' id='localiza-id' name='localiza-id'  maxlength='10' style='width:90%' class='formata-numero' value='".$id."'/></p>
							</div>
							<div class='titulo-secundario' style='float:left;width:50%'>
								<p>Protocolo (Ticket)</p>
								<p><input type='text' id='localiza-codigo' name='localiza-codigo'  maxlength='20' style='width:90%' class='formata-numero' value='".$codigo."'/></p>
							</div>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Nome Cliente</p>
							<p><input type='text' id='localiza-nome-completo' name='localiza-nome-completo'  style='width:90%' maxlength='250' value='".$nomeCompleto."'/></p>
						</div>

						<div class='titulo-secundario quatro-colunas' style='position:relative;z-index:2;'>
							<p>Situa&ccedil;&atilde;o:</p>
							<select name='localiza-chamado-situacao[]' multiple id='localiza-chamado-situacao' style='height:71px;'>".optionValueGrupoMultiplo(22, $situacoes)."<select>
						</div>
						<div class='titulo-secundario quatro-colunas'>
							<p>Data Abertura:</p>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-inicio-abertura' id='data-inicio-abertura' class='formata-data' style='width:95%' maxlength='10' value='".$dataInicio."'/>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='data-fim-abertura' id='data-fim-abertura' class='formata-data' style='width:95%' maxlength='10' value='".$dataFim."'/></p>
							</div>
						</div>
						<div class='titulo-secundario  quatro-colunas'>
							<p>CPF Cliente</p>
							<p><input type='text' id='localiza-cpf' name='localiza-cpf' maxlength='14' style='width:95%' class='mascara-cpf' value='".$cpf."'/></p>
						</div>
						<div class='titulo-secundario  quatro-colunas'>
							<p>CNPJ Cliente</p>
							<p><input type='text' id='localiza-cnpj'  name='localiza-cnpj' maxlength='18' style='width:95%' style='width:95%'class='mascara-cnpj' value='".$cnpj."'/></p>
						</div>
						<div class='titulo-secundario  quatro-colunas'>&nbsp;
						</div>
						<div class='titulo-secundario  quatro-colunas' style='margin-top:15px;'>
							<p><input type='button' value='Pesquisar' id='botao-pesquisar-pedidos' style='width:96%' class='cadastra-grupos-pedidos'/></p>
						</div>

					</div>
				</div>
			</div>
			<input type='hidden' id='localiza-workflow-id' name='localiza-workflow-id' value=''>";

	if($_POST){
		echo "<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center' id='$idTabela'>
				<thead>
					<tr>
						<td class='tabela-fundo-escuro-titulo' width=''>Cadastro</td>
						<td class='tabela-fundo-escuro-titulo' width='130px'>Tipo Pessoa </td>
						<td class='tabela-fundo-escuro-titulo' width='130px'>CPF/CNPJ	</td>
						<td class='tabela-fundo-escuro-titulo' width='090px'>Nº Pedido</td>
						<td class='tabela-fundo-escuro-titulo' width='090px'>Protocolo</td>
						<td class='tabela-fundo-escuro-titulo' width=''>Situa&ccedil;&atilde;o</td>
						<td class='tabela-fundo-escuro-titulo' width='090px'>Data Abertura</td>
						<td class='tabela-fundo-escuro-titulo' width='150px' align='center'>Usu&aacute;rio</td>
						<td class='tabela-fundo-escuro-titulo'>&nbsp;</td>
					</tr>
				</thead>
				<tbody>";

		if ($nomeCompleto != ""){ $sqlCond .= " and (cd.Nome like '%$nomeCompleto%'  or cd.Nome_Fantasia like '%$nomeCompleto%')";}
		if ($cpf != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cpf%'";}
		if ($cnpj != ""){ $sqlCond .= " and cd.Cpf_Cnpj like '%$cnpj%'";}
		if ($id != ""){ $sqlCond .= " and tw.Workflow_ID = '$id'";}
		if ($codigo != ""){ $sqlCond .= " and tw.Codigo = '$codigo'";}
		if ($situacoes!="") $sqlCond .= " and tf.Situacao_ID IN ($situacoes)";

		if(($dataInicio!="")||($dataFim!="")){
			$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
			if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
			$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
			if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
			$sqlCond .= " and tw.Data_Cadastro between '$dataInicio' and '$dataFim' ";
		}
		$representanteID = ($dadosUserLogin['userID'] * -1);

		$sql = "select tw.Workflow_ID as Workflow_ID, tw.Codigo as Codigo, cd.Cadastro_ID as Cadastro_ID, tp.Descr_Tipo as Tipo_Pessoa, cd.Nome, cd.Nome_Fantasia, cd.Cpf_Cnpj,
				cd.Inscricao_Municipal, cd.Inscricao_Estadual, COALESCE(twp.Descr_Tipo,'Nenhum processo cadastrado') as Tipo_Workflow,
				DATE_FORMAT(tw.Data_Cadastro,'%d/%m/%Y') as Data_Cadastro, coalesce(ts.Descr_Tipo,'N/A') as Situacao, usu.Nome as Usuario
				from cadastros_dados cd
				left join telemarketing_workflows tw on tw.Solicitante_ID = cd.Cadastro_ID
				left join cadastros_dados usu on usu.Cadastro_ID = tw.Usuario_Cadastro_ID
				left join tipo twp on twp.Tipo_ID = tw.Tipo_Workflow_ID
				left join tipo tp on tp.Tipo_ID = cd.Tipo_Pessoa and tp.Tipo_Grupo_ID = 8
				left join tipo tc on tc.Tipo_ID = cd.Tipo_Cadastro and tc.Tipo_Grupo_ID = 9
				left join telemarketing_follows tf on tw.Workflow_ID = tf.Workflow_ID
					and tf.Follow_ID = (select max(tfaux.Follow_ID) from telemarketing_follows tfaux where tf.Workflow_ID = tfaux.Workflow_ID)
				left join tipo ts on ts.Tipo_ID = tf.Situacao_ID
				where cd.Situacao_ID = 1
				and Fornecedor_ID = ".($representanteID*-1)."
				$sqlCond
				order by cd.Cadastro_ID";
		//echo $sql ;
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$editarWorkflow = "";
			$nome = $row[Nome];
			if ($row[Nome_Fantasia]!=""){ $nome .= " / ".$row[Nome_Fantasia];}
			$editarWorkflow = "";
			if ($row[Workflow_ID]!=""){$editarWorkflow = "<input type='button' value='Visualizar Pedido' class='editar-workflow' workflow-id='".$row[Workflow_ID]."' Style='height:20px;font-size:10px;margin-top:-3px;width:100px'/>";}
			if ($row[Cadastro_ID]!=$cadastroIDAnt){
				$campos = "	<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$nome."</b></p></td>
							<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$row[Tipo_Pessoa]."</b></p></td>
							<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'><b>".$row[Cpf_Cnpj]."</b></p></td>";
			}
			else{
				$campos = "	<td class='fundo-escuro'>&nbsp;</td>
							<td class='fundo-escuro'>&nbsp;</td>
							<td class='fundo-escuro'>&nbsp;</td>";
			}
			echo "	<tr>
						".$campos."
						<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;' class='editar-workflow link' workflow-id='".$row[Workflow_ID]."'><b>".$row[Workflow_ID]."</b></p></td>
						<td class='fundo-escuro'><p Style='margin:2px 5px 0 20px;float:left;' class='editar-workflow link' workflow-id='".$row[Workflow_ID]."'><b>".$row[Codigo]."</b></p></td>
						<td class='fundo-escuro'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Situacao]."</p></td>
						<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Data_Cadastro]."</p></td>
						<td class='fundo-escuro' align='center'><p Style='margin:2px 5px 0 5px;'>".$row[Usuario]."</p></td>
						<td class='fundo-escuro' width='105'><p Style='margin:2px 5px 0 5px;float:left;'>".$editarWorkflow."</p></td>
					</tr>";
			$cadastroIDAnt = $row[Cadastro_ID];
		}
		echo "	</tbody>
			</table>";

		if($i==0){
			echo "	<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro/processo localizado</p><br></p>";
		}
	}
?>