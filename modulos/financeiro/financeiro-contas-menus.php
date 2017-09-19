<?php
global $configFinanceiro, $modulosAtivos;
$configFinanceiro = carregarConfiguracoesGeraisModulos('financeiro');
if ($configFinanceiro['exibe-conta']==1){

	$filtroCadastroConta = $_POST['filtro-cadastro-conta'];
	$sql = "SELECT cc.Cadastro_Conta_ID, cc.Nome_Conta, SUM(coalesce(fm.Valor,0)) + cc.Saldo_Inicial as Saldo, cc.Dados
				FROM cadastros_contas cc
				left join financeiro_movimentacoes fm on fm.Cadastro_Conta_ID = cc.Cadastro_Conta_ID and fm.Situacao_ID = 1
				where cc.Situacao_ID = 1
				group by cc.Cadastro_Conta_ID, cc.Nome_Conta, cc.Dados
				order by cc.Tipo_Conta_ID, cc.Nome_Conta ";
	//echo $sql;
	$resultSet = mpress_query($sql);
	while($rs = mpress_fetch_array($resultSet)){
		$cadastroContaID = $rs['Cadastro_Conta_ID'];
		$dados = unserialize($rs['Dados']);
		$flag = 1;
		if (!($modulosAtivos['financeiro-gerenciar-contas'])){
			if (!(in_array($dadosUserLogin['userID'], $dados['usuarios']))){
				$flag = 0;
			}
		}
		if ($flag==1){
			if (!($modulosAtivos['financeiro-gerenciar-contas'])){
				if ($filtroCadastroConta=="")
					$filtroCadastroConta = $rs['Cadastro_Conta_ID'];
			}
			if ($rs['Saldo']<=0){
				$cor = "color:red;";
				$sinal = "";
			}
			else{
				$cor = "color:blue;";
				$sinal = "+";
			}
			$classeConta = "menu-interno-superior menu-interno-modulo";
			if ($filtroCadastroConta==$rs['Cadastro_Conta_ID']){
				$classeConta = "menu-interno-superior-selecionado menu-interno-modulo";
				$descrCadastroConta = $rs['Nome_Conta'];
			}

			$menuSuperior .="	<div class='".$classeConta." localizar-contas-acao' id='menu-superior-$cadastroContaID' title='' style='padding: 3px 1px 0px 1px;' attr-div='.conjunto1' attr-pos='$cadastroContaID'>
									".$rs['Nome_Conta']."
									<p style='$cor font-size:13px; margin:2px 0 0 0; padding: 0 0 0 0;'>$sinal".number_format($rs['Saldo'], 2, ',', '.')."</p>
								</div>";
		}
		$saldoTotalGeral += $rs['Saldo'];
	}

	if ($modulosAtivos['financeiro-gerenciar-contas']){
		$classeConta = "menu-interno-superior menu-interno-modulo";
		if ($filtroCadastroConta=='') $classeConta = "menu-interno-superior-selecionado menu-interno-modulo";

		if ($saldoTotalGeral<=0){
			$cor = "color:red;";
			$sinal = "";
		}
		else{
			$cor = "color:blue;";
			$sinal = "+";
		}
		if ($filtroCadastroConta==$rs['Cadastro_Conta_ID'])
			$classeConta = "menu-interno-superior-selecionado menu-interno-modulo";
		else
			$styleBorder = "border:0px;";

		$menuSuperior = "	<div class='".$classeConta." localizar-contas-acao' id='menu-superior-' title='' style='padding: 3px 1px 0px 1px; $styleBorder' attr-div='.conjunto1' attr-pos=''>
								GERAL
								<p style='$cor font-size:13px; margin:2px 0 0 0; padding: 0 0 0 0;'>$sinal".number_format($saldoTotalGeral, 2, ',', '.')."</p>
							</div>".$menuSuperior;
	}


	echo "<input type='hidden' id='filtro-cadastro-conta' name='filtro-cadastro-conta' value='".$filtroCadastroConta."'>";
}
?>