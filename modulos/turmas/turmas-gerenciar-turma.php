<?php
include("functions.php");
$turmaID = $_POST['id-turma'];
if ($turmaID==""){
	$botoes = "<input type='button' value='Cadastrar Turma' id='botao-cadastrar-turma' class='botao-cadastrar-turma'  Style='margin-top:-5px;'>";
}
else{
	$detalhesTurma = localizaDetalhesTurma($turmaID);
	$botoes = "<input type='button' value='Atualizar Turma' id='botao-atualizar-turma' class='botao-atualizar-turma'  Style='margin-top:-5px;'>
			<input type='button' value='Excluir Turma' id='botao-exclui-turma' class='botao-exclui-turma' Style='background-color:#E58989; margin-top:-5px;'>";
}
//if ($detalhesTurma[Codigo]==""){
//	$sql = "select year(Data_Evento) as ano, month(Data_Evento) as mes from orcamentos_workflows ow
//				inner join orcamentos_propostas op on op.Workflow_ID = ow.Workflow_ID
//				inner join orcamentos_propostas_eventos ope on ope.Proposta_ID = op.Proposta_ID
//				where op.Status_ID = 141
//				and Solicitante_ID = '".$detalhesTurma[Cadastro_ID]."'
//				order by Data_Evento desc limit 1";
//	$resultado = mpress_query($sql);
//	if($rs = mpress_fetch_array($resultado)){
		//echo $sql;
		$botaoGerarCodigo = "<input type='button' class='gerar-codigo-turma' value='Gerar C&oacute;digo' style='font-size:9px; width:60px; height:15px; padding-left:1px; float:right; margin-right:8px'/>";
//	}
//}
?>
<input type='hidden' id='id-aluno' name='id-aluno' value=''>
<input type='hidden' id='id-turma' name='id-turma' value='<?php echo $turmaID;?>'>
<input type='hidden' id='id-cadastro' name='id-cadastro' value='<?php echo $detalhesTurma[Cadastro_ID]?>'>
<input type='hidden' id='cadastro-id' name='cadastro-id' value='<?php echo $detalhesTurma[Cadastro_ID]?>'>
<input type='hidden' id='workflow-id' name='workflow-id' value=''>
<input type='hidden' id='nome-turma' name='nome-turma' value=''>
<input type='hidden' id='evento-id' name='evento-id' value=''>

<div class="titulo-container conjunto1"  id='form-detalhes-turma'>
	<div class="titulo">
		<p>
			Detalhes da Turma
			<?php echo $botoes;?>
		</p>
	</div>
	<div class="conteudo-interno">
		<div class="titulo-secundario" Style='width:25%;float:left;'>
			<div class="titulo-secundario">
				<p>Nome da Instituição:</p>
				<p>
					<select name="turma-instituicao" id="turma-instituicao" class='required localiza-filho' campo-filho='turma-campus' filho-id='47'>
						<?php echo optionValueGrupo(46,$detalhesTurma[Instituicao_ID],'Selecione','');?>
					</select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<div class="titulo-secundario">
				<p>Campus:</p>
				<p>
					<select name="turma-campus" id="turma-campus" class='required localiza-filho' campo-filho='turma-curso' filho-id='48'>
						<?php echo optionValueGrupo(47,$detalhesTurma[Campus_ID],'Selecione','');?>
					</select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario" Style='width:25%;float:left;'>
			<div class="titulo-secundario">
				<p>Curso:</p>
				<p>
					<select name="turma-curso" id="turma-curso" class='required' >
						<?php echo optionValueGrupo(48,$detalhesTurma[Curso_ID] ,'Selecione','');?>
					</select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<div class="titulo-secundario">
				<p>Período:</p>
				<p class='omega'>
					<select name="turma-periodo" id="turma-periodo" class='required'>
						<?php echo optionValueGrupo(49,$detalhesTurma[Periodo_ID],'Selecione','');?>
					</select>
				</p>
			</div>
		</div>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<p>Turno:</p>
			<p class='omega'>
				<select name="turma-turno" id="turma-turno" class='required'>
					<?php echo optionValueGrupo(55,$detalhesTurma[Turno_ID],'Selecione','');?>
				</select>
			</p>
		</div>

		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<div class="titulo-secundario">
				<p>Nome da Turma:</p>
				<p><input type='text' name='titulo-turma' id='titulo-turma' style='width:95%' value='<?php echo $detalhesTurma[Nome_Turma]?>'></p>
			</div>
		</div>
		<p Style='float:left;width:100%;margin:3px;'></p>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<p>C&oacute;digo <?php echo $botaoGerarCodigo;?></p>
			<p><input type='text' name="turma-codigo" id="turma-codigo" maxlength='30' style='width:95%' value='<?php echo $detalhesTurma[Codigo]?>'></p>
		</div>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<p>Nº Formandos:</p>
			<p><input type='text' name="turma-quantidade" id="turma-quantidade" class='formata-numero' maxlength='3' style='width:96%' value='<?php echo $detalhesTurma[Quantidade]?>'></p>
		</div>
		<div class="titulo-secundario" Style='width:12.5%;float:left;'>
			<p>Responsável</p>
			<p>
				<select name="turma-responsavel" id="turma-responsavel" style='width:98.5%'>
					<option value=''></option>
<?php
		$grupos = mpress_query("select cd.Cadastro_ID, cd.Nome from cadastros_dados cd
									inner join modulos_acessos ma on ma.Modulo_Acesso_ID = cd.Grupo_ID
									where cd.Cadastro_ID > 0 and ma.Situacao_ID = 1 order by cd.Nome");
		while($row = mpress_fetch_array($grupos)){
			if ($detalhesTurma[Responsavel_ID]==$row['Cadastro_ID']) $selecionado = ' selected '; else $selecionado = '' ;
			echo " 						<option value='".$row['Cadastro_ID']."' $selecionado>".$row['Nome']."</option>";
		}
?>				</select>
			</p>
		</div>

		<div class="titulo-secundario" Style='width:25%;float:left;'>
			<p>Email Turma:</p>
			<p><input type='text' name="email-turma" id="email-turma" maxlength='200' style='width:99%' value='<?php echo $detalhesTurma[Email]?>'></p>
		</div>

	</div>
</div>

<?php
if ($turmaID!=""){
?>
	<div class="titulo-container conjunto1" id='form-eventos-cadastrados'>
		<div class="titulo">
			<p>
				Eventos
				<input type="button" value="Incluir Novo Evento" class='turma-gerencia-evento' evento-id='' style="float:right;height:24px;font-size:10px;margin-top:-4px;width:120px">
			</p>
		</div>
		<div class="conteudo-interno" id='form-mostra-eventos-cadastrados'>
			<div id='mostra-eventos-cadastrados-lista'>
				<?php $eventosCadastrados = localizaEventosCadastradosTurma($turmaID);?>
			</div>
			<?php
				if($eventosCadastrados < 1){
					echo "<p align='center' style='margin:5px 0 5px 0;'>Nenhum evento cadastrado para a turma</p>";
					echo "<style>#mostra-eventos-cadastrados-lista{display:none;}</style>";
				}
			?>
		</div>
	</div>

	<div class="titulo-container esconde" id='eventos-gerenciar'>
		<div class="titulo">
			<p>Cadastrar Evento</p>
		</div>
		<div class="conteudo-interno" id='form-cadastra-evento'>
			<div class="titulo-secundario" style="width:20%;float:left;">
				<p>Tipo Evento</p>
				<p>
					<select name="tipo-evento" id="tipo-evento" class='required'>
						<?php echo optionValueGrupo(57,'','Selecione','');?>
					</select>
				</p>
			</div>
			<div class="titulo-secundario" style="width:10%;float:left;">
				<p>Data Evento</p>
				<p><input type="text" id="data-evento" name="data-evento" maxlength="10" class="formata-data" value="" style='width:95%;'/></p>
			</div>
			<div class="titulo-secundario" style="width:20%;float:left;">
				<p>Local Evento</p>
				<p>
					<select name="local-evento" id="local-evento" class=''>
						<?php echo optionValueGrupo(58,'','Selecione','');?>
					</select>
				</p>
			</div>
			<div class="titulo-secundario" style="width:10%;float:left;">
				<p>Participantes</p>
				<p><input type='text' name="participantes-evento" id="participantes-evento" maxlength='30' class='formata-numero' style='width:90%' value='<?php echo ""; ?>'/></p>
			</div>
			<div class="titulo-secundario" style="width:40%;float:left;">
				<p>Observações</p>
				<p><input type='text' name="descricao-evento" id="descricao-evento" maxlength='30' style='width:99%' value='<?php echo ""; ?>'/></p>
			</div>
			<div class="titulo-secundario" style="width:100%;float:right; margin-top:5px;">
				<input type="button" value="Cancelar" class="botao-cancelar-evento" style='width:150px; float:right; margin-left:8px;'/>
				<input type="button" value="Salvar Evento" class="botao-salvar-evento" style='width:150px; float:right; margin-left:8px;'/>
			</div>
		</div>
	</div>


	<div class="titulo-container conjunto1" id='form-alunos-cadastrados'>
		<div class="titulo">
			<p>
				Alunos Cadastrados
				<input type="button" value="Incluir Novo Aluno" id='turma-inclui-aluno' style="float:right;height:24px;font-size:10px;margin-top:-4px;width:120px">
			</p>
		</div>
		<div class="conteudo-interno" id='form-mostra-alunos-cadastrados'>
			<div id='mostra-alunos-cadastrados-lista'>
				<?php $alunosCadastrados = localizaAlunosCadastradosTurma($detalhesTurma[Cadastro_ID]);?>
			</div>
			<?php
				if($alunosCadastrados < 1){
					echo "<p align='center' style='margin:5px 0 5px 0;'>Nenhum aluno cadastrado para a turma</p>";
					echo "<style>#mostra-alunos-cadastrados-lista{display:none;}</style>";
				}
			?>
		</div>
	</div>


	<div class="titulo-container esconde" id='alunos-novo-aluno'>
		<div class="titulo">
			<p>
				Cadastrar Novo Aluno
			</p>
		</div>
		<div class="conteudo-interno" id='form-cadastra-nova-turma'>
			<div class="titulo-secundario" style="width:5%;float:left;padding-left:5px;">
				<input type="file" name="arquivo-upload-cadastro" id="arquivo-upload-cadastro" class="esconde">
				<p>Foto</p>
				<p>
					<iframe name="iframe-upload-cadastro" id="iframe-upload-cadastro" class="esconde"></iframe>
					</p><div id="div-foto" name="div-foto"><img src="http://homologacao.grupoinformare.com.br/images/geral/imagem-usuario.jpg" width="100%" id="imagem-foto" style="cursor:pointer"></div>
				<p></p>
			</div>
			<div class="titulo-secundario" Style='width:94%;float:right;'>
				<div class="titulo-secundario quatro-colunas">
					<p>Nome Completo</p>
					<p><input type="text" id="nome-completo" name="nome-completo" maxlength="250" value="" autocomplete="off" Style='width:95%;height:20px;' class='required'></p>
				</div>
				<div class="div-pf titulo-secundario oito-colunas">
					<p>CPF</p>
					<p><input type="text" id="cpf" name="cpf" maxlength="14" class="mascara-cpf" value="" autocomplete="off" Style='width:88%'></p>
				</div>
				<div class="div-pf titulo-secundario oito-colunas">
					<p>RG</p>
					<p><input type="text" id="rg" name="rg" maxlength="20" value="" autocomplete="off" style="width:87%;"></p>
				</div>
				<div class="div-pf titulo-secundario dez-colunas">
					<p>Sexo</p>
					<p>
						<select name="sexo" id="sexo" Style='width:89%;height:28px;'>
							<option value='M'>Masculino</option>
							<option value='F'>Feminino</option>
						</select>
					</p>
				</div>
				<div class="div-pf titulo-secundario seis-colunas">
					<p>E-mail</p>
					<p><input type="text" id="cadastro-email" name="cadastro-email" class="valida-email" value="" autocomplete="off" style="width:90%;height:20px;"></p>
				</div>
				<div class="titulo-secundario" Style='width:11%;float:left;'>
					<p>Data Nascimento</p>
					<p><input type="text" id="data-nascimento" name="data-nascimento" maxlength="10" class="formata-data" value="" autocomplete="off" Style='margin-left:-3px;height:20px;width:89%;text-align:left;'></p>
				</div>
				<div class="titulo-secundario" Style='width:12%;float:left;'>
					<p>Telefone</p>
					<p><input type="text" id="telefone-telefone" name="telefone-telefone" value="" class="formata-telefone required" maxlength="15" Style='height:20px;width:97.3%'></p>
				</div>

				<div Style='width:100%;float:left;margin-top:2px;'></div>
				<div class="titulo-secundario" Style='width:11%;float:left;'>
					<p>CEP</p>
					<p><input type="text" id="cep-endereco" name="cep-endereco" value="" class="mascara-cep" maxlength="9" Style='height:20px;width:89%'></p>
				</div>
				<div class="titulo-secundario" Style='width:26.5%;float:left;'>
					<p>Endereço</p>
					<p><input type="text" id="logradouro-endereco" name="logradouro-endereco" value="" class="" maxlength="15" Style='height:20px;width:94.8%'></p>
				</div>

				<div class="titulo-secundario" Style='width:6.5%;float:left;'>
					<p>Nº</p>
					<p><input type="text" id="numero-endereco" name="numero-endereco" value="" class="numero-enderco" maxlength="5" Style='height:20px;width:78%'></p>
				</div>

				<div class="titulo-secundario" Style='width:16%;float:left;'>
					<p>Complemento</p>
					<p><input type="text" id="complemento-endereco" name="complemento-endereco" value="" class="" maxlength="20" Style='height:20px;width:90%'></p>
				</div>

				<div class="titulo-secundario" Style='width:16.3%;float:left;'>
					<p>Bairro</p>
					<p><input type="text" id="bairro-endereco" name="bairro-endereco" value="" class="" maxlength="15" Style='height:20px;width:92%'></p>
				</div>
				<div class="titulo-secundario" Style='width:18%;float:left;'>
					<p>Cidade</p>
					<p><input type="text" id="cidade-endereco" name="cidade-endereco" value="" class="e" maxlength="15" Style='height:20px;width:92%'></p>
				</div>
				<div class="titulo-secundario" Style='width:5%;float:left;'>
					<p>UF</p>
					<p><input type="text" id="uf-endereco" name="uf-endereco" value="" class="" maxlength="15" Style='height:20px;width:99.5%'></p>
				</div>

				<div Style='width:100%;float:left;margin-top:5px;'></div>
				<div class="titulo-secundario" style="width:100%;float:left">
					<div class="titulo-secundario" style="width:100%;float:right;">
						<div class="titulo-secundario duas-colunas" style="margin-left:0px;">
							<p>Cargo</p>
							<p>
								<select name='cargo-turma' id='cargo-turma' class='required'>
									<?php echo optionValueGrupo(50, '', 'Selecione', '');?>
								</select>
							</p>
						</div>

						<div class="titulo-secundario duas-colunas" style="margin-left:0px;">
							<p>Observação </p>
							<p><input type='text' id="observacao" name="observacao" Style='width:98.5%;'></p>
						</div>

					</div>
				</div>
				<div class="titulo-secundario uma-coluna" Style='margin-top:5px;min-height:30px;'>
					<div class="titulo-secundario">
						<p Style='width:150px;float:right;margin-left:8px;'><input type="button" value="Cancelar" id="botao-cadastra-aluno-turma-cancela" class="botao-cadastra-aluno-turma-cancela"></p>
						<p Style='width:150px;float:right;margin-left:8px;'><input type="button" value="Cadastrar Aluno" id="botao-cadastra-aluno-turma" class="botao-cadastra-aluno-turma"></p>
						<p Style='width:150px;float:right;margin-left:8px;'><input type="button" value="Atualizar Aluno" id="botao-atualiza-aluno-turma" class="botao-atualiza-aluno-turma esconde"></p>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="titulo-container conjunto7 esconde">
		<div class="titulo">
			<p>
				Or&ccedil;amentos
				<input type="button" value="Incluir Novo" class="novo-orcamento-turma" style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
			</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<?php carregarOrcamentosTurma($detalhesTurma[Cadastro_ID]);?>
			</div>
		</div>
	</div>

	<div class="titulo-container conjunto2 esconde">
		<div class="titulo">
			<p>
				<?php echo $_SESSION['objeto'];?>
				<input type="button" value="Incluir Novo" class='workflow-localiza' name='' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
			</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario uma-coluna">
				<?php carregarChamadosTurma($detalhesTurma[Cadastro_ID]);?>
			</div>
		</div>
	</div>

		<!-- INICIO Bloco Upload usando PLUPLOAD -->
		<div id='div-documentos'></div>
		<div id="container">
			<input type="hidden" id="pickfiles"/>
			<input type="hidden" id="uploadfiles"/>
		</div>
		<!-- FIM Bloco Upload usando PLUPLOAD -->

	<!--
	<div class="titulo-container conjunto4 esconde">
		<div class="titulo">
			<p>
				Produtos
			</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario quatro-colunas">
			</div>
		</div>
	</div>

	<div class="titulo-container conjunto6 esconde">
		<div class="titulo">
			<p>
				Financeiro
			</p>
		</div>
		<div class="conteudo-interno">
			<div class="titulo-secundario quatro-colunas">
			</div>
		</div>
	</div>
	-->
<?php
}
?>
