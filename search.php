<?
$query = "SELECT * FROM (SELECT id FROM zdarzenie WHERE id = 83932261) as zdarzenie 

INNER JOIN

(SELECT id, zszd_id FROM pojazdy) as pojazdy 
ON zdarzenie.id=pojazdy.zszd_id";

$result = mysql_query($query);
disp_table($result);

$query = "SELECT * FROM (SELECT id FROM zdarzenie WHERE id = 83932261) as zdarzenie 

INNER JOIN

(SELECT id, zszd_id FROM uczestnicy) as uczestnicy
ON zdarzenie.id=uczestnicy.zszd_id";
$result = mysql_query($query);
disp_table($result);
?>
