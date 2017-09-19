<?php
header("Content-Type: text/html; charset=ISO-8859-1",true);
include('functions.php');
echo "<option value=''>Selecione</option>";
echo optionValueFormularios("", $_GET['tabela-estrangeira'], '');
?>