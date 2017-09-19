<?php
	$dadospagina = get_page_content();
	$documentoID = $_POST['documento-id'];
	global $modulosAtivos;
?>
<div id='div-retorno'></div>
<div id="container-geral">
	<div class="titulo-container">
		<div class="titulo" style="min-height:25px">
			<p style="margin-top:2px;">Documentos para Edição</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario Style='width:100%'">
				<p class="omega">
					<select name="documentos-disponiveis" id="documentos-disponiveis" class='documentos-disponiveis'>
						<option value="">Selecione</option>
						<?php documentosDisponiveis($documentoID);?>
					</select>
					<table width="100%"id='cadastra-documento' style='display:none'>
						<tr>
							<td width="120">T&iacute;tulo do Documento:</td>
							<td>
								<input type="text" name="nome-documento" id="nome-documento" Style='width:98.5%;height:18.5px;'>
								<input type='hidden' name='documento-id' id='documento-id' value='<?php echo $documentoID;?>'/>
								<input type='hidden' name='action-excluir-documento' id='action-excluir-documento'>
							</td>
							<td width="100"><input type="button" value="Cadastrar" id="salva-documento" Style='width:95px'></td>
							<td width="100"><input type="button" value="Cancelar" id="cancela-documento" Style='width:100px'></td>
						</tr>
					</table>
				</p>
			</div>
		</div>
	</div>
<?php
	if ($documentoID!=""){
		$rs = mpress_query("select Titulo, Texto_Documento, Cabecalho_Rodape, Slug_Modulo from cadastros_documentos where Documento_ID = $documentoID");
		if ($row = mpress_fetch_array($rs)){
			$titulo 		= $row[Titulo];
			$textoDocumento = str_replace("´","'",$row[Texto_Documento]);
			$cabecalho		= $row[Cabecalho_Rodape];
			$modulo			= $row[Slug_Modulo];
		}
?>
		<div class="titulo-container" id='detalhes-documento-container'>
			<div class='titulo'>
				<p>
					Detalhes do Documento
					<input type="button" value="Excluir" id="exclui-documento" Style='width:100px;float:right;'>
					<input type="button" value="Atualizar" id="atualiza-documento" Style='width:95px;float:right;margin-right:10px;'>
				</p>
			</div>
			<div class='conteudo-interno'>
				<div class='titulo-secundario uma-coluna'>
					<div class='titulo-secundario' style='width:50%;float:left;'>
						<p><b>Titulo:</b></p>
						<p><input type='text' name='edita-nome-documento' id='edita-nome-documento' Style='width:95%' value='<?php echo $titulo;?>'/></p>
					</div>
					<div class='titulo-secundario' style='width:20%;float:left;'>
						<p><b>Destino:</b></p>
						<p><select name='destino-documento' id='destino-documento' Style='width:99%'>
<?php
			  $selecionado[$modulo] = "selected";

			  /*echo "<option>*** ";
			  print_r($modulosAtivos['chamados-orcamento']);
			  echo " ***</option>";*/
			  if ($modulosAtivos[cadastros]){ echo "<option value='cadastros'"; if ($destinoDocumento=='cadastros') echo 'selected'; echo " ".$selecionado['cadastros'].">Cadastros</option>"; }
			  if ($modulosAtivos[chamados]){ echo "<option value='chamados'"; if ($destinoDocumento=='chamados') echo 'selected'; echo " ".$selecionado['chamados'].">".$_SESSION['objeto']."</option>"; }
			  if ($modulosAtivos[compras]){ echo "<option value='compras'"; if ($destinoDocumento=='compras') echo 'selected'; echo " ".$selecionado['compras'].">Compras</option>"; }
			  if ($modulosAtivos[envios]){ echo "<option value='envios'"; if ($destinoDocumento=='envios') echo 'selected'; echo " ".$selecionado['envios'].">Centro de Distribuição</option>"; }
			  if ($modulosAtivos['chamados-orcamento']){ echo "<option value='orcamentos'"; if ($destinoDocumento=='orcamentos') echo 'selected'; echo " ".$selecionado['orcamentos'].">Orçamentos</option>"; }
			  if ($modulosAtivos[turmas]){ echo "<option value='turmas'"; if ($destinoDocumento=='turmas') echo 'selected'; echo " ".$selecionado['turmas'].">Turmas</option>"; }

			  tinyMCE('detalhes-documento',$modulo);
?>
							 </select>
							 <input type='hidden' id='origem-selecionada' name='origem-selecionada' value='<?php echo $destinoDocumento; ?>'/>
						</p>
					</div>
					<div class='titulo-secundario' style='width:30%;float:left;'>
						<p>&nbsp;</p>
						<p align='right'>
							<input type='checkbox' name='chkCabecalho' value='C' <?php if(($cabecalho=='C')||($cabecalho=='CR')) echo "checked";?>>Cabe&ccedil;alho
							&nbsp;&nbsp;
							<input type='checkbox' name='chkRodape' value='R' <?php if(($cabecalho=='R')||($cabecalho=='CR')) echo "checked";?>>Rodape
						</p>
					</div>
				</div>
				<div class='titulo-secundario uma-coluna'>
					<div class="titulo-secundario" Style='width:100%;margin-top:10px;'>
						<textarea name='detalhes-documento' id='detalhes-documento' Style='height:400px'><?php echo $textoDocumento;?></textarea>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
	}
	/********************************* Inicio Funções de Gerenciar Documentos *********************************/
	function documentosDisponiveis($documentoID){
		$optionValue = "<option value='-1'>Cadastrar novo Documento</option>";
		$rs = mpress_query("select Documento_ID, Titulo from cadastros_documentos where Situacao_ID = 1 order by Titulo");
		while($row = mpress_fetch_array($rs)){
			if($documentoID==$row[0])$selecionado = "selected"; else $selecionado = "";
			$optionValue .= "<option value='$row[0]' $selecionado>$row[1]</option>";
		}
		echo $optionValue;
	}
	/********************************* Fim Funções de Gerenciar Documentos *********************************/
?>