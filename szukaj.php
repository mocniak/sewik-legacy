<?php
//include ("baza.php");
$razem = 1;
$baza = 'sewik_2016';

echo '<h2>Wyniki wyszukiwania</h2>';
echo '<p>baza danych: ' . $baza . '</p>';

$baza_danych = mysql_select_db('sewik_warszawa_2016') or die(mysql_error());

$totalTime = microtime(true);
$time = microtime(true);

echo '<h3>1. Czas zdarzeń</h3>';

echo '<h3>Zmienność roczna</h3>';

echo '<table>
	<tr><th>Liczba</th><th>2007</th><th>2008</th><th>2009</th><th>2010</th><th>2011</th><th>2012</th><th>2013</th><th>2014</th><th>2015</th><th>2016</th><th>Razem</th></tr>
	';

disp_php_row1('zdarzenia', 'zdarzenie', 'miejscowosc', NULL);
disp_php_row1('śmierć na miejscu', 'uczestnicy', 'STUC_KOD', 'ZM');
disp_php_row1('śmierć w ciągu 30 dni', 'uczestnicy', 'STUC_KOD', 'ZC');
disp_php_row1('ciężko rannych', 'uczestnicy', 'STUC_KOD', 'RC');
disp_php_row1('lekko rannych', 'uczestnicy', 'STUC_KOD', 'RL');

echo '</table>';


echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Zmienność miesięczna</h3>';

$query = ("SELECT MONTH(DATA_ZDARZ) AS lp, MONTHNAME(DATA_ZDARZ) AS miesiac, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY lp,miesiac ORDER BY lp ASC;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '</table>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Zmienność w zależności od dnia tygodnia</h3>';

$query = ("SELECT DAYOFWEEK(DATA_ZDARZ) AS lp, DAYNAME(DATA_ZDARZ) AS dzien_tygodnia, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY lp,dzien_tygodnia ORDER BY lp ASC;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Zmienność godzinna</h3>';

$query = ("SELECT HOUR(GODZINA_ZDARZ) AS godzina, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY godzina;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Światło dzienne</h3>';

$query = ("SELECT szos.opis AS oswietlenie, zdarzenia FROM (
    SELECT
      szos_kod, count(*) AS zdarzenia FROM zdarzenie
  GROUP BY SZOS_KOD
  ) AS zdarzenie
    LEFT JOIN szos
    ON zdarzenie.szos_kod=szos.kod ORDER BY zdarzenia DESC");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Warunki atmosferyczne</h3>';

$query = "SELECT sswa.opis AS warunki, zdarzenia FROM (
    SELECT SSWA_KOD, count(*) AS zdarzenia FROM zdarzenie GROUP BY SSWA_KOD) AS zdarzenie
LEFT JOIN sswa ON sswa.kod=zdarzenie.sswa_kod ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>2. Miejsce zdarzenia</h3>
	<h3>Powiaty</h3>';


query('powiat');

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Miasta</h3>';

query('miejscowosc');

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>Prędkość dopuszczalna</h3>';

query('predkosc_dopuszczalna');


echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Charakterystyka miejsca zdarzenia</h3>';

$query = "SELECT chmz.opis AS miejsce, zdarzenia FROM
  (SELECT chmz_kod, COUNT(*) AS zdarzenia
   FROM zdarzenie GROUP BY chmz_kod) AS zdarzenie
  LEFT JOIN chmz ON chmz.kod=zdarzenie.chmz_kod
ORDER BY zdarzenia DESC";

$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>Obszar zabudowany/niezabudowany</h3>';

$query = "SELECT zabu.opis AS obszar, zdarzenia FROM
  (SELECT ZABU_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY ZABU_KOD) AS zdarzenie
  INNER JOIN zabu ON zabu.kod=zdarzenie.zabu_kod ORDER BY zdarzenia DESC";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>Geometria</h3>';

$query = "SELECT geod.opis AS geometria, zdarzenia FROM
  (SELECT geod_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY geod_KOD) AS zdarzenie
  INNER JOIN geod ON geod.kod=zdarzenie.geod_kod ORDER BY zdarzenia DESC;";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Rodzaj drogi</h3>';

$query = "SELECT rodr.opis AS rodzaj_drogi, zdarzenia FROM
  (SELECT rodr_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY rodr_KOD) AS zdarzenie
  INNER JOIN rodr ON rodr.kod=zdarzenie.rodr_kod ORDER BY zdarzenia DESC;";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Rodzaj skrzyżowania</h3>';

$query = "SELECT skrz.opis AS skrzyżowanie, zdarzenia FROM
  (SELECT skrz_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY SKRZ_KOD) AS zdarzenie
  INNER JOIN skrz ON skrz.kod=zdarzenie.skrz_kod ORDER BY zdarzenia DESC;";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Sygnalizacja świetlna</h3>';

$query = "SELECT sysw.opis AS obecnosc_sygnalizacji, zdarzenia FROM
  (SELECT sysw_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY sysw_KOD) AS zdarzenie
  INNER JOIN sysw ON sysw.kod=zdarzenie.sysw_kod ORDER BY zdarzenia DESC;";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>3. Rodzaje zdarzeń, uczestnicy i zachowania</h3>
<h3>Rodzaje zdarzeń drogowych</h3>';

$query = "SELECT szrd.opis AS rodzaj_zdarzenia, zdarzenia FROM
  (SELECT szrd_KOD, COUNT(*) AS zdarzenia FROM zdarzenie GROUP BY szrd_KOD) AS zdarzenie
  LEFT JOIN szrd ON szrd.kod=zdarzenie.szrd_kod ORDER BY zdarzenia DESC;";
$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>Pojazdy uczestników</h3>';

$query = "
		SELECT skar.opis AS rodzaj_pojazdu, wynik.ilosc AS pojazdy FROM (

		skar 
		INNER JOIN
		(
		SELECT pojazdy.rodzaj_pojazdu, COUNT(pojazdy.rodzaj_pojazdu) AS ilosc 
		FROM  pojazdy

		WHERE pojazdy.rodzaj_pojazdu IS NOT NULL GROUP BY pojazdy.rodzaj_pojazdu
		) AS wynik 

		ON wynik.rodzaj_pojazdu=skar.kod)

		UNION 

		SELECT 'Pieszy' AS opis, count(uczestnicy.ssru_kod) AS ilosc FROM uczestnicy WHERE ssru_kod='I' GROUP BY ssru_kod

		ORDER BY pojazdy DESC";

$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


$query = "SELECT RODZAJ_POJAZDU, skar.opis FROM (SELECT DISTINCT RODZAJ_POJAZDU FROM pojazdy) AS pojazdy
		LEFT JOIN 
		skar
		ON pojazdy.rodzaj_pojazdu=skar.kod
		";

$lista_pojazdow = mysql_query($query) or die(mysql_error());
$il_pojazdow = mysql_num_rows($lista_pojazdow);


for ($i = 0; $i < $il_pojazdow; $i++) {
    echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
    $query = "(SELECT opis as przyczyna_zdarzenia, ilosc FROM 
			spsz 
			INNER JOIN (
				SELECT uczestnicy.spsz_kod as przyczyna, COUNT( uczestnicy.spsz_kod ) AS ilosc FROM uczestnicy 
				INNER JOIN 
				pojazdy 
				ON pojazdy.id = uczestnicy.zspo_id
				WHERE pojazdy.rodzaj_pojazdu='" . mysql_result($lista_pojazdow, $i, 0) . "' AND uczestnicy.spsz_kod IS NOT NULL
				GROUP BY uczestnicy.spsz_kod
			) as wynik 
			ON wynik.przyczyna=spsz.kod)
			ORDER BY ilosc DESC";


    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) > 0) disp_table($result);
    else echo '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';
    echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
    $time = microtime(true);
}

echo '<h3>Piesi</h3>';

$query = "SELECT sppi.opis AS opis, wynik AS ilosc FROM (
			SELECT sppi_kod, count(sppi_kod) AS wynik 
			FROM uczestnicy WHERE CHAR_LENGTH(sppi_kod) > 0 GROUP BY sppi_kod) AS uczestnicy
			
			INNER JOIN 
			
			sppi
			
			ON sppi.kod = uczestnicy.sppi_kod
			
			ORDER BY ilosc DESC";

$result = mysql_query($query) or die(mysql_error());
if (mysql_num_rows($result) > 0) disp_table($result);
else echo '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>4. Uczestnicy zdarzeń</h3>
<h3>Struktura ofiar</h3>';

for ($i = 0; $i < $il_pojazdow; $i++) {
    $kodPojazdu = mysql_result($lista_pojazdow, $i, 0);
    echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';

    $query = "SELECT opis, ilosc FROM
  (SELECT
  STUC_KOD,
  COUNT(*) AS ilosc
FROM
  (SELECT
     uczestnicy.stuc_kod,
     ZSPO_ID
   FROM uczestnicy WHERE STUC_KOD != '') AS u
  LEFT JOIN
  pojazdy AS p
    ON p.id = u.zspo_id

WHERE p.rodzaj_pojazdu = '$kodPojazdu' AND u.STUC_KOD IS NOT NULL
GROUP BY STUC_KOD
ORDER BY ilosc DESC) as uczestnicy
LEFT JOIN stuc ON stuc.kod = uczestnicy.STUC_KOD;";

    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) > 0) disp_table($result);
    else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
    echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
    $time = microtime(true);
}


echo '<h3>Piesi</h3>';

$query = "SELECT stuc.opis AS opis, wynik AS ilosc FROM (
			SELECT stuc_kod, count(stuc_kod) AS wynik 
			FROM uczestnicy WHERE CHAR_LENGTH(stuc_kod) > 0 AND ((zspo_id = '') OR (zspo_id IS NULL)) GROUP BY stuc_kod) AS uczestnicy
			
			INNER JOIN 
			
			stuc
			
			ON stuc.kod = uczestnicy.stuc_kod
			
			ORDER BY ilosc DESC";

$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) > 0) disp_table($result);
else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);
echo '<h3>Płeć</h3>';

$query = "SELECT uczestnicy.plec, count(uczestnicy.plec) AS ilosc FROM uczestnicy GROUP BY uczestnicy.plec ORDER BY ilosc DESC";
$result = mysql_query($query);
disp_table($result);
echo '<p class="text-align: center">K - kobieta, M - mężczyzna, N - nieznany</p>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


echo '<h3>Wiek</h3>';

$query = "SELECT (FLOOR((YEAR(DATA_ZDARZ) - YEAR(DATA_UR) - (DATE_FORMAT(DATA_ZDARZ, '%m%d') < DATE_FORMAT(DATA_UR, '%m%d')))/10))*10 AS wiek_od, COUNT(*) AS uczestnicy FROM (SELECT ID, ZSZD_ID, DATA_UR FROM uczestnicy WHERE DATA_UR != '0000-00-00') AS u
LEFT JOIN (SELECT id, DATA_ZDARZ FROM zdarzenie) AS z ON z.ID = u.zszd_id GROUP BY wiek_od ORDER BY wiek_od;";


$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

for ($i = 0; $i < $il_pojazdow; $i++) {
    echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
    $rodzajPojazdu = mysql_result($lista_pojazdow, $i, 0);
    $query = "SELECT
  (FLOOR((YEAR(DATA_ZDARZ) - YEAR(DATA_UR) - (DATE_FORMAT(DATA_ZDARZ, '%m%d') < DATE_FORMAT(DATA_UR, '%m%d'))) / 10)) *
  10       AS wiek_od,
  COUNT(*) AS uczestnicy
FROM (SELECT
        ID,
        ZSZD_ID,
        ZSPO_ID,
        DATA_UR
      FROM uczestnicy
      WHERE DATA_UR != '0000-00-00') AS u
  INNER JOIN (SELECT
                ZSZD_ID,
                ID
              FROM pojazdy
              WHERE RODZAJ_POJAZDU = '$rodzajPojazdu') AS p ON p.ID = u.ZSPO_ID
  LEFT JOIN (SELECT
               id,
               DATA_ZDARZ
             FROM zdarzenie) AS z ON z.ID = u.zszd_id
GROUP BY wiek_od
ORDER BY wiek_od;";
    $result = mysql_query($query) or die(mysql_error());
    if (mysql_num_rows($result) > 0) disp_table($result);
    else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
    echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
    $time = microtime(true);
}

echo '<h3>Piesi</h3>';

$query = "SELECT
  (FLOOR((YEAR(DATA_ZDARZ) - YEAR(DATA_UR) - (DATE_FORMAT(DATA_ZDARZ, '%m%d') < DATE_FORMAT(DATA_UR, '%m%d'))) / 10)) *
  10       AS wiek_od,
  COUNT(*) AS uczestnicy
FROM (SELECT
        ID,
        ZSZD_ID,
        DATA_UR
      FROM uczestnicy
      WHERE DATA_UR != '0000-00-00' AND ZSPO_ID IS NULL) AS u
  LEFT JOIN (SELECT
               id,
               DATA_ZDARZ
             FROM zdarzenie) AS z ON z.ID = u.zszd_id
GROUP BY wiek_od
ORDER BY wiek_od;";

$result = mysql_query($query) or die(mysql_error());

if (mysql_num_rows($result) > 0) disp_table($result);
else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h2>Alkohol i narkotyki</h2>';

echo '<h3>Wszyscy uczestnicy</h3>';

$query = "SELECT IFNULL(opis,'Brak') AS substancje_psychoaktywne, uczestnicy FROM
  (SELECT susw_kod, count(*) AS uczestnicy FROM uczestnicy GROUP BY susw_kod ORDER BY susw_kod) AS u
   LEFT JOIN
   susw
   ON susw.kod = u.susw_kod";

$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

for ($i = 0; $i < $il_pojazdow; $i++) {
    echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
    $rodzajPojazdu = mysql_result($lista_pojazdow, $i, 0);

    $query = "SELECT IFNULL(opis,'Brak') AS dzialanie_substancji_psychoaktywnych, uczestnicy FROM
  (SELECT susw_kod, count(*) AS uczestnicy FROM uczestnicy 
  WHERE zspo_id IN (SELECT id FROM pojazdy WHERE rodzaj_pojazdu = '$rodzajPojazdu')
  GROUP BY susw_kod ORDER BY susw_kod) AS u
   LEFT JOIN
   susw
   ON susw.kod = u.susw_kod";

    $result = mysql_query($query) or die(mysql_error());

    if (mysql_num_rows($result) > 0) disp_table($result);
    else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';

    echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
    $time = microtime(true);
}

echo '<h3>5. Niebezpieczne ulice i skrzyżowania</h3>';

echo '<h3>Ulice</h3>';

$result = mysql_query("SELECT ulica_adres AS ulica, COUNT(ulica_adres) zdarzenia FROM


		(SELECT ulica_adres FROM zdarzenie WHERE ulica_adres IS NOT NULL

		UNION ALL

		SELECT ulica_skrzyz AS ulica_adres FROM zdarzenie WHERE CHAR_LENGTH(ulica_skrzyz) > 0) AS zdarzenie

		GROUP BY ulica_adres ORDER BY zdarzenia DESC LIMIT 50") or die(mysql_error());
disp_table($result);

echo '<p>Jeżeli zdarzenie miało miejsce na skrzyżowaniu jest ono przypisywane do obu ulic.</p>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);
//
echo '<h3>Skrzyżowania</h3>';

$result = mysql_query("SELECT CONCAT_WS(' / ', ulica1, ulica2) AS skrzyzowanie, count(*) AS ilosc FROM
		(SELECT
		CASE WHEN ULICA_ADRES < ULICA_SKRZYZ THEN ULICA_ADRES ELSE ULICA_SKRZYZ END ulica1,
		CASE WHEN ULICA_ADRES < ULICA_SKRZYZ THEN ULICA_SKRZYZ ELSE ULICA_ADRES END ulica2
		FROM zdarzenie WHERE ulica_skrzyz != ''
		) AS tablica

		GROUP BY ulica1, ulica2
		ORDER BY ilosc DESC LIMIT 50
		");
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);
//
echo '<h2>Lista zdarzeń</h2>

		<p>W kolejności wg. miejsca zdarzenia.</p>';

$query = "SELECT zdarzenia.id, zdarzenia.miejscowosc, zdarzenia.ulica, chmz.opis AS miejsce, szrd.opis AS rodzaj_zdarzenia, zdarzenia.data_zdarz FROM

		(SELECT id, gmina, miejscowosc, CONCAT_WS(' ',ulica_adres, numer_domu, ulica_skrzyz) AS ulica, chmz_kod, szrd_kod, data_zdarz FROM zdarzenie  LIMIT 50) AS zdarzenia

		LEFT JOIN

		chmz

		ON chmz.kod = zdarzenia.chmz_kod

		LEFT JOIN

		szrd

		ON szrd.kod = zdarzenia.szrd_kod

		ORDER BY zdarzenia.ulica";


$result = mysql_query($query);
echo '<p>Razem: ' . mysql_num_rows($result) . '</p>';

disp_zdarzenia($result);

echo '<p>Lista pierwszych 50 zdarzeń. Nie znalazłeś poszukiwanego? Zawęź zakres wyszukiwania.</p>';


echo '<p>czas generowania wszystkich raportów: ' . (microtime(true) - $totalTime) . 's</p>';