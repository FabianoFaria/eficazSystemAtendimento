<?php
	error_reporting(E_ERROR);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	function cadastrarNovaTurma(){
		global $caminhoSistema;
		$idTurmaCadastro = mpress_fetch_array(mpress_query("select min(cadastro_ID) ID from cadastros_dados"));
		if($idTurmaCadastro[ID] > -1000) $cadastroID = -1000; else $cadastroID = $idTurmaCadastro[ID]-1;
		mpress_query("insert into cadastros_dados(Cadastro_ID, Nome, Email, Situacao_ID, Tipo_Pessoa) values ($cadastroID,'".$_POST['nome-turma']."','".$_POST['email-turma']."',1, 25)");
		mpress_query("insert into turmas_dados(Cadastro_ID, Nome_Turma, Instituicao_ID, Campus_ID, Curso_ID,
												Periodo_ID, Turno_ID, Quantidade, Codigo, Responsavel_ID, Usuario_ID)
					  					values($cadastroID,'".$_POST['titulo-turma']."','".$_POST['turma-instituicao']."','".$_POST['turma-campus']."','".$_POST['turma-curso']."',
					  							'".$_POST['turma-periodo']."','".$_POST['turma-turno']."','".$_POST['turma-quantidade']."','".$_POST['turma-codigo']."','".$_POST['turma-responsavel']."','".$_SESSION['dadosUserLogin']['userID']."')");

		echo "	<form method='post' action='$caminhoSistema/turmas/turmas-gerenciar-turma/' name='formNovaTurma'>
					<input type='hidden' id='id-turma' name='id-turma' value='".mpress_identity()."'>
				</form>
				<script>document.formNovaTurma.submit();</script>";

	}

	function localizaDetalhesTurma($turmaID){
		return mpress_fetch_array(mpress_query("select td.Cadastro_ID, td.Turma_ID, td.Nome_Turma, td.Instituicao_ID, td.Campus_ID, td.Curso_ID, td.Periodo_ID, td.Turno_ID, td.Quantidade,
												td.Codigo, td.Responsavel_ID, cd.Email
												from turmas_dados td
												inner join cadastros_dados cd on cd.Cadastro_ID = td.Cadastro_ID
												where td.Turma_ID = '$turmaID'"));
	}

	function cadastrarNovoAlunoTurma(){
		global $caminhoSistema;
		$idTurmaCadastro = $_POST['id-cadastro'];
		$turmaID 		 = $_POST['id-turma'];
		$imagem 		 = $_POST['arquivo-imagem'];
		$nome 			 = $_POST['nome-completo'];
		$cpf 			 = $_POST['cpf'];
		$rg 			 = $_POST['rg'];
		$sexo 			 = $_POST['sexo'];
		$email 			 = $_POST['cadastro-email'];
		$telefone		 = $_POST['telefone-telefone'];
		$nascimento		 = formataDataBD($_POST['data-nascimento']);
		$observacao		 = $_POST['observacao'];
		mpress_query("insert into cadastros_dados(Tipo_Pessoa,Tipo_Cadastro,Nome,Data_Nascimento,Sexo,Cpf_Cnpj,RG,Email,Foto,Observacao,Usuario_Cadastro_ID, Situacao_ID)values(24,'a:1:{i:0;s:3:\"108\";}','$nome','$nascimento','$sexo','$cpf','$rg','$email','$imagem','$observacao','".$_SESSION['dadosUserLogin']['userID']."',1)");
		$cadastroID = mpress_identity();
		mpress_query("insert into cadastros_telefones(Cadastro_ID,Telefone,Tipo_Telefone_ID, Situacao_ID,Usuario_Cadastro_ID)values($cadastroID,'$telefone',28, 1, '".$_SESSION['dadosUserLogin']['userID']."')");
		mpress_query("insert into cadastros_enderecos(Cadastro_ID, Tipo_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Situacao_ID, Usuario_Cadastro_ID)values($cadastroID,'26','".$_POST['cep-endereco']."','".$_POST['logradouro-endereco']."','".$_POST['numero-endereco']."','".$_POST['complemento-endereco']."','".$_POST['bairro-endereco']."','".$_POST['cidade-endereco']."','".$_POST['uf-endereco']."','1','".$_SESSION['dadosUserLogin']['userID']."')");
		mpress_query("insert into cadastros_vinculos(Tipo_Vinculo_ID,Cadastro_ID,Cadastro_Filho_ID,Situacao_ID)values(109,$idTurmaCadastro,$cadastroID,1)");
		if($_POST['cargo-turma'] != "")
			mpress_query("insert into cadastros_vinculos(Tipo_Vinculo_ID,Cadastro_ID,Cadastro_Filho_ID,Situacao_ID)values(".$_POST['cargo-turma'].",$idTurmaCadastro,$cadastroID,1)");
		mpress_query("insert into turmas_dados_alunos(Turma_ID,Cadastro_ID,Usuario_ID,Situacao_ID)values($idTurmaCadastro,$cadastroID,'".$_SESSION['dadosUserLogin']['userID']."',1)");
		echo "	<form method='post' action='$caminhoSistema/turmas/turmas-gerenciar-turma/' name='formNovaTurma'><input type='hidden' id='id-turma' name='id-turma' value='$turmaID'></form><script>document.formNovaTurma.submit();</script>";
	}

	function excluirAlunoTurma(){
		$alunoID = $_POST['id-aluno'];
		$turmaID = $_POST['id-cadastro'];
		$sql = "update turmas_dados_alunos set Situacao_ID = 3 where Turma_ID = '$turmaID' and Cadastro_ID = '$alunoID'";
		//echo $sql;
		mpress_query($sql);
	}



	function vinculoAlunoTurma($cadastroID){
		$resultado = mpress_query(" select Turma_ID, Nome_Turma, t1.Descr_Tipo Instituicao, t2.Descr_Tipo Campus, t3.Descr_Tipo Curso,
									t4.Descr_Tipo Periodo, d.Data_Cadastro, t5.Descr_Tipo Cargo
									from turmas_dados d
									inner join tipo t1 on t1.Tipo_ID = d.Instituicao_ID
									inner join tipo t2 on t2.Tipo_ID = d.Campus_ID
									inner join tipo t3 on t3.Tipo_ID = d.Curso_ID
									inner join tipo t4 on t4.Tipo_ID = d.Periodo_ID
									inner join cadastros_vinculos v on v.Cadastro_ID = d.Cadastro_ID and Tipo_Vinculo_ID = 109
									inner join cadastros_vinculos v1 on v1.Cadastro_Filho_ID = $cadastroID
									inner join tipo t5 on t5.Tipo_ID = v1.Tipo_Vinculo_ID and t5.Tipo_Grupo_ID = 50
									where v.cadastro_filho_id = $cadastroID");
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<span class='turma-mostrar-detalhes' turma-id='".$row[Turma_ID]."'><p Style='margin:2px 5px 0 5px;float:left;' class='link'>".$row[Turma_ID]."</p></span>";
			$dados[colunas][conteudo][$i][2] = $row['Instituicao'];
			$dados[colunas][conteudo][$i][3] = $row['Campus'];
			$dados[colunas][conteudo][$i][4] = $row['Curso'];
			$dados[colunas][conteudo][$i][5] = $row['Periodo'];
			$dados[colunas][conteudo][$i][6] = $row['Nome_Turma'];
			$dados[colunas][conteudo][$i][7] = $row['Cargo'];
			$dados[colunas][conteudo][$i][8] = "<span style='text-align:center;width:100%;float:left;'>".formataData($row['Data_Cadastro']."</span>");
		}
		$largura = "100.2%";
		$colunas = "8";
		$dados[colunas][tamanho][1] = "width='5%'";
		$dados[colunas][tamanho][2] = "width='15%'";
		$dados[colunas][tamanho][3] = "width='15%'";
		$dados[colunas][tamanho][4] = "width='15%'";
		$dados[colunas][tamanho][5] = "width='15%'";
		$dados[colunas][tamanho][6] = "";
		$dados[colunas][tamanho][8] = "width='100px'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Instituição";
		$dados[colunas][titulo][3] 	= "Campus";
		$dados[colunas][titulo][4] 	= "Curso";
		$dados[colunas][titulo][5] 	= "Período";
		$dados[colunas][titulo][6] 	= "Nome da Turma";
		$dados[colunas][titulo][7] 	= "Cargo";
		$dados[colunas][titulo][8] 	= "<span style='text-align:center;width:100%;float:left;'>Data Cadastro</span>";

		geraTabela($largura,$colunas,$dados, null, 'lista-turma-localiza', 2, 2, 100,null);
	}

	function localizaAlunosCadastradosTurma($turmaID){
		global $caminhoSistema;
		$resultado = mpress_query(" select d.Cadastro_ID,d.Nome,d.Data_Nascimento,d.Sexo,d.Cpf_Cnpj,d.RG,d.Email,d.Foto,t.Telefone,d.Data_Inclusao,
									t1.Descr_Tipo Cargo
									from turmas_dados_alunos a
									inner join cadastros_dados d on d.Cadastro_ID = a.Cadastro_ID
									inner join cadastros_vinculos v on v.Cadastro_Filho_ID = d.Cadastro_ID
									inner join tipo t1 on t1.Tipo_ID = v.Tipo_Vinculo_ID and t1.Tipo_Grupo_ID = 50
									left join cadastros_telefones t on t.Cadastro_ID = a.Cadastro_ID
									where Turma_ID = $turmaID
									and a.Situacao_ID = 1 ");

		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1]  = $row['Cadastro_ID'];
			$dados[colunas][conteudo][$i][2]  = $row['Nome'];
			$dados[colunas][conteudo][$i][3]  = $row['Cargo'];
			$dados[colunas][conteudo][$i][4]  = $row['Email'];
			$dados[colunas][conteudo][$i][5]  = "<span style='text-align:center;width:100%;float:left;'>".$row['Telefone']."</span>";
			$dados[colunas][conteudo][$i][6]  = "<span style='text-align:center;width:100%;float:left;'>".$row['Sexo']."</span>";
			$dados[colunas][conteudo][$i][7]  = "<span style='text-align:left;width:100%;float:left;'>&nbsp;".$row['Cpf_Cnpj']."</span>";
			$dados[colunas][conteudo][$i][8]  = "<span style='text-align:left;width:100%;float:left;'>&nbsp;".$row['RG']."</span>";
			$dados[colunas][conteudo][$i][9]  = "<span style='text-align:center;width:100%;float:left;'>".formataData($row['Data_Inclusao'])."</span>";
			$dados[colunas][conteudo][$i][10] = "<span style='text-align:center;width:100%;float:left;'>
											<div class='btn-editar turma-edita-aluno' style='float:right;padding-right:1px' attr-id='$row[Cadastro_ID]' title='Editar'>&nbsp;</div>
											<div class='btn-excluir turma-exclui-aluno' style='float:right;padding-right:5px' attr-id='$row[Cadastro_ID]' title='Excluir'>&nbsp;</div>
										  </span>";


		}
		$largura = "100.2%";
		$colunas = "10";
		$dados[colunas][tamanho][1]  = "width='50px'";
		$dados[colunas][tamanho][2]  = "";
		$dados[colunas][tamanho][3]  = "";
		$dados[colunas][tamanho][4]  = "";
		$dados[colunas][tamanho][5]  = "width='090px'";
		$dados[colunas][tamanho][6]  = "width='040px'";
		$dados[colunas][tamanho][7]  = "width='095px'";
		$dados[colunas][tamanho][8]  = "width='100px'";
		$dados[colunas][tamanho][9]  = "width='080px'";
		$dados[colunas][tamanho][10] = "width='040px'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Nome";
		$dados[colunas][titulo][3] 	= "Cargo";
		$dados[colunas][titulo][4] 	= "E-mail";
		$dados[colunas][titulo][5] 	= "<span style='text-align:center;width:100%;float:left;'>Telefone</span>";
		$dados[colunas][titulo][6] 	= "<span style='text-align:center;width:100%;float:left;'>Sexo</span>";
		$dados[colunas][titulo][7] 	= "&nbsp;Cpf";
		$dados[colunas][titulo][8] 	= "&nbsp;RG";
		$dados[colunas][titulo][9] 	= "<span style='text-align:center;width:100%;float:left;'>Data Cadastro</span>";
		geraTabela($largura, $colunas, $dados, null, 'lista-turma-aluno-mostra', 2, 2, 100,'');
		return $i;
	}

	function salvarEventoTurma(){
		global $caminhoSistema, $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');

		$eventoID = $_POST['evento-id'];
		$turmaID = $_POST['id-turma'];
		$tipoEventoID = $_POST['tipo-evento'];
		$localEventoID = $_POST['local-evento'];
		$descricao = $_POST['descricao-evento'];
		$dataEvento = formataDataBD($_POST['data-evento']);
		$participantes = $_POST['participantes-evento'];

		if ($eventoID==""){
			$sql = "	INSERT INTO turmas_eventos
						(Turma_ID, Tipo_Evento_ID, Local_Evento_ID, Descricao, Participantes, Data_Evento, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
					VALUES
						('$turmaID', '$tipoEventoID', '$localEventoID', '$descricao', '$participantes', '$dataEvento', 1, '$dataHoraAtual', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
		}
		else{
			$sql = "	UPDATE turmas_eventos set
							Tipo_Evento_ID = '$tipoEventoID',
							Local_Evento_ID = '$localEventoID',
							Descricao = '$descricao',
							Participantes = '$participantes',
							Data_Evento = '$dataEvento',
							Data_Alteracao = '$dataHoraAtual',
							Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
						where Evento_ID = '$eventoID'";
			mpress_query($sql);
		}
	}

	function excluirEventoTurma(){
		global $caminhoSistema, $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$eventoID = $_POST['evento-id'];
		if ($eventoID!=""){
			$sql = "update turmas_eventos set
				Situacao_ID = 3,
				Data_Alteracao = '$dataHoraAtual',
				Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."'
			where Evento_ID = '$eventoID'";
			mpress_query($sql);
		}
	}


	function localizaEventosCadastradosTurma($turmaID){
		global $caminhoSistema;
		$sql = " select Evento_ID, t.Descr_Tipo as Tipo_Evento, l.Descr_Tipo as Local_Evento, Data_Evento, te.Tipo_Evento_ID, te.Local_Evento_ID, te.Descricao, te.Participantes
									from turmas_eventos te
									left join tipo t on t.Tipo_ID = te.Tipo_Evento_ID
									left join tipo l on l.Tipo_ID = te.Local_Evento_ID
									where Turma_ID = '$turmaID' and te.Situacao_ID = 1
									order by Data_Evento";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$eventoID = $row[Evento_ID];
			$dados[colunas][conteudo][$i][1] = $row['Tipo_Evento']."<input type='hidden' id='tipo-evento-id-$eventoID' value='".$row[Tipo_Evento_ID]."'/>";
			$dados[colunas][conteudo][$i][2] = "<span id='data-evento-$eventoID'>".formataData($row['Data_Evento'])."</span>";
			$dados[colunas][conteudo][$i][3] = $row['Local_Evento']."<input type='hidden' id='local-evento-id-$eventoID' value='".$row[Local_Evento_ID]."'/>";
			$dados[colunas][conteudo][$i][4] = "<span id='participantes-evento-$eventoID'>".$row['Participantes']."</span>";
			$dados[colunas][conteudo][$i][5] = "<span id='descricao-evento-$eventoID'>".$row['Descricao']."</span>";
			$dados[colunas][conteudo][$i][6] = "<span style='text-align:center;width:100%;float:left;'>
											<div class='btn-editar turma-gerencia-evento' style='float:right;padding-right:1px' evento-id='$row[Evento_ID]' title='Editar'>&nbsp;</div>
											<div class='btn-excluir turma-exclui-evento' style='float:right;padding-right:5px' evento-id='$row[Evento_ID]' title='Excluir'>&nbsp;</div>
										  </span>";
		}
		$largura = "100.2%";
		$dados[colunas][tamanho][1]  = "";
		$dados[colunas][tamanho][2]  = "";
		$dados[colunas][tamanho][3]  = "";
		$dados[colunas][tamanho][4]  = "";
		$dados[colunas][tamanho][5]  = "";
		$dados[colunas][tamanho][6] = "width='040px'";

		$dados[colunas][titulo][1] 	= "Tipo Evento";
		$dados[colunas][titulo][2] 	= "Data Evento";
		$dados[colunas][titulo][3] 	= "Local Evento";
		$dados[colunas][titulo][4] 	= "Participantes";
		$dados[colunas][titulo][5] 	= "Observa&ccedil;&otilde;es";
		geraTabela($largura, 6, $dados, null, 'lista-turma-eventos', 2, 2, 100,'');
		return $i;
	}


	function atualizaDadosTurma(){
		global $caminhoSistema;
		$turmaID = $_POST['id-turma'];
		$cadastroID = $_POST['id-cadastro'];
		$sql = "update turmas_dados set Nome_Turma = '".$_POST['titulo-turma']."',
								  Instituicao_ID	= '".$_POST['turma-instituicao']."',
								  Campus_ID			= '".$_POST['turma-campus']."',
								  Curso_ID			= '".$_POST['turma-curso']."',
								  Turno_ID			= '".$_POST['turma-turno']."',
								  Periodo_ID		= '".$_POST['turma-periodo']."',
								  Quantidade		= '".$_POST['turma-quantidade']."',
								  Codigo			= '".$_POST['turma-codigo']."',
								  Responsavel_ID	= '".$_POST['turma-responsavel']."',
								  Usuario_ID		= '".$_SESSION['dadosUserLogin']['userID']."'
					  where Turma_ID = '".$turmaID."'";
		mpress_query($sql);
		mpress_query("update cadastros_dados set Nome = '".$_POST['nome-turma']."', Email = '".$_POST['email-turma']."' where Cadastro_ID = $cadastroID");
		echo "	<form method='post' action='$caminhoSistema/turmas/turmas-gerenciar-turma/' name='formNovaTurma'><input type='hidden' id='id-turma' name='id-turma' value='$turmaID'></form><script>document.formNovaTurma.submit();</script>";
	}

	function excluirDadosTurma(){
		global $caminhoSistema;
		$turmaID = $_POST['id-turma'];
		$cadastroID = $_POST['id-cadastro'];
		mpress_query("update turmas_dados set Situacao_ID = 3 where Turma_ID = '".$turmaID."'");
		mpress_query("update cadastros_dados set Situacao_ID = 3 where Cadastro_ID = '$cadastroID'");
		echo "	<form method='post' action='$caminhoSistema/turmas/turmas-localizar-turma/' name='formNovaTurma'></form><script>document.formNovaTurma.submit();</script>";
	}


	function localizaDadosAluno(){
		$resultado = mpress_query("SELECT d.Foto, d.Nome, cpf_cnpj, rg, sexo, d.Email, d.Data_Nascimento, t.Telefone,
								   e.CEP, e.Logradouro, e.Numero, e.Complemento, e.Bairro, e.Cidade, e.UF,
								   t1.Tipo_ID Cargo, d.observacao
								   FROM turmas_dados_alunos a
								   INNER JOIN cadastros_dados d ON d.Cadastro_ID = a.Cadastro_ID
								   INNER JOIN cadastros_vinculos v ON v.Cadastro_Filho_ID = d.Cadastro_ID
								   INNER JOIN tipo t1 ON t1.Tipo_ID = v.Tipo_Vinculo_ID AND t1.Tipo_Grupo_ID = 50
								   LEFT JOIN cadastros_telefones t ON t.Cadastro_ID = a.Cadastro_ID
								   LEFT JOIN cadastros_enderecos e ON e.Cadastro_ID = a.Cadastro_ID
								   where d.Cadastro_ID = ".$_POST['id-aluno']);
		if($row = mpress_fetch_array($resultado))
			echo "$row[0]|$row[1]|$row[2]|$row[3]|$row[4]|$row[5]|$row[5]|".formataData($row[6])."|$row[7]|$row[8]|$row[9]|$row[10]|$row[11]|$row[12]|$row[13]|$row[14]|$row[15]|$row[15]|$row[16]";
	}

	function atualizaDadosAluno(){
		global $caminhoSistema;
		$idTurmaCadastro = $_POST['id-cadastro'];
		$turmaID 		 = $_POST['id-turma'];
		$alunoID 		 = $_POST['id-aluno'];
		$cadastroID		 = $_POST['id-aluno'];
		$imagem 		 = $_POST['arquivo-imagem'];
		$nome 			 = $_POST['nome-completo'];
		$cpf 			 = $_POST['cpf'];
		$rg 			 = $_POST['rg'];
		$sexo 			 = $_POST['sexo'];
		$email 			 = $_POST['cadastro-email'];
		$telefone		 = $_POST['telefone-telefone'];
		$nascimento		 = formataDataBD($_POST['data-nascimento']);
		$observacao		 = $_POST['observacao'];

		mpress_query("update cadastros_dados set Nome = '$nome', Data_Nascimento = '$nascimento', Sexo = '$sexo', Cpf_Cnpj = '$cpf', RG = '$rg', Email = '$email', Foto = '$imagem', Observacao = '$observacao', Data_Alteracao = now(), Usuario_Alteracao_ID = '".$_SESSION['dadosUserLogin']['userID']."' where Cadastro_ID = $alunoID");
		mpress_query("update cadastros_telefones set Telefone = '$telefone', Usuario_Cadastro_ID = '".$_SESSION['dadosUserLogin']['userID']."' where Cadastro_ID = $cadastroID");
		mpress_query("update cadastros_enderecos set CEP = '".$_POST['cep-endereco']."', Logradouro = '".$_POST['logradouro-endereco']."', Numero = '".$_POST['numero-endereco']."', Complemento = '".$_POST['complemento-endereco']."', Bairro = '".$_POST['bairro-endereco']."', Cidade = '".$_POST['cidade-endereco']."', UF = '".$_POST['uf-endereco']."', Usuario_Cadastro_ID = '".$_SESSION['dadosUserLogin']['userID']."' where Cadastro_ID = $cadastroID");

		$vinculoID = mpress_fetch_array(mpress_query("select Cadastro_Vinculo_ID from cadastros_vinculos where Tipo_Vinculo_ID in (select tipo_id from tipo where tipo_grupo_id = 50) and cadastro_filho_id = $cadastroID"));
		mpress_query("update cadastros_vinculos set Tipo_Vinculo_ID = ".$_POST['cargo-turma']." where Cadastro_Vinculo_ID = $vinculoID[Cadastro_Vinculo_ID]");

		echo "	<form method='post' action='$caminhoSistema/turmas/turmas-gerenciar-turma/' name='formNovaTurma'><input type='hidden' id='id-turma' name='id-turma' value='$turmaID'></form><script>document.formNovaTurma.submit();</script>";
	}

	function carregarOrcamentosTurma($solicitanteID){
		global $caminhoSistema;
		$sql = "select o.Workflow_ID, o.Codigo, o.Titulo, s.Nome as Solicitante, ts.Descr_Tipo as Situacao, o.Data_Abertura,
					o.Data_Finalizado, of.Data_Cadastro as Data_Interacao, r.Nome as Representante,
					op.Titulo as Titulo_Proposta, tp.Descr_Tipo as Situacao_Proposta, op.Proposta_ID as Proposta_ID, s.Cadastro_ID,
					SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Valor
					from orcamentos_workflows o
					inner join cadastros_dados s on s.Cadastro_ID = o.Solicitante_ID
					inner join tipo ts on ts.Tipo_ID = o.Situacao_ID
					inner join orcamentos_follows of on o.Workflow_ID = of.Workflow_ID and of.Follow_ID = (select max(ofaux.Follow_ID) from orcamentos_follows ofaux where ofaux.Workflow_ID = o.Workflow_ID limit 1)
					left join cadastros_dados r on r.Cadastro_ID = o.Representante_ID
					left join orcamentos_propostas op on op.Workflow_ID = o.Workflow_ID and op.Situacao_ID = 1
					left join orcamentos_propostas_produtos opp on opp.Proposta_ID = op.Proposta_ID and opp.Situacao_ID = 1
					left join tipo tp on tp.Tipo_ID = op.Status_ID
					where o.Workflow_ID > 0
					and o.Solicitante_ID = '$solicitanteID'
					group by o.Workflow_ID, o.Codigo, o.Titulo, s.Nome, ts.Descr_Tipo, o.Data_Abertura, o.Data_Finalizado,
					of.Data_Cadastro, r.Nome, op.Titulo, tp.Descr_Tipo, op.Proposta_ID, s.Cadastro_ID
					order by o.Workflow_ID desc";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row[Workflow_ID]!=$workFlowIDAnt){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link orcamento-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Workflow_ID]."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link orcamento-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Solicitante]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Titulo]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Abertura]),0,10)."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".substr(converteData($row[Data_Interacao]),0,10)."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Representante]."</p>";
				$dados[colunas][classe][$i] = "tabela-fundo-escuro";
			}
			if ($row['Proposta_ID']!=""){
				$i++;
				$dados[colunas][colspan][$i][2] = "2";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 30px;float:left;'>".$row[Titulo_Proposta]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao_Proposta]."</p>";
				$dados[colunas][colspan][$i][5] = "2";
				$dados[colunas][conteudo][$i][5] = "<p align='right'>R$ ".number_format($row[Valor], 2, ',', '.')."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>";

				$dados[colunas][conteudo][$i][7] = "<div align='center'><a title='Proje&ccedil;&atilde;o de valores' class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/turmas/projecao.php?orcamento=$row[Proposta_ID]&titulo=$row[Titulo_Proposta]&cadastro=$row[Cadastro_ID]'><div class='btn-grafico'>&nbsp;</div></a></div>";
				$dados[colunas][classe][$i] = "tabela-fundo-claro";
			}
			$workFlowIDAnt = $row[Workflow_ID];
		}
		$largura = "100%";
		$colunas = "7";
		$dados[colunas][titulo][1] 	= "ID <!--Or&ccedil;amento-->";
		$dados[colunas][titulo][2] 	= "Solicitante";
		$dados[colunas][titulo][3] 	= "T&iacute;tulo";
		$dados[colunas][titulo][4] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][5] 	= "Abertura";
		$dados[colunas][titulo][6]	= "Intera&ccedil;&atilde;o";
		$dados[colunas][titulo][7]	= "Representante";

		$dados[colunas][tamanho][1] = "width='40px'";
		$dados[colunas][tamanho][5] = "width='70px'";
		$dados[colunas][tamanho][6] = "width='70px'";
		$dados[colunas][tamanho][7] = "width='70px'";
		geraTabela($largura,$colunas,$dados, null, 'orcamentos-localizar', 2, 2, 100,"","");
		if($i==0) echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum registro localizado</p>";
	}


	function carregarChamadosTurma($cadastroID){
		global $caminhoSistema;
		$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, tw.Descr_Tipo as Tipo_Chamado,
							cd1.Nome as Solicitante, cd1.Nome_Fantasia as Solicitante_Fantasia, cd1.email as Email_Solicitante, cw.Titulo as Titulo,
							t.Descr_Tipo as Situacao,
							DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
							DATE_FORMAT(cw.Data_Finalizado,'%d/%m/%Y') as Data_Finalizado,
							DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
							(select count(*) from modulos_anexos a where a.Chave_Estrangeira = cw.Workflow_ID and a.Tabela_Estrangeira = 'chamados' and a.Situacao_ID = 1) as arquivos,
							Data_Hora_Retorno
					from chamados_workflows cw
					inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
					left join tipo tw on tw.Tipo_ID = cw.Tipo_WorkFlow_ID and tw.Tipo_Grupo_ID = 19
					left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
						and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
					left join tipo t on t.Tipo_ID = cf.Situacao_ID
					left join modulos_acessos ma on ma.Modulo_Acesso_ID = cf.Responsabilidade_ID
					where cw.Solicitante_ID = '$cadastroID'
					order by cw.Workflow_ID desc";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$nome = $row[Nome];
			$solicitante = $row[Solicitante];
			if ($row[Email_Solicitante]!=""){ $solicitante .= "&nbsp;(".$row[Email_Solicitante].")";}

			$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-vazia.png' alt='Nenhum Arquivo Anexado'/>";
			if ($row[arquivos]>0){
				$arquivos = "<img src='$caminhoSistema/images/geral/ico-pasta-cheia.png' alt='(".$row[arquivos].") Arquivo(s) Anexado(s)'/>";
			}
			$dadosChamado[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Workflow_ID]."</p>";
			$dadosChamado[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Codigo]."</p>";
			$dadosChamado[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Tipo_Chamado]."</p>";
			$dadosChamado[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."' title='".$row[Solicitante_Fantasia]."'>".$solicitante."</p>";
			$dadosChamado[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>".$row[Titulo]."</p>";
			$dadosChamado[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao].formataDataHoraRelatorio($row['Data_Hora_Retorno'])."</p>";
			$dadosChamado[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Abertura]."</p>";
			$dadosChamado[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Interacao]."</p>";
			$dadosChamado[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Finalizado]."</p>";
			$dadosChamado[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$row[Workflow_ID]."'>$arquivos</p>";
		}
		$largura = "100%";
		$colunas = "10";
		$dadosChamado[colunas][titulo][1] 	= "ID";
		$dadosChamado[colunas][titulo][2] 	= $_SESSION['objeto'];
		$dadosChamado[colunas][titulo][3] 	= "Tipo";
		$dadosChamado[colunas][titulo][4] 	= "Solicitante";
		$dadosChamado[colunas][titulo][5] 	= "Titulo";
		$dadosChamado[colunas][titulo][6] 	= "Situa&ccedil;&atilde;o";
		$dadosChamado[colunas][titulo][7] 	= "Abertura";
		$dadosChamado[colunas][titulo][8]	= "Intera&ccedil;&atilde;o";
		$dadosChamado[colunas][titulo][9] 	= "Finalizado";
		$dadosChamado[colunas][titulo][10]	= "";

		$dadosChamado[colunas][tamanho][1] = "width='40px'";
		$dadosChamado[colunas][tamanho][2] = "width='90px'";
		$dadosChamado[colunas][tamanho][3] = "";
		$dadosChamado[colunas][tamanho][4] = "";
		$dadosChamado[colunas][tamanho][5] = "";
		$dadosChamado[colunas][tamanho][6] = "width='150px'";
		$dadosChamado[colunas][tamanho][7] = "width='90px'";
		$dadosChamado[colunas][tamanho][8] = "width='90px'";
		$dadosChamado[colunas][tamanho][9] = "width='90px'";
		$dadosChamado[colunas][tamanho][10] = "width='20px'";
		geraTabela($largura,$colunas,$dadosChamado);
		if($i==0) echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum registro localizado</p>";
	}

	function projecaoSalvar(){
		global $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$propostaID = $_POST['proposta-id'];

		$sql = "update turmas_projecoes set Situacao_ID = 3, Data_Alteracao = '$dataHoraAtual', Usuario_Alteracao_ID = '".$dadosUserLogin['userID']."' where Proposta_ID = '$propostaID' and Situacao_ID = 1";
		mpress_query($sql);

		$sql = "insert into turmas_projecoes (Proposta_ID, Situacao_ID, Dados, Data_Cadastro, Usuario_Cadastro_ID)
								values ('$propostaID', 1, '".serialize($_POST)."','$dataHoraAtual',".$dadosUserLogin['userID'].")";
		mpress_query($sql);
	}

	function carregarDadosProjecao($propostaID){
		$sql = "select Dados from turmas_projecoes where Proposta_ID = '$propostaID' and Situacao_ID = 1";
		$resultado = mpress_query($sql);
		if($row = mpress_fetch_array($resultado)){
			$dados = $row['Dados'];
		}
		return(unserialize($dados));
	}

?>
