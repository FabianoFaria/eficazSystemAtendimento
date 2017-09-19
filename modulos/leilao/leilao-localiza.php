<?php
	/*
	if($_POST){
		$tipoNF = $_POST['localiza-tipo-nf'];
		if ($tipoNF=='produto') $selecionadoProduto = "selected";
		if ($tipoNF=='servico') $selecionadoServico = "selected";

		$empresa = $_POST['localiza-empresa'];
		$serieNF = $_POST['localiza-serie-nf'];
		$numeroNF = $_POST['localiza-numero-nf'];
		$empresa = $_POST['localiza-empresa'];
		$contaID = $_POST['localiza-conta'];
		$dataInicioEmissao = $_POST['localiza-data-inicio-emissao'];
		$dataFimEmissao = $_POST['localiza-data-fim-emissao'];
	}
	else{

	}
	*/
	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo' style='min-height:25px'>
						<p style='margin-top:2px;'>
							Filtros de Pesquisa
							<input type='button' class='leilao-inc-alt' leilao-id='' value='Incluir Novo'/>
						</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Leilão ID</p>
							<p><input type='text' name='localiza-leilao-id' id='localiza-leilao-id' class='formata-numero' value='".$_POST['localiza-leilao-id']."' style='width:80%;'></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:40%'>
							<p>Título</p>
							<p><input type='text' name='localiza-titulo-leilao' id='localiza-titulo-leilao' value='".$_POST['localiza-titulo-leilao']."' style='width:80%;'></p>
						</div>
						<!--
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Tipo</p>
							<p><select id='localiza-empresa' name='localiza-empresa' style='width:98%'><option>&nbsp;</option>".optionValueEmpresasNF($empresa)."</select></p>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>N&uacute;mero</p>
							<p><input type='text' id='localiza-numero-nf' name='localiza-numero-nf' class='formata-numero'  value='".$numeroNF."' style='width:90%'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Conta</p>
							<p><input type='text' id='localiza-conta' name='localiza-conta' class='formata-numero'  value='".$contaID."' style='width:90%'/></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:25%'>
							<p>Empresa</p>
							<p><select id='localiza-empresa' name='localiza-empresa' style='width:98%'><option>&nbsp;</option>".optionValueEmpresasNF($empresa)."</select></p>
						</div>
						<div class='titulo-secundario' style='float:left;width:20%'>
							<p>Data Emiss&atilde;o:</p>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='localiza-data-inicio-emissao' id='localiza-data-inicio-emissao' class='formata-data' style='width:92%' maxlength='10' value='$dataInicioEmissao'>&nbsp;&nbsp;</p>
							</div>
							<div style='width:10%;text-align:center;float:left;'><p>a</p></div>
							<div style='width:43%;float:left;'>
								<p><input type='text' name='localiza-data-fim-emissao' id='localiza-data-fim-emissao' class='formata-data' style='width:92%' maxlength='10' value='$dataFimEmissao'></p>
							</div>
						</div>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Situa&ccedil;&atilde;o</p>
							<p>
								<select name='localiza-situacao' id='localiza-situacao'>
									<option value=''></option>
									<option value='a'>Aprovadas</option>
									<option value='r'>Reprovadas</option>
									<option value='c'>Canceladas</option>
								</select>
							</p>
						</div>
						-->
						<div style='float:right;width:10%'>
							<div Style='margin-top:15px;'>
								<p align='right'><input type='button' value='Pesquisar' id='botao-pesquisar-leiloes' style='width:120px'/></p>
							</div>
						</div>						
					</div>
				</div>
			</div>
			<input type='hidden' id='leilao-id' name='leilao-id' value=''>";
	//if($_POST){
	
			/*
			if ($serieNF != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
			if ($numeroNF != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
			if ($empresa != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}
			if ($contaID != ""){ $sqlCond .= " and cd.Cadastro_ID = '$id' ";}

			if(($dataInicioEmissao!="")||($dataFimEmissao!="")){
				$dataInicioEmissao = implode('-',array_reverse(explode('/',$dataInicioEmissao)));
				if ($dataInicioEmissao=="") $dataInicioEmissao = "0000-00-00"; $dataInicioEmissao .= " 00:00";
				$dataFimEmissao = implode('-',array_reverse(explode('/',$dataFimEmissao)));
				if ($dataFimEmissao=="") $dataFimEmissao = "2100-01-01"; $dataFimEmissao .= " 23:59";
				$sqlCond .= " and fc.Data_Cadastro between '$dataInicioEmissao' and '$dataFimEmissao' ";
			}

			if($_POST['ordena-tabela'] != ""){
				$sqlOrdem = " order by ".$_POST['ordena-tabela']." ".$_POST['ordena-tipo'];
			}else{
				$sqlOrdem = " order by n.NF_ID";
			}
			*/

		$sql = "SELECT Leilao_ID, Empresa_ID, Titulo, Descricao, Data_Leilao, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro 
					FROM leiloes_dados ld
						where ld.Leilao_ID > 0
						$sqlCond
						$sqlOrdem";
		//echo $sql;
		$resultado = mpress_query($sql);
		while ($rs = mpress_fetch_array($resultado)){
			$i++;
			$dados[colunas][conteudo][$i][1] = "<span style='float:right; margin-right:15px;' class='link leilao-inc-alt' leilao-id='".$rs[Leilao_ID]."'>".$rs[Leilao_ID]."</span>";
			$dados[colunas][conteudo][$i][2] = $rs[Titulo];
			$dados[colunas][conteudo][$i][3] = converteDataHora($rs[Data_Leilao],1);
			$dados[colunas][conteudo][$i][4] = $rs[Situacao_ID];
			$dados[colunas][conteudo][$i][5] = converteDataHora($rs[Data_Cadastro],1);
		}
		$largura = "100%";
		$colunas = "5";

		$dados[colunas][titulo][1] 	= "Leil&atilde;o ID";
		$dados[colunas][titulo][2] 	= "T&iacute;tulo";
		$dados[colunas][titulo][3] 	= "Data Leil&atilde;o";
		$dados[colunas][titulo][4] 	= "Situa&ccedil;&atilde;o";
		$dados[colunas][titulo][5] 	= "Data Inclus&atilde;o";

		$dados[colunas][tamanho][1] 	= "width='5%'";

		echo "	<div class='titulo-container'>
					<div class='titulo'>
						<p>Registro localizados: $i</p>
					</div>
					<div class='conteudo-interno'>";
		geraTabela($largura,$colunas,$dados, null, 'leilao-localiza', 2, 2, 100,1);
		echo "		</div>
				 </div>";
//	}
?>