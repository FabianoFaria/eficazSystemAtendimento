<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");
	$retorno .= "<option value=''>Selecione</option>";
	if($_GET['id'] != ""){
		$rs = mpress_query("select t2.Tipo_ID, t2.Descr_Tipo
							from modulos_vinculos v
							inner join tipo t1 on t1.Tipo_ID = v.Tipo_Principal_ID
							inner join tipo t2 on t2.Tipo_ID = v.Tipo_Secundario_ID
							where Nome_Tabela = 'tipo' and tipo_Principal_ID = ".$_GET['id']."
							order by t2.Descr_Tipo");
		while($row = mpress_fetch_array($rs))
			$retorno .= "<option value='$row[Tipo_ID]'>$row[Descr_Tipo]</option>";
	}else{
		$retorno = optionValueGrupo($_GET['grupo']);
	}
	echo $retorno;
?>
