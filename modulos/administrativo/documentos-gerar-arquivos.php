<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");
	global $caminhoSistema, $caminhoFisico;

	$titulo = $_POST["slug-pagina"]."_".date('Ymd');
	if ($_GET['tipo']=='pdf'){
		//echo "<center><img src='../../images/geral/ajax-loader-barra.gif'/></center>";
		include($caminhoFisico."/includes/dompdf/dompdf_config.inc.php");
		ini_set('memory_limit', '-1');
		$cabecalho 	= "../../images/documentos/cabecalho.jpg";
		$rodape 	= "../../images/documentos/rodape.jpg";
		$c = imagecreatefromjpeg($cabecalho);
		$r = imagecreatefromjpeg($rodape);
		$topCabecalho 	= (imagesy($c)+10)."px";
		$topRodape 		= (imagesy($r)+10)."px";
		$html = "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
					<style>
						body{
							font-family: Arial;
							background: url(../../images/documentos/cabecalho-rodape.jpg) top center no-repeat;
							padding-top: $topCabecalho;
							padding-bottom: $topRodape;
							font-family:arial;
							font-size:9px;
							height: 100%;
							margin:-40px;
						}
						.tabela-fundo-escuro-titulo{
							border:0px;
							font-weight:bold;
							background-color:#f1f1f1;
						}

						.tabela-fundo-escuro{
							border:0px;
							background-color:#f9f9f9;
						}
						.tabela-fundo-claro{
							border:0px;
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
						.destaque-tabela{
							font-weight:bold;
							background-color:#E5ECF1;
							border:0px;
							font-family:arial;
							color:#000000;
						}
					</style>
					<body>
						<div style='width:91.5%;margin:0 auto;'>
						".geraTabela($_SESSION['documento']['largura'], $_SESSION['documento']['colunas'], $_SESSION['documento']['dados'], "border:0px;", 'relatorio', '2', '2', '', '', 'return')."
						</div>
					</body>
				</html>";
		$html = utf8_encode(str_replace("'",'"', $html));
		$dompdf = new DOMPDF();
		$dompdf->load_html(stripslashes($html));
		if ($tipoPapel=="")
			$tipoPapel = "a4";
		if ($orientacao=="")
			$orientacao = "portrait";
		$dompdf->set_paper($tipoPapel, $orientacao);
		$dompdf->render();
		$dompdf->stream($titulo.".pdf");
	}
	if ($_GET['tipo']=='excel'){
		header("Content-type: application/vnd.ms-excel");
		header("Content-type: application/force-download");
		header("Content-Disposition: attachment; filename=$titulo.xls");
		header("Pragma: no-cache");
		global $caminhoSistema, $caminhoFisico;
		$cabecalho 	= "$caminhoSistema/images/documentos/cabecalho.jpg";
		$rodape 	= "$caminhoSistema/images/documentos/rodape.jpg";
		$c = imagecreatefromjpeg($cabecalho);
		$r = imagecreatefromjpeg($rodape);
		$topCabecalho 	= (imagesy($c))."px";
		$larCabecalho 	= (imagesx($c))."px";
		$topRodape 		= (imagesy($r))."px";
		$larRodape 		= (imagesx($r))."px";

		$html = geraTabela($_SESSION['documento']['largura'], $_SESSION['documento']['colunas'], $_SESSION['documento']['dados'], 'border:0px;', '', '2', '2', '', '', 'return');
		echo $html;
		/*
		$html = geraTabela($_SESSION['documento']['largura'], $_SESSION['documento']['colunas'], $_SESSION['documento']['dados'], 'border:0px;', '', '2', '2', '', '', 'return');
		$html = "	<table>
						<tr>
							<td background='$caminhoSistema/images/documentos/cabecalho.jpg' height='$topCabecalho' width='$larCabecalho' colspan='$colunas'>&nbsp;</td>
						</tr>
					</table>
					$html
					<table>
						<tr>
							<td background='$caminhoSistema/images/documentos/rodape.jpg' height='$topRodape' width='$larRodape' colspan='$colunas'>&nbsp;</td>
						</tr>
					</table>";
		echo $html;
		*/
	}
	if ($_GET['tipo']=='html'){
		$html = geraTabela($_SESSION['documento']['largura'], $_SESSION['documento']['colunas'], $_SESSION['documento']['dados'], 'border:0px;', '', '2', '2', '', '', 'return');
		echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
				<html xmlns='http://www.w3.org/1999/xhtml'>
					<head><link rel='stylesheet' type='text/css' href='$caminhoSistema/css/print.css'/></head>
					<body>
						<header class='header'>&nbsp;</header>
						$html
						<footer class='footer'>&nbsp;</footer>
					</body>
					<script>window.print();</script>
				</html>";

		//geraHTMLImpressao($titulo, $html);
	}
?>