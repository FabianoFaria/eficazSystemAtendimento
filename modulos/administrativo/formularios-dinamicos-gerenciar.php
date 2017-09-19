<?php
include("functions.php");
if ($_GET['tipo']=='direto'){
	echo "	<style>
				#topo-container{display:none;}
				#menu-container{display:none;}
			</style>";
}

$formularioID = $_POST['formulario-id'];

if ($formularioID!=''){
	$textoBotao = 'Salvar';
	$sql = "SELECT f.Formulario_ID, f.Nome, f.Tabela_Estrangeira FROM formularios f where Formulario_ID = '$formularioID'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$nome = $rs['Nome'];
		$tabelaEstrangeira = $rs['Tabela_Estrangeira'];
	}
	$tipoFluxo = $_POST['tipo'];
}
else{
	$textoBotao = 'Incluir';
	$tabelaEstrangeira = $_GET['tabela-estrangeira'];
	$tipoFluxo = $_GET['tipo'];
}
$opcoesModulos = optionValueModulosProjeto($tabelaEstrangeira);

echo "	<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Dados Formul&aacute;rio
					<input type='button' value='$textoBotao' class='salvar-formulario-dinamico' style='width:150px;'>
					<input type='hidden' name='tipo-fluxo' id='tipo-fluxo' value='$tipoFluxo'/>
				</p>
			</div>

			<div class='conteudo-interno form-incluir-alterar-formularios'>
				<div class='titulo-secundario' style='float:left; width:10%;'>
					<p>ID</p>
					<p><input type='text' id='formulario-id' name='formulario-id' value='$formularioID' style='width:90%' readonly/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:40%;'>
					<p>Nome Formul&aacute;rio</p>
					<p><input type='text' id='nome' name='nome' value='$nome' style='width:97.5%' class='required'/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:50%;'>
					<p>M&oacute;dulo Destino</p>
					<p>
						<select id='tabela-estrangeira' name='tabela-estrangeira' class='required' style='width:95%'>
							<option value=''></option>
							$opcoesModulos
						</select>
					</p>
				</div>
			</div>
		</div>";


if ($formularioID!=''){
	echo "	<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Campos Formul&aacute;rio
						<input type='button' value='Incluir Campo Formul&aacute;rio' class='inc-alt-campo-formulario' id='incluir-campo-formulario' formulario-campo-id='0' style='width:150px;'>
					</p>
				</div>";

	echo "		<div class='conteudo-interno'>
					<div id='bloco-incluir-editar-campos-0'>&nbsp;</div>";
		$sql = "SELECT fc.Nome as Campo, fc.Tipo_Campo, ffc.Formulario_Campo_ID, ffc.Campo_ID, ffc.Posicao,
						ffc.Largura, ffc.Altura, ffc.Obrigatorio, fc.Tipo_Campo as Tipo_Campo
						FROM formularios_formulario_campo ffc
						INNER join formularios_campos fc on fc.Campo_ID = ffc.Campo_ID
						WHERE ffc.Formulario_ID = '$formularioID'
						AND ffc.Situacao_ID = 1
						order by ffc.Posicao";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$formularioCampoID = $row['Formulario_Campo_ID'];
			$obrigatorio = "N&Atilde;O";
			if ($row['Obrigatorio']==1) $obrigatorio = "SIM";
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;'>".$row['Campo']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;'>".$descrTiposCampos[$row['Tipo_Campo']]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;'>$obrigatorio</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;'>".$row['Altura']."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;'>".$row['Largura']."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 5px;' align='center'>".$row['Posicao']."</p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;float:left;'>
												 	<div class='btn-editar campo-formulario-editar' style='float:right;padding-right:10px' formulario-campo-id='$formularioCampoID' title='Editar'>&nbsp;</div>
												 	<div class='btn-excluir campo-formulario-excluir' style='float:right;padding-right:10px' formulario-campo-id='$formularioCampoID' title='Excluir'>&nbsp;</div>
												</p>";
		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum campo cadastrado no formul&aacute;rio</p>";
		}
		else{
			$largura = "100%";
			$dados[colunas][titulo][1] 	= "Campo";
			$dados[colunas][titulo][2] 	= "Tipo";
			$dados[colunas][titulo][3] 	= "Obrigat&oacute;rio";
			$dados[colunas][titulo][4] 	= "Altura";
			$dados[colunas][titulo][5] 	= "Largura";
			$dados[colunas][titulo][6] 	= "Posi&ccedil;&atilde;o";
			$dados[colunas][titulo][7] 	= "&nbsp;";

			$dados[colunas][tamanho][6] = " width='50px' ";
			$dados[colunas][tamanho][7] = " width='65px' ";

			geraTabela("100%", 7, $dados, null, 'formulario-dinamico-campos-cadastrados', 2, 2, '','');
		}



		echo "
				</div>
			</div>";

	echo "	<div class='titulo-container'>
				<div class='titulo'>
					<p>Pr&eacute;-visualiza&ccedil;&atilde;o do formul&aacute;rio:</p>
				</div>
				<div class='conteudo-interno'>".montarFormularioDinamico($formularioID)."</div>
			</div>";

}