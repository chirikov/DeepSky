<?
function auth_form()
{
        echo "
        <form action=login.php method=post>
        <table border=0 align=center class=news>
        <tr><td class=lshow>���</td><Td class=lshow><input type=text name=name></td></tr>
        <tr><td class=lshow>������</tD><td class=lshow><input type=password name=pass></td></tr>
        <tr><td colspan=2 align=center><input class=btn type=submit name=auth_submit value=��������������></td></tr>
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
                    $err = "�� �� ����� ���.<BR>";
                if (empty($pass))
                    $err.= "�� �� ����� ������.<BR>";
                if (!$err)
                {
                     $sql = mysql_query ("select id from ".$t1." where name = '$name' and pass = '$pass'");
                     if (mysql_num_rows($sql)<1)
                     {
                         $err .="��� ��� ������ ������� �������.<BR>";
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
     	 <td colspan=2 class=hd><h1>������ ����������</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>';if (!$access)echo "������� ����� � ������";}
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
                    <h2>������������, $user!</h2><br><br>
                    ";
                    //�������
                      echo "<a href=\"news.php?act=add\" class=without>�������� �������</a><BR>
                      ����� �� ������� �������� ����� �������, ������� ��� �����������. ������� ������ ��������������� �������� �����. �� ��������������� ������� �� ����� ������������.<BR><BR>";
                      if (check_pravo($uid, "newsedit"))
                          echo "<a href=\"news.php?act=moderate\" class=without>������������ �������</a><br>
                          � ������ ������� �� ������� ��������� �������, ������������ ��, � ����� ������� � ��������.<BR><BR>";
                      else
                      {
                              if (check_pravo($uid, "newsadd"))
                              {
                                  if (mysql_num_rows(mysql_query ("select id from ".$t3." where author = '$uid'"))>0)
                                     echo "<a href=\"news.php?act=edit\" class=without>������������� ����� ����������� �������</a><br>
                                     ������������� ����� ����������� ���� �������<BR><BR>";
                              }
                      }
                    //������
                      echo "<a href=\"articles.php?act=add\" class=without>�������� ������</a><br>
                      ����� �� ������� �������� ������ �� ����� �� ������� �����<BR><BR>";
                      if (check_pravo($uid, "articlesedit"))
                          echo "<a href=\"articles.php?act=moderate\" class=without>������������ ������</a><br>
                          � ������ ������� ����� ��������� ������, ������������ ��������� ����������, � ����� ������������� ��� �������.<BR><BR>";
                      else
                      {
                              if (check_pravo($uid, "articlesadd"))
                              {
                                  if (mysql_num_rows(mysql_query ("select id from ".$t5." where author = '$uid'"))>0)
                                      echo "<a href=\"articles.php?act=edit\" class=without>������������� ����� ����������� ������</a><br>
                                      ������ ��� �������������� ����� ����������� ���� ������.<BR><BR>";
                              }
                      }
					//�����������
					if (check_pravo($uid, "votesadd"))echo "<a href=\"vote.php?act=add\" class=without>�������� �����������</a><br>
					�������� �����������, ����� ������ ������ �����.<BR><BR>";
					if (check_pravo($uid, "votesedit"))
					echo "<a href=\"vote.php?act=moder\" class=without>������������ �����������</a><br>
					����������� ����������� � ����� � �� ������.<BR><BR>";
					//����
					if (check_pravo($uid, "photoadd"))
						echo "<a href=\"photo.php?act=add\" class=without>�������� ���������� � ������������</a><br>
						���������� ��������� ���������� � ���� �� �������� �����������.<BR><BR>";
                    //EXIT  
					echo "<a href=logout.php class=without>�����</a>";   
            }
            ?>
           
<?
foot();
?>
