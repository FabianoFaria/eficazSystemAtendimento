<?php
	session_start();
	if (!function_exists("get_header"))
		require_once("../../includes/functions.gerais.php");
	if (!function_exists("mpress_query"))
		require_once("../../config.php");

	function excluirGrupo(){
		$grupoID = $_GET['grupo-id'];
		$sql = "update modulos_acessos set Situacao_ID = 3 where Modulo_Acesso_ID = '$grupoID'";
		mpress_query($sql);
	}

/********************************* Inicio Funções Documentos *********************************/

	function carregarDocumentos($id, $origem){
		global $dadosUserLogin, $caminhoSistema, $tituloSistema, $caminhoFisico;
		$titulo = "Documentos";
		$titulo2 = "Documentos e Anexos";
		if ($origem=="produtos"){
			$titulo2 = $titulo = "Imagens";
		}
		$botaoIncluir = "	<input type='button' id='botao-novo-documento' class='btn-novo' value='Incluir' style='width:95px'>";

		if (($origem=="modulos_listas_cadastros") || ($origem=="modulos_listas_produtos")){
			$escondeCampanhas = " esconde ";
			$titulo = "Bases CSV";
			$titulo2 = "Arquivos";
		}

		echo "	<div id='div-mensagem'></div>
				<div class='titulo-container' id='div-cadastros-documentos'>
					<div class='titulo'>
						<p>
							$titulo
							$botaoIncluir
							<input type='button' class='botao-enviar-email-geral $escondeCampanhas' value='Enviar e-mail' style='width:95px;'>
							<input type='hidden' name='id-referencia' id='id-referencia' value='$id'/>
							<input type='hidden' name='origem-documento' id='origem-documento' value='$origem'/>
							<input type='hidden' name='nome-arquivo' id='nome-arquivo' value=''/>
							<input type='hidden' name='usuario-cadastro-documento-id' id='usuario-cadastro-documento-id' value='".$dadosUserLogin['userID']."'/>
							<input type='button' value='Cancelar' id='botao-cancelar-documento-edita' class='esconde' style='width:100px;float:right;'>
							<input type='button' value='Atualizar' id='botao-atualiza-documento-anexo' class='esconde' style='width:95px;float:right;margin-right:10px;'>
						</p>
					</div>
					<div class='conteudo-interno' id='conteudo-interno-documentos'>
						<div id='div-cadastro-documentos'></div>
						<div id='div-documentos-cadastrados'>";

		$sql = "select distinct a.Documento_ID, a.Nome_Arquivo_Original Titulo, d.Texto_Documento, a.Nome_Arquivo, a.Data_Cadastro, a.Anexo_ID,  u.Nome as Usuario, a.Observacao
				from modulos_anexos a
				left join cadastros_documentos d on a.Documento_ID = d.Documento_ID
				left join cadastros_dados u on u.Cadastro_ID = a.Usuario_Cadastro_ID
				where a.Chave_Estrangeira = '$id'
				and a.Situacao_ID = 1
				and Tabela_Estrangeira = '$origem'
				order by a.Data_Cadastro desc, titulo";
		//echo $sql;

		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$c = 1;
			$imagem = "";
			$extensao = strtolower(strrchr($row['Nome_Arquivo'],"."));
			$dataHora = converteDataHora($row['Data_Cadastro'],1);

			if (($extensao==".png") || ($extensao==".jpg") || ($extensao==".gif")) $imagem = "<img src='$caminhoSistema/uploads/".utf8_encode($row['Nome_Arquivo'])."' height='35px'/>";
			if ($extensao==".pdf") $imagem = "<img src='$caminhoSistema/images/geral/pdf.gif' height='35px'/>";
			if (($extensao==".xls") || ($extensao==".xlsx") || ($extensao==".csv")) $imagem = "<img src='$caminhoSistema/images/geral/excel.jpg' height='35px'/>";
			if (($extensao==".doc") || ($extensao==".docx") || ($extensao==".rtf")) $imagem = "<img src='$caminhoSistema/images/geral/word.jpg' height='35px'/>";
			if (($extensao==".rar") || ($extensao==".zip")) $imagem = "<img src='$caminhoSistema/images/geral/winrar.gif' height='35px'/>";
			if ($extensao==".txt") $imagem = "<img src='$caminhoSistema/images/geral/txt.jpg' height='35px'/>";
			if ($extensao==".html") $imagem = "<img src='$caminhoSistema/images/geral/html.jpg' height='35px'/>";
			if ($imagem=="") $imagem = "<img src='$caminhoSistema/images/geral/desconhecido.jpg' height='35px'/>";

			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:0px' align='center'>".$imagem."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:0px; float:left;'>
													<b><a href='$caminhoSistema/uploads/".utf8_encode($row['Nome_Arquivo'])."' class='link' target='documento'>".utf8_encode($row[Titulo])."</a></b>
												</p>";
			//if ($origem=="orcamentos"){
			//	$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:0px'>$proposta</p>";
			//}

			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:0px;' align='center'>".utf8_encode($row['Usuario'])."</p>";
			$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:0px;' align='center'>".$dataHora."</p>";

			if (($origem=="modulos_listas_cadastros") || ($origem=="modulos_listas_produtos")){
				$resultsetImp = mpress_query("select Count(*) as cont from modulos_listas where Anexo_ID = '".$row['Anexo_ID']."' and Slug = '".$origem."'");
				$rowImp = mpress_fetch_array($resultsetImp);
				if($rowImp['cont']>0)
					$compleImp = "<span style='color:red' title='Planilha importada ".$rowImp['cont']." vez(es)'><b>***</b></span>";
				else
					$compleImp = "<span><b>&nbsp;&nbsp;&nbsp;&nbsp;</b></span>";

				$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' name='arquivo-importar-base[]' class='arquivo-importar-base' value='".$row['Anexo_ID']."'> $compleImp</center>";
			}
			else{
				$dados[colunas][conteudo][$i][$c++] = "<center><input type='checkbox' name='arquivo-anexo-envia-email[]' class='arquivo-anexo-envia-email' value='$caminhoFisico/uploads/".utf8_encode($row['Nome_Arquivo'])."'></center>";
				$htmlConteudo = "";
				if (($row['Observacao']!='')){
					$htmlConteudo = "<center><img class='link link-ver-html mini-lupa' src='$caminhoSistema/images/geral/mini-lupa.png' align='center' anexo-id='".$row['Anexo_ID']."'/></center>";
				}
				$dados[colunas][conteudo][$i][$c++] = $htmlConteudo;
			}

			if(($dadosUserLogin['grupoID'] != -2) && ($dadosUserLogin['grupoID'] != -3)) {
				$dados[colunas][conteudo][$i][$c++] = "<div class='btn-excluir btn-excluir-documento' style='float:right; padding-right:5px'  anexo-id='$row[Anexo_ID]' nome-anexo='".$row['Nome_Arquivo']."' title='Excluir'>&nbsp;</div>";
				if($row['Documento_ID'] != "")
					$dados[colunas][conteudo][$i][$c++] = "<div class='btn-editar btn-editar-documento-anexo' style='float:right;padding-right:5px' anexo-id='$row[Anexo_ID]' title='Editar'>&nbsp;</div>";
				else
					$dados[colunas][conteudo][$i][$c++] = "";
			}
			$colunas = $c - 1;
		}


		$largura = "100%";
		$dados[colunas][tamanho][1] = "width='70px' heigth='70px'";

		$c = 1;
		$dados[colunas][titulo][$c++] 	= "&nbsp;";
		$dados[colunas][titulo][$c++] 	= "&nbsp;".$titulo2;

		//if ($origem=="orcamentos"){
		//	$dados[colunas][titulo][$c++] = "&nbsp;Proposta";
		//}

		$dados[colunas][titulo][$c++] 	= "&nbsp;Usu&aacute;rio Respons&aacute;vel";
		$dados[colunas][titulo][$c++] 	= "&nbsp;Data";

		if (($origem=="modulos_listas_cadastros") || ($origem=="modulos_listas_produtos")){
			$dados[colunas][titulo][$c++] 	= "<center>Importar</center>";
			$dados[colunas][tamanho][$c] = "width='50px'";
		}
		else{
			$dados[colunas][titulo][$c++] 	= "<center>Anexar</center>";
			$dados[colunas][tamanho][$c] = "width='25px'";
			$dados[colunas][titulo][$c++] 	= "<center><!--HTML--></center>";
			$dados[colunas][tamanho][$c] = "width='25px'";
		}
		$dados[colunas][titulo][$c++] 	= "&nbsp;";
		$dados[colunas][tamanho][$c] = "width='25px'";

		if($i>0){
			geraTabela($largura,$colunas,$dados);
		}
		else{
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhum documento cadastrado</p>";
		}


		$sql = "select d.Documento_ID, d.Titulo, d.Texto_Documento
				from cadastros_documentos d
				where Situacao_ID = 1 and  d.Slug_Modulo = '$origem'
				order by titulo ";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado))
			$optionValueModelos .= "<option value='".$row['Documento_ID']."' nome-arquivo='".utf8_encode($row['Titulo'])."'>Gerar ".utf8_encode($row['Titulo'])."</option>";

		$aleatorio = date("YmdHis");

		echo "			</div>
						<div id='div-documentos-edicao'></div>
						<div class='div-aguarde-email-documento'></div>
						<div class='div-enviar-email-anexos esconde'>
							<div class='titulo-secundario uma-coluna' style='margin-top:10px;'>
								<p><b>E-mail(s) para envio:</b> <i>(separar emails por ponto e virgula;)</i></p>
								<p><input type='text' name='emails-email-enviar-geral' id='emails-email-enviar-geral' style='width:99%;' value='' class='required'/></p>
							</div>
							<div class='titulo-secundario duas-colunas' style='margin-top:5px;'>
								<p><b>T&iacute;tulo Email</b></p>
								<p><input type='text' name='titulo-email-enviar-geral' id='titulo-email-enviar-geral' style='width:99%;' value='' class='required'/></p>
							</div>
							<div class='titulo-secundario duas-colunas' style='margin-top:5px;'>
								<p><b>Utilizar modelo de email:</b></p>
								<p>
									<select name='modelo-email-id' id='modelo-email-id' class='modelo-email-id'>
										<option></option>
										$optionValueModelos
									</select>
								</p>
							</div>
							<div class='titulo-secundario uma-coluna' style='margin-top:5px;'>
								<p><b>Texto Email:</b></p>
								<p><textarea name='texto-email-enviar-geral-$aleatorio' id='texto-email-enviar-geral-$aleatorio' style='width:99%; height:100px;'></textarea></p>
								<input type='hidden' name='editor-aleatorio' id='editor-aleatorio' value='$aleatorio'/>
							</div>
							<div class='titulo-secundario uma-coluna'>
								<br>
								<p>
									<input type='button' class='botao-enviar-email-geral-submete' value='Enviar Email' style='width:150px; float:right;'>
									<input type='button' class='botao-cancelar-enviar-email-geral' value='Cancelar' style='width:150px; float:right;'/>
								</p>
							</div>
						</div>
					</div>
				</div>
				<div id='div-exibir-listagem-importacao'></div>
				<script>
					$('select').chosen({width: '99%', no_results_text: 'Nenhuma op&ccedil;&atilde;o localizada!', placeholder_text_single: ' ', placeholder_text_multiple: ' ', allow_single_deselect: true });
				</script>";
		tinyMCE("texto-email-enviar-geral-".$aleatorio);

	}

	function documentosEnviarEmail(){
		global $dadosUserLogin, $caminhoSistema, $caminhoFisico;
		$i = 0;

		$titulo = utf8_decode($_POST['titulo-email-enviar-geral']);
		$dadosEmail = $_POST['emails-email-enviar-geral'];
		$aleatorio = $_POST['editor-aleatorio'];

		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		
		$conteudoEmail = "
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			</head>
			<body style='margin: 0 auto; background-color:#ffffff'>
				<table border='0' align='center' cellpadding='0' cellspacing='0'>
					<tr>
						<td width='735' height='80' align='center'>
							<img src='".$caminhoSistema."/images/documentos/cabecalho.jpg' border='0' id='r&r' style='display: block' />
						</td>
					</tr>
					<tr>
						<td><br><br>".$_POST['texto-email-enviar-geral-'.$aleatorio]."<br><br></td>
					</tr>
					<tr>
						<td width='735' height='55' align='center'>
							<img src='".$caminhoSistema."/images/documentos/rodape.jpg' border='0' style='display: block' />
						</td>
					</tr>
				</table>
			</body>
		</html>";
		foreach($_POST['arquivo-anexo-envia-email'] as $caminhoArquivo){
			$arquivosAnexos[] = $caminhoArquivo;
		}
		echo enviaEmails($dadosEmail, $titulo, $conteudoEmail, "Envio efetuado com successo", $arquivosAnexos);
	}


	function excluirAnexoDocumento(){
		global $caminhoFisico;
		$anexoID 	= $_GET['idAnexo'];
		$nomeAnexo 		= $_GET['nomeAnexo'];
		mpress_query("update modulos_anexos set Situacao_ID = 3 where Anexo_ID = '$anexoID'");
		//unlink($caminhoFisico."/uploads/$nomeAnexo");
	}

	function carregarCadastroDocumento(){
		global $dadosUserLogin;
		$origem = $_GET['origem'];
		$id = $_GET['id'];
		if($dadosUserLogin[grupoID] != -3){
			$sql = "select d.Documento_ID, d.Titulo, d.Texto_Documento
					from cadastros_documentos d
					where Situacao_ID = 1 and  d.Slug_Modulo = '$origem'
					order by titulo ";
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado))
				$optionValue .= "<option value='".$row['Documento_ID']."' nome-arquivo='".utf8_encode($row['Titulo'])."'>Gerar ".utf8_encode($row['Titulo'])."</option>";
		}

		if ($origem=='orcamentos'){
			$optionValuePropostas = "<option id=''></option>";

			$resultado = mpress_query("select Proposta_ID, Titulo from orcamentos_propostas where Workflow_ID = '$id' and Situacao_ID = 1 order by Titulo");
			while($rs = mpress_fetch_array($resultado)){
				$optionValuePropostas .= "<option value='".$rs['Proposta_ID']."'>".utf8_encode($rs['Titulo'])."</option>";
			}

			$tamanho = "width:35%;";
			$divAuxOrcamentos = "
				<div style='float:left; $tamanho' id='div-tipo-documento-id'>
					<p><b>Proposta desejada:</b></p>
					<p>
						<select name='id-referencia-secundario' id='id-referencia-secundario'>
							$optionValuePropostas
						</select>
					</p>
				</div>";
		}
		else{
			$tamanho = "width:70%;";
		}
		echo "	<div class='titulo-secundario uma-coluna' Style='margin-top:5px; margin-bottom:15px'>
					<div class='titulo-secundario uma-coluna'>
						<div style='float:left; $tamanho' id='div-tipo-documento-id'>
							<p><b>Selecione a op&ccedil;&atilde;o desejada</b></p>
							<p>
								<select name='tipo-documento-id' id='tipo-documento-id'>
									<option value='-1' nome-arquivo=''>Upload de arquivo digital</option>
									$optionValue
								</select>
							</p>
						</div>
						$divAuxOrcamentos
						<div style='width:15%; float:left; margin-top:15px;'>
							<input type='button' value='Selecione Arquivos' id='botao-selecione-arquivo' style='width:95%;'>
							<input type='button' value='Gerar Arquivo' id='botao-novo-documento-inclui' class='esconde' Style='width:95%'/>
						</div>
						<div style='width:15%; float:left; margin-top:15px;'>
							<input type='button' value='Cancelar' id='botao-cancelar-documento' class='botao-cancelar-documento' Style='width:99.5%'/>
						</div>
						<div id='filelist'  style='width:100%; float:left; margin-top:5px;'></div>
						<div id='console'   style='width:100%; float:left;'></div>
					</div>
				</div>
				<script>
					$('select').chosen({width: '99%', no_results_text: 'Nenhuma op&ccedil;&atilde;o localizada!', placeholder_text_single: ' ', placeholder_text_multiple: ' ', allow_single_deselect: true });
				</script>";
	}

	global $descrTiposCampos;
	$descrTiposCampos['text'] = "Texto Aberto";
	$descrTiposCampos['textarea'] = "Texto Longo";
	$descrTiposCampos['select'] = "Select Drop Down";
	$descrTiposCampos['radio'] = "Radio";
	$descrTiposCampos['checkbox'] = "Checkbox de Multiplas Op&ccedil;&otilde;es";

	function optionValueTiposCampos($selecionado){
		global $descrTiposCampos;
		$sel[$selecionado] = "selected";
		return "	<option value='text' ".$sel['text'].">".$descrTiposCampos['text']."</option>
					<option value='textarea' ".$sel['textarea'].">".$descrTiposCampos['textarea']."</option>
					<option value='select' ".$sel['select'].">".$descrTiposCampos['select']."</option>
					<option value='radio' ".$sel['radio'].">".$descrTiposCampos['radio']."</option>
					<option value='checkbox' ".$sel['checkbox'].">".$descrTiposCampos['checkbox']."</option>";
	}


	function optionValueAlturaCampos($selecionado){
		$sel[$selecionado] = "selected";
		for($i=100; $i>35; $i=($i-5)){
			$optionValue .= "<option value='".$i."px' ".$sel[$i.'px'].">".$i."px</option>";
		}
		return $optionValue;
	}

	function optionValueTamanhosCampos($selecionado){
		$sel[$selecionado] = "selected";
		for($i=100; $i>0; $i=($i-5)){
			$optionValue .= "<option value='".$i."%' ".$sel[$i.'%'].">".$i."%</option>";
		}
		return $optionValue;
	}

	function optionValuePosicoesCampos($posicoes, $selecionado){
		$sel[$selecionado] = "selected";
		for($i=1; $i<=$posicoes; $i++){
			$optionValue .= "<option value='$i' ".$sel[$i].">$i</option>";
		}
		return $optionValue;
	}

	function optionValueQuantidadeCampos($quantidade, $selecionado){
		$sel[$selecionado] = "selected";
		for($i=2;$i<=$quantidade;$i++){
			$j="";
			$optionValue .= "<option value='$i' ".$sel[$i].">$i</option>";
		}
		return $optionValue;
	}


	function optionValueCampos($selecionado, $formularioID){
		global $descrTiposCampos;
		$sel[$selecionado] = " selected ";
		$sql = "SELECT fc.Campo_ID, fc.Nome, fc.Descricao, fc.Tipo_Campo, fc.Usuario_Cadastro_ID, fc.Data_Cadastro,
							(select count(*) from formularios_campos_opcoes fco where fco.Campo_ID = fc.Campo_ID and fco.Situacao_ID = 1) as Quantidade,
							ffc.Formulario_Campo_ID
				FROM formularios_campos fc
				left join formularios_formulario_campo ffc on ffc.Campo_ID = fc.Campo_ID and ffc.Formulario_ID = '$formularioID'
				where fc.Situacao_ID = 1
				ORDER BY Tipo_Campo, Nome";
		//echo $sql;
		$resultado = mpress_query($sql);
		while($rs = mpress_fetch_array($resultado)){
			$disabled = "";
			if ($rs['Formulario_Campo_ID']!=''){
				$disabled = "disabled";
				if ($selecionado==$rs['Campo_ID'])
					$disabled = "";
			}
			if ($tipoCampoAnt != $rs['Tipo_Campo']){
				$optionValue .= $fechaGrupo."<optgroup label='".utf8_encode($descrTiposCampos[$rs['Tipo_Campo']])."'>";
				$fechaGrupo .= "</optgroup>";
			}
			$optionValue .= "	<option value='".$rs['Campo_ID']."' tipo-campo='".$rs['Tipo_Campo']."' quantidade='".$rs['Quantidade']."' tipo-campo-descricao='".utf8_encode($descrTiposCampos[$rs['Tipo_Campo']])."' descricao='".utf8_encode($rs['Descricao'])."' ".$sel[$rs['Campo_ID']]." $disabled>".utf8_encode($rs['Nome'])."</option>";
			$tipoCampoAnt = $rs['Tipo_Campo'];
		}
		$optionValue .= $fechaGrupo;
		return $optionValue;
	}


	function carregarOpcoesCampos($campoID, $quantidade){
		if ($campoID!=''){
			$sql = "SELECT Campo_Opcao_ID, Descricao, Posicao FROM formularios_campos_opcoes WHERE Campo_ID = '$campoID' AND Situacao_ID = 1";
			$resultado = mpress_query($sql);
			while($rs = mpress_fetch_array($resultado)){
				$opcoes[$rs['Posicao']] = $rs['Descricao'];
				$campoOpcaoID[$rs['Posicao']] = $rs['Campo_Opcao_ID'];
			}
		}
		for ($i=1; $i<=$quantidade;$i++){
			$html .= "	<div style='float:left; width:25%;'>
							<p><b>Op&ccedil;&atilde;o $i</b></p>
							<p>
								<input type='hidden' id='campo-opcao-$i' name='campo-opcao[$i]' style='width:95%' value='".$campoOpcaoID[$i]."'/>
								<input type='text' id='multipla-opcao-$i' name='multipla-opcao[$i]' style='width:95%' class='required' value='".$opcoes[$i]."'/>
							</p>
						</div>";
		}
		return $html;
	}

	function editarDocumento(){
		global $caminhoSistema;
		$aleatorio = date("YmdHis");
		echo "<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
		$sql = "select distinct d.Documento_ID, a.Nome_Arquivo_Original as Titulo, a.Observacao as Observacao, a.Cabecalho_Rodape
									from modulos_anexos a
									inner join cadastros_documentos d on a.Documento_ID = d.Documento_ID and Tabela_Estrangeira = '".$_GET['origem-documento']."'
									where a.Anexo_ID = ".$_GET['anexo-id'];
		$resultado = mpress_query($sql);
		$row = mpress_fetch_array($resultado);
		$titulo = utf8_encode($row[Titulo]);
		$texto  = $row[Observacao];
		$cabecalho = $row[Cabecalho_Rodape];
?>
		<div class='conteudo-interno'>
			<div class='titulo-secundario uma-coluna'>
				<div class='titulo-secundario' style='width:50%;float:left;'>
					<p><b>Titulo:</b></p>
					<p><input type='text' name='titulo-arquivo' id='titulo-arquivo' Style='width:95%' value='<?php echo $titulo;?>' maxlength='300'/></p>
				</div>
				<div class='titulo-secundario' Style='width:20%;float:left;'>&nbsp;</div>
				<div class='titulo-secundario' Style='width:30%;float:left;'>
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
					<textarea name='detalhes-documento-<?php echo $aleatorio; ?>' id='detalhes-documento-<?php echo $aleatorio; ?>' Style='height:400px'><?php echo $texto;?></textarea>
					<input type='hidden' name='detalhes-documento-aleatorio' id='detalhes-documento-aleatorio' value='<?php echo $aleatorio;?>'/>
				</div>
			</div>
		</div>
		<input type="hidden" id="anexo-id" name="anexo-id" value='<?php echo $_GET['anexo-id'];?>'>
<?php
		tinyMCE('detalhes-documento-'.$aleatorio);
	}

	function geraPDF($titulo, $conteudo, $cabecalhoRodape, $tipoPapel, $orientacao){
		global $caminhoSistema, $caminhoFisico;
		$cabecalho 	= "$caminhoFisico/images/documentos/cabecalho.jpg";
		$rodape 	= "$caminhoFisico/images/documentos/rodape.jpg";
		$c = imagecreatefromjpeg($cabecalho);
		$r = imagecreatefromjpeg($rodape);
		$topCabecalho 	= (imagesy($c)+10)."px";
		$topRodape 		= (imagesy($r)+10)."px";
		//$html = utf8_decode(nl2br($html));
		if($cabecalhoRodape=='C'){
			$back = "background: url(../../images/documentos/cabecalho.jpg) top left no-repeat;";
			$padding = "padding-top:$topCabecalho;font-family: Arial;";
		}
		if($cabecalhoRodape=='R'){
			$back = "background: url(../../images/documentos/rodape.jpg) bottom left no-repeat;";
			$padding = "padding-bottom:$topRodape;font-family: Arial;";
		}
		if($cabecalhoRodape=='CR'){
			$back = "background: url(../../images/documentos/cabecalho-rodape.jpg) top left no-repeat";
			$padding = "padding-top:$topCabecalho;padding-bottom:$topRodape;font-family: Arial;";
		}

		$html = "<style>body{".$padding.$back.";margin:-40px;}</style><div style='width:91.5%;margin:0 auto;'>$conteudo</div>";
		if (file_exists($caminhoFisico."/includes/dompdf/dompdf_config.inc.php")){
			include($caminhoFisico."/includes/dompdf/dompdf_config.inc.php");
			$dompdf = new DOMPDF();
			$dompdf->load_html(stripslashes($html));
			if ($tipoPapel=="")
				$tipoPapel = "a4";
			if ($orientacao=="")
				$orientacao = "portrait";
			$dompdf->set_paper($tipoPapel, $orientacao);
			$dompdf->render();
			$retorno = $dompdf->output();
			file_put_contents("$caminhoFisico/uploads/$titulo", $retorno);
		}
		/*
		else{
			$data['html'] = $html;
			$data['titulo'] = $titulo;
			$data = http_build_query($data);
			$curl = curl_init("http://webservice.grupoinformare.com.br/modulos/administrativo/documentos-gera-arquivo-pdf-webservice.php");
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_HEADER, 0);
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
			$retorno = curl_exec($curl);
			$erro = curl_error($curl);
			curl_close($curl);
			file_put_contents($caminhoFisico."/uploads/$titulo", $retorno);
		}
		*/

	}

	function salvarDocumentoEditado(){
		global $caminhoFisico;
		$anexoID 	 = $_POST['anexo-id'];
		$titulo 	 = utf8_decode($_POST['titulo-arquivo']);
		$aleatorio 	 = $_POST['detalhes-documento-aleatorio'];

		$textoDocumento = utf8_decode(str_replace("'","&#39;",$_POST['detalhes-documento-'.$aleatorio]));
		$cabecalhoRodape = $_POST['chkCabecalho'].$_POST['chkRodape'];

		$resultado = mpress_query("select Nome_Arquivo from modulos_anexos where Anexo_ID = '$anexoID'");
		if ($row = mpress_fetch_array($resultado)){
			unlink($caminhoFisico."/uploads/".$row[Nome_Arquivo]);
		}

		$nomeArquivo = retiraCaracteresEspeciais(date('Ymd_hms')."_".$titulo.".pdf");
		geraPDF($nomeArquivo, $textoDocumento, $cabecalhoRodape);
		mpress_query("update modulos_anexos set Observacao = '$textoDocumento', Nome_Arquivo_Original = '$titulo', Nome_Arquivo = '$nomeArquivo', Cabecalho_Rodape = '$cabecalhoRodape' where Anexo_ID = '$anexoID'");

	}

/********************************* Fim Funções Documentos *********************************/









/********************************* Inicio Funções Formulários *********************************/
	function selecionaDadosFormulario(){
		$rs = mpress_query("select Formulario_ID, Titulo,Modulo_Cadastro_ID from formularios where Formulario_ID = ".$_POST['id-formulario']." and Situacao_ID = 1");
		while($row = mpress_fetch_array($rs)){
			$dados['idForm']  	= $row['Formulario_ID'];
			$dados['titulo']  	= $row['Titulo'];
			$dados['modulo'] 	= $row['Modulo_Cadastro_ID'];
		}
		return $dados;
	}
	function modulosDisponiveisFormularios($dadosFormulario){
		$rs = mpress_query("select Modulo_ID, Nome from modulos where situacao_id = 1 and slug not in('pdv','administrativo','mensagens','help') order by nome");
		while($row = mpress_fetch_array($rs)){
			if($dadosFormulario['modulo']==$row['Modulo_ID']) $selecionado = "selected"; else $selecionado = "";
			echo "	<option value='".$row['Modulo_ID']."' $selecionado>".$row['Nome']."</option>";
		}
	}
	function formulariosCadastrados($dadosFormulario){
		echo "		<option value='-1'>Cadastrar novo Formulário</option>";
		$rs = mpress_query("select Formulario_ID, Titulo from formularios where Situacao_ID = 1 order by Titulo");
		while($row = mpress_fetch_array($rs)){
			if($dadosFormulario['idForm']==$row['Formulario_ID']) $selecionado = "selected"; else $selecionado = "";
			echo "	<option value='".$row['Formulario_ID']."' $selecionado>".$row['Titulo']."</option>";
		}
	}

	function cadastraFormulario(){
		if($_POST['formularios-disponiveis']== -1)
			if($_POST['nome-formulario'] != ""){
				mpress_query("insert into formularios(Titulo)values('".utf8_decode($_POST['nome-formulario'])."')");
				echo mpress_identity();
			}
	}
	function excluiFormulario(){
		mpress_query("update formularios set Situacao_ID = 2 where Formulario_ID = '".$_POST['id-formulario']."'");
	}

	function atualizaFormulario(){
		mpress_query("update formularios set Titulo 			= '".utf8_decode($_POST['nome-formulario-edita'])."',
											 Modulo_Cadastro_ID = '".$_POST['seleciona-modulo-cadastro']."'
					  where Formulario_ID = '".$_POST['id-formulario']."'");
	}
/********************************* Final Funções Formulários *********************************/




function carregarCentroDistribuicao($cdID, $empresaID){
		global $caminhoSistema;
		if ($cdID!=""){
			$sql = "SELECT Empresa_ID, Endereco_ID, Telefone_ID, Contato, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID FROM envios_centros_distribuicao where CD_ID = '$cdID'";
			$resultado = mpress_query($sql);
			if($rs = mpress_fetch_array($resultado)){
				$nome = $rs['Descricao'];
				$enderecoID = $rs['Endereco_ID'];
				$telefoneID = $rs['Telefone_ID'];
				$contato = $rs['Contato'];
				//$botaoExcluir = "<input type='button' value='Excluir' class='cd-excluir' style='width:120px'/>";
			}
		}
		echo "	<input type='hidden' name='empresa-cd' id='empresa-cd' value='$empresaID'>
					<div class='conteudo-interno' id='form-incluir-alterar-cd'>
						<div style='float:left; width:100%'>
							<div class='titulo-secundario' style='float:left; width:15%'>
								<p>Nome</p>
								<p><input type='text' name='cd-nome' id='cd-nome' class='required' value='$nome' style='width:95%'></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:15%'>
								<p>Contato</p>
								<p><input type='text' name='cd-contato' id='cd-contato' class='' value='$contato' style='width:95%'></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:50%'>
								<div style='float:left; width:98%'>
									<p>Endere&ccedil;o</p>
									<p>
										<select name='empresa-endereco-cd' id='empresa-endereco-cd' class='required'>
											<option></option>";
			echo optionValueEnderecos($empresaID, $enderecoID);
			echo "						</select>
									</p>
								</div>
							</div>
							<div class='titulo-secundario' style='float:left; width:20%'>
								<div style='float:left; width:98%'>
									<p>Telefone</p>
									<p>
										<select name='empresa-telefone-cd' id='empresa-telefone-cd' class='required'>
											<option></option>";
		echo optionValueTelefones($empresaID,$telefoneID);
		echo "							</select>
									</p>
								</div>
							</div>
						</div>
						<div style='float:left; width:100%'>
							<div class='titulo-secundario' align='right' style='margin-top:15px; width:100%'>
								<input type='button' value='Cancelar' class='cd-cancelar' style='width:120px'/>
								<input type='button' value='Salvar' class='cd-salvar' style='width:120px'/>
							</div>
						</div>
					</div>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
	}

	function salvarCentroDistribuicao(){
		global $dadosUserLogin;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$cdID = $_POST['cd-id'];
		$empresaID = $_POST['empresa-cd'];
		$enderecoID = $_POST['empresa-endereco-cd'];
		$telefoneID = $_POST['empresa-telefone-cd'];
		$contato = utf8_decode($_POST['cd-contato']);
		$nome = utf8_decode($_POST['cd-nome']);

		if ($cdID==""){
			$sql = "INSERT INTO envios_centros_distribuicao (Empresa_ID, Endereco_ID, Telefone_ID, Contato, Descricao, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
											 VALUES ('$empresaID', '$enderecoID', '$telefoneID', '$contato', '$nome', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		}
		else{
			if ($_GET['acao']=="D"){
				$sqlPlus = ", Situacao_ID = 3";
			}
			$sql = "UPDATE envios_centros_distribuicao set Empresa_ID = '$empresaID', Endereco_ID = '$enderecoID',
												Telefone_ID = '$telefoneID', Contato = '$contato', Descricao = '$nome'
												$sqlPlus
					WHERE CD_ID = '$cdID'";
		}
		mpress_query($sql);
	}

	function carregarCDs($empresaID){
		$sql = "SELECT ecd.CD_ID, cde.Nome AS Empresa, ecd.Endereco_ID, ecd.Telefone_ID, ecd.Contato, ecd.Descricao, ecd.Situacao_ID,
					ecd.Data_Cadastro, ecd.Usuario_Cadastro_ID,
					concat(cdee.Logradouro, ' Nº ', cdee.Numero, ' ',cdee.Complemento,' ', cdee.Bairro,' - ', cdee.Cidade,' - ', cdee.UF,' ', cdee.Referencia, ' - ', cdee.CEP) as Endereco,
					concat(cdef.Telefone, ' ', cdef.Observacao) as Telefone
					FROM envios_centros_distribuicao ecd
					INNER JOIN cadastros_dados cde ON cde.Cadastro_ID = ecd.Empresa_ID
					left join cadastros_enderecos cdee on cdee.Cadastro_Endereco_ID = ecd.Endereco_ID
					left join cadastros_telefones cdef on cdef.Cadastro_Telefone_ID = ecd.Telefone_ID
					where ecd.Situacao_ID = 1
					and ecd.Empresa_ID = '$empresaID'
					ORDER BY ecd.Descricao";
		//echo $sql;
		$resultSet = mpress_query($sql);
		$i = 0;
		while($rs = mpress_fetch_array($resultSet)){
			$i++;
			$cdID = $rs['CD_ID'];
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs[Descricao]."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs[Endereco]."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs[Telefone]."</p>";
			$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs[Contato]."</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>
										 	<div class='btn-editar cd-inc-alt' style='float:right;padding-right:10px' cd-id='$cdID' title='Editar'>&nbsp;</div>
										 	<div class='btn-excluir cd-inc-exc' style='float:right;padding-right:10px' cd-id='$cdID' title='Excluir'>&nbsp;</div>
										 </p>";
		}
		$largura = "100.2%";
		$colunas = "5";
		$dados[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
		$dados[colunas][titulo][2] 	= "Endere&ccedil;o";
		$dados[colunas][titulo][3] 	= "Telefone";
		$dados[colunas][titulo][4] 	= "Contato";
		$dados[colunas][titulo][5] 	= "";
		$dados[colunas][tamanho][5] = "width='70px'";

		if($i==0)
			echo "<p Style='margin:2px 5px 0 5px; color:red; text-align:center'>Nenhum registro localizado</p>";
		else
			geraTabela($largura,$colunas,$dados);
	}



	/* IMPORTAÇÃO DE ARQUIVOS DE CADASTROS / PRODUTOS */


	function optionValueColunasImportacao($tipo,$selecionado){
		global $modulosGeral;
		$sel[$selecionado] = " selected ";
		$opcoes = "<option value=''>Desconsiderar</option>";
		if (($tipo=='modulos_listas_cadastros') || ($tipo=='modulos_listas_contas')){
			//<option ".$sel['Tipo_Pessoa']." 		value='Tipo_Pessoa'>Tipo Pessoa (PF / PJ)</option>
			$opcoes .= "
				<optgroup label='Dados Gerais'>
					<option ".$sel['Nome']." 				value='Nome'>Nome ou Raz&atilde;o Social</option>
					<option ".$sel['Cpf_Cnpj']." 			value='Cpf_Cnpj'>CNPJ ou CPF</option>
					<option ".$sel['Nome_Fantasia']." 		value='Nome_Fantasia'>Nome Fantasia</option>
					<option ".$sel['Codigo']." 				value='Codigo'>C&oacute;digo</option>
					<option ".$sel['Sexo']." 				value='Sexo'>Sexo</option>
					<option ".$sel['Inscricao_Estadual']." 	value='Inscricao_Estadual'>Inscri&ccedil;&atilde;o Estadual</option>
					<option ".$sel['Inscricao_Municipal']." value='Inscricao_Municipal'>Inscri&ccedil;&atilde;o Municipal</option>
					<option ".$sel['RG']." 					value='RG'>RG</option>
					<option ".$sel['Email']." 				value='Email'>Email</option>
					<option ".$sel['Observacao']." 			value='Observacao'>Observa&ccedil;&atilde;o</option>
				</optgroup>
				<optgroup label='Telefones'>
					<option ".$sel['Telefone-28']." 		value='Telefone-28'>Telefone Celular</option>
					<option ".$sel['Telefone-27']." 		value='Telefone-27'>Telefone Comercial</option>
					<option ".$sel['Telefone-29']." 		value='Telefone-29'>Telefone Residencial</option>
				</optgroup>
				<optgroup label='Endere&ccedil;o'>
					<option ".$sel['CEP']." 				value='CEP'>CEP</option>
					<option ".$sel['Logradouro']." 			value='Logradouro'>Logradouro</option>
					<option ".$sel['Numero']." 				value='Numero'>Numero</option>
					<option ".$sel['Bairro']." 				value='Bairro'>Bairro</option>
					<option ".$sel['Cidade']." 				value='Cidade'>Cidade</option>
					<option ".$sel['UF']." 					value='UF'>UF</option>
					<option ".$sel['Referencia']." 			value='Referencia'>Referencia</option>
					<option ".$sel['Complemento']." 		value='Complemento'>Complemento</option>
				</optgroup>";
			if ($tipo=='modulos_listas_contas'){
				$opcoes .= "
				<optgroup label='Cobran&ccedil;a Finaceira'>
					<option ".$sel['Valor_Cobranca']." 		value='Valor_Cobranca'>Valor Cobran&ccedil;a</option>
					<option ".$sel['Data_Vencimento']." 	value='Data_Vencimento'>Data Vencimento</option>
					<option ".$sel['Observacoes_Cobranca']." value='Observacoes_Cobranca'>Complemento</option>
				</optgroup>";
			}
		}
		if ($tipo=='modulos_listas_produtos'){
			$opcoes .= "
					<optgroup label='Dados Produto'>
						<option ".$sel['Descricao_Produto']." 			value='Descricao_Produto'>Descri&ccedil;&atilde;o Produto</option>
						<option ".$sel['Descricao_Produto_Completa']." 	value='Descricao_Produto_Completa'>Descri&ccedil;&atilde;o Completa Produto</option>
						<option ".$sel['Codigo_Produto']." 				value='Codigo_Produto'>C&oacute;digo Produto</option>
						<option ".$sel['Tipo']." 						value='Codigo_Produto'>Tipo (Produto ou Servico)</option>
						<option ".$sel['Categoria']." 					value='Categoria'>Categoria</option>
						<option ".$sel['CNPJ_Fornecedor']." 			value='CNPJ_Fornecedor'>CNPJ Fornecedor</option>
						<option ".$sel['Marca']." 						value='Marca'>Marca</option>
						<option ".$sel['Origem']." 						value='Marca'>Origem</option>
						<option ".$sel['NCM']." 						value='Marca'>NCM</option>
						<option ".$sel['Industrializado']." 			value='Industrializado'>Industrializado</option>
					</optgroup>
					<optgroup label='Dados Varia&ccedil;&atilde;o'>
						<option ".$sel['Descricao_Produto_Variacao']." 	value='Descricao_Produto_Variacao'>Descri&ccedil;&atilde;o Produto Varia&ccedil;&atilde;o</option>
						<option ".$sel['Codigo_Variacao']." 			value='Codigo_Variacao'>C&oacute;digo Varia&ccedil;&atilde;o</option>
						<option ".$sel['Codigo_Barra']." 			value='Codigo_Barra'>Código de Barra</option>
						<option ".$sel['Valor_Custo']." 	value='Valor_Custo'>Valor Custo</option>
						<option ".$sel['Valor_Venda']." 	value='Valor_Venda'>Valor Venda</option>
						<option ".$sel['Altura']." 			value='Altura'>Altura</option>
						<option ".$sel['Largura']." 		value='Largura'>Largura</option>
						<option ".$sel['Peso']." 			value='Peso'>Peso</option>
						<option ".$sel['Comprimento']." 	value='Comprimento'>Comprimento</option>
					</optgroup>";
		}
		return $opcoes;
	}


	function campanhasCarregarArquivoImportacao(){
		$origem = $_POST['origem-documento'];
		global $caminhoSistema, $caminhoFisico, $dadosUserLogin, $modulosGeral;
		$html = "<div class='titulo-container'>
					<div class='titulo'>
						<p>
							Lista
							<input type='button' value='Importar' class='importar-arquivo-sistema' style='width:150px;'>
							<span id='aviso-importacao' style='color:red;float:right'></span>
						</p>
					</div>";

		$html .= "	<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left; width:100%;'>
							<p><b>Nome Lista</b></p>
							<p><input type='text' name='nome-lista' id='nome-lista' value='' class='required' style='width:98%;'/></p>
						</div>";
		if ($origem=='modulos_listas_cadastros'){
			$html .="		<div style='float:left; width:100%;'>
								<div class='titulo-secundario' style='float:left; width:33.33%;'>
									<p><b>Tipo Cadastro</b></p>
									<p><select name='tipo-cadastro[]' multiple id='tipo-cadastro'>".optionValueGrupo('9', '', '&nbsp;', '')."</select></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:33.33%;'>
									<p><b>Vincular ao Cadastro</b></p>
									<p>
										<select name='vinculo-cadastro-id' id='vinculo-cadastro-id'>
											<option value=''></option>
											".optionValueCadastros('','')."
										</select></p>
								</div>
								<div class='titulo-secundario' style='float:left; width:33.33%;'>
									<p><b>Tipo V&iacute;nculo</b></p>
									<p><select name='tipo-vinculo-id' id='tipo-vinculo-id'>".optionValueGrupo('12', '', '&nbsp;', '')."</select></p>
								</div>";
			if ($modulosGeral['tele']){
				$rdCobImport = $_POST['rdCobImport'];
				if ($rdCobImport=='') $rdCobImport = 'n';
				$chkCobImport[$rdCobImport] = "checked";
				$html .= "		<div class='titulo-secundario' style='text-align:center; width:100%;'>
									<p style='margin-top:10px;'><b>Esta &eacute; uma importa&ccedil;&atilde;o para uma lista de cobran&ccedil;a?</b></p>
									<p>
										<input type='radio' id='rdCobImportN' name='rdCobImport' class='rdCobImport' value='n' ".$chkCobImport['n']."/>N&atilde;o
										<input type='radio' id='rdCobImportS' name='rdCobImport' class='rdCobImport' value='s' ".$chkCobImport['s']."/>Sim
									</p>
								</div>";
			}
		}
		if ($origem=='modulos_listas_produtos'){
			//$html .= "AQUI VARIAVEIS PRODUTOS";
		}

		$html .= "		</div>
					</div>
				</div>
				<!--<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>-->";

		/**************************************************/
		/* CRIANDO E INSERINDO DADOS EM TABELA TEMPORARIA */
		/**************************************************/

		$tabelaTemporaria = "tmp_importacao_".str_replace('-','_',$dadosUserLogin['userID']);

		$sqlTmpD = "DROP TABLE IF EXISTS ".$tabelaTemporaria;
		mpress_query($sqlTmpD);

		$anexoID = $_POST['arquivo-importar-base'][0];
		$resultSetAnexo = mpress_query("select Nome_Arquivo from modulos_anexos ma where ma.Anexo_ID = '$anexoID'");
		$rsAnexo = mpress_fetch_array($resultSetAnexo);
		$arquivo = "$caminhoFisico/uploads/".$rsAnexo['Nome_Arquivo'];


		$fp = fopen($arquivo, "r");
		$i=0;
		$dadosArquivo = fgetcsv($fp, 0, ";");
		for($c = 0; $c < count($dadosArquivo); $c++){
			$campo =  "campo".($c+1);
			$camposSql .= "$campo VARCHAR(150) NOT NULL DEFAULT '',";
			$camposUpdate .= $campo.' = trim(replace(replace('.$campo.',"\n"," "),"\r"," ")),';
		}
		fclose($fp);
		if ($c>0){
			$sqlTmp = "CREATE TABLE $tabelaTemporaria
							(".$camposSql."
							ID INT(11) NOT NULL AUTO_INCREMENT,
							Cadastro_ID INT(11) NOT NULL DEFAULT 0,
							Status INT(1) NOT NULL DEFAULT 1,
							Atualizar INT(1) NOT NULL DEFAULT 0,
							PRIMARY KEY (ID))
							COLLATE='utf8_general_ci'
							ENGINE=InnoDB";
			mpress_query($sqlTmp);

			$sqlTmp = "	LOAD DATA LOCAL INFILE '".$arquivo."'
						INTO TABLE ".$tabelaTemporaria."
						CHARACTER SET LATIN1
						FIELDS TERMINATED BY ';'";
			mpress_query($sqlTmp);

			//$sqlTmp = "update $tabelaTemporaria set ".substr($camposUpdate, 0, -1);
			$sqlTmp = "update $tabelaTemporaria set $camposUpdate Status = 1, Atualizar = 0";
			mpress_query($sqlTmp);

			/*************************************************/
			/*  ABRINDO TABELA TEMPORARIA E JOGANDO EM TELA  */
			/*************************************************/
			$html .= " <div class='titulo-secundario' style='float:left; width:100%;'>
							<!--<p><b>Registros localizados:</b></p>-->
							<div id='bloco-tabela-temporaria'>
								".carregarTabelaTemporariaImportada($origem)."
							</div>
						</div>";
		}
		else{
			$html .= "	<div class='titulo-secundario' style='float:left; width:100%;'>
							<p align='center' style='color:red;'><b>Arquivo inv&aacute;lido</b><p>
						</div>";
		}
		//$html .= "		<script> $('#div-exibir-listagem-importacao select').show();</script>";
		$html .= "	<script> $('.campo-coluna').show();</script>";
		$html .= "	<script> $('#tipo-cadastro, #vinculo-cadastro-id, #tipo-vinculo-id').chosen({width: '99%', no_results_text: 'Nenhum tipo localizado!', placeholder_text_single: ' ', placeholder_text_multiple: ' ', allow_single_deselect: true });</script>";

		return $html;
	}


	function carregarTabelaTemporariaImportada($origem){
		global $dadosUserLogin;
		//$tipoImportacao = 'cadastro';
		$tipoImportacao = $origem;
		$tabelaTemporaria = "tmp_importacao_".str_replace('-','_',$dadosUserLogin['userID']);
		foreach($_POST['campo-coluna'] as $posicao => $campo){
			$arrayCampos[$campo] = $posicao;
		}
		if ($_POST['rdCobImport']=='s'){
			$tipoImportacao = 'modulos_listas_contas';
		}

		$reload = false;
		if ($arrayCampos['Cpf_Cnpj']!=''){
			$reload = true;
			$sqlJoin = " left join cadastros_dados cd on cd.Cpf_Cnpj = t.Campo".($arrayCampos['Cpf_Cnpj'])." and cd.Cpf_Cnpj != ''";
			$sqlWhere = " where t.Campo".($arrayCampos['Cpf_Cnpj'])." != '' ";
			$sqlCampoCadastroID = " cd.Cadastro_ID,";
			$sqlCampoCadastroIDesc = " cd.Cadastro_ID desc,";
			$novaColuna = "	<td>
								<p style='font-size:10px; text-align:center; color:red'>Em destaque registros encontrados no cadastro geral, deseja atualizar o registro?</p>
								<p><span class='link m-sim'>SIM</span> <span class='link m-nao' style='float:right;'>N&Atilde;O</span></p>
							</td>";

			$sql = "update ".$tabelaTemporaria." t
					inner join cadastros_dados cd on cd.Cpf_Cnpj = t.Campo".($arrayCampos['Cpf_Cnpj'])."
					SET t.Cadastro_ID = cd.Cadastro_ID
					WHERE cd.Cadastro_ID <> 0 and cd.Cadastro_ID is not null and t.Campo".($arrayCampos['Cpf_Cnpj'])." != ''";

			//echo "<br>".$sql;
			mpress_query($sql);


			/* TRATANDO REGISTROS COM cpf_cnpj VAZIOS */
			$sql = "update ".$tabelaTemporaria." t set Status = 0 where t.Campo".($arrayCampos['Cpf_Cnpj'])." = ''";
			mpress_query($sql);

		}
		$tabela = "		<table class='tabela-importacao' cellspacing='0'>";
		$i = 0;
		$sql = "select $sqlCampoCadastroID t.*
						from ".$tabelaTemporaria." t
						$sqlJoin
						$sqlWhere
						order by $sqlCampoCadastroIDesc t.ID";
		//echo "<br>".$sql;
		//exit();
		$resultSet = mpress_query($sql);

		$colunas = mpress_num_fields($resultSet);
		$tabela .= "	<td align='center' style='min-width:150px;'><b>Incluir na Lista?</b></td>$novaColuna";
		for ($c=1;$c<$colunas;$c++){
			$tabela .= "	<td><select name='campo-coluna[$c]' class='campo-coluna' style='min-width:150px;'>".optionValueColunasImportacao($tipoImportacao,$_POST['campo-coluna'][$c])."</select></td>";
		}
		while($rs = mpress_fetch_array($resultSet)){
			$i = $rs['ID'];
			if (($i % 2)==0) $classe = "imp-a"; else $classe = "imp-b";
			$colunaAtualiza = "";
			if ($reload){
				$colunaAtualiza = "	<td></td>";
				if ($rs['Cadastro_ID']!=0) {
					$classe = 'imp-c';
					$colunaAtualiza = "	<td nowrap><input type='radio' id='at-$i' name='at[$i]' class='at-s' value='".$rs['Cadastro_ID']."'/> SIM &nbsp;<input type='radio' id='at-$i' name='at[$i]' class='at-n' checked value=''/> N&Atilde;O</td>";
				}
				$checkedLinha = "";
				if ($rs['Status']=='1')
					$checkedLinha = "checked";
				/*
				if ($_POST['linha'][$i]!=''){
					$checkedLinha = "checked";
				}
				else
					$checkedLinha = "";
				*/
			}
			else{
				$checkedLinha = "checked";
			}

			$tabela .= "	<tr class='$classe'>
								<td align='center'><input type='checkbox' name='linha[$i]' $checkedLinha class='incluir-linha' value='$i'></td>
								".$colunaAtualiza;

			for ($c=1;$c<$colunas;$c++)
				$tabela .= "	<td nowrap>".$rs['campo'.$c]."</td>";
			$tabela .= "	</tr>";
		}
		$tabela .= "	</table>";
		$html .= $tabela;

		//$html .= "	<script> $('#div-exibir-listagem-importacao select').show();</script>";
		$html .= "	<script> $('.campo-coluna').show();</script>";
		$html .= "	<script> $('#tipo-cadastro, #vinculo-cadastro-id, #tipo-vinculo-id').chosen({width: '99%', no_results_text: 'Nenhum tipo localizado!', placeholder_text_single: ' ', placeholder_text_multiple: ' ', allow_single_deselect: true });</script>";
		return $html;
	}



	function campanhasImportarArquivo(){
		global $dadosUserLogin, $caminhoFisico;
		$dataHoraAtual = "'".retornaDataHora('','Y-m-d H:i:s')."'";
		$nomeLista = utf8_decode($_POST['nome-lista']);
		$origem = $_POST['origem-documento'];

		$anexoID = $_POST['arquivo-importar-base'][0];
		$resultSetAnexo = mpress_query("select Nome_Arquivo from modulos_anexos ma where ma.Anexo_ID = '$anexoID'");
		$rsAnexo = mpress_fetch_array($resultSetAnexo);
		$arquivo = "$caminhoFisico/uploads/".$rsAnexo['Nome_Arquivo'];

		$sql = "INSERT INTO modulos_listas (Descricao, Slug, Anexo_ID, Data_Cadastro, Usuario_Cadastro_ID)
								VALUES ('$nomeLista', '$origem', '$anexoID', $dataHoraAtual, '".$dadosUserLogin['userID']."')";
		echo "\n".$sql;
		mpress_query($sql);
		$listaID = mpress_identity();
		$tabelaTemporaria = "tmp_importacao_".str_replace('-','_',$dadosUserLogin['userID']);

		if ($origem=="modulos_listas_cadastros"){

			$flagEndereco = 0;
			foreach($_POST['campo-coluna'] as $posicao => $campo){
				if ($campo!=''){
					$arrayCampos[$campo] = $posicao;
					if (($campo=='Nome') ||
						($campo=='Cpf_Cnpj') ||
						($campo=='Nome_Fantasia') ||
						($campo=='Codigo') ||
						($campo=='Tipo_Pessoa') ||
						($campo=='Inscricao_Estadual') ||
						($campo=='Inscricao_Municipal') ||
						($campo=='RG') ||
						($campo=='Email') ||
						($campo=='Observacao')){
						$camposInsertCadastroDados .= $campo.",";
						$camposInsertCadastroDadosImportados .= " campo".$posicao.",";
					}
					if (($campo=='CEP') ||
						($campo=='Logradouro') ||
						($campo=='Numero') ||
						($campo=='Bairro') ||
						($campo=='Cidade') ||
						($campo=='UF') ||
						($campo=='Cidade') ||
						($campo=='Referencia') ||
						($campo=='Complemento')){

						$flagEndereco = 1;
						$camposInsertCadastroEndereco .= $campo.",";
						$camposInsertCadastroEnderecoImportados .= " campo".$posicao.",";
					}
					if ($campo=='Valor_Cobranca'){
						//$flagCobranca = 1;
						//$camposInsertCadastroCobranca .= $campo.",";
						//$camposInsertCadastroCobrancaImportados .= " campo".$posicao.",";
						/*
						<option ".$sel['Valor_Cobranca']." 		value='Valor_Cobranca'>Valor Cobran&ccedil;a</option>
						<option ".$sel['Data_Vencimento']." 	value='Data_Vencimento'>Data Vencimento</option>
						<option ".$sel['Observacoes_Cobranca']." value='Observacoes_Cobranca'>Complemento</option>
						*/
					}

				}
			}

			$sql = "INSERT INTO cadastros_dados (".$camposInsertCadastroDados." Situacao_ID)
						SELECT ".$camposInsertCadastroDadosImportados." 1 from ".$tabelaTemporaria."
						where Status = 1 and Cadastro_ID = 0";
						/*ID in (".substr($ids, 0, -1).") */
			//echo "\n".$sql;
			mpress_query($sql);

			/* PENDENTE */
			/* TERMINAR TRECHO ABAIXO AGORA TA FACIL APENAS ATUALIZAR OS CAMPOS QUE FORAM  SELECIONADOS PARA REALIZAR ATUALIZAÇÃO EM REGISTROS NA IMPORTAÇÃO */
			/*
			foreach($_POST['at'] as $cadastroUpdateID){
				if ($cadastroUpdateID!=''){
					$cadastrosUpdate .= $cadastroUpdateID.",";
				}
			}

			if ($cadastrosUpdate!=''){
				$cadastrosUpdate = substr($cadastrosUpdate,0,-1);
				$sql = "UPDATE cadastros_dados cd
							set Situacao_ID = 1,
							Nome = '123456789'
							where Cadastro_ID IN ($cadastrosUpdate)";
				mpress_query($sql);
			}
			*/

			/****************************/
			/* ARRUMANDO TIPO DE PESSOA */
			/****************************/

			$tipoCadastro = serialize($_POST['tipo-cadastro']);
			$cadastroVinculoID = $_POST['vinculo-cadastro-id'];
			$tipoVinculoID = $_POST['tipo-vinculo-id'];

			$sql = "UPDATE cadastros_dados set Tipo_Pessoa = IF(length(Cpf_Cnpj)>=14, 25, 24), Tipo_Cadastro = '$tipoCadastro' where Tipo_Pessoa = 0 or Tipo_Pessoa is null";
			//echo "\n".$sql;
			mpress_query($sql);

			/******************************************************************/
			/****  TRECHO ABAIXO REALIZA RELACIONAMENTO ENTRE OS CADASTROS  ***/
			/******************************************************************/
			if ($cadastroVinculoID!=''){
				$resultSetV = mpress_query("select Tipo_Vinculo from cadastros_dados where Cadastro_ID = '$cadastroVinculoID'");
				if($rsV = mpress_fetch_array($resultSetV)){
					if ($rsV['Tipo_Vinculo']!=''){
						$tipoV = unserialize($rsV['Tipo_Vinculo']);
						$tipoV[] = $tipoVinculoID;
						$resultSetV2 = mpress_query("update cadastros_dados set Tipo_Vinculo = '".serialize($tipoV)."' where Cadastro_ID = '$cadastroVinculoID'");
					}
				}
				$sql = "INSERT INTO cadastros_vinculos
								(Tipo_Vinculo_ID, Cadastro_ID, Cadastro_Filho_ID, Situacao_ID)
						SELECT '".$tipoVinculoID."','".$cadastroVinculoID."', cd.Cadastro_ID ,1
							FROM cadastros_dados cd
							INNER JOIN $tabelaTemporaria t on cd.Cpf_Cnpj = t.Campo".$arrayCampos['Cpf_Cnpj']."
							WHERE trim(cd.Cpf_Cnpj) <> '' and trim(t.Campo".$arrayCampos['Cpf_Cnpj'].") <> '' and t.Status = 1";
				//echo "\n".$sql;
				mpress_query($sql);
			}
			/************************************************************/

			$sql = "INSERT INTO modulos_listas_cadastros (Lista_ID, Cadastro_ID, Data_Cadastro, Usuario_Cadastro_ID)
												SELECT $listaID, cd.Cadastro_ID, $dataHoraAtual, '".$dadosUserLogin['userID']."'
													from cadastros_dados cd
													inner join $tabelaTemporaria t on cd.Cpf_Cnpj = t.Campo".$arrayCampos['Cpf_Cnpj']."
													WHERE trim(cd.Cpf_Cnpj) <> '' and trim(t.Campo".$arrayCampos['Cpf_Cnpj'].") <> '' and t.Status = 1";
			echo "\n".$sql;
			mpress_query($sql);

			foreach($_POST['campo-coluna'] as $posicao => $campo){
				if (($campo=='Telefone-27') || ($campo=='Telefone-28') || ($campo=='Telefone-29')){
					$campoFone = explode("-", $campo);
					$tipoFone = $campoFone[1];
					$sql = "INSERT INTO cadastros_telefones (Cadastro_ID, Telefone, Tipo_Telefone_ID, Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
													SELECT cd.Cadastro_ID, campo".$posicao.", '$tipoFone', 1, $dataHoraAtual, '".$dadosUserLogin['userID']."'
														FROM cadastros_dados cd
														INNER JOIN $tabelaTemporaria t on cd.Cpf_Cnpj = t.Campo".$arrayCampos['Cpf_Cnpj']."
														WHERE trim(cd.Cpf_Cnpj) <> '' and trim(t.Campo".$arrayCampos['Cpf_Cnpj'].") <> '' and t.Status = 1
														and trim(campo".$posicao.") <> ''";
					//echo "\n".$sql;
					mpress_query($sql);
				}
			}
			if ($flagEndereco==1){
				$sql = "	INSERT INTO cadastros_enderecos
								(Cadastro_ID, Tipo_Endereco_ID, $camposInsertCadastroEndereco Situacao_ID, Data_Cadastro, Usuario_Cadastro_ID)
							SELECT cd.Cadastro_ID, 26, $camposInsertCadastroEnderecoImportados 1, $dataHoraAtual, '".$dadosUserLogin['userID']."'
								FROM cadastros_dados cd
								INNER JOIN $tabelaTemporaria t on cd.Cpf_Cnpj = t.Campo".$arrayCampos['Cpf_Cnpj']."
							WHERE trim(cd.Cpf_Cnpj) <> '' and trim(t.Campo".$arrayCampos['Cpf_Cnpj'].") <> '' and t.Status = 1";
				//echo "\n".$sql;
				mpress_query($sql);
			}
		}
		if ($origem=="modulos_listas_produtos"){
//			foreach($_POST['campo-coluna'] as $posicao => $campo){
//			}
			echo "Aguarde processo em execução!";
		}
	}

	function documentosImportacaoAtualizarLinha(){
		global $dadosUserLogin;
		$id = $_GET['linha'];
		$statusLinha = $_GET['statusLinha'];
		$tabelaTemporaria = "tmp_importacao_".str_replace('-','_',$dadosUserLogin['userID']);
		$sql = "UPDATE $tabelaTemporaria SET Status = '$statusLinha' where ID = '$id'";
		mpress_query($sql);
		echo $sql;
	}
?>