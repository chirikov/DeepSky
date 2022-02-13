<?
include '../inc/config.php';
include 'head.php';


?>

<table>
<tr><td>Имя</tD><td>E-mail</td><td>HomePage</td><td>ICQ</tD><td>Право</td><td>Удаление</td><td>Изменение права</td></tr>
<?
$sql = mysqli_query ($db, "select id, name, email, url, icq, pravo from ".$t1." where 1 order by id desc");
while ($row = mysqli_fetch_array($sql))
{
	echo "<tr><td>$row[name]</td><Td>$row[email]</td><td>$row[url]</td><td>$row[icq]</tD>
	<td><a href=prava.php?show=edit&pravo=$row[pravo]>$row[pravo]</a></td><td><a href=deluser.php?id=$row[id]>Удалить</a></tD><td><a href=\"prava.php?show=useradd&user=".rawurlencode($row[name])."\">Назначить ур. прав доступа</a></tr>";
}
?>
</table>
</body>
</html>