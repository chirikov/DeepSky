<?
include '../inc/config.php';
include 'head.php';
function prava_form()
{
        echo "
        <form action=prava.php method=post>
        <input type=hidden name=show value=add>
        �������� ������ ���� ������� <input type=text name=pravo><br>
        <input type=checkbox name=newsadd>���������� ��������<br>
        <input type=checkbox name=newsedit>������������� ��������<br>
        <input type=checkbox name=articlesadd>���������� ������<br>
        <input type=checkbox name=articlesedit>������������� ������<br>
        <input type=checkbox name=filesadd>���������� ������<br>
        <input type=checkbox name=filesedit>������������� ������<br>
        <input type=checkbox name=voteadd>���������� �����������<br>
        <input type=checkbox name=voteedit>������������� �����������<br>
        <input type=checkbox name=photoadd>���������� ����������<BR>
        <input type=submit name=prava_submit value=��������>
        </form>
        ";
}
?>

<?
switch ($show)
{
        default:
        echo "
        <a href=prava.php?show=add>�������� ������� ���� �������</a><br>
        <a href=prava.php?show=list>������ ������� ���� �������</a><br>
        <a href=prava.php?show=useradd>��������� ������������ ������� ���� �������</a><bR>
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
                        $sql = mysql_query ("select pravo from ".$t2." where pravo = '$pravo'");
                        if (mysql_num_rows($sql)>0)
                            echo "������� ������� $pravo ��� ����������. ���� ������ ��������.<BR>";
                        else
                        {
                                $sql = mysql_query ("insert into ".$t2." values ('$pravo', '$newsadd', '$newsedit', '$articlesadd', '$articlesedit', '$filesadd', '$filesedit', '$voteadd', '$voteedit', '$photoadd');");
                                if ($sql)
                                    echo "OK";
                                else
                                    echo mysql_error();
                        }
                }
        }
        break;
        case 'list':
        $sql =mysql_query ("select * from ".$t2." where 1");
        echo "<table>
        <tr><td>Name</td><td>NEWS ADD</td><td>NEWS EDIT</td><td>Articles Add</td><td>Articles Edit</td>
        <td>Files Add</td><td>Files Edit</td><td>Votes Add</td><td>Votes Edit</td><TD>Photos Add</td>
        <td>��������������</td><td>��������</td><td>���������</td></tr>";
        while ($row = mysql_fetch_array($sql))
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
               <td><a href=prava.php?show=edit&pravo=$row[pravo]>�������������</a></td>
               <td><a href=prava.php?show=del&pravo=$row[pravo]>�������</a></td>
               <td><a href=prava.php?show=useradd&pravo=$row[pravo]>���������</a></td></tr>
               ";
        }
        echo "</table>";
        break;
        case 'useradd':
        if (empty($username))
        {
                if (empty($pravo))
                        $sql = mysql_query ("select pravo from ".$t2." where 1");
                $user = rawurldecode($user);
                echo"
                <form action=prava.php method=post>
                <input type=hidden name=show value=useradd>
                ��������� ����� <input type=text name=username value=$user> ������� ���� �������
                ";
                if (!empty($pravo))echo "<input type=hidden name=pravo value='$pravo'> $pravo";
                else
                {
                        echo "<select name=pravo>";
                        while ($row = mysql_fetch_array($sql))
                        {
                                echo "<option value=$row[pravo]>$row[pravo]";
                        }
                        echo "</select>";

                }
                echo"<input type=submit name=ad_us value=���������>
                </form>
                ";
        }
        else
        {
                $sql = mysql_query ("select id from ".$t1." where name = '$username'");
                if (mysql_num_rows($sql)<1)
                    echo "����� $username �� ����������";
                else
                {
                        $sql = mysql_query ("update ".$t1." set pravo = '$pravo' where name = '$username'");
                        if ($sql)
                            echo "����� ���������";
                        else
                            echo mysql_error();
                }
        }
        break;
        case 'del':
        if ($del!='yes')
        {
                echo"������� ����� $pravo?<br>
                <a href=prava.php?show=del&pravo=$pravo&del=yes>��
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=prava.php>���</a>";
        }
        else
        {
                $sql = mysql_query ("update ".$t1." set pravo = 'user' where pravo = '$pravo'");
                $sql2 = mysql_query ("delete from ".$t2." where pravo = '$pravo'");
                if (!$sql or !$sql2)
                     echo mysql_error();
                else
                    echo "����� �������.";
        }
        break;
        case 'edit':
        if (!empty($pravo))
        {
              if (!isset($upd_sub))
              {
               $sql = mysql_query ("select * from ".$t2." where pravo = '$pravo'");
               $row=  mysql_fetch_array($sql);
                echo "
                <form action=prava.php method=post>
                <input type=hidden name=pravo value=$pravo>
                <input type=hidden name=show value=edit>
                �������� ������ ���� ������� $pravo<br>
                <input type=checkbox name=newsadd ";if ($row[newsadd]=='on')echo "checked"; echo">���������� ��������<br>
                <input type=checkbox name=newsedit ";if ($row[newsedit]=='on')echo "checked"; echo">������������� ��������<br>
                <input type=checkbox name=articlesadd ";if ($row[articlesadd]=='on')echo "checked"; echo">���������� ������<br>
                <input type=checkbox name=articlesedit ";if ($row[articlesedit]=='on')echo "checked"; echo">������������� ������<br>
                <input type=checkbox name=filesadd ";if ($row[filesadd]=='on')echo "checked"; echo">���������� ������<br>
                <input type=checkbox name=filesedit ";if ($row[filesedit]=='on')echo "checked"; echo">������������� ������<br>
                <input type=checkbox name=voteadd ";if ($row[votesadd]=='on')echo "checked"; echo">���������� �����������<br>
                <input type=checkbox name=voteedit ";if ($row[votesedit]=='on')echo "checked"; echo">������������� �����������<br>
                <input type=checkbox name=photoadd ";if ($row[photoadd]=='on')echo "checked"; echo">���������� ����������<br>
                <input type=submit name=upd_sub value=��������>
                </form>
                ";
              }
              else
              {
                      $sql = mysql_query ("update ".$t2." set newsadd = '$newsadd', newsedit = '$newsedit', articlesadd = '$articlesadd', articlesedit = '$articlesedit', filesadd = '$filesadd', filesedit = '$filesedit', votesadd = '$voteadd', votesedit = '$voteedit', photoadd='$photoadd' where pravo = '$pravo'");
                      if ($sql) echo "OK";
                      else echo mysql_error();
              }
        }
        break;
}
?>
</body>
</html>