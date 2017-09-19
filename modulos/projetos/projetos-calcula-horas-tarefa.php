<?php
	header("Content-Type: text/html; charset=ISO-8859-1",true);
	include("functions.php");

	$inicio = $_GET['inicio'];
	$final  = $_GET['final'];

	$inicio = implode('-',array_reverse(explode('/',substr($inicio, 0, 10))))." ".substr($inicio,-5);
	$final = implode('-',array_reverse(explode('/',substr($final, 0, 10))))." ".substr($final,-5);

	$tempo = mpress_fetch_array(mpress_query("select timediff('$final','$inicio') Horas_Utilizadas"));
	echo (int) str_replace(':','',$tempo['Horas_Utilizadas']);
?>