<?php
require_once 'inc/config.php';
require_once 'modules/functions.php';
if (isset($submit))
{
	if (!empty($_COOKIE[recently]))
		$ans = "Вы отправляли последнее письмо меньше чем  15 секунд назад. Для безопасности разрешено отправлять письма не чаще одного раза в 15 сек. Приносим извенения за неудобство.";
	else 
	{
		if (empty($name))
			$name = "Anonymos";
		if (empty($message))
			$ans = "Вы не ввели текст письма";
		$mm = conf('letter_min');
		$mm2 = conf('letter_max');
		if (!check_length($message, "letter"))
			$ans = "Длина сообщения должна быть от $mm до $mm2 символов";
		if (!empty($email))
		{
			if (!eregi("@", $email) or !eregi("\.", $email))
				$ans = "E-mail введен неверно.";
				$mm = conf('email_min');
				$mm2 = conf('email_max');
				if (!check_length($email, "email"))
					$ans = "Длина E-mail адреса должны быть от  $mm до $mm2 символов";
		}
		if (!$ans)
		{
			$finalmessage = "
			Отправитель: $name
			E-mail: $email
			Сообщение: $message";
			$res = mail ("sokrat1988@mail.ru", "С дипская, форма обр. связи", "$finalmessage","From: Deepsky\n"."Content-type: text/plain; charset=windows-1251");
			if ($res)
			{
				$ans = "Письмо отправлено! Спасибо за проявленный интерес! Заходите к нам почаще, пишите письма и вы всегда получите ответ!!!";
				setcookie("recently", "1", time()+15);
			}
			else $ans = "Произошла ошибка во время отправки письма. Скорее всего, не установлен SMTP сервер, позволяющий отправлять письма. ";
		}
			
	}
}
include_once 'inc/head.php';

?>
<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Обратная связь</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
      <h2>Авторы сайта:</h2>
		
		 <ul>
		 <li>Карим Сахибгареев<br>
		 E-mail: <a href="mailto:karim@detalk.ru" class=without title="karim@detalk.ru">karim@detalk.ru</a><br>
		 ICQ: 168258384
		 </li><br><br>
		 <li>Роман Чириков<br>
		 E-mail: <a href="mailto:sokrat1988@mail.ru" class=without title="sokrat1988@mail.ru">sokrat1988@mail.ru</a><br>
		 ICQ: 230404233
		 </li>
		 </ul>
		 <?if (!empty($ans))
		 	echo "<p><b>$ans</b></p>";
		 ?>
		 <p>Вы можете написать нам письмо с этой страницы! Для этого вам достаточно заполнить форму.<br>Если вы хотите задать какой-то вопрос, обязательно заполните поле e-mail, иначе мы не сможем связаться с вами!</p>
		 <form action=feed_back.php method=post>
		 <table class=articles>
		 <tr><td class=cont>Ваше имя</td><td class=cont><input typ=text name=name></td></tr>
		 <tr><td class=cont>Ваш e-mail</td><td class=cont><input type=text name=email></td></tr>
		 <tr><td class=cont>Пиьсмо</td><td class=cont>
		 <textarea name=message cols="45" rows="6"></textarea></td></tr>
		 <tr><td colspan=2 align=center><input type=submit class=btn name=submit value=Отправить></td></tr></table></form>
		 
		   </td>
        </tr>
      
    </table>
<?php
include_once('inc/foot.php')
?>
