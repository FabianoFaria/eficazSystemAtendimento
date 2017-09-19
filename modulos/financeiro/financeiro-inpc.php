<?php
include("functions.php");
$sql = "SELECT Tipo_ID, Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar, Tipo_Auxiliar_Extra
			FROM tipo where Tipo_ID = 179 ";
$i=0;
$resultado = mpress_query($sql);
if($rs = mpress_fetch_array($resultado)){
	$dadosAux = unserialize($rs['Tipo_Auxiliar']);
}


foreach($dadosAux as $chave => $dado){
	$i++;
	$dados[colunas][conteudo][$i][1] = $chave;
	$dados[colunas][conteudo][$i][2] = "<span>".$dado."</span>";
	$dados[colunas][conteudo][$i][3] = "<div class='btn-excluir btn-excluir-indice' style='float:right; padding-right:10px' id='$chave' title='Excluir'>&nbsp;</div>";
}
$largura = "100%";
$colunas = "3";
$dados[colunas][titulo][1] 	= "Mês / Ano";
$dados[colunas][titulo][2] 	= "Indice";
$dados[colunas][tamanho][3] = "60px";
echo "	<div class='titulo-secundario duas-colunas'>
			<div class='titulo-container-interno-alpha'>
				<div class='titulo'>
					<p>Incluir Índices INPC</p>
				</div>
				<p><input type='hidden' name='tipo-id' id='tipo-id' value='179' class='formata-mes-ano required' /></p>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left; width:50%; margin-top:5px'>
						<p>Mês/Ano</p>
						<p><input type='text' name='mesano' id='mesano' value='' class='formata-mes-ano required' maxlength='7'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:50%; margin-top:5px'>
						<p>&Iacute;ndice</p>
						<p><input type='text' name='indice' id='indice' value='' class='formata-valor required' maxlength='5'/></p>
					</div>
					<div class='titulo-secundario' style='float:right; width:100%; margin-top:5px;' align='right'>
						<input type='button' class='btn-acao-array' value='Incluir' valor='179' style='width:150px; margin:3px 7px 3px 0 ;'/>
					</div>
				</div>
			</div>
		</div>
		<div class='titulo-secundario duas-colunas'>
			<div class='titulo-container-interno-omega' Style='min-height:317px'>
				<div class='titulo'>
					<p>Índices Cadastrados</p>
				</div>
				<div class='titulo-secundario' style='float:left; width:100%; margin-top:5px'>
					<p>";
geraTabela("100%", $colunas, $dados, "", "financeiro-contabil", 2, 2, 500, "");
echo "				</p>
				</div>
			 </div>
		<div>";

//echo "<pre>";
//print_r($dados);
//echo "</pre>";



?>