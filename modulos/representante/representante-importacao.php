<?php
	//echo "<meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>";
	global $caminhoFisico,$caminhoSistema;
	$userID = $_SESSION[dadosUserLogin][userID]*-1;
	$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$userID' and meta_key = 'representantes_pagamento'");
	if($row = mpress_fetch_array($rs)){
		$dadosBoleto 	= unserialize(unserialize($row['meta_value']));
		$bancoCliente 	= $dadosBoleto['boleto']['banco'];
		$numConvenio 	= $dadosBoleto['boleto']['conveniocobranca'];
	}
	$bancos['033'] = "Banco Santander";
	$bancos['001'] = "Banco do Brasil";
	//echo "$userID - $bancoCliente";

	if($_FILES['arquivo-upload']['type'] != "text/plain"){
		$mensagemImportacao  = "Tipo de arquivo não permitido!";
	}else{
		$nomeArquivoUpload 	= $caminhoFisico."/uploads/".$userID."-".date('dmYHi')."-arquivo-baixa-boleto.txt";
		move_uploaded_file($_FILES["arquivo-upload"]["tmp_name"],  $nomeArquivoUpload);
		chmod($nomeArquivoUpload,0777);

		/*
		$rs = mpress_query("select meta_value from wp_postmeta where post_id = '$userID' and meta_key = 'representantes_pagamento'");
		if($row = mpress_fetch_array($rs)){
			$dadosBoleto 	= unserialize(unserialize($row['meta_value']));
			$bancoCliente 	= $dadosBoleto['boleto']['banco'];
			$numConvenio 	= $dadosBoleto['boleto']['conveniocobranca'];
		}
		*/

		if($bancoCliente=='001'){
			$retorno = importaArquivoBB($numConvenio,$nomeArquivoUpload,$userID);
			$mensagemImportacao = $retorno['mensagem'];
		}else if($bancoCliente=='033'){
			$retorno = importaArquivoSantander($nomeArquivoUpload,$userID);
			$mensagemImportacao = $retorno['mensagem'];
		}else{
			$mensagemImportacao = "ERRO: Arquivo para o banco $bancoCliente não configurado.";
		}
	}
?>
<div id='importar-container'>
	<div class='titulo-container'>
		<div class='titulo' style="min-height:25px">
			<p style="margin-top:2px;">
			Importa&ccedil;&atilde;o Arquivo Bancário - <?php echo $bancos[$bancoCliente];?>
			<input type="button" value="Importar" id="botao-importacao" class='esconde' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:100px;">
			</p>
		</div>
		<div class='conteudo-interno titulo-secundario'>
			<div style='float:left;'>
				<p>
					<input type='file' name='arquivo-upload' id='arquivo-upload' style='width:400px;'/>
					<input type='button' value='Importar dados' id='botao-importa-arquivo' style='width:150px;'>
				</p>
			</div>
			<div style='float:left;margin-left:30px;margin-top:-5px;'>
				<p>&nbsp;</p>
				<p><div id='nome-arquivo-tmp' Style='text-align:left;'><b style='color:red;'><?php if($_POST)echo $mensagemImportacao;?></b></div></p>
			</div>
			<div style='width:40%;float:left;'>
				&nbsp;
				<p id='mensagem-aguarde' class='esconde' style='text-align:left;'> </p>
				<input type='hidden' name='arquivo-importar' id='arquivo-importar' value=''/>
			</div>
		</div>
	</div>
	<div class='titulo-container esconde' id='div-arquivo-aberto'>
		<div class='titulo' style="min-height:25px">
			<p style="margin-top:2px;">Arquivo</p>
		</div>
		<div class='conteudo-interno titulo-secundario' id='div-retorno'>
		</div>
	</div>

	<?php echo $retorno['detalhes'];?>


</div>

<?php
	function realizarBaixaPagamento($codPedido, $fornecedorID, $linha){
		$situacaoPagamento = mpress_query("select tf.Situacao_ID, tw.Solicitante_ID, s.Descr_Tipo as Situacao_Atual from telemarketing_follows tf 
											inner join telemarketing_workflows tw on tw.Workflow_ID = tf.Workflow_ID
											inner join tipo s on s.Tipo_ID = tf.Situacao_ID
											where tw.workflow_id = '$codPedido' 
											and Fornecedor_ID = $fornecedorID 
											order by Follow_ID desc limit 1");
		if ($baixaPgto = mpress_fetch_array($situacaoPagamento)){
			if($baixaPgto['Situacao_ID'] == '39'){
				mpress_query("update wp_produtos_cadastros_pedidos set Situacao = 'Pagamento Efetuado' where Produto_cadastro_Pedido_ID = $codPedido");
				mpress_query("insert into telemarketing_follows(Workflow_ID,Descricao,Situacao_ID,Usuario_Cadastro_ID)values($codPedido,'Baixa de pagamento por arquivo<br>Dados do registro: <br> $linha',42,".($fornecedorID * -1).")");
				$dadosArquivo .= "	<tr><td Style='background-color:#f1f1f1;padding:5px;text-align:center;color:blue;'>Pedido <b>$codPedido</b> baixado com sucesso.</td></tr>";
			}else{
				$dadosArquivo .= "	<tr><td Style='background-color:#f1f1f1;padding:5px;text-align:center;color:green;'>Pedido <b>$codPedido</b> não disponível para baixa, situação atual: <b>".$baixaPgto['Situacao_Atual']."</b></td></tr>";
			}
		}
		else{
			$dadosArquivo .= "	<tr><td Style='background-color:#f1f1f1;padding:5px;text-align:center;color:red;'>Pedido <b>$codPedido</b> não localizado no sistema.</td></tr>";
		}
		return $dadosArquivo;
	}


	function importaArquivoSantander($nomeArquivoUpload,$userID){
		$fornecedorID = $userID;
		$data = fopen($nomeArquivoUpload, "r");
		$workflow = mpress_fetch_array(mpress_query("select max(Workflow_ID) Max from telemarketing_workflows"));
		$maxID = $workflow['Max'];
		$baixaEfetuada = 0;
		while(!feof($data)) {
			$i++;
			$linha = fgets($data, 4096);
			$linha = str_replace('	',' ',$linha);
			if($i>=9){
				$campos[$i] = explode(' ',$linha);
				if($campos[$i][1] != ""){
					$codPedido = substr($campos[$i][1],0,-1);
					if($codPedido > $maxID){
						$codPedido = substr($codPedido, (strpos($codPedido,"10") + 2));
					}
					if(($codPedido <= $maxID)&&(trim($codPedido)!="")){
						$baixaEfetuada++;
						$dadosArquivo .= realizarBaixaPagamento($codPedido, $fornecedorID, $linha);
					}
				}
			}
		}
		if($baixaEfetuada==0){
			$dadosArquivo .= "	<tr><td Style='background-color:#f9f9f9;padding:10px;font-size:14px;text-align:center;'>Nenhuma baixa localizada no Sistema!</td></tr>";
		}
		$dados['detalhes'] = "<table width='500' align='center' cellpadding='3' cellspacing='3' Style='margin:0 auto;'>".$dadosArquivo."</table>";
		$dados['mensagem'] 	= "Arquivo Importado com sucesso!";
		return $dados;
	}
	
	function importaArquivoBB($numConvenio,$nomeArquivoUpload,$userID){
		$fornecedorID = $userID;
		$workflow = mpress_fetch_array(mpress_query("select max(Workflow_ID) Max from telemarketing_workflows"));
		$maxID = $workflow['Max'];
		$data = fopen($nomeArquivoUpload, "r");
		$baixaEfetuada = 0;		
		while(!feof($data)) {
			$linha = fgets($data, 4096);
			$liquidado 	= strpos($linha,'Liquidado');
			$nossoNum	= strpos($linha,$numConvenio);
			if($liquidado >= 1){
				if($nossoNum >= 1){
					$j++;
					$codInicio 	 = substr($linha,$nossoNum,strlen($linha));
					$codPedido	 = substr((int) str_replace($numConvenio,'',substr($codInicio,0,strpos($codInicio,'	'))),0,10);
					if($codPedido > $maxID){
						$codPedido = substr($codPedido, 2,10);
					}
					if(($codPedido <= $maxID)&&(trim($codPedido)!="")){
						$baixaEfetuada++;
						$dadosArquivo .= realizarBaixaPagamento($codPedido, $fornecedorID, $linha);
					}
				}
			}
		}
		fclose($data);
		if($baixaEfetuada==0){
			$dadosArquivo .= "	<tr><td Style='background-color:#f9f9f9;padding:10px;font-size:14px;text-align:center;'>Nenhuma baixa localizada no Sistema!</td></tr>";
		}
		$dados['detalhes'] = "<table width='500' align='center' cellpadding='3' cellspacing='3' Style='margin:0 auto;'>".$dadosArquivo."</table>";
		$dados['mensagem'] 	= "Arquivo Importado com sucesso!";
		return $dados;
	}


?>
