<?
include '../inc/config.php';
include 'head.php';


?>

<table>
<tr><td>���</tD><td>E-mail</td><td>HomePage</td><td>ICQ</tD><td>�����</td><td>��������</td><td>��������� �����</td></tr>
<?
$sql = mysql_query ("select id, name, email, url, icq, pravo from ".$t1." where 1 order by id desc");
while ($row = mysql_fetch_array($sql))
{
	echo "<tr><td>$row[name]</td><Td>$row[email]</td><td>$row[url]</td><td>$row[icq]</tD>
	<td><a href=prava.php?show=edit&pravo=$row[pravo]>$row[pravo]</a></td><td><a href=deluser.php?id=$row[id]>�������</a></tD><td><a href=\"prava.php?show=useradd&user=".rawurlencode($row[name])."\">��������� ��. ���� �������</a></tr>";
}
?>
</table>
</body>
</html>