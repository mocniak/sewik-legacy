<?php
if (isset($_REQUEST["baza"])) {
	$baza = $_REQUEST["baza"];
}
else $baza = 'sewik';

include "connect.php";


$baza_danych = mysql_select_db ($baza) or die(mysql_error());
$uchwyt = mysql_query("set names 'utf8' collate 'utf8_general_ci'");
include ("funkcje.php");

?>