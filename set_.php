<?php
ob_start();
$user = $_POST['user'];
$pass =  md5($_POST['pass']);

setcookie("sewik_user", $user);
setcookie("sewik_pass", $pass);


?>

<html>
<head>
<title>Logowanie</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<?
echo'<META HTTP-EQUIV="Refresh" CONTENT="0; URL=index.php">'
?>

</head>
<body>
<strong>trwa logowanie... </strong> 
</body>
</html>
<?php ob_end_flush(); ?>