<?php
require_once("../../config.php");
global $caminhoSistema;

$arquivoCSV = $_POST["arquivo-importar"];
mpress_query("truncate table cidades_ufs");

$row = 1;
$handle = fopen ($caminhoSistema."/uploads/".$arquivoCSV,"r");
while (($data = fgetcsv($handle, 100000, ";")) !== FALSE) {
    $num = count ($data);
    $row++;
    $strInsert = "";
    for ($c=0; $c < $num; $c++) {
    	if($c>=1) $virgula = ","; else $virgula = "";
        $strInsert .= $virgula."'".str_replace("'","´",$data[$c])."'";
    }
	mpress_query("insert into cidades_ufs(Uf, Cidade, ClaroTV, Globo, SBT, Tipo_Antena, Antena)values($strInsert)");
}
fclose ($handle);
echo "<b>$row registros importados com sucesso!</b>";
?>