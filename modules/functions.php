<?
function check_pravo ($id, $pr)
{
	global $db, $t1, $t2;
	$sql = mysqli_query ($db, "select pravo from ".$t1." where id = '$id'");
	$row = mysqli_fetch_array($sql);
	$sql = mysqli_query ($db, "select ".$pr." from ".$t2." where pravo = '".$row['pravo']."'");
	$rowp = mysqli_fetch_array($sql);
	if ($rowp[$pr]=='on')
	return 1;
	else
	return 0;
}
function get_name ($id)
{
	global $db, $t1;
	$sql = mysqli_query ($db, "select name from ".$t1." where id = '$id'");
	$row = mysqli_fetch_array($sql);
	return $row['name'];
}
function check_author ($name)
{
	global $db, $t1;
	$sql = mysqli_query ($db, "select id from ".$t1." where name = '$name' limit 1");
	return mysql_num_rows($sql);

}
function true_date ($time)
{
	$date = getdate ($time);
	$tdate =$date['mday'].".".$date['mon'].".".$date['year'];
	return $tdate;
}
function conf ($what)
{
	global $db, $t9;
	$sql = mysqli_query ($db, "select v from ".$t9." where what = '".$what."' limit 1");
	$row = mysqli_fetch_array($sql);
	return $row['v'];
}
function check_img($st)
{
	$r=1;
	if (eregi("<img", $st, $r2))
	{
		$st2 = split (" ", $st);
		foreach ($st2 as $st3)
		{
			if (eregi("src", $st3, $r0))
			{
				if (!eregi ("src=([a-zA-Z0-9]|\-|\_|\.)*\.(jpg|jpeg|gif|png)$|\"|\>$", $st3, $r))
				$r=0;
			}
		}
	}
	return $r;

}
function spech_filter($str)
{
	$str = strtolower($str);
	if (ereg(conf('mat_list'), $str, $a))
	$res=array($a[0], 2);
	else
	$res=array($str, 0);
	return $res;
}
function filter($str)
{
	$str = strip_tags($str);		
	$str = htmlspecialchars($str);
	$str = trim($str);
	$str = ucfirst($str);	
	return $str;
}
function flud_check($name, $table)
{
	global $db, $_COOKIE;
	$tf = conf("flud_time");	
	if ((time() - $_COOKIE['ngpe_last_post'])<$tf)
	return 0;
	else
	{
		if($name!="No name")
		{
			$sql = mysqli_query ($db, "select date from ".$table." where author = '$name' order by date desc limit 1");
			$row = mysqli_fetch_array($sql);			
			if ((time()-$row['date'])<$tf)
			return 0;
			else 
			return 1;
		}
		else 
		return 1;
	}

}
function check_length($str, $pre)
{
	$vt = strlen($str);
	if ($vt>conf($pre."_max") or $vt<conf($pre."_min"))
	return 0;
	else
	return 1;
}
function getip() {
	if(getenv("HTTP_CLIENT_IP"))
	$ip = getenv("HTTP_CLIENT_IP");
	elseif(getenv("HTTP_X_FORWARDED_FOR"))
	$ip = getenv("HTTP_X_FORWARDED_FOR");
	else
	$ip = getenv("REMOTE_ADDR");
	return $ip;
}
?>