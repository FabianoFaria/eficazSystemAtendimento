<?php
	global $dadosUserLogin;
	$data = get_date();
	if ($_POST){
		$responsavelID = $_POST['select-responsavel'];
		$solicitanteID = $_POST['select-solicitante'];
		$chkAberto = $_POST['select-solicitante'];
		$situacaoID = $_POST['select-situacao'];
		$tipoTarefaID = $_POST['select-tipo-tarefa'];
	}else{
		$responsavelID = $dadosUserLogin['userID'];
		$situacaoID = "83";
	}
	$fechado = "#CC0000;";
	$aberto  = "#376d8a;";
?>
<div class="titulo-container">
	<div class="titulo" style="min-height:25px">
		<p style="margin-top:2px;">Filtros de Pesquisa</p>
	</div>
	<div class="conteudo-interno">
<?php
	if ($dadosUserLogin['grupoID'] == '1'){
?>
		<div class="titulo-secundario cinco-colunas">
				<p>Respons&aacute;vel:</p>
<?php
			$sql = "Select distinct r.Grupo_ID, r.Cadastro_ID as Responsavel_ID, r.Nome as Responsavel, m.Modulo_Acesso_ID, m.Titulo as Grupo
					from chamados_workflows_tarefas t
					inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
					inner join cadastros_dados r on r.Cadastro_ID = t.Responsavel_ID
					inner join modulos_acessos m on m.Modulo_Acesso_ID = r.Grupo_ID
					where m.Situacao_ID = 1 and r.Situacao_ID = 1
					order by m.Titulo, m.Modulo_Acesso_ID, r.Nome";
			if ($responsavelID=='A')$selectedUsuario = "selected";
			echo "<select id='select-responsavel' name='select-responsavel' Style='width:98.5%'>
						<option value=''>Todos</option>
						<option value='A' ".$selectedUsuario.">Tarefas Abertas por ".$dadosUserLogin[nome]."</option>";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				if ($row[Grupo_ID]!=$grupoIDAnt){
					$selecionado = ""; if ($responsavelID=="G".$row[Grupo_ID]) $selecionado = "selected";
					echo "<option value='G".$row[Grupo_ID]."' $selecionado>".$row['Grupo']."</option>";
				}
				$selecionado = ""; if ($responsavelID==$row[Responsavel_ID]) $selecionado = "selected";
				echo "<option value='".$row[Responsavel_ID]."' $selecionado>&nbsp;&nbsp;&nbsp;".$row['Responsavel']."</option>";
				$grupoIDAnt = $row[Grupo_ID];
			}
			echo "</select>";
?>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Solicitante:</p>
<?php
		$sql = "Select distinct w.Solicitante_ID as Solicitante_ID, c.Nome as Solicitante
					from chamados_workflows_tarefas t
					inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
					inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
					order by c.Nome";
		echo "<select id='select-solicitante' name='select-solicitante' Style='width:98.5%'>
					<option value=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$selecionado = "";
			if ($solicitanteID==$row[Solicitante_ID]) $selecionado = "selected";
			echo "<option value='".$row[Solicitante_ID]."' $selecionado>".$row['Solicitante']."</option>";
		}

		echo "</select>";
?>
		</div>
<?php
	}
	else{
		$responsavelID = $dadosUserLogin['userID'];
	}
?>
		<div class="titulo-secundario" style="float:left;width:20%;">
			<p>Tarefa</p>
			<p>
				<select name="select-tipo-tarefa" id="select-tipo-tarefa">
				<?php echo optionValueGrupo(40, $tipoTarefaID, "Todos");?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario cinco-colunas">
			<p>Situa&ccedil;&atilde;o Tarefa</p>
			<p>
				<select name="select-situacao" id="select-situacao">
				<?php echo optionValueGrupo(41, $situacaoID, "Todos");?>
				<select>
			</p>
		</div>
		<div class='titulo-secundario' style='float:right;width:20%; margin-top:25px'>
			<p>
				<span style='width:50px;background-color:<?php echo $aberto;?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				Aberta
				&nbsp;&nbsp;&nbsp;
				<span style='width:50px;background-color:<?php echo $fechado;?>'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
				Finalizada
			</p>
		</div>

		<div class='titulo-secundario cinco-colunas' Style='margin-top:15px; float:left;'>
			<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' id='botao-pesquisar-calendario'></p>
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
		global $dadosUserLogin,$caminhoSistema;
		if ($responsavelID!=""){
			if ($responsavelID=="A")
				$sqlCond = " and t.Usuario_cadastro_ID = '".$dadosUserLogin['userID']."' ";
			else{
				if (substr($responsavelID,0,1)=="G")
					$sqlCond = " and t.Grupo_Responsavel_ID = '".substr($responsavelID,1,strlen($responsavelID))."'";
				else
					$sqlCond = " and t.Responsavel_ID = '$responsavelID' ";
			}
		}
		if ($solicitanteID!="")
			$sqlCond .= " and w.Solicitante_ID = '$solicitanteID'";
		if ($situacaoID!="")
			$sqlCond .= " and t.Situacao_ID = '$situacaoID'";
		if ($tipoTarefaID!="")
			$sqlCond .= " and t.Tipo_Tarefa_ID = '$tipoTarefaID'";

		$sql = "Select w.Workflow_ID as Workflow_ID, tf.Descr_Tipo as Tipo_Tarefa,t.Workflow_Tarefa_ID,
				DATE_FORMAT(t.Data_Retorno,'%d/%m/%Y') as Data_Retorno,
				DATE_FORMAT(t.Data_Retorno,'%H:%i') as Hora_Retorno, c.Nome as Cadastro,
				coalesce(r.Nome,m.Titulo) as Responsavel, ts.Descr_Tipo as Situacao, t.Situacao_ID as Situacao_ID, w.Titulo Titulo_Workflow
				from chamados_workflows_tarefas t
					inner join tipo tf on tf.Tipo_ID = t.Tipo_Tarefa_ID
					inner join chamados_workflows w on t.Workflow_ID = w.Workflow_ID
					inner join cadastros_dados c on c.Cadastro_ID = w.Solicitante_ID
					inner join tipo ts on ts.Tipo_ID = t.Situacao_ID
					left join cadastros_dados r on r.Cadastro_ID = t.Responsavel_ID
					left join modulos_acessos m on m.Modulo_Acesso_ID = t.Grupo_Responsavel_ID
					where t.Workflow_ID > 0
					$sqlCond
					order by Data_Retorno";

		//echo $sql;

		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dataAtual = $row['Data_Retorno'];
			if($dataAtual != $dataAnterior) $i=1;
			$detalheAgendamento[$row['Data_Retorno']][$i][id] 			= $row['Workflow_ID'];
			$detalheAgendamento[$row['Data_Retorno']][$i][tarefa] 		= $row['Tipo_Tarefa'];
			$detalheAgendamento[$row['Data_Retorno']][$i][hora]			= $row['Hora_Retorno'];
			$detalheAgendamento[$row['Data_Retorno']][$i][cadastro]		= $row['Cadastro'];
			$detalheAgendamento[$row['Data_Retorno']][$i][responsavel]	= $row['Responsavel'];
			$detalheAgendamento[$row['Data_Retorno']][$i][situacaoID]	= $row['Situacao_ID'];
			$detalheAgendamento[$row['Data_Retorno']][$i][situacao]		= $row['Situacao'];
			$detalheAgendamento[$row['Data_Retorno']][$i][titulo]		= $row['Titulo_Workflow'];
			$detalheAgendamento[$row['Data_Retorno']][$i][tarefaID]		= $row['Workflow_Tarefa_ID'];
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
					if ($detalheAgendamento[$dataConsulta][$c][situacaoID]=='83') $color = "color:$aberto"; else $color = "color:$fechado";
					if($detalheAgendamento[$dataConsulta][$c][titulo] != "") $tituloTarefa = $detalheAgendamento[$dataConsulta][$c][titulo]."<br>"; else $tituloTarefa = "";
					$texto .= "	<div style='$color font-size:11px;width:97.5%;margin-left:2.5%;cursor:pointer;margin-bottom:5px;float:left;' workflow-id='".$detalheAgendamento[$dataConsulta][$c][id]."' title='Respons&aacute;vel ".$detalheAgendamento[$dataConsulta][$c][responsavel]."'>
									<div Style='float:left;margin-top:5px;margin-right:3px;height:25px'>
										<a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/chamados/chamados-follows-tarefas.php?wID=".$detalheAgendamento[$dataConsulta][$c][id]."&t=".$detalheAgendamento[$dataConsulta][$c][tarefaID]."&origem=calendario'>
											<img src='$caminhoSistema/images/geral/ico-editar-var-produto.png' class='finaliza-edita-chamado' title='Editar tarefa' style='cursor:pointer'>
										</a>
									</div>
									<div class='workflow-localiza' Style='float:left;width:83%'  workflow-id='".$detalheAgendamento[$dataConsulta][$c][id]."'>
										$tituloTarefa
										<b  Style='font-weight:normal;'>".$detalheAgendamento[$dataConsulta][$c][hora]."</b> - <u Style='font-weight:normal;'>".$detalheAgendamento[$dataConsulta][$c][cadastro]."
										".$detalheAgendamento[$dataConsulta][$c][tarefa]."</u>
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