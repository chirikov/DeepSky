<?php
require_once 'inc/config.php';
require_once 'modules/functions.php';
if (isset($submit))
{
	if (!empty($_COOKIE[recently]))
		$ans = "�� ���������� ��������� ������ ������ ���  15 ������ �����. ��� ������������ ��������� ���������� ������ �� ���� ������ ���� � 15 ���. �������� ��������� �� ����������.";
	else 
	{
		if (empty($name))
			$name = "Anonymos";
		if (empty($message))
			$ans = "�� �� ����� ����� ������";
		$mm = conf('letter_min');
		$mm2 = conf('letter_max');
		if (!check_length($message, "letter"))
			$ans = "����� ��������� ������ ���� �� $mm �� $mm2 ��������";
		if (!empty($email))
		{
			if (!eregi("@", $email) or !eregi("\.", $email))
				$ans = "E-mail ������ �������.";
				$mm = conf('email_min');
				$mm2 = conf('email_max');
				if (!check_length($email, "email"))
					$ans = "����� E-mail ������ ������ ���� ��  $mm �� $mm2 ��������";
		}
		if (!$ans)
		{
			$finalmessage = "
			�����������: $name
			E-mail: $email
			���������: $message";
			$res = mail ("sokrat1988@mail.ru", "� �������, ����� ���. �����", "$finalmessage","From: Deepsky\n"."Content-type: text/plain; charset=windows-1251");
			if ($res)
			{
				$ans = "������ ����������! ������� �� ����������� �������! �������� � ��� ������, ������ ������ � �� ������ �������� �����!!!";
				setcookie("recently", "1", time()+15);
			}
			else $ans = "��������� ������ �� ����� �������� ������. ������ �����, �� ���������� SMTP ������, ����������� ���������� ������. ";
		}
			
	}
}
include_once 'inc/head.php';

?>
<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>�������� �����</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
      <h2>������ �����:</h2>
		
		 <ul>
		 <li>����� �����������<br>
		 E-mail: <a href="mailto:karim@detalk.ru" class=without title="karim@detalk.ru">karim@detalk.ru</a><br>
		 ICQ: 168258384
		 </li><br><br>
		 <li>����� �������<br>
		 E-mail: <a href="mailto:sokrat1988@mail.ru" class=without title="sokrat1988@mail.ru">sokrat1988@mail.ru</a><br>
		 ICQ: 230404233
		 </li>
		 </ul>
		 <?if (!empty($ans))
		 	echo "<p><b>$ans</b></p>";
		 ?>
		 <p>�� ������ �������� ��� ������ � ���� ��������! ��� ����� ��� ���������� ��������� �����.<br>���� �� ������ ������ �����-�� ������, ����������� ��������� ���� e-mail, ����� �� �� ������ ��������� � ����!</p>
		 <form action=feed_back.php method=post>
		 <table class=articles>
		 <tr><td class=cont>���� ���</td><td class=cont><input typ=text name=name></td></tr>
		 <tr><td class=cont>��� e-mail</td><td class=cont><input type=text name=email></td></tr>
		 <tr><td class=cont>������</td><td class=cont>
		 <textarea name=message cols="45" rows="6"></textarea></td></tr>
		 <tr><td colspan=2 align=center><input type=submit class=btn name=submit value=���������></td></tr></table></form>
		 
		   </td>
        </tr>
      
    </table>
<?php
include_once('inc/foot.php')
?>
