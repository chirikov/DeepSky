<?php
function katlist($pid)
{
	global $db, $t4, $t5;
	if (empty($pid))
	$pid=1;
	else
	{
		$row = mysqli_fetch_array(mysqli_query($db, "select kat from ".$t4." where id = '$pid'"));

	}
	$sql = mysqli_query ($db, "select id, kat, about from ".$t4." where pid = '$pid' order by id");
	if (mysqli_num_rows($sql)>0)
	{
		echo "
		<tr>
      <td class=cont>
      <h2>Категории</h2>";$numtogether = mysqli_num_rows($sql);
		$i=1;
      while ($row = mysqli_fetch_array($sql))
		{
			$num = mysqli_num_rows(mysqli_query($db, "select id from ".$t5." where kid = '".$row['id']."' and status='ok'"));
			echo "			
       		<a href=show_articles.php?kid=".$row['id'].">".$row['kat']." ($num)</a><br>
       		".$row['about']."";if ($i!=$numtogether)echo"<br><br>";
			$i++;
		}
		echo "
      </td>
     </tr>";
	}
	return $pid;
}
function artlist($kid, $page)
{
	if ($kid>1)
	{
		global $db, $t5;
		if (empty($page))$page=0;
		else $page--;
		$articles_show = conf('articles_per_page');
		$sql = mysqli_query ($db, "select about, title, id from ".$t5." where kid = '$kid' and status = 'ok' order by date desc limit ".$articles_show*$page.", ".$articles_show."");
		$numall=mysqli_num_rows($sql);
		if ($numall<1)
		echo"<tr>
      <td class=cont>
      <h2>Статей в данной категории пока нет</h2>
		<p>Вы можете добавить статью в эту категорию.
		Для этого <a href=reg.php class=without>зарегистрируйтесь</a> или <a href=login.php class=without>авторизуйтесь</a>,  если вы уже зарегистрированы.<BR>
		Будут сохранены ваши авторские права на статью. В случае, если статья написана не вами, перед опубликованием статьи получите согласие автора на её публикацию на сайте DeepSky.DeTalk.ru. </p>
		<p>Вы сможете публиковать свои статьи сразу после регистрации. Если вам не было выдано специального разрешения на опубликование статей, ваша статья будет опубликована после предварительной проверки модератором.
		</p></td></tr>";		
		else
		{
			echo "<tr>
      <td class=cont>
      <h2>Список статей</h2>";
			$i=1;
			while ($row = mysqli_fetch_array($sql))
			{
				echo "<a href=show_articles.php?act=read&aid=".$row['id'].">".$row['title']."</a><br>".$row['about'];
				if ($i!=$numall)echo "<BR><BR>";
				$i++;
			}
			$sql = mysqli_query ($db, "select id from ".$t5." where status = 'ok' and kid='$kid'");
			$numpages = ceil (mysqli_num_rows($sql)/$articles_show);
			if ($numpages>1)
			{
				echo "<center>";
				$i=0; $k=0;
				if ($page>4)
				echo "&nbsp;&nbsp;<a href=\"?kid=$kid&page=1\" class=without>1</a>&nbsp;&nbsp; ... ";
				while ($i<$numpages)
				{
					$i++;
					if ($k<9 and $page-$i<4)
					{
						$k++;
						if ($i==($page+1))
						echo "&nbsp;<b>[".$i."]</b>&nbsp;";
						else
						echo "&nbsp;&nbsp;<a href=\"?kid=$kid&page=".$i."\" class=without>".$i."</a>&nbsp;&nbsp;";
					}
					elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
						echo " ... ";$z=1;}
						elseif ($i==$numpages)
						echo "&nbsp;&nbsp;<a href=\"?kid=$kid&page=".$i."\" class=without>".$i."</a>&nbsp;&nbsp;";

				}
				echo "</center>";
			}
			echo "</td></tr>";
			
		}
	}
}
function show_article ($id)
{
	global $db, $t5, $t10, $_COOKIE;
	$sql = mysqli_query ($db, "select id from ".$t5." where id = '$id' limit 1");
	if (mysqli_num_rows($sql)<1)
	echo "Нет такой статьи...";
	else
	{
		mysqli_query ($db, "update ".$t5." set readnum=readnum+1 where id = '$id'");
		$sql = mysqli_query ($db, "select title, article, readnum, author, date, rate, votenum from ".$t5." where id = '$id'");
		$row = mysqli_fetch_array($sql);
		$ch2 = $row['readnum']%10;
		$po = "";
		if (($row['readnum']%100 - $row['readnum']%10)!='10')
		{
			if ($ch2=='2' or $ch2=='3' or $ch2=='4')
			$po = 'а';
		}
		$ch = $row['votenum']%10;
		$po3='о';
		if (($row['votenum']%100 - $row['votenum']%10)!='10' or $row['votenum']<10)
		{
			if ($ch=='1')$po3 = '';
		}
		$ch2 = $row['votenum']%10;
		$po2 = "";
		if (($row['votenum']%100 - $row['votenum']%10)!='10')
		{
			if ($ch2=='2' or $ch2=='3' or $ch2=='4')
			$po2 = 'а';
		}
		echo
		"<tr>
      <td>
       <table style=\"width: 100%; height: 100%\" cellspacing=0 cellpadding=0>
        <tr>
         <td class=cont align=center>
          <h2>".$row['title']."</h2>
          <div class=author><B>Автор</B>: ".$row['author']."<br><B>Опубликовано</B> ".true_date($row['date'])."<br><B>Просмотрено</B> ".$row['readnum']." раз$po</div>
        ".stripslashes($row['article'])." 
     	  <div id=rated><B>Рейтинг статьи</B>: ".$row['rate']."/10.00<br><B>Проголосовал$po3</B>: ".$row['votenum']." человек$po2</div>";
		vote_panel($id);
		echo'
     </td>
        </tr>
       </table>
      </td>
     </tr> ';





		if (conf('articles_kom_switch')=='on')
		{
			articles_komment_form($id);
			if (mysqli_num_rows($sql)>0)
			{
				articles_komment_table($id, 0);
			}
		}
	}
}
function articles_komment_table($id, $page)
{
	global $db, $t11;
	$komspp = conf("articles_komments");
	if (conf("articles_komments_order")=="desc")
	$sql = mysqli_query ($db, "select * from ".$t11." where aid = '$id' order by date desc limit ".$komspp*$page.", ".$komspp."");
	else
	$sql = mysqli_query ($db, "select * from ".$t11." where aid = '$id' order by date asc limit ".$komspp*$page.", ".$komspp."");
	echo "<table>";
	while ($row = mysqli_fetch_array($sql))
	{
		echo"<TR><td>";
		if (!empty($row['email']))echo "<a href=\"mailto:".stripslashes($row['email'])."\">";
		echo"Автор: ".stripslashes($row['author'])."";
		if (!empty($row['email']))echo"</a>";
		echo"&nbsp;&nbsp;&nbsp;".true_date($row['date'])."</td></tR>
        <tr><td>".stripslashes($row['message'])."</td></tr>
        ";
	}
	$sql = mysqli_query ($db, "select id from ".$t11." where aid = '$id'");
	$numpages = ceil (mysqli_num_rows($sql)/$komspp);
	if ($numpages>1)
	{
		echo "<tr><td align=center>";
		$i=0; $k=0;
		if ($page>4)
		echo "&nbsp;[&nbsp;<a href=\"?act=readkomments&id=$id&page=1\">1</a>&nbsp;]&nbsp; ... ";
		while ($i<$numpages)
		{
			$i++;
			if ($k<9 and $page-$i<4)
			{
				$k++;
				if ($i==($page+1))
				echo "&nbsp;".$i."&nbsp;";
				else
				echo "&nbsp;[&nbsp;<a href=\"?act=readkomments&id=$id&page=".$i."\">".$i."</a>&nbsp;]&nbsp;";
			}
			elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
				echo " ... ";$z=1;}
				elseif ($i==$numpages)
				echo "&nbsp;[&nbsp;<a href=\"?act=readkomments&id=$id&page=".$i."\">".$i."</a>&nbsp;]&nbsp;";

		}
		echo "</td></tr>";
	}
	echo "</table>";
}
function articles_komment_form($aid)
{
	global $db, $_COOKIE;
	if (!empty($_COOKIE['ngpe_id']))
	$aname = get_name($_COOKIE['ngpe_id']);
	else $aname="";
	echo "
	<form action=show_articles.php method=post>
	<input type=hidden name=act value=addkomment>
	<input type=hidden name=aid value=\"$aid\">
	<table><tr><td colspan=2>Добавление комментария</td></tr>
	<tr><td>Ваше имя*</td><td>";
	if (empty($aname))
	echo "<input type=text name=aname value=\"No name\"></td></tr>
	<tr><td>E-mail</td><td><input type=text name=email></td></tr>";
	else
	echo "$aname<input type=hidden name=aname value=$aname></td></tr>";
	echo"
	<tr><td>Комментарий*</td><td><textarea name=message></textarea></tr></tr>
	<tr><td colspan=2 align=center><input type=submit name=add value=Добавить></td></tr></table></form>
	";
}
function read_kom($aid, $page)
{
	global $db, $t5;
	if (empty($page))
	$page=0;
	else
	$page--;
	$sql = mysqli_query ($db, "select title from ".$t5." where id = '$aid' limit 1");
	$row = mysqli_fetch_array($sql);
	echo "Комментари к статье <a href=\"?act=read&aid=$aid\">".$row['title']."</a><BR>";
	articles_komment_table($aid, $page);
}
function add_kom($aid, $aname, $message, $email)
{
	global $db, $_COOKIE, $t1, $t11, $t5;
	$err = array(
	1=>"Имя $aname уже зарегистрировано в базе. Выберите другое.<br>",
	2=>"Учимся говорить красиво! Комментарий не добавлен<br>",
	3=>"Нельзя писать сообщения чаше чем раз в ".conf("flud_time")." секунд<BR>",
	4=>"Поля, помеченные *, обязательны для заполнения<BR>",
	5=>"Длина поля Имя должна быть от ".conf("name_min")." до ".conf("name_max")." символов<BR>",
	6=>"Не выбрана статья",
	7=>"Длина поля Комментария должна быть от ".conf("komments_min")." до ".conf("komments_max")." символов<BR>",
	8=>"Неправильно введён e-mail адрес",
	9=>"Нет такой статьи",
	);
	if (empty($aid))
	$errkod=6;
	else
	{
		$sql = mysqli_query ($db, "select id from ".$t5." where id = '$aid' limit 1");
		if (mysqli_num_rows($sql)<1)
		$errkod=9;
		else
		{
			if (empty($aname) or empty($message))
			$errkod = 4;
			else
			{
				if (!empty($_COOKIE['ngpe_id']))
				{
					$sql = mysqli_query ($db, "select name from ".$t1." where id = '".$_COOKIE['ngpe_id']."' limit 1");
					$row=  mysqli_fetch_array($sql);
					if (empty($row['name']))
					{
						if (check_author($aname))
						$errkod = 1;
					}
				}
				else
				{
					if (check_author($aname))
					$errkod = 1;
				}

				$speech = spech_filter($message);
				if ($speech[1]=='2')
				$errkod=2;
				$message = filter($message);
				$aname = filter($aname);
				$email = filter($email);
				if (!flud_check($aname, $t11))
				$errkod = 3;
				if (!check_length($message, "komments"))
				{
					$errkod = 7;
					$errstring = "комментария";
					$min = conf("komments_min");
					$max = conf("komments_max");
				}
				if (!check_length($aname, "name"))
				{
					$errkod = 5;
					$errstring = "имени";
					$min = conf("name_min");
					$max = conf("name_max");
				}
				if (!empty($email))
				{
					if (!ereg("@", $email) or !ereg(".", $email) or !check_length($email, "email"))
					$errkod = 8;
				}
			}
		}
		if ($errkod)
		{
			head();
			echo $err[$errkod];
		}
		else
		{
			$sql = mysqli_query($db, "insert into ".$t11." (aid, author, message, date, email) values ('$aid', '$aname', '$message', ".time().", '$email');");
			if ($sql)
			return 1;
			else
			echo mysql_error();
		}
	}
}
function check_vote($id)
{
	global $db, $_COOKIE, $t5;
	if (!empty($_COOKIE['ngpe_id']))
	{
		$row = mysqli_fetch_array(mysqli_query($db, "select rateids from ".$t5." where id = '$id' limit 1"));
		$ids = explode (";", $row['rateids']);
		foreach ($ids as $uid)
		{
			if ($uid==$_COOKIE['ngpe_id'])
			return 0;
		}
	}
	if (!empty($_COOKIE["ngpe_art_vote_$id"]) and $_COOKIE["ngpe_art_vote_$id"]=="1")
	return 0;
	$ip = getip();
	$row = mysqli_fetch_array(mysqli_query($db, "select rateip from ".$t5." where id = '$id' limit 1"));
	$ips = explode(";", $row['rateip']);
	foreach ($ips as $uip)
	{
		if ($ip==$uip)
		return 0;
	}
	return 1;

}
function vote_panel ($id)
{
	if (check_vote($id))
	echo "
		<form action=show_articles.php>
			<input type=hidden name=id value=$id>
		<input type=hidden name=act value=vote>
	<div id=rate><B>Оцените эту статью</B>:<input class=rateradio  name=voterate type=radio value=\"1\">1<input class=rateradio type=radio  name=voterate value=\"2\">2<input class=rateradio  name=voterate type=radio value=\"3\">3<input class=rateradio  name=voterate type=radio value=\"4\">4<input class=rateradio name=voterate  type=radio value=\"5\">5<input class=rateradio type=radio  name=voterate value=\"6\">6<input class=rateradio type=radio  name=voterate value=\"7\">7<input class=rateradio type=radio  name=voterate value=\"8\">8<input class=rateradio type=radio  name=voterate value=\"9\">9<input class=rateradio type=radio  name=voterate value=\"10\">10&nbsp;&nbsp;&nbsp;<input type=submit class=vtbtn name=\"vote\" value=\"Оценить\"></div></form>
		";
}
function vote($id, $rate)
{
	$err = array(
	1=>"Вы уже голосовали за данную статью",
	2=>"Данной статьи не найдено в базе",
	3=>"Ошибка в SQL запросе",
	);

	if (!check_vote($id))
	$errkod = 1;
	else
	{
		global $db, $t5, $_COOKIE;
		$sql = mysqli_query ($db, "select votenum, voterate from ".$t5." where id = '".$id."' limit 1");
		if (mysqli_num_rows($sql)==0)
		$errkod = 2;
		else
		{
			$row = mysqli_fetch_array($sql);
			$ratef = sprintf("%.2f", ($row['voterate']+$rate)/($row['votenum']+1));
			$sql = mysqli_query ($db, "update ".$t5." set votenum=votenum+1, voterate=voterate+'$rate', rate = '$ratef' where id = '".$id."' limit 1");
			if (!$sql)
			$errkod = 3;
			else
			{
				if (!emptY($_COOKIE['ngpe_id']))
				mysqli_query ($db, "update ".$t5." set rateids=CONCAT(rateids, '".$_COOKIE['ngpe_id'].";') where id = '$id' limit 1");
				$ip = getip();
				mysqli_query ($db, "update ".$t5." set rateip=CONCAT(rateip, '".$ip.";') where id = '$id' limit 1");
			}
		}
	}
	if (!empty($errkod))
	{
		head();
		echo $err[$errkod];
	}
	else
	return 1;

}
?>
