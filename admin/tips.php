<?
include '../inc/config.php';
include 'head.php';
if (!isset($submit) or empty($tips))
{
     echo "
     <form action=tips.php method=post>Добавить Типс <textarea name=tips></textarea><br>    
     <input type=submit name=submit value=Добавить></form>";
     
}
else
{
    $sql = mysql_query ("insert into ".$t14." (tips) values ('$tips');");
    if ($sql)
        echo "OK";
    else
        echo mysql_error();
}