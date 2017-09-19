<?php
echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Formul&aacute;rios Cadastrados
						<input type='button' value='Incluir Novo Fomul&aacute;rio' class='inc-alt-formulario' formulario-id='' style='width:150px;'>
					</p>
				</div>
				<div class='conteudo-interno'>";


	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by Nome";
	}
	echo "<input type='hidden' id='formulario-id' name='formulario-id' value=''>";

	$sql = "SELECT f.Formulario_ID, f.Nome, f.Tabela_Estrangeira, u.Nome as Usuario_Cadastro, f.Data_Cadastro
			FROM formularios f
			left join cadastros_dados u on u.Cadastro_ID = f.Usuario_Cadastro_ID
			$ordem";

	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$nome = $row[Nome];
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-formulario link' formulario-id='".$row[Formulario_ID]."'>".$row[Formulario_ID]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-formulario link' formulario-id='".$row[Formulario_ID]."'>".$row[Nome]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Tabela_Estrangeira]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Usuario_Cadastro]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($row[Data_Cadastro],1)."</p>";
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum formul&aacute;rio cadastrado</p>";
	}
	else{
		$largura = "100%";
		$dados[colunas][tamanho][1] = "width='6%'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "Formul&aacute;rio";
		$dados[colunas][titulo][3] 	= "M&oacute;dulo Destino";
		$dados[colunas][titulo][4] 	= "Usu&aacute;rio Cadastro";
		$dados[colunas][titulo][5] 	= "Data Cadastro";

		$dados[colunas][ordena][1] = "Formulario_ID";
		$dados[colunas][ordena][2] = "Nome";
		$dados[colunas][ordena][3] = "Tabela_Estrangeira";
		$dados[colunas][ordena][4] = "Usuario_Cadastro";
		$dados[colunas][ordena][5] = "Data_Cadastro";
		geraTabela($largura,5,$dados, null, 'formulario-dinamico-localiza', 2, 2, '','');
	}

	echo "		</div>
			</div>";
?>