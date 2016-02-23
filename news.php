<?
include 'modules/functions.php';
include 'modules/news.php';
include 'inc/config.php';
function head()
{
        include 'inc/head.php';
        echo'<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Управление новостями</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
        ';
}
function foot()
{
        echo"
        </tD></tr>
        </table>
        ";
        include 'inc/foot.php';
}
head();
if (!$_COOKIE[ngpe_access] or empty($_COOKIE[ngpe_id]) or mysql_num_rows(mysql_query("select id from ".$t1." where id = '$_COOKIE[ngpe_id]'"))<1)
{
        echo "Вы не авторизованы. <BR>
        <A href=login.php>Авторизуйтесь</a>";
}
else
{
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_title_min'"));
              $news_title_length_min = $row[v];
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_title_max'"));
              $news_title_length_max = $row[v];
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_short_min'"));
              $news_short_length_min = $row[v];
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_short_max'"));
              $news_short_length_max = $row[v];
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_full_min'"));
              $news_full_length_min = $row[v];
      $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'news_full_max'"));
              $news_full_length_max = $row[v];
      switch ($act)
      {
              default:
              echo "<a href=\"?act=add\" class=without>Добавить новость</a><BR>";
              if (check_pravo($_COOKIE[ngpe_id], "newsedit"))
                  echo "<a href=\"?act=moderate\" class=without>Модерировать новости</a><BR>";
              else
              {
                      if (check_pravo($_COOKIE[ngpe_id], "newsadd"))
                      {
                          if (mysql_num_rows(mysql_query ("select id from ".$t3." where author = '$_COOKIE[ngpe_id]'"))>0)
                              echo "<a href=\"?act=edit\" class=without>Редактировать ранее добавленные новости</a><BR>";
                      }
              }
              break;
              case 'add':
              if (!isset($add))
              {
                          news_add_auth_form();
              }
              else
              {
                      if (check_pravo($_COOKIE[ngpe_id], "newsedit") or check_pravo($_COOKIE[ngpe_id], "newsadd"))
                          $status = "ok";
                      else
                              $status = "mwait";
                      $title=news_filter($title, $news_title_length_min, $news_title_length_max);
                      if (empty($title))
                      {
                              echo "Неправильно введен загаловок новости.<BR>";
                              news_add_auth_form($title, $news_short, $news_full);
                              foot();
                              exit();
                      }
                      $news_short=news_filter($news_short, $news_short_length_min, $news_short_length_max);
                      if (empty($news_short))
                      {
                              echo "Неправильно введено краткое описание новости.<BR>";
                              news_add_auth_form($title, $news_short, $news_full);
                              foot();
                              exit();
                      }
                      $news_full=news_filter($news_full, $news_full_length_min, $news_full_length_max);
                      if (empty($news_full))
                      {
                              echo "Неправильно введена новость.<BR>";
                              news_add_auth_form($title, $news_short, $news_full);
                              foot();
                              exit();
                      }
                      $res = news_insert($title, $news_short, $news_full, $_COOKIE[ngpe_id], $status, $kid);
                      echo $res;
              }
              break;
              case 'edit':
                    if (check_pravo($_COOKIE[ngpe_id], "newsadd"))
                    {
                            $sql = mysql_query ("select * from ".$t3." where author = '$_COOKIE[ngpe_id]'");
                            if (mysql_num_rows($sql)<1)
                                echo "Нет новостей для редактирования.<BR>";
                            else
                            {
                                    if (!isset($news_edit_submit))
                                    {
                                         while ($row = mysql_fetch_array($sql))
                                         {
                                                 news_edit_show($row);
                                         }
                                    }
                                    else
                                    {
                                            $uid = $_COOKIE[ngpe_id];
                                            echo news_edit_update ($title, $kid, $news_short, $news_full, $id, $uid);
                                    }
                            }
                    }
                    else
                        echo "Вы не имеете прав доступа к этой части сайта.<BR>";
              break;
              case 'moderate':
                    if (!check_pravo($_COOKIE[ngpe_id], "newsedit"))
                         echo "Вы не имеете прав доступа к этой части сайта.<BR>";
                    else
                    {
                            switch ($mtype)
                            {
                                    default:
                                          global $t3;
                                          $sql = mysql_query ("select id from ".$t3." where status = 'mwait'");
                                          $numnew = mysql_num_rows($sql);
                                          $sql = mysql_query ("select id from ".$t3." where status = 'ok'");
                                          $numold = mysql_num_rows($sql);
                                          if ($numnew>0)
                                             echo"
                                             <a href=\"?act=moderate&mtype=new\" class=without>Модерировать ожидающие модерации новости ($numnew)<BR></a>";
                                          if ($numold>0)
                                             echo"
                                             <a href=\"?act=moderate&mtype=old\" class=without>Редактировать уже опубликованные новости ($numold)<BR></a>";
                                    break;
                                    case 'new':
                                          if (!isset($news_new_submit))
                                               news_new_moder();
                                          else
                                              echo news_moder_update($id, $title, $kid, $news_short, $news_full);
                                    break;
                                    case 'old':
                                          if (!isset($news_old_submit))
                                               news_old_moder();
                                          else
                                              echo news_moder_update($id, $title, $kid, $news_short, $news_full);
                                    break;
                            }
                    }
              break;
              case 'del':
                    if (!check_pravo($_COOKIE[ngpe_id], "newsedit"))
                         echo "Вы не имеете прав доступа к этой части сайта.<BR>";
                    else
                    {
                            if (empty($id))
                                echo "Не выбрана новость<BR>";
                            else
                            {
                                $sql = mysql_query ("delete from ".$t3." where id = '$id'");
                                if ($sql)
                                    echo "Новость удалена!<BR>";
                                else
                                    echo mysql_error();
                            }
                    }
              break;
      }

}
foot();
?>
