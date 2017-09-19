<?php
	error_reporting(E_ERROR);
	session_start();
	require_once("../../includes/functions.gerais.php");
	require_once("../../config.php");
	$produtoID = $_GET['produtoID'];
	$qtdVariacoes = $_GET['qtdVariacoes'];
	global $modulosAtivos;
	if (!($modulosAtivos[envios])){
		$classeCD = "esconde";
	}
	$p = 0;
	global $caminhoSistema;
	echo "	<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";

?>
		<input type='hidden' name='produto-nova-variacao[]' value='1'>
		<div Style='width:99%;border:1px solid #bcc4d3;float:left;padding:7px;margin-bottom:3px;'>
			<div class='titulo-secundario duas-colunas' Style='margin-bottom:3px'>
				<p>C&oacute;digo</p>
				<p><input type="text" id="codigo-variacao-<?php echo $p;?>" name="codigo-variacao-<?php echo $p;?>"  value='<?php echo $codigoVariacao; ?>' class='formata-campo' maxlength='250'/></p>
			</div>
			<div class='titulo-secundario duas-colunas' Style='margin-bottom:3px'>
				<p>Imagem</p>
				<p class='omega'>
					<select id='imagem-variacao-<?php echo $p;?>' name='imagem-variacao-<?php echo $p;?>'>
						<option value=''>Sem imagem</option>
	<?php
	$rs = mpress_query("select Imagem_ID, Nome_Imagem from produtos_imagens where produto_ID = $produtoID and Situacao_ID = 1 order by Nome_Imagem");
	while($row = mpress_fetch_array($rs)){
	if($imgVariacao == $row['Imagem_ID']) $selecionado = "selected"; else $selecionado = "";
	echo "				<option value='".$row['Imagem_ID']."' $selecionado>".$row['Nome_Imagem']."</option>";
	}
	?>
					</select>
				</p>
			</div>
			<div class='titulo-secundario duas-colunas'>
				<p>Descri&ccedil;&atilde;o</p>
				<p><input type="text" id="descricao-variacao-<?php echo $p;?>" name="descricao-variacao-<?php echo $p;?>"  value='<?php echo $descricaoVariacao; ?>' class='formata-campo' maxlength='250'/></p>
			</div>
			<div class='titulo-secundario duas-colunas'>
				<p>Forma de Cobran&ccedil;a</p>
				<p class='omega'><select id='forma-cobranca-<?php echo $p;?>' name='forma-cobranca-<?php echo $p;?>' class='forma-cobranca' indice='<?php echo $p;?>'><?php echo optionValueGrupo(20, $formaCobrancaID); ?></select></p>
			</div>
			<div class='forma-cobranca-35-<?php echo $p;?>'>
				<div class="titulo-secundario cinco-colunas">
					<p>Pre&ccedil;o Custo</p>
					<p><input type="text" id="valor-custo-variacao-<?php echo $p;?>" name="valor-custo-variacao-<?php echo $p;?>"  value='<?php echo $valorCusto; ?>' class='formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas">
					<p>Pre&ccedil;o Venda</p>
					<p><input type="text" id="valor-venda-variacao-<?php echo $p;?>" name="valor-venda-variacao-<?php echo $p;?>"  value='<?php echo $valorVenda; ?>' class='valor-venda-variacao formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas">
					<p>Pre&ccedil;o Promo&ccedil;&atilde;o</p>
					<p><input type="text" id="valor-promocao-variacao-<?php echo $p;?>" name="valor-promocao-variacao-<?php echo $p;?>"  value='<?php echo $valorPromocao; ?>' class='formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas">
					<p>Data Inicio Promo&ccedil;&atilde;o</p>
					<p><input type="text" id="data-inicio-promocao-variacao-<?php echo $p;?>" name="data-inicio-promocao-variacao-<?php echo $p;?>"  value='<?php echo $dataInicioPromocao; ?>' class='formata-data' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas">
					<p>Data Fim Promo&ccedil;&atilde;o</p>
					<p class='omega'><input type="text" id="data-fim-promocao-variacao-<?php echo $p;?>" name="data-fim-promocao-variacao-<?php echo $p;?>"  value='<?php echo $dataFimPromocao; ?>' class='formata-data' maxlength='10'/></p>
				</div>
			</div>
			<div class='<?php echo $classeCD;?>'>
				<div class="titulo-secundario cinco-colunas dados-produto">
					<p>Altura (cm)</p>
					<p><input type="text" id="altura-variacao-<?php echo $p;?>" name="altura-variacao-<?php echo $p;?>"  value='<?php echo $alturaVariacao; ?>'  class='formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas dados-produto">
					<p>Largura (cm)</p>
					<p><input type="text" id="largura-variacao-<?php echo $p;?>" name="largura-variacao-<?php echo $p;?>"  value='<?php echo $larguraVariacao; ?>'  class='formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas dados-produto">
					<p>Comprimento (cm)</p>
					<p><input type="text" id="comprimento-variacao-<?php echo $p;?>" name="comprimento-variacao-<?php echo $p;?>"  value='<?php echo $comprimentoVariacao; ?>'  class='formata-valor' maxlength='10'/></p>
				</div>
				<div class="titulo-secundario cinco-colunas dados-produto">
					<p>Peso (kilos)</p>
					<p><input type="text" id="peso-variacao-<?php echo $p;?>" name="peso-variacao-<?php echo $p;?>"  value='<?php echo $pesoVariacao; ?>'  class='formata-valor-decimal-3' maxlength='10'/></p>
				</div>
			</div>
			<div class="titulo-secundario dados-produto" Style='float:right'>
				<p>&nbsp;</p>
<?php	if ($qtdVariacoes>0){	?>
				<input type='button' value='excluir varia&ccedil;&atilde;o' id='botao-exclui-produto-variacao' class='botao-cadastra-produto' Style='font-size:10px;height:25px;margin-top:5px;' attr-id='' />
<?php	}	?>
			</div>
		</div>




