<?php
	session_start();
	global $dadosUserLogin;
	$paginaInicial = mpress_fetch_array(mpress_query("select descr_tipo from tipo where Tipo_ID = 6"));
	$dadosPagina = unserialize($paginaInicial[0]);

	$sel[$dadosPagina[$dadosUserLogin['userID']]] = "checked";
	//if($pag[Modulo_Pagina_ID]==) $checked = "checked"; else $checked = "";
	$strAcesso = "";
	if($dadosUserLogin['userID'] != -1){
		$grupos = mpress_query("select acessos from modulos_acessos where Modulo_Acesso_ID = ".$dadosUserLogin['grupoID']." and Situacao_ID = 1");
		if($row = mpress_fetch_array($grupos)){
			$acessos = unserialize($row[0]);
			$leitura = $acessos[leitura];
			$gravacao = $acessos[gravacao];
			for($y=0;$y<count($leitura);$y++) $paginas .= $leitura[$y].",";
			for($z=0;$z<count($gravacao);$z++) $paginas .= $gravacao[$z].",";
		}
		$strAcesso = "and mp.Modulo_Pagina_ID in (".$paginas."0)";
	}

	$sql = "select distinct m.Modulo_ID, m.Nome, m.Descricao from modulos m
						inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
						where m.Situacao_ID = 1 and m.Modulo_ID > 0 and m.Slug <> 'administrativo'
						$strAcesso
						order by m.Posicao";
	$modulos = mpress_query($sql);
	while($row = mpress_fetch_array($modulos)){
		$i++;
		$dados[colunas][extras][$i][1] = " valign='top' ";
		$dados[colunas][extras][$i][2] = " valign='top' ";
		$dados[colunas][conteudo][$i][1] = "<p style='margin-left:10px;'><b>".$row[Nome]."</b></p>";
		$dados[colunas][conteudo][$i][2] = "<p style='margin-left:10px;'>".$row[Descricao]."</p>";
		$paginas = mpress_query("select Modulo_Pagina_ID, Titulo from modulos_paginas mp where Situacao_ID = 1 and Modulo_ID = ".$row[Modulo_ID]."  and (Pagina_Pai_ID = '' or Pagina_Pai_ID is null) $strAcesso order by Posicao");
		while($pag = mpress_fetch_array($paginas)){
			$dados[colunas][conteudo][$i][3] .= "<input type='radio' name='rdPaginas' class='configurar-pagina-inicial' id='".$pag[Modulo_Pagina_ID]."' ".$sel[$pag[Modulo_Pagina_ID]]."><label for='".$pag[Modulo_Pagina_ID]."'>".$pag[Titulo]."</label><br>";
			$paginas2 = mpress_query("select Modulo_Pagina_ID, Titulo from modulos_paginas mp where Situacao_ID = 1 and Pagina_Pai_ID = '".$pag[Modulo_Pagina_ID]."' $strAcesso order by Posicao");
			while($pag2 = mpress_fetch_array($paginas2)){
				$dados[colunas][conteudo][$i][3] .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='radio' name='rdPaginas' class='configurar-pagina-inicial' id='".$pag2[Modulo_Pagina_ID]."' ".$sel[$pag2[Modulo_Pagina_ID]]."><label for='".$pag2[Modulo_Pagina_ID]."'>".$pag2[Titulo]."</label><br>";
			}
		}
	}

	$largura = "99%";
	$colunas = "3";

	$dados[colunas][tamanho][1] = "width='20%'";
	$dados[colunas][tamanho][2] = "width='30%'";

	$dados[colunas][titulo][1] = "Módulo";
	$dados[colunas][titulo][2] = "Descrição";
	$dados[colunas][titulo][3] = "Páginas <span Style='float:right;font-weight:normal'><input type='radio' name='rdPaginas' class='configurar-pagina-inicial' id='' checked> Não atribuir página inicial</span>";

	geraTabela($largura,$colunas,$dados);
?>