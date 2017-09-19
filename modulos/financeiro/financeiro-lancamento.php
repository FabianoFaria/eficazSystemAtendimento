<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
include("functions.php");
global $caminhoFisico, $modulosAtivos, $modulosGeral, $configFinanceiro;

$contaID = $_POST['localiza-conta-id'];
if ($contaID=="")
	$contaID = $_GET['localiza-conta-id'];

if ($contaID != ""){
	$sql = "select Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Cadastro_Conta_ID_de, Cadastro_Conta_ID_para, Tipo_Conta_ID, Observacao from financeiro_contas where Conta_ID = $contaID";
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$tipo = $row[Tipo_ID];
		$empresaID = $cadastroIDde = $row[Cadastro_ID_de];
		$cadastroIDpara = $row[Cadastro_ID_para];
		$observacao = $row[Observacao];
		$tipoContaID = $row[Tipo_Conta_ID];
		$cadastroContaIDde = $row[Cadastro_Conta_ID_de];
		$cadastroContaIDpara = $row[Cadastro_Conta_ID_para];
	}
	$sql = "select Situacao_Pagamento_ID from financeiro_titulos ft where Conta_ID = '$contaID'";
	$resultado = mpress_query($sql);
	if ($rs = mpress_fetch_array($resultado)){
		if ($rs[Situacao_Pagamento_ID]=="-1"){
			$situacaoTitulos = "pendente";
			$tituloID = "";
		}
	}
}
else{
	$situacaoTitulos = "pendente";
	$tipo = $_POST['localiza-tipo'];
	if ($tipo==""){
		$tipo = "44";
	}
	$cadastroContaIDde = $_GET['filtro-cadastro-conta'];
	if ($cadastroContaIDde!=""){
		$sql = "select Cadastro_ID from cadastros_contas where Cadastro_Conta_ID = '$cadastroContaIDde'";
		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$cadastroIDde = $rs['Cadastro_ID'];
		}
	}
}

$cont = verificaNumeroEmpresas();
if ($cont==1){
	$cadastroIDde = retornaCodigoEmpresa();
	$condicao = " and Tipo_ID IN (44,45) ";
}

$tamanho = "100%";
if ($configFinanceiro['exibe-conta']){
	$tamanho = "50%";
	$empresasContasCadastradas .= "	<div style='float:left;width:50%;' class='$escondeEmpresaConta'>
										<p><b>Conta</b></p>
										<p><select id='cadastro-conta-id-de' name='cadastro-conta-id-de' style='width:99.7%' class='required' readonly><option value=''>Selecione</option>".optionValueContas($cadastroContaIDde)."</select></p>
									</div>";
	$empresasContasCadastradasTransferencia .= "	<div style='float:left;width:50%;' class='$escondeEmpresaConta'>
														<p><b>Para Conta</b></p>
														<p><select id='cadastro-conta-id-para-transf' name='cadastro-conta-id-para-transf' style='width:99.7%' class='' readonly><option value=''>Selecione</option>".optionValueContas($cadastroContaIDpara)."</select></p>
													</div>";
}
$empresasContasCadastradas .= "	<div style='float:left;width:$tamanho;' class='$escondeEmpresaConta'>
									<p><b id='configFinanceiroCadastro'>".$configFinanceiro['cadastro']."</b></p>
									<p>
										<select id='cadastro-id-de' name='cadastro-id-de' style='width:99.7%' class='required'>
											<option value=''>Selecione</option>
											".optionValueEmpresas($cadastroIDde)."
										</select>
										<input type='hidden' id='lancamento-exibir-dados-empresa' value='".$configFinanceiro['lancamento-exibir-dados-empresa']."'/>
									</p>";
if (($configFinanceiro['lancamento-exibir-dados-empresa']=='1')){
	$empresasContasCadastradas .= "	<div id='dados-cadastro-de' style='width:99.5%; margin-bottom:13px;' >";
	if ($cadastroIDde!=''){
		$empresasContasCadastradas .= carregarCadastroGeral($cadastroIDde, 'campo-auxiliar',$configFinanceiro['cadastro'],'');
	}
	$empresasContasCadastradas .= "	</div>";
}
$empresasContasCadastradas .= "	</div>";
$empresasContasCadastradasTransferencia .= "	<div style='float:left;width:$tamanho;' class='$escondeEmpresaConta'>
													<p><b>".$configFinanceiro['cadastro']."</b></p>
													<p>
														<select id='cadastro-id-para-transf' name='cadastro-id-para-transf' style='width:99.7%' class=''>
															<option value=''>Selecione</option>
															".optionValueEmpresas($cadastroIDpara)."
														</select>
													</p>
												</div>";

// FATURAMENTO DIRETO

if ($_GET['tipo']=='direto'){
	echo "	<style>
				#topo-container{display:none;}
				#menu-container{display:none;}
			</style>";
	$lancamentoFancybox = "1";
	if ($_GET['modulo']=='chamados'){
		// ENTRADA
		if (is_array($_GET['check-fat-receber'])){
			$tipo = "45";
			$cadastroIDde = $_GET['cadastro-id'];
			$cadastroIDpara = $_GET['solicitante-id'];
		}
		// SAIDA
		if (is_array($_GET['check-fat-pagar'])){
			$tipo = "44";
			$cadastroIDde = $_GET['cadastro-id'];
			$cadastroIDpara = $_GET['prestador-id'];
		}
		$faturamentoDireto = "S";
	}
	if ($_GET['modulo']=='orcamentos'){
		// ENTRADA e SAIDA
		$tipo = $_GET['tipo-id'];
		$cadastroIDde = $_GET['empresa-id'];
		$cadastroIDpara = $_GET['cadastro-id'];
		$faturamentoDireto = "S";
	}
}

/*
if (($_GET['slug-pagina']=='financeiro-contas-receber') || (($contaID!='') && ($tipo=='45'))){
	$tipo = "45";
	$condicaoTipoConta = " and Tipo_ID = '45'";
	$escondeTipoTransacao = " esconde ";
	//$tituloConta = "<font style='color:#003A90;'>A RECEBER</font>";
	$tituloConta = "<font style='color:#0047c9;'>A RECEBER</font>";
}
if (($_GET['slug-pagina']=='financeiro-contas-pagar') || (($contaID!='') && ($tipo=='44'))){
	$tipo = "44";
	$condicaoTipoConta = " and Tipo_ID = '44'";
	$escondeTipoTransacao = " esconde ";
	$tituloConta = "<font style='color:#FF4D4D;'>A PAGAR</font>";
}
if (($_GET['slug-pagina']=='financeiro-contas-transferencias') || (($contaID!='') && ($tipo=='46'))){
	$tipo = "46";
	$condicaoTipoConta = " and Tipo_ID = '46'";
	$escondeTipoTransacao = " esconde ";
	$tituloConta = "<font style='color:#4F4F4F;'>TRANSFER&Ecirc;NCIA</font>";
}
*/

?>
<div id="container-geral">
	<div id='div-retorno'></div>
	<input type="hidden" id="texto-geral" name="texto-geral" value=""/>
	<input type="hidden" id="tipo-cadastro" name="tipo-cadastro" value=""/>
	<input type="hidden" id="localiza-titulo-id" name="localiza-titulo-id" value="<?php echo $tituloID;?>"/>
	<input type="hidden" id="localiza-conta-id" name="localiza-conta-id" value="<?php echo $contaID;?>"/>
	<input type="hidden" id="conta-id" name="conta-id" value="<?php echo $contaID;?>"/>
	<input type="hidden" id="numero-empresas" name="numero-empresas" value="<?php echo $cont;?>"/>
	<input type='hidden' id="situacao-titulos" name='situacao-titulos' value="<?php echo $situacaoTitulos;?>"/>

	<input type="hidden" id="aux-forma-pagamento" name="aux-forma-pagamento" value=""/>
	<input type="hidden" id="aux-data-vencimento" name="aux-data-vencimento" value=""/>
	<input type="hidden" id="aux-data-pago" name="aux-data-pago" value=""/>
	<input type="hidden" id="aux-situacao-vencimento" name="aux-situacao-vencimento" value=""/>
	<input type="hidden" id="workflow-id" name="workflow-id" value=""/>
	<input type="hidden" id="ordem-compra-id" name="ordem-compra-id" value=""/>
	<input type="hidden" id="cadastroID" name="cadastroID" value=""/>
	<input type="hidden" id="tipo-conta-id" name="tipo-conta-id" value="<?php echo $tipo;?>"/>
	<input type="hidden" id='faturamento-direto'  name='faturamento-direto' value="<?php echo $faturamentoDireto;?>"/>
	<input type="hidden" id='lancamento-fancybox'  name='lancamento-fancybox' value="<?php echo $lancamentoFancybox;?>"/>
	<input type="hidden" id='lancamento-salvo'  name='lancamento-salvo' value="<?php echo $_POST['lancamento-salvo'];?>"/>
	
	<div id='div-aux'></div>

	<div class="titulo-container conjunto1" id='div-dados-gerais'>
		<div class="titulo">
			<p>Conta
<?php
				if ($contaID != "")
					echo " - ".$contaID." ".$tituloConta;
				echo $btGerarNF;
?>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario' style='float:left;width:100%;margin-top:5px;margin-bottom:15px;'>
				<p class='<?php echo $escondeTipoTransacao;?>'><b><?php echo montaRadioGrupo(27, $tipo, $condicaoTipoConta);?></b></p>
				<p><br>Entrada = Nota Fiscal de Sa&iacute;da <br> Sa&iacute;da = Nota Fiscal de Entrada</p>
				<input type='hidden' name='hidden-tipo-transacao' id='hidden-tipo-transacao' value='<?php echo $tipo;?>'/>
			</div>
			<div class='titulo-secundario' style='float:left;width:100%;margin-top:5px;'>
				<?php echo $empresasContasCadastradas;?>
			</div>
			<div id='bloco-contabil' class='bloco-contabil-lancamento'>
				<?php echo carregarBlocoTipoContaCentroCusto($tipo, $contaID); ?>
			</div>
			<div class='titulo-secundario bloco-contabil-lancamento' style='float:right;width:100%;margin-top:5px;'>
				<p align='center'><input type='button' id='dividir-valores' value='Dividir Valor' style='height:20px; font-size:10px;' /></p>
			</div>
			<div class='titulo-secundario' id='conteudo-interno-cadastro-id-para' style='float:right;width:100%;margin-top:5px;'>
				<p>
					<b>
						<span class='contas-a-receber esconde' style='float:left;'>Pagador</span>
						<span class='contas-a-pagar esconde' style='float:left;'>Favorecido</span>
					</b>
				</p>
				<input type='button' class='editar-cadastro-generico' style="width:50px; height:15px; font-size:9px;" value='Alterar' id='botao-alterar-cadastro-id-para' campo-alvo='cadastro-id-para'></b></p>
				<p><?php carregarBlocoCadastroGeral($cadastroIDpara, 'cadastro-id-para','Cadastro',1,''); ?></p>
			</div>
			<div class='titulo-secundario transferencias' style='float:left;width:100%;margin-top:5px;'>
				<?php echo $empresasContasCadastradasTransferencia;?>
			</div>
			<div class='titulo-secundario' style='float:left;width:100%;margin-top:5px;'>
				<p><b>Observa&ccedil;&otilde;es </b><i>(Hist&oacute;rico)</i>:</p>
				<p><textarea name='observacao' id='observacao' style='width:99.2%;height:40px'><?php echo $observacao;?></textarea></p>
			</div>
		</div>
	</div>
<?php

	if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
		require_once($caminhoFisico."/modulos/igreja/functions.php");
		echo "	<input type='hidden' id='modulo-igreja-ativo'>
			 	<div id='div-igreja-membros'>";
		carregarLancamentoMembros($cadastroIDde);
		echo "	</div>";
	}
?>
	<div id='div-titulo'>
		<?php carregarTitulos($contaID, $tituloID, $situacaoTitulos, $tipo); ?>
	</div>
	<div class='titulo-container conjunto3 esconde' id='div-lancamentos-mes'></div>

<?php
	if ($modulosAtivos['produtos']){
		echo "<div id='div-produtos' class='titulo-container conjunto2 esconde'>";
		carregarProdutosConta($contaID);
		echo "</div>";
	}
?>
	<!-- INICIO Bloco Upload usando PLUPLOAD -->
	<div id='div-documentos'></div>
	<div id="container">
		<input type="hidden" id="pickfiles"/>
		<input type="hidden" id="uploadfiles"/>
	</div>
	<!-- FIM Bloco Upload usando PLUPLOAD -->

	<!-- INICIO NF -->
	<div id="dados-emissao-nfe" class='conjunto5 esconde'>
		<?php carregarEmissaoNF($contaID);?>
	</div>
</div>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>

