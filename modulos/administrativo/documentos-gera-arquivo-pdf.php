<?php
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');
	include("functions.php");
	global $caminhoSistema, $caminhoFisico, $dadosUserLogin;

	$id = $_POST['id-referencia'];
	$id2 = $_POST['id-referencia-secundario'];
	$origem = $_POST['origem-documento'];

	if ($_GET['email']=='true'){
		$documentoID = $_POST['modelo-email-id'];
	}
	else{
		$documentoID = $_POST['tipo-documento-id'];
	}


	$nomeArquivoOriginal = utf8_decode($_POST['nome-arquivo']);

	$resultado 		= mpress_query("select titulo, texto_documento, Cabecalho_Rodape from cadastros_documentos where Documento_ID = $documentoID");
	$dadosarquivo 	= mpress_fetch_array($resultado);
	$titulo 		= $dadosarquivo['titulo'];
	$background		= $dadosarquivo['Cabecalho_Rodape'];
	$conteudo 		= $dadosarquivo['texto_documento'];

	$hoje["mday"] = date('d');
	$hoje["mon"]  = date('m');
	$hoje["year"] = date('Y');
	$hoje["wday"] = date('w');

	$conteudo		= str_replace('[cabecalho]', '<img src="'.$caminhoSistema.'/images/documentos/cabecalho.jpg">', $conteudo);
	$conteudo		= str_replace('[rodape]', '<img src="'.$caminhoSistema.'/images/documentos/rodape.jpg">', $conteudo);
	$conteudo		= str_replace('[data-extenso]', dataDia($hoje), $conteudo);
	$conteudo		= str_replace('[data-extenso-d-m-a]', dataDia($hoje,'d-m-a'), $conteudo);
	$conteudo		= str_replace('[data]', date('d/m/Y'), $conteudo);
	$conteudo		= str_replace('[ano]', date('Y'), $conteudo);
	$conteudo		= str_replace('[mes]', date('m'), $conteudo);
	$conteudo		= str_replace('[dia]', date('d'), $conteudo);
	if (date('m')<7) $semestre = "01"; else $semestre = "02";
	$conteudo		= str_replace('[semestre]', $semestre, $conteudo);


	$conteudo		= str_replace('!-- pagebreak --', 'div Style="page-break-after: always;">&nbsp;</div', $conteudo);



	if ($origem=="cadastros"){
		/* endereços*/
		$pulaLinha = "";
		$query = mpress_query("Select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF from cadastros_enderecos where cadastro_id = $id and Situacao_ID = 1");
		while($row = mpress_fetch_array($query)){
			$enderecos .= $pulaLinha.$row[Logradouro].", ".$row[Numero]." ".$row[Complemento]." - ".$row[Bairro]." - ".$row[Cidade]." - ".$row[UF]." - ".$row[CEP];
			$pulaLinha = "<br>";
		}
		/* endereço principal completo*/
		$resultado 		= mpress_query("Select CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF from cadastros_enderecos where cadastro_id = $id and tipo_endereco_id = 26 and Situacao_ID = 1");
		$dadosarquivo	= mpress_fetch_array($resultado);
		$enderecoPrincipal = $dadosarquivo[Logradouro].", ".$dadosarquivo[Numero]." ".$dadosarquivo[Complemento]." - ".$dadosarquivo[Bairro]." - ".$dadosarquivo[Cidade]." - ".$dadosarquivo[UF]." - ".$dadosarquivo[CEP];

		/* endereço principal quebrado */
		$principalLogradouro 	= $dadosarquivo[Logradouro];
		$principalNumero 		= $dadosarquivo[Numero];
		$principalComplemento 	= $dadosarquivo[Complemento];
		$principalBairro 		= $dadosarquivo[Bairro];
		$principalCidade 		= $dadosarquivo[Cidade];
		$principalUF 			= $dadosarquivo[UF];
		$principalCEP 			= $dadosarquivo[CEP];

		/* telefones*/
		$barra = "";
		$query = mpress_query("select Telefone from cadastros_telefones where Cadastro_ID = $id and Situacao_ID = 1");
		while($row = mpress_fetch_array($query)){
			$telefones .= $barra.$row[Telefone];
			$barra = "&nbsp;/&nbsp;";
		}

		/* telefone - comercial */
		$barra = "";
		$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $id and Tipo_Telefone_ID = 27");
		while($row = mpress_fetch_array($query)){
			$telefoneComercial .= $barra.$row[Telefone];
			$barra = "&nbsp;/&nbsp;";
		}

		/* telefone - celular */
		$barra = "";
		$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $id and Tipo_Telefone_ID = 28");
		while($row = mpress_fetch_array($query)){
			$telefoneCelular .= $barra.$row[Telefone];
			$barra = "&nbsp;/&nbsp;";
		}

		/* telefone - residencial */
		$barra = "";
		$query = mpress_query("select Telefone from cadastros_telefones where Situacao_ID = 1 and Cadastro_ID = $id and Tipo_Telefone_ID = 29");
		while($row = mpress_fetch_array($query)){
			$telefoneResidencial .= $barra.$row[Telefone];
			$barra = "&nbsp;/&nbsp;";
		}

		$resultado 		= mpress_query("select cd.codigo, cd.nome, cd.email, cd.foto, cd.cpf_cnpj, cd.rg, cd.observacao, cd.inscricao_municipal, cd.inscricao_estadual,
											cd.nome_fantasia, cd.Areas_Atuacoes, DATE_FORMAT(cd.Data_Nascimento, '%d/%m/%Y') as Data_Nascimento, t.descr_tipo, cd.Tipo_Cadastro
											from cadastros_dados cd
											inner join tipo t on t.tipo_id = cd.tipo_pessoa
											where Cadastro_ID = $id");
		$dadosarquivo 	= mpress_fetch_array($resultado);

		/* Areas de Atuação */
		$arrayAreas = unserialize($dadosarquivo['Areas_Atuacoes']);
		for($i=0; $i<sizeof($arrayAreas); $i++){
			$query = mpress_query("Select Descr_Tipo from tipo where Tipo_ID = '".$arrayAreas[$i]."'");
			$row = mpress_fetch_array($query);
			$descAreas .= $virgula.$row[Descr_Tipo];
			$virgula = ",&nbsp;";
		}

		//$dataNascimento = str_replace('-','/',$dadosarquivo['Data_Nascimento']);

		$conteudo 		= str_replace('[data-nascimento]', utf8_encode($dadosarquivo['Data_Nascimento']), $conteudo);
		$conteudo		= str_replace('[areas-atuacao]', utf8_encode($descAreas), $conteudo);
		$conteudo		= str_replace('[nome-razao-social]', utf8_encode($dadosarquivo['nome']), $conteudo);
		$conteudo		= str_replace('[codigo]',$dadosarquivo['codigo'], $conteudo);
		$conteudo		= str_replace('[email]', utf8_encode($dadosarquivo['email']), $conteudo);
		$conteudo		= str_replace('[foto]', '<img src="../../uploads/'.utf8_encode($dadosarquivo['foto']).'" style="max-width:120px; max-height:165px;">', $conteudo);
		$conteudo		= str_replace('[logo]', '<img src="../../documentos/logo.jpg">', $conteudo);
		$conteudo		= str_replace('[cpf-cnpj]', $dadosarquivo['cpf_cnpj'], $conteudo);
		$conteudo		= str_replace('[rg]', $dadosarquivo[rg], $conteudo);
		$conteudo		= str_replace('[observacao]', utf8_encode($dadosarquivo['observacao']), $conteudo);
		$conteudo		= str_replace('[enderecos]', utf8_encode($enderecos), $conteudo);
		$conteudo		= str_replace('[endereco-principal-completo]', utf8_encode($enderecoPrincipal), $conteudo);
		$conteudo		= str_replace('[endereco-principal-bairro]', utf8_encode($principalBairro), $conteudo);
		$conteudo		= str_replace('[endereco-principal-cep]', utf8_encode($principalCEP), $conteudo);
		$conteudo		= str_replace('[endereco-principal-cidade]', utf8_encode($principalCidade), $conteudo);
		$conteudo		= str_replace('[endereco-principal-complemento]', utf8_encode($principalComplemento), $conteudo);
		$conteudo		= str_replace('[endereco-principal-logradouro]', utf8_encode($principalLogradouro), $conteudo);
		$conteudo		= str_replace('[endereco-principal-numero]', utf8_encode($principalNumero), $conteudo);
		$conteudo		= str_replace('[endereco-principal-uf]', utf8_encode($principalUF), $conteudo);
		$conteudo		= str_replace('[telefones]', $telefones, $conteudo);
		$conteudo		= str_replace('[telefone-comercial]', $telefoneComercial, $conteudo);
		$conteudo		= str_replace('[telefone-residencial]', $telefoneResidencial, $conteudo);
		$conteudo		= str_replace('[telefone-celular]', $telefoneCelular, $conteudo);
		$conteudo		= str_replace('[inscricao-municipal]', utf8_encode($dadosarquivo['inscricao_municipal']), $conteudo);
		$conteudo		= str_replace('[inscricao-estadual]', utf8_encode($dadosarquivo['inscricao_estadual']), $conteudo);
		$conteudo		= str_replace('[nome-fantasia]', utf8_encode($dadosarquivo['nome_fantasia']), $conteudo);
		$conteudo		= str_replace('[tipo-pessoa]', utf8_encode($dadosarquivo['descr_tipo']), $conteudo);

		if((string) strpos($conteudo, '[barcode]') != ""){
			for($i=strlen($id);$i<=18;$i++) $codigobarras .= "0";
			new barCodeGenrator("$codigobarras$id",1,"../../uploads/barcode.gif");
			$conteudo	= str_replace('[barcode]', '<img src="../../uploads/barcode.gif" Style="width:150px">', $conteudo);
		}

		if(strstr($conteudo,'[tipo-cadastro]')!=""){
			$arrTipos = unserialize($dadosarquivo['Tipo_Cadastro']);
			$arrTipoCadastro = carregarArrayTipo(9);
			foreach($arrTipos as $tipoID){
				if (($tipoID != "")&& ($tipoID != "1050")){
					$strTipo .= $arrTipoCadastro['descricao'][$tipoID]." ";
				}
			}
			$conteudo	= str_replace('[tipo-cadastro]', utf8_encode($strTipo), $conteudo);
		}

		// TRECHO ABAIXO RESPONSAVEL POR COLOCAR CAMPOS ADICIONAIS SE MODULO DE IGREJA ATIVO
		if (file_exists($caminhoFisico."/modulos/igreja/functions.php")){
			$sql = "select t.Descr_Tipo as Estado_Civil, coalesce(cd.Nome,'') as Congregacao, Data_Batismo, Data_Ordenacao, Nome_Pai, Nome_Mae, Cidade_Natural, UF_Natural
					from igreja_cadastros_dados icd
					left join cadastros_dados cd on icd.Congregacao_ID = cd.Cadastro_ID
					left join tipo t on t.Tipo_ID = icd.Estado_Civil
					where icd.Cadastro_ID = '$id'";
			$query = mpress_query($sql);
			if($rs = mpress_fetch_array($query)){
				$conteudo	= str_replace('[estado-civil]', utf8_encode($rs['Estado_Civil']), $conteudo);
				$conteudo	= str_replace('[data-batismo]', substr(converteDataHora($rs['Data_Batismo'],1),0,10), $conteudo);
				$conteudo	= str_replace('[data-ordenacao]', substr(converteDataHora($rs['Data_Ordenacao'],1),0,10), $conteudo);
				$conteudo	= str_replace('[nome-pai]', utf8_encode($rs['Nome_Pai']), $conteudo);
				$conteudo	= str_replace('[nome-mae]', utf8_encode($rs['Nome_Mae']), $conteudo);
				$conteudo	= str_replace('[cidade-natural]', utf8_encode($rs['Cidade_Natural']), $conteudo);
				$conteudo	= str_replace('[uf-natural]', utf8_encode($rs['UF_Natural']), $conteudo);
				$conteudo	= str_replace('[congregacao]', utf8_encode($rs['Congregacao']), $conteudo);
			}
		}
	}

	if ($origem=="chamados"){
		$sql = "SELECT cw.Workflow_ID, cw.Codigo, cw.Titulo, cw.Tipo_Workflow_ID, cw.Data_Abertura, cw.Data_Cadastro, cw.Data_Finalizado,
					tpp.Descr_Tipo AS Prioridade,
					tpc.Descr_Tipo,
					cds.Cadastro_ID AS ID_Solicitante, cds.Nome AS Nome_Razao_Social_Solicitante, cds.Codigo AS Codigo_Solicitante, cds.Cpf_Cnpj AS CPF_CNPJ_Solicitante, cds.Email AS Email_Solicitante, cds.Inscricao_Estadual AS Incri_Est_Solicitante, cds.Inscricao_Municipal AS Inscr_Mun_Solicitante, cds.Nome_Fantasia AS Nome_Fantasia_Solicitante, cds.Areas_Atuacoes as Areas_Atuacoes_Sol
						FROM chamados_workflows cw
						INNER JOIN cadastros_dados cds ON cw.Solicitante_ID = cds.Cadastro_ID
						LEFT JOIN tipo tpp ON cw.Prioridade_ID = tpp.Tipo_ID
						LEFT JOIN tipo tpc ON cw.Tipo_Workflow_ID = tpc.Tipo_ID
					WHERE cw.Workflow_ID = $id";

		$query = mpress_query($sql);
		if($row = mpress_fetch_array($query)){
			/* Tabela de Produtos */
			$sql = "SELECT pv.Produto_ID ,pv.Codigo, concat(pd.Nome, ' - ', pv.Descricao) AS Descricao, cwp.Quantidade, cwp.Valor_Venda_Unitario as vUnitario, (cwp.Valor_Venda_Unitario * cwp.Quantidade) as Valor, ma.Nome_Arquivo As Imagem
					FROM chamados_workflows_produtos cwp
					INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					LEFT JOIN modulos_anexos ma ON ma.Tabela_Estrangeira = 'produtos' AND ma.Anexo_ID = pv.Imagem_ID AND ma.Situacao_ID = 1
						WHERE Workflow_ID = '".$row['Workflow_ID']."' AND cwp.Situacao_ID = 1";


//									<td style="border:1px solid" align = "center"><strong>Imagem</strong></td>
//									<td style="border:1px solid" align = "center"><strong>C&oacute;digo</strong></td>

			$query = mpress_query($sql);
			$produtos .= '	</span></p>
							<table cellspacing="0" cellpadding="5" width="100%" align="center" style="font-size: 8pt;">
								<tr>
									<td style="border:1px solid" align = "center"><strong>Produto / Servi&ccedil;o</strong></td>
									<td style="border:1px solid" align = "center"><strong>Quantidade</strong></td>
									<td style="border:1px solid" align = "center"><strong>Valor Unit&aacute;rio</strong></td>
									<td style="border:1px solid" align = "center"><strong>Valor Total</strong></td>
								</tr>';
			$i = 0;
			$totalGeral = 0;
			while($rowP = mpress_fetch_array($query)){
				$i++;
				if ($rowP['Codigo'] == "") $codigoProduto = "---"; else $codigoProduto = $rowP['Codigo'];

				if($rowP['Imagem'] == ""){
					$produto_id = $rowP['Produto_ID'];


					$sql = "select count(*) as Produtos from produtos_variacoes pv where pv.Produto_ID = ".$produto_id;
					$queryCount = mpress_query($sql);

					if($rowCount = mpress_fetch_array($queryCount)){

						if($rowCount['Produtos'] == 1){
							$sql = 'select Nome_Arquivo from modulos_anexos where Chave_Estrangeira ='.$produto_id.' AND Nome_Arquivo like "%jpg" AND Situacao_ID = 1';
							$queryImage = mpress_query($sql);

							if($rowImage = mpress_fetch_array($queryImage)){
								if($rowImage['Nome_Arquivo'] == "")
									$imagemProduto = "../../images/geral/imagem-produto.jpg";
								else
									$imagemProduto = "../../uploads/".$rowImage['Nome_Arquivo'];
							}
						}
						if($rowCount['Produtos'] > 1){
							$imagemProduto = "../../images/geral/imagem-produto.jpg";
						}
					}
				}else{
					$imagemProduto = "../../uploads/".$rowP['Imagem'];
				}

				$totalGeral += $rowP['Valor'];

				//					<td style="border:1px solid" align="center"><img src="'.$imagemProduto.'" height="50"></td>
				//					<td style="border:1px solid" align="center">'.$codigoProduto.'</td>

				$produtos .= '	<tr>
									<td style="border:1px solid" align="left">'.$rowP['Descricao'].'</td>
									<td style="border:1px solid;" align="center">'.number_format($rowP['Quantidade'], 2, ',', '.').'</td>
									<td style="border:1px solid" align="left"> R$ '.number_format($rowP['vUnitario'], 2, ',', '.').'</td>
									<td style="border:1px solid" align="left"> R$ '.number_format($rowP['Valor'], 2, ',', '.').'</td>
								</tr>';
			}
			if($i==0){
				$produtos .= '	<tr>
									<td style="border:1px solid" align="center" colspan="4"><strong>NENHUM PRODUTO ADICIONADO</strong></td>
								</tr>';
			}
			else{
				$produtos .= '	<tr>
									<td style="border:1px solid" colspan="5"><b>TOTAL GERAL</b></td>
									<td style="border:1px solid" align="left"> R$ '.number_format($totalGeral, 2, ',', '.').'</td>
								</tr>';
			}
			$produtos .="	</table>";

			/* Tabela de Tarefas */
			$sql = "SELECT cwt.Workflow_Tarefa_ID, cwt.Descricao, tf.Descr_Tipo AS Tipo_Tarefa, ts.Descr_Tipo AS Situacao, DATE_FORMAT(cwt.Data_Retorno, '%e/%c/%y - %H:%i') AS Data_Retorno
					FROM chamados_workflows_tarefas cwt
					INNER JOIN tipo tf ON cwt.Tipo_Tarefa_ID = tf.Tipo_ID
					INNER JOIN tipo ts ON cwt.Situacao_ID = ts.Tipo_ID
					WHERE Workflow_ID = '".$row['Workflow_ID']."'";
			$query = mpress_query($sql);
			$tarefas .= '	<table cellspacing="0" cellpadding="5" width="737px" align="center" style="max-width:737px; min-width:737px">
								<tr>
									<td style="border:1px solid" align = "center"><b>Tarefa</b></td>
									<td style="border:1px solid" align = "center"><b>Tipo</b></td>
									<td style="border:1px solid" align = "center"><b>Descrição</b></td>
									<td style="border:1px solid" align = "center"><b>Situação</b></td>
									<td style="border:1px solid" align = "center"><b>Data de Retorno</b></td>
									<td style="border:1px solid" align = "center"><b>Horas Utilizadas</b></td>
								</tr>';
			while($rowT = mpress_fetch_array($query)){
				$idTarefa = $rowT['Workflow_Tarefa_ID'];
				$tarefas .= '	<tr>
									<td style="border:1px solid" align = "center">'.$idTarefa.'</td>
									<td style="border:1px solid" align = "center">'.$rowT['Tipo_Tarefa'].'</td>
									<td style="border:1px solid" align = "center">'.$rowT['Descricao'].'</td>
									<td style="border:1px solid" align = "center">'.$rowT['Situacao'].'</td>
									<td style="border:1px solid" align = "center">'.$rowT['Data_Retorno'].'</td>';

				$sql = "SELECT TIMEDIFF(Hora_Fim, Hora_Inicio) AS Total_Horas FROM chamados_workflows_tarefas_follows WHERE Workflow_Tarefa_ID = '".$rowT['Workflow_Tarefa_ID']."'";
				$queryH = mpress_query($sql);
				$horasTotais ='';
				$minutosTotais='';
				while($rowH = mpress_fetch_array($queryH)){
					$horasInicio 	= substr($rowH['Total_Horas'], 0, strlen($rowH['Total_Horas'])-3);
					$horasTarefa 	= substr($horasInicio, 0, strlen($horasInicio)-3);
					$minutosTarefa 	= substr($horasInicio, -2);
					$horasTotais 	+= $horasTarefa;
					$minutosTotais 	+= $minutosTarefa;
				}
				if(($horasTotais !=0) or ($minutosTotais !=0)){
					$horasMinutos 	= (int)($minutosTotais/60);
					$minutosTotais  = $minutosTotais%60;
					$horasTotais	+= $horasMinutos;

					if($minutosTotais !=0)
						$tarefas .='	<td style="border:1px solid" align = "center">'.$horasTotais.' horas e '.$minutosTotais.' minutos</td>';
					else
						$tarefas .='	<td style="border:1px solid" align = "center">'.$horasTotais.' horas</td>';
				}
				else{
					$tarefas .='	<td style="border:1px solid" align = "center"><b>NENHUMA HORA UTILIZADA</b></td>';
				}
				$tarefas.= '	</tr>';
			}
			if($idTarefa == '')
				$tarefas .='	<tr>
									<td style="border:1px solid" align = "center" colspan="6"><strong>NENHUMA TAREFA ADICIONADA</strong></td>
								</tr>';
			$tarefas .= "	</table>";

			/* Situação */
			$sql = "SELECT tcf.Descr_Tipo FROM chamados_follows cf INNER JOIN tipo tcf ON cf.Situacao_ID = tcf.Tipo_ID
					WHERE cf.Workflow_ID = '".$row['Workflow_ID']."' AND Data_Cadastro = (SELECT MAX(Data_Cadastro) FROM chamados_follows WHERE Workflow_ID = '".$row['Workflow_ID']."')";
			$query = mpress_query($sql);
			if($rowSituacao = mpress_fetch_array($query)){
				$situacao = $rowSituacao['Descr_Tipo'];
			}

			/* Endereço Principal Solicitante */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Tipo_Endereco_ID = 26 AND Situacao_ID = 1";
			$query = mpress_query($sql);
			if($rowEnd = mpress_fetch_array($query))
				$enderecoPrincipalSol .= $rowEnd[Logradouro].", ".$rowEnd[Numero]." ".$rowEnd[Complemento]." - ".$rowEnd[Bairro]." - ".$rowEnd[Cidade]." - ".$rowEnd[UF]." - ".$rowEnd[CEP];

			/* Endereço Principal Quebrado Solicitante */
			$bairroSol 		= $rowEnd[Bairro];
			$cepSol 		= $rowEnd[CEP];
			$cidadeSol 		= $rowEnd[Cidade];
			$complementoSol = $rowEnd[Complemento];
			$logradouroSol 	= $rowEnd[Logradouro];
			$numeroSol 		= $rowEnd[Numero];
			$ufSol 			= $rowEnd[UF];

			/* Endereços Solicitante */
			$barra = "";
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowEnd = mpress_fetch_array($query)){
				$enderecoSol .= $barra.$rowEnd[Logradouro].", ".$rowEnd[Numero]." ".$rowEnd[Complemento]." - ".$rowEnd[Bairro]." - ".$rowEnd[Cidade]." - ".$rowEnd[UF]." - ".$rowEnd[CEP];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Telefones Solicitante */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefonesSol .= $barra.$rowTel[Telefone];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Telefone Residencial Solicitante */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 29";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneResSol .= $barra.$rowTel[Telefone];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Telefone Celular Solicitante */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 28";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneCelSol .= $barra.$rowTel[Telefone];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Telefone Comercial Solicitante */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['ID_Solicitante']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 27";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneComSol .= $barra.$rowTel[Telefone];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Areas de Atuação Solicitante */
			$arrayAreas = unserialize($dadosarquivo['Areas_Atuacoes_Sol']);
			for($i=0; $i<sizeof($arrayAreas); $i++){
				$query = mpress_query("Select Descr_Tipo from tipo where Tipo_ID = '".$arrayAreas[$i]."'");
				$rowAreasSol = mpress_fetch_array($query);
				$descAreasSol .= $virgula.$rowAreasSol[Descr_Tipo];
				$virgula = ",&nbsp;";
			}

			$conteudo = str_replace('[codigo]', utf8_encode($row['Codigo']), $conteudo);
			$conteudo = str_replace('[tipo]', utf8_encode($row['Descr_Tipo']), $conteudo);
			$conteudo = str_replace('[data-abertura]', utf8_encode($row['Data_Abertura']), $conteudo);
			$conteudo = str_replace('[id]', utf8_encode($row['Workflow_ID']), $conteudo);
			$conteudo = str_replace('[produtos]', utf8_encode($produtos), $conteudo);
			$conteudo = str_replace('[tarefas]', utf8_encode($tarefas), $conteudo);
			$conteudo = str_replace('[situacao]', utf8_encode($situacao), $conteudo);
			$conteudo = str_replace('[prioridade]', utf8_encode($row['Prioridade']), $conteudo);

			//SOLICITANTE
			$conteudo = str_replace('[solicitante-areas-atuacao]', utf8_encode($descAreasSol), $conteudo);
			$conteudo = str_replace('[solicitante-codigo]', utf8_encode($row['Codigo_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-id]', utf8_encode($row['ID_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-cpf-cnpj]', utf8_encode($row['CPF_CNPJ_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-email]', utf8_encode($row['Email_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-nome-razao-social]', utf8_encode($row['Nome_Razao_Social_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-inscricao-municipal]', utf8_encode($row['Inscr_Mun_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-inscricao-estadual]', utf8_encode($row['Incri_Est_Solicitante']), $conteudo);
			$conteudo = str_replace('[solicitante-nome-fantasia]', utf8_encode($row['Nome_Fantasia_Solicitante']), $conteudo);
			$conteudo	= str_replace('[solicitante-endereco-principal]', utf8_encode($enderecoPrincipalSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-bairro]', utf8_encode($bairroSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-cep]', utf8_encode($cepSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-cidade]', utf8_encode($cidadeSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-complemento]', utf8_encode($complementoSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-logradouro]', utf8_encode($logradouroSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-numero]', utf8_encode($numeroSol), $conteudo);
			$conteudo	= str_replace('[solicitante-principal-uf]', utf8_encode($ufSol), $conteudo);
			$conteudo	= str_replace('[solicitante-enderecos]', utf8_encode($enderecoSol), $conteudo);
			$conteudo	= str_replace('[solicitante-telefones]', utf8_encode($telefonesSol), $conteudo);
			$conteudo	= str_replace('[solicitante-telefone-residencial]', utf8_encode($telefoneResSol), $conteudo);
			$conteudo	= str_replace('[solicitante-telefone-celular]', utf8_encode($telefoneCelSol), $conteudo);
			$conteudo	= str_replace('[solicitante-telefone-comercial]', utf8_encode($telefoneComSol), $conteudo);
		}


		/*********************************/
		/*********** OTICA ***************/
		/*********************************/

		if(strstr($conteudo,'[produtos-agrupados-cliente-final]')!=""){
			unset($dados);
			$sql = "SELECT CONCAT(pd.Nome,' ', pv.Descricao) AS Produto, cwp.Quantidade, cwp.Valor_Venda_Unitario, co.Nome AS Colaborador,
						co.Codigo, co.Foto AS FotoColaborador, cwp.Cliente_Final_ID, cw.Workflow_ID AS Workflow_ID,
						cw.Solicitante_ID as Solicitante_ID, s.Nome as Solicitante, s.Email as Email, co.Cadastro_ID as Colaborador_ID, mf.Dados as DadosColaborador
						FROM chamados_workflows cw
						INNER JOIN chamados_workflows_produtos cwp ON cwp.Workflow_ID = cw.Workflow_ID
						INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
						INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
						INNER JOIN cadastros_dados s on s.Cadastro_ID = cw.Solicitante_ID
						LEFT JOIN cadastros_dados co ON co.Cadastro_ID = cwp.Cliente_Final_ID
						LEFT JOIN modulos_formularios mf ON mf.Slug = 'formulario-otica-colaborador' AND mf.Tabela_Estrangeira = 'cadastros_dados' AND mf.Chave_Estrangeira = cwp.Cliente_Final_ID AND mf.Situacao_ID = 1
						WHERE cw.Workflow_ID = '$id' and cwp.Situacao_ID = 1
						ORDER BY co.Nome, cwp.Cliente_Final_ID";
			// $hProdCli = $sql;
			$quantidadeTotal = 0;
			$valorTotal = 0;
			$resultSet = mpress_query($sql);
			while($rs = mpress_fetch_array($resultSet)){
				$dadosColaborador = unserialize($rs['DadosColaborador']);
				if ($colaboradorIDAnt != $rs['Colaborador_ID']){
					if ($flagColab==1){
						$hProdCli .= '</table><p>&nbsp;</p>';
					}
					$flagColab = 1;
					if ($foto!="")
						$imagemFoto = '<img src="'.$caminhoSistema.'/uploads/'.$foto.'" style="height:40px; cursor:pointer;">';
					else
						$imagemFoto = '<img src="'.$caminhoSistema.'/images/geral/imagem-usuario.jpg" style="height:40px; cursor:pointer;">';

					$txtNaoInfo = "N&atilde;o Informado";
					$turno = "";
					if ($dadosColaborador['turno']=='C')
						$turno = 'Comercial';
					if ($dadosColaborador['turno']=='M')
						$turno = 'Manh&atilde;';
					if ($dadosColaborador['turno']=='T')
						$turno = 'Tarde';


					$hProdCli .= '	<div Style="page-break-after: always;">&nbsp;</div>';
					$hProdCli .= '	<table width="100%" style="border:0px; font-family: arial; font-size: 10px; margin-top:20px;" cellpadding="2" cellspacing="2">
										<tr>
											<td colspan="4" style="height:25px; background-color:#C0C0C0; text-align:center;"><b>'.utf8_encode($rs['Colaborador']).'</b></td>
										</tr>
										<tr>
											<td style="text-align:center; width:80px" rowspan="2">
												'.$imagemFoto.'
											</td>
											<td>
												<b>N&ordm; de Registro:</b> <br>'.utf8_encode($rs['Codigo']).'&nbsp;
											</td>
											<td>
												<b>Cargo:</b> <br>'.$dadosColaborador['cargo'].'&nbsp;
											</td>
											<td>
												<b>Telefone:</b> <br>'.$dadosColaborador['telefone'].'&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												<b>Email:</b> <br>'.$dadosColaborador['email'].'&nbsp;
											</td>
											<td>
												<b>Centro Custo:</b> <br>'.$dadosColaborador['centro-custo'].'&nbsp;
											</td>
											<td>
												<b>Turno:</b> <br>'.$turno.'&nbsp;
											</td>
										</tr>';
				$hProdCli .= '		<tr>
										<td colspan="2"  style="background-color:#C0C0C0;"><b>Produto</b></td>
										<td colspan="1"  style="background-color:#C0C0C0; text-align:center;"><b>Quantidade</b></td>
										<td colspan="1"  style="background-color:#C0C0C0; text-align:center;"><b>Valor</b></td>
									</tr>';
				}
				$hProdCli .= '		<tr>
										<td colspan="2">'.utf8_encode($rs['Produto']).'</td>
										<td colspan="1" style="text-align:center;">'.number_format($rs['Quantidade'],0,'','').'</td>
										<td colspan="1" style="text-align:center;">R$'.number_format($rs['Valor_Venda_Unitario'], 2, ',', '.').'</td>
									</tr>';

				$quantidadeTotal += $rs['Quantidade'];
				$valorTotal += $rs['Quantidade'] * $rs['Valor_Venda_Unitario'];
				$colaboradorIDAnt = $rs['Colaborador_ID'];
			}
			$hProdCli .= '</table><p>&nbsp;</p>';

			$hProdCli = '<table width="100%" style="border:0px; font-family: arial; font-size: 10px; margin-top:20px;" cellpadding="2" cellspacing="2">
							<tr>
								<td colspan="2" style="height:25px; background-color:#C0C0C0; text-align:center;"><b>RESUMO ORDEM DE SERVI&Ccedil;O</b></td>
							</tr>
							<tr>
								<td width="50%" style="text-align:center;"><b>Quantidade total de produtos e servi&ccedil;os</b></td>
								<td width="50%" style="text-align:center;"><b>Valor Total:</b></td>
							</tr>
							<tr>
								<td style="text-align:center;">'.$quantidadeTotal.'</td>
								<td style="text-align:center;">R$ '.number_format($valorTotal, 2, ',', '.').'</td>
							</tr>
						 </table>'.$hProdCli;

			$conteudo	= str_replace('[produtos-agrupados-cliente-final]', $hProdCli, $conteudo);
		}

	}

	if ($origem=="compras"){
		$sql = "SELECT coc.Ordem_Compra_ID, coc.Data_Cadastro, coc.Data_Limite_Retorno,
					cd.Nome
					FROM compras_ordem_compra coc
					INNER JOIN cadastros_dados cd ON cd.Cadastro_ID = coc.Usuario_Cadastro_ID
				WHERE coc.Ordem_Compra_ID = '$id';";
		$query = mpress_query($sql);
		if($row = mpress_fetch_array($query)){
			/* Produtos */
			$sql = "SELECT pd.Nome, pd.Descricao_Resumida, pd.Codigo, cs.Quantidade FROM compras_ordens_compras_produtos cocp
						INNER JOIN compras_solicitacoes cs ON cs.Compra_Solicitacao_ID = cocp.Compra_Solicitacao_ID
						INNER JOIN produtos_dados pd ON pd.Produto_ID = cs.Produto_Variacao_ID
					WHERE cocp.Ordem_Compra_ID = '".$row['Ordem_Compra_ID']."'";
			$query = mpress_query($sql);
			$produtos = '	<table cellspacing="0" cellpadding="5">
								<tr>
									<td style="border: 1px solid;" align="center"><strong>Código</strong></td>
									<td style="border: 1px solid;" align="center"><strong>Nome</strong></td>
									<td style="border: 1px solid;" align="center"><strong>Descrição</strong></td>
									<td style="border: 1px solid;" align="center"><strong>Quantidade</strong></td>
								</tr>';
			while($rowP = mpress_fetch_array($query)){
				$produtos .='	<tr>
									<td style="border: 1px solid;" align="center">'.$rowP['Codigo'].'</td>
									<td style="border: 1px solid;" align="left">'.$rowP['Nome'].'</td>
									<td style="border: 1px solid;" align="left">'.$rowP['Descricao_Resumida'].'</td>
									<td style="border: 1px solid;" align="center">'.$rowP['Quantidade'].'</td>
								</tr>';
			}
			$produtos .='	</table>';

			/* Situação Ordem Compra */
			$sql = "SELECT t.Descr_Tipo FROM tipo t INNER JOIN compras_ordem_compra_follows cocf ON cocf.Situacao_ID = t.Tipo_ID
			WHERE cocf.Ordem_Compra_ID = '".$row['Ordem_Compra_ID']."' AND cocf.Data_Cadastro = (SELECT MAX(Data_Cadastro) FROM compras_ordem_compra_follows WHERE Ordem_Compra_ID = '".$row['Ordem_Compra_ID']."')";
			$query = mpress_query($sql);
			$rowSit = mpress_fetch_array($query);

			$conteudo = str_replace('[data-cadastro-compra]', utf8_encode($row['Data_Cadastro']), $conteudo);
			$conteudo = str_replace('[data-limite-compra]', utf8_encode($row['Data_Limite_Retorno']), $conteudo);
			$conteudo = str_replace('[id-compra]', utf8_encode($row['Ordem_Compra_ID']), $conteudo);
			$conteudo = str_replace('[nome-responsavel]', utf8_encode($row['Nome']), $conteudo);
			$conteudo = str_replace('[produtos]', utf8_encode($produtos), $conteudo);
			$conteudo = str_replace('[situacao-compra]', utf8_encode($rowSit['Descr_Tipo']), $conteudo);
		}
	}

	if ($origem=="envios"){
		/*$sql = "select ew.Workflow_ID as Workflow_ID, Chave_Estrangeira, ew.Codigo_Rastreamento, tf.Descr_Tipo as Forma_Envio,
					ew.Cadastro_ID_de, cdde.Codigo as Codigo_de, cdde.Nome as Nome_de, cdde.Cpf_Cnpj as Cpf_Cnpj_de, cdde.Foto as Foto_de,
					ew.Cadastro_ID_para, cdpara.Codigo as Codigo_para, cdpara.Nome as Nome_para, cdpara.Cpf_Cnpj as Cpf_Cnpj_para, cdpara.Foto as Foto_para,
					ew.Transportadora_ID, cdtrans.Codigo as Codigo_trans, cdtrans.Nome as Nome_trans, cdtrans.Cpf_Cnpj as Cpf_Cnpj_trans, cdtrans.Foto as Foto_trans,
					cede.CEP as CEP_de, cede.Logradouro as Logradouro_de, cede.Numero as Numero_de, cede.Complemento as Complemento_de, cede.Bairro as Bairro_de, cede.Cidade as Cidade_de, cede.UF as UF_de,
					cepara.CEP as CEP_para, cepara.Logradouro as Logradouro_para, cepara.Numero as Numero_para, cepara.Complemento as Complemento_para, cepara.Bairro as Bairro_para, cepara.Cidade as Cidade_para, cepara.UF as UF_para,
					DATE_FORMAT(ew.Data_Envio,'%d/%m/%Y') as Data_Envio,
							DATE_FORMAT(ew.Data_Previsao,'%d/%m/%Y') as Data_Previsao,
							DATE_FORMAT(ew.Data_Entrega,'%d/%m/%Y') as Data_Entrega
							  from envios_workflows ew
							inner join cadastros_dados cdde on cdde.Cadastro_ID = ew.Cadastro_ID_de
							left join tipo tf on tf.Tipo_ID = ew.Forma_Envio_ID
							left join cadastros_dados cdpara on cdpara.Cadastro_ID = ew.Cadastro_ID_para
							left join cadastros_dados cdtrans on cdtrans.Cadastro_ID = ew.Transportadora_ID
							left join cadastros_enderecos cede on cede.Cadastro_Endereco_ID = ew.Cadastro_ID_de_Endereco
							left join cadastros_enderecos cepara on cede.Cadastro_Endereco_ID = ew.Cadastro_ID_para_Endereco
					where ew.Workflow_ID = $id";
		*/

		$sql ="SELECT ew.Workflow_ID AS Workflow_ID, Chave_Estrangeira, Tabela_Estrangeira, ew.Codigo_Rastreamento, tf.Descr_Tipo AS Forma_Envio, ew.Data_Envio, ew.Data_Previsao, ew.Data_Entrega, ew.Cadastro_ID_de_Endereco AS Origem, ew.Cadastro_ID_para_Endereco AS Destino,
					ew.Cadastro_ID_de, cdde.Codigo AS Codigo_de, cdde.Nome AS Nome_de, cdde.Cpf_Cnpj AS Cpf_Cnpj_de, cdde.Foto AS Foto_de, cdde.Email AS Email_de, cdde.Inscricao_Estadual AS Inscricao_Estadual_de, cdde.Nome_Fantasia AS Nome_Fantasia_de,
					ew.Cadastro_ID_para, cdpara.Codigo AS Codigo_para, cdpara.Nome AS Nome_para, cdpara.Cpf_Cnpj AS Cpf_Cnpj_para, cdpara.Foto AS Foto_para, cdpara.Email AS Email_para, cdpara.Inscricao_Estadual AS Inscricao_Estadual_para, cdpara.Nome_Fantasia AS Nome_Fantasia_para,
					ew.Transportadora_ID, cdtrans.Codigo AS Codigo_trans, cdtrans.Nome AS Nome_trans, cdtrans.Cpf_Cnpj AS Cpf_Cnpj_trans, cdtrans.Foto AS Foto_trans, cdtrans.Email AS Email_trans, cdtrans.Inscricao_Estadual AS Inscricao_Estadual_trans, cdtrans.Nome_Fantasia AS Nome_Fantasia_trans
					FROM envios_workflows ew
					INNER JOIN cadastros_dados cdde ON cdde.Cadastro_ID = ew.Cadastro_ID_de
					LEFT JOIN tipo tf ON tf.Tipo_ID = ew.Forma_Envio_ID
					LEFT JOIN cadastros_dados cdpara ON cdpara.Cadastro_ID = ew.Cadastro_ID_para
					LEFT JOIN cadastros_dados cdtrans ON cdtrans.Cadastro_ID = ew.Transportadora_ID
				WHERE ew.Workflow_ID =$id";

		$query = mpress_query($sql);
		if($row = mpress_fetch_array($query)){
			/* Código do Chamado */
			if($row['Tabela_Estrangeira'] == "chamados_workflows"){
				$sql = "select Codigo from chamados_workflows where Workflow_ID = ".$row['Chave_Estrangeira'];
				$query = mpress_query($sql);
				$rowCod = mpress_fetch_array($query);
				$codigoChamado = $rowCod['Codigo'];
			}

			/* Endereço Origem */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_Endereco_ID = '".$row['Origem']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			$rowEnd = mpress_fetch_array($query);
			$enderecoOrigem .= $rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];

			/* Endereço Destino */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_Endereco_ID = '".$row['Destino']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			$rowEnd = mpress_fetch_array($query);
			$enderecoDestino .= $rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];

			/* Situação do envio */
			$sql = "SELECT Descr_Tipo FROM tipo tp INNER JOIN envios_follows ef ON ef.Situacao_ID = tp.Tipo_ID
					WHERE ef.Workflow_ID = '".$row['Workflow_ID']."' AND ef.Data_Cadastro = (SELECT MAX(Data_Cadastro) FROM envios_follows WHERE Workflow_ID = '".$row['Workflow_ID']."')";
			$query = mpress_query($sql);
			$rowTp = mpress_fetch_array($query);
			$situacao = $rowTp['Descr_Tipo'];

			/* Lista Produtos Romaneio */
			$sql = "SELECT pv.Codigo, pv.Descricao, (pv.Altura*pv.Largura*pv.Comprimento) AS Volume , pv.Peso,
						pd.Nome,
						ewp.Quantidade
					FROM envios_workflows_produtos ewp
					INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					WHERE ewp.Workflow_ID = '".$row['Workflow_ID']."'";
			$query = mpress_query($sql);
			$produtosRomaneio = '	<table cellspacing="0" cellpadding="5" style="font-size: 9pt; font-family: `times new roman`, times;" width="555">
										<tr>
											<td style="border:1px solid" align = "center"><b>Quantidade</b></td>
											<td style="border:1px solid" align = "center"><b>Nome</b></td>
											<td style="border:1px solid" align = "center"><b>Código</b></td>
											<td style="border:1px solid" align = "center"><b>Volume(cm³)</b></td>
											<td style="border:1px solid" align = "center"><b>Peso(KG)</b></td>
										</tr>';
			while($rowProdutosRomaneio = mpress_fetch_array($query)){
				$volumeTabela = substr($rowProdutosRomaneio['Volume'], 0, strlen($rowProdutosRomaneio['Volume'])-4);
				if($volumeTabela != 0.00)
					$volumeTabela += 0.01;
				$codProd = $rowProdutosRomaneio['Codigo'];
				$produtosRomaneio .= '	<tr>
											<td style="border-left:1px solid; border-right:1px solid;" align = "center">'.$rowProdutosRomaneio['Quantidade'].'</td>
											<td style="border-left:1px solid; border-right:1px solid;" align = "center">'.$rowProdutosRomaneio['Nome'].'</td>
											<td style="border-left:1px solid; border-right:1px solid;" align = "center">'.$codProd.'</td>
											<td style="border-left:1px solid; border-right:1px solid;" align = "center">'.$volumeTabela.'</td>
											<td style="border-left:1px solid; border-right:1px solid;" align = "center">'.$rowProdutosRomaneio['Peso'].'</td>
										</tr>';
			}
			$produtosRomaneio .= '		<tr>
											<td style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid;" align="center">&nbsp;</td>
											<td style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid;" align="center">&nbsp;</td>
											<td style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid;" align="center">&nbsp;</td>
											<td style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid;" align="center">&nbsp;</td>
											<td style="border-left:1px solid; border-right:1px solid; border-bottom:1px solid;" align="center">&nbsp;</td>
										</tr>
									</table>';

			/* Telefones Remetente */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefonesDe 	.= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Residencial Remetente */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 29";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneResDe 	.= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Celular Remetente */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 28";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneCelDe 	.= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Comercial Remetente */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 27";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneComDe .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Endereço Principal Remetente */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Tipo_Endereco_ID = 26 AND Situacao_ID = 1";
			$query = mpress_query($sql);
			$rowEnd = mpress_fetch_array($query);
			$enderecoPrincipalDe = $rowEnd[Logradouro].", ".$rowEnd[Numero]." ".$rowEnd[Complemento]." - ".$rowEnd[Bairro]." - ".$rowEnd[Cidade]." - ".$rowEnd[UF]." - ".$rowEnd[CEP];

			/* Endereço Principal Quebrado Remetente */
			$bairroDe 		= $rowEnd[Bairro];
			$cepDe 			= $rowEnd[CEP];
			$cidadeDe 		= $rowEnd[Cidade];
			$complementoDe 	= $rowEnd[Complemento];
			$logradouroDe 	= $rowEnd[Logradouro];
			$numeroDe 		= $rowEnd[Numero];
			$ufDe 			= $rowEnd[UF];

			/* Enderecos remetente */
			$barra = "";
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Cadastro_ID_de']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowEnd = mpress_fetch_array($query)){
				$enderecoDe .= $barra.$rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];
				$barra = "&nbsp;/&nbsp;";
			}

			/* Telefones Destinatário */
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefonesPara .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Comercial Destinatário */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 27";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneComercialPara .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Celular Destinatário */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 28";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneCelularPara .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Residencial Destinatário */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 29";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneResidencialPara .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Endereços Destinatário */
			$barra = "";
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowEnd = mpress_fetch_array($query)){
				$enderecoPara  .= $barra.$rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];
				$barra 			= "&nbsp;/&nbsp;";
			}

			/* Endereço Principal Destinatário */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Cadastro_ID_para']."' AND Situacao_ID = 1 AND Tipo_Endereco_ID = 26";
			$query = mpress_query($sql);
			$rowEnd = mpress_fetch_array($query);
			$enderecoPrincipalPara = $rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];

			/* Endereço Principal Quebrado Destinatário */
			$bairroPara 		= $rowEnd['Bairro'];
			$cepPara 			= $rowEnd['CEP'];
			$cidadePara 		= $rowEnd['Cidade'];
			$complementoPara 	= $rowEnd['Complemento'];
			$logradouroPara 	= $rowEnd['Logradouro'];
			$numeroPara 		= $rowEnd['Numero'];
			$ufPara 			= $rowEnd['UF'];

			/* Telefones Transportadora */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefonesTrans .= $barra.$rowTel['Telefone'];
				$barra 			 = "&nbsp;/&nbsp;";
			}

			/* Telefone Comercial Transportadora */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 27";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneComercialTrans .= $barra.$rowTel['Telefone'];
				$barra 			 		 = "&nbsp;/&nbsp;";
			}

			/* Telefone Celular Transportadora */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 28";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneCelularTrans  .= $barra.$rowTel['Telefone'];
				$barra 			 		= "&nbsp;/&nbsp;";
			}

			/* Telefone Residencial Transportadora */
			$barra = "";
			$sql = "SELECT Telefone FROM cadastros_telefones WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1 AND Tipo_Telefone_ID = 29";
			$query = mpress_query($sql);
			while($rowTel = mpress_fetch_array($query)){
				$telefoneResTrans .= $barra.$rowTel['Telefone'];
				$barra 			   = "&nbsp;/&nbsp;";
			}

			/* Endereços Transportador */
			$barra = "";
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1";
			$query = mpress_query($sql);
			while($rowEnd = mpress_fetch_array($query)){
				$enderecoTrans .= $barra.$rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];
				$barra 			= "&nbsp;/&nbsp;";
			}

			/* Endereço Principal Transportador */
			$sql = "SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$row['Transportadora_ID']."' AND Situacao_ID = 1 AND Tipo_Endereco_ID = 26";
			$query = mpress_query($sql);
			$rowEnd = mpress_fetch_array($query);
			$enderecoPrincipalTrans = $rowEnd['Logradouro'].", ".$rowEnd['Numero']." ".$rowEnd['Complemento']." - ".$rowEnd['Bairro']." - ".$rowEnd['Cidade']." - ".$rowEnd['UF']." - ".$rowEnd['CEP'];

			/* Endereço Principal Quebrado Transportador */
			$bairroTrans 		= $rowEnd['Bairro'];
			$cepTrans 			= $rowEnd['CEP'];
			$cidadeTrans 		= $rowEnd['Cidade'];
			$complementoTrans 	= $rowEnd['Complemento'];
			$logradouroTrans 	= $rowEnd['Logradouro'];
			$numeroTrans 		= $rowEnd['Numero'];
			$ufTrans 			= $rowEnd['UF'];

			$conteudo	= str_replace('[codigo-chamado]', utf8_encode($codigoChamado), $conteudo);
			$conteudo	= str_replace('[id-envio]', utf8_encode($row['Workflow_ID']), $conteudo);
			$conteudo	= str_replace('[id-origem]', utf8_encode($row['Chave_Estrangeira']), $conteudo);
			$conteudo	= str_replace('[codigo-rastreamento]', utf8_encode($row['Codigo_Rastreamento']), $conteudo);
			$conteudo	= str_replace('[endereco-origem]', utf8_encode($enderecoOrigem), $conteudo);
			$conteudo	= str_replace('[endereco-destino]', utf8_encode($enderecoDestino), $conteudo);
			$conteudo	= str_replace('[forma-envio]', utf8_encode($row['Forma_Envio']), $conteudo);
			$conteudo	= str_replace('[data-envio]', utf8_encode($row['Data_Envio']), $conteudo);
			$conteudo	= str_replace('[data-previsao-entrega]', utf8_encode($row['Data_Previsao']), $conteudo);
			$conteudo	= str_replace('[data-entrega]', utf8_encode($row['Data_Entrega']), $conteudo);
			$conteudo	= str_replace('[codigo-origem]', utf8_encode($row['Codigo_de']), $conteudo);
			$conteudo 	= str_replace('[situacao-envio]', utf8_encode($situacao), $conteudo);
			$conteudo 	= str_replace('[lista-produtos-romaneio]', utf8_encode($produtosRomaneio), $conteudo);

			//REMETENTE
			$conteudo	= str_replace('[codigo-de]', utf8_encode($row['Codigo_de']), $conteudo);
			$conteudo	= str_replace('[nome-razao-social-de]', utf8_encode($row['Nome_de']), $conteudo);
			$conteudo	= str_replace('[cpf-cnpj-de]', utf8_encode($row['Cpf_Cnpj_de']), $conteudo);
			$conteudo	= str_replace('[foto-de]', '<img src="../../uploads/'.utf8_encode($row['Foto_de']).'">', $conteudo);
			$conteudo	= str_replace('[telefones-de]', utf8_encode($telefonesDe), $conteudo);
			$conteudo	= str_replace('[telefone-residencial-de]', utf8_encode($telefoneResDe), $conteudo);
			$conteudo	= str_replace('[telefone-celular-de]', utf8_encode($telefoneCelDe), $conteudo);
			$conteudo	= str_replace('[telefone-comercial-de]', utf8_encode($telefoneComDe), $conteudo);
			$conteudo	= str_replace('[endereco-principal-de]', utf8_encode($enderecoPrincipalDe), $conteudo);
			$conteudo	= str_replace('[bairro-principal-de]', utf8_encode($bairroDe), $conteudo);
			$conteudo	= str_replace('[cep-principal-de]', utf8_encode($cepDe), $conteudo);
			$conteudo	= str_replace('[cidade-principal-de]', utf8_encode($cidadeDe), $conteudo);
			$conteudo	= str_replace('[complemento-principal-de]', utf8_encode($complementoDe), $conteudo);
			$conteudo	= str_replace('[logradouro-principal-de]', utf8_encode($logradouroDe), $conteudo);
			$conteudo	= str_replace('[numero-principal-de]', utf8_encode($numeroDe), $conteudo);
			$conteudo	= str_replace('[uf-principal-de]', utf8_encode($ufDe), $conteudo);
			$conteudo	= str_replace('[enderecos-de]', utf8_encode($enderecoDe), $conteudo);
			$conteudo	= str_replace('[email-de]', utf8_encode($row['Email_de']), $conteudo);
			$conteudo	= str_replace('[inscricao-estadual-de]', utf8_encode($row['Inscricao_Estadual_de']), $conteudo);
			$conteudo	= str_replace('[nome-fantasia-de]', utf8_encode($row['Nome_Fantasia_de']), $conteudo);

			//DESTINATÁRIO
			$conteudo	= str_replace('[codigo-para]', utf8_encode($row['Codigo_para']), $conteudo);
			$conteudo	= str_replace('[nome-razao-social-para]', utf8_encode($row['Nome_para']), $conteudo);
			$conteudo	= str_replace('[cpf-cnpj-para]', utf8_encode($row['Cpf_Cnpj_para']), $conteudo);
			$conteudo	= str_replace('[foto-para]', '<img src="../../uploads/'.utf8_encode($row['Foto_para']).'">', $conteudo);
			$conteudo	= str_replace('[telefones-para]', utf8_encode($telefonesPara), $conteudo);
			$conteudo	= str_replace('[telefone-comercial-para]', utf8_encode($telefoneComercialPara), $conteudo);
			$conteudo	= str_replace('[telefone-celular-para]', utf8_encode($telefoneCelularPara), $conteudo);
			$conteudo	= str_replace('[telefone-residencial-para]', utf8_encode($telefoneResidencialPara), $conteudo);
			$conteudo	= str_replace('[enderecos-para]', utf8_encode($enderecoPara), $conteudo);
			$conteudo	= str_replace('[endereco-principal-para]', utf8_encode($enderecoPrincipalPara), $conteudo);
			$conteudo	= str_replace('[bairro-principal-para]', utf8_encode($bairroPara), $conteudo);
			$conteudo	= str_replace('[cep-principal-para]', utf8_encode($cepPara), $conteudo);
			$conteudo	= str_replace('[cidade-principal-para]', utf8_encode($cidadePara), $conteudo);
			$conteudo	= str_replace('[complemento-principal-para]', utf8_encode($complementoPara), $conteudo);
			$conteudo	= str_replace('[logradouro-principal-para]', utf8_encode($logradouroPara), $conteudo);
			$conteudo	= str_replace('[numero-principal-para]', utf8_encode($numeroPara), $conteudo);
			$conteudo	= str_replace('[uf-principal-para]', utf8_encode($ufPara), $conteudo);
			$conteudo	= str_replace('[email-para]', utf8_encode($row['Email_para']), $conteudo);
			$conteudo	= str_replace('[inscricao-estadual-para]', utf8_encode($row['Inscricao_Estadual_para']), $conteudo);
			$conteudo	= str_replace('[nome-fantasia-para]', utf8_encode($row['Nome_Fantasia_para']), $conteudo);

			//TRANSPORTADOR
			$conteudo	= str_replace('[codigo-trans]', utf8_encode($row['Codigo_trans']), $conteudo);
			$conteudo	= str_replace('[nome-razao-social-trans]', utf8_encode($row['Nome_trans']), $conteudo);
			$conteudo	= str_replace('[cpf-cnpj-trans]', utf8_encode($row['Cpf_Cnpj_trans']), $conteudo);
			$conteudo	= str_replace('[foto-trans]', '<img src="../../uploads/'.utf8_encode($row['Foto_trans']).'">', $conteudo);
			$conteudo	= str_replace('[telefones-trans]', utf8_encode($telefonesTrans), $conteudo);
			$conteudo	= str_replace('[telefone-comercial-trans]', utf8_encode($telefoneComercialTrans), $conteudo);
			$conteudo	= str_replace('[telefone-celular-trans]', utf8_encode($telefoneCelularTrans), $conteudo);
			$conteudo	= str_replace('[telefone-residencial-trans]', utf8_encode($telefoneResTrans), $conteudo);
			$conteudo	= str_replace('[enderecos-trans]', utf8_encode($enderecoTrans), $conteudo);
			$conteudo	= str_replace('[endereco-principal-trans]', utf8_encode($enderecoPrincipalTrans), $conteudo);
			$conteudo	= str_replace('[bairro-principal-trans]', utf8_encode($bairroTrans), $conteudo);
			$conteudo	= str_replace('[cep-principal-trans]', utf8_encode($cepTrans), $conteudo);
			$conteudo	= str_replace('[cidade-principal-trans]', utf8_encode($cidadeTrans), $conteudo);
			$conteudo	= str_replace('[complemento-principal-trans]', utf8_encode($complementoTrans), $conteudo);
			$conteudo	= str_replace('[logradouro-principal-trans]', utf8_encode($logradouroTrans), $conteudo);
			$conteudo	= str_replace('[numero-principal-trans]', utf8_encode($numeroTrans), $conteudo);
			$conteudo	= str_replace('[uf-principal-trans]', utf8_encode($ufTrans), $conteudo);
			$conteudo	= str_replace('[email-trans]', utf8_encode($row['Email_trans']), $conteudo);
			$conteudo	= str_replace('[inscricao-estadual-trans]', utf8_encode($row['Inscricao_Estadual_trans']), $conteudo);
			$conteudo	= str_replace('[nome-fantasia-trans]', utf8_encode($row['Nome_Fantasia_trans']), $conteudo);
		}
	}
	if($origem=="orcamentos"){

		if ($id2!=''){
			$sql = "select Titulo from orcamentos_propostas where Proposta_ID = '$id2'";
			$resultSet = mpress_query($sql);
			if($rs = mpress_fetch_array($resultSet)){
				$nomeArquivoOriginal .= " - ".$rs['Titulo'];
				$conteudo	= str_replace('[titulo-proposta]', $rs['Titulo'], $conteudo);
			}
		}

		$sql = "select nf.Config, o.Workflow_ID, o.Codigo,
						o.Solicitante_ID, s.Nome Solicitante_Nome, s.Nome_Fantasia Solicitante_Fantasia, s.Cpf_Cnpj Solicitante_Cpf_Cnpj, s.Email Solicitante_Email,
						o.Representante_ID, r.Nome as Representante_Nome, r.Nome_Fantasia as Representante_Fantasia, r.Cpf_Cnpj as Representante_Cpf_Cnpj, r.Email Representante_Email,
						s.Sexo as Sexo, s.Observacao as Solicitante_Observacao, s.Codigo as Solicitante_Codigo
					from orcamentos_workflows o
					left join nf_config nf on nf.Empresa_ID = o.Empresa_ID
					left join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
					left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
					where o.Workflow_ID = '$id'";
		$resultSet = mpress_query($sql);
		if($rs = mpress_fetch_array($resultSet)){
			if ($rs['Sexo']=='F') $ref = "a" ; else $ref = "o";
			//if ($rs['Sexo']=='M') $ref = "o";

			$configNF = unserialize($rs['Config']);

			$conteudo	= str_replace('[id]', $rs['Workflow_ID'], $conteudo);
			$conteudo	= str_replace('[orcamento-codigo]', utf8_encode($rs['Codigo']), $conteudo);
			$conteudo	= str_replace('[solicitante-nome]', utf8_encode($rs['Solicitante_Nome']), $conteudo);
			$conteudo	= str_replace('[solicitante-cpf-cnpj]', utf8_encode($rs['Solicitante_Cpf_Cnpj']), $conteudo);
			$conteudo	= str_replace('[solicitante-email]', utf8_encode($rs['Solicitante_Email']), $conteudo);
			$conteudo	= str_replace('[solicitante-codigo]', utf8_encode($rs['Solicitante_Codigo']), $conteudo);

			$conteudo	= str_replace('[solicitante-fantasia]', utf8_encode($rs['Solicitante_Fantasia']), $conteudo);
			$conteudo	= str_replace('[solicitante-observacao]', utf8_encode($rs['Solicitante_Observacao']), $conteudo);
			$conteudo	= str_replace('[ref]', $ref, $conteudo);


			$conteudo	= str_replace('[vendedor-nome]', utf8_encode($rs['Representante_Nome']), $conteudo);
			$conteudo	= str_replace('[vendedor-cpf-cnpj]', utf8_encode($rs['Representante_Cpf_Cnpj']), $conteudo);
			$conteudo	= str_replace('[vendedor-email]', utf8_encode($rs['Representante_Email']), $conteudo);

			if ($rs['Solicitante_ID']!=0){
				$query = mpress_query("SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$rs['Solicitante_ID']."' AND Situacao_ID = 1");
				if ($rsE = mpress_fetch_array($query)){
					$enderecoSolicitante = $rsE[Logradouro].", ".$rsE[Numero]." ".$rsE[Complemento]." - ".$rsE[Bairro]." - ".$rsE[Cidade]." - ".$rsE[UF]." - ".$rsE[CEP];
				}
				$conteudo	= str_replace('[solicitante-endereco]', utf8_encode($enderecoSolicitante), $conteudo);

				$barra = "";
				$query = mpress_query("select Telefone from cadastros_telefones where Cadastro_ID = '".$rs['Solicitante_ID']."' and Situacao_ID = 1");
				while($rsT = mpress_fetch_array($query)){
					$telefonesSolicitante .= $barra.$rsT[Telefone];
					$barra = "&nbsp;/&nbsp;";
				}
				$conteudo	= str_replace('[solicitante-telefones]', utf8_encode($telefonesSolicitante), $conteudo);
			}
			if ($rs['Representante_ID']!=0){
				$query = mpress_query("SELECT Logradouro, Numero, Complemento, Bairro, Cidade, UF, CEP FROM cadastros_enderecos WHERE Cadastro_ID = '".$rs['Representante_ID']."' AND Situacao_ID = 1");
				if ($rsE = mpress_fetch_array($query)){
					$enderecoRepresentante = $rsE[Logradouro].", ".$rsE[Numero]." ".$rsE[Complemento]." - ".$rsE[Bairro]." - ".$rsE[Cidade]." - ".$rsE[UF]." - ".$rsE[CEP];
				}
				$conteudo	= str_replace('[vendedor-endereco]', utf8_encode($enderecoRepresentante), $conteudo);

				$barra = "";
				$query = mpress_query("select Telefone from cadastros_telefones where Cadastro_ID = '".$rs['Representante_ID']."' and Situacao_ID = 1");
				while($rsT = mpress_fetch_array($query)){
					$telefonesRepresentante .= $barra.$rsT[Telefone];
					$barra = "&nbsp;/&nbsp;";
				}
				$conteudo	= str_replace('[vendedor-telefones]', utf8_encode($telefonesRepresentante), $conteudo);
			}
		}

		$icmsPerc = $configNF['percentual_icms_padrao_saida'];
		$ipiPerc = $configNF['percentual_ipi_padrao_saida'];
		$issPerc = $configNF['percentual_issqn_servico_padrao'];

		if ((strstr($conteudo,'[produtos-listagem-completa-impostos]')!="")||(strstr($conteudo,'[produtos-listagem-completa-impostos-com-imagem]')!="")){
			if (strstr($conteudo,'[produtos-listagem-completa-impostos-com-imagem]')!=""){
				$comImagem = 'true';
				$col = 10;
			}
			else{
				$comImagem = 'false';
				$col = 9;
			}


			$sql = "SELECT pd.Codigo as Codigo, CONCAT(COALESCE(pd.Nome),' ', COALESCE(pv.Descricao)) AS Produto, tp.Descr_Tipo AS Tipo,
						opp.Quantidade, opp.Valor_Venda_Unitario, pd.Tipo_Produto AS Tipo_Produto, ma.Nome_Arquivo, pd.NCM as NCM, op.Titulo as Titulo_Proposta
						FROM orcamentos_propostas_produtos opp
						INNER JOIN orcamentos_propostas op ON op.Proposta_ID = opp.Proposta_ID
						INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
						INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
						INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
						LEFT JOIN modulos_anexos ma ON ma.Anexo_ID = pv.Imagem_ID
						WHERE opp.Proposta_ID = '$id2' AND opp.Situacao_ID = 1
						ORDER BY Tipo, Produto";

			$resultSet = mpress_query($sql);
			$htmlProdOrcComp = '<table width="100%" style="border:1px solid silver; font-family: arial; font-size: 10px;" cellpadding="2" cellspacing="2">
									<tr style="background-color: #c9c9c9; height:40px">
										<td colspan="'.$col.'" align="center" style="height:40px">
											<b align="right">Produtos / Serviços</b>
										</td>
									</tr>
									<tr style="background-color: #c9c9c9; height:40px">
										<td align="center"><b>N&deg;</b></td>';
			if ($comImagem=='true'){
				$htmlProdOrcComp .= '	<td><b>&nbsp;</b></td>';
			}
			$htmlProdOrcComp .= '		<td><b>Código / Descrição</b></td>
										<td align="center"><b>Qtde</b></td>
										<td align="center"><b>Valor Unitário</b></td>
										<td align="center"><b>NCM</b></td>
										<td align="center"><b>ICMS</b></td>
										<td align="center"><b>IPI</b></td>
										<td align="center"><b>ISS</b></td>
										<td align="center"><b>Total</b></td>
									</tr>';
			$i = 0;
			while($rs = mpress_fetch_array($resultSet)){
				$propostaTitulo = $rs[Titulo_Proposta];
				$i++;
				$nomeArquivo = "../../images/geral/imagem-produto.jpg";
				if ($rs['Nome_Arquivo']!="") $nomeArquivo = "../../uploads/".$rs[Nome_Arquivo];
				$totProd = ($rs['Valor_Venda_Unitario'] * $rs['Quantidade']);
				$totProdGeral += $totProd;
				$htmlProdOrcComp .= '	<tr>
										<td align="right">'.$i.'&nbsp;</td>';
				if ($comImagem=='true'){
					$htmlProdOrcComp .= '<td align="center"><img style="max-width:50px; max-height:50px; vertical-align:middle;" src="'.$nomeArquivo.'"/></td>';
				}
				$htmlProdOrcComp .= '	<td>'.trim($rs['Codigo'].' '.$rs['Produto']).'</td>
										<td align="right">'.number_format($rs['Quantidade'], 2, ',', '.').'</td>
										<td align="right">'.number_format($rs['Valor_Venda_Unitario'], 2, ',', '.').'</td>
										<td align="center">'.$rs['NCM'].'</td>
										<td align="right">'.number_format((($totProd * $icmsPerc)/100), 2, ',', '.').'</td>
										<td align="right">'.number_format((($totProd * $ipiPerc)/100), 2, ',', '.').'</td>
										<td align="right">'.number_format((($totProd * $issPerc)/100), 2, ',', '.').'</td>
										<td align="right">'.number_format($totProd, 2, ',', '.').'</td>
									 </tr>';
			}
			$htmlProdOrcComp .= '	 <tr style="background-color: #c9c9c9; height:40px">
									<td colspan="'.($col-1).'" align="right"><b>Total Geral:&nbsp;</b></td>
									<td align="right"><b>'.number_format($totProdGeral, 2, ',', '.').'</b></td>
								 </tr>';


			$htmlProdOrcComp .= '</table>';
			$conteudo	= str_replace('[produtos-listagem-completa-impostos]', utf8_encode($htmlProdOrcComp), $conteudo);
			$conteudo	= str_replace('[produtos-listagem-completa-impostos-com-imagem]', utf8_encode($htmlProdOrcComp), $conteudo);
			$conteudo	= str_replace('[titulo-proposta]', utf8_encode($propostaTitulo), $conteudo);
		}

		/***********************/

		if((strstr($conteudo,'[produtos-servicos-categorizados]')!="")||(strstr($conteudo,'[produtos-servicos-categorizados-sem-extras]')!="")){

			function exibirTotaisSubCategoria($totalSub){
				return '	<tr style="background-color: #f1f1f1;">
								<td colspan="3" align="right"><b>Sub-Total:</b></td>
								<td align="right"><b>'.number_format($totalSub, 2, ',', '.').'</b></td>
							</tr>';
			}

			if ($id2!=""){
				$sql = "select ow.Workflow_ID, ow.Solicitante_ID, ow.Representante_ID,
						op.Proposta_ID, op.Workflow_ID, op.Titulo, op.Tabela_Preco_ID
						from orcamentos_propostas op
						inner join orcamentos_workflows ow on ow.Workflow_ID = op.Workflow_ID
						and op.Proposta_ID = '$id2'";
				$resultSet1 = mpress_query($sql);
				if($rs1 = mpress_fetch_array($resultSet1)){
					$cadastroID = $rs1["Solicitante_ID"];
					if ($cadastroID < 0){
						$sql = "select Turma_ID from turmas_dados where Cadastro_ID = $cadastroID";
						$resultSet2 = mpress_query($sql);
						if($rs2 = mpress_fetch_array($resultSet2)){
							$turmaID = $rs2["Turma_ID"];
						}
					}
				}
			}


			if ($id2!=""){
				$totalGeral = 0;
				/* DEPOIS ARRUMAR ISSO ABAIXO DIREITO, NÃO MOSTRAR PRODUTOS COMISSIONADOS */
				if(strstr($conteudo,'[produtos-servicos-categorizados-sem-extras]')!=""){
					$sqlCond = " and pcFilho.Categoria_ID not in (select Categoria_ID from produtos_categorias where upper(Nome) like '%EXTRAS%') ";
				}
				$sql = "select coalesce(pcPai.Categoria_ID, pcFilho.Categoria_ID) as CategoriaPaiID, coalesce(pcPai.Nome,pcFilho.Nome) as CategoriaPai,
									concat(coalesce(pd.Nome),' ', coalesce(pv.Descricao)) as Produto, tp.Descr_Tipo as Tipo,
									pcFilho.Nome as CategoriaFilho, pcFilho.Categoria_ID as CategoriaFilhoID,
									opp.Quantidade, opp.Valor_Venda_Unitario, ope.Data_Evento, pd.Tipo_Produto as Tipo_Produto, ma.Nome_Arquivo
									from orcamentos_propostas_produtos opp
									inner join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
									inner join produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
									inner join tipo tp on tp.Tipo_ID = pd.Tipo_Produto
									inner join produtos_dados_categorias pdc on pdc.Produto_Categoria_ID = opp.Produto_Categoria_ID
									inner join produtos_categorias pcFilho on pcFilho.Categoria_ID = pdc.Categoria_ID
									left join produtos_categorias pcPai on pcPai.Categoria_ID = pcFilho.Categoria_Pai_ID
									left join orcamentos_propostas_eventos ope on ope.Proposta_ID = opp.Proposta_ID and ope.Proposta_Produto_ID = opp.Proposta_Produto_ID and ope.Situacao_ID = 1
									left join modulos_anexos ma on ma.Anexo_ID = pv.Imagem_ID
									where opp.Proposta_ID = '$id2' and opp.Situacao_ID = 1
									$sqlCond
									order by CategoriaPai, CategoriaPaiID, CategoriaFilho";
				//echo $sql;
				$resultSet = mpress_query($sql);
				/*
				$cabecalhoProd = '		<tr style="background-color: #f1f1f1;">
										<td>Itens</td>
										<td align="right">Quantidade</td>
										<td align="right">Valor Unit&aacute;rio</td>
										<td align="right">Valor</td>
									</tr>';
				*/
				$cabecalhoProd = '		<tr style="background-color: #f1f1f1;">
										<td>Itens</td>
										<td align="center">Quantidade</td>
										<td align="right">&nbsp;</td>
										<td align="right">&nbsp;</td>
									</tr>';

				//$htmlListaProdCat .= '	<table width="100%" style="border:1px solid silver; font-family: arial; font-size: 9px;" cellpadding="2" cellspacing="2">';
				$flagTable = 0;
				while($rs = mpress_fetch_array($resultSet)){
					$nomeArquivo = "../../images/geral/imagem-produto.jpg";

					if ($rs['Nome_Arquivo']!="") $nomeArquivo = "../../uploads/".$row[Nome_Arquivo];
					if ($rs['CategoriaPaiID']!=$categoriaPaiIDAnt){
						if (($categoriaPaiIDAnt!="")&&($tipoProdutoIDAnt!=140)) $htmlListaProdCat .= exibirTotaisSubCategoria($totalSubCategoria);
						if ($flagTable==1){
							$htmlListaProdCat .= '		<tr style="background-color: #c9c9c9;">
														<td colspan="3" align="right"><b>Total:</b></td>
														<td align="right"><b>'.number_format($totalCategoria[$categoriaPaiIDAnt], 2, ',', '.').'</b></td>
													</tr>
												</table>';
						}
						$htmlListaProdCat .= '		<div>&nbsp;</div>
												<table width="100%" style="border:1px solid silver; font-family: arial; font-size: 9px;" cellpadding="2" cellspacing="2">
												<tr style="background-color: #c9c9c9;">
													<td colspan="4" align="center" height="40px"><b>'.$rs['CategoriaPai'].'</b></td>
												</tr>';
						$totalSubCategoria = 0;
						$flagTable=1;
					}
					if (($rs['CategoriaFilhoID']!=$categoriaFilhoIDAnt) && ($rs['CategoriaFilhoID']!=$rs['CategoriaPaiID'])){
						if (($categoriaFilhoIDAnt!="")&&($tipoProdutoIDAnt!=140)) $htmlListaProdCat .= exibirTotaisSubCategoria($totalSubCategoria);
						$htmlListaProdCat .= '	<tr style="background-color: #f1f1f1;">
													<td colspan="4" align="center"><b>'.$rs['CategoriaFilho'].'</b></td>
												</tr>'.$cabecalhoProd;
						$totalSubCategoria = 0;
					}
					if ($rs['Tipo_Produto']=="140"){
						if(strstr($conteudo,'[produtos-servicos-categorizados-sem-extras]')==""){
							/*
							$htmlListaProdCat .= '	<tr style="background-color: #f1f1f1;">
														<td colspan="3"><b>LOCAL EVENTO:</b></td>
														<td colspan="1" align="right">Valor</td>
													</tr>
													<tr style="background-color: #f1f1f1;">
														<td colspan="3" ><img style="max-width:20px; max-height:20px; vertical-align:middle; margin-right:5px;" src="'.$nomeArquivo.'"/>'.$rs['Produto'].'</td>
														<td align="right">'.number_format($rs['Valor_Venda_Unitario'], 2, ',', '.').'</td>
													</tr>';
							$totalSubCategoria += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
							$totalGeral += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
							$totalCategoria[$rs['CategoriaPaiID']] += $rs['Quantidade'] * $rs['Valor_Venda_Unitario'];
							*/
							$htmlListaProdCat .= '	<tr style="background-color: #f1f1f1;">
														<td colspan="3"><b>LOCAL EVENTO:</b></td>
														<td colspan="1" align="right">Valor</td>
													</tr>
													<tr style="background-color: #f1f1f1;">
														<td colspan="3" ><img style="max-width:20px; max-height:20px; vertical-align:middle; margin-right:5px;" src="'.$nomeArquivo.'"/>'.$rs['Produto'].'</td>
														<td align="right">&nbsp;</td>
													</tr>';
							$totalSubCategoria += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
							$totalGeral += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
							$totalCategoria[$rs['CategoriaPaiID']] += $rs['Quantidade'] * $rs['Valor_Venda_Unitario'];
						}
						else{
							$htmlListaProdCat .= '	<tr style="background-color: #f1f1f1;">
														<td colspan="4"><b>LOCAL EVENTO:</b></td>
													</tr>
													<tr style="background-color: #f1f1f1;">
														<td colspan="4" ><img style="max-width:20px; max-height:20px; vertical-align:middle; margin-right:5px;" src="'.$nomeArquivo.'"/>'.$rs['Produto'].'</td>
													</tr>';
						}
					}
					else{
						/*
						$htmlListaProdCat .= '	<tr>
													<td style="border-bottom:1px #f1f1f1 solid;"><img style="max-width:20px; max-height:20px; vertical-align:middle; margin-right:5px;" src="'.$nomeArquivo.'"/>'.$rs['Produto'].'</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="right">'.number_format($rs['Quantidade'], 0, ',', '.').'</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="right">'.number_format($rs['Valor_Venda_Unitario'], 2, ',', '.').'</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="right">'.number_format(($rs['Quantidade'] * $rs['Valor_Venda_Unitario']), 2, ',', '.').'</td>
												</tr>';
						*/
						$htmlListaProdCat .= '	<tr>
													<td style="border-bottom:1px #f1f1f1 solid;"><img style="max-width:20px; max-height:20px; vertical-align:middle; margin-right:5px;" src="'.$nomeArquivo.'"/>'.$rs['Produto'].'</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="center">'.number_format($rs['Quantidade'], 0, ',', '.').'</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="right">&nbsp;</td>
													<td style="border-bottom:1px #f1f1f1 solid;" align="right">&nbsp;</td>
												</tr>';
						$totalSubCategoria += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
						$totalGeral += ($rs['Quantidade'] * $rs['Valor_Venda_Unitario']);
						$totalCategoria[$rs['CategoriaPaiID']] += $rs['Quantidade'] * $rs['Valor_Venda_Unitario'];
					}

					$categoriaPaiIDAnt = $rs['CategoriaPaiID'];
					$categoriaFilhoIDAnt = $rs['CategoriaFilhoID'];
					$tipoProdutoIDAnt = $rs['Tipo_Produto'];
				}
				if ($tipoProdutoIDAnt!="140"){
					$htmlListaProdCat .= exibirTotaisSubCategoria($totalSubCategoria);
				}
				$htmlListaProdCat .= '		<tr style="background-color: #c9c9c9;">
											<td colspan="3" align="right"><b>Total:</b></td>
											<td align="right"><b>'.number_format($totalCategoria[$categoriaPaiIDAnt], 2, ',', '.').'</b></td>
										</tr>
									</table>';
				//echo $htmlListaProdCat;
			}
			//exit();
			$conteudo	= str_replace('[produtos-servicos-categorizados]', utf8_encode($htmlListaProdCat), $conteudo);
			$conteudo	= str_replace('[produtos-servicos-categorizados-sem-extras]', utf8_encode($htmlListaProdCat), $conteudo);
			$conteudo	= str_replace('[valor-total-orcamento]', utf8_encode(number_format($totalGeral, 2, ',', '.')), $conteudo);
			$conteudo	= str_replace('[valor-total-orcamento-extenso]', utf8_encode(valorPorExtenso($totalGeral)), $conteudo);
		}

		if (strstr($conteudo,'[forma-de-pagamento]')!=""){
			$htmlFP = "";
			$sql = "SELECT Dias_Vencimento, Valor_Vencimento
					FROM orcamentos_propostas_vencimentos
					WHERE Proposta_ID = '$id2' AND Situacao_ID = 1
					ORDER BY Dias_Vencimento";
			$resultSet = mpress_query($sql);
			$i = 0;
			while($rs = mpress_fetch_array($resultSet)){
				$i++;
				$htmlFP .= '<tr>
								<td align="center">'.$i.'</td>
								<td align="center">'.$rs['Dias_Vencimento'].'</td>
								<td align="right">'.number_format($rs['Valor_Vencimento'], 2, ',', '.').'</td>
							</tr>';
			}
			if ($i>0){
				$htmlFP = ' <table width="300px" style="border:0px; font-family: arial; font-size: 10px; margin-top:20px;" cellpadding="2" cellspacing="2">
								<tr style="background-color: #f1f1f1;">
									<td align="center"><b>Parcela</b></td>
									<td align="center"><b>Dias vencimento</b></td>
									<td align="right"><b>Valor</b></td>
								</tr>
								'.$htmlFP.'
							</table>';
			}
			$sql = "SELECT fp.Descr_Tipo as Forma_Pagamento
						FROM orcamentos_propostas op
						INNER JOIN tipo fp
						WHERE fp.Tipo_ID = op.Forma_Pagamento_ID
						AND op.Proposta_ID = '$id2'";
			$resultSet = mpress_query($sql);
			if($rs = mpress_fetch_array($resultSet)){
				$htmlFP = $rs['Forma_Pagamento'].$htmlFP;
			}
			$conteudo	= str_replace('[forma-de-pagamento]', $htmlFP, $conteudo);
		}

		/***************************************/
		/**************** ÓTICA ****************/
		/***************************************/

		if(strstr($conteudo,'[produtos-agrupados-cliente-final]')!=""){
			unset($dados);
			$sql = "SELECT CONCAT(pd.Nome,' ', pv.Descricao) AS Produto, opp.Quantidade, opp.Valor_Venda_Unitario, co.Nome AS Colaborador,
							 co.Codigo, co.Foto AS FotoColaborador, opp.Cliente_Final_ID, ow.Workflow_ID AS Workflow_ID,
							 op.Proposta_ID AS Proposta_ID, op.Titulo, mf.Dados, ow.Situacao_ID, op.Status_ID, opp.Proposta_Produto_ID,
							 ow.Solicitante_ID as Solicitante_ID, s.Nome as Solicitante, s.Email as Email, ts.Descr_Tipo as Situacao,
							 co.Cadastro_ID as Colaborador_ID, mf.Dados as DadosColaborador
							FROM orcamentos_workflows ow
							INNER JOIN orcamentos_propostas op ON op.Workflow_ID = ow.Workflow_ID
							INNER JOIN orcamentos_propostas_produtos opp ON opp.Proposta_ID = op.Proposta_ID
							INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
							INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
							INNER JOIN cadastros_dados s on s.Cadastro_ID = ow.Solicitante_ID
							INNER JOIN tipo ts on ts.Tipo_ID = ow.Situacao_ID
							LEFT JOIN cadastros_dados co ON co.Cadastro_ID = opp.Cliente_Final_ID
							LEFT JOIN modulos_formularios mf ON mf.Slug = 'formulario-otica-colaborador' AND mf.Tabela_Estrangeira = 'cadastros_dados' AND mf.Chave_Estrangeira = opp.Cliente_Final_ID AND mf.Situacao_ID = 1
							WHERE ow.Workflow_ID = '$id' AND op.Proposta_ID = '$id2' and opp.Situacao_ID = 1
							ORDER BY op.Proposta_ID, co.Nome, opp.Cliente_Final_ID";

			$quantidadeTotal = 0;
			$valorTotal = 0;
			$resultSet = mpress_query($sql);
			while($rs = mpress_fetch_array($resultSet)){
				$dadosColaborador = unserialize($rs['DadosColaborador']);
				if ($colaboradorIDAnt != $rs['Colaborador_ID']){
					if ($flagColab==1){
						$hProdCli .= '</table><p>&nbsp;</p>';
					}
					$flagColab = 1;
					if ($foto!="")
						$imagemFoto = '<img src="'.$caminhoSistema.'/uploads/'.$foto.'" style="height:40px; cursor:pointer;">';
					else
						$imagemFoto = '<img src="'.$caminhoSistema.'/images/geral/imagem-usuario.jpg" style="height:40px; cursor:pointer;">';

					$txtNaoInfo = "N&atilde;o Informado";
					$turno = "";
					if ($dadosColaborador['turno']=='C')
						$turno = 'Comercial';
					if ($dadosColaborador['turno']=='M')
						$turno = 'Manh&atilde;';
					if ($dadosColaborador['turno']=='T')
						$turno = 'Tarde';


					$hProdCli .= '	<div Style="page-break-after: always;">&nbsp;</div>';
					$hProdCli .= '	<table width="100%" style="border:0px; font-family: arial; font-size: 10px; margin-top:20px;" cellpadding="2" cellspacing="2">
										<tr>
											<td colspan="4" style="height:25px; background-color:#C0C0C0; text-align:center;"><b>'.utf8_encode($rs['Colaborador']).'</b></td>
										</tr>
										<tr>
											<td style="text-align:center; width:80px" rowspan="2">
												'.$imagemFoto.'
											</td>
											<td>
												<b>N&ordm; de Registro:</b> <br>'.utf8_encode($rs['Codigo']).'&nbsp;
											</td>
											<td>
												<b>Cargo:</b> <br>'.$dadosColaborador['cargo'].'&nbsp;
											</td>
											<td>
												<b>Telefone:</b> <br>'.$dadosColaborador['telefone'].'&nbsp;
											</td>
										</tr>
										<tr>
											<td>
												<b>Email:</b> <br>'.$dadosColaborador['email'].'&nbsp;
											</td>
											<td>
												<b>Centro Custo:</b> <br>'.$dadosColaborador['centro-custo'].'&nbsp;
											</td>
											<td>
												<b>Turno:</b> <br>'.$turno.'&nbsp;
											</td>
										</tr>';
				$hProdCli .= '		<tr>
										<td colspan="2"  style="background-color:#C0C0C0;"><b>Produto</b></td>
										<td colspan="1"  style="background-color:#C0C0C0; text-align:center;"><b>Quantidade</b></td>
										<td colspan="1"  style="background-color:#C0C0C0; text-align:center;"><b>Valor</b></td>
									</tr>';
				}
				$hProdCli .= '		<tr>
										<td colspan="2">'.utf8_encode($rs['Produto']).'</td>
										<td colspan="1" style="text-align:center;">'.number_format($rs['Quantidade'],0,'','').'</td>
										<td colspan="1" style="text-align:center;">R$'.number_format($rs['Valor_Venda_Unitario'], 2, ',', '.').'</td>
									</tr>';

				$quantidadeTotal += $rs['Quantidade'];
				$valorTotal += $rs['Quantidade'] * $rs['Valor_Venda_Unitario'];
				$colaboradorIDAnt = $rs['Colaborador_ID'];
			}
			$hProdCli .= '</table><p>&nbsp;</p>';

			$hProdCli = '<table width="100%" style="border:0px; font-family: arial; font-size: 10px; margin-top:20px;" cellpadding="2" cellspacing="2">
							<tr>
								<td colspan="2" style="height:25px; background-color:#C0C0C0; text-align:center;"><b>RESUMO OR&Ccedil;AMENTO</b></td>
							</tr>
							<tr>
								<td width="50%" style="text-align:center;"><b>Quantidade total de produtos e servi&ccedil;os</b></td>
								<td width="50%" style="text-align:center;"><b>Valor Total:</b></td>
							</tr>
							<tr>
								<td style="text-align:center;">'.$quantidadeTotal.'</td>
								<td style="text-align:center;">R$ '.number_format($valorTotal, 2, ',', '.').'</td>
							</tr>
						 </table>'.$hProdCli;

			$conteudo	= str_replace('[produtos-agrupados-cliente-final]', $hProdCli, $conteudo);
		}
	}





	if (($origem=="turmas")||($origem=="orcamentos")){
		if (!(($origem=="orcamentos") && ($turmaID!=""))){
			$turmaID = $id;
		}
		/* Dados da Turma */
		$rsTurma = mpress_query("select t.Codigo, t.Nome_Turma, tpi.Descr_Tipo as Instituicao, tpca.Descr_Tipo as Campus, tpc.Descr_Tipo as Curso, tpp.Descr_Tipo as Periodo, t.Cadastro_ID, ttt.Descr_Tipo as Turno
								from turmas_dados t
								inner join tipo tpi on tpi.Tipo_ID = t.Instituicao_ID
								inner join tipo tpca on tpca.Tipo_ID = t.Campus_ID
								inner join tipo tpc on tpc.Tipo_ID = t.Curso_ID
								inner join tipo ttt on ttt.Tipo_ID = t.Turno_ID
								inner join tipo tpp on tpp.Tipo_ID = t.Periodo_ID
								where t.Turma_ID = ".$turmaID);

		if($rsT = mpress_fetch_array($rsTurma)){
			$turmaCadastroID = $rsT['Cadastro_ID'];


			$conteudo	= str_replace('[codigo-turma]', utf8_encode($rsT['Codigo']), $conteudo);
			$conteudo	= str_replace('[nome-turma]', utf8_encode($rsT['Nome_Turma']), $conteudo);
			$conteudo	= str_replace('[campus]', utf8_encode($rsT['Campus']), $conteudo);
			$conteudo	= str_replace('[curso]', utf8_encode($rsT['Curso']), $conteudo);
			$conteudo	= str_replace('[faculdade-universidade]', utf8_encode($rsT['Instituicao']), $conteudo);
			$conteudo	= str_replace('[turno-curso]', utf8_encode($rsT['Turno']), $conteudo);

			/* Dados do Conselho Diretor */

			$rsTurma2 = mpress_query("select c.Cadastro_ID, c.Nome, c.RG, c.Cpf_Cnpj, c.Email, ct.Telefone, t.Descr_Tipo, ce.CEP, ce.Logradouro, ce.Numero, ce.Complemento, ce.Bairro, ce.Cidade, ce.UF, ce.Referencia
									from cadastros_dados c
									inner join cadastros_vinculos cv on cv.Cadastro_Filho_ID = c.Cadastro_ID
									inner join turmas_dados_alunos td on td.Cadastro_ID = c.Cadastro_ID
									inner join tipo t on t.Tipo_ID = cv.Tipo_Vinculo_ID
									inner join cadastros_telefones ct on ct.Cadastro_ID = c.Cadastro_ID
									inner join cadastros_enderecos ce on ce.Cadastro_ID = c.Cadastro_ID
									where t.Tipo_Grupo_ID = 50 and td.Turma_ID = ".$turmaCadastroID);

			while($rsT2 = mpress_fetch_array($rsTurma2)){
				$cargo = retiraCaracteresEspeciais(strtolower($rsT2['Descr_Tipo']));
				$conteudo = str_replace('['.$cargo.'-nome]', utf8_encode($rsT2['Nome']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-rg]', utf8_encode($rsT2['RG']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-cpf]', utf8_encode($rsT2['Cpf_Cnpj']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-email]', utf8_encode($rsT2['Email']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-bairro]', utf8_encode($rsT2['Bairro']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-cep]', utf8_encode($rsT2['CEP']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-cidade]', utf8_encode($rsT2['Cidade']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-complemento]', utf8_encode($rsT2['Complemento']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-logradouro]', utf8_encode($rsT2['Logradouro']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-numero]', utf8_encode($rsT2['Numero']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-uf]', utf8_encode($rsT2['UF']), $conteudo);
				$conteudo = str_replace('['.$cargo.'-telefone]', utf8_encode($rsT2['Telefone']), $conteudo);
			}
		}


		$sql = " select d.Cadastro_ID, d.Nome, d.Email, t.Telefone, t1.Descr_Tipo Cargo
						from turmas_dados_alunos a
						inner join cadastros_dados d on d.Cadastro_ID = a.Cadastro_ID
						inner join cadastros_vinculos v on v.Cadastro_Filho_ID = d.Cadastro_ID
						inner join tipo t1 on t1.Tipo_ID = v.Tipo_Vinculo_ID and t1.Tipo_Grupo_ID = 50
						left join cadastros_telefones t on t.Cadastro_ID = a.Cadastro_ID
						where Turma_ID = ".$turmaCadastroID."
						and a.Situacao_ID = 1 ";
		$resultSetComissao = mpress_query($sql);
		$comissaoDados = '	<table width="100%" style="border:1px solid silver; font-family: arial; font-size: 10px;" cellpadding="2" cellspacing="2">
								<tr style="background-color: #c9c9c9;">
									<td width="25%"><b>Nome</b></td>
									<td width="25%"><b>Cargo</b></td>
									<td width="25%"><b>Email</b></td>
									<td width="25%"><b>Telefone</b></td>
								</tr>';
		while($rc = mpress_fetch_array($resultSetComissao)){
			$comissaoDados .= '	<tr style="background-color: #f1f1f1;">
									<td>'.$rc['Nome'].'</td>
									<td>'.$rc['Cargo'].'</td>
									<td>'.$rc['Email'].'</td>
									<td>'.$rc['Telefone'].'</td>
								</tr>';
		}
		$comissaoDados .= '	</table>';
		$conteudo = str_replace('[comissao-dados]', utf8_encode($comissaoDados), $conteudo);
	}


/*
	$conteudo = str_replace('<p><table','<table', $conteudo);
	$conteudo = str_replace('</p><table>','</table>', $conteudo);
	$conteudo = str_replace('<span><table','<table', $conteudo);
	$conteudo = str_replace('</span><table>','</table>', $conteudo);
*/
	$conteudo = str_replace("'","&#39;",$conteudo);
	$conteudo = preg_replace('/\s+/', ' ', $conteudo);


	if ($_GET['email']=='true'){
		exit($conteudo);
	}

	$nomeArquivo = retiraCaracteresEspeciais(date('Ymd_hms')."_".$titulo.".pdf");

	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

	$sql = "insert into modulos_anexos(Documento_ID, Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Observacao, Nome_Arquivo_Original, Data_Cadastro, Usuario_Cadastro_ID)
										values ('$documentoID', '$background', '$id','$origem','$nomeArquivo', '$conteudo','$nomeArquivoOriginal', $dataHoraAtual,'".$dadosUserLogin['userID']."')";
	mpress_query($sql);
	geraPDF($nomeArquivo, $conteudo, $background);
?>
