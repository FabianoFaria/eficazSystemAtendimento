<?php
echo "	<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>Arquivo Cooparticipação
						<input type='button' value='Incluir Novo Produto' class='produto-localiza' produto-id='' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<p><b>Arquivo:</b></p>
						<p id='arquivo-participacao'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%;'>
						<p>Conteúdo</p>
						<p><textarea id='conteudo-arquivo' class='conteudo-arquivo' width='100%' height='300'></textarea></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:30%'>
						<p>Descri&ccedil;&atilde;o</p>
						<p><input type='text' id='localiza-descricao-produto' name='localiza-descricao-produto' value='".$descricao."' style='width:98.5%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:25%'>
						<p>Tipo</p>
						<p><select id='localiza-tipo-produto' name='localiza-tipo-produto'>".optionValueGrupo(13, $tipoProdutoID,"&nbsp;")."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:25%;'>
						<p>Situa&ccedil;&atilde;o:</p>
						<p><select name='localiza-situacao-id' id='localiza-situacao-id' class='required'>".optionValueGrupo(1, $situacaoID, '', 'and Tipo_ID IN (1,2)')."</select></p>
					</div>

					<div class='titulo-secundario' style='float:left; width:10%'>
						<div class='titulo-secundario' style='float:left; width:96%'>
							<p>Numero Série</p>
							<p><select id='localiza-numero-serie' name='localiza-numero-serie'>".optionValueSimNao($numeroSerie,'&nbsp;')."</select></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Fornecedor</p>
						<p><input type='text' id='localiza-fornecedor' name='localiza-fornecedor' value='".$fornecedor."' style='width:97%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%'>
						<p>Categoria</p>
						<p><select multiple id='localiza-categorias' name='localiza-categorias[]'>".optionValueCategorias($categorias)."</select></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:25%'>
						<p style='margin:10px 0 0 0;'><input type='checkbox' id='exibir-destaques' name='exibir-destaques' $chkExibirDestaques/><label for='exibir-destaques'>Filtrar Destaques</label></p>
						<p><input type='checkbox' id='exibir-lancamentos' name='exibir-lancamentos' $chkExibirLancamentos/><label for='exibir-lancamentos'>Filtrar Lan&ccedil;amentos</label></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:12.5%'>
						<p style='margin:15px 0 10px 0;'><input type='checkbox' id='exibir-variacoes' name='exibir-variacoes' $chkExibirVariacoes/><label for='exibir-variacoes'>Exibir Variações</label></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:12.5%'>
						<p><input type='button' value='Pesquisar' id='botao-pesquisar-produtos' style='width:90%; margin-top:15px; margin-bottom:5px'/></p>
					</div>
				</div>
			</div>
		</div>";
?>
<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type='text/javascript'>

     google.charts.load('current', {'packages': ['geochart']});
     google.charts.setOnLoadCallback(drawMarkersMap);

      function drawMarkersMap() {
      var data = google.visualization.arrayToDataTable([
        ['Cidade',   'Quantidade', 'Potencia'],
        ['Maringa',   2761477, 1285.31],
        ['Curitiba',  181.76, 181.76],
      ]);

      var options = {
			region: 'BR',
			resolution: 'provinces',
			region: 'BR',
			displayMode: 'markers',
			resolution: 'provinces',
			colorAxis: {colors: ['yellow', 'blue', 'red', 'green']}
      };

      var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
      chart.draw(data, options);
    };
    </script>
    <div id="chart_div" style='width:100%; min-height:600px; float:left;min-width:400px;'></div>
