<?php
include('functions.php');
if ($_GET['tipo']=='direto'){
	echo "	<style>
				#topo-container{display:none;}
				#menu-container{display:none;}
			</style>";
}

$campoID = $_POST['campo-id'];
if ($campoID!=''){
	$sql = "SELECT fc.Nome, fc.Descricao, fc.Tipo_Campo, fc.Data_Cadastro
				FROM formularios_campos fc
				WHERE fc.Situacao_ID = 1
				and fc.Campo_ID = $campoID";
	$resultset = mpress_query($sql);
	if ($rs = mpress_fetch_array($resultset)){
		$nome = $rs['Nome'];
		$descricao = $rs['Descricao'];
		$tipoCampo = $rs['Tipo_Campo'];
		$quantidadeOpcoes = 2;
	}

	if (($tipoCampo=='select')||($tipoCampo=='radio')||($tipoCampo=='checkbox')){
		$sql = "SELECT Campo_Opcao_ID, Campo_ID, Descricao, Situacao_ID, Posicao, Usuario_Cadastro_ID, Data_Cadastro
					FROM formularios_campos_opcoes
					WHERE Campo_ID = '$campoID' and Situacao_ID = 1";
		//echo $sql;
		$qtdeOpcoes = 0;
		$resultset = mpress_query($sql);
		while ($rs = mpress_fetch_array($resultset)){
			$qtdeOpcoes++;
		}
		if ($qtdeOpcoes==0) $qtdeOpcoes = 2;
	}
	else{
		$escondeQuantidade = 'esconde';
	}
}
else{
	$qtdeOpcoes = 2;
	$escondeQuantidade = 'esconde';
}

echo "	<div class='titulo-container'>
			<div class='titulo'>
				<p>
					Dados Campo
					<input type='button' class='salvar-modelo-campo' value='Salvar Campo' style='float:right; margin-left:5px; width:150px;'/>
					<input type='hidden' name='campo-id' id='campo-id' value='$campoID'/>
					<input type='hidden' name='tipo' id='tipo' value='".$_GET['tipo']."'/>

				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario dados-campo-modelo' id='dados-campo-modelo' style='float:left; width:100%; margin-top:10px;'>
					<div class='titulo-secundario' style='float:left; width:50%;'>
						<div class='titulo-secundario' style='float:left; width:100%;'>
							<p><b>Nome campo</b></p>
							<p><input type='text' id='nome-campo' name='nome-campo' style='width:98.5%' value='$nome' class='required'/></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:75%;'>
							<p><b>Tipo campo</b></p>
							<p>
								<select id='tipo-campo-modelo' name='tipo-campo-modelo' class='required'>
									<option value=''>Selecione</option>
									".optionValueTiposCampos($tipoCampo)."
								</select>
							</p>
						</div>
						<div class='titulo-secundario $escondeQuantidade bloco-multiplas-opcoes' style='float:left; width:24%;'>
							<p><b>N&ordm; de op&ccedil;&otilde;es: </b></p>
							<p>
								<select name='quantidade-opcoes-multiplas' id='quantidade-opcoes-multiplas'>
									".optionValueQuantidadeCampos(20, $qtdeOpcoes)."
								</select>
							</p>
						</div>

					</div>
					<div class='titulo-secundario' style='float:left; width:50%;'>
						<div class='titulo-secundario' style='float:left; width:100%;'>
							<p><b>Descri&ccedil;&atilde;o completa campo:</b></p>
							<p><textarea type='text' id='descricao-campo' name='descricao-campo' style='height:72px; width:98%;'>$descricao</textarea></p>
						</div>
					</div>
					<div class='bloco-multiplas-opcoes $escondeQuantidade' style='float:left; width:100%; margin-top:5px;' id='bloco-opcoes-diponiveis'>
					".carregarOpcoesCampos($campoID, $qtdeOpcoes)."
					</div>
				</div>
			</div>
		</div>";
?>