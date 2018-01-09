<?php
echo '<h2>logowanie</h2>
<form action="set_.php" method="post">
<table>
<tr><td>login</td><td><input class="input_text" name="user" type="text" id="user"></td></tr>
<tr><td>hasło</td><td><input class="input_text" name="pass" type="password" id="pass"></td></tr>';

if (isset($_COOKIE["sewik_user"])) echo '<tr><td  class="error" colspan="2">błędny login lub hasło</td></tr>';

echo '</table>
	<input type="submit" value="loguj" style="width:50px; height:24px;" >
</form>';

?>