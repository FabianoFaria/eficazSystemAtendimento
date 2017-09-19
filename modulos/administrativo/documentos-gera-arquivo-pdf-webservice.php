<?php
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename="downloaded.pdf"');

	include("functions.php");
	global $caminhoSistema, $caminhoFisico;
	include($caminhoFisico."/includes/dompdf/dompdf_config.inc.php");

	$html = $_POST['html'];
	$titulo = $_POST['titulo'];

	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	$dompdf->render();
	$dompdf->stream("downloaded.pdf");
?>