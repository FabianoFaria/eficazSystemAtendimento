<?php
//include("functions.php");
global $modulosAtivos, $caminhoSistema, $dadosUserLogin;
$contEmpresas 	= verificaNumeroEmpresas();
$workflowID 	= $_POST["workflow-id"];
$orcamentoId 	= $_POST["chave-estrangeira-tarefa"];


if ($workflowID!=""){
	$descricaoBotao = "Atualizar ".$_SESSION['objeto'];
	$descricaoFollow = "Observa&ccedil;&atilde;o";
	$sql = "SELECT w.Cadastro_ID, w.Solicitante_ID, w.Prestador_ID, w.Codigo, w.Tipo_Workflow_ID, w.Data_Cadastro, w.Usuario_Cadastro_ID, w.Prioridade_ID,
				DATE_FORMAT(w.Data_Abertura, '%d/%m/%Y %H:%i') as Data_Abertura,
				DATE_FORMAT(w.Data_Finalizado, '%d/%m/%Y %H:%i') as Data_Finalizado,
				DATE_FORMAT(w.Data_Limite, '%d/%m/%Y %H:%i') as Data_Limite,
				c.Nome as Cadastro, w.Titulo, w.Grupo_Responsavel_ID, w.Responsavel_ID
				FROM chamados_workflows w
				LEFT JOIN cadastros_dados c on c.Cadastro_ID = w.Cadastro_ID
				WHERE Workflow_ID = $workflowID";

	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){
		$cadastro 			= $rs['Cadastro'];
		$cadastroID 		= $rs['Cadastro_ID'];
		$solicitanteID 		= $rs['Solicitante_ID'];
		$prestadorID 		= $rs['Prestador_ID'];
		$tipoWorkflowID 	= $rs['Tipo_Workflow_ID'];
		$prioridadeID 		= $rs['Prioridade_ID'];
		$dataCadastro 		= $rs['Data_Cadastro'];
		$responsavelID 	 	= $rs['Responsavel_ID'];
		$grupoResponsavelID = $rs['Grupo_Responsavel_ID'];

		$dataAbertura 		= $rs['Data_Abertura']; if ($dataAbertura == "00/00/0000 00:00") $dataAbertura = "";
		$dataFinalizado 	= $rs['Data_Finalizado']; if ($dataFinalizado == "00/00/0000 00:00") $dataFinalizado = "";
		$dataLimite 		= $rs['Data_Limite']; if ($dataLimite == "00/00/0000 00:00") $dataLimite = "";

		$tituloChamado		= $rs['Titulo'];

		$usuarioCadastroID 	= $rs[Usuario_Cadastro_ID];
		$codigo 			= $rs[Codigo];

		$sql = "SELECT Follow_ID, Descricao, Dados, t.Descr_Tipo as Situacao, cf.Situacao_ID as Situacao_ID, DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, cd.Nome as Usuario_Follow
				FROM chamados_follows cf
				LEFT JOIN cadastros_dados cd on cd.Cadastro_ID = cf.Usuario_Cadastro_ID
				LEFT JOIN tipo t on t.Tipo_ID = cf.Situacao_ID
				WHERE Workflow_ID = $workflowID
				ORDER BY cf.Follow_ID desc";
		// echo $sql;

		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($i==1){
				$situacaoAtualID = $rs['Situacao_ID'];
				$situacaoAtual = $rs['Situacao'];
				$descricaoAdicional = "N&ordm; $workflowID - $situacaoAtual";
			}
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br($rs['Descricao'])."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
		}
		if($i>=1){
			$largura = "99%";
			$dados[colunas][titulo][1] 	= "Observações Cadastradas";
			$dados[colunas][tamanho][1] = "width=''";
			$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][tamanho][2] = "width='180px'";
			$dados[colunas][titulo][3] 	= "Data";
			$dados[colunas][tamanho][3] = "width='180px'";
		}
	}
}
else{
	$situacaoAtualID = "32";
	$classeEsconde = "esconde";
	$dataAbertura = retornaDataHora('d','d/m/Y H:i');
	if ($_POST['cadastro-id']!="") $solicitanteID = $_POST['cadastro-id'];
	$responsavelID 	 = $dadosUserLogin[userID];
	$grupoResponsavelID = $dadosUserLogin[grupoID];
	$descricaoFollow = "Descri&ccedil;&atilde;o Inicial";
	$descricaoBotao = "Abrir ".$_SESSION['objeto'];
}

if ($contEmpresas==1){
	if (($cadastroID=="")||($cadastroID=="0")){
		$cadastroID = retornaCodigoEmpresa();
	}
	$empresasCadastradas = "<input type='hidden' id='cadastro-id' name='cadastro-id' value='$cadastroID'>";
}
else{
	$empresasCadastradas = "<div style='float:left;width:100%;'><b>Empresa Respons&aacute;vel</b></div>
							<div style='float:left;width:100%;margin-bottom:3px'><select id='cadastro-id' name='cadastro-id' style='width:99.7%'><option value=''>Selecione</option>".optionValueEmpresas($cadastroID)."</select></div>";
}

$sql = "SELECT Email FROM cadastros_dados WHERE Cadastro_ID = ".$dadosUserLogin['userID'];
$query = mpress_query($sql);
if($rs = mpress_fetch_array($query))
	$emailLogado = $rs[Email];



// caso processo aberto e etc..
if (($situacaoAtualID!="33")&&($situacaoAtualID!="34")){
	$botaoIncluirProdutos = "<input type='button' class='botao-exibir-produto' style='float:right;' value='Incluir Novo'/>";
	$botaoSolicitante = "<input type='button' class='editar-cadastro-generico' style='float:right;margin-right:0px; width:50px;' value='Alterar' id='botao-alterar-solicitante-id' campo-alvo='solicitante-id'>";
	$botaoPrestador = "<input type='button' class='editar-cadastro-generico' style='float:right;margin-right:0px; width:50px;' value='Alterar' id='botao-alterar-prestador-id' campo-alvo='prestador-id'>";
	$divSituacao = "<div class='titulo-secundario' style='width:100%;'>
					<p><b>$descricaoFollow</b></p>
					<p class='omega'><textarea id='descricao-follow' name='descricao-follow' style='height:60px;width:99.3%'></textarea></p>
				</div>
				<div class='titulo-secundario' style='width:73%;float:left;' id='div-situacao-follow'>
					<p><b>Situa&ccedil;&atilde;o</b></p>
					<select name='select-situacao-follow' id='select-situacao-follow' style='width:98.5%' class='required'>".optionValueGrupo(18, $situacaoAtualID, "Selecione")."</select>
					</p>
				</div>
				<div class='titulo-secundario div-data-finalizado' style='width:15%;float:left;'>
					<p><b>Data Finaliza&ccedil;&atilde;o</b></p>
					<p><input type='text' id='data-finalizado-chamado' name='data-finalizado-chamado' class='formata-data-hora' style='width:91%' maxlength='16'/></p>
				</div>
				<div style='width:2%;float:left;'>
					<p style='margin-top:20px; margin-left:5px'><input type='checkbox' id='enviar-email' name='enviar-email'/></p>
				</div>
				<div style='width:10%;float:left;'>
					<p style='margin-top:22px; margin-left:5px'><label for='enviar-email' style='cursor:pointer;'><b>ENVIAR EMAIL</b></label></p>
				</div>
				<div style='width:15%;float:right;'>
					<p>&nbsp;</p>
					<p><input type='button' value='$descricaoBotao' id='botao-cadastra-workflow'  Style='width:95%;'/></p>
				</div>";
	if ($workflowID!="")
		$botaoAtualizarDados = "<input type='button' value='Atualizar Dados' id='botao-salva-workflow'  Style='float:right;margin-right:0px;'/>";
}
// caso fechado ou cancelado
if (($situacaoAtualID=="33")||($situacaoAtualID=="34")){
	$dadosFechamento = $situacaoAtual;
	if ($situacaoAtualID=="34") $dadosFechamento .= " em $dataFinalizado &nbsp;&nbsp; <input type='hidden' name='data-finalizado-chamado' value='$dataFinalizado'/></p>";
	if ($dadosUserLogin[grupoID]==1){
		$reabrirChamado = "
			<div class='titulo-secundario' style='float:left;width:100%;'>
				<input type='button' value='Re-Abrir ".$_SESSION['objeto']."' id='botao-reabrir-chamado' Style='width:150px;float:right; margin-top:10px'/></p>
			</div>
			<div class='titulo-secundario esconde' Style='float:left;width:99%;' id='descricao-motivo-reabertura'>
				<p><b>Descreva o motivo da re-abertura</b></p>
				<p class='omega'>
					<textarea id='descricao-follow' name='descricao-follow' style='height:60px;width:100%'></textarea>
					<input type='hidden' name='select-situacao-follow' id='select-situacao-follow' style='width:98.5%' value='32'>
				</p>
			</div>";
	}
}


// caso usuario externo representante
if($dadosUserLogin[grupoID] == -3){
	$botaoSolicitante = "";
	$botaoAtualizarDados = "";
}


echo "	<input type='hidden' id='nome-campo' name='nome-campo'/>
		<input type='hidden' class='email-workflow' id='email-workflow' value='$emailLogado;'/>
		<input type='hidden' id='localiza-workflow-id'  name='localiza-workflow-id' value=''/>
		<input type='hidden' id='localiza-conta-id'  name='localiza-conta-id' value=''/>
		<input type='hidden' id='localiza-titulo-id'  name='localiza-titulo-id' value=''/>
		<input type='hidden' id='ordem-compra-id'  name='ordem-compra-id' value=''/>
		<input type='hidden' id='localiza-requisicao-id'  name='localiza-requisicao-id' value=''/>
		<input type='hidden' id='radio-tipo-grupo'  name='radio-tipo-grupo' value=''/>
		<input type='hidden' id='cadastroID'  name='cadastroID' value=''/>
		<input type='hidden' id='produto-id'  name='produto-id' value=''/>
		<input type='hidden' id='tabela-estrangeira'  name='tabela-estrangeira' value='chamados'/>

		<div id='div-retorno'></div>
		<div class='titulo-container conjunto1' id='div-solicitante-dados'>
			<div class='titulo'>
				<p>Dados do Solicitante $botaoSolicitante</p>
			</div>
			<div class='conteudo-interno' id='conteudo-interno-solicitante'>";
				carregarBlocoCadastroGeral($solicitanteID, 'solicitante-id','Solicitante',1,'','','','required');
echo "		</div>
		</div>";

if ($configChamados['exibe-bloco-prestador']==1){
	if($dadosUserLogin[grupoID] == -2) $prestadorID = $dadosUserLogin[userID];
	if(($dadosUserLogin[grupoID] != -3) && ($dadosUserLogin[grupoID] != -2)){
		echo "	<div class='titulo-container conjunto1 $visaoPrestador;' id='div-prestador-dados'>
					<div class='titulo'>
						<p>	Dados do Prestador
							$botaoPrestador
						</p>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-prestador'>
						<input type='hidden' name='prestador-id-ant' id='prestador-id-ant' value='$prestadorID'/>";
						carregarBlocoCadastroGeral($prestadorID, 'prestador-id','Prestador',1,'');
		echo "		</div>
				</div>";

	}
}

// echo "modulo ativo ".$modulosAtivos['produtos'];
// echo "Workflow atual: ".$workflowID;
// echo "Orcamento atual: ".$orcamentoId;

if ($modulosAtivos['produtos']){
	echo "	<div class='titulo-container $classeEsconde; conjunto2 esconde' id='div-produtos-dados'>
				<div class='titulo'>
					<p>
						Produtos e Servi&ccedil;os
						$botaoIncluirProdutos
					</p>
				</div>
				<div class='conteudo-interno titulo-secundario' id='conteudo-interno-produtos'>
					<div id='div-produtos-incluir-editar' Style='float:left;width:100%;'></div>
					<div id='div-produtos' class='titulo-secundario uma-coluna' style='margin-top:5px;'>";
					carregarProdutos($workflowID,'chamado');
		echo "
					</div>
				</div>
			</div>";
}

if ($modulosAtivos['envios']){
	echo "	<div id='div-centro-distribuicao'>";
	carregarEnviosCD($workflowID, "esconde");
	echo "	</div>";
}

if ($modulosAtivos['financeiro']){
	echo "	<div class='titulo-container esconde conjunto7' id='div-financeiro-dados'>
				<div class='titulo'>
					<p>
						Financeiro
					</p>
				</div>
				<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='div-financeiro'></div>
			</div>";
}

if ($modulosAtivos['projetos']){
	echo "<div class='titulo-container esconde conjunto10' id='div-tarefas-cadastradas-geral'>";
	carregarTarefas($workflowID,'chamados_workflows','Workflow_ID', $cadastroID);
	echo "</div>";
}


echo "	<!-- INICIO Bloco Upload usando PLUPLOAD -->
		<div id='div-documentos'></div>
		<div id='container'>
			<input type='hidden' id='pickfiles'/>
			<input type='hidden' id='uploadfiles'/>
		</div>
		<!-- FIM Bloco Upload usando PLUPLOAD -->";

echo "	<div class='titulo-container conjunto1 $classEsconde' id='div-chamado-dados'>
			<div class='titulo'>
				<p>Dados Gerais $descricaoAdicional
					<input type='hidden' id='workflow-id' name='workflow-id' value='$workflowID'/>
					<input type='hidden' id='situacao-atual-chamado' name='situacao-atual-chamado' value='$situacaoAtualID'/>
					$botaoAtualizarDados
				</p>
			</div>";

echo "		<div class='conteudo-interno titulo-secundario' id='conteudo-interno-chamado'>
				$empresasCadastradas
				<div class='titulo-secundario' style='width:10%;float:left;'>
					<p><b>C&oacute;digo ".$_SESSION['objeto']."</b></p>
					<p><input type='text' id='codigo-workflow' name='codigo-workflow' value='$codigo' style='width:91%' maxlength='50'/></p>
				</div>

				<div class='titulo-secundario' style='width:25%;float:left;'>
					<p><b>Tipo ".$_SESSION['objeto']."</b></p>
					<p><select name='tipo-workflow' id='tipo-workflow' style='width:97%'>".optionValueGrupoFilho(19, $tipoWorkflowID, "Selecione")."</select></p>
					<input type='hidden' name='tipo-workflow-ant' id='tipo-workflow-ant'  value='$tipoWorkflowID'/>
				</div>

				<div class='titulo-secundario' style='width:15%;float:left;'>
					<p><b>Data Abertura</b></p>
					<p><input type='text' id='data-abertura-chamado' name='data-abertura-chamado' class='formata-data-meia-hora required' value='$dataAbertura' style='width:91%' maxlength='16'/></p>
				</div>

				<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Prioridade</b></p>
					<p>
						<select name='select-prioridade' id='select-prioridade'>
							<option value=''>Selecione</option>
							".optionValuePrioridade($prioridadeID)."
						<select>
					</p>
				</div>

				<div class='titulo-secundario' style='width:15%;float:left;'>
					<p><b>Data Limite/Previs&atilde;o</b></p>
					<p><input type='text' id='data-limite' name='data-limite' class='formata-data-hora' style='width:91%' maxlength='16' value='$dataLimite'/></p>
				</div>

				<div class='titulo-secundario' style='width:15%;float:left;'>
					<p><b>Tempo Restante</b></p><br>
					<p><div id='div-tempo-restante' class='titulo-secundario texto-contador' style='width:95%'>&nbsp;</div></p>
				</div>

				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>T&iacute;tulo ".$_SESSION['objeto']."</b></p>
					<p class='omega' Style='float:left;'>
						<input type='text' name='titulo-chamado' id='titulo-chamado' value='$tituloChamado' Style='width:97.5%;'>
					</p>
				</div>";

echo "			<div class='titulo-secundario' style='width:20%;float:left;'>
					<p><b>Grupo Respons&aacute;vel</b></p>
					<p>
						<select name='select-grupo-chamado' id='select-grupo-chamado' style='width:98.5%' campo='select-usuario-chamado'>
							".optionValueGruposAcessos($grupoResponsavelID, "")."
						</select>
					</p>
				</div>";

echo "			<div class='titulo-secundario' style='width:30%;float:left;'>
					<p><b>Usuário Respons&aacute;vel</b>
					<p>
						<select name='select-usuario-chamado' id='select-usuario-chamado' style='width:98.5%'>
							".optionValueUsuarios($responsavelID, $grupoResponsavelID, "")."
						<select>
					</p>
				</div>";

if (($workflowID=="") && ($modulosAtivos['projetos'])){
	echo "		<div class='titulo-secundario' style='width:100%;float:left;'>
					<p><b>Projeto ".$_SESSION['objeto']."</b></p>
					<p class='omega' Style='float:left;'>
						<select name='projeto-id-chamado' id='projeto-id-chamado' style='width:100%;float:left;' class=''>
							<option value=''>Selecione</option>";
	echo optionValueProjetos($projetoID, "chamados_workflows");
	echo "			</select>
					</p>
				</div>";
}

echo $reabrirChamado;

if ($workflowID!=""){
	echo "	</div>
		</div>

		<div class='titulo-container conjunto1' id='div-situacao-chamado'>
			<div class='titulo'>
				<p Style='float:left;'>Hist&oacute;rico ".$_SESSION['objeto']."</p>
				<p Style='float:right;'>".$dadosFechamento."</p>
			</div>
			<div class='conteudo-interno titulo-secundario'>";
}
echo $divSituacao;

if ($workflowID!=""){
	echo "		<div class='titulo-secundario uma-coluna' Style='margin-top:5px;padding:0 0 5px 5px;'>";
					geraTabela("99.4%","3",$dados);
	echo "		</div>";
}
echo "		</div>
		</div>";

echo "	<div id='div-email' style='position:absolute; width:800px; height:250px; z-index:100; overflow-X:auto; display:none; border-radius:15px;'>
			<table width='100%' height='100%' border='0' cellspacing='10' cellpadding='4' bgcolor='#F9F9F9' style='border: black 1px solid'>
				<tr>
					<td colspan='2' valign='top'>
						<p style='margin:5px'><b>Enviar e-mail para:</b></p>
						<p style='margin:5px'><textarea id='emails-envio' name='emails-envio' style='height:110px;width:100%'></textarea></p>
						<p style='margin:5px'><b>* Separar e-mails com ponto e virgula <font color='red'>;</font></b></p>
					</td>
				</tr>
				<tr>
					<td align='center' width='50%'><input type='button' id='botao-submeter-email-workflow' name='botao-submeter-email-workflow' Style='width:150px' value='Enviar'></td>
					<td align='center' width='50%'><input type='button' id='botao-cancelar-email-workflow' name='botao-cancelar-email-workflow' Style='width:150px' value='Cancelar'></td>
				</tr>
			</table>
		</div>";

echo "	<div class='titulo-container conjunto6 esconde' id='div-compras-dados'></div>";

if ($_POST["workflow-id"]!=""){
	if($prestadorID >= 1) $cadastroID = $prestadorID;

	//$consulta = mpress_query("select distinct concat(Logradouro,' ',Numero,' ', Cidade,' ', Uf) Endereco from cadastros_enderecos where (Cadastro_ID = '$solicitanteID') or (Cadastro_ID = '$cadastroID')  order by Logradouro");
	$consulta = mpress_query("select distinct concat(Cidade,' ', Uf) Endereco from cadastros_enderecos where (Cadastro_ID = '$solicitanteID') or (Cadastro_ID = '$cadastroID')  order by Logradouro");
	while($row = mpress_fetch_array($consulta)){
		$optionsEnderecosOrigem .= "<option value='".$row[Endereco]."'>".$row[Endereco]."</option>";
		$optionsEnderecosDestino .= "<option value='".$row[Endereco]."' selected>".$row[Endereco]."</option>";
	}

	echo "	<div class='titulo-container conjunto8 esconde' id='div-localizacao-dados-mapa-5'>
				<div class='titulo'>
					<p Style='float:left'>Localiza&ccedil;&atilde;o</p>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div Style='float:left;width:50%;' class='esconde'>
						Busca:
						<input type='text' Style='margin-top:-3px;' id='busca-string-mapa'>
						<input type='button' value='Exibir Rota' class='busca-mapa'>
					</div>
					<div Style='float:left;width:25%;'>
						<p>Pesquisar cadastros pela regi&atilde;o:</p>
						<p>
							<select name='tipos-cadastros-regiao' id='tipos-cadastros-regiao' multiple Style='height:50px;'>
								".optionValueGrupoMultiplo(9,'')."
							</select>
						</p>
					</div>
					<div Style='float:left;width:25%;'>
						<p>&nbsp;</p>
						<p><input type='button' value='Pesquisar' class='localizar-cadastros-regiao-mapa'></p>
					</div>

					<div Style='float:right;width:25%;'>
						<p>Destino:</p>
						<p><select style='width:330px;margin-top:-3px;' id='destino-mapa' class='mostra-localizacao-select'>$optionsEnderecosOrigem</select></p>
					</div>
					<div Style='float:right;width:25%;'>
						<p>Origem:</p>
						<p><select style='width:330px;margin-top:-3px;' id='origem-mapa' class='mostra-localizacao-select'>$optionsEnderecosDestino</select></p>
					</div>
					<iframe id='frm-mapa-localizacao' src='' width='100%' height='500' frameborder='0'></iframe>
				</div>
			</div>";
}
?>