<?
include '../inc/config.php';
include 'head.php';
$sql = mysql_query ("select id from ".$t1." where 1");
$num = mysql_num_rows($sql);
?>

<a href=prava.php>Добавление/удаление уровней прав доступа</a>  <br>
<a href=newskat.php>Добавление разделов новостей</a><br>
<A href=artkat.php>Категории/подкатегории статей</a><BR>
<a href=galkat.php>Добавление разделов фотогаллереи</a><BR>
<a href=tips.php>Добавить краткую полезную информацию</a><BR>
<a href=users.php>Просмотреть список участников (всего <?=$num;?>)</a>
</body>
</html>