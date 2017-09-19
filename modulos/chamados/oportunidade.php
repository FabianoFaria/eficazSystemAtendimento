<?php
include('functions.php');
$oportunidadeID = $_POST['oportunidade-id'];
carregarOportunidade($oportunidadeID,'');
function carregarOportunidade($oportunidadeID, $cadastroID){
	global $caminhoSistema, $dadosUserLogin, $modulosAtivos;

	$tipoFluxo = $_GET['tipo-fluxo'];
	if ($tipoFluxo=='direto'){
		echo "	<style>
					#topo-container{display:none;}
					#menu-container{display:none;}
				</style>";
		$cadastroID = $_GET['cadastro-id'];
	}


	$configChamados = carregarConfiguracoesGeraisModulos('chamados');
	$dataCadastro = $dataHoraAtual = retornaDataHora('','d/m/Y H:i');
	$valor = "0,00";
	$situacaoFunil = "";
	if($oportunidadeID!=''){
		$sql = "SELECT Oportunidade_ID, Tipo_ID, Orcamento_ID, Cadastro_ID, Empresa_ID, Origem_ID, Chave_Estrangeira, Tabela_Estrangeira, Titulo,
						Descricao, Expectativa_Valor, Situacao_ID, Status_ID, Responsavel_ID, Usuario_Cadastro_ID, Data_Cadastro, Data_Previsao, Probabilidade_Fechamento
					FROM oportunidades_workflows
					where Oportunidade_ID = '$oportunidadeID'";


		//echo $sql;
		$resultado = mpress_query($sql);
		$orcamentoID = "";
		if($row = mpress_fetch_array($resultado)){
			if ($row['Orcamento_ID']!=""){
				$readOnly = "readonly='readonly'";
				$esconde = "esconde";
			}
			$orcamentoID = $row['Orcamento_ID'];
			$cadastroID = $row['Cadastro_ID'];
			$empresaID = $row['Empresa_ID'];
			$origemID = $row['Origem_ID'];
			$chaveEstrangeira = $row['Chave_Estrangeira'];
			$tabelaEstrangeira = $row['Tabela_Estrangeira'];
			$nome = $row['Titulo'];
			$descricao = $row['Descricao'];
			$valor = number_format($row['Expectativa_Valor'], 2, ',', '.');
			$dataCadastro = converteDataHora($row['Data_Cadastro'],1);
			$dataPrevisaoFechamento = substr(converteData($row['Data_Previsao'],1),0,10);
			$responsavelID = $row['Responsavel_ID'];
			$probabilidadeFechamento = $row['Probabilidade_Fechamento'];
			$situacaoFunil = $row['Situacao_ID'];
			$tipoID = $row['Tipo_ID'];
			if ($dataPrevisaoFechamento=="00/00/0000") $dataPrevisaoFechamento = "";
		}
	}
	else{
		$responsavelID 	 = $dadosUserLogin['userID'];
	}
	/*	BLOCO EMPRESA */
	if (verificaNumeroEmpresas()==1){
		$empresaID = retornaCodigoEmpresa();
		$htmlEmpresa = "<input type='hidden' id='oportunidade-empresa-id' name='oportunidade-empresa-id' value='$empresaID'>";
	}
	else{
		$htmlEmpresa = "<div class='titulo-secundario' style='float:left; width:100%;'>
							<p>Empresa</p>
							<p><select id='oportunidade-empresa-id' name='oportunidade-empresa-id' class='required'><option value=''>Selecione</option>".optionValueEmpresas($empresaID)."</select></p>
						</div>";
	}

	echo "	<input type='hidden' name='workflow-id' id='workflow-id' value='".$orcamentoID."'>";
	echo "	<div class='titulo-container conjunto1' id='div-solicitante-dados'>
				<div class='titulo'>
					<p>Dados Cliente <input type='button' class='editar-cadastro-generico ".$esconde."' style='float:right;margin-right:0px; width:50px;' value='Alterar' id='botao-alterar-cliente-id' campo-alvo='cliente-id'></p>
				</div>
				<div class='conteudo-interno' id='conteudo-interno-solicitante'>";
	carregarBlocoCadastroGeral($cadastroID, 'oportunidade-cadastro-id','Cliente',1,'','','','required');
	echo  "		</div>
			</div>";
	echo "	<div class='titulo-container conjunto1''>
				<div class='titulo'>
					<p>Dados Oportunidade</p>
				</div>
				<div class='conteudo-interno'>";
	echo "		<div Style='margin-top:5px;float:left; width:100%;' id='div-oportunidade-cadastro'>
					<div Style='margin-top:5px; width:100%;'>
						<input type='hidden' name='oportunidade-id' id='oportunidade-id' value='".$oportunidadeID."'>";
	echo $htmlEmpresa."	<div class='titulo-secundario' style='width:25%; float:left;'>
							<p><b>Nome Oportunidade</b></p>
							<p><input type='text' name='oportunidade-nome' id='oportunidade-nome' value='$nome' class='required' style='width:98%;'></p>
						</div>
						<div class='titulo-secundario' style='width:25%;float:left;'>
							<p><b>Respons&aacute;vel Oportunidade</b></p>
							<p><select name='oportunidade-responsavel' id='oportunidade-responsavel' class='required' style='width:98.5%'>".optionValueUsuarios($responsavelID, unserialize($configChamados['orcamento-grupos-responsaveis']), $condicoesRepresentante)."</select></p>
						</div>
						<div class='titulo-secundario' style='width:17.5%; float:left;'>
							<p><b>Origem</b></p>
							<p><select name='oportunidade-origem' id='oportunidade-origem'>".optionValueGrupo(76, $origemID, "Selecione")."</select></p>
						</div>
						<div class='titulo-secundario' style='width:17.5%; float:left;'>
							<p><b>&nbsp; <!--Origem ID--></b></p>
							<p><input type='text' readonly name='oportunidade-origem-id' id='oportunidade-origem-id' style='width:98.5%'/></p>
						</div>
						<div class='titulo-secundario' style='width:15%; float:left;'>
							<p><b>Data Abertura</b></p>
							<p><input type='text' class='required' id='oportunidade-data-cadastro' name='oportunidade-data-cadastro' style='width:97%; text-align:center;' maxlength='' readonly value='".$dataCadastro."'/></p>
						</div>
						<div class='titulo-secundario' style='width:25%; float:left;'>
							<p><b>Tipo Oportunidade</b></p>
							<p><select id='oportunidade-tipo' name='oportunidade-tipo'>".optionValueGrupo(77, $tipoID, "Selecione")."</select>
							<!--
							<p><b>Descri&ccedil;&atilde;o Oportunidade</b></p>
							<p><textarea id='oportunidade-descricao' name='oportunidade-descricao' style='width:98%; height:75px;' class='required'>$descricao</textarea></p>
							-->
						</div>
						<div class='titulo-secundario' style='width:25%; float:left;'>
							<p><b>Situa&ccedil;&atilde;o do Funil</b></p>
							<p><select class='required' name='oportunidade-situacao-funil' id='oportunidade-situacao-funil'>".optionValueGrupo(51,$situacaoFunil,'Selecione',' and Tipo_ID <> 111 and Tipo_ID <> 113 and Tipo_ID <> 184 ', " Tipo_Auxiliar ")."</select></p>
						</div>
						<div class='titulo-secundario' style='width:17.5%; float:left;'>
							<p><b>Expectativa de Valor R$</b></p>
							<p><input type='text' class='formata-valor required zero-nao' id='oportunidade-valor' name='oportunidade-valor' style='width:97%;' maxlength='20' value='".$valor."'/></p>
						</div>
						<div class='titulo-secundario' style='width:17.5%; float:left;'>
							<p><b>Previs&atilde;o Fechamento</b></p>
							<p><input type='text' class='formata-data' id='oportunidade-data-previsao-fechamento' name='oportunidade-data-previsao-fechamento' style='width:97%;' maxlength='' value='".$dataPrevisaoFechamento."'/></p>
						</div>
						<div class='titulo-secundario' style='width:15%; float:left;'>
							<p><b>Probabilidade fechamento</b></p>
							<p><select id='oportunidade-probabilidade-fechamento' name='oportunidade-probabilidade-fechamento' class='required'><option></option>".optionValueOportunidadePerc($probabilidadeFechamento)."</select></p>
						</div>
						<div class='titulo-secundario' style='width:25%; float:right; margin-top:15px;'>
							<p class='direita'>";
	if (1==2){
		echo "					<input type='button' value='Cancelar' class='botao-cancelar-salvar-oportunidade' cadastro-id='$cadastroID' oportunidade-id='$oportunidadeID' Style='width:47%;float:left;margin-right:5px;'/>";
	}
	echo "						<input type='button' value='Salvar'  class='botao-salvar-oportunidade ".$esconde."' cadastro-id='$cadastroID' oportunidade-id='$oportunidadeID' Style='width:47%;float:right;margin-left:5px;'/>
							</p>
						</div>

						<!--
						<div class='titulo-secundario' style='width:30%; float:left;'>
							<p><b>Tarefa</b></p>
							<p><select class='required' name='oportunidade-responsaveis[]' id='oportunidade-responsaveis' multiple>".optionValueUsuarios($responsaveis,'','','','multiple')."</select></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Data Retorno</b></p>
							<p><input type='text' class='formata-data-hora required' id='oportunidade-data-retorno' name='oportunidade-data-retorno' style='width:97%;' maxlength='' value='".$dataRetorno."'/></p>
						</div>
						<div class='titulo-secundario' style='width:30%; float:left;'>
							<p><b>Respons&aacute;veis Tarefa</b></p>
							<p><select class='required' name='oportunidade-responsaveis[]' id='oportunidade-responsaveis' multiple>".optionValueUsuarios($responsaveis,'','','','multiple')."</select></p>
						</div>
						-->

					</div>
				</div>
			</div>
		</div>";

	if (($oportunidadeID!="") && ($modulosAtivos['projetos'])){
			echo "	<div class='titulo-container conjunto1' id='div-tarefas-cadastradas-geral'>";
			carregarTarefas($oportunidadeID, 'oportunidades_workflows', 'Oportunidade_ID', $cadastroID);
			echo "	</div>";
	}
}
?>