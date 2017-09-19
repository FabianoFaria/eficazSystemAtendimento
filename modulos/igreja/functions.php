<?php
	//error_reporting(0);
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	function salvarLancamentoMembros($igrejaID, $quantidade, $dataLancamento){
		global $dadosUserLogin;
		$dataHoraAtual = retornaDataHora('','Y-m-d H:i:s');
		$usuarioID = $dadosUserLogin['userID'];
		$sql = "insert into igreja_lancamento (Igreja_ID, Quantidade, Data_Lancamento, Situacao_ID, Data_Cadastro,Usuario_Cadastro_ID)
											values ('$igrejaID', '$quantidade', '$dataLancamento', 1, '$dataHoraAtual', '$usuarioID')";
		//echo $sql;
		mpress_query($sql);
		//$lancamentoID = mysql_insert_id();
	}


	function carregarLancamentoMembros($igrejaID){
		global $caminhoSistema;
?>		<div class="titulo-container esconde conjunto6">
			<div class="titulo">
				<p>
					Lan&ccedil;amento de Membros
					<input type='button' value='Salvar Lan&ccedil;amento' id='botao-salvar-lancamento-membro' name='botao-salvar-lancamento-membro' style="float:right;margin-right:0px;"/>
				</p>
			</div>
			<div class="conteudo-interno">
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='width:20%; float:left;'>
						<p>Data Lan&ccedil;amento</p>
						<p><input type='text' id='data-lancamento-membros' name='data-lancamento-membros' class='formata-data' value=''/></p>
					</div>
					<div class='titulo-secundario' style='widh:20%; float:left;'>
						<p>Quantidade Membros</p>
						<p><input type='text' id='quantidade-membros' name='quantidade-membros' class='formata-numero' value=''/></p>
					</div>
				</div>
			</div>
		</div>
		<div class="titulo-container esconde conjunto6">
			<div class="titulo">
				<p>&Uacute;ltimos Lan&ccedil;amentos</p>
			</div>
			<div class="conteudo-interno">
<?php
		if ($igrejaID == ""){
			echo "<p align='center'> Necess&aacute;rio selecionar a Igreja </p>";
		}
		else{
			$sql = "select il.Igreja_Lancamento_ID, il.Igreja_ID, il.Quantidade, il.Data_Lancamento, il.Data_Cadastro, cd.Nome
					from igreja_lancamento il
					left join cadastros_dados cd on cd.Cadastro_ID = il.Usuario_Cadastro_ID
					where Igreja_ID = '$igrejaID'
					order by il.Data_Lancamento desc, il.Data_Cadastro desc
					limit 20";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;' align='center'>".formataData($row[Data_Lancamento])."</p><a>";
				$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;' align='center'>".$row[Quantidade]."</p>";
				$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Nome]."</p>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($row[Data_Cadastro],1)."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 3px 0 3px;float:left;'><div class='btn-excluir btn-excluir-lancamento-membro' style='float:right; padding-right:5px' igreja-lancamento-id='".$row[Igreja_Lancamento_ID]."' title='Excluir'>&nbsp;</div></p>";

			}
			if($i>=1){
				$largura = "100%";
				$colunas = "5";
				$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 5px;' align='center'>Data</p>";
				$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;' align='center'>Quantidade</p>";
				$dados[colunas][titulo][3] 	= "Usu&aacute;rio Cadastro";
				$dados[colunas][titulo][4] 	= "Data Cadastro";
				$dados[colunas][tamanho][1] = "width=''";
				$dados[colunas][tamanho][2] = "width='25%'";
				$dados[colunas][tamanho][3] = "width='20%'";
				$dados[colunas][tamanho][4] = "width='20%'";
				$dados[colunas][tamanho][5] = "width='50px'";
				geraTabela($largura,$colunas,$dados);
			}else{
				echo "<p align='center'> Nenhum registro localizado</p>";
			}
		}
?>
			</div>
		</div>
<?php
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.js'></script>";
	}

	function excluirLancamentoMembro($lancamentoID){
		if ($lancamentoID!=""){
			$sql = "delete from igreja_lancamento where Igreja_Lancamento_ID = '$lancamentoID'";
			mpress_query($sql);
		}
	}


	function exibeDadosComplementaresCadastroIgreja($cadastroID){
		if ($cadastroID!=""){
			$sql = "SELECT Estado_Civil, Data_Batismo, Data_Ordenacao, Nome_Pai, Nome_Mae, Procedencia, Cidade_Natural, UF_Natural, Congregacao_ID FROM igreja_cadastros_dados where Cadastro_ID = '$cadastroID'";
			if ($rs = mpress_fetch_array(mpress_query($sql))){
				$estadoCivil = $rs['Estado_Civil'];
				$procedencia = $rs['Procedencia'];
				$nomePai = $rs['Nome_Pai'];
				$nomeMae = $rs['Nome_Mae'];
				$ufNatural = $rs['UF_Natural'];
				$cidadeNatural = $rs['Cidade_Natural'];
				$congregacaoID = $rs['Congregacao_ID'];
				$dataBatismo = substr(converteDataHora($rs['Data_Batismo'],1),0,10);
				$dataOrdenacao = substr(converteDataHora($rs['Data_Ordenacao'],1),0,10);

			}
		}
		echo "<div style='width:100%; float:left;' class='div-pf'>
				<div class='titulo-secundario' style='width:25%; float:left;'>
					<p><b>Nome Pai</b></p>
					<p><input type='text' name='nome-pai' id='nome-pai' value='$nomePai' style='width:98%;'/></p>
				</div>
				<div class='titulo-secundario' style='width:25%; float:left;'>
					<p><b>Nome M&atilde;e</b></p>
					<p><input type='text' name='nome-mae' id='nome-mae' value='$nomeMae' style='width:98%;'/></p>
				</div>
				<div class='titulo-secundario' style='width:16.66%; float:left;'>
					<p><b>Estado Civil</b></p>
					<p>
						<select name='estado-civil' id='estado-civil' style='width:95%;'>
							".optionValueGrupo(52, $estadoCivil, "&nbsp;")."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:16.66%; float:left;'>
					<p><b>Data Batismo</b></p>
					<p><input type='text' name='data-batismo' id='data-batismo' value='$dataBatismo' class='formata-data' style='width:95%;'/></p>
				</div>
				<div class='titulo-secundario' style='width:16.66%; float:left;'>
					<p><b>Data de Ordena&ccedil;&atilde;o</b></p>
					<p><input type='text' name='data-ordenacao' id='data-ordenacao' value='$dataOrdenacao' class='formata-data' style='width:95%;'/></p>
				</div>
				<div class='titulo-secundario' style='width:25%; float:left;'>
					<p><b>Proced&ecirc;ncia</b></p>
					<p>
						<select name='procedencia' id='procedencia' style='width:98%;'>
							".optionValueGrupo(53, $procedencia,"&nbsp;")."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:25%; float:left;'>
					<p><b>Congrega&ccedil;&atilde;o</b></p>
					<p>
						<select name='congregacao' id='congregacao' style='width:98%;'>
							<option></option>
							".optionValueEmpresas($congregacaoID)."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='width:10.66%; float:left;'>
					<p><b>Natural de</b></p>
					<p><input type='text' name='cidade-natural' id='cidade-natural' value='$cidadeNatural' style='width:95%;'/></p>
				</div>
				<div class='titulo-secundario' style='width:6%; float:left;'>
					<p><b>UF</b></p>
					<p>
						<select name='uf-natural' id='uf-natural' style='width:95%;'>
							".optionValueGrupoUF($ufNatural, "&nbsp;")."
						</select>
					</p>
				</div>
			</div>";
	}

	function salvarDadosComplementaresIgreja($cadastroID){
		if ($row = mpress_fetch_array(mpress_query("select Cadastro_ID from igreja_cadastros_dados where Cadastro_ID = '$cadastroID'"))){
			$sql = "update igreja_cadastros_dados set Estado_Civil = '".$_POST['estado-civil']."',
											Procedencia = '".$_POST['procedencia']."',
											Data_Batismo = '".formataDataBD($_POST['data-batismo'])."',
											Data_Ordenacao = '".formataDataBD($_POST['data-ordenacao'])."',
											Nome_Pai = '".utf8_decode($_POST['nome-pai'])."',
											Nome_Mae = '".utf8_decode($_POST['nome-mae'])."',
											Cidade_Natural = '".utf8_decode($_POST['cidade-natural'])."',
											UF_Natural = '".utf8_decode($_POST['uf-natural'])."',
											Congregacao_ID = '".$_POST['congregacao']."'
						where Cadastro_ID = '$cadastroID'";
		}
		else{
			$sql = "insert into igreja_cadastros_dados (Cadastro_ID, Estado_Civil, Procedencia, Data_Batismo, Data_Ordenacao, Nome_Pai, Nome_Mae, Cidade_Natural, UF_Natural, Congregacao_ID)
					values ('".$cadastroID."', '".$_POST['estado-civil']."', '".$_POST['procedencia']."', '".formataDataBD($_POST['data-batismo'])."', '".formataDataBD($_POST['data-ordenacao'])."', '".utf8_decode($_POST['nome-pai'])."', '".utf8_decode($_POST['nome-mae'])."','".utf8_decode($_POST['cidade-natural'])."','".utf8_decode($_POST['uf-natural'])."','".$_POST['congregacao']."')";
		}
		mpress_query($sql);
	}
?>