<?PHP
ini_set( 'display_errors', 'Off' ); 
error_reporting( ~E_ALL );
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>System Ewidencji Wypadków i Kolizji</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">

<link rel="stylesheet" href="style_min.css" type="text/css">

</head>

<body>
<div id="box"><h1><a href="index.php">SEWIK / Start</a></h1> <div id="kontakt"><a href="?id=kontakt">kontakt</a></div>
<div id="info"><h2>System Ewidencji Wypadków i Kolizji</h2>
<p>Na stronie dostępne są zdarzenia z terenu kilkunastu największych polskich miast oraz wszystkie wypadki i kolizje z udziałem rowerzystów w latach 2007-2016.</p>

<p>Dane na stronie zostały opracowane na podstawie zrzutu z policyjnego Systemu Ewidencji Wypadków i Kolizji udostępnionego przez Komendę Główną Policji dla sieci Miasta dla Rowerów.</p>

<h2>Dlaczego prosimy o logowanie?</h2>

<p>Wykonywanie obliczeń na ogromnej liczbie danych jakie są dostępne na stronie jest znacznym obciążeniem dla serwera, przez co musimy ograniczyć dostęp do wyszukiwarki przed różnymi botami spamującymi sieć. Dodatkowo informacja o osobach i instytucjach korzystających z wyszukiwarki jest dla nas dodatkową mobilizacją do dalszego ulepszenia strony :-)</p>
</div>
<div id="login">
<?
include ("baza.php");
$id = $_REQUEST["id"];
//echo $id;
if ((!$auth) AND ($id!='rejestracja') AND ($id!='kontakt')) $id = 'login';
if ($id == NULL) $id = 'login';
//echo $id;
if ($id == 'login') {
include "logowanie.php";
include "rejestracja.php";
}
else if ($id == 'rejestracja') {
include "rejestracja.php";
}
else if ($id == 'wybierz') {
include "wybierz.php";
}
else if ($id == 'kontakt') {
include "kontakt.php";
}
?>
</div>
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
