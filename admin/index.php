<?
include '../inc/config.php';
include 'head.php';
$sql = mysql_query ("select id from ".$t1." where 1");
$num = mysql_num_rows($sql);
?>

<a href=prava.php>����������/�������� ������� ���� �������</a>  <br>
<a href=newskat.php>���������� �������� ��������</a><br>
<A href=artkat.php>���������/������������ ������</a><BR>
<a href=galkat.php>���������� �������� ������������</a><BR>
<a href=tips.php>�������� ������� �������� ����������</a><BR>
<a href=users.php>����������� ������ ���������� (����� <?=$num;?>)</a>
</body>
</html>