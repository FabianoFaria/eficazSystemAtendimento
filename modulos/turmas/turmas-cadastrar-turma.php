<?php include("functions.php");?>
<div class="titulo-container">
	<div class="titulo">
		<p>Detalhes da Nova Turma</p>
	</div>
	<div class="conteudo-interno" id='form-cadastra-nova-turma'>
		<div class="titulo-secundario" Style='width:20%;float:left;'>
			<p>Nome da Instituição:</p>
			<p>
				<select name="turma-instituicao" id="turma-instituicao" class='required'>
					<?php echo optionValueGrupo(46,$selecionado,'Selecione','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:15%;float:left;margin-left:0.3%;'>
			<p>Campus:</p>
			<p>
				<select name="turma-campus" id="turma-campus" class='required'>
					<?php echo optionValueGrupo(47,$selecionado,'Selecione','');?>

				<select>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:15%;float:left;margin-left:0.3%;'>
			<p>Curso:</p>
			<p>
				<select name="turma-curso" id="turma-curso" class='required'>
					<?php echo optionValueGrupo(48,$selecionado,'Selecione','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:11%;float:left;margin-left:0.3%;'>
			<p>Período:</p>
			<p class='omega'>
				<select name="turma-periodo" id="turma-periodo" class='required'>
					<?php echo optionValueGrupo(49,$selecionado,'Selecione','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:11%;float:left;margin-left:0.3%;'>
			<p>Turno:</p>
			<p class='omega'>
				<select name="turma-turno" id="turma-turno" class='required'>
					<?php echo optionValueGrupo(55,$selecionado,'Selecione','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:16%;float:left;margin-left:0.3%;'>
			<p>Nome da Turma:</p>
			<p class='omega'>
				<input type='text' name="titulo-turma" id="titulo-turma" class='required'>
			</p>
		</div>
		<div class="titulo-secundario" Style='width:10%;float:left;margin-left:0.5%;'>
			<p>Nº Formandos:</p>
			<p><input type='text' name="turma-quantidade" id="turma-quantidade" class='required formata-numero' maxlength='3' style='width:97%'></p>
		</div>

		<div class="titulo-secundario uma-coluna" Style='margin-top:5px;min-height:30px;'>
			<div class="titulo-secundario">
				<p class="direita"><input type="button" value="Cadastrar Turma" id="botao-cadastrar-turma" class="botao-cadastrar-turma"></p>
			</div>
		</div>
		<input type='hidden' id='nome-turma' name='nome-turma'>
	</div>
</div>