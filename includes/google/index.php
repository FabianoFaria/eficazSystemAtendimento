<?php
	include('../../config.php');
	$tipoBusca = explode(",",$_GET[tipo]);
	foreach($tipoBusca as $busca){
		$j++;
		if($j==1)
			$strTipoCadastro .= " Tipo_cadastro like '%\"$busca\"%'";
		else
			$strTipoCadastro .= " or Tipo_cadastro like '%\"$busca\"%'";
	}
	$result = mpress_query("select d.Nome,Logradouro, Numero, Complemento, Bairro, Cidade, UF
							from cadastros_dados d
							inner join cadastros_enderecos e on e.Cadastro_ID = d.Cadastro_ID
							where cidade <> '' and logradouro <> '' and ($strTipoCadastro)");
	while($row = mpress_fetch_array($result)){
		$resultado = 1;
		$enderecoLoc  .= $virgula."\"".$row['Logradouro']." ".$row['Numero']." ".$row['Bairro']." ".$row['Cidade']." ".$row['UF']."\"";
		$prestadorLoc .= $virgula."\"".$row['Nome']."\"";
		$virgula = ",";
	}
?>
<script>
	var enderecoLoc = [];
	var prestadorLoc = [];
	if('<?php echo $resultado?>' != ""){
		enderecoLoc  = [<?php echo $enderecoLoc?>]
		prestadorLoc = [<?php echo $prestadorLoc?>]
	}
</script>

   <link rel="stylesheet" type="text/css" href="css/estilo.css">
	<form method="post" action="index.html">
		<input type="hidden" id="txtEnderecoPartida" name="txtEnderecoPartida" value='<?php echo $_GET['origem']?>'/>
		<input type="hidden" id="txtEnderecoChegada" name="txtEnderecoChegada" value='<?php echo $_GET['destino']?>'/>
	</form>

	<div id='site'>
		<div id="mapa"></div>
		<div id="trajeto-texto"></div>
	</div>

	<script src="js/jquery.min.js"></script>
	<script src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
	<script src="js/mapa.js"></script>