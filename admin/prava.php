<?
include '../inc/config.php';
include 'head.php';
function prava_form()
{
        echo "
        <form action=prava.php method=post>
        <input type=hidden name=show value=add>
        Название уровня прав доступа <input type=text name=pravo><br>
        <input type=checkbox name=newsadd>Добавление новостей<br>
        <input type=checkbox name=newsedit>Модерирование новостей<br>
        <input type=checkbox name=articlesadd>Добавление статей<br>
        <input type=checkbox name=articlesedit>Модерирование статей<br>
        <input type=checkbox name=filesadd>Добавление файлов<br>
        <input type=checkbox name=filesedit>Модерирование файлов<br>
        <input type=checkbox name=voteadd>Добавление голосования<br>
        <input type=checkbox name=voteedit>Модерирование голосований<br>
        <input type=checkbox name=photoadd>Добавление фотографий<BR>
        <input type=submit name=prava_submit value=Добавить>
        </form>
        ";
}
?>

<?
switch ($show)
{
        default:
        echo "
        <a href=prava.php?show=add>Добавить уровень прав доступа</a><br>
        <a href=prava.php?show=list>Список уровней прав доступа</a><br>
        <a href=prava.php?show=useradd>Назначить пользователю уровень прав доступа</a><bR>
        ";
        break;
        case 'add':
        if (!isset($prava_submit))
             prava_form();
        else
        {
                if (empty($pravo))
                    prava_form();
                else
                {
                        $sql = mysqli_query ($db, "select pravo from ".$t2." where pravo = '$pravo'");
                        if (mysql_num_rows($sql)>0)
                            echo "Уровень доступа $pravo уже существует. Надо другое название.<BR>";
                        else
                        {
                                $sql = mysqli_query ($db, "insert into ".$t2." values ('$pravo', '$newsadd', '$newsedit', '$articlesadd', '$articlesedit', '$filesadd', '$filesedit', '$voteadd', '$voteedit', '$photoadd');");
                                if ($sql)
                                    echo "OK";
                                else
                                    echo mysql_error();
                        }
                }
        }
        break;
        case 'list':
        $sql =mysqli_query ($db, "select * from ".$t2." where 1");
        echo "<table>
        <tr><td>Name</td><td>NEWS ADD</td><td>NEWS EDIT</td><td>Articles Add</td><td>Articles Edit</td>
        <td>Files Add</td><td>Files Edit</td><td>Votes Add</td><td>Votes Edit</td><TD>Photos Add</td>
        <td>Редактирование</td><td>Удаление</td><td>Назначить</td></tr>";
        while ($row = mysqli_fetch_array($sql))
        {
               if ($row[newsadd]=='on')$newsadd='+';
                   else $newsadd='-';
               if ($row[newsedit]=='on')$newsedit='+';
                   else $newsedit='-';
               if ($row[articlesadd]=='on')$articlesadd='+';
                   else $articlesadd='-';
               if ($row[articlesedit]=='on')$articlesedit='+';
                   else $articlesedit='-';
               if ($row[filesadd]=='on')$filesadd='+';
                   else $filesadd='-';
               if ($row[filesedit]=='on')$filesedit='+';
                   else $filesedit='-';
               if ($row[votesadd]=='on')$votesadd='+';
                   else $votesadd='-';
               if ($row[votesedit]=='on')$votesedit='+';
                   else $votesedit='-';
               if ($row[photoadd]=='on')$photoadd='+';
                   else $photoadd='-';               

               echo "
               <tr><td>$row[pravo]</td><td>$newsadd</td><td>$newsedit</td><td>$articlesadd</td><td>$articlesedit</td>
               <td>$filesadd</td><td>$filesedit</td><td>$votesadd</td><td>$votesedit</td><td>$photoadd</td>
               <td><a href=prava.php?show=edit&pravo=$row[pravo]>Редактировать</a></td>
               <td><a href=prava.php?show=del&pravo=$row[pravo]>Удалить</a></td>
               <td><a href=prava.php?show=useradd&pravo=$row[pravo]>Назначить</a></td></tr>
               ";
        }
        echo "</table>";
        break;
        case 'useradd':
        if (empty($username))
        {
                if (empty($pravo))
                        $sql = mysqli_query ($db, "select pravo from ".$t2." where 1");
                $user = rawurldecode($user);
                echo"
                <form action=prava.php method=post>
                <input type=hidden name=show value=useradd>
                Назначить юзеру <input type=text name=username value=$user> уровень прав доступа
                ";
                if (!empty($pravo))echo "<input type=hidden name=pravo value='$pravo'> $pravo";
                else
                {
                        echo "<select name=pravo>";
                        while ($row = mysqli_fetch_array($sql))
                        {
                                echo "<option value=$row[pravo]>$row[pravo]";
                        }
                        echo "</select>";

                }
                echo"<input type=submit name=ad_us value=Назначить>
                </form>
                ";
        }
        else
        {
                $sql = mysqli_query ($db, "select id from ".$t1." where name = '$username'");
                if (mysql_num_rows($sql)<1)
                    echo "Юзера $username не существует";
                else
                {
                        $sql = mysqli_query ($db, "update ".$t1." set pravo = '$pravo' where name = '$username'");
                        if ($sql)
                            echo "Право назначено";
                        else
                            echo mysql_error();
                }
        }
        break;
        case 'del':
        if ($del!='yes')
        {
                echo"Удалить право $pravo?<br>
                <a href=prava.php?show=del&pravo=$pravo&del=yes>Да
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=prava.php>Нет</a>";
        }
        else
        {
                $sql = mysqli_query ($db, "update ".$t1." set pravo = 'user' where pravo = '$pravo'");
                $sql2 = mysqli_query ($db, "delete from ".$t2." where pravo = '$pravo'");
                if (!$sql or !$sql2)
                     echo mysql_error();
                else
                    echo "Право удалено.";
        }
        break;
        case 'edit':
        if (!empty($pravo))
        {
              if (!isset($upd_sub))
              {
               $sql = mysqli_query ($db, "select * from ".$t2." where pravo = '$pravo'");
               $row=  mysqli_fetch_array($sql);
                echo "
                <form action=prava.php method=post>
                <input type=hidden name=pravo value=$pravo>
                <input type=hidden name=show value=edit>
                Название уровня прав доступа $pravo<br>
                <input type=checkbox name=newsadd ";if ($row[newsadd]=='on')echo "checked"; echo">Добавление новостей<br>
                <input type=checkbox name=newsedit ";if ($row[newsedit]=='on')echo "checked"; echo">Модерирование новостей<br>
                <input type=checkbox name=articlesadd ";if ($row[articlesadd]=='on')echo "checked"; echo">Добавление статей<br>
                <input type=checkbox name=articlesedit ";if ($row[articlesedit]=='on')echo "checked"; echo">Модерирование статей<br>
                <input type=checkbox name=filesadd ";if ($row[filesadd]=='on')echo "checked"; echo">Добавление файлов<br>
                <input type=checkbox name=filesedit ";if ($row[filesedit]=='on')echo "checked"; echo">Модерирование файлов<br>
                <input type=checkbox name=voteadd ";if ($row[votesadd]=='on')echo "checked"; echo">Добавление голосования<br>
                <input type=checkbox name=voteedit ";if ($row[votesedit]=='on')echo "checked"; echo">Модерирование голосований<br>
                <input type=checkbox name=photoadd ";if ($row[photoadd]=='on')echo "checked"; echo">Добавление фотографий<br>
                <input type=submit name=upd_sub value=Изменить>
                </form>
                ";
              }
              else
              {
                      $sql = mysqli_query ($db, "update ".$t2." set newsadd = '$newsadd', newsedit = '$newsedit', articlesadd = '$articlesadd', articlesedit = '$articlesedit', filesadd = '$filesadd', filesedit = '$filesedit', votesadd = '$voteadd', votesedit = '$voteedit', photoadd='$photoadd' where pravo = '$pravo'");
                      if ($sql) echo "OK";
                      else echo mysql_error();
              }
        }
        break;
}
?>
</body>
</html>