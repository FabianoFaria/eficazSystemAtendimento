<?php	require_once("functions.php");	if ($_GET['tipo-fluxo']=='direto'){		echo "	<style>					#topo-container{display:none;}					#menu-container{display:none;}				</style>";	}		$tipoProduto = 30;	$formaCobrancaID = 35;	$produtoID = $_POST["produto-id"];	if ($produtoID=='') $produtoID = $_GET["produto-id"];	if ($produtoID!=""){		$sql = "Select Nome, Descricao_Resumida, Slug, Codigo, Categorias, Descricao_Completa, Tipo_Produto, Marca, Destaque, Lancamento, Faturamento_Direto, Data_Cadastro, Industrializado, NCM, Formulario_ID,					Origem, Numero_Serie, Situacao_ID					from produtos_dados where Produto_ID = $produtoID";		//echo $sql;		$query = mpress_query($sql);		if($produto = mpress_fetch_array($query)){			$nome 			   = $produto['Nome'];			$descricaoResumida = $produto['Descricao_Resumida'];			$descricaoCompleta = $produto['Descricao_Completa'];			$codigo 			= $produto['Codigo'];			$tipoProduto 		= $produto['Tipo_Produto'];			$marcaID 			= $produto['Marca'];			$NCM 				= $produto['NCM'];			$origem				= $produto['Origem'];			$industrializado	= $produto['Industrializado'];			$numeroSerie		= $produto['Numero_Serie'];			$destaque			= $produto['Destaque'];			$lancamento			= $produto['Lancamento'];			$slug				= $produto['Slug'];			$situacaoID			= $produto['Situacao_ID'];			$formularioID 		= $produto['Formulario_ID'];			$faturamentoDireto	= $produto['Faturamento_Direto'];			$query2 = mpress_query("Select Categoria_ID from produtos_dados_categorias where Produto_ID = '$produtoID' and Situacao_ID = 1");			while($rs = mpress_fetch_array($query2)){				$categorias[] = $rs[Categoria_ID];			}		}	}	else{ 		$formaCobrancaID = "35";		$situacaoID		 = "1";	}	global $modulosAtivos;	if (!($modulosAtivos[envios])){		$classeCD = "esconde";	}?>	<div id='div-retorno'></div>	<input type='hidden' id='cadastroID' name='cadastroID' value=''/>	<input type='hidden' id='produtoID' name='produto-id' value='<?php echo $produtoID;?>'/>	<div id='chamados-container'>		<div class='titulo-container grupo1' id='div-dados-gerais'>			<div class='titulo'>				<p> Dados Gerais <input type='button' value='Salvar Produto'  id='botao-cadastra-produto' class='botao-cadastra-produto' origem='geral' style='width:100px'/></p>			</div>			<div class='conteudo-interno'>				<div class='titulo-secundario' style='float:left; width:7.5%;'>					<p>ID</p>					<p><input type='text' id='produto-id' name='produto-id' value='<?php echo $produtoID; ?>' style='width:90%' readonly /></p>				</div>				<div class='titulo-secundario' style='float:left; width:7.5%;'>					<p>C�digo</p>					<p><input type='text' id='produto-codigo' name='produto-codigo' value='<?php echo $codigo; ?>' style='width:90%'/></p>				</div>				<div class='titulo-secundario' style='float:left; width:35%;'>					<p>Nome</p>					<p><input type='text' id='produto-nome' name='produto-nome' value='<?php echo $nome; ?>' style='width:97.5%' maxlength='2000'/></p>				</div>				<div class='titulo-secundario' style='float:left; width:25%;'>					<p>Tipo</p>					<p style='width:97%'>						<select class="select-tipo-grupo-13" name="select-tipo-grupo-13" id="select-tipo-grupo-13" >							<?php echo optionValueGrupo(13, $tipoProduto);  ?>						<select>					</p>				</div>				<div class='titulo-secundario' style='float:left; width:25%;'>					<p>Situa&ccedil;&atilde;o:</p>					<p><select name='situacao-id' id='situacao-id' class='required'><?php echo optionValueGrupo(1, $situacaoID, '', 'and Tipo_ID IN (1,2)');?></select></p>				</div>				<div class='titulo-secundario' style='float:left; width:75%'>					<p>Descri��o Resumida</p>					<p><input type='text' id='descricao-resumida' name='descricao-resumida' style='width:98%' value='<?php echo $descricaoResumida; ?>'/></p>				</div>				<div class='titulo-secundario' style='float:left; width:25%'>					<p>Slug do produto</p>					<p><input type='text' id='slug' name='slug' style='width:98%' value='<?php echo $slug; ?>'/></p>				</div>				<div class='titulo-secundario uma-coluna' style='margin-top:5px;'>					<div class='titulo-secundario' Style='width:74%;float:left;'>						<p>Descri&ccedil;&atilde;o Completa</p>						<p Style='margin-top:3px;'><textarea id='descricao-completa' name='descricao-completa' style='height:250px;'><?php echo $descricaoCompleta; ?></textarea></p>						<?php tinyMCE('descricao-completa','');?>					</div>					<div class='titulo-secundario' Style='width:1%;float:left;'>&nbsp;</div>					<div class='titulo-secundario' Style='width:25%;float:right;height:250px;'>						<p>Categorias</p>						<p Style='margin-top:3px;'>							<div Style='width:99%;height:350px;overflow:auto;border: 1px solid #dddddd;background-color: #f6f6f6;'><?php		$query = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome");		while($categoria1 = mpress_fetch_array($query)){			if(strlen(array_search($categoria1[Categoria_ID],$categorias))>=1){$selecionado = " checked ";}else{$selecionado = "";}			echo "<input type='checkbox' name='chkCategoria[]' $selecionado value='".$categoria1[Categoria_ID]."'>&nbsp;".$categoria1[Nome]."<br>";			$query2 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria1[Categoria_ID]."' order by nome");			while($categoria2 = mpress_fetch_array($query2)){				if(strlen(array_search($categoria2[Categoria_ID],$categorias))>=1){$selecionado = " checked ";}else{$selecionado = "";}				echo "&nbsp;&nbsp;&nbsp;"."<input type='checkbox' name='chkCategoria[]' $selecionado  value='".$categoria2[Categoria_ID]."'>&nbsp;".$categoria2[Nome]."<br>";				$query3 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria2[Categoria_ID]."' order by nome");				while($categoria3 = mpress_fetch_array($query3)){					if(strlen(array_search($categoria3[Categoria_ID],$categorias))>=1){$selecionado = " checked ";}else{$selecionado = "";}					echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<input type='checkbox' name='chkCategoria[]' $selecionado value='".$categoria3[Categoria_ID]."'>&nbsp;".$categoria3[Nome]."<br>";					$query4 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria3[Categoria_ID]."' order by nome");					while($categoria4 = mpress_fetch_array($query4)){						if(strlen(array_search($categoria4[Categoria_ID],$categorias))>=1){$selecionado = " checked ";}else{$selecionado = "";}						echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<input type='checkbox' name='chkCategoria[]' $selecionado value='".$categoria4[Categoria_ID]."'>&nbsp;".$categoria4[Nome]."<br>";						$query5 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria4[Categoria_ID]."' order by nome");						while($categoria5 = mpress_fetch_array($query5)){							if(strlen(array_search($categoria5[Categoria_ID],$categorias))>=1){$selecionado = " checked ";}else{$selecionado = "";}							echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"."<input type='checkbox' name='chkCategoria[]' $selecionado value='".$categoria5[Categoria_ID]."'>&nbsp;".$categoria5[Nome]."<br>";						}					}				}			}		}?>							</div>						</p>					</div>				</div>				<div class='titulo-secundario uma-coluna'>					<div class='titulo-secundario' style='float:left; width:50%;'>						<div class='titulo-secundario dados-produto' style='float:left; width:50%; margin-top:3px;'>							<p>Marca</p>							<p><select id='marca-produto' name='marca-produto'><?php echo optionValueGrupo(14, $marcaID); ?></select></p>						</div>						<div class='titulo-secundario dados-produto' style='float:left; width:50%; margin-top:3px;'>							<p>Origem</p>							<p>								<select id='origem-produto' name='origem-produto'>									<option value='0' <?php if ($origem=="0") echo "selected"; ?>>Nacional</option>									<option value='1' <?php if ($origem=="1") echo "selected"; ?>>Estrangeira � Importa��o direta</option>									<option value='2' <?php if ($origem=="2") echo "selected"; ?>>Estrangeira � Adquirida no mercado interno.</option>								</select>							</p>						</div>						<div class='titulo-secundario' style='float:left; width:50%;'>							<div class='titulo-secundario' style='float:left; width:95%;'>								<p>Grupo de caracter&iacute;sticas</p>								<p>									<select name='formulario-id' id='formulario-id' class=''>									<option value=''>N&atilde;o Utiliza</option>										<?php echo optionValueFormularios($formularioID, 'produtos_dados','');?>									</select>								</p>							</div>							<div class='titulo-secundario' style='float:left; width:5%; margin-top:18px;'>								<div class='btn-mais btn-incluir-novo-formulario' title='Incluir novo formul&aacute;rio' style='float:left;padding-left:10px'>&nbsp;</div>							</div>						</div>					</div>					<div class='titulo-secundario dados-produto' style='float:left; width:25%; margin-top:3px;'>						<p>NCM</p>						<p><input type='text' id='ncm-produto' name='ncm-produto' style='width:90%' value='<?php echo $NCM;?>' class='formata-numero' maxlength='8'/></p>					</div>					<div class='titulo-secundario dados-produto' style='float:left; width:25%; margin-top:10px;'>						<p><input type='checkbox' id='produto-industrializado' name='produto-industrializado' value='1' <?php if ($industrializado=="1") echo "checked";?>/> <label for='produto-industrializado'>Industrializado</label></p>						<p><input type='checkbox' id='produto-numero-serie' name='produto-numero-serie' value='1' <?php if ($numeroSerie=="1") echo "checked";?>/> <label for='produto-numero-serie'>Controle de N�mero de S�rie</label></p>						<p><input type='checkbox' id='destaque' name='destaque' value='1' <?php if ($destaque=="1") echo "checked";?>/> <label for='destaque'>Destaque</label></p>						<p><input type='checkbox' id='lancamento' name='lancamento' value='1' <?php if ($lancamento=="1") echo "checked";?>/> <label for='lancamento'>Lan&ccedil;amento</label></p>						<p><input type='checkbox' id='faturamento-direto' name='faturamento-direto' value='1' <?php if ($faturamentoDireto=="1") echo "checked";?>/> <label for='faturamento-direto'>Faturamento direto</label></p>					</div>				</div>			</div>		</div><?php	if ($modulosAtivos['site']){?>		<div class='titulo-container grupo1' id='div-dados-gerais'>			<div class='titulo'>				<p> Site <input type='button' value='Atualizar no Site' class='botao-atualizar-site' style='width:100px'/></p>			</div>			<div class='conteudo-interno'>				<input type='hidden' name='site-acao' id='site-acao' value=''/><?php				$rs = mpress_query("select Site_ID, URL, Empresa_ID from sites_dados where Situacao_ID = 1");				while($row = mpress_fetch_array($rs)){					echo "	<div class='titulo-secundario quatro-colunas'>								<input type='checkbox' name='sites-selecionados[]' checked value='".$row['Site_ID']."'> ".$row['URL']."							</div>";				}?>			</div>		</div><?php	}?>		<div class="titulo-container esconde grupo4" id='div-caracteristicas'>			<div class="titulo">				<p>					Caracter&iacute;sticas					<input type='button' value='Salvar Produto' id='botao-cadastra-produto' class='botao-cadastra-produto' origem='variacoes'/>					<input type='button' value='Incluir Nova Varia&ccedil;&atilde;o' id='botao-cadastra-produto-caracteristica' class='botao-cadastra-produto-caracteristica' style='width:150px;'/>				</p>			</div>			<div class='conteudo-interno' id='conteudo-interno-variacoes'><?php			echo carregarVariacoesProduto($produtoID);?>			</div>		</div>		<div class='titulo-container div-produtos-fornecedores grupo3 esconde' id='div-produtos-fornecedores'>			<div class='titulo'>				<p>					Fornecedores					<input type='button' class='botao-incluir-fornecedor' style='float:right;margin-right:0px;' campo-alvo='fornecedor-id' value='Incluir' parametro=''>				</p>			</div>			<div class='conteudo-interno conteudo-interno-fornecedor-id' id='conteudo-interno-fornecedor-id'></div>		</div><!--		<div class='titulo-container div-cadastros-vinculos esconde grupo3' id='div-cadastros-vinculos'>			<div class='titulo'>				<div class='btn-expandir-retrair-vinculos'  id='btn-expandir-retrair-vinculos' style='float:right;' title='Expandir'></div>				<p>					Fornecedores					<input type='button' value='Salvar' id='botao-cadastra-produto' class='botao-cadastra-produto' origem='fornecedores'/>				</p>			</div>			<div class='conteudo-interno esconde' id='div-vincular-usuario' id='div-vincular-usuario' Style='float:left;width:100%;margin-bottom:5px;'>				<div id='div-vincular-usuario-select' style='float:left; width:80%;'>					<select id='select-novo-vinculo' name='select-novo-vinculo' style='width:98.5%'><option value=''>Selecione</option></select>				</div>				<div id='div-vincular-usuario-texto' style='float:left; width:80%;'>					<input type='text' id='texto-localizar-cadastros-vinculos' name='texto-localizar-cadastros' style='width:98.5%'>				</div>				<div id='div-localizar-usuario-localizar' style='width:10%; float:left;'>					<input type='button' id='botao-localizar-cadastros' name='botao-localizar-cadastros' value='Localizar' Style='width:95%'>				</div>				<div id='div-localizar-usuario-incluir' style='width:10%; float:left;'>					<input type='button' value='Incluir' id='botao-novo-vinculo' class='botao-novo-vinculo' Style='width:95%'/>				</div>				<div id='div-localizar-usuario-cancelar' style='width:10%; float:left;'>					<input type='button' value='Cancelar' id='botao-cancelar-vinculo' class='botao-cancelar-vinculo' Style='width:95%'/>				</div>			</div>			<div id='div-usuarios-vinculados'></div>		</div>--><!--		<div class="titulo-container esconde grupo2" id='div-caracteristicas'>			<div class="titulo">				<p>					Controle de Estoque					<input type='button' value='Salvar' id='botao-cadastra-produto' class='botao-cadastra-produto' origem='controle-estoque'/>				</p>			</div>--><?php	/*	$query = mpress_query("select Produto_Variacao_ID from produtos_variacoes where produto_ID = $produtoID");	if($row = mpress_fetch_array($query)) $produtoVariacaoID = $row['Produto_Variacao_ID'];	$compraMinima			= 0;	$prazoEntrega 			= 0;	$estoqueMinimo 			= 0;	$utilizacaoMedia 		= 0;	$selecionadoEmbalagem 	= 0;	$saldoEstoqueVariacao	= 0;	$query = mpress_query("select Estoque_Minimo, Compra_Minima, Utilizacao_Media, Prazo_Medio_Entrega, Quantidade_Embalagem from produtos_estoque where Produto_Variacao_ID = $produtoVariacaoID");	if($row = mpress_fetch_array($query)){		$estoqueMinimo = number_format($row[Estoque_Minimo], 2, ',', '.');		$compraMinima = number_format($row[Compra_Minima], 2, ',', '.');		$utilizacaoMedia = number_format($row[Utilizacao_Media], 2, ',', '.');		$prazoEntrega = number_format($row[Prazo_Medio_Entrega], 0, ',', '.');		$selecionadoEmbalagem 	= $row[Quantidade_Embalagem];	}	$query = mpress_query("select sum(Quantidade) Quantidade from produtos_movimentacoes where Produto_Variacao_ID = $produtoVariacaoID");	if($row = mpress_fetch_array($query))		$saldoEstoqueVariacao	= number_format($row[Quantidade], 2, ',', '.');	*/?><!--			<input type='hidden' name='hdProdV' value='<?php echo $produtoVariacaoID;?>'>			<div Style='margin-top:10px;float:left;width:99%;margin-left:0.5%;'>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Saldo estoque</p>					<p><input type="text" id="saldo-variacao" name="saldo-estoque-variacao"  value='<?php echo $saldoEstoqueVariacao; ?>'  class='formata-valor' maxlength='10' readonly/></p>				</div>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Estoque Minimo (un)</p>					<p><input type="text" id="estoque-minimo" name="estoque-minimo"  value='<?php echo $estoqueMinimo; ?>'  class='formata-valor' maxlength='10'/></p>				</div>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Compra Minima (un)</p>					<p><input type="text" id="compra-minima" name="compra-minima"  value='<?php echo $compraMinima; ?>'  class='formata-valor' maxlength='10'/></p>				</div>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Utilizacao M&eacute;dia M&ecirc;s (un)</p>					<p><input type="text" id="utilizacao-media" name="utilizacao-media"  value='<?php echo $utilizacaoMedia; ?>'  class='formata-valor' maxlength='10'/></p>				</div>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Prazo Entrega (dias)</p>					<p class='omega'><input type="text" id="prazo-entrega" name="prazo-entrega"  value='<?php echo $prazoEntrega; ?>'  class='formata-numero' maxlength='10'/></p>				</div>				<div class="titulo-secundario seis-colunas dados-produto grupo2">					<p>Quantidade embalagem</p>					<p>						<select name='quantidade-embalagem' id='quantidade-embalagem' class='omega'>							<?php echo optionValueCountSelect(100,$selecionadoEmbalagem);?>						</select>					</p>				</div>			</div>		</div>-->		<div class="titulo-container esconde grupo2" id='div-caracteristicas'>			<div class="titulo">				<p>Movimenta&ccedil;&otilde;es de Entrada e Sa&iacute;da					<!--<input type='button' value='Salvar' id='botao-cadastra-produto' class='botao-cadastra-produto' origem='estoque'/>-->				</p>			</div>			<div class='conteudo-interno'><?php	$sql = "select concat(coalesce(pd.Nome,''),' ',coalesce(pv.Descricao,'')) as Nome, pv.Produto_Variacao_ID as Produto_Variacao_ID								from produtos_variacoes pv								inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID								where pv.Produto_ID = '$produtoID' and pv.Situacao_ID = 1								order by pv.Produto_Variacao_ID desc";	//left join produtos_movimentacoes m on pv.Produto_Variacao_ID = m.Produto_Variacao_ID	//echo $sql;	$resultado = mpress_query($sql);	while($row = mpress_fetch_array($resultado)){		echo "	<div style='float:left; width:100%; margin-bottom:20px;' class='titulo-secundario'>					<div style='float:left; width:100%;'>						<p><b>Varia&ccedil;&atilde;o</b></p>						<p>".$row['Nome']."</p>					</div>					<div style='float:left; margin-top:3px; margin-bottom:3px;'>						<div style='float:left; width:20%;'>							<p>Estoque Minimo (un)</p>							<p><input type='text' id='estoque-minimo' name='estoque-minimo'  value='$estoqueMinimo'  class='formata-valor' maxlength='10' style='width:90%'/></p>						</div>						<div style='float:left; width:20%;'>							<p>Compra Minima (un)</p>							<p><input type='text' id='compra-minima' name='compra-minima'  value='$compraMinima'  class='formata-valor' maxlength='10' style='width:90%'/></p>						</div>						<div style='float:left; width:20%;'>							<p>Utilizacao M&eacute;dia M&ecirc;s (un)</p>							<p><input type='text' id='utilizacao-media' name='utilizacao-media'  value='$utilizacaoMedia'  class='formata-valor' maxlength='10' style='width:90%'/></p>						</div>						<div style='float:left; width:20%;'>							<p>Prazo Entrega (dias)</p>							<p class='omega'><input type='text' id='prazo-entrega' name='prazo-entrega'  value='$prazoEntrega' class='formata-numero' maxlength='10' style='width:90%'/></p>						</div>						<div style='float:left; width:20%;'>							<p>Quantidade embalagem</p>							<p><select name='quantidade-embalagem' id='quantidade-embalagem' style='width:90%'>".optionValueCountSelect(100,$selecionadoEmbalagem)."</select></p>						</div>					</div>";		carregarMovimentacoes($row[Produto_Variacao_ID]);		echo "	</div>";	}	echo "	</div>		</div>";?>		<!-- INICIO Bloco Upload usando PLUPLOAD -->		<div id='div-documentos'></div>		<div id="container">			<input type="hidden" id="pickfiles"/>			<input type="hidden" id="uploadfiles"/>		</div>		<!-- FIM Bloco Upload usando PLUPLOAD --><?php	$detalhesVariacao = mpress_fetch_array(mpress_query("SELECT distinct v.Descricao, v.Valor_Venda, v.Codigo, ma.Nome_Arquivo,Anexo_ID									  FROM produtos_compostos c									  inner join produtos_variacoes v on v.Produto_Variacao_ID = c.Produto_Variacao_Pai_ID									  left join modulos_anexos ma on ma.Anexo_ID = v.Imagem_ID									  where c.Produto_Pai_ID = $produtoID and c.Situacao_id = 1"));?>		<div class="titulo-container esconde grupo6" id='div-composicao'>			<div class="titulo">				<p>					Detalhes da Composi��o do Produto					<input type='button' value='Salvar Produto'  id='botao-cadastra-produto' class='botao-cadastra-produto' origem='geral'/>				</p>			</div>			<div class='conteudo-interno'>				<div class="titulo-secundario" Style='width:10%;float:left;'>					<p>C�digo</p>					<p><input type='text' id='composto-codigo-secundario' name='codigo-variacao-composto' Style='width:90%' value='<?php echo $detalhesVariacao['Codigo'];?>'  class='formata-texto' maxlength='150'/></p>				</div>				<div class="titulo-secundario" Style='width:50%;float:left;'>					<p>T�tulo Auxiliar</p>					<p><input type='text' id='composto-titulo-secundario' name='descricao-variacao-composto' Style='width:98.5%' value='<?php echo $detalhesVariacao['Descricao'];?>'  class='formata-texto' maxlength='150'/></p>				</div>				<div class="titulo-secundario" Style='width:29.5%;float:left;margin-right:0.5%'>					<p>Imagem Varia&ccedil;&atilde;o</p>					<p>						<select id='imagem-variacao-composto' name='imagem-variacao-composto' style='width:95%'>							<option value=''>Sem imagem</option><?php			$rs = mpress_query("select Anexo_ID, Nome_Arquivo,Nome_Arquivo_Original from modulos_anexos ma where ma.Situacao_ID = 1 and ma.Chave_Estrangeira = '$produtoID' and Tabela_Estrangeira = 'produtos' order by Nome_Arquivo");			while($row = mpress_fetch_array($rs)){				if($detalhesVariacao['Anexo_ID'] == $row['Anexo_ID']) $selecionado = "selected"; else $selecionado = "";				echo "				<option value='".$row['Anexo_ID']."' $selecionado>".$row['Nome_Arquivo_Original']."</option>";			}?>						</select>					</p>				</div>				<div class="titulo-secundario" Style='width:10%;float:left;'>					<p>Valor(R$)</p>					<p><input type='text' id='valor-venda-variacao-composto' name='valor-venda-variacao-composto' Style='width:98%'  value='<?php echo number_format($detalhesVariacao['Valor_Venda'],2, ',', '.');?>'  class='formata-valor' maxlength='150'/></p>				</div>			</div>		</div>		<div class="titulo-container esconde grupo6" id='div-composicao'>			<div class="titulo">				<p>Gerenciar Composi��o do Produto</p>			</div>			<div class='conteudo-interno'>				<div class="titulo-secundario uma-coluna">					<?php carregarLocalizarProdutos();?>				</div>			</div>		</div>		<div class="titulo-container esconde grupo6" id='div-detalhes-composicao'>			<?php carregaDetalhesProdutoComposto();?>		</div>