<?php
	if($_POST['nome']!= ""){
		$nome 	= $_POST['nome'];
		$pai 	= $_POST['pai'];
		if($_POST['categoria-atualiza'] == ""){
			$query = mpress_query("select Tipo_ID from tipo where Descr_Tipo = '$nome' and Situacao_ID = 1 and Tipo_Grupo_ID = 40");
			if(!$produto = mpress_fetch_array($query)){
				mpress_query("insert into tipo(Descr_Tipo, Tipo_Auxiliar,Tipo_Grupo_ID)values('$nome','$pai', '40')");
			}
		}else{
			mpress_query("update tipo set Descr_Tipo = '$nome', Tipo_Auxiliar = '$pai' where Tipo_ID = ".$_POST['categoria-atualiza']);
		}
	}
	if($_POST['categoria-exclui']!="")
		mpress_query("update tipo set Situacao_ID = 2 where Tipo_ID ='".$_POST['categoria-exclui']."'");
?>
<div id='chamados-container'>
	<div class='titulo-secundario duas-colunas'>
		<div class='titulo-container-interno-alpha'>
			<div class='titulo'>
				<p Style='margin-top:2px'>&nbsp;Cadastrar nova Tarefa</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna'>
					<p>Nome</p>
					<p><input type='text' id='nome' name='nome' value='' /></p>
				</div>
				<div class='titulo-secundario uma-coluna'>
					<p>Tarefa Pai</p>
					<p>
						<select name='pai' id='pai'>
							<option value=''>Nenhuma</option>
<?php
		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		while($categoria1 = mpress_fetch_array($query)){
			echo "<option value='".$categoria1['Tipo_ID']."'>".$categoria1['Descr_Tipo']."</option>";
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar ='".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($categoria2 = mpress_fetch_array($query2)){
				echo "<option value='".$categoria2['Tipo_ID']."'>- ".$categoria2['Descr_Tipo']."</option>";
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar ='".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria3 = mpress_fetch_array($query3)){
					echo "<option value='".$categoria3['Tipo_ID']."'>-- ".$categoria3['Descr_Tipo']."</option>";
					$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar ='".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria4 = mpress_fetch_array($query4)){
						echo "<option value='".$categoria4['Tipo_ID']."'>--- ".$categoria4['Descr_Tipo']."</option>";
					}
				}
			}
		}
?>
						</select>
					</p>
					<input type='button' value='Cancelar edição' id='cancela-edita-tarefa' Style='width:130px;float:right;margin-top:5px;margin-right:15px;' class='esconde'>
					<input type='button' value='Cadastrar Tarefa' id='cadastra-nova-tarefa' Style='width:130px;float:right;margin-top:5px;margin-right:15px;'>
				</div>
			</div>
		</div>
	</div>

<?php
		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		while($categoria1 = mpress_fetch_array($query)){
			$i++;
			$dados[colunas][conteudo][$i][1] = $categoria1['Descr_Tipo'];
			$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-categoria' id='".$categoria1['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir Tarefa'>&nbsp;</div>";
			$dados[colunas][conteudo][$i][3] = "<div class='btn-editar' style='float:right;padding-right:10px'  title='Editar' id='".$categoria1['Tipo_ID']."' descr='".$categoria1['Descr_Tipo']."' pai=''>&nbsp;</div>";
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar = '".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($categoria2 = mpress_fetch_array($query2)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;".$categoria2['Descr_Tipo'];
				$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-categoria' id='".$categoria2['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir Tarefa'>&nbsp;</div>";
				$dados[colunas][conteudo][$i][3] = "<div class='btn-editar' style='float:right;padding-right:10px'    title='Editar'id='".$categoria2['Tipo_ID']."' descr='".$categoria2['Descr_Tipo']."' pai='".$categoria1['Tipo_ID']."'>&nbsp;</div>";
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar = '".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria3 = mpress_fetch_array($query3)){
					$i++;
					$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria3['Descr_Tipo'];
					$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-categoria' id='".$categoria3['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir Tarefa'>&nbsp;</div>";
					$dados[colunas][conteudo][$i][3] = "<div class='btn-editar' style='float:right;padding-right:10px'  title='Editar'id='".$categoria3['Tipo_ID']."' descr='".$categoria3['Descr_Tipo']."' pai='".$categoria2['Tipo_ID']."'>&nbsp;</div>";
					$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar = '".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria4 = mpress_fetch_array($query4)){
						$i++;
						$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria4['Descr_Tipo'];
						$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-categoria' id='".$categoria4['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir Tarefa'>&nbsp;</div>";
						$dados[colunas][conteudo][$i][3] = "<div class='btn-editar' style='float:right;padding-right:10px'  title='Editar' id='".$categoria4['Tipo_ID']."' descr='".$categoria4['Descr_Tipo']."' pai='".$categoria3['Tipo_ID']."'>&nbsp;</div>";
						$query5 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = 40 and Tipo_Auxiliar = '".$categoria4['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						while($categoria5 = mpress_fetch_array($query5)){
							$i++;
							$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$categoria5['Descr_Tipo'];
							$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-categoria' id='".$categoria5['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir Tarefa'>&nbsp;</div>";
							$dados[colunas][conteudo][$i][3] = "<div class='btn-editar' style='float:right;padding-right:10px'  title='Editar' id='".$categoria5['Tipo_ID']."' descr='".$categoria5['Descr_Tipo']."' pai='".$categoria4['Tipo_ID']."'>&nbsp;</div>";
						}
					}
				}
			}
		}

		if($i>=1){
			$largura = "99%";
			$colunas = "3";
			$style	 = "float:left;";
			$titulo  = "display:none";
			$dados[colunas][tamanho][2] = "width='38x'";
			$dados[colunas][tamanho][3] = "width='38px'";
		}

?>
	<div class='titulo-secundario duas-colunas'>
		<div class='titulo-container-interno-omega' Style='min-height:317px'>
			<div class='titulo'>
				<p>Tarefas Cadastradas</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna'>
					<?php geraTabela($largura,$colunas,$dados,$style);?>

				</div>
			</div>
		</div>
	</div>
	<input type='hidden' name='categoria-atualiza' id='categoria-atualiza' value=''>
	<input type='hidden' name='categoria-exclui'   id='categoria-exclui'   value=''>
</div>