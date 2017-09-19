<?php
require_once("functions.php");
global $modulosGeral;
//global $configPlenario;
//print_r($configFinanceiro);
echo "<div id='container-geral'>
		<div id='div-retorno'></div>
		<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Configurações Financeiro
					<input type='button' value='Salvar' class='botao-salvar-configuracoes-gerais'  Style='float:right;margin-right:0px;'/>
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Titulo para os cadastros </b><i>(ex: Empresa, Igreja)</i></p>
					<p><input type='text' name='cadastro' id='cadastro' class='required' style='width:98.5%' value='".$configFinanceiro['cadastro']."'/></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Trabalha com Conta?</b><i>(ex: Bancárias, Crédito, Caixas)</i></p>
					<p><select id='exibe-conta' name='exibe-conta'>".optionValueSimNao($configFinanceiro['exibe-conta'])."</select></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Trabalha com Centros de Custo?</b></p>
					<p><select id='exibe-centro-custo' name='exibe-centro-custo'>".optionValueSimNao($configFinanceiro['exibe-centro-custo'])."</select></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Agrupar Lançamento:</b><i>(CTA CTR e T em única tela)</i></p>
					<p><select id='agrupar-lancamento' name='agrupar-lancamento'>".optionValueSimNao($configFinanceiro['agrupar-lancamento'])."</select></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Lançamento Fancybox?</b></p>
					<p><select id='lancamento-fancybox' name='lancamento-fancybox'>".optionValueSimNao($configFinanceiro['lancamento-fancybox'])."</select></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Exibir dados da ".$configFinanceiro['cadastro']." no lan&ccedil;amento</b></p>
					<p><select id='lancamento-exibir-dados-empresa' name='lancamento-exibir-dados-empresa'>".optionValueSimNao($configFinanceiro['lancamento-exibir-dados-empresa'])."</select></p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left; margin-top:5px;'>
					<p><b>Em lançamento exibir botao Salvar e Continuar?</b></p>
					<p><select id='lancamento-exibir-botao-continuar' name='lancamento-exibir-botao-continuar'>".optionValueSimNao($configFinanceiro['lancamento-exibir-botao-continuar'])."</select></p>
				</div>
			</div>
		</div>
	</div>";
if($modulosGeral['nf']){

}