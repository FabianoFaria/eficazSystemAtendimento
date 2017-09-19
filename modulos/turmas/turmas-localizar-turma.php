<?php
include("functions.php");
global $dadosUserLogin;
?>
<input type='hidden' id='id-turma' name='id-turma' value='<?php echo $detalhesTurma[Turma_ID]?>'>
<div class="titulo-container">
	<div class="titulo">
		<p>
			Filtros de Pesquisa - Localizar Turmas
			<input type="button" value="Incluir Nova" class='turma-mostrar-detalhes' turma-id='' style="float:right;height:24px;font-size:10px;margin-top:-3px;width:120px">
		</p>
	</div>

	<div class="conteudo-interno">
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p>C&oacute;digo</p>
			<p><input type='text' name='turma-localiza-codigo' id='turma-localiza-codigo' value="<?php echo $_POST['turma-localiza-codigo'];?>" style='width:95%'/></p>
		</div>
		<div class="titulo-secundario" style="width:25%;float:left;">
			<p>Nome da Instituição:</p>
			<p>
				<select name="turma-localiza-instituicao" id="turma-localiza-instituicao" class='required localiza-filho' campo-filho='turma-localiza-campus' filho-id='47'>
					<?php echo optionValueGrupo(46,$_POST['turma-localiza-instituicao'],'&nbsp;','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p>Campus:</p>
			<p>
				<select name="turma-localiza-campus" id="turma-localiza-campus" class='localiza-filho' campo-filho='turma-localiza-curso' filho-id='48'>
					<?php echo optionValueGrupo(47,$_POST['turma-localiza-campus'],'&nbsp;','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:25%;float:left;">
			<p>Curso:</p>
			<p>
				<select name="turma-localiza-curso" id="turma-localiza-curso">
					<?php echo optionValueGrupo(48,$_POST['turma-localiza-curso'],'&nbsp;','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p>Período:</p>
			<p>
				<select name="turma-localiza-periodo" id="turma-localiza-periodo">
					<?php echo optionValueGrupo(49,$_POST['turma-localiza-periodo'],'&nbsp;','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p>Turno:</p>
			<p>
				<select name="turma-localiza-turno" id="turma-localiza-turno">
					<?php echo optionValueGrupo(55,$_POST['turma-localiza-turno'],'&nbsp;','');?>
				<select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p align='center'><input type='checkbox' name='turma-localiza-com-contrato' id='turma-localiza-com-contrato' value="1" <?php if ($_POST['turma-localiza-com-contrato'] == 1) echo "checked"; ?>/>Exibir apenas turmas com contrato</p>
		</div>
 		<div class="titulo-secundario" style="width:25%;float:left;">
			<p>Responsável</p>
			<p>
				<select name="turma-localiza-responsavel" id="turma-localiza-responsavel" style='width:98.5%'>
					<option value=''></option>
<?php
		$sql = "select distinct cd.Cadastro_ID, cd.Nome from cadastros_dados cd
									inner join modulos_acessos ma on ma.Modulo_Acesso_ID = cd.Grupo_ID
									inner join turmas_dados t on t.Responsavel_ID = cd.Cadastro_ID
									where cd.Cadastro_ID > 0 and ma.Situacao_ID = 1 and t.Situacao_ID = 1 order by cd.Nome";
		$resultSet = mpress_query($sql);
		while($rs = mpress_fetch_array($resultSet)){
			if ($_POST['turma-localiza-responsavel']==$rs['Cadastro_ID']) $selecionado = ' selected '; else $selecionado = '' ;
			echo " 						<option value='".$rs['Cadastro_ID']."' $selecionado>".$rs['Nome']."</option>";
		}
?>				</select>
			</p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:left;">
			<p>Nome da Turma:</p>
			<p><input type='text' name='turma-localiza-titulo' id='turma-localiza-titulo' value="<?php echo $_POST['turma-localiza-titulo'];?>" style='width:95%'/></p>
		</div>
		<div class="titulo-secundario" style="width:12.5%;float:right;">
			<p>&nbsp;</p>
			<p class='omega'>
				<input type="button" value="Localizar" id="turma-localiza" Style='height:30px;'>
			</p>
		</div>
	</div>
</div>
<?php
	$i = 0;
	if($_POST['turma-localiza-codigo']!="") 		$strCond 	= " and d.Codigo like '".$_POST['turma-localiza-codigo']."%'";
	if($_POST['turma-localiza-com-contrato']!="")	$strCond 	.= " and trim(d.Codigo) <> '' ";
	if($_POST['turma-localiza-responsavel']!="")	$strCond 	.= " and d.Responsavel_ID = '".$_POST['turma-localiza-responsavel']."' ";
	if($_POST['turma-localiza-titulo']!="")			$strCond 	.= " and Nome_Turma like '%".$_POST['turma-localiza-titulo']."%' ";

	if($_POST['turma-localiza-instituicao']!="") $strInstituicao 	= " and d.Instituicao_ID = ".$_POST['turma-localiza-instituicao']." ";
	if($_POST['turma-localiza-campus']!="")		$strCampus 		= " and d.Campus_ID 	 = ".$_POST['turma-localiza-campus']." ";
	if($_POST['turma-localiza-curso']!="")		$strCurso 		= " and d.Curso_ID 		 = ".$_POST['turma-localiza-curso']." ";
	if($_POST['turma-localiza-periodo']!="") 	$strPeriodo 		= " and d.Periodo_ID 	 = ".$_POST['turma-localiza-periodo']." ";
	if($_POST['turma-localiza-turno']!="") 		$strTurno 		= " and d.Turno_ID 	 	 = ".$_POST['turma-localiza-turno']." ";


	if (($dadosUserLogin['grupoID']==-3) || ($dadosUserLogin['grupoID']==-2))
		$strCond  = " and d.Responsavel_ID = ".$dadosUserLogin['userID'];


	if($_POST['ordena-tabela'] != ""){
		$strOrdem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
	}else{
		$strOrdem = " order by d.Codigo, Curso, Instituicao";
	}
	$sql = " select d.Codigo as Codigo, Turma_ID, Nome_Turma, t1.Descr_Tipo Instituicao, t2.Descr_Tipo Campus, t3.Descr_Tipo Curso,
								t4.Descr_Tipo Periodo, d.Data_Cadastro, t5.Descr_Tipo Turno, r.Nome as Responsavel_Turma
								from turmas_dados d
								inner join tipo t1 on t1.Tipo_ID = d.Instituicao_ID
								inner join tipo t2 on t2.Tipo_ID = d.Campus_ID
								left join tipo t3 on t3.Tipo_ID = d.Curso_ID
								left join tipo t4 on t4.Tipo_ID = d.Periodo_ID
								left join tipo t5 on t5.Tipo_ID = d.Turno_ID
								left join cadastros_dados r on r.Cadastro_ID = d.Responsavel_ID
								where d.Situacao_ID = 1
								$strInstituicao $strCampus $strCond $strCurso $strPeriodo $strTurno
								$strOrdem";
	$resultado = mpress_query($sql);
	while($row = mpress_fetch_array($resultado)){
		$i++;
		$c=1;
		$dados[colunas][conteudo][$i][$c++] = "<span class='turma-mostrar-detalhes link' turma-id='".$row[Turma_ID]."'>".$row[Turma_ID]."</span>";
		$dados[colunas][conteudo][$i][$c++] = "<span class='turma-mostrar-detalhes link' turma-id='".$row[Turma_ID]."'>".$row[Codigo]."</span>";
		$dados[colunas][conteudo][$i][$c++] = "<span class='turma-mostrar-detalhes link' turma-id='".$row[Turma_ID]."'>".$row['Instituicao']."</span>";
		$dados[colunas][conteudo][$i][$c++] = $row['Campus'];
		$dados[colunas][conteudo][$i][$c++] = $row['Curso'];
		$dados[colunas][conteudo][$i][$c++] = $row['Periodo'];
		$dados[colunas][conteudo][$i][$c++] = $row['Turno'];
		$dados[colunas][conteudo][$i][$c++] = $row['Nome_Turma'];
		$dados[colunas][conteudo][$i][$c++] = $row['Responsavel_Turma'];
		$dados[colunas][conteudo][$i][$c++] = "<span style='text-align:center;width:100%;float:left;'>".formataData($row['Data_Cadastro']."</span>");
	}
	$largura = "100.2%";
	$colunas = $c - 1;
	$c = 1;
	$dados[colunas][tamanho][$c++] = "width='5%'";
	$dados[colunas][tamanho][$c++] = "width='90px'";
	$dados[colunas][tamanho][$c++] = "width=''";
	$dados[colunas][tamanho][$c++] = "width=''";
	$dados[colunas][tamanho][$c++] = "width=''";
	$dados[colunas][tamanho][$c++] = "width='70px'";
	$dados[colunas][tamanho][$c++] = "width=''";
	$dados[colunas][tamanho][$c++] = "";
	$dados[colunas][tamanho][$c++] = "";
	$dados[colunas][tamanho][$c++] = "width='70px'";
	$c = 1;
	$dados[colunas][titulo][$c++] 	= "ID";
	$dados[colunas][titulo][$c++] 	= "Código";
	$dados[colunas][titulo][$c++] 	= "Instituição";
	$dados[colunas][titulo][$c++] 	= "Campus";
	$dados[colunas][titulo][$c++] 	= "Curso";
	$dados[colunas][titulo][$c++] 	= "Período";
	$dados[colunas][titulo][$c++] 	= "Turno";
	$dados[colunas][titulo][$c++] 	= "Nome da Turma";
	$dados[colunas][titulo][$c++] 	= "Responsável";
	$dados[colunas][titulo][$c++] 	= "<span style='text-align:center;width:100%;float:left;'>Data Cadastro</span>";
	$c = 1;
	$dados[colunas][ordena][$c++] = " Turma_ID";
	$dados[colunas][ordena][$c++] = " d.Codigo";
	$dados[colunas][ordena][$c++] = " t1.Descr_Tipo";
	$dados[colunas][ordena][$c++] = " t2.Descr_Tipo";
	$dados[colunas][ordena][$c++] = " t3.Descr_Tipo";
	$dados[colunas][ordena][$c++] = " t4.Descr_Tipo";
	$dados[colunas][ordena][$c++] = " t5.Descr_Tipo";
	$dados[colunas][ordena][$c++] = " Nome_Turma";
	$dados[colunas][ordena][$c++] = " Responsavel_Turma";
	$dados[colunas][ordena][$c++] = " d.Data_Cadastro";

?>
<?php if($_POST){?>
	<div class="titulo-container">
		<div class="titulo">
			<p Style='margin-top:3px;'>
				Turmas Localizadas: <?php echo $i;?>
			</p>
		</div>
		<div class="conteudo-interno" id='form-cadastra-nova-turma'>
			<?php
				if ($i==0)
					echo "<p align='center'>Nenhuma turma localizada</p>";
				else
					geraTabela($largura,$colunas,$dados, null, 'lista-turma-localiza', 2, 2, 100,1);
			?>
		</div>
	</div>
<?php }?>
