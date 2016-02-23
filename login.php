<?
function auth_form()
{
        echo "
        <form action=login.php method=post>
        <table border=0 align=center class=news>
        <tr><td class=lshow>Имя</td><Td class=lshow><input type=text name=name></td></tr>
        <tr><td class=lshow>Пароль</tD><td class=lshow><input type=password name=pass></td></tr>
        <tr><td colspan=2 align=center><input class=btn type=submit name=auth_submit value=Авторизоваться></td></tr>
        </table>
        </form>
        ";
}
include 'inc/config.php';
include 'modules/functions.php';
$access=0;
if (!$_COOKIE[ngpe_id])
{
        if (!isset($auth_submit))
             $show_form = 1;
        else
        {
                if (empty($name))
                    $err = "Вы не ввели имя.<BR>";
                if (empty($pass))
                    $err.= "Вы не ввели пароль.<BR>";
                if (!$err)
                {
                     $sql = mysql_query ("select id from ".$t1." where name = '$name' and pass = '$pass'");
                     if (mysql_num_rows($sql)<1)
                     {
                         $err .="Имя или пароль введены неверно.<BR>";
                         $show_form=1;
                     }
                     else
                     {
                             $row = mysql_fetch_array($sql);
                             setcookie ("ngpe_id", "$row[id]", time()+3600*24*356);
                             setcookie ("ngpe_access", "1",  time()+3600*24*356);
                             setcookie ("registered", "1",  time()+3600*24*356*10);                             
                             $access=1;
                     }
                }
                else
                    $show_form=1;
        }
}
else
{
    if ($_COOKIE[ngpe_access]!='1' or empty($_COOKIE[ngpe_id]) or mysql_num_rows(mysql_query("select id from ".$t1." where id = '$_COOKIE[ngpe_id]' or name = '$name'"))<1)
        $show_form=1;
    else
        $access=1;
}

function head()
{
    global $access;    
	include_once 'inc/head.php';
        echo'    <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Панель управления</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>';if (!$access)echo "Введите логин и пароль";}
function foot()
{
        echo"</td></tr></table>
        ";
        include_once 'inc/foot.php';
}
?>        
            <?
            head();
            if (!$access)
            {
                    
            		if ($err)
                        echo $err;
                    if ($show_form=='1')
                        auth_form();
            }
            else
            {
                    $uid = $_COOKIE[ngpe_id];  if (empty($uid))$uid=$row[id];
                    $user = get_name($uid);
                    echo "
                    <h2>Здравствуйте, $user!</h2><br><br>
                    ";
                    //НОВОСТИ
                      echo "<a href=\"news.php?act=add\" class=without>Добавить новость</a><BR>
                      Здесь вы сможете добавить любую новость, которая вам понравилась. Новость должны соответствовать тематике сайта. Не соответствующие новости не будут опубликованы.<BR><BR>";
                      if (check_pravo($uid, "newsedit"))
                          echo "<a href=\"news.php?act=moderate\" class=without>Модерировать новости</a><br>
                          В данном разделе вы сможете проверить новости, опубликовать их, а также удалить и изменить.<BR><BR>";
                      else
                      {
                              if (check_pravo($uid, "newsadd"))
                              {
                                  if (mysql_num_rows(mysql_query ("select id from ".$t3." where author = '$uid'"))>0)
                                     echo "<a href=\"news.php?act=edit\" class=without>Редактировать ранее добавленные новости</a><br>
                                     Редактировать ранее добавленные вами новости<BR><BR>";
                              }
                      }
                    //СТАТЬИ
                      echo "<a href=\"articles.php?act=add\" class=without>Добавить статью</a><br>
                      Здесь вы сможете добавить статью по одной из тематик сайта<BR><BR>";
                      if (check_pravo($uid, "articlesedit"))
                          echo "<a href=\"articles.php?act=moderate\" class=without>Модерировать статьи</a><br>
                          В данном разделе можно проверить статьи, опубликовать ожидающие публикации, а также редактировать или удалить.<BR><BR>";
                      else
                      {
                              if (check_pravo($uid, "articlesadd"))
                              {
                                  if (mysql_num_rows(mysql_query ("select id from ".$t5." where author = '$uid'"))>0)
                                      echo "<a href=\"articles.php?act=edit\" class=without>Редактировать ранее добавленные статьи</a><br>
                                      Раздел для редактирования ранее добавленных вами статей.<BR><BR>";
                              }
                      }
					//ГОЛОСОВАНИЯ
					if (check_pravo($uid, "votesadd"))echo "<a href=\"vote.php?act=add\" class=without>Добавить голосование</a><br>
					Добавьте голосование, чтобы узнать мнение людей.<BR><BR>";
					if (check_pravo($uid, "votesedit"))
					echo "<a href=\"vote.php?act=moder\" class=without>Модерировать голосования</a><br>
					Перемещение голосований в архив и их архива.<BR><BR>";
					//ФОТО
					if (check_pravo($uid, "photoadd"))
						echo "<a href=\"photo.php?act=add\" class=without>Добавить фотографию в фотогаллерею</a><br>
						Добавление различных фотографий в один из разделов фотогалереи.<BR><BR>";
                    //EXIT  
					echo "<a href=logout.php class=without>Выйти</a>";   
            }
            ?>
           
<?
foot();
?>
