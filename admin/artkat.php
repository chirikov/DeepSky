<?
include '../inc/config.php';
include 'head.php';
if (!isset($submit) or empty($kat) or empty($about))
{
     echo "
     <form action=artkat.php method=post>Добавить категорию <input type=text name=kat><br>
     Она будет вложена в категорию <select name=pid><option value=1>Статьи<br>";
     $sql = mysqli_query ($db, "select id, kat from ".$t4." where 1 order by kat");
     while ($row = mysqli_fetch_array($sql))
     {
             echo "<option value=$row[id]>$row[kat]";
     }
     echo"</select><br>
     Описание: <textarea name=about></textarea>
     <input type=submit name=submit value=Добавить></form>
     ";
}
else
{
    $sql = mysqli_query ($db, "insert into ".$t4." (pid, kat, about) values ('$pid', '$kat', '$about');");
    if ($sql)
        echo "OK";
    else
        echo mysql_error();
}