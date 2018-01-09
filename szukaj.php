<?
//include ("baza.php");

$cache_echo .= '<h2>Wyniki wyszukiwania</h2>';
$cache_echo .= '<p>baza danych: '.$baza.'</p>';
function search_part($part) {
	$value = $_REQUEST[$part];
	if ($part!='woj') $part = $part.'_kod';
	if ($value !=NULL) return ' '.$part.'=\''.$value.'\' AND ';
	else return NULL;
}

$id_zdarz = $_REQUEST["zdarzenie"];
$woj = $_REQUEST["woj"];
$pow = $_REQUEST["pow"];
$gmina = $_REQUEST["gmina"];
$miejscowosc = $_REQUEST["miejscowosc"];
if ($miejscowosc ==NULL) $miejscowosc = $miasto;
$ulica = $_REQUEST["ulica"];
$data_od = $_REQUEST["data_od"];
$data_do = $_REQUEST["data_do"];
//$predkosc_dopuszczalna = $_REQUEST["predkosc_dopuszczalna"];

if ($id_zdarz !=NULL) $search = ' id='.$id_zdarz.' AND ';
if ($woj!=NULL) $search = $search.' woj = \''.$woj.'\' AND ';
if ($gmina!=NULL) $search = $search.' gmina = \''.$gmina.'\' AN D ';
//if ($predkosc_dopuszczalna!=NULL) $search = $search.' predkosc_dopuszczalna = \''.$predkosc_dopuszczalna.'\' AND ';
if ($pow!=NULL) $search = $search.' powiat = \''.$pow.'\' AND ';
if ($miejscowosc!=NULL) $search = $search.' miejscowosc = \''.$miejscowosc.'\' AND ';
if ($ulica!=NULL) $search = $search.' (ulica_adres = \''.$ulica.'\' OR ULICA_SKRZYZ = \''.$ulica.'\') AND ';

$skrzy1 = $_REQUEST["skrzy1"];
$skrzy2 = $_REQUEST["skrzy2"];
$skrzy3 = $_REQUEST["skrzy3"];
$skrzy4 = $_REQUEST["skrzy4"];
$skrzy5 = $_REQUEST["skrzy5"];
$skrzy6 = $_REQUEST["skrzy6"];

if (($skrzy1 != NULL) AND ($skrzy2 != NULL)) {
	//echo '<p>Wybrano skrzyżowanie</p>';
	$i = 1;
	while (${'skrzy'.$i} != NULL) {
		//echo ${'skrzy'.$i};
		$skrzyzowania .= '\''.${'skrzy'.$i}.'\', ';
		$i++;
	}
	$search = $search.' ulica_adres IN (' . $skrzyzowania . ' \'xxxxxxx\') AND ulica_skrzyz IN (' . $skrzyzowania . ' \'xxxxxxx\') AND ';
}

$search = $search.search_part('szos');
$search = $search.search_part('sswa');
$search = $search.search_part('szrd');
$search = $search.search_part('stna');
$search = $search.search_part('rodr');
$search = $search.search_part('sysw');
$search = $search.search_part('ozpo');
$search = $search.search_part('geod');
$search = $search.search_part('skrz');
$search = $search.search_part('zabu');
$search = $search.search_part('chmz');
$search = $search.search_part('spip');

if (($data_od!=NULL) AND ($data_do!=NULL)) $search = $search.' data_zdarz BETWEEN \''.$data_od.'\' AND \''.$data_do.'\' ';

else $search = $search.' 1=1 ';

$search = 'WHERE '.$search;

$ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
if ($ip == "") $ip = $_SERVER["REMOTE_ADDR"];

$vowels = array("drop", "delete", "truncate", "insert", "update", "create", "alter");
$search = str_replace($vowels, "", $search);

// sprawdzamy czy rekord jest w cache

$cache_zdarzenie = $search;

//echo $search;

$search_ucz = NULL;
$search_ucz = $search_ucz.search_part('stuc');
$search_ucz = $search_ucz.search_part('spsz');
$search_ucz = $search_ucz.search_part('susw');

$cache_uczestnicy = $search_ucz;

$search_poj = $_POST["usr_grp"];
$sel_typ = $_POST["sel_typ"];
$cache_typ = $sel_typ;
//$cache_echo .= '<p>sel typ: '.$sel_typ.'</p>';

foreach ($search_poj as $pojazd) $cache_pojazdy .= $pojazd.' ';

$cache_zdarzenie = addslashes($cache_zdarzenie);
$cache_pojazdy = addslashes($cache_pojazdy);
$cache_uczestnicy = addslashes($cache_uczestnicy);

$baza_danych = mysql_select_db ('sewik_sewik') or die(mysql_error());

$cache = mysql_query("SELECT * FROM cache WHERE baza = '$baza' AND zdarzenie = '$cache_zdarzenie' AND pojazdy = '$cache_pojazdy' AND typ = '$cache_typ' AND uczestnicy = '$cache_uczestnicy'") or die(mysql_error());

$use_cache = $_REQUEST["cache"];
if ($use_cache  == '') $use_cache = 'yes';

if ((mysql_num_rows($cache) > 0) AND ($use_cache == 'yes')) {
	echo '<p>Znaleziono w cache</p>';
	echo mysql_result($cache, 0, 7);
}
else {
	echo '<p>Nie naleziono w cache</p>';
	$baza_danych = mysql_select_db ($baza) or die(mysql_error());
	if ($_REQUEST['truncate'] != 'no') {
		mysql_query ("TRUNCATE TABLE zdarzenie_temp");
		mysql_query ("TRUNCATE TABLE uczestnicy_temp");
		mysql_query ("TRUNCATE TABLE pojazdy_temp");
		/* tu sie wpisuje limit, jeżeli jest */
		$query = "INSERT INTO zdarzenie_temp (SELECT * FROM zdarzenie ".$search.")";
		$result = mysql_query($query);
		$liczba_zdarzen = mysql_num_rows(mysql_query("SELECT * FROM zdarzenie_temp"));
		
		$query = "INSERT INTO pojazdy_temp (SELECT pojazdy.ID, pojazdy.ZSZD_ID, pojazdy.NR_POJAZDU, pojazdy.RODZAJ_POJAZDU, pojazdy.MARKA, pojazdy.SPSU_KOD, pojazdy.SPSP_KOD, pojazdy.SPIC_KOD, pojazdy.KRAJ_REJ, pojazdy.KRAJ_UBZ, pojazdy.ZSPO_ID FROM zdarzenie_temp as zdarzenie 
		INNER JOIN
		pojazdy 
		ON zdarzenie.id=pojazdy.zszd_id)";

		$result = mysql_query($query) or die(mysql_error());
		$query = "INSERT INTO uczestnicy_temp 
			(SELECT uczestnicy.ID, uczestnicy.ZSZD_ID, uczestnicy.ZSPO_ID, uczestnicy.SSRU_KOD, uczestnicy.DATA_UR, uczestnicy.SOBY_KOD, uczestnicy.PLEC, uczestnicy.SUSU_KOD, uczestnicy.LICZBA_LAT_KIEROWANIA, uczestnicy.SPSZ_KOD, uczestnicy.SRUZ_KOD, uczestnicy.SUSW_KOD, uczestnicy.STUC_KOD, uczestnicy.POD_WPLYWEM, uczestnicy.SUSB_KOD, uczestnicy.OBCOKRAJOWIEC, uczestnicy.ZBIEGL_Z_MIEJSCA, uczestnicy.SPPI_KOD, uczestnicy.MIEJSCE_W_POJ, uczestnicy.SUZZ_KOD, uczestnicy.INWALIDA FROM 
			
			zdarzenie_temp as zdarzenie 
			
			INNER JOIN
			
			uczestnicy
			
			ON zdarzenie.id=uczestnicy.zszd_id)";
		$result = mysql_query($query) or die(mysql_error());

		if ($search_ucz != NULL) {
			//$cache_echo .= '<p>search_ucz != NULL</p>';
			$search_ucz = $search_ucz.' 1=1 ';
			$query = "DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE ".$search_ucz.")";
			$result = mysql_query($query);
		}

		//$cache_echo .= '<p>'.$cache_pojazdy.'</p>';
		echo '<p>'.$sel_typ.'</p>';
		if ($sel_typ != "wszyscy") {

			if (isset($sel_typ)) {
				if ($sel_typ == "rowniez") {
					//$cache_echo .= '<p>rowniez</p>';
					if(empty($search_poj)){
						//$cache_echo .= '<p>Nie wybrano żadnej grupy uczestników.</p>';
//						break;
					}
					else {
						$N = count($search_poj);
						for($i=0; $i < $N; $i++){ // uwaga, Pieszy musi być na pierwszej pozycji!
							if ($search_poj[$i] == 'Pieszy') {
								$result = mysql_query("DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I')");
							}
							else {
								$query = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM pojazdy_temp WHERE RODZAJ_POJAZDU = \''.$search_poj[$i].'\')';
								//$cache_echo .= '<p>"'.$query.'"</p>';
								$result = mysql_query($query) or die(mysql_error());
							}
						}
					}
					//$cache_echo .= '<p>Selekcja pojazdów ok</p>';
				}
				else if ($sel_typ == "tylko") {
					//$cache_echo .= '<p>tylko</p>';
					if(empty($search_poj)){
						$cache_echo .= '<p>Nie wybrano żadnej grupy uczestników.</p>';
//						break;
					}
					else {
						$N = count($search_poj);
						//echo("You selected $N door(s): ");
						$query = 'DELETE FROM zdarzenie_temp WHERE id IN (SELECT ZSZD_ID FROM pojazdy WHERE ';
						for($i=0; $i < $N; $i++){ // uwaga, Pieszy musi być na pierwszej pozycji!
							if ($search_poj[$i] == 'Pieszy') {
								$query_pieszy .= "DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I')";
								//$cache_echo .= '<p>'.$query_pieszy.'</p>';
								$result = mysql_query($query_pieszy) or die(mysql_error());
								$query .= ' 1 = 1 ';
							}
							else if ($i == 0) {
								$query_pieszy .= "DELETE FROM zdarzenie_temp WHERE id IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I')";
								//$cache_echo .= '<p>'.$query_pieszy.'</p>';
								$result = mysql_query($query_pieszy) or die(mysql_error());
							}
							if ($search_poj[$i] != 'Pieszy') {
								if ($i > 0) $query .= ' AND ';
								$query .= '  RODZAJ_POJAZDU != \''.$search_poj[$i].'\' ';
								//if ($i > 0) $query .=  ' ON '.$search_poj[0].'.ZSZD_ID = '.$search_poj[$i].'.ZSZD_ID';
								//$cache_echo .= '<p>"'.$query.'"</p>';
								$query2 = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM pojazdy_temp WHERE RODZAJ_POJAZDU = \''.$search_poj[$i].'\')';
								//$cache_echo .= '<p>"'.$query2.'"</p>';
								$result = mysql_query($query2) or die(mysql_error());
							}
						}
						$query .= ')';
						//$cache_echo .= '<p>'.$query.'<p>';
						$result = mysql_query($query) or die(mysql_error());
					}
				}
				else if ($sel_typ == "wiele") {
					//$cache_echo .= '<p>wiele</p>';
					if(empty($search_poj)){
						$cache_echo .= '<p>Nie wybrano żadnej grupy uczestników.</p>';
//						break;
					}
					else {
						$N = count($search_poj);
						//echo("You selected $N door(s): ");
						$query = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM pojazdy WHERE ';
						for($i=0; $i < $N; $i++){ // uwaga, Pieszy musi być na pierwszej pozycji!
							if ($search_poj[$i] == 'Pieszy') {
								$query = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = \'I\') AND id NOT IN (SELECT ZSZD_ID FROM pojazdy WHERE ';
								//$query_pieszy .= "DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I')";
								//$cache_echo .= '<p>'.$query_pieszy.'</p>';
								//$result = mysql_query($query_pieszy) or die(mysql_error());
								$query .= ' 0 = 1 ';
							}
							else if ($i == 0) {
								//$query_pieszy .= "DELETE FROM zdarzenie_temp WHERE id IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I')";
								//$cache_echo .= '<p>'.$query_pieszy.'</p>';
								//$result = mysql_query($query_pieszy) or die(mysql_error());
							}
							if ($search_poj[$i] != 'Pieszy') {
								if ($i > 0) $query .= ' OR ';
								$query .= '  RODZAJ_POJAZDU = \''.$search_poj[$i].'\' ';
								//if ($i > 0) $query .=  ' ON '.$search_poj[0].'.ZSZD_ID = '.$search_poj[$i].'.ZSZD_ID';
								//$cache_echo .= '<p>"'.$query.'"</p>';
								//$query2 = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM pojazdy_temp WHERE RODZAJ_POJAZDU = \''.$search_poj[$i].'\')';
								//$cache_echo .= '<p>"'.$query2.'"</p>';
								//$result = mysql_query($query2) or die(mysql_error());
							}
						}
						$query .= ')';
						//$cache_echo .= '<p>'.$query.'<p>';
						$result = mysql_query($query) or die(mysql_error());
					}
				}
				/*else if ($sel_typ == 'sprawcy') {
					if (empty($search_poj)) {
						$cache_echo .= '<p>Nie wybrano żadnej grupy uczestników</p>';
						break;
					}
					else {
						$N = count($search_poj);
						echo("You selected $N door(s): ");
						echo '<p>sprawcy</p>';
						for($i=0; $i < $N; $i++){ // uwaga, Pieszy musi być na pierwszej pozycji!
							if ($search_poj[$i] == 'Pieszy') {
								$result = mysql_query("DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT ZSZD_ID FROM uczestnicy_temp WHERE SSRU_KOD = 'I' AND CHAR_LENGTH(spip_kod) > 0)");
							}
							else {
								$query = 'DELETE FROM zdarzenie_temp WHERE id NOT IN (SELECT pojazdy_temp.ZSZD_ID FROM ( 
								(SELECT ZSPO_ID FROM uczestnicy_temp WHERE CHAR_LENGTH(spsz_kod) > 0) AS uczestnicy_temp

								LEFT JOIN

								(SELECT ZSZD_ID, id FROM pojazdy_temp WHERE RODZAJ_POJAZDU = \''.$search_poj[$i].'\') as pojazdy_temp

								ON uczestnicy_temp.zspo_id = pojazdy_temp.id))';
								echo '<p>"'.$query.'"</p>';
								$result = mysql_query($query) or die(mysql_error());
							}
						}
					}
				}*/
			}
		}
		if (($search_ucz != NULL) OR ($search_poj != NULL)) {
			$query = "DELETE FROM uczestnicy_temp WHERE zszd_id NOT IN (SELECT id FROM zdarzenie_temp)";
			$result = mysql_query($query) or die(mysql_error());
			$query = "DELETE FROM pojazdy_temp WHERE zszd_id NOT IN (SELECT id FROM zdarzenie_temp)";
			$result = mysql_query($query) or die(mysql_error());
			//$cache_echo .= '<p>Del OK</p>';
		}
	} // end of if ($_REQUEST['truncate'] != 'no')
	else echo '<p>Don\'t truncate</p>';
	$query = "SELECT id FROM zdarzenie_temp";
	$result = mysql_query($query);
	$razem = mysql_num_rows($result);
	//$cache_echo .= '<p>Razem: '.$razem.'</p>';
	
	$cache_echo .= '<h3>1. Czas zdarzeń</h3>';


	$cache_echo .= '<h3>Zmienność roczna</h3>';

	$cache_echo .= '<table>
	<tr><th>Liczba</th><th>2007</th><th>2008</th><th>2009</th><th>2010</th><th>2011</th><th>2012</th><th>2013</th><th>2014</th><th>2015</th><th>2016</th><th>Razem</th></tr>
	';

	disp_php_row1('zdarzenia', 'zdarzenie_temp', 'miejscowosc', NULL);
	disp_php_row1('śmierć na miejscu', 'uczestnicy_temp', 'STUC_KOD', 'ZM');
	disp_php_row1('śmierć w ciągu 30 dni', 'uczestnicy_temp', 'STUC_KOD', 'ZC');
	disp_php_row1('ciężko rannych', 'uczestnicy_temp', 'STUC_KOD', 'RC');
	disp_php_row1('lekko rannych', 'uczestnicy_temp', 'STUC_KOD', 'RL');

	$cache_echo .= '</table>';
	
	
	
		$cache_echo .= '<h3>Zmienność miesięczna</h3>';

		$cache_echo .= '<table>

		<tr><th>Miesiąc</th><th>Zdarzenia</th><th>%</th></tr>';

		$miesiace = array(1=>'styczeń', 2=>'luty', 3=>'marzec', 4=>'kwiecień', 5=>'maj', 6=>'czerwiec', 7=>'lipiec', 8=>'sierpień', 9=>'wrzesień', 10=>'październik', 11=>'listopad', 12=>'grudzień');

		for ($miesiac = 1; $miesiac <= 12; $miesiac++) {
			
			$cache_echo .= '<tr><td>'.$miesiace[$miesiac].'</td>';
			$query = "SELECT count( * ) AS suma FROM zdarzenie_temp WHERE EXTRACT(MONTH FROM data_zdarz)='$miesiac' GROUP BY EXTRACT(MONTH FROM data_zdarz)";
			$result = mysql_query($query);
			$n = (mysql_num_rows($result) ? mysql_result($result, 0, 0) : 0);
			$proc = round(100 * $n/$razem, 1);
			
			$cache_echo .= '<td>'.$n.'</td><td>'.$proc.'</td></tr>';
		}

		$cache_echo .= '</table>';

		$cache_echo .= '
		<h3>Zmienność w zależności od dnia tygodnia</h3>
		<table>
		<tr><th>Dzień tygodnia</th><th>Zdarzenia</th><th>%</th></tr>
		';

		$tydzien = array(1=>'poniedziałek', 2=>'wtorek', 3=>'środa', 4=>'czwartek', 5=>'piątek', 6=>'sobota', 7=>'niedziela');


		for ($dzien = 1; $dzien <= 7; $dzien++) {
			$cache_echo .= '<tr><td>'.$tydzien[$dzien].'</td>';
			$query = "SELECT count( * ) AS suma FROM zdarzenie_temp WHERE DAYOFWEEK(data_zdarz)='".(($dzien%7)+1)."' GROUP BY DAYOFWEEK(data_zdarz)";
			$result = mysql_query($query);
			$n = (mysql_num_rows($result) ? mysql_result($result, 0, 0) : 0);
			$proc = round(100 * $n/$razem, 1);
			
			$cache_echo .= '<td>'.$n.'</td><td>'.$proc.'</td></tr>
			';
		}

		$cache_echo .= '</table>';
		$cache_echo .= '<h3>Zmienność godzinna</h3>';
		$cache_echo .= '<table>

		<tr><th>Godzina</th><th>Zdarzenia</th><th>%</th></tr>';

		for ($miesiac = 0; $miesiac <= 23; $miesiac++) {
			$cache_echo .= '<tr><td>'.$miesiac.':00 - '.$miesiac.':59</td>';
			$query = "SELECT count( * ) AS suma FROM zdarzenie_temp WHERE EXTRACT( HOUR FROM godzina_zdarz )='$miesiac' GROUP BY EXTRACT( HOUR FROM godzina_zdarz )";
			$result = mysql_query($query);
			$n = (mysql_num_rows($result) ? mysql_result($result, 0, 0) : 0);
			$proc = round(100 * $n/$razem, 1);
			
			$cache_echo .= '<td>'.$n.'</td><td>'.$proc.'</td></tr>';
		}

		$cache_echo .= '</table>

		<h3>Światło dzienne</h3>';

		$query = ("SELECT szos.opis as oświetlenie, COUNT(*) as zdarzenia FROM zdarzenie_temp LEFT JOIN szos ON zdarzenie_temp.szos_kod=szos.kod GROUP BY opis ORDER BY zdarzenia DESC");
		//$cache_echo .= '<p>'.$query.'</p>';
		$result = mysql_query($query) or die(mysql_error());
		disp_table($result);
		$cache_echo .= '<h3>Warunki atmosferyczne</h3>
		';

		$query = "SELECT sswa.opis as warunki, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN sswa ON sswa.kod=zdarzenie_temp.sswa_kod GROUP BY sswa.opis ORDER BY zdarzenia DESC";
			$result = mysql_query($query);
		disp_table($result);
	if ($liczba_zdarzen > 1000000) $cache_echo .= '<p><b>Osiągnięto limit 1000000 zdarzeń! Zawęź zakres wyszukiwania, aby otrzymać więcej danych.</b></p>';
	else {
		$cache_echo .= '
		<h3>2. Miejsce zdarzenia</h3>

		<h3>Powiaty</h3>';


		query('powiat');

		$cache_echo .= '<h3>Miasta</h3>';

		query('miejscowosc');

		$cache_echo .= '<h3>Prędkość dopuszczalna</h3>';

		query('predkosc_dopuszczalna');


		$cache_echo .= '<h3>Charakterystyka miejsca zdarzenia</h3>
		';

		$query = "SELECT chmz.opis as miejsce, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN chmz ON chmz.kod=zdarzenie_temp.chmz_kod GROUP BY chmz.opis ORDER BY zdarzenia DESC";
			$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Obszar zabudowany/niezabudowany</h3>
		';

		$query = "SELECT zabu.opis as obszar, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN zabu ON zabu.kod=zdarzenie_temp.zabu_kod GROUP BY zabu.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Geometria</h3>
		';

		$query = "SELECT geod.opis as geometria, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN geod ON geod.kod=zdarzenie_temp.geod_kod GROUP BY geod.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Rodzaj drogi</h3>
		';

		$query = "SELECT rodr.opis as rodzaj, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN rodr ON rodr.kod=zdarzenie_temp.rodr_kod GROUP BY rodr.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Rodzaj skrzyżowania</h3>
		';

		$query = "SELECT skrz.opis as skrzyżowanie, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN skrz ON skrz.kod=zdarzenie_temp.skrz_kod GROUP BY skrz.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Sygnalizacja świetlna</h3>
		';

		$query = "SELECT sysw.opis as sygnalizacja, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN sysw ON sysw.kod=zdarzenie_temp.sysw_kod GROUP BY sysw.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>3. Rodzaje zdarzeń, uczestnicy i zachowania</h3>

		<h3>Rodzaje zdarzeń drogowych</h3>
		';
		$query = "SELECT szrd.opis as rodzaj_zdarzenia, COUNT(*) as zdarzenia FROM zdarzenie_temp INNER JOIN szrd ON szrd.kod=zdarzenie_temp.szrd_kod GROUP BY szrd.opis ORDER BY zdarzenia DESC";
		$result = mysql_query($query);
		disp_table($result);

		$cache_echo .= '
		<h3>Pojazdy uczestników</h3>
		';
		$query = "
		SELECT skar.opis, wynik.ilosc FROM (

		skar 
		INNER JOIN
		(
		SELECT pojazdy_temp.rodzaj_pojazdu, COUNT(pojazdy_temp.rodzaj_pojazdu) as ilosc 
		FROM  pojazdy_temp

		WHERE pojazdy_temp.rodzaj_pojazdu IS NOT NULL GROUP BY pojazdy_temp.rodzaj_pojazdu
		) as wynik 

		ON wynik.rodzaj_pojazdu=skar.kod)

		UNION 

		SELECT 'Pieszy' as opis, count(uczestnicy_temp.ssru_kod) as ilosc FROM uczestnicy_temp WHERE ssru_kod='I' GROUP BY ssru_kod

		ORDER BY ilosc DESC";
		$result = mysql_query($query);
		disp_table($result);



		$cache_echo .= '
		<h3>Przyczyny sprawców</h3>

		';
		$query = "(SELECT spsz.opis, wynik.rowerzyści, wynik.inni_uczestnicy, wynik.ilosc FROM 

		spsz 

		INNER JOIN (
			SELECT suma.przyczyna, rowery.rowerzyści, inni.inni_uczestnicy, suma.ilosc FROM (
				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS ilosc
				FROM uczestnicy_temp
				WHERE uczestnicy_temp.spsz_kod IS NOT NULL
				GROUP BY uczestnicy_temp.spsz_kod ORDER BY ilosc DESC
			)
			AS suma

			LEFT JOIN (
				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS rowerzyści FROM uczestnicy_temp 
				
				INNER JOIN 
				
				pojazdy_temp 
				
				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
				
				WHERE pojazdy_temp.rodzaj_pojazdu='IS101' AND uczestnicy_temp.spsz_kod IS NOT NULL
				GROUP BY uczestnicy_temp.spsz_kod
			)
			
			AS rowery ON rowery.przyczyna=suma.przyczyna


			LEFT JOIN (
				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS inni_uczestnicy FROM uczestnicy_temp 
				
				INNER JOIN 
				
				pojazdy_temp 
				
				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
				WHERE pojazdy_temp.rodzaj_pojazdu!='IS101' AND uczestnicy_temp.spsz_kod IS NOT NULL
				
				GROUP BY uczestnicy_temp.spsz_kod)

				AS inni 
				ON inni.przyczyna=suma.przyczyna 
				
				GROUP BY suma.przyczyna
				
			) as wynik ON wynik.przyczyna=spsz.kod
		) 

		UNION (
			SELECT spip.opis AS opis, '' as rowerzyści, zdarzenie.wynik AS inni_uczestnicy, zdarzenie.wynik 
			FROM (SELECT spip_kod, count(spip_kod) AS wynik FROM zdarzenie_temp WHERE CHAR_LENGTH(spip_kod) > 0 GROUP BY spip_kod) as zdarzenie

			INNER JOIN 
			
			spip
			
			on spip.kod = zdarzenie.spip_kod
		)

		UNION (
			SELECT sppi.opis AS opis, '' as rowerzyści, uczestnicy.wynik AS inni_uczestnicy, uczestnicy.wynik FROM (SELECT sppi_kod, count(sppi_kod) AS wynik FROM uczestnicy_temp WHERE CHAR_LENGTH(sppi_kod) > 0 GROUP BY sppi_kod) as uczestnicy
			
			INNER JOIN 
			
			sppi
			
			on sppi.kod = uczestnicy.sppi_kod
		)

		ORDER BY ilosc DESC";


		$result = mysql_query($query) or die(mysql_error());
		disp_table($result);
		// spsz.opis, wynik.rowerzyści, wynik.inni_uczestnicy, wynik.ilosc

		$query = "SELECT DISTINCT RODZAJ_POJAZDU, skar.opis FROM pojazdy_temp
		LEFT JOIN 
		skar

		ON pojazdy_temp.rodzaj_pojazdu=skar.kod
		";

		$lista_pojazdow = mysql_query($query) or die(mysql_error());
		//disp_table($lista_pojazdow);
		$il_pojazdow = mysql_num_rows($lista_pojazdow);


		for ($i = 0; $i < $il_pojazdow; $i++) {
			$cache_echo .= '<h3>'.mysql_result($lista_pojazdow, $i, 1).'</h3>';
			$query = "(SELECT opis, ilosc FROM 

			spsz 

			INNER JOIN (
				SELECT uczestnicy_temp.spsz_kod as przyczyna, COUNT( uczestnicy_temp.spsz_kod ) AS ilosc FROM uczestnicy_temp 
				
				INNER JOIN 
				
				pojazdy_temp 
				
				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
				
				WHERE pojazdy_temp.rodzaj_pojazdu='".mysql_result($lista_pojazdow, $i, 0)."' AND uczestnicy_temp.spsz_kod IS NOT NULL
				GROUP BY uczestnicy_temp.spsz_kod
			) as wynik 

			ON wynik.przyczyna=spsz.kod)


			ORDER BY ilosc DESC";


			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';
		}


		$cache_echo .= '<h3>Piesi</h3>';

		$query = "SELECT sppi.opis AS opis, wynik as ilosc FROM (
			SELECT sppi_kod, count(sppi_kod) AS wynik 
			FROM uczestnicy_temp WHERE CHAR_LENGTH(sppi_kod) > 0 GROUP BY sppi_kod) as uczestnicy
			
			INNER JOIN 
			
			sppi
			
			on sppi.kod = uczestnicy.sppi_kod
			
			ORDER BY ilosc DESC";

			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak sprawców w tej grupie uczestników</td></tr></table>';

		$cache_echo .= '<h3>4. Uczestnicy zdarzeń</h3>

		<h3>Struktura ofiar</h3>
		';

		for ($i = 0; $i < $il_pojazdow; $i++) {
			$cache_echo .= '<h3>'.mysql_result($lista_pojazdow, $i, 1).'</h3>';
			$query = "(SELECT opis, ilosc FROM 

			stuc 

			INNER JOIN (
				SELECT uczestnicy_temp.stuc_kod as obrazenia, COUNT( uczestnicy_temp.stuc_kod ) AS ilosc FROM uczestnicy_temp 
				
				INNER JOIN 
				
				pojazdy_temp 
				
				ON pojazdy_temp.id = uczestnicy_temp.zspo_id
				
				WHERE pojazdy_temp.rodzaj_pojazdu='".mysql_result($lista_pojazdow, $i, 0)."' AND uczestnicy_temp.stuc_kod IS NOT NULL
				GROUP BY uczestnicy_temp.stuc_kod
			) as wynik 

			ON wynik.obrazenia=stuc.kod)


			ORDER BY ilosc DESC";


			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
		}


		$cache_echo .= '<h3>Piesi</h3>';

		$query = "SELECT stuc.opis AS opis, wynik as ilosc FROM (
			SELECT stuc_kod, count(stuc_kod) AS wynik 
			FROM uczestnicy_temp WHERE CHAR_LENGTH(stuc_kod) > 0 AND ((zspo_id = '') OR (zspo_id IS NULL)) GROUP BY stuc_kod) as uczestnicy
			
			INNER JOIN 
			
			stuc
			
			on stuc.kod = uczestnicy.stuc_kod
			
			ORDER BY ilosc DESC";

			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';


		$cache_echo .= '<h3>Płeć</h3>';

		$query = "SELECT uczestnicy_temp.plec, count(uczestnicy_temp.plec) as ilosc FROM uczestnicy_temp GROUP BY uczestnicy_temp.plec ORDER BY ilosc DESC";
		$result = mysql_query($query);
		disp_table($result);
		$cache_echo .= '<p class="text-align: center">K - kobieta, M - mężczyzna, N - nieznany</p>';
		$cache_echo .= '<h3>Wiek</h3>';

			$query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc FROM 

			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00') AS rowerzysci 

			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";


		$result = mysql_query($query) or die(mysql_error());
		disp_table($result);
		 
		for ($i = 0; $i < $il_pojazdow; $i++) {
			$cache_echo .= '<h3>'.mysql_result($lista_pojazdow, $i, 1).'</h3>';
			$query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc 

			FROM 

			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00') 

			AS rowerzysci 

			INNER JOIN 

			pojazdy_temp

			ON pojazdy_temp.id = rowerzysci.zspo_id

			WHERE rodzaj_pojazdu='".mysql_result($lista_pojazdow, $i, 0)."'

			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";
			//echo '<p>'.$query.'</p>';
			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
		}

			$cache_echo .= '<h3>Piesi</h3>';

			$query = "SELECT IFNULL(ROUND((2008 - YEAR(rowerzysci.data_ur)), -1), 'brak') as wiek_do, count(rowerzysci.id) as ilosc FROM 

			(SELECT id, zszd_id, zspo_id, data_ur FROM uczestnicy_temp WHERE data_ur!='0000-00-00' AND ssru_kod = 'I') AS rowerzysci 

			GROUP BY ROUND((2008 - YEAR(rowerzysci.data_ur)), -1) ORDER BY wiek_do";
			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
		$cache_echo .= '
		<h3>Alkohol</h3>
		';


			$query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 

			(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp) AS rowerzysci 
			
			
			LEFT JOIN

			susw

			ON susw.kod = rowerzysci.susw_kod

			GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";

		$result = mysql_query($query) or die(mysql_error());
		disp_table($result);
		for ($i = 0; $i < $il_pojazdow; $i++) {
			$cache_echo .= '<h3>'.mysql_result($lista_pojazdow, $i, 1).'</h3>';
			$query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 

			(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp) AS rowerzysci 

			INNER JOIN 

			pojazdy_temp

			ON rowerzysci.zspo_id = pojazdy_temp.id

			LEFT JOIN

			susw

			ON susw.kod = rowerzysci.susw_kod

			WHERE rodzaj_pojazdu='".mysql_result($lista_pojazdow, $i, 0)."'

			GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";
			//echo '<p>'.$query.'</p>';
			$result = mysql_query($query) or die(mysql_error());
			if (mysql_num_rows($result) > 0) disp_table($result);
			else $cache_echo .= '<table><tr><td>Brak ofiar w tej grupie uczestników</td></tr></table>';
		}
		
		/*$cache_echo .= '
		<h3>Piesi</h3>
		';


			$query = "SELECT IFNULL(susw.opis, 'brak') as wplyw, count(rowerzysci.susw_kod) as ilosc FROM 

			(SELECT id, zszd_id, zspo_id, IFNULL(susw_kod, 'brak') as susw_kod FROM uczestnicy_temp WHERE ssru_kod='I') AS rowerzysci 
			
			
			LEFT JOIN

			susw

			ON susw.kod = rowerzysci.susw_kod

			GROUP BY rowerzysci.susw_kod ORDER BY susw.kod";

		$result = mysql_query($query) or die(mysql_error());
		disp_table($result);
*/

		$cache_echo .= '<h3>5. Niebezpieczne ulice i skrzyżowania</h3>';

		$cache_echo .= '<h3>Ulice</h3>';

		$result = mysql_query("SELECT ulica_adres as ulica, COUNT(ulica_adres) zdarzenia FROM 


		(SELECT ulica_adres FROM zdarzenie_temp WHERE ulica_adres IS NOT NULL

		UNION ALL

		SELECT ulica_skrzyz as ulica_adres FROM zdarzenie_temp WHERE CHAR_LENGTH(ulica_skrzyz) > 0) AS zdarzenie

		GROUP BY ulica_adres ORDER BY zdarzenia DESC LIMIT 50") or die(mysql_error()); 
		disp_table($result);

		$cache_echo .= '<p>Jeżeli zdarzenie miało miejsce na skrzyżowaniu jest ono przypisywane do obu ulic.</p>

		<h3>Skrzyżowania</h3>';


		$result = mysql_query("SELECT CONCAT_WS(' / ',ulica1, ulica2) as skrzyzowanie, count(*) AS ilosc FROM 
		(SELECT 
		case when ULICA_ADRES < ULICA_SKRZYZ then ULICA_ADRES else ULICA_SKRZYZ end ulica1,
		case when ULICA_ADRES < ULICA_SKRZYZ then ULICA_SKRZYZ else ULICA_ADRES end ulica2
		FROM zdarzenie_temp WHERE ulica_skrzyz != '' 
		) as tablica

		GROUP BY ulica1, ulica2
		ORDER BY ilosc DESC LIMIT 50
		");
		disp_table($result);
		
		$cache_echo .= '<h2>Lista zdarzeń</h2>
		
		<p>W kolejności wg. miejsca zdarzenia.</p>';

		$query = "SELECT zdarzenia.id, zdarzenia.miejscowosc, zdarzenia.ulica, chmz.opis as miejsce, szrd.opis as rodzaj_zdarzenia, zdarzenia.data_zdarz FROM 
		
		(SELECT id, gmina, miejscowosc, CONCAT_WS(' ',ulica_adres, numer_domu, ulica_skrzyz) as ulica, chmz_kod, szrd_kod, data_zdarz FROM zdarzenie_temp) AS zdarzenia 

		LEFT JOIN

		chmz

		ON chmz.kod = zdarzenia.chmz_kod

		LEFT JOIN 

		szrd

		ON szrd.kod = zdarzenia.szrd_kod

		ORDER BY zdarzenia.ulica LIMIT 50";

		/* jeżeli raport pieszy (z kolumną "pojazd"), to kod poniżej: */

		/*
		$query = "SELECT zdarzenia.id, zdarzenia.miejscowosc, zdarzenia.ulica, chmz.opis as miejsce, skar.opis as pojazd, zdarzenia.data_zdarz FROM 
		
		(SELECT id, gmina, miejscowosc, CONCAT_WS(' ',ulica_adres, numer_domu, ulica_skrzyz) as ulica, chmz_kod, data_zdarz FROM zdarzenie_temp) AS zdarzenia 

		LEFT JOIN

		chmz

		ON chmz.kod = zdarzenia.chmz_kod

		LEFT JOIN

		(SELECT rodzaj_pojazdu, zszd_id FROM pojazdy_temp) as pojazdy

		ON zdarzenia.id = pojazdy.zszd_id

		LEFT JOIN

		skar
		
		ON pojazdy.rodzaj_pojazdu = skar.kod

		ORDER BY zdarzenia.ulica";*/

		$result = mysql_query($query);
		$razem = mysql_num_rows($result);
		$cache_echo .= '<p>Razem: '.$razem.'</p>';

		disp_zdarzenia($result);

		//$cache_echo .= '<p>Lista pierwszych 50 zdarzeń. Nie znalazłeś poszukiwanego? Zawęź zakres wyszukiwania.</p>';

		$baza_danych = mysql_select_db ('sewik_sewik') or die(mysql_error());
	} //koniec if l_zdarzeń > 12000

	echo $cache_echo;

	$cache_echo = addslashes($cache_echo);
	mysql_query("INSERT INTO cache VALUES (NULL, '{$baza}', 'null1', '{$cache_zdarzenie}', '{$cache_pojazdy}', '{$cache_typ}', '{$cache_uczestnicy}', '$cache_echo', '".date('Y-m-d')."', '$sewik_user', $liczba_zdarzen)") or die(mysql_error());
	//mysql_query ("TRUNCATE TABLE zdarzenie_temp");
	//mysql_query ("TRUNCATE TABLE uczestnicy_temp");
	//mysql_query ("TRUNCATE TABLE pojazdy_temp");
} // koniec nieznalezienia w cache


?>
