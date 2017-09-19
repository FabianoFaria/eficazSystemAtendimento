<?php
	global $tituloSistema;
	if(!mpress_fetch_array(mpress_query("select * from cadastros_dados cd where md5(cd.Cadastro_ID) = '".$_GET['oc']."'")))
		include('login.php');
	else{
		$dadosOrcamento = mpress_query("select cd.Cadastro_ID, cd.Nome, p.Codigo, p.Nome Produto, pv.Produto_Variacao_ID, sum(cs.Quantidade) Quantidade
										from compras_ordem_compra c
										inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
										inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
										inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
										inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
										inner join produtos_fornecedores f on f.Produto_ID = pv.Produto_ID
										inner join cadastros_dados cd on cd.Cadastro_ID = f.Cadastro_ID and md5(cd.Cadastro_ID) = '".$_GET['oc']."'
										where data_limite_retorno >= now() and c.Situacao_ID = 1 and cs.Situacao_ID = 63 and p.Situacao_ID = 1 and cd.Situacao_ID = 1
										and c.Ordem_Compra_ID  not in (select Ordem_Compra_ID from compras_ordens_compras_orcamentos where md5(Fornecedor_ID) = '".$_GET['oc']."')
										group by cd.Cadastro_ID, cd.Nome, p.Codigo, p.Nome,pv.Produto_Variacao_ID
										order by p.Nome,p.Codigo");
		while($row = mpress_fetch_array($dadosOrcamento)){
			$i++;
			$dados[cadastroID] 		= $row['Cadastro_ID'];
			$dados[fornecedor] 		= $row['Nome'];
			$dados[variacaoID][$i] 	= $row['Produto_Variacao_ID'];
			$dados[codigo][$i] 		= $row['Codigo'];
			$dados[produto][$i] 	= $row['Produto'];
			$dados[quantidade][$i] 	= $row['Quantidade'];
		}
		foreach($_POST['valor-cotacao'] as $valorCotacao){
			$p++;
			$valorOrcado[$dados[variacaoID][$p]] = $valorCotacao;
		}
		$retornoOrcamento = mpress_query("select c.Ordem_Compra_ID, cd.Cadastro_ID, pv.Produto_Variacao_ID, cs.Quantidade
										  from compras_ordem_compra c
										  inner join compras_ordens_compras_produtos cp on cp.Ordem_Compra_ID = c.Ordem_Compra_ID
										  inner join compras_solicitacoes cs on cs.Compra_Solicitacao_ID = cp.Compra_Solicitacao_ID
										  inner join produtos_variacoes pv on pv.Produto_Variacao_ID = cs.Produto_Variacao_ID
										  inner join produtos_dados p on p.Produto_ID = pv.Produto_ID
										  inner join produtos_fornecedores f on f.Produto_ID = pv.Produto_ID
										  inner join cadastros_dados cd on cd.Cadastro_ID = f.Cadastro_ID
										  where data_limite_retorno >= now() and c.Situacao_ID = 1 and cs.Situacao_ID = 63 and p.Situacao_ID = 1 and cd.Situacao_ID = 1
										  and  md5(cd.Cadastro_ID) = '".$_GET['oc']."'");
		while($row = mpress_fetch_array($retornoOrcamento)){
			if($_POST)
				if($i!="")
					mpress_query("insert into compras_ordens_compras_orcamentos(Ordem_Compra_ID, Fornecedor_ID, Produto_Variacao_ID, Valor_Retorno) values('".$row['Ordem_Compra_ID']."','".$row['Cadastro_ID']."','".$row['Produto_Variacao_ID']."','".$valorOrcado[$row['Produto_Variacao_ID']]."')");
		}
?>
	<link rel='stylesheet' type='text/css' href='<?php echo $caminhoSistema?>/css/content.css' />
	<input type='hidden' id='attr-oc' value='<?php echo $_GET['oc'];?>'>
	<div id='login-container'>
		<table width="538" height='109' cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:20px'>
			<tr>
				<td align='center'><img src='<?php echo $caminhoSistema;?>/images/login/m-press-logo.png'></td>
				<td width='2' bgcolor='#ebebeb'></td>
				<td align='center'><img src='<?php echo $caminhoSistema;?>/images/login/sistema-gestao-versao.png'></td>
			</tr>
		</table>

		<div Style="width:800px;height:1px;border-top:1px solid #e1e1e1;margin:0 auto;margin-top:20px"></div>

		<table width="800" cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:5px'>
			<tr>
				<td class='conteudo-pagina-orcamento' Style='height:30px;'><?php echo saudacao().$dados[fornecedor]?></td>
			</tr>
<?php if(($i=="")||($_GET['status']=='ok')){
	if($i==""){
		echo "	<tr>
					<td class='titulo-pagina-orcamento' Style='height:170px;' align='center'>Nenhum orçamento pendente aguardando preenchimento!<br><br><br></td>
				</tr>";
	}else{
		if($_GET['status']=='ok')
			echo "	<tr>
						<td class='titulo-pagina-orcamento' Style='height:170px;' align='center'>Orçamento cadastrado com sucesso!<br><br><br></td>
					</tr>";
	}
}else{?>
			<tr>
				<td class='conteudo-pagina-orcamento'>Bem vindo à página de orçamentos on-line do <?php echo $tituloSistema;?>. Favor preencher os dados abaixo para enviar o orçamento solicitado.</td>
			</tr>
		</table>
		<table width="800" cellspacing='2' cellpadding='2' border='0' align='center' Style='margin-top:20px'>
			<tr>
				<td class='titulo-pagina-orcamento' width='576' align='left'>PRODUTOS</td>
				<td class='titulo-pagina-orcamento' width='100' align='center'>QUANTIDADE</td>
				<td class='titulo-pagina-orcamento' width='110' align='center'>VALOR UNITÁRIO</td>
			</tr>
		</table>
<?php for($j=1;$j<=count($dados[produto]);$j++){?>
			<table width="797" cellspacing='2' cellpadding='2' border='0' align='center' Style='border-bottom:1px solid #e1e1e1;'>
				<tr>
					<td class='conteudo-pagina-orcamento' width='540' align='left'><div style='width:110px;float:left'><?php echo $dados[codigo][$j];?></div><div style='width:440px;float:right'><?php echo $dados[produto][$j];?></div></td>
					<td class='conteudo-pagina-orcamento' width='100' align='center'><?php echo $dados[quantidade][$j];?></td>
					<td class='conteudo-pagina-orcamento' width='110' align='center'>
						<input type='text' name='valor-cotacao[]' Style='width:90px;padding:5px;text-align:center' value='R$ 0,00' onfocus="if(this.value=='R$ 0,00')this.value=''" onblur="if(this.value=='')this.value='R$ 0,00'">
					</td>
				</tr>
			</table>
<?php }?>
		<table width="797" cellspacing='0' cellpadding='0' border='0' align='center' Style='margin-top:5px;'>
			<tr>
				<td align='right'>
					<img src='images/geral/bt-enviar-orcamento.png' Style='cursor:pointer' onclick="enviaOrcamento('<?php echo $_GET['oc'];?>')">
				</td>
			</tr>
		</table>
	</div>
<?php
		}
	}
	function saudacao(){
		$hora = date('H');
		if($hora>=6 && $hora <12){
			$texto = "Bom dia ";
		}elseif($hora>=12 && $hora <18){
			$texto = "Boa tarde ";
		}else{
			$texto = "Boa noite ";
		}
		return $texto;
	}
?>