<?php
	function converteDataJavascript($data){
		$data = implode(',',array_reverse(explode('/',$data)));
		return "new Date($data)";
	}

	function optionValueGrupoAnoFinanceiro($selecionado){
		$rs = mpress_query("select distinct YEAR(ft.Data_Vencimento) as ano from financeiro_titulos ft where YEAR(ft.Data_Vencimento) > 1900 order by YEAR(ft.Data_Vencimento) desc");
		while($row = mpress_fetch_array($rs)){
			if ($selecionado==$row['ano']){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$row['ano']."' $seleciona>".($row['ano'])."</option>";
		}
		return $optionValue;
	}
	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
	}
	if ($_POST){
		$dataInicioVencimento = $_POST['data-inicio-vencimento'];
		$dataFimVencimento = $_POST['data-fim-vencimento'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-regionais']); $i++){
			$regionais .= $virgula.$_POST['localiza-regionais'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
			$cadastrosDe .= $virgula.$_POST['localiza-cadastro-de'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-situacao-conta']); $i++){
			$situacoes .= $virgula.$_POST['localiza-situacao-conta'][$i];
			$virgula = ",";
		}
	}
	else{
		$dataInicioVencimento = "01/".date("m/Y");
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFimVencimento = $ultimo_dia."/".date("m/Y");

		$filtroMes = date("n");
		$filtroAno = date("Y");
		$classeEsconde = "esconde";
		$situacoes = "48,49";
	}

?>
		<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_demonstracao_financeira'>
		<input type='hidden' id='conteudo-relatorio' name='conteudo-relatorio' value=''>
		<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo' style="min-height:25px">
					<p style="margin-top:2px;">
					Filtros de Pesquisa
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class="titulo-secundario" style='float:left;width:20%' >
						<div class='titulo-secundario' style='float:left;width:98%;'>
							<p><b>Data Vencimento:</b></p>
							<div style='width:45%;float:left;'>
								<p><input type='text' name='data-inicio-vencimento' id='data-inicio-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicioVencimento; ?>'>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:45%;float:left;'>
								<p><input type='text' name='data-fim-vencimento' id='data-fim-vencimento' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFimVencimento; ?>'></p>
							</div>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:15%'>
						<p><b>Situa&ccedil;&atilde;o:</b></p>
						<p><select name='localiza-situacao-conta[]' id='localiza-situacao-conta' multiple Style='height:58px'><?php echo optionValueGrupoMultiplo(29, $situacoes);?></select></p>
					</div>
					<div class='titulo-secundario' style='float:left;width:15%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<p><b>Exibir informações:</b></p>
							<p>
								<select id='exibir-informacoes' name='exibir-informacoes' style='width:92%'>
									<option value='ger' <?php echo verificaSelected($_POST['exibir-informacoes'],'ger');?>>Geral</option>
									<option value='cad' <?php echo verificaSelected($_POST['exibir-informacoes'],'cad');?>><?php echo $configFinanceiro['cadastro'];?></option>
									<option value='reg' <?php echo verificaSelected($_POST['exibir-informacoes'],'reg');?>>Regionais</option>
								</select>
							</p>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:40%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<div class='esconde div-filtros div-rel-reg'>
								<p>Regionais:</p>
								<p><select name='localiza-regionais[]' id='localiza-regionais' multiple Style='height:90px'><?php echo optionValueGrupoMultiplo(26, $regionais,'');?></select></p>
							</div>
							<div class='esconde div-filtros div-rel-cad'>
								<p>Cadastro:</p>
								<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple Style='height:90px'><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
							</div>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:10%;margin-top:15px'>
						<div style='width:100%;float:left;'>
							<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
						</div>
						<div style='width:100%;float:left; height:29px;margin-top:3px'>
							<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel' style="float:left;" title="Gerar Excel"></div>&nbsp;
							<div class="btn-imprimir <?php echo $classeEsconde; ?>" id='botao-imprimir' style="float:left;" title="Imprimir"></div>&nbsp;
						</div>
					</div>
					<!--
					<div class='titulo-secundario' style='float:left;width:20%;margin-top:15px'>
						<div style='width:50%;float:left;'>
							<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel' style="float:right;" title="Gerar Excel"></div>&nbsp;
							<div class="btn-imprimir <?php echo $classeEsconde; ?>" id='botao-imprimir' style="float:right;" title="Imprimir"></div>&nbsp;
						</div>
						<div style='width:50%;float:left;'>
							<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
						</div>
					</div>
					-->
				</div>
			</div>
		</div>
		<input type='hidden' id='data-inicial' name='data-inicial' value='<?php echo $dataInicioVencimento;?>'>
		<input type='hidden' id='data-final' name='data-final' value='<?php echo $dataFimVencimento;?>'>
		<input type='hidden' id='tipo-operacao' name='tipo-operacao' value='44,45,46'>

<?php
	if($_POST){
		$html = "<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>
					<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
					<tr class=''>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='center'  height='53' align='center'>DEMONSTRA&Ccedil;&Atilde;O FINANCEIRA <br> Per&iacute;odo: ".$_POST['data-inicio-vencimento']." a ".$_POST['data-fim-vencimento']." </td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='middle' width='210' height='53' align='right'><img src='$caminhoSistema/images/topo/logo.png';?></td>
					</tr>";

		$dataInicial = implode('-',array_reverse(explode('/',$dataInicial)))." 00:00";
		$dataFinal = implode('-',array_reverse(explode('/',$dataFinal)))." 23:59";

		if ($cadastrosDe!=""){ $sqlCond .= " and cd.Cadastro_ID IN($cadastrosDe)";}
		if ($regionais!=""){ $sqlCond .= " and cd.Centro_Custo_ID IN($regionais)";}
		if ($situacoes != ""){ $sqlCond .= " and ft.Situacao_Pagamento_ID IN ($situacoes)";}

		if(($dataInicioVencimento!="")||($dataFimVencimento!="")){
			$dataInicioVencimento = implode('-',array_reverse(explode('/',$dataInicioVencimento)));
			if ($dataInicioVencimento=="") $dataInicioVencimento = "0000-00-00"; $dataInicioVencimento .= " 00:00";
			$dataFimVencimento = implode('-',array_reverse(explode('/',$dataFimVencimento)));
			if ($dataFimVencimento=="") $dataFimVencimento = "2100-01-01"; $dataFimVencimento .= " 23:59";
			$sqlCond .= " and ft.Data_Vencimento between '$dataInicioVencimento' and '$dataFimVencimento' ";
		}

		$sql = "select fc.Tipo_ID, SUM(ft.Valor_Titulo) as Valor_Titulo, SUM(ft.Valor_Pago) as Valor_Pago,
					(CASE fc.Tipo_ID WHEN 44 THEN 2 WHEN 45 THEN 1 WHEN 46 THEN 3 END) as Ordem
					from financeiro_contas fc
					inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
					inner join cadastros_dados cd on fc.Cadastro_ID_de = cd.Cadastro_ID
					where fc.Tipo_ID in (44,45)
					$sqlCond
					group by fc.Tipo_ID order by Ordem";
		//echo $sql;
		$linha = "<tr>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='top'>&nbsp;</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='top'>&nbsp;</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='top'>&nbsp;</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='top'>&nbsp;</td>
				  </tr>";
		$i = 0;
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($rs[Tipo_ID]==44) $saidas += $rs[Valor_Pago];
			if ($rs[Tipo_ID]==45) $entradas += $rs[Valor_Pago];
		}
		$html .= "<tr>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='top'>
						TOTAL GERAL DE ENTRADAS
						<div class='btn-expandir btn-expandir-retrair-operacoes' style='float:right;' title='Expandir' nome-bloco='bloco-entrada' tipo-operacao='45'></div>
					</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' align='center' width='30'>(A)</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='middle' align='right'>R$ ".number_format($entradas, 2, ',', '.')."</td>
				</tr>
				<tr><td class='tabela-fundo-escuro-titulo' colspan='4' id='bloco-entrada'>&nbsp;</td></tr>";

		$html .= "<tr>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='top'>
						TOTAL GERAL DE SA&Iacute;DAS
						<div class='btn-expandir btn-expandir-retrair-operacoes' style='float:right;' title='Expandir' nome-bloco='bloco-saida' tipo-operacao='44'></div>
					</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' align='center' width='30'>(B)</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='middle' align='right'>R$ ".number_format($saidas, 2, ',', '.')."</td>
				</tr>
				<tr><td class='tabela-fundo-escuro-titulo' colspan='4' id='bloco-saida'>&nbsp;</td></tr>";

		$html .= "<tr>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='top'>SALDO DO PER&Iacute;ODO EM AN&Aacute;LISE </td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' align='center'>(A-B)</td>
					<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='middle' align='right'>R$ ".number_format(($entradas-$saidas), 2, ',', '.')."</td>
				</tr>
				$linha";
		if (($cadastrosDe!="")||($regionais!="")){
			$sql = "select
						(select SUM(ft.Valor_Titulo)
							from financeiro_contas fc
							inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
							inner join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_de
							where fc.Tipo_ID = 46 $sqlCond)
							as Valor_Transferencia_Saida,
						(select SUM(ft.Valor_Titulo)
							from financeiro_contas fc
							inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
							inner join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_Para
							where fc.Tipo_ID = 46 $sqlCond)
							as Valor_Transferencia_Entrada";
			//echo $sql;
			$query = mpress_query($sql);
			if($rs = mpress_fetch_array($query)){
				$transferenciasS += $rs[Valor_Transferencia_Saida];
				$transferenciasE += $rs[Valor_Transferencia_Entrada];
			}
			$html .= "<tr>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='top'> TOTAL GERAL DE TRANSFER&Ecirc;NCIAS ENTRADAS</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' align='center' width='30'>(C)</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='middle' align='right'>R$ ".number_format($transferenciasE, 2, ',', '.')."</td>
					</tr>
					<tr><td class='tabela-fundo-escuro-titulo' colspan='4'>&nbsp;</td></tr>";

			$html .= "<tr>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='2' valign='top'>TOTAL GERAL DE TRANSFER&Ecirc;NCIAS SA&Iacute;DAS</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' align='center' width='30'>(D)</td>
						<td class='tabela-fundo-escuro-titulo' styletitulo colspan='1' valign='middle' align='right'>R$ ".number_format($transferenciasS, 2, ',', '.')."</td>
					</tr>
					<tr><td class='tabela-fundo-escuro-titulo' colspan='4'>&nbsp;</td></tr>";
		}

		$html .= "</table>
				</div>";
		echo $html;
	}

?>
