<?php
	include("functions.php");
	if($_POST){
		$projetoID = $_POST['localiza-projeto-id'];
		$descricao = $_POST['localiza-descricao'];
		$situacaoID = $_POST['localiza-situacao'];
		$modulo = $_POST['localiza-tabela-estrangeira'];
	}
	else{
		$situacaoID = "1";
	}
	$opcoesModulos = optionValueModulosProjeto($modulo);
	$arrayModulos = arrayModulosProjetoDescricao();

	echo "	<input type='hidden' id='projeto-id' name='projeto-id' value=''/>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Filtros de Pesquisa";
	echo "				<input type='button' value='Incluir Projeto' projeto-id='' class='projeto-localizar' style='float:right;margin-right:0px;width:100px;'>";
	echo "			</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='width:10%; float:left;'>
						<p>ID</p>
						<p><input type='text' name='localiza-projeto-id' id='localiza-projeto-id' class='formata-numero' value='".$projetoID."' style='width:90%;'></p>
					</div>
					<div class='titulo-secundario' style='width:45%; float:left;'>
						<p>Titulo / Descri&ccedil;&atilde;o</p>
						<p><input type='text' id='localiza-descricao' name='localiza-descricao'  maxlength='250' value='".$descricao."'  style='width:98%;'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%;'>
						<p>M&oacute;dulo Destino</p>
						<p>
							<select id='localiza-tabela-estrangeira' name='localiza-tabela-estrangeira' style='width:90%'>
								<option value=''></option>
								$opcoesModulos
							</select>
						</p>
					</div>
					<div class='titulo-secundario' style='width:10%; float:left;'>
						<p>Situa&ccedil;&atilde;o</p>
						<p><select name='localiza-situacao' id='localiza-situacao' style='width:80%;'>".optionValueGrupo(1, $situacaoID, 'Todos', 'and Tipo_ID IN(1,3)')."</select></p>
					</div>
					<div class='titulo-secundario' style='width:15%; float:left; margin-top:15px;'>
						<p><input type='button' value='Pesquisar' id='botao-pesquisar-projetos' style='float:right;margin-right:0px;width:100px;'/></p>
					</div>
				</div>
			</div>";

	//if($_POST){
		if ($projetoID!="") $sqlCond .= " and Projeto_ID = '$projetoID' ";
		if ($descricao!="") $sqlCond .= " and Titulo like '%$descricao% ";
		if ($situacaoID!="") $sqlCond .= " and Situacao_ID = $situacaoID ";
		if ($modulo!="") $sqlCond .= " and Tabela_Estrangeira = '$modulo' ";

		/*
		if($_POST['ordena-tabela'] != ""){
			$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
		}else{
			$ordem = " order by Titulo";
		}
		*/
		$ordem = " order by Tabela_Estrangeira, Titulo";

		$sql = "select Projeto_ID, Titulo, Descricao, Tabela_Estrangeira, Projeto_Padrao, Data_Cadastro, Usuario_Cadastro_ID, Situacao_ID
					from projetos where Projeto_ID > 0
					$sqlCond
					$ordem";
		//echo $sql;
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row[Situacao_ID]==3) $classe = "lixeira"; else $classe = "link";
			$i++;
			if ($row[Projeto_Padrao]=="1") $projetoPadrao = "X"; else $projetoPadrao = "";
			$dados[colunas][conteudo][$i][1] = "<span class='$classe projeto-localizar' projeto-id='".$row[Projeto_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Projeto_ID]."</p></span>";
			$dados[colunas][conteudo][$i][2] = "<span class='$classe projeto-localizar' projeto-id='".$row[Projeto_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Titulo]."</p></span>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$arrayModulos[$row[Tabela_Estrangeira]]."</p>";
			$dados[colunas][conteudo][$i][4] = "<p align='center' Style='margin:2px 2px 2px 2px;'>$projetoPadrao</p>";
		}

		$largura = "100%";
		$colunas = "4";
		$dados[colunas][tamanho][1] = "width='10%'";
		$dados[colunas][tamanho][2] = "";
		$dados[colunas][tamanho][3] = "width='20%'";
		$dados[colunas][tamanho][4] = "width='100px'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "T&iacute;tulo";
		$dados[colunas][titulo][3] 	= "M&oacute;dulo Destino";
		$dados[colunas][titulo][4] 	= "<p align='center' Style='margin:2px 2px 2px 2px;'>Projeto Padr&atilde;o</p>";

		//$dados[colunas][ordena][1] = "Projeto_ID";
		//$dados[colunas][ordena][2] = "Titulo";
		//$dados[colunas][ordena][3] = "M&oacute;dulo Destino";
		if($i==0){
			$dados[colunas][colspan][1][1] = "4";
			$dados[colunas][conteudo][1][1] =  "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum registro localizado</p>";
		}

		geraTabela($largura,$colunas,$dados, null, 'projetos-localiza', 2, 2, 100,1);
//}
?>