<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 'On');
include("functions.php");
global $caminhoFisico, $modulosAtivos;

$contaID = $_POST['localiza-conta-id'];
$tituloID = $_POST['localiza-titulo-id'];
if ($contaID != ""){
	$sql = "select Tipo_ID, Cadastro_ID_de, Cadastro_ID_para, Observacao from financeiro_contas where Conta_ID = $contaID";
	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$tipo = $row[Tipo_ID];
		$empresaID = $cadastroIDde = $row[Cadastro_ID_de];
		$cadastroIDpara = $row[Cadastro_ID_para];
		$observacao = $row[Observacao];
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
	if ($tipo==""){ $tipo = "44";}
}
$cont = verificaNumeroEmpresas();
if ($cont==1){
	$cadastroIDde = retornaCodigoEmpresa();
	$classeEscondeMulti = "esconde ";
	$condicao = " and Tipo_ID IN (44,45) ";
	/*
	$query2 = mpress_query("Select Cadastro_ID from cadastros_dados where Centro_Custo_ID is not null and Centro_Custo_ID <> 0 and Situacao_ID = 1");
	if($rs2 = mpress_fetch_array($query2)){
		$cadastroIDde = $rs2['Cadastro_ID'];
		$classeEscondeMulti = "esconde ";
		$condicao = " and Tipo_ID IN (44,45) ";
	}
}

	$contEmpresas = verificaNumeroEmpresas();
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
*/
}
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
	<div id='div-aux'></div>

	<div class="titulo-container conjunto1" id='div-dados-gerais'>
		<div class="titulo">
			<p>Dados Gerais Conta <?php if ($contaID != "")echo " - ".$contaID;?>
				<?php echo $btGerarNF;?>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario' style='float:left;width:30%;'>
				<p><?php echo montaRadioGrupo(27,$tipo, $condicao);?></p>
				<input type='hidden' name='hidden-tipo-transacao' id='hidden-tipo-transacao' value='<?php echo $tipo;?>'/>
			</div>
			<div class='titulo-secundario' style='float:left;width:70%;'>
				<p>Observa&ccedil;&atilde;o:</p>
				<p><textarea name='observacao' id='observacao' style='width:95%;height:60px'><?php echo $observacao;?></textarea></p>
			</div>
		</div>
	</div>

	<div class='<?php echo $classeEscondeMulti;?>'>
		<div class="titulo-container conjunto1 conjunto6">
			<div class="titulo">
				<p id='texto-cadastro'>
					<span class='contas-a-receber esconde' style='float:left;'><b>Lan&ccedil;amento de conta a receber para Cadastro:&nbsp;&nbsp;</b></span>
					<span class='contas-a-pagar esconde' style='float:left;'><b>Lan&ccedil;amento de conta a pagar para Cadastro:&nbsp;&nbsp;</b></span>
					<span class='transferencias esconde' style='float:left;'><b>Transfer&ecirc;ncia de valores do Cadastro:&nbsp;&nbsp;</b></span>
					<span class='membros esconde' style='float:left;'><b>Lancamento de n&uacute;mero de membros para o Cadastro:&nbsp;&nbsp;</b></span>
					<!--<input type='button' class='editar-cadastro-generico' style="float:right;margin-right:0px; width:50px;" value='Alterar' id='botao-alterar-cadastro-id-de' campo-alvo='cadastro-id-de'>-->
				</p>
			</div>
			<div class="conteudo-interno">
				<div class='conteudo-interno' id='conteudo-interno-cadastro-id-de'>					
					<select id='cadastro-id-de' name='cadastro-id-de' style='width:99.7%'><option value=''>Selecione</option><?php echo optionValueEmpresas($cadastroIDde);?></select>
					<?php
						//carregarBlocoCadastroGeral($cadastroIDde, 'cadastro-id-de','Cadastro',1,'',1); 
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="titulo-container conjunto1">
		<div class="titulo">
			<p id='texto-cadastro'>
				<span class='contas-a-receber esconde' style='float:left;'><b>Cadastro devedor da conta:&nbsp;&nbsp;</b></span>
				<span class='contas-a-pagar esconde' style='float:left;'><b>Cadastro emitente da conta:&nbsp;&nbsp;</b></span>
				<span class='transferencias esconde' style='float:left;'><b>Para o Cadastro:&nbsp;&nbsp;</b></span>
				<input type='button' class='editar-cadastro-generico' style="float:right;margin-right:0px; width:50px;" value='Alterar' id='botao-alterar-cadastro-id-para' campo-alvo='cadastro-id-para'>
			</p>
		</div>
		<div class="conteudo-interno">
			<div class='conteudo-interno' id='conteudo-interno-cadastro-id-para'>
				<?php carregarBlocoCadastroGeral($cadastroIDpara, 'cadastro-id-para','Cadastro',1,''); ?>
			</div>
		</div>
	</div>

<?php
	if ($moduloIgrejaAtivo){
		echo "<input type='hidden' id='modulo-igreja-ativo'>";
		require_once($caminhoFisico."/modulos/igreja/functions.php");
		echo "<div id='div-igreja-membros'>";
		carregarLancamentoMembros($cadastroIDde);
		echo "</div>";
	}
?>
	<div id='div-titulo'>
		<?php carregarTitulos($contaID, $tituloID, $situacaoTitulos, $tipo); ?>
	</div>
	<div class='titulo-container conjunto3 esconde' id='div-lancamentos-mes'></div>

<?php
	if ($modulosAtivos[produtos]){
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
