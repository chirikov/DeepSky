<?
function reg_form($name='', $email='', $url='', $icq='')
{
        echo "
        <form action=reg.php method=post>
        <table border=0 align=center class=articles>
        <tr><td class=cont>���*</tD><td class=cont><input type=text name=name value=$name></td><td class=cont>������� ���� ���. ��� ����� �������������� ��� �����������.</td></tr>
        <tr><td class=cont>������*</td><tD class=cont><input type=password name=pass></td><td class=cont>������� ��� ������. �� ��������� ����� ������, ��� ��� ��������� ���� ������ �� ������ ����������������.</td></tr>
        <tr><td class=cont>E-mail*</td><Td class=cont><input type=text name=email value=$email></td><td class=cont>��� E-mail �����. �� ����������� �� ��� ��� e-mail ����� �� ������ � ����-����� ����� ��� ����.</td></tr>
        <tr><td class=cont>ICQ</td><td class=cont><input type=text name=icq value=$icq></td><td class=cont>��� ICQ UIN. ����� ��� ������� ����� � ����.</td></tr>
        <Tr><td class=cont>URL</td><td class=cont><input type=text name=url value=$url></td><td class=cont>������� ����� ����� �������� ���������, ���� ������� �������.</td></tr>
        <tr><td class=cont colspan=3 align=center><input type=submit class=btn name=reg_submit value=������������������></td></tr>
        <tr><td class=cont colspan=3>* - ����������� ��� ����������.</td></tr>
        </table>
        </form>
        ";
}
function regcheck_length ($word, $minl, $maxl)
{
        if (empty($word))
            return 0;
        else
        {
                if (strlen($word)<$minl or strlen($word)>$maxl)
                    return 1;
        }
}
include 'inc/config.php';
include 'modules/functions.php';
if (!isset($reg_submit))
     $show_form=1;
else
{
        $err='';
        if (empty($name))
            $err = "�� �� ����� ���� ���.<BR>";
        if (empty($pass))
            $err.= "�� �� ����� ������.<br>";
        if (empty($email))
            $err.= "�� �� ����� E-mail.<br>";
        if ($err)
            $show_form=1;
        else
        {
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'name_min'"));
                       $name_length_min = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'name_max'"));
                       $name_length_max = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'pass_min'"));
                       $pass_length_min = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'pass_max'"));
                       $pass_length_max = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'email_min'"));
                       $email_length_min = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'email_max'"));
                       $email_length_max = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'url_min'"));
                       $url_length_min = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'url_max'"));
                       $url_length_max = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'icq_min'"));
                       $icq_length_min = $row[v];
                $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = 'icq_max'"));
                       $icq_length_max = $row[v];
                if (regcheck_length($name, $name_length_min, $name_length_max))
                    $err.="����� ����� ������ ���� �� $name_length_min �� $name_length_max ��������.<br>";
                if (regcheck_length($pass, $pass_length_min, $pass_length_max))
                    $err.="����� ������ ������ ���� �� $pass_length_min �� $pass_length_max ��������.<br>";
                if (regcheck_length($email, $email_length_min, $email_length_max))
                    $err.="����� ������ ������ ���� �� $email_length_min �� $email_length_max ��������.<br>";
                if (regcheck_length($url, $url_length_min, $url_length_max))
                    $err.="����� URL ������ ������ ���� �� $url_length_min �� $url_length_max ��������.<BR>";
                if (regcheck_length($icq, $icq_length_min, $icq_length_max))
                    $err.="����� ICQ ������ ���� �� $icq_length_min �� $icq_length_max  ��������.<BR>";
                if (!eregi("@", $email) or !eregi(".", $email))
                	$err.="Email ������ �������<BR>";
                    $sql = mysql_query ("select id from ".$t1." where name = '$name'");
                if (mysql_num_rows($sql)>0)
                    $err.="������������ � ����� ������� ��� ���������������. �������� ������.<BR>";
                $sql = mysql_query ("select id from ".$t1." where email = '$email'");
                if (mysql_num_rows($sql)>0)
                    $err.="������������ � ����� e-mail ��� ����������. �������� ������.<BR>";
                if (!emptY($url))
                {
                	if (!eregi("http://", $url) or !eregi(".", $url))
                		$err.="URL ����� ������ �������.<BR>";
                }
                if ($err)
                    $show_form=1;
                else
                {
                        $date = time();
                        $sql = mysql_query ("insert into ".$t1." (name, pass, pravo, email, icq, url, date) values ('$name', '$pass', 'user', '$email', '$icq', '$url', '$date');");
                        if ($sql)
                        {
                                $err = "<p>�� ������� ����������������!������ �� ������ ������� ������� � ����� �����! �� ������ ��������� ���� ����������� ������ � �������. ����� ���������� � ������ � ������, ��� ������ ������� <a href=login.php class=without>����</a>.";
                                $id = mysql_insert_id();
                                setcookie ("ngpe_id", "$id", time()+3600*24*356);
                                setcookie ("ngpe_access", "1",  time()+3600*24*356);
                                setcookie ("registered", "1", time()+3600*24*365*5);
                        }
                        else
                            $err = mysql_error();
                }
        }
}

include 'inc/head.php';
?>
        <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>�����������</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
         
         <A href=login.php class="without">�����������</a><BR>
            <?
            if ($err)
                echo "<p><BR><b>$err</b></p>";
            if ($show_form)
                reg_form($name, $email, $url, $icq);            
            ?>
            </td></tr></table>
<?
include 'inc/foot.php';
?>
