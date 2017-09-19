<?php
	require_once("includes/functions.gerais.php");
	require_once("config.php");

	$tipoLixeira = $_POST['tipo-lixeira'];
	$lixeiraID  = $_POST['id-lixeira'];
	$slug  = $_POST['slug'];


	if ($tipoLixeira=='tipos'){
		echo "<p><a href='$slug' class='link'>Voltar<a></p>";
		echo "<input type='hidden' name='tipoID' id='tipoID' value=''>";
		echo "<input type='hidden' name='slug' id='slug' value='$slug'>";
		echo "<input type='hidden' name='slug-lixeira' id='slug-lixeira' value='$slugLixeira'>";
		echo "<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='$tipoLixeira'>";
		echo "<input type='hidden' name='lixeira' id='lixeira' value='$lixeiraID'>";

		$sql = "select Tipo_ID, Descr_Tipo, Situacao_ID from tipo where Situacao_ID = 3 and Tipo_Grupo_ID = $lixeiraID";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;width:60%;'><a href='#' class='link recupera-item-lixeira' id='lixeira-recupera-".$row[Tipo_ID]."'>".$row[Descr_Tipo]."<a></p><p Style='margin:2px 5px 0 5px;float:right;width:20%;text-align:right;' class='exclui' id='exclui-item-".$row[Tipo_ID]."' title='Excluir ".$row[Descr_Tipo]."'>X</p>";
		}
		if($i>=1){
			$largura = "99%";
			$colunas = "1";
			$dados[colunas][tamanho][1] = "width='100%'";
			$dados[colunas][titulo][1] 	= "&nbsp;Ítens Excluídos";
			geraTabela($largura,$colunas,$dados);
		}else{
			echo "Nenhum ítem localizado na lixeira para o módulo selecionado";
		}
	}
?>