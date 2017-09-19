<?php
global $modulosAtivos;
$workflowID = $_POST['localiza-workflow-id'];
if ($workflowID != ""){
	$sql = "select Cadastro_ID_de, Cadastro_ID_para, Transportadora_ID, Tabela_Estrangeira, Chave_Estrangeira, Forma_Envio_ID, Codigo_Rastreamento,
				DATE_FORMAT(Data_Envio, '%d/%m/%Y') as Data_Envio, DATE_FORMAT(Data_Envio, '%H:%i') as Hora_Envio,
				DATE_FORMAT(Data_Previsao, '%d/%m/%Y') as Data_Previsao, DATE_FORMAT(Data_Previsao, '%H:%i') as Hora_Previsao,
				DATE_FORMAT(Data_Entrega, '%d/%m/%Y') as Data_Entrega, DATE_FORMAT(Data_Entrega, '%H:%i') as Hora_Entrega,
				Usuario_Cadastro_ID
				from envios_workflows where Workflow_ID = $workflowID";
	//echo $sql;

	$resultado = mpress_query($sql);
	if($row = mpress_fetch_array($resultado)){
		$cadastroIDde = $row[Cadastro_ID_de];
		$cadastroIDpara = $row[Cadastro_ID_para];
		$transportadoraID = $row[Transportadora_ID];
		$tabelaEstrangeira = $row[Tabela_Estrangeira];
		$chaveEstrangeira = $row[Chave_Estrangeira];

		if ($tabelaEstrangeira=="chamados_workflows"){
			$resultado2 = mpress_query("select Codigo from chamados_workflows where Workflow_ID = $chaveEstrangeira");
			if($row2 = mpress_fetch_array($resultado2)){
				$codigoEstrangeira = $row2[Codigo];
			}
		}
		$codigoRastreamento = $row[Codigo_Rastreamento];
		$formaEnvioID = $row[Forma_Envio_ID];
		$dataEnvio = $row[Data_Envio]; if ($dataEnvio == "00/00/0000") $dataEnvio = "";
		$horaEnvio = $row[Hora_Envio]; if ($horaEnvio == "00:00") $horaEnvio = "";
		$dataPrevisaoEntrega = $row[Data_Previsao]; if ($dataPrevisaoEntrega == "00/00/0000") $dataEnvio = "";
		$horaPrevisaoEntrega = $row[Hora_Previsao]; if ($horaPrevisaoEntrega == "00:00") $horaPrevisaoEntrega = "";
		$dataEntrega = $row[Data_Entrega]; if ($dataEntrega == "00/00/0000") $dataEnvio = "";
		$horaEntrega = $row[Hora_Entrega]; if ($horaEntrega == "00:00") $horaEntrega = "";


		$sql = "Select Follow_ID, Descricao, t.Descr_Tipo as Situacao, ef.Situacao_ID as Situacao_ID,
				DATE_FORMAT(Data_Cadastro, '%d/%m/%Y %H:%i') as Data_Cadastro, cd.Nome as Usuario_Follow
				from envios_follows ef
				left join tipo t on t.Tipo_ID = ef.Situacao_ID
				inner join cadastros_dados cd on cd.Cadastro_ID = ef.Usuario_Cadastro_ID
				where Workflow_ID = $workflowID
				order by ef.Follow_ID desc ";
		$query = mpress_query($sql);
		$i=0;
		while($rs = mpress_fetch_array($query)){
			$i++;
			if ($i==1){
				$situacaoAtualID = $rs['Situacao_ID'];
				$situacaoAtual = $rs['Situacao'];
			}
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Descricao']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Situacao']."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Data_Cadastro']."</p><p Style='margin:2px 5px 0 5px;float:left;'>".$rs['Usuario_Follow']."</p>";
		}
		if($i>=1){
			$largura = "99%";
			$colunas = "3";
			$dados[colunas][titulo][1] 	= "Descri&ccedil;&atilde;o";
			$dados[colunas][tamanho][1] = "width=''";
			$dados[colunas][titulo][2] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][tamanho][2] = "width='180px'";
			$dados[colunas][titulo][3] 	= "Data";
			$dados[colunas][tamanho][3] = "width='180px'";
		}


	}
}
else{
	$classeEsconde = "esconde";
	$dataEnvio = retornaDataHora('d','d/m/Y');
	$horaEnvio = retornaDataHora('h','H:i');

}
?>
<div id="container-geral">

	<input type="hidden" id="localiza-workflow-id" name="localiza-workflow-id" value="<?php echo $workflowID;?>"/>
	<input type="hidden" id="workflow-id" name="workflow-id" value="<?php echo $workflowID;?>"/>

	<input type="hidden" id="texto-geral" name="texto-geral" value=""/>
	<input type="hidden" id="tipo-cadastro" name="tipo-cadastro" value=""/>

	<input type="hidden" id="cadastro-id-de" name="cadastro-id-de" value="<?php echo $cadastroIDde;?>"/>
	<input type="hidden" id="cadastro-id-para" name="cadastro-id-para" value="<?php echo $cadastroIDpara;?>"/>
	<input type="hidden" id="cadastro-id-trans" name="cadastro-id-trans" value="<?php echo $transportadoraID;?>"/>
	<input type='hidden' id='situacao-atual-chamado' name='situacao-atual-chamado' value='<?php echo $situacaoAtualID;?>'/>

	<div id='div-retorno'></div>

	<div class="titulo-container">
		<div class="titulo" Style='min-height:25px'>
			<p Style='margin-top:2px;'>Envio <?php if ($workflowID!=""){ echo "N&deg;".$workflowID;}?> Dados Gerais</p>
		</div>
		<div class='conteudo-interno'>
			<!-- DE -->

			<div class='titulo-secundario' style='float:left;width:100%;margin-top:10px'>
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Envio de:&nbsp;&nbsp;</b><i></i></span>
					<span style='float:left;'><i>Cadastro que est&aacute; realizando o envio. &nbsp;&nbsp;&nbsp; <span id='texto-de'>Localizar Cadastro por C&oacute;digo, Nome, CPF, CNPJ </span></i></span>
				</p>
				<p id='campo-envio-cadastro-localiza-de' style='width:100%;'>
					<input type='text' class='envio-cadastro-localiza' id='envio-cadastro-localiza-de' tipo-cadastro='de' style='width:98%;' value=''/>
				</p>
			</div>
			<div id='div-localiza-cadastro-envio-de' class='esconde' style='float:left;width:100%;height:150px;overflow-x: hidden'></div>
			<div id='envio-cadastro-de' class='esconde'></div>

			<!-- PARA -->

			<div class='titulo-secundario' style='float:left;width:100%;margin-top:10px'>
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Envio para:&nbsp;&nbsp;</b><i></i></span>
					<span style='float:left;'><i>Cadastro que realizar&aacute; o recebimento do envio. &nbsp;&nbsp;&nbsp; <span id='texto-para'>Localizar Cadastro por C&oacute;digo, Nome, CPF, CNPJ</span></i></span>
				</p>
				<p id='campo-envio-cadastro-localiza-para' style='width:100%;'>
					<input type='text' class='envio-cadastro-localiza' id='envio-cadastro-localiza-para' tipo-cadastro='para' style='width:98%;' value=''/>
				</p>
			</div>
			<div id='div-localiza-cadastro-envio-para' class='esconde' style='float:left;width:100%;height:150px;overflow-x: hidden'></div>
			<div id='envio-cadastro-para' class='esconde'></div>

			<!-- TRANSP -->

			<div class='titulo-secundario' style='float:left;width:100%;margin-top:10px'>
				<p id='texto-cadastro'>
					<span style='float:left;'><b>Transportador:&nbsp;&nbsp;</b><i></i></span>
					<span style='float:left;'><i><span id='texto-trans'>Localizar Cadastro por C&oacute;digo, Nome, CPF, CNPJ</span></i></span>
				</p>
				<p id='campo-envio-cadastro-localiza-trans' style='width:100%;'>
					<input type='text' class='envio-cadastro-localiza' id='envio-cadastro-localiza-trans' tipo-cadastro='trans' style='width:98%;' value=''/>
				</p>
			</div>
			<div id='div-localiza-cadastro-envio-trans' class='esconde' style='float:left;width:100%;height:150px;overflow-x: hidden'></div>
			<div id='envio-cadastro-trans' class='esconde'></div>
		</div>
	</div>

	<div class="titulo-container">
		<div class="titulo" Style='min-height:25px'>
			<p Style='margin-top:2px;'>Origem de Envio</p>
		</div>
		<div class='conteudo-interno titulo-secundario'>
			<div class="titulo-secundario" style="float:left;width:30%;">
				<p></p>
				<p>
					<input type="radio" class="radio-origem" name="radio-origem" id="radio-origem-0" value="0" <?php if ($tabelaEstrangeira==""){ echo "checked";} ?>/>
					<label for="radio-origem-0"> Sem Origem </label>&nbsp;
				<?php if ($modulosAtivos[chamados]){ ?>
					<input type="radio" class="radio-origem" name="radio-origem" id="radio-origem-1" value="1" <?php if ($tabelaEstrangeira=="chamados_workflows"){ echo "checked";} ?>/>
					<label for="radio-origem-1"> Chamado </label>&nbsp;
				<?php } ?>
				</p>
			</div>
			<?php if ($modulosAtivos[chamados]){ ?>
			<div class='titulo-secundario esconde origem-envio origem-envio-1' style='width:30%;float:left;'>
				<p><b>C&oacute;digo Chamado</b></p>
				<p><input type='text' name='envio-codigo-chamado' id='envio-codigo-chamado' class='formata-campo' style='width:50%' value='<?php echo $codigoEstrangeira; ?>'></p>
				&nbsp;
			</div>
			<?php } ?>
			<div class="titulo-secundario" style="float:left;width:40%;">&nbsp;</div>
			<div name='div-chamado' id='div-chamado' class='div-origem-envio' style="float:left;width:100%;"></div>
		</div>
	</div>

	<div class='titulo-container' id='div-produtos-dados'>
		<div class="titulo" Style='min-height:25px'>
			<p Style='margin-top:2px;'>Materiais Enviados</p>
		</div>
		<div class='conteudo-interno titulo-secundario' id='conteudo-interno-produtos'>
			<div id='div-produtos-incluir-editar' Style='float:left;width:100%;'></div>
			<div id='div-produtos' class="titulo-secundario uma-coluna" style='margin-top:5px;'></div>
		</div>
	</div>

	<div id='div-financeiro'></div>

	<div class="titulo-container">
		<div class="titulo" Style='min-height:25px'>
			<p Style='margin-top:2px;'>Dados Envio <?php if ($workflowID!=""){ echo "N&ordm; $workflowID - Situa&ccedil;&atilde;o Atual: $situacaoAtual";}?></p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario' style='width:23%;float:left;'>
				<p><b>C&oacute;digo Rastreamento</b></p>
				<p><input type='text' name='codigo-rastreamento' id='codigo-rastreamento' class='formata-campo' style='width:95%' value='<?php echo $codigoRastreamento; ?>'></p>
			</div>

			<div class='titulo-secundario' style='width:23%;float:left;'>
				<p><b>Forma de Envio</b></p>
				<p><select name="forma-envio" id="forma-envio"><?php echo optionValueGrupo(31, $formaEnvioID, "Selecione");?><select></p>
			</div>

			<div class='titulo-secundario' style='width:12%;float:left;'>
				<p><b>Data Envio</b></p>
				<p><input type="text" id="data-envio" name="data-envio" class='formata-data' value='<?php echo $dataEnvio; ?>' style='width:89%' maxlength='10'/></p>
			</div>
			<div class='titulo-secundario' style='width:6%;float:left;'>
				<p><b>Hora</b></p>
				<p><input type="text" id="hora-envio" name="hora-envio" class='formata-hora' value='<?php echo $horaEnvio; ?>' style='width:83%' maxlength='5'/></p>
			</div>

			<div class='titulo-secundario' style='width:12%;float:left;'>
				<p><b>Previsão entrega</b></p>
				<p><input type="text" id="data-previsao-entrega" name="data-previsao-entrega" class='formata-data' value='<?php echo $dataPrevisaoEntrega; ?>' style='width:89%' maxlength='10'/></p>
			</div>
			<div class='titulo-secundario' style='width:6%;float:left;'>
				<p><b>Hora</b></p>
				<p><input type="text" id="hora-previsao-entrega" name="hora-previsao-entrega" class='formata-hora' value='<?php echo $horaPrevisaoEntrega; ?>' style='width:83%' maxlength='5'/></p>
			</div>

			<div class='titulo-secundario div-data-entrega' style='width:12%;float:left;'>
				<p><b>Data Entrega</b></p>
				<p><input type="text" id="data-entrega" name="data-entrega" class='formata-data' value='<?php echo $dataEntrega; ?>' style='width:89%' maxlength='10'/></p>
			</div>
			<div class='titulo-secundario div-data-entrega' style='width:6%;float:left;'>
				<p><b>Hora</b></p>
				<p><input type="text" id="hora-entrega" name="hora-entrega" class='formata-hora' value='<?php echo $horaEntrega; ?>' style='width:83%' maxlength='5'/></p>
			</div>
			<?php if ($situacaoID=="53"){ $descricaoBotao = "Re-Abrir Envio";}else{$descricaoBotao = "Salvar";}?>
			<div class='titulo-secundario' style='width:100%;'>
				<p><b>Descri&ccedil;&atilde;o</b></p>
				<p class='omega'><textarea id='descricao-follow' name='descricao-follow' style='height:60px'><?php echo $descricaoCompleta; ?></textarea></p>
			</div>
			<div class="titulo-secundario" style='width:85%;float:left;'>
				<p><b>Situa&ccedil;&atilde;o Envio:</b></p>
				<select name="select-situacao-follow" id="select-situacao-follow"><?php echo optionValueGrupo(30, $situacao, "Selecione");?><select>
			</div>
			<div id='div-localizar-solicitante-cancelar' style='width:15%;float:left;'>
				<p><input type='button' value='<?php echo $descricaoBotao;?>' id='botao-cadastra-envio'  Style='width:95%;'/></p>
			</div>
		</div>
	</div>


	<div class="titulo-container <?php echo $classeEsconde; ?>" id='div-historico-dados'>
		<div class="titulo">
			<div class='btn-retrair btn-expandir-retrair-historico' style='float:right;' title='Expandir'></div>
			<p>Hist&oacute;rico do envio</p>
		</div>
		<div class='titulo-secundario uma-coluna' Style='margin-top:5px;' id='conteudo-interno-historico'>
			<?php geraTabela($largura,$colunas,$dados); ?>
		</div>
	</div>


</div>
