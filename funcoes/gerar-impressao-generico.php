<?php
//header("Content-type: application/vnd.ms-excel");
//header("Content-type: application/force-download");
//header("Content-Disposition: attachment; filename=".$_POST["nome-relatorio"]."_".date('Ymd').".xls");
//header("Pragma: no-cache");

echo "aaaaaaaaaaaaa
	<style>
		@media print {
			.page-break	{ display: block; page-break-before: always; }
		}
	</style>";
	
echo $_POST["conteudo-relatorio"];
?>
<script language='javascript'>
	window.print();
</script>