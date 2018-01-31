<?php
	
	error_reporting(E_ERROR);
	ini_set('display_errors', 'On');

	session_start();
	if (!function_exists("get_header"))
		require_once("../includes/functions.gerais.php");

	function geraDadosMenu(){
		global $dadosUserLogin, $paginasAcesso, $modulosGeral;

		$modulosGerais = mpress_query("select Slug from modulos where Situacao_ID = 1");
		while($modGer = mpress_fetch_array($modulosGerais)){
			$modulosGeral[$modGer[Slug]] = true;
			$_SESSION['modulosGeral'][$modGer[Slug]] = true;
		}

		//var_dump($dadosUserLogin);

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
		$menuPrincipal = mpress_query("	select distinct m.Modulo_ID, m.Nome Nome_Modulo, m.Descricao Descricao_Modulo, m.Slug Slug_Modulo
										from modulos m
										inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
										where m.Situacao_ID = 1 and mp.Situacao_ID = 1

										order by m.Modulo_ID,Nome_Modulo ");
										//order by m.Posicao,mp.Posicao");
										

		//$strAcesso

		//var_dump($menuPrincipal);
			//die();

		while($principal = mpress_fetch_array($menuPrincipal)){

			//echo $principal[Modulo_ID];

			$i++;
			$j=0;
			$dadosMenu[menu_principal][$i][id] 		= $principal[Modulo_ID];
			$dadosMenu[menu_principal][$i][nome] 		= $principal[Nome_Modulo];
			$dadosMenu[menu_principal][$i][descricao] 	= $principal[Descricao_Modulo];
			$dadosMenu[menu_principal][$i][slug] 		= $principal[Slug_Modulo];

			$modulosAtivos[$principal[Slug_Modulo]] = true;
			$_SESSION['modulosAtivos'][$principal[Slug_Modulo]] = true;

			$menuSecundario = mpress_query("select mp.Titulo Titulo_Modulo_Pagina, mp.Descricao Descricao_Modulo_Pagina, mp.Slug Slug_Modulo_Pagina,  mp.Modulo_Pagina_ID, mp.Oculta_Menu Oculta_Menu
											from modulos m
											inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
											where m.Situacao_ID = 1 and mp.Situacao_ID = 1
											and m.Modulo_ID = $principal[Modulo_ID] and pagina_pai_ID is null
											$strAcesso
											order by m.Posicao, mp.Posicao");
			while($secundario = mpress_fetch_array($menuSecundario)){
				$j++;
				$k=0;
				$dadosMenu[menu_principal][$i][paginas][$j][id] 	 	= $secundario[Modulo_Pagina_ID];
				$dadosMenu[menu_principal][$i][paginas][$j][slug] 	 	= $secundario[Slug_Modulo_Pagina];
				$dadosMenu[menu_principal][$i][paginas][$j][nome] 		= $secundario[Titulo_Modulo_Pagina];
				$dadosMenu[menu_principal][$i][paginas][$j][descricao]	= $secundario[Descricao_Modulo_Pagina];
				$dadosMenu[menu_principal][$i][paginas][$j][acesso] 	= $secundario[Tipo_Acesso_ID];
				$dadosMenu[menu_principal][$i][paginas][$j][oculta_menu]= $secundario[Oculta_Menu];

				$modulosAtivos[$secundario[Slug_Modulo_Pagina]] = true;
				$_SESSION['modulosAtivos'][$secundario[Slug_Modulo_Pagina]] = true;

				$menuTerciario = mpress_query("select mp.Titulo Titulo_Modulo_Pagina, mp.Descricao Descricao_Modulo_Pagina, mp.Slug Slug_Modulo_Pagina,  mp.Modulo_Pagina_ID, mp.Oculta_Menu Oculta_Menu
												from modulos m
												inner join modulos_paginas mp on mp.Modulo_ID = m.Modulo_ID
												where m.Situacao_ID = 1 and mp.Situacao_ID = 1
												and m.Modulo_ID = $principal[Modulo_ID] and Pagina_Pai_ID = ".$secundario[Modulo_Pagina_ID]."
												$strAcesso
												order by m.Posicao, mp.Posicao");
				while($terciario = mpress_fetch_array($menuTerciario)){
					$k++;
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][id] 	 		= $terciario[Modulo_Pagina_ID];
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][slug] 	 	= $terciario[Slug_Modulo_Pagina];
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][nome] 		= $terciario[Titulo_Modulo_Pagina];
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][descricao]	= $terciario[Descricao_Modulo_Pagina];
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][acesso] 		= $terciario[Tipo_Acesso_ID];
					$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][oculta_menu] = $terciario[Oculta_Menu];

					$modulosAtivos[$terciario[Slug_Modulo_Pagina]] = true;
					$_SESSION['modulosAtivos'][$terciario[Slug_Modulo_Pagina]] = true;

				}
			}
		}

		return $dadosMenu;
	}
	function dadosMenuPrincipal($dadosMenu){
		$dadospagina = get_page_content();
		
		for($k=1;$k<=count($dadosMenu[menu_principal]);$k++){

			if($dadospagina[Slug_Modulo]==$dadosMenu[menu_principal][$k][slug]){
				$classeAdd="interno-nivel-1-selecionado"; 
			}else{
				$classeAdd="interno-nivel-1";
			}

			//Notificação de Financeiro...
			//ADICIONA UM CONTADOR DE DESPESA ATRASADAS QUE SERÁ EXIBIDO NO MENU QUANDO O SISTEMA FOR ACESSADO.
			$notificacoesFin = '';

			if($dadosMenu[menu_principal][$k][nome] == 'Financeiro'){

				$totalContasVencidas 	= contasAtrasadas();

				if($totalContasVencidas > 0){

					$notificacoesFin 	= "<span style='width:10px;background-color:red;'> ".$totalContasVencidas."!</span>";
				}

			}

			$menuLvl01 .= "	<div id='".$dadosMenu[menu_principal][$k][slug]."|$k' class='menu-interno $classeAdd menu-".$dadosMenu[menu_principal][$k][slug]."' title='".$dadosMenu[menu_principal][$k][descricao]."'>
								<p style='margin-top:32px;'>
									<a href='#'>".$dadosMenu[menu_principal][$k][nome]."</a>
									".$notificacoesFin."
								</p>
							</div>";
		}
		return $menuLvl01;
	}
	function dadosMenuSegundoNivel($dadosMenu){
		global $caminhoSistema;
		$dadospagina = get_page_content();
		for($i=1;$i<=count($dadosMenu[menu_principal]);$i++){
			$menuLvl2 .=	"<div class='interno-nivel' id='".$dadosMenu[menu_principal][$i][slug]."-2-$i'>";
			for($j=1;$j<=count($dadosMenu[menu_principal][$i][paginas]);$j++){
				if(count($dadosMenu[menu_principal][$i][paginas][$j][filha]) >= 1){
					if($dadospagina[Slug_Pagina]==$dadosMenu[menu_principal][$i][paginas][$j][slug]){
						$classeAdd="interno-nivel-2-selecionado";
						$scriptSelect = "<script>
											$('#menu-ramificado-2').show();
											$('#".$dadosMenu[menu_principal][$i][slug]."-2-$i').show();
										 </script>";
					}else{
						$classeAdd="interno-nivel-2";
					}

					$menuLvl2 .=	"<div class='$classeAdd menu-interno'  id='".$dadosMenu[menu_principal][$i][paginas][$j][slug]."-3' title='".$dadosMenu[menu_principal][$i][paginas][$j][descricao]."'>
										<a href='#'>".$dadosMenu[menu_principal][$i][paginas][$j][nome]."</a>
									</div>";
				}else{
					if($dadospagina[Slug_Pagina]==$dadosMenu[menu_principal][$i][paginas][$j][slug]){
						$classeAdd="interno-nivel-2-selecionado";
						$scriptSelect = "<script>
											$('#menu-ramificado-2').show();
											$('#".$dadosMenu[menu_principal][$i][slug]."-2-$i').show();
										 </script>";
					}else{
						$classeAdd="interno-nivel-2";
					}
					if($dadosMenu[menu_principal][$i][paginas][$j][oculta_menu]!="1"){

						//Notificação de Contas atrasadas...
						//ADICIONA UM CONTADOR DE DESPESA ATRASADAS QUE SERÁ EXIBIDO NO MENU QUANDO O SISTEMA FOR ACESSADO.
						$notificacoesFin = '';
						if($dadosMenu[menu_principal][$i][paginas][$j][nome] == 'Contas'){
							$totalContasVencidas 	= contasAtrasadas();

							if($totalContasVencidas > 0){

								$notificacoesFin 	= "<span style='width:10px;background-color:red;'> ".$totalContasVencidas."!</span>";
							}
						}

						$menuLvl2 .= "	<a href='".$caminhoSistema."/".	$dadosMenu[menu_principal][$i][slug]."/".$dadosMenu[menu_principal][$i][paginas][$j][slug]."'>
											<div class='$classeAdd menu-interno'  id='".$dadosMenu[menu_principal][$i][paginas][$j][slug]."-3'  title='".$dadosMenu[menu_principal][$i][paginas][$j][descricao]."'>
												".$dadosMenu[menu_principal][$i][paginas][$j][nome]."".$notificacoesFin."
											</div>
										</a>";
					}
				}
			}
			$menuLvl2 .= "</div>";
		}
		$menuLvl2 .= $scriptSelect;
		return $menuLvl2;
	}
	function dadosMenuTerceiroNivel($dadosMenu){
		global $caminhoSistema;
		$dadospagina = get_page_content();
		for($i=1;$i<=count($dadosMenu[menu_principal]);$i++){
			for($j=1;$j<=count($dadosMenu[menu_principal][$i][paginas]);$j++){
				if($dadosMenu[menu_principal][$i][paginas][$j][slug] != ""){
					$l++;
					$menuLvl3 .=	"<div Class='interno-nivel-tres' id='".$dadosMenu[menu_principal][$i][paginas][$j][slug]."-3-1'>";
					for($k=1;$k<=count($dadosMenu[menu_principal][$i][paginas][$j][filha]);$k++){
						$classeAdd = "interno-nivel-3";
						if($dadospagina[Slug_Pagina_Filho]==$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][slug]){
							$classeAdd="interno-nivel-3-selecionado";
							$scriptSelect = "<script>
												$('#menu-ramificado-3').show();
												$('#".$dadosMenu[menu_principal][$i][paginas][$j][slug]."-3-1').show();
											 </script>";
						}

						if($dadosMenu[menu_principal][$i][paginas][$j][filha][$k][oculta_menu]!="1"){
							$menuLvl3 .= "	<a href='".$caminhoSistema."/".	$dadosMenu[menu_principal][$i][slug]."/".$dadosMenu[menu_principal][$i][paginas][$j][slug]."/".$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][slug]."/'>
												<div class='$classeAdd menu-interno' title='".$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][descricao]."'>
													".$dadosMenu[menu_principal][$i][paginas][$j][filha][$k][nome]."
												</div>
											</a>";
						}
					}
					$menuLvl3 .= "</div>";
				}
			}
		}
		$menuLvl3 .= $scriptSelect;
		return $menuLvl3;
	}
?>