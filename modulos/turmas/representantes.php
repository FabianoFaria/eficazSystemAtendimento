<?php
	add_action( 'admin_init', 'metabox_representantes_detalhes' );
	function metabox_representantes_detalhes() {
		add_meta_box( 'representantes_detalhes', 'Dados Principais', 'representantesDados', 'representantes', 'normal');
		function representantesDados(){
			global $post;
			wp_enqueue_script('ma_script_js', plugin_dir_url(__FILE__).'javascript/functions.js', array('jquery'));
			wp_enqueue_style('ma_estilo_css', plugin_dir_url(__FILE__).'css/estilos.css');
			$cadastros = get_post_meta($post->ID, 'representantes');
			$texto .= "	<table width='100%'>
							<tr>
								<td>
									<div>
										<div Style='width:15%;float:left;'>
											&nbsp;C&oacute;digo:<br><input type='text' name='txtCodigoRepresentante' id='txtCodigoRepresentante'  Style='width:95%;' value='".$post->ID."' readonly>
										</div>

										<div Style='width:65%;float:left;'>
											&nbsp;Razao Social:<br><input type='text' name='txtRazao' id='txtRazao' class='required' Style='width:99%;' value='".$cadastros[0][dadosPrincipais][razaoSocial]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:20%;float:left;'>
											&nbsp;CNPJ:<br><input type='text' name='txtCnpj' Style='width:100%' value='".$cadastros[0][dadosPrincipais][cnpj]."' onkeypress='mascaraCampo(this)'>
										 </div>
									</div>
									<div Style='margin-top:-2px;'>
										<div Style='width:25%;float:left;'>
											&nbsp;Inscricao Estatual:<br><input type='text' name='txtIE' Style='width:97%' value='".$cadastros[0][dadosPrincipais][ie]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:25%;float:left;'>
											&nbsp;Telefone1:<br><input type='text' name='txtFone1' Style='width:97%' value='".$cadastros[0][dadosPrincipais][fone1]."' onkeypress='mascaraCampo(this)'>
										 </div>
										<div Style='width:25%;float:left;'>
											&nbsp;Telefone 2:<br><input type='text' name='txtFone2' Style='width:97%' value='".$cadastros[0][dadosPrincipais][fone2]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:25%;float:left;'>
											&nbsp;Telefone 3:<br><input type='text' name='txtFone2' Style='width:100%' value='".$cadastros[0][dadosPrincipais][fone2]."' onkeypress='mascaraCampo(this)'>
										</div>
									</div>
									<div Style='margin-top:-2px;'>
										<div Style='width:35%;margin-top:5px;float:left;margin-bottom:5px;'>
											&nbsp;E-mail:<br><input type='text' name='txtEmail' id='txtEmail' Style='width:98.5%' class='required' value='".$cadastros[0][dadosPrincipais][email]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:15%;margin-top:5px;float:left;margin-bottom:5px;'>
											&nbsp;Senha:<br><input type='password' name='txtSenha' id='txtSenha' Style='width:95%' class='required' value='".$cadastros[0][dadosPrincipais][senha]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:50%;margin-top:5px;float:left;margin-bottom:5px;'>
											&nbsp;Endere&ccedil;o do Site:<br><input type='text' name='txtSite' id='txtSite' Style='width:100%' class='required' value='".$cadastros[0][dadosPrincipais][site]."' onkeypress='mascaraCampo(this)'>
										</div>
									</div>
								</td>
							</tr>
						</table>";
			echo $texto;
		}
	}


	add_action( 'admin_init', 'metabox_representantes_enderecos' );
	function metabox_representantes_enderecos() {
		add_meta_box( 'representantes_enderecos', 'Endere&ccedil;o', 'representantesEnderecos', 'representantes', 'normal');
		function representantesEnderecos(){
			global $post;
			$cadastros = get_post_meta($post->ID, 'representantes');
			$texto .= "	<table width='100%'>
							<tr>
								<td>
									<div id='endereco-dados'>
										<div Style='width:10%;float:left;'>
											&nbsp;Cep	<br><input style='width:95%' type='text' name='txtCep' id='txtCep' maxlength='9' value='".$cadastros[0][endereco][cep]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:60%;float:left;min-width:178px;'>
											&nbsp;Rua<br><input style='width:99%' type='text' name='txtLogradouro' value='".$cadastros[0][endereco][logradouro]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:10%;float:left;'>
											&nbsp;N&uacute;mero<br><input style='width:95%;' type='text' name='txtNumero'  maxlength='5' value='".$cadastros[0][endereco][numero]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:20%;float:left;min-width:100px;'>
											&nbsp;Complemento<br><input style='width:100%;min-width:96px;' type='text' name='txtComplemento'  maxlength='50' value='".$cadastros[0][endereco][complemento]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:25%;float:left;'>
											&nbsp;Bairro<br><input style='width:98%' type='text' name='txtBairro'  maxlength='100' value='".$cadastros[0][endereco][bairro]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:35%;float:left;'>
											&nbsp;Cidade<br><input style='width:98%' type='text' name='txtCidade'  maxlength='100' value='".$cadastros[0][endereco][cidade]."' onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:10%;float:left;'>
											&nbsp;Estado<br><input style='width:97%' type='text' name='txtUF'  maxlength='100' value='".$cadastros[0][endereco][uf]."'  onkeypress='mascaraCampo(this)'>
										</div>
										<div Style='width:30%;float:left;'>
											&nbsp;Refer&ecirc;ncia<br><input style='width:100%' type='text' name='txtReferencia'  maxlength='50' value='".$cadastros[0][endereco][referencia]."'  onkeypress='mascaraCampo(this)'>
										</div>
									</div>
								</td>
							</tr>
						</table>";
			echo $texto;
		}
	}

	add_action( 'admin_init', 'metabox_representantes_pagamento' );
	function metabox_representantes_pagamento() {
		add_meta_box( 'representantes_pagamento', 'Dados para Cobran&ccedil;a', 'representantes_pagamento', 'representantes', 'normal');
		function representantes_pagamento(){
			global $post;

			$dadosPgtoRepresentante = get_post_meta($post->ID, 'representantes_pagamento');
			$dadosPgtoRepresentante = $dadosPgtoRepresentante[0];
			$dadosPgtoRepresentante = unserialize($dadosPgtoRepresentante);
			$bancoSelecionado["B".$dadosPgtoRepresentante[boleto][banco]] = "selected";

			$texto .= "	<table width='100%'>
							<tr>
								<td><h3>Dados cobran&ccedil;a CIELO</td>
							</tr>
						</table>

						<table width='98%' align='center'>
							<tr>
								<td width='090'>Chave da loja:</td>
								<td width='165'><input type='text' name='txtCodigo' id='txtCodigo' style='width:150px;' onkeypress='mascaraCampo(this)' maxlength='200' value='".$dadosPgtoRepresentante[cielo][loja]."'></td>
								<td width='075'>Chave Cielo:</td>
								<td><input type='text' name='txtChave' id='txtChave' style='width:100%;' onkeypress='mascaraCampo(this)' maxlength='200' value='".$dadosPgtoRepresentante[cielo][chave]."'></td>
							</tr>
						</table>";

			$texto .= "	<table width='100%'>
							<tr>
								<td><h3>Dados cobran&ccedil;a Boleto</td>
							</tr>
						</table>

						<table width='98%' align='center'>
							<tr>
								<td width='30'>Banco:</td>
								<td>
									<select name='slcBanco' id='slcBanco' style='margin-top:3px;width:98%;'>
										<option value=''>Selecione:</option>
										<option value='001' ".$bancoSelecionado[B001].">Banco do Brasil</option>
										<option value='237' ".$bancoSelecionado[B237].">Bradesco</option>
										<option value='104' ".$bancoSelecionado[B104].">Caixa Economica</option>
										<option value='399' ".$bancoSelecionado[B399].">Hsbc</option>
										<option value='341' ".$bancoSelecionado[B341].">Ita&uacute;</option>
										<option value='033' ".$bancoSelecionado[B033].">Santander</option>
									</select>
								</td>
								<td width='045'>Ag&ecirc;ncia:</td>
								<td width='060'><input type='text' name='txtAgencia' id='txtAgencia' style='width:045px;' onkeypress='mascaraVal(this)' maxlength='6' value='".$dadosPgtoRepresentante[boleto][agencia]."'></td>
								<td width='25'>C/C:</td>
								<td width='080'><input type='text' name='txtContaCorrente' id='txtContaCorrente' style='width:068px;' onkeypress='mascaraCampo(this)' maxlength='12' value='".$dadosPgtoRepresentante[boleto][conta]."'></td>
								<td width='040'>D&iacute;gito:</td>
								<td width='040'><input type='text' name='txtDigito' id='txtDigito' style='width:025px;' onkeypress='mascaraCampo(this)' maxlength='12' value='".$dadosPgtoRepresentante[boleto][digito]."'></td>
								<td width='085'>C&oacute;d Cedente:</td>
								<td width='100'><input type='text' name='txtCodCedente' id='txtCodCedente' style='width:100%;' onkeypress='mascaraCampo(this)' maxlength='12' value='".$dadosPgtoRepresentante[boleto][cedente]."'></td>

							</tr>
						</tabe>
						<table width='98%' align='center'>
							<tr>
								<td width='040'>Raz&atilde;o:</td>
								<td><input type='text' name='txtRazaoSocial' id='txtRazaoSocial' style='width:98%' onkeypress='mascaraCampo(this)' maxlength='120' value='".$dadosPgtoRepresentante[boleto][razao]."'></td>
								<td width='030'>Cnpj:</td>
								<td width='145'><input type='text' name='txtNumeroCnpj' id='txtNumeroCnpj' style='width:100%;' onkeypress='mascaraVal(this)' onblur='validaCNPJ(this.value, this)' maxlength='14' value='".$dadosPgtoRepresentante[boleto][cnpj]."'></td>
							</tr>
						</table>
						<table width='98%' align='center'>
							<tr>
								<td width='190'>Conv&ecirc;nio Com&eacute;rcio Eletr&ocirc;nico:</td>
								<td><input type='text' name='txtComercioEletronico' id='txtComercioEletronico' style='width:92%' onkeypress='mascaraVal(this)' maxlength='10' value='".$dadosPgtoRepresentante[boleto][comercioeletronico]."'></td>
								<td width='145'>Conv&ecirc;nio de Cobran&ccedil;a:</td>
								<td><input type='text' name='txtConvenioCobranca' id='txtConvenioCobranca' style='width:100%;' onkeypress='mascaraVal(this)' maxlength='7' value='".$dadosPgtoRepresentante[boleto][conveniocobranca]."'></td>
							</tr>
						</table>";
			echo $texto;
		}
	}

	add_action( 'admin_init', 'metabox_representantes_regioes' );
	function metabox_representantes_regioes() {
		add_meta_box( 'representantes_regioes', 'Regi&atilde;o Atendida', 'representantes_regioes', 'representantes', 'side');
		function representantes_regioes(){
			global $post;
			$cadastros = get_post_meta($post->ID, 'representantes');
			$texto .= ufsDisponiveis();
			echo $texto;
		}
	}




	function ufsDisponiveis(){
		global $ufSelecionado, $cidadesSelecionadas, $post;

		$rs = mysql_query("select meta_value from wp_postmeta where meta_key = 'representantes_regiao' and post_id <> $post->ID");
		while ($row = mysql_fetch_row($rs)){
			$ufsUtilizadas = unserialize(unserialize($row[0]));
			$cidadesUtilizadas = $ufsUtilizadas['cidade'];
			foreach($ufsUtilizadas['uf'] as $ufUtilizada)
				if($ufUtilizada != ""){
					$ufDesabilitada[$ufUtilizada]		 		= "disabled";
					$ufDesabilitadaEsconde[$ufUtilizada] 		= "display:none;";
				}
			$rs = mysql_query("select distinct UF from cidades_ufs where uf <> '' order by uf");
			while ($row = mysql_fetch_row($rs)){
				foreach($ufsUtilizadas[cidade][$row[0]] as $cidadeDesabilita)
				if(trim($cidadeDesabilita) != ""){
					$ufCidadeDesabilita[$row[0]][str_replace(' ','_',$cidadeDesabilita)]			= "disabled";
					$ufCidadeDesabilitaEsconde[$row[0]][str_replace(' ','_',$cidadeDesabilita)] 	= "display:none;";
					$cidadeSelecionadaUf[$row[0]] = "1";
				}
			}
		}
		$regioes = get_post_meta($post->ID, 'representantes_regiao');
		$regioes = unserialize($regioes[0]);
		$ufs = $regioes[uf];
		$cidades = $regioes[cidade];
		foreach($ufs as $uf)
			$ufSelecionado[$uf] = "checked";


		$texto .= "<div style='margin-top:5px;border:0px solid red;text-align:left;height:397px;overflow:auto;'>";
		$rs = mysql_query("select distinct UF from cidades_ufs where uf <> '' order by uf");
		while ($row = mysql_fetch_row($rs)){
			$i++;

			foreach($cidades[$row[0]] as $cidade)
				$cidadesSelecionadas[$row[0]][str_replace(' ','',$cidade)] = "checked";

			$texto .= "	<script>
							function mostraCidades(posicao){
								if(document.getElementById('hdSitUf'+posicao).value == 0){
									document.getElementById('divUf'+posicao).style.display = 'block';
									document.getElementById('hdSitUf'+posicao).value = 1;
								}else{
									document.getElementById('divUf'+posicao).style.display = 'none';
									document.getElementById('hdSitUf'+posicao).value = 0;
								}
							}
							</script>";

			$texto .= "<div style='margin-top:5px;border:0px solid red;text-align:left;".$ufDesabilitadaEsconde[$row[0]]."'>
							&nbsp;
							<a href='javascript:mostraCidades($i)' Style='text-decoration:none;".$ufDesabilitadaEsconde[$row[0]]."'><b>+</b></a>
							<input type='checkbox' class='representante-uf'  posicao='$i' name='chkUf$i' id='chkUf$i' onclick=\"validaSelecionado($i)\"  value='$row[0]' ".$ufSelecionado[$row[0]]." ".$ufDesabilitada[$row[0]]."/>
							<input type='hidden' name='hdCidadesSelUf-$i' id='hdCidadesSelUf-$i' value='".$cidadeSelecionadaUf[$row[0]]."'>
							<label for='chkUf$i'>$row[0]</label>
							<div id='divUf$i' Style='border:0px solid silver;min-height:20px;margin-left:32px;display:none;'>
								<input type='hidden' name='hdSitUf$i' id='hdSitUf$i' value='0'>";
			$k=0;
			$uf = mysql_query("select distinct Cidade from cidades_ufs where UF = '$row[0]' order by Cidade");
			while ($rowUf = mysql_fetch_row($uf)){
				$j++;
				$k++;
				$ufS		= $row[0];
				$cidadeS	= str_replace(' ','',utf8_encode($rowUf[0]));
				$cidadeY	= str_replace(' ','_',utf8_encode($rowUf[0]));
				$city 		= utf8_encode($rowUf[0]);
				$texto .= '		<div Style="'.$ufCidadeDesabilitaEsconde[$ufS][$cidadeY].'"><input type="checkbox" class="representante-cidade-'.$i.' checked-cidade" posicao='.$i.' name="chkCidade'.$i.'_'.$k.'" id="chkCidade'.$i.$k.'" onclick="retiraSelecao('.$i.')"  value="'.$row[0].'|'.$city.'" '.$cidadesSelecionadas[$ufS][$cidadeS].' '.$ufCidadeDesabilita[$ufS][$cidadeY].' />
								<label for="chkCidade'.$i.$k.'" Style="'.$ufCidadeDesabilitaEsconde[$ufS][$cidadeY].'">'.$city.'</label><br></div>';
			}
			$texto .= "			<input type='hidden' name='txtTotalCidade$i'  id='txtTotalCidade$i' value='$k'>
								<input type='hidden' name='txtCidadeSelect$i' id='txtCidadeSelect$i' value='0'>
							</div>
						</div>";
		}
		$texto .= "	</div>";
		$texto .= "<input type='hidden' name='hdTotalUf' 	 value='$i'>";
		$texto .= "<input type='hidden' name='hdTotalCidade' value='$j'>";
		echo $texto;
	}

	add_action( 'save_post', 'regioesAtendidasSave' );
	function regioesAtendidasSave(){
		global $post;
		for($i=1;$i<=$_POST['hdTotalUf'];$i++){
			$uf[$i] = $_POST['chkUf'.$i];
			$j=0;
			$l=0;
			$totalCidade = $_POST['txtTotalCidade'.$i];
			for($j=1;$j<=$totalCidade;$j++){
				if($_POST['chkCidade'.$i."_".$j] != ""){
					$l++;
					$ufCidade = substr($_POST['chkCidade'.$i."_".$j], 0,2);
					$cidade[$ufCidade][$l] = substr($_POST['chkCidade'.$i."_".$j],3,100);
				}
			}
		}
		$dados[uf]		= $uf;
		$dados[cidade]	= $cidade;
		$dados = serialize($dados);
		update_post_meta($post->ID,'representantes_regiao',$dados);
	}


	add_action( 'save_post', 'dadosPrincipaisSave' );
	function dadosPrincipaisSave(){
		global $post;
		$cadastro[dadosPrincipais]['razaoSocial']	= $_POST['txtRazao'];
		$cadastro[dadosPrincipais]['cnpj']			= $_POST['txtCnpj'];
		$cadastro[dadosPrincipais]['ie']			= $_POST['txtIE'];
		$cadastro[dadosPrincipais]['fone1']			= $_POST['txtFone1'];
		$cadastro[dadosPrincipais]['fone2']			= $_POST['txtFone2'];
		$cadastro[dadosPrincipais]['email']			= $_POST['txtEmail'];
		$cadastro[dadosPrincipais]['nome']			= $_POST['txtNome'];
		$cadastro[dadosPrincipais]['site']			= $_POST['txtSite'];
		$cadastro[dadosPrincipais]['funcionarios']	= $_POST['txtFuncionarios'];
		$cadastro[dadosPrincipais]['senha']			= $_POST['txtSenha'];


		$cadastro[endereco]['cep']					= $_POST['txtCep'];
		$cadastro[endereco]['logradouro']			= $_POST['txtLogradouro'];
		$cadastro[endereco]['numero']				= $_POST['txtNumero'];
		$cadastro[endereco]['complemento']			= $_POST['txtComplemento'];
		$cadastro[endereco]['bairro']				= $_POST['txtBairro'];
		$cadastro[endereco]['cidade']				= $_POST['txtCidade'];
		$cadastro[endereco]['uf']					= $_POST['txtUF'];
		$cadastro[endereco]['referencia']			= $_POST['txtReferencia'];

		if($cadastro[dadosPrincipais]['razaoSocial'] != "")
			update_post_meta($post->ID, 'representantes', $cadastro);

		$codRepresentante = $post->ID*-1;
		$representante = mysql_query("select Cadastro_ID from cadastros_dados where Cadastro_ID = $codRepresentante");
		if($row = mysql_fetch_row($representante)){
			mysql_query("update cadastros_dados set Nome = '".$_POST['txtRazao']."',Senha = '".$_POST['txtSenha']."',Email = '".$_POST['txtEmail']."'where Cadastro_ID = $codRepresentante");
		}else{
			mysql_query("insert into cadastros_dados(Cadastro_ID,Nome,Senha,Email,Grupo_ID, Situacao_ID)values('$codRepresentante','".$_POST['txtRazao']."','".$_POST['txtSenha']."','".$_POST['txtEmail']."','3','-1')");

		}
	}

	add_action( 'save_post', 'dadosPagamentoSave' );
	function dadosPagamentoSave(){
		global $post;

		$dadosPgto[cielo][loja]	 = $_POST['txtCodigo'];
		$dadosPgto[cielo][chave] = $_POST['txtChave'];

		$dadosPgto[boleto][banco]	= $_POST['slcBanco'];
		$dadosPgto[boleto][agencia]	= $_POST['txtAgencia'];
		$dadosPgto[boleto][conta]	= $_POST['txtContaCorrente'];
		$dadosPgto[boleto][digito]	= $_POST['txtDigito'];
		$dadosPgto[boleto][razao]	= $_POST['txtRazaoSocial'];
		$dadosPgto[boleto][cnpj]	= $_POST['txtNumeroCnpj'];
		$dadosPgto[boleto][cedente]	= $_POST['txtCodCedente'];

		$dadosPgto[boleto][comercioeletronico]	= $_POST['txtComercioEletronico'];
		$dadosPgto[boleto][conveniocobranca]	= $_POST['txtConvenioCobranca'];

		if(($dadosPgto['cielo']['loja'] != "")&&($dadosPgto[boleto][banco] != ""))
			update_post_meta($post->ID, 'representantes_pagamento', serialize($dadosPgto));
	}
?>