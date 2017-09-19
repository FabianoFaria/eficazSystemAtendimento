		<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo' style="min-height:25px">
					<p style="margin-top:2px;">
					Filtros de Pesquisa
					</p>
				</div>
				<div class='conteudo-interno'>
					<div class="titulo-secundario" style='float:left;width:10%' >
						<div style='width:100%;float:left;'>
							<p Style='margin-top:5px;'><?php echo montaCheckboxGroupo(27, serialize($_POST['check-tipo-grupo-27']), " and Tipo_ID in(44,45) ");?></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:30%'>
						<div class='titulo-secundario' style='float:left;width:98.5%'>
							<p>Situa&ccedil;&atilde;o:</p>
							<p><select name='localiza-situacao-conta[]' id='localiza-situacao-conta'><?php echo optionValueGrupoMultiplo(29, $situacoes);?></select></p>
						</div>
					</div>
					<div class='titulo-secundario' style='float:left;width:50%'>
						<div class='<?php echo $classeEmpresasEsconde; ?>'>
							<p>Cadastros:</p>
							<p><select name='localiza-cadastro-de[]' id='localiza-cadastro-de'><?php echo optionValueEmpresasMultiplo($cadastrosDe);?></select></p>
						</div>
						&nbsp;
					</div>
					<div class='titulo-secundario' style='float:left;width:10%;margin-top:15px'>
						<div style='width:100%;float:left;'>
							<input type='button' value='Pesquisar' id='botao-pesquisar-relatorio-cadastros' style='width:92%;margin-right:2px'/>&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>


		<table width='100%' border='0' cellspacing='0' cellpadding='1' Style='margin-top:5px;'>
			<tr>
				<td width='50%' valign='top'>
					<table width='100%' border='0' cellpadding='1' cellspacing='0' class='txtAgenda'>
						<tr>
							<td colspan='7' height='39' Style='border:1px solid #DCDCDC;background:#f6f6f6;padding:2px;'>
								<table width='100%' height='39' border='0' cellspacing='0' cellpadding='1'>
								<tr>
										<td width='50' align='center'><a href='#' attr-mes-ano='<?php echo mesAno(-1)?>' class='calendario-mes'><img src='<?php echo $caminhoSistema?>/images/geral/anterior.png' alt='anterior' name='anterior' width='20' height='20' id='anterior' style='margin-top:3px;'></a></td>
										<td align='center' Style='font-size:16px'><b><?php echo getDiaMesAno()?></b></td>
										<td width='50' align='center'><a href='#' attr-mes-ano='<?php echo mesAno(1)?>'  class='calendario-mes'><img src='<?php echo $caminhoSistema?>/images/geral/proximo.png' alt='proximo' name='proximo' width='20' height='20' id='proximo' Style='margin-top:3px;'></a></td>
									</tr>
								</table>
							</td>
						</tr>
						<tr>
							<td><?php echo mostraCalendario(get_date(),$responsavelID, $solicitanteID, $situacaoID, $tipoTarefaID, $aberto, $fechado);?></td>
						</tr>
					</table>
				</td>
			</tr>
		</table>
		<input type='hidden' name='mes' id='mes-calendario' value='<?php echo $_POST[mes]; ?>'>
		<input type='hidden' name='ano' id='ano-calendario' value='<?php echo $_POST[ano]; ?>'>
		<input type='hidden' name='workflow-id' id='workflow-id'>
<?php
	function getDiaMesAno(){
		$data = get_date();
		if($_POST['dia'] != "") $dia = $_POST['dia']; else $dia = date('d');
		return mostraMeses($data[mes])." DE ".$data['ano'];
	}

	function get_date(){
		$data[dia] = $_POST['dia'];
		$data[mes] = $_POST['mes'];
		$data[ano] = $_POST['ano'];
		if(strlen($data[dia])==1)$data[dia]="0".$data[dia];
		if($data[mes]=="") $data[mes] = date('m');
		if($data[ano]=="") $data[ano] = date('Y');
		return $data;
	}
	function mostraSemanas(){
		$semana[0] = 'Dom';
		$semana[1] = 'Seg';
		$semana[2] = 'Ter';
		$semana[3] = 'Qua';
		$semana[4] = 'Qui';
		$semana[5] = 'Sex';
		$semana[6] = 'Sab';
		for($i=0;$i<7;$i++ ){
			if ($i==6){
				$classeRight = " bordaRight";
			}
			$diasSemana .=  "<td align='center' valign='middle' class='bordaSemanaLatlInf bordaSemanaLeft $classeRight' width='14.28%'>".$semana[$i]."</td>";
		}
		return $diasSemana;
	}
	function mostraMeses($entrada){
		$mes['01'] = 'JANEIRO';
		$mes['02'] = 'FEVEREIRO';
		$mes['03'] = 'MAR&Ccedil;O';
		$mes['04'] = 'ABRIL';
		$mes['05'] = 'MAIO';
		$mes['06'] = 'JUNHO';
		$mes['07'] = 'JULHO';
		$mes['08'] = 'AGOSTO';
		$mes['09'] = 'SETEMBRO';
		$mes['10'] = 'OUTUBRO';
		$mes['11'] = 'NOVEMBRO';
		$mes['12'] = 'DEZEMBRO';
		return $mes[$entrada];
	}

	function mesAno($tipo){
		$mesAno = get_date();
		$data['mes'] = $mesAno['mes'];
		$data['ano'] = $mesAno['ano'];
		$data['mes'] = $data['mes']+$tipo;
		if($data['mes']==13){$data['mes'] = '01';$data['ano']=$data['ano']+1;}
		if($data['mes']==00){$data['mes'] = '12';$data['ano']=$data['ano']-1;}
		if(strlen($data['mes'])==1)$data['mes'] = "0".$data['mes'];
		return $data['mes']."|".$data['ano'];
	}
	function getNumeroDias( $mes ){
		$numero_dias = array('01' => 31, '02' => 28, '03' => 31, '04' =>30, '05' => 31, '06' => 30,'07' => 31, '08' =>31, '09' => 30, '10' => 31, '11' => 30, '12' => 31);
		if (((date('Y') % 4) == 0 and (date('Y') % 100)!=0) or (date('Y') % 400)==0){
			$numero_dias['02'] = 29;	// altera o numero de dias de fevereiro se o ano for bissexto
		}
		return $numero_dias[$mes];
	}

	function mostraCalendario($mesAno,$responsavelID, $solicitanteID, $situacaoID, $tipoTarefaID, $aberto, $fechado){

		$sql = "select
				ft.Conta_ID, ft.Titulo_ID, ft.Valor_Titulo, ft.Data_Vencimento, ft.Valor_Pago, ft.Data_Pago,
				t1.tipo_ID Tipo_Conta_ID, t1.Descr_tipo Tipo_Conta,t2.tipo_ID Tipo_ID, t2.Descr_tipo Tipo, t3.tipo_ID Situacao_ID, t3.Descr_tipo Situacao,
				cd1.Nome De, cd2.Nome Para
				from financeiro_titulos ft
				inner join financeiro_contas fc on fc.Conta_ID = ft.Conta_ID
				inner join tipo t2 on t2.Tipo_ID = fc.Tipo_ID
				inner join tipo t3 on t3.Tipo_ID = ft.Situacao_Pagamento_ID
				inner join cadastros_dados cd1 on cd1.Cadastro_ID = fc.Cadastro_ID_de
				left join cadastros_dados cd2 on cd2.Cadastro_ID = fc.Cadastro_ID_para
				left join tipo t1 on t1.Tipo_ID = fc.Tipo_Conta_ID
				where month(ft.Data_Vencimento) = $mesAno[mes] and year(ft.Data_Vencimento)= $mesAno[ano]";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;

			$dataAtual = implode('/',array_reverse(explode('-',$row['Data_Vencimento'])));
			if($dataAtual != $dataAnterior) $i=1;
			$detalheAgendamento[$dataAtual][$i][id] 		= $row['Conta_ID'];
			$detalheAgendamento[$dataAtual][$i][de]			= $row['De'];
			$detalheAgendamento[$dataAtual][$i][para]		= $row['Para'];
			$detalheAgendamento[$dataAtual][$i][tipo]		= $row['Tipo'];
			$detalheAgendamento[$dataAtual][$i][tipoID]		= $row['Tipo_ID'];
			$detalheAgendamento[$dataAtual][$i][valor]		= "R$ ".number_format($row['Valor_Titulo'], 2, ',', '.');
			$detalheAgendamento[$dataAtual][$i][situacao]	= $row['Situacao'];
			$detalheAgendamento[$dataAtual][$i][situacaoID]	= $row['Situacao_ID'];
			$detalheAgendamento[$dataAtual][$i][motivo]		= $row['Tipo_Conta'];
			$dataAnterior = $dataAtual;
		}

		$mes = $mesAno['mes'];
		$numero_dias = getNumeroDias($mes);
		$diacorrente = 0;
		$diasemana = jddayofweek( cal_to_jd(CAL_GREGORIAN, $mes,"01",date('Y')) , 0 );	// função que descobre o dia da semana
		$texto .= "	<table border='0' cellspacing='0' width='100%'>
						<tr class ='linha_semanas' height='40'>
							".mostraSemanas()."
						</tr>";
		for($linha=0;$linha<6;$linha++ ){
		   $texto .= "<tr  height='90'>";
		   for($coluna=0;$coluna<7;$coluna++){
				$texto .= "<td align='left' valign='top'";
				$classeRight = "";
				if ((($coluna + 1) % 7)==0)
					$classeRight = " bordaRight";
				if(($diacorrente==(date('d')-1)&&date('m')==$mes)){
					$incrementoDia = 0;
					if($_POST['dia']==""){
				   		$texto .= "class='DiaSelecionado-adm $classeRight'";
						$incrementoDia = 1;
				   	}else
						$texto .= "class='bordaLatlInf $classeRight'";
				}else{
					if(($diacorrente + 1) <= $numero_dias ){
						$dia = $diacorrente+1;
						$dataAtual = $dia."/".$mesAno['mes']."/".$mesAno['ano'];
						if($_POST['dia']==$dia+1){
							$texto .= "class='DiaSelecionado $classeRight'";
						}else{
							$texto .= "class='bordaLatlInf bordaLeft $classeRight'";
						}
					 }else{
						if ($diacorrente==$numero_dias){
							$texto .= "class='bordaLeft'";
						}
					 }
				}
				$texto .= " >";
				if($diacorrente + 1 <= $numero_dias ){
					if($coluna < $diasemana && $linha == 0){
						$texto .= "&nbsp;";
					}else{
						$texto .= "<div Style='margin-top:3px;margin-left:3px'>".++$diacorrente."</div>";
					}
				}else{
					break;
				}
				$dia = $diacorrente;
				if(strlen($dia)==1) $dia = "0$dia";
				$dataConsulta = $dia."/".$mesAno['mes']."/".$mesAno['ano'];
				for($c=1;$c<=count($detalheAgendamento[$dataConsulta]);$c++){
					if ($detalheAgendamento[$dataConsulta][$c][situacaoID]=='49') $iconePagamento = "<span Style='color:red'><b>=></b></span>"; else $iconePagamento = "<span Style='color:blue'><b><=</b></span>";;
					if ($detalheAgendamento[$dataConsulta][$c][tipoID]=='44') $color = "background-color:#c5d4eb"; else $color = "background-color:#ebc5c5";


					$texto .= "	<div style='font-size:11px;width:97.5%;margin-left:2.5%;cursor:pointer;margin-bottom:5px;float:left;' workflow-id='".$detalheAgendamento[$dataConsulta][$c][id]."' title='Respons&aacute;vel ".$detalheAgendamento[$dataConsulta][$c][responsavel]."'>
									<div Style='float:left;margin-top:5px;margin-right:3px;height:25px'>
										<a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/chamados/chamados-follows-tarefas.php?wID=".$detalheAgendamento[$dataConsulta][$c][id]."&t=".$detalheAgendamento[$dataConsulta][$c][tarefaID]."&origem=calendario'>
											<img src='$caminhoSistema/images/geral/ico-editar-var-produto.png' class='finaliza-edita-chamado' title='Editar tarefa' style='cursor:pointer'>
											<br><br>
											$iconePagamento
										</a>
									</div>
									<div class='workflow-localiza' Style='float:left;width:83%;font-size:11px;$color;border-radius:5px;padding:5px;' workflow-id='".$detalheAgendamento[$dataConsulta][$c][id]."'>
										De: ".$detalheAgendamento[$dataConsulta][$c][de]."<br>
										Para: ".$detalheAgendamento[$dataConsulta][$c][para]."<br>
										Motivo: ".$detalheAgendamento[$dataConsulta][$c][motivo]."<br>
										Valor: ".$detalheAgendamento[$dataConsulta][$c][valor]."<br>

										tipo: ".$detalheAgendamento[$dataConsulta][$c][tipo]."<br>

										situacao: ".$detalheAgendamento[$dataConsulta][$c][situacao]."<br>
									</div>
							   </div> ";
				}
				$incrementoDia = "";

				$texto .= "</td>";
			}
			$texto .= "</tr>";
		}
		$texto .= "</table>";
		return $texto;
	}
?>