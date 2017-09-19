<?php
include('functions.php');
global $dadosUserLogin, $modulosGeral, $modulosAtivos;

$tipoFluxo = $_GET['tipo-fluxo'];

	$i = 0;
	$sql = "select tc.Campanha_ID, tc.Operacao_ID, tc.Nome as Campanha, tc.Formulario_ID, tc.Situacao_ID,
					tc.Data_Cadastro, tc.Usuario_Cadastro_ID, s.Descr_Tipo AS Situacao,
					top.Nome as Operacao, tpop.Descr_Tipo as Tipo_Campanha
			from tele_campanhas tc
			inner join tipo s ON s.Tipo_ID = tc.Situacao_ID
			inner join tele_operacoes top on top.Operacao_ID = tc.Operacao_ID
			".$sqlOperador."
			left join tipo tpop on tpop.Tipo_ID = tc.Tipo_Campanha_ID
			where tc.Campanha_ID > 0 and tc.Situacao_ID = 161
			group by tc.Campanha_ID, tc.Operacao_ID
			order by tc.Nome, tc.Campanha_ID, tc.Operacao_ID";
	//echo $sql;
	$resultado = mpress_query($sql);
	if ($rs = mpress_fetch_array($resultado)){
		$rs['Operacao'];
	}
}
?>