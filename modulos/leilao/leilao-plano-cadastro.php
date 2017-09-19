<?php
include('functions.php');
$planoID = $_POST['plano-id'];
$situacaoID = 1;

if ($planoID!=""){
	$sql = "select Codigo, Nome, Descricao_Completa, Tipo_Produto, Data_Cadastro, Situacao_ID, Usuario_Cadastro_ID
					from produtos_dados where Produto_ID = '$planoID'";
	$resultSet = mpress_query($sql);
	if($rs = mpress_fetch_array($resultSet)){
		$planoNome = $rs[Nome];
		$descricao = $rs[Descricao_Completa];
		$situacaoID = $rs[Situacao_ID];
		/*
		$dataLeilao = converteDataHora($rs[Data_Leilao],1);
		$situacaoID = $rs[Situacao_ID];
		$usuarioCadastro = $rs[Usuario_Cadastro];
		$dataCadastro = converteDataHora($rs[Data_Cadastro],1);
		$lanceAberto = $rs[Lance_Aberto];
		$tempoInicial = $rs[Tempo_Duracao_Inicial];
		$tempoRenovacao = $rs[Tempo_Renovacao_Lance];
		$valorLance = number_format($rs[Valor_Lance], 2, ',', '.');
		$valorLance = number_format($rs[Valor_Lance], 0, ',', '.');
		*/
	}
}

echo "	<div id='div-retorno'></div>
		<input type='hidden' name='plano-id' id='plano-id' value='$planoID'/>
		<div class='titulo-container' id='div-dados-plano'>
			<div class='titulo'>
				<p>
					Dados Plano
					<input type='button' value='Salvar' class='botao-salvar-plano btn-novo'/>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:100%;float:left;'>
					<div class='titulo-secundario' style='width:80%;float:left;'>
						<p>Nome Plano:</p>
						<p><input type='text' name='plano-nome' id='plano-nome' style='width:99%' value='$planoNome' class='required'/></p>
					</div>
					<div class='titulo-secundario' style='width:20%;float:left;'>
						<p>Situação:</p>
						<p><select name='situacao-id' id='situacao-id' class='required'>".optionValueGrupo(1,$situacaoID,'&nbsp;')."</select></p>
					</div>
				</div>";
echo "			<div class='titulo-secundario' style='width:100%;float:left;'>
					<div class='titulo-secundario uma-coluna' style='width:100%;float:left;'>
						<p>Descrição Completa:</p>
						<p><textarea id='plano-descricao' name='plano-descricao' style='width:99.5%; height:60px' class='required'>$descricao</textarea></p>
					</div>
				</div>
			</div>
		</div>";

if ($planoID!=""){
	echo "	<div class='titulo-container' id='div-dados-pacotes'>
				<div class='titulo-container-interno'>
					<div class='titulo'>
						<p>Pacote <input type='button' value='Incluir' class='pacote-inc-alt' pacote-id=''> </p>
						<input type='hidden' name='pacote-id' id='pacote-id' value=''/>
					</div>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div id='pacote-incluir'></div>
					<div id='pacotes-cadastrados' class='titulo-secundario' style='width:100%;float:left;'>";
	carregarPacotesLeiloes($planoID);
	echo "			</div>
				</div>
			</div>";
}
?>