<?
include '../inc/config.php';
include 'head.php';
if (!isset($submit) or empty($kat) or empty($about))
{
     echo "
     <form action=artkat.php method=post>�������� ��������� <input type=text name=kat><br>
     ��� ����� ������� � ��������� <select name=pid><option value=1>������<br>";
     $sql = mysql_query ("select id, kat from ".$t4." where 1 order by kat");
     while ($row = mysql_fetch_Array($sql))
     {
             echo "<option value=$row[id]>$row[kat]";
     }
     echo"</select><br>
     ��������: <textarea name=about></textarea>
     <input type=submit name=submit value=��������></form>
     ";
}
else
{
    $sql = mysql_query ("insert into ".$t4." (pid, kat, about) values ('$pid', '$kat', '$about');");
    if ($sql)
        echo "OK";
    else
        echo mysql_error();
}