<?php
//include ("baza.php");
$razem = 1;
$baza = 'sewik_2016';

echo '<h2>Wyniki wyszukiwania</h2>';
echo '<p>baza danych: ' . $baza . '</p>';

$baza_danych = mysql_select_db('sewik_warszawa_2016') or die(mysql_error());

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

$query = ("SELECT MONTH(DATA_ZDARZ) as lp, MONTHNAME(DATA_ZDARZ) as miesiac, COUNT(*) as zdarzenia FROM zdarzenie GROUP BY lp,miesiac ORDER BY lp ASC;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '</table>';

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Zmienność w zależności od dnia tygodnia</h3>';

$query = ("SELECT DAYOFWEEK(DATA_ZDARZ) as lp, DAYNAME(DATA_ZDARZ) as dzien_tygodnia, COUNT(*) as zdarzenia FROM zdarzenie GROUP BY lp,dzien_tygodnia ORDER BY lp ASC;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Zmienność godzinna</h3>';

$query = ("SELECT HOUR(GODZINA_ZDARZ) as godzina, COUNT(*) as zdarzenia FROM zdarzenie GROUP BY godzina;");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Światło dzienne</h3>';

$query = ("SELECT szos.opis as oswietlenie, zdarzenia FROM (
    SELECT
      szos_kod, count(*) as zdarzenia FROM zdarzenie
  GROUP BY SZOS_KOD
  ) as zdarzenie
    LEFT JOIN szos
    ON zdarzenie.szos_kod=szos.kod ORDER BY zdarzenia DESC");
$result = mysql_query($query) or die(mysql_error());
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);

echo '<h3>Warunki atmosferyczne</h3>';

$query = "SELECT sswa.opis as warunki, zdarzenia FROM (
    SELECT SSWA_KOD, count(*) as zdarzenia FROM zdarzenie GROUP BY SSWA_KOD) AS zdarzenie
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

$query = "SELECT chmz.opis as miejsce, zdarzenia FROM
  (SELECT chmz_kod, COUNT(*) as zdarzenia
   FROM zdarzenie GROUP BY chmz_kod) as zdarzenie
  LEFT JOIN chmz ON chmz.kod=zdarzenie.chmz_kod
ORDER BY zdarzenia DESC";

$result = mysql_query($query);
disp_table($result);

echo '<p>czas generowania raportu: ' . (microtime(true) - $time) . 's</p>';
$time = microtime(true);


//    echo '
//		<h3>Obszar zabudowany/niezabudowany</h3>
//		';
//
//    $query = "SELECT zabu.opis as obszar, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN zabu ON zabu.kod=zdarzenie.zabu_kod GROUP BY zabu.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>Geometria</h3>
//		';
//
//    $query = "SELECT geod.opis as geometria, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN geod ON geod.kod=zdarzenie.geod_kod GROUP BY geod.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>Rodzaj drogi</h3>
//		';
//
//    $query = "SELECT rodr.opis as rodzaj, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN rodr ON rodr.kod=zdarzenie.rodr_kod GROUP BY rodr.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>Rodzaj skrzyżowania</h3>
//		';
//
//    $query = "SELECT skrz.opis as skrzyżowanie, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN skrz ON skrz.kod=zdarzenie.skrz_kod GROUP BY skrz.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>Sygnalizacja świetlna</h3>
//		';
//
//    $query = "SELECT sysw.opis as sygnalizacja, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN sysw ON sysw.kod=zdarzenie.sysw_kod GROUP BY sysw.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>3. Rodzaje zdarzeń, uczestnicy i zachowania</h3>
//
//		<h3>Rodzaje zdarzeń drogowych</h3>
//		';
//    $query = "SELECT szrd.opis as rodzaj_zdarzenia, COUNT(*) as zdarzenia FROM zdarzenie INNER JOIN szrd ON szrd.kod=zdarzenie.szrd_kod GROUP BY szrd.opis ORDER BY zdarzenia DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//    echo '
//		<h3>Pojazdy uczestników</h3>
//		';
//    $query = "
//		SELECT skar.opis, wynik.ilosc FROM (
//
//		skar 
//		INNER JOIN
//		(
//		SELECT pojazdy_temp.rodzaj_pojazdu, COUNT(pojazdy_temp.rodzaj_pojazdu) as ilosc 
//		FROM  pojazdy_temp
//
//		WHERE pojazdy_temp.rodzaj_pojazdu IS NOT NULL GROUP BY pojazdy_temp.rodzaj_pojazdu
//		) as wynik 
//
//		ON wynik.rodzaj_pojazdu=skar.kod)
//
//		UNION 
//
//		SELECT 'Pieszy' as opis, count(uczestnicy_temp.ssru_kod) as ilosc FROM uczestnicy_temp WHERE ssru_kod='I' GROUP BY ssru_kod
//
//		ORDER BY ilosc DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//
//
//    echo '
//		<h3>Przyczyny sprawców</h3>
//
//		';
//    $query = "(SELECT spsz.opis, wynik.rowerzyści, wynik.inni_uczestnicy, wynik.ilosc FROM 
//
//		spsz 
//
//		INNER JOIN (
//			SELECT suma.przyczyna, rowery.rowerzyści, inni.inni_uczestnicy, suma.ilosc FROM (
//				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS ilosc
//				FROM uczestnicy_temp
//				WHERE uczestnicy_temp.spsz_kod IS NOT NULL
//				GROUP BY uczestnicy_temp.spsz_kod ORDER BY ilosc DESC
//			)
//			AS suma
//
//			LEFT JOIN (
//				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS rowerzyści FROM uczestnicy_temp 
//				
//				INNER JOIN 
//				
//				pojazdy_temp 
//				
//				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
//				
//				WHERE pojazdy_temp.rodzaj_pojazdu='IS101' AND uczestnicy_temp.spsz_kod IS NOT NULL
//				GROUP BY uczestnicy_temp.spsz_kod
//			)
//			
//			AS rowery ON rowery.przyczyna=suma.przyczyna
//
//
//			LEFT JOIN (
//				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS inni_uczestnicy FROM uczestnicy_temp 
//				
//				INNER JOIN 
//				
//				pojazdy_temp 
//				
//				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
//				WHERE pojazdy_temp.rodzaj_pojazdu!='IS101' AND uczestnicy_temp.spsz_kod IS NOT NULL
//				
//				GROUP BY uczestnicy_temp.spsz_kod)
//
//				AS inni 
//				ON inni.przyczyna=suma.przyczyna 
//				
//				GROUP BY suma.przyczyna
//				
//			) as wynik ON wynik.przyczyna=spsz.kod
//		) 
//
//		UNION (
//			SELECT spip.opis AS opis, '' as rowerzyści, zdarzenie.wynik AS inni_uczestnicy, zdarzenie.wynik 
//			FROM (SELECT spip_kod, count(spip_kod) AS wynik FROM zdarzenie WHERE CHAR_LENGTH(spip_kod) > 0 GROUP BY spip_kod) as zdarzenie
//
//			INNER JOIN 
//			
//			spip
//			
//			on spip.kod = zdarzenie.spip_kod
//		)
//
//		UNION (
//			SELECT sppi.opis AS opis, '' as rowerzyści, uczestnicy.wynik AS inni_uczestnicy, uczestnicy.wynik FROM (SELECT sppi_kod, count(sppi_kod) AS wynik FROM uczestnicy_temp WHERE CHAR_LENGTH(sppi_kod) > 0 GROUP BY sppi_kod) as uczestnicy
//			
//			INNER JOIN 
//			
//			sppi
//			
//			on sppi.kod = uczestnicy.sppi_kod
//		)
//
//		ORDER BY ilosc DESC";
//
//
//    $result = mysql_query($query) or die(mysql_error());
//    disp_table($result);
//    // spsz.opis, wynik.rowerzyści, wynik.inni_uczestnicy, wynik.ilosc
//
//    $query = "SELECT DISTINCT RODZAJ_POJAZDU, skar.opis FROM pojazdy_temp
//		LEFT JOIN 
//		skar
//
//		ON pojazdy_temp.rodzaj_pojazdu=skar.kod
//		";
//
//    $lista_pojazdow = mysql_query($query) or die(mysql_error());
//    //disp_table($lista_pojazdow);
//    $il_pojazdow = mysql_num_rows($lista_pojazdow);
//
//
//    for ($i = 0; $i < $il_pojazdow; $i++) {
//        echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
//        $query = "(SELECT opis, ilosc FROM 
//
//			spsz 
//
//			INNER JOIN (
//				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS ilosc FROM uczestnicy_temp 
//				
//				INNER JOIN 
//				
//				pojazdy_temp 
//				
//				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
//				
//				WHERE pojazdy_temp.rodzaj_pojazdu='" . mysql_result($lista_pojazdow, $i, 0) . "' AND uczestnicy_temp.spsz_kod IS NOT NULL
//				GROUP BY uczestnicy_temp.spsz_kod
//			) as wynik 
//
//			ON wynik.przyczyna=spsz.kod)
//
//
//			ORDER BY ilosc DESC";
//
//
//        $result = mysql_query($query) or die(mysql_error());
//        if (mysql_num_rows($result) > 0) disp_table($result);
//        else echo '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';
//    }
//
//
//    echo '<h3>Piesi</h3>';
//
//    $query = "SELECT sppi.opis AS opis, wynik as ilosc FROM (
//			SELECT sppi_kod, count(sppi_kod) AS wynik 
//			FROM uczestnicy_temp WHERE CHAR_LENGTH(sppi_kod) > 0 GROUP BY sppi_kod) as uczestnicy
//			
//			INNER JOIN 
//			
//			sppi
//			
//			on sppi.kod = uczestnicy.sppi_kod
//			
//			ORDER BY ilosc DESC";
//
//    $result = mysql_query($query) or die(mysql_error());
//    if (mysql_num_rows($result) > 0) disp_table($result);
//    else echo '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';
//
//    echo '<h3>4. Uczestnicy zdarzeń</h3>
//
//		<h3>Struktura ofiar</h3>
//		';
//
//    for ($i = 0; $i < $il_pojazdow; $i++) {
//        echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
//        $query = "(SELECT opis, ilosc FROM 
//
//			stuc 
//
//			INNER JOIN (
//				SELECT uczestnicy_temp.stuc_kod as obrazenia, COUNT( uczestnicy_temp.stuc_kod ) AS ilosc FROM uczestnicy_temp 
//				
//				INNER JOIN 
//				
//				pojazdy_temp 
//				
//				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
//				
//				WHERE pojazdy_temp.rodzaj_pojazdu='" . mysql_result($lista_pojazdow, $i, 0) . "' AND uczestnicy_temp.stuc_kod IS NOT NULL
//				GROUP BY uczestnicy_temp.stuc_kod
//			) as wynik 
//
//			ON wynik.obrazenia=stuc.kod)
//
//
//			ORDER BY ilosc DESC";
//
//
//        $result = mysql_query($query) or die(mysql_error());
//        if (mysql_num_rows($result) > 0) disp_table($result);
//        else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
//    }
//
//
//    echo '<h3>Piesi</h3>';
//
//    $query = "SELECT stuc.opis AS opis, wynik as ilosc FROM (
//			SELECT stuc_kod, count(stuc_kod) AS wynik 
//			FROM uczestnicy_temp WHERE CHAR_LENGTH(stuc_kod) > 0 AND ((zspo_id = '') OR (zspo_id IS NULL)) GROUP BY stuc_kod) as uczestnicy
//			
//			INNER JOIN 
//			
//			stuc
//			
//			on stuc.kod = uczestnicy.stuc_kod
//			
//			ORDER BY ilosc DESC";
//
//    $result = mysql_query($query) or die(mysql_error());
//    if (mysql_num_rows($result) > 0) disp_table($result);
//    else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
//
//
//    echo '<h3>Płeć</h3>';
//
//    $query = "SELECT uczestnicy_temp.plec, count(uczestnicy_temp.plec) as ilosc FROM uczestnicy_temp GROUP BY uczestnicy_temp.plec ORDER BY ilosc DESC";
//    $result = mysql_query($query);
//    disp_table($result);
//    echo '<p class="text-align: center">K - kobieta, M - mężczyzna, N - nieznany</p>';
//    echo '<h3>Wiek</h3>';
//
//    $query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc FROM 
//
//			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00') AS rowerzysci 
//
//			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";
//
//
//    $result = mysql_query($query) or die(mysql_error());
//    disp_table($result);
//
//    for ($i = 0; $i < $il_pojazdow; $i++) {
//        echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
//        $query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc 
//
//			FROM 
//
//			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00') 
//
//			AS rowerzysci 
//
//			INNER JOIN 
//
//			pojazdy_temp
//
//			ON pojazdy_temp.id = rowerzysci.zspo_id
//
//			WHERE rodzaj_pojazdu='" . mysql_result($lista_pojazdow, $i, 0) . "'
//
//			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";
//        //echo '<p>'.$query.'</p>';
//        $result = mysql_query($query) or die(mysql_error());
//        if (mysql_num_rows($result) > 0) disp_table($result);
//        else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
//    }
//
//    echo '<h3>Piesi</h3>';
//
//    $query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc FROM 
//
//			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00' AND ssru_kod = 'I') AS rowerzysci 
//
//			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";
//    $result = mysql_query($query) or die(mysql_error());
//    if (mysql_num_rows($result) > 0) disp_table($result);
//    else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
//    echo '
//		<h3>Alkohol</h3>
//		';
//
//
//    $query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 
//
//			(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp) AS rowerzysci 
//			
//			
//			LEFT JOIN
//
//			susw
//
//			ON susw.kod = rowerzysci.susw_kod
//
//			GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";
//
//    $result = mysql_query($query) or die(mysql_error());
//    disp_table($result);
//    for ($i = 0; $i < $il_pojazdow; $i++) {
//        echo '<h3>' . mysql_result($lista_pojazdow, $i, 1) . '</h3>';
//        $query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 
//
//			(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp) AS rowerzysci 
//
//			INNER JOIN 
//
//			pojazdy_temp
//
//			ON rowerzysci.zspo_id = pojazdy_temp.id
//
//			LEFT JOIN
//
//			susw
//
//			ON susw.kod = rowerzysci.susw_kod
//
//			WHERE rodzaj_pojazdu='" . mysql_result($lista_pojazdow, $i, 0) . "'
//
//			GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";
//        //echo '<p>'.$query.'</p>';
//        $result = mysql_query($query) or die(mysql_error());
//        if (mysql_num_rows($result) > 0) disp_table($result);
//        else echo '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
//    }
//
//    /*echo '
//    <h3>Piesi</h3>
//    ';
//
//
//        $query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 
//
//        (SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp WHERE ssru_kod='I') AS rowerzysci 
//        
//        
//        LEFT JOIN
//
//        susw
//
//        ON susw.kod = rowerzysci.susw_kod
//
//        GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";
//
//    $result = mysql_query($query) or die(mysql_error());
//    disp_table($result);
//*/
//
//    echo '<h3>5. Niebezpieczne ulice i skrzyżowania</h3>';
//
//    echo '<h3>Ulice</h3>';
//
//    $result = mysql_query("SELECT ulica_adres as ulica, COUNT(ulica_adres) zdarzenia FROM 
//
//
//		(SELECT ulica_adres FROM zdarzenie WHERE ulica_adres IS NOT NULL
//
//		UNION ALL
//
//		SELECT ulica_skrzyz as ulica_adres FROM zdarzenie WHERE CHAR_LENGTH(ulica_skrzyz) > 0) AS zdarzenie
//
//		GROUP BY ulica_adres ORDER BY zdarzenia DESC LIMIT 50") or die(mysql_error());
//    disp_table($result);
//
//    echo '<p>Jeżeli zdarzenie miało miejsce na skrzyżowaniu jest ono przypisywane do obu ulic.</p>
//
//		<h3>Skrzyżowania</h3>';
//
//
//    $result = mysql_query("SELECT CONCAT_WS(' / ',ulica1, ulica2) as skrzyzowanie, count(*) AS ilosc FROM 
//		(SELECT 
//		case when ULICA_ADRES < ULICA_SKRZYZ then ULICA_ADRES else ULICA_SKRZYZ end ulica1,
//		case when ULICA_ADRES < ULICA_SKRZYZ then ULICA_SKRZYZ else ULICA_ADRES end ulica2
//		FROM zdarzenie WHERE ulica_skrzyz != '' 
//		) as tablica
//
//		GROUP BY ulica1, ulica2
//		ORDER BY ilosc DESC LIMIT 50
//		");
//    disp_table($result);
//
//    echo '<h2>Lista zdarzeń</h2>
//		
//		<p>W kolejności wg. miejsca zdarzenia.</p>';
//
//    $query = "SELECT zdarzenia.id, zdarzenia.miejscowosc, zdarzenia.ulica, chmz.opis as miejsce, szrd.opis as rodzaj_zdarzenia, zdarzenia.data_zdarz FROM 
//		
//		(SELECT id, gmina, miejscowosc, CONCAT_WS(' ',ulica_adres, numer_domu, ulica_skrzyz) as ulica, chmz_kod, szrd_kod, data_zdarz FROM zdarzenie) AS zdarzenia 
//
//		LEFT JOIN
//
//		chmz
//
//		ON chmz.kod = zdarzenia.chmz_kod
//
//		LEFT JOIN 
//
//		szrd
//
//		ON szrd.kod = zdarzenia.szrd_kod
//
//		ORDER BY zdarzenia.ulica LIMIT 50";
//
//    /* jeżeli raport pieszy (z kolumną "pojazd"), to kod poniżej: */
//
//    /*
//    $query = "SELECT zdarzenia.id, zdarzenia.miejscowosc, zdarzenia.ulica, chmz.opis as miejsce, skar.opis as pojazd, zdarzenia.data_zdarz FROM 
//    
//    (SELECT id, gmina, miejscowosc, CONCAT_WS(' ',ulica_adres, numer_domu, ulica_skrzyz) as ulica, chmz_kod, data_zdarz FROM zdarzenie) AS zdarzenia 
//
//    LEFT JOIN
//
//    chmz
//
//    ON chmz.kod = zdarzenia.chmz_kod
//
//    LEFT JOIN
//
//    (SELECT rodzaj_pojazdu, zszd_id FROM pojazdy_temp) as pojazdy
//
//    ON zdarzenia.id = pojazdy.zszd_id
//
//    LEFT JOIN
//
//    skar
//    
//    ON pojazdy.rodzaj_pojazdu = skar.kod
//
//    ORDER BY zdarzenia.ulica";*/
//
//    $result = mysql_query($query);
//    $razem = mysql_num_rows($result);
//    echo '<p>Razem: ' . $razem . '</p>';
//
//    disp_zdarzenia($result);
//
//    //echo '<p>Lista pierwszych 50 zdarzeń. Nie znalazłeś poszukiwanego? Zawęź zakres wyszukiwania.</p>';
//
//    $baza_danych = mysql_select_db('sewik_sewik') or die(mysql_error());
//} //koniec if l_zdarzeń > 12000
//
