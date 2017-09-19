<?php
	error_reporting(E_ERROR);
	session_start();

	function salvarPergunta(){
		global $dadosUserLogin;
		$perguntaID = $_POST['pergunta-id'];
		$descricao = utf8_decode($_POST['pergunta-descricao']);
		$ordemPergunta = $_POST['pergunta-ordem'];
		if ($perguntaID==""){
			$sql = "Insert Into faq_perguntas (Descricao, Situacao_ID, Ordem, Usuario_Cadastro_ID)
										 Values ('$descricao', '1', '$ordemPergunta', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$perguntaID = mysql_insert_id();
		}
		else{
			$sql = "Update faq_perguntas set Descricao = '$descricao', Ordem = '$ordemPergunta' where Pergunta_ID = '$perguntaID'";
			mpress_query($sql);
		}
		echo $perguntaID;
	}

	function excluirPergunta(){
		global $dadosUserLogin;
		$perguntaID = $_GET['pergunta-id'];
		$sql = "update faq_perguntas Set Situacao_ID = 2 where Pergunta_ID = $perguntaID";
		mpress_query($sql);
	}

	function atualizaOrdemPergunta(){
		$perguntaID = $_GET['pergunta-id'];
		$ordem = $_GET['ordem-pergunta'];
		$sql = "Update faq_perguntas set Ordem = '$ordem' where Pergunta_ID = '$perguntaID'";
		mpress_query($sql);
		echo $sql;
	}


	function salvarResposta(){
		global $dadosUserLogin;
		$perguntaID = $_POST['pergunta-id'];
		$respostaID = $_POST['resposta-id'];
		$descricao = utf8_decode($_POST['resposta-descricao']);
		$ordemResposta = $_POST['resposta-ordem'];

		if ($respostaID==""){
			$sql = "Insert Into faq_respostas (Pergunta_ID, Descricao, Situacao_ID, Ordem, Usuario_Cadastro_ID)
										 Values ('$perguntaID', '$descricao', 1, '$ordemResposta', '".$dadosUserLogin['userID']."')";
			mpress_query($sql);
			$perguntaID = mysql_insert_id();
		}
		else{
			$sql = "Update faq_respostas set Descricao = '$descricao', Ordem = '$ordemResposta'  where Resposta_ID = '$respostaID'";
			mpress_query($sql);
		}
	}

	function excluirResposta(){
		global $dadosUserLogin;
		$respostaID = $_GET['resposta-id'];
		$sql = "update faq_respostas set Situacao_ID = 2 where Resposta_ID = $respostaID";
		mpress_query($sql);
	}

	function atualizaOrdemResposta(){
		$respostaID = $_GET['resposta-id'];
		$ordem = $_GET['ordem-resposta'];
		$sql = "Update faq_respostas set Ordem = '$ordem' where Resposta_ID = '$respostaID'";
		mpress_query($sql);
		//echo $sql;
	}

/********************************* Inicio Funções Respostas *********************************/

	function carregarRespostas(){
		$perguntaID  = $_GET['pergunta-id'];
		$sql = "select Resposta_ID, Descricao, Ordem from faq_respostas where Situacao_ID = 1 and Pergunta_ID = $perguntaID order by Ordem";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".nl2br(utf8_encode($row[Descricao]))."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'><input type='text' class='resposta-ordem formata-numero' value='".$row[Ordem]."' resposta-id='".$row[Resposta_ID]."' style='width:20px'/></p>";
			$dados[colunas][conteudo][$i][3] = "<div class='btn-excluir btn-excluir-resposta' style='float:right; padding-right:5px' resposta-id='".$row[Resposta_ID]."' title='Excluir'>&nbsp;</div>";
			$dados[colunas][conteudo][$i][4] = "<div class='btn-editar btn-editar-resposta' style='float:right;padding-right:5px' resposta-id='".$row[Resposta_ID]."' title='Editar'>&nbsp;</div>";
		}
		$largura = "100%";
		$colunas = "4";
		$dados[colunas][titulo][1] 	= "<p Style='margin:2px 5px 0 5px;float:left;'>Respostas Cadastrados</p>";
		$dados[colunas][tamanho][1] = "";
		$dados[colunas][titulo][2] 	= "<p Style='margin:2px 5px 0 5px;float:left;'>Ordem</p>";
		$dados[colunas][tamanho][2] = "width='40px'";
		$dados[colunas][titulo][3] 	= "";
		$dados[colunas][tamanho][3] = "width='25px'";
		$dados[colunas][titulo][4] 	= "<p class='link btn-novo' style='margin:2px 5px 0 5px;cursor:pointer;float:right' id='botao-nova-resposta'>(+)</p>";
		$dados[colunas][tamanho][4] = "width='25px'";

		geraTabela($largura,$colunas,$dados);
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px; text-align:center'>Nenhuma resposta cadastrada</p>";
		}
	}

	function carregarResposta(){
		$respostaID = $_GET['resposta-id'];
		$perguntaID = $_GET['pergunta-id'];

		if ($respostaID!=""){
			$sql = "select Descricao, Ordem from faq_respostas where Resposta_ID = $respostaID";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$descricaoResposta = $row[Descricao];
				$ordemResposta = $row[Ordem];
			}
		}
		else{
			$sql = "select max(Ordem) as Ordem from faq_respostas where Pergunta_ID = $perguntaID and Situacao_ID = 1";
			$resultado = mpress_query($sql);
			if($row = mpress_fetch_array($resultado)){
				$ordemResposta = $row[Ordem] + 1;
			}
		}
	?>
		<div class='conteudo-interno'>
			<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>
				<div class="titulo-secundario" style='float:left; width:70%'>
					<input type="hidden" id="resposta-id" name="resposta-id" value="<?php echo $respostaID; ?>">
					<p>Descri&ccedil;&atilde;o Resposta</p>
					<p><textarea id="pergunta-descricao" name="resposta-descricao" class='resposta-descricao' style='width:99%;height:120px'><?php echo utf8_encode($descricaoResposta); ?></textarea></p>
				</div>
				<div class="titulo-secundario" Style='float:left;width:40px' align='center'>
					<p>Ordem</p>
					<p><input type='text' id="resposta-ordem" name="resposta-ordem" class='resposta-ordem' class='formata-numero' value='<?php echo $ordemResposta; ?>' style='width:20px'/></p>
				</div>
				<div class='titulo-secundario' Style='margin-top:15px; float:left;width:250px' align='right'>
					<input type='button' value='Salvar' class='botao-salvar-resposta' Style='width:120px;'/>
					<input type='button' value='Cancelar'  class='botao-cancelar-resposta' Style='width:120px'/>
				</div>
			</div>
		</div>
	<?php
	}

	/********************************* Fim Funções Respostas *********************************/

?>