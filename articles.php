<?
include 'modules/functions.php';
include 'modules/articles.php';
include 'inc/config.php';
function head()
{
        include 'inc/head.php';
        echo'<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>���������� ��������</h1></td>
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
        echo "�� �� ������������. <BR>
        <A href=login.php>�������������</a>";
        foot();
        die();
}
switch ($act)
{
        default:
             echo "<a href=\"?act=add\" class=without>�������� ������</a><BR>";
             if (check_pravo($_COOKIE[ngpe_id], "articlesedit"))
                 echo "<a href=\"?act=moderate\" class=without>������������ ������</a><BR>";
             else
             {
                     if (check_pravo($_COOKIE[ngpe_id], "articlesadd"))
                     {
                         if (mysql_num_rows(mysql_query ("select id from ".$t5." where author = '$_COOKIE[ngpe_id]'"))>0)
                             echo "<a href=\"?act=edit\" class=without>������������� ����� ����������� ������</a><BR>";
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
                           echo "�� ��������� ������ �������� ������.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   $short = articles_filter($short, "art_short");
                   if (empty($short))
                   {
                           echo "�� ��������� ������ ������� �������� ������.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   $full = articles_filter($full, "art_full");
                   if (empty($full))
                   {
                           echo "�� ��������� ������� ������.<BR>";
                           add_form($author, $title, $kid, $short, $full, $mid, $ngcid);
                           foot();
                           exit();
                   }
                   if (get_name($_COOKIE[ngpe_id])!=$author)
                   {
                           if (check_author($author))
                           {
                               echo "������������ ��� ������, �.�. �������� � ����� ������ ��� ���������������.";
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
                echo "�� �� ������ ���� ������� � ���� ����� �����.";
                foot();
                exit();
        }
              switch ($mact)
              {
                      default:
                      $sql = mysql_query ("select id from ".$t5." where status='mwait'");
                      $num = mysql_num_rows($sql);
                      if ($num>0)
                          echo "<a href=\"?act=moderate&mact=new\" class=without>������������ ��������� �������� ������ ($num)<br></a>";
                      $sql = mysql_query ("select id from ".$t5." where status='ok'");
                      $num = mysql_num_rows($sql);
                      if ($num>0)
                          echo "<a href=\"?act=moderate&mact=old\" class=without>������������ ��� �������������� ������ ($num)<br></a>";
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
                                          echo "�������� ���������.<BR>";
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
            echo "�� ������� ������<BR>";
        else
        {
                $sql = mysql_query ("delete from ".$t5." where id = '$id'");
                if ($sql)
                    echo "������ �������!<BR>";
                else
                    echo mysql_error();
        }
        break;
}

foot();
