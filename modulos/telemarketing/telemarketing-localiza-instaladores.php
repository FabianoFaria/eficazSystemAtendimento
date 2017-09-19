<?php
$dadospagina = get_page_content();
echo "	<div id='cadastros-container'>
			<div class='titulo-container'>
				<div class='titulo'>
					<p>Localizar Instaladores</p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario' style='float:left;width:120px'>
						<p>CEP</p>
						<p><input type='text' id='localiza-instalador-cep' name='localiza-instalador-cep' maxlength='9' style='width:95%' value='".$_POST['localiza-instalador-cep']."'/></p>
					</div>
					<div class='titulo-secundario' style='float:left;width:10px;margin-top:15px;margin-left:15px; float:left;'>
						<input type='button' Style='width:140px;' value='Localizar Instalador' id='botao-localizar-instalador'></p>
					</div>
				</div>
			</div>
		</div>";
$cep = formataCepInstalador(str_replace('.','',str_replace('-','',$_POST['localiza-instalador-cep'])));
if(($_POST)&&($cep!="")){
	$i=0;
	$instaladores = "http://sgc.brasilsat.com.br/services/BuscaInstalador?method=buscaPorCEP&CEP=$cep";
	$ch = curl_init($instaladores);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	$data = curl_exec($ch);
	curl_close($ch);
	$strPosIni 	= strpos($data,"&lt;instaladores&gt;");
	$strPosFim 	= strpos($data,"&lt;/instaladores&gt;");
	$data = substr($data, $strPosIni, ($strPosFim-$strPosIni)+21);
	$data = str_replace('&lt;','<',$data);
	$data = str_replace('&gt;','>',$data);
	$xml = simplexml_load_string($data);
	$cont = 0;
	foreach($xml->instalador as $instalador){
		if ($instalador->nome!=""){
			$i++;
			$enderecoRepresentante = $instalador->endereco."&nbsp;&nbsp;&nbsp;".$instalador->cidade.", ".$instalador->estado.", CEP ".$cep;
			$dados[colunas][conteudo][$i][1] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$instalador->nome."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$instalador->telefone."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$enderecoRepresentante."</p>";
		}
	}
	$largura = "99%";
	$colunas = "3";
	$dados[colunas][titulo][1] 	= "Instalador";
	$dados[colunas][titulo][2] 	= "Telefone";
	$dados[colunas][titulo][3] 	= "Endere&ccedil;o";

	$dados[colunas][tamanho][1] = "width=''";
	$dados[colunas][tamanho][2] = "width=''";
	$dados[colunas][tamanho][3] = "width=''";

	geraTabela($largura,$colunas,$dados);
	if($i==0){
		echo "	<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum instalador localizado</p>";
	}
	//else{
	//	echo "	<p Style='margin:15px 5px 0px 15px;color:red; text-align:center'><input type='button' id='enviar-instalador-email' value='Enviar resultado da pesquisa por E-mail'/></p>";
	//}

}

function formataCepInstalador($cep){
	if ($cep!=""){
		$cep = str_replace(".","",str_replace("-","",$cep));
		$cep5 = substr($cep, 0,5);
		$cep3 = substr($cep, -3);
		$cep  = substr($cep5,0,2).".".substr($cep5,-3)."-".$cep3;
	}
	return $cep;
}
?>

