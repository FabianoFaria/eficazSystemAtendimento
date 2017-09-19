<?php
include("functions.php");
global $caminhoSistema, $dadosUserLogin;
if ($_POST){
	$responsavelID = $_POST['responsavel'];
	$grupoID = $_POST['grupo'];
	$exibirAtrelados = $_POST['exibir-atrelados'];
	if ($exibirAtrelados==1){
		$checkedAtrelados = " checked ";	
	}
	$virgula = "";
	for($i = 0; $i < count($_POST['localiza-chamado-situacao']); $i++){
		$situacoes .= $virgula.$_POST['localiza-chamado-situacao'][$i];
		$virgula = ",";
	}	
}
else{
	$checkedAtrelados = " checked ";	
	$grupoID = $dadosUserLogin['grupoID'];
	$responsavelID = $dadosUserLogin['userID'];
}
$arrTiposChamados = carregarArrayTipo(19);

echo "<input type='hidden' id='workflow-id' name='workflow-id' value=''/>
	<div class='titulo-container'>
		<div class='titulo'>
			<p>Filtros de pesquisa</p>
		</div>
		<div class='conteudo-interno'>
			<div class='titulo-secundario' style='float:left;width:20%'>
				<p>Grupo Respons&aacute;vel:</p>
				<p><select id='grupo' name='grupo' Style='width:98.5%' class='select-grupo-atualiza-usuarios' campo='responsavel'>".optionValueGruposAcessos($grupoID, '', 'Todos')."</select></p>
			</div>
			<div class='titulo-secundario' style='float:left;width:20%'>
				<p>Respons&aacute;vel:</p>
				<p><select id='responsavel' name='responsavel' Style='width:98.5%'>".optionValueUsuarios($responsavelID, "", "","Todos")."</select></p>
			</div>
			<div class='titulo-secundario' style='float:left;width:30%; position:relative; z-index:2;'>
				<p>Situa&ccedil;&atilde;o:</p>
				<select name='localiza-chamado-situacao[]' multiple id='localiza-chamado-situacao' style='height:71px;'>".optionValueGrupoMultiplo(18, $situacoes, " AND Tipo_ID not in(33,34) ")."<select>
			</div>
			<div class='titulo-secundario' style='float:left;width:20%'>
				<p style='margin-top:20px;'>Exibir apenas chamados com Envios <input type='checkbox' name='exibir-atrelados' value='1' $checkedAtrelados /></p>
			</div>
			<div class='titulo-secundario' Style='width:10%; margin-top:15px; float:right;'>
				<p class='direita'><input type='button' Style='width:140px;' value='Pesquisar' class='botao-pesquisar-resumo'></p>
			</div>
		</div>
	</div>";
	
	$i = 0;
	
	
	if ($responsavelID!=""){ 
		$sqlCond .= " and cw.Responsavel_ID = '$responsavelID' ";
	}
	else{
		if ($grupoID!=""){ 
			$sqlCond .= " and cw.Grupo_Responsavel_ID = '$grupoID' ";
		}
	}
	if($situacoes!="") $sqlCond .= " and cf.Situacao_ID in ($situacoes)";
	if ($exibirAtrelados!=""){
		$sqlCond .= " and cw.Workflow_ID in (Select ew.Chave_Estrangeira from envios_workflows ew where ew.Chave_Estrangeira =  cw.Workflow_ID AND ew.Tabela_Estrangeira = 'chamados')";
	}
	
	$sql = "select cw.Workflow_ID, cw.Codigo as Codigo, cw.Tipo_WorkFlow_ID, cw.Titulo,
					cd1.Nome as Solicitante, cd1.Nome_Fantasia as Solicitante_Fantasia, cd1.email as Email_Solicitante,
					cd2.Nome as Prestador, cd2.Nome_Fantasia as Prestador_Fantasia, cd2.email as Email_Prestador,
					t.Descr_Tipo as Situacao, cf.Situacao_ID as Situacao_ID,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y') as Data_Abertura,
					DATE_FORMAT(cw.Data_Abertura,'%d/%m/%Y %H:%i') as Data_Hora_Abertura,
					DATE_FORMAT(cf.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
					DATE_FORMAT(cw.Data_Limite,'%d/%m/%Y') as Data_Limite,
					p.Descr_Tipo as Prioridade, p.Tipo_Auxiliar, r.Nome as Responsavel, g.Titulo as Grupo_Responsavel
			from chamados_workflows cw
			$sqlInner
			inner join cadastros_dados cd1 on cd1.Cadastro_ID = cw.Solicitante_ID
			left join cadastros_dados cd2 on cd2.Cadastro_ID = cw.Prestador_ID
			left join chamados_follows cf on cw.Workflow_ID = cf.Workflow_ID
				and cf.Follow_ID = (select max(cfaux.Follow_ID) from chamados_follows cfaux where cf.Workflow_ID = cfaux.Workflow_ID)
			left join tipo t on t.Tipo_ID = cf.Situacao_ID
			left join tipo p on p.Tipo_ID = cw.Prioridade_ID
			left join cadastros_dados r on r.Cadastro_ID = cw.Responsavel_ID
			left join modulos_acessos g on g.Modulo_Acesso_ID = cw.Grupo_Responsavel_ID
			where cw.Workflow_ID > 0 and cf.Situacao_ID not in(33,34) 
				$sqlCond
			order by cw.Workflow_ID desc";			
	//echo $sql;
	$origem = 0;
	$resultado = mpress_query($sql);
	while($rs = mpress_fetch_array($resultado)){

		$i++;
		$dados[colunas][classe][$i] = "destaque-tabela";
		$dados[colunas][conteudo][$i][1] = "<p align='center' style='margin:5px 5px 5px; 5px;'>".strtoupper($_SESSION['objeto']).":&nbsp;".$rs[Workflow_ID]."</p>";
		$dados[colunas][colspan][$i][1] = "10";
		
		$i++; $c=1;
		$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";	
		$dados[colunas][conteudo][$i][$c++] 	= " ID ".$_SESSION['objeto'];
		$dados[colunas][conteudo][$i][$c++] 	= " C&oacute;digo ".$_SESSION['objeto'];
		$dados[colunas][conteudo][$i][$c++] 	= " Respons&aacute;vel";
		$dados[colunas][conteudo][$i][$c++] 	= " T&iacute;tulo";
		$dados[colunas][conteudo][$i][$c++] 	= " Solicitante";
		$dados[colunas][conteudo][$i][$c++] 	= " Situa&ccedil;&atilde;o";
		$dados[colunas][conteudo][$i][$c++] 	= " Abertura";
		$dados[colunas][conteudo][$i][$c++]	= " Intera&ccedil;&atilde;o";
		$dados[colunas][conteudo][$i][$c++] 	= " Prioridade";
		$dados[colunas][conteudo][$i][$c++] 	= " Limite";
		
		$i++; $c=1;
		$dados[colunas][classe][$i] = "tabela-fundo-claro";	
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$rs[Workflow_ID]."'>".$rs[Workflow_ID]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-localiza' workflow-id='".$rs[Workflow_ID]."'>".$rs[Codigo]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Responsavel]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Titulo]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;' title='".$rs[Solicitante_Fantasia]."'>".$rs[Solicitante]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Situacao]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Data_Abertura]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Data_Interacao]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;color:".$arrayPrioridades[cor]."'>".$rs[Prioridade]."</p>";
		$dados[colunas][conteudo][$i][$c++] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$rs[Data_Limite]."</p>";
		$origem .= $rs[Workflow_ID].",";
		
/**/		
		$sql = "select ew.Workflow_ID, cd1.Nome as Cadastro_de, cd2.Nome as Cadastro_para, cd3.Nome as Trasportadora, ef.Situacao_ID as Situacao_ID, tf.Descr_Tipo as Forma_Envio, ew.Codigo_Rastreamento, t.Descr_Tipo as Situacao,
				DATE_FORMAT(ew.Data_Envio,'%d/%m/%Y') as Data_Envio, DATE_FORMAT(ew.Data_Previsao,'%d/%m/%Y') as Data_Previsao,  DATE_FORMAT(ew.Data_Entrega,'%d/%m/%Y') as Data_Entrega, DATE_FORMAT(ef.Data_Cadastro,'%d/%m/%Y') as Data_Interacao,
				ew.Usuario_Cadastro_ID as Usuario_Cadastro_ID,  CONCAT(COALESCE(pd.Nome,''),' ', COALESCE(pv.Descricao,'')) as Produto, ewp.Quantidade, sol.Nome as Solicitante, ewp.Retorna as Retorna, ewp.Data_Retorno as Data_Retorno, ewp.Embalado, ewp.Observacoes as Observacoes,
				te.Descr_Tipo as Tipo_Envio
				from envios_workflows ew
				inner join envios_workflows_produtos ewp on ewp.Workflow_ID = ew.Workflow_ID
				inner join produtos_variacoes pv on pv.Produto_Variacao_ID = ewp.Produto_Variacao_ID
				inner join produtos_dados pd on pd.Produto_ID = pv.Produto_ID
				inner join cadastros_dados sol on sol.Cadastro_ID = ew.Usuario_Cadastro_ID
				inner join envios_follows ef on ew.Workflow_ID = ef.Workflow_ID and ef.Follow_ID = (select max(efaux.Follow_ID) from envios_follows efaux where ef.Workflow_ID = efaux.Workflow_ID)
				inner join tipo t on t.Tipo_ID = ef.Situacao_ID
				left join tipo te on te.Tipo_ID = ew.Tipo_Envio_ID 
				left join cadastros_dados cd1 on cd1.Cadastro_ID = ew.Cadastro_ID_de
				left join cadastros_dados cd3 on cd3.Cadastro_ID = ew.Transportadora_ID
				left join tipo tf on tf.Tipo_ID = ew.Forma_Envio_ID
				left join cadastros_dados cd2 on cd2.Cadastro_ID = ew.Cadastro_ID_para
				where ew.Workflow_ID is not null
				and ew.Tabela_Estrangeira = 'chamados'
				and ew.Chave_Estrangeira = '".$rs[Workflow_ID]."'
				order by ew.Workflow_ID desc, ef.Follow_ID";
		//echo $sql."<br><br><br>";
		$query2 = mpress_query($sql);
		$ii=0;
		$flagCab = 0;
		while($row = mpress_fetch_array($query2)){
			$botaoCancelar = "";
			if ($row[Workflow_ID]!=$workflowIDAnt){
				$flagCab = 0;
				$ii++;
				$dadosEnvio[colunas][classe][$ii] = "tabela-fundo-escuro-titulo";	
				$dadosEnvio[colunas][conteudo][$ii][1] = "Tipo";
				$dadosEnvio[colunas][conteudo][$ii][2] = "ID Envio";
				$dadosEnvio[colunas][conteudo][$ii][3] = "Remetente";
				$dadosEnvio[colunas][conteudo][$ii][4] = "Destinat&aacute;rio";
				$dadosEnvio[colunas][conteudo][$ii][5] = "Forma Envio";
				$dadosEnvio[colunas][conteudo][$ii][6] = "Situa&ccedil;&atilde;o";
				$dadosEnvio[colunas][conteudo][$ii][7] = "Solicitante";
				$dadosEnvio[colunas][conteudo][$ii][8] = "Envio";
				$dadosEnvio[colunas][conteudo][$ii][9] = "Previs&atilde;o";
				$dadosEnvio[colunas][conteudo][$ii][10] = "Entrega";
		
				$ii++;
				$dadosEnvio[colunas][classe][$ii] = "tabela-fundo-claro";
				$dadosEnvio[colunas][conteudo][$ii][1] = "<p Style='margin:2px 3px 0 3px;float:left;'><b>".$row[Tipo_Envio]."</b></p>";			
				$dadosEnvio[colunas][conteudo][$ii][2] = "<p Style='margin:2px 3px 0 3px;float:left;' class='link workflow-envio' workflow-id='$row[Workflow_ID]'>".$row[Workflow_ID]."</p>";
 				$dadosEnvio[colunas][colspan][$ii][3] = "2";
				$dadosEnvio[colunas][conteudo][$ii][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Cadastro_de])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][3] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Cadastro_de])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][4] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Cadastro_para])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][5] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Forma_Envio])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Situacao])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][7] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Solicitante])."</p>";
				$dadosEnvio[colunas][conteudo][$ii][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Envio]."</p>";
				$dadosEnvio[colunas][conteudo][$ii][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Previsao]."</p>";
				$dadosEnvio[colunas][conteudo][$ii][10] = "<p Style='margin:2px 3px 0 3px;float:left;'>".$row[Data_Entrega]."</p>";
			}
			if ($flagCab==0){
				$ii++;
				$dadosEnvio[colunas][classe][$ii] = "tabela-fundo-escuro-titulo";			
				$dadosEnvio[colunas][conteudo][$ii][1] = "<b>Produto</b>";
				$dadosEnvio[colunas][colspan][$ii][1] = "5";
				$dadosEnvio[colunas][conteudo][$ii][6] = "<b>Quantidade</b>";
				$dadosEnvio[colunas][conteudo][$ii][7] = "<b>Observação</b>";
				$dadosEnvio[colunas][conteudo][$ii][8] = "<b>Retorna</b>";
				$dadosEnvio[colunas][conteudo][$ii][9] = "<b>Embalado</b>";
				$flagCab = 1;
			}
			$ii++;
			$dadosEnvio[colunas][classe][$ii] = "tabela-fundo-claro";
			$dadosEnvio[colunas][conteudo][$ii][1] = "<p Style='margin:2px 3px 0 3px;float:left;'>".($row[Produto])."</p>";
			$dadosEnvio[colunas][colspan][$ii][1] = "5";	
			$dadosEnvio[colunas][conteudo][$ii][6] = "<p Style='margin:2px 3px 0 3px;float:left;'>".number_format($row[Quantidade], 2, ',', '.')."</p>";

			if ($row[Retorna]==1) $retorna = "SIM"; else $retorna = "N&Atilde;O";
			if ($row[Embalado]==1) $embalado = "SIM"; else $embalado = "N&Atilde;O";

			$dadosEnvio[colunas][conteudo][$ii][7] = "<p Style='margin:5px 3px 3px 15px;float:left;'>".$row['Observacoes']."</p>";
			$dadosEnvio[colunas][conteudo][$ii][8] = "<p Style='margin:2px 3px 0 3px;float:left;'>$retorna</p>";
			$dadosEnvio[colunas][conteudo][$ii][9] = "<p Style='margin:2px 3px 0 3px;float:left;'>$embalado</p>";
			$workflowIDAnt = $row[Workflow_ID];
		}
		/*
		$dadosEnvio[colunas][titulo][1] = "Tipo";
		$dadosEnvio[colunas][titulo][2] = "ID Envio";
		$dadosEnvio[colunas][titulo][3] = "Remetente";
		$dadosEnvio[colunas][titulo][4] = "Destinat&aacute;rio";
		$dadosEnvio[colunas][titulo][5] = "Forma Envio";
		$dadosEnvio[colunas][titulo][6] = "Situa&ccedil;&atilde;o";
		$dadosEnvio[colunas][titulo][7] = "Solicitante";
		$dadosEnvio[colunas][titulo][8] = "Envio";
		$dadosEnvio[colunas][titulo][9] = "Previs&atilde;o";
		$dadosEnvio[colunas][titulo][10] = "Entrega";
		*/
		$dadosEnvio[colunas][tamanho][1] = "width='100px'";
		$dadosEnvio[colunas][tamanho][2] = "width='80px'";
		$dadosEnvio[colunas][tamanho][3] = "width='200px'";
		$dadosEnvio[colunas][tamanho][4] = "width='200px'";
		$dadosEnvio[colunas][tamanho][5] = "";
		$dadosEnvio[colunas][tamanho][6] = "";
		$dadosEnvio[colunas][tamanho][7] = "";
		$dadosEnvio[colunas][tamanho][8] = "width='075px'";
		$dadosEnvio[colunas][tamanho][9] = "width='075px'";
		$dadosEnvio[colunas][tamanho][10] = "width='075px'";

		if ($ii>0){
			$i++;
			$dados[colunas][classe][$i] = "tabela-fundo-claro";				
			$dados[colunas][conteudo][$i][1] = "<p align='center' style='margin:5px 5px 5px; 5px;'><font color='red'><b>CENTRO DE DISTRIBUIÇÃO</b></font></p>".geraTabela("90%","10",$dadosEnvio,"margin-top:0px;border:0px solid silver;margin-bottom:0px;",'','','','','','return');
			$dados[colunas][colspan][$i][1] = $c-1;
			unset($dadosEnvio);
		}
/**/	
	}
	$colunas = $c - 1;
	$largura = "100%";
	$c=1;

	$dados[colunas][tamanho][4] 	= "width='300px'";



	echo " <div class='titulo-container' id='localiza-chamado-retorno'>
			<div class='conteudo-interno-retorno' id='conteudo-interno-retorno'>";
	geraTabela($largura,$colunas,$dados, "margin-top:0px;border:0px solid silver;margin-bottom:0px;", 'cahamados-relatorio-resumo', 1, 1, '','');
	echo "		</div>
			</div>";
	


?>