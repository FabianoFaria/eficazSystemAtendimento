<?php
	ob_start();
	session_start();
	if ($_SESSION['dadosUserLogin']['userID']=="")
		echo "false";
	else
		echo "true";
?>