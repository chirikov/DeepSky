<?
include '../inc/config.php';
include 'head.php';
if (!isset($submit) or empty($kat))
{
     echo "
     <form action=galkat.php method=post>Добавить раздел <input type=text name=kat><br>    
     <input type=submit name=submit value=Добавить></form>";
     
}
else
{
    $sql = mysql_query ("insert into ".$t15." (kat) values ('$kat');");
    if ($sql)
        echo "OK";
    else
        echo mysql_error();
}