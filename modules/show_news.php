<?
function  kat_list()
{
	global $t8;
	$sql = mysql_query ("select * from ".$t8." where 1 order by kat");
	echo "Категории новостей:<BR>";
	while ($row = mysql_fetch_array($sql))
	{
		echo "<a href=\"?act=show&kid=$row[id]\">$row[kat]</a><BR>
                &nbsp;&nbsp;&nbsp;&nbsp;$row[about]<BR><BR>";
	}
}
function news_show($kid, $page)
{
	global $t3, $t9, $t8, $t1;
	if (empty($page))
	$page=0;
	else
	$page--;
	$newspp = conf('news_per_page');
	if (empty($kid))
	$sql = mysql_query ("select id, author, title, news_short, kid, date from ".$t3." where status = 'ok' order by date desc limit ".$newspp*$page.", ".$newspp."");
	else
	$sql = mysql_query ("select id, author, title, news_short, kid, date from ".$t3." where status = 'ok' and kid='$kid' order by date desc limit ".$newspp*$page.", ".$newspp."");
	while ($row = mysql_fetch_array($sql))
	{
		$auth = mysql_fetch_array(mysql_query ("select name from ".$t1." where id = '$row[author]'"));
		$kat = mysql_fetch_array(mysql_query ("select kat from ".$t8." where id = '$row[kid]'"));
		echo"                    
		<tr>
	      <td class=date>".true_date($row[date])."</td>
	      <td>
	       <table style=\"width: 100%; height: 100%\" cellspacing=0 cellpadding=0 class=news>
	        <tr>
	         <td class=nhd>$row[title]</td>
	        </tr>
	        <tr>
	         <td class=cont>
	          $row[news_short]
	         </td>
	        </tr>
	        <tr>
	         <td class=hrefs><a href=\"?act=read&id=$row[id]\">Читать новость полностью</a> | Автор: $auth[name]</td>
	        </tr>
	       </table>
	      </td>
	     </tr>		
		";  
	}	
	if (empty($kid))
	$sql = mysql_query ("select id from ".$t3." where status = 'ok'");
	else
	$sql = mysql_query ("select id from ".$t3." where status = 'ok' and kid='$kid'");
	$numpages = ceil (mysql_num_rows($sql)/$newspp);
	if (!empty($kid))
	$add = "act=show&kid=$kid&";
	if ($numpages>1)
	{
		echo "<tr><td class=date></td><td style=\"text-align:center;\" class=hrefs>Страницы: ";
		$i=0; $k=0;
		if ($page>4)
		echo "&nbsp;&nbsp;<a href=\"?".$add."page=1\">1</a>&nbsp;&nbsp; ... ";
		while ($i<$numpages)
		{
			$i++;
			if ($k<9 and $page-$i<4)
			{
				$k++;
				if ($i==($page+1))
				echo "&nbsp;<b>[".$i."]</b>&nbsp;";
				else
				echo "&nbsp;&nbsp;<a href=\"?".$add."page=".$i."\">".$i."</a>&nbsp;&nbsp;";
			}
			elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
				echo " ... ";$z=1;}
				elseif ($i==$numpages)
				echo "&nbsp;&nbsp;<a href=\"?".$add."page=".$i."\">".$i."</a>&nbsp;&nbsp;";

		}
		echo "</td></tr>";
	}

}
function news_komment_table($id, $page)
{
	global $t10;
	$komspp = conf("news_komments");
	if (conf("news_komments_order")=="desc")
	$sql = mysql_query ("select * from ".$t10." where nid = '$id' order by date desc limit ".$komspp*$page.", ".$komspp."");
	else
	$sql = mysql_query ("select * from ".$t10." where nid = '$id' order by date asc limit ".$komspp*$page.", ".$komspp."");
	echo "<table>";
	while ($row = mysql_fetch_array($sql))
	{
		echo"<TR><td>";
		if (!empty($row[email]))echo "<a href=\"mailto:".stripslashes($row[email])."\">";
		echo"Автор: ".stripslashes($row[author])."";
		if (!empty($row[email]))echo"</a>";
		echo"&nbsp;&nbsp;&nbsp;".true_date($row[date])."</td></tR>
        <tr><td>".stripslashes($row[message])."</td></tr>
        ";
	}
	$sql = mysql_query ("select id from ".$t10." where nid = '$id'");
	$numpages = ceil (mysql_num_rows($sql)/$komspp);
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
function news_komment_form($nid)
{
	global $_COOKIE;
	if (!empty($_COOKIE[ngpe_id]))
	$aname = get_name($_COOKIE[ngpe_id]);
	else $aname="";
	echo "
	<form action=show_news.php method=post>
	<input type=hidden name=act value=addkomment>
	<input type=hidden name=nid value=\"$nid\">
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
function readn($id)
{
	global  $t3,$t8, $t1, $t10;
	$sql = mysql_query ("select news_full, author, title, date from ".$t3." where id = '$id'");
	$row = mysql_Fetch_array($sql);
	$auth = mysql_fetch_array(mysql_query ("select name from ".$t1." where id = '$row[author]'"));
     echo '
     <tr>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0>
        <tr>
         <td class=cont align=center>
          <h2>'.$row[title].'</h2>
           <p>'.$row[news_full].'</p>
          <div class=author><B>Разместил</B>: '.$auth[name].' '.true_date($row[date]).' </div>
         </td>
        </tr>
       </table>
      </td>
     </tr>
     </table>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><a href=#><h1>Фотогалерея</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние фотографии</td>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0 class=photo>
        <tr>';
     global $t16;
     $sqlpgal = mysql_query ("select id, kid, photo, url from ".$t16." where 1 order by id desc limit 3");
	 while ($rowpgal = mysql_fetch_array($sqlpgal))
	 {
		echo "<td class=cont>
          <a href=showpic.php?pid=$rowpgal[id]><img width=147 src=\"".conf('siteurl')."photos/small/$rowpgal[url]\" alt=\"$rowpgal[photo]\"></a><br>
          $rowpgal[photo]
         </td>";
	 }
     echo'
         
        </tr>
       </table>
      </td>
     </tr>
    </table>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     <tr>
      <td colspan=2 class=hd><a href=#><h1>Статьи и материалы</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние статьи</td>
      <td class=cont>
       ';
     
        global $t5;   
      $sqlartl = mysql_query ("select title, id, about from ".$t5." where status = 'ok' order by date desc limit 5");
           while ($rowartl = mysql_fetch_array($sqlartl))
           {
           echo "<a href=show_articles.php?act=read&aid=$rowartl[id]>$rowartl[title]</a><br>
           $rowartl[about]<br><br>";
           }          
      	   
     echo'
      </td>
     </tr>';
     	
	if (conf('news_kom_switch')=='on')
	{
		$sql = mysql_query ("select id from ".$t10." where nid = '$id' limit 1");
		news_komment_form($id);
		if (mysql_num_rows($sql)>0)
		{
			news_komment_table($id, 0);
		}
	}
}
function read_kom($nid, $page)
{
	global $t10, $t9, $t3;
	if (empty($page))
	$page=0;
	else
	$page--;
	$sql = mysql_query ("select title from ".$t3." where id = '$nid' limit 1");
	$row = mysql_fetch_Array($sql);
	echo "Комментари к новости <a href=\"?act=read&id=$nid\">$row[title]</a><BR>";

	news_komment_table($nid, $page);
}
function add_kom($nid, $aname, $message, $email)
{
	global $_COOKIE, $t1, $t10;
	$err = array(
	1=>"Имя $aname уже зарегистрировано в базе. Выберите другое.<br>",
	2=>"Учимся говорить красиво! Комментарий не добавлен<br>",
	3=>"Нельзя писать сообщения чаше чем раз в ".conf("flud_time")." секунд<BR>",
	4=>"Поля, помеченные *, обязательны для заполнения<BR>",
	5=>"Длина поля Имя должна быть от ".conf("name_min")." до ".conf("name_max")." символов<BR>",
	6=>"Не выбрана новость",
	7=>"Длина поля Комментария должна быть от ".conf("komments_min")." до ".conf("komments_max")." символов<BR>",
	8=>"Неправильно введён e-mail адрес",
	);
	if (empty($nid))
	$errkod=6;
	else
	{
		if (empty($aname) or empty($message))
		$errkod = 4;
		else
		{
			if (!empty($_COOKIE[ngpe_id]))
			{
				$sql = mysql_query ("select name from ".$t1." where id = '$_COOKIE[ngpe_id]' limit 1");
				$row=  mysql_fetch_array($sql);
				if (empty($row[name]))
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
			$email = filter($email);
			$aname = filter($aname);
			if (!flud_check($aname, $t10))
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
		if ($errkod)
		{
			head();
			echo $err[$errkod];
		}
		else
		{
			$sql = mysql_query("insert into ".$t10." (nid, author, message, date, email) values ('$nid', '$aname', '$message', ".time().", '$email');");
			if ($sql)
			return 1;
			else
			echo mysql_error();
		}
	}
}
?>
