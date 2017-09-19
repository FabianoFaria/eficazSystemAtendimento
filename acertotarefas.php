<?php
include('config.php');
$sql = "select Projeto_Vinculo_Tarefa_ID, Tabela_Estrangeira, Campo_Estrangeiro, Chave_Estrangeira from projetos_vinculos_tarefas";
echo $sql;
$resultado = mpress_query($sql);
while($rs = mpress_fetch_array($resultado)){
	$campo = "";
	if ($rs['Tabela_Estrangeira'] == 'orcamentos_workflows')
		$campo = " Solicitante_ID ";
	if ($rs['Tabela_Estrangeira'] == 'chamados_workflows')
		$campo = " Solicitante_ID ";
	if ($rs['Tabela_Estrangeira'] == 'oportunidades_workflows')
		$campo = " Cadastro_ID ";
	if ($rs['Tabela_Estrangeira'] == 'tele_workflows')
		$campo = " Cadastro_ID ";
	if ($campo!=""){
		$sql = "select ".$campo." as ID from ".$rs['Tabela_Estrangeira']." where ".$rs['Campo_Estrangeiro']." = ".$rs['Chave_Estrangeira'];
		echo "<br>".$sql;
		$resultado1 = mpress_query($sql);
		if ($rs1 = mpress_fetch_array($resultado1)){
			$sql = "update projetos_vinculos_tarefas set Cadastro_Alvo_ID = '".$rs1['ID']."' where Projeto_Vinculo_Tarefa_ID = ".$rs['Projeto_Vinculo_Tarefa_ID'];
			mpress_query($sql);
			echo "<br>".$sql;
		}
		//else{
		//	$sql = "delete from projetos_vinculos_tarefas where Projeto_Vinculo_Tarefa_ID = ".$rs['Projeto_Vinculo_Tarefa_ID'];
		//	mpress_query($sql);
		//	echo "<br>".$sql;
		//}
	}
	else{
		echo "<br>".$rs['Tabela_Estrangeira']." | ".$rs['Chave_Estrangeira'];

	}
}
echo "Atualizado";
?>