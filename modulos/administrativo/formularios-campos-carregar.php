<?php
include('functions.php');
global $caminhoSistema;
$formularioCampoID = $_GET['formulario-campo-id'];
$campoID = $_GET['campo-selecionado'];
$formularioID = $_POST['formulario-id'];

$sql = "SELECT coalesce(max(Posicao),0) + 1 as Posicao
			FROM formularios_formulario_campo where Formulario_ID = '$formularioID'
			AND Situacao_ID = 1";
$resultado = mpress_query($sql);
$rs = mpress_fetch_array($resultado);
$maxPosicao = $rs['Posicao'];

if ($formularioCampoID==''){
	$textoAcao = "Incluir";
	$largura = "100%";
	$altura = "50px";
	$obrigatorio = "";
	$posicao = $maxPosicao;
}
else{
	$textoAcao = "Alterar";
	$sql = "SELECT Campo_ID, Posicao, Largura, Altura, Obrigatorio FROM formularios_formulario_campo where Formulario_Campo_ID = '$formularioCampoID'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$campoID = $rs['Campo_ID'];
		$posicao = $rs['Posicao'];
		$largura = $rs['Largura'];
		$altura = $rs['Altura'];
		$obrigatorio = $rs['Obrigatorio'];
		$maxPosicao--;
	}
}

echo "
		<div class='titulo-secundario dados-campo-incluir' style='float:left; width:100%;'>
			<div class='titulo-secundario bloco-campos-disponiveis' style='float:left; width:100%;'>
				<div class='titulo-secundario' style='float:left; width:95%;'>
					<p><b>Campos dispon&iacute;veis para inclus&atilde;o</b></p>
					<p>
						<select id='campo-formulario-dinamico' name='campo-formulario-dinamico' class='required' style='width:99%'>
							<option value=''>Selecione</option>
							".optionValueCampos($campoID, $formularioID)."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='float:left; width:05%; margin-top:18px;'>
					<div class='btn-mais btn-incluir-modelo-campo' title='Incluir novo modelo de campo' style='float:left;padding-left:10px'>&nbsp;</div>
				</div>
			</div>
			<div class='titulo-secundario' style='float:left; width:20%;'>
				<p><b>Obrigat&oacute;rio?</b></p>
				<p>
					<select name='preenchimento-obrigatorio' id='preenchimento-obrigatorio' class='required'>
						<option value=''></option>
						".optionValueSimNao($obrigatorio)."
					</select>
				</p>
			</div>
			<div class='titulo-secundario' style='float:left; width:20%;'>
				<p><b>Altura Bloco (px)</b></p>
				<p>
					<select name='altura-linha' id='altura-linha' class='required'>
						<option></option>
						".optionValueAlturaCampos($altura)."
					</select>
				</p>
			</div>
			<div class='titulo-secundario' style='float:left; width:20%;'>
				<p><b>Largura Bloco (%)</b></p>
				<p>
					<select name='percentual-linha' id='percentual-linha' class='required'>
						<option></option>
						".optionValueTamanhosCampos($largura)."
					</select>
				</p>
			</div>
			<div class='titulo-secundario' style='float:left; width:7.5%;'>
				<p><b>Posi&ccedil;&atilde;o</b></p>
				<p>
					<select name='posicao-campo' id='posicao-campo' class='required'>
						<option></option>
						".optionValuePosicoesCampos($maxPosicao, $posicao)."
					</select>
				</p>
			</div>
			<div class='titulo-secundario' style='float:right; width:30%; margin-top:15px;'>
				<p>
					<input type='button' id='campo-formulario-incluir' value='$textoAcao' formulario-campo-id='$formularioCampoID'  style='float:right; font-size:10px; margin-left:5px; width:90px;'/>
					<input type='button' id='cancelar-campo-formulario' value='Cancelar' formulario-campo-id='0' style='float:right; font-size:10px; margin-left:5px; width:90px;'/>
				</p>
			</div>

			<div class='titulo-secundario' style='float:left; width:100%;'>&nbsp;</div>
		</div>
		<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
?>