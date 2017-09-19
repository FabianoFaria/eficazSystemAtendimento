<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("../../config.php");
	include("../../includes/functions.gerais.php");
	if($_GET['pagina'] == ""){
		$rs = mpress_query("select distinct Pagina_Nome, Pagina_Slug
								from help_paginas
								where Modulo_Slug = '".$_GET['modulo']."' $strPagina
								order by Pagina_Nome");

		while($row = mpress_fetch_array($rs)){
				$j++;
				$options .= "<option value='".$row['Pagina_Slug']."'>".$row['Pagina_Nome']."</option>";
		}
	}
	if($_GET['pagina'] != ""){
		$rs = mpress_query("select Pagina_Secundaria_Nome, Pagina_Secundaria_Slug
										from help_paginas
										where Pagina_Slug = '".$_GET['pagina']."'
										and Pagina_Secundaria_Nome is not null
										order by Pagina_Secundaria_Nome");

		while($row = mpress_fetch_array($rs)){
				$j++;
				$options .= "<option value='".$row['Pagina_Secundaria_Slug']."'>".$row['Pagina_Secundaria_Nome']."</option>";
		}
	}

	if($_GET['pagina'] == "") $required = "class='required'";
	if($j=="") $classe = " disabled ";
?>
<select name="select-pagina-principal[]" id="select-pagina-principal<?php echo $_GET['pagina']?>" <?php echo $classe." ".$required?>>
	<option value="">selecione</option>
	<?php echo $options?>
</select>