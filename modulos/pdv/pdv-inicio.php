<?php
	include('functions.php');
	$idCaixa = $_POST['idCaixa'];
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
	if($idCaixa==''){
		include('pdv-login-caixa.php');
	}
	else{
		/*******************************************/
		/* INSERINDO REGISTRO DE OCUPAÇÃO DO CAIXA */
		/*******************************************/
		$sql = "SELECT PDV_ID from pdv where Situacao_ID = 97 and Atendente_ID = '".$dadosUserLogin['userID']."' and Caixa_Numero = '$idCaixa'";
		$resultset = mpress_query($sql);
		if($rs = mpress_fetch_array($resultset)){
			$_SESSION['pdv-id'] = $rs['PDV_ID'];
			$_SESSION['idCaixa'] = $idCaixa;
		}
		else{
			$sql = "INSERT INTO pdv (Atendente_ID, 						Caixa_Numero, 	Cliente_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
						VALUES ('".$dadosUserLogin['userID']."', '".$_POST['idCaixa']."', 			 0, 		 97, ".$dataHoraAtual.", '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$_SESSION['pdv-id'] = mpress_identity();
		}

		$resultset = mpress_query("select p.Cliente_ID from pdv p where p.PDV_ID = ".$_SESSION['pdv-id']);
		if($rs = mpress_fetch_array($resultset)){
			/*********************************************/
			/*	TRECHO RESPONSAVEL POR ATUALIZAR CLIENTE */
			/*********************************************/
			if (($_POST['cadastro-id']!='') && ($_POST['cadastro-id'] != $rs['Cliente_ID'])){
				$sql = "update pdv set Cliente_ID = ".$_POST['cadastro-id']." where PDV_ID = ".$_SESSION['pdv-id'];
				mpress_query($sql);
			}
			else{
				$_POST['cadastro-id'] = $rs['Cliente_ID'];
			}
		}
		$resultset = mpress_query("select p.Cliente_ID, cd.Nome as Nome_Cliente
										from pdv p
										inner join cadastros_dados cd on cd.Cadastro_ID = p.Cliente_ID
										where PDV_ID = ".$_SESSION['pdv-id']);
		if($rs = mpress_fetch_array($resultset)){
			$nomeCliente = $rs['Nome_Cliente'];
		}

		if($_SERVER['REQUEST_METHOD']=='POST'){
			$request = md5(implode($_POST));
			if(isset($_SESSION['last_request']) && $_SESSION['last_request']== $request){
				//echo 'refresh';
			}
			else{
				$_SESSION['last_request']  = $request;
				//echo 'post';
				registraCompraPDV();
			}
		}


		/*********************/
		/* BUSCA VALOR TOTAL */
		/*********************/
		$valorTotal = compraPDVSubTotal();

		/*********************************************/
		/* VERIFICA SE O PAGAMENTO ESTA EM ANDAMENTO */
		/*********************************************/

		$textoValor = "Sub Total:";

		$sql = "SELECT fp.Descr_Tipo as Forma_Pagamento, Valor, Condicao_Pagamento
								FROM pdv_pagamentos pp
								inner join tipo fp on fp.Tipo_ID = pp.Forma_Pagamento_ID
								where pp.PDV_ID = ".$_SESSION['pdv-id']." and pp.Situacao_ID = 1
								order by pp.PDV_Pagamento_ID";
		//echo $sql;
		$rsPgto = mpress_query($sql);
		$i = 0;
		unset($arrPgtos);
		$exibePagamentoAndamento = "esconde";
		while($rs = mpress_fetch_array($rsPgto)){
			$i++;
			$escondePagamentoAndamento = "esconde";
			$exibePagamentoAndamento = "";
			$textoValor = "TOTAL:";
			$arrPgtos[$i]['Forma_Pagamento'] = $rs['Forma_Pagamento'];
			$arrPgtos[$i]['Condicao_Pagamento'] = $rs['Condicao_Pagamento'];
			$arrPgtos[$i]['Valor'] = $rs['Valor'];
			$andamento = "pagamento";
			$valorPago += $rs['Valor'];
		}
		$saldo = $valorTotal - $valorPago;
		if ($i!=0){
			if(($saldo>0)){
				$textoSaldo = "SALDO A PAGAR:";
				$corSaldo = "color:blue;";
			}
			else{
				$textoSaldo = "TROCO:";
				$corSaldo = "color:red;";
				$andamento = "finalizado";
				$escondePagamentoAndamento = "esconde";
				$exibePagamentoAndamento = "esconde";
			}
		}
?>
		<input type='hidden' name="andamento" id="andamento" value="<?php echo $andamento; ?>"/>
		<div id='pdv-container-principal'>
			<div id='div-produto-superior'>
				<div id='div-produto-superior-left' class='numero-pdv' title='Encerrar Caixa'>
					<span id='texto-caixa-numero'>Caixa Nº</span>
					<br>
					<span id='caixa-numero'><?php echo $idCaixa;?></span>
				</div>
				<div id='div-produto-superior-centro' Style="background: url('<?php echo $caminhoSistema;?>/uploads/<?php echo $_POST['produto-imagem']?>') no-repeat center center"></div>
				<div id='div-produto-superior-right'>
					<p id='produto-superior-nome'><?php echo $_POST['produto-nome']?></p>
					<p id='produto-superior-busca' class='esconde' Style='margin:0;padding:0'>
						<input type='text' Style='height:48px;width:97.7%;margin-top:5px;margin-left:5px;font-size:20px;text-transform:uppercase;padding:8px;' id='campo-busca-produto' name='campo-busca-produto'>
					</p>
				</div>
			</div>
			<div id='div-produto-esquerdo'>
				<div id='div-produto-esquerdo-funcao' class='<?php echo $exibePagamentoAndamento; ?>'>
					<div id='div-produto-esquerdo-funcao-superior' class='produto-esquerdo-funcao'>
						<div id='produto-esquerdo-funcao-superior-titulo'></div>
						<div id='div-executa-funcoes'>
							<input type='text' name='input-function-codigo' id='input-function-codigo' maxlength='20' class='pdv-campo-grande' style='text-align:center;' value=''>
							<input type='hidden' name='input-function-evento' id='input-function-evento'>
						</div>
						<div id='div-forma-pagamento' class='<?php echo $exibePagamentoAndamento; ?>'>

							<div style='width:100%; float:left;' class='forma-pagamento'>
								<p style='color:red; font-size:11px;'>Forma de Pagamento</p>
								<p><select class='campo-funcao' id='pdv-formas-pagamento' name='pdv-formas-pagamento'>
									<!--<option value='Selecione'>Selecione</option>-->
									<?php selecionaFormasPagamento();?>
								</select></p>
							</div>

							<div style='width:25%; float:left;' class='parcelamento-credito esconde'>
								<p style='color:red; font-size:11px; width:50%;'>Parcelamento</p>
								<p>
									<select class='campo-funcao' id='pdv-condicao-pagamento' name='pdv-condicao-pagamento'>
										<?php selecionaCondicoesPagamento();?></p>
									</select>
								</p>
							</div>
							<div style='width:100%; float:left;' class='valor-pago'>
								<p style='color:red; font-size:11px;'>Valor Pago</p>
								<p><input type='text' name='input-valor-finaliza' id='input-valor-finaliza' class='pdv-campo-grande formata-valor zero-nao' style='text-align:center; width: 99%;'></p>
							</div>
							<!--
							<p style='color:red; font-size:11px;'>Condição de Pagamento</p>
							-->
						</div>

						<div id='produto-esquerdo-funcao-inferior-titulo' class='esconde' Style='margin-top:10px'>Valor Desconto</div>
						<div id='produto-esquerdo-funcao-inferior-campos' class='esconde'>
							<input type='text' name='input-function-desconto' id='input-function-desconto' class='pdv-campo-grande formata-valor' style='text-align:center;'>
						</div>
					</div>
					<div class='produto-esquerdo-funcao'><p class='f10'><b>F10 ou ESQ</b> - Voltar</p></div>
				</div>
<?php
	if ($andamento=='finalizado'){
		echo "	<div id='div-produto-esquerdo-container'>";
		confirmaFechamentoPedidoPDV();
		echo "	</div>";
	}
	else{
		echo "	<div id='div-produto-esquerdo-container' class='".$escondePagamentoAndamento."'>
					<div class='produto-esquerdo-container f1'><p><b>F1</b> - Identificar Cliente <br></p></div>
					<div class='produto-esquerdo-container f2'><p><b>F2</b> - Cancelar Ítem</p></div>
					<div class='produto-esquerdo-container f3'><p><b>F3</b> - Localizar Produto</p></div>
					<div class='produto-esquerdo-container f4'><p><b>F4</b> - Finalizar Venda</p></div>
					<div class='produto-esquerdo-container f5'><p><b>F5</b> - Aplicar Desconto</p></div>
					<div class='produto-esquerdo-container f6'><p><b>F6</b> - Cancelar Venda</p></div>
					<div class='produto-esquerdo-container f7'><p><b>F7</b> - Consultar Preço</p></div>
					<div class='produto-esquerdo-container f8'><p><b>F8</b> - Encerrar Caixa</p></div>
				</div>";
	}
?>
				<div id='div-produto-centro'>
					<div id='div-produto-centro-produtos'><?php mostraCompraPDV();?></div>
					<div id='div-produto-centro-busca' Style='margin-top:10px;float:right; width:100%'></div>
					<div id='div-produto-centro-finaliza' Style='margin-top:10px;float:right;'></div>
				</div>
				<div id='div-produto-direito-superior' class='<?php echo $escondePagamentoAndamento; ?>'>
					<div class='produto-direito-superior'>
						<p>&nbsp;Código:</p>
						<input type='text' id='pdv-codigo' name='pdv-codigo' class='pdv-campo-grande' maxlength='20'>
						<input type='hidden' id='pdv-produto-variacao-id' name='pdv-produto-variacao-id' />
					</div>
					<div class='produto-direito-superior'>
						<p>&nbsp;Quantidade:</p>
						<input type='text' id='pdv-quantidade' name='pdv-quantidade' class='pdv-campo-grande' value='1' readonly style='text-align:center;' maxlength='3'>
					</div>
					<div class='produto-direito-superior'>
						<p>&nbsp;Valor Unit&aacute;rio:</p>
						<input type='text' id='pdv-valor-unitario'  name='pdv-valor-unitario' class='pdv-campo-grande' readonly>
					</div>
				</div>
				<div id='div-produto-direito-inferior'>
					<div class='produto-direito-superior'>
						<p class='sub-total'>&nbsp;<?php echo $textoValor; ?></p>
						<p style='text-align:right; padding-right:20%; font-size:30px;text-weight:bold;font-weight:bold; margin:2px;' id='total-geral'><?php echo number_format($valorTotal, 2, ',', '.');?></p>
					</div>
<?php
	if (is_array($arrPgtos)){
		echo "	<div class='produto-direito-superior'>
					<div class='produto-direito-superior'>
						<p class='sub-total-pago'>PAGO:</p>";
		$ii = 0;
		foreach($arrPgtos as $pgto){
			$ii++;
			$condicaoPagamento = "";
			if ($pgto['Condicao_Pagamento']){
				$condicaoPagamento = " ".$pgto['Condicao_Pagamento']." X";
			}
			echo "		<p class='sub-total-pago' style='text-align:right; padding-right:20%; font-size:30px;text-weight:bold;font-weight:bold; margin:2px;'>
							<b style='font-size:12px;'>(".$pgto['Forma_Pagamento']."$condicaoPagamento)</b>
							<b>".number_format($pgto['Valor'],'2',',','.')."</b>
						</p>";
		}
		if ($ii){
			echo "		<p class='sub-total-pago' style='border-bottom: 1px solid red;'>$textoSaldo</p>";
			echo "		<p class='sub-total' style='".$corSaldo."text-align:right; padding-right:20%; font-size:30px;text-weight:bold;font-weight:bold; margin:2px;'><b id='saldo-a-pagar'>".number_format(($saldo),'2',',','.')."</b></p>";
			echo "		<input type='button' value='Extornar Valores' class='pdv-cancela-compra' Style='width:140px;height:20px; font-weight:bold; background-color:#BF3232;'>";
		}
		echo "		</div>
				</div>";
	}
?>
					<div class='produto-direito-superior'>
						<p>&nbsp;Cliente Identificado:</p>
						<input type='hidden' name='cadastro-id' id='cadastro-id' value='<?php echo $_POST['cadastro-id']; ?>'/>
						<input type='text' id='pdv-cliente' name='pdv-cliente' class='pdv-campo-grande' style='font-size:16px !important;' readonly value="<?php echo $nomeCliente;?>"/>
					</div>
				</div>
			</div>
		</div>

		<input type='hidden' name='fator' id='fator'>
		<input type='hidden' name='idCaixa' value='<?php echo $idCaixa;?>'>
		<input type='hidden' name='produto-nome' id='produto-nome'>
		<input type='hidden' name='produto-imagem' id='produto-imagem'>
		<input type='hidden' name='finaliza-compra' id='finaliza-compra'>
<?php }?>
<!--
<img src='http://www.conectivasistemas.com/images/sisloja_pdv_1.jpg'>
-->
