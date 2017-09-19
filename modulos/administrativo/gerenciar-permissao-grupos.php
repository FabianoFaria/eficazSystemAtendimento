<?php
	require_once("includes/functions.gerais.php");
	require_once("config.php");

	if($_POST['grupo-edicao'] != "") $_POST['edita'] = $_POST['grupo-edicao'];
	if($_POST['nome-grupo'] != ""){
		mpress_query("insert into modulos_acessos(Titulo)values('".$_POST['nome-grupo']."')");
		$_POST['edita'] = mpress_identity();
	}

	if($_POST['grupo-selecionado'] != ""){
		$acessos['leitura']  = $_POST['rdPaginasL'];
		$acessos['gravacao'] = $_POST['rdPaginasG'];
		$acessos['nivelAcesso'] = $_POST['nivel-acesso'];

		mpress_query("update modulos_acessos set Acessos = '".serialize($acessos)."' where Modulo_Acesso_ID = ".$_POST['edita']);
	}

	$classeEsconde = "esconde";
	if($_POST['edita'] != ""){
		if ($_POST['edita']>=2){
			$classeEsconde = "";
		}
		$acessos = mpress_query("select Acessos from modulos_acessos where Modulo_Acesso_ID = ".$_POST['edita']);
		$row = mpress_fetch_array($acessos);
		$acessos = unserialize($row[0]);

	}

	/*
	echo "<pre>";
	print_r($acessos);
	echo "</pre>";
	*/
	echo "	<div id='grupo-conteudo-interno-inicio'>
				<div class='titulo-container'>
					<div class='titulo' Style='min-height:25px'>
						<p Style='margin-top:2px;'>Grupo para Edi&ccedil;&atilde;o
							<input type='button' value='Atualizar Grupo' id='botao-atualizar-grupo' class='botao-atualizar-grupo $classeEsconde' Style='float:right;height:24px;font-size:10px;margin-top:-3px;margin-left:3px;'/>
							<input type='button' value='Excluir Grupo' id='botao-excluir-grupo' class='botao-excluir-grupo $classeEsconde' Style='float:right;height:24px;font-size:10px;margin-top:-3px;margin-left:3px;'/>
						</p>
					</div>

					<div class='conteudo-interno'>
						<div class='titulo-secundario uma-coluna'>
							<p>&nbsp;</p>
							<p class='omega'>
								<select name='grupo-edicao' id='grupo-edicao'>
									<option value=''>Selecione</option>
									<option value='-1'>Cadastrar novo grupo</option>";
	$grupos = mpress_query("select Modulo_Acesso_ID, Titulo from modulos_acessos where Situacao_ID = 1 order by Titulo");
	while($row = mpress_fetch_array($grupos)){
		if($_POST['edita']==$row['Modulo_Acesso_ID'])$selecionado = "selected"; else $selecionado = "";
		echo " 						<option value='".$row['Modulo_Acesso_ID']."' $selecionado>".$row['Titulo']."</option>";
	}
	echo "						</select>
							</p>
						</div>
					</div>

					<div>


					</div>
				</div>
			</div>";

		echo "	<div  id='grupo-conteudo-interno-novo' Style='display:none'>
					<div class='titulo-container'>
						<div class='titulo'>
							<p>Cadastrar Novo Grupo</p>
						</div>
						<div class='conteudo-interno'>
							<div class='titulo-secundario uma-coluna'>
								<p class='omega'>
									<table width='100%'>
										<tr>
											<td width='100'>Nome do Grupo:</td>
											<td><input type='text' name='nome-grupo' id='nome-grupo'></td>
											<td width='100'><input type='button' value='cadastrar' id='salva-grupo'></td>
											<td width='100'><input type='button' value='cancelar'  id='cancela-grupo'></td>
										</tr>
									</table>
								</p>
							</div>
						</div>
					</div>
				</div>";



	if($_POST['edita'] != ""){
		echo "<input type='hidden' name='grupo-selecionado' id='grupo-selecionado' value='".$_POST['edita']."'>";
		//$paginaInicial = mpress_fetch_array(mpress_query("select descr_tipo from tipo where Tipo_ID = 6"));
	
		$modulos = mpress_query("select * from modulos where Situacao_ID = 1 and Modulo_ID > 0");
		while($row = mpress_fetch_array($modulos)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p style='margin:0 0 0 10px;'>".$row[Nome]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p style='margin:0 0 0 10px;'>".$row[Descricao]."</p>";
			$sql = "SELECT coalesce(mpp.Posicao, mp.Posicao) as Ordenacao,  mp.Modulo_Pagina_ID, mp.Modulo_ID, mp.Titulo, mp.Descricao, mp.Slug, mp.Situacao_ID, mp.Data_Cadastro, mp.Pagina_Pai_ID, mp.Posicao, mp.Tipo_Grupo_ID, mp.Oculta_Menu
					FROM modulos_paginas mp
					left join modulos_paginas mpp on mpp.Modulo_Pagina_ID = mp.Pagina_Pai_ID
					WHERE mp.Situacao_ID = 1 AND mp.Modulo_ID = '".$row[Modulo_ID]."'
					ORDER BY Ordenacao";
			$paginas = mpress_query($sql);
			$dados[colunas][conteudo][$i][3] .= "&nbsp;&nbsp;<a class='link permissao-modulo' value='".$row[Slug]."' type='L'>L</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a class='link permissao-modulo' value='".$row[Slug]."' type='G'>G</a><br>";
			while($pag = mpress_fetch_array($paginas)){
				if(strlen(array_search($pag[Modulo_Pagina_ID],$acessos[leitura]))>=1){$selecionadoL = " checked ";}else{$selecionadoL = "";}
				if(strlen(array_search($pag[Modulo_Pagina_ID],$acessos[gravacao]))>=1){$selecionadoG = " checked ";}else{$selecionadoG = "";}
				$nivelAcesso = "";
				//if ($pag[Slug]=='chamados-cadastrar'){
				//	$nivelAcesso = "<select name='nivel-acesso[".$pag[Modulo_Pagina_ID]."]' style='width:200px'>".optionValueArray($arrCadastroChamados,$acessos[nivelAcesso][$pag[Modulo_Pagina_ID]])."</select>";
				//}
				$espaco = "&nbsp;"; 
				if ($pag[Pagina_Pai_ID]!="") $espaco = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				$dados[colunas][conteudo][$i][3] .= "<p style='margin:0 0 0 2px;'>
												<input type='checkbox' name='rdPaginasL[]' class='paginas-L-".$pag[Modulo_Pagina_ID]." configurar-leitura-gravacao modulo-".$row[Slug]."-L' permissao='L' value='".$pag[Modulo_Pagina_ID]."' $selecionadoL>
												<input type='checkbox' name='rdPaginasG[]' class='paginas-G-".$pag[Modulo_Pagina_ID]." configurar-leitura-gravacao modulo-".$row[Slug]."-G' permissao='G' value='".$pag[Modulo_Pagina_ID]."' $selecionadoG >$espaco $pag[Titulo]$nivelAcesso <br>
											 </p>";
			}
		}
		$largura = "99%";
		$colunas = "3";

		$dados[colunas][tamanho][1] = "width='20%'";
		$dados[colunas][tamanho][2] = "width='30%'";

		$dados[colunas][titulo][1] 		= "M&oacute;dulo";
		$dados[colunas][titulo][2] 		= "Descri&ccedil;&atilde;o";
		$dados[colunas][titulo][3] 		= "P&aacute;ginas <span Style='float:right;font-weight:normal'><input type='button' value='Atualizar Permiss&otilde;es' id='atualiza-permissao' Style='height:30px;'></span>";

		geraTabela($largura,$colunas,$dados);
	}
?>