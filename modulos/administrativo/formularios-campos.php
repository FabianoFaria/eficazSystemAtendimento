<?php
include("functions.php");
echo "		<div class='titulo-container'>
				<div class='titulo'>
					<p>
						Campos Cadastrados
						<input type='button' value='Incluir Novo Campo' class='inc-alt-campo' campo-id='' style='width:150px;'>
					</p>
				</div>
				<div class='conteudo-interno'>";


	if($_POST['ordena-tabela'] != ""){
		$ordem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$ordem = " order by Nome";
	}
	echo "<input type='hidden' id='campo-id' name='campo-id' value=''>";

	$sql = "SELECT fc.Campo_ID, fc.Nome, fc.Descricao, fc.Tipo_Campo, fc.Data_Cadastro, cd.Nome as Usuario_Cadastro
				FROM formularios_campos fc
				left join cadastros_dados cd on cd.Cadastro_ID = fc.Usuario_Cadastro_ID
				where fc.Situacao_ID = 1
				$ordem";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;' class='inc-alt-campo link' campo-id='".$row[Campo_ID]."'>".$row[Nome]."</p>";
		$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$descrTiposCampos[$row[Tipo_Campo]]."</p>";
		$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Descricao]."</p>";
		$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Usuario_Cadastro]."</p>";
		$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".converteDataHora($row[Data_Cadastro],1)."</p>";
	}
	if($i==0){
		echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum formul&aacute;rio cadastrado</p>";
	}
	else{
		$largura = "100%";

		$dados[colunas][titulo][1] 	= "Campo";
		$dados[colunas][titulo][2] 	= "Tipo Campo";
		$dados[colunas][titulo][3] 	= "Descri&ccedil;&atilde;o";
		$dados[colunas][titulo][4] 	= "Usu&aacute;rio Cadastro";
		$dados[colunas][titulo][5] 	= "Data Cadastro";

		$dados[colunas][ordena][1] = "Nome";
		$dados[colunas][ordena][2] = "Tipo_Campo";
		$dados[colunas][ordena][3] = "Descricao";
		$dados[colunas][ordena][4] = "Usuario_Cadastro";
		$dados[colunas][ordena][5] = "Data_Cadastro";
		geraTabela($largura,5,$dados, null, 'formulario-campos-localiza', 2, 2, '','');
	}

	echo "		</div>
			</div>";
?>