<!--
<div class="titulo-container"  style='width:24%;'>
	<div class="titulo">
		<p>Oportunidades</p>
	</div>
	<div class='conteudo-interno'>
<?php
$sql = "SELECT Cadastro_ID, Oportunidade_ID, Classificacao, Data_Retorno, Descricao, Tarefa_ID, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro
				FROM oportunidades where Situacao_ID = 1
				order by Data_Retorno";
//echo $sql;
$resultado = mpress_query($sql);
while($row = mpress_fetch_array($resultado)){
	$i++;
	$dados[colunas][conteudo][$i][1] = "<span class=''><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Descricao]."</p></span>";
	$dados[colunas][conteudo][$i][2] = "<span class=''><p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($row[Data_Retorno],1)."</p></span>";
}
$largura = "98%";
$colunas = "2";
$dados[colunas][tamanho][1] = "width=''";
$dados[colunas][tamanho][2] = "width='150px'";
$dados[colunas][tamanho][3] = "width='100px'";

$dados[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
//$dados[colunas][titulo][2] 	= "Classifica&ccedil;&atilde;o";
//$dados[colunas][titulo][2] 	= "Respons&aacute;vel";
$dados[colunas][titulo][2] 	= "Data Retorno";
geraTabela($largura, $colunas, $dados, "margin-top:0px;border:0px solid silver;margin-bottom:0px;", 'lista-oportunidades-pipeline', 4, 0, 100,'');
?>
	<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
<div class="titulo-container"  style='width:24%;'>
	<div class="titulo">
		<p>Reuniões Agendadas</p>
	</div>
	<div class='conteudo-interno'>
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
<div class="titulo-container"  style='width:24%;'>
	<div class="titulo">
		<p>Propostas Enviadas</p>
	</div>
	<div class='conteudo-interno' >
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
<div class="titulo-container"  style='width:24%;'>
	<div class="titulo">
		<p>Propostas Fechadas</p>
	</div>
	<div class='conteudo-interno' >
		<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
	</div>
</div>
-->