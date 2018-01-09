<?php


echo '<h2>Wyszukiwanie zaawansowane</h2>
<h3>Miejsce zdarzenia</h3>';
$id_zdarz = $_REQUEST["zdarzenie"];
$woj = $_REQUEST["woj"];
$pow = $_REQUEST["pow"];
$miejscowosc = $_REQUEST["miejscowosc"];
if ($miejscowosc ==NULL) $miejscowosc = $miasto;
$ulica = $_REQUEST["ulica"];
$data_od = $_REQUEST["data_od"];
$data_do = $_REQUEST["data_do"];

//echo '<form action="szukaj_plain.php" method="post">';
echo '<form action="index.php?id=szukaj" method="post">';

echo '<table class="szukaj_zaaw">
			<tr><td>baza danych</td><td><select name="baza">
				<option value="sewik_2014">sewik_2014</option>
				<option value="sewik_rowery">rowery</option>
				<option value="sewik_bialystok">bialystok</option>
				<option value="sewik_brzeg">brzeg</option>
				<option value="sewik_katowice">katowice</option>
				<option value="sewik_krakow">krakow</option>
				<option value="sewik_lodz">lodz</option>
				<option value="sewik_lublin">lublin</option>
				<option value="sewik_olsztyn">olsztyn</option>
				<option value="sewik_poznan">poznan</option>
				<option value="sewik_radom">radom</option>
				<option value="sewik_rzeszow">rzeszow</option>
				<option value="sewik_szczecin">szczecin</option>
				<option value="sewik_torun">torun</option>
				<option value="sewik_trojmiasto">trojmiasto</option>
				<option value="sewik_warszawa">warszawa</option>
				<option value="sewik_wroclaw">wroclaw</option>
			</select>
			</td></tr>
<tr><td>ID zdarzenia:</td><td><input type="text" name="zdarzenie"></td></tr>';
opt_in_tab ('województwo:','woj');
echo '<tr><td>miejscowość:</td><td><input type="text" name="miejscowosc"></td></tr>
<tr><td>powiat:</td><td><input type="text" name="pow"></td></tr>';

echo '<tr><td>ulica (bez ul.):<sup><a href="?id=pomoc#wyszukiwanie_ulice" title="pomoc">[?]</a></sup></td><td><input type="text" name="ulica"></td></tr>
<tr><td>skzyżowanie:<sup><a href="?id=pomoc#wyszukiwanie_skrzyżowania" title="pomoc">[?]</a></sup></td><td><input type="text" name="skrzy1"></td></tr>
<tr><td></td><td><input type="text" name="skrzy2"></td></tr>
<tr><td></td><td><input type="text" name="skrzy3"></td></tr>
<tr><td></td><td><input type="text" name="skrzy4"></td></tr>
<tr><td></td><td><input type="text" name="skrzy5"></td></tr>
<tr><td></td><td><input type="text" name="skrzy6"></td></tr>
<tr><td>od (yyyy-mm-dd):</td><td><input type="text" name="data_od"></td></tr>
<tr><td>do (yyyy-mm-dd):</td><td><input type="text" name="data_do"></td></tr>
<tr><td>dopuszczalna prędkość:</td><td><input type="text" name="predkosc_dopuszczalna"></td></tr>';
opt_in_tab ('oświetlenie:','szos');
opt_in_tab ('warunki atmosferyczne:','sswa');
opt_in_tab ('rodzaj zdarzenia:','szrd');
opt_in_tab ('stan nawierzchni:','stna');
opt_in_tab ('rodzaj drogi:','rodr');
opt_in_tab ('sygnalizacja świetlna:','sysw');
opt_in_tab ('oznkaowanie poziome:','ozpo');
opt_in_tab ('geometria drogi:','geod');
opt_in_tab ('rodzaj skrzyżowania:','skrz');
opt_in_tab ('obszar zabudowany:','zabu');
opt_in_tab ('miejsce zdarzenia:','chmz');
echo '</table>
<h3>Uczestnicy</h3> 
<table class="szukaj_zaaw">
<tr><td>typ selekcji uczestników<sup><a href="?id=pomoc#wyszukiwanie_selekcja_użytkowników" title="pomoc">[?]</a></sup></td><td><input style="width:20px" type="radio" name="sel_typ" id="wszyscy" value="wszyscy" checked="checked"> <label for="wszyscy">bez selekcji uczestników</label></td></tr>
<tr><td></td><td><input style="width:20px" type="radio" name="sel_typ" id="tylko" value="tylko"> <label for="tylko">zdarzenia w których brały udział wszystkie wybrane grupy uczestników i tylko te</label></td></tr>
<tr><td></td><td><input style="width:20px" type="radio" name="sel_typ" id="rowniez" value="rowniez"> <label for="rowniez">zdarzenia w których brały udział wszystkie wybrane grupy uczestników, ale nie tylko te</label></td></tr>
<tr><td></td><td><input style="width:20px" type="radio" name="sel_typ" id="wiele" value="wiele"> <label for="wiele">zdarzenia w których brała udział przynajmniej jedna z wybranych grup uczestników</label></td></tr>
<tr><td></td><td><input style="width:20px" type="radio" name="sel_typ" id="sprawcy" value="sprawcy"> <label for="sprawcy">zdarzenia w których sprawcy należeli do wybranej (jednej) grupy uczestników [beta]</label></td></tr>
<tr><td>grupa uczestników</td><td><input style="width:20px" type="checkbox" name="usr_grp[]" value="Pieszy" id="Pieszy"> <label for="Pieszy">Pieszy</label></td></tr>
';

$query = "SELECT * FROM skar";
$result = mysql_query($query);
$a = mysql_num_rows($result);
for ($i=0; $i < $a; $i++) {
	echo '<tr><td></td><td><input style="width:20px" type="checkbox" name="usr_grp[]" id="'.mysql_result($result, $i, 0).'" value="'.mysql_result($result, $i, 0).'"> <label for="'.mysql_result($result, $i, 0).'">'.mysql_result($result, $i, 1).'</label></td></tr>'."\n";
}

opt_in_tab ('obrażenia:','stuc');
opt_in_tab ('przyczyny:','spsz');
opt_in_tab ('środki psychoaktywne:','susw');

/*echo '</table>';

echo '<h3>Pojazdy</h3> 
<table class="szukaj_zaaw">';
opt_in_tab ('rodzaj pojazdu:','skar');*/
echo '<tr><td></td><td><input type="submit"></td></tr>
</table>
</form>
';
?>
