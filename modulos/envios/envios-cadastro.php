<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');

include("functions.php");
global $modulosAtivos;
$workflowID = $_POST['localiza-workflow-id'];

$contEmpresas = verificaNumeroEmpresas();
if ($workflowID != ""){
	$sql = "select ew.Empresa_ID, ew.Cadastro_ID_de, ew.Cadastro_ID_para, ew.Transportadora_ID, ew.Tabela_Estrangeira, ew.Chave_Estrangeira, ew.Forma_Envio_ID, ew.Codigo_Rastreamento,
				DATE_FORMAT(ew.Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro,
				DATE_FORMAT(ew.Data_Envio, '%d/%m/%Y %H:%i') as Data_Envio,
				DATE_FORMAT(ew.Data_Previsao, '%d/%m/%Y %H:%i') as Data_Previsao,
				DATE_FORMAT(ew.Data_Entrega, '%d/%m/%Y %H:%i') as Data_Entrega,
				ew.Usuario_Cadastro_ID, cds.Nome as Solicitante,
				ew.Cadastro_ID_de_Endereco, ew.Cadastro_ID_para_Endereco, ew.ID_Servico_Envio, ew.Tipo_Envio_ID
				from envios_workflows ew
				left join cadastros_dados cds on cds.Cadastro_ID = ew.Usuario_Cadastro_ID
				where ew.Workflow_ID = $workflowID";
	//echo $sql;

	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$empresaID	 			= $row['Empresa_ID'];
		$solicitante				= $row['Solicitante'];
		$tipoEnvioID	 			= $row['Tipo_Envio_ID'];
		$cadastroIDde 			= $row['Cadastro_ID_de'];
		$cadastroIDpara 		= $row['Cadastro_ID_para'];
		$enderecoRemetenteID 	= $row['Cadastro_ID_de_Endereco'];
		$enderecoDestinatarioID = $row['Cadastro_ID_para_Endereco'];
		$transportadoraID 		= $row['Transportadora_ID'];
		$tabelaEstrangeira 		= $row['Tabela_Estrangeira'];
		$chaveEstrangeira 		= $row['Chave_Estrangeira'];
		if ($chaveEstrangeira==0) $chaveEstrangeira = "";
		$tipoEnvio			= $row['ID_Servico_Envio'];
		if($tipoEnvio=="") $tipoEnvio = "41106";
		$envioSeleciona[$tipoEnvio] = "checked";
		if($enderecoRemetenteID == ""){
			$enderecoDe 	= mpress_fetch_array(mpress_query("select Cadastro_Endereco_ID from cadastros_enderecos where cadastro_id = '$cadastroIDde' and tipo_endereco_ID = 26"));
			$enderecoRemetenteID 	= $enderecoDe['Cadastro_Endereco_ID'];
		}
		if($enderecoDestinatarioID == ""){
			$enderecoPara 	= mpress_fetch_array(mpress_query("select Cadastro_Endereco_ID from cadastros_enderecos where cadastro_id = '$cadastroIDpara' and tipo_endereco_ID = 26"));
			$enderecoDestinatarioID = $enderecoPara['Cadastro_Endereco_ID'];
		}
		if ($tabelaEstrangeira=="chamados"){
			$resultado2 = mpress_query("select Codigo from chamados_workflows where Workflow_ID = '$chaveEstrangeira'");
			if($row2 = mpress_fetch_array($resultado2)){
				$codigoEstrangeira = $row2[Codigo];
			}
		}
		$codigoRastreamento 	= $row[Codigo_Rastreamento];
		$formaEnvioID 			= $row[Forma_Envio_ID];
		$dataEnvio 				= $row[Data_Envio]; if ($dataEnvio == "00/00/0000 00:00") $dataEnvio = "";
		$dataPrevisaoEntrega 	= $row[Data_Previsao]; if ($dataPrevisaoEntrega == "00/00/0000 00:00") $dataPrevisaoEntrega = "";
		$dataEntrega 			= $row[Data_Entrega]; if ($dataEntrega == "00/00/0000 00:00") $dataEnvio = "";
		$dataCadastro 			= $row[Data_Cadastro]; if ($dataCadastro == "00/00/0000 00:00") $dataCadastro = "";



		$sql = "Select Follow_ID, Descricao, t.Descr_Tipo as Situacao, ef.Situacao_ID as Situacao_ID,
				DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, cd.Nome as Usuario_Follow
				from envios_follows ef
				left join tipo t on t.Tipo_ID = ef.Situacao_ID
				inner join cadastros_dados cd on cd.Cadastro_ID = ef.Usuario_Cadastro_ID
				where Workflow_ID = $workflowID
				order by ef.Follow_ID desc ";
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($i==1){
				$situacaoAtualID = $rs['Situacao_ID'];
				$situacaoAtual = $rs['Situacao'];
			}
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Descricao']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
		}
		if($i>=1){
			$largura = "100%";
			$colunas = "3";
			$dados[colunas][titulo][1] 	= "Observa&ccedil;&atilde;o";
			$dados[colunas][tamanho][1] = "width=''";
			$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][tamanho][2] = "width='180px'";
			$dados[colunas][titulo][3] 	= "Data";
			$dados[colunas][tamanho][3] = "width='180px'";
		}
	}
}
else{
	$classeEsconde = "esconde";
	$dataEnvio = retornaDataHora('d','d/m/Y H:i');
}
if ($enderecoRemetenteID=="") $enderecoRemetenteID = 0;
if ($enderecoDestinatarioID=="") $enderecoDestinatarioID = 0;


if ($contEmpresas==1){
	if (($cadastroID=="")||($cadastroID=="0")){
		$cadastroID = retornaCodigoEmpresa();
	}
	$empresasCadastradas = "<input type='hidden' id='empresa-id' name='empresa-id' value='$cadastroID'>";
}
else{
	$empresasCadastradas = "<div style='float:left;width:100%;'><b>Empresa Respons&aacute;vel</b></div>
							<div style='float:left;width:100%;margin-bottom:3px'><select id='empresa-id' name='empresa-id' style='width:99.7%'><option value=''>Selecione</option>".optionValueEmpresas($empresaID)."</select></div>";
}
$escondeCorreio = "esconde";
if($formaEnvioID == "54") $escondeCorreio = "";

$escondeDataEntrega = "esconde";

if (($situacaoAtualID=="53") || ($situacaoAtualID=="59")){
	$escondeSituacao = "esconde";
	$textoObservacao = "Motivo";
	$textoBotao = "Re-Abrir";
	//$situacaoAtualID = "";
	if ($situacaoAtualID=="53")
		$escondeDataEntrega = "";
}
else{
	$escondeSituacao = "";
	$textoObservacao = "Observa&ccedil;&atilde;o";
	$textoBotao = "Salvar";
}
?>
<div id="container-geral">
	<input type="hidden" id="localiza-workflow-id" name="localiza-workflow-id" value="<?php echo $workflowID;?>"/>
	<input type="hidden" id="workflow-id" name="workflow-id" value="<?php echo $workflowID;?>"/>
	<input type="hidden" id="texto-geral" name="texto-geral" value=""/>
	<input type="hidden" id="tipo-cadastro" name="tipo-cadastro" value=""/>
	<input type="hidden" id="cadastroID" name="cadastroID" value=""/>
	<input type='hidden' id='situacao-atual-chamado' name='situacao-atual-chamado' value='<?php echo $situacaoAtualID;?>'/>

	<input type="hidden" id="cadastro-id-de-endereco"   name="cadastro-id-de-endereco"   value="<?php echo $enderecoRemetenteID;?>"/>
	<input type="hidden" id="cadastro-id-para-endereco" name="cadastro-id-para-endereco" value="<?php echo $enderecoDestinatarioID;?>"/>
	<div id='div-retorno'></div>


	<div class="titulo-container conjunto1 conjunto4" >
		<div class="titulo" >
			<p>Dados Gerais <?php if ($workflowID!=""){ echo " - $situacaoAtual "; }?>
				<input type='button' class='botao-cadastra-envio' value='<?php echo $textoBotao; ?>'>
			</p>
		</div>
		<div class='conteudo-interno'>
			<?php echo $empresasCadastradas;?>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>ID</b></p>
				<p><input type="text" value='<?php echo $workflowID; ?>' style='width:95%' readonly/></p>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<div class='titulo-secundario' style='width:99%;float:left;'>
					<p><b>Tipo</b></p>
					<p><select name="tipo-envio" id="tipo-envio" class='required'><?php echo optionValueGrupo(59, $tipoEnvioID, "Selecione");?><select></p>
				</div>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<div class='titulo-secundario' style='width:99%;float:left;'>
					<p><b>Forma de Envio</b></p>
					<p><select name="forma-envio" id="forma-envio" class='required'><?php echo optionValueGrupo(31, $formaEnvioID, "Selecione");?><select></p>
				</div>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>C&oacute;digo Rastreamento</b></p>
				<p><input type='text' name='codigo-rastreamento' id='codigo-rastreamento' class='formata-campo' style='width:95%' value='<?php echo $codigoRastreamento; ?>'></p>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;height:50px;'><p>&nbsp;</p></div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>Data Solicitação</b></p>
				<p><input type="text" id="data-solicitacao" name="data-solicitacao" value='<?php echo $dataCadastro; ?>' style='width:95%;text-align:center;' readonly/></p>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>Solicitante</b></p>
				<p><input type="text" id="solicitacao-nome" name="solicitacao-nome" value='<?php echo $solicitante; ?>' style='width:95%;' readonly/></p>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>Data Envio</b></p>
				<p><input type="text" id="data-envio" name="data-envio" class='formata-data-meia-hora' value='<?php echo $dataEnvio; ?>' style='width:95%'/></p>
			</div>
			<div class='titulo-secundario' style='width:20%;float:left;'>
				<p><b>Previsão entrega</b></p>
				<p><input type="text" id="data-previsao-entrega" name="data-previsao-entrega" class='formata-data-meia-hora' value='<?php echo $dataPrevisaoEntrega; ?>' style='width:95%'></p>
			</div>
			<div class='titulo-secundario div-data-entrega <?php echo $escondeDataEntrega;?>' style='width:20%;float:left;'>
				<p><b>Data Entrega</b></p>
				<p><input type="text" id="data-entrega" name="data-entrega" class='formata-data-meia-hora' value='<?php echo $dataEntrega; ?>' style='width:95%'/></p>
			</div>
			<div class="titulo-secundario" style="float:left;width:100%; margin-top:5px">
				<div class="titulo-secundario" style="float:left;width:20%;">
					<div class="titulo-secundario" style="float:left;width:99%;">
						<p><b>Origem</b></p>
						<p>
							<select name='origem-envio' id='origem-envio'>
								<option id='0'></option>
								<?php
								if ($modulosAtivos[chamados]){
									echo "<option value='1'"; if ($tabelaEstrangeira=="chamados"){ echo " selected ";} else { $escondeChamado = "esconde"; } echo ">".$_SESSION['objeto']."</option>";
								}
								?>
							</select>
						</p>
					</div>
				</div>
				<?php if ($modulosAtivos[chamados]){ ?>
				<div class='titulo-secundario origem-envio origem-envio-1 <?php echo $escondeChamado;?>' style='width:20%;float:left;'>
					<div style='width:50%;float:left;'>
						<p><b>ID <?php echo $_SESSION['objeto'];?></b></p>
						<p><input type='text' name='envio-id-chamado' id='envio-id-chamado' class='formata-campo' style='width:90%' value='<?php echo $chaveEstrangeira; ?>'></p>
					</div>
					<div style='width:50%;float:left;'>
						<p><b>C&oacute;digo <?php echo $_SESSION['objeto'];?></b></p>
						<p><input type='text' name='envio-codigo-chamado' id='envio-codigo-chamado' class='formata-campo' style='width:90%' value='<?php echo $codigoEstrangeira; ?>'></p>
					</div>
				</div>
				<div name='div-chamado' id='div-chamado' class='div-origem-envio' style="float:left;width:60%;"></div>
				<?php } ?>
			</div>


			<div class='titulo-secundario'>
				<div class="titulo-secundario" style='width:100%;float:left;'>
					<div class='<?php echo $escondeSituacao;?> div-situacao'>
						<p><b>Situa&ccedil;&atilde;o Envio</b></p>
						<select name="select-situacao-follow" id="select-situacao-follow" style='width:99.3%' class='required'><?php echo optionValueGrupo(30, $situacaoAtualID, "Selecione");?><select>
					</div>
				</div>
<!--
				<div id='div-localizar-solicitante-cancelar' style='width:18%;float:left; margin-top:15px'>
					<p><input type='button' value='<?php echo $textoBotao;?>' class='botao-cadastra-envio'  Style='width:95%;'/></p>
				</div>
-->
				<div class="titulo-secundario esconde div-situacao" style='width:100%; float:left;'>
					<p><b><?php echo $textoObservacao; ?></b></p>
					<p><textarea id='descricao-follow' name='descricao-follow' style='height:30px; width:99%'><?php echo $descricaoCompleta; ?></textarea></p>
				</div>
			</div>

		</div>
	</div>

	<div>
		<div class="titulo-container conjunto1">
			<div class="titulo">
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Remetente:</b></span>
					<input type='button' class='editar-cadastro-generico' style="float:right;margin-right:0px; width:50px;" value='Alterar' id='botao-alterar-cadastro-id-de' campo-alvo='cadastro-id-de'>
				</p>
			</div>
			<div class="conteudo-interno">
				<div class='conteudo-interno' id='conteudo-interno-cadastro-id-de'>
					<?php carregarBlocoCadastroGeral($cadastroIDde, 'cadastro-id-de','Remetente',1,'','',$enderecoRemetenteID); ?>
				</div>
			</div>
		</div>
	</div>
	<div>
		<div class="titulo-container conjunto1">
			<div class="titulo">
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Destinat&aacute;rio:</b></span>
					<input type='button' class='editar-cadastro-generico' style="float:right;margin-right:0px; width:50px;" value='Alterar' id='botao-alterar-cadastro-id-para' campo-alvo='cadastro-id-para'>
				</p>
			</div>
			<div class="conteudo-interno">
				<div class='conteudo-interno' id='conteudo-interno-cadastro-id-para'>
					<?php carregarBlocoCadastroGeral($cadastroIDpara, 'cadastro-id-para','Destinat&aacute;rio',1,'','',$enderecoDestinatarioID); ?>
				</div>
			</div>
		</div>
	</div>

	<div class='titulo-container conjunto5 esconde' id='div-financeiro-dados'>
		<?php carregarFinanceiroEnvio($workflowID); ?>
	</div>
	<div>
		<div class="titulo-container conjunto1 conjunto5">
			<div class="titulo">
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Transportadora:</b></span>
					<input type='button' class='editar-cadastro-generico' style="float:right;margin-right:0px; width:50px;" value='Alterar' id='botao-alterar-cadastro-id-trans' campo-alvo='cadastro-id-trans'>
				</p>
			</div>
			<div class="conteudo-interno">
				<div class='conteudo-interno' id='conteudo-interno-cadastro-id-para'>
					<?php carregarBlocoCadastroGeral($transportadoraID, 'cadastro-id-trans','Transportadora',1,'','',''); ?>
				</div>
			</div>
		</div>
	</div>

	<div class='titulo-container conjunto4 esconde' id='div-produtos-dados'>
		<div class="titulo" >
			<p>Produtos / Materiais Enviados
<?php		if (($situacaoAtualID!="53") && ($situacaoAtualID!="59")){
				echo "<input type='button' class='btn-novo-produto' value='Inclur Produto'>";
			}
?>
				<span Style='float:right;font-weight:normal;font-size:12px;' class='<?php echo $escondeCorreio; ?>' id='formas-envio-correio'>
					<span Style='float:left'><b>Forma de envio Correio:</b></span>
					<span Style='float:left'><input type='radio' Style='margin-top:0px' value='41106' name='radio-tipo-envio' class='tipo-envio-correio' <?php echo $envioSeleciona['41106'];?> id='radio-tipo-envio-pac' checked> Pac &nbsp;&nbsp;</span>
					<span Style='float:left'><input type='radio' Style='margin-top:0px' value='40010' name='radio-tipo-envio' class='tipo-envio-correio' <?php echo $envioSeleciona['40010'];?> id='radio-tipo-envio-sedex'> Sedex &nbsp;&nbsp;</span>
					<span Style='float:left'><input type='radio' Style='margin-top:0px' value='40215' name='radio-tipo-envio' class='tipo-envio-correio' <?php echo $envioSeleciona['40215'];?> id='radio-tipo-envio-sedex10'> Sedex 10 &nbsp;&nbsp;</span>
					<span Style='float:left'><input type='radio' Style='margin-top:0px' value='40045' name='radio-tipo-envio' class='tipo-envio-correio' <?php echo $envioSeleciona['40045'];?> id='radio-tipo-envio-sedexcobrar'> Sedex a cobrar &nbsp;&nbsp;</span>
					<input type='hidden' id='tipo-envio-selecionado' value='<?php echo $tipoEnvio;?>'>
				</span>
			</p>
		</div>
		<div class='conteudo-interno titulo-secundario' id='conteudo-interno-produtos'>
			<div id='div-produtos-incluir-editar' Style='float:left;width:100%; margin-bottom:5px;'></div>
			<div id='div-produtos' class="titulo-secundario uma-coluna" style='margin-top:5px;'>
				<?php carregarProdutosWorkflow($workflowID, $enderecoRemetenteID, $enderecoDestinatarioID, $formaEnvioID, $tipoEnvio);?>
			</div>
		</div>
	</div>


	<div class="titulo-container <?php echo $classeEsconde; ?> conjunto1" id='div-historico-dados'>
		<div class="titulo">
			<!--<div class='btn-retrair btn-expandir-retrair-historico' style='float:right;' title='Expandir'></div>-->
			<p>Hist&oacute;rico do envio</p>
		</div>
		<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-historico'>
			<?php geraTabela($largura,$colunas,$dados); ?>
		</div>
	</div>

	<!-- INICIO Bloco Upload usando PLUPLOAD -->
	<div id='div-documentos'></div>
	<div id="container">
		<input type="hidden" id="pickfiles"/>
		<input type="hidden" id="uploadfiles"/>
	</div>
	<!-- FIM Bloco Upload usando PLUPLOAD -->


	<div class="titulo-container conjunto8 conjunto8" >
		<div class="titulo">
			<!--<div class='btn-retrair btn-expandir-retrair-historico' style='float:right;' title='Expandir'></div>-->
			<p>Emiss&atilde;o de Nota Fiscal</p>
		</div>
		<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-historico'>
			<?php emissaoNFe(); ?>
		</div>
	</div>
</div>