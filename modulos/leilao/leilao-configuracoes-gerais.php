<?php
include("functions.php");
//$dados = carregarConfiguracoesGeraisModulos('leilao');
$dados = $configLeilao;
$contEmpresas = verificaNumeroEmpresas();
$sel['tipo-leilao'][$dados['tipo-leilao']] = "checked";
$sel['tipo-fechamanto'][$dados['tipo-fechamanto']] = "checked";

/*
echo "<pre>";
print_r($dados);
echo "</pre>";
*/

echo "	<div id='div-retorno'></div>
		<div class='titulo-container' id='div-chamado-dados' >
			<div class='titulo'>
				<p>
					Configura&ccedil;&otilde;es Gerais Leilão
					<input type='button' value='Salvar' class='botao-salva-configuracoes-gerais'  Style='float:right;margin-right:0px;'/></p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p>Leilão:</p>
					<p style='margin-top:5px;'>
						<input type='radio' class='tipo-leilao' id='tipo-leilao-pos' name='tipo-leilao' value='pos' ".$sel['tipo-leilao']['pos']."/><label for='tipo-leilao-pos'>Pós-pago (Modelo Tradicional)</label>
						<input type='radio' class='tipo-leilao' id='tipo-leilao-pre' name='tipo-leilao' value='pre' ".$sel['tipo-leilao']['pre']."/><label for='tipo-leilao-pre'>Pré-pago (Modelo Compra de Créditos)</label>
					</p>
				</div>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p>Fechamento:</p>
					<p style='margin-top:5px;'>
						<input type='radio' class='tipo-fechamanto' id='tipo-fechamanto-auto' name='tipo-fechamanto' value='auto' ".$sel['tipo-fechamanto']['auto']."/><label for='tipo-fechamanto-auto'>Automático</label>
						<input type='radio' class='tipo-fechamanto' id='tipo-fechamanto-manual' name='tipo-fechamanto' value='manual' ".$sel['tipo-fechamanto']['manual']."/><label for='tipo-fechamanto-manual'>Manual</label>
					</p>
				</div>
			</div>
		</div>";		
?>