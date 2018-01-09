<?PHP
ini_set( 'display_errors', 'On' ); 
error_reporting( E_ALL );
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>System Ewidencji Wypadków i Kolizji (Rowerowych)</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
	<div id="container">
			<h1><a href="index.php">SEWIK / Rowery</a></h1>
		<div id="menu">
			<ul>
				<li><a href="?id=ogolne">Statystyki ogólnokrajowe</a></li>
				<li><a href="?id=woj">Statystyki dla województw</a></li>
				<li><a href="?id=kontakt">Kontakt</a></li>
			</ul>
		</div>
		<div id="czywiesz">
			<img src="img/czywiesz.png">
		</div>
<?
include ("baza.php");

$miasto = $_REQUEST["miasto"];
$id = $_REQUEST["id"];
$zdarzenie = $_REQUEST["zdarzenie"];

if ($zdarzenie !=NULL) $id = 'zdarzenie';
if (($id == NULL) AND ($miasto != NULL)) $id = 'szukaj';

if ($id == NULL) include ("main.php");
else if ($id == 'ogolne') include ("ogolne.php");
else if ($id == 'zdarzenie') include ("zdarzenie.php");
else if ($id == 'kontakt') include ("kontakt.php");
else if ($id == 'szukaj') include ("szukaj.php");
else if ($id == 'zaaw') include ("szuk_zaaw.php");
?>
		<div id="footer">&copy; 2010</div>
	</div>
</body>
</html>
