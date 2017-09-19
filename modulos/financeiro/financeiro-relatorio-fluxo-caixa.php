<?php
	include('functions.php');
	global $configFinanceiro;

	$contEmpresas = verificaNumeroEmpresas();
	if ($contEmpresas==1){
		$classeEmpresasEsconde = " esconde ";
	}

	if ($_POST){
		$dataInicial = $_POST['data-inicial'];
		$dataFinal = $_POST['data-final'];
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-regional']); $i++){
			$regionais .= $virgula.$_POST['localiza-regional'][$i];
			$virgula = ",";
		}
		$virgula = "";
		for($i = 0; $i < count($_POST['localiza-cadastro-de']); $i++){
			$cadastrosDe .= $virgula.$_POST['localiza-cadastro-de'][$i];
			$virgula = ",";
		}
		$exibirInformacoes = $_POST['exibir-informacoes'];
		$agruparPor = $_POST['agrupar-por'];

	}
	else{
		$dataInicial = "01/".date("m/").(date("Y")-1);
		$exibirInformacoes = 'mensal';
		$mes = date("m");
		$ano = date("Y");
		$ultimo_dia = date("t", mktime(0,0,0,$mes,'01',$ano));
		$dataFinal = $ultimo_dia."/".date("m/Y");
	}

?>
		<input type='hidden' id='nome-relatorio' name='nome-relatorio' value='Relatorio_fluxo_caixa'>
		<input type='hidden' id='conteudo-relatorio' name='conteudo-relatorio' value=''>
		<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo' style="min-height:25px">
					<p style="margin-top:2px;">
					Filtros de Pesquisa
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left;width:15%'>
						<p>Visualiza&ccedil;&atilde;o Per&iacute;odo:</p>
						<p>
							<select id='exibir-informacoes' name='exibir-informacoes' style='width:92%'>
								<option value='anual' <?php echo verificaSelected($exibirInformacoes,'anual');?>>Anual</option>
								<option value='mensal' <?php echo verificaSelected($exibirInformacoes,'mensal');?>>Mensal</option>
								<option value='diario' <?php echo verificaSelected($exibirInformacoes,'diario');?>>Diário</option>
							</select>
						</p>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<p style='margin-top:15px'>Agrupar por:</p>
							<p>
								<select id='agrupar-por' name='agrupar-por' style='width:92%'>
									<option value='ger' <?php echo verificaSelected($agruparPor,'ger');?>>Geral</option>
									<option value='cad' <?php echo verificaSelected($agruparPor,'cad');?>><?php echo $configFinanceiro['cadastro'];?></option>
									<option value='reg' <?php echo verificaSelected($agruparPor,'reg');?>>Regionais</option>
								</select>
							</p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:15%'>
						<p>Data Pagamento:</p>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='data-inicial' id='data-inicial' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataInicial; ?>'>&nbsp;&nbsp;</p>
						</div>
						<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
						<div style='width:43%;float:left;'>
							<p><input type='text' name='data-final' id='data-final' class='formata-data' style='width:92%' maxlength='10' value='<?php echo $dataFinal; ?>'></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:25%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
						<p>Regionais:</p>
						<p><select name='localiza-regional[]' id='localiza-regional' multiple Style='height:90px'><?php echo optionValueGrupoMultiplo(26, $regionais,'');?></select></p>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:25%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
						<div id='div-cadastros'>
						<p>Cadastros:</p>
						<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de' multiple Style='height:90px'><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
						</div>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:20%;margin-top:15px'>
						<div style='width:50%;float:left;'>
							<div class="btn-excel <?php echo $classeEsconde; ?>" id='botao-salvar-excel-fluxo' style="float:right;" title="Gerar Excel"></div>&nbsp;
						</div>
						<div style='width:50%;float:left;'>
							<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
<?php
	if(($_POST)&&($dataInicial!="")&&($dataFinal!="")){

		if ($cadastrosDe != ""){ $sqlCondCadastro .= " and cd.Cadastro_ID IN ($cadastrosDe)";}
		if ($regionais != ""){ $sqlCondCadastro .= " and cd.Centro_Custo_ID IN ($regionais)";}

		if(($dataInicial!="")||($dataFinal!="")){
			$dataInicial = implode('-',array_reverse(explode('/',$dataInicial)));
			$dataInicialAux = $dataInicial;
			if ($dataInicial=="") $dataInicial = "0000-00-00"; $dataInicial .= " 00:00";
			$dataFinal = implode('-',array_reverse(explode('/',$dataFinal)));
			$dataFinalAux = $dataFinal;
			if ($dataFinal=="") $dataFinal = "2100-01-01"; $dataFinal .= " 23:59";
			$sqlCond .= " and ft.Data_Pago between '$dataInicial' and '$dataFinal' ";
		}
		if(strtotime($dataInicialAux) < strtotime($dataFinalAux)){
			$qtdeCol = 1;

			if ($exibirInformacoes=='anual'){
				$agrupador = " year(ft.Data_Pago) ";
				while (strtotime($dataInicialAux) <= strtotime($dataFinalAux)){
					$arrayDatas[date('Y', strtotime($dataInicialAux))] = date('Y', strtotime($dataInicialAux));
					$dataInicialAux = date('Y-m-d', strtotime($dataInicialAux. '+ 1 year'));
					$qtdeCol++;
				}
			}

			if ($exibirInformacoes=='mensal'){
				$agrupador = "  DATE_FORMAT(ft.Data_Pago, '%m/%Y') ";
				while (strtotime($dataInicialAux) <= strtotime($dataFinalAux)){
					$arrayDatas[date('m/Y', strtotime($dataInicialAux))] = "";
					$dataInicialAux = date('Y-m-d', strtotime($dataInicialAux. '+ 1 month'));
					$qtdeCol++;
				}
			}
			if ($exibirInformacoes=='diario'){
				$agrupador = "  DATE_FORMAT(ft.Data_Pago, '%d/%m/%Y') ";
				while (strtotime($dataInicialAux) <= strtotime($dataFinalAux)){
					$arrayDatas[date('d/m/Y', strtotime($dataInicialAux))] = "";
					$dataInicialAux = date('Y-m-d', strtotime($dataInicialAux. '+ 1 days'));
					$qtdeCol++;
				}
			}

			if ($agruparPor=="ger"){
				$sql = "select 1 as ID, 'Geral' as Descricao";
				$sql2 = "select 1 as ID, fc.Tipo_ID, coalesce(tc.Descr_Tipo, 'Não Informado') as Tipo_Conta, fc.Tipo_Conta_ID as Tipo_Conta_ID, sum(ft.Valor_Pago) as Valor_Pago, sum(ft.Valor_Titulo) as Valor_Titulo, $agrupador as Agrupador
									from financeiro_contas fc
									inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
									left join tipo tc on tc.Tipo_ID = fc.Tipo_Conta_ID
									where fc.Tipo_ID in (44,45)
									$sqlCond $sqlCondCadastro
									group by fc.Tipo_Conta_ID, tc.Descr_Tipo,".$agrupador."
									order by ".$agrupador;
			}
			if ($agruparPor=="cad"){
				$sql = "select cd.Cadastro_ID as ID, cd.Nome as Descricao from cadastros_dados cd where cd.Situacao_ID in (1,3) $sqlCondCadastro";
				$sql2 = "select cd.Cadastro_ID as ID, fc.Tipo_ID, coalesce(tc.Descr_Tipo, 'Não Informado') as Tipo_Conta, fc.Tipo_Conta_ID as Tipo_Conta_ID, sum(ft.Valor_Pago) as Valor_Pago, sum(ft.Valor_Titulo) as Valor_Titulo, $agrupador as Agrupador
									from financeiro_contas fc
									inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
									inner join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_de
									left join tipo tc on tc.Tipo_ID = fc.Tipo_Conta_ID
									where fc.Tipo_ID in (44,45)
									$sqlCond $sqlCondCadastro
									group by fc.Tipo_Conta_ID, tc.Descr_Tipo,".$agrupador.", cd.Cadastro_ID
									order by ".$agrupador;
				//echo $sql2;
			}
			if ($agruparPor=="reg"){
				$sql = "select distinct Tipo_ID as ID, Descr_Tipo as Descricao from cadastros_dados cd inner join tipo on Tipo_ID = cd.Centro_Custo_ID where Tipo_Grupo_ID = 26 $sqlCondCadastro";
				$sql2 = "select cd.Centro_Custo_ID as ID, fc.Tipo_ID, coalesce(tc.Descr_Tipo, 'Não Informado') as Tipo_Conta, fc.Tipo_Conta_ID as Tipo_Conta_ID, sum(ft.Valor_Pago) as Valor_Pago, sum(ft.Valor_Titulo) as Valor_Titulo, $agrupador as Agrupador
									from financeiro_contas fc
									inner join financeiro_titulos ft on fc.Conta_ID = ft.Conta_ID
									inner join cadastros_dados cd on cd.Cadastro_ID = fc.Cadastro_ID_de
									left join tipo tc on tc.Tipo_ID = fc.Tipo_Conta_ID
									where fc.Tipo_ID in (44,45)
									$sqlCond $sqlCondCadastro
									group by fc.Tipo_Conta_ID, tc.Descr_Tipo,".$agrupador.", cd.Centro_Custo_ID
									order by ".$agrupador;
			}
			//echo $sql2;
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				$dadosAux[$rs[ID]][Descricao] = $rs[Descricao];
			}
			$query = mpress_query($sql2);
			while($rs = mpress_fetch_array($query)){
				if ($rs[Tipo_ID]==44){
					$dadosAux[$rs[ID]][Saidas][$rs[Tipo_Conta_ID]][Tipo_Conta] = $rs[Tipo_Conta];
					$dadosAux[$rs[ID]][Saidas][$rs[Tipo_Conta_ID]][Valor_Pago][$rs[Agrupador]] = $rs[Valor_Pago];
					$dadosTotais[$rs[ID]][Saidas][Valor_Total][$rs[Agrupador]] = $dadosTotais[$rs[ID]][Saidas][Valor_Total][$rs[Agrupador]] + $rs[Valor_Pago];
				}
				if ($rs[Tipo_ID]==45){
					$dadosAux[$rs[ID]][Entradas][$rs[Tipo_Conta_ID]][Tipo_Conta] = $rs[Tipo_Conta];
					$dadosAux[$rs[ID]][Entradas][$rs[Tipo_Conta_ID]][Valor_Pago][$rs[Agrupador]] = $rs[Valor_Pago];
					$dadosTotais[$rs[ID]][Entradas][Valor_Total][$rs[Agrupador]] = $dadosTotais[$rs[ID]][Entradas][Valor_Total][$rs[Agrupador]] + $rs[Valor_Pago];
				}
			}

			/*
			echo "<pre>";
			print_r($dadosAux);
			echo "</pre>";
			*/


			$html = "<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>";
			foreach($dadosAux as $cadastroID => $dado){
				$html .= "<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque align='center' colspan='$qtdeCol' height='35px'><b>".$dado[Descricao]."</b></td>
						  </tr>
						  <tr>
						  	<td class='tabela-fundo-escuro' styledestaque align='center' style='width:250px;'>&nbsp;</td>";
				foreach($arrayDatas as $chave  => $datas){$html .= "<td class='tabela-fundo-escuro' stylenormal align='center'><b>".$chave."</b></td>";}

				/******************** INICIO ENTRADAS ********************/
				$html .= "<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque align='center' colspan='$qtdeCol'><b>Entradas</b></td>
						  </tr>";
				$contE=0;
				foreach($dado[Entradas] as $chaveE  => $entrada){
					$contE++;
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'><b>".$entrada[Tipo_Conta]."</b></td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'>".number_format($entrada[Valor_Pago][$chaveData], 2, ',', '.')."</td>";}
					$html .= "</tr>";
				}
				if ($contE==0){
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'>&nbsp;</td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'>0,00</td>";}
					$html .= "</tr>";
				}else{
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'><b>Totais</b></td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'><b>".number_format($dadosTotais[$cadastroID][Entradas][Valor_Total][$chaveData], 2, ',', '.')."</b></td>";}
					$html .= "</tr>";
				}
				/******************** FIM ENTRADAS ***********************/

				/******************** INICIO SAIDAS ********************/
				$html .= "<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque align='center' colspan='$qtdeCol'><b>Sa&iacute;das</b></td>
						  </tr>";
				$contS=0;
				foreach($dado[Saidas] as $chaveD  => $saida){
					$contS++;
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'><b>".$saida[Tipo_Conta]."</b></td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'>".number_format($saida[Valor_Pago][$chaveData], 2, ',', '.')."</td>";}
					$html .= "</tr>";
				}
				if ($contS==0){
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'>&nbsp;</td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'>0,00</td>";}
					$html .= "</tr>";
				}else{
					$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'><b>Totais</b></td>";
					foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'><b>".number_format($dadosTotais[$cadastroID][Saidas][Valor_Total][$chaveData], 2, ',', '.')."</b></td>";}
					$html .= "</tr>";
				}

				/******************** FIM SAIDAS ***********************/


				/******************** INICIO SALDO *********************/
				$html .= "<tr>
							<td class='tabela-fundo-escuro-titulo' styledestaque align='center' colspan='$qtdeCol'><b>Saldo</b></td>
						  </tr>";
				$html .= "<tr><td class='tabela-fundo-escuro-titulo' styledestaque align='left' colspan='1'><b>Saldo Totais</b></td>";
				foreach($arrayDatas as $chaveData => $datas){$html .= "<td class='tabela-fundo-escuro' style='padding-right:8px' stylenormal align='right'><b>".number_format(($dadosTotais[$cadastroID][Entradas][Valor_Total][$chaveData]-$dadosTotais[$cadastroID][Saidas][Valor_Total][$chaveData]), 2, ',', '.')."</b></td>";}
				$html .= "</tr>";
				/******************** FIM SALDO ************************/



			}
			$html .= "</table>";
			echo $html;

			$colunas1 = intval($qtdeCol/2);
			$colunas2 = intval($qtdeCol/2);
			if($qtdeCol%2!=0){
				$colunas1++;
			}

			$html ="<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:2px;' cellpadding='2' cellspacing='2' align='center'>
						<tr>
							<td class='tabela-fundo-escuro-titulo' styletitulo colspan='".$colunas1."' valign='top' width='518' height='53' >RELAT&Oacute;RIO FLUXO DE CAIXA</td>
							<td class='tabela-fundo-escuro-titulo' styletitulo colspan='".$colunas2."' valign='middle' align='right' width='210' height='53'><img src='$caminhoSistema/images/topo/logo.png';?></td>
						</tr>
					</table>".$html;
			$html = str_replace("styletitulo"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:15px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
			$html = str_replace("styledestaque"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;border:0px solid #ccc;font-weight:bold;background-color: #DCDCDC;'", $html);
			$html = str_replace("stylenormal"," style='vertical-align:text-top;white-space:nowrap; font-family:arial;font-size:12px;solid #fff;'", $html);
			$_SESSION["session-conteudo-relatorio"] = $html;
		}
	}
?>
