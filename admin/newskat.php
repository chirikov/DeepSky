<?
include '../inc/config.php';
include 'head.php';
if (!isset($submit) or empty($kat) or empty($about))
     echo "
     <form action=newskat.php method=post>�������� ��������� <input type=text name=kat><br>
     ��������: <textarea name=about></textarea>
     <input type=submit name=submit value=��������></form>
     ";
else
{
    $sql = mysql_query ("insert into ".$t8." (kat, about) values ('$kat', '$about');");
    if ($sql)
        echo "OK";
    else
        echo mysql_error();
}
