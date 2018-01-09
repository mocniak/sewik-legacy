<? ob_start();

setcookie("sewik_user", NULL, time()-3600);
setcookie("sewik_pass", NULL, time()-3600);
?>
<html>
<head>
<title>Wylogowywanie</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Refresh" content="0; URL=index.php">
</head>
<body>
<strong>trwa wylogowywanie... </strong> 
</body>
</html>
<?php ob_end_flush(); ?>