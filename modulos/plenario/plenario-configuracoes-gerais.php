<?php
require_once("functions.php");
//global $configPlenario;
//print_r($configPlenario);
echo "<div id='container-geral'>
		<div id='div-retorno'></div>
		<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Configurações Gerais
					<input type='button' value='Salvar' class='salvar-configuracoes-plenario'>
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario' style='float:left; width:25%'>
					<p>Quantidade de Canais</p>
					<p><select name='quantidade-canais' id='quantidade-canais' class='quantidade-canais' style='width:90%'>".optionValueCountSelect(32, $configPlenario['quantidade-canais'], 0)."</select></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:25%'>
					<p>Posição Driver MIDI <i>(Device)</i></p>
					<p><select name='device-midi' id='device-midi' class='device-midi' style='width:90%'>".optionValueCountSelect(32, $configPlenario['device-midi'], 0)."</select></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:25%'>
					<p>Channel <i>(Sempre 0)</i></p>
					<p><input type='text' name='channel-midi' style='width:98%' value='".$configPlenario['channel-midi']."'></p>
				</div>
				<div class='titulo-secundario' style='float:left; width:25%'>
					<p>Host</p>
					<p><input type='text' name='host' id='host' class='host' value='".$configPlenario['host']."' style='width:95%'/></p>
				</div>
			</div>
		</div>";
echo "	<div class='titulo-container' style='width:100%; float:left;'>
			<div class='titulo'>
				<p>Organização Padrão Plenário</p>
			</div>
			<div class='conteudo-interno bloco-organizacao-plenario'>";
carregarOrganizacaoPlenario($configPlenario['quantidade-canais'],'completo');
echo "		</div>
		</div>";

echo "	<div class='titulo-container' style='width:100%; float:right;'>
			<div class='titulo'>
				<p>Tribuna</p>
			</div>
			<div class='conteudo-interno bloco-organizacao-tribuna'>";
carregarOrganizacaoTribuna($configPlenario['quantidade-canais'],'completo');
echo "		</div>
		</div>";
echo "</div>";



