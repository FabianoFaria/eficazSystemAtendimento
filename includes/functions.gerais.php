<?php
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');

	function get_header(){
		global $caminhoSistema, $tituloSistema, $descricaoSistema, $dadosUserLogin, $caminhoFisico;
		$dadospagina = get_page_content();

		if($dadospagina[Titulo] != "")
			$tituloSistema = $tituloSistema." - ".$dadospagina[Titulo];

		echo "	<title>".$tituloSistema."</title>
				<meta name='description' content='$descricaoSistema.'>
				<meta http-equiv='content-type' content='text/html; charset=UTF-8'>
				<link rel='icon' href='$caminhoSistema/images/favicon/favicon.ico' type='image/x-icon'>
				<link rel='shortcut icon' href='$caminhoSistema/images/favicon/favicon.ico' type='image/x-icon'>
				<link rel='stylesheet' type='text/css' href='$caminhoSistema/css/global.css' />
				<link rel='stylesheet' type='text/css' href='$caminhoSistema/css/jquery.datetimepicker.css' >
				<link rel='stylesheet' type='text/css' href='$caminhoSistema/css/jquery.fancybox.css?v=2.1.5' media='screen' />
				<link rel='stylesheet' type='text/css' href='$caminhoSistema/css/help.css?v=1.0' media='screen' />
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery-1.8.1.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery-ui-1.10.4.custom.min.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.easyui.min.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/preload.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.upload.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.cadastro.geral.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.maskMoney.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.maskedinput.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.datetimepicker.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/plupload.full.min.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/tinymce/tinymce.min.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.fancybox.js?v=2.1.5'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/help.js?v=1.0'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/xcolor/js/modcoder_excolor/jquery.modcoder.excolor.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.plugin.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.countdown.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/jquery.countdown-pt-BR.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/chosen.jquery.min.js'></script>
				<script type='text/javascript' src='$caminhoSistema/javascript/alertify.min.js'></script>
				<script>var caminhoScript = '$caminhoSistema'; userID = '".$dadosUserLogin['userID']."';</script>";
		if(file_exists("$caminhoFisico/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".js"))
			echo "\n				<script type='text/javascript' src='$caminhoSistema/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".js'></script>";
		if(file_exists("$caminhoFisico/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".css"))
			echo "\n				<link rel='stylesheet' type='text/css' href='$caminhoSistema/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".css'>";
	}

	function get_topo(){
		global $dadosUserLogin,$caminhoSistema;
		$dadosPagina = get_page_content();
		$dadosMenuHelp['Nome']				= $dadosPagina['Nome'];
		$dadosMenuHelp['Titulo']			= $dadosPagina['Titulo'];
		$dadosMenuHelp['Titulo_Filho']		= $dadosPagina['Titulo_Filho'];
		$dadosMenuHelp['Slug_Pagina_Filho']	= $dadosPagina['Slug_Pagina_Filho'];
		$dadosMenuHelp['Slug_Pagina']		= $dadosPagina['Slug_Pagina'];
		echo "	<div id='topo-container'>
					<div id='topo-esquerdo'>";
		if(!empty($dadosUserLogin))
			echo "		<div id='btnhome'>
							<a href='$caminhoSistema/' >
								<img src='$caminhoSistema/images/topo/btn-home.png' height='53' width='86' id='imgHome' onmouseover=\"MM_swapImage('imgHome','','$caminhoSistema/images/topo/btn-home-hover.png',1)\" onmouseout='MM_swapImgRestore()' />
							</a>
						</div>
						<div id='btnsair'>
							<a href='$caminhoSistema/sair.php' >
								<img src='$caminhoSistema/images/topo/btn-sair.png' height='53' width='86' id='imgSair' onmouseover=\"MM_swapImage('imgSair','','$caminhoSistema/images/topo/btn-sair-hover.png',1)\" onmouseout='MM_swapImgRestore()' />
							</a>
						</div>
						<div id='btnhelp'>
							<a class='fancybox fancybox.iframe' href='$caminhoSistema/help.php?pagina=".serialize($dadosMenuHelp)."'>
								<img src='$caminhoSistema/images/topo/btn-ajuda.png' height='53' width='86' id='imgHelp' onmouseover=\"MM_swapImage('imgHelp','','$caminhoSistema/images/topo/btn-ajuda-hover.png',1)\" onmouseout='MM_swapImgRestore()' />
							</a>
						</div>";
		echo "		</div>
					<div id='topo-direito'>
					</div>
					<div id='topo-centro'>
						<p id='topo-login'>
							<a class='fancybox fancybox.iframe link' href='$caminhoSistema/funcoes/dados-basicos.php'>
								$dadosUserLogin[nome]
							</a>
						</p>
						<p id='topo-data'>".dataDia(getdate())."</p>
					</div>
				</div>";
	}

	function get_menu($paginaAtual){
		global $dadosUserLogin,$caminhoSistema;
		$dadosPagina = get_page_content();
		//if($dadosPagina['Slug_Pagina']!='pdv-inicio'){

		if(!empty($dadosUserLogin)){
			require_once("includes/functions.menu.php");
			$dadosMenu = geraDadosMenu();
			echo "	<div id='menu'>
						<div id='menu-ramificado-1' class='menu-ramificado menu-1'>".dadosMenuPrincipal($dadosMenu)."</div>
						<div id='menu-ramificado-2' class='menu-ramificado menu-2'>".dadosMenuSegundoNivel($dadosMenu)."</div>
						<div id='menu-ramificado-3' class='menu-ramificado menu-3'>".dadosMenuTerceiroNivel($dadosMenu)."</div>
						<div id='menu-ramificado-4' class='menu-ramificado menu-4'></div>
						<div id='menu-ramificado-5' class='menu-ramificado menu-5'></div>
					</div>";
		}
		//}
	}

	function get_content(){
		cadastraLogAcesso(74);
		$strAcesso = "and mp.Modulo_Pagina_ID in (".$paginas."0)";
		global $caminhoSistema,$dadosUserLogin,$caminhoInclude,$caminhoFisico;
		$dadospagina = get_page_content();

		foreach(unserialize($dadospagina['Campos_Obrigatorios_Pai']) as $campoObrigatorio){
			$camposObrigatorios .= $virgula."'".$campoObrigatorio."'";
			$virgula =  ",";
		}
		echo "	<script>var arrayCamposForm = new Array($camposObrigatorios);</script>";


		if(empty($dadosUserLogin))
			if($_GET['oc'] != "")
				include("orcamento.php");
			else
				include("login.php");
		else{

			echo "	<input type='hidden' id='timer-count' value='1800'>";
			if($dadospagina =="404"){
				echo "<script>open('$caminhoSistema', '_top')</script>";
			}else{

				if($dadospagina[Slug_Modulo] == ""){
					if($dadospagina[Nome] != "lixeira"){
						$paginaInicial = mpress_fetch_array(mpress_query("select descr_tipo from tipo where Tipo_ID = 6"));
						$paginaInicial = unserialize($paginaInicial[0]);
						$resultado = mpress_query("select m.Nome, mp.Titulo, m.Slug Slug_Modulo, mp.Slug Slug_Pagina, mpf.Titulo Titulo_Filho, mpf.Slug Slug_Pagina_Filho
												   from
												   modulos m
												   inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
												   left join modulos_paginas mpf on mpf.Modulo_ID = m.Modulo_ID and mpf.Pagina_Pai_ID  = mp.Modulo_pagina_ID
												   $strAcesso
												   where mp.Modulo_Pagina_ID = '".$paginaInicial[$_SESSION['dadosUserLogin']['userID']]."' or mpf.Modulo_Pagina_ID = '".$paginaInicial[0]."'");
						$dadospagina = mpress_fetch_array($resultado);
						if(file_exists("$caminhoFisico/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".js"))
							echo "\n				<script type='text/javascript' src='$caminhoSistema/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".js'></script>";
						if(file_exists("$caminhoFisico/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".css"))
							echo "\n				<link rel='stylesheet' type='text/css' href='$caminhoSistema/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Modulo].".css'>";

					}
				}
				if($dadospagina[Slug_Modulo] != ""){
					require_once("includes/functions.menu.php");
					require_once("config.php");
					if($dadospagina[Titulo_Filho] != ""){
						$tituloFilho = " - ".$dadospagina[Titulo_Filho];
						$dadospagina[Slug_Pagina] = $dadospagina[Slug_Pagina_Filho];
					}
					global $leitura,$gravacao;
					if(file_exists("$caminhoFisico/modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Pagina]."-menus.php"))
						include("modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Pagina]."-menus.php");
					echo "<span id='pagina-modulo' modulo='".strlen(array_search($dadospagina[Modulo_Pagina_ID],$leitura))."'></span>";
					echo "<div id='titulo-principal'>
								<div id='div-titulo-pagina'>$dadospagina[Titulo] $tituloFilho</div>
								<div id='div-opcoes-menu'>".$menuSuperior."</div>
						</div>";
					echo "<div id='div-conteudo-pagina'>
							<input type='hidden' id='slug-pagina' name='slug-pagina' value='".$dadospagina['Slug_Pagina']."'/>";
					include("modulos/".$dadospagina[Slug_Modulo]."/".$dadospagina[Slug_Pagina].".php");
					echo "</div>";
				}
			}
		}
		$dadospagina = get_page_content();
		if($dadospagina[Nome]=='lixeira'){
			echo "<div id='titulo-principal'>Arquivos Exclu&iacute;dos - Lixeira</div>";
			echo "<div id='div-conteudo-pagina'>";
			include("funcoes/lixeira.php");
			echo "</div>";
		}
	}


	function get_fotter(){
		global $dadosUserLogin;
		if(!empty($dadosUserLogin))	$ultimoAcesso = "&Uacute;ltimo acesso: ".converteData($dadosUserLogin[ultimoLogin]);
		echo "	<div id='rodape-esquerdo'>$ultimoAcesso</div>";
		echo "	<div id='rodape-centro'>".$_SERVER['HTTP_USER_AGENT']." - ".$_SERVER['REMOTE_ADDR']."</div>";
		echo "	<div id='rodape-direito'>Sistema MWN Vers&atilde;o 2.5 ".date("Y")."</div>";

	}

	function dataDia($hoje, $tipo = null) {
		$meses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Mar&ccedil;o", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
		$diasdasemana = array (1 => "Segunda-Feira",2 => "Ter&ccedil;a-Feira",3 => "Quarta-Feira",4 => "Quinta-Feira",5 => "Sexta-Feira",6 => "S&aacute;bado",0 => "Domingo");
 		$dia = $hoje["mday"];
 		$mes = $hoje["mon"];
 		$nomemes = $meses[$mes];
 		$ano = $hoje["year"];
 		$diadasemana = $hoje["wday"];
 		$nomediadasemana = $diasdasemana[$diadasemana];
 		if ($tipo=='d-m-a'){
 			$retorno = "$dia de $nomemes de $ano";
 		}
	 	else{
	 		$retorno = "$nomediadasemana, $dia de $nomemes de $ano";
	 	}
	 	return $retorno;
 	}

 	function dataMes($mes) {
		$meses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Mar&ccedil;o", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
 		$nomemes = $meses[$mes];
 		return $nomemes;
 	}

	function converteData($date){
		$data = implode("/",array_reverse(explode("-",substr($date, 0,10))));
		$hora = substr($date, 11,5);
		return "$data $hora";
	}

	function converteDataHora($date, $tipo = null){
		if($tipo==1){
			$data = implode("/",array_reverse(explode("-",substr($date, 0,10))));
			$hora = substr($date, 11,5);
			if ("$data $hora"=="00/00/0000 00:00"){$data=""; $hora="";}
		}else{
			$data = implode("-",array_reverse(explode("/",substr(str_replace('.','/',$date), 0,10))));
			$hora = substr($date, 11,5);
		}
		return trim("$data $hora");
	}

	function get_page_content(){
		global $dadosUserLogin,$paginas;
		global $leitura,$gravacao;

		if(($dadosUserLogin['userID'] == '-1') || ($dadosUserLogin['userID'] >= 1) ){
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

		$arrinstancia = explode("?",str_replace(str_replace("index.php", "", $_SERVER['SCRIPT_NAME']),"/", $_SERVER['REQUEST_URI']));
		$instancia = $arrinstancia[0];
		
		if(substr($instancia, 0,1)=="/") $instancia = substr($instancia,1,strlen($instancia));
		if(substr($instancia, -1)=="/") $instancia = substr($instancia,0,strlen($instancia)-1);

		$referencia[geral] 	= explode("/", $instancia);
		$referencia[plugin] = $referencia[geral][0];
		$referencia[pagina] = $referencia[geral][1];
		$referencia[filho]  = $referencia[geral][2];

		if($referencia[filho] != "") $strFilho = " and  mpf.slug='$referencia[filho]'";

		if(!empty($referencia[pagina])){
			$resultado = mpress_query("select m.Nome, mp.Titulo, m.Slug Slug_Modulo, mp.Slug Slug_Pagina, mpf.Titulo Titulo_Filho, mpf.Slug Slug_Pagina_Filho,mpf.Modulo_Pagina_ID Modulo_Pagina_Filho_ID, mp.Modulo_Pagina_ID, m.Modulo_ID, coalesce(mpf.Tipo_Grupo_ID, mp.Tipo_Grupo_ID) as Tipo_Grupo_ID, mp.Campos_Obrigatorios Campos_Obrigatorios_Pai, mpf.Campos_Obrigatorios Campos_Obrigatorios_Filho
									   from
									   modulos m
									   inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
									   left join modulos_paginas mpf on mpf.Modulo_ID = m.Modulo_ID and mpf.Pagina_Pai_ID  = mp.Modulo_pagina_ID
									   where m.slug='$referencia[plugin]' and mp.slug='$referencia[pagina]' $strAcesso $strFilho");
			if($row = mpress_fetch_array($resultado)){
				return $row;
			}else{
				return "404";
			}
		}else{
			if($referencia[plugin]=='lixeira'){
				$dadosPagina[Nome] 			= "lixeira";
				$dadosPagina[Plugin] 		= "";
				$dadospagina[Slug_Modulo]	= "lixeira";
				return $dadosPagina;
			}
		}

	}


 	function geraTabela($largura, $colunas, $dados, $style = null, $idTabela = "tabela", $cellpadding = 2, $cellspacing = 2, $mostraRegistros, $mostraFiltro, $tipoRetorno){
		unset($_SESSION['campoVisivel']);
		//unset($_SESSION['documento']);
		$_SESSION['campoVisivel'] = "";
		if($mostraFiltro != ""){
			$_SESSION['documento']['largura'] 		= $largura;
			$_SESSION['documento']['colunas'] 		= $colunas;
			$_SESSION['documento']['dados'] 		= $dados;
			$filtroLateral 							= geraTabelaFiltro($colunas,$dados);
		}
		$registrosBloqueados = geraTabelaBloqueio($dados,$idTabela);
		if($mostraRegistros == "") $mostraRegistros = count($dados[colunas][conteudo]);
		if($_POST['posicao-paginador'] == "") $paginaAtual = 1; else $paginaAtual = $_POST['posicao-paginador'];
		$paginasTotais 	 = ceil(count($dados[colunas][conteudo]) / $mostraRegistros);
		$registroInicial = (($mostraRegistros*$paginaAtual)+1) - $mostraRegistros;
		$registroFinal	 = $mostraRegistros * $paginaAtual;
		if($registroFinal > count($dados[colunas][conteudo]))
			$registroFinal = count($dados[colunas][conteudo]);
		if ($style==""){
			$style = "float:left;margin-top:0px; border:1px solid silver; margin-bottom:2px;";
		}

		if ($dados[colunas][cabecalho][conteudo]!=""){
			$htmlCabecalhoExtra = "<thead>
										<tr>
											<td colspan='".$colunas."' class='".$dados[colunas][cabecalho][classe]."'>
												".$dados[colunas][cabecalho][conteudo]."
											</td>
										</tr>
									</thead>";
		}

		$tabela = "	<div id='div-$idTabela' class='gera-tabela'>
						$filtroLateral
						<table width='$largura' Style='$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center' id='$idTabela'>
							$htmlCabecalhoExtra
							<thead>
								<tr>";
		for($i=1;$i<=$colunas;$i++){
			if ((!(is_array($_POST['gera-tabela-campos']))) || (in_array("coluna-".$i, $_POST['gera-tabela-campos']))){
				$ordenatabela = "";
				$classeTitulo = " tabela-fundo-escuro-titulo ";
				if ($dados[colunas][titulo][classe]!="") $classeTitulo = $dados[colunas][titulo][classe];
				if($dados[colunas][ordena][$i] != "")$ordenatabela = " class='ordena-tabela link' id='".$dados[colunas][ordena][$i]."' ";
				$tabela .= "				<td class='$classeTitulo coluna-$i ".$_SESSION['campoVisivel']['coluna-'.$i]."' ".$dados[colunas][tamanho][$i]."><a $ordenatabela>".$dados[colunas][titulo][$i]."</a></td>";
			}
		}
		$tabela .= "			</tr>
							</thead>";
		for($i=$registroInicial;$i<=$registroFinal;$i++){
			$classe=$dados[colunas][classe][$i];
			if ($dados[colunas][classe][$i]==""){
				if($i%2==0)$classe=' tabela-fundo-claro '; else $classe=' tabela-fundo-escuro ';
			}
			$tabela .= "	<tbody>
								<tr ".$dados[colunas][tr][$i].">";
			for($j=1;$j<=$colunas;$j++){
				if ($dados[colunas][colspan][$i][$j]=="") $celula = " coluna-$j "; else $celula = "";
				if ((!(is_array($_POST['gera-tabela-campos']))) || (in_array("coluna-".$j, $_POST['gera-tabela-campos']))){
					$tabela .= "			<td class='$classe  $celula ".$_SESSION['campoVisivel']['coluna-'.$j]."' ".$dados[colunas][extras][$i][$j]." colspan='".$dados[colunas][colspan][$i][$j]."'>".$dados[colunas][conteudo][$i][$j]."</td>";
				}
				if ($dados[colunas][colspan][$i][$j]!="") $j += $dados[colunas][colspan][$i][$j] - 1;
			}
			$tabela .= "		</tr>
							</tbody>";
		}
		$tabela .= "	</table>
					</div>
					<input type='hidden' id='ordena-tabela' name='ordena-tabela'>
					<input type='hidden' id='ordena-tipo' name='ordena-tipo' value='".$_POST['ordena-tipo']."'>";
		if($_SESSION['paginador'] == "")
			$tabela .= paginadorTabela($paginasTotais,$paginaAtual,$mostraRegistros,$dados,$idTabela,$largura, $colunas);
		$_SESSION['paginador'] = "";
		$tabela .= "<style>.esconde-campo-tabela{display:none;}</style>";
		if ($tipoRetorno=="return")
			return $tabela;
		else
			echo $tabela;

	}

 	function returnTabelaExcel($largura, $colunas, $dados, $style = null, $idTabela = "tabela", $cellpadding = 2, $cellspacing = 2){
		$dadosPagina = get_page_content();
		$cookieRelatorio = "relatorio-".$dadosPagina[Modulo_Pagina_ID].$dadosPagina[Modulo_Pagina_Filho_ID].$_SESSION[dadosUserLogin][userID];
		$camposRelatorio = unserialize($_COOKIE[$cookieRelatorio]);

		$flagPasso = 0;
		if(count($camposRelatorio) >= 2){
			$flagPasso = 1;
			for($i=1;$i<=50;$i++){ $campoVisivel["coluna-$i"] = "0";}
			foreach($camposRelatorio as $chave  => $dado){
				$campoVisivel[$dado] = "1";
			}
		}

 		$tabelaFundoEscuro = "border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;background-color:#f9f9f9;";
 		$tabelaFundoClaro = "border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;background-color:#ffffff;";
 		$tabelaFundoEscuroTitulo = "border-left:1px solid #cccccc;border-bottom:1px solid #cccccc;font-weight:bold;background-color:#f1f1f1;";

		$tabela = "			<table width='$largura' Style='font-family:arial; font-size:11px; margin-top:0px;border:1px solid silver;margin-bottom:2px;$style' cellpadding='$cellpadding' cellspacing='$cellspacing' align='center'>
								<thead>
									<tr>";

		for($i=1;$i<=$colunas;$i++){
			if (($campoVisivel['coluna-'.$i]==1) || ($flagPasso==0)){
				$tabela .= "				<td style='".$tabelaFundoEscuroTitulo."' ".$dados[colunas][tamanho][$i].">".$dados[colunas][titulo][$i]."</td>";
			}
		}
		$tabela .= "				</tr>
								</thead>";
		for($i=1;$i<=count($dados[colunas][conteudo]);$i++){
			if($i%2==0) $classe = $tabelaFundoEscuro; else $classe = $tabelaFundoClaro;
			$tabela .= "		<tbody>
									<tr>";
			for($j=1;$j<=$colunas;$j++){
				if (($campoVisivel['coluna-'.$j]==1) || ($flagPasso==0))
					$tabela .= "			<td style='".$classe."'>".$dados[colunas][conteudo][$i][$j]."</td>";
			}
			$tabela .= "			</tr>
								</tbody>";
		}
		$tabela .= "		</table>";
		return $tabela;
	}

	function geraTabelaFiltro($colunas,$dados){
		ob_start();
		$dadosPagina = get_page_content();
		$cookieRelatorio = "relatorio-".$dadosPagina[Modulo_Pagina_ID].$dadosPagina[Modulo_Pagina_Filho_ID].$_SESSION[dadosUserLogin][userID];
		$camposRelatorio = unserialize($_COOKIE[$cookieRelatorio]);

		if(count($camposRelatorio) >= 2){
			for($i=1;$i<=50;$i++){
				$campoVisivel["coluna-$i"] = "esconde-campo-tabela";
			}
			foreach($camposRelatorio as $campoRelatorio){
				$campoVisivel[$campoRelatorio] = "mostra-campo-tabela";
				$campoSelecionado[$campoRelatorio] = "checked";
			}
			$_SESSION['campoVisivel'] = $campoVisivel;
		}
		else{
			if (($_POST) && is_array($_POST['gera-tabela-campos'])){
				foreach($_POST['gera-tabela-campos'] as $coluna){
					$campoVisivel["coluna-$j"] = "mostra-campo-tabela";
					$campoSelecionado[$coluna] = "checked";
				}
			}
			else{
				for($j=1;$j<=50;$j++){
					$campoSelecionado["coluna-$j"] = "checked";
					$campoVisivel["coluna-$j"] = "mostra-campo-tabela";
				}
			}
			$_SESSION['campoVisivel'] = $campoVisivel;
		}

		$filtroLateral = "	<div id='tabela-filtro-lateral' Style='position:fixed;bottom:15px;right:0px;border:1px solid silver;margin-top:2px;width:200px;background-color:#ffffff;display:block;border-radius:10px;display:none;cursor:pointer;' attr-status='fechada'>
								<p Style='text-align:center;width:100%;margin-left:0px;margin-bottom:5px;margin-top:0px;background-color:#E5ECF1;height:16px;border-top-right-radius:10px;border-top-left-radius:10px;padding-top:2px;'><b>Filtrar Campos</b></p>";
		for($i=1;$i<=$colunas;$i++){
			if($dados[colunas][titulo][$i] != "")
				$filtroLateral .= "	<input type='checkbox' value='coluna-$i' ".$campoSelecionado["coluna-$i"]." class='filtra-coluna-tabela' Style='margin-left:10px;' name='gera-tabela-campos[]'>".strip_tags($dados[colunas][titulo][$i])."<br>";
		}
		$filtroLateral .= "		<p Style='text-align:center;width:100%;'>
									<a id='fechar-filtro-lateral'><b>Fechar</b></a> - <a id='salvar-filtro-lateral'><b>Salvar</b></a>
								</p>
								<p Style='text-align:center;width:100%;'>
									<img class='btn-pdf btn-gerar-all' tipo='pdf' align='center' style='width:16px;height:16px;'/> <!--title='Gerar Excel'-->
									<img class='btn-excel btn-gerar-all' tipo='excel' align='center' style='width:16px;height:16px;'/> <!--title='Gerar Excel'-->
									<img class='btn-imprimir btn-gerar-all' tipo='html' align='center' style='width:16px;height:16px;'/> <!--title='Imprimir'-->
								</p>
								<p>&nbsp;</p>
								<input type='hidden' name='pagina-tabela-save' value='$cookieRelatorio'>
							</div>";
		return $filtroLateral;
	}

 	function enviaEmail($emailPara,$nomePara,$assunto,$msg,$resposta){
		require("PHPMailer/PHPMailerAutoload.php");
		//require("class.phpmailer.php");

		$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 7");
		if($row = mysql_fetch_array($resultado))
			$dadosEnvio = unserialize($row[descr_tipo]);


		$mail = new PHPMailer();

		//if ($dadosEnvio['servidor_smtp']!="smtp.google.com"){
		$mail->SetLanguage("br", "PHPMailer/language/phpmailer.lang-br.php");
		//}

		if ($dadosEnvio['smtp'] != "")
			$mail->IsSMTP();
		$mail->Port 		= $dadosEnvio[porta];
		$mail->Host 		= $dadosEnvio[servidor_smtp];
		$mail->SMTPSecure 	= $dadosEnvio[certificado];
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $dadosEnvio[usuario];
		$mail->Password 	= $dadosEnvio[senha];
		$mail->From 		= $dadosEnvio[email_envio];
		$mail->FromName 	= $dadosEnvio[nome_envio];
		$mail->WordWrap 	= 1000;
		$mail->IsHTML(true);
		$mail->AddAddress($emailPara,utf8_decode($nomePara));
		$mail->AddReplyTo($emailPara,utf8_decode($nomePara));
		$mail->Body 		= utf8_decode($msg);
		$mail->Subject 	= $assunto;
		$mail->ErrorInfo 	= "Erro no envio: ";
		$mail->SMTPDebug  	= 0;
		if($mail->Send())
			echo $resposta;
		else{
			echo $mail->ErrorInfo;
		}
 	}

 	function enviaEmails($dadosEmail, $assunto, $msg, $resposta, $arquivos){
 		global $caminhoFisico, $dadosUserLogin;
		//require("class.phpmailer.php");
		require("PHPMailer/PHPMailerAutoload.php");

 		if (!(is_array($dadosEmail))){
			$emails = explode(";", $dadosEmail);
			$i = 0;
			foreach ($emails as $email) {
				if (trim($email)!=""){
					$i++;
					$dadosEmailArray['email'][$i] = $email;
					$dadosEmailArray['nome'][$i] = $email;
				}
			}
 		}
 		else{
			$dadosEmailArray = $dadosEmail;
		}
		$sql = "select (select Descr_Tipo from tipo where Tipo_ID = 7) as Dados_Email,
 					(select Email from cadastros_dados where Cadastro_ID = '".$dadosUserLogin['userID']."') as Email_Reply,
 					(select Nome from cadastros_dados where Cadastro_ID = '".$dadosUserLogin['userID']."')  as Nome_Reply";
 		$resultado = mpress_query($sql);
		if($row = mysql_fetch_array($resultado)){
			$dadosEnvio = unserialize($row['Dados_Email']);
			$emailReply = $row['Email_Reply'];
			$nomeReply = $row['Nome_Reply'];
			if (!(validaEmail($emailReply)))
				$emailReply = $dadosEnvio[email_envio];
		}

		$mail = new PHPMailer();
		//if ($dadosEnvio['servidor_smtp']!="smtp.google.com")
		$mail->SetLanguage("br", "PHPMailer/language/phpmailer.lang-br.php");

		if ($dadosEnvio['smtp'] != "")
			$mail->IsSMTP();
		$mail->Port 		= $dadosEnvio[porta];
		$mail->Host 		= $dadosEnvio[servidor_smtp];
		$mail->SMTPSecure 	= $dadosEnvio[certificado];
		$mail->SMTPAuth 	= true;
		$mail->Username 	= $dadosEnvio[usuario];
		$mail->Password 	= $dadosEnvio[senha];
		$mail->From 		= $dadosEnvio[email_envio];
		$mail->FromName 	= $dadosEnvio[nome_envio];
		$mail->WordWrap 	= 1000;
		$mail->IsHTML(true);
		$i = 0;
		for($i=0;$i<=count($dadosEmailArray['email']);$i++){
			$emailPara = trim($dadosEmailArray['email'][$i]);
			if (validaEmail($emailPara)){
				$nomePara = trim($dadosEmailArray['email'][$i]);
				$mail->AddAddress($emailPara, $nomePara);
			}
			if (trim($emailReply!=""))
				$mail->AddReplyTo($emailReply, $nomeReply);
		}
		$mail->Body 		= utf8_decode($msg);
		/* Anexos */
		if (is_array($arquivos)){
			foreach ($arquivos as $arquivo) {
				$mail->AddAttachment($arquivo);
			}
		}
		$mail->Subject 	= $assunto;
		$mail->ErrorInfo 	= "Erro no envio: ";
		$mail->SMTPDebug  	= 0;
		if($mail->Send())
			return $resposta;
		else{
			return $mail->ErrorInfo;
		}
 	}


 	function geraEmailPadrao($conteudo){
	 	global $caminhoSistema;
 		$conteudoEmail = "
		<html xmlns='http://www.w3.org/1999/xhtml'>
			<head>
			<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1' />
			</head>
			<body style='margin: 0 auto; background-color:#ffffff'>
				<style>
					table {
						font-family: Arial, Verdana, Tahoma, sans-serif; font-size:12px; font-weight: normal; font-size:13px; color:#222222; margin:21px; border:0px; border-radius: 5px; padding:1.5px;
					}
					fieldset {
						border-radius: 5px;
						border:1px solid silver;
						padding:1.5px;
					}
					.tabela-fundo-escuro-titulo{
						border-left:1px solid #cccccc;
						border-bottom:1px solid #cccccc;
						font-weight:bold;
						background-color:#f1f1f1;
						font-size:12px;
					}

					.tabela-fundo-escuro{
						border-left:1px solid #cccccc;
						border-bottom:1px solid #cccccc;
						background-color:#f9f9f9;
					}
					.tabela-fundo-claro{
						border-left:1px solid #cccccc;
						border-bottom:1px solid #cccccc;
						background-color:#ffffff;
					}
					.fundo-escuro-titulo{
						border:0px;
						font-weight:bold;
						background-color:#f1f1f1;
					}

					.fundo-escuro{
						border:0px;
						background-color:#f9f9f9;
					}
					.fundo-claro{
						border:0px;
						background-color:#ffffff;
					}
					.titulo-secundario p{
						float:left;
						margin:0 0 0 0;
						width:100%;
					}
				</style>
				<table border='0' align='center' cellpadding='0' cellspacing='0' style='max-width:800;'>
					<tr>
						<td style='height:80px' align='center'>
							<img src='".$caminhoSistema."/images/documentos/cabecalho.jpg' border='0' id='r&r' style='display: block' />
						</td>
					</tr>
					<tr>
						<td style='padding-top:10px; padding-bottom:10px;'>
							<div>$conteudo</div>
						</td>
					</tr>
					<tr>
						<td style='height:80px' align='center'>
							<img src='".$caminhoSistema."/images/documentos/rodape.jpg' border='0' style='display: block' />
						</td>
					</tr>
				</table>
			</body>
		</html>";
		return $conteudoEmail;

 	}
	//min-height:300px;


	function validaEmail($email) {
		$conta = "^[a-zA-Z0-9\._-]+@";
		$domino = "[a-zA-Z0-9\._-]+.";
		$extensao = "([a-zA-Z]{2,4})$";
		$pattern = $conta.$domino.$extensao;
		if (ereg($pattern, $email))
			return true;
		else
			return false;
	}

	function enviaSMS($mensagem, $numeroEnvio, $resposta){
		$resultado = mpress_query("select descr_tipo from tipo where tipo_id = 7");
		if($row = mysql_fetch_array($resultado))
			$dadosEnvio = unserialize($row[descr_tipo]);

		$idEnvio = file_get_contents("http://webapi.comtele.com.br/api/api_fuse_connection.php?fuse=get_id&user=".$dadosEnvio[sms_usuario]."&pwd=".$dadosEnvio[sms_senha]);
		echo "<iframe Style='width:0px;height:0px;' frameborder='0' src='http://webapi.comtele.com.br/api/api_fuse_connection.php?fuse=send_msg&id=".trim($idEnvio)."&from=".$dadosEnvio[sms_usuario]."&msg=$mensagem&number=$numeroEnvio'</iframe>";

		echo $resposta;
	}



	function geraTelaTiposNiveis(){
		session_start();

		$tipoID = $_POST['tipo-id'];
		$pai = $_POST['pai'];

		$dadospagina = get_page_content();
		echo "	<input type='hidden' id='tipo-grupo' name='tipo-grupo' value='".$dadospagina[Tipo_Grupo_ID]."'/>";

		if( $_SERVER['REQUEST_METHOD']=='POST'){
			$request = md5(implode($_POST));
			//echo "<br>".$request."<br>";
			if (!((isset( $_SESSION['last_request']) && $_SESSION['last_request']== $request))){
				$_SESSION['last_request']  = $request;
				/************/
				if($_POST['nome']!= ""){
					$nome 	= $_POST['nome'];
					$pai 	= $_POST['pai'];
					if($_POST['tipo-id'] == ""){
						mpress_query("insert into tipo(Descr_Tipo, Tipo_Auxiliar,Tipo_Grupo_ID)values('$nome','$pai', '".$dadospagina[Tipo_Grupo_ID]."')");
					}else{
						mpress_query("update tipo set Descr_Tipo = '$nome', Tipo_Auxiliar = '$pai' where Tipo_ID = '".$tipoID."'");
					}
				}
				if($_POST['tipo-excluir']!="")
					mpress_query("update tipo set Situacao_ID = 3 where Tipo_ID ='".$_POST['tipo-excluir']."'");
				/************/

			}
			else {
				//echo "REFRESH";
			}
		}

		$primeiroNivel = $dadospagina[Tipo_Grupo_ID];
		if ($dadospagina[Tipo_Grupo_ID]=='28'){
			$primeiroNivel = '27';
			$primeiroNivelEsconde = "esconde";
			$sqlCond1Nivel = " and Tipo_ID IN (44,45) ";
		}

?>
	<input type='hidden' id='tipo-id' name='tipo-id' value=''/>
	<div id='chamados-container'>
		<div class='titulo-secundario duas-colunas'>
			<div class='titulo-container-interno-alpha'>
				<div class='titulo'>
					<p Style='margin-top:2px'>&nbsp;Cadastrar <?php echo $dadospagina[Titulo_Filho];?></p>
				</div>
				<div class='conteudo-interno'>
					<div class='titulo-secundario uma-coluna'>
						<p>Descri&ccedil;&atilde;o</p>
						<p><input type='text' id='nome' name='nome' value='' class='required' style='width:98.5%;'/></p>
					</div>
					<div class='titulo-secundario uma-coluna'>
						<p><?php echo $dadospagina[Titulo_Filho];?> Pai</p>
						<p>
							<select name='pai' id='pai'>
								<option value=''>Nenhuma</option>
<?php
			$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$primeiroNivel." ".$sqlCond1Nivel." and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
			while($categoria1 = mpress_fetch_array($query)){
				echo "<option value='".$categoria1['Tipo_ID']."'>".$categoria1['Descr_Tipo']."</option>";
				$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar ='".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria2 = mpress_fetch_array($query2)){
					echo "<option value='".$categoria2['Tipo_ID']."'>-- ".$categoria2['Descr_Tipo']."</option>";
					$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar ='".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria3 = mpress_fetch_array($query3)){
						echo "<option value='".$categoria3['Tipo_ID']."'>---- ".$categoria3['Descr_Tipo']."</option>";
						$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar ='".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						while($categoria4 = mpress_fetch_array($query4)){
							echo "<option value='".$categoria4['Tipo_ID']."'>------ ".$categoria4['Descr_Tipo']."</option>";
						}
					}
				}
			}
?>
							</select>
						</p>
						<input type='button' value='Cancelar' id='cancela-edita-tarefa' Style='width:130px;float:right;margin-top:5px;margin-right:15px;' class='esconde'>
						<input type='button' value='Cadastrar' id='cadastra-nova-tarefa' Style='width:130px;float:right;margin-top:5px;margin-right:15px;'>
					</div>
				</div>
			</div>
		</div>

<?php
			$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$primeiroNivel." ".$sqlCond1Nivel." and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
			//echo "select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$primeiroNivel." and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo";
			while($categoria1 = mpress_fetch_array($query)){
				$i++;
				$dados[colunas][conteudo][$i][1] = "<span style='font-weight:bold;'><br>".$categoria1['Descr_Tipo']."<br><br></span>";
				$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-tipo $primeiroNivelEsconde' id='".$categoria1['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir'>&nbsp;</div>";
				$dados[colunas][conteudo][$i][3] = "<div class='btn-editar btn-editar-tipo $primeiroNivelEsconde' style='float:right;padding-right:10px'  title='Editar' id='".$categoria1['Tipo_ID']."' descr='".$categoria1['Descr_Tipo']."' pai=''>&nbsp;</div>";
				//echo "select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo";
				$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria2 = mpress_fetch_array($query2)){
					$i++;
					$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria2['Descr_Tipo'];
					$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-tipo' id='".$categoria2['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir'>&nbsp;</div>";
					$dados[colunas][conteudo][$i][3] = "<div class='btn-editar btn-editar-tipo' style='float:right;padding-right:10px'    title='Editar'id='".$categoria2['Tipo_ID']."' descr='".$categoria2['Descr_Tipo']."' pai='".$categoria1['Tipo_ID']."'>&nbsp;</div>";
					$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					//echo "select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo";
					while($categoria3 = mpress_fetch_array($query3)){
						$i++;
						$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria3['Descr_Tipo'];
						$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-tipo' id='".$categoria3['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir'>&nbsp;</div>";
						$dados[colunas][conteudo][$i][3] = "<div class='btn-editar btn-editar-tipo' style='float:right;padding-right:10px'  title='Editar'id='".$categoria3['Tipo_ID']."' descr='".$categoria3['Descr_Tipo']."' pai='".$categoria2['Tipo_ID']."'>&nbsp;</div>";
						$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						//echo "<br>select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo";
						while($categoria4 = mpress_fetch_array($query4)){
							$i++;
							$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria4['Descr_Tipo'];
							$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-tipo' id='".$categoria4['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir'>&nbsp;</div>";
							$dados[colunas][conteudo][$i][3] = "<div class='btn-editar btn-editar-tipo' style='float:right;padding-right:10px'  title='Editar' id='".$categoria4['Tipo_ID']."' descr='".$categoria4['Descr_Tipo']."' pai='".$categoria3['Tipo_ID']."'>&nbsp;</div>";
							$query5 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Tipo_Auxiliar = '".$categoria4['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
							while($categoria5 = mpress_fetch_array($query5)){
								$i++;
								$dados[colunas][conteudo][$i][1] = "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria5['Descr_Tipo'];
								$dados[colunas][conteudo][$i][2] = "<div class='btn-excluir btn-excluir-tipo' id='".$categoria5['Tipo_ID']."' style='float:right; padding-right:10px'  title='Excluir'>&nbsp;</div>";
								$dados[colunas][conteudo][$i][3] = "<div class='btn-editar btn-editar-tipo' style='float:right;padding-right:10px'  title='Editar' id='".$categoria5['Tipo_ID']."' descr='".$categoria5['Descr_Tipo']."' pai='".$categoria4['Tipo_ID']."'>&nbsp;</div>";
							}
						}
					}
				}
			}

			if($i>=1){
				$largura = "99%";
				$colunas = "3";
				$style	 = "float:left;";
				$titulo  = "display:none";
				$dados[colunas][tamanho][2] = "width='38x'";
				$dados[colunas][tamanho][3] = "width='38px'";
			}
?>
			<div class='titulo-secundario duas-colunas'>
				<div class='titulo-container-interno-omega' Style='min-height:317px'>
					<div class='titulo'>
						<p>Cadastrado</p>
					</div>
					<div class='conteudo-interno'>
						<div class='titulo-secundario uma-coluna'>
							<?php geraTabela($largura,$colunas,$dados,$style);?>
						</div>
					</div>
				</div>
			</div>
				<?php
					$lixeira = mpress_query("select count(*) Total from tipo where Situacao_ID = 3 and Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]);
					$itens = mpress_fetch_array($lixeira); $totalItens = $itens[Total];
					if($totalItens >=1) $classeAddLixeira = "lixeira-cheia"; else $classeAddLixeira = "lixeira-vazia";
					echo "		<div class='exclui-dragable $classeAddLixeira' title='$totalItens item(s) na lixeira'>
								 			<input type='hidden' name='slug' id='slug' value=''>
											<input type='hidden' name='id-lixeira' id='id-lixeira' value='".$dadospagina[Tipo_Grupo_ID]."'>
											<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='tipos'>
										</div>";
				?>
			<input type='hidden' name='tipo-excluir' id='tipo-excluir' value=''>
		</div>
<?php
	}



	function geraTelaTipos(){
		$dadospagina = get_page_content();
		$tipoID = $_POST['tipo-id'];

		// var_dump($_POST);
		// die();

		if ($tipoID!=''){
			$rs = mpress_query("select Tipo_Auxiliar, Tipo_Auxiliar_Extra, Descr_Tipo from tipo where Tipo_ID = '$tipoID' order by Descr_Tipo");
			if($row = mpress_fetch_array($rs)){
				$tipoAuxiliar 		= $row['Tipo_Auxiliar'];
				$tipoAuxiliarExtra 	= $row['Tipo_Auxiliar_Extra'];
				$descrTipo 			= $row['Descr_Tipo'];
			}
		}
		echo "	<input type='hidden' id='tipo-grupo' name='tipo-grupo' value='".$dadospagina[Tipo_Grupo_ID]."'/>
				<input type='hidden' name='slug' id='slug' value=''>
				<div class='titulo-container'>
					<div class='titulo'>
						<p>Cadastrar ".$dadospagina[Titulo_Filho]."
							<input type='button' value='Salvar' id='".$dadospagina[Titulo]."' class='cadastra-tipo' style='float:right;height:24px;font-size:10px;margin-top:-3px;'>
						</p>
					</div>
					<div class='conteudo-interno cart-tipos-editar'>
						<div class='titulo-secundario uma-coluna'>
							<p><b>T&iacute;tulo</b></p>
							<p>
								<input type='hidden' id='tipo-id' name='tipo-id' value='$tipoID'/>
								<input type='text' id='titulo' name='titulo' maxlength='500' autocomplete='off' value='$descrTipo'/>
							</p>
						</div>";

		/******** CASO CENTRO DE CUSTO POSSIBILITAR ATRELAR A UM CADASTRO RESPONSAVEL ********/
		if ($dadospagina[Tipo_Grupo_ID]=='26'){
			$responsavelID = $tipoAuxiliar;
			echo "		<div style='margin-top:3px;'>
							<p style='margin-bottom:3px'><b>Respons&aacute;vel:</b>
								<input type='button' class='editar-cadastro-generico' style='height:20px;font-size:10px;margin-top:0px;' value='Alterar' id='botao-alterar-responsavel-id' campo-alvo='responsavel-id'>
							</p>
							<div id='conteudo-interno-responsavel-id'>";
			carregarBlocoCadastroGeral($responsavelID, 'responsavel-id','Cadastro',1,'','');
			echo "			</div>
						</div>";
		}
		/*************************************************************************************/

		/****************** CASO PRIORIDADES DOS CHAMADOS CAMPOS DE SLA E CORES **************/
		if ($dadospagina[Tipo_Grupo_ID]=='21'){
			$dados = unserialize($tipoAuxiliar);
			if ($dados['sabado']=='1') $selecionadoSabado = "checked";
			if ($dados['domingo']=='1') $selecionadoDomingo = "checked";
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario tres-colunas'>
								<p><b>Tempo Retorno (Horas)</b></p>
								<p><input type='text' id='tempo' name='tempo' maxlength='10' class='formata-horas' autocomplete='off' value='".$dados['tempo']."'/></p>
							</div>
							<div class='titulo-secundario seis-colunas'>
								<p><b>Cor Fundo</b></p>
								<p><input type='text' style='width:50px' name='cor-fundo' id='cor-fundo'  class='formata-cor' value='".$dados['cor-fundo']."'/></p>
							</div>
							<div class='titulo-secundario seis-colunas'>
								<p><b>Cor Texto</b></p>
								<p><input type='text' style='width:50px' name='cor-texto' id='cor-texto'  class='formata-cor' value='".$dados['cor-texto']."'/></p>
							</div>
							<div class='titulo-secundario tres-colunas' style='margin-top:5px;margin-bottom:0px;'>
								<p><input type='checkbox' name='sabado' id='sabado' value='1' $selecionadoSabado> Desconsiderar S&aacute;bado</p>
								<p><input type='checkbox' name='domingo' id='domingo' value='1' $selecionadoDomingo> Desconsiderar Domingo</p>
							</div>
						</div>";
		}
		/*************************************************************************************/

		/****************** CASO FINANCEIRO ENTRADAS X SAIDAS ********************************/
		if ($dadospagina[Tipo_Grupo_ID]=='28'){
			$dados = unserialize($tipoAuxiliar);
			if (($dados['saida']=='1') || ($tipoAuxiliar == "")) $selecionadoS = "checked";
			if (($dados['entrada']=='1') || ($tipoAuxiliar == "")) $selecionadoE = "checked";
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario seis-colunas' style='margin-top:5px;margin-bottom:0px;'>
								<p></p>
								<p><input type='checkbox' name='saida' id='saida' value='1' $selecionadoS> Sa&iacute;da &nbsp;&nbsp;&nbsp;
								   <input type='checkbox' name='entrada' id='entrada' value='1' $selecionadoE> Entrada
								</p>
							</div>
						</div>";
		}
		/*************************************************************************************/

		/****************** CASO PRIORIDADES DOS CHAMADOS CAMPOS DE SLA E CORES **************/
		if ($dadospagina[Tipo_Grupo_ID]=='44'){
			$dados = unserialize($tipoAuxiliar);
			if (($dados['tipo-cfop']=='S') || ($dados['tipo-cfop']=='')) $selecionadoS = "checked";
			if ($dados['tipo-cfop']=='E') $selecionadoE = "checked";

			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario seis-colunas'>
								<p><b>C&oacute;digo</b></p>
							<p><input type='text' id='codigo-cfop' name='codigo-cfop' maxlength='4' class='formata-numero' autocomplete='off' value='".$dados['codigo-cfop']."'/></p>
							</div>
							<div class='titulo-secundario seis-colunas' style='margin-top:5px;margin-bottom:0px;'>
								<p>&nbsp;</p>
								<p><input type='radio' name='tipo-cfop' id='tipo-cfop' value='S' $selecionadoS> Sa&iacute;da &nbsp;&nbsp;&nbsp;
								<input type='radio' name='tipo-cfop' id='tipo-cfop' value='E' $selecionadoE> Entrada</p>
							</div>
						</div>";
		}
		/*************************************************************************************/
		/*
		if ($dadospagina[Tipo_Grupo_ID]=='46'){
			$condicao = "and Tipo_ID not in (select Tipo_Secundario_ID from modulos_vinculos mv where mv.Nome_tabela = 'tipo' and mv.Valor_Vinculo = '46')";

			// CAMPUS
			if ($tipoID!=''){
				$selecionados = "";
				$rs = mpress_query("select Tipo_Secundario_ID from modulos_vinculos mv where mv.Nome_tabela = 'tipo' and mv.Tipo_Principal_ID = '$tipoID' and mv.Valor_Vinculo = '46'");
				while($row = mpress_fetch_array($rs))
					$selecionados .= $row['Tipo_Secundario_ID'].",";
				$selecionados = substr($selecionados, 0, -1);
				if ($selecionados!=""){
					$condicao .= " or Tipo_ID in ($selecionados)";
				}
			}
			echo "	<div style='margin-top:3px;'>
						<div class='titulo-secundario uma-coluna'>
							<p><b>Campus</b></p>
							<p><select name='campus' id='campus' class='' multiple readonly>".optionValueGrupoMultiplo(47,$selecionados, $condicao)."</select></p>
						</div>
					</div>";
		}
		*/
		if ($dadospagina[Tipo_Grupo_ID]=='47'){
			if ($tipoID!=''){
				$selecionados = "";
				/* Universidade */
				$rs = mpress_query("select Tipo_Principal_ID from modulos_vinculos mv where mv.Nome_tabela = 'tipo' and mv.Tipo_Secundario_ID = '$tipoID' and mv.Valor_Vinculo = '46'");
				if($row = mpress_fetch_array($rs))
					$universidadeSelecionada = $row['Tipo_Principal_ID'];
				/* Cursos */
				$rs = mpress_query("select Tipo_Secundario_ID from modulos_vinculos mv where mv.Nome_tabela = 'tipo' and mv.Tipo_Principal_ID = '$tipoID' and mv.Valor_Vinculo = '47'");
				while($row = mpress_fetch_array($rs))
					$cursosSelecionados .= $row['Tipo_Secundario_ID'].",";
				$cursosSelecionados = substr($cursosSelecionados, 0, -1);
			}
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario uma-coluna'>
								<p><b>Institui&ccedil;&atilde;o</b></p>
							<p><select name='instituicao' id='instituicao' class='required'>".optionValueGrupo(46, $universidadeSelecionada, "Selecione")."</select></p>
							</div>
							<div class='titulo-secundario uma-coluna'>
								<p><b>Cursos</b></p>
								<p><select name='cursos[]' id='cursos' class='' multiple>".optionValueGrupoMultiplo(48, $cursosSelecionados, "")."</select></p>
							</div>
						</div>";
		}

		if ($dadospagina[Tipo_Grupo_ID]=='57'){
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario uma-coluna'>
								<p><b>Categoria</b></p>
								<p>
									<select name='categoria' id='categoria' class='required'>
										<option></option>";
			$rs = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome");
			$sel[$tipoAuxiliar] = " selected ";
			while($row = mpress_fetch_array($rs)){
				echo "					<option value='".$row['Categoria_ID']."' ".$sel[$row['Categoria_ID']].">".$row['Nome']."</option>";
			}
			echo "					</select>
								</p>
							</div>
						</div>";
		}


		/****************** CASO PRIORIDADES DOS CHAMADOS CAMPOS DE SLA E CORES **************/
		if (($dadospagina[Tipo_Grupo_ID]=='18') || ($dadospagina[Tipo_Grupo_ID]=='51') || ($dadospagina[Tipo_Grupo_ID]=='53') || ($dadospagina[Tipo_Grupo_ID]=='60')){

			$dados = unserialize($tipoAuxiliar);
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario seis-colunas'>
								<p><b>Cor Fundo</b></p>
								<p><input type='text' style='width:50px' name='cor-fundo' id='cor-fundo'  class='formata-cor' value='".$dados['cor-fundo']."'/></p>
							</div>
							<div class='titulo-secundario seis-colunas'>
								<p><b>Cor Texto</b></p>
								<p><input type='text' style='width:50px' name='cor-texto' id='cor-texto'  class='formata-cor' value='".$dados['cor-texto']."'/></p>
							</div>
							<div class='titulo-secundario seis-colunas'>
								<p><b>Posi&ccedil;&atilde;o</b></p>
								<p><input type='text' style='width:50px' name='posicao' id='posicao'  class='formata-numero' value='".$dados['posicao']."'/></p>
							</div>";
			if ($modulosAtivos[projetos]){
				$menuSuperior .= "	<div class='menu-interno-superior menu-interno-modulo' id='menu-superior-6' title='Visualizar Tarefas' attr-div='.conjunto6' attr-pos='6'>
										Tarefas
									</div>";
			}



			echo"	</div>";
		}
		/*************************************************************************************/


		if ($dadospagina[Tipo_Grupo_ID]=='65'){
			$dados = unserialize($tipoAuxiliar);
			$sel[$dados['a-parte']] = "checked";
			echo "	<div class='titulo-secundario' style='float:left; width:100%; padding-top:5px;'>
						<div class='titulo-secundario' style='float:left; width:10%'>
							<p style='margin-top:15px' align='center'><input align='center' type='button' class='incluir-tempos-fase-sessao' value='Incluir Tempo'/></p>
							<p>&nbsp;<p>
							<p>&nbsp;<p>
							<p><b>A Parte</b></p>
							<p><input type='radio' name='a-parte' value='0' ".$sel[0]."/>Nenhum</p>
						</div>";
				echo "	<div class='titulo-secundario tempos-fases-sessao' style='float:left; width:70%'>";
				$i = 0;
				foreach($dados['tempo'] as $chave => $tempo){
					$i++;
					$descricao = $dados['descricao'][$chave];
					echo "	<div style='float:left; width:10%;' id='div-bloco-tempo-".$i."'>
								<p><b>Descri&ccedil;&atilde;o</b>
									<span style='float:right' class='link excluir-tempo-fase-sessao' posicao='".$i."'>x &nbsp;&nbsp;&nbsp;&nbsp;</span>
									<input type='text' name='descricao[]' style='width:90%' value='".$descricao."'/>
								</p>
								<p><b>Tempo</b><input type='text' name='tempo[]' class='formata-horas' style='width:90%' value='".$tempo."'/></p>
								<p><b>A Parte</b><input type='radio' name='a-parte' value='$i' ".$sel[$i]."/></p>
							</div>";
				}
				echo "		<input type='hidden' id='contadorTempos' value='".$i."'/>
						</div>
					</div>";
			echo "	<div class='titulo-secundario' style='float:left; width:100%; padding-top:5px;'>";
			echo "		<div class='titulo-secundario' style='float:left; width:25%'>
							<p><b>Cron&oacute;metro Realiza Pausa?</b></p>
							<p><select id='cronometro-pausa' name='cronometro-pausa' class='required'>".optionValueSimNao($dados['cronometro-pausa'],'Selecione')."</select></p>
						</div>";
			echo "		<div class='titulo-secundario' style='float:left; width:25%'>
							<p><b>Fase faz uso da tribuna?</b></p>
							<p><select id='uso-tribuna' name='uso-tribuna' class='required'>".optionValueSimNao($dados['uso-tribuna'],'Selecione')."</select></p>
						</div>";
/*
			echo "		<div class='titulo-secundario' style='float:left; width:25%'>
							<p><b>Fluxo Tempo?</b></p>
							<p><select id='fluxo-tempo' name='fluxo-tempo' class='required'><option></select></p>
						</div>";
*/
			echo "	</div>";
		}

		if ($dadospagina['Tipo_Grupo_ID']=='72'){
			$dados = unserialize($tipoAuxiliar);
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario' style='float:left; width:20%'>
								<p><b>Quantidade de Parcelas?</b></p>
								<p><select name='dados-tipo[quantidade-parcelas]' id='quantidade-parcelas' class='quantidade-parcelas required'><option>".optionValueCountSelect(100, $dados['quantidade-parcelas'])."</option></select></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:10%'>&nbsp;</div>
							<div class='titulo-secundario' style='float:left; width:20%' id='qtde-parcelas-formas-pagamento'>".carregarParcelasFormasPagamento($dados)."</div>

							<!-- Tipo de modificador de valor a ser adicionado -->
							<div class='titulo-secundario' style='float:left; width:20%' id='tipo-bonus'>
								<p><b>Tipo de b&ocirc;nus dispon&iacute;vel</b></p>
								<select name='dados-tipo[tipo-bonus-disponivel]' id='tipo-bonus-disponivel' class='tipo-bonus-disponivel required'>
									<option value=''>Selecione...</option>
									<option value='Desconto'>Desconto </option>
									<option value='Acrescimo'>Acr&eacute;scimo</option>
								</select>
							</div>

							<!-- Valor do modificador de valor a ser adicionado -->
							<div class='titulo-secundario' style='float:left; width:20%'>
								<p><b>Valor a ser modificado em %</b></p>
								<p><input type='text' name='dados-tipo[valor_modificado]' id='valor_modificado' class='formata-numero'></p>
								<p id='errorValueModificador' style='color: red;display: none;'>Valor deve ser um número menor que 100<p>
							</div>

						</div>";
		}


		if ($dadospagina['Tipo_Grupo_ID']=='74'){
			$tExtra = unserialize($tipoAuxiliar);
			$classificacao = $tExtra['classificacao'];
			$dia = $tExtra['dia'];
			$mes = $tExtra['mes'];
			echo "		<div style='margin-top:3px;'>
							<div class='titulo-secundario' style='float:left; width:25%'>
								<p><b>Classifica&ccedil;&atilde;o (Estrelas)</b></p>
								<p><input type='text' name='dados-tipo[classificacao]' id='dados-tipo-classificacao' value='".$classificacao."'/></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:15%'>
								<p><b>Dia(s)</b></p>
								<p><input type='text' name='dados-tipo[dia]' id='dados-tipo-dia' class='formata-numero' value='".$dia."'/></p>
							</div>
							<div class='titulo-secundario' style='float:left; width:15%'>
								<p><b>Mes(es)</b></p>
								<p><input type='text' name='dados-tipo[mes]' id='dados-tipo-mes' class='formata-numero' value='".$mes."'/></p>
							</div>
						</div>";
		}

		echo "		</div>
				</div>
				<div id='item-container' class='titulo-container'>
					<div class='titulo' Style='margin-bottom:5px;'>
						<p>&nbsp;".$dadospagina[Titulo_Filho]." </p>
					</div>";

		$lixeira = mpress_query("select count(*) Total from tipo where Situacao_ID = 3 and Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]);
		$itens = mpress_fetch_array($lixeira); $totalItens = $itens[Total];
		if($totalItens >=1) $classeAddLixeira = "lixeira-cheia"; else $classeAddLixeira = "lixeira-vazia";
		echo "		<div class='cart-tipos exclui-dragable $classeAddLixeira' id='exclui-dragable-".$dadospagina[Tipo_Grupo_ID]."' title='$totalItens item(s) na lixeira'>
					<input type='hidden' name='id-lixeira' id='id-lixeira' value='".$dadospagina[Tipo_Grupo_ID]."'>
					<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='tipos'>
					</div>";

		$tipos = mpress_query("select Tipo_ID, Tipo_Auxiliar, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Situacao_ID = 1 order by Descr_Tipo");
		while($row = mpress_fetch_array($tipos)){
			$extras = "";

			/******************************************************************************/
			/******** CASO CENTRO DE CUSTO POSSIBILITAR BUSCAR CADASTRO RESPONSAVEL ********/
			/******************************************************************************/
			if ($dadospagina[Tipo_Grupo_ID]=="26"){
				$extras = "<div><!--Respons&aacute;vel:-->N/A</div>";
				if ($row[Tipo_Auxiliar] != ""){
					$rs = mpress_query("Select Nome from cadastros_dados where Cadastro_ID = '".$row[Tipo_Auxiliar]."'");
					if($rw = mpress_fetch_array($rs)){
						$extras = "<div><p style='white-space:nowrap;'><!--Respons&aacute;vel:-->".$rw[Nome]."</p></div>";
					}
				}
			}

			/******************************************************************************/
			/******** CASO PRIORIDADES CAMPO TEMPO E COR **********************************/
			/******************************************************************************/
			if ($dadospagina[Tipo_Grupo_ID]=="21"){
				if ($row[Tipo_Auxiliar] != ""){
					$dados = unserialize($row[Tipo_Auxiliar]);
					//$extras = "<div style='color:".$dados['cor']."'>".$dados['tempo']."</div>";
					$extras = "<br><div style='text-align:center; color:".$dados['cor-texto']."; background-color:".$dados['cor-fundo']."'>".$dados['tempo']."</div>";
				}
			}

			if (($dadospagina[Tipo_Grupo_ID]=='18') || ($dadospagina[Tipo_Grupo_ID]=='51') || ($dadospagina[Tipo_Grupo_ID]=='53') || ($dadospagina[Tipo_Grupo_ID]=='60')){
				$dados = unserialize($row[Tipo_Auxiliar]);
				$extras = "<br><div style='text-align:center; color:".$dados['cor-texto']."; background-color:".$dados['cor-fundo']."'>".$dados['posicao']." - CORES</div>";
			}


			/******************************************************************************/
			/******** CASO TIPO DE CONTA ENTRADA E SAIDA **********************************/
			/******************************************************************************/
			if ($dadospagina[Tipo_Grupo_ID]=="28"){
				$dados = unserialize($row[Tipo_Auxiliar]);
				if (($dados['entrada']=='1') || ($row[Tipo_Auxiliar]=="")) $extras .= "<p> Entrada&nbsp;&nbsp;&nbsp;&nbsp;";
				if (($dados['saida']=='1') || ($row[Tipo_Auxiliar]=="")) $extras .= "<p> Sa&iacute;da&nbsp;&nbsp;&nbsp;&nbsp;</p>";
				$extras = "<br><div>$extras</div>";
			}

			/******************************************************************************/
			/******** CASO CFOP	- CODIGO E TIPO E/S**********************************/
			/******************************************************************************/
			if ($dadospagina[Tipo_Grupo_ID]=="44"){
				if ($row[Tipo_Auxiliar] != ""){
					$dados = unserialize($row[Tipo_Auxiliar]);
					if ($dados['tipo-cfop']=="E") $tipoCFOP = "Entrada";
					if ($dados['tipo-cfop']=="S") $tipoCFOP = "Sa&iacute;da";
					$extras = "<div>".$dados['codigo-cfop']." - ".$tipoCFOP."</div>";
				}
			}
			/******************************************************************************/

			/******************************************************************************/
			/******** CASO CAMPUS 					**********************************/
			/******************************************************************************/
			if ($dadospagina[Tipo_Grupo_ID]=="47"){
				$resultSet = mpress_query("select t.Descr_Tipo as Universidade from modulos_vinculos mv
										inner join tipo t on t.Tipo_ID = mv.Tipo_Principal_ID
										where mv.Nome_tabela = 'tipo' and mv.Tipo_Secundario_ID = '".$row['Tipo_ID']."' and mv.Valor_Vinculo = '46'");
				if($rs = mpress_fetch_array($resultSet)){
					$universidade = $rs['Universidade'];
					$extras = "<div>$universidade</div>";
				}
				/*
				$rs = mpress_query("select Tipo_Secundario_ID from modulos_vinculos mv where mv.Nome_tabela = 'tipo' and mv.Tipo_Principal_ID = '$tipoID' and mv.Valor_Vinculo = '47'");
				while($row = mpress_fetch_array($rs))
					$cursosSelecionados .= $row['Tipo_Secundario_ID'].",";
				$cursosSelecionados = substr($cursosSelecionados, 0, -1);
				*/

			}
			/******************************************************************************/

			$classe='item titulo-drag-drop';
			if ($row[Tipo_ID] < 1000)
				$classe='item titulo-drag-drop titulo-tipo-fixo';

			echo "	<div class='$classe editar-tipo' Style='margin-bottom:5px;margin-left:5px; min-width:100px;' tipo-id='".$row['Tipo_ID']."'>
						<p class='esconde'>".$row['Tipo_ID']."</p>
						<div><p style='white-space:nowrap;'>".$row['Descr_Tipo']."&nbsp;<p></div>
						$extras
					</div>";
		}
		echo "		</div><div class='pula-linha'>&nbsp;</div>";
	}

	function carregarParcelasFormasPagamento($dados){
		$qtde = $dados['quantidade-parcelas'];
		if ($qtde>0){
			$h = "	<div class='titulo-secundario' style='float:left; width:30%'>
						<p><b>&nbsp;</b></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:70%'>
						<p><b>Per&iacute;odo (dias)</b></p>
					</div>";
			for ($i = 1; $qtde >= $i; $i++){
				$diasParcela = $dados['dias'][$i];
				if ($diasParcela=='')
					$diasParcela = ($i-1) * 30;
				$h .= "	<div class='titulo-secundario' style='float:left; width:30%'>
							<p><b>Parcela $i</b></p>
						</div>
						<div class='titulo-secundario' style='float:left; width:70%'>
							<p><input type='text' name='dados-tipo[dias][$i]' id='dados-tipo-dias-$i' class='formata-numero' value='$diasParcela'></p>
						</div>";
			}
		}
		return $h;
	}


	function montaRadioGrupo($idGrupo, $selecionado, $condicao){
		$tipos = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = '$idGrupo' and Situacao_ID = 1 $condicao order by descr_tipo");
		while($tipo = mpress_fetch_array($tipos)){
			if ($selecionado==$tipo['Tipo_ID']){$seleciona='checked';}else{$seleciona='';}
			$radios.= "<input type='radio' class='radio-tipo-grupo-$idGrupo' name='radio-tipo-grupo-$idGrupo' id='radio-tipo-grupo-$idGrupo-".$tipo['Tipo_ID']."' value='".$tipo['Tipo_ID']."' $seleciona>
						<label for='radio-tipo-grupo-$idGrupo-".$tipo['Tipo_ID']."'> ".($tipo['Descr_Tipo'])."</label>&nbsp;";
		}
		return $radios;
	}

	function montaCheckboxGroupo($idGrupo, $selecionado, $condicao){
		$tiposSelecionados = unserialize($selecionado);
		$tipos = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = '$idGrupo' and Situacao_ID = 1 $condicao order by descr_tipo");
		while($tipo = mpress_fetch_array($tipos)){
			$seleciona='';
			foreach ($tiposSelecionados as $i => $value) {
				if ($tiposSelecionados[$i]==$tipo['Tipo_ID']){$seleciona='checked';}
			}
			$radios.= "	<div Style='float:left;width:20%;overflow:inherit;height:18px;background-color:#ffffff;min-width:120px;'>
							<div Style='float:left;'><input type='checkbox' class='check-tipo-grupo' name='check-tipo-grupo-".$idGrupo."[]' id='check-tipo-grupo-$idGrupo-".$tipo['Tipo_ID']."' value='".$tipo['Tipo_ID']."' $seleciona></div>
							<div Style='float:left;width:80%' class='check-tipo-grupo'><label for='check-tipo-grupo-$idGrupo-".$tipo['Tipo_ID']."'> ".($tipo['Descr_Tipo'])."</label>&nbsp;</div>
						</div> ";
		}
		return $radios;
	}

	function optionValueGrupoMes($selecionado) {
		$meses = array (1 => "Janeiro", 2 => "Fevereiro", 3 => "Mar&ccedil;o", 4 => "Abril", 5 => "Maio", 6 => "Junho", 7 => "Julho", 8 => "Agosto", 9 => "Setembro", 10 => "Outubro", 11 => "Novembro", 12 => "Dezembro");
		foreach($meses as $chave => $mes){
			if ($selecionado==$chave){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='$chave' $seleciona>$mes</option>";
		}
		return $optionValue;
 	}


	function optionValueGrupoUF($selecionado, $textoPrimeiro){
		if($textoPrimeiro==""){$textoPrimeiro="Selecione";}
		$optionValue = "<option value=''>$textoPrimeiro</option>";
		$rs = mpress_query("select distinct UF from cadastros_enderecos where UF is not null and UF <> '' and Situacao_ID = 1 order by UF ");
		while($row = mpress_fetch_array($rs)){
			if ($selecionado==$row['UF']){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$row['UF']."' $seleciona>".($row['UF'])."</option>";
		}
		return $optionValue;
	}

	function optionValueGrupo($idGrupo, $selecionado, $textoPrimeiro, $condicao, $orderBy = "descr_tipo"){
		if ($selecionado!="")
			$condicao .= " or Tipo_ID = '$selecionado'";
		if ($textoPrimeiro=="") $textoPrimeiro="Selecione";
		$optionValue = "<option value=''>$textoPrimeiro</option>";
		$sql = "select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = '$idGrupo' and Situacao_ID = 1 $condicao order by $orderBy";
		$tipos = mpress_query($sql);
		while($tipo = mpress_fetch_array($tipos)){
			if ($selecionado==$tipo['Tipo_ID']){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$tipo['Tipo_ID']."' $seleciona>".($tipo['Descr_Tipo'])."</option>";
		}
		return $optionValue;
	}

	//Função que irá retornar todos os parceiros para serem filtrados
	function optionValueParceirosGrupo($idParceiro, $selecionado, $condicao){

		$optionValue = "";

		if($idParceiro!=""){
			$condicao .= " and Parceiro_ID = '$idParceiro'";
		}
		$sql = "SELECT Parceiro_ID, Nome_Parceiro FROM sistema_parceiros WHERE Status_ID = 1 $condicao";
		$parceiros = mpress_query($sql);

		if(empty($parceiros)){

			$optionValue = "<option value='0' selected> Nenhum parceiro encontrado </option>";

		}else{

			$optionValue .= "<option value='0'> Selecione </option>";

			while($parceiro = mpress_fetch_array($parceiros)){

				if(	$selecionado==$parceiro['Parceiro_ID'] ){
					$seleciona='selected';
				}else{
					$seleciona='';
				}

				$optionValue .= "<option value='".$parceiro['Parceiro_ID']."' $seleciona>".($parceiro['Nome_Parceiro'])."</option>";
			}

		}

		return $optionValue;
	}

	function optionValueGrupoMultiplo($idGrupo, $selecionados, $condicoes){
		if (is_array(unserialize($selecionados))){
			foreach(unserialize($selecionados) as $selAux){
				$strSelecionados .= $selAux.",";
			}
			$selecionados = substr($strSelecionados,0,-1);
		}
		$arrSelecionados = explode(",",$selecionados);
		$tipos = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = '$idGrupo' and Situacao_ID = 1 $condicoes order by descr_tipo");
		while($tipo = mpress_fetch_array($tipos)){
			if (strlen(array_search($tipo[Tipo_ID],$arrSelecionados))>=1){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$tipo['Tipo_ID']."' $seleciona>".($tipo['Descr_Tipo'])."</option>";
		}
		return $optionValue;
	}

	function optionValueCadastros($selecionado, $condicoes){
		$sel[$selecionado] = ' selected ';
		$rsCadastro = mpress_query("select Cadastro_ID, Nome from cadastros_dados where Situacao_ID = 1 $condicoes order by Nome");
		while($rsCad = mpress_fetch_array($rsCadastro)){
			$optionValue .= "<option value='".$rsCad['Cadastro_ID']."' ".$sel[$rsCad['Cadastro_ID']].">".($rsCad['Nome'])."</option>";
		}
		return $optionValue;
	}



	function verificaNumeroEmpresas(){
		$numeroEmpresas = 0;
		$query = mpress_query("Select count(*) Numero_Empresas from cadastros_dados where Empresa = 1 and Situacao_ID = 1");
		if($rs = mpress_fetch_array($query)){
			$numeroEmpresas = $rs[Numero_Empresas];
		}
		return $numeroEmpresas;
	}

	function retornaCodigoEmpresa(){
		$query = mpress_query("Select Cadastro_ID from cadastros_dados where Empresa = 1 and Situacao_ID = 1");
		if($rs = mpress_fetch_array($query)){
			$cadastroID = $rs[Cadastro_ID];
		}
		return $cadastroID;
	}

	function optionValueEmpresas($selecionado){
		if ($selecionado!=''){
			$sqlCond = " or Cadastro_ID = '$selecionado'";
		}
		$empresas = mpress_query("Select Cadastro_ID, Nome, Nome_Fantasia from cadastros_dados where Empresa = 1 and Situacao_ID = 1 $sqlCond order by Nome");
		while($rs = mpress_fetch_array($empresas)){
			if ($rs[Cadastro_ID]==$selecionado){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$rs['Cadastro_ID']."' $seleciona>".($rs['Nome'])."</option>";
		}
		return $optionValue;
	}


	function optionValueFormularios($selecionado, $tabelaEstrangeira, $sqlCond){
		if ($selecionado!=''){
			$sqlCond = " or Formulario_ID = '$selecionado'";
		}
		if ($tabelaEstrangeira!=''){
			$sqlCond = " and Tabela_Estrangeira = '$tabelaEstrangeira'";
		}
		//echo "select Formulario_ID, Nome from formularios where Situacao_ID = 1 $sqlCond order by Nome";
		$resultSet = mpress_query("select Formulario_ID, Nome from formularios where Situacao_ID = 1 $sqlCond order by Nome");
		while($rs = mpress_fetch_array($resultSet)){
			if ($rs[Formulario_ID]==$selecionado){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$rs['Formulario_ID']."' $seleciona>".($rs['Nome'])."</option>";
		}
		return $optionValue;
	}


	function optionValueSimNao($selecionado, $textoPrimeiro){
		$seleciona[$selecionado] = " selected ";
		if ($textoPrimeiro!=''){
			$optionValue .= "<option value=''>$textoPrimeiro</option>";
		}
		$optionValue .= "<option value='1' ".$seleciona[1].">Sim</option>";
		$optionValue .= "<option value='0' ".$seleciona[0].">N&atilde;o</option>";
		return $optionValue;
	}


	function optionValueEmpresasNF($selecionado){
		$empresas = mpress_query("Select cd.Cadastro_ID, cd.Nome, cd.Nome_Fantasia
									from cadastros_dados cd
									inner join nf_config nf on nf.Empresa_ID = cd.Cadastro_ID
									where cd.Empresa = 1 and cd.Situacao_ID = 1 and nf.Situacao_ID = 1 order by Nome");
		while($rs = mpress_fetch_array($empresas)){
			if ($rs[Cadastro_ID]==$selecionado){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$rs['Cadastro_ID']."' $seleciona>".($rs['Nome'])."</option>";
		}
		return $optionValue;
	}


	function optionValueEmpresasMultiplo($selecionados){
		$arrSelecionados = explode(",",$selecionados);
		$empresas = mpress_query("Select Cadastro_ID, Nome, Nome_Fantasia from cadastros_dados where Empresa = 1 and Situacao_ID in (1,3) order by Nome");
		while($rs = mpress_fetch_array($empresas)){
			if (strlen(array_search($rs[Cadastro_ID],$arrSelecionados))>=1){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$rs['Cadastro_ID']."' $seleciona>".($rs['Nome'])."</option>";
		}
		return $optionValue;
	}

	function optionValueGrupoMultiploUF($selecionados){
		$arrSelecionados = explode(",",$selecionados);
		$rs = mpress_query("select distinct UF from cadastros_enderecos where UF is not null and UF <> '' order by UF");
		while($row = mpress_fetch_array($rs)){
			if (strlen(array_search($row[UF],$arrSelecionados))>=1){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$row['UF']."' $seleciona>".($row['UF'])."</option>";
		}
		return $optionValue;
	}

	function optionValueEnderecos($cadastroID, $enderecoSelecionaID, $condicoes){
		$resultado = mpress_query("select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
											from cadastros_enderecos ce
											left join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
											where Cadastro_ID = '$cadastroID' $condicoes");
		$optionEnderecos = "<option></option>";
		while($rowE = mpress_fetch_array($resultado)){
			if ($enderecoSelecionaID==$rowE['Cadastro_Endereco_ID']) $enderecoSelected = "selected"; else $enderecoSelected = "";
			$optionEnderecos .= "<option value='".$rowE['Cadastro_Endereco_ID']."' $enderecoSelected>".($rowE[Logradouro])."&nbsp;".$rowE[Numero]."&nbsp;".($rowE[Complemento])." &nbsp;&nbsp;CEP:".$rowE[CEP]."&nbsp;&nbsp;".($rowE[Referencia])." &nbsp;&nbsp;".($rowE[Bairro])."&nbsp;&nbsp;".($rowE[Cidade])."&nbsp;&nbsp;".$rowE[UF]."</option>";
		}
		return $optionEnderecos;
	}

	function optionValueTelefones($cadastroID, $telefoneSelecionadoID, $condicoes){
		$resultado = mpress_query("select Cadastro_Telefone_ID, t.Descr_Tipo, Telefone, Observacao
									from cadastros_telefones ct
									left join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
									where ct.Situacao_ID = 1
										and Cadastro_ID = $cadastroID
										order by  t.Descr_Tipo, Telefone $condicoes");
		$optionTelefones = "<option></option>";
		$sel[$telefoneSelecionadoID] = " selected ";
		while($row = mpress_fetch_array($resultado)){
			$optionTelefones .= "<option value='".$row['Cadastro_Telefone_ID']."' ".$sel[$row['Cadastro_Telefone_ID']].">".$row[Telefone]."&nbsp;".$row[Numero]."&nbsp;".$row[Observacao]."</option>";
		}
		return $optionTelefones;

	}

	function optionValueArray($array, $selecionado){
		for($i=1;$i<=count($array[value]);$i++){
			if ($array['value'][$i] == $selecionado){$seleciona = " selected ";}else{$seleciona = "";}
			$optionValue .= "<option value='".$array['value'][$i]."' $seleciona>".$array['value'][$i]."</option>";
		}
		return $optionValue;
	}

	function optionValueArrayMultiplo($array, $selecionados){
		$arrSelecionados = explode(",",$selecionados);
		foreach ($array as $chave => $dado){
			if (strlen(array_search($dado['value'],$arrSelecionados))>=1){$seleciona='selected';}else{$seleciona='';}
			$optionValue .= "<option value='".$dado[value]."' $seleciona>".$dado[descricao]."</option>";
		}
		return $optionValue;
	}

	function optionValueCount($quantidade){
		$optionValue = "<option value=''>Selecione</option>";
		for($i=1;$i<=$quantidade;$i++)
			$optionValue .= "<option value='$i'>$i</option>";
		return $optionValue;
	}

	function optionValueCountSelect($quantidade, $selecionado, $textoPrimeiro, $texto, $texto2){
		if ($textoPrimeiro!=""){
			$optionValue = "<option value=''>$textoPrimeiro</option>";
		}
		if (is_array($selecionado)){
			foreach($selecionado as $selAux)
				$sel[$selAux] = " selected ";
		}
		else
			$sel[$selecionado] = "selected";

		for($i=1;$i<=$quantidade;$i++){
			$j="";
			$optionValue .= "<option value='$i' ".$sel[$i].">$texto $i $texto2</option>";
		}
		return $optionValue;
	}

	function optionValueOportunidadePerc($selecionado){
		$sel[$selecionado] = "selected";
		for($i=10; $i<100; $i = ($i+10)){
			$optionValue .= "<option value='".$i."' ".$sel[$i].">".$i." %</option>";
		}
		return $optionValue;
	}


	function optionValueForm(){
 		$dadospagina = get_page_content();
		$optionValue = "<option value=''>Selecione</option>
						<option value='0'>Nenhum</option>";
		$tipos = mpress_query("select Formulario_ID, Titulo from modulos_formularios where situacao_id = 1 and Modulo_ID = ".$dadospagina[Modulo_ID]." order by Titulo");
		while($tipo = mpress_fetch_array($tipos)){
			$optionValue .= "<option value='".$tipo['Formulario_ID']."'>".($tipo['Titulo'])."</option>";
		}
		return $optionValue;
	}


 	function gerenciaCamposCadastraNovo(){
 		$dadospagina = get_page_content();
 		echo "	<div class='titulo-container'>
 					<div class='titulo'>
 						<p>Cadastrar ".$dadospagina[Titulo_Filho]."</p>
 					</div>
 					<div class='conteudo-interno'>
 						<div class='titulo-secundario duas-colunas'>
 							<p>T&iacute;tulo</p>
 							<p><input type='text' name='titulo' id='titulo'/></p>
 						</div>
 						<div class='titulo-secundario duas-colunas'>
	 						<div class='titulo-secundario duas-colunas'>
		 						<div class='titulo-secundario duas-colunas'>
		 							<p>Tipo de Campo</p>
		 							<p>
	 									<select name='tipo-campo' id='tipo-campo'>".optionValueGrupo(5)."</select>
		 							</p>
								</div>
		 						<div class='titulo-secundario duas-colunas'>
		 							<p>N&ordm; de Op&ccedil;&otilde;es</p>
		 							<p>
 									<select name='opcoes-campo' id='opcoes-campo'>".optionValueCount(50)."</select>
		 							</p>
								</div>
							</div>
	 						<div class='titulo-secundario duas-colunas'>
								<p>Mascara</p>
	 							<p>
 									<select name='mascara-campo' id='mascara-campo'>".optionValueGrupo(6)."</select>
	 							</p>
							</div>
 						</div>

 						<div class='titulo-secundario uma-coluna' id='campos-secundarios' Style='margin-top:5px'></div>

						<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>
 							<p class='direita'><input type='button' value='Cadastrar' id='".$dadospagina[Slug_Pagina_Filho]."-".$dadospagina[Modulo_ID]."' class='cadastra-novo-campo'/></p>
 						</div>
 					</div>
 				</div>";
	}


 	function tinyMCE($id, $destinoDocumento){
 		global $caminhoSistema, $caminhoFisico, $modulosGeral;
		$tagsDisponiveis = "menu: [		{text: 'Datas', menu: [
															{text: 'Data', onclick: function() {editor.insertContent('[data]');}},
															{text: 'Data por Extenso', onclick: function() {editor.insertContent('[data-extenso]');}}]},";
		if ($destinoDocumento != ""){
			if($destinoDocumento == 'cadastros'){
				/*CASO MODULO IGREJA ATIVO*/
				if (($modulosGeral['igreja']) && (file_exists($caminhoFisico."/modulos/igreja/functions.php"))){
					$tagsDisponiveis .= "	{text: 'Tags Igreja', menu:[
											{text: 'Estado Civil', onclick: function() {editor.insertContent('[estado-civil]');}},
											{text: 'Data Batismo', onclick: function() {editor.insertContent('[data-batismo]');}},
											{text: 'Nome Pai', onclick: function() {editor.insertContent('[nome-pai]');}},
											{text: 'Nome Mae', onclick: function() {editor.insertContent('[nome-mae]');}},
											{text: 'Naturalidade Cidade', onclick: function() {editor.insertContent('[cidade-natural]');}},
											{text: 'Naturalidade Estado', onclick: function() {editor.insertContent('[uf-natural]');}},
											{text: 'Congregacao', onclick: function() {editor.insertContent('[congregacao]');}}
										]},";
				}

				$tagsDisponiveis .= "		{text: 'Area de Atuacao', onclick: function() {editor.insertContent('[areas-atuacao]');}},
											{text: 'Codigo', onclick: function() {editor.insertContent('[codigo]');}},
											{text: 'Cpf/Cnpj', onclick: function() {editor.insertContent('[cpf-cnpj]');}},
											{text: 'Data de Nascimento', onclick: function() {editor.insertContent('[data-nascimento]');}},
											{text: 'Email', onclick: function() {editor.insertContent('[email]');}},
											{text: 'Enderecos', menu:[
												{text: 'Endereco Principal', menu:[
													{text: 'Endereco Completo', onclick: function() {editor.insertContent('[endereco-principal-completo]');}},
													{text: 'Bairro', onclick: function() {editor.insertContent('[endereco-principal-bairro]');}},
													{text: 'CEP', onclick: function() {editor.insertContent('[endereco-principal-cep]');}},
													{text: 'Cidade', onclick: function() {editor.insertContent('[endereco-principal-cidade]');}},
													{text: 'Complemento', onclick: function() {editor.insertContent('[endereco-principal-complemento]');}},
													{text: 'Logradouro', onclick: function() {editor.insertContent('[endereco-principal-logradouro]');}},
													{text: 'Numero', onclick: function() {editor.insertContent('[endereco-principal-numero]');}},
													{text: 'UF', onclick: function() {editor.insertContent('[endereco-principal-uf]');}}
												]},
												{text: 'Todos os Enderecos', onclick: function() {editor.insertContent('[enderecos]');}}
											]},
											{text: 'Inscricao Estadual', onclick: function() {editor.insertContent('[inscricao-estadual]');}},
											{text: 'Inscricao Municipal', onclick: function() {editor.insertContent('[inscricao-municipal]');}},
											{text: 'Nome Fantasia', onclick: function() {editor.insertContent('[nome-fantasia]');}},
											{text: 'Nome/Razao Social', onclick: function() {editor.insertContent('[nome-razao-social]');}},
											{text: 'Observa&ccedil;&atilde;o', onclick: function() {editor.insertContent('[observacao]');}},
											{text: 'Foto', onclick: function() {editor.insertContent('[foto]');}},
											{text: 'RG', onclick: function() {editor.insertContent('[rg]');}},
											{text: 'Telefones', menu:[
												{text: 'Comercial', onclick: function() {editor.insertContent('[telefone-comercial]');}},
												{text: 'Celular', onclick: function() {editor.insertContent('[telefone-celular]');}},
												{text: 'Residencial', onclick: function() {editor.insertContent('[telefone-residencial]');}},
												{text: 'Todos os Telefones', onclick: function() {editor.insertContent('[telefones]');}}
											]},
											{text: 'Tipo Pessoa', onclick: function() {editor.insertContent('[tipo-pessoa]');}}";
			}
			if($destinoDocumento == 'chamados'){
				$tagsDisponiveis .= "		{text: 'C&oacute;digo', onclick: function() {editor.insertContent('[codigo]');}},
											{text: 'Data Abertura', onclick: function() {editor.insertContent('[data-abertura]');}},
											{text: 'ID ".$_SESSION['objeto']."', onclick: function() {editor.insertContent('[id]');}},
											{text: 'Lista Produtos', onclick: function() {editor.insertContent('[produtos]');}},
											{text: 'Lista Tarefas', onclick: function() {editor.insertContent('[tarefas]');}},
											{text: 'Prioridade', onclick: function() {editor.insertContent('[prioridade]');}},
											{text: 'Situacao', onclick: function() {editor.insertContent('[situacao]');}},
											{text: 'Tipo', onclick: function() {editor.insertContent('[tipo]');}},

											{text: 'Solicitante', menu:[{text: 'Areas de Atuacao', onclick: function() {editor.insertContent('[solicitante-areas-atuacao]');}},
												{text: 'Codigo', onclick: function() {editor.insertContent('[solicitante-codigo]');}},
												{text: 'CPF/CNPJ', onclick: function() {editor.insertContent('[solicitante-cpf-cnpj]');}},
												{text: 'Email', onclick: function() {editor.insertContent('[solicitante-email]');}},
												{text: 'Enderecos', menu:[
													{text: 'Endereco Principal', menu:[
														{text: 'Endereco Completo', onclick: function() {editor.insertContent('[solicitante-endereco-principal]');}},
														{text: 'Bairro', onclick: function() {editor.insertContent('[solicitante-principal-bairro]');}},
														{text: 'CEP', onclick: function() {editor.insertContent('[solicitante-principal-cep]');}},
														{text: 'Cidade', onclick: function() {editor.insertContent('[solicitante-principal-cidade]');}},
														{text: 'Complemento', onclick: function() {editor.insertContent('[solicitante-principal-complemento]');}},
														{text: 'Logradouro', onclick: function() {editor.insertContent('[solicitante-principal-logradouro]');}},
														{text: 'Numero', onclick: function() {editor.insertContent('[solicitante-principal-numero]');}},
														{text: 'UF', onclick: function() {editor.insertContent('[solicitante-principal-uf]');}}
													]},
													{text: 'Todos os Enderecos', onclick: function() {editor.insertContent('[solicitante-enderecos]');}}
												]},
												{text: 'ID', onclick: function() {editor.insertContent('[solicitante-id]');}},
												{text: 'Inscricao Estadual', onclick: function() {editor.insertContent('[solicitante-inscricao-estadual]');}},
												{text: 'Inscricao Municipal', onclick: function() {editor.insertContent('[solicitante-inscricao-municipal]');}},
												{text: 'Nome/Razao Social', onclick: function() {editor.insertContent('[solicitante-nome-razao-social]');}},
												{text: 'Nome Fantasia', onclick: function() {editor.insertContent('[solicitante-nome-fantasia]');}},
												{text: 'Telefones', menu:[
													{text: 'Celular', onclick: function() {editor.insertContent('[solicitante-telefone-celular]');}},
													{text: 'Comercial', onclick: function() {editor.insertContent('[solicitante-telefone-comercial]');}},
													{text: 'Residencial', onclick: function() {editor.insertContent('[solicitante-telefone-residencial]');}},
													{text: 'Todos os Telefones', onclick: function() {editor.insertContent('[solicitante-telefones]');}}
												]}
											]}";
			}
			if($destinoDocumento == 'compras'){
				$tagsDisponiveis .= "		{text: 'Data Cadastro Compra', onclick: function() {editor.insertContent('[data-cadastro-compra]');}},
											{text: 'Data Limite Compra', onclick: function() {editor.insertContent('[data-limite-compra]');}},
											{text: 'ID Compra', onclick: function() {editor.insertContent('[id-compra]');}},
											{text: 'Produtos', onclick: function() {editor.insertContent('[produtos]');}},
											{text: 'Responsavel', onclick: function() {editor.insertContent('[nome-responsavel]');}},
											{text: 'Situacao Compra', onclick: function() {editor.insertContent('[situacao-compra]');}}";
			}
			if($destinoDocumento == 'envios'){
				$tagsDisponiveis .= "		{text: 'Codigo do Chamado', onclick: function() {editor.insertContent('[codigo-chamado]');}},
											{text: 'Codigo Origem', onclick: function() {editor.insertContent('[codigo-origem]');}},
											{text: 'Codigo Rastreamento Envio', onclick: function() {editor.insertContent('[codigo-rastreamento]');}},
											{text: 'Data Envio', onclick: function() {editor.insertContent('[data-envio]');}},
											{text: 'Data Entrega', onclick: function() {editor.insertContent('[data-entrega]');}},

											{text: 'Destinatario', menu:[
												{text: 'Cpf/Cnpj', onclick: function() {editor.insertContent('[cpf-cnpj-para]');}},
												{text: 'Codigo', onclick: function() {editor.insertContent('[codigo-para]');}},
												{text: 'Email', onclick: function() {editor.insertContent('[email-para]');}},
												{text: 'Enderecos', menu:[
													{text: 'Endereco Principal', menu:[
														{text: 'Endereco Completo', onclick: function() {editor.insertContent('[endereco-principal-para]');}},
														{text: 'Bairro', onclick: function() {editor.insertContent('[bairro-principal-para]');}},
														{text: 'CEP', onclick: function() {editor.insertContent('[cep-principal-para]');}},
														{text: 'Cidade', onclick: function() {editor.insertContent('[cidade-principal-para]');}},
														{text: 'Complemento', onclick: function() {editor.insertContent('[complemento-principal-para]');}},
														{text: 'Logradouro', onclick: function() {editor.insertContent('[logradouro-principal-para]');}},
														{text: 'Numero', onclick: function() {editor.insertContent('[numero-principal-para]');}},
														{text: 'UF', onclick: function() {editor.insertContent('[uf-principal-para]');}}
													]},
													{text: 'Todos os Enderecos', onclick: function() {editor.insertContent('[enderecos-para]');}},
												]},
												{text: 'Foto', onclick: function() {editor.insertContent('[foto-para]');}},
												{text: 'Inscricao Estadual', onclick: function() {editor.insertContent('[inscricao-estadual-para]');}},
												{text: 'Nome Fantasia', onclick: function() {editor.insertContent('[nome-fantasia-para]');}},
												{text: 'Nome/Razao Social', onclick: function() {editor.insertContent('[nome-razao-social-para]');}},
												{text: 'Telefones', menu:[
													{text: 'Celular', onclick: function() {editor.insertContent('[telefone-celular-para]');}},
													{text: 'Comercial', onclick: function() {editor.insertContent('[telefone-comercial-para]');}},
													{text: 'Residencial', onclick: function() {editor.insertContent('[telefone-residencial-para]');}},
													{text: 'Todos os Telefones', onclick: function() {editor.insertContent('[telefones-para]');}}
												]}
											]},

											{text: 'Endereco Destino', onclick: function() {editor.insertContent('[endereco-destino]');}},
											{text: 'Endereco Origem', onclick: function() {editor.insertContent('[endereco-origem]');}},
											{text: 'Forma de Envio', onclick: function() {editor.insertContent('[forma-envio]');}},
											{text: 'ID Envio', onclick: function() {editor.insertContent('[id-envio]');}},
											{text: 'ID Origem', onclick: function() {editor.insertContent('[id-origem]');}},
											{text: 'Lista Produtos Romaneio', onclick: function() {editor.insertContent('[lista-produtos-romaneio]');}},
											{text: 'Previsao Entrega', onclick: function() {editor.insertContent('[data-previsao-entrega]');}},

											{text: 'Remetente', menu:[
												{text: 'Cpf/Cnpj', onclick: function() {editor.insertContent('[cpf-cnpj-de]');}},
												{text: 'Codigo', onclick: function() {editor.insertContent('[codigo-de]');}},
												{text: 'Email', onclick: function() {editor.insertContent('[email-de]');}},
												{text: 'Enderecos', menu:[
													{text: 'Endereco Principal', menu:[
														{text: 'Endereco Principal', onclick: function() {editor.insertContent('[endereco-principal-de]');}},
														{text: 'Bairro', onclick: function() {editor.insertContent('[bairro-principal-de]');}},
														{text: 'CEP', onclick: function() {editor.insertContent('[cep-principal-de]');}},
														{text: 'Cidade', onclick: function() {editor.insertContent('[cidade-principal-de]');}},
														{text: 'Complemento', onclick: function() {editor.insertContent('[complemento-principal-de]');}},
														{text: 'Logradouro', onclick: function() {editor.insertContent('[logradouro-principal-de]');}},
														{text: 'Numero', onclick: function() {editor.insertContent('[numero-principal-de]');}},
														{text: 'UF', onclick: function() {editor.insertContent('[uf-principal-de]');}}
													]},
													{text: 'Todos os Enderecos', onclick: function() {editor.insertContent('[enderecos-de]');}}
												]},
												{text: 'Foto', onclick: function() {editor.insertContent('[foto-de]');}},
												{text: 'Inscricao Estadual', onclick: function() {editor.insertContent('[inscricao-estadual-de]');}},
												{text: 'Nome Fantasia', onclick: function() {editor.insertContent('[nome-fantasia-de]');}},
												{text: 'Nome/Razao Social', onclick: function() {editor.insertContent('[nome-razao-social-de]');}},
												{text: 'Telefones', menu:[
													{text: 'Celular', onclick: function() {editor.insertContent('[telefone-celular-de]');}},
													{text: 'Comercial', onclick: function() {editor.insertContent('[telefone-comercial-de]');}},
													{text: 'Residencial', onclick: function() {editor.insertContent('[telefone-residencial-de]');}},
													{text: 'Todos os Telefones', onclick: function() {editor.insertContent('[telefones-de]');}}
												]}
											]},

											{text: 'Situacao Envio', onclick: function() {editor.insertContent('[situacao-envio]');}},

											{text: 'Transportador', menu:[
												{text: 'Cpf/Cnpj', onclick: function() {editor.insertContent('[cpf-cnpj-trans]');}},
												{text: 'Codigo', onclick: function() {editor.insertContent('[codigo-trans]');}},
												{text: 'Email', onclick: function() {editor.insertContent('[email-trans]');}},
												{text: 'Enderecos', menu:[
													{text: 'Endereco Principal', menu:[
														{text: 'Endereco Completo', onclick: function() {editor.insertContent('[endereco-principal-trans]');}},
														{text: 'Bairro', onclick: function() {editor.insertContent('[bairro-principal-trans]');}},
														{text: 'CEP', onclick: function() {editor.insertContent('[cep-principal-trans]');}},
														{text: 'Cidade', onclick: function() {editor.insertContent('[cidade-principal-trans]');}},
														{text: 'Complemento', onclick: function() {editor.insertContent('[complemento-principal-trans]');}},
														{text: 'Logradouro', onclick: function() {editor.insertContent('[logradouro-principal-trans]');}},
														{text: 'Numero', onclick: function() {editor.insertContent('[numero-principal-trans]');}},
														{text: 'UF', onclick: function() {editor.insertContent('[uf-principal-trans]');}}
													]},
													{text: 'Todos os Enderecos', onclick: function() {editor.insertContent('[enderecos-trans]');}}
												]},
												{text: 'Foto', onclick: function() {editor.insertContent('[foto-trans]');}},
												{text: 'Inscricao Estadual', onclick: function() {editor.insertContent('[inscricao-estadual-trans]');}},
												{text: 'Nome Fantasia', onclick: function() {editor.insertContent('[nome-fantasia-trans]');}},
												{text: 'Nome/Razao Social', onclick: function() {editor.insertContent('[nome-razao-social-trans]');}},
												{text: 'Telefones', menu:[
													{text: 'Celular', onclick: function() {editor.insertContent('[telefone-celular-trans]');}},
													{text: 'Comercial', onclick: function() {editor.insertContent('[telefone-comercial-trans]');}},
													{text: 'Residencial', onclick: function() {editor.insertContent('[telefone-residencial-trans]');}},
													{text: 'Todos os Telefones', onclick: function() {editor.insertContent('[telefones-trans]');}}
												]}
											]}";
			}
			if($destinoDocumento == 'orcamentos'){
				$tagsDisponiveis .= "		{text: 'Codigo', onclick: function() {editor.insertContent('[codigio]');}},
											{text: 'Data Abertura', onclick: function() {editor.insertContent('[data-abertura]');}},
											{text: 'Data Finalizado', onclick: function() {editor.insertContent('[data-finalizado]');}},
											{text: 'Produtos / Servicos Categorizados', onclick: function() {editor.insertContent('[produtos-servicos-categorizados]');}},
											{text: 'Situacao', onclick: function() {editor.insertContent('[situacao]');}},
											{text: 'Solicitante', menu:[
												{text: 'Nome', onclick: function() {editor.insertContent('[solicitante-nome]');}},
												{text: 'Telefone', onclick: function() {editor.insertContent('[solicitante-telefone]');}}
											]},
											{text: 'Representante', menu:[
												{text: 'Nome', onclick: function() {editor.insertContent('[representante-nome]');}},
												{text: 'Telefone', onclick: function() {editor.insertContent('[representante-telefone]');}}
											]},
											{text: 'Tarefas', onclick: function() {editor.insertContent('[tarefas]');}},
											{text: 'Titulo', onclick: function() {editor.insertContent('[titulo]');}},
											{text: 'Vendedor/Representante', onclick: function() {editor.insertContent('[vendedor-representante]');}}";
			}

			if($destinoDocumento == 'turmas'){
				$tagsDisponiveis .= "		{text: 'Ano Formatura', onclick: function() {editor.insertContent('[ano-formatura]');}},
											{text: 'Campus', onclick: function() {editor.insertContent('[campus]');}},
											{text: 'Conselho Diretor', menu:[";

				/* Busca todos os tipos de cargos para as tags */
				$tiposCargos = mpress_query("select Descr_Tipo from tipo where Tipo_Grupo_ID = 50");
				while($row = mpress_fetch_array($tiposCargos)){
					$cargo = retiraCaracteresEspeciais(strtolower($row['Descr_Tipo']));
					$tagsDisponiveis .= "		{text: '".$row['Descr_Tipo']."', menu:[
													{text: 'CPF', onclick: function() {editor.insertContent('[".$cargo."-cpf]');}},
													{text: 'Email', onclick: function() {editor.insertContent('[".$cargo."-email]');}},
													{text: 'Nome', onclick: function() {editor.insertContent('[".$cargo."-nome]');}},
													{text: 'RG', onclick: function() {editor.insertContent('[".$cargo."-rg]');}},
													{text: 'Bairro', onclick: function() {editor.insertContent('[".$cargo."-bairro]');}},
													{text: 'CEP', onclick: function() {editor.insertContent('[".$cargo."-cep]');}},
													{text: 'Cidade', onclick: function() {editor.insertContent('[".$cargo."-cidade]');}},
													{text: 'Complemento', onclick: function() {editor.insertContent('[".$cargo."-complemento]');}},
													{text: 'Logradouro', onclick: function() {editor.insertContent('[".$cargo."-logradouro]');}},
													{text: 'Numero', onclick: function() {editor.insertContent('[".$cargo."-numero]');}},
													{text: 'UF', onclick: function() {editor.insertContent('[".$cargo."-uf]');}},
													{text: 'Telefone', onclick: function() {editor.insertContent('[".$cargo."-telefone]');}}
												]},";
				}

				/* Retiro a ultima virgula */
				$tagsDisponiveis = substr($tagsDisponiveis, 0, -1);

				$tagsDisponiveis .= "		]},
											{text: 'Curso', onclick: function() {editor.insertContent('[curso]');}},
											{text: 'Faculdade/Universidade', onclick: function() {editor.insertContent('[faculdade-universidade]');}},
											{text: 'Nome Turma', onclick: function() {editor.insertContent('[nome-turma]');}},
											{text: 'Semestre', onclick: function() {editor.insertContent('[semestre]');}}";
			}
			$tagsDisponiveis .= "		]";
		}

 		$texto = "	<script>
						tinymce.init({
						relative_urls: false,
						convert_urls: false,
						selector: 'textarea#$id',
							theme: 'modern',
							width: '99.8%',
							language: 'pt_BR',
							plugins: [
								 'advlist autolink link image lists charmap print preview hr anchor pagebreak',
								 'searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking',
								 'table contextmenu directionality emoticons template paste textcolor'
							],
							content_css: 'css/content.css',
							toolbar: ' bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | print preview  fullpage | forecolor backcolor | fontsizeselect | styleselect | Tags',";
		if ($destinoDocumento != ""){
			$texto .= "			setup: function(editor) {
								editor.addButton('Tags', {
									type: 'menubutton',
									text: 'Inserir Tags',
									icon: false,
									$tagsDisponiveis
								});
							},";
		}
		$texto .= "				style_formats: [
								{title: 'Open Sans', inline: 'span', styles: { 'font-family':'Open Sans'}},
								{title: 'Arial', inline: 'span', styles: { 'font-family':'arial'}},
								{title: 'Book Antiqua', inline: 'span', styles: { 'font-family':'book antiqua'}},
								{title: 'Comic Sans MS', inline: 'span', styles: { 'font-family':'comic sans ms,sans-serif'}},
								{title: 'Courier New', inline: 'span', styles: { 'font-family':'courier new,courier'}},
								{title: 'Georgia', inline: 'span', styles: { 'font-family':'georgia,palatino'}},
								{title: 'Helvetica', inline: 'span', styles: { 'font-family':'helvetica'}},
								{title: 'Impact', inline: 'span', styles: { 'font-family':'impact,chicago'}},
								{title: 'Symbol', inline: 'span', styles: { 'font-family':'symbol'}},
								{title: 'Tahoma', inline: 'span', styles: { 'font-family':'tahoma'}},
								{title: 'Terminal', inline: 'span', styles: { 'font-family':'terminal,monaco'}},
								{title: 'Times New Roman', inline: 'span', styles: { 'font-family':'times new roman,times'}},
								{title: 'Verdana', inline: 'span', styles: { 'font-family':'Verdana'}}
							],
        				});
					</script>
					<style>
						.mce-btn{height:27px;}
					</style>";
 		echo $texto;
 	}

 	function optionValueCadastrosEnderecos($cadastroID, $selecionado){
 		$sql = "select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
				from cadastros_enderecos ce
				left join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
				where Cadastro_ID = '$cadastroID'
				and ce.Situacao_ID = 1
				order by Descr_Tipo";

		$sel[$selecionado] = " selected ";

		$resultSetCE = mpress_query($sql);
		while($rsCE = mpress_fetch_array($resultSetCE)){
			$optionValueCadastrosEnderecos .= "<option value='".$rsCE['Cadastro_Endereco_ID']."'  ".$sel[$rsCE['Cadastro_Endereco_ID']].">
												".$rsCE['Descr_Tipo']."&nbsp;&nbsp;".$rsCE['Logradouro']."&nbsp;".$rsCE['Numero']."&nbsp;".$rsCE['Complemento']."
												&nbsp;&nbsp;CEP:".$rsCE['CEP']."&nbsp;&nbsp;".$rsCE['Referencia']."
												&nbsp;&nbsp;".$rsCE['Bairro']."&nbsp;&nbsp;".$rsCE['Cidade']."&nbsp;&nbsp;".$rsCE['UF']."</option>";
		}
		return $optionValueCadastrosEnderecos;
 	}


	function carregarFoto($foto,$altura,$largura){
		global $caminhoSistema;
		global $caminhoFisico;
		if($foto != ""){
			if(file_exists("$caminhoFisico/uploads/$foto")){
				if(substr($foto,-3)=='jpg'){
					$j = imagecreatefromjpeg("$caminhoSistema/uploads/$foto");
					$width 		= imagesx($j);
					$height 	= imagesy($j);
				}else{
					$j = imagecreatefrompng("$caminhoSistema/uploads/$foto");
					$width 		= imagesx($j);
					$height 	= imagesy($j);
				}
				if($width >= $height){
					$new_width	= "$altura";
					$new_height = "";
					$new_widthPorcento = (($new_width*100)/$width)/100;
					$new_height = $height * $new_widthPorcento;
				}else{
					$new_width	= "";
					$new_height = "$altura";
					$new_heightPorcento = (($new_height*100)/$height)/100;
					$new_width = $width * $new_heightPorcento;
				}
				if($new_height >= $altura){
					$new_width	= "";
					$new_height = "$altura";
					$new_heightPorcento = (($new_height*100)/$height)/100;
					$new_width = $width * $new_heightPorcento;
				}
				if($new_width >= $largura){
					$new_width	= $largura;
					$new_height = "";
					$new_widthPorcento = (($new_width*100)/$width)/100;
					$new_height = $height * $new_widthPorcento;
				}
				$imagem = "<img src='$caminhoSistema/uploads/$foto' width='$new_width' height='$new_height' style='cursor:pointer'><input type='hidden' id='arquivo-imagem' name='arquivo-imagem' value='$foto'>";
			}else{
				$imagem = "<img src='../images/geral/imagem-usuario.jpg' width='$largura' height='$altura' style='cursor:pointer'><input type='hidden' id='arquivo-imagem' name='arquivo-imagem' value=''>";
			}
		}else{
			$imagem = "<img src='../images/geral/imagem-usuario.jpg' width='$largura' height='$altura' style='cursor:pointer'><input type='hidden' id='arquivo-imagem' name='arquivo-imagem' value=''>";
		}
		return $imagem;
	}

	function formataCPF_CNPJ($valor){
		$valor = str_replace('.','',str_replace('/','',str_replace('-','',$valor)));
		if(strlen($valor)<=11){
			for($i=strlen($valor);$i<11;$i++) $valor = "0".$valor;
		}else{
			for($i=strlen($valor);$i<14;$i++) $valor = "0".$valor;
		}
		return $valor;
	}

	function preencheTextoSeVazio($texto, $campo){
		if ($campo=="")
			return $texto;
		else
			return $campo;
	}

	function removeAcentos($var){
		$var = ereg_replace("[ÁÀÃÂÄ]","A",$var);
		$var = ereg_replace("[áàãâä]","a",$var);
		$var = ereg_replace("[ÉÈÊË]","E",$var);
		$var = ereg_replace("[éèêë]","e",$var);
		$var = ereg_replace("[ÍÌÎÏ]","I",$var);
		$var = ereg_replace("[íìîï]","i",$var);
		$var = ereg_replace("[ÓÒÕÔÖ]","O",$var);
		$var = ereg_replace("[óòõôö]","o",$var);
		$var = ereg_replace("[ÚÙÛÜ]","U",$var);
		$var = ereg_replace("[úùûü]","u",$var);
		$var = str_replace("Ç","C",$var);
		$var = str_replace("ç","c",$var);
		return $var;
	}

	function soNumeros($str) {
		$retorno = trim(preg_replace("/[^0-9]/", "", $str));
		return $retorno;
	}

	function retiraCaracteresEspeciais($resultado){
		$resultado = removeAcentos($resultado);
		$resultado = str_replace('(','', $resultado);
		$resultado = str_replace(')','', $resultado);
		$resultado = str_replace("'","", $resultado);
		$resultado = str_replace('"','', $resultado);
		$resultado = str_replace("/","_", $resultado);
		$resultado = str_replace("*","", $resultado);
		$resultado = str_replace("$","", $resultado);
		$resultado = str_replace("%","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("&","e", $resultado);
		$resultado = str_replace("@","", $resultado);
		$resultado = str_replace("|","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		$resultado = str_replace("`","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("#","", $resultado);
		$resultado = str_replace(":","", $resultado);
		$resultado = str_replace(";","", $resultado);
		$resultado = str_replace(">","", $resultado);
		$resultado = str_replace("<","", $resultado);
		$resultado = str_replace("+","", $resultado);
		$resultado = str_replace("~","", $resultado);
		$resultado = str_replace("^","", $resultado);
		$resultado = str_replace("=","", $resultado);
		$resultado = str_replace("}","", $resultado);
		$resultado = str_replace("{","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		return $resultado;
	}

	function criaSlug($resultado){
		$resultado = removeAcentos($resultado);
		$resultado = str_replace(' ','_', $resultado);
		$resultado = str_replace('&','_', $resultado);
		$resultado = str_replace('-','_', $resultado);
		$resultado = str_replace('(','', $resultado);
		$resultado = str_replace(')','', $resultado);
		$resultado = str_replace('__','_', $resultado);
		$resultado = str_replace('__','_', $resultado);
		$resultado = str_replace(' ','', $resultado);
		$resultado = str_replace("'","", $resultado);
		$resultado = str_replace('"','', $resultado);
		$resultado = str_replace("/","_", $resultado);
		$resultado = str_replace("*","", $resultado);
		$resultado = str_replace("$","", $resultado);
		$resultado = str_replace("%","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("&","e", $resultado);
		$resultado = str_replace("@","", $resultado);
		$resultado = str_replace("|","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		$resultado = str_replace("`","", $resultado);
		$resultado = str_replace("?","", $resultado);
		$resultado = str_replace("#","", $resultado);
		$resultado = str_replace(":","", $resultado);
		$resultado = str_replace(";","", $resultado);
		$resultado = str_replace(">","", $resultado);
		$resultado = str_replace("<","", $resultado);
		$resultado = str_replace("+","", $resultado);
		$resultado = str_replace("~","", $resultado);
		$resultado = str_replace("^","", $resultado);
		$resultado = str_replace("=","", $resultado);
		$resultado = str_replace("}","", $resultado);
		$resultado = str_replace("{","", $resultado);
		$resultado = str_replace("[","", $resultado);
		$resultado = str_replace("]","", $resultado);
		$resultado = strtolower(trim($resultado));
		return $resultado;
	}


 	function retornaDataHora($tipo, $formato){
 		$correcao = 0;
 		if(date_default_timezone_get() == "America/Los_Angeles")
 			$correcao = 4;
 		if(date_default_timezone_get() == "UTC")
 			$correcao = -3;
 		if(date_default_timezone_get() == "Europe/Paris")
 			$correcao = -5;
 		if(date_default_timezone_get() == "Europe/Berlin")
 			$correcao = -5;

        $hora = date("H");
        $minuto = date("i");
        $novo_horario = mktime($hora + $correcao);
        if($tipo=='d')
         	return date($formato, $novo_horario);
		else if ($tipo=='h')
         	return date($formato, $novo_horario);
		else
         	return date($formato, $novo_horario);
	}


	function somarData($data, $dias, $meses, $anos, $horas, $minutos){
		$resData = "";
		if (strlen($data)==10){
			$data = explode("/", $data);
			$resData = date("d/m/Y", mktime(0, 0, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $anos));
		}
		if (strlen($data)==16){
			$hora = explode(":", substr($data,11,5));
			$data = explode("/", substr($data,0,10));
			$resData = date("d/m/Y H:i", mktime($hora[0] + $horas, $hora[1] + $minutos, 0, $data[1] + $meses, $data[0] + $dias, $data[2] + $anos));
		}
		return $resData;
	}


	function verificaSelected($a,$b){
		if ($a==$b)
			return "selected";
		else
			return "";
	}

 	function geraTelaFormularios(){
		echo "	<script>
					$(function() {
						$('#container-formulario').sortable();
						$('#container-formulario').disableSelection();
					});
				</script>";

 	 		$dadospagina = get_page_content();
 	 		echo "	<input type='hidden' id='tipo-grupo' name='tipo-grupo' value='".$dadospagina[Tipo_Grupo_ID]."'/>
 	 				<input type='hidden' name='slug' id='slug' value=''>
 	 				<div class='titulo-container'>
 	 					<div class='titulo'>
 	 						<p>Cadastrar novo Campo</p>
 	 					</div>
 	 					<div class='conteudo-interno cart-tipos-editar'>
 	 						<div class='titulo-secundario uma-coluna'>
 	 							<p>T&iacute;tulo</p>
 	 							<p>
 	 								<input type='hidden' id='tipo-id' name='tipo-id'/>
 	 								<input type='text' id='titulo' name='titulo'/>
 	 							</p>
 	 						</div>

 	 						<div class='titulo-secundario tres-colunas'>
 	 							<p>Tipo</p>
 	 							<p>
 	 								<input type='text' id='tipo' name='tipo'/>
 	 							</p>
 	 						</div>


 	 						<div class='titulo-secundario uma-coluna' Style='margin-top:5px;'>
 	 							<p class='direita'><input type='button' value='Salvar' id='".$dadospagina[Titulo]."' class='cadastra-tipo'/></p>
 	 						</div>
 	 					</div>
 	 				</div>

 	 				<div id='container-formulario' class='titulo-container'>
 	 					<div class='titulo' Style='margin-bottom:5px;'>
 	 						<p>&nbsp;".$dadospagina[Titulo]." </p>
 	 					</div>";

			$lixeira = mpress_query("select count(*) Total from tipo where Situacao_ID = 3 and Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]);
			$itens = mpress_fetch_array($lixeira); $totalItens = $itens[Total];
			if($totalItens >=1) $classeAddLixeira = "lixeira-cheia"; else $classeAddLixeira = "lixeira-vazia";
	 		echo "		<div class='cart-tipos exclui-dragable $classeAddLixeira' id='exclui-dragable-".$dadospagina[Tipo_Grupo_ID]."' title='$totalItens item(s) na lixeira'>
	 					<input type='hidden' name='id-lixeira' id='id-lixeira'>
	 					<input type='hidden' name='tipo-lixeira' id='tipo-lixeira' value='tipos'>
	 					</div>";


 	 		$tipos = mpress_query("select Tipo_ID, Descr_Tipo from tipo where Tipo_Grupo_ID = ".$dadospagina[Tipo_Grupo_ID]." and Situacao_ID = 1 order by Descr_Tipo");
 	 		while($row = mpress_fetch_array($tipos)){
 		 			$classe='item titulo-drag-drop';
 	 				if ($row[Tipo_ID] < 1000)
 			 			$classe='item titulo-drag-drop titulo-tipo-fixo';

 	 				echo "	<div class='$classe' Style='margin-bottom:5px;margin-left:5px;width:98.5%;'>
 	 							<p>".$row['Descr_Tipo']."</p>
 	 							<p class='esconde'>".$row['Tipo_ID']."</p>
 	 						</div>";
 	 		}
 	 		echo "		</div><div class='pula-linha'>&nbsp;</div>";
 	}


	/********************************* Inicio Funcoes Generica de localizacao de Cadastro para ser usada em outros moulos ******************/

	function cadastroSelecionaGeral(){
		$nomeCampo = $_GET["nome-campo"];
		$condicaoEmpresa = $_GET["condicao-empresa"];
		$descricao = utf8_decode($_POST["texto-cadastro-localiza-".$nomeCampo]);
		$idAtual = $_POST[$nomeCampo];
		if($_SESSION[dadosUserLogin][grupoID] == -2) $sqlCond .= " and (cd.Cadastro_ID in (select Cadastro_Filho_ID from cadastros_vinculos vc where vc.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."' and vc.Tipo_Vinculo_ID = 101) or cd.Cadastro_ID = '".$_SESSION[dadosUserLogin][userID]."') or (cd.Cadastro_ID < -999) ";

		if($_SESSION[dadosUserLogin][grupoID] == -3) $sqlCond .= " and cadastro_ID = ".$_SESSION[dadosUserLogin][userID]." ";
		if ($condicaoEmpresa=="1")  $sqlCond .= " and cd.Empresa = 1 ";


		$sql = "select Cadastro_ID, Descr_Tipo, Nome, Cpf_Cnpj, Codigo
					from cadastros_dados cd
					inner join tipo tp on Tipo_ID = Tipo_Pessoa and Tipo_Grupo_ID = 8
					where cd.Situacao_ID = 1 and cd.Cadastro_ID != -1
					and (Nome like '%$descricao%' or Nome_Fantasia like '%$descricao%' or Cpf_Cnpj like '%$descricao%' or Codigo like '%$descricao%')
					$sqlCond
					order by Nome, Nome_Fantasia Limit 100";
		//echo $sql;
		$resultado = mpress_query($sql);
		$i = 0;
		echo "<table width='100%' cellpadding='1' cellspacing='2' border='0'>
				<tr>
					<td class='fundo-escuro-titulo' width='10%'>ID</td>
					<td class='fundo-escuro-titulo' width='10%'>C&oacute;digo</td>
					<td class='fundo-escuro-titulo' width='40%'>Nome</td>
					<td class='fundo-escuro-titulo' width='20%'>Cpf / Cnpj</td>
					<td class='fundo-escuro-titulo' width='20%'>Tipo</td>
				</tr>";
		while($row = mpress_fetch_array($resultado)){
			$i++;

			$estSel[$idAtual] = "ls-sel";
			echo "	<tr style='cursor:pointer;' class='link seleciona-cadastro-geral' cadastro-id='".$row[Cadastro_ID]."' nome-campo='".$nomeCampo ."'>
						<td class='ls ".$estSel[$row[Cadastro_ID]]." ls-$i titulo-secundario' pos='$i'>".$row[Cadastro_ID]."</td>
						<td class='ls ".$estSel[$row[Cadastro_ID]]." ls-$i titulo-secundario' pos='$i'>".$row[Codigo]."</td>
						<td class='ls ".$estSel[$row[Cadastro_ID]]." ls-$i titulo-secundario' pos='$i'>".utf8_encode($row[Nome])."</td>
						<td class='ls ".$estSel[$row[Cadastro_ID]]." ls-$i titulo-secundario' pos='$i'>".$row[Cpf_Cnpj]."</td>
						<td class='ls ".$estSel[$row[Cadastro_ID]]." ls-$i titulo-secundario' pos='$i'>".utf8_encode($row[Descr_Tipo])."</td>
					</tr>";
		}
		if ($i==0){
			echo "	<tr><td class='titulo-secundario'><p style='margin:2px 5px 0 50px; text-align:left; color:red;'>Nenhum cadastro encontrado</p></td></tr>";
		}
		echo "</table>";
	}


	function carregarBlocoCadastroGeral($cadastroID, $nomeCampo, $descricaoCampo, $visualizarDetalhes, $parametro, $condicaoEmpresa, $selecionaEndereco, $classe, $atributos){
		global $caminhoSistema;
		$slugPagina = $_POST['slug-pagina'];
		$txtNaoInfo = "N&atilde;o Informado";
		if (($cadastroID!="")&&($cadastroID!="0")){
			$classeInsere = "esconde";
			$query = mpress_query("SELECT Nome FROM cadastros_dados WHERE Cadastro_ID = '$cadastroID'");
			if($rs = mpress_fetch_array($query)){
				$nome = $rs['Nome'];
			}
		}
		echo " <input type='hidden' id='$nomeCampo' name='$nomeCampo' class='$classe' $atributos value='$cadastroID' descricao-campo='$descricaoCampo' visualizar-detalhes='$visualizarDetalhes' parametro='$parametro' seleciona-endereco='$selecionaEndereco'/>
			  <div class='titulo-secundario $classeInsere' style='float:left;width:100%;' id='div-campos-consulta-".$nomeCampo."'>
				<p><span id='texto-localizar-".$nomeCampo."' style='float:left;'><i>Localizar ".$descricaoCampo." por C&oacute;digo, Nome, CPF, CNPJ</i></span></p>
				<p style='width:100%;'>
					<div style='float:left;width:100%'>
						<input type='text' class='texto-cadastro-localiza $classe' $atributos id='texto-cadastro-localiza-".$nomeCampo."' name='texto-cadastro-localiza-".$nomeCampo."' nome-campo='".$nomeCampo."' condicao-empresa='$condicaoEmpresa' autocomplete='off' style='width:95%;' value='$nome'/>
						<div style='float:right;margin-top:5px;margin-right:02px;' class='btn-cancelar limpar-cadastro-generico' nome-campo='".$nomeCampo."' title='Cancelar'>&nbsp;</div>
						<div style='float:right;margin-top:2px;margin-right:10px;' class='btn-mais btn-incluir-novo-cadastro' title='Incluir novo $descricaoCampo' >&nbsp;</div>
						<input type='button' id='aux-cad-new' class='seleciona-cadastro-geral' cadastro-id='' nome-campo='$nomeCampo' style='display:none;'/>
					</div>
				</p>
			  </div>
			  <div id='div-localiza-cadastro-".$nomeCampo."' class='esconde' style='float:left;width:100%;height:150px;overflow-x: hidden'></div>
			  <div id='div-cadastro-".$nomeCampo."'>";
		if (($cadastroID!="")&&($cadastroID!="0")){
			echo carregarCadastroGeral($cadastroID, $nomeCampo, $descricaoCampo, $selecionaEndereco);
			echo "<script>$('#botao-alterar-".$nomeCampo."').show();</script>";
		}else{
			echo "<script>$('#botao-alterar-".$nomeCampo."').hide();</script>";
		}
		echo " </div>";
	}


	function carregarCadastroGeral($cadastroID, $nomeCampo, $descricaoCampo, $selecionaEndereco){
		global $caminhoSistema, $caminhoFisico, $modulosGeral;
		$html = "";
		if (($modulosGeral['turmas']) && ($cadastroID < 0)){
			$sql = "select td.Turma_ID, ins.Descr_Tipo as Instituicao, cam.Descr_Tipo as Campus, cur.Descr_Tipo as Curso,
							tur.Descr_Tipo as Turno, per.Descr_Tipo as Periodo, td.Nome_Turma as Titulo, cad.Nome as Nome_Cadastro, cad.Email as Email, td.Quantidade as Numero_Formandos
					from turmas_dados td
					inner join cadastros_dados cad on cad.Cadastro_ID = td.Cadastro_ID
					inner join tipo ins on ins.Tipo_ID = td.Instituicao_ID
					inner join tipo cam on cam.Tipo_ID = td.Campus_ID
					left join tipo cur on cur.Tipo_ID = td.Curso_ID
					left join tipo per ON per.Tipo_ID = td.Periodo_ID
					left join tipo tur on tur.Tipo_ID = td.Turno_ID
					where cad.Cadastro_ID = '$cadastroID'";
			//echo $sql;
			$query = mpress_query($sql);
			if($cadastro = mpress_fetch_array($query)){
				$turmaID = $cadastro['Turma_ID'];
				$instituicao = $cadastro['Instituicao'];
				$campus = $cadastro['Campus'];
				$curso = $cadastro['Curso'];
				$periodo = $cadastro['Periodo'];
				$turno = $cadastro['Turno'];
				$titulo = $cadastro['Titulo'];
				$email = $cadastro['Email'];
				$numeroFormandos = $cadastro['Numero_Formandos'];
			}
			$html .= "<table width='100%' style='margin-top:1px;border:0px; margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center'>
						<tr>
							<td class='fundo-escuro-titulo' width='25%'>
								<p style='margin:2px 5px 0 5px;'>
									Nome da Institui&ccedil;&atilde;o
									<span class='btn-turma link link-geral-turma' cadastro-id='$cadastroID' turma-id='$turmaID'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</p>
							</td>
							<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Campus</p></td>
							<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Curso</p></td>
							<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Per&iacute;odo</p></td>
						</tr>
						<tr>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$instituicao</p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$campus</p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$curso</p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$periodo</p></td>
						</tr>
						<tr>
							<td class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>Turno</p></td>
							<td class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>T&iacute;tulo Turma</p></td>
							<td class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>Email</p></td>
							<td class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>N&ordm; Formandos:</p></td>

						</tr>
						<tr>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$turno</p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$titulo</p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$email <input type='hidden' class='email-workflow' value='$email'/></p></td>
							<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$numeroFormandos</p></td>
						</tr>
					</table>";
				$sql = " select d.Cadastro_ID, d.Nome, d.Email, t.Telefone, t1.Descr_Tipo Cargo
								from turmas_dados_alunos a
								inner join cadastros_dados d on d.Cadastro_ID = a.Cadastro_ID
								inner join cadastros_vinculos v on v.Cadastro_Filho_ID = d.Cadastro_ID
								inner join tipo t1 on t1.Tipo_ID = v.Tipo_Vinculo_ID and t1.Tipo_Grupo_ID = 50
								left join cadastros_telefones t on t.Cadastro_ID = a.Cadastro_ID
								where Turma_ID = '$cadastroID'
								and a.Situacao_ID = 1 ";
				//echo $sql;


				$resultado = mpress_query($sql);
				while($row = mpress_fetch_array($resultado)){
					$i++;
					$dados[colunas][classe][$i]  = "fundo-claro";
					$dados[colunas][conteudo][$i][1]  = "<p style='margin:2px 5px 0 5px;'>".$row['Nome']."</p>";
					$dados[colunas][conteudo][$i][2]  = "<p style='margin:2px 5px 0 5px;'>".$row['Cargo']."</p>";
					$dados[colunas][conteudo][$i][3]  = "<p style='margin:2px 5px 0 5px;'>".$row['Email']."<input type='hidden' class='email-workflow' value='".$row['Email']."'/>"."</p>";
					$dados[colunas][conteudo][$i][4]  = "<p style='margin:2px 5px 0 5px;'>".$row['Telefone']."</p>";
				}
				if ($i>0){
					$largura = "100%";
					$colunas = "4";
					$dados[colunas][titulo][classe] = "fundo-escuro-titulo";
					$dados[colunas][titulo][1] 	= "<p style='margin:2px 5px 0 5px;'>Nome</p>";
					$dados[colunas][titulo][2] 	= "<p style='margin:2px 5px 0 5px;'>Cargo</p>";
					$dados[colunas][titulo][3] 	= "<p style='margin:2px 5px 0 5px;'>Email</p>";
					$dados[colunas][titulo][4] 	= "<p style='margin:2px 5px 0 5px;'>Telefone</p>";
					$dados[colunas][tamanho][1] = $dados[colunas][tamanho][2] = $dados[colunas][tamanho][3] = $dados[colunas][tamanho][4] = "width='25%'";
					$html .= geraTabela($largura, $colunas, $dados, "margin-top:0px;border:0px solid silver;margin-bottom:0px;", 'lista-alunos', 4, 0, 100,'','return');
				}
		}
		else{
			$sql = "Select Codigo, Tipo_Pessoa, Tipo_Cadastro, Grupo_ID, Nome, Nome_Fantasia, Senha, Email, Data_Nascimento, Foto, Cpf_Cnpj,
								Inscricao_Municipal, Inscricao_Estadual, Tipo_Vinculo, Observacao, Usuario_Cadastro_ID, Situacao_ID
							from cadastros_dados where Cadastro_ID = '$cadastroID'";
			$query = mpress_query($sql);
			if($cadastro = mpress_fetch_array($query)){
				$tipoPessoa = $cadastro[Tipo_Pessoa];
				$codigo = preencheTextoSeVazio($txtNaoInfo,$cadastro[Codigo]);
				if ($tipoPessoa=="24"){
					$cpf = preencheTextoSeVazio($txtNaoInfo, $cadastro[Cpf_Cnpj]);
					$nomeCompleto =  preencheTextoSeVazio($txtNaoInfo, ($cadastro[Nome]));
					$dataNascimento = preencheTextoSeVazio($txtNaoInfo, $cadastro[Data_Nascimento]);
				}
				if ($tipoPessoa=="25"){
					$cnpj = preencheTextoSeVazio($txtNaoInfo, $cadastro[Cpf_Cnpj]);
					$razaoSocial = preencheTextoSeVazio($txtNaoInfo, ($cadastro[Nome]));
					$nomeFantasia = preencheTextoSeVazio($txtNaoInfo, ($cadastro[Nome_Fantasia]));
					$inscricaoEstadual = preencheTextoSeVazio($txtNaoInfo, $cadastro[Inscricao_Estadual]);
					$inscricaoMunicipal = preencheTextoSeVazio($txtNaoInfo, $cadastro[Inscricao_Municipal]);
				}
				$observacao = preencheTextoSeVazio($txtNaoInfo, ($cadastro[Observacao]));
				$emailOrig = $cadastro[Email];
				$email = preencheTextoSeVazio($txtNaoInfo, ($cadastro[Email]));
				$foto = $cadastro[Foto];

				$arrTiposCadastros = carregarArrayTipo(9);

				$tipoCadastro = "";
				foreach(unserialize($cadastro['Tipo_Cadastro']) as $chave => $tipo){
					if ($arrTiposCadastros[descricao][$tipo]!=''){
						$tipoCadastro .= $arrTiposCadastros[descricao][$tipo].", ";
					}
				}
				$tipoCadastro = substr($tipoCadastro,0,-2);
			}

			if (($foto!="") && (file_exists("$caminhoFisico/uploads/$foto")))
				$imagemFoto = "<img src='$caminhoSistema/uploads/$foto' id='imagem-foto' style='max-width:50px;'>";
			else
				$imagemFoto = "<img src='$caminhoSistema/images/geral/imagem-usuario.jpg' id='imagem-foto'>";

			$html .= "
				<table width='99%' style='margin-top:0px;border:0px;margin-bottom:0px;' cellpadding='0' cellspacing='0' align='center'>
						<tr>
							<td style='width:80px; cursor:pointer;' align='center' valign='middle' class='link link-geral-cadastro' cadastro-id='$cadastroID'>$imagemFoto</td>
							<td align='center' valign='top'>
								<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center'>
									<tr>";
			if ($tipoPessoa=="24"){
				$html .= "
										<td colspan='2' class='fundo-escuro-titulo' width='50%'><p style='margin:2px 5px 0 5px;'>Nome Completo</p></td>
										<td colspan='2' class='fundo-escuro-titulo' width='50%'><p style='margin:2px 5px 0 5px;'>CPF</p></td>
									</tr>
									<tr>
										<td colspan='2' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$nomeCompleto</p></td>
										<td colspan='2' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$cpf</p></td>
									</tr>";
			}

			if ($tipoPessoa=="25"){
						$html .= "
							<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Raz&atilde;o social</p></td>
									<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Nome Fantasia</p></td>
									<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>CNPJ</p></td>
									<td class='fundo-escuro-titulo' width='25%'><p style='margin:2px 5px 0 5px;'>Inscri&ccedil;&atilde;o Estadual &nbsp;</div></p></td>
								</tr>
								<tr>
									<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$razaoSocial</p></td>
									<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$nomeFantasia</p></td>
									<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$cnpj</p></td>
									<td class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$inscricaoEstadual</p></td>
								</tr>";
			}
			$html .= "			<tr>
									<td colspan='1' class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>C&oacute;digo $descricaoCampo</p></td>
									<td colspan='1' class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>E-mail</p></td>
									<td colspan='1' class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>Observa&ccedil;&atilde;o</p></td>
									<td colspan='1' class='fundo-escuro-titulo'><p style='margin:2px 5px 0 5px;'>Tipo Cadastro</p></td>
								</tr>
								<tr>
									<td colspan='1' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$codigo</td>
									<td colspan='1' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$email <input type='hidden' class='email-workflow' id='email-workflow' value='$emailOrig'></p></td>
									<td colspan='1' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$observacao</p></td>
									<td colspan='1' class='fundo-claro'><p style='margin:2px 5px 0 5px;'>$tipoCadastro</p></td>
								</tr>
							  </table>
							 </td>
						</tr>
				</table>

				<table width='99%' style='margin-top:0px;border:0px; margin-bottom:0px;' cellpadding='0' cellspacing='0' align='center'>
					<tr>
						<td width='59%' align='left' valign='top'>
							<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center'>
								<tr><td class='fundo-escuro-titulo'>Endere&ccedil;os Cadastrados</td></tr>";

			$sql = "select Cadastro_Endereco_ID, CEP, Logradouro, Numero, Complemento, Bairro, Cidade, UF, Referencia, Usuario_Cadastro_ID, Descr_Tipo
					from cadastros_enderecos ce
					inner join tipo t on t.Tipo_ID = ce.Tipo_Endereco_ID
					where Cadastro_ID = '$cadastroID'
					and ce.Situacao_ID = 1 ";

			$i=0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				if ($selecionaEndereco!=""){
					if ($selecionaEndereco==$row[Cadastro_Endereco_ID]) $selecionado = "checked"; else $selecionado = "";
					$radioSelecionaEndereco = "<input type='radio' name='".$nomeCampo."-endereco-id' id='".$nomeCampo."-endereco-id' value='".$row[Cadastro_Endereco_ID]."' $selecionado/>";
				}
				$html .= "		<tr>
									<td class='fundo-claro'>
										<p style='margin:2px 5px 0 5px;float:left;width:100%;'>
											$radioSelecionaEndereco
											<b>".($row[Descr_Tipo]).":</b>
											".($row[Logradouro])."&nbsp;".$row[Numero]."&nbsp;".($row[Complemento])."
											&nbsp;&nbsp;CEP:".$row[CEP]."&nbsp;&nbsp;".($row[Referencia])."
											&nbsp;&nbsp;".($row[Bairro])."&nbsp;&nbsp;".($row[Cidade])."&nbsp;&nbsp;".$row[UF]." &nbsp;&nbsp;
											<a href='https://maps.google.com.br/?q=".($row[Logradouro]).", ".$row[Numero]." ".($row[CEP])." ".($row[Cidade])." ".($row[UF])."' target='_blank' >Ver Mapa</a>
											<!--  class='fancybox fancybox.iframe' -->
										</p>
									</td>
								</tr>";
			}
			//if($i==0){ $html .= "	<tr><td><p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum endere&ccedil;o cadastrado</p></td></tr>"; }
			$html .= "		</table>
						</td>
						<td width='1%'>&nbsp;</td>
						<td width='40%' align='left' valign='top'>";

			$sql = "select Cadastro_Telefone_ID, t.Descr_Tipo, Telefone, Observacao
							from cadastros_telefones ct
							inner join tipo t on t.Tipo_ID = ct.Tipo_Telefone_ID
					where  ct.Situacao_ID = 1
					and Cadastro_ID = $cadastroID";
			$html .= "		<table width='100%' style='margin-top:1px;border:0px solid silver;margin-bottom:0px;' cellpadding='4' cellspacing='0' align='center' id='tabela-telefone-$nomeCampo'>
								<tr><td class='fundo-escuro-titulo'>Telefones Cadastrados</td></tr>";
			$i=0;
			$resultado = mpress_query($sql);
			while($row = mpress_fetch_array($resultado)){
				$i++;
				$html .= "		<tr>
									<td class='fundo-claro'><p Style='margin:0px;float:left;width:100%;'>
											<b>".($row[Descr_Tipo]).":</b>
											".$row[Telefone]."&nbsp;".$row[Numero]."&nbsp;".($row[Observacao])."</p>
									</td>
								</tr>";
			}
			//if($i==0){$html .= "		<tr><td><p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum telefone cadastrado</p></td></tr>";}
			$html .= "		</table>
						</td>
					</tr>
				</table>";
		}
		return $html;
	}

	function carregarListagemCadastrosGeral($sql, $parametro){
		$cadastroIDAnt = "";
		$resultado = mpress_query($sql);
		while($row = mpress_fetch_array($resultado)){
			if ($row[Cadastro_ID] != $cadastroIDAnt){
				$i++;
				$nome = $row[Nome];
				$dados[colunas][conteudo][$i][1] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cadastro_ID]."</p></span>";
				$dados[colunas][conteudo][$i][2] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$row[Codigo]."</p></span>";
				$dados[colunas][conteudo][$i][3] = "<span class='link cadastro-localiza' cadastro-id='".$row[Cadastro_ID]."'><p Style='margin:2px 5px 0 5px;float:left;'>".$nome."</p></span>";
				$dados[colunas][conteudo][$i][4] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Cpf_Cnpj]."</p>";
				$dados[colunas][conteudo][$i][5] = "<p Style='margin:2px 5px 0 5px;float:left;'>".$row[Email]."</p>";
				$dados[colunas][conteudo][$i][7] = "<p Style='margin:2px 5px 0 5px;'><div class='btn-excluir btn-excluir-cadastro-geral' style='float:right; padding-right:10px' parametro='".$row[Parametro]."' title='Excluir'>&nbsp;</div></div></p>";

				$telefones = "";
			}
			$telefones .= "<span Style='margin:2px 5px 0 5px;float:left;'>".$row['Telefone']."</span>";
			$dados[colunas][conteudo][$i][6] = $telefones;
			$cadastroIDAnt = $row[Cadastro_ID];
		}
		$largura = "100.2%";
		$colunas = "7";
		$dados[colunas][tamanho][1] = "width='6%'";
		$dados[colunas][tamanho][2] = "width='10%'";

		$dados[colunas][titulo][1] 	= "ID";
		$dados[colunas][titulo][2] 	= "C&oacute;digo";
		$dados[colunas][titulo][3] 	= "Nome";
		$dados[colunas][titulo][4] 	= "Cpf / Cnpj";
		$dados[colunas][titulo][5] 	= "Email";
		$dados[colunas][titulo][6] 	= "Telefones";
		$dados[colunas][titulo][7] 	= "";
		geraTabela($largura,$colunas,$dados);
		if($i==0){
			echo "<p Style='margin:2px 5px 0 5px;color:red; text-align:center'>Nenhum cadastro localizado</p>";
		}

	}
	/********************************* Fim  Funcoees Generica de localizacao de Cadastro *****************************************************/


	function cadastraLogAcesso($idTipoAcesso){
		global $dadosUserLogin;
		$dadospagina 	= get_page_content();
		$ipRemoto 	 	= $_SERVER['REMOTE_ADDR'];
		$idUsuario 	 	= $dadosUserLogin['userID'];
		$idModulo		 = $dadospagina['Modulo_ID'];
		if($dadospagina['Modulo_Pagina_Filho_ID'] != "") $idPaginaAcesso = $dadospagina['Modulo_Pagina_Filho_ID'];else $idPaginaAcesso = $dadospagina['Modulo_Pagina_ID'];
		if(count($dadospagina)==0) $idPaginaAcesso = -1;
		if($idPaginaAcesso != 4)
			mpress_query("insert into log_acessos(Usuario_ID, Pagina_ID, Modulo_ID, Chave_Estrangeira, Tipo_Acesso_ID, IP_Acesso)values('$idUsuario','$idPaginaAcesso','$idModulo','','$idTipoAcesso','$ipRemoto')");
		//bloqueiaRegistro();
	}

	function formataValorBD($valor){
		$valor = str_replace(',','.',str_replace('.','',$valor));
		if ($valor=="") $valor = "0.00";
		return $valor;
	}

	function formataDataHoraRelatorio($data){
		$dataNova = implode('/',array_reverse(explode('-',substr($data, 0, 10))));
		$dataNova .= " ".substr($data, 11, 5);
		$dataNova = "<br><span Style='font-size:11px'>$dataNova</span>";
		if($data == "0000-00-00 00:00:00")
			$dataNova = "";

		return $dataNova;
	}

	function formataData($data){
		$dataNova = implode('/',array_reverse(explode('-',substr($data, 0, 10))));
		if ($dataNova=="00/00/0000") $dataNova="";
		return $dataNova;
	}
	function formataDataBD($data){
		$dataNova = implode('-',array_reverse(explode('/',substr($data, 0, 10))));
		if ($dataNova=="") $dataNova="0000-00-00";
		return $dataNova;
	}

	function retornaNumeroDias($inicio,$fim) {
		if (($inicio!="")&&($fim!="")){
			$inicio = substr($inicio,0,10);
			$fim = substr($fim,0,10);
			//echo "$inicio ||| $fim";
			//exit();
			if ($inicio!=$fim){
				list($dia1,$mes1,$ano1) = split("/",$inicio);
				list($dia2,$mes2,$ano2) = split("/",$fim);
				$fimMK = mktime(0,0,0,$mes2,$dia2,$ano2);
				for ($i=1;$i>0;$i++) {
					$calcula = mktime(0,0,0,$mes1,$dia1+$i,$ano1);
					if (date('w',$calcula) == 0)
						$dom++;
					if (date('w',$calcula) == 6) {
						$sab++;
					}
					if (($calcula == $fimMK) || ($i > 365000))
						break;
				}
			}
		}
		return array('domingo' => $dom,'sabado' => $sab);
	}

	function paginadorTabela($paginasTotais, $paginaAtual, $quantidadeRegistros, $dados, $idTabela, $largura, $colunas){
		if($paginasTotais >= 2){
			$_SESSION[idTabela] 			= $idTabela;
			$_SESSION[$idTabela][dados] 	= $dados;
			$_SESSION[$idTabela][registros] = $quantidadeRegistros;
			$_SESSION[$idTabela][largura] 	= $largura;
			$_SESSION[$idTabela][colunas] 	= $colunas;
			for($i=1;$i<=$paginasTotais;$i++){
				if($i==$paginaAtual) $classe = "paginador-selecionado"; else $classe = "paginador";
				$paginador .= "<div class='$classe' attr-pagina='$i' id='paginador-$i' attr-tabela='div-$idTabela'>$i</div>";
			}
			return "<div id='paginador-container'>$paginador</div>";
		}
	}

 	function localizaCodigoMunicipio($uf, $cidade){
 		$codigo = "";
		$resultado = mpress_query("select distinct concat(uf,municipio) as Codigo from codigos_ibge where upper(Abreviatura_UF) = upper('$uf') and upper(Nome_Municipio) = upper('$cidade')");
		while($rs = mpress_fetch_array($resultado)){
			$codigo = $rs['Codigo'];
		}
 		return $codigo;
 		/*
	 	$cidade = str_replace(" ","-",$cidade);
		$retorno = "http://webservice.grupoinformare.com.br/modulos/nfe/codigos-ibge.php?uf=$uf&cidade=$cidade";
		//echo $retorno."&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>";
		$ch = curl_init($retorno);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$data = curl_exec($ch);
		curl_close($ch);
		$xml = new SimpleXmlElement($data, LIBXML_NOCDATA);
		return $xml->dadosconsulta->codigo_unificado[0];
		*/
 	}

	/*
	function optionValueCFOP($tipoCFOP, $selecionado, $tipoComparaSel = "N"){
		$tipos = mpress_query("select Tipo_ID, Descr_Tipo, Tipo_Auxiliar from tipo where tipo_grupo_id = '44' and Situacao_ID = 1 ");
		while($tipo = mpress_fetch_array($tipos)){
			$dados = unserialize($tipo['Tipo_Auxiliar']);
			if ((($tipoCFOP==44) && ($dados['tipo-cfop']=="E")) || (($tipoCFOP==45) && ($dados['tipo-cfop']=="S"))){
				if ($tipoComparaSel=="N"){if ($selecionado==$dados['codigo-cfop']){$seleciona='selected';}else{$seleciona='';}}
				if ($tipoComparaSel=="S"){if (strtoupper($selecionado)==strtoupper(removeAcentos($tipo['Descr_Tipo']))){$seleciona='selected';}else{$seleciona='';}}
				$optionValue .= "<option value='".$dados['codigo-cfop']."' descricao-cfop='".$tipo['Descr_Tipo']."' $seleciona>".$dados['codigo-cfop']." - ".($tipo['Descr_Tipo'])."</option>";
			}
		}
		return $optionValue;
	}
	*/

	function optionValueTributacao($array, $selecionado, $soNumeros = 0){
		$retorno = "";
		foreach ($array as $chave => $dado){
			if ($soNumeros==1) $chave = soNumeros($chave);
			if ($selecionado==$chave) $selected = "selected"; else $selected = "";
			$retorno .= "<option value='".$chave."' $selected>$chave - ".$dado[descricao]."</option>";
		}
		return $retorno;
	}

	function carregarArrayTipoTabela($tipoGrupoID){
		$rs1 = mpress_query("select Tipo_ID, Descr_Tipo, (coalesce(Tipo_Auxiliar,0) + 0) as Tipo_Pai_ID from tipo where Tipo_Grupo_ID in ($tipoGrupoID) order by Tipo_Pai_ID");
		while($row1 = mpress_fetch_array($rs1)){
			$array[tipoPaiID][$row1["Tipo_ID"]] = $row1["Tipo_Pai_ID"];
			$array[descricao][$row1["Tipo_ID"]] = $row1["Descr_Tipo"];
			$array[familia][$row1["Tipo_ID"]] = $row1["Tipo_ID"];
			if ($row1[Tipo_Pai_ID]=="0"){
				$array[nivel][$row1["Tipo_ID"]] = "1";
			}
		}
		for ($i = 1; $i <= 5; $i++) {
			foreach ($array[tipoPaiID] as $chave => $pai){
				if (($pai>0) && ($array[nivel][$pai]==$i))
					$array[nivel][$chave] = ($i+1);
			}
		}
		foreach ($array[nivel] as $chave => $dado){
			if ($dado>1)
				$array[descricao][$chave] = "<table><tr><td width='50%'>".$array[descricao][$array[tipoPaiID][$chave]]."</td><td width='50%'>".$array[descricao][$chave]."</td></tr></table>";
		}

		for ($i=5; $i >= 1; $i--) {
			foreach ($array[tipoPaiID] as $chave => $pai){
				if (($pai>0) && ($array[nivel][$chave]==$i))
					$array[familia][$pai] .= ",".$array[familia][$chave];
			}
		}
		return $array;
	}

	function carregarArrayTipo($tipoGrupoID){
		$rs1 = mpress_query("select Tipo_ID, Descr_Tipo, (coalesce(Tipo_Auxiliar,0) + 0) as Tipo_Pai_ID from tipo where Tipo_Grupo_ID in ($tipoGrupoID) order by Tipo_Pai_ID");
		while($row1 = mpress_fetch_array($rs1)){
			$array[tipoPaiID][$row1["Tipo_ID"]] = $row1["Tipo_Pai_ID"];
			$array[descricao][$row1["Tipo_ID"]] = $row1["Descr_Tipo"];
			$array[familia][$row1["Tipo_ID"]] = $row1["Tipo_ID"];
			if ($row1[Tipo_Pai_ID]=="0"){
				$array[nivel][$row1["Tipo_ID"]] = "1";
			}
		}
		for ($i = 1; $i <= 5; $i++) {
			foreach ($array[tipoPaiID] as $chave => $pai){
				if (($pai>0) && ($array[nivel][$pai]==$i))
					$array[nivel][$chave] = ($i+1);
			}
		}
		foreach ($array[nivel] as $chave => $dado){
			if ($dado>1)
				$array[descricao][$chave] = $array[descricao][$array[tipoPaiID][$chave]]." - ".$array[descricao][$chave];
		}

		for ($i=5; $i >= 1; $i--) {
			foreach ($array[tipoPaiID] as $chave => $pai){
				if (($pai>0) && ($array[nivel][$chave]==$i))
					$array[familia][$pai] .= ",".$array[familia][$chave];
			}
		}
		return $array;
	}

	// ARRUMAR ESSE TRAMBOLHO ABAIXO, USAR A FUNCAO carregarArrayTipo() como base
	function optionValueGrupoFilho($idGrupo, $selecionado, $textoPrimeiro, $tipo){
		if ($tipo=='multiple'){
			if (is_array($selecionado)){
				foreach($selecionado as $selecionadoAux){
					$sel[$selecionadoAux] = " selected ";
				}
			}
		}
		else{
			$sel[$selecionado] = " selected ";
			if($textoPrimeiro==""){
				$textoPrimeiro="Selecione";
			}
			$optionValue = "<option value=''>$textoPrimeiro</option>";
		}

		$query = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and (Tipo_Auxiliar is null or Tipo_Auxiliar = '') and Situacao_ID = 1 order by descr_tipo");
		while($categoria1 = mpress_fetch_array($query)){
			$optionValue .= "<option value='".$categoria1['Tipo_ID']."' ".$sel[$categoria1['Tipo_ID']].">".$categoria1['Descr_Tipo']."</option>";
			$query2 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria1['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
			while($categoria2 = mpress_fetch_array($query2)){
				$optionValue .= "<option value='".$categoria2['Tipo_ID']."' ".$sel[$categoria2['Tipo_ID']].">&nbsp;&nbsp;&nbsp; ".$categoria2['Descr_Tipo']."</option>";
				$query3 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria2['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
				while($categoria3 = mpress_fetch_array($query3)){
					$optionValue .= "<option value='".$categoria3['Tipo_ID']."' ".$sel[$categoria3['Tipo_ID']].">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria3['Descr_Tipo']."</option>";
					$query4 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria3['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
					while($categoria4 = mpress_fetch_array($query4)){
						$optionValue .= "<option value='".$categoria4['Tipo_ID']."' ".$sel[$categoria4['Tipo_ID']].">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria4['Descr_Tipo']."</option>";
						$query5 = mpress_query("select Tipo_ID, Descr_Tipo from tipo where tipo_grupo_id = $idGrupo and Tipo_Auxiliar ='".$categoria4['Tipo_ID']."' and Situacao_ID = 1 order by descr_tipo");
						while($categoria5 = mpress_fetch_array($query5)){
							$optionValue .= "<option value='".$categoria5['Tipo_ID']."' ".$sel[$categoria5['Tipo_ID']].">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria5['Descr_Tipo']."</option>";
						}
					}
				}
			}
		}
		return $optionValue;
	}



	function optionValueGruposAcessos($selecionado, $condicoes, $texto){
		if ($texto=="") $texto = "Selecione";
		if (is_array($selecionado)){
			foreach($selecionado as $gruposSelecionados){
				$sel[$gruposSelecionados] = " selected ";
			}
		}
		else{
			$sel[$selecionado] = " selected ";
		}

		if ($texto!="0") $optionValueGruposAcessos .= "<option value=''>$texto</option>";
		$sql = "select distinct m.Modulo_Acesso_ID as Modulo_Acesso_ID, m.Titulo as Titulo
									from modulos_acessos m
									inner join cadastros_dados c on c.Grupo_ID = m.Modulo_Acesso_ID
									where c.Situacao_ID = 1
									$condicoes
									and m.Situacao_ID = 1 order by Titulo";
		$grupos = mpress_query($sql);
		while($row = mpress_fetch_array($grupos)){
			//if ($selecionado==$row['Modulo_Acesso_ID']) $seleciona = ' selected '; else $seleciona = '' ;
			$optionValueGruposAcessos .=  " <option value='".$row['Modulo_Acesso_ID']."' ".$sel[$row['Modulo_Acesso_ID']].">".$row['Titulo']."</option>";
		}
		return $optionValueGruposAcessos;
	}

	function optionValueUsuarios($selecionado, $grupo, $condicoes, $texto, $multiple){
		if ($texto=="") $texto = "Selecione";
		if ($grupo !=""){
			if (is_array($grupo)){
				foreach($grupo as $grupoSelecionado)
					$gruposAux .= $grupoSelecionado.",";
				$condicoes .= " and cd.Grupo_ID IN (".substr($gruposAux, 0, -1).")";
			}
			else
				$condicoes .= " and cd.Grupo_ID = '$grupo'";
		}

		if (is_array($selecionado)){
			foreach($selecionado as $selAux){
				$sel[$selAux] = " selected ";
				$condicoes .= " or (Cadastro_ID = '$selAux') ";
			}
		}
		else{
			$sel[$selecionado] = " selected ";
			if ($selecionado!="") $condicoes .= " or (Cadastro_ID = '$selecionado') ";
		}

		$sql = "select cd.Cadastro_ID, cd.Nome from cadastros_dados cd
									inner join modulos_acessos ma on ma.Modulo_Acesso_ID = cd.Grupo_ID
									where cd.Cadastro_ID > 0 and ma.Situacao_ID = 1 and cd.Situacao_ID = 1 $condicoes
									and Grupo_ID <> -4
									order by cd.Nome";
		if ($multiple!='multiple'){
			$optionValueUsuarios .= "<option value=''>$texto</option>";
		}
		$resultSet = mpress_query($sql);
		while($row = mpress_fetch_array($resultSet)){
			$optionValueUsuarios .= " <option value='".$row['Cadastro_ID']."' ".$sel[$row['Cadastro_ID']].">".$row['Nome']."</option>";
		}
		return $optionValueUsuarios;
	}



/********************************* Inicio  Funcoes de Projetos e Tarefas *****************************************************/
function carregarTarefas($chaveEstrangeira, $tabelaEstrangeira, $campoEstrangeiro, $cadastroAlvoID){
	global $caminhoSistema;
	if ($tabelaEstrangeira=='cadastros_dados'){
		$sqlOr = "or (vt.Cadastro_Alvo_ID = '$chaveEstrangeira')";
	}
	$sql = "SELECT vt.Descricao_Inicial as Descricao_Inicial, coalesce(p.Titulo, 'Tarefas Avulsas') as Projeto, coalesce(v.Projeto_Vinculo_ID, 0) as Projeto_Vinculo_ID,
						vt.Projeto_Vinculo_Tarefa_ID, t.Descricao, vt.Data_Cadastro, ma.Titulo Grupo, t.Titulo Tipo_Tarefa, vt.Situacao_ID Situacao_Tarefa,
						vt.Data_Limite as Data_Retorno, r.Nome as Responsavel, vt.Tempo_Execucao as Tempo_Execucao,
						vt.Projeto_Vinculo_Tarefa_ID, vtf.Descricao as Descricao_Follow, vtf.Hora_Inicio, vtf.Hora_Fim, vtf.Data_Cadastro as Data_Cadastro_Follow,
						fr.Nome as Usuario_Follow
						FROM projetos_vinculos_tarefas vt
						left join projetos_vinculos v ON vt.Projeto_Vinculo_ID = v.Projeto_Vinculo_ID
						left join tarefas t on t.Tarefa_ID = vt.Tarefa_ID
						left join modulos_acessos ma on ma.Modulo_Acesso_ID = vt.Grupo_Responsavel_ID
						left join cadastros_dados r on vt.Usuario_Responsavel_ID = r.Cadastro_ID
						left join projetos p on p.Projeto_ID = v.Projeto_ID
						left join projetos_vinculos_tarefas_follows vtf on vtf.Projeto_Vinculo_Tarefa_ID = vt.Projeto_Vinculo_Tarefa_ID
						left join cadastros_dados fr on fr.Cadastro_ID = vtf.Usuario_Cadastro_ID
						WHERE (vt.tabela_estrangeira = '$tabelaEstrangeira' AND vt.chave_estrangeira = '$chaveEstrangeira')
						".$sqlOr."
						ORDER BY v.Projeto_Vinculo_ID, vt.Data_Limite, vt.Posicao, vtf.Data_Cadastro /* vtf.Hora_Inicio */";
	//echo $sql;
	$rs = mpress_query($sql);
	$botaoIncluirProjeto = "<input type='button' class='botao-incluir-projeto' value='Incluir Projeto' style='width:150px;'/>";
	$botaoIncluirTarefa = "<input type='button' class='botao-incluir-tarefa' value='Incluir Tarefa Avulsa' style='width:150px;'/>";
	$blocoIncluirProjeto = "
			<div class='bloco-incluir-projeto-geral esconde'>
				<div class='titulo-secundario' style='width:75%;float:left;'>
					<p><b>Projeto</b></p>
					<p>
						<select name='projeto-incluir-geral' id='projeto-incluir-geral' style='width:100%;float:left;' class='campos-projeto required'>
							<option value=''>Selecione</option>
							".optionValueProjetos("", $tabelaEstrangeira)."
						</select>
					</p>
				</div>
				<div class='titulo-secundario' style='float:left; width:12.5%;margin-top:15px;'>
					<input type='button' class='botao-incluir-projeto-geral' style='width:99%' value='Incluir'/>
				</div>
				<div class='titulo-secundario' style='float:left; width:12.5%; margin-top:15px;'>
					<input type='button' class='botao-cancelar-incluir-projeto-geral' style='width:99%' value='Cancelar'/>
				</div>
			</div>";
	echo "	<div class='titulo'>
				<p>
					Tarefas Cadastradas
					$botaoIncluirProjeto
					$botaoIncluirTarefa
				</p>
			</div>
			<div class='titulo-secundario conteudo-interno' id='div-tarefas' class='div-tarefas'>
				$blocoIncluirProjeto";
	carregarBlocoIncluirTarefa($chaveEstrangeira, $tabelaEstrangeira, $campoEstrangeiro, $cadastroAlvoID, 'esconde');
	while($row = mpress_fetch_array($rs)){
		if ($row['Projeto_Vinculo_ID']!=$projetoVinculoIDAnt){
			$i++;
			$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";
			$dados[colunas][colspan][$i][1] = "7";
			$dados[colunas][extras][$i][1] = " valign='middle' align='center'";
			if ($row['Projeto_Vinculo_ID']==0)
				$dados[colunas][conteudo][$i][1] = "<p style='margin-top:10px;'>".strtoupper($row['Projeto'])."</p>";
			else
				$dados[colunas][conteudo][$i][1] = "<p style='margin-top:10px;'>".strtoupper($row['Projeto'])."<input type='button' class='botao-incluir-tarefa' value='Incluir Tarefa' style='float:right' projeto-vinculo-id='".$row['Projeto_Vinculo_ID']."'></p>";
			$i++;
			$dados[colunas][classe][$i] = "tabela-fundo-escuro-titulo";
			$dados[colunas][conteudo][$i][1] 	= "Tarefa";
			$dados[colunas][conteudo][$i][2] 	= "Descri&ccedil;&atilde;o";
			$dados[colunas][conteudo][$i][3] 	= "Responsabilidade";
			$dados[colunas][conteudo][$i][4] 	= "<center>Tempo execu&ccedil;&atilde;o</center>";
			$dados[colunas][conteudo][$i][5] 	= "<center>Data Retorno</center>";
			$dados[colunas][conteudo][$i][6] 	= "&nbsp;Horas Utilizadas";
		}
		if ($row['Projeto_Vinculo_Tarefa_ID'] != $projetoVinculoTarefaIDAnt){
			if ($classe=="tabela-fundo-claro")
				$classe="tabela-fundo-escuro";
			else
				$classe="tabela-fundo-claro";
			$i++;
			$dados[colunas][classe][$i] = $classe;
			$dados[colunas][conteudo][$i][1] = "<p Style='text-align:left;font-size:11px;'>".$row['Tipo_Tarefa']."</p>";
			$dados[colunas][conteudo][$i][2] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".trim($row['Descricao_Inicial'])."</p>";
			//$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".nl2br($row['Descricao'])."</p>";
			$dados[colunas][conteudo][$i][3] = "<p Style='margin:2px 2px 0 2px;float:left;font-size:11px;'>".$row['Grupo']."&nbsp;<br>".$row['Responsavel']."&nbsp;</p>";
			$dados[colunas][conteudo][$i][4] = "<p align='center' Style='font-size:11px;'>".$row['Tempo_Execucao']." dia(s)</p>";
			$dados[colunas][conteudo][$i][5] = "<p Style='text-align:center;font-size:11px;'>".converteDataHora($row['Data_Retorno'],1)."</p>";
			$dados[colunas][conteudo][$i][6] = "<p Style='text-align:center;font-size:11px;'>".calculaHorasUtilizadasTarefasProjeto($row['Projeto_Vinculo_Tarefa_ID'])."</p>";

			if($row['Situacao_Tarefa']==83)
				$dados[colunas][conteudo][$i][7] = "<center><a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/projetos/projetos-follows-tarefas.php?wID=".$row['Projeto_Vinculo_ID']."&t=".$row['Projeto_Vinculo_Tarefa_ID']."' id='tarefa-edita-".$row['Projeto_Vinculo_Tarefa_ID']."'><img src='".$caminhoSistema."/images/geral/ico-editar-var-produto.png' class='finaliza-tarefa-chamado' title='Editar tarefa' Style='cursor:pointer'></a></center></p>";
			else
				$dados[colunas][conteudo][$i][7] = "<center><a class='fancybox fancybox.iframe' href='$caminhoSistema/modulos/projetos/projetos-follows-tarefas.php?wID=".$row['Projeto_Vinculo_ID']."&t=".$row['Projeto_Vinculo_Tarefa_ID']."' id='tarefa-edita-".$row['Projeto_Vinculo_Tarefa_ID']."'><img src='".$caminhoSistema."/images/geral/disponivel.png' class='tarefa-finalizada' title='Visualizar Tarefa Finalizada'></a></center></p>";
		}
		if ($row['Descricao_Follow']!=''){
			$i++;
			$dados[colunas][classe][$i] = $classe;
			$dados[colunas][conteudo][$i][2] = "<div Style='padding:3px; float:left; font-size:11px;'>
													".trim(nl2br($row['Descricao_Follow']))."
												</div>
												<div Style='padding:3px; float:right; font-size:11px;'>
													".$row['Usuario_Follow']." - ".converteDataHora($row['Data_Cadastro_Follow'],1)."
												</div>";
			$dados[colunas][colspan][$i][2] = "6";
		}

		$projetoVinculoIDAnt = $row['Projeto_Vinculo_ID'];
		$projetoVinculoTarefaIDAnt = $row[Projeto_Vinculo_Tarefa_ID];

	}
	if(mpress_count($rs)>=1){
		$dados[colunas][titulo][classe] = "esconde";
		$dados[colunas][tamanho][1] = "width='250px'";
		$dados[colunas][tamanho][4] = "width='80px'";
		$dados[colunas][tamanho][5] = "width='90px'";
		$dados[colunas][tamanho][6] = "width='120px'";
		$dados[colunas][tamanho][7] = "width='30px'";
		geraTabela("100%","7",$dados);
	}else{
		echo "	<p Style='margin:15px 5px 0 5px;color:red; text-align:center'>Nenhuma tarefa cadastrada.</p>";
	}
	echo "	</div>
			<script type='text/javascript' src='$caminhoSistema/javascript/funcoes.formatacao.js'></script>";
}

function carregarBlocoIncluirTarefa($chaveEstrangeira, $tabelaEstrangeira, $campoEstrangeiro, $cadastroAlvoID, $esconde){
	global $dadosUserLogin;
	$responsavelID = $dadosUserLogin['userID'];
	$grupoResponsavelID = $dadosUserLogin['grupoID'];
	$aleatorio = date("YmdHis");
	echo "		<input type='hidden' id='chave-estrangeira-tarefa' name='chave-estrangeira-tarefa' class='campos-projeto' value='$chaveEstrangeira'/>
				<input type='hidden' id='tabela-estrangeira-tarefa' name='tabela-estrangeira-tarefa' class='campos-projeto' value='$tabelaEstrangeira'/>
				<input type='hidden' id='campo-estrangeiro-tarefa' name='campo-estrangeiro-tarefa' class='campos-projeto' value='$campoEstrangeiro'/>
				<input type='hidden' id='cadastro-alvo-id-tarefa' name='cadastro-alvo-id-tarefa' class='campos-projeto' value='$cadastroAlvoID'/>
				<div class='titulo-secundario bloco-incluir-tarefa-geral $esconde' style='float:left; width:100%; margin-bottom:10px;'>
					<div class='titulo-secundario' style='float:left; width:25%;'>
						<p><b>Grupo Respons&aacute;vel</b></p>
						<p>
							<select id='tarefa-grupo-responsavel' name='tarefa-grupo-responsavel' class='required select-grupo-atualiza-usuarios campos-tarefas' style='width:92%' campo='tarefa-usuario-responsavel'>
								<option value=''></option>";
	echo optionValueGruposAcessos($grupoResponsavelID, "","0");
	echo "					</select>
						</p>
					</div>
					<div class='titulo-secundario' style='float:left; width:25%;'>
						<p><b>Usu&aacute;rio Respons&aacute;vel</b></p>
						<p>
							<select id='tarefa-usuario-responsavel' name='tarefa-usuario-responsavel' class='required campos-tarefas' style='width:92%'>
								<option value=''></option>";
	echo optionValueUsuarios($responsavelID, $grupoResponsavelID, "");
	echo "					</select>
						</p>
					</div>
					<div class='titulo-secundario' style='float:left; width:30%;'>
						<p><b>Tarefa</b></p>
						<p>
							<select id='tarefa-seleciona-tarefa-id' name='tarefa-seleciona-tarefa-id' class='required campos-tarefas' style='width:95%'>
								<option value=''></option>";
	echo optionValueTarefa($tarefaID);
	echo "					</select>
						</p>
					</div>
					<div class='titulo-secundario esconde' style='float:left; width:5%;'>
						<p><b>Posi&ccedil;&atilde;o</b></p>
						<p><input type='text' id='tarefa-posicao' name='tarefa-posicao' value='0' class='formata-numero required campos-tarefas' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario esconde' style='float:left; width:10%;'>
						<p><b>Tempo Execu&ccedil;&atilde;o</b></p>
						<p><input type='text' id='tarefa-tempo-execucao' name='tarefa-tempo-execucao' value='1' class='formata-numero required campos-tarefas' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:20%; '>
						<p><b>Data Limite</b></p>
						<p><input type='text' id='tarefa-data-limite' name='tarefa-data-limite' value='' class='formata-data-hora required campos-tarefas' style='width:95%'/></p>
					</div>
					<div class='titulo-secundario' style='float:left; width:100%; margin-top:5px;'>
						<p><b>Descri&ccedil;&atilde;o</b></p>
						<p><textarea id='texto-descricao-tarefa-".$aleatorio."' name='texto-descricao-tarefa-".$aleatorio."' class='campos-tarefas' style='width:99%; height:60px'/></textarea></p>
						<input type='hidden' name='tarefa-aleatorio' id='tarefa-aleatorio' class='campos-tarefas' value='".$aleatorio."'/>
					</div>
					<div class='titulo-secundario botoes-acao-tarefa' style='float:right; width:20%; margin-top:15px'>
						<p align='right'>
							<input type='hidden' value='' name='projeto-vinculo-id' id='projeto-vinculo-id' class='campos-tarefas'>
							<input type='button' value='Cancelar' class='cancelar-incluir-tarefa' Style='width:45%; margin-right:2px'>
							<input type='button' value='Incluir' class='incluir-tarefa-geral' Style='width:45%; '>
						</p>
					</div>
					<div class='titulo-secundario' style='float:right; width:80%; margin-top:15px'>
						<p align='right'>
							<input type='checkbox' id='enviar-email-tarefa' name='enviar-email-tarefa' checked='checked'/>
							<label for='enviar-email' style='cursor:pointer;'><b>ENVIAR EMAIL</b></label>
						</p>
					</div>
				</div>";
	tinyMCE('texto-descricao-tarefa-'.$aleatorio,'');
}


function calculaHorasUtilizadasTarefasProjeto($workflowTarefaID){
	$rs = mpress_query("select timediff(f.Hora_Fim,f.Hora_Inicio) Horas_Utilizadas FROM projetos_vinculos_tarefas t INNER JOIN projetos_vinculos_tarefas_follows f ON f.Projeto_Vinculo_Tarefa_ID = t.Projeto_Vinculo_Tarefa_ID AND t.Projeto_Vinculo_Tarefa_ID = $workflowTarefaID");
	while($row = mpress_fetch_array($rs)){
		$horasInicio 	= substr($row['Horas_Utilizadas'], 0, strlen($row['Horas_Utilizadas'])-3);
		$horasTarefa 	= substr($horasInicio, 0, strlen($horasInicio)-3);
		$minutosTarefa 	= substr($horasInicio, -2);
		$horasTotais 	+= $horasTarefa;
		$minutosTotais 	+= $minutosTarefa;
	}
	$horasMinutos 	= (int)($minutosTotais/60);
	$minutosTotais  = $minutosTotais%60;
	$horasTotais	+= $horasMinutos;

	return "$horasTotais horas e $minutosTotais minutos";
}


function optionValueProjetos($selecionado, $modulo){
	if ($modulo!=""){
		$condicao = " and Tabela_Estrangeira = '$modulo'";
	}
	$sql = "select Projeto_ID, Titulo, Projeto_Padrao from projetos where Situacao_ID = 1 $condicao or Projeto_ID = -1 order by Titulo";
	$resultado = mpress_query($sql);
	while($rs = mpress_fetch_array($resultado)){
		if ($selecionado!=""){
			if ($selecionado==$rs['Projeto_ID']) $seleciona='selected'; else $seleciona='';
		}
		else{
			if ($rs[Projeto_Padrao]=="1") $seleciona='selected'; else $seleciona='';
		}
		$optionValue .= "<option value='".$rs['Projeto_ID']."' $seleciona>".$rs['Titulo']."</option>";
	}
	return $optionValue;
}

function atualizarTarefasUsuarioProjeto($chaveEstrangeira, $campoEstrangeiro, $tabelaEstrangeira, $antigoResponsavelID, $novoResponsavelID){
	global $dadosUserLogin;
	if ($antigoResponsavelID!=$novoResponsavelID){
		if($rsU = mpress_fetch_array(mpress_query("Select Grupo_ID from cadastros_dados where Cadastro_ID = '$novoResponsavelID'")))
			$grupoResponsavelID = $rsU['Grupo_ID'];

		$sql = "	update projetos_vinculos_tarefas as pvt
				inner join projetos_vinculos as pv on pv.Projeto_Vinculo_ID = pvt.Projeto_Vinculo_ID
				set pvt.Usuario_Responsavel_ID = '$novoResponsavelID',
					pvt.Grupo_Responsavel_ID = '$grupoResponsavelID'
				where pv.Chave_Estrangeira = '$chaveEstrangeira'
				and pv.Tabela_Estrangeira = '$tabelaEstrangeira'
				and pv.Campo_Estrangeiro = '$campoEstrangeiro'
				and pvt.Usuario_Responsavel_ID = '$antigoResponsavelID'
				and pvt.Situacao_ID = 83";

		mpress_query($sql);
	}
}


function salvarProjeto($projetoID, $chaveEstrangeira, $campoEstrangeiro, $tabelaEstrangeira, $responsavelID, $cadastroAlvoID){

	/* PAREI AQUI ATUALIZAR TODAS AS CHAMADAS DESTA FUN??O PARA PASSAR O CADASTROALVOID*/
	/* em teoria atualizado, testar */
	global $dadosUserLogin;
	$sql = "insert into projetos_vinculos (Projeto_ID, Chave_Estrangeira, Campo_Estrangeiro, Tabela_Estrangeira, Usuario_Cadastro_ID, Data_Cadastro, Situacao_ID)
		                         values ('$projetoID', '$chaveEstrangeira', '$campoEstrangeiro', '$tabelaEstrangeira', '".$dadosUserLogin['userID']."','".retornaDataHora('','Y-m-d H:i:s')."',1)";

	mpress_query($sql);
	$projetoVinculoID = mpress_identity();


	if ($responsavelID!=""){
		if($rsU = mpress_fetch_array(mpress_query("Select Grupo_ID from cadastros_dados where Cadastro_ID = '$responsavelID'"))){
			$grupoResponsavelIDAux = $rsU['Grupo_ID'];
		}
	}

	$sql = "select Tarefa_ID, Posicao, Tempo_Execucao, Data_Cadastro, Grupo_Responsavel_ID, Usuario_Responsavel_ID, Usuario_Cadastro_ID, Situacao_ID
				from projetos_tarefas where Projeto_ID = '$projetoID' and Situacao_ID = 1
			order by Posicao";
	$resultado = mpress_query($sql);
	$dataLimite = retornaDataHora('','Y-m-d H:i:s');
	while($rs = mpress_fetch_array($resultado)){
		$tempoExecucao += $rs[Tempo_Execucao];
		$usuarioResponsavelID = $rs[Usuario_Responsavel_ID];
		$grupoResponsavelID = $rs[Grupo_Responsavel_ID];
		if ($usuarioResponsavelID==0){
			if ($grupoResponsavelID==$grupoResponsavelIDAux){
				$usuarioResponsavelID = $responsavelID;
			}
		}

		$sql = "insert into projetos_vinculos_tarefas
					(Projeto_Vinculo_ID, Chave_Estrangeira,  Tabela_Estrangeira, Campo_Estrangeiro, Cadastro_Alvo_ID, Tarefa_ID, Posicao, Tempo_Execucao, Data_Limite, Grupo_Responsavel_ID, Usuario_Responsavel_ID, Usuario_Cadastro_ID, Situacao_ID, Data_Cadastro)
				values
					('$projetoVinculoID', '$chaveEstrangeira', '$tabelaEstrangeira', '$campoEstrangeiro', '$cadastroAlvoID', '".$rs[Tarefa_ID]."', '".$rs[Posicao]."', '".$rs[Tempo_Execucao]."', (select DATE_ADD('$dataLimite',INTERVAL $tempoExecucao DAY)) , '$grupoResponsavelID', '$usuarioResponsavelID', '".$dadosUserLogin['userID']."', 83, '".retornaDataHora('','Y-m-d H:i:s')."')";


		mpress_query($sql);
	}

}

function optionValueTarefa($selecionado, $condicao){
	$sql = "select t.Tarefa_ID, Titulo, Descricao, Tempo_Execucao, tg.Grupo_ID
				from tarefas t
				left join tarefas_grupos tg on tg.Tarefa_ID = t.Tarefa_ID and tg.Situacao_ID = 1
				where t.Situacao_ID = 1
				$condicao
				order by t.Titulo, t.Tarefa_ID";
	$resultado = mpress_query($sql);
	$sel[$selecionado] = 'selected';
	while($rs = mpress_fetch_array($resultado)){
		$tarefas[$rs['Tarefa_ID']]['titulo'] = $rs['Titulo'];
		$tarefas[$rs['Tarefa_ID']]['grupos'] .= $rs['Grupo_ID'].",";
		$tarefas[$rs['Tarefa_ID']]['tempo-execucao'] = $rs['Tempo_Execucao'];
	}
	//echo "<pre>";
	//print_r($tarefas);
	//echo "</pre>";

	foreach($tarefas as $chave => $tarefa){
		$grupos = substr($tarefa['grupos'],0,-1);
		$optionValue .= "<option value='$chave' tempo-execucao='".$tarefa['tempo-execucao']."' grupos='$grupos' ".$sel[$chave].">".$tarefa['titulo']."</option>";
	}
	return $optionValue;
}

function optionValueProjetoTarefa($tipoSelecionado, $selecionado, $textoPrimeiro){
	$optionValue = "<option value=''>".$textoPrimeiro."</option>";
	/* TAREFAS */
	$sql = "select t.Tarefa_ID, Titulo, Descricao, Tempo_Execucao /*, tg.Grupo_ID*/
				from tarefas t
				/*left join tarefas_grupos tg on tg.Tarefa_ID = t.Tarefa_ID and tg.Situacao_ID = 1*/
				where t.Situacao_ID = 1
				order by t.Titulo, t.Tarefa_ID";
	$resultado = mpress_query($sql);
	if (($tipoSelecionado=='t') || ($tipoSelecionado=='')){
		$sel[$selecionado] = 'selected';
	}
	$optionValue .= "<optgroup label='Tarefas Avulsas'>";
	while($rs = mpress_fetch_array($resultado)){
		$optionValue .= "<option value='t".$rs['Tarefa_ID']."' ".$sel[$rs['Tarefa_ID']].">".$rs['Titulo']."</option>";
	}
	$optionValue .= "</optgroup>";

	/* PROJETOS */
	$sql = "select Projeto_ID, Titulo from projetos where Projeto_ID > 0 and Situacao_ID = 1";
	$resultado = mpress_query($sql);
	if ($tipoSelecionado=='p'){
		$sel[$selecionado] = 'selected';
	}
	$optionValue .= "<optgroup label='Projetos'>";
	while($rs = mpress_fetch_array($resultado)){
		$optionValue .= "<option value='p".$rs['Projeto_ID']."' ".$sel[$rs['Projeto_ID']].">".$rs['Titulo']."</option>";
	}
	$optionValue .= "</optgroup>";
	return $optionValue;
}

function optionValueModulosProjeto($modulo){
	global $modulosAtivos;
	$selecionado[$modulo] = "selected";
	if ($modulosAtivos[cadastros]){ $opcoesModulos .= "<option value='cadastros_dados' ".$selecionado['cadastros_dados'].">Cadastros</option>"; }
	if ($modulosAtivos[chamados]){ $opcoesModulos .= "<option value='oportunidades_workflows' ".$selecionado['oportunidades_workflows'].">Oportunidades</option>"; }
	if ($modulosAtivos[chamados]){ $opcoesModulos .= "<option value='orcamentos_workflows' ".$selecionado['orcamentos_workflows'].">Or&ccedil;amento</option>"; }
	if ($modulosAtivos[chamados]){ $opcoesModulos .= "<option value='chamados_workflows' ".$selecionado['chamados_workflows'].">".$_SESSION['objeto']."</option>"; }
	if ($modulosAtivos[tele]){ $opcoesModulos .= "<option value='tele_workflows' ".$selecionado['tele_workflows'].">Telemarketing</option>"; }
	if ($modulosAtivos[compras]){ $opcoesModulos .= "<option value='compras_ordem_compra' ".$selecionado['compras_ordem_compra'].">Compras</option>"; }
	if ($modulosAtivos[produtos]){ $opcoesModulos .= "<option value='produtos_dados' ".$selecionado['produtos_dados'].">Produtos</option>"; }
	if ($modulosAtivos[envios]){ $opcoesModulos .= "<option value='envios_workflows' ".$selecionado['envios_workflows'].">Centro de Distribui&ccedil;&atilde;o</option>"; }
	return $opcoesModulos;
}

function arrayModulosProjetoDescricao(){
	$array['cadastros_dados'] = "Cadastros";
	$array['orcamentos_workflows'] = "Or&ccedil;amento";
	$array['chamados_workflows'] = $_SESSION['objeto'];
	$array['compras_ordem_compra'] = "Compras";
	$array['produtos_dados'] = "Produtos";
	$array['envios_workflows'] = "Centro de Distribui&ccedil;&atilde;o";
	$array['tele_workflows'] = "Telemarketing";
	return $array;
}

/********************************* Final Funcoes de Projetos e Tarefas *****************************************************/


function optionValueTabelaPreco($selecionado, $condicao){
	$rs = mpress_query("select Tabela_Preco_ID, Titulo_Tabela from produtos_tabelas_precos where Situacao_ID = 1 $condicao order by Titulo_Tabela");
	while($row = mpress_fetch_array($rs)){
		if($row['Tabela_Preco_ID'] == $selecionado) $seleciona = "selected"; else $seleciona = "";
		$optionValueTabelaPreco .=	"<option value='".$row['Tabela_Preco_ID']."' $seleciona>".$row['Titulo_Tabela']."</option>";
	}
	return $optionValueTabelaPreco;
}

function optionValueCategorias($categorias){
	$query = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome");
	//echo "select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '' order by nome";
	while($categoria1 = mpress_fetch_array($query)){
		if(strlen(array_search($categoria1[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
		$optionValueCategorias .= "<option $selecionado value='".$categoria1[Categoria_ID]."'>".$categoria1[Nome]."</option>";
		$query2 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria1[Categoria_ID]."' order by nome");
		while($categoria2 = mpress_fetch_array($query2)){
			if(strlen(array_search($categoria2[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
			$optionValueCategorias .= "<option $selecionado  value='".$categoria2[Categoria_ID]."'>&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria2[Nome]."</option>";
			$query3 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria2[Categoria_ID]."' order by nome");
			while($categoria3 = mpress_fetch_array($query3)){
				if(strlen(array_search($categoria3[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
				$optionValueCategorias .= "<option $selecionado value='".$categoria3[Categoria_ID]."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria3[Nome]."</option>";
				$query4 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria3[Categoria_ID]."' order by nome");
				while($categoria4 = mpress_fetch_array($query4)){
					if(strlen(array_search($categoria4[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
					$optionValueCategorias .= "<option $selecionado value='".$categoria4[Categoria_ID]."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria4[Nome]."</option>";
					$query5 = mpress_query("select Categoria_ID, Nome from produtos_categorias where Situacao_ID = 1 and Categoria_Pai_ID = '".$categoria4[Categoria_ID]."' order by nome");
					while($categoria5 = mpress_fetch_array($query5)){
						if(strlen(array_search($categoria5[Categoria_ID],$categorias))>=1){$selecionado = " selected ";}else{$selecionado = "";}
						$optionValueCategorias .= "<option $selecionado value='".$categoria5[Categoria_ID]."'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ".$categoria5[Nome]."</option>";
					}
				}
			}
		}
	}
	return $optionValueCategorias;
}



function converteImagem($nomeArquivo){
	global $caminhoSistema;
	$width 		= 1;
	$height 	= 1;
	$arquivo =  $caminhoFisico."/uploads/".$nomeArquivo;
	if(file_exists($arquivo)){
		if(strtoupper(substr($nomeArquivo,-3))=='JPG'){
			$j = imagecreatefromjpeg($arquivo);
			$width 		= imagesx($j);
			$height 	= imagesy($j);
		}else{
			$j = imagecreatefrompng($arquivo);
			$width 		= imagesx($j);
			$height 	= imagesy($j);
		}
		if($width >= $height){
			$new_width	= "270";
			$new_height = "";
			$new_widthPorcento = (($new_width*100)/$width)/100;
			$new_height = $height * $new_widthPorcento;
		}else{
			$new_width	= "";
			$new_height = "210";
			$new_heightPorcento = (($new_height*100)/$height)/100;
			$new_width = $width * $new_heightPorcento;
		}
		if($new_height >= 210){
			$new_width	= "";
			$new_height = "210";
			$new_heightPorcento = (($new_height*100)/$height)/100;
			$new_width = $width * $new_heightPorcento;
		}
		if($new_width >= 270){
			$new_width	= "270";
			$new_height = "";
			$new_widthPorcento = (($new_width*100)/$width)/100;
			$new_height = $height * $new_widthPorcento;
		}

		if(strtoupper(substr($nomeArquivo,-3))=='JPG'){
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_resized, $j, 0, 0, 0, 0, ((int) $new_width), ((int) $new_height), $width, $height);
			imagejpeg($image_resized, $arquivo);
			imagedestroy($image_resized);
		}else{
			$image_resized = imagecreatetruecolor($new_width, $new_height);
			imagecopyresampled($image_resized, $j, 0, 0, 0, 0, ((int) $new_width), ((int) $new_height), $width, $height);
			imagepng($image_resized, $arquivo);
			imagedestroy($image_resized);
		}
	}
	return $nomeArquivo;
}

function bloqueiaRegistro($pagina){
	global $dadosUserLogin;
	if($pagina == "")
		$pagina = get_page_content();

	mpress_query("delete from modulos_ajax where data_registro <= DATE_ADD(now(),INTERVAL -2 MINUTE)");

	if($pagina['Slug_Modulo']=='cadastros'){
		if($pagina['Slug_Pagina'] == 'cadastro-dados'){
			$dados['idBloqueio'] = $_POST['cadastroID'];
			$dados['idModulo']	 = $pagina['Modulo_ID'];
			$dados['idUsuario']	 = $dadosUserLogin[userID];
		}
	}
	if($pagina['Slug_Modulo']=='chamados'){
		if($pagina['Slug_Pagina'] == 'chamados-cadastro-chamado'){
			$dados['idBloqueio'] = $_POST['workflow-id'];
			$dados['idModulo']	 = $pagina['Modulo_ID'];
			$dados['idUsuario']	 = $dadosUserLogin[userID];
		}
	}
	mpress_query("delete from modulos_ajax where Usuario_ID = ".$dados['idUsuario']);
	if(count(array_filter($dados)) == 3){
		mpress_query("insert into modulos_ajax(Modulo_ID,Registro_ID,Usuario_ID)values($dados[idModulo],$dados[idBloqueio],$dados[idUsuario])");
	}
}

function geraTabelaBloqueio($dados,$idTabela){
	$pagina = get_page_content();
	if($idTabela=='cadastro-localiza'){
		//echo "select * from modulos_ajax where modulo_id = ".$pagina[Modulo_ID];
	}
}


function salvarConfiguracoesGeraisModulos(){
	$sql = "select m.Slug as Slug from modulos_paginas mp
							inner join modulos m on m.Modulo_ID = mp.Modulo_ID
							where mp.Slug = '".$_POST['slug-pagina']."'";

	$resultSet = mpress_query($sql);
	if($rs = mpress_fetch_array($resultSet)){
		$slug = $rs['Slug'];
		$resultSet2 = mpress_query("select Tipo_ID from tipo where Tipo_Grupo_ID = 52 and Tipo_Auxiliar = '$slug'");
		if($rs2 = mpress_fetch_array($resultSet2)){
			$tipoID = $rs2[Tipo_ID];
		}
	}
	if ($tipoID==""){
		$sql = "INSERT INTO tipo (Tipo_Grupo_ID, Descr_Tipo, Tipo_Auxiliar, Situacao_ID)
					VALUES (52, '".serialize($_POST)."', '$slug', 1)";
	}
	else{
		$sql = "UPDATE tipo set Descr_Tipo = '".serialize($_POST)."'
					where Tipo_ID = '$tipoID'";
	}
	mpress_query($sql);

	/*********  CONFIG EXTRAS **********/
	if ($slug=='financeiro'){
		if ($_POST['agrupar-lancamento']){
			mpress_query("update modulos_paginas set Oculta_Menu = 1 where Slug = 'financeiro-contas-pagar' or Slug = 'financeiro-contas-receber' or Slug = 'financeiro-contas-transferencias'");
			mpress_query("update modulos_paginas set Oculta_Menu = 0 where Slug = 'financeiro-contas'");
		}
		else{
			mpress_query("update modulos_paginas set Oculta_Menu = 0 where Slug = 'financeiro-contas-pagar' or Slug = 'financeiro-contas-receber' or Slug = 'financeiro-contas-transferencias'");
			mpress_query("update modulos_paginas set Oculta_Menu = 1 where Slug = 'financeiro-contas'");
		}
		if ($_POST['lancamento-fancybox']){
			mpress_query("update modulos_paginas set Oculta_Menu = 1 where Slug = 'financeiro-lancamento'");
		}
		else{
			mpress_query("update modulos_paginas set Oculta_Menu = 0 where Slug = 'financeiro-lancamento'");
		}
	}
}

function carregarConfiguracoesGeraisModulos($slug){
	$sql = "select Descr_Tipo from tipo where Tipo_Grupo_ID = '52' and Tipo_Auxiliar = '$slug'";
	if($rs = mpress_fetch_array(mpress_query($sql)))
		$array = unserialize($rs['Descr_Tipo']);

	//abaixo gambi
	if ($slug=='chamados'){
		$sql = "select (select Oculta_Menu from modulos_paginas where slug = 'chamados-orcamento-localizar') as orcamentos,
						(select Oculta_Menu from modulos_paginas where slug = 'chamados-localizar-chamado') as chamados";
		$resultado = mpress_query($sql);
		if($rs = mpress_fetch_array($resultado)){
			$array['orcamentos'] .= $rs['orcamentos'];
			$array['chamados'] .= $rs['chamados'];
		}
	}


	return $array;
}

function get_url(){
	$dadosPagina = get_page_content();

	$hash = $_GET['hash'];
	if($hash != ""){
		echo "	<input type='hidden' name='get-hash' value='$hash'>
				<input type='hidden' name='get-slug' value='".$dadosPagina['Slug_Pagina']."' >";
	}
}
function redirecionaUrl($post){
	global $caminhoSistema;
	$hash = $post['get-hash'];
	$slug = $post['get-slug'];
	if($slug=='cadastro-dados'){
		$rs = mpress_query("select cadastro_id from cadastros_dados where md5(cadastro_id) = '$hash'");
		$row = mpress_fetch_array($rs);
		$dados['url']   = "$caminhoSistema/cadastros/cadastro-dados";
		$dados['campo'] = "<input type='hidden' name='cadastroID' value='".$row['cadastro_id']."'>";
	}
	return $dados;
}

//	runkit_function_redefine('get_header','','mudei a funcoes rs');


function valorPorExtenso($valor=0) {
	$singular = array("centavo", "real", "mil", "milh&atilde;ao", "bilh&atilde;o", "trilh&atilde;o", "quatrilh&atilde;o");
	$plural = array("centavos", "reais", "mil", "milh&otilde;es", "bilh&otilde;es", "trilh&otilde;es","quatrilh&otilde;es");

	$c = array("", "cem", "duzentos", "trezentos", "quatrocentos","quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
	$d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta","sessenta", "setenta", "oitenta", "noventa");
	$d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze","dezesseis", "dezesete", "dezoito", "dezenove");
	$u = array("", "um", "dois", "tr&ecirc;s", "quatro", "cinco", "seis","sete", "oito", "nove");

	$z=0;

	$valor = number_format($valor, 2, ".", ".");
	$inteiro = explode(".", $valor);
	for($i=0;$i<count($inteiro);$i++)
		for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
			$inteiro[$i] = "0".$inteiro[$i];

	// $fim identifica onde que deve se dar jun de centenas por "e" ou por "," ;)
	$fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);
	for ($i=0;$i<count($inteiro);$i++) {
		$valor = $inteiro[$i];
		$rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
		$rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
		$ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

		$r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd && $ru) ? " e " : "").$ru;
		$t = count($inteiro)-1-$i;
		$r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";
		if ($valor == "000")$z++; elseif ($z > 0) $z--;
		if (($t==1) && ($z>0) && ($inteiro[0] > 0)) $r .= (($z>1) ? " de " : "").$plural[$t];
		if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
	}

	return($rt ? $rt : "zero");
}



function optionValueFrete($selecionado){
	$optionValue = "";
	$sel[$selecionado] = " selected ";
	$optionValue .= "	<option value='CIF' ".$sel['CIF'].">CIF - Custos do FORNECEDOR</option>";
	$optionValue .= "	<option value='FOB' ".$sel['FOB'].">FOB - Custos do CLIENTE</option>";
	return $optionValue;
}

function optionValueContas($selecionados, $condicoes){
	if (is_array($selecionados)){
		foreach($selecionados as $selecionado){
			$sel[$selecionado] = " selected ";
		}
	}
	else{
		$sel[$selecionados] = " selected ";
	}
	$resultSet = mpress_query("SELECT Cadastro_Conta_ID, Cadastro_ID, Nome_Conta FROM cadastros_contas where Situacao_ID = 1 $condicoes order by Nome_Conta");
	while($rs = mpress_fetch_array($resultSet)){
		$optionValue .= "<option value='".$rs['Cadastro_Conta_ID']."' ".$sel[$rs['Cadastro_Conta_ID']]." empresa-id='".$rs['Cadastro_ID']."'>".$rs['Nome_Conta']."</option>";
	}
	return $optionValue;
}


/***********************************/
/*	FORMULARIOS DINAMICOS CARREGAR */
/***********************************/

function montarFormularioDinamico($formularioID, $tabelaEstrangeiraFormulario, $chaveEstrangeiraFormulario, $modoExibicao = "completo"){
	/* VERIFICA SE FORMULARIO DINAMICO JA FOI RESPONDIDO */
	if (($tabelaEstrangeiraFormulario!="") && ($chaveEstrangeiraFormulario != "")){
		$sql = "SELECT Resposta_ID, Formulario_ID, Chave_Estrangeira, Tabela_Estrangeira, Respostas, Situacao_ID, Usuario_Cadastro_ID, Data_Cadastro
					FROM formularios_respostas
					where Chave_Estrangeira = '$chaveEstrangeiraFormulario'
					and Tabela_Estrangeira = '$tabelaEstrangeiraFormulario'
					and Situacao_ID = 1";
		//echo $sql;
		$resultado = mpress_query($sql);
		$i = 0;
		if($rs = mpress_fetch_array($resultado)){
			$respostas = unserialize($rs['Respostas']);
			//echo "<pre>";
			//print_r($respostas);
			//echo "</pre>";
		}
	}

	//if ($acao=='edicao'){
		//$estiloBlocoCampo = " box-shadow: 0 0 0 1px #c9c9c9; border-radius: 5px!important; opacity:0.9; cursor:pointer;";
		//$estiloBlocoCampo = " box-shadow: -1px -1px 4px 0px red; border-radius: 5px!important; opacity:0.9; border:0px; cursor:pointer;";
		//$estiloC = " box-shadow: -1px -1px 4px 0px red; border-radius: 5px!important; opacity:0.9; border:0px; cursor:pointer;";
		//$estiloBlocoCampo = " box-shadow: -3px -3px 5px 1px #555555; border-radius: 5px!important; opacity:0.9;";
	//}

	$html .= "	<!--<fieldset style='float:left; width:99%; margin-top:10px; padding-top:10px;'>-->";
	$sql = "SELECT f.Nome as Nome_Formulario, fc.Nome as Campo, fc.Tipo_Campo, ffc.Formulario_Campo_ID, ffc.Campo_ID, ffc.Posicao, ffc.Largura, ffc.Altura, ffc.Obrigatorio, fc.Tipo_Campo as Tipo_Campo
				FROM formularios_formulario_campo ffc
				INNER join formularios_campos fc on fc.Campo_ID = ffc.Campo_ID
				inner join formularios f on f.Formulario_ID = ffc.Formulario_ID
				WHERE ffc.Formulario_ID = '$formularioID'
				AND ffc.Situacao_ID = 1
				order by ffc.Posicao";
	//echo $sql;
	$resultado = mpress_query($sql);
	$i = 0;
	while($rs = mpress_fetch_array($resultado)){
		$i++;
		$nomeFormulario = $rs['Nome_Formulario'];
		$campoID = $rs['Campo_ID'];
		$htmlCampo = "";

		$obrigatorio = $obrigatorioClasse = "";
		if ($rs['Obrigatorio']==1){
			$obrigatorio = "<span style='color:red;'>*</span>";
			$obrigatorioClasse = "required";
		}

		if ($rs['Tipo_Campo']=='text'){
			$htmlCampo = "<input type='text' name='cfd[$campoID]' id='cfd_".$campoID."_' class='$obrigatorioClasse' value='".$respostas[$campoID]."' style='width:98%'/>";
		}
		if ($rs['Tipo_Campo']=='textarea'){
			$htmlCampo = "<textarea type='text' name='cfd[$campoID]' id='cfd_".$campoID."_' class='$obrigatorioClasse' style='word-wrap: break-word; width:98%;height:70px'>".$respostas[$campoID]."</textarea>";
		}

		if (($rs['Tipo_Campo']=='radio') || ($rs['Tipo_Campo']=='checkbox') || ($rs['Tipo_Campo']=='select')){
			$sql = "SELECT Campo_Opcao_ID, Descricao
						FROM formularios_campos_opcoes
						where Campo_ID = '".$rs['Campo_ID']."' and Situacao_ID = 1
						order by Campo_Opcao_ID";
			//echo "<br>".$sql;
			$resultado1 = mpress_query($sql);
			if ($rs['Tipo_Campo']=='radio'){
				while($rs1 = mpress_fetch_array($resultado1)){
					$selecionado = "";
					if ($respostas[$campoID]==$rs1['Campo_Opcao_ID']){
						$selecionado = "checked";
					}
					$htmlCampo .= " <div style='float:left; white-space: nowrap;'>
										<input type='radio' name='cfd[$campoID]' id='cfd_".$campoID."_' class='$obrigatorioClasse' ".$selecionado." value='".$rs1['Campo_Opcao_ID']."'/> ".$rs1['Descricao']."
									</div>";
				}
			}
			if ($rs['Tipo_Campo']=='checkbox'){
				while($rs1 = mpress_fetch_array($resultado1)){
					$selecionado = "";
					if ($respostas[$campoID][$rs1['Campo_Opcao_ID']]==$rs1['Campo_Opcao_ID']){
						$selecionado = "checked";
					}
					$htmlCampo .= "	<div style='float:left; white-space: nowrap;'>
										<input type='checkbox' name='cfd[$campoID][".$rs1['Campo_Opcao_ID']."]' id='cfd_$campoID__".$rs1['Campo_Opcao_ID']."_' ".$selecionado." value='".$rs1['Campo_Opcao_ID']."'/> ".$rs1['Descricao']."
									</div>";
				}
			}
			if ($rs['Tipo_Campo']=='select'){
				$htmlCampo .= "<select name='cfd[$campoID]' id='cfd_".$campoID."_' class='$obrigatorioClasse' style='width:98%'>";
				$htmlCampo .= "		<option value=''></option>";
				while($rs1 = mpress_fetch_array($resultado1)){
					$selecionado = "";
					if ($respostas[$campoID]==$rs1['Campo_Opcao_ID']){
						$selecionado = "selected";
					}
					$htmlCampo .= "	<option value='".$rs1['Campo_Opcao_ID']."' $selecionado>".$rs1['Descricao']."</option>";
				}
				$htmlCampo .= "</select>";
			}
		}

		$largura = $rs['Largura'];
		$altura = $rs['Altura'];
		$html .= "	<div class='titulo-secundario' style='margin-bottom:10px; float:left; width:$largura; height: $altura; /*min-height: $altura;*/ $estiloBlocoCampo'>
						<div class='titulo-secundario' style='float:left; width:98%'>
							<p style='margin-bottom:0px;'><b>".$rs['Campo']." $obrigatorio</b></p>
							<p style='margin-bottom:5px;'>$htmlCampo</p>
						</div>
					</div>";
	}
	/**************************************************/
	/* EXIBE FORMULARIO COM BOTAO PARA SALVAR PROPRIO */
	/**************************************************/
	if ($modoExibicao=='completo'){
		$html = "	<div class='titulo-container conjunto1 form-formulario-dinamico-generico'>
						<div class='titulo'>
							<p style='text-transform: uppercase;'>".$nomeFormulario."<input type='button' class='salvar-formulario-dinamico-generico' value='Salvar dados' style='width:150px;'/></p>
						</div>
						<div class='conteudo-interno titulo-secundario' style='margin-top:5px;'>
							<input type='hidden' name='formulario-dinamico-id' id='formulario-dinamico-id' value='".$formularioID."'>
							<input type='hidden' name='tabela-estrangeira-formulario-dinamico' id='tabela-estrangeira-formulario-dinamico' value='".$tabelaEstrangeiraFormulario."'>
							<input type='hidden' name='chave-estrangeira-formulario-dinamico' id='chave-estrangeira-formulario-dinamico' value='".$chaveEstrangeiraFormulario."'>
							".$html."
						</div>
					</div>";
	}
	/***********************************************************************/
	/* EXIBE FORMULARIO INTEGRADO EM OUTRO POR ISSO OCULTA BOTAO DE SALVAR */
	/***********************************************************************/
	if ($modoExibicao=='integrado'){
		$html = "	<fieldset>
						<div class='form-formulario-dinamico-generico'>
							<p align='center' style='text-transform: uppercase;'><b>".$nomeFormulario."</b>
								<input type='hidden' class='salvar-formulario-dinamico-generico'/>
							</p>
							<div style='margin-top:2px;'>
								<input type='hidden' name='formulario-dinamico-id' id='formulario-dinamico-id' value='".$formularioID."'>
								<input type='hidden' name='tabela-estrangeira-formulario-dinamico' id='tabela-estrangeira-formulario-dinamico' value='".$tabelaEstrangeiraFormulario."'>
								<input type='hidden' name='chave-estrangeira-formulario-dinamico' id='chave-estrangeira-formulario-dinamico' value='".$chaveEstrangeiraFormulario."'>
								".$html."
							</div>
						</div>
					</fieldset>";
	}
	return $html;
}

function carregarFormulariosTela ($tabelaEstrangeira, $chaveEstrangeira){
	$sql = "select Formulario_ID from formularios_respostas
				where Tabela_Estrangeira = '$tabelaEstrangeira'
				and Chave_Estrangeira = '$chaveEstrangeira'
				and Situacao_ID = 1";
	$resultado = mpress_query($sql);
	$h = "";
	while($rs = mpress_fetch_array($resultado)){
		$h .= montarFormularioDinamico($rs['Formulario_ID'], $tabelaEstrangeira, $chaveEstrangeira);
	}
	return $h;
}


/* POG DATE DIFF */
if(!function_exists('date_diff')) {
  class DateInterval {
    public $y;
    public $m;
    public $d;
    public $h;
    public $i;
    public $s;
    public $invert;
    public $days;

    public function format($format) {
      $format = str_replace('%R%y',
        ($this->invert ? '-' : '+') . $this->y, $format);
      $format = str_replace('%R%m',
         ($this->invert ? '-' : '+') . $this->m, $format);
      $format = str_replace('%R%d',
         ($this->invert ? '-' : '+') . $this->d, $format);
      $format = str_replace('%R%h',
         ($this->invert ? '-' : '+') . $this->h, $format);
      $format = str_replace('%R%i',
         ($this->invert ? '-' : '+') . $this->i, $format);
      $format = str_replace('%R%s',
         ($this->invert ? '-' : '+') . $this->s, $format);

      $format = str_replace('%y', $this->y, $format);
      $format = str_replace('%m', $this->m, $format);
      $format = str_replace('%d', $this->d, $format);
      $format = str_replace('%h', $this->h, $format);
      $format = str_replace('%i', $this->i, $format);
      $format = str_replace('%s', $this->s, $format);

      return $format;
    }
  }

  function date_diff(DateTime $date1, DateTime $date2) {

    $diff = new DateInterval();

    if($date1 > $date2) {
      $tmp = $date1;
      $date1 = $date2;
      $date2 = $tmp;
      $diff->invert = 1;
    } else {
      $diff->invert = 0;
    }

    $diff->y = ((int) $date2->format('Y')) - ((int) $date1->format('Y'));
    $diff->m = ((int) $date2->format('n')) - ((int) $date1->format('n'));
    if($diff->m < 0) {
      $diff->y -= 1;
      $diff->m = $diff->m + 12;
    }
    $diff->d = ((int) $date2->format('j')) - ((int) $date1->format('j'));
    if($diff->d < 0) {
      $diff->m -= 1;
      $diff->d = $diff->d + ((int) $date1->format('t'));
    }
    $diff->h = ((int) $date2->format('G')) - ((int) $date1->format('G'));
    if($diff->h < 0) {
      $diff->d -= 1;
      $diff->h = $diff->h + 24;
    }
    $diff->i = ((int) $date2->format('i')) - ((int) $date1->format('i'));
    if($diff->i < 0) {
      $diff->h -= 1;
      $diff->i = $diff->i + 60;
    }
    $diff->s = ((int) $date2->format('s')) - ((int) $date1->format('s'));
    if($diff->s < 0) {
      $diff->i -= 1;
      $diff->s = $diff->s + 60;
    }

    $start_ts   = $date1->format('U');
    $end_ts   = $date2->format('U');
    $days     = $end_ts - $start_ts;
    $diff->days  = round($days / 86400);

    if (($diff->h > 0 || $diff->i > 0 || $diff->s > 0))
      $diff->days += ((bool) $diff->invert)
        ? 1
        : -1;

    return $diff;
  }

}

//Contador de contas que estão para vencer ou venceram

  function contasAtrasadas() {

  	$hoje 			= date('Y-m-d');

  	$totalAtrasados = mpress_fetch_array(mpress_query("SELECT COUNT(fc.Conta_ID) as totalAtrasados
  														FROM financeiro_contas fc
  														INNER JOIN financeiro_titulos ft ON fc.Conta_ID = ft.Conta_ID
  														WHERE fc.Conta_ID is not null AND ft.Data_Vencimento <= '".$hoje."' AND ft.Situacao_Pagamento_ID = '48'
  														"));
  	return $totalAtrasados[0];

  }

?>