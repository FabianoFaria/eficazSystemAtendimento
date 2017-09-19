<?php
	ob_start();
	global $conn, $caminhoSistema, $caminhoFisico, $tituloSistema, $descricaoSistema, $tipoBaseDados, $dadosUserLogin, $modulosAtivos;
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");

	$projecao = carregarDadosProjecao($_GET['orcamento']);
	$detalhesOrcamento = calculaValoresOrcamento($_GET['orcamento'],$_GET['cadastro']);


	$rs = mpress_query("select Workflow_ID from orcamentos_propostas where Proposta_ID = '".$_GET['orcamento']."'");
	if($row = mpress_fetch_array($rs))
		$workflowID = $row['Workflow_ID'];

	echo "
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
	<html xmlns='http://www.w3.org/1999/xhtml'>
		<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'>
		<head>";
	get_header();
	echo 	"<script type='text/javascript' src='$caminhoSistema/modulos/turmas/turmas.js'></script>
		</head>
		<style>
			td {
				border:1px solid silver;
			}
			#projecao-orcamento p{
				margin-top:5px;
				font-weight:bold;
			}
			#projecao-orcamento p span{
				font-weight:normal;
			}
		</style>
		<body>
			<form method='post' id='frmProjecao' name='frmProjecao' autocomplete='off' class='iframe-interno'>
				<input type='hidden' id='conteudo-geral-projecao' name='conteudo-geral-projecao' value=''/>
				<div id='container-geral' Style='width:100%;float:left;min-width:780px;'>
					<div id='div-conteudo-pagina'>
						<input type='hidden' name='proposta-id' id='proposta-id' value='".$_GET['orcamento']."'/>
						<input type='hidden' name='workflow-id' id='workflow-id' value='".$workflowID."'/>";

	if (is_array($projecao)){
		if ($projecao['data-inicio-pagamentos']!="") $detalhesOrcamento['gerais']['data-final']  = $projecao['data-inicio-pagamentos'];
		if ($projecao['valores-total']!="") $detalhesOrcamento['gerais']['valores-total']  = $projecao['valores-total'];
		if ($projecao['data-inicio-pagamentos']!="") $detalhesOrcamento['gerais']['data-inicio-pagamentos'] = $projecao['data-inicio-pagamentos'];
		if ($projecao['meses-evento']!="") $detalhesOrcamento['gerais']['meses-evento'] = $projecao['meses-evento'];
	}

	echo "				<div class='titulo-container'>
							<div class='titulo'>
								<p>Dados Gerais - Proje&ccedil;&atilde;o de Proposta</p>
							</div>
							<div class='conteudo-interno'>
								<div class='titulo-secundario'>
									<table border='0' width='100%' cellspacing='2' cellpadding='3' class='projecao-orcamento'>
										<tr height='40'>
											<td width='16.66%' align='right'>
												Valor &agrave; Alcan&ccedil;ar&nbsp;R$
											</td>
											<td width='16.66%' align='left'>
												<p><input type='text' readonly name='valores-total' style='text-align:center; font-weight: bolder; width:100px; font-size: 17px; height:20px; background-color: #FFFFFF' value='".number_format($detalhesOrcamento['valores']['total'], 2, ',','.')."'/></p>
											</td>
											<td width='16.66%' align='right'>
												Inicio Mensalidades
											</td>
											<td width='16.66%' align='left'>
												<input type='text' readonly name='data-inicio-pagamentos' style='text-align:center; font-weight: bolder; width:100px; font-size: 17px; height:20px; background-color: #FFFFFF' value='".$detalhesOrcamento['gerais']['data-inicio-pagamentos']."'/>
											</td>
											<td width='16.66%' align='right'>
												Meses até a Formatura
											</td>
											<td width='16.66%' align='left'>
												<input type='text' readonly name='meses-string' style='text-align:center; font-weight: bolder; width:120px; font-size: 17px; height:20px; background-color: #FFFFFF'  value='".$detalhesOrcamento['gerais']['meses-evento']." (".$detalhesOrcamento['gerais']['data-final'].")'/>
												<input type='hidden' name='data-final' value='".$detalhesOrcamento['gerais']['data-final']."'/>
												<input type='hidden' name='meses-evento' value='".$detalhesOrcamento['gerais']['meses-evento']."'/>
											</td>
										</tr>
									</table>
								</div>
							</div>
						</div>";

	echo "					<div class='titulo-container'>
								<div class='titulo'>
									<p>
										Detalhes do Or&ccedil;amento
									</p>
								</div>
								<div class='conteudo-interno'>
									<div class='titulo-secundario'>
										<table border='0' width='100%' cellspacing='2' cellpadding='3' id='detalhes-orcamento'>
											<tr>";
	$largura = 100/count($detalhesOrcamento['eventos']);
	for($t=1;$t<=count($detalhesOrcamento['eventos']);$t++){
		echo "									<td width='$largura%' valign='top'>
													<table width='100%'>
														<tr height='25'>
															<td colspan='2' align='center' Style='font-size:12px;'>
																<b>".$detalhesOrcamento['eventos'][$t]['Nome']."</b>
															</td>
														</tr>";
	/* PAREI AQUI !!!!!*/
	/*
		echo "											<tr height='22' Style='font-size:11px;'>
															<td>Descri&ccedil;&atilde;o</td>
															<td width='85' align='center'>Valor</td>
														</tr>";
		for($c=1; $c <= count($detalhesOrcamento['eventos'][$t]['catfilho']); $c++){
			echo "										<tr height='22'>
															<td Style='font-size:11px;'>
																&nbsp;".$detalhesOrcamento['eventos'][$t]['catfilho'][$c]['Categoria_Filho']."
															</td>
															<td align='center' Style='font-size:11px;'>
																<span Style='float:left;margin-left:5px; font-size:11px;'>R$</span><span Style='float:right;margin-right:5px;'>".number_format($detalhesOrcamento['eventos'][$t]['catfilho'][$c]['Valor_Categoria'], 2,',','.')."</span>
															</td>
														</tr>";
		}
	*/
	// COMENTEI ATE AQUI
		echo "										</table>
												</td>";
		//echo subCategoriasEventos($detalhesOrcamento['eventos'][$t]['Categoria_ID'],$_GET['orcamento']);
	}
	echo "									</tr>
											<tr>";
	for($t=1;$t<=count($detalhesOrcamento['eventos']);$t++){
		if ($detalhesOrcamento['eventos'][$t]['Participantes']==""){
			$detalhesOrcamento['eventos'][$t]['Participantes'] = $detalhesOrcamento['gerais']['total-participantes'];
		}
		$mediaTotalMensal = ($detalhesOrcamento['eventos'][$t]['Total'] / $detalhesOrcamento['eventos'][$t]['Participantes']);
		$detalhesOrcamento['eventos'][$t]['MensalidadeParticipante'] = ($detalhesOrcamento['eventos'][$t]['Total'] / $detalhesOrcamento['eventos'][$t]['Participantes'] / $detalhesOrcamento['gerais']['meses-evento']);


		echo "									<td>
													<table width='100%' Style='margin-top:5px; font-size:11px;'>
														<tr height='22'>
															<td>Total</td>
															<td width='100' align='center'>
																<span Style='float:left;margin-left:5px;'>R$</span>
																<span Style='float:right;margin-right:5px;'>".number_format($detalhesOrcamento['eventos'][$t]['Total'],2,',','.')."</span>
															</td>
														</tr>
														<tr height='22'>
															<td>Participantes</td>
															<td width='100' align='center'>
																<span Style='float:right;margin-right:5px;'>".number_format($detalhesOrcamento['eventos'][$t]['Participantes'],0,'','')."</span>
															</td>
														</tr>
														<tr height='22'>
															<td>Total participante</td>
															<td width='100' align='center'>
																<span Style='float:left;margin-left:5px;'>R$</span>
																<span Style='float:right;margin-right:5px;'>".number_format($mediaTotalMensal,2,',','.')."</span>
															</td>
														</tr>
														<tr height='22'>
															<td>Mensalidade participante (".$detalhesOrcamento['gerais']['meses-evento']." X)</td>
															<td width='100' align='center'>
																<span Style='float:left;margin-left:5px;'>R$</span>
																<span Style='float:right;margin-right:5px;'>".number_format($detalhesOrcamento['eventos'][$t]['MensalidadeParticipante'],2,',','.')."</span>
															</td>
														</tr>
													</table>
												</td>";
	}
	echo "
											</tr>
										</table>
									</div>
								</div>
							</div>";

	echo "					<div class='titulo-container'>
								<div class='titulo'>
									<p>
										Proje&ccedil;&atilde;o";
	if ($_GET['geraDocumento']!='S'){
		echo "							<input type='button' class='botoes-acoes salvar-projecao' value='Salvar Proje&ccedil;&atilde;o'/>
										<input type='button' class='botoes-acoes gerar-documento' value='Gerar Documento'/>";
	}
	echo "							</p>
								</div>
								<div class='conteudo-interno'>
									<div class='titulo-secundario'>
										<table border='0' width='100%' cellspacing='2' cellpadding='3' id='projecao-orcamento'>
											<tr height='40'>
												<td width='12.5%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr  height='34'>
															<td width='100%' align='center'><b>Período</b></td>
														</tr>";

	if (is_array($projecao)){
		if ($projecao['semestres-totais']!="") $detalhesOrcamento['gerais']['semestres-totais']  = $projecao['semestres-totais'];
	}

	for($s=0;$s<$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		if($inicio=='') $anoPeriodo = date('Y');
		if($inicio==2) $anoPeriodo++;
		$inicio = ((($detalhesOrcamento['gerais']['semestre-inicio']-1)+$s)%2)+1;
		echo "											<tr  height='27'>
															<td width='100%' align='center'>
																$inicio&deg; Semestre de $anoPeriodo
															</td>
														</tr>";
	}
	echo "											</table>
													<input type='hidden' id='tot-semestres' name='tot-semestres'  value='".$s."' />
													<input type='hidden' id='semestres-totais' name='semestres-totais' value='".$detalhesOrcamento['gerais']['semestres-totais']."'/>
												</td>";


	/*************** BLOCO PLANOS ****************/


	$mediaTotalMensal = ($detalhesOrcamento['eventos'][$t]['Total'] / $detalhesOrcamento['eventos'][$t]['Participantes']);

	ksort($detalhesOrcamento['planos']);

	$chaveAnt = 0;
	foreach($detalhesOrcamento['planos'] as $chave => $plano){
		$detalhesOrcamento['planos'][$chave]['participantes'] = ($chave - $chaveAnt);
		foreach($detalhesOrcamento['eventos'] as $chaveEvento => $evento){
			if ($evento['Participantes'] >= $chave){
				$detalhesOrcamento['planos'][$chave]['MensalidadePlano'] += $evento['MensalidadeParticipante'];
				$detalhesOrcamento['planos'][$chave]['descricao'] .= "<br>&nbsp;".$evento['Nome'];
			}
			else{
				$detalhesOrcamento['planos'][$chave]['descricao'] .= "<br>&nbsp;";
			}
		}
		$chaveAnt = $chave;
	}

	$tamCP = (100/count($detalhesOrcamento['planos']));
	$p = 0;
	echo "											<td width='38.5%' align='center' valign='bottom'>";
	foreach($detalhesOrcamento['planos'] as $chave => $plano){
		echo "											<table border='0' width='$tamCP%' style='float:left;' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
															<tr height='34'>
																<td width='100%' align='center' colspan='2' style='font-size:11px;'>
																	<b>Mensalidade<br>".$plano['participantes']." participantes</b>
																	".$plano['descricao']."
																	<input type='hidden' id='campo-mensalidade-participantes-$p' name='campo-mensalidade-participantes[$s]' value='".$plano['participantes']."'/>
																</td>
															</tr>";
		for($s=0;$s<$detalhesOrcamento['gerais']['semestres-totais'];$s++){
			if (is_array($projecao)){
				if ($projecao['campo-valor-mensalidade'][$p][$s]!="") $detalhesOrcamento['planos'][$chave]['MensalidadePlano']  = $projecao['campo-valor-mensalidade'][$p][$s];
			}
			echo "											<tr  height='27'>
																<td width='25' align='center'>R$</td>
																<td><input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#f0f0f0;text-align:center;' id='campo-valor-mensalidade-$p-$s'  name='campo-valor-mensalidade[$p][$s]' class='formata-valor calcula-valores-projecao mensal-plano-$p' plano='$p' value='".number_format($detalhesOrcamento['planos'][$chave]['MensalidadePlano'],2,',','.')."'></td>
															</tr>";
		}
		echo "											</table>";
		$totalGeralPlano[$chave]['valor'] = $detalhesOrcamento['planos'][$chave]['MensalidadePlano'] * $plano['participantes'] * $detalhesOrcamento['gerais']['meses-evento'];
		$totalGeralPlano[$chave]['participantes'] = $plano['participantes'];
		$p++;
	}
	echo "												<input type='hidden' name='tot-planos' id='tot-planos' value='$p'/>
													</td>";


	/*************** BLOCO QUANTIDADE DE MENSALIDADES ****************/
	echo "										<td width='5%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr height='34'>
															<td width='100%' align='center'><b>Mensalidades no Período</b></td>
														</tr>";

	for($s=1;$s<=$detalhesOrcamento['gerais']['semestres-totais'];$s++){

		if (is_array($projecao)){
			if ($projecao['campo-mensalidade'][$s-1]!="") $meses = $projecao['campo-mensalidade'][$s-1];
		}
		else{
			if($s==1)
				$meses = $detalhesOrcamento['gerais']['meses-semestre-inicio'];
			else if($s==$detalhesOrcamento['gerais']['semestres-totais'])
				$meses = $detalhesOrcamento['gerais']['meses-semestre-final'];
			else
				$meses = 6;
		}
		echo "											<tr  height='27'>
															<td width='100%' align='center'>
																<input style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#f0f0f0;text-align:center;' id='campo-mensalidade-".($s-1)."' name='campo-mensalidade[".($s-1)."]' class='formata-numero calcula-valores-projecao' value='$meses'/>
															</td>
														</tr>";
		$totalMeses +=$meses;
	}
	echo "											</table>
												</td>
												<td width='15%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr  height='34'>
															<td align='center' colspan='2'><b>Total Mens. Período</b></td>
														</tr>";

	for($s=1;$s<=$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		if($s==1)
			$meses = $detalhesOrcamento['gerais']['meses-semestre-inicio'];
		else if($s==$detalhesOrcamento['gerais']['semestres-totais'])
			$meses = $detalhesOrcamento['gerais']['meses-semestre-final'];
		else
			$meses = 6;

		$mensalidadeArrecadar = ($meses * $detalhesOrcamento['valores']['total-mensal-participante'] * $detalhesOrcamento['gerais']['total-participantes']);
		$totalArrecadacao += $mensalidadeArrecadar;
		echo "											<tr height='27'>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' id='campo-total-mensalidade-".($s-1)."' name='campo-total-mensalidade[".($s-1)."]'   readonly value='".number_format($mensalidadeArrecadar,2,',','.')."'>
															</td>
														</tr>";
	}
	echo "
													</table>
												</td>
												<td width='5%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr  height='34'>
															<td width='100%' align='center'><b>Qtd Ativ.</td>
														</tr>";
	for($s=0;$s<$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		$atividades = 0;
		if (is_array($projecao)){
			if ($projecao['qtd-atividade-lucrativa'][$s]!="") $atividades = $projecao['qtd-atividade-lucrativa'][$s];
		}
		echo "											<tr  height='27'>
															<td><input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#f0f0f0;text-align:center;' name='qtd-atividade-lucrativa[$s]' class='calcula-valores-projecao' id='qtd-atividade-lucrativa-$s' value='$atividades'></td>
														</tr>";
	}
	echo "
													</table>
												</td>
												<td width='10%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr height='34'>
															<td width='100%' align='center' colspan='2'><b>Lucro Atividades</b></td>
														</tr>";
	for($s=0;$s<$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		$valorAtividades = "0,00";
		if (is_array($projecao)){
			if ($projecao['campo-lucro-atividades'][$s]!="") $valorAtividades = $projecao['campo-lucro-atividades'][$s];
		}
		echo "											<tr height='27'>
															<td width='25' align='center'>R$</td>
															<td><input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#f0f0f0;text-align:center;' id='campo-lucro-atividades-$s' name='campo-lucro-atividades[$s]' class='formata-valor calcula-valores-projecao' value='$valorAtividades'></td>
														</tr>";
	}
	echo "											</table>
												</td>
												<td width='15%' align='center' valign='bottom' colspan='2'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr  height='34'>
															<td width='100%' align='center'><b>Total Geral Período<b></td>
														</tr>";

	for($s=0;$s<$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		echo "											<tr  height='27'>
															<td><input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' id='campo-total-periodo-$s' name='campo-total-geral[$s]' readonly></td>
														</tr>";
	}
	echo "
													</table>
												</td>";
/*
	echo "										<td width='15%' align='center' valign='bottom'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr  height='34'>
															<td width='100%' align='center'><b>% Pago</b></td>
														</tr>";

	for($s=1;$s<=$detalhesOrcamento['gerais']['semestres-totais'];$s++){
		echo "											<tr  height='27'>
															<td width='100%' align='center'></td>
														</tr>";
	}
	echo "
													</table>
												</td>
											</tr>";
*/
	echo "									<tr height='40'>
												<td align='center' valign='top' style='border:0px;'>&nbsp;</td>
												<td align='center' valign='top' style='border:0px;'>";
	$p = 0;
	foreach($totalGeralPlano as $chave => $totalPlano){
		echo "										<table border='0' width='$tamCP%' style='float:left; font-size:11px;' cellspacing='2' cellpadding='2' id='projecao-orcamento'>
														<tr height='34'>
															<td align='center' colspan='2'><b>Total ".$totalPlano['participantes']." participantes</b></td>
														</tr>
														<tr  height='27'>
															<td width='25' align='center'>R$</td>
															<td><input readonly Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' id='campo-valor-total-plano-mensalidade-$p' name='campo-valor-total-plano-mensalidade[$p]' class='formata-valor calcula-valores-projecao' value='".number_format($totalPlano['valor'],2,',','.')."'></td>
														</tr>
													</table>";
		$p++;
	}
	echo "										</td>
												<td align='center' valign='top'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' height='25'><b>Total Mensalidades</b></td>
														</tr>
														<tr>
															<td align='center'>
																<b><input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' name='campo-total-mensalidades' id='campo-total-mensalidades' value='".$totalMeses."' readonly/></b>
															</td>
														</tr>
													</table>
												</td>
												<td align='center' valign='top'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' colspan='2' height='25'><b>Total Arrecada&ccedil;&atilde;o</b></td>
														</tr>
														<tr>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' id='campo-total-arrecadacoes' value='".number_format($totalArrecadacao,2,',','.')."' readonly/>
															</td>
														</tr>
													</table>
												</td>
												<td align='center' valign='top' colspan='2'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' colspan='2' height='25'><b>Valor Total Atividades</b></td>
														</tr>
														<tr>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' name='campo-total-atividades' id='campo-total-atividades' value='".number_format($totalAtividades,2,',','.')."' readonly/>
															</td>
														</tr>
													</table>
												</td>
												<td align='center' valign='top' colspan='2'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' colspan='2' height='25'><b>Total Geral</b></td>
														</tr>
														<tr>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' name='campo-total-geral-tudo'  id='campo-total-geral-tudo' value='".number_format($totalArrecadacao,2,',','.')."' readonly/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan='6' style='border:0px;'></td>
												<td align='center' valign='top' colspan='2'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' colspan='2' height='25'><b>Valor &agrave; Alcan&ccedil;ar</b></td>
														</tr>
														<tr>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' name='campo-total-atingir' id='campo-total-atingir' value='".number_format($detalhesOrcamento['valores']['total'],2,',','.')."' readonly/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
											<tr>
												<td colspan='6' style='border:0px;'></td>
												<td align='center' valign='top' colspan='2'>
													<table border='0' width='100%' cellspacing='2' cellpadding='2' id='projecao-orcamento' style='font-size:11px;'>
														<tr>
															<td width='100%' align='center' colspan='2' height='25'><b>Saldo</b></td>
														</tr>
														<tr>
															<td width='25' align='center'>R$</td>
															<td align='center'>
																<input Style='font-size:12px; height:19px;border:0px solid;width:100%;background-color:#ffffff;text-align:center;' name='campo-total-saldo' id='campo-total-saldo' value='".number_format($totalSaldo,2,',','.')."' readonly/>
															</td>
														</tr>
													</table>
												</td>
											</tr>
										</table>";
	if ($_GET['geraDocumento']!='S'){
		echo "				<input type='hidden' name='detOrcOrig' value='".serialize($detalhesOrcamento)."'>";
	}

	echo "
									</div>
								</div>
							</div>";
	echo "
					</div>
				</div>
			</form>
		</body>
	</html>";

	function calculaValoresOrcamento($propostaID, $cadastroID){

		$sql = "SELECT TIMESTAMPDIFF(YEAR, NOW(), MIN(data_evento)) Anos_Evento,
						TIMESTAMPDIFF(MONTH, now(),MIN(data_evento)) Meses_Evento,
						DATE_FORMAT(min(data_evento),'%d/%m/%Y') Data_Evento,
						DATE_FORMAT(min(data_evento),'%m') Mes_Evento,
						DATE_FORMAT(min(data_evento),'%Y') Ano_Evento
					FROM orcamentos_propostas_eventos pe
						WHERE Proposta_id = '$propostaID'
						AND Situacao_id = 1 AND Data_Evento >= '1900-01-01'";
		//echo $sql;
		$rs = mpress_fetch_array(mpress_query($sql));
		$dados['gerais']['meses-evento'] 				= $rs['Meses_Evento'] - 2;
		$dados['gerais']['anos-evento'] 				= $rs['Anos_Evento'];
		$dados['gerais']['mes-evento']   				= $rs['Mes_Evento'];
		$dados['gerais']['ano-evento'] 	 			= $rs['Ano_Evento'];
		$dados['gerais']['data-final']  				= $rs['Data_Evento'];

		$mes = date('m')+1;
		$ano = date('Y');
		if ($mes+1==13) {
			$ano+1;
			$mes = 1;
		}
		$mesAux = $mes;
		if ($mesAux>6)
			$dados['gerais']['meses-semestre-inicio'] = 12 - $mesAux;
		else
			$dados['gerais']['meses-semestre-inicio']= 6 - $mesAux;

		$dados['gerais']['data-inicio-pagamentos']		= str_pad($mes, 2,'0',STR_PAD_LEFT)."/".$ano;
		$dados['gerais']['meses-semestre-restante'] 	= $dados['gerais']['meses-evento'] - $dados['gerais']['meses-semestre-inicio'];
		$dados['gerais']['semestres-inteiros-eventos'] 	= floor($dados['gerais']['meses-semestre-restante']/6);
		$dados['gerais']['meses-semestre-final'] 		= $dados['gerais']['meses-evento'] - (($dados['gerais']['semestres-inteiros-eventos']*6)+$dados['gerais']['meses-semestre-inicio']);
		$dados['gerais']['semestres-totais']			= $dados['gerais']['semestres-inteiros-eventos'];

		if($dados['gerais']['meses-semestre-inicio'] >=1)$dados['gerais']['semestres-totais']++;
		if($dados['gerais']['meses-semestre-final'] >=1)$dados['gerais']['semestres-totais']++;
		if(date('m')<6)$dados['gerais']['semestre-inicio'] = 1; else $dados['gerais']['semestre-inicio'] = 2;

		$dados['gerais']['meses-evento'] = $rs['Meses_Evento'] - 1;

		$sql = "SELECT COALESCE(pcPai.Categoria_ID, pcFilho.Categoria_ID) AS CategoriaPaiID, COALESCE(pcPai.Nome,pcFilho.Nome) AS CategoriaPai,
						CONCAT(COALESCE(pd.Nome),' ', COALESCE(pv.Descricao)) AS Produto, tp.Descr_Tipo AS Tipo, pcFilho.Nome AS CategoriaFilho,
						pcFilho.Categoria_ID AS CategoriaFilhoID, SUM(opp.Quantidade * opp.Valor_Venda_Unitario) as Total,
						ope.Data_Evento as Data_Evento, ope.Participantes, pd.Tipo_Produto AS Tipo_Produto
					FROM orcamentos_propostas_produtos opp
					INNER JOIN produtos_variacoes pv ON pv.Produto_Variacao_ID = opp.Produto_Variacao_ID
					INNER JOIN produtos_dados pd ON pd.Produto_ID = pv.Produto_ID
					INNER JOIN tipo tp ON tp.Tipo_ID = pd.Tipo_Produto
					INNER JOIN produtos_dados_categorias pdc ON pdc.Produto_Categoria_ID = opp.Produto_Categoria_ID
					INNER JOIN produtos_categorias pcFilho ON pcFilho.Categoria_ID = pdc.Categoria_ID
					LEFT JOIN produtos_categorias pcPai ON pcPai.Categoria_ID = pcFilho.Categoria_Pai_ID
					LEFT JOIN orcamentos_propostas_eventos ope ON ope.Proposta_ID = opp.Proposta_ID AND ope.Proposta_Produto_ID = opp.Proposta_Produto_ID AND ope.Situacao_ID = 1
					WHERE opp.Proposta_ID = '$propostaID' AND opp.Situacao_ID = 1
					GROUP BY CategoriaPaiID, CategoriaPai, pcFilho.Nome, pcFilho.Categoria_ID, ope.Data_Evento, pd.Tipo_Produto
					ORDER BY CategoriaPai, CategoriaPaiID, CategoriaFilho";
		//echo $sql;
		$rs = mpress_query($sql);
		$dados['gerais']['total-participantes'] = 1;
		$p=0;
		while($row = mpress_fetch_array($rs)){
			if ($row['CategoriaPaiID']!=$categoriaPaiIDAnt){
				$i++;
				$dados['eventos'][$i]['Categoria_ID'] = $row['CategoriaPaiID'];
				$dados['eventos'][$i]['Nome'] = $row['CategoriaPai'];
				$dados['eventos'][$i]['Data_Evento'] = $row['Data_Evento'];
				$dados['eventos'][$i]['Participantes'] = $row['Participantes'];
				if (($row['Participantes']) > ($dados['gerais']['total-participantes'])){
					$dados['gerais']['total-participantes'] = $row['Participantes'];
				}
				$ii = 0;
				if ($row[Participantes]!=""){
					$p++;
					$dados['planos'][$row[Participantes]][descricao] = "" ;
				}
			}
			$dados['eventos'][$i][Total] += $row[Total];
			$ii++;
			$dados['eventos'][$i]['catfilho'][$ii]['Categoria_Filho_ID'] = $row["CategoriaFilhoID"];
			$dados['eventos'][$i]['catfilho'][$ii]['Categoria_Filho'] = $row["CategoriaFilho"];
			$dados['eventos'][$i]['catfilho'][$ii]['Valor_Categoria'] = $row["Total"];
			$dados['valores']['total'] += $row["Total"];
			$categoriaPaiIDAnt = $row['CategoriaPaiID'];
		}
		$dados['valores']['total-mensal-participante'] = ($dados['valores']['total'] / $dados['gerais']['total-participantes'] / $dados['gerais']['meses-evento']);
		$dados['valores']['total-formando'] 	= number_format($dados['valores']['total'] / $dados['gerais']['total-participantes'], 2, '.','');
		return $dados;

	}

/*
	function subCategoriasEventos($categoriaPai, $propostaID){
		$rs = mpress_query("select pc.Categoria_ID, pc.Nome, sum(opp.Valor_Venda_Unitario*opp.Quantidade) Total
							from orcamentos_propostas_produtos opp
							inner join produtos_variacoes pv on pv.Produto_Variacao_ID = opp.Produto_Variacao_ID and pv.Situacao_ID = 1
							inner join produtos_dados_categorias pdc on pdc.Produto_ID = pv.Produto_ID and pdc.Situacao_ID = 1
							inner join produtos_categorias pc on pc.Categoria_ID = pdc.Categoria_ID and pc.Situacao_ID = 1
							where opp.proposta_id = $propostaID and opp.quantidade > 0 and opp.Situacao_ID = 1 and opp.categoria_id = $categoriaPai
							and pc.Categoria_Pai_ID = $categoriaPai
							group by pc.Categoria_ID
							order by pc.nome");
		while($row = mpress_fetch_array($rs)){
			$texto .= "	<tr height='22'>
							<td Style='font-size:9px;'>&nbsp;$row[Nome]</td>
							<td align='center' Style='font-size:11px;'><span Style='float:left;margin-left:5px;'>R$</span><span Style='float:right;margin-right:5px;'>".number_format($row[Total], 2,',','.')."</span></td>
						</tr>";
		}
		return "$texto</table></td>";
	}
*/

if ($_GET['geraDocumento']=='S'){
	//include("../administrativo/functions.php");
	$nomeArquivo = "Projecao_".date('Ymd_hms').".html";
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
	$id = $_POST['workflow-id'];
	$origem = 'orcamentos';
	$background = "CR";

	$conteudo = ob_get_contents();

	//$conteudo = str_replace("'","&#39;",$conteudo);
	$conteudo = str_replace("'",'"',$conteudo);

	$sql = "insert into modulos_anexos(Documento_ID, Cabecalho_Rodape, Chave_Estrangeira, Tabela_Estrangeira, Nome_Arquivo, Observacao, Nome_Arquivo_Original, Data_Cadastro, Usuario_Cadastro_ID)
										values (-1,'$background', '$id','$origem','$nomeArquivo', '$conteudo','$nomeArquivo', $dataHoraAtual,'".$dadosUserLogin['userID']."')";
	mpress_query($sql);

	$f = fopen("../../uploads/".$nomeArquivo, "w");
	fwrite($f, $conteudo);
	fclose($f);

	echo "	<script>
				parent.$.fancybox.close();
				parent.$('#menu-superior-3').click();
			</script>";

	//geraPDF($nomeArquivo, $conteudo, $background,'a4','landscape');

	//$conteudo = ob_get_contents();
	//print_r($conteudo);
	//ob_end_clean();
}
?>