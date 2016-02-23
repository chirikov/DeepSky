<?
include 'modules/functions.php';
include 'modules/articles.php';
include 'inc/config.php';
function head()
{
        include 'inc/head.php';
        echo'<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Управление статьями</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
        ';
}
function foot()
{
        echo"</tD></tr>
        </table>
        ";
        include 'inc/foot.php';
}
head();
if (!$_COOKIE[ngpe_access] or empty($_COOKIE[ngpe_id]) or mysql_num_rows(mysql_query("select id from ".$t1." where id = '$_COOKIE[ngpe_id]'"))<1)
{
        echo "Вы не авторизованы. <BR>
        <A href=login.php>Авторизуйтесь</a>";
        foot();
        die();
}
switch ($act)
{
        default:
             echo "<a href=\"?act=add\" class=without>Добавить статью</a><BR>";
             if (check_pravo($_COOKIE[ngpe_id], "articlesedit"))
                 echo "<a href=\"?act=moderate\" class=without>Модерировать статьи</a><BR>";
             else
             {
                     if (check_pravo($_COOKIE[ngpe_id], "articlesadd"))
                     {
                         if (mysql_num_rows(mysql_query ("select id from ".$t5." where author = '$_COOKIE[ngpe_id]'"))>0)
                             echo "<a href=\"?act=edit\" class=without>Редактировать ранее добавленные статьи</a><BR>";
                     }
             }
        break;
        case 'add':
              if (!isset($add_sub))
                   add_form(get_name($_COOKIE[ngpe_id]));
              else
              {
                   if (check_pravo($_COOKIE[ngpe_id], "articlesadd") or check_pravo($_COOKIE[ngpe_id], "articlesedit"))
                       $status='ok';
                   else
                       $status = 'mwait';
                   $title = articles_filter($title, "art_title");
                   if (empty($title))
                   {
                           echo "Не правильно ведено название статьи.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   $short = articles_filter($short, "art_short");
                   if (empty($short))
                   {
                           echo "Не правильно ведено кртакое описание статьи.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   $full = articles_filter($full, "art_full");
                   if (empty($full))
                   {
                           echo "Не правильно введена статья.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   if (get_name($_COOKIE[ngpe_id])!=$author)
                   {
                           if (check_author($author))
                           {
                               echo "Недопустимое имя автора, т.к. участник с таким именем уже зарегистрирован.";
                               add_form(get_name($_COOKIE[ngpe_id]), $title, $kid, $short, $full, $mid, $ngcid);
                               foot();
                               exit();
                           }
                   }
                   echo article_insert($status, $title, $kid, $short, $full, $author, $mid, $ngcid);
              }
        break;
        case 'moderate':
        if (!check_pravo ($_COOKIE[ngpe_id], "articlesedit"))
        {
                echo "Вы не имеете прав доступа к этой части сайта.";
                foot();
                exit();
        }
              switch ($mact)
              {
                      default:
                      $sql = mysql_query ("select id from ".$t5." where status='mwait'");
                      $num = mysql_num_rows($sql);
                      if ($num>0)
                          echo "<a href=\"?act=moderate&mact=new\" class=without>Модерировать ожидающие проверку статьи ($num)<br></a>";
                      $sql = mysql_query ("select id from ".$t5." where status='ok'");
                      $num = mysql_num_rows($sql);
                      if ($num>0)
                          echo "<a href=\"?act=moderate&mact=old\" class=without>Модерировать уже опубликованные статьи ($num)<br></a>";
                      break;
                      case 'new':
                      if (!isset($new_sub) and empty($id))
                           new_list();
                      elseif (!isset($new_sub) and !empty($id))
                           new_form($id);
                      elseif (isset($new_sub) and !empty($id))
                           new_public ($id, $kid, $title, $short, $full, $author, $mid, $ngcid);

                      break;
                      case 'old':
                            switch ($oldact)
                            {
                                    default:
                                          echo "Выберите категорию.<BR>";
                                          kat_list('m');
                                    break;
                                    case 'artlist':
                                          articles_list($kid, $page);
                                    break;
                                    case 'form':
                                          old_m_form($id);
                                    break;
                                    case 'update':
                                          old_insert ($id, $kid, $author, $title, $short, $full, $mid, $ngcid);
                                    break;
                            }
                      break;
              }
        break;
        case 'del':
        if (empty($id))
            echo "Не выбрана статья<BR>";
        else
        {
                $sql = mysql_query ("delete from ".$t5." where id = '$id'");
                if ($sql)
                    echo "Статья удалена!<BR>";
                else
                    echo mysql_error();
        }
        break;
}

foot();
