<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Adding</title>
</head>

<body>

<?php
$mysql = @mysql_connect('localhost', 'root', 'password') or $error = "Не удалось подключиться к серверу MySQL.";
@mysql_select_db('deepsky') or $error = "Не удалось выбрать базу данных.";

$qw = mysql_query("insert into constell values('".$name."', '".$rusname."', '".substr($skeleton,1)."')");

if($qw) print "ok";
else mysql_error();

mysql_close($mysql);
?>

</body>
</html>
