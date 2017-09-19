<?php
include("functions.php");
global $modulosAtivos, $caminhoSistema, $dadosUserLogin, $configChamados;

if (verificaNumeroEmpresas()==1){
	$empresaID = retornaCodigoEmpresa();
	$htmlEmpresa = "<input type='hidden' id='empresa-id' name='empresa-id' value='$empresaID'>";
}
else{
	$htmlEmpresa = "<div class='titulo-secundario' style='float:left; width:100%;'>
						<p>Empresa</p>
						<p><select id='empresa-id' name='empresa-id' class='required'><option value=''>Selecione</option>".optionValueEmpresas($empresaID)."</select></p>
					</div>";
}

if ($_POST){
	$cliente 		= $_POST['localiza-oportunidade-cliente'];
	$nome 			= $_POST['localiza-oportunidade-nome'];
	$origem 		= $_POST['oportunidade-origem'];
	$situacaoFunil 	= $_POST['oportunidade-situacao-funil'];
	$responsavel 	= $_POST['oportunidade-responsavel'];
	$tipoOportunidade = $_POST['localiza-tipo-oportunidade'];
}

if ($cliente!=''){ $sqlCond .= " and c.Nome like '$cliente%' "; }
if ($nome!=''){ $sqlCond .= " and o.Titulo like '%$nome%' "; }
if ($origem!=''){ $sqlCond .= " and o.Origem_ID = '$origem' "; }
if ($situacaoFunil!=''){ $sqlCond .= " and o.Situacao_ID = '$situacaoFunil' "; }
if ($responsavel!=''){ $sqlCond .= " and o.Responsavel_ID = '$responsavel' "; }
if ($tipoOportunidade!=''){ $sqlCond .= " and o.Tipo_ID IN ($tipoOportunidade) "; }

?>
<div class="titulo-container">
	<div class="titulo">
		<p>
			Filtros de Pesquisa
			<input type="button" value="Incluir Novo" class='oportunidade-localiza' oportunidade-id=''>
		</p>
	</div>
	<input type='hidden' name='oportunidade-id' id='oportunidade-id'>
	<div class="conteudo-interno">
		<?php echo $htmlEmpresa;?>
		<div class="titulo-secundario" style='width:25%; float:left;'>
			<p><b>Cliente</b></p>
			<p><input type='text' name='localiza-oportunidade-cliente' id='localiza-oportunidade-cliente'  value='<?php echo $cliente; ?>' style='width:95%;'></p>
		</div>
		<div class="titulo-secundario" style='width:25%; float:left;'>
			<p><b>Nome Oportunidade</b></p>
			<p><input type='text' name='localiza-oportunidade-nome' id='localiza-oportunidade-nome'  value='<?php echo $nome; ?>' style='width:95%;'></p>
		</div>
		<div class='titulo-secundario' style='width:25%; float:left;'>
			<p><b>Tipo Oportunidade</b></p>
			<p><select name='localiza-tipo-oportunidade' id='localiza-tipo-oportunidade'><?php echo optionValueGrupo(77, $tipoOportunidade,'&nbsp;');?></select></p>
		</div>
		<div class='titulo-secundario' style='width:25%; float:left;'>
			<p><b>Status do Funil</b></p>
			<p><select class='required' name='oportunidade-situacao-funil' id='oportunidade-situacao-funil'><?php echo optionValueGrupo(51,$situacaoFunil,'&nbsp;', '', " Tipo_Auxiliar ");?></select></p>
		</div>
		<div class='titulo-secundario' style='width:25%;float:left;'>
			<p><b>Respons&aacute;vel Oportunidade</b></p>
			<p><select name='oportunidade-responsavel' id='oportunidade-responsavel' class='required' style='width:98.5%'><?php echo optionValueUsuarios($responsavel, unserialize($configChamados['orcamento-grupos-responsaveis']), '','&nbsp;');?></select></p>
		</div>
		<div class='titulo-secundario' style='width:25%; float:left;'>
			<p><b>Origem</b></p>
			<p><select class='required' name='oportunidade-origem' id='oportunidade-origem'><?php echo optionValueGrupo(76, $origem,'&nbsp;', '', " Tipo_Auxiliar ");?></select></p>
		</div>
		<div class="titulo-secundario" style="margin-top:15px; width:10%; float:right;">
			<p><input type="button" style="width:90%; float:right;" value="Pesquisar" id="botao-localizar-oportunidades"></p>
		</div>
	</div>
</div>

<?php
$sql = " SELECT o.Oportunidade_ID, o.Empresa_ID, o.Origem_ID, o.Titulo, o.Descricao, o.Data_Cadastro, o.Expectativa_Valor,
			c.Nome as Cliente, e.Nome as Empresa, r.Nome as Responsavel,
			ts.Descr_Tipo as Situacao, ts.Tipo_Auxiliar as Situacao_Dados,
			tor.Descr_Tipo as Origem, o.Orcamento_ID as Orcamento_ID,
			o.Probabilidade_Fechamento as Probabilidade_Fechamento, o.Data_Previsao as Data_Previsao,
			top.Descr_Tipo as Tipo_Oportunidade
			FROM oportunidades_workflows o
			INNER JOIN cadastros_dados e on e.Cadastro_ID = o.Empresa_ID
			INNER JOIN tipo ts on ts.Tipo_ID = o.Situacao_ID
			LEFT JOIN cadastros_dados c on c.Cadastro_ID = o.Cadastro_ID
			LEFT JOIN tipo tor on tor.Tipo_ID = o.Origem_ID
			LEFT JOIN tipo top on top.Tipo_ID = o.Tipo_ID
			LEFT JOIN cadastros_dados r on r.Cadastro_ID = o.Responsavel_ID
			WHERE o.Status_ID = 1
				and o.Orcamento_ID is null
				$sqlCond
			ORDER BY o.Data_Cadastro DESC";

//echo $sql;
$resultado = mpress_query($sql);
while($row = mpress_fetch_array($resultado)){
	$i++;

	$situacaoDados = unserialize($row['Situacao_Dados']);
	$style = "";
	if ($situacaoDados['cor-fundo']!='') $style .= "background-color: ".$situacaoDados['cor-fundo'].";";
	if ($situacaoDados['cor-texto']!='') $style .= "color: ".$situacaoDados['cor-texto'].";";

	$dados[colunas][tr][$i] = " style='font-weight:bold; cursor:pointer;' class='oportunidade-localiza lnk' oportunidade-id='".$row['Oportunidade_ID']."'";
	$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Cliente']."</p>";
	$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Titulo']."</p>";
	$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Situacao']."</p>";
	$dados[colunas][extras][$i][3] = " style='".$style."'";
	$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;text-align:center;'>".$row['Tipo_Oportunidade']."</p>";
	$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;text-align:center;'>".$row['Orcamento_ID']."</p>";
	$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Responsavel']."</p>";
	$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row['Origem']."</p>";
	$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 5px 0 5px;float:right;'>R$ ".number_format($row['Expectativa_Valor'], 2, ',', '.')."</p>";
	$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 5px 0 5px;float:left;'>".number_format($row['Probabilidade_Fechamento'], 2, ',', '.')." %</p>";
	$dados[colunas][conteudo][$i][10] = "<p Style='margin:2px 5px 0 5px;float:left;'>".substr(converteDataHora($row['Data_Previsao'],1),0,10)."</p>";
	$dados[colunas][conteudo][$i][11] = "<p Style='margin:2px 5px 0 5px;float:left;'>".substr(converteDataHora($row['Data_Cadastro'],1),0,10)."</p>";

	/*
	$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;'>
											<div class='btn-editar botao-editar-oportunidade' oportunidade-id='".$row['Oportunidade_ID']."' cadastro-id='".$row['Cadastro_ID']."' style='float:right; padding-right:10px' title='Editar'>&nbsp;</div></div>
											<div class='btn-excluir botao-excluir-oportunidade' oportunidade-id='".$row['Oportunidade_ID']."' cadastro-id='".$row['Cadastro_ID']."' style='float:right; padding-right:10px' title='Excluir'>&nbsp;</div></div>
										</p>";
	*/
}
$largura = "100.2%";
$colunas = "11";
/*
$dados[colunas][tamanho][1] = "width=''";
$dados[colunas][tamanho][2] = "width=''";
$dados[colunas][tamanho][3] = "width='120px'";
$dados[colunas][tamanho][4] = "width='100px'";
*/

$dados[colunas][titulo][1] 	= "Cliente";
$dados[colunas][titulo][2] 	= "Nome Oportunidade";
$dados[colunas][titulo][3] 	= "Situa&ccedil;&atilde;o";
$dados[colunas][titulo][4] 	= "<center>Tipo Oportunidade</center>";
$dados[colunas][titulo][5] 	= "<center>Or&ccedil;amento</center>";
$dados[colunas][titulo][6] 	= "Respons&aacute;vel";
$dados[colunas][titulo][7] 	= "Origem";
$dados[colunas][titulo][8] 	= "Expectativa";
$dados[colunas][titulo][9]	= "Probabilidade";
$dados[colunas][titulo][10] 	= "Data Previs&atilde;o";
$dados[colunas][titulo][11]	= "Data Cadastro";


$dados[colunas][ordena][1] = "Cliente";
$dados[colunas][ordena][2] = "o.Titulo";
$dados[colunas][ordena][3] = "ts.Descr_Tipo";
$dados[colunas][ordena][4] = "to.Descr_Tipo";
$dados[colunas][ordena][5] = "o.Orcamento_ID";
$dados[colunas][ordena][6] = "Responsavel";
$dados[colunas][ordena][7] = "Origem";
$dados[colunas][ordena][8] = "o.Data_Cadastro";
$dados[colunas][ordena][9] = "o.Data_Cadastro";
$dados[colunas][ordena][10]	= "o.Data_Previsao";
$dados[colunas][ordena][11]	= "o.Probabilidade_Fechamento";



?>
<div class="titulo-container">
	<div class="titulo">
		<p>Registro encontrados: <?php echo $i; ?></p>
	</div>
	<div class="conteudo-interno">
<?php
if($i==0){
	$h .= "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma oportunidade cadastrada!</p>";
}
else{
	$h = geraTabela($largura, $colunas, $dados, "", 'lista-oportunidades', 4, 0, 100,'','return');
}
echo $h;
?>
	</div>
</div>