<?php
require_once("functions.php");
global $caminhoSistema, $configPlenario;
acertaFollowsAbertos();
$query = mpress_query("select Sessao_ID from sessao_workflows where Situacao_ID = '148' order by Sessao_ID desc");
if($rs = mpress_fetch_array($query))
	$sessaoID = $rs['Sessao_ID'];

if ($sessaoID==""){
	echo "	<div id='container-geral'>
				<div id='div-retorno'></div>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>
							Sessão
							<input type='button' class='salvar-sessao' value='Iniciar Sessão'/>
						</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:100%'>
							<p>Título Sessão</p>
							<p><input type='text' name='titulo-sessao' value='' class='required' style='width:98%'/></p>
						</div>
					</div>
				</div>";
	echo "		<div class='titulo-container' style='width:100%; float:left;'>
					<div class='titulo'>
						<p>Organização Padrão Plenário</p>
					</div>
					<div class='conteudo-interno bloco-organizacao-plenario'>";
	carregarOrganizacaoPlenario($configPlenario['quantidade-canais'],'');
	echo "			</div>
				</div>";

	echo "		<div class='titulo-container' style='width:100%; float:right;'>
					<div class='titulo'>
						<p>Convidados</p>
					</div>
					<div class='conteudo-interno bloco-organizacao-tribuna'>
						<p><select name='convidados[$i][]' class='convidados' multiple>".optionValueUsuarios($configPlenario['usuario-canal'][$i], "", "", "")."</select></p>
					</div>
				</div>";
/*
	echo "		<div class='titulo-container' style='width:100%; float:right;'>
					<div class='titulo'>
						<p>Tribuna</p>
					</div>
					<div class='conteudo-interno bloco-organizacao-tribuna'>";
	carregarOrganizacaoTribuna($configPlenario['quantidade-canais'],'');
	echo "			</div>
				</div>";
*/
	echo "	</div>";
}

if ($sessaoID!=""){
	echo "	<script type='text/javascript' src='".$caminhoSistema."/modulos/plenario/inc/TimeCircles.js'></script>
			<link rel='stylesheet' href='".$caminhoSistema."/modulos/plenario/inc/TimeCircles.css'/>";
	echo "	<input type='hidden' class='dados-config' id='host' name='host' value='".$configPlenario['host']."'/>";
	echo "	<input type='hidden' class='dados-config' id='channel-midi' name='channel-midi' value='".$configPlenario['channel-midi']."'/>";
	echo "	<input type='hidden' class='dados-config' id='device-midi' name='device-midi' value='".$configPlenario['device-midi']."'/>";
	echo "	<input type='hidden' class='dados-config' id='quantidade-canais' name='quantidade-canais' value='".$configPlenario['quantidade-canais']."'/>";
	echo "	<input type='hidden' class='dados-config' id='uso-tribuna' name='uso-tribuna' value='".$configPlenario['uso-tribuna']."'/>";

	foreach	($configPlenario['command-channel'] as $chave => $canal){
		echo "<input type='hidden' class='canais-geral' canal='$chave' command='".$configPlenario['command-channel'][$chave]."' actionOpen='".$configPlenario['value-channel-open'][$chave]."' actionClose='".$configPlenario['value-channel-close'][$chave]."'>";
	}

	$sql = "select s.Titulo, ts.Descr_Tipo as Situacao, s.Situacao_ID
			from sessao_workflows s
			inner join tipo ts on ts.Tipo_ID = s.Situacao_ID
			where s.Sessao_ID = '$sessaoID'";
	//echo $sql;
	$query = mpress_query($sql);
	if($rs = mpress_fetch_array($query)){
		$titulo = $rs[Titulo];
		$situacao = $rs[Situacao];
		$situacaoID = $rs[Situacao_ID];
		if ($situacaoID==148) $botoesAcoes = "<input type='button' class='botao-acao-plenario' acao='150' value='Finalizar Sessão'/><input type='button' class='botao-acao-plenario' acao='149' value='Cancelar Sessão'/>";

		$sql = "select s.Fase_ID, s.Situacao_ID, ts.Descr_Tipo as Situacao_Sessao, s.Data_Inicio, t.Descr_Tipo as Tipo_Fase, cd.Nome as Usuario_Cadastro,
					t.Tipo_Auxiliar as Dados_Fase, s.Tipo_Fase_ID as Tipo_Fase_ID
				from sessao_fase s
				left join tipo t on t.Tipo_ID = s.Tipo_Fase_ID
				left join tipo ts on ts.Tipo_ID = s.Situacao_ID
				left join cadastros_dados cd on cd.Cadastro_ID = s.Usuario_Cadastro_ID
				where s.Sessao_ID = '$sessaoID' and s.Situacao_ID = '148'
				order by Fase_ID desc";
		//echo $sql;
		$query = mpress_query($sql);
		if($rs = mpress_fetch_array($query)){
			$botoesAcoes = "";
			$faseConfig = unserialize($rs['Dados_Fase']);
			$i = 0;
			$botoesFalar = "";
			//echo "<pre>";
			//print_r();
			//echo "</pre>";

			foreach ($faseConfig['tempo'] as $chave => $faseBotao){
				$i++;
				if ($i==$faseConfig['a-parte']) {
					$escondeBotaoAcao = "esconde a-parte";
					$aParte = "a-parte='1'";
				}
				else{
					$escondeBotaoAcao = "";
					$aParte = "a-parte='0'";
				}
				$botoesFalar .= "<input type='button' class='btn-$i btn-$i-usuarioReplace-canalReplace botao-acao-falar acoes-tempo-usuarioReplace-canalReplace $escondeBotaoAcao' posicao='$i' canal='canalReplace' usuario='usuarioReplace' tipo='tipoReplace' tempo='$faseBotao' pausa='".$faseConfig["cronometro-pausa"]."' value='".$faseConfig['descricao'][$chave]."' title='$faseBotao' style='width:90%; height:20px; font-size:9px;' command='commandReplace' action='actionReplaceOpen' $aParte>";
			}
			$botoesFalar .= "<input type='button' class='botao-finalizar esconde botao-finalizar-usuarioReplace-canalReplace' canal='canalReplace' usuario='usuarioReplace' value='Finalizar' style='width:90%; height:20px; font-size:9px; background-color: #E58989;' align='center' command='commandReplace' action='actionReplaceClose'>";

			if ($faseConfig['uso-tribuna']==1){
				$botoesFalar .= "<input type='button' class='btn-tribuna btn-$i btn-$i-usuarioReplace-canalReplace botao-acao-falar acoes-tempo-usuarioReplace-canalReplace $escondeBotaoAcao' posicao='$i' canal='canalReplace' usuario='usuarioReplace' tipo='T' tempo='10:00' pausa='0' value='Tribuna 01' title='' style='width:90%; height:20px; font-size:9px;' command='comamandReplace' action='actionReplaceOpen'>";
				$botoesFalar .= "<input type='button' class='btn-tribuna btn-$i btn-$i-usuarioReplace-canalReplace botao-acao-falar acoes-tempo-usuarioReplace-canalReplace $escondeBotaoAcao' posicao='$i' canal='canalReplace' usuario='usuarioReplace' tipo='T' tempo='10:00' pausa='0' value='Tribuna 02' title='' style='width:90%; height:20px; font-size:9px;' command='comamandReplace' action='actionReplaceOpen'>";
			}

			$avisoFase = "	<input type='hidden' id='fase-id' name='fase-id' value='".$rs['Fase_ID']."'/>
							<div class='titulo-secundario' style='float:left; width:75%'>
								<p>Tipo Fase</p>
								<p><input type='text' name='tipo-fase' value='".$rs['Tipo_Fase']."' style='width:98%' readonly/></p>
								<input type='hidden' name='tipo-fase-atual-id' value='".$rs['Tipo_Fase_ID']."'/>
							</div>
							<div class='titulo-secundario' style='float:left; width:25%'>
								<p>Situação</p>
								<p><input type='text' name='tipo-sessao' value='".$rs['Situacao_Sessao']."' style='width:98%' readonly/></p>
							</div>";
			$botoesFase = "	<input type='button' class='finalizar-fase esconde' value='Finalizar Fase'/>
							<input type='button' class='fechar-todos-canais esconde' value='Fechar Canais'  style='margin-right:100px;'/>
							<input type='button' class='abrir-todos-canais esconde' value='Abrir Canais'/>

							<!--
							<input type='button' class='abaixa-volumes esconde' value='Abaixa Volumes' style='margin-right:100px;'/>
							<input type='button' class='aumenta-volumes esconde' value='Aumenta Volumes'/>-->

							<input type='button' class='cancelar-fase esconde' value='Cancelar Fase'/>";
		}else{
			$botoesFase = "<input type='button' class='iniciar-fase' value='Iniciar Fase'/>";
			$avisoFase = "<p align='center' class='aviso-aguardando-inicio-fase'>Aguardando iniciar nova Fase da Sessão</p>";
		}



		echo "
			<input type='hidden' name='sessao-id' id='sessao-id' value='$sessaoID'/>
			<input type='hidden' name='nova-situacao-id' id='nova-situacao-id' value=''/>
			<div id='container-geral'>
				<div id='div-retorno'></div>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Sessão - Nº $sessaoID $botoesAcoes</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:75%'>
							<p>Título Sessão</p>
							<p><input type='text' name='titulo-sessao' value='$titulo' readonly style='width:98%'/></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:25%'>
							<p>Situação</p>
							<p><input type='text' name='situacao-sessao' value='$situacao' style='width:98%' readonly/></p>
						</div>
					</div>
				</div>";

		echo "	<div class='titulo-container'>
					<div class='titulo'>
						<p>Fase Sessão $botoesFase</p>
					</div>
					<div class='conteudo-interno'>
						$avisoFase
						<div class='bloco-nova-fase esconde'>
							<div class='titulo-secundario' style='float:left; width:80%'>
								<p>Tipo Fase</p>
								<p><select id='tipo-nova-fase-id' name='tipo-nova-fase-id'>".optionValueGrupo(65, "Selecione", 0)."</select></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px'>
								<input type='button' class='incluir-nova-fase' value='Incluir' style='width:99%'/>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%; margin-top:15px'>
								<input type='button' class='cancelar-incluir-nova-fase' value='Cancelar' style='width:99%'/>
							</div>
						</div><div class='bloco-andamento-sessao'></div>";
		echo "			<div style='float:left'>
							<p>";
		$sql = "select sp.Posicao as Posicao, cd.Nome, sp.Cadastro_ID as Cadastro_ID, cd.Foto, sp.Tipo
								from sessao_posicao sp
								left join cadastros_dados cd on cd.Cadastro_ID = sp.Cadastro_ID
								where sp.Sessao_ID = '$sessaoID'
								order by sp.Tipo desc, sp.Posicao";
		$query = mpress_query($sql);
		while($rs = mpress_fetch_array($query)){
			$botoesFalarModificado = str_replace("usuarioReplace",$rs['Cadastro_ID'], str_replace("canalReplace", $rs['Posicao'], $botoesFalar));
			$botoesFalarModificado = str_replace("commandReplace",$configPlenario['command-channel'][$rs['Posicao']], $botoesFalarModificado);
			$botoesFalarModificado = str_replace("actionReplaceOpen",$configPlenario['value-channel-open'][$rs['Posicao']], $botoesFalarModificado);
			$botoesFalarModificado = str_replace("actionReplaceClose",$configPlenario['value-channel-close'][$rs['Posicao']], $botoesFalarModificado);
			if ($rs[Tipo]=='C'){
				$botoesFalarModificado = str_replace("tipoReplace", "C", $botoesFalarModificado);
				$tituloBloco = "Canal ".str_pad($rs['Posicao'], 2, "0", STR_PAD_LEFT);
				$foto = "<img style='max-height:100px;' src='$caminhoSistema/uploads/".$rs[Foto]."'/>";
				$nome = $rs[Nome];
			}
			/*
			if ($rs[Tipo]=='T'){
				$botoesFalarModificado = str_replace("tipoReplace", "T", $botoesFalarModificado);
				$tituloBloco = "Canal ".str_pad($rs['Posicao'], 2, "0", STR_PAD_LEFT);
				$foto = "<img style='max-height:100px;' src='$caminhoSistema/images/geral/tribuna.png'/>";
				$nome = "Tribuna ".str_pad($rs['Cadastro_ID'], 2, "0", STR_PAD_LEFT);
			}
			*/
			echo "			<div class='titulo-secundario' style='float:left; width:130px; margin-top: 3px; margin-left: 0.5%; border: 1px solid #cccccc; border-radius: 5px; min-height:250px'>
								<div style='float:left; width:100%; min-height:20px; background-color: #cccccc;'>
									<p align='center'><b>$tituloBloco</b></p>
								</div>
								<div style='float:left; width:100%; min-height:150px;'>
									<div style='float:left; width:100%'>
										<p align='center'>$foto</p>
									</div>
									<div style='float:left; width:100%'>
										<p align='center'><b>$nome</b></p>
									</div>
								</div>
								<div style='width:100%; color:red; font-weight: bold; margin-top:-125px;float:left;' id='cronometro-".$rs['Cadastro_ID']."-".$rs['Posicao']."' canal='".$rs['Posicao']."' usuario='".$rs['Cadastro_ID']."' posicao='' align='center'></div>
								<div style='float:left; width:100%; margin-top:3px;'>
									<p align='center' valign='bottom'>$botoesFalarModificado</p>
								</div>
							</div>";
		}
		echo "				</p>
						</div>";
		echo "		</div>
				</div>";
		carregarCronometrosGerenciamentoSessao($sessaoID);
		echo "	<div class='titulo-container'>
					<div class='titulo'>
						<p>Histórico:</p>
					</div>
					<div class='conteudo-interno'>
						<div id='conteudo-historico-follows'>";
		echo carregarSessaoFollows($sessaoID,'');
		echo "			</div>
					</div>
				</div>
			</div>";
	}
}

?>