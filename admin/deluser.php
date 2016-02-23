<?
include '../inc/config.php';
include 'head.php';


?>

<?
if (emptY($id))
	echo "Не выбран пользователь";
else 
{
	$sql = mysql_query ("select pravo, name from ".$t1." where id = '$id'");
	if (mysql_num_rows($sql)<1)die('Нет пользователя с идентификатором $id');
	$row = mysql_fetch_array($sql);
	if ($row[pravo]=='admin')die('Нельзя удалить администратора');
	if ($shure==1)
	{
		$sql = mysql_query ("delete from ".$t1." where id = '$id' limit 1");
		if ($sql)
			echo "Пользователь $row[name] удален!";
		else 	
			echo "Ошибка БД:<BR>".mysql_error();
	}
	else 
		echo "
		Вы действительно хотите удалить пользователя $row[name]?<BR>
		<a href=deluser.php?shure=1&id=$id>Да</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=users.php>Нет</a>
		";
}
?>
</body>
</html>