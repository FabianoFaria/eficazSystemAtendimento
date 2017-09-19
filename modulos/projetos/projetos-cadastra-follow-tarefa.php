<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");

	$userID			= $_SESSION['dadosUserLogin']['userID'];
	$observacao		= utf8_decode($_POST['descricao-tarefa-follow']);
	$horaInicio		= converteDataHora($_POST['horas-inicio-tarefa-follow']);
	$horaFinal		= converteDataHora($_POST['horas-final-tarefa-follow']);
	$idTarefa		= $_POST['tarefa-id'];
	$idWorkflow		= $_POST['workflow-id'];

	if($_GET['encaminhada']=='s'){
		$novaTarefa = mpress_fetch_array(mpress_query("select Descr_Tipo from tipo where Tipo_ID = ".$_POST['select-tipo-tarefa']));
		$novoGrupo 	= mpress_fetch_array(mpress_query("select Titulo from modulos_acessos where Modulo_Acesso_ID = ".$_POST['select-grupo-tarefa']));;
		$novoUser 	= mpress_fetch_array(mpress_query("select Nome from cadastros_dados where cadastro_id = ".$_POST['select-usuario-tarefa']));;
		$observacao = "Tarefa Alterada para <b>$novaTarefa[Descr_Tipo]</b> e encaminhada para <b>$novoGrupo[Titulo] $novoUser[Nome]</b>";
	 	mpress_query("insert into chamados_workflows_tarefas_follows(Workflow_Tarefa_ID, Descricao, Hora_Inicio, Hora_Fim, Usuario_Cadastro_ID)values('$idTarefa','$observacao',now(),now(),'$userID')");
	}
	else{
		mpress_query("INSERT INTO projetos_vinculos_tarefas_follows(Projeto_Vinculo_Tarefa_ID, Descricao, Hora_Inicio, Hora_Fim, Usuario_Cadastro_ID) values('$idTarefa','$observacao','$horaInicio','$horaFinal','$userID')");
	}
	if($_GET['finaliza']=='s') mpress_query("update projetos_vinculos_tarefas set Situacao_ID = 84 where Projeto_Vinculo_Tarefa_ID = '$idTarefa'");
	$erro = mysql_error();
	if($erro=="")
		mostraDadosFolowsTarefas($idWorkflow);
	else 
		echo $erro;
?>