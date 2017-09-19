<?php
	global $modulosAtivos;
	$tipoProduto = 30;
	$workflowID = $_POST["workflow-id"];
	if ($workflowID!=""){
		$sql = "Select Solicitante_ID, Prestador_ID, Codigo, Tipo_Workflow_ID, Data_Cadastro, Usuario_Cadastro_ID, Prioridade_ID,
					DATE_FORMAT(Data_Abertura, '%d/%m/%Y') as Data_Abertura, DATE_FORMAT(Data_Abertura, '%H:%i') as Hora_Abertura,
					DATE_FORMAT(Data_Finalizado, '%d/%m/%Y') as Data_Finalizado, DATE_FORMAT(Data_Finalizado, '%H:%i') as Hora_Finalizado
					from chamados_workflows
					where Workflow_ID = $workflowID";
		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){

			$solicitanteID = $rs[Solicitante_ID];
			$prestadorID = $rs[Prestador_ID];
			$tipoWorkflowID = $rs[Tipo_Workflow_ID];
			$prioridadeID = $rs[Prioridade_ID];
			$dataCadastro = $rs[Data_Cadastro];
			$dataAbertura = $rs[Data_Abertura]; if ($dataAbertura == "00/00/0000") $dataAbertura = "";
			$horaAbertura = $rs[Hora_Abertura]; if ($horaAbertura == "00:00") $horaAbertura = "";
			$dataFinalizado = $rs[Data_Finalizado]; if ($dataFinalizado == "00/00/0000") $dataFinalizado = "";
			$horaFinalizado = $rs[Hora_Finalizado]; if ($horaFinalizado == "00:00") $horaFinalizado = "";


			$usuarioCadastroID = $rs[Usuario_Cadastro_ID];
			$codigo = $rs[Codigo];

			$sql = "Select Follow_ID, Descricao, Dados, t.Descr_Tipo as Situacao, cf.Situacao_ID as Situacao_ID, DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro,
					Responsabilidade_ID, cd.Nome as Usuario_Follow
					from chamados_follows cf
					left join tipo t on t.Tipo_ID = cf.Situacao_ID
					inner join cadastros_dados cd on cd.Cadastro_ID = cf.Usuario_Cadastro_ID
					where Workflow_ID = $workflowID
					order by cf.Follow_ID desc ";

			$query = mpress_query($sql);
			$i=0;
			while($rs = mpress_fetch_array($query)){
				$i++;
				if ($i==1){
					$situacaoAtualID = $rs['Situacao_ID'];
					$situacaoAtual = $rs['Situacao'];
				}
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Descricao']."</p>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
			}
			if($i>=1){
				$largura = "99%";
				$colunas = "3";
				$dados[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
				$dados[colunas][tamanho][1] = "width=''";
				$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
				$dados[colunas][tamanho][2] = "width='180px'";
				$dados[colunas][titulo][3] 	= "Data";
				$dados[colunas][tamanho][3] = "width='180px'";
			}
		}

		//********Caso modulo de Centro de Distribuição ativo*************
		if ($modulosAtivos[envios]){
			$sql = "select ew.Workflow_ID, cd1.Nome as Cadastro_de, cd2.Nome as Cadastro_para, cd3.Nome as Trasportadora, tf.Descr_Tipo as Forma_Envio, ew.Codigo_Rastreamento, t.Descr_Tipo as Situacao,
						DATE_FORMAT(ew.Data_Envio,'%d/%m/%Y') as Data_Envio, DATE_FORMAT(ew.Data_Previsao,'%d/%m/%Y') as Data_Previsao,  DATE_FORMAT(ew.Data_Entrega,'%d/%m/%Y') as Data_Entrega
						from envios_workflows ew inner join tipo tf on tf.Tipo_ID = ew.Forma_Envio_ID
						inner join cadastros_dados cd1 on cd1.Cadastro_ID = ew.Cadastro_ID_de
						inner join cadastros_dados cd2 on cd2.Cadastro_ID = ew.Cadastro_ID_para
						left join cadastros_dados cd3 on cd3.Cadastro_ID = ew.Transportadora_ID
						inner join envios_follows ef on ew.Workflow_ID = ef.Workflow_ID and ef.Follow_ID = (select max(efaux.Follow_ID) from envios_follows efaux where ef.Workflow_ID = efaux.Workflow_ID)
						inner join tipo t on t.Tipo_ID = ef.Situacao_ID
						where ew.Workflow_ID is not null
						and ew.Tabela_Estrangeira = 'chamados'
						and ew.Chave_Estrangeira = '$workflowID'
						order by ew.Workflow_ID, ef.Follow_ID";
			$query2 = mpress_query($sql);
			while($row = mpress_fetch_array($query2)){

				$dadosEnvio[colunas][conteudo][$i][1] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-envio' workflow-id='$row[Workflow_ID]'>".str_pad($row[Workflow_ID], 7, "0", STR_PAD_LEFT)."</p>";
				$dadosEnvio[colunas][conteudo][$i][2] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Cadastro_de]."</p>";
				$dadosEnvio[colunas][conteudo][$i][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Cadastro_para]."</p>";
				$dadosEnvio[colunas][conteudo][$i][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Transportador]."</p>";
				$dadosEnvio[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Codigo_Rastreamento]."</p>";
				$dadosEnvio[colunas][conteudo][$i][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Forma_Envio]."</p>";
				$dadosEnvio[colunas][conteudo][$i][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Situacao]."</p>";
				$dadosEnvio[colunas][conteudo][$i][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Envio]."</p>";
				$dadosEnvio[colunas][conteudo][$i][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Previsao]."</p>";
				$dadosEnvio[colunas][conteudo][$i][10] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Entrega]."</p>";
			}
			$dadosEnvio[colunas][titulo][1] 	= "ID Envio";
			$dadosEnvio[colunas][titulo][2] 	= "Envio de";
			$dadosEnvio[colunas][titulo][3] 	= "Envio para";
			$dadosEnvio[colunas][titulo][4] 	= "Transportador";
			$dadosEnvio[colunas][titulo][5] 	= "Rastreamento";
			$dadosEnvio[colunas][titulo][6] 	= "Forma Envio";
			$dadosEnvio[colunas][titulo][7] 	= "Situa&ccedil;&atilde;o";
			$dadosEnvio[colunas][titulo][8] 	= "Envio";
			$dadosEnvio[colunas][titulo][9] 	= "Previs&atilde;o";
			$dadosEnvio[colunas][titulo][10] = "Entrega";

			$dadosEnvio[colunas][tamanho][1] = "width='80px'";
			$dadosEnvio[colunas][tamanho][2] = "";
			$dadosEnvio[colunas][tamanho][3] = "";
			$dadosEnvio[colunas][tamanho][4] = "";
			$dadosEnvio[colunas][tamanho][5] = "width='150px'";
			$dadosEnvio[colunas][tamanho][6] = "width='150px'";
			$dadosEnvio[colunas][tamanho][7] = "width='110px'";
			$dadosEnvio[colunas][tamanho][8] = "width='080px'";
			$dadosEnvio[colunas][tamanho][9] = "width='080px'";
			$dadosEnvio[colunas][tamanho][10] = "width='080px'";
		}
	}
	else{
		$classeEsconde = "esconde";
		$dataAbertura = retornaDataHora('d','d/m/Y');
		$horaAbertura = retornaDataHora('h','H:i');
	}

	$sql = "Select Email from cadastros_dados where Cadastro_ID = ".$dadosUserLogin['userID'];
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query))
		$emailLogado = $rs[Email];

?>
	<input type='hidden' id='nome-campo' name='nome-campo'>
	<input type='hidden' class='email-workflow' id='email-workflow' value='<?php echo $emailLogado; ?>'>
	<input type='hidden' id='localiza-workflow-id'  name='localiza-workflow-id' value=''>

	<div id='chamados-container'>
		<div class="titulo-container" id='div-solicitante-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-cadastro' id='btn-expandir-retrair-solicitante' tipo='solicitante' style='float:right;' title='Expandir'></div>
				<p>Dados do Solicitante</p>
			</div>
			<div class='conteudo-interno' id='conteudo-interno-solicitante'>
				<div id='div-solicitante-localizar' Style='float:left;width:100%;'>
					<div id='div-localizar-solicitante-select' style='float:left; width:90%;'>
						<select id='select-cadastro-solicitante' name='select-cadastro-solicitante' style='width:98.5%'><option value=''>Selecione</option></select>
					</div>
					<div id='div-localizar-solicitante-texto' style='float:left; width:90%;'>
						<input type='text' id='texto-localizar-cadastros-solicitante' name='texto-localizar-cadastros-solicitante' style='width:98.5%'>
					</div>
					<div id='div-localizar-solicitante-localizar' style='width:10%; float:left;'>
						<input type='button' id='botao-localizar-cadastros-solicitante' name='botao-localizar-cadastros-solicitante' Style='width:95%' value='Localizar' >
					</div>
					<div id='div-localizar-solicitante-cancelar' style='width:10%; float:left;'>
						<input type='button' value='Cancelar' id='botao-cancelar-solicitante' class='botao-cancelar-solicitante' Style='width:95%'/>
					</div>
				</div>
				<div id='div-solicitante' class="titulo-secundario uma-coluna esconde"></div>
				<input type='hidden' id='solicitante-id' name='solicitante-id' value='<?php echo $solicitanteID;?>'/>
			</div>
		</div>
		<div class="titulo-container" id='div-prestador-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-cadastro' id='btn-expandir-retrair-prestador' tipo='prestador' style='float:right;' title='Expandir'></div>
				<div class='btn-editar esconde' style='float:right;padding-right:20px;margin-top:2px' class='link esconde' title='Alterar prestador' id='alterar-prestador'>&nbsp;</div>
				<div class='btn-cancelar esconde' style='float:right;padding-right:20px;margin-top:2px' class='link esconde' title='Cancelar altera&ccedil;&atilde;o de prestador' id='cancelar-alterar-prestador'>&nbsp;</div>
				<p>Dados do Prestador</p>
			</div>
			<div class='conteudo-interno' id='conteudo-interno-prestador'>
				<div id='div-prestador-localizar' Style='float:left;width:100%;'>
					<div id='div-localizar-prestador-select' style='float:left; width:90%;'>
						<select id='select-cadastro-prestador' name='select-cadastro-prestador' style='width:98.5%'><option value=''>Selecione</option></select>
					</div>
					<div id='div-localizar-prestador-texto' style='float:left; width:90%;'>
						<input type='text' id='texto-localizar-cadastros-prestador' name='texto-localizar-cadastros-prestador' style='width:98.5%'>
					</div>
					<div id='div-localizar-prestador-localizar'  style='width:10%; float:left;'>
						<input type='button' id='botao-localizar-cadastros-prestador' name='botao-localizar-cadastros-prestador' Style='width:95%' value='Localizar' >
					</div>
					<div id='div-localizar-prestador-cancelar' style='width:10%; float:left;'>
						<input type='button' value='Cancelar' id='botao-cancelar-prestador' class='botao-cancelar-prestador' Style='width:95%'/>
					</div>
				</div>
				<div id='div-prestador' class="titulo-secundario uma-coluna esconde"></div>
				<input type='hidden' id='prestador-id' name='prestador-id' value='<?php echo $prestadorID;?>'/>
			</div>
		</div>
		<div class="titulo-container <?php echo $classeEsconde; ?>" id='div-produtos-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-produtos' style='float:right;' title='Expandir'></div>
				<p>Produtos e Servi&ccedil;os</p>
			</div>
			<div class='conteudo-interno titulo-secundario' id='conteudo-interno-produtos'>
				<div id='div-produtos-incluir-editar' Style='float:left;width:100%;'></div>
				<div id='div-produtos' class="titulo-secundario uma-coluna" style='margin-top:5px;'></div>
			</div>
		</div>
		<div class="titulo-container <?php echo $classeEsconde; ?>" id='div-anexos'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-anexos' style='float:right;' title='Expandir'></div>
				<p>Anexos</p>
			</div>
			<div class="conteudo-interno esconde" id='conteudo-interno-anexo'>
				<div id="div-cadastro-anexo"></div>
				<div id="div-anexos-cadastrados"></div>
			</div>
		</div>

<?php
		//********Caso modulo de Centro de Distribuição ativo*************
		if (($modulosAtivos[envios]) && ($workflowID!="")){
?>
		<div class="titulo-container <?php echo $classeEsconde; ?>" id='div-cd-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-cd' style='float:right;' title='Expandir'></div>
				<p>Controle de Distribui&ccedil;&atilde;o</p>
			</div>
			<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-cd'>
				<?php geraTabela("99%","11",$dadosEnvio); ?>
			</div>
		</div>
<?php
		}
?>


		<div class="titulo-container" id='div-chamado-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-chamado' style='float:right;' title='Expandir'></div>
				<p>Dados Chamado <?php if ($workflowID!=""){ echo "N&ordm; $workflowID - $situacaoAtual";}?></p>
				<input type='hidden' id='workflow-id' name='workflow-id' value='<?php echo $workflowID;?>'/>
				<input type='hidden' id='situacao-atual-chamado' name='situacao-atual-chamado' value='<?php echo $situacaoAtualID;?>'/>
			</div>
			<div class='conteudo-interno titulo-secundario' id='conteudo-interno-chamado'>
				<div class='titulo-secundario' style='width:12%;float:left;'>
					<p><b>C&oacute;digo Chamado</b></p>
					<p><input type="text" id="codigo-workflow" name="codigo-workflow" value='<?php echo $codigo; ?>' style='width:91%' maxlength='50'/></p>
				</div>

				<div class='titulo-secundario' style='width:28%;float:left;'>
					<p><b>Tipo Chamado</b></p>
					<p><select name="tipo-workflow" id="tipo-workflow" style='width:97%'><?php echo optionValueGrupo(19, $tipoWorkflowID, "Selecione");?><select></p>
					<input type='hidden' name="tipo-workflow-ant" id="tipo-workflow-ant"  value='<?php echo $tipoWorkflowID;?>'/>
				</div>
				<div class='titulo-secundario' style='width:28%;float:left;'>
					<p><b>Prioridade</b></p>
					<p><select name="prioridade" id="prioridade" style='width:97%'><?php echo optionValueGrupo(21, $prioridadeID, "Selecione");?><select></p>
				</div>
				<div class='titulo-secundario' style='width:10%;float:left;'>
					<p><b>Data Abertura</b></p>
					<p><input type="text" id="data-abertura-chamado" name="data-abertura-chamado" class='formata-data' value='<?php echo $dataAbertura; ?>' style='width:89%' maxlength='10'/></p>
				</div>
				<div class='titulo-secundario' style='width:6%;float:left;'>
					<p><b>Hora</b></p>
					<p><input type="text" id="hora-abertura-chamado" name="hora-abertura-chamado" class='formata-hora' value='<?php echo $horaAbertura; ?>' style='width:83%' maxlength='5'/></p>
				</div>

				<div class='titulo-secundario div-data-finalizado' style='width:10%;float:left;'>
					<p><b>Data Finaliza&ccedil;&atilde;o</b></p>
					<p><input type="text" id="data-finalizado-chamado" name="data-finalizado-chamado" class='formata-data' value='<?php echo $dataFinalizado; ?>' style='width:89%' maxlength='10'/></p>
				</div>
				<div class='titulo-secundario div-data-finalizado' style='width:6%;float:left;'>
					<p><b>Hora</b></p>
					<p><input type="text" id="hora-finalizado-chamado" name="hora-finalizado-chamado" class='formata-hora' value='<?php echo $horaFinalizado; ?>' style='width:83%' maxlength='5'/></p>
				</div>
				<?php
				if (($situacaoAtualID=="33")||($situacaoAtualID=="34")){
					if ($dadosUserLogin['GrupoID']==1){
				?>

				<div class='titulo-secundario' style='width:73%;float:left;'>&nbsp;
					<input type='hidden' name='select-situacao-follow' id='select-situacao-follow' value='32'>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:2%;float:left;'>
					<p style='margin-top:20px; margin-left:5px'><input type='checkbox' id='enviar-email' name='enviar-email'/></p>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:10%;float:left;'>
					<p style='margin-top:22px; margin-left:5px'><label for="enviar-email" style='cursor:pointer;'><b>ENVIAR EMAIL</b></label></p>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:15%;float:left;'>
					<p>&nbsp;</p>
					<p><input type='button' value='Re-Abrir Chamado' id='botao-reabrir-chamado'  Style='width:95%'/></p>
				</div>
				<?php
					}
				}
				else{
				?>
				<?php //tinyMCE('descricao-follow');?>
				<div class='titulo-secundario' style='width:100%;'>
					<p><b>Descri&ccedil;&atilde;o</b></p>
					<p class='omega'><textarea id='descricao-follow' name='descricao-follow' style='height:60px'><?php echo $descricaoCompleta; ?></textarea></p>
				</div>
				<div class='titulo-secundario' style='width:73%;float:left;'>
					<p><b>Situa&ccedil;&atilde;o</b></p>
					<select name="select-situacao-follow" id="select-situacao-follow" style='width:98.5%%'>
						<?php
							if($textoPrimeiro==""){$textoPrimeiro="Selecione";}
							$optionValue = "<option value=''>Selecione</option>";
							$sql = "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 18 and Situacao_ID = 1 and Tipo_ID order by descr_tipo";
							$tipos = mpress_query($sql);
							while($tipo = mpress_fetch_array($tipos)){
								//if ($situacaoAtualID==$tipo['Tipo_ID']){$seleciona='selected';}else{$seleciona='';}
								$optionValue .= "<option value='".$tipo[Tipo_ID]."' $seleciona>".($tipo[Descr_Tipo])."</option>";
							}
							echo $optionValue;
						?>
					<select>
					</p>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:2%;float:left;'>
					<p style='margin-top:20px; margin-left:5px'><input type='checkbox' id='enviar-email' name='enviar-email'/></p>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:10%;float:left;'>
					<p style='margin-top:22px; margin-left:5px'><label for="enviar-email" style='cursor:pointer;'><b>ENVIAR EMAIL</b></label></p>
				</div>
				<div id='div-localizar-solicitante-cancelar' style='width:15%;float:left;'>
					<p>&nbsp;</p>
					<p><input type='button' value='Salvar' id='botao-cadastra-workflow'  Style='width:95%;'/></p>
				</div>
				<?php
				}
				?>
			</div>
		</div>

		<div class="titulo-container <?php echo $classeEsconde; ?>" id='div-historico-dados'>
			<div class="titulo">
				<div class='btn-retrair btn-expandir-retrair-historico' style='float:right;' title='Expandir'></div>
				<p>Hist&oacute;rico do chamado</p>
			</div>
			<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-historico'>
				<?php geraTabela("99%","3",$dados); ?>
			</div>
		</div>

		<div id='div-email' style='position:absolute; width:800px; height:250px; z-index:100; overflow-X:auto; display:none; border-radius:15px;'>
			<table width='100%' height='100%' border='0' cellspacing='10' cellpadding='4' bgcolor='#F9F9F9' style='border: black 1px solid'>
				<tr>
					<td colspan='2' valign='top'>
						<p style='margin:5px'><b>Enviar e-mail para:</b></p>
						<p style='margin:5px'><textarea id='emails-envio' name='emails-envio' style='height:110px;width:100%'></textarea></p>
						<p style='margin:5px'><b>* Separar e-mails com ponto e virgula <font color='red'>;</font></b></p>
					</td>
				</tr>
				<tr>
					<td align='center' width='50%'><input type='button' id='botao-submeter-email-workflow' name='botao-submeter-email-workflow' Style='width:150px' value='Enviar'></td>
					<td align='center' width='50%'><input type='button' id='botao-cancelar-email-workflow' name='botao-cancelar-email-workflow' Style='width:150px' value='Cancelar'></td>
				</tr>
			</table>
		</div>
