<?php
include("functions.php");
$sql = "select 	(select Oculta_Menu from modulos_paginas where slug = 'chamados-orcamento-localizar') as orcamentos,
				(select Oculta_Menu from modulos_paginas where slug = 'chamados-localizar-chamado') as chamados,
				(select Descr_Tipo from tipo where Tipo_ID = 106) as dadosGerais";

$resultado = mpress_query($sql);
if($rs = mpress_fetch_array($resultado)){
	if ($rs[orcamentos]=="0") $chkOrcamentos = "checked"; else $visualizaBlocoOrcamentos = "esconde";
	if ($rs[chamados]=="0") $chkChamados= "checked";  else $visualizaBlocoChamados = "esconde";
	$configProcessos = unserialize($rs[dadosGerais]);
	$selOrcamento["".$configProcessos['listagem-orcamento']] = "checked";
	$selChamado["".$configProcessos['listagem-chamado']] = "checked";
	$selFluxoAprovacao["".$configProcessos['fluxo-aprovacao']] = "selected";
	$selAgruparProdutos["".$configProcessos['agrupar-produtos']] = "selected";
}
/*
echo "<pre>";
print_r($configProcessos);
echo "</pre>";
*/

echo "	<div id='div-retorno'></div>
		<div class='titulo-container' id='div-chamado-dados' >
			<div class='titulo'>
				<p>
					Configura&ccedil;&otilde;es Gerais
					<input type='button' value='Salvar' class='botao-salva-configuracoes-gerais'  Style='float:right;margin-right:0px;'/></p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:25%;float:left;'>
					<p><b>Utilizar:</b></p>
					<p style='margin-top:5px;'>
						<input type='checkbox' class='seleciona-exibicao' id='orcamentos' name='orcamentos' value='1' ".$chkOrcamentos."/><label for='orcamentos'>Orçamento</label>
						<input type='checkbox' class='seleciona-exibicao' id='chamados' name='chamados' value='1' ".$chkChamados."/><label for='chamados'>".$_SESSION['objeto']."</label>
					</p>
				</div>
				<div class='titulo-secundario' style='width:25%;float:left;'>
					<p><b>CRM</b></p>
					<p><select name='crm' id='crm'>".optionValueSimNao($configProcessos['crm'], '')."</select></p>
				</div>
			</div>
		</div>";
echo "	<div class='titulo-container bloco-orcamentos ".$visualizaBlocoOrcamentos."'>
			<div class='titulo-secundario' style='width:100%;float:left;'>
				<div class='titulo'>
					<p>Configura&ccedil;&otilde;es Orçamento</p>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div class='titulo-secundario' style='width:100%;float:left;'>
						<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
							<p><b>Modo exibição dos produtos:</b></p>
							<p style='margin-top:5px;'>
								<input type='radio' id='listagem-produtos-orcamento-1' name='listagem-produtos-orcamento' value='procura' ".$selOrcamento['procura']."/><label for='listagem-produtos-orcamento-1'>Listagem de procura
								<input type='radio' id='listagem-produtos-orcamento-2' name='listagem-produtos-orcamento' value='completa' ".$selOrcamento['completa']."/><label for='listagem-produtos-orcamento-2'>Lista completa produtos
							</p>
						</div>
						<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
							<p><b>Campos obrigatórios</b></p>
							<p>
								<select name='campos-obrigatorios-orcamento[]' id='campos-obrigatorios-orcamento' multiple>";
	echo optionValueCamposObrigatoriosOrcamento();
	echo "						</select>
							</p>
						</div>
					</div>
					<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
						<p><b>Grupos Representantes</b></p>
						<p>
							<select name='orcamento-grupos-responsaveis[]' id='orcamento-grupos-responsaveis' multiple>";
	echo optionValueGruposAcessos(unserialize($configProcessos['orcamento-grupos-responsaveis']), "");
	echo "					</select>
						</p>
					</div>

					<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
						<p><b>E-mail em cópia para orçamentos</b></p>
						<p><input type='text' name='emails-copia-orcamento' style='width:98.5%' value='".$configProcessos['emails-copia-orcamento']."'/></p>
					</div>
					<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px'>
						<p><b>Comissionamento</b></p>
						<p><select name='comissionamento-orcamento' id='comissionamento-orcamento'>".optionValueGrupo(56, $configProcessos['comissionamento-orcamento'], 'Não Comissiona')."</select></p>
					</div>
					<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px'>
						<p><b>Fluxo situações de aprovação</b></p>
						<p>
							<select name='fluxo-aprovacao' id='fluxo-aprovacao'>
								<option value='fluxo-aprovacao-simples' ".$selFluxoAprovacao['fluxo-aprovacao-simples'].">Fluxo Simples - Apenas Aprovação do Cliente</option>
								<option value='fluxo-aprovacao-pre' ".$selFluxoAprovacao['fluxo-aprovacao-pre'].">Exige Aprovação Interna antes de enviar para Aprovação do cliente</option>
								<option value='fluxo-aprovacao-pos' ".$selFluxoAprovacao['fluxo-aprovacao-pos'].">Exige Aprovação Interna após Aprovação do Cliente</option>
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px'>
						<p><b>Enviar email em cópia quando Proposta Aprovada ou Finalizada</b></p>
						<p><input type='text' name='emails-copia-orcamento-finalizado' style='width:98.5%' value='".$configProcessos['emails-copia-orcamento-finalizado']."'/></p>
					</div>
					<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px'>
						<p><b>Agrupar produtos</b></p>
						<p>
							<select name='agrupar-produtos' id='agrupar-produtos'>
								<option value='' 					".$selAgruparProdutos[''].">Não Agrupar</option>
								<option value='agrupar-por-cliente' ".$selAgruparProdutos['agrupar-por-cliente'].">Agrupar por Cliente Final</option>
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='width:12.5%;float:left; margin-top:5px'>
						<p><b>Exibir Prestador</b></p>
						<p><select name='exibe-bloco-prestador' id='exibe-bloco-prestador'>".optionValueSimNao($configProcessos['exibe-bloco-prestador'], '')."</select></p>
					</div>
					<div class='titulo-secundario' style='width:12.5%;float:left; margin-top:5px'>
						<p><b>Exibir Frete</b></p>
						<p><select name='exibe-bloco-frete' id='exibe-bloco-frete'>".optionValueSimNao($configProcessos['exibe-bloco-frete'], '')."</select></p>
					</div>
					<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px'>
						<p><b>Exibir Forma de Pagamento?</b></p>
						<p><select name='exibe-bloco-forma-pagamento' id='exibe-bloco-forma-pagamento'>".optionValueSimNao($configProcessos['exibe-bloco-forma-pagamento'], '')."</select></p>
					</div>";
echo "				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px'>
						<p><b>Exibe botão enviar proposta email?</b></p>
						<p><select name='exibe-envia-proposta-email' id='exibe-envia-proposta-email'>".optionValueSimNao($configProcessos['exibe-envia-proposta-email'], '')."</select></p>
					</div>";
echo "				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px'>
						<p><b>Cancelar propostas de um orçamento na aprovação de uma delas?</b></p>
						<p><select name='cancelar-propostas' id='cancelar-propostas'>".optionValueSimNao($configProcessos['cancelar-propostas'], '')."</select></p>
					</div>";
echo "			</div>
			</div>
		</div>";
echo "	<div class='titulo-container bloco-chamados ".$visualizaBlocoChamados."'>
			<div class='titulo'>
				<p><b>Configura&ccedil;&otilde;es ".$_SESSION['objeto']."</b></p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>Modo exibição dos produtos:</b></p>
					<p style='margin-top:5px;'>
						<input type='radio' id='listagem-produtos-chamado-1' name='listagem-produtos-chamado' value='procura' ".$selChamado['procura']."/><label for='listagem-produtos-chamado-1'>Listagem de procura</label>
					</p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>Campos obrigatórios:</b></p>
					<p>
						<select name='campos-obrigatorios-chamado[]' id='campos-obrigatorios-chamado' multiple>";
echo optionValueCamposObrigatoriosChamado();
echo "
						</select>
					</p>
				</div>
			</div>
		</div>";


function optionValueCamposProposta($selecionados){
/*
	$campos = unserialize($selecionados);
	foreach ($campos as $campo){
		$sel[$campo] = " selected ";
	}
	*/
	return "<option value='validade-proposta' 	".$sel['validade-proposta'].">Validade Proposta</option>
			<option value='cobranca-icms' 		".$sel['cobranca-icms'].">Cobrança ICMS</option>
			<option value='cobranca-ipi' 		".$sel['cobranca-ipi'].">Cobrança IPI</option>
			<option value='condicoes-pagamento' ".$sel['condicoes-pagamento'].">Condições de Pagamento</option>
			<option value='data-faturamento' 	".$sel['data-faturamento'].">Data Faturamento</option>
			<option value='garantia' 			".$sel['garantia'].">Garantia</option>
			<!--<option value='valor-frete' 		".$sel['valor-frete'].">Valor frete</option>
			<option value='local-entrega' 		".$sel['local-entrega'].">Local de entrega</option>-->";
}


function optionValueCamposObrigatoriosChamado(){
	$sql = "select Campos_Obrigatorios from modulos_paginas where Slug = 'chamados-cadastro-chamado'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$campos = unserialize($rs['Campos_Obrigatorios']);
	}
	foreach ($campos as $campo){
		$sel[$campo] = " selected ";
	}
	echo "	<option value='codigo-workflow' 		".$sel['codigo-workflow'].">Código ".$_SESSION['objeto']."</option>
			<option value='tipo-workflow' 			".$sel['tipo-workflow'].">Tipo ".$_SESSION['objeto']."</option>
			<option value='select-prioridade' 		".$sel['select-prioridade'].">Prioridade</option>
			<option value='data-limite' 			".$sel['data-limite'].">Data Limite</option>
			<option value='projeto-id-chamado' 		".$sel['projeto-id-chamado'].">Projeto</option>
			<option value='titulo-chamado' 			".$sel['titulo-chamado'].">Título</option>
			<option value='select-grupo-chamado' 	".$sel['select-grupo-chamado'].">Grupo responsável</option>
			<option value='select-usuario-chamado' 	".$sel['select-usuario-chamado'].">Usuário responsável</option>
			<option value='descricao-follow' 		".$sel['descricao-follow'].">Descrição / Observação</option>";



}


function optionValueCamposObrigatoriosOrcamento(){
	$sql = "select Campos_Obrigatorios from modulos_paginas where Slug = 'chamados-orcamento'";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$campos = unserialize($rs['Campos_Obrigatorios']);
	}
	foreach ($campos as $campo){
		$sel[$campo] = " selected ";
	}
	echo "	<option value='codigo-workflow' 		".$sel['codigo-workflow'].">Código Orçamento</option>
			<option value='representante-orcamento' ".$sel['representante-orcamento'].">Representante</option>
			<option value='titulo-orcamento' 		".$sel['titulo-orcamento'].">Título</option>
			<option value='projeto-id-orcamento' 	".$sel['projeto-id-orcamento'].">Projeto</option>
			<option value='descricao-follow' 		".$sel['descricao-follow'].">Descrição / Observação</option>";
}

?>