<?php
include("functions.php");
$campanhaID = $_POST['campanha-id'];

if ($campanhaID!=''){
	$textoBotao = 'Atualizar';
	$sql = "SELECT Operacao_ID, Nome, Tipo_Campanha_ID, Formulario_ID, Campanha_Conta_ID, Dados_Campanha, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID
				FROM tele_campanhas where Campanha_ID = '$campanhaID'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$operacaoID = $rs['Operacao_ID'];
		$nome = $rs['Nome'];
		$situacaoID = $rs['Situacao_ID'];
		$operacaoID = $rs['Operacao_ID'];
		$formularioID = $rs['Formulario_ID'];
		$tipoCampanhaID = $rs['Tipo_Campanha_ID'];
		$campanhaContaID = $rs['Campanha_Conta_ID'];
		$config = unserialize($rs['Dados_Campanha']);
		$config['indice-correcao-sel'][$config['indice-correcao']] = "selected";

		$sql = "select Lista_ID from tele_campanhas_listas where Campanha_ID = '$campanhaID' and Situacao_ID = 1";

		$resultado1 = mpress_query($sql);
		while($rs1 = mpress_fetch_array($resultado1)){
			$listasCampanha[] = $rs1['Lista_ID'];
		}
	}
}
else{
	$situacaoID = "160";
	$textoBotao = "Incluir";
	$fluxoOperacao = "A";
	$sqlCondSituacao = " and Tipo_ID = 160 ";
}

echo "	<div class='titulo-container dados-gerais'>
			<div class='titulo'>
				<p>
					Dados Campanha
					<input type='button' value='$textoBotao' class='salvar-campanha' style='width:150px;'>
				</p>
			</div>

			<div class='conteudo-interno form-incluir-alterar-formularios'>
				<div class='titulo-secundario' style='float:left; width:10%;'>
					<p><b>ID</b></p>
					<p><input type='text' id='campanha-id' name='campanha-id' value='$campanhaID' style='width:90%' readonly/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:70%;'>
					<p><b>T&iacute;tulo Campanha</b></p>
					<p><input type='text' id='nome' name='nome' value='$nome' style='width:99%' class='required'/></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:20%;'>
					<p><b>Situa&ccedil;&atilde;o</b></p>
					<p><select name='situacao-id' id='situacao-id' class='required'>".optionValueGrupo(68, $situacaoID, '', $sqlCondSituacao)."</select></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:50%;'>
					<p><b>Opera&ccedil;&atilde;o</b></p>
					<p><select id='operacao-id' name='operacao-id' class='required'>
						<option value=''>Selecione</option>
						".optionValueOperacoes($operacaoID,'')."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='float:left; width:50%;'>
					<p><b>Tipo Campanha:</b></p>
					<p><select name='tipo-campanha-id' id='tipo-campanha-id' class='required'>".optionValueGrupo(67, $tipoCampanhaID, '', '')."</select></p>
				</div>
				<div class='titulo-secundario bloco-listagem-campanha esconde' style='float:right; width:50%;'>
					<div class='titulo-secundario' style='float:left; width:95%;'>
						<p><b>Listagens campanha:</b></p>
						<p>
							<select name='listagem-campanha[]' id='listagem-campanha' class='' multiple>
							<option value=''></option>
							".optionValueListasCampanhas($listasCampanha, '')."
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='float:left; width:5%; margin-top:18px;'>
						<div class='btn-mais btn-incluir-nova-listagem' title='Incluir nova listagem' style='float:left;padding-left:10px'>&nbsp;</div>
					</div>
				</div>

				<div class='titulo-secundario bloco-cobranca esconde' style='float:left; width:50%;'>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<p><b>Conta</b></p>
						<p>
							<select name='campanha-conta-id' id='campanha-conta-id' class=''>
							<option value=''>Selecione</option>
							".optionValueContas($campanhaContaID, " and Tipo_Conta_ID = '171'")."
							</select>
						</p>
					</div>
				</div>

				<div class='titulo-secundario bloco-formulario-campanha esconde' style='float:right; width:50%;'>
					<div class='titulo-secundario' style='float:left; width:95%;'>
						<p><b>Formul&aacute;rio Campanha</b></p>
						<p>
							<select name='formulario-id' id='formulario-id' class=''>
							<option value=''>Selecione</option>
							".optionValueFormularios($formularioID, 'tele_workflows','')."
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='float:left; width:5%; margin-top:18px;'>
						<div class='btn-mais btn-incluir-novo-formulario' title='Incluir novo formul&aacute;rio' style='float:left;padding-left:10px'>&nbsp;</div>
					</div>
				</div>
				<div class='titulo-secundario bloco-cobranca esconde' style='float:left; width:100%; margin-top:15px;'>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<p><b>Configura&ccedil;&otilde;es Cobran&ccedil;a</b></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<div style='float:left; width:25%;'>
							<p>Permite agrupar valores de t&iacute;tulos?</p>
							<p><select id='agrupa-titulos'  name='config[agrupa-titulos]'><option></option>".optionValueSimNao($config['agrupa-titulos'])."</select></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<div style='float:left; width:25%;'>
							<p>Realiza cobran&ccedil;a honorários?</p>
							<p><select id='honorario-cobranca'  name='config[honorario-cobranca]'><option></option>".optionValueSimNao($config['honorario-cobranca'])."</select></p>
						</div>
						<div style='float:left; width:12.5%;'>
							<p>Percentual honor&aacute;rios(%)</p>
							<p><input type='text' id='honorario-porcentagem' name='config[honorario-porcentagem]' maxlength='6' class='formata-valor' style='width:95%;' value='".$config['honorario-porcentagem']."'/></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<div style='float:left; width:25%;'>
							<p>Realiza corre&ccedil;&atilde;o monet&aacute;ria?</p>
							<p><select id='correcao-monetaria'  name='config[correcao-monetaria]'><option></option>".optionValueSimNao($config['correcao-monetaria'])."</select></p>
						</div>
						<div style='float:left; width:25%;'>
							<p>Indice de atualiza&ccedil;&atilde;o?</p>
							<p><select id='indice-correcao'  name='config[indice-correcao]'>
								<option></option>
								<option value='inpc' ".$config['indice-correcao-sel']['inpc'].">INPC - &Iacute;ndice Nacional de Pre&ccedil;os ao Consumidor</option>
								</select>
							</p>
						</div>

						<div class='titulo-secundario' style='float:left; width:12.5%;'>
							<p>Taxa de juros mensal (%)</p>
							<p><input type='text' id='juros-mensal' name='config[juros-mensal]' maxlength='6' class='formata-valor' style='width:95%;' value='".$config['juros-mensal']."'/></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:12.5%;'>
							<p>Multa (%)</p>
							<p><input type='text' id='multa-percentual' name='config[multa-percentual]' maxlength='6' class='formata-valor' style='width:95%;' value='".$config['multa-percentual']."'/></p>
						</div>
					</div>
				</div>
			</div>
		</div>";

if ($campanhaID!=''){
	echo "	<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>
						Operadores Campanha
						<input type='button' value='Incluir Colaborador' class='incluir-colaborador-campanha' style='width:150px;'>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div id='div-incluir-usuario-campanha' class='titulo-secundario esconde' style='float:left; width:100%;'></div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						".campanhasCarregarUsuarios()."
					</div>
				</div>
			</div>";

	echo "	<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>
						Situações Campanha
						<input type='button' value='Incluir Situa&ccedil;&atilde;o' class='incluir-situacao-campanha' style='width:150px;' situacao-campanha-id=''>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div id='div-incluir-situacao-campanha' class='titulo-secundario' style='float:left; width:100%;'></div>
					<div id='div-carregar-situacoes-campanha' class='titulo-secundario' style='float:left; width:100%;'>
						".campanhasCarregarSituacoes()."
					</div>
				</div>
			</div>";

	echo "	<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>
						Motivos Cancelamento
						<input type='button' value='Incluir Motivo' class='incluir-motivo-campanha' style='width:150px;' motivo-campanha-id=''>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div id='div-incluir-motivo-campanha' class='titulo-secundario' style='float:left; width:100%;'></div>
					<div id='div-carregar-motivo-campanha' class='titulo-secundario' style='float:left; width:100%;'>
						".campanhasCarregarMotivos()."
					</div>
				</div>
			</div>";


	echo "	<!--
			<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>
						Histórico Campanha
					</p>
				</div>
				<div class='conteudo-interno form-incluir-alterar-formularios'>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						".campanhasCarregarHistorico()."
					</div>
				</div>
			</div>
			-->";

echo "		<!-- INICIO Bloco Upload usando PLUPLOAD -->
			<div id='div-documentos'></div>
			<div id='container'>
				<input type='hidden' id='pickfiles'/>
				<input type='hidden' id='uploadfiles'/>
			</div>
			<!-- FIM Bloco Upload usando PLUPLOAD -->";
}