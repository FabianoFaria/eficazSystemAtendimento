<?php
	global $modulosAtivos, $configChamados;
	include('functions.php');
	$configChamados 	= carregarConfiguracoesGeraisModulos('chamados');

	$contEmpresas 		= verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
	}

	if ($_POST){
		$dataInicio 		= $_POST['data-inicio'];
		$dataFim 			= $_POST['data-fim'];
		$chamadoID 			= $_POST['chamado-id'];
		$ordemCompraID 		= $_POST['localiza-ordem-compra-id'];
		$envioID 			= $_POST['localiza-envio-id'];
		$orcamentoID 		= $_POST['localiza-orcamento-id'];
		$virgula 			= "";
		for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
			$cadastrosDe 	.= $virgula.$_POST['localiza-cadastro-de'][$i];
			$virgula 		= ",";
		}
		$tipoGrupo 			= $_POST['radio-tipo-grupo'];
		$origemFaturamento 	= $_POST['origem-faturamento'];
		$cadastroPara 		= $_POST['cadastro-para'];
	}
	else{
		//$origemFaturamento = "chamados";
		$origemFaturamento = "orcamentos";
		$tipoGrupo = "45";

		$mes 				= date("m");
		$ano 				= date("Y");
		$ultimo_dia 		= date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFim 			= $ultimo_dia."/".date("m/Y");
		$dataInicio 		= "01/".date("m/Y");

	}
?>
				<div id='div-retorno'></div>
				<div id='financeiro-container'>
					<div class='titulo-container'>
						<div class='titulo'>
							<p style="margin-top:2px;">Filtros de Pesquisa
								<input type='button' value='Atualizar' id='botao-faturar-cancelar' tipo-faturamento='45' class='botao-faturar-cancelar' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
							</p>
						</div>
						<div class='conteudo-interno'>

							<div class='titulo-secundario' style='float:left;width:20%'>
								<p>Origem</p>
								<p>
									<select name='origem-faturamento' id='origem-faturamento'>
									
									<?php

										if (($modulosAtivos['chamados']) || ($configChamados['orcamentos']==0)){ echo "<option value='orcamentos'"; if ($origemFaturamento=='orcamentos') echo 'selected'; echo ">Or&ccedil;amento</option>"; }
										if (($modulosAtivos['chamados']) || ($configChamados['chamados']==0)){ echo "<option value='chamados'"; if ($origemFaturamento=='chamados') echo 'selected'; echo ">".$_SESSION['objeto']."</option>"; }
										if ($modulosAtivos['compras']){ echo "<option value='compras'"; if ($origemFaturamento=='compras') echo 'selected'; echo ">Compras</option>"; }
										if ($modulosAtivos['envios']){ echo "<option value='envios'"; if ($origemFaturamento=='envios') echo 'selected'; echo ">Centro de Distribui&ccedil;&atilde;o</option>"; }
										if ($modulosAtivos['financeiro-comissionamento']){ echo "<option value='comissao'"; if ($origemFaturamento=='comissao') echo 'selected'; echo ">Comissões</option>"; }
									?>

									</select>
									<input type='hidden' id='origem-selecionada' name='origem-selecionada' value='<?php echo $origemFaturamento; ?>'/>
								</p>
							</div>
							<div class="titulo-secundario div-todos div-origem-comissao" style='float:left;width:20%'>
								<p>Representante / Vendedor
									<div>
										<select name="representante" id="representante" style='width:98.5%'>
											<option value=''></option>
												<?php
													$grupos = mpress_query("SELECT cd.Cadastro_ID, cd.Nome FROM cadastros_dados cd
																				inner join modulos_acessos ma on ma.Modulo_Acesso_ID = cd.Grupo_ID
																				where cd.Cadastro_ID > 0 and ma.Situacao_ID = 1 order by cd.Nome");
													while($row = mpress_fetch_array($grupos)){
														if ($representanteID==$row['Cadastro_ID']) $selecionado = ' selected '; else $selecionado = '' ;
														echo " 						<option value='".$row['Cadastro_ID']."' $selecionado>".$row['Nome']."</option>";
													}
												?>										
										<select>
									</div>
								</p>
							</div>

							<div class="titulo-secundario div-todos div-origem-comissao" style='float:left;width:20%'>
								<p>Data Venda</p>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-venda-inicio' id='data-venda-inicio' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataVendaInicio; ?>'>&nbsp;&nbsp;</p>
								</div>
								<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-venda-fim' id='data-venda-fim' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataVendaFim; ?>'></p>
								</div>
							</div>

							<div class="titulo-secundario div-todos div-origem-chamados" style='float:left;width:20%'>
								<p><?php echo $_SESSION['objeto'];?> ID</p>
								<p><input type='text' name='chamado-id' id='chamado-id' maxlength='50' style='width:92%' class='formata-numero' value='<?php echo $chamadoID; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div class="titulo-secundario div-todos div-origem-compras" style='float:left;width:20%'>
								<p>Ordem de Compra</p>
								<p><input type='text' name='localiza-ordem-compra-id' id='localiza-ordem-compra-id' maxlength='50' style='width:92%' class='formata-numero' value='<?php echo $ordemCompraID; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div class="titulo-secundario div-todos div-origem-envios" style='float:left;width:20%'>
								<p>Envio ID</p>
								<p><input type='text' name='localiza-envio-id' id='localiza-envio-id' maxlength='50' style='width:92%' class='formata-numero' value='<?php echo $envioID; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div class="titulo-secundario div-todos div-origem-orcamentos" style='float:left;width:20%'>
								<p>Or&ccedil;amento ID</p>
								<p><input type='text' name='localiza-orcamento-id' id='localiza-orcamento-id' maxlength='50' style='width:92%' class='formata-numero' value='<?php echo $orcamentoID; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div class="titulo-secundario div-todos div-origem-envios div-origem-compras div-origem-chamados div-origem-orcamentos" style='float:left;width:40%'>
								<p>Fornecedor / Emitente</p>
								<p><input type='text' name='cadastro-para' id='cadastro-para' maxlength='300' style='width:93%' value='<?php echo $cadastroPara; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div class='titulo-secundario' style='float:right;width:20%;'>
								<div class='<?php echo $classeEmpresasEsconde; ?>'>
									<div id='div-cadastros'>
										<p>Cadastros:</p>
										<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
									</div>
								</div>
								&nbsp;
							</div>
							<div class='titulo-secundario div-todos div-origem-chamados' style='float:left;width:20%; height:35px;'>
								<p>Contas</p>
								<p>
									<input type='radio' class='radio-tipo-grupo' name='radio-tipo-grupo' id='radio-tipo-grupo-45' value='45' <?php if ($tipoGrupo=='45') echo 'checked'; ?>>
									<label for='radio-tipo-grupo-45'> A Receber </label>&nbsp;
									<input type='radio' class='radio-tipo-grupo' name='radio-tipo-grupo' id='radio-tipo-grupo-44' value='44' <?php if ($tipoGrupo=='44') echo 'checked'; ?>>
									<label for='radio-tipo-grupo-44'> A Pagar </label>&nbsp;
								</p>
							</div>
							<!--
							<div class="titulo-secundario div-todos div-origem-chamados" style='float:left;width:20%'>
								<p>Data Solicita&ccedil;&atilde;o</p>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-inicio' id='data-inicio' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicio; ?>'>&nbsp;&nbsp;</p>
								</div>
								<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
								<div style='width:43%;float:left;'>
									<p><input type='text' name='data-fim' id='data-fim' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFim; ?>'></p>
								</div>
							</div>
							-->
							<div class="titulo-secundario div-todos div-origem-compras div-origem-envios" style='float:left;width:40%'><p>&nbsp;<p><p>&nbsp;<p></div>
							<div class="titulo-secundario" style='float:left;width:40%; height:65px; margin-top:13px'><p>&nbsp;<p><p>&nbsp;<p></div>
							
							<div class='titulo-secundario duas-colunas' Style='margin-top:3px; float:right;width:10%'>
								<p align='right'><input type='button' value='Pesquisar' id='botao-pesquisar-contas' style='width:100px:margin-right:2px'/></p>
							</div>
						</div>
					</div>
				</div>
				<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>
				<!--<input type='hidden' id='localiza-titulo-id' name='localiza-titulo-id' value=''>-->
				<input type='hidden' id='workflow-id' name='workflow-id' value=''>
				<input type='hidden' id='ordem-compra-id' name='ordem-compra-id' value=''>
<?php
	//if($_POST){
		if ($origemFaturamento=='orcamentos'){
			/*
			if ($cadastrosDe != ""){ $sqlCond .= " and fc.Cadastro_ID_de IN ($cadastrosDe)";}
			if(($dataInicio!="")||($dataFim!="")){
				$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
				if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
				$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
				if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
				$sqlCond .= " and fc.Data_Cadastro between '$dataInicio' and '$dataFim' ";
			}
			if ($orcamentoID!=''){ $sqlCond .= " and cwp.Workflow_ID = '$chamadoID'";}
			//if ($cadastroPara!=""){ $sqlCond .= " and (cd2.Nome like '%$cadastroPara%' or cd2.Nome_Fantasia like '%$cadastroPara%')";}
			*/
			$titulo = "Aguardando Faturamento";
			$array = carregarProdutosFaturar('','orcamentos');
			$dados = $array['dados'];
			$largura = $array['largura'];
			$colunas = $array['colunas'];
		}
		/****************/
		/* chamados o.s */
		/****************/
		
		if ($origemFaturamento=='chamados'){
			$sqlCond = "";
			if ($tipoGrupo!=''){ $sqlCond .= " and  fc.Tipo_ID = $tipoGrupo";}
			if ($cadastrosDe != ""){ $sqlCond .= " and fc.Cadastro_ID_de IN ($cadastrosDe)";}
			if(($dataInicio!="")||($dataFim!="")){
				$dataInicio = implode('-',array_reverse(explode('/',$dataInicio)));
				if ($dataInicio=="") $dataInicio = "0000-00-00"; $dataInicio .= " 00:00";
				$dataFim = implode('-',array_reverse(explode('/',$dataFim)));
				if ($dataFim=="") $dataFim = "2100-01-01"; $dataFim .= " 23:59";
				$sqlCond .= " and fc.Data_Cadastro between '$dataInicio' and '$dataFim' ";
			}
			if ($chamadoID!=''){ $sqlCond .= " and cwp.Workflow_ID = '$chamadoID'";}
			if ($cadastroPara!=""){ $sqlCond .= " and (cd2.Nome like '%$cadastroPara%' or cd2.Nome_Fantasia like '%$cadastroPara%')";}

			$sql = "SELECT fp.Conta_ID, tc.Tipo_ID, tc.Descr_Tipo as Tipo, cd1.Nome as Nome_De, cd2.Nome as Nome_Para, pd.Nome as Produto, fc.Valor_Total,
						(cwp.Quantidade * cwp.Valor_Venda_Unitario) as Valor_Venda, (cwp.Quantidade * cwp.Valor_Custo_Unitario) as Valor_Custo,
						DATE_FORMAT(fc.Data_Cadastro, '%d/%m/%Y') as Data_Solicitacao, cwp.Workflow_Produto_ID, fp.Financeiro_Produto_ID, cwp.Workflow_ID as ID_Ref
						FROM financeiro_contas fc
						INNER JOIN tipo tc on tc.Tipo_ID = fc.Tipo_ID and tc.Tipo_Grupo_ID = 27
						INNER JOIN financeiro_produtos fp on fp.Tabela_Estrangeira = 'chamados' and fp.Conta_ID = fc.Conta_ID and fp.Situacao_ID = 1
						INNER JOIN chamados_workflows_produtos cwp on cwp.Workflow_Produto_ID = fp.Produto_Referencia_ID
						INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = cwp.Produto_Variacao_ID
						INNER JOIN produtos_dados pd on pd.Produto_ID = pv.Produto_ID
						INNER JOIN cadastros_dados cd1 on cd1.Cadastro_ID = Cadastro_ID_de
						LEFT JOIN cadastros_dados cd2 on cd2.Cadastro_ID = Cadastro_ID_para
						LEFT JOIN financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
						WHERE ft.Titulo_ID is null
						$sqlCond
						and (fc.Tabela_Estrangeira = 'chamados')
						and (case fc.Tipo_ID when 45 then cwp.Valor_Venda_Unitario when 44 then cwp.Valor_Custo_Unitario else 0 end) > 0
						order by fc.Tipo_ID desc, cd2.Cadastro_ID";
			//echo $sql;
			$i=0;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				if ($rs[Nome_De]==""){$nomeDe = "N&atilde;o Informado";}else{$nomeDe = ($rs[Nome_De]);}
				if ($rs[Nome_Para]==""){$nomePara = "N&atilde;o Informado";}else{$nomePara = ($rs[Nome_Para]);}
				if ($rs[Tipo_ID]=="44"){ $descricaoConta = "<font color='red'><b>".$rs[Tipo]."</b></font> para <i>".$nomePara."</i>";}
				if ($rs[Tipo_ID]=="45"){ $descricaoConta = "<font color='blue'><b>".$rs[Tipo]."</b></font> de <i>".$nomePara."</i>";}
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$nomeDe."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 2px;float:right;' class='link link-chamado' workflow-id='$rs[ID_Ref]'>".$rs[ID_Ref]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$descricaoConta."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Produto]."</p>";
				if ($rs[Tipo_ID]=="44"){
					$total += $rs[Valor_Custo];
					$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs[Valor_Custo], 2, ',', '.')."</p>";
				}
				if ($rs[Tipo_ID]=="45"){
					$total += $rs[Valor_Venda];
					$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs[Valor_Venda], 2, ',', '.')."</p>";
				}
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 2px;float:right;'>".$rs[Data_Solicitacao]."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-faturar' id='produto-faturar-$i' value='".$rs['Financeiro_Produto_ID']."' name='produto-faturar[]' posicao='$i'></p>";
				$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-cancelar' id='produto-cancelar-$i' value='".$rs['Financeiro_Produto_ID']."' name='produto-cancelar[]' posicao='$i'></p>";
				// 45 a receber
			}
			$largura = "100%";
			$colunas = "8";
			$dados[colunas][tamanho][1] = "width='10%'";
			$dados[colunas][tamanho][2] = "width='5%'";
			$dados[colunas][tamanho][3] = "width='25%'";
			$dados[colunas][tamanho][4] = "width=''";
			$dados[colunas][tamanho][5] = "width='10%'";
			$dados[colunas][tamanho][6] = "width='100px'";
			$dados[colunas][tamanho][7] = "width='30px'";
			$dados[colunas][tamanho][8] = "width='30px'";

			$dados[colunas][titulo][1] = "Cadastro";
			$dados[colunas][titulo][2] = $_SESSION['objeto']." ID";
			$dados[colunas][titulo][3] = "Conta";
			$dados[colunas][titulo][4] = "Produto / Servi&ccedil;o";
			$dados[colunas][titulo][5] = "<p Style='margin:2px 5px 0 5px;float:right;'>Valor</p>";
			$dados[colunas][titulo][6] = "<p Style='margin:2px 5px 0 5px;float:right;'>Solicita&ccedil;&atilde;o</p>";
			$dados[colunas][titulo][7] = "<center><img src='../images/geral/disponivel.png' class='seleciona-todas-faturar' style='cursor:pointer' title='Aceitar Todas'></center>";
			$dados[colunas][titulo][8] = "<center><img src='../images/geral/indisponivel.png' class='seleciona-todas-cancelar' style='cursor:pointer' title='Negar Todas'></center>";

			echo "<div id='financeiro-container'>";
			if ($tipoGrupo=='45'){
				$titulo = $_SESSION['objeto']." - Aguardando Faturamento A RECEBER";
			}
			if ($tipoGrupo=='44'){
				$titulo = $_SESSION['objeto']." - Aguardando Faturamento A PAGAR";
			}
			$i++;
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:right;'><b>TOTAL: <b/></p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($total, 2, ',', '.')."</p>";
		}

		if ($origemFaturamento=='compras'){
			$sqlCond = "";
			if ($ordemCompraID!=''){ $sqlCond .= " and c.Ordem_Compra_ID = $ordemCompraID";}

			$titulo = "Compras - Aguardando Faturamento";
			$sql = "SELECT p.Codigo, p.Nome Produto, pv.Produto_Variacao_ID, Ordens_Compras_Produtos_ID,
						 cpf.Fornecedor_ID, cf.Nome Fornecedor, ce.Nome Cadastro, cpf.Quantidade_Aprovada, cpf.Valor_Aprovado, c.Ordem_Compra_ID, cpf.Ordem_Compra_Produto_ID
						FROM compras_ordem_compra c
						INNER JOIN compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
						INNER JOIN compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
						INNER JOIN produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
						INNER JOIN produtos_dados p on p.Produto_ID = pv.Produto_ID
						INNER JOIN compras_ordem_compras_finalizadas cpf on cpf.Ordem_Compra_ID = c.Ordem_Compra_ID and cpf.Ordem_Compra_Produto_ID = cp.Ordens_Compras_Produtos_ID
						INNER JOIN cadastros_dados cf on cf.Cadastro_ID = cpf.Fornecedor_ID
						LEFT JOIN cadastros_dados ce on ce.Cadastro_ID = c.Cadastro_ID
						LEFT JOIN financeiro_produtos fp on fp.Produto_Referencia_ID = cpf.Ordem_Compra_Produto_ID and cpf.Ordem_Compra_ID = fp.Chave_Estrangeira and fp.Tabela_Estrangeira = 'compras'
						WHERE c.Situacao_ID = 1 and cs.Situacao_ID = 65 and p.Situacao_ID = 1
						and fp.Financeiro_Produto_ID is null
						$sqlCond
						group by p.Codigo, p.Nome, pv.Produto_Variacao_ID, fp.Produto_Referencia_ID, cpf.Ordem_Compra_Produto_ID, cpf.Ordem_Compra_ID, fp.Chave_Estrangeira
						order by ce.Nome, ce.Cadastro_ID, cf.Nome, p.Nome, p.Codigo";

			//echo $sql;
			$i=0;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Cadastro]."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 2px;float:right;' class='link link-ordem-compra' ordem-compra-id='$rs[Ordem_Compra_ID]'>".$rs[Ordem_Compra_ID]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Fornecedor]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Produto]."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>".number_format($rs[Quantidade_Aprovada], 2, ',', '.')."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs[Valor_Aprovado], 2, ',', '.')."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format(($rs[Valor_Aprovado] * $rs[Quantidade_Aprovada]), 2, ',', '.')."</p>";
				$dados[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-faturar' id='produto-faturar-$i' value='".$rs['Ordem_Compra_Produto_ID']."' name='produto-faturar[]' fornecedor-id='".$rs['Cadastro_ID']."' posicao='$i'></p>";
				$dados[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-cancelar' id='produto-cancelar-$i' value='".$rs['Ordem_Compra_Produto_ID']."' name='produto-cancelar[]' posicao='$i'></p>";
				// 45 a receber
				$total += ($rs[Valor_Aprovado] * $rs[Quantidade_Aprovada]);
			}
			$largura = "100%";
			$colunas = "9";
			$dados[colunas][tamanho][1] = "width='10%'";
			$dados[colunas][tamanho][2] = "width='07%'";
			$dados[colunas][tamanho][3] = "width='25%'";
			$dados[colunas][tamanho][4] = "width=''";
			$dados[colunas][tamanho][5] = "width='10%'";
			$dados[colunas][tamanho][6] = "width='10%'";
			$dados[colunas][tamanho][7] = "width='10%'";
			$dados[colunas][tamanho][8] = "width='30px'";
			$dados[colunas][tamanho][9] = "width='30px'";

			$dados[colunas][titulo][1] = "Cadastro Pagamento";
			$dados[colunas][titulo][2] = "Ordem Compra ID";
			$dados[colunas][titulo][3] = "Fornecedor";
			$dados[colunas][titulo][4] = "Produto";
			$dados[colunas][titulo][5] = "<p Style='margin:2px 5px 0 5px;float:right;'>Quantidade</p>";
			$dados[colunas][titulo][6] = "<p Style='margin:2px 5px 0 5px;float:right;'>Valor Unit.</p>";
			$dados[colunas][titulo][7] = "<p Style='margin:2px 5px 0 5px;float:right;'>Total</p>";
			$dados[colunas][titulo][8] = "<center><img src='../images/geral/disponivel.png' class='seleciona-todas-faturar' style='cursor:pointer' title='Aceitar Todas'></center>";
			$dados[colunas][titulo][9] = "<center><img src='../images/geral/indisponivel.png' class='seleciona-todas-cancelar' style='cursor:pointer' title='Negar Todas'></center>";
			$i++;
			$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 5px 0 2px;float:right;'><b>TOTAL: <b/></p>";
			$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($total, 2, ',', '.')."</p>";

		}

		if ($origemFaturamento=='envios'){
			$sqlCond = "";
			if ($ordemCompraID!=''){ $sqlCond .= " and c.Ordem_Compra_ID = $ordemCompraID";}

			$titulo = "Envios - Aguardando Faturamento - A Pagar";
			$sql = "SELECT ew.Workflow_ID as Envio_ID, ce.Nome as Empresa, ccf.Nome as Cliente_Fornecedor, ff.Cliente_Fornecedor_ID as Fornecedor_ID, (ff.Quantidade * ff.Valor_Unitario) as Valor_Total from envios_workflows ew
					INNER JOIN financeiro_faturar ff on ff.Tabela_Estrangeira = 'envios' and ff.Chave_Estrangeira = ew.Workflow_ID and ff.Situacao_ID = 1
					INNER JOIN cadastros_dados ce on ce.Cadastro_ID = ff.Empresa_ID
					INNER JOIN cadastros_dados ccf on ccf.Cadastro_ID = ff.Cliente_Fornecedor_ID
					LEFT JOIN financeiro_contas fc on fc.Tabela_Estrangeira = 'envios' and fc.Chave_Estrangeira = ew.Workflow_ID
					LEFT JOIN financeiro_produtos fp on fp.Tabela_Estrangeira = 'envios' and fp.Chave_Estrangeira = ew.Workflow_ID
					WHERE fc.Conta_ID is null and fp.Conta_ID is null
					order by ce.Nome, ce.Cadastro_ID, ccf.Nome, ccf.Cadastro_ID";
			//echo $sql;
			$i=0;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Empresa]."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 2px;float:right;' class='link link-envios' envio-id='$rs[Envio_ID]'>".$rs[Envio_ID]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Cliente_Fornecedor]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:left;'>Transporte</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($rs[Valor_Total], 2, ',', '.')."</p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-faturar' id='produto-faturar-$i' value='".$rs['Envio_ID']."' fornecedor-id='".$rs['Fornecedor_ID']."' name='produto-faturar[]' posicao='$i'></p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-cancelar' id='produto-cancelar-$i' value='".$rs['Envio_ID']."' name='produto-cancelar[]' posicao='$i'></p>";
				// 46 a pagar
				$total += $rs[Valor_Total];
			}
			$largura = "100%";
			$colunas = "7";
			$dados[colunas][tamanho][1] = "width='10%'";
			$dados[colunas][tamanho][2] = "width='07%'";
			$dados[colunas][tamanho][3] = "width='25%'";
			$dados[colunas][tamanho][4] = "width=''";
			$dados[colunas][tamanho][5] = "width='10%'";
			$dados[colunas][tamanho][6] = "width='30px'";
			$dados[colunas][tamanho][7] = "width='30px'";

			$dados[colunas][titulo][1] = "Cadastro Pagamento";
			$dados[colunas][titulo][2] = "Envio ID";
			$dados[colunas][titulo][3] = "Transportadora";
			$dados[colunas][titulo][4] = "Servi&ccedil;o";
			$dados[colunas][titulo][5] = "<p Style='margin:2px 5px 0 5px;float:right;'>Valor</p>";
			$dados[colunas][titulo][6] = "<center><img src='../images/geral/disponivel.png' class='seleciona-todas-faturar' style='cursor:pointer' title='Aceitar Todas'></center>";
			$dados[colunas][titulo][7] = "<center><img src='../images/geral/indisponivel.png' class='seleciona-todas-cancelar' style='cursor:pointer' title='Negar Todas'></center>";
			$i++;
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:right;'><b>TOTAL: <b/></p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($total, 2, ',', '.')."</p>";
		}


		if ($origemFaturamento=='comissao'){
			$sqlCond = "";
			if ($ordemCompraID!=''){ $sqlCond .= " and c.Ordem_Compra_ID = $ordemCompraID";}

			$titulo = "Comissionamento - Aguardando Faturamento - A Pagar";


			$sql = "SELECT cd.Nome, Descricao, opp.Quantidade, opp.Valor_Custo_Unitario, opp.Valor_Venda_Unitario from orcamentos_propostas op
					INNER JOIN orcamentos_propostas_produtos opp on op.Proposta_ID = opp.Proposta_ID
					INNER JOIN orcamentos_workflows ow on ow.Workflow_ID = op.Workflow_ID
					INNER JOIN cadastros_dados cd on cd.Cadastro_ID = ow.Representante_ID
					WHERE opp.Situacao_ID = 2 and op.Situacao_ID = 1
					and op.Status_ID = 121";
			//echo $sql;
			$i=0;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Nome]."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 2px;float:right;'".$rs[Descricao]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:left;'>".$rs[Quantidade]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format(($rs[Quantidade] * $rs[Valor_Venda_Unitario] ), 2, ',', '.')."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-faturar' id='produto-faturar-$i' value='".$rs['Envio_ID']."' fornecedor-id='".$rs['Fornecedor_ID']."' name='produto-faturar[]' posicao='$i'></p>";
				$dados[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'><input type='checkbox' class='produto-cancelar' id='produto-cancelar-$i' value='".$rs['Envio_ID']."' name='produto-cancelar[]' posicao='$i'></p>";
				// 46 a pagar
				$total += $rs[Valor_Total];
			}
			$largura = "100%";
			$colunas = "7";
			$dados[colunas][tamanho][1] = "width='10%'";
			$dados[colunas][tamanho][2] = "width='07%'";
			$dados[colunas][tamanho][3] = "width='25%'";
			$dados[colunas][tamanho][4] = "width=''";
			$dados[colunas][tamanho][5] = "width='10%'";
			$dados[colunas][tamanho][6] = "width='30px'";
			$dados[colunas][tamanho][7] = "width='30px'";

			$dados[colunas][titulo][1] = "Cadastro Pagamento";
			$dados[colunas][titulo][2] = "Produto / Serviço";
			$dados[colunas][titulo][3] = "";
			$dados[colunas][titulo][4] = "<p Style='margin:2px 5px 0 5px;float:right;'>Valor</p>";
			$dados[colunas][titulo][5] = "<center><img src='../images/geral/disponivel.png' class='seleciona-todas-faturar' style='cursor:pointer' title='Aceitar Todas'></center>";
			$dados[colunas][titulo][6] = "<center><img src='../images/geral/indisponivel.png' class='seleciona-todas-cancelar' style='cursor:pointer' title='Negar Todas'></center>";
			$i++;
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 2px;float:right;'><b>TOTAL: <b/></p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 2px;float:right;'>R$ ".number_format($total, 2, ',', '.')."</p>";
		}



		echo "	<div class='titulo-container'>
					<div class='titulo'>
						<p style='margin-top:2px;'>
							$titulo
						</p>
					</div>
					<div class='conteudo-interno'>";
		if($i==1){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum registro aguardando faturamento encontrado</p>";
		}
		else {
			//geraTabela($largura,$colunas,$dados);
			geraTabela($largura,$colunas,$dados, null, 'financeiro-aguardando-faturamento', 2, 2, "","");
		}
		echo "		</div>
				</div>";
		echo "
			</div>";


	//}
?>