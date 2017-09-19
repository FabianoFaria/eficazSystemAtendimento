<?php

	function chamadosSalvar($dados){
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		if ($dados['id']==''){
			$sql = "insert into chamados_workflows
							(Cadastro_ID, Solicitante_ID, Tipo_Workflow_ID, Titulo, Data_Abertura, Codigo, Data_Cadastro, Usuario_Cadastro_ID)
						values(1, '".$dados['userID']."',".$dados['tipo-workflow'].",'".$dados['titulo-chamado']."', $dataHoraAtual,'Web Site', $dataHoraAtual, '".$dados['userID']."')";			
			mpress_query($sql);
			$dados['id'] = mpress_identity();
			$dados['situacaoID'] = "32";
		}
		$sql = "insert into chamados_follows(Workflow_ID, Descricao, Usuario_Cadastro_ID, Situacao_ID, Data_Cadastro) 
							values('".$dados['id']."','".$dados['descricao-follow']."','".$dados['userID']."','".$dados['situacaoID']."',$dataHoraAtual)";
		mpress_query($sql);
		$dado['retorno'] = "Salvo com sucesso!";
		echo serialize($dado);
	}
/*
	function chamadosLocaliza($dados){
		$sql = "select  Workflow_ID, Cadastro_ID, Solicitante_ID, Prestador_ID, Tipo_Workflow_ID, Prioridade_ID, Codigo, Titulo,
						Data_Abertura, Data_Finalizado, Data_Cadastro, Data_Limite, Usuario_Cadastro_ID, Responsavel_ID, Grupo_Responsavel_ID
					from chamados_workflows";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dado['chamados'][$i]['workflowID'] = $row['Workflow_ID'];
			$dado['chamados'][$i]['cadastroID'] = $row['Cadastro_ID'];
			$dado['chamados'][$i]['solicitanteID'] = $row['Solicitante_ID'];
			$dado['chamados'][$i]['prestadorID'] = $row['Prestador_ID'];
			$dado['chamados'][$i]['tipoworkflowID'] = $row['Tipo_Workflow_ID'];
			$dado['chamados'][$i]['prioridadeID'] = $row['Prioridade_ID'];
			$dado['chamados'][$i]['codigo'] = $row['Codigo'];
			$dado['chamados'][$i]['titulo'] = $row['Titulo'];
			$dado['chamados'][$i]['dataabertura'] = $row['Data_Abertura'];
			$dado['chamados'][$i]['datafinalizado'] = $row['Data_Finalizado'];
			$dado['chamados'][$i]['datacadastro'] = $row['Data_Cadastro'];
			$dado['chamados'][$i]['datalimite'] = $row['Data_Limite'];
			$dado['chamados'][$i]['usuariocadastroID'] = $row['Usuario_Cadastro_ID'];
			$dado['chamados'][$i]['responsavelID'] = $row['Responsavel_ID'];
			$dado['chamados'][$i]['gruporesponsavelID'] = $row['Grupo_Responsavel_ID'];
		}
		//echo "<pre>";
		//print_r($dado);
		//echo "</pre>";
		echo serialize($dado);
	}
	*/
	
	/*
	
		$chamado['id'] 			= $id->ID_Sistema;
		$chamado['empresa']		= $empresa['Cadastro_ID'];
		$chamado['tipo']		= $_POST['tipo-workflow'];
		$chamado['titulo']		= utf8_decode($_POST['titulo-chamado']);
	$chamado['descricao']	= utf8_decode($_POST['descricao-follow']);
	*/
?>