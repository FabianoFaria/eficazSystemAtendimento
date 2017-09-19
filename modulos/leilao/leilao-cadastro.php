<?php
include('functions.php');
$leilaoID = $_POST['leilao-id'];
if ($leilaoID==""){
	$situacaoID = 134;
}
else{
	$sql = "select ld.Empresa_ID, ld.Titulo, ld.Descricao, ld.Data_Leilao, ld.Situacao_ID, ld.Data_Cadastro, cd.Nome as Usuario_Cadastro,
			TIME_FORMAT(ld.Tempo_Duracao_Inicial, '%H:%i') as Tempo_Duracao_Inicial,
			TIME_FORMAT(ld.Tempo_Renovacao_Lance, '%H:%i') as Tempo_Renovacao_Lance,
			ld.Valor_Lance, ld.Lance_Aberto, ld.Plano_ID as Plano_ID
			from leiloes_dados ld
			inner join cadastros_dados cd on cd.Cadastro_ID = ld.Usuario_Cadastro_ID
			where ld.Leilao_ID = '$leilaoID'";
	//echo $sql;
	$resultSet = mpress_query($sql);
	if($rs = mpress_fetch_array($resultSet)){
		$empresaID = $rs[Empresa_ID];
		$titulo = $rs[Titulo];
		$descricao = $rs[Descricao];
		$dataLeilao = converteDataHora($rs[Data_Leilao],1);
		$situacaoID = $rs[Situacao_ID];
		$usuarioCadastro = $rs[Usuario_Cadastro];
		$dataCadastro = converteDataHora($rs[Data_Cadastro],1);
		$lanceAberto = $rs[Lance_Aberto];
		$tempoInicial = $rs[Tempo_Duracao_Inicial];
		$tempoRenovacao = $rs[Tempo_Renovacao_Lance];
		$planoID = $rs[Plano_ID];

		//$valorLance = number_format($rs[Valor_Lance], 2, ',', '.');
		$valorLance = number_format($rs[Valor_Lance], 0, ',', '.');
	}
}

if (verificaNumeroEmpresas()==1){
	if (($empresaID=="")||($empresaID=="0")) $empresaID = retornaCodigoEmpresa();
	$multiEmpresas = "<input type='hidden' id='empresa-id' name='empresa-id' value='$empresaID'>";
}
else{
	$multiEmpresas = "	<div style='float:left;width:100%;margin-bottom:3px'>
						<p>Empresa Respons&aacute;vel</p>
						<p>
							<select id='empresa-id' name='empresa-id' style='width:99.7%' class='required'>
								<option value=''></option>
								".optionValueEmpresas($empresaID)."
							</select>
						</p>
					</div>";
}

echo "	<div id='div-retorno'></div>
		<input type='hidden' name='leilao-id' id='leilao-id' value='$leilaoID'/>
		<div class='titulo-container div-dados-leilao' id='div-dados-leilao'>
			<div class='titulo'>
				<p>
					Dados Leilão
					<input type='button' value='Salvar' class='botao-salvar-leilao btn-novo'/>
				</p>
			</div>
			<div class='conteudo-interno titulo-secundario'>
				<div class='titulo-secundario' style='width:100%;float:left;'>
					<div class='titulo-secundario' style='width:50%;float:left;'>
						<p>Título:</p>
						<p><input type='text' name='titulo' id='titulo' style='width:98.4%' value='$titulo' class='required'/></p>
					</div>
					<div class='titulo-secundario' style='width:16.66%;float:left;'>
						<p>Data Leilão:</p>
						<p><input type='text' name='data-leilao' id='data-leilao' class='formata-data-meia-hora' style='width:97%' value='$dataLeilao'/></p>
					</div>
					<div style='float:left; width:16.66%;'>
						<p>Lance Aberto?</p>
						<p><select id='lance-aberto' name='lance-aberto' class='required'>".optionValueSimNao($lanceAberto)."</select></p>
					</div>
					<!--
					<div class='titulo-secundario' style='width:20%;float:left;'>
						<p>$usuarioCadastro &nbsp;</p>
						<p>$dataCadastro &nbsp;</p>
					</div>
					-->
					<div class='titulo-secundario' style='width:16.66%;float:left;'>
						<p>Situação:</p>
						<p><select name='situacao-id' id='situacao-id' class='required'>".optionValueGrupo(61,$situacaoID,'&nbsp;')."</select></p>
					</div>
				</div>
				$multiEmpresas";
if (($configLeilao['tipo-leilao']=="pre")&&($configLeilao['tipo-fechamanto']=="auto")){
	echo "		<div class='titulo-secundario' style='width:100%;float:left;'>
					<div class='titulo-secundario' style='width:50%;float:left;'>
						<p>Plano:</p>
						<p>
							<select name='plano-id' id='plano-id' class='required'>
								<option>&nbsp;</option>
								".optionValuePlanos($planoID)."
							</select></p>
					</div>
					<div style='float:left; width:16.66%;' class='titulo-secundario'>
						<p>Tempo Inicial</p>
						<p><input type='text' id='tempo-inicial' name='tempo-inicial' value='$tempoInicial' class='formata-horas required' style='width:97%'/></p>
					</div>
					<div style='float:left; width:16.66%;'>
						<p>Tempo Renovação Lance</p>
						<p><input type='text' id='tempo-renovacao' name='tempo-renovacao' value='$tempoRenovacao' class='formata-horas required' style='width:97%'/></p>
					</div>
					<div style='float:left; width:16.66%;'>
						<p>Valor Lance</p>
						<p><input type='text' id='valor-lance' name='valor-lance' value='$valorLance' class='formata-numero required' style='width:97%'/></p>
					</div>
				</div>";
}
echo "			<div class='titulo-secundario' style='width:100%;float:left;'>
					<div class='titulo-secundario uma-coluna' style='width:100%;float:left;'>
						<p>Descrição:</p>
						<p><textarea id='descricao' name='descricao' style='width:99.5%; height:60px' class='required'>$descricao</textarea></p>
					</div>
				</div>
			</div>
		</div>";

if ($leilaoID!=""){
	echo "	<div class='titulo-container div-dados-leilao' id='div-dados-leilao-lotes'>
				<div class='titulo-container-interno'>
					<div class='titulo'>
						<p>Lote <input type='button' value='Incluir' class='leilao-inc-alt-lote' leilao-lote-id=''> </p>
						<input type='hidden' name='leilao-lote-id' id='leilao-lote-id' value=''/>
					</div>
				</div>
				<div class='conteudo-interno titulo-secundario'>
					<div id='leilao-incluir-lote'></div>
					<div id='leilao-lotes-cadastrados' class='titulo-secundario' style='width:100%;float:left;'>";
	carregarLotesLeilao($leilaoID);
	echo "			</div>
				</div>
			</div>";		
}

?>