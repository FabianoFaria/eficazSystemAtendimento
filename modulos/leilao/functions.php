<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	global $configLeilao;
	$configLeilao = (carregarConfiguracoesGeraisModulos('leilao'));


	function leilaoCadastroSalvar(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		$leilaoID = $_POST['leilao-id'];
		$empresaID = $_POST['empresa-id'];
		$planoID = $_POST['plano-id'];
		$dataLeilao = converteDataHora($_POST['data-leilao']);
		$situacaoID = $_POST['situacao-id'];
		$titulo = utf8_decode($_POST['titulo']);
		$descricao = utf8_decode($_POST['descricao']);
		$lanceAberto = $_POST['lance-aberto'];
		$tempoInicial = $_POST['tempo-inicial'].":00";
		$tempoRenovacao = $_POST['tempo-renovacao'].":00";
		$valorLance = formataValorBD($_POST['valor-lance']);
		if ($leilaoID==""){
			$sql = "INSERT INTO leiloes_dados (Empresa_ID, Plano_ID, Titulo, Lance_Aberto, Tempo_Duracao_Inicial, Tempo_Renovacao_Lance, Valor_Lance, Descricao, Data_Leilao, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
								VALUES ('$empresaID', '$planoID', '$titulo', '$lanceAberto', '$tempoInicial', '$tempoRenovacao', '$valorLance', '$descricao', '$dataLeilao', $situacaoID, '".$dadosUserLogin['userID']."',$dataHoraAtual)";
			mpress_query($sql);
			$leilaoID = mpress_identity();
		}
		else{
			$sql = "UPDATE leiloes_dados set Empresa_ID = '$empresaID',
									Plano_ID = '$planoID',
									Titulo = '$titulo',
									Lance_Aberto = '$lanceAberto',
									Tempo_Duracao_Inicial = '$tempoInicial',
									Tempo_Renovacao_Lance = '$tempoRenovacao',
									Valor_Lance = '$valorLance',
									Descricao = '$descricao',
									Data_Leilao = '$dataLeilao',
									Situacao_ID = '$situacaoID'
									WHERE Leilao_ID = '$leilaoID'";
			mpress_query($sql);
			//echo $sql;
		}
		echo $leilaoID;
	}


	function incluirEditarLoteLeilao($leilaoLoteID){
		global $caminhoSistema, $configLeilao;

		//echo "<pre>";
		//print_r($configLeilao);
		//echo "</pre>";

		$textoBotaoIncAlt = "Incluir";
		if ($leilaoLoteID==""){
			$quantidade = "1";
		}
		else{
			$textoBotaoIncAlt = "Atualizar";
			$resultado = mpress_query(" SELECT Produto_Variacao_ID, Quantidade, Valor_Inicial, Valor_Lance, Descricao, Data_Inicio, Data_Fim, Ordem, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro
									FROM leiloes_lotes where Leilao_Lote_ID = '$leilaoLoteID'");
			if($row = mpress_fetch_array($resultado)){
				$produtoVariacaoID = $row['Produto_Variacao_ID'];
				$quantidade = $row['Quantidade'];
				$descricao = $row['Descricao'];
			}
		}

		$sql = "SELECT pv.Produto_Variacao_ID AS Produto_Variacao_ID, pv.Codigo AS Codigo_Variacao, pd.Codigo AS Codigo, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto,
					pv.Forma_Cobranca_ID, f.Descr_Tipo as Forma_Cobranca, case pv.Forma_Cobranca_ID when 35 then pv.Valor_Venda else '' end as Valor_Venda, pd.Produto_ID as Produto_ID
					FROM produtos_dados pd
					INNER JOIN produtos_variacoes pv ON pd.Produto_ID = pv.Produto_ID
					inner join tipo f on f.Tipo_ID = pv.Forma_Cobranca_ID
					WHERE pd.Situacao_ID = 1 AND pv.Situacao_ID = 1 AND pd.Produto_ID > 0 AND pv.Produto_Variacao_ID > 0
					and pd.Tipo_Produto IN (30,100)
				ORDER BY Descricao_Produto";
		$selectProdutos = "<select id='select-produtos' name='select-produtos' Style='width:98.5%' class='required'>
								<option value='' produto-id=''>Selecione</option>";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row['Valor_Venda']!="") $valorVenda = ":&nbsp;".number_format($row['Valor_Venda'], 2, ',', '.'); else $valorVenda = "";
			if ($row['Produto_Variacao_ID']==$produtoVariacaoID) $selecionado = "selected"; else $selecionado = "";
			$selectProdutos .= "<option value='".$row[Produto_Variacao_ID]."' produto-id='".$row[Produto_ID]."' $selecionado>".($row['Descricao_Produto'])."</option>";
		}
		$selectProdutos .= "			</select>";

		echo "	<fieldset style='margin-bottom:2px;'>
					<div style='float:left; width:70%;' >
						<p>Produto</p>
						<p>$selectProdutos</p>
					</div>
					<div style='width:10%;float:left;'>
						<p>Quantidade</p>
						<p><input type='text' id='quantidade-produtos' name='quantidade-produtos' value='$quantidade' class='formata-numero required' style='width:90%' maxlength='10'/></p>
					</div>
					<div id='div-produtos-incluir'  style='width:10%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' class='botao-salvar-lote' Style='width:95%' value='$textoBotaoIncAlt' leilao-lote-id='$leilaoLoteID'/></p>
					</div>
					<div id='div-produtos-cancelar' style='width:10%; float:left;'>
						<p>&nbsp;</p>
						<p><input type='button' value='Cancelar' class='botao-cancelar-lote' Style='width:95%' leilao-lote-id='$leilaoLoteID'/></p>
					</div>
					<div id='div-detalhes-produto' style='width:100%; float:left; margin-bottom:10px;'>
						<p>Descrição</p>
						<p><textarea id='descricao-lote' name='descricao-lote' class='required' style='width:99%; height:60px;'>$descricao</textarea></p>
					</div>
					<div id='div-detalhes-produto' style='width:100%; float:left;'>";
		echo "			&nbsp;
					</div>";

		if ($configLeilao['tipo-leilao']=="pos"){
		}
		echo "	</fieldset>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";

	}
	function salvarLoteLeilao(){
		global $dadosUserLogin;
		$leilaoID = $_POST['leilao-id'];
		$leilaoLoteID = $_POST['leilao-lote-id'];
		$quantidade = $_POST['quantidade-produtos'];
		$produtoVariacaoID = $_POST['select-produtos'];
		$descricaoLote = utf8_decode($_POST['descricao-lote']);
		$situacaoID = $_POST['situacao-id'];
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

		if ($leilaoLoteID==""){
			$sql = "INSERT INTO leiloes_lotes (Leilao_ID, Produto_Variacao_ID, Quantidade, Valor_Inicial, Valor_Lance, Descricao, Data_Inicio, Data_Fim, Ordem, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro)
									VALUES('$leilaoID', '$produtoVariacaoID', $quantidade, 0, 0, '$descricaoLote', NULL, NULL, 0, '$situacaoID', '".$dadosUserLogin['userID']."', $dataHoraAtual)";
		}
		else{
			$sql = "UPDATE leiloes_lotes set Produto_Variacao_ID = '$produtoVariacaoID',
										Quantidade = $quantidade,
										Descricao = '$descricaoLote' where Leilao_Lote_ID = '$leilaoLoteID'";
		}
		//echo $sql;
		mpress_query($sql);
	}

	function excluirLoteLeilao(){
		$leilaoLoteID = $_POST['leilao-lote-id'];
		$sql = "update leiloes_lotes set Situacao_ID = 2 where Leilao_Lote_ID = '$leilaoLoteID'";
		mpress_query($sql);
	}


	function carregarLotesLeilao($leilaoID){
		$sql = "SELECT ll.Leilao_Lote_ID as Leilao_Lote_ID, CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) AS Descricao_Produto, ll.Quantidade as Quantidade, ll.Descricao as Descricao
				FROM leiloes_lotes ll
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = ll.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				where ll.Leilao_ID = '$leilaoID'
				and ll.Situacao_ID not in (2,3)";
		//echo $sql;
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			echo "	<div class='leilao-editar-lote-".$rs['Leilao_Lote_ID']."'></div>
					<div class='leilao-lote-".$rs['Leilao_Lote_ID']."'>
						<fieldset style='margin-bottom:2px;'>
							<div style='float:left; width:70%;'>
								<p>Produto</p>
								<p><input type='text' value='".$rs['Descricao_Produto']."' style='width:98%' maxlength='10' readonly/></p>
							</div>
							<div style='width:10%;float:left;'>
								<p>Quantidade</p>
								<p><input type='text' value='".$rs['Quantidade']."' style='width:98%' maxlength='10' readonly/></p>
							</div>
							<div id='div-produtos-incluir'  style='width:20%; float:left;'>
								<p style='float:right; margin-top:10px;'>
									<div style='float:right; margin-right:05px;' class='btn-editar leilao-inc-alt-lote' leilao-lote-id='".$rs['Leilao_Lote_ID']."'>&nbsp;</div>
									<div style='float:right; margin-right:10px;' class='btn-excluir leilao-del-lote' leilao-lote-id='".$rs['Leilao_Lote_ID']."'>&nbsp;</div>
								</p>
							</div>
							<div id='div-detalhes-produto' style='width:100%; float:left; margin-bottom:10px;'>
								<p>Descrição</p>
								<p><textarea style='width:99%; height:20px;' readonly>".$rs['Descricao']."</textarea></p>
							</div>
						</fieldset>
					</div>";
		}
		//echo "Carregando....";

	}

	/***********************/

	function salvarPlanoLeilao(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$planoID = $_POST['plano-id'];
		$situacaoID = $_POST['situacao-id'];
		$nome = utf8_decode($_POST['plano-nome']);
		$descricao = utf8_decode($_POST['plano-descricao']);
		if ($planoID==""){
			$sql = "INSERT INTO produtos_dados
						(Nome, Descricao_Completa, Tipo_Produto, Data_Cadastro, Situacao_ID, Usuario_Cadastro_ID)
					VALUES ('$nome', '$descricao', 139, $dataHoraAtual, '$situacaoID', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$planoID = mpress_identity();
		}
		else{
			$sql = "UPDATE produtos_dados set Nome = '$nome', Descricao_Completa = '$descricao', Situacao_ID = '$situacaoID' WHERE Produto_ID = '$planoID'";
			mpress_query($sql);
		}
		echo $planoID;
	}

	function pacoteIncluirEditar(){
		global $caminhoSistema;
		$pacoteID = $_POST["pacote-id"];
		$textoBotaoIncAlt = "Incluir";
		$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";

		if ($pacoteID!=""){
			$sql = "select Produto_Variacao_ID, Descricao, Valor_Custo, Valor_Venda from produtos_variacoes where Produto_Variacao_ID = '$pacoteID' order by Valor_Venda";
			$resultado = mpress_query($sql);
			if($rs = mpress_fetch_array($resultado)){
				$descricaoPacote = $rs['Descricao'];
				$quantidadeLances = number_format($rs['Valor_Custo'], 0, ',', '.');
				$valorPacote = number_format($rs['Valor_Venda'], 2, ',', '.');
				//$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			}
			$textoBotaoIncAlt = "Alterar";
		}

		echo "	<fieldset style='margin-bottom:2px;'>
					<div style='float:left; width:100%;' >
						<div style='float:left; width:10%;'>
							<p align='center' style='margin-top:5px;'><a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:40px; max-height:40px;' src='$nomeArquivo'></a></p>
						</div>
						<div style='float:left; width:40%;'>
							<p>Descrição Pacote</p>
							<p><input type='text' id='descricao-pacote' name='descricao-pacote' value='$descricaoPacote' class='required' style='width:97%'/></p>
						</div>
						<div style='float:left; width:15%;' >
							<p>Quantidade Lances</p>
							<p><input type='text' id='quantidade-lances' name='quantidade-lances' value='$quantidadeLances' class='formata-numero required' style='width:93%'/></p>
						</div>
						<div style='float:left; width:15%;' >
							<p>Valor Pacote</p>
							<p><input type='text' id='valor-pacote' name='valor-pacote' value='$valorPacote' class='formata-valor required' style='width:93%'/></p>
						</div>
						<div style='float:right; width:10%;' >
							<p>&nbsp;</p>
							<p align='right'><input type='button' value='Cancelar' class='botao-cancelar-pacote' Style='width:99%' pacote-id='$pacoteID'/></p>
						</div>
						<div style='float:right; width:10%;'>
							<p>&nbsp;</p>
							<p align='right'><input type='button' class='botao-salvar-pacote' Style='width:99%' value='$textoBotaoIncAlt' pacote-id='$pacoteID'/></p>
						</div>
					</div>
				</fieldset>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function salvarPacoteLeilao (){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$planoID = $_POST['plano-id'];
		$pacoteID = $_POST['pacote-id'];
		$descricao = utf8_decode($_POST['descricao-pacote']);
		$valorPacote = formataValorBD($_POST['valor-pacote']);
		$quantidadeLances = formataValorBD($_POST['quantidade-lances']);
		if ($pacoteID==""){
			$sql = "INSERT INTO produtos_variacoes
						(Produto_ID, Descricao, Forma_Cobranca_ID, Imagem_ID, Valor_Custo, Valor_Venda, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
					VALUES ('$planoID', '$descricao', '35', '$imagemID', $quantidadeLances, $valorPacote, 1,  $dataHoraAtual, '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$pacoteID = mpress_identity();
		}
		else{
			$sql = "UPDATE produtos_variacoes set Descricao = '$descricao', Forma_Cobranca_ID = 35, Imagem_ID = '$imagemID',
												Valor_Custo = $quantidadeLances, Valor_Venda = $valorPacote where Produto_Variacao_ID = '$pacoteID'";
			mpress_query($sql);
		}
		echo $sql;
	}

	function excluirPacote(){
		$pacoteID = $_POST['pacote-id'];
		$sql = "update produtos_variacoes set Situacao_ID = 2 where Produto_Variacao_ID = '$pacoteID'";
		mpress_query($sql);
	}

	function carregarPacotesLeiloes(){
		global $caminhoSistema;
		$planoID = $_POST['plano-id'];
		$sql = "select Produto_Variacao_ID, Descricao, Valor_Custo, Valor_Venda from produtos_variacoes where Produto_ID = '$planoID' order by Valor_Venda";
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$nomeArquivo = $caminhoSistema."/images/geral/imagem-produto.jpg";
			echo "	<div class='pacote-editar-".$rs['Produto_Variacao_ID']."'></div>
					<div class='pacote-".$rs['Produto_Variacao_ID']."'>
						<fieldset style='margin-bottom:2px;'>
							<div style='float:left; width:100%;' >
								<div style='float:left; width:10%;'>
									<p align='center' style='margin-top:5px;'><a href='$nomeArquivo' class='fancybox' rel='fancybox'><img style='max-width:40px; max-height:40px;' src='$nomeArquivo'></a></p>
								</div>
								<div style='float:left; width:40%;'>
									<p>Descrição Pacote</p>
									<p><input type='text' value='".$rs['Descricao']."' readonly  style='width:97%'/></p>
								</div>
								<div style='float:left; width:15%;'>
									<p>Quantidade Lances</p>
									<p><input type='text' value='".number_format($rs['Valor_Custo'], 0, ',', '.')."' readonly style='width:93%'/></p>
								</div>
								<div style='float:left; width:15%;'>
									<p>Valor Pacote</p>
									<p><input type='text' value='".number_format($rs['Valor_Venda'], 2, ',', '.')."' readonly style='width:93%'/></p>
								</div>
								<div style='float:left; width:20%;'>
									<p style='float:right; margin-top:10px;'>
										<div style='float:right; margin-right:05px;' class='btn-editar pacote-inc-alt' pacote-id='".$rs['Produto_Variacao_ID']."'>&nbsp;</div>
										<div style='float:right; margin-right:10px;' class='btn-excluir pacote-del' pacote-id='".$rs['Produto_Variacao_ID']."'>&nbsp;</div>
									</p>
								</div>
							</div>
						</fieldset>
					</div>";
		}
	}


	function optionValuePlanos($selecionado){
		$sel[$selecionado] = "selected";
		$resultSet = mpress_query("select Produto_ID as Plano_ID, Nome as Plano from produtos_dados where Situacao_ID = 1 and Tipo_Produto = 139 order by Nome");
		while($rs = mpress_fetch_array($resultSet)){
			$optionValue .= "<option value='".$rs['Plano_ID']."' ".$sel[$rs['Plano_ID']].">".($rs['Plano'])."</option>";
		}
		return $optionValue;
	}

?>