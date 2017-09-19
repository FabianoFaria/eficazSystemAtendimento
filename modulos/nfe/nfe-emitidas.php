<?php
	include('functions.php');
	if($_POST){
		$tipoNF = $_POST['localiza-tipo-nf'];
		$selAmbiente[$_POST['localiza-ambiente-nf']] = ' selected ';

		$empresa = $_POST['localiza-empresa'];
		$serieNF = $_POST['localiza-serie-nf'];
		$numeroNF = $_POST['localiza-numero-nf'];
		$empresa = $_POST['localiza-empresa'];
		$contaID = $_POST['localiza-conta'];
		$dataInicioEmissao = $_POST['localiza-data-inicio-emissao'];
		$dataFimEmissao = $_POST['localiza-data-fim-emissao'];
	}
	else{
		$selAmbiente[1] = ' selected ';
	}
	if ($tipoNF=='produto') $selecionadoProduto = " selected ";
	if ($tipoNF=='servico') $selecionadoServico = " selected ";

	//print_r($configFinanceiro);

	echo "	<div id='cadastros-container'>
				<div class='titulo-container'>
					<div class='titulo' style='min-height:25px'>
						<p style='margin-top:2px;'>
							Filtros de Pesquisa
						</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Ambiente</p>
							<p>
								<select name='localiza-ambiente-nf' id='localiza-tipo-nf'>]
									<option value='2' ".$selAmbiente[2].">Homologação</option>
									<option value='1' ".$selAmbiente[1].">Produção</option>-->
								</select>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left;width:10%'>
							<p>Tipo NF</p>
							<p>
								<select name='localiza-tipo-nf' id='localiza-tipo-nf'>]
									<option value='produto' $selecionadoProduto>NF-e</option>
									<!--<option value='servico' $selecionadoServico>NFS-e</option>-->
								</select>
							</p>
						</div>
						<div class='titulo-secundario' style='float:left;width:5%'>
							<p>S&eacute;rie</p>
							<p><input type='text' name='localiza-serie-nf' id='localiza-serie-nf' class='formata-numero' value='".$serieNF."' style='width:80%;'></p>
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
						<div class='titulo-secundario' style='float:right;width:10%'>
							<div Style='margin-top:15px;'>
								<p><input type='button' value='Pesquisar' style='width:100%' id='botao-pesquisar-nfe'/></p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<input type='hidden' id='localiza-conta-id' name='localiza-conta-id' value=''>";
	echo "	<input type='hidden' value='".$configFinanceiro['lancamento-fancybox']."' id='lancamento-fancybox'>";			
	//if($_POST){

		//if ($tipoNF=="produto"){
			//if ($_POST['localiza-ambiente-nf'] != ""){ $sqlCond .= " and n.Ambiente = '".$_POST['localiza-ambiente-nf']."' ";}
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

			$sql = "SELECT n.NF_ID, n.Numero_NF, n.Serie, n.Empresa_ID, em.Nome as Empresa, n.Chave_Acesso, n.Data_Emissao, emi.Nome as Usuario_Emitente,
							n.Conta_ID, c.Data_Cadastro as Data_Cancelamento, can.Nome as Usuario_Cancelamento,
							sol.Nome as Cliente
							from nf_dados n
							inner join cadastros_dados em on em.Cadastro_ID = n.Empresa_ID
							inner join financeiro_contas fc on fc.Conta_ID = n.Conta_ID
							inner join cadastros_dados sol on sol.Cadastro_ID = fc.Cadastro_ID_para
							left join nf_canceladas c on c.NF_ID = n.NF_ID and c.Erro = 0
							left join cadastros_dados emi on emi.Cadastro_ID = n.Usuario_Emissao_ID
							left join cadastros_dados can on can.Cadastro_ID = c.Usuario_Cadastro_ID
							where n.Conta_ID > 0
							$sqlCond
							$sqlOrdem";
			//echo $sql;
			$resultado = mpress_query($sql);
			while ($rs = mpress_fetch_array($resultado)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<p style='margin:0' align='center'>".str_pad($rs[Serie], 3, "0", STR_PAD_LEFT)."</p>";
				$dados[colunas][conteudo][$i][2] = "<center>".str_pad($rs[Numero_NF], 6, "0", STR_PAD_LEFT)."</center>";
				$dados[colunas][conteudo][$i][3] = $rs[Tipo];
				$dados[colunas][conteudo][$i][4] = $rs[Empresa];
				$dados[colunas][conteudo][$i][5] = $rs[Cliente];
				$dados[colunas][conteudo][$i][6] = $rs[Chave_Acesso];
				$dados[colunas][conteudo][$i][7] = "<span class='link localiza-conta' conta-id='".$rs['Conta_ID']."'>".$rs['Conta_ID']."</span>";
				$dados[colunas][conteudo][$i][8] = $rs[Usuario_Emitente];
				$dados[colunas][conteudo][$i][9] = converteDataHora($rs[Data_Emissao],1);
				$dados[colunas][conteudo][$i][10] = $rs[Usuario_Emitente];
				$dados[colunas][conteudo][$i][11] = "<div class='btn-xml' style='height:20px'>&nbsp;</div>";
			}
			$largura = "100%";
			$colunas = "11";

			$dados[colunas][titulo][1] 	= "S&eacute;rie";
			$dados[colunas][titulo][2] 	= "<center>N&uacute;mero</center>";
			$dados[colunas][titulo][3] 	= "Tipo";
			$dados[colunas][titulo][4] 	= "Empresa";
			$dados[colunas][titulo][5] 	= "Cliente";
			$dados[colunas][titulo][6] 	= "Chave";
			$dados[colunas][titulo][7] 	= "Conta";
			$dados[colunas][titulo][8] 	= "Usu&aacute;rio Emissor";
			$dados[colunas][titulo][9] 	= "Data Emiss&atilde;o";
			$dados[colunas][titulo][10] 	= "Situa&ccedil;&atilde;o";
			$dados[colunas][titulo][11] 	= "XML";

			$dados[colunas][tamanho][11] = "width='30px'";

			echo "	<div class='titulo-container'>
						<div class='titulo'>
							<p>Registro localizados: $i</p>
						</div>
						<div class='conteudo-interno'>";
			geraTabela($largura,$colunas,$dados, null, 'nfe-localiza', 2, 2, 100,1);
			echo "		</div>
					 </div>";

		//}
	//}
?>