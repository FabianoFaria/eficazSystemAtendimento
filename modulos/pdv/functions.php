<?php
session_start();
if (!function_exists("get_header"))
	require_once("../../includes/functions.gerais.php");
if (!function_exists("mpress_query"))
	require_once("../../config.php");

global $configPDV;
$configPDV = carregarConfiguracoesGeraisModulos('pdv');

//echo "<pre>";
//print_r($configPDV);
//echo "</pre>";

//if($_SESSION['idCaixa'] == ""){
//	$_SESSION['idCaixa'] = $_POST['idCaixa'];
//}

function registraCompraPDV(){
	global $dadosUserLogin, $caminhoFisico, $modulosGeral, $configCadastros;
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";


	$codigoEvento = $_POST['input-function-codigo'];
	$codigoFuncao = $_POST['input-function-evento'];
	if($codigoEvento != ""){
		efetuaFuncao($codigoFuncao, $codigoEvento);
	}
	/* INCLUIR REGISTRO DE PAGAMENTO */
	elseif (($_POST['input-function-evento'] == "115") || ($_POST['input-valor-finaliza'] != 0)){
		$valorFinaliza = formataValorBD($_POST['input-valor-finaliza']);
		$formaPagamento = $_POST['pdv-formas-pagamento'];

		$condicaoPagamento = 1;
		if ($formaPagamento==92){
			$condicaoPagamento = $_POST['pdv-condicao-pagamento'];
		}

		$rs = mpress_query("select coalesce(SUM(p.Valor),0) as Valor_Pago from pdv_pagamentos p where PDV_ID = '".$_SESSION['pdv-id']."'");
		$row = mpress_fetch_array($rs);
		if ($row['Valor_Pago']>= ($row['Valor_Pago'] + $valorFinaliza)){
			//echo "FINALIZOU";
		}
		else{
			$sql = "INSERT INTO pdv_pagamentos
								(					PDV_ID,    Forma_Pagamento_ID, Condicao_Pagamento, Data_Vencimento, Valor, 			   Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
						VALUES  ('".$_SESSION['pdv-id']."', '".$formaPagamento."', $condicaoPagamento, $dataHoraAtual, '$valorFinaliza', 			 1, $dataHoraAtual, ".$dadosUserLogin['userID'].")";
			//echo $sql;
			mpress_query($sql);
		}
	}
	else{
		if(($_SESSION['idCaixa'] != "") && ((trim($_POST['pdv-codigo'] != "")) || ($_POST['pdv-produto-variacao-id']!=''))){
			if ($_POST['pdv-produto-variacao-id']!=''){
				$sql = "select Produto_Variacao_ID, v.Codigo, concat(Nome, ' ', Descricao) Nome, Valor_Venda
							from produtos_dados d
							inner join produtos_variacoes v on v.Produto_ID = d.Produto_ID where Produto_Variacao_ID = '".$_POST['pdv-produto-variacao-id']."'";
			}
			else{
				$sql = "select Produto_Variacao_ID, v.Codigo, concat(Nome, ' ', Descricao) Nome, Valor_Venda
										from produtos_dados d
											inner join produtos_variacoes v on v.Produto_ID = d.Produto_ID where v.CEAN = '".$_POST['pdv-codigo']."'
									union all
						select Produto_Variacao_ID, v.Codigo, concat(Nome, ' ', Descricao), Valor_Venda
										from produtos_dados d
											inner join produtos_variacoes v on v.Produto_ID = d.Produto_ID where v.Codigo = '".$_POST['pdv-codigo']."'";
			}
			//echo $sql;
			$rs = mpress_query($sql);
			if($row = mpress_fetch_array($rs)){
				$sql ="INSERT INTO pdv_produtos	(PDV_ID, 					Atendente_ID, 		Produto_Variacao_ID, 									Quantidade, 		Valor_Unitario, 	Valor_Desconto, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
										VALUES (".$_SESSION['pdv-id'].",  '".$dadosUserLogin['userID']."', '".$row['Produto_Variacao_ID']."', '".$_POST['pdv-quantidade']."', '".$row['Valor_Venda']."', 				 0, 1, 			 $dataHoraAtual, '".$dadosUserLogin['userID']."')";
				mpress_query($sql);
			}
		}
	}
}
function mostraCompraPDV(){
	$sql = "select pp.PDV_Produto_ID as PDV_Produto_ID, v.Produto_Variacao_ID, v.Codigo, concat(d.Nome,' ',v.descricao) Nome, pp.Valor_Unitario,
				pp.Valor_Desconto, pp.Quantidade, pp.Situacao_ID
				from pdv p
				inner join pdv_produtos pp on pp.PDV_ID = p.PDV_ID
				inner join produtos_variacoes v on v.Produto_Variacao_ID = pp.Produto_Variacao_ID
				inner join produtos_dados d on d.Produto_ID = v.Produto_ID
				where p.Caixa_Numero = ".$_SESSION['idCaixa']."
					and p.PDV_ID = ".$_SESSION['pdv-id']."
					and p.Situacao_ID = 97
				order by pp.PDV_Produto_ID";
	//echo $sql;
	$i = 0;
	$rs = mpress_query($sql);
	while($row = mpress_fetch_array($rs)){
		$i++;
		$item = str_pad($i, 4, '0', STR_PAD_LEFT);
		$complemento = "";
		$complementoDesconto = "";
		$cor = "";

		$htmlComp = "";
		if($row['Tipo']==0){
			$htmlComp = "	<input type='hidden' name='campo-item-$i' value='".$row['PDV_Produto_ID']."'>
							<input type='hidden' name='produto-item-$i' value='".$row['Produto_Variacao_ID']."'>";
		}
		if($row['Valor_Desconto']!=0){
			$complemento = "***";
			$complementoDesconto = "<div Style='float:right;'><span Style='font-size:11px;'>(Desconto)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;".number_format($row['Valor_Desconto'], 2,',','.')."</div><br>";
			$cor = "color:blue;";
		}
		if($row['Situacao_ID']==2){
			$complemento = "<br>CANCELADO";
			$cor = "color:red;";
			$complementoDesconto = "";
		}
		echo "	<div class='div-linha-produto lnk' style='$cor'>
					<div Style='float:left;'>".$item." - ".$row['Nome'].$complemento."</div>
					<div Style='float:right;'>".$row['Quantidade']." x ".number_format($row['Valor_Unitario'], 2,',','.')."</div><br>
					".$complementoDesconto."
					<div Style='float:right;'>".number_format(($row['Quantidade'] * $row['Valor_Unitario']) - $row['Valor_Desconto'] , 2, ',', '.')."</div>
					".$htmlComp."
				</div>";
	}
	echo "<input type='hidden' name='quantidade-produtos-pdv' id='quantidade-produtos-pdv' value='$i'/>";
}
function localizarProdutoPDV(){
	if ($_POST['pdv-codigo']==''){
		$_POST['pdv-codigo'] = $_GET['codigo'];
	}
	$rs = mpress_query("select v.CEAN, concat(Nome, ' ', Descricao) Nome, Valor_Venda, ma.Nome_Arquivo Imagem
								from produtos_dados d
								inner join produtos_variacoes v on v.Produto_ID = d.Produto_ID
								left join modulos_anexos ma on ma.Anexo_ID = v.Imagem_ID
								where v.codigo = '".$_POST['pdv-codigo']."'
								or v.CEAN = '".$_POST['pdv-codigo']."'");
	if($row = mpress_fetch_array($rs)){
		echo $row['CEAN']."|".utf8_encode($row['Nome'])."|".$_POST['pdv-quantidade']."|".number_format($row['Valor_Venda'],2,',','.')."|".number_format($_POST['pdv-quantidade']*$row['Valor_Venda'], 2,',','.')."|".$row['Imagem'];
	}
}
function compraPDVSubTotal(){
	$sql ="select sum((v.Valor_Unitario * v.Quantidade) - v.Valor_Desconto) as Total
				from pdv
				inner join pdv_produtos v on v.PDV_ID = pdv.PDV_ID
				inner join produtos_variacoes pv on v.Produto_Variacao_ID = pv.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				where pdv.Caixa_Numero = ".$_SESSION['idCaixa']."
				and v.PDV_ID = ".$_SESSION['pdv-id']."
				and v.Situacao_ID = 1
				and pdv.Situacao_ID = 97";
	$rs = mpress_query($sql);
	$valorTotal = 0;
	if($row = mpress_fetch_array($rs)){
		$valorTotal += $row['Total'];
	}
	return $valorTotal;
}

function efetuaFuncao($funcao, $evento){
	global $configPDV;
	/**********************/
	/*** CANCELAR ITEM  ***/
	/**********************/
	if($funcao=='113'){
		$evento = (int) $evento;
		$campoCancelamento = $_POST['campo-item-'.$evento];
		$prodCancelamento  = $_POST['produto-item-'.$evento];
		mpress_query("update pdv_produtos set Situacao_ID = 2 where PDV_Produto_ID = '$campoCancelamento'");
	}

	/**********************/
	/*** CANCELAR VENDA ***/
	/**********************/
	if($funcao=='117'){
		if ($evento==$configPDV['codigo-cancelamento-venda']){
			mpress_query("update pdv set Situacao_ID = 99 where Caixa_Numero = ".$_SESSION['idCaixa']." and Situacao_ID = '97'");
		}
	}

	/*************************/
	/*** DESCONTO DE VALOR ***/
	/*************************/
	if($funcao=='116'){
		$evento = (int) $evento;
		$valor = $_POST['input-function-estorno'];
		$prodDesconto  = $_POST['campo-item-'.$evento];
		$sql = "update pdv_produtos set Valor_Desconto = '".formataValorBD($_POST['input-function-desconto'])."' where PDV_Produto_ID = '$prodDesconto'";
		//echo $sql;
		mpress_query($sql);
	}
}

function localizarProdutoBuscaPDV(){
	$sql = "select pv.Produto_Variacao_ID, pv.Codigo, concat(Nome,' ',descricao) Nome, Valor_Venda
						from produtos_variacoes pv
						inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
						where pv.Situacao_ID = 1 and pd.Situacao_ID = 1
						and concat(Nome,' ',descricao) like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%'
						or pv.codigo like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%'
						or pv.CEAN like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%'
						order by concat(Nome,' ',descricao)
						limit 100";
	//echo $sql;
	$rs = mpress_query($sql);
	while($row = mpress_fetch_array($rs)){
		$i++;
		if($i%2==1)$classe='tabela-fundo-escuro'; else $classe='tabela-fundo-claro';
		echo "	<table width='95%' border='0' align='center' Style='font-size:15px;border-left:0px' class='tabela-busca-produto lnk $classe' attr-id='".$row['Produto_Variacao_ID']."'>
					<tr>
						<td width='120' valign='top'>".utf8_encode($row['Codigo'])."&nbsp;</td>
						<td valign='top'>".utf8_encode($row['Nome'])."</td>
						<td width='90' valign='top' align='right'>".number_format($row['Valor_Venda'], 2, ',', '.')."</td>
					</tr>
				</table>";
	}
}

function selecionaFormasPagamento(){
	global $configPDV;
	$formas = $configPDV['formas-pagamento-pdv'];
	//print_r($formas);
	foreach($formas as $forma){
		$str .= $virgula.$forma;
		$virgula = ",";
	}
	$sql = "select Tipo_ID, Descr_Tipo from tipo
								where tipo_grupo_id = 25
								and Situacao_ID = 1
								and Tipo_ID in (".$str.")
							 order by Tipo_ID /*field(Tipo_ID,$str)*/";
	//echo $sql;
	$rs = mpress_query($sql);
	while($row = mpress_fetch_array($rs)){
		$i++;
		echo "<option value='".$row['Tipo_ID']."'>	".$row['Descr_Tipo']."</option>";
	}
}

function selecionaCondicoesPagamento(){
	global $configPDV;
	$finaliza = 1;
	if ($configPDV['quantidade-parcelas-pdv']!=''){
		$finaliza = $configPDV['quantidade-parcelas-pdv'];
	}
	for($i=1;$i<=$finaliza;$i++){
		echo "<option value='$i' class='pdv-finaliza-opcoes'> ".$i." X</option>";
	}
}
function confirmaFechamentoPedidoPDV(){
	echo "	<div style='text-align:center;'>
				<br>
				<br>
				<span Style='color:red;font-size:18px;'>Confirma a Finaliza&ccedil;&atilde;o?</span>
				<p><input type='button' value='CONFIRMAR!' class='pdv-confirma-compra' Style='width:140px;height:55px; font-weight:bold;'></p>
				<p><input type='button' value='CANCELAR!'  class='pdv-cancela-compra' Style='width:140px;height:40px; font-weight:bold; background-color:#BF3232;'></p>
			</div>";
}



function finalizaVendaPDV(){
	global $dadosUserLogin, $caminhoFisico, $modulosGeral, $configCadastros;
	$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";

	// (97 - 'Aberta')
	// (98 - 'Finalizada')
	// (99 - 'Cancelada');
	$situacaoID = $_GET['situacao-id'];
	if ($situacaoID==99){
		$resultSet = mpress_query("update pdv_pagamentos set Situacao_ID = 2 where PDV_ID = '".$_SESSION['pdv-id']."' and Situacao_ID = 1");
	}
	else{
		$troco = "";
		$valorCompra = compraPDVSubTotal();
		$resultSet = mpress_query("select SUM(Valor) as Valor_Pago from pdv_pagamentos where PDV_ID = '".$_SESSION['pdv-id']."' and Situacao_ID = 1");
		//echo "select SUM(Valor) as Valor_Pago from pdv_pagamentos where PDV_ID = '".$_SESSION['pdv-id']."' and Situacao_ID = 1";
		if($rs = mpress_fetch_array($resultSet)){
			$troco = $valorCompra - $rs['Valor_Pago'];
			//echo "->".$rs['Valor_Pago']." Z----Z  ".$valorCompra;
			if ($troco<0){
				$sql = "INSERT INTO pdv_pagamentos (PDV_ID, Forma_Pagamento_ID, Data_Vencimento, Valor, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
								VALUES  ('".$_SESSION['pdv-id']."', '90',  $dataHoraAtual, '$troco', 					 1, $dataHoraAtual, ".$dadosUserLogin['userID'].")";
				//echo $sql;
				mpress_query($sql);
			}
		}
		/* FINALIZANDO VENDA */
		$sql = "UPDATE pdv SET Situacao_ID = '$situacaoID' WHERE PDV_ID = '".$_SESSION['pdv-id']."'";
		$_SESSION['pdv-id'] = "";
		$_POST['cadastro-id'] = "";
		mpress_query($sql);
	}
}


function localizarClienteBuscaPDV(){
	$sql = "select cd.Cadastro_ID as Cadastro_ID, cd.Cpf_Cnpj as Cpf_Cnpj, cd.Nome as Nome from cadastros_dados cd
						where Cadastro_ID <> -1
						and (cd.Cpf_Cnpj like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%'
						or cd.Nome like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%'
						or cd.Codigo like '%".str_replace(' ','%',$_POST['campo-busca-produto'])."%')
						order by cd.Nome";
	//echo $sql;
	$rs = mpress_query($sql);
	while($row = mpress_fetch_array($rs)){
		$i++;
		if($i%2==1)$classe='tabela-fundo-escuro'; else $classe='tabela-fundo-claro';
		echo "	<table width='95%' border='0' align='center' Style='font-size:15px;border-left:0px' class='selecionar-cliente lnk $classe' attr-id='".$row['Cadastro_ID']."'>
					<tr>
						<td width='160' valign='top'>".utf8_encode($row['Cpf_Cnpj'])."&nbsp;</td>
						<td valign='top'>".utf8_encode($row['Nome'])."</td>
					</tr>
				</table>";

	}
	if ($i==0){
		echo "	<p style='text-align:center; color:red; width:80%'><b>NENHUM CLIENTE LOCALIZADO</b></p>
				<p style='text-align:center; color:red; width:80%'>DESEJA INCLUIR UM NOVO CADASTRO?</p>
				<p style='text-align:center; color:red; width:80%'><input type='button' class='incluir-cliente' value='SIM' style='width:100px'/></p>";
	}
}

function faturarVendasFinanceiro(){
	echo "em andamento";
}
?>
