<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>System Ewidencji Wypadków i Kolizji</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="style.css" type="text/css">
</head>

<body>
	<div id="container">
			<h1><a href="index.php">SEWIK / Wyszukiwarka</a></h1>
		<div id="menu">
			<ul>
				<li><a href="logout.php">wyloguj</a></li>
				<li><a href="?id=pomoc">pomoc</a></li>
				<li><a href="?id=kontakt">kontakt</a></li>
			</ul>
		</div>
		<div id="czywiesz">
			<!-- <img src="img/czywiesz.png"> -->
			<!-- <img src="img/fenomen_sewik.png" style="margin: 0 10px;" alt="logo fundacji fenomen"> -->
		</div>
<?php
include ("baza.php");

$miasto = $_REQUEST["miasto"];
$id = $_REQUEST["id"];
$woj = $_REQUEST["woj"];
$zdarzenie = $_REQUEST["zdarzenie"];
$grupa = $_REQUEST["grupa"];

if ($zdarzenie !=NULL) $id = 'zdarzenie';
if ($grupa !=NULL) $id = 'grupa';
if (($id == NULL) AND ($miasto != NULL)) $id = 'szukaj';

if ($id == NULL) include ("main.php");
else if ($id == 'ogolne') include ("ogolne.php");
else if ($id == 'zdarzenie') include ("zdarzenie.php");
else if ($id == 'kontakt') include ("kontakt.php");
else if ($id == 'szukaj') include ("szukaj.php");
else if ($id == 'zaaw') include ("szuk_zaaw.php");
else if ($id == 'pomoc') include ("pomoc.php");
else if (($id == 'woj') AND ($woj==NULL)) include ("woj.php");
else if (($id == 'woj') AND ($woj!=NULL)) include ("woj/".$woj.".php");
//mysql_close();
?>
		<div id="footer">Karol Mocniak &copy; 2018</div>
	</div>

<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37506377-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>

</body>
</html>
