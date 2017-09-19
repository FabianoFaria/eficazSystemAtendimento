<?php
	include('functions.php');
	global $caminhoSistema;
	$cadastroID = $_POST['id-cadastro'];
	$turmaID = $_POST['id-turma'];
	$sql = "select year(Data_Evento) as ano, month(Data_Evento) as mes, r.Nome as Representante,
				(select (922 + count(*)) + 1 from turmas_dados where Codigo <> '') as Codigo_Next
				from orcamentos_workflows ow
				inner join orcamentos_propostas op on op.Workflow_ID = ow.Workflow_ID
				inner join orcamentos_propostas_eventos ope on ope.Proposta_ID = op.Proposta_ID
				left join cadastros_dados r on r.Cadastro_ID = ow.Representante_ID
				where op.Status_ID IN (141,118)
				and Solicitante_ID = '$cadastroID'
				order by Data_Evento desc limit 1";
	mpress_query($sql);
	$resultado = mpress_query($sql);
	$ano = "YY";
	$periodo = "PP";
	$r = "?";
	if($rs = mpress_fetch_array($resultado)){
		$ano = substr($rs['ano'],-2);
		$r = strtoupper(substr($rs['Representante'],0,1));
		$r = strtoupper(substr($rs['Representante'],0,1));
		$codigo = $rs['Codigo_Next'];
		if ($rs['mes']>6)
			$periodo = "02";
		else
			$periodo = "01";
	}
	echo "C-".$ano.".".$codigo."/".$periodo." ".$r;
?>