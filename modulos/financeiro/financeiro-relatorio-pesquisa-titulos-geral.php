<?php
	include('functions.php');
	global $configFinanceiro;
	$localizaGeral = $_POST['localiza-geral'];
	echo "	<input type='hidden' value='".$configFinanceiro['lancamento-fancybox']."' id='lancamento-fancybox'>
			<div class='titulo-container conjunto1'>
				<div class='titulo' style='min-height:25px'>
					<p style='margin-top:2px;'>Filtros de Pesquisa</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left;width:75%' >
						<p><b>Localizar por:</b></p>
						<p><input type='text' name='localiza-geral' id='localiza-geral' class='' style='width:100%' value='".$localizaGeral."'>&nbsp;&nbsp;</p>
					</div>
					<div class='titulo-secundario duas-colunas' Style='margin-top:15px; float:right;width:10%' >
						<p align='right'><input type='button' value='Pesquisar' id='botao-pesquisar-contas' style='width:100px:margin-right:2px'/></p>
					</div>
				</div>
			</div>
			<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>";
?>
