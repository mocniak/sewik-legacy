<?
function disp_table($result){
	global $cache_echo;
	$cache_echo .= '<table><tr>';
	$i = 0;
	while ($i < mysql_num_fields($result)) {
		$meta = mysql_fetch_field($result, $i);
		$cache_echo .= '<th>'.$meta->name.'</th>';
		$i++;
	}
	$cache_echo .= '</tr>';
	for ($i = 0; $i< mysql_num_rows($result); $i++) {
		$cache_echo .= '<tr>';
		for ($j = 0; $j < mysql_num_fields($result); $j++) {
			$cache_echo .= '<td>'.mysql_result($result, $i, $j).'</td>';
		}
		$cache_echo .= '</tr>';
	}
	$cache_echo .= '</table>';
}
function disp_zdarzenia($result){
	global $cache_echo;
	global $baza;
	$cache_echo .= '<table><tr>';
	$i = 0;
	while ($i < mysql_num_fields($result)) {
		$meta = mysql_fetch_field($result, $i);
		$cache_echo .= '<th>'.$meta->name.'</th>';
		$i++;
	}
	$cache_echo .= '</tr>';
	for ($i = 0; $i< mysql_num_rows($result); $i++) {
		$cache_echo .= '<tr>';
		for ($j = 0; $j < mysql_num_fields($result); $j++) {
			$cache_echo .= '<td>';
			if ($j==0) $cache_echo .= '<a href="index.php?zdarzenie='.mysql_result($result, $i, $j).'&amp;baza='.$baza.'">'.mysql_result($result, $i, $j).'</td>';

			else $cache_echo .= mysql_result($result, $i, $j).'</td>';
		}
		$cache_echo .= '</tr>';
	}
	$cache_echo .= '</table>';
}
function query($typ) {
	$result = mysql_query("SELECT $typ, COUNT($typ) ilosc FROM zdarzenie_temp GROUP BY $typ ORDER BY ilosc DESC LIMIT 25");
	disp_table($result);
}
function disp_row($nazwa, $result) {
	global $cache_echo;
	$cache_echo .= '<tr><td>'.$nazwa.'</td>';
	for ($j = 0; $j < mysql_num_fields($result); $j++) {
		$cache_echo .= '<td>'.mysql_result($result, 0, $j).'</td>';
	}
	$cache_echo .= '</tr>
	';
}
function disp_php_row($nazwa, $table, $column, $key, $miasto) {
	global $cache_echo;
	$cache_echo .= '<tr><td>'.$nazwa.'</td>';
	for ($i=2007; $i <= 2016; $i++) {
		$pre = $i - 1;
		$aft = $i + 1;
		$query="SELECT count( zdarzenie.id ) AS r$i FROM zdarzenie";
		if ($table != 'zdarzenie') $query=$query." INNER JOIN $table ON $table.ZSZD_ID = zdarzenie.id ";
		$query=$query." WHERE zdarzenie.miejscowosc = '$miasto' ";
		if (($column != NULL) AND ($key !=NULL)) $query = $query."AND $column = '$key'";
		$query=$query." AND zdarzenie.data_zdarz
		BETWEEN '$pre-12-31'
		AND '$aft-01-01'";
		//$cache_echo .= $query;
		$result = mysql_query($query) or die(mysql_error());
		$cache_echo .= '<td>'.mysql_result($result, 0, 0).'</td>';
	}
	$query = "SELECT count( zdarzenie.id ) AS suma
	FROM zdarzenie ";
	if ($table != 'zdarzenie') $query=$query." INNER JOIN $table ON $table.ZSZD_ID = zdarzenie.id";
	$query=$query."
	WHERE zdarzenie.miejscowosc = '$miasto'	";
	if (($column != NULL) AND ($key !=NULL)) $query = $query."AND $column = '$key'";
	//$cache_echo .= $query;
	$result = mysql_query($query) or die(mysql_error());
	$cache_echo .= '<td>'.mysql_result($result, 0, 0).'</td>';
	$cache_echo .= '</tr>
	';
}
function disp_php_row1($nazwa, $table, $column, $key) {
	global $cache_echo;
	$cache_echo .= '<tr><td>'.$nazwa.'</td>';
	for ($i=2007; $i <= 2016; $i++) {
		$pre = $i - 1;
		$aft = $i + 1;
		$query="SELECT count( zdarzenie_temp.id ) AS r$i FROM zdarzenie_temp";
		if ($table != 'zdarzenie_temp') $query=$query." INNER JOIN $table ON $table.ZSZD_ID = zdarzenie_temp.id ";
		if (($column != NULL) AND ($key !=NULL)) $query = $query."AND $column = '$key'";
		$query=$query." WHERE zdarzenie_temp.data_zdarz
		BETWEEN '$i-01-01'
		AND '$i-12-31'";
		//$cache_echo .= '<br>'.$query;
		$result = mysql_query($query) or die(mysql_error());
		$cache_echo .= '<td>'.mysql_result($result, 0, 0).'</td>';
	}
	$query = "SELECT count( zdarzenie_temp.id ) AS suma
	FROM zdarzenie_temp ";
	if ($table != 'zdarzenie_temp') $query=$query." INNER JOIN $table ON $table.ZSZD_ID = zdarzenie_temp.id";
	if (($column != NULL) AND ($key !=NULL)) $query = $query." AND $column = '$key'";
	//$cache_echo .= '<br>'.$query;
	$result = mysql_query($query) or die(mysql_error());
	$cache_echo .= '<td>'.mysql_result($result, 0, 0).'</td>';
	$cache_echo .= '</tr>
	';
}
function select_cases($table, $column, $key, $miasto) {
	$query = "SELECT miejscowosc, count(*) from zdarzenie ";

	if ($table != 'zdarzenie') $query=$query." RIGHT JOIN $table ON $table.ZSZD_ID=zdarzenie.id ";
	$query=$query." WHERE zdarzenie.miejscowosc='$miasto' ";
	if (($column != NULL) AND ($key !=NULL)) $query = $query." AND $column='$key'  ";
	$query=$query." GROUP BY miejscowosc";
	$cache_echo .= $query;
	$result = mysql_query($query) or die(mysql_error());
	return $result;
}
function search_option ($table) {
	$query = "SELECT kod, opis FROM $table";
	$result = mysql_query($query);
	$l_opcji = mysql_num_rows($result);
	if ($l_opcji > 0) echo '<select name="'.$table.'"><option value=""></option>';
	for ($i=0;$i<$l_opcji;$i++) {
		echo '<option value="'.mysql_result($result, $i, 0).'">'.mysql_result($result, $i, 1).'</option>
		';
	}
	if ($l_opcji > 0) echo '</select>
	';
}
function opt_in_tab($opis, $table) {
	echo '<tr><td>'.$opis.'</td><td>';
	search_option ($table);
	echo '</tr>';
}
?>