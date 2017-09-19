<?php
/*
echo "	<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>Filtros de Pesquisa
					<input type='button' value='Incluir Novo Produto' class='produto-localiza' produto-id='' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left; width:10%;'>
						<p>ID Produto</p>
						<p><input type='text' name='localiza-produto-id' id='localiza-produto-id' class='formata-numero' value='".$id."' style='width:93%;'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:10%;'>
						<p>Código</p>
						<p><input type='text' name='localiza-produto-codigo' id='localiza-produto-codigo' value='".$codigo."' style='width:93%;'></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:30%'>
						<p>Descri&ccedil;&atilde;o</p>
						<p><input type='text' id='localiza-descricao-produto' name='localiza-descricao-produto' value='".$descricao."' style='width:98.5%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:25%'>
						<p>Tipo</p>
						<p><select id='localiza-tipo-produto' name='localiza-tipo-produto'>".optionValueGrupo(13, $tipoProdutoID,"&nbsp;")."</select></p>
					</div>
					<!--
					<div class='titulo-secundario' style='float:left; width:25%'>
						<p>Forma Pagamento</p>
						<p><select id='localiza-forma-cobranca' name='localiza-forma-cobranca'>".optionValueGrupo(20, $formaCobranca,"&nbsp;")."</select></p>
					</div>
					-->
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
*/
?>
<script type='text/javascript' src='https://www.gstatic.com/charts/loader.js'></script>
<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type='text/javascript'>
function initMap() {
  var myLatLng = {lat: -25.363, lng: 131.044};
  // Create a map object and specify the DOM element for display.
  var map = new google.maps.Map(document.getElementById('map'), {
    center: myLatLng,
    scrollwheel: false,
    zoom: 4
  });

  // Create a marker and set its position.
  var marker = new google.maps.Marker({
    map: map,
    position: myLatLng,
    title: 'Hello World!'
  });
}
initMap();
</script>
    <div id="map" style='width:100%; min-height:600px; min-width:400px;'></div>
