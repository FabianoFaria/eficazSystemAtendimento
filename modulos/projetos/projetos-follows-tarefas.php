<?php
	session_start();
	header("Cache-Control: no-cache");
	header("Expires: -1");
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	$modulos = arrayModulosProjetoDescricao();
	
	$dataHoraAtual = retornaDataHora('','d/m/Y H:i');

	$rs = mpress_query("select count(*) Total from projetos_vinculos_tarefas_follows where Projeto_Vinculo_Tarefa_ID = ".$_GET['t']);
	$row = mpress_fetch_array($rs);
	$followsCadastrados = $row['Total'];
	$rs = mpress_query("select Usuario_Cadastro_ID UserID from projetos_vinculos_tarefas where Projeto_Vinculo_ID = ".$_GET['wID']." and Usuario_Cadastro_ID = ".$_SESSION[dadosUserLogin][userID]);
	if(!$row = mpress_fetch_array($rs))
		$followsCadastrados = 1;

	$rs = mpress_query("select Situacao_ID from projetos_vinculos_tarefas where Projeto_Vinculo_tarefa_ID = ".$_GET['t']);
	$row = mpress_fetch_array($rs);
	$situacaoAtual = $situacao = $row['Situacao_ID'];

	$rs = mpress_query("select Posicao from projetos_vinculos_tarefas t where t.Projeto_Vinculo_Tarefa_ID = ".$_GET['t']);
	$row = mpress_fetch_array($rs);
	$posicaoAtual = $row['Posicao'];

	if($posicaoAtual >=2 ){
		$rs = mpress_query("select Situacao_ID from projetos_vinculos_tarefas where Projeto_Vinculo_ID = ".$_GET['wID']." and posicao =(select max(posicao) from projetos_vinculos_tarefas t where t.Projeto_Vinculo_ID = ".$_GET['wID']." and posicao < $posicaoAtual)");/*"*/
		$row = mpress_fetch_array($rs);
		if($row[Situacao_ID] ==83){
			$situacao = 84;
			$msgTarefa = "<p Style='margin:15px 5px 0 5px;color:red; text-align:center'>A tarefa não pode ser iniciada por ter uma tarefa dependente aberta.</p>";
		}
	}
	$sql = "select vt.Descricao_Inicial as Descricao_Inicial, vt.Projeto_Vinculo_ID, t.Descricao, vt.Data_Cadastro, ma.Titulo Grupo_Responsavel,
						t.Titulo Tipo_Tarefa,vt.Situacao_ID, vt.Data_Limite Data_Retorno,
						r.Nome as Responsavel, cd2.Nome UserCadastro,
						v.Tabela_Estrangeira, v.Chave_Estrangeira, v.Campo_Estrangeiro, t.Tarefa_ID as Tarefa_ID,
						vt.Grupo_Responsavel_ID as Grupo_Responsavel_ID, vt.Usuario_Responsavel_ID as Responsavel_ID
						from projetos_vinculos_tarefas vt
						left join projetos_vinculos v ON vt.Projeto_Vinculo_ID = v.Projeto_Vinculo_ID
						left join tarefas t on t.Tarefa_ID = vt.Tarefa_ID
						left join modulos_acessos ma on ma.Modulo_Acesso_ID = vt.Grupo_Responsavel_ID
						left join cadastros_dados cd2 ON cd2.Cadastro_ID = vt.Usuario_Cadastro_ID
						left join cadastros_dados r ON r.Cadastro_ID = vt.Usuario_Responsavel_ID
						left join projetos_vinculos_tarefas_follows f ON f.Projeto_Vinculo_Tarefa_ID = vt.Projeto_Vinculo_Tarefa_ID
						where vt.Projeto_Vinculo_Tarefa_ID = ".$_GET['t'];
	//echo $sql;
	$rs = mpress_query($sql);
	if ($row = mpress_fetch_array($rs)){
		$tarefaDescr 	= $row['Descricao_Inicial'];
		//$descricaoInicial = $row['Descricao_Inicial'];		
		$tarefaID 		= $row['Tarefa_ID'];
		$tarefaData 	= converteDataHora($row['Data_Cadastro'],1);
		$tarefaTitulo 	= $row['Titulo'];
		$tarefaTipo 	= $row['Tipo_Tarefa'];
		$tarefaRetorno = converteDataHora($row['Data_Retorno'],1);
		$tarefaUserCad 	= $row['UserCadastro'];
		$responsavel 	= $row['Responsavel'];
		$responsavelID 	= $row['Responsavel_ID'];
		$grupoResponsavel = $row['Grupo_Responsavel'];
		$grupoResponsavelID = $row['Grupo_Responsavel_ID'];
		$tabela 		= $row['Tabela_Estrangeira'];
		$modulo 		= $modulos[$row['Tabela_Estrangeira']];
		$chaveEstrangeira	= $row['Chave_Estrangeira'];

		if ($row[Tabela_Estrangeira]=="orcamentos_workflows"){
			$resultSet = mpress_query("Select Solicitante_ID, Titulo from orcamentos_workflows where Workflow_ID = '".$chaveEstrangeira."'");
			$rsA = mpress_fetch_array($resultSet);
			$cadastroTarefaID = $rsA['Solicitante_ID'];
			$titulo = $rsA['Titulo'];
		}
		if ($row[Tabela_Estrangeira]=="chamados_workflows"){
			$resultSet = mpress_query("Select Solicitante_ID, Titulo from chamados_workflows where Workflow_ID = '".$chaveEstrangeira."'");
			$rsA = mpress_fetch_array($resultSet);
			$cadastroTarefaID = $rsA['Solicitante_ID'];
			$titulo = $rsA['Titulo'];
		}
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml">
		<head>
			<?php get_header();?>
		</head>
		<body>
			<form name='formulario-cadastra-follow' id='formulario-cadastra-follow' method='post' class='iframe-interno'>
				<input type='hidden' name='tarefa-id' id='tarefa-id' value='<?php echo $_GET['t'];?>'>
 				<input type='hidden' name='workflow-id' id='workflow-id' value='<?php echo $_GET['wID'];?>'>
<?php
	if ($cadastroTarefaID!=""){
		echo "	<div class='titulo-container' id='' style='width:99.8%;'>
					<div class='titulo'>
						<p>$modulo - <span class='link'>$chaveEstrangeira</span> $titulo</p>
							<input type='hidden' name='chave-estrangeira-tarefa' id='chave-estrangeira-tarefa' value='$chaveEstrangeira'/>
							<input type='hidden' name='tabela-estrangeira-tarefa' id='tabela-estrangeira-tarefa' value='$tabela'/>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-solicitante'>";
		carregarBlocoCadastroGeral($cadastroTarefaID, 'cadastro-tarefa-id','Solicitante',1,'','','','');
		echo "		</div>
				</div>";
	}
	echo "
				<div class='titulo-container ' id='' style='width:99.8%;'>
					<div class='titulo'>
						<p>Tarefa Cadastrada";
	if($situacaoAtual==83){
		echo "				<input type='button' class='altera-tarefa dados-estatico dados-estatico' value='Alterar'/>";
	}
	echo "				</p>
					</div>";
	echo "
					<div class='conteudo-interno dados-gerais-tarefa'>
						<div class='titulo-secundario' style='float:left; width:75%;'>
							<p><b>Tarefa:</b></p>
							<p>
								<input type='text' value='".$tarefaTipo."' style='width:99%;' readonly/>
								<!--
								<input type='text' value='".$tarefaTipo."' class='dados-estatico' style='width:99%;' readonly/>
								<span class='dados-alteravel esconde'>
									<select id='selecione-tarefa-id' name='selecione-tarefa-id' class='required'>".optionValueTarefa($tarefaID)."</select>
								</span>
								-->
							</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%;'>
							<div class='titulo-secundario dados-estatico' style='float:left; width:100%;'>
								<p><b>Data Limite:</b></p>
								<p><input type='text' value='".$tarefaRetorno."' Style='width:98.5%' readonly/></p>
								<input type='hidden' name='data-retorno-atual' value='".$tarefaRetorno."'/>
							</div>
							<div class='titulo-secundario dados-alteravel esconde' style='float:left; width:60%;'>
								<p><b>Data Limite:</b><!-- <span style='font-size:10px'>(".converteDataHora($tarefaRetorno,1).")</span>--></p>
								<p><input type='text' value='".$tarefaRetorno."' Style='width:98.5%' class='formata-data-hora required' name='data-retorno' id='campo-data-retorno-tarefa' readonly/></p>
							</div>
							<div class='titulo-secundario dados-alteravel esconde' style='float:left; width:40%;'>
								<p align='center'>Atualizar datas das pr&oacute;ximas tarefas:</p>
								<p align='center'>
									<input type='radio' name='alterar-datas-sequencia' value='S'>Sim
									<input type='radio' name='alterar-datas-sequencia' value='N' checked>N&atilde;o
								</p>
							</div>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%;'>
							<p><b>Usu&aacute;rio Solicitante:</b></p>
							<p><input type='text' value='".$tarefaUserCad."' style='width:98.5%;' readonly/></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%;'>
							<p><b>Data da Solicita&ccedil;&atilde;o:</b></p>
							<p><input type='text' value='".$tarefaData."' class='formata-data' style='width:98.5%;' readonly/></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%;'>
							<p><b>Grupo Respons&aacute;vel:</b></p>
							<p>
								<input type='text' value='".$grupoResponsavel."' class='dados-estatico' style='width:98.5%;' readonly/>
								<span class='dados-alteravel esconde'>
									<select id='grupo-responsavel' name='grupo-responsavel' class='select-grupo-atualiza-usuarios required' campo='usuario-responsavel'>".optionValueGruposAcessos($grupoResponsavelID, "", "Todos")."</select>
								</span>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%;'>
							<p><b>Usu&aacute;rio Respons&aacute;vel:</b>
							<p>
								<input type='text' value='".$responsavel."' class='dados-estatico' style='width:98.5%;' readonly/>
								<span class='dados-alteravel esconde'>
									<select id='usuario-responsavel' name='usuario-responsavel' class='required'>".optionValueUsuarios($responsavelID, "", "","Todos")."</select>
								</span>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left; width:100%;'>
							<p><b>Descrição Inicial:</b></p>
							<p>$tarefaDescr</p>
							<!--<p><textarea type='text' style='width:99.5%; height:35px' class='required' name='descricao-inicial' id='descricao-inicial' readonly>".nl2br($tarefaDescr)."</textarea></p>-->
						</div>
						<div class='titulo-secundario' style='float:left; width:100%;'>
							<p align='right' style='margin-top:5px;'>
								<input type='button' style='margin-left:5px; width:090px;' class='altera-tarefa-cancela esconde dados-alteravel' value='Cancelar'>
								<input type='button' style='margin-left:5px; width:110px;' class='altera-tarefa-confirma esconde dados-alteravel' value='Atualizar Dados'>
							</p>
						</div>
					</div>
				</div>";
?>
				<div class="titulo-container" id="cadastra-observacoes-tarefa" style="width:99.8%;">
					<div class="titulo">
						<p class='dados-estatico'>Cadastrar Observa&ccedil;&atilde;o para a Tarefa</p>
						<p class='dados-alteravel esconde'>Motivo Alteração</p>
					</div>
					<div class="titulo-secundario conteudo-interno">
						<div class="titulo-secundario" style="width:100%;float:left;margin-bottom:5px;">
							<p class="omega">
								<textarea id="descricao-tarefa-follow" name="descricao-tarefa-follow" style="height:60px;width:99.5%" class="required"></textarea>
							</p>
						</div>
						<div class="titulo-secundario bloco-acoes-tarefas" style="width:100%;float:left;">
<?php if($situacao==83){?>
<?php if($_GET['origem'] != "") $classeUtilizada = "margin-right:10px;";?>
							<div class="titulo-secundario" style="width:26%;float:right;text-align:right;margin-top:2px;" id='chamados-botoes-acao'>
								<input type="button" class="adiciona-observacao-follow" style="width:100px; <?php echo $classeUtilizada?>" value="Cadastrar" id="botao-adiciona-observacao-follow">
								<!--<input type="button" Style="margin-left:5px;" value="Encaminhar" id="botao-encaminha-tarefa" <?php echo $classeUtilizada;?>>-->
								<input type="button" Style="margin-left:5px; width:100px;" value="Finalizar" id="botao-finaliza-tarefa" <?php echo $classeUtilizada;?>>
<?php if($followsCadastrados>=1){?>
<?php if($_GET['origem'] != "") $classeUtilizada = "class='esconde'";?>
							&nbsp;&nbsp;
<?php }else{?>
								<!--<input type="button" Style="margin-left:5px;margin-right:10px;height:25px;width:090px" value="Cancelar" id="botao-cancela-tarefa" <?php echo $classeUtilizada;?>>-->
							&nbsp;&nbsp;
<?php }?>
							</div>
							<div class="titulo-secundario" style="width:49%;float:left;text-align:left;">
								Hora Inicial:&nbsp;<input type='text' Style='width:150;' class='required formata-data-meia-hora data-inicio' name='horas-inicio-tarefa-follow' id='horas-inicio-tarefa-follow' value='<?php echo $dataHoraAtual;?>' maxlength='16'>
								&nbsp;
								Hora Final:&nbsp;<input type='text' Style='width:150px;' class='required formata-data-meia-hora data-final' name='horas-final-tarefa-follow' id='horas-final-tarefa-follow' value='<?php echo $dataHoraAtual;?>' maxlength='16'>
							</div>
						</div>
						<div id='msg-horas-utilizadas' Style='color:red;width:200px;float:left;margin-left:-10px;font-size:12px;margin-top:10px;'></div>
<?php }?>
					</div>
					<script>$(document).ready(function(){$("#horas-inicio-tarefa-follow").click();});</script>
				</div>

				<div class="titulo-container esconde" id="container-encaminha-tarefa" style="width:99.8%;">
					<div class="titulo">
						<p Style='margin-top:3px;margin-left:5px;font-size:12px;'>Encaminhar Tarefa</p>
					</div>
					<div class="titulo-secundario uma-coluna" style="margin-top:5px;padding:0 0 5px 5px;font-size:12px;" id='mostra-detalhes-encaminha-tarefa'>
						<div class='titulo-secundario' style='width:75%;float:left;margin-bottom:5px;'>
							<p>
								Tipo da Tarefa
								<select name="select-tipo-tarefa" id="select-tipo-tarefa"  style='width:99.4%' class='required-e'>
									<?php echo optionValueGrupoFilho(40, "", "Selecione o tipo da tarefa desejada");?>
								<select>
							</p>
						</div>

					<div class='titulo-secundario' style='width:24%;float:left;margin-bottom:5px;margin-left:1%'>
							<p>
								Data Limite da tarefa:
								<input type='text' name='tarefa-data-limite' id='tarefa-data-limite' class='formata-data-hora required-e' Style='width:89%'>
							</p>
					</div>
						<div class='titulo-secundario' style='width:35%;float:left;'>
							<p>Grupo Respons&aacute;vel</p>
							<select name="select-grupo-tarefa" id="select-grupo-tarefa" style='width:98.5%' class='required-e' campo='select-usuario-tarefa'>
								<option value=''></option>
<?php
	$grupos = mpress_query("select distinct m.Modulo_Acesso_ID as Modulo_Acesso_ID, m.Titulo as Titulo
							from modulos_acessos m
							inner join cadastros_dados c on c.Grupo_ID = m.Modulo_Acesso_ID
							where c.Situacao_ID = 1 and m.Situacao_ID = 1 order by Titulo");
	while($row = mpress_fetch_array($grupos)){
		echo " 						<option value='".$row['Modulo_Acesso_ID']."' $selecionado>".$row['Titulo']."</option>";
	}
?>
							<select>
							</p>
						</div>
						<div class='titulo-secundario' style='width:38.9%;float:left;margin-left:10px;'>
								Usuário Responsável
								<div id='div-select-usuario-tarefa'>
									<select name="select-usuario-tarefa" id="select-usuario-tarefa" style='width:98.5%' disabled >
										<option value=''></option>
									<select>
								</div>
						</p>
						</div>
						<div class='titulo-secundario' style='width:23%;float:right;margin-left:0px;margin-top:18px;text-align:right;margin-right:12px;'>
							<!--<input type="button" Style="margin-left:5px;height:25px;width:090px" value="Encaminhar" id="botao-encaminha-tarefa-finaliza" <?php echo $classeUtilizada;?>>-->
							<!--<input type="button" Style="margin-left:5px;margin-right:0px;height:25px;width:080px" value="Cancelar" id="botao-cancela-tarefa-finaliza" <?php echo $classeUtilizada;?>>-->
							</div>
						</div>
					</div>
				</div>
				<div class="titulo-container " id="observacoes-cadastradas-tarefa" style="width:99.8%;">
					<div class="titulo">
						<p Style='margin-top:3px;margin-left:5px;font-size:12px;'>Observa&ccedil;&otilde;es Cadastradas para a Tarefa</p>
					</div>
					<div class="conteudo-interno">
						<div class="titulo-secundario uma-coluna">
<?php
	global $caminhoSistema;
	$rs = mpress_query("select f.Descricao, f.Data_Cadastro, d.Nome, f.Hora_Inicio, f.Hora_Fim, timediff(f.Hora_Fim,f.Hora_Inicio) Horas_Utilizadas
						from projetos_vinculos_tarefas_follows f
						left join cadastros_dados d on d.Cadastro_ID = f.Usuario_Cadastro_ID
						where Projeto_Vinculo_Tarefa_ID = ".$_GET['t']." order by Projeto_Vinculos_Tarefas_Follow_ID desc");
	while($row = mpress_fetch_array($rs)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<p Style='text-align:left;font-size:11px;'>".converteDataHora($row['Data_Cadastro'],1)."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".nl2br($row['Descricao'])."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='text-align:left;font-size:11px;'>&nbsp;".$row['Nome']."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='text-align:left;margin:2px 2px 0 2px;float:left;font-size:11px;'>Inicio:&nbsp;".converteDataHora($row['Hora_Inicio'],1)."<br>Final:&nbsp;&nbsp;".converteDataHora($row['Hora_Fim'], 1)."<br>Horas Utilizadas:&nbsp;&nbsp;&nbsp;".substr($row['Horas_Utilizadas'], 0, strlen($row['Horas_Utilizadas'])-3)."</p>";

		$horasInicio 	= substr($row['Horas_Utilizadas'], 0, strlen($row['Horas_Utilizadas'])-3);
		$horasTarefa 	= substr($horasInicio, 0, strlen($horasInicio)-3);
		$minutosTarefa 	= substr($horasInicio, -2);
		$horasTotais 	+= $horasTarefa;
		$minutosTotais 	+= $minutosTarefa;
	}
	$horasMinutos 	= (int)($minutosTotais/60);
	$minutosTotais  = $minutosTotais%60;
	$horasTotais	+= $horasMinutos;

	if(mpress_count($rs)>=1){
		$dados[colunas][titulo][1] 	= "Data Cadastro";
		$dados[colunas][titulo][2] 	= "Observa&ccedil;&atilde;o";
		$dados[colunas][titulo][3] 	= "Usuario Cadastro";
		$dados[colunas][titulo][4] 	= "<center>Tempo Gasto</center>";

		$dados[colunas][tamanho][1] = "width='090px;'";
		$dados[colunas][tamanho][3] = "width='180px'";
		$dados[colunas][tamanho][4] = "width='130px'";

		geraTabela("99.3%","4",$dados);
		echo "<p Style='margin:5px 5px 0 5px; text-align:center;font-size:11px;'><b>Total de Horas Utilizadas:</b>&nbsp;$horasTotais horas e $minutosTotais minutos</p>";

	}
	else{
		if($msgTarefa == "")
			echo "<p Style='margin:15px 5px 0 5px;color:red; text-align:center'>Nenhuma tarefa localizada</p>";
		else
			echo $msgTarefa;
	}
?>
						</div>
					</div>
				</div>
			</form>
		</body>
	</html>