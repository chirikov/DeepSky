<?
include '../inc/config.php';
include 'head.php';


?>

<?
if (emptY($id))
	echo "�� ������ ������������";
else 
{
	$sql = mysql_query ("select pravo, name from ".$t1." where id = '$id'");
	if (mysql_num_rows($sql)<1)die('��� ������������ � ��������������� $id');
	$row = mysql_fetch_array($sql);
	if ($row[pravo]=='admin')die('������ ������� ��������������');
	if ($shure==1)
	{
		$sql = mysql_query ("delete from ".$t1." where id = '$id' limit 1");
		if ($sql)
			echo "������������ $row[name] ������!";
		else 	
			echo "������ ��:<BR>".mysql_error();
	}
	else 
		echo "
		�� ������������� ������ ������� ������������ $row[name]?<BR>
		<a href=deluser.php?shure=1&id=$id>��</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=users.php>���</a>
		";
}
?>
</body>
</html>