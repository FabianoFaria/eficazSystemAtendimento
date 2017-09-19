<?php
	include("../../config.php");
	echo "<div id='gerencia-modulo-retorno' Style='color:#d54e21;font-weight:bold;;padding:3px;'></div>";

	$dir = "./modulos/";
	$dh  = opendir($dir);
	while (false !== ($filename = readdir($dh)))
		if(substr($filename, -3) != "php")
			if(substr($filename, 0,1) != ".")
    			$files[] = $filename;
	closedir($dh);

	for($i=0;$i<=count($files);$i++){
		if($files[$i] != ""){
			$dir = "./modulos/".$files[$i];
			$dh  = opendir($dir);
			while (false !== ($filename = readdir($dh))){
				if($filename == "setup.php"){
					$dadosPlugin = fopen($dir."/setup.php", "r");
					while(!feof($dadosPlugin)){
						$linha = fgets($dadosPlugin);
						if(strpos($linha, ":") != ""){
							$nomeCampo = trim(substr($linha,0,strpos($linha, ":")));
							$descCampo = trim(substr($linha,strpos($linha, ":")+1, strlen($linha)));
							$detalhes[$files[$i]][$nomeCampo] = $descCampo;
						}
					 }
					fclose($file);
				}
			}
			closedir($dh);
		}
	}

	$resultado = mpress_query("select Modulo_ID, Slug, Posicao from  modulos m where Situacao_ID = 1");
	while($row = mpress_fetch_array($resultado)){
		$moduloID[$row[Slug]] = $row[Modulo_ID];
		$posicaoID[$row[Slug]] = $row[Posicao];
	}

	for($modulo=0;$modulo<=count($files);$modulo++){
		$arquivo = $files[$modulo];
		if($detalhes[$arquivo][Ativado] == ""){
			$ativar[$arquivo] 		= "<span id='modulo-$arquivo-ativar'><a href='#' id='modulo-$arquivo' class='link ativa-modulo'>Ativar</a></span>";
		}else{
			$detalhes[$arquivo][modulos] = "|&nbsp;&nbsp;Ativado em: ".$detalhes[$arquivo][Ativado];
			$desativar[$arquivo]		= " <span id='modulo-$arquivo-desativar'><a href='#' id='modulo-$arquivo' class='link desativa-modulo'>Desativar</a></span>&nbsp;
											<span class='atualizar-modulo link' slug='$arquivo' modulo-id='".$moduloID[$detalhes[$arquivo][Slug]]."'>Atualizar</span>&nbsp;
										    <span Style='float:right;'>Posição: <input type='text' Style='width:25px;height:12px;text-align:center;' class='modulo-posicao' id='modulo-posicao-".$moduloID[$detalhes[$arquivo][Slug]]."' value='".$posicaoID[$detalhes[$arquivo][Slug]]."'></span>";
		}
		if($detalhes[$arquivo][Titulo] != "")
			echo "	<table width='100%' Style='margin-top:0px;border:1px solid silver;margin-bottom:3px;' cellpadding='2' cellspacing='2'>
						<tr>
							<td align='left' width='300' Style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;font-weight:bold;background-color:#f9f9f9;'>&nbsp;".$detalhes[$arquivo][Titulo]."</td>
							<td align='left' Style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;background-color:#f9f9f9;'>".$detalhes[$arquivo][Descricao]."</td>
						</tr>
						<tr>
							<td align='left'  Style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;background-color:#f9f9f9;' id='acoes-modulo-$arquivo'>&nbsp;$ativar[$arquivo]$desativar[$arquivo]</td>
							<td align='left' Style='border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;background-color:#f9f9f9;'  id='versao-modulo-$arquivo'>Versão ".$detalhes[$arquivo][Versao]." (".$detalhes[$arquivo][Data].")&nbsp;&nbsp;<span id='data-ativacao-$arquivo'>".$detalhes[$arquivo][Ativado]."</span></td>
						</tr>
					</table>";
	}
?>