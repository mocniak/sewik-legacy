
<?

$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
 if ($ip == "") $ip = $_SERVER["REMOTE_ADDR"];

mysql_query("INSERT INTO log (`miasto`, `ip`) VALUES ('$miasto', '$ip')") or die(mysql_error());

echo '<h2>'.$miasto.'</h2>';


echo '<h3>1. Czas zdarzeń</h3>';



$razem = mysql_result(mysql_query("SELECT count( * ) AS suma FROM zdarzenie WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY miejscowosc"), 0, 0);

echo '<h3>Zmienność roczna</h3>';

echo '<table>
<tr><th>Liczba</th><th>2007</th><th>2008</th><th>2009</th><th>Razem</th></tr>
';

disp_php_row('zdarzenia', 'zdarzenie', 'miejscowosc', NULL, $miasto);
disp_php_row('śmierć na miejscu', 'uczestnicy', 'STUC_KOD', 'ZM', $miasto);
disp_php_row('śmierć w ciągu 30 dni', 'uczestnicy', 'STUC_KOD', 'ZC', $miasto);
disp_php_row('ciężko rannych', 'uczestnicy', 'STUC_KOD', 'RC', $miasto);
disp_php_row('lekko rannych', 'uczestnicy', 'STUC_KOD', 'RL', $miasto);

echo '</table>';

echo '<h3>Zmienność miesięczna</h3>';

echo '<table>

<tr><th>Miesiąc</th><th>Zdarzenia</th><th>%</th></tr>';

$miesiace = array(1=>'styczeń', 2=>'luty', 3=>'marzec', 4=>'kwiecień', 5=>'maj', 6=>'czerwiec', 7=>'lipiec', 8=>'sierpień', 9=>'wrzesień', 10=>'październik', 11=>'listopad', 12=>'grudzień');

for ($miesiac = 1; $miesiac <= 12; $miesiac++) {
	
	echo '<tr><td>'.$miesiace[$miesiac].'</td>';
	$query = "SELECT count( * ) AS suma FROM zdarzenie WHERE zdarzenie.miejscowosc = '$miasto' AND EXTRACT(MONTH FROM data_zdarz)='$miesiac' GROUP BY miejscowosc";
	$result = mysql_query($query);
	$n = mysql_result($result, 0, 0);
	$proc = round(100 * $n/$razem, 2);
	
	echo '<td>'.mysql_result($result, 0, 0).'</td><td>'.$proc.'</td></tr>';
}

echo '</table>';

echo '
<h3>Zmienność w zależności od dnia tygodnia</h3>
<table>
<tr><th>Dzień tygodnia</th><th>Zdarzenia</th><th>%</th></tr>
';

$tydzien = array(1=>'poniedziałek', 2=>'wtorek', 3=>'środa', 4=>'czwartek', 5=>'piątek', 6=>'sobota', 7=>'niedziela');


for ($dzien = 1; $dzien <= 7; $dzien++) {
	echo '<tr><td>'.$tydzien[$dzien].'</td>';
	$query = "SELECT count( * ) AS suma FROM zdarzenie WHERE zdarzenie.miejscowosc = '$miasto' AND DAYOFWEEK(data_zdarz)='".(($dzien%7)+1)."' GROUP BY miejscowosc";
	$result = mysql_query($query);
	$n = mysql_result($result, 0, 0);
	$proc = round(100 * $n/$razem);
	
	echo '<td>'.mysql_result($result, 0, 0).'</td><td>'.$proc.'</td></tr>
	';
}

echo '</table>';
echo '<h3>Zmienność godzinna</h3>';
echo '<table>

<tr><th>Godzina</th><th>Zdarzenia</th><th>%</th></tr>';

for ($miesiac = 0; $miesiac <= 23; $miesiac++) {
	echo '<tr><td>'.$miesiac.':00 - '.$miesiac.':59</td>';
	$query = "SELECT count( * ) AS suma FROM zdarzenie WHERE zdarzenie.miejscowosc = '$miasto' AND EXTRACT( HOUR FROM godzina_zdarz )='$miesiac' GROUP BY miejscowosc";
	$result = mysql_query($query);
	$n = mysql_result($result, 0, 0);
	$proc = round(100 * $n/$razem, 1);
	
	echo '<td>'.mysql_result($result, 0, 0).'</td><td>'.$proc.'</td></tr>';
}

echo '</table>

<h3>Światło dzienne</h3>';

$query = ("SELECT szos.opis as oświetlenie, COUNT(*) as zdarzenia FROM zdarzenie LEFT JOIN szos ON zdarzenie.szos_kod=szos.kod WHERE zdarzenie.miejscowosc='$miasto' GROUP BY opis ORDER BY zdarzenia DESC");
//echo '<p>'.$query.'</p>';
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '
<h3>2. Miejsce zdarzenia</h3>
<h3>Charakterystyka miejsca zdarzenia</h3>
';

$query = "SELECT chmz.opis as miejsce, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN chmz ON chmz.kod=zdarzenie.chmz_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY chmz.opis ORDER BY zdarzenia DESC";
	$result = mysql_query($query);
disp_table($result);

echo '
<h3>Geometria drogi</h3>
';

$query = "SELECT geod.opis as geometria, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN geod ON geod.kod=zdarzenie.geod_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY geod.opis ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>Rodzaj drogi</h3>
';

$query = "SELECT rodr.opis as rodzaj, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN rodr ON rodr.kod=zdarzenie.rodr_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY rodr.opis ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>Rodzaj skrzyżowania</h3>
';

$query = "SELECT skrz.opis as skrzyżowanie, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN skrz ON skrz.kod=zdarzenie.skrz_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY skrz.opis ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>Sygnalizacja świetlna</h3>
';

$query = "SELECT sysw.opis as sygnalizacja, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN sysw ON sysw.kod=zdarzenie.sysw_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY sysw.opis ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>3. Rodzaje zdarzeń, uczestnicy i zachowania</h3>

<h3>Rodzaje zdarzeń drogowych</h3>
';
$query = "SELECT szrd.opis as rodzaj_zdarzenia, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN szrd ON szrd.kod=zdarzenie.szrd_kod WHERE zdarzenie.miejscowosc = '$miasto' GROUP BY szrd.opis ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>Pojazdy uczestników</h3>
';
$query = "(SELECT skar.opis, wynik.ilosc FROM skar INNER JOIN (SELECT pojazdy.rodzaj_pojazdu, COUNT(pojazdy.rodzaj_pojazdu) as ilosc FROM (SELECT uczestnicy.zspo_id FROM zdarzenie INNER JOIN uczestnicy ON uczestnicy.zszd_id = zdarzenie.id WHERE miejscowosc='$miasto') AS uczest_po INNER JOIN pojazdy ON uczest_po.zspo_id=pojazdy.id  WHERE pojazdy.rodzaj_pojazdu IS NOT NULL GROUP BY pojazdy.rodzaj_pojazdu) as wynik ON wynik.rodzaj_pojazdu=skar.kod) UNION (SELECT 'Pieszy' as opis, count(uczestnicy.ssru_kod) as ilosc FROM zdarzenie INNER JOIN uczestnicy ON uczestnicy.zszd_id = zdarzenie.id WHERE miejscowosc='$miasto' AND ssru_kod='I' GROUP BY ssru_kod) ORDER BY ilosc DESC";
$result = mysql_query($query);
disp_table($result);

echo '
<h3>Przczyczyny sprawców</h3>
';
$query = "SELECT spsz.opis, wynik.rowerzyści, wynik.inni_uczestnicy, wynik.ilosc FROM spsz INNER JOIN (SELECT suma.przyczyna, rowery.rowerzyści, inni.inni_uczestnicy, suma.ilosc FROM 


(SELECT uczestnicy.spsz_kod as przyczyna, COUNT( uczestnicy.spsz_kod ) AS ilosc
FROM zdarzenie
INNER JOIN uczestnicy ON uczestnicy.zszd_id = zdarzenie.id
WHERE miejscowosc = '$miasto' AND uczestnicy.spsz_kod IS NOT NULL
GROUP BY uczestnicy.spsz_kod ORDER BY ilosc DESC)

AS suma

LEFT JOIN


(SELECT uczestnicy.spsz_kod as przyczyna, COUNT( uczestnicy.spsz_kod ) AS rowerzyści FROM zdarzenie
INNER JOIN uczestnicy INNER JOIN pojazdy ON pojazdy.id = uczestnicy.zspo_id and pojazdy.zszd_id = zdarzenie.id AND uczestnicy.zszd_id = zdarzenie.id 
WHERE miejscowosc = '$miasto' AND pojazdy.rodzaj_pojazdu='IS01' AND uczestnicy.spsz_kod IS NOT NULL
GROUP BY uczestnicy.spsz_kod)

AS rowery ON rowery.przyczyna=suma.przyczyna


LEFT JOIN

(SELECT uczestnicy.spsz_kod as przyczyna, COUNT( uczestnicy.spsz_kod ) AS inni_uczestnicy FROM zdarzenie
INNER JOIN uczestnicy INNER JOIN pojazdy ON pojazdy.id = uczestnicy.zspo_id and pojazdy.zszd_id = zdarzenie.id AND uczestnicy.zszd_id = zdarzenie.id
WHERE miejscowosc = '$miasto' AND pojazdy.rodzaj_pojazdu!='IS01' AND uczestnicy.spsz_kod IS NOT NULL
GROUP BY uczestnicy.spsz_kod)

AS inni ON inni.przyczyna=suma.przyczyna GROUP BY suma.przyczyna) as wynik ON wynik.przyczyna=spsz.kod ORDER BY wynik.ilosc DESC";
$result = mysql_query($query) or die(mysql_error());
disp_table($result);


echo '<h3>4. Rowerzyści - uczestnicy zdarzeń</h3>';

echo '<h3>Płeć</h3>';

$query = "SELECT uczestnicy.plec, count(uczestnicy.plec) as ilosc FROM (SELECT id, zszd_id, rodzaj_pojazdu FROM pojazdy WHERE rodzaj_pojazdu='IS01') as rowery INNER JOIN uczestnicy ON rowery.id=uczestnicy.zspo_id INNER JOIN (SELECT id FROM zdarzenie WHERE miejscowosc='$miasto') as miasto ON rowery.zszd_id=miasto.id GROUP BY uczestnicy.plec ORDER BY ilosc DESC";
$result = mysql_query($query);
disp_table($result);
echo '<p class="text-align: center">K - kobieta, M - mężczyzna, N - nieznany</p>';
echo '<h3>Wiek</h3>';


	$query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc FROM 

	(SELECT id, zszd_id, rodzaj_pojazdu FROM pojazdy WHERE rodzaj_pojazdu='IS01') as rowery 

	INNER JOIN 

	(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy) AS rowerzysci 
	
	ON rowery.id=rowerzysci.zspo_id

	INNER JOIN 

	(SELECT id, miejscowosc FROM zdarzenie WHERE miejscowosc='$miasto') as miasto 

	ON rowery.zszd_id=miasto.id 

	GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";


$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '
<h3>Alkohol</h3>
';


	$query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 

	(SELECT id, zszd_id, rodzaj_pojazdu FROM pojazdy WHERE rodzaj_pojazdu='IS01') as rowery 

	INNER JOIN 

	(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy) AS rowerzysci 
	
	ON rowery.id=rowerzysci.zspo_id

	INNER JOIN 

	(SELECT id, miejscowosc FROM zdarzenie WHERE miejscowosc='$miasto') as miasto 

	ON rowery.zszd_id=miasto.id 
	
	LEFT JOIN

	susw

	ON susw.kod = rowerzysci.susw_kod

	GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";


$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<h3>5. Niebezpieczne ulice i skrzyżowania</h3>';

echo '<h3>Ulice</h3>';

$result = mysql_query("SELECT ulica_adres as ulica, COUNT(ulica_adres) zdarzenia FROM 


(SELECT ulica_adres FROM zdarzenie WHERE miejscowosc='$miasto' AND ulica_adres IS NOT NULL

UNION ALL

SELECT ulica_skrzyz as ulica_adres FROM zdarzenie WHERE miejscowosc='$miasto' AND CHAR_LENGTH(ulica_skrzyz) > 0) AS zdarzenie

GROUP BY ulica_adres ORDER BY zdarzenia DESC LIMIT 40") or die(mysql_error()); 
disp_table($result);

echo '<p>Jeżeli zdarzenie miało miejsce na skrzyżowaniu jest ono przypisywane do obu ulic.</p>

<h3>Skrzyżowania</h3>';


$result = mysql_query("SELECT CONCAT_WS(' / ',ulica_adres,ulica_skrzyz) as skrzyzowanie, COUNT(ulica_adres) as zdarzenia FROM zdarzenie WHERE miejscowosc='$miasto' AND CHAR_LENGTH(ulica_skrzyz) > 0

GROUP BY skrzyzowanie ORDER BY zdarzenia DESC LIMIT 25") or die(mysql_error()); 
disp_table($result);

?>
