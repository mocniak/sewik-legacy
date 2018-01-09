<?php
echo '<h2>Zdarzenie: '.$zdarzenie.'</h2>';
echo '<div style="float: left; width: 360px; margin: 5px;"><h3>Lokalizacja</h3>';

$query = "SELECT * FROM zdarzenie WHERE id=$zdarzenie";
$result = mysql_query($query);

echo '<p>'.mysql_result($result, 0, 5).'</p>';
echo '<p>'.mysql_result($result, 0, 7).'</p>';
echo '<p>Gmina: '.mysql_result($result, 0, 6).'</p>';
echo '<p>Miejscowość: '.mysql_result($result, 0, 8).'</p>';
echo '<p>Adres: '.mysql_result($result, 0, 9).' '.mysql_result($result, 0, 10).'</p>';
echo '<p>Skrzyżowanie z: '.mysql_result($result, 0, 28).'</p>';
echo '<h3>Czas</h3>';

echo '<p>Data zdarzenia: '.mysql_result($result, 0, 12).'</p>';
echo '<p>Godzina: '.mysql_result($result, 0, 13).'</p>';

$kod = mysql_result($result, 0, 15);
$query = "SELECT opis FROM szos WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Oświetlenie: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 26);
$query = "SELECT opis FROM sswa WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Warunki atmosferyczne: '.mysql_result($result_1, 0, 0).'</p>';

echo '<h3>Opis zdarzenia</h3>';

$kod = mysql_result($result, 0, 16);
$query = "SELECT opis FROM szrd WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Rodzaj zdarzenia: '.mysql_result($result_1, 0, 0).'</p>';

echo '<h3>Charakterystyka miejsca zdarzenia</h3>';

echo '<p>Prędkość dopuszczalna: '.mysql_result($result, 0, 14).'km/h</p>';

$kod = mysql_result($result, 0, 19);
$query = "SELECT opis FROM stna WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Stan nawierzchni: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 20);
$query = "SELECT opis FROM rodr WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Rodzaj drogi: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 21);
$query = "SELECT opis FROM sysw WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Sygnalizacja świetlna: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 22);
$query = "SELECT opis FROM ozpo WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Oznakowanie poziome: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 23);
$query = "SELECT opis FROM geod WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Geometria drogi: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 34);
$query = "SELECT opis FROM skrz WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Rodzaj skrzyżowania: '.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 24);
$query = "SELECT opis FROM zabu WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>'.mysql_result($result_1, 0, 0).'</p>';

$kod = mysql_result($result, 0, 25);
$query = "SELECT opis FROM chmz WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Miejsce zdarzenia: '.mysql_result($result_1, 0, 0).'</p>';

echo '<h3>Inne przyczyny zdarzenia</h3>';

$kod = mysql_result($result, 0, 37);
$query = "SELECT opis FROM spip WHERE kod = '$kod'";
$result_1 = mysql_query($query);


echo '<p>Inne przyczyny: <b>'.mysql_result($result_1, 0, 0).'</b></p>';


echo '</div>';
echo '<iframe style="width:425px; height:350px; border:0; float: right; margin: 5px;" scrolling="no" src="http://maps.google.pl/maps?f=q&amp;source=s_q&amp;hl=pl&amp;geocode=&amp;q='.mysql_result($result, 0, 8).','.mysql_result($result, 0, 9).' '.mysql_result($result, 0, 10).'&amp;ie=UTF8&amp;output=embed"></iframe>';

//disp_table($result);

echo '<div style = "width: 600px; margin: 3px; clear: both;">

<h3>Pojazdy i uczestnicy</h3>';

$query = "SELECT * FROM pojazdy WHERE zszd_id=$zdarzenie";
$pojazdy = mysql_query($query);
//disp_table($pojazdy);

$l_poj = mysql_num_rows($pojazdy);
//echo $l_poj;

for ($i = 0; $i < $l_poj; $i++) {
	$id_poj = mysql_result($pojazdy, $i, 0);
	//echo $id_poj;
	$query = "SELECT * FROM uczestnicy WHERE zspo_id = $id_poj";
	$uczestnicy = mysql_query($query);
	echo '<p><b>Pojazd nr '.mysql_result($pojazdy, $i, 2).'</b></p>';
	
	$kod = mysql_result($pojazdy, $i, 3);
	$query = "SELECT opis FROM skar WHERE kod = '$kod'";
	$result_1 = mysql_query($query);
	echo '<p>Rodzaj pojazdu: '.mysql_result($result_1, 0, 0).'</p>';
	$rodzaj_poj = mysql_result($result_1, 0, 0);
	if ($rodzaj_poj != 'Rower') {
		echo '<p>Marka: '.mysql_result($pojazdy, $i, 4).'</p>';
	
		$kod = mysql_result($pojazdy, $i, 5);
		$query = "SELECT opis FROM spsu WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		echo '<p>Ubezpieczyciel: '.mysql_result($result_1, 0, 0).'</p>';
		
	}
	if (mysql_result($pojazdy, $i, 6) != NULL) {
		$kod = mysql_result($pojazdy, $i, 6);
		$query = "SELECT opis FROM spsp WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		echo '<p>Braki w pojeździe: '.mysql_result($result_1, 0, 0).'</p>';
	}
	
	echo '<ul>';
	$l_ucz = mysql_num_rows($uczestnicy);
	for ($j = 0; $j < $l_ucz; $j++) {
		$kod = mysql_result($uczestnicy, $j, 3);
		$query = "SELECT opis FROM ssru WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		echo '<li><p>'.mysql_result($result_1, 0, 0).'</p>';
		echo '<p>Data urodzenia: '.mysql_result($uczestnicy, $j, 4).'</p>';
		echo '<p>Płeć: '.mysql_result($uczestnicy, $j, 6).'</p>';
		
		if (mysql_result($uczestnicy, $j, 7)!=NULL) {
		$kod = mysql_result($uczestnicy, $j, 7);
		$query = "SELECT opis FROM susu WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		echo '<p>Prawo jazdy: '.mysql_result($result_1, 0, 0).'</p>';
		}
		
		echo '<p>Liczba lat kierowania: '.mysql_result($uczestnicy, $j, 8).'</p>';
		if (mysql_result($uczestnicy, $j, 9) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 9);
			$query = "SELECT opis FROM spsz WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Winny w czasie kolizji: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 10) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 10);
			$query = "SELECT opis FROM sruz WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Postępowanie wobec uczestnika: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 11) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 11);
			$query = "SELECT opis FROM susw WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Pod wpływem: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 12) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 12);
			$query = "SELECT opis FROM stuc WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Obrażenia: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		echo '</li>';
	}
	echo '</ul>';
	//disp_table($uczestnicy);
	
}

$query = "SELECT * FROM uczestnicy WHERE zszd_id=$zdarzenie AND SSRU_KOD = 'I'";
$uczestnicy = mysql_query($query);
//disp_table($uczestnicy);

$l_pieszych = mysql_num_rows($uczestnicy);

if ($l_pieszych > 0) {
	echo '<p><b>Piesi</b></p>
	<ul>';
	for ($j = 0; $j < $l_pieszych; $j++) {
		$kod = mysql_result($uczestnicy, $j, 3);
		$query = "SELECT opis FROM ssru WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		$k = $j + 1;
		echo '<li><p>'.mysql_result($result_1, 0, 0).' '.$k.'</p>';
		echo '<p>Data urodzenia: '.mysql_result($uczestnicy, $j, 4).'</p>';
		echo '<p>Płeć: '.mysql_result($uczestnicy, $j, 6).'</p>';
		
		if (mysql_result($uczestnicy, $j, 7)!=NULL) {
		$kod = mysql_result($uczestnicy, $j, 7);
		$query = "SELECT opis FROM susu WHERE kod = '$kod'";
		$result_1 = mysql_query($query);
		echo '<p>Prawo jazdy: '.mysql_result($result_1, 0, 0).'</p>';
		}
		if (mysql_result($uczestnicy, $j, 17) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 17);
			$query = "SELECT opis FROM sppi WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Winny w czasie kolizji: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 10) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 10);
			$query = "SELECT opis FROM sruz WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Postępowanie wobec uczestnika: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 11) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 11);
			$query = "SELECT opis FROM susw WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Pod wpływem: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		if (mysql_result($uczestnicy, $j, 12) != NULL) {
			$kod = mysql_result($uczestnicy, $j, 12);
			$query = "SELECT opis FROM stuc WHERE kod = '$kod'";
			$result_1 = mysql_query($query);
			echo '<p>Obrażenia: <b>'.mysql_result($result_1, 0, 0).'</b></p>';
		}
		echo '</li>';
	}
	echo '</ul>';
}
echo '</div>';
?>
