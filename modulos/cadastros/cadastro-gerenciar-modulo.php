<?php
require_once("functions.php");
//global $configPlenario;
//print_r($configCadastros);

echo "<div id='container-geral'>
		<div id='div-retorno'></div>
		<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Configurações Gerais
					<input type='button' value='Salvar' class='botao-salvar-configuracoes-gerais'>
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario' style='width:20%;float:left; margin-top:5px;'>
					<p>Exibir vinculos?</p>
					<p style='margin-top:5px;'>
						<select name='exibir-vinculos' id='exibir-vinculos'>".optionValueSimNao($configCadastros['exibir-vinculos'])."</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:20%;float:left; margin-top:5px;'>
					<p>Exibir regionais?</p>
					<p style='margin-top:5px;'>
						<select name='exibir-regionais' id='exibir-regionais'>".optionValueSimNao($configCadastros['exibir-regionais'])."</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:20%;float:left; margin-top:5px;'>
					<p>Exibir tabelas de preço?</p>
					<p style='margin-top:5px;'>
						<select name='exibir-produto-tabelas-precos' id='exibir-produto-tabelas-precos'>".optionValueSimNao($configCadastros['exibir-produto-tabelas-precos'])."</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:20%;float:left; margin-top:5px;'>
					<p>Classifica Clientes?</p>
					<p style='margin-top:5px;'>
						<select name='classifica-clientes' id='classifica-clientes'>".optionValueSimNao($configCadastros['classifica-clientes'])."</select>
					</p>
				</div>
			</div>
		</div>";
