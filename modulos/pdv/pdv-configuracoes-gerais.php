<?phpinclude("functions.php");global $configPDV;//echo "<pre>";//print_r($configPDV);//echo "</pre>";echo "	<div id='div-retorno'></div>		<div class='titulo-container' id='div-laboratorio-dados' >			<div class='titulo'>				<p>					Configura&ccedil;&otilde;es Gerais PDV					<input type='button' value='Salvar' class='botao-salvar-configuracoes-gerais'  Style='float:right;margin-right:0px;'/>				</p>			</div>			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px;'>
					<p>Quantidade de Caixas Disponíveis no PDV:</p>
					<p><input type='text' name='quantidade-caixas' id='quantidade-caixas' style='width:98.5%;text-align:center;' class='formata-numero' value='".$configPDV['quantidade-caixas']."'/></p>
				</div>
				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px;'>
					<p>Código para Cancelamento de Venda:</p>
					<p><input type='text' name='codigo-cancelamento-venda' id='codigo-cancelamento-venda' style='width:98.5%;text-align:center;' value='".$configPDV['codigo-cancelamento-venda']."'/></p>
				</div>
				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px;'>
					<p>Formas de Pagamento:</p>
					<p><select name='formas-pagamento-pdv[]' id='formas-pagamento-pdv' multiple>".optionValueGrupoMultiplo(25, serialize($configPDV['formas-pagamento-pdv']), "")."</select></p>
				</div>
				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px;'>
					<p>Condições de Pagamento <i>(Quantidade Parcelas)</i>:</p>
					<p><input type='text' name='quantidade-parcelas-pdv' id='quantidade-parcelas-pdv' value='".$configPDV['quantidade-parcelas-pdv']."' style='width:98.5%;text-align:center;'></p>
				</div>
				<!--
				<div class='titulo-secundario' style='width:25%;float:left; margin-top:5px;'>
					<p>Código para cancelamento de ITEM na compra:</p>
					<p><input type='text' name='codigo-cancelamento-item' id='codigo-cancelamento-item' style='width:98.5%' value='".$configPDV['codigo-cancelamento-item']."'/></p>
				</div>
				-->
			</div>		</div>";