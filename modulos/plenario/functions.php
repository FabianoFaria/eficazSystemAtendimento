<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $configPlenario;
	$configPlenario = carregarConfiguracoesGeraisModulos('plenario');
	//echo "<pre>";
	//print_r($configPlenario);
	//echo "</pre>";


	function carregarOrganizacaoPlenario($quantidadeCanais, $tipo){
		global $caminhoSistema, $configPlenario;
		if ($tipo=='completo'){
			$tamanho = "55%";
			$blocoTit = "	<div style='float:left; width:10%'>
								<p><b>Command</b></p>
							</div>
							<div style='float:left; width:10%'>
								<p><b>Value Open</b></p>
							</div>
							<div style='float:left; width:10%'>
								<p><b>Value Close</b></p>
							</div>";
		}
		else{
			$tamanho = "85%";
			$bloco = "";
		}
		//	CONFIGURACOES DA SESSAO
		echo "	<div class='titulo-secundario' style='float:left; width:100%; margin-top: 5px;'>
					<div style='float:left; width:15%'>
						<p><b><!--Canal-->&nbsp;</b></p>
					</div>
					<div style='float:left; width:$tamanho'>
						<p><b>Usuário</b></p>
					</div>
					$blocoTit";
		for($i = 1; $i <= $quantidadeCanais; $i++){
			if ($tipo=='completo'){
				$bloco = "	<div style='float:left; width:10%'><p><input type='text' name='command-channel[$i]' value='".$configPlenario['command-channel'][$i]."' style='width:90%'/></p></div>
							<div style='float:left; width:10%'><p><input type='text' name='value-channel-open[$i]' value='".$configPlenario['value-channel-open'][$i]."' style='width:90%'/></p></div>
							<div style='float:left; width:10%'><p><input type='text' name='value-channel-close[$i]' value='".$configPlenario['value-channel-close'][$i]."' style='width:90%'/></p></div>";
			}
			echo "	<div class='titulo-secundario' style='float:left; width:100%; margin-top: 5px;'>
						<div style='float:left; width:15%'>
							<p style='margin-top:3px;'>Canal ".str_pad($i, 2, "0", STR_PAD_LEFT)."</p>
						</div>
						<div style='float:left; width:$tamanho'>
							<p><select name='usuario-canal[$i][]' class='seleciona-usuario-canal' posicao='$i' multiple>".optionValueUsuarios($configPlenario['usuario-canal'][$i], "", "", "")."</select></p>
						</div>
						$bloco
					</div>";
		}
		echo "	</div>";
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function carregarOrganizacaoTribuna($quantidadeCanais, $sessaoID){
		global $caminhoSistema, $configPlenario;
		echo "	<div class='titulo-secundario' style='float:left; width:100%; margin-top: 5px;'>
					<div style='float:left; width:15%'>
						<p style='margin-top:3px;'>Tribuna 01</p>
					</div>
					<div style='float:left; width:85%'>
						<p><select name='tribuna-canal[1][]' class='seleciona-canal' posicao='1' /*multiple*/>".optionValueCountSelect($quantidadeCanais, $configPlenario['tribuna-canal'][1], "", "Canal")."</select></p>
					</div>
				</div>";
		echo "	<div class='titulo-secundario' style='float:left; width:100%; margin-top: 5px;'>
					<div style='float:left; width:15%'>
						<p style='margin-top:3px;'>Tribuna 02</p>
					</div>
					<div style='float:left; width:85%'>
						<p><select name='tribuna-canal[2][]' class='seleciona-canal' posicao='2' /*multiple*/>".optionValueCountSelect($quantidadeCanais, $configPlenario['tribuna-canal'][2], 0, "Canal")."</select></p>
					</div>
				</div>";
		echo "	<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}


	function salvarSessao(){
		global $dadosUserLogin, $caminhoSistema;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$tituloSessao = $_POST['titulo-sessao'];
		$tipoSessao = $_POST['tipo-sessao'];
		$canais = $_POST['usuario-canal'];
		$tribunas = $_POST['tribuna-canal'];

		$sql = "insert into sessao_workflows (Titulo, Data_Inicio, Data_Fim, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
									values  ('$tituloSessao', $dataHoraAtual, null, 148, '".$dadosUserLogin['userID']."', now())";

		mpress_query($sql);
		$sessaoID = mpress_identity();



		//echo "<pre>";
		//print_r($usuarioCanal);
		//echo "</pre>";
		//exit();

		foreach($canais as $posicao => $canal){
			foreach($canal as $usuarioCanalID){
				if ($usuarioCanalID!=""){
					$sql = "insert into sessao_posicao (Sessao_ID, Cadastro_ID, Posicao, Tipo)
												values ('$sessaoID', '$usuarioCanalID', '$posicao', 'C')";
					mpress_query($sql);
				}
			}
		}
		foreach($tribunas as $tribunaID => $tribuna){
			foreach($tribuna as $posicao){
				if ($posicao!=""){
					$sql = "insert into sessao_posicao (Sessao_ID, Cadastro_ID, Posicao, Tipo)
												values ('$sessaoID', '$tribunaID', '$posicao', 'T')";
					mpress_query($sql);
				}
			}
		}



		$sql = "insert into sessao_follows (Sessao_ID, Descricao, Fase_ID, Usuario_Cadastro_ID, Data_Cadastro)
								values ('$sessaoID', 'Sess&atilde;o Criada', 0, '".$dadosUserLogin['userID']."', $dataHoraAtual)";

		mpress_query($sql);
		echo "	<form method='post' action='$caminhoSistema/plenario/plenario-geral/plenario-gerenciar-sessao' name='frmAuxiliar'>
					<input type='hidden' id='sessao-id' name='sessao-id' value='$sessaoID'>
				</form>
				<script>document.frmAuxiliar.submit();</script>";
	}

	function atualizarSessao(){
		global $dadosUserLogin, $caminhoSistema;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$sessaoID = $_POST['sessao-id'];
		$situacaoID = $_POST['nova-situacao-id'];
		$sql = "update sessao_workflows set Data_Fim = $dataHoraAtual, Situacao_ID = 'situacaoID' where Sessao_ID = '$sessaoID'";
		mpress_query($sql);

		$sql = "insert into sessao_follows (Sessao_ID, Descricao, Usuario_Cadastro_ID, Situacao_ID, Data_Cadastro)
										values ('$sessaoID', 'Sess&atilde;o Atualializada', '$situacaoID','".$dadosUserLogin['userID']."', $dataHoraAtual)";
		mpress_query($sql);


		echo "	<form method='post' action='$caminhoSistema/plenario/plenario-geral/plenario-gerenciar-sessao' name='frmAuxiliar'>
					<input type='hidden' id='sessao-id' name='sessao-id' value=''>
				</form>
				<script>document.frmAuxiliar.submit();</script>";
	}

	function salvarFaseSessao(){
		global $dadosUserLogin, $caminhoSistema;
		$faseID = $_POST['fase-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$sessaoID = $_POST['sessao-id'];

		if ($faseID==""){
			$tipoFase = $_POST['tipo-nova-fase-id'];
			$canais = $_POST['usuario-canal'];

			$sql = "insert into sessao_fase (Sessao_ID, Tipo_Fase_ID, Situacao_ID, Data_Inicio, Usuario_Cadastro_ID, Data_Cadastro)
								values ('$sessaoID', '$tipoFase', 148, $dataHoraAtual, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			mpress_query($sql);
			$faseID = mpress_identity();
			$sql = "insert into sessao_follows (Sessao_ID, Fase_ID, Descricao, Data_Hora_Inicio, Usuario_Cadastro_ID, Data_Cadastro)
									values ('$sessaoID', '$faseID', 'Fase Iniciada', $dataHoraAtual, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			mpress_query($sql);
		}
		else{
			$tipoFase = $_POST['tipo-fase-atual-id'];
			$sql = "update sessao_fase set Situacao_ID = '150', Data_Fim = $dataHoraAtual where Fase_ID = '$faseID'";
			mpress_query($sql);
			$sql = "insert into sessao_follows (Sessao_ID, Fase_ID, Descricao, Data_Hora_Inicio, Usuario_Cadastro_ID, Data_Cadastro)
									values ('$sessaoID', '$faseID', 'Fase Finalizada', $dataHoraAtual, '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			//echo $sql;
			mpress_query($sql);
		}

		echo "	<form method='post' action='$caminhoSistema/plenario/plenario-geral/plenario-gerenciar-sessao' name='frmAuxiliar'>
					<input type='hidden' id='sessao-id' name='sessao-id' value='$sessaoID'>
				</form>
				<script>document.frmAuxiliar.submit();</script>";

	}

	function carregarAndamentoSessao($sessaoID, $dataCadastro){
		global $dadosUserLogin, $caminhoSistema;
		$sql = "select s.Fase_ID, s.Situacao_ID, ts.Descr_Tipo as Situacao_Sessao, s.Data_Inicio, t.Descr_Tipo as Tipo_Fase,
				t.Tipo_Auxiliar as Dados_Fase, s.Tipo_Fase_ID as Tipo_Fase_ID
				from sessao_fase s
				inner join tipo t on t.Tipo_ID = s.Tipo_Fase_ID
				inner join tipo ts on ts.Tipo_ID = s.Situacao_ID
				where s.Sessao_ID = '$sessaoID' and s.Situacao_ID = '148'
				order by Fase_ID desc";
		//echo $sql;
		$query = mpress_query($sql);
		if($rsF = mpress_fetch_array($query)){
			$faseID = $rsF['Fase_ID'];
			$i = 0;
			$html .= "		<div class='titulo-secundario' style='float:right; width: 40%; height:560px; margin: 0 auto; border: 1px solid #cccccc; border-radius: 5px; text-align:center;'>
								<span align='center' style='font-size:16px;'><b>Histórico</b></span>
								<div class='conteudo-interno'>
									<div>".carregarSessaoFollows($sessaoID,'resumida')."</div>
								</div>
							</div>";
			$html .= "		<div class='titulo-secundario' style='float:left; width: 59%; height:560px; margin: 0 auto; border: 1px solid #cccccc; border-radius: 5px; text-align:center;'>
								<span align='center' style='font-size:16px;'><b>".$rsF['Tipo_Fase']."</span>
								<div class='conteudo-interno'>";


			$sql = "select f.Sessao_Follow_ID, f.Fase_ID, tf.Descr_Tipo as Tipo_Fase, f.Tipo as Tipo,
						DATE_FORMAT(f.Data_Hora_Inicio,'%d/%m/%Y %H:%i:%s') as Data_Hora_Inicio,
						DATE_FORMAT(f.Data_Hora_Fim,'%d/%m/%Y %H:%i:%s') as Data_Hora_Fim,
						cr.Nome as Nome, f.Data_Cadastro,
						(select sp1.Data_Hora_Inicio from sessao_follows sp1 where sp1.Tipo = 'P' and sp1.Responsavel_Follow_ID = f.Responsavel_Follow_ID and sp1.Canal = f.Canal and sp1.Data_Hora_Fim is null limit 1) as Pausa,
						IF(((select count(*) from sessao_follows sp where sp.Tipo = 'P' and sp.Responsavel_Follow_ID = f.Responsavel_Follow_ID and sp.Canal = f.Canal and sp.Data_Hora_Fim is null) = 0),
							 (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())),
							 (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, (select sp1.Data_Hora_Inicio from sessao_follows sp1 where sp1.Tipo = 'P' and sp1.Responsavel_Follow_ID = f.Responsavel_Follow_ID and sp1.Canal = f.Canal and sp1.Data_Hora_Fim is null limit 1))))
						as Segundos,
						cr.Foto as Foto, cr.Foto as Foto, f.Descricao as Descricao, f.Canal
						from sessao_follows f
						inner JOIN sessao_fase sf ON sf.Fase_ID = f.Fase_ID AND sf.Sessao_ID = f.Sessao_ID
						inner JOIN tipo tf ON tf.Tipo_ID = sf.Tipo_Fase_ID
						inner JOIN cadastros_dados cr ON cr.Cadastro_ID = f.Responsavel_Follow_ID
						where f.Sessao_ID = '$sessaoID' and sf.Fase_ID = '$faseID'
						and Tipo IN ('T','C')
						and Data_Hora_Fim is null and Responsavel_Follow_ID <> 0
						having Segundos >= 0
						order by Sessao_Follow_ID";
			//echo "<br>$sql<br>";
			$query = mpress_query($sql);
			while($rs = mpress_fetch_array($query)){
				$i++;
				if ($rs[Tipo]=='C'){
					$tituloBloco = "Canal ".str_pad($rs['Canal'], 2, "0", STR_PAD_LEFT);
					$foto = "<img style='height:300px;' src='$caminhoSistema/uploads/".$rs[Foto]."'/>";
					$nome = $rs[Nome];
					$descricao = $rs['Descricao'];
				}

				if ($rs[Tipo]=='T'){
					$tituloBloco = "Canal ".str_pad($rs['Canal'], 2, "0", STR_PAD_LEFT);
					$foto = "<img style='height:300px;' src='$caminhoSistema/images/geral/tribuna.png'/>";
					//$nome = "Tribuna ".str_pad($rs['Cadastro_ID'], 2, "0", STR_PAD_LEFT);
					$nome = $rs['Descricao'];
					$descricao = "&nbsp;";
				}
				$segundos = $rs['Segundos'];
				$followIDAux = $rs['Sessao_Follow_ID'];
				$html .= "			<div class='titulo-secundario' style='float:left; margin: 0 auto; text-align:center; width:larguraReplace%'>
										<div style='width:100%; padding-top:5px; padding-bottom:5px;' class='destaque-tabela'>
											<span align='center' style='font-size:16px;'><b>$nome<br>$descricao</b></span><span style='float:right'>($tituloBloco)&nbsp;</span>
										</div>
										<p>$foto</p>
										<!--background-image: url($foto); background-opacity:0.4; background-repeat: no-repeat; background-position: center;-->
										<div style='width:100%; float:left; margin-top:-180px; color:red; font-weight: bold;' id='cronometro-$followIDAux' align='center' data-timer='$segundos'></div>
									</div>";
				if ($rs['Pausa']=="")
					$html .= "			<script> $('#cronometro-$followIDAux').TimeCircles({ time: { Days: { show: false }, Hours: { show: false }}, start: true, count_past_zero: false, animation_interval: 'smooth', use_background: true })</script>";
				else
					$html .= "			<script> $('#cronometro-$followIDAux').TimeCircles({ time: { Days: { show: false }, Hours: { show: false }}, start: false, count_past_zero: false, animation_interval: 'smooth', use_background: true })</script>";

			}
			$html .= "			</div>
							</div>";
			if ($i==0) $i=1;
			$html = str_replace('larguraReplace',(99.5/$i),$html);
		}
		else{
			$html .= "<div class='titulo-secundario' style='float:left; width:100%'>
						<p align='center' style='margin: 5px 5px 5px 5px;'><b>AGUARDANDO INICIO DE NOVA FASE DA SESSÃO</b></p>
					 </div>";
		}
		$query = mpress_query("select Data_Cadastro from sessao_follows where Sessao_ID = '$sessaoID' order by Data_Cadastro desc limit 1");
		if($rs = mpress_fetch_array($query)){
			$dataCadastroHist = $rs['Data_Cadastro'];
		}
		$html .= "<input type='hidden' id='dataCadastro' name='dataCadastro' value='$dataCadastroHist'/>";
		if (($dataCadastroHist!=$dataCadastro) || ($dataCadastro=="")){
			header("Content-Type: text/html; charset=ISO-8859-1",true);
			echo "<meta http-equiv='Content-type' content='text/html; charset=ISO-8859-1'/>";
			echo $html;
		}

	}

	function carregarSessaoFollows($sessaoID, $tipo){
		global $dadosUserLogin, $caminhoSistema;
		$sql = "select f.Sessao_Follow_ID, f.Fase_ID, tf.Descr_Tipo as Tipo_Fase,
					DATE_FORMAT(f.Data_Hora_Inicio,'%H:%i:%s') as Data_Hora_Inicio,
					DATE_FORMAT(f.Data_Hora_Fim,'%H:%i:%s') as Data_Hora_Fim,
					cr.Nome as Responsavel, f.Descricao, f.Canal,
					cd.Nome as Usuario_Cadastro, ts.Descr_Tipo as Situacao, f.Data_Cadastro
					from sessao_follows f
					left join sessao_fase sf on sf.Fase_ID = f.Fase_ID and sf.Sessao_ID = f.Sessao_ID
					left join tipo tf on tf.Tipo_ID = sf.Tipo_Fase_ID
					left join tipo ts on ts.Tipo_ID = sf.Situacao_ID
					left join cadastros_dados cd on cd.Cadastro_ID = f.Usuario_Cadastro_ID
					left join cadastros_dados cr on cr.Cadastro_ID = f.Responsavel_Follow_ID
					where f.Sessao_ID = '$sessaoID'
					order by Sessao_Follow_ID desc limit 24";
		//echo $sql;
		$query = mpress_query($sql);
		$i=0;

		if ($tipo=="resumida") $colunas = "4"; else $colunas = "5";

		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($rs['Responsavel']==""){
				$dados[colunas][colspan][$i][1] = $colunas;
				$dados[colunas][conteudo][$i][1] = $rs['Tipo_Fase']." - ".$rs['Descricao'];
			}
			else{
				$dados[colunas][conteudo][$i][1] = $rs['Responsavel'];
				$dados[colunas][conteudo][$i][2] = $rs['Descricao'];
				$dados[colunas][conteudo][$i][3] = "<span align='center'><b>".$rs['Data_Hora_Inicio']."</b></span>";
				$dados[colunas][conteudo][$i][4] = "<span align='center'><b>".$rs['Data_Hora_Fim']."</b></span>";
				$dados[colunas][conteudo][$i][5] = "<span align='center'>".$rs['Usuario_Cadastro']." &nbsp; &nbsp; &nbsp; <!--<br>-->".converteDataHora($rs['Data_Cadastro'],1)."</span>";
			}
		}
		if ($i>0){
			$dados[colunas][titulo][1] 	= "Respons&aacute;vel";
			$dados[colunas][titulo][2] 	= "Descrição";
			$dados[colunas][titulo][3] 	= "Inicio";
			$dados[colunas][titulo][4] 	= "Fim";
			$dados[colunas][titulo][5] 	= "<span align='center'>Usu&aacute;rio / Data Cadastro</span>";

			$html = geraTabela("99.4%",$colunas ,$dados, null, "tabela-follows-plenario-sessao", 2, 2, 24, "","return");
		}
		return $html;
	}

	// função sempre chamada para arrumar sessoes abertas perdidas, acontece quando é fechado uma sessão sem interação no browser
	function acertaFollowsAbertos($sessaoID){
		$sql = "update sessao_follows f
				INNER JOIN sessao_fase sf ON sf.Fase_ID = f.Fase_ID AND sf.Sessao_ID = f.Sessao_ID
				INNER JOIN tipo tf ON tf.Tipo_ID = sf.Tipo_Fase_ID
				INNER JOIN cadastros_dados cr ON cr.Cadastro_ID = f.Responsavel_Follow_ID
				set f.Data_Hora_Fim = DATE_ADD(f.Data_Hora_Inicio, INTERVAL tempo SECOND)
				WHERE Tipo IN ('T','C') AND Data_Hora_Fim IS NULL AND Responsavel_Follow_ID <> 0 AND (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())) <= 0";
		//"""
		mpress_query($sql);
	}

	function carregarCronometrosGerenciamentoSessao($sessaoID){
		/*
		$sql = "select Sessao_Follow_ID, Canal, (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())) Tempo_Restante, Responsavel_Follow_ID, Posicao from sessao_follows f
				INNER JOIN sessao_fase sf ON sf.Fase_ID = f.Fase_ID AND sf.Sessao_ID = f.Sessao_ID
				INNER JOIN tipo tf ON tf.Tipo_ID = sf.Tipo_Fase_ID
				INNER JOIN cadastros_dados cr ON cr.Cadastro_ID = f.Responsavel_Follow_ID
				WHERE Tipo IN ('T','C') AND Data_Hora_Fim IS NULL AND Responsavel_Follow_ID <> 0 AND (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())) > 0";
		*/
		$sql = "SELECT Sessao_Follow_ID, Canal, Responsavel_Follow_ID, Posicao,
					IF(((select count(*) from sessao_follows sp where sp.Tipo = 'P' and sp.Responsavel_Follow_ID = f.Responsavel_Follow_ID and sp.Canal = f.Canal and sp.Data_Hora_Fim is null) = 0),
						 (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())),
						 (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, (select sp1.Data_Hora_Inicio from sessao_follows sp1 where sp1.Tipo = 'P' and sp1.Responsavel_Follow_ID = f.Responsavel_Follow_ID and sp1.Canal = f.Canal and sp1.Data_Hora_Fim is null limit 1))))
					as Tempo_Restante
				FROM sessao_follows f
				INNER JOIN sessao_fase sf ON sf.Fase_ID = f.Fase_ID AND sf.Sessao_ID = f.Sessao_ID
				INNER JOIN tipo tf ON tf.Tipo_ID = sf.Tipo_Fase_ID
				INNER JOIN cadastros_dados cr ON cr.Cadastro_ID = f.Responsavel_Follow_ID
				WHERE Tipo IN ('T','C') AND Data_Hora_Fim IS NULL AND Responsavel_Follow_ID <> 0 AND (f.Tempo - TIMESTAMPDIFF(SECOND,f.Data_Hora_Inicio, NOW())) > 0";
		//echo $sql;
		$resultSet = mpress_query($sql);
		while($rs = mpress_fetch_array($resultSet)){
			$followID = $rs['Sessao_Follow_ID'];
			$canal = $rs['Canal'];
			$usuario = $rs['Responsavel_Follow_ID'];
			$tempo = $rs['Tempo_Restante'];
			$posicao = $rs['Posicao'];
			echo "<script>realizarAcao($('.btn-$posicao-$usuario-$canal'),'$followID','$tempo');</script>";
		}
	}


	function salvarAcaoGeralSessao(){
		global $dadosUserLogin, $caminhoSistema;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$sessaoID = $_POST['sessao-id'];
		$faseID = $_POST['fase-id'];
		$tipo = $_GET['tipo'];
		$canal = $_GET['canal'];
		$usuario = $_GET['usuario'];
		$tempo = $_GET['tempo'];
		$descricao = $_GET['descricao'];
		$followID = $_GET['follow'];
		$posicao = $_GET['posicao'];
		if ($followID==""){
			$sql = "insert into sessao_follows (Sessao_ID, Fase_ID, Data_Hora_Inicio, Responsavel_Follow_ID, Tipo, Tempo, Canal, Posicao, Descricao, Usuario_Cadastro_ID, Data_Cadastro)
									values ('$sessaoID', '$faseID', $dataHoraAtual, '$usuario', '$tipo', '$tempo', '$canal', '$posicao', '$descricao', '".$dadosUserLogin['userID']."', $dataHoraAtual)";
			mpress_query($sql);
			$followID = mpress_identity();
			echo $followID;
		}
		else{
			$sql = "update sessao_follows set Data_Hora_Fim = $dataHoraAtual, Data_Cadastro = $dataHoraAtual where Sessao_Follow_ID = '$followID' or Tipo = 'P'";
			mpress_query($sql);
		}

	}
?>