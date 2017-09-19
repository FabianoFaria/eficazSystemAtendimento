<?php
include("functions.php");
$operacaoID = $_POST['operacao-id'];
if ($operacaoID!=''){
	$textoBotao = 'Atualizar';
	$sql = "SELECT Nome, Operacao_ID, Empresa_ID, Fluxo_Operacao, Tipo_Operacao_ID, Situacao_ID
				FROM tele_operacoes where Operacao_ID = '$operacaoID'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$nome = $rs['Nome'];
		$situacaoID = $rs['Situacao_ID'];
		$empresaID = $rs['Empresa_ID'];
		$tipoOperacaoID = $rs['Tipo_Operacao_ID'];
		$fluxoOperacao = $rs['Fluxo_Operacao'];
	}
}
else{
	$situacaoID = '1';
	$textoBotao = 'Incluir';
	$fluxoOperacao = 'A';
}
$opcoesModulos = optionValueModulosProjeto($tabelaEstrangeira);

$chkOperacao[$fluxoOperacao] = ' checked ';

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

echo "	<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Dados Opera&ccedil;&atilde;o
					<input type='button' value='$textoBotao' class='salvar-operacao' style='width:150px;'>
				</p>
			</div>

			<div class='conteudo-interno form-incluir-alterar-formularios'>
				<div class='titulo-secundario' style='float:left; width:10%;'>
					<p>ID</p>
					<p><input type='text' id='operacao-id' name='operacao-id' value='$operacaoID' style='width:90%' readonly/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:50%;'>
					<p>Nome Opera&ccedil;&atilde;o</p>
					<p><input type='text' id='nome' name='nome' value='$nome' style='width:97.5%' class='required'/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:20%;'>
					<p>Opera&ccedil;&atilde;o:</p>
					<p>
						<input type='radio' id='operacao' name='fluxo-operacao' value='A' ".$chkOperacao['A']."/>Ativa
						<input type='radio' id='operacao' name='fluxo-operacao' value='R' ".$chkOperacao['R']."/>Receptiva
						<input type='radio' id='operacao' name='fluxo-operacao' value='AR' ".$chkOperacao['AR']."/>Ambas
					</p>
				</div>
				<!--
				<div class='titulo-secundario' style='float:left; width:20%;'>
					<p>Tipo Campanha:</p>
					<p><select name='tipo-operacao-id' id='tipo-operacao-id' class='required'>".optionValueGrupo(67, $tipoOperacaoID, '', '')."</select></p>
				</div>
				-->
				<div class='titulo-secundario' style='float:left; width:20%;'>
					<p>Situa&ccedil;&atilde;o:</p>
					<p><select name='situacao-id' id='situacao-id' class='required'>".optionValueGrupo(1, $situacaoID, '', 'and Tipo_ID IN (1,2)')."</select></p>
				</div>
				$htmlEmpresa
			</div>
		</div>";