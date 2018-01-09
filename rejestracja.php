<?

function form($nazwa, $name, $type) {
	global $error_counter;
	echo '<tr><td>'.$nazwa.'</td><td><input name="'.$name.'" type="'.$type.'" id="'.$name.'_input"';
	if ($_REQUEST["id"] == 'rejestracja') {
		echo 'value="'.$_REQUEST[$name].'"';
		echo '></td></tr>';
		if (strlen($_REQUEST[$name]) == 0) {
			echo '<tr><td  class="error" colspan="2">pole '.$nazwa.' nie może być puste</td></tr>';
			$error_counter++;
		}
	}
	else echo '></td></tr>';
}
function isValidEmail($email){
	return eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$", $email);
}
$error_counter = 0;

if ($id == 'rejestracja') $reg = 1;
if ($reg) {
	$user = $_REQUEST["user"];
	$pass = $_REQUEST["pass"];
	$pass2 = $_REQUEST["pass2"];
	$imie = $_REQUEST["imie"];
	$nazwisko = $_REQUEST["nazwisko"];
	$organizacja = $_REQUEST["organizacja"];
	$email = $_REQUEST["email"];
}

echo '<h2>rejestracja</h2>';


echo '<form action="?id=rejestracja" method="post">
<table>';
form ('login', 'user', 'text');
if (mysql_num_rows(mysql_query("SELECT user FROM users WHERE user = '".$user."'")) > 0 ) {
	echo '<tr><td class="error" colspan="2">taki login już istnieje</td></tr>';
	$error_counter++;
}
form ('hasło', 'pass', 'password');
form ('powtórz hasło', 'pass2', 'password');
if ($pass != $pass2) {
	echo '<tr><td class="error" colspan="2">hasła nie są jednakowe</td></tr>';
	$error_counter++;
}
form ('imię', 'imie', 'text');
form ('nazwisko', 'nazwisko', 'text');
form ('organizacja', 'organizacja', 'text');
form ('email', 'email', 'text');
if ((!isValidEmail($email)) AND ($reg)) {
	echo '<tr><td class="error" colspan="2">to nie jest prawidłowy email</td></tr>';
	$error_counter++;
}
echo '</table>';

//echo $error_counter;
if (($error_counter != 0) OR (!$reg)){
	echo '<input type="submit" value="załóż konto" style="height:24px;" >';
}
else  {
	$query = "INSERT INTO users VALUES (NULL, '".$user."', '".md5($pass)."', '".$imie."', '".$nazwisko."', '".$organizacja."', '".$email."')";
	//echo $query;
	mysql_query($query) or die();
	echo '<p>Konto zostało założone. W celu modyfikacji danych lub likwidacji konta prosimy o kontakt z administratorem.</p>
	<p><a href="index.php">Wróć do strony logowania</a></p>';
}
echo '</form>';
?>