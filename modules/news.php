<?
function news_add_auth_form ($title='', $news_short='', $news_full='')
{
         //NEWS ADD FORM... YOU CAN CHANGE DESIGN ELEMENTS HERE
         echo "
         <form action=news.php method=post>
         <table class=articles>
         <input type=hidden name=act value=add>
         <tr><td class=cont>Заголовок новости</td><td class=cont><input type=text name=title value=\"$title\"></td></tr>
         <tr><td class=cont>Категория</tD><td class=cont><select name=kid>";
         global $t8;
         $sql = mysqli_query ($db, "select id, kat from ".$t8." where 1 order by kat");
         while ($row= mysqli_fetch_array($sql))
         {
                echo "<option value=$row[id]>$row[kat]";
         }
         echo" </selecT></td></tr>
         <tr><td class=cont>Короткое описание новости</td><tD class=cont><textarea name=news_short rows=3 cols=25>$news_short</textarea></td></tr>
         <tr><td class=cont>Новость</tD><td class=cont><textarea name=news_full rows=7 cols=50>$news_full</textarea><br>
         Допустимые теги: &lt;b>&lt;i>&lt;a>&lt;p>&lt;br>&lt;img>&lt;h1>&lt;h2>&lt;h3>&lt;h4>&lt;h5>&lt;div>&lt;em>&lt;table>&lt;tr>&lt;td><BR>
         Внимание: при использовании тега &lt;img> допустимые разширения файлов - gif, jpg, jpeg, png</td></tr>
         <tr><td colspan=2 align=center class=cont><input type=submit class=btn name=add value=Добавить></td></tr>
         </table>
         </form>
         ";
}
function news_insert ($title, $news_short, $news_full, $author, $status, $kid)
{
        global $t3;
        $sql = mysqli_query ($db, "select id from ".$t3." where title = '$title' and news_short = '$news_short' and news_full = '$news_full'");
        if (mysql_num_rows($sql)>0)
            $res = "Ошибка: данная новость уже присутствует в базе";
        else
        {
                $date = time();
                $sql = mysqli_query ($db, "insert into ".$t3." (news_full, news_short, title, author, status, kid, date) values ('$news_full', '$news_short', '$title', '$author', '$status', '$kid', '$date');");
                if ($sql)
                    $res = "Новость успешно добавлена!<BR><a href=news.php class=without>Вернуться к управлению новостями.</a>";
                else
                    $res = "Произошла ошибка во время добавления новости в базу.<BR>".mysql_error();

        }
        return $res;
}
function news_filter ($text, $minsize, $maxsize)
{
         $text = strip_tags($text,"<b><i><a><p><br><img><h1><h2><h3><h4><h5><div><em><table><tr><td>");
         if (strlen($text)>$maxsize or strlen($text)<$minsize)
             $text="";
         else
         {
                 if (!check_img($text))
                     $text="";
         }
         return $text;
}
function news_edit_show($row)
{
        //NEWS EDIT FORM... YOU CAN CHANGE DESIGN ELEMENTS HERE
        echo "
        <form action=news.php method=post>
        <input type=hidden name=act value=edit>
        <input type=hidden name=id value=$row[id]>
        <table class=articles>
        <tr><td class=cont>Заголовок новости</td><td class=cont><input type=text name=title value=\"$row[title]\"></td></tr>
         <tr><td class=cont>Категория</tD><td class=cont><select name=kid>";
         global $t8;
         $sql = mysqli_query ($db, "select id, kat from ".$t8." where 1 order by kat");
         while ($row2= mysqli_fetch_array($sql))
         {
                echo "<option value=$row2[id] ";
                if ($row[kid]==$row2[id])
                    echo "selected";
                echo" >$row2[kat]";
         }
         echo" </selecT>
         <tr><td class=cont>Короткое описание новости</td><tD class=cont><textareaname=news_short rows=3 cols=25>$row[news_short]</textarea></td></tr>
         <tr><td class=cont>Новость</tD><td class=cont><textarea name=news_full rows=7 cols=50>$row[news_full]</textarea><br>
         Допустимые теги: &lt;b>&lt;i>&lt;a>&lt;p>&lt;br>&lt;img>&lt;h1>&lt;h2>&lt;h3>&lt;h4>&lt;h5>&lt;div>&lt;em>&lt;table>&lt;tr>&lt;td><BR>
         Внимание: при использовании тега &lt;img> допустимые разширения файлов - gif, jpg, jpeg, png</td></tr>
         <tr><td colspan=2 align=center class=cont><input class=btn type=submit name=news_edit_submit value=Изменить></td></tr>
        </table>
        </form>
        ";
}
function news_edit_update ($title, $kid, $news_short, $news_full, $id, $uid)
{
        global $t3, $news_title_length_min, $news_title_length_max, $news_short_length_min, $news_short_length_max, $news_full_length_min, $news_full_length_max;
        $sql2 = mysqli_query ($db, "select id from ".$t3." where id = '$id' and author = '$uid'");
        if (mysql_num_rows($sql2)<1)
            $res = "Вы не имеете права редактировать данную новость.<BR>";
        else
        {
                $title = news_filter($title, $news_title_length_min, $news_title_length_max);
                $news_short = news_filter($news_short, $news_short_length_min, $news_short_length_max);
                $news_full = news_filter($news_full, $news_full_length_min, $news_full_length_max);
                if (empty($title) or empty($news_short) or empty($news_full))
                    $res = "Не правильно введена новость.<BR>";
                else
                {
                        $sql = mysqli_query ($db, "update ".$t3." set title = '$title', news_short = '$news_short', news_full = '$news_full', kid = '$kid' where id = '$id'");
                        if ($sql)
                            $res =  "Новость успешно изменена.<BR>";
                        else
                            $res = mysql_error();
                }
        }
        return $res;

}
function news_old_moder()
{
        global $t3, $t8, $page, $t9;
        if (empty($page))$page=0;
        else $page--;
        $admin_news_show_row = mysqli_fetch_array(mysqli_query($db, "select v from ".$t9." where what = 'news_per_page'"));
        $admin_news_show = $admin_news_show_row[v];
        $sql = mysqli_query ($db, "select * from ".$t3." where status = 'ok' order by date DESC limit ".$admin_news_show*$page.", ".$admin_news_show."");
        while ($row = mysqli_fetch_array($sql))
        {
                $author = get_name($row[author]);
                // NEWS MODERATION FORM... YOU CAN CHANGE DESIGN ELEMENTS HERE
                echo "
                <form action=news.php method=post>
                <input type=hidden name=act value=moderate>
                <input type=hidden name=mtype value=old>
                <input type=hidden name=id value=$row[id]>
                <table class=articles>
                <TR><td class=cont>Автор</td><td class=cont>$author</td></tr>
                <tr><td class=cont>Заголовок новости</td><td class=cont><input type=text name=title value=\"$row[title]\"></td></tr>
                 <tr><td class=cont>Категория</tD><td class=cont><select name=kid>";
                 $sql2= mysqli_query ($db, "select id, kat from ".$t8." where 1 order by kat");
                 while ($row2= mysqli_fetch_array($sql2))
                 {
                        echo "<option value=$row2[id] ";
                        if ($row[kid]==$row2[id])
                            echo "selected";
                        echo" >$row2[kat]";
                 }
                 echo" </selecT>   </td></tr>
                 <tr><td class=cont>Короткое описание новости</td><tD class=cont><textarea rows=3 cols=25 name=news_short>$row[news_short]</textarea></td></tr>
                 <tr><td class=cont>Новость</tD><td class=cont><textarea name=news_full rows=7 cols=50>$row[news_full]</textarea></td></tr>
                 <tr><td align=center class=cont><input class=btn type=submit name=news_old_submit value=Изменить></td><td><a class=without href=\"?act=del&id=$row[id]\">Удалить</a></td></tr>
                </table>
                </form><BR>
                ";
        }
            $sql = mysqli_query ($db, "select id from ".$t3." where status = 'ok'");
            $numpages = ceil (mysql_num_rows($sql)/$admin_news_show);

        if ($numpages>1)
        {
            echo "<center>";
            $i=0; $k=0;
            if ($page>4)
            echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=old&page=1 class=without>1</a>&nbsp;]&nbsp; ... ";
            while ($i<$numpages)
            {
                $i++;
                if ($k<9 and $page-$i<4)
                {
                $k++;
                if ($i==($page+1))
                        echo "&nbsp;".$i."&nbsp;";
                else
                        echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=old&page=".$i." class=without>".$i."</a>&nbsp;]&nbsp;";
                }
                elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
                        echo " ... ";$z=1;}
                elseif ($i==$numpages)
                        echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=old&page=".$i." class=without>".$i."</a>&nbsp;]&nbsp;";

             }
             echo "</center>";
        }
}
function news_moder_update($id, $title, $kid, $news_short, $news_full)
{
             global $t3, $news_title_length_min, $news_title_length_max, $news_short_length_min, $news_short_length_max, $news_full_length_min, $news_full_length_max;
             $title = news_filter($title, $news_title_length_min, $news_title_length_max);
             $news_short = news_filter($news_short, $news_short_length_min, $news_short_length_max);
             $news_full = news_filter($news_full, $news_full_length_min, $news_full_length_max);
             if (empty($title) or empty($news_short) or empty($news_full))
                 $res = "Не правильно введена новость.<BR>";
             else
             {
                     $sql = mysqli_query ($db, "update ".$t3." set title = '$title', news_short = '$news_short', news_full = '$news_full', kid = '$kid', status = 'ok' where id = '$id'");
                     if ($sql)
                         $res = "Операция проведена успешно.<BR>";
                     else
                         $res = mysql_error();
             }
             return $res;
}
function news_new_moder()
{
        global $t3, $t8, $page, $t9;
        if (empty($page))$page=0;
        else $page--;
        $admin_news_show_row = mysqli_fetch_array(mysqli_query($db, "select v from ".$t9." where what = 'news_per_page'"));
        $admin_news_show = $admin_news_show_row[v];
        $sql = mysqli_query ($db, "select * from ".$t3." where status = 'mwait' order by date DESC limit ".$admin_news_show*$page.", ".$admin_news_show."");
        if (mysql_num_rows($sql)<=0)
			echo "Вы некоректно зашли на данную страницу. Если вы пришли на эту страницу по ссылке с сайта, просьба сообщить об этом администраторам сайта <a href=feedback.php class=without>ОТСЮДА</a>.";
		while ($row = mysqli_fetch_array($sql))
        {
                $author = get_name($row[author]);
                // NEWS MODERATION FORM... YOU CAN CHANGE DESIGN ELEMENTS HERE
                echo "
                <form action=news.php method=post>
                <input type=hidden name=act value=moderate>
                <input type=hidden name=mtype value=new>
                <input type=hidden name=id value=$row[id]>
                <table class=articles>
                <TR><td class=cont>Автор</td><td class=cont>$author</td></tr>
                <tr><td class=cont>Заголовок новости</td><td class=cont><input type=text name=title value=\"$row[title]\"></td></tr>
                 <tr><td class=cont>Категория</tD><td class=cont><select name=kid>";
                 $sql2 = mysqli_query ($db, "select id, kat from ".$t8." where 1 order by kat");
                 while ($row2= mysqli_fetch_array($sql2))
                 {
                        echo "<option value=$row2[id] ";
                        if ($row[kid]==$row2[id])
                            echo "selected";
                        echo" >$row2[kat]";
                 }
                 echo" </selecT> </td></tr>
                 <tr><td class=cont>Короткое описание новости</td><tD class=cont><textarea name=news_short rows=3 cols=25>$row[news_short]</textarea></td></tr>
                 <tr><td class=cont>Новость</tD><td class=cont><textarea name=news_full rows=7 cols=50>$row[news_full]</textarea></td></tr>
                 <tr><td class=cont><input class=btn type=submit name=news_new_submit value=Опубликовать></td><td class=cont><a class=without href=\"?act=del&id=".$row[id]."\">Удалить</a></td></tr>
                </table>
                </form>
                ";
        }
            $sql = mysqli_query ($db, "select id from ".$t3." where status = 'mwait'");
            $numpages = ceil (mysql_num_rows($sql)/$admin_news_show);
        if ($numpages>1)
        {
            // NEWS PAGES' LINKS ('[1] [2] ...')... YOU CAN CHANGE LINKS STYLE HERE...
            echo "<center>";
            $i=0; $k=0;
            if ($page>4)
            echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=new&page=1 class=without>1</a>&nbsp;]&nbsp; ... ";
            while ($i<$numpages)
            {
                $i++;
                if ($k<9 and $page-$i<4)
                {
                $k++;
                if ($i==($page+1))
                        echo "&nbsp;".$i."&nbsp;";
                else
                        echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=new&page=".$i." class=without>".$i."</a>&nbsp;]&nbsp;";
                }
                elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
                        echo " ... ";$z=1;}
                elseif ($i==$numpages)
                        echo "&nbsp;[&nbsp;<a href=news.php?act=moderate&mtype=new&page=".$i." class=without>".$i."</a>&nbsp;]&nbsp;";

             }
             echo "</center>";
        }
}
?>
