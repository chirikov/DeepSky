<?
function mainlist()
{
	global $t12, $t13, $_COOKIE;
	$err = array(
	1=>"����� ����� �� ���� ������������ ���� �����������. ��� ���������� ����������� �����������������.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;");
	$sql = mysql_query ("select id from ".$t12." where active = '1'");
	while ($row = mysql_fetch_array($sql))
	{
		$voteids[] = $row[id];
	}
	if (count($voteids)>1)
	{
		srand((double) microtime() * 10000000);
		$rand_keys = array_rand($voteids, count($voteids));
		foreach ($rand_keys as $r)
		{
			if (!check_if_vote($voteids[$r]))
			{
				vote_form($voteids[$r]);
				$voteshowok=1;
				break;
			}
		}
		if ($voteshowok!=1)
		$errkod=1;
	}
	elseif (count($voteids)==1)
	{
		if (!check_if_vote($voteids[0]))
		vote_form($voteids[0]);
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
function vote_form($id)
{
	global $t12, $t13;
	$sql = mysql_query ("select id, vote, type from ".$t12." where id = '$id' limit 1");
	$row = mysql_fetch_array($sql);	
	$sql = mysql_query ("select id, ans, numvotes from ".$t13." where vid = '$id'");
	echo "<form action=vote.php method=post>
				<input type=hidden name=act value=vote>
				<input type=hidden name=vid value=$id><p><B>".$row[vote]."</B><BR>";				
	while ($row2 = mysql_fetch_array($sql))
	{
		$S+=$row2[numvotes];
		if ($row[type]=="1")
		echo "<input type=radio name=ans value=$row2[id] class=radio>$row2[ans]<BR>";
		else
		{
			echo "<input type=checkbox name=ans[] value=$row2[id]>$row2[ans]<BR>";
		}
	}
	echo "<BR><div style=\"text-align: center\"><input type=submit name=submit value=\"     ����������     \"  class=btn></div></form></p>
	<p style=\"text-align: center\"><a href=vote.php?act=showresults&id=$row[id] class=without>����������</a><br>�������: $S</p>
	";
}
function check_if_vote($id)
{
	global $_COOKIE, $t12;
	if ($_COOKIE['cms_vote_label_id_$id']=="1")
	return 1;
	else
	{
		$row = mysql_fetch_array(mysql_query("select voteips, voteids from ".$t12." where id = '$id' limit 1"));
		if (!empty($_COOKIE[ngpe_id]))
		{
			$ids = explode (";", $row[voteids]);
			foreach ($ids as $uid)
			{
				if ($uid==$_COOKIE[ngpe_id])
				return 1;
			}
		}
		$ip = getip();
		$ips = explode(";", $row[voteips]);
		foreach ($ips as $uip)
		{
			if ($ip==$uip)
			return 1;
		}
	}
	return 0;
}
function addpart()
{
	global $_COOKIE, $t1;
	$err = array(1=>"� ��� ��� ������� � ���� ����� �����.");
	if (empty($_COOKIE[ngpe_id]) or !check_pravo($_COOKIE[ngpe_id], "votesadd"))
	echo $err[1];
	else
	{
		$sql = mysql_query("select id from ".$t1." where id = '$_COOKIE[ngpe_id]' limit 1");
		if (mysql_num_rows($sql)!="1")
		echo $err[1];
		else
		{
			vote_add_form();
		}
	}
}
function vote_add_form()
{
	echo "
	<form action=vote.php method=post>
	<input type=hidden name=act value=addvote>
	<table class='articles' style=\"width: 100%; \">
	<tr><td class=cont>���� �����������</td><td><input type=text name=theme></td></tr>
	<tr><td colspan=2 class=cont style='text-align: left;'>
	<input type=radio name=type value=1 checked>���������� ����� ������ �� ���� ������� ������<BR>
	<input type=radio name=type value=2>���������� ����� �� ����� ���-�� ��������� ������<Br></td></tr>
	<tr><td class=cont>������� 1</td><td><input type=text name=ans1></td></tr>
	<tr><td class=cont>������� 2</td><td><input type=text name=ans2></td></tr>
	<tr><td class=cont>������� 3</td><td><input type=text name=ans3></td></tr>
	<tr><td class=cont>������� 4</td><td><input type=text name=ans4></td></tr>
	<tr><td class=cont>������� 5</td><td><input type=text name=ans5></td></tr>
	<tr><td class=cont>������� 6</td><td><input type=text name=ans6></td></tr>
	<tr><td class=cont>������� 7</td><td><input type=text name=ans7></td></tr>
	<tr><td class=cont>������� 8</td><td><input type=text name=ans8></td></tr>
	<tr><td class=cont>������� 9</td><td><input type=text name=ans9></td></tr>
	<tr><td class=cont>������� 10</td><td><input type=text name=ans10></td></tr>
	<tr><td colspan=2 align=center class=cont><input class=btn type=submit name=submit value=��������></td></tR>
	</table>
	</form>
	";
}
function addvote($theme, $type, $ans1, $ans2, $ans3, $ans4, $ans5, $ans6, $ans7, $ans8, $ans9, $ans10)
{
	global $t1, $t12, $t13, $_COOKIE;
	$err = array(1=>"� ��� ��� ������� � ���� ����� �����.",
	2=>"��������� �� ��� ����, ���������� *",
	3=>"������ ��� ��������� ����������� � ��");
	if (empty($_COOKIE[ngpe_id]) or !check_pravo($_COOKIE[ngpe_id], "votesadd"))
	$errkod=1;
	else
	{
		$sql = mysql_query("select id from ".$t1." where id = '$_COOKIE[ngpe_id]' limit 1");
		if (mysql_num_rows($sql)!="1")
		$errkod=1;
		else
		{
			if (empty($theme) or empty($type) or empty($ans1) or empty($ans2))
			$errkod=2;
			else
			{
				$active = 0;
				if (check_pravo($_COOKIE[ngpe_id], "votesadd"))
				$active=1;
				$sql = mysql_query ("insert into ".$t12." (vote, type, active) values ('$theme', '$type', '$active');");
				$id = mysql_insert_id();
				if (empty($id))
				$errkod=3;
				else
				{
					if (!empty($ans1))
					$sql = mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans1');");
					if (!empty($ans2))
					$sql2 = mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans2');");
					if (!empty($ans3))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans3');");
					if (!empty($ans4))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans4');");
					if (!empty($ans5))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans5');");
					if (!empty($ans6))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans6');");
					if (!empty($ans7))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans7');");
					if (!empty($ans8))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans8');");
					if (!empty($ans9))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans9');");
					if (!empty($ans10))
					mysql_query ("insert into ".$t13." (vid, ans) values ('$id', '$ans10');");
					if (!$sql or !$sql2)
					$errkod=3;
				}
			}
		}
	}
	if (!empty($errkod))
	echo $err[$errkod];
	else
	echo "����������� ���������!<BR><a href=\"?act=add\" class=without>�������� ���</a><BR><a class=without href=login.php>��������� � ������ ����������</a>";
}
function vote($vid, $ans)
{
	$err = array(
	1=>"�� ������� �����������",
	2=>"�� ��� ���������� � ���� �����������");
	if (empty($vid) or empty($ans))
	$errkod=1;
	else
	{
		global $t12, $t13;
		if (!check_if_vote($vid))
		{
			if (is_array($ans))
			{
				foreach ($ans as $a)
				{
					mysql_query ("update ".$t13." set numvotes=numvotes+1 where id = '$a'");
				}
			}
			else
			mysql_query ("update ".$t13." set numvotes=numvotes+1 where id = '$ans'");
		}
		else 	$errkod=2;
	}
	if (!empty($errkod))
	{
		head();
		echo $err[$errkod];
	}
	else return 1;

}
function showresult($id)
{
	global $t12, $t13;
	$err = array(1=>"�� ���������� ����� �����������...");
	$sql = mysql_query ("select vote from ".$t12." where id = '$id' limit 1");
	if (mysql_num_rows($sql)!="1")
	$errkod=1;
	else
	{
		$row = mysql_fetch_array($sql);
		$sql = mysql_query("select ans, numvotes from ".$t13." where vid  = '$id'");
				while ($row2 = mysql_fetch_array($sql))
		{
			$summ +=$row2[numvotes];
		}		
		echo "<table class=articles><TR><td colspan=2 align=center class=cont><b>$row[vote]</b> ($summ �������)</td></tR>";
		$sql = mysql_query("select ans, numvotes from ".$t13." where vid  = '$id'");
		while ($row = mysql_fetch_array($sql))
		{
			echo "<TR><TD class=cont style='padding-bottom: 0px'>$row[ans] ($row[numvotes] �������, ";if (!empty($summ))echo round($row[numvotes]/$summ*100); else echo "0";echo "%)</td><TD class=cont align=left style=\"text-align:left; padding-bottom: 0px;\"><img src=img/l.JPG border=0 alt=''><img src=img/in.JPG border=0 alt='' height=11 width=";if (!empty($summ))echo round($row[numvotes]/$summ*100); else echo "0";echo "><img src=img/r.JPG border=0></td></tr>";
		}
		echo "</table>";
	}
}
function showall()
{
	global $t12;
	$sql = mysql_query ("select id, vote from ".$t12." where active='1' order by id desc");
	while ($row = mysql_Fetch_array($sql))
	{
		echo "<a class=without href=\"?act=voteinfo&id=$row[id]\">$row[vote]</a><BR>";
	}
}
function active_form()
{
	global $t12, $_COOKIE;
	if (!empty($_COOKIE[ngpe_id]))
	{
		if (check_pravo($_COOKIE[ngpe_id], "votesedit"))
		{
			$sql = mysql_query ("select id, vote from ".$t12." where active='1' order by id desc");
			echo "����� ����������� ����������� � �����, �������� �� ���� ������<BR><br>";
			while ($row = mysql_fetch_array($sql))
				echo "<a class=without href=\"?act=a2p&id=$row[id]\">$row[vote]</a><BR>";
			echo "<BR><a class=without href=\"?act=showarchive\">����������� �����</a>";
		}
		else 	
			$err = "� ��� ��� ����� �� �������������� �����������.";		
	}
	else 
		$err = "�� �� ������������.";
	echo $err;
}
function a2p($id)
{
	global $t12, $_COOKIE;
	if (!empty($_COOKIE[ngpe_id]))
	{
		if (check_pravo($_COOKIE[ngpe_id], "votesedit"))
		{
			if (empty($id))
				$err = "�� ������� �����������";
			else 	
				mysql_query ("update ".$t12." set active='0' where id = '$id' limit 1");
				echo "����������� ���������� � �����. <a class=without href='vote.php?act=moder'>�����</a>";
		}
		else 	
			$err = "� ��� ��� ����� �� �������������� �����������.";		
	}	
	else 
		$err = "������ �������� ������� �����������";
	echo $err;
}
function showarchive()
{
	global $t12, $_COOKIE;
	if (check_pravo($_COOKIE[ngpe_id], "votesedit"))
		{
			$sql = mysql_query ("select id, vote from ".$t12." where active='0' order by id desc");
			echo "����� ����������� ����������� �� ������ � ��������, �������� �� ���� ������<BR><br>";
			while ($row = mysql_fetch_array($sql))
				echo "<a class=without href=\"?act=p2a&id=$row[id]\">$row[vote]</a><BR>";			
		}
	else 
		echo "�� �� ������ ���� ������� � ������ ����� �����";
}
function p2a($id)
{
	global $t12, $_COOKIE;
	if (!empty($_COOKIE[ngpe_id]))
	{
		if (check_pravo($_COOKIE[ngpe_id], "votesedit"))
		{
			if (empty($id))
				$err = "�� ������� �����������";
			else 	
				mysql_query ("update ".$t12." set active='1' where id = '$id' limit 1");
				echo "����������� ���������� � ��������. <a class=without href='vote.php?act=moder'>�����</a>";
		}
		else 	
			$err = "� ��� ��� ����� �� �������������� �����������.";		
	}	
	else 
		$err = "������ �������� ������� �����������";
	echo $err;
}
?>
