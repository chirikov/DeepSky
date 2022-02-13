</td>
   <td style="width: 250px; vertical-align: top">
    <table style="width: 100%" cellspacing=0 cellpadding=0>
     <tr>
      <td class=rhd><h1>А знаете ...</h1></td>
     </tr>
     <tr>
      <td class=rcol>
	<?
	global $db, $t14;
	$sql = mysqli_query ($db, "select id from ".$t14." where 1");
	if(mysqli_num_rows($sql) > 0) {
	$id=array();
	while ($row = mysqli_fetch_array($sql))
	{
		$id[] = $row["id"];
	}
	srand((float)microtime() * 1000000);
	shuffle($id);
	$sql = mysqli_query ($db, "select tips from ".$t14." where id = '$id[0]'");
	$row = mysqli_fetch_assoc($sql);
	if (!empty($row["tips"]))echo $row["tips"];
	}
	else echo"
	Если вы зарегистрируетесь, вы сможете добавлять статьи и файлы. А со временем вы также сможете добавлять свои голосования и фотографии.
	";?></td>
     </tr>
     <tr>
      <td class=rhd><h1>Опрос</h1></td>
     </tr>
     <tr>
      <td class=rcol>
	  <?
	  function specmainlist()
	  {
	  	global $db, $t12, $t13, $_COOKIE;
	  	$err = array(
	  	1=>"Здесь могло бы быть предложенное вами голосование. Для добавления голосования зарегистрируйтесь.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	  	$sql = mysqli_query ($db, "select id from ".$t12." where active = '1'");
	  	$voteids = array();
	  	while ($row = mysqli_fetch_array($sql))
	  	{
	  		$voteids[] = $row['id'];
	  	}
	  	if (count($voteids)>1)
	  	{
	  		srand((double) microtime() * 10000000);
	  		shuffle($voteids);
	  		foreach ($voteids as $r)
	  		{
	  			if (!speccheck_if_vote($r))
	  			{
	  				specvote_form($r);
	  				$voteshowok=1;
	  				break;
	  			}
	  		}
	  		if ($voteshowok!=1)
	  		$errkod=1;
	  	}
	  	elseif (count($voteids)==1)
	  	{
	  		if (!speccheck_if_vote($voteids[0]))
	  		specvote_form($voteids[0]);
	  		else
	  		$errkod=1;
	  	}
	  	else
	  	{
	  		$errkod=1;
	  	}
	  	if (!empty($errkod))
	  	echo $err[$errkod];
	  }
	  function specvote_form($id)
	  {
	  	global $db, $t12, $t13;
	  	$sql = mysqli_query ($db, "select id, vote, type from ".$t12." where id = '".$id."' limit 1");
	  	$row = mysqli_fetch_array($sql);
	  	$sql = mysqli_query ($db, "select id, ans, numvotes from ".$t13." where vid = '".$id."'");
	  	echo "<form action=vote.php method=post>
				<input type=hidden name=act value=vote>
				<input type=hidden name=vid value=$id><p><B>".$row['vote']."</B><BR>";				
	  	$S = 0;
	  	while ($row2 = mysqli_fetch_array($sql))
	  	{
	  		$S+=$row2["numvotes"];
	  		if ($row["type"]=="1")
	  		echo "<input type=radio name=ans value=".$row2["id"]." class=radio>".$row2["ans"]."<BR>";
	  		else
	  		{
	  			echo "<input type=checkbox name=ans[] value=".$row2["id"].">".$row2["ans"]."<BR>";
	  		}
	  	}
	  	echo "<BR><div style=\"text-align: center\"><input type=submit name=submit value=\"   Голосовать   \"  class=btn></div></form></p>
	<p style=\"text-align: center\"><a href=vote.php?act=showresults&id=".$row["id"].">Результаты</a><br>Голосов: $S</p>
	";
	  }
	  function speccheck_if_vote($id)
	  {
	  	global $db, $_COOKIE, $t12;
	  	if (isset($_COOKIE['cms_vote_label_id_'.$id]) && $_COOKIE['cms_vote_label_id_'.$id]=="1")
	  	return 1;
	  	else
	  	{
	  		$row = mysqli_fetch_array(mysqli_query($db, "select voteips, voteids from ".$t12." where id = '".$id."' limit 1"));
	  		if (!empty($_COOKIE["ngpe_id"]))
	  		{
	  			$ids = explode (";", $row["voteids"]);
	  			foreach ($ids as $uid)
	  			{
	  				if ($uid==$_COOKIE["ngpe_id"])
	  				return 1;
	  			}
	  		}
	  		$ip = getip();
	  		$ips = explode(";", $row["voteips"]);
	  		foreach ($ips as $uip)
	  		{
	  			if ($ip==$uip)
	  			return 1;
	  		}
	  	}
	  	return 0;
	  }
specmainlist();?>       
      </td>
     </tr>
    </table>
   </td>
   <td style="width: 1px"></td>
  </tr>

  <tr><td colspan=2 style="height: 20px">&nbsp;</td></tr>

  <tr>
   <td colspan=4>
    <table style="width: 100%" cellspacing=0 cellpadding=0>

     <tr>
      <td width=120 class=counter><img src=php.png alt="counter"></td>
      <td class=foot_mnu>
       <a href=index.php>Главная</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php>Статьи и материалы</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php?kid=2>Галактики</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php?kid=3>Туманности</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php?kid=4>Скопления</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php?kid=5>Наблюдения</a>&nbsp;&nbsp;&nbsp;
       <a href=show_articles.php?kid=6>Фотографирование</a>&nbsp;&nbsp;&nbsp;
       <a href=calendar_select.php>Небо&nbsp;над&nbsp;головой</a>&nbsp;&nbsp;&nbsp;
       <a href=gal.php>Фотогалерея</a>
      </td>
     </tr>

     <tr>
      <td width=120 class=counter><img src=php4.gif alt="counter"></td>
      <td class=foot_copy>
       <table style="width: 100%" cellspacing=0 cellpadding=0>
        <tr>
         <td style="background: url(img/foot_l_bg.gif) no-repeat left bottom">
          &copy <a href=index.php>DeepSky.DeTalk.ru</a> 2005<br>Все права защищены.<br><br>При использовании материалов данного сайта, ссылка на DeepSky.DeTalk.ru обязательна!
         </td>
         <td align=right>Проект <a href="mailto:karim@detalk.ru">Сахибгареева Карима</a><BR>
								<a href="mailto:sokrat1988@mail.ru">Романа Чирикова</a>	</td>
        </tr>
       </table>
      </td>
     </tr>

    </table>
   </td>
  </tr>

 </table>
</body>
</html>