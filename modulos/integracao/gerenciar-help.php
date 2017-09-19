<?php
	include("../../includes/functions.gerais.php");
?>
<div id='cadastros-container'>
	<div class='titulo-container'>
		<div class='titulo' style='min-height:20px'>
			<p style='margin-top:2px;'>
				Localizar Registros cadastrados
				<input type='button' value='Incluir Novo registro' name='' class='help-cadastra-novo' style='float:right;height:24px;font-size:10px;margin-top:-3px;width:120px;'>
			</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario tres-colunas'>
				<p>Módulo</p>
				<p>
					<select name='modulo-selecionado' id='modulo-selecionado' class='required'>
						<option value=''>selecione</option>
<?php
	$rs = mpress_query("SELECT distinct Modulo_Nome, Modulo_Slug FROM help_paginas where Situacao_ID = 1 order by Modulo_Nome");
	while($row = mpress_fetch_array($rs)){
		echo  "			<option value='".$row['Modulo_Slug']."'>".$row['Modulo_Nome']."</option>";
	}
?>
					</select>
				</p>
			</div>
			<div class='titulo-secundario tres-colunas'>
				<p>Página Principal</p>
				<p id='select-pagina-principal-inclui'>
					<select name='select-pagina-principal[]' id='select-pagina-principal' disabled  class='required'>
						<option value=''>selecione</option>;
					</select>
				</p>
			</div>
			<div class='titulo-secundario tres-colunas'>
				<p>Página Secundária</p>
				<p class='omega' id='select-pagina-secundaria-inclui'>
					<select name='select-pagina-principal[]' id='select-pagina-secundaria' disabled>
						<option value=''>selecione</option>
					</select>
				</p>
			</div>
			<div class='titulo-secundario dez-colunas' Style='margin-top:5px; float:right;height:30px;'>
				<p class='omega'>
					<input type='button' value='Pesquisar' id='botao-pesquisar-help' />
				</p>
			</div>
		</div>
	</div>
</div>

<?php
	if($_POST){
		if($_POST['modulo-selecionado'])
			$modulo = "and Modulo_Nome = '".$_POST['modulo-selecionado']."'";
		if($_POST['select-pagina-principal']['0'] != "")
			$paginaPrincipal = "and (Pagina_Slug = '".$_POST['select-pagina-principal']['0']."')";
		if($_POST['select-pagina-principal']['1'] != "")
			$paginaSecundaria = "and Pagina_Secundaria_Slug = '".$_POST['select-pagina-principal']['1']."'";
		echo "	<div class='titulo-container'>
					<div class='titulo' style='min-height:20px'>
						<p style='margin-top:2px;'>Registros Localizados</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario uma-coluna'>";
		$rs = mpress_query("SELECT h.Help_ID, h.Titulo, p1.Modulo_Nome, p1.Pagina_Nome, p1.Pagina_Secundaria_Nome
							FROM `help` h
							INNER JOIN help_paginas p1 ON p1.Pagina_Slug = h.Slug_Pagina
							WHERE h.Situacao_ID = 1 $modulo $paginaPrincipal
							union all
							SELECT h.Help_ID, h.Titulo, p2.Modulo_Nome, p2.Pagina_Nome, p2.Pagina_Secundaria_Nome
							FROM `help` h
							inner JOIN help_paginas p2 ON p2.Pagina_Secundaria_Slug = h.Slug_Pagina
							WHERE h.Situacao_ID = 1 $modulo $paginaPrincipal $paginaSecundaria
							order by Modulo_Nome,Pagina_Nome,Pagina_Secundaria_Nome");
		while($row = mpress_fetch_array($rs)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "&nbsp;".$row['Modulo_Nome'];
			$dados[colunas][conteudo][$i][2] = "&nbsp;".$row['Pagina_Nome'];
			$dados[colunas][conteudo][$i][3] = "&nbsp;".$row['Pagina_Secundaria_Nome'];
			$dados[colunas][conteudo][$i][4] = "&nbsp;".$row['Titulo'];
			$dados[colunas][conteudo][$i][5] = "<center><a class='help-visualizar' attr-id='".$row['Help_ID']."'>visualizar</a></center>";
		}
		$dados[colunas][tamanho][1] = "width='20%'";
		$dados[colunas][tamanho][5] = "width='70px'";
		$dados[colunas][titulo][1] 	= "Modulo";
		$dados[colunas][titulo][2] 	= "Página Principal";
		$dados[colunas][titulo][3] 	= "Página Secundaria";
		$dados[colunas][titulo][4] 	= "Título";
		$largura = "100%";
		$colunas = "5";
		if($i>=1)
			geraTabela($largura,$colunas,$dados);
		else
			echo "			<span class='erro'>Nenhum registro Localizado!!</span>";
		echo "				<input type='hidden' name='pagina-help-id' id='pagina-help-id'>
						</div>
					</div>
				</div>";
	}
?>