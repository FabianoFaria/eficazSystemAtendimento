<?php
	function atualizaProdutosTabelaComissao($tabelaID){
		$i=0;
		mpress_query("update financeiro_tabelas_comissoes_produtos set Situacao_ID = 2 where Tabela_Comissao_ID = '$tabelaID'");
		foreach($_POST['produto-variacao-id'] as $produtoVariacaoID){
			$percentual = formataValorBD($_POST['percentual'][$i]);
			if ($percentual>0){
				mpress_query("insert into financeiro_tabelas_comissoes_produtos (Tabela_Comissao_ID, Produto_Variacao_ID,  Percentual_Comissao, Situacao_ID)
															values('$tabelaID', '$produtoVariacaoID', $percentual, 1)");
			}
			$i++;
		}
	}

	$sql = "select Descr_Tipo as dadosGerais from tipo where Tipo_ID = 106";
	$resultado = mpress_query($sql);
	if($rs = mpress_fetch_array($resultado)){
		$config = unserialize($rs[dadosGerais]);
	}


	if ($config['comissionamento-orcamento'] == ""){
		echo "<div id='tabela-container'>
				<div class='titulo-container grupo1' id='div-dados-gerais'>
					<div class='titulo'>
						<p>Comissionamento</p>
					</div>
					<div class='conteudo-interno'>
						<p>O sistema foi configurado para não trabalhar com comissionamento</p>
					</div>
				</div>
			 </div>";
	}

	if ($config['comissionamento-orcamento'] == "126"){
		if($_POST['nome-tabela']!=''){
			$rs = mpress_query("select * from financeiro_tabelas_comissoes where Titulo_Tabela = '".$_POST['nome-tabela']."' and Situacao_ID = 1");
			if(!$row = mpress_fetch_array($rs)){
				mpress_query("insert into financeiro_tabelas_comissoes(Titulo_Tabela, Tipo_Comissionamento)values('".$_POST['nome-tabela']."',126)");
				$tabelaID = mpress_identity();
			}
		}
		if($_POST['tabela-comissao-seleciona'] > 0) $tabelaID = $_POST['tabela-comissao-seleciona'];
		if($_POST['acao-tabela']!=''){
			if($_POST['acao-tabela']=='e'){
				mpress_query("update financeiro_tabelas_comissoes set Situacao_ID = 2 where Tabela_Comissao_ID = ".$_POST['tabela-comissao-seleciona']);
				mpress_query("update financeiro_tabelas_comissoes_produtos set Situacao_ID = 2 where Tabela_Comissao_ID = ".$_POST['tabela-comissao-seleciona']);
			}
			if($_POST['acao-tabela']=='a')
				atualizaProdutosTabelaComissao($tabelaID);
		}
		echo "	<div id='tabela-container'>
					<div class='titulo-container grupo1' id='div-dados-gerais'>
						<div class='titulo'>
							<p>
								Tabela para Edição";
		if($tabelaID != ""){
			echo "				<input type='button' value='Salvar'  id='botao-atualiza-tabela-comissao' class='botao-atualiza-tabela-comissao'/>
								<input type='button' value='Excluir' id='botao-excluir-tabela-comissao' class='botao-excluir-tabela-comissao lixeira'/>";
		}
		echo "
							</p>
						</div>
						<div class='conteudo-interno'>
							<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>
								<select name='tabela-comissao-seleciona' id='tabela-comissao-seleciona' Style='padding:5px;margin-top:7px;margin-left:3px'>
									<option value=''>Selecione</option>
									<option value='-1'>Cadastrar nova tabela</option>";
					$rs = mpress_query("select Tabela_Comissao_ID, Titulo_Tabela from financeiro_tabelas_comissoes where Situacao_ID = 1 and Tipo_Comissionamento = 126 order by Titulo_Tabela");
					while($row = mpress_fetch_array($rs)){
						if($row['Tabela_Comissao_ID'] == $tabelaID) $selecionado = "selected"; else $selecionado = "";
						echo "		<option value='".$row['Tabela_Comissao_ID']."' $selecionado>".$row['Titulo_Tabela']."</option>";
					}
		echo "					</select>
							</div>
							<div class='titulo-secundario uma-coluna esconde' id='div-cadastra-tabela'>
								<p>
									<table Style='width:99.5%;margin-top:8px;' align='center'>
										<tr>
											<td width='096'>Nome da Tabela:</td>
											<td><input type='text' name='nome-tabela' id='nome-tabela' Style='padding:4px;width:99%'></td>
											<td width='100'><input type='button' value='Cadastrar' id='cadastra-tabela-comissao'></td>
											<td width='100'><input type='button' value='Cancelar'  id='cancela-tabela-comissao'></td>
										</tr>
									</table>
								</p>
							</div>
						</div>
					</div>
				</div>";

		 if($tabelaID != ""){
			echo "	<div id='tabela-container'>
						<div class='titulo-container grupo1' id='div-produtos-cadastrados'>
							<div class='titulo'>
								<p>
									Produtos e Serviços Cadastrados
									<input type='text' value='0,00' style='float:right; width:80px;text-align:center; margin-top:-6px; margin-right:1px' id='comissao-geral' class='formata-valor' />
									<input type='button' value='Replicar'  id='replicar-comissao' class='replicar-comissao'/>
								</p>
							</div>
							<div class='conteudo-interno'>
								<div class='titulo-secundario uma-coluna omega' id='div-seleciona-tabela'>";
			$rs = mpress_query("select Produto_Variacao_ID, Percentual_Comissao from financeiro_tabelas_comissoes_produtos where Tabela_Comissao_ID = '$tabelaID' and Situacao_ID = 1");
			while($row = mpress_fetch_array($rs))
				$prod[$row['Produto_Variacao_ID']][percentual] = $row['Percentual_Comissao'];

			$sql = "select pv.Produto_Variacao_ID, trim(concat(coalesce(p.Nome,''), ' ', coalesce(pv.Descricao,''))) as Descricao, t.Descr_Tipo as Tipo_Produto
					 from produtos_dados p
					 inner join produtos_variacoes pv on pv.Produto_ID = p.Produto_ID
					 inner join tipo t on t.Tipo_ID = p.Tipo_Produto
					 inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
					 where p.Situacao_ID = 1 and pv.Situacao_ID = 1 and p.Situacao_ID = 1 order by Descricao";
			//echo $sql;
			$rs = mpress_query($sql);
			while($row = mpress_fetch_array($rs)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;".$row[Descricao];
				$dados[colunas][conteudo][$i][2] = "&nbsp;&nbsp;".$row[Tipo_Produto];
				$dados[colunas][conteudo][$i][3] = "<center>
														<input type='hidden' name='produto-variacao-id[]' value='".$row['Produto_Variacao_ID']."'/>
														<input type='text' name='percentual[]' value='".number_format($prod[$row['Produto_Variacao_ID']][percentual], 2, ',','.')."' class='formata-valor comissao-lista' Style='width:80px;text-align:center;'/>
													</center>";
			}
			$largura = "100.2%";
			$colunas = "10";
			$dados[colunas][tamanho][1] = "";
			$dados[colunas][tamanho][2] = "width='100'";
			$dados[colunas][tamanho][3] = "width='100'";
			$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 5px;'>Produto</p>";
			$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;'>Tipo</p>";
			$dados[colunas][titulo][3] 	= "<p Style='margin:2px 5px 0 13px;'>Comissao %</p>";
			geraTabela('100.2%',3,$dados);

			echo "		</div>
					</div>
				</div>
			</div>";
		}
		echo "<input type='hidden' id='acao-tabela' name='acao-tabela'>";
	}

if (($config['comissionamento-orcamento'] == "127")||($config['comissionamento-orcamento'] == "128")){

	if($_SERVER['REQUEST_METHOD']=='POST'){
		$request = md5(implode($_POST));
		// define se é POST e não apenas um REFRESH
		if(!(isset($_SESSION['last_request']) && $_SESSION['last_request'] == $request)){
			$_SESSION['last_request']  = $request;

			$valorInicial = formataValorBD($_POST['valor-inicial']);
			$valorFinal = formataValorBD($_POST['valor-final']);
			$percComissao = formataValorBD($_POST['perc-comissao-faixa']);
			$descricaoFaixa = $_POST['descricao-faixa'];
			$tabelaComissaoFaixaID = $_POST['tabela-comissao-faixa-id'];


			$resultado = mpress_query("select Tabela_Comissao_ID from financeiro_tabelas_comissoes where Tipo_Comissionamento = 127");
			if($rs = mpress_fetch_array($resultado)){
				$tabelaComissaoID = $rs[Tabela_Comissao_ID];
			}			
			if ($tabelaComissaoID==""){
				mpress_query("insert into financeiro_tabelas_comissoes (Titulo_Tabela, Tipo_Comissionamento, Situacao_ID)
																values	('Tabela Padrão',				127, 1)");
				$tabelaComissaoID = mpress_identity();
			}
			if ($_POST['acao-faixa']=="I"){
				mpress_query("insert into financeiro_tabelas_comissoes_faixas (Tabela_Comissao_ID, Descricao, Valor_Inicial, Valor_Final, Percentual_Comissao, Situacao_ID)
																		values ($tabelaComissaoID, '$descricaoFaixa', '$valorInicial', '$valorFinal', '$percComissao', 1)");
			}
			if ($_POST['acao-faixa']=="U"){
				mpress_query("update financeiro_tabelas_comissoes_faixas set Descricao = '$descricaoFaixa', 
																Valor_Inicial = '$valorInicial', 
																Valor_Final = '$valorFinal', 
																Percentual_Comissao = '$percComissao'
														where Tabela_Comissao_Faixa_ID = '$tabelaComissaoFaixaID'");
			}
			

			if ($_POST['acao-faixa']=="D"){
				mpress_query("update financeiro_tabelas_comissoes_faixas set Situacao_ID = 3 where Tabela_Comissao_Faixa_ID = '$tabelaComissaoFaixaID'");
			}

		}
	}

	echo "	<!--
			<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Comissionamento por faixa de valores
						<input type='button' value='Salvar' id='salvar-configuracoes' class='botoes-acao'/>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div style='float:left; width:100%' class='titulo-secundario'>
						<p>Tipo</p>
						<p>
							<input type='radio' id='tipo-comissionamento-1' name='tipo-comissionamento' value='proposta'/><label for='tipo-comissionamento-1'>Por Proposta</label>
							<input type='radio' id='tipo-comissionamento-2' name='tipo-comissionamento' value='mensal'/><label for='tipo-comissionamento-2'>Mensal</label>
						</p>
					</div>
				</div>
			</div>
			-->";

	echo "	<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Faixas de comissionamento
						<input type='button' value='Incluir' class='exibir-incluir-faixa botoes-acao'/>
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='bloco-incluir-faixa uma-coluna titulo-secundario esconde' style='margin-bottom:5px;' >
						<div style='float:left; width:40%'>
							<p>Descrição</p>
							<p><input type='text' id='descricao-faixa' name='descricao-faixa' style='width:98%' class='required'/></p>
						</div>
						<div style='float:left; width:15%'>
							<p>Valor Inicial (de)</p>
							<p><input type='text' id='valor-inicial' name='valor-inicial' style='width:95%' class='formata-valor required'/></p>
						</div>
						<div style='float:left; width:15%'>
							<p>Valor Final (até)</p>
							<p><input type='text' id='valor-final' name='valor-final' style='width:95%' class='formata-valor required'/></p>
						</div>
						<div style='float:left; width:10%'>
							<p>% Comissão</p>
							<p><input type='text' id='perc-comissao-faixa' name='perc-comissao-faixa' style='width:92%' class='formata-valor required'/></p>
						</div>

						<div style='float:left; width:10%'>
							<p>&nbsp;</p>
							<p><input type='button' value='Cancelar' class='cancelar-incluir-faixa'/></p>
						</div>
						<div style='float:left; width:10%'>
							<p>&nbsp;</p>
							<p><input type='button' value='Incluir' class='salvar-faixa' id='salvar-faixa'/></p>
						</div>
					</div>";
	carregarFaixasComissionamento();
	echo "		</div>
			</div>
			<input type='hidden' id='acao-faixa' name='acao-faixa' value=''>
			<input type='hidden' id='tabela-comissao-faixa-id' name='tabela-comissao-faixa-id' value=''>";
}


	function carregarFaixasComissionamento(){
		$sql = "select Tabela_Comissao_Faixa_ID, Descricao, Valor_Inicial, Valor_Final, Percentual_Comissao
						from financeiro_tabelas_comissoes_faixas where Situacao_ID = 1 and
						Tabela_Comissao_ID in (select Tabela_Comissao_ID from financeiro_tabelas_comissoes where Tipo_Comissionamento = 127)
						order by Valor_Inicial";
		//echo $sql;
		$rs = mpress_query($sql);
		$ultimoValor = 0;
		while($row = mpress_fetch_array($rs)){
			$i++;
			$faixaID = $row[Tabela_Comissao_Faixa_ID];
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 13px;' id='descricao-$faixaID'>".$row[Descricao]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 15px 0 0;' align='right' id='valor-inicial-$faixaID'>".number_format($row[Valor_Inicial], 2, ',','.')."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 15px 0 0;' align='right' id='valor-final-$faixaID'>".number_format($row[Valor_Final], 2, ',','.')."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 15px 0 0' align='right' id='percentual-comissao-$faixaID'>".number_format($row[Percentual_Comissao], 2, ',','.')."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;' align='left'>
													<div class='btn-excluir btn-excluir-comissao' style='float:right; padding-right:5px' faixa-id='$faixaID' title='Excluir'>&nbsp;</div>
													<div class='btn-editar btn-editar-comissao' style='float:right; padding-right:5px' faixa-id='$faixaID' title='Editar'>&nbsp;</div>
												</p>";
			$ultimoValor = $row[Valor_Final];
		}
		$dados[colunas][tamanho][1] = "width='40%'";
		$dados[colunas][tamanho][2] = "width='15%'";
		$dados[colunas][tamanho][3] = "width='15%'";
		$dados[colunas][tamanho][4] = "width='10%'";
		$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 5px;'>Descrição</p>";
		$dados[colunas][titulo][2] 	= "<p Style='margin:2px 15px 0 0;' align='right'>Valor Inicial (de)</p>";
		$dados[colunas][titulo][3] 	= "<p Style='margin:2px 15px 0 0;' align='right'>Valor Final (até)</p>";
		$dados[colunas][titulo][4] 	= "<p Style='margin:2px 15px 0 0;' align='right'>% Comissão</p>";
		geraTabela('100%',5,$dados);
		echo "<input type='hidden' id='ultimo-valor' value='$ultimoValor'/>";

	}

?>
