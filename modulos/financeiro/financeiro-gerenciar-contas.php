<?php
include("functions.php");
include("arrays-bancos.php");
global $caminhoFisico, $modulosAtivos, $modulosGeral;
$acao = $_POST['acao-conta'];
$cadastroContaID = $_POST['cadastro-conta-id'];
$situacaoContaID = '1';

echo "	<input type='hidden' id='acao-conta' name='acao-conta' value='$acao'>
		<input type='hidden' id='cadastro-conta-id' name='cadastro-conta-id' value='$cadastroContaID'>";

if (($acao=='I') || ($acao=='U')){

	if (($acao=="U") && ($cadastroContaID!='')){
		$sql = "SELECT Cadastro_ID as Empresa_ID, Tipo_Conta_ID, Nome_Conta, Dados, Saldo_Inicial, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro
						FROM cadastros_contas where Cadastro_Conta_ID = '$cadastroContaID'";

		$resultado = mpress_query($sql);
		if ($rs = mpress_fetch_array($resultado)){
			$empresaID = $rs['Empresa_ID'];
			$tipoContaID = $rs['Tipo_Conta_ID'];
			$nomeConta = $rs['Nome_Conta'];
			$dataCadastro = substr(converteData($rs['Data_Cadastro'],1),0,10);
			$saldoInicial = $rs['Saldo_Inicial'];
			$situacaoContaID = $rs['Situacao_ID'];
			$dados = unserialize($rs['Dados']);
		}
	}

	if (verificaNumeroEmpresas()==1){
		$empresaID = retornaCodigoEmpresa();
		$htmlEmpresa = "<input type='hidden' id='empresa-id' name='empresa-id' value='$empresaID'>";
	}
	else{
		$htmlEmpresa = "<div class='titulo-secundario' style='float:left; width:20%;'>
							<p><b>Empresa</b></p>
							<p><select id='empresa-id' name='empresa-id' class='required'><option value=''>Selecione</option>".optionValueEmpresas($empresaID)."</select></p>
						</div>";
	}

	echo "	<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Dados Conta
						<input type='button' value='Salvar Conta' class='botao-salvar-conta-gestao' style='font-size:10px;'>
					</p>
				</div>
				<div class='conteudo-interno bloco-dados-conta-gestao'>
					<div style='float:left; width:100%'>
						<div class='titulo-secundario' style='width:50%; float:left;'>
							<p><b>Nome Conta</b></p>
							<p><input type='text' name='nome-conta' id='nome-conta' value='$nomeConta' style='width:99%;' class='required'></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:20%;'>
							<p><b>Tipo Conta</b></p>
							<p><select name='tipo-conta' id='tipo-conta' class='required'>".optionValueGrupo(71, $tipoContaID, '&nbsp;', '')."</select></p>
						</div>
						$htmlEmpresa
						<div class='titulo-secundario' style='float:left; width:10%;'>
							<p><b>Situação Conta</b></p>
							<p><select name='situacao-conta' id='situacao-conta' class='required'>".optionValueGrupo(1, $situacaoContaID, "&nbsp;", " and Tipo_ID IN(1,2) ")."</select></p>
						</div>
					</div>
					<div style='float:left; width:100%' class='esconde dados-banco'>
						<div class='titulo-secundario' style='width:50%; float:left;'>
							<p><b>Banco</b></p>
							<p>
								<select name='dados[banco]' id='banco'>
									<option value=''>Selecione</option>
									".optionValueBancos($dados['banco'])."
								</select>
							</p>
						</div>
						<!--
						<div class='titulo-secundario' style='width:12.5%; float:left;'>
							&nbsp;
						</div>
						-->
						<div class='titulo-secundario' style='width:12.5%; float:left;'>
							<p><b>Agência</b></p>
							<p><input type='text' name='dados[agencia]' id='agencia' style='width:90%;' class='formata-numero' maxlength='6' value='".$dados['agencia']."'></p>
						</div>

						<div class='titulo-secundario' style='width:12.5%; float:left;'>
							<p><b>Conta Corrente:</b></p>
							<p><input type='text' name='dados[conta-corrente]' id='conta-corrente' style='width:90%;' class='formata-numero' maxlength='12' value='".$dados['conta-corrente']."'></p>
						</div>
						<div class='titulo-secundario' style='width:12.5%; float:left;'>
							<p><b>Dígito:</b></p>
							<p><input type='text' name='dados[digito]' id='digito' style='width:90%;' class='formata-numero' maxlength='6' value='".$dados['digito']."'></p>
						</div>
						<div class='titulo-secundario' style='width:12.5%; float:left;'>
							<p><b>Carteira:</b></p>
							<p><input type='text' name='dados[carteira]' id='carteira' style='width:90%;' class='formata-numero' maxlength='5' value='".$dados['carteira']."'></p>
						</div>
					</div>

					<div style='float:left; width:100%' class='esconde dados-banco-boleto'>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Dias para Vencimento</b></p>
							<p><input type='text' name='dados[dias-vencimento]' id='dias-vencimento' style='width:95%;' class='formata-numero' maxlength='6' value='".$dados['dias-vencimento']."'></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Aceitar Pós Vencimento</b></p>
							<p><select name='dados[aceita-apos-vencimento]' id='aceita-apos-vencimento' class='required'>".optionValueSimNao($dados['aceita-apos-vencimento'])."</select></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Prazo Máximo para Pagamento</b></p>
							<p><input type='text' name='dados[prazo-maximo-pagamento]' id='prazo-maximo-pagamento' style='width:95%;' class='formata-numero' maxlength='10' value='".$dados['prazo-maximo-pagamento']."'></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Multa Atraso (%)</b></p>
							<p><input type='text' name='dados[multa-atraso-percentual]' id='multa-atraso-percentual' style='width:95%;' class='formata-valor' maxlength='10' value='".$dados['multa-atraso-percentual']."'></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Juros Dia (%)</b></p>
							<p><input type='text' name='dados[juros-diarios-percentual]' id='juros-diarios-percentual' style='width:95%;' class='formata-valor' maxlength='10' value='".$dados['juros-diarios-percentual']."'></p>
						</div>
						<div class='titulo-secundario' style='width:20%; float:left;'>
							<p><b>Custo boleto</b><i>(Cobrar do cliente)</i></p>
							<p><input type='text' name='dados[taxa-boleto]' id='taxa-boleto' style='width:95%;' class='formata-valor' maxlength='10' value='".$dados['taxa-boleto']."'></p>
						</div>
						<div class='titulo-secundario' style='width:100%; float:left;'>
							<div class='titulo-secundario' style='float:left; width:33%'>
								<p><b>Texto bloco CABEÇALHO</b><i>(até 3 linhas)</i></p>
								<p><textarea style='width:99%; height:60px' name='dados[texto-bloco-cabecalho]'>".$dados['texto-bloco-cabecalho']."</textarea></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:33%'>
								<p><b>Texto bloco DEMONSTRATIVO</b><i>(até 3 linhas)</i></p>
								<p><textarea style='width:99%; height:60px' name='dados[texto-bloco-demonstrativo]'>".$dados['texto-bloco-demonstrativo']."</textarea></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:33%'>
								<p><b>Texto bloco INSTRUÇÕES</b><i>(até 4 linhas)</i></p>
								<p><textarea style='width:99%; height:60px' name='dados[texto-bloco-instrucoes]'>".$dados['texto-bloco-instrucoes']."</textarea></p>
							</div>
							<!--
							<div style='float:left; width:100%'>
								<div class='titulo-secundario' style='float:left; width:100%'>
									<p><b>Observações</b></p>
									<p><input type='text' name='dados[observacoes]' id='observacoes' style='width:99.5%;' value='".$dados['observacoes']."'></p>
								</div>
							</div>
							-->
						</div>
					</div>

					<div style='float:left; width:100%' class='esconde dados-banco'>
						<div class='titulo-secundario' style='float:left; width:20%'>
							<p><b>Limite de crédito</b></p>
							<p><input type='text' name='dados[limite-credito]' id='limite-credito' style='width:90%;' class='formata-valor' value='".$dados['limite-credito']."'></p>
						</div>
					</div>
					<div style='float:left; width:100%'>
						<div class='titulo-secundario' style='float:left; width:20%'>
							<p><b>Saldo inicial</b></p>
							<p><input type='text' name='saldo-inicial' id='saldo-inicial' style='width:90%;' class='formata-valor-pos-neg' value='".number_format($saldoInicial, 2, ',', '.')."'></p>
						</div>
						<div class='titulo-secundario esconde' style='float:left; width:20%'>
							<p><b>Data cadastro</b></p>
							<p><input type='text' name='data-cadastro' id='data-cadastro' style='width:90%;' class='formata-data' readonly value='".$dataCadastro."'></p>
						</div>
					</div>
				</div>
			</div>";

	echo "	<div class='titulo-container dados-gerais'>
				<div class='titulo'>
					<p>
						Usuários Acesso Conta
					</p>
				</div>
				<div class='conteudo-interno'>
					<div id='div-incluir-usuario-conta' class='titulo-secundario esconde' style='float:left; width:100%;'></div>
					<div class='titulo-secundario' style='float:left; width:100%;'>
						<select name='dados[usuarios][]' id='dados-usuarios' multiple>".optionValueUsuarios($dados['usuarios'],'','','','multiple')."</select>
					</div>
				</div>
			</div>";

}
else{
	echo "
		<div id='cadastros-container' class='conta-bancaria-lista'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Contas
						<input type='button' value='Incluir Conta' class='inc-alt-conta' style='font-size:10px;'>
					</p>
				</div>
				<div class='conteudo-interno'>";

		$sql = "SELECT cc.Cadastro_Conta_ID, cd.Nome as Empresa, t.Descr_Tipo as Tipo, cc.Nome_Conta as Nome_Conta, cc.Dados, cc.Saldo_Inicial
					FROM cadastros_contas cc
					left join tipo t on t.Tipo_ID = cc.Tipo_Conta_ID
					left join cadastros_dados cd on cd.Cadastro_ID = cc.Cadastro_ID
					where cc.Situacao_ID = 1
					order by t.Tipo_ID";
		$resultado = mpress_query($sql);
		$i = 0;
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p class='link inc-alt-conta' cadastro-conta-id='".$row['Cadastro_Conta_ID']."'>".$row['Cadastro_Conta_ID']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p class='link inc-alt-conta' cadastro-conta-id='".$row['Cadastro_Conta_ID']."'>".$row['Nome_Conta']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p>".$row['Tipo']."</p>";
			$dados[colunas][conteudo][$i][4] = "<p>".$row['Empresa']."</p>";
		}
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhuma conta/caixa cadastroados</p>";
		}
		else{
			$largura = "100.2%";
			$colunas = "4";
			$dados[colunas][titulo][1] 	= "ID";
			$dados[colunas][titulo][2] 	= "Conta";
			$dados[colunas][titulo][3] 	= "Tipo";
			$dados[colunas][titulo][4] 	= "Empresa";
			geraTabela($largura,$colunas,$dados, null, 'cadastro-localiza-dados-contas', 2, 2, 100,1);
		}
	echo "
				</div>
			</div>
		</div>
	</div>";
}