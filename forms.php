<?


function news_add_form($title='', $news='')
{
        echo "
        <form action=news_add.php method=post>
        <table border=o align=center>
        <tr><td>Загаловок</td><td><input type=text name=title value='$title'></td></tr>
        <tr><td>Новость</td><td><textarea name=news>$news</textarea></td></tr>
        <tr><td colspan=2 align=center><input type=submit name=news_add_submit value=Добавить></td></tr>
        </table>
        </form>
        ";
}
function articles_add_f($title='', $about='', $article='')
{
        global $t4;
        $sql = mysqli_query ($db, "select id, kat from ".$t4." where 1 order by kat");
        echo "
        <form action=articles_add.php method=post>
        <table border=0 align=center>
        <tr><td>Название</td><td><input type=text name=title  value='$title'></td></tr>
        <tr><td>Краткое описание</td><td><textarea name=about>$about</textarea></td></tr>
        <tr><td>Статья</td><td><textarea name=article>$article</textarea></td></tr>
        <tr><td>Категория</td><td><select name=kat>";
        while ($row = mysqli_fetch_array($sql))
        {
                echo "<option value=$row[id]>$row[kat]";
        }
        echo "
        <tr><td colspan=2 align=center><input type=submit name=art_add_sub value=Добавить></td></tr>
        </table>
        </form>
        ";
}
function art_add_nf($title='', $about='', $article='', $author = '')
{
        global $t4;
        $sql = mysqli_query ($db, "select id, kat from ".$t4." where 1 order by kat");
        echo "
        <form action=articles_add.php method=post>
        <table border=0 align=center>
        <tr><td>Название</td><td><input type=text name=title  value='$title'></td></tr>
        <tr><td>Автор</td><td><input type=text name=author value='$author'></tD></tr>
        <tr><td>Краткое описание</td><td><textarea name=about>$about</textarea></td></tr>
        <tr><td>Статья</td><td><textarea name=article>$article</textarea></td></tr>
        <tr><td>Категория</td><td><select name=kat>";
        while ($row = mysqli_fetch_array($sql))
        {
                echo "<option value=$row[id]>$row[kat]";
        }
        echo "
        <tr><td colspan=2 align=center><input type=submit name=art_add_sub value=Добавить></td></tr>
        </table>
        </form>
        ";
}
function files_add_f($title='', $about='')
{
        global $t6;
        $sql = mysqli_query ($db, "select id, kat from ".$t6." where 1 order by kat");
        echo "
        <form action=files_add.php method=post enctype=multipart/form-data>
        <table border=0 align=center>
        <tr><td>Название</td><td><input type=text name=title  value='$title'></td></tr>
        <tr><td>Описание</td><td><textarea name=about>$about</textarea></td></tr>
        <tr><td>Файл</td><td><input type=file name=file>  </td></tr>
        <tr><td>Категория</td><td><select name=kat>";
        while ($row = mysqli_fetch_array($sql))
        {
                echo "<option value=$row[id]>$row[kat]";
        }
        echo "
        <tr><td colspan=2 align=center><input type=submit name=art_add_sub value=Добавить></td></tr>
        </table>
        </form>
        ";
}
function files_add_nf($title='', $about='', $author = '')
{
        global $t6;
        $sql = mysqli_query ($db, "select id, kat from ".$t6." where 1 order by kat");
        echo "
        <form action=files_add.php method=post enctype=multipart/form-data>
        <table border=0 align=center>
        <tr><td>Название</td><td><input type=text name=title  value='$title'></td></tr>
        <tr><td>Автор</td><td><input type=text name=author value='$author'></tD></tr>
        <tr><td>Описание</td><td><textarea name=about>$about</textarea></td></tr>
        <tr><td>Файл</td><td><input type=file name=file>  </td></tr>
        <tr><td>Категория</td><td><select name=kat>";
        while ($row = mysqli_fetch_array($sql))
        {
                echo "<option value=$row[id]>$row[kat]";
        }
        echo "
        <tr><td colspan=2 align=center><input type=submit name=art_add_sub value=Добавить></td></tr>
        </table>
        </form>
        ";
}
?>