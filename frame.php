<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
<meta name="description" content="DeepSky Сайт по объектам дальнего космоса. Галактики, туманности, скопления, фотогалерея.">
<meta http-equiv="content-type" content="text/html; charset=windows-1251">
<meta name="author" content="&copy; Карим, Рома 2005">
<meta name="copyright" content="&copy; DeepSky. 2005">
	<title>Небо</title>
</head>
<body marginheight="0" marginwidth="0" rightmargin="0" leftmargin="0" topmargin="0">
<div id="div2" align="center"><font color="#000000"><b>Построение изображения...</b></font></div>
<?php
include_once("inc/config.php");

function mysql_result($q, $i, $f) {
	$r = mysqli_fetch_assoc($q);
	return $r[$f];
}

error_reporting(E_ALL);

$vars = array("shirota", "w", "day", "month", "hour", "minute", "no_stars", "no_const", "no_names", "no_gx", "no_nb", "no_gb", "no_oc", "no_cpn", "mag_max_stars", "mag_max_obj", "lang", "fsize", "timezone");
foreach($vars as $var) {
	if(isset($_GET[$var])) $$var = $_GET[$var];
}

$w = $_GET['w'];
$shirota = $_GET['shirota'];
$timezone = $_GET['timezone'];

function hor_coor($dec, $ra, $delta, $time, $shirota) {
	$atel = $ra - $delta/3600 - $time;
	if($atel<0) $atel+=24;
	$tugol = (12-$atel)*15;
	
	$height = 90-rad2deg(acos(sin(deg2rad($dec))*sin(deg2rad($shirota)) + cos(deg2rad($dec))*cos(deg2rad($shirota))*cos(deg2rad($tugol))));
	$azim = rad2deg(asin(cos(deg2rad($dec))*sin(deg2rad($tugol))/sin(deg2rad(90-$height))));
	
	if(round(sin(deg2rad(90-$height))*cos(deg2rad($azim)), 5) != round(sin(deg2rad($shirota))*cos(deg2rad($dec))*cos(deg2rad($tugol)) - cos(deg2rad($shirota))*sin(deg2rad($dec)), 5)) {
		if($azim<90) $azim = 180 - $azim;
		elseif($azim>270) $azim = 270 - $azim + 270;
	}
	
	$azim+=180;
	if($azim>=360) $azim-=360;
	if($azim<0) $azim+=360;
	$hor_coord = array('height' => $height, 'azim' => $azim);
	
	return $hor_coord;
}

function draw($w, $h, $azim, $height) {
	$mash = $w/180; // масштаб - сколько в градусе пикселей.
	
	$dx = $height*$mash*sin(deg2rad($azim));
	$dy = $mash*$height*cos(deg2rad($azim));
	$x = $w/2+15 - sin(deg2rad($azim))*$w/2;
	$y = $h/2+15 - cos(deg2rad($azim))*$h/2;
	
	$tx = $x+$dx;
	$ty = $y+$dy;
	$cc = array('x'=>round($tx), 'y'=>round($ty));
	
	return $cc;
}

function getmoon($day, $month) {
	global $db;
	$q = mysqli_query($db, "select ra, deca from mooneph where date = ".$day.$month);
	$r = mysqli_fetch_assoc($q);
	$ram = $r['ra'];
	$height = $r['deca'];
	
	$moon = array('ra'=>$ram, 'dec'=>$height);
	
	return $moon;
}

if(!isset($day)) $day = date("j");
if(!isset($month)) $month = date("m");
if(!isset($hour)) $hour = 0;
if(!isset($minute)) $minute = 0;
if(!isset($lang)) $lang = "eng";
if(!isset($fsize)) $fsize = 10;
if(!isset($mag_max_stars)) $mag_max_stars = 4.2;
if(!isset($mag_max_obj)) $mag_max_obj = 9;

$raz0 = date("Z")/3600;
$raz2 = $timezone+1;
if(date("I") == 1) $raz2+=1;
$raz = $raz2-$raz0;

$dopstr = "";
$dopstr.="&day=".$day;
$dopstr.="&month=".$month;
$dopstr.="&hour=".$hour;
$dopstr.="&minute=".$minute;
if(isset($no_stars)) $dopstr.="&no_stars=".$no_stars;
if(isset($no_const)) $dopstr.="&no_const=".$no_const;
if(isset($no_names)) $dopstr.="&no_names=".$no_names;
if(isset($no_gx)) $dopstr.="&no_gx=".$no_gx;
if(isset($no_nb)) $dopstr.="&no_nb=".$no_nb;
if(isset($no_gb)) $dopstr.="&no_gb=".$no_gb;
if(isset($no_oc)) $dopstr.="&no_oc=".$no_oc;
if(isset($no_cpn)) $dopstr.="&no_cpn=".$no_cpn;
$dopstr.="&mag_max_stars=".$mag_max_stars;
$dopstr.="&mag_max_obj=".$mag_max_obj;
$dopstr.="&lang=".$lang;
$dopstr.="&fsize=".$fsize;

////// Определение поправки и времени.
$n0 = 115; // номер дня 26 апреля невисокосного года.
$n = date("z", mktime(12,0,0,$month,$day,2005));

if($n>=$n0) $x = $n-$n0;
else $x = 365-$n0+$n;

$res1 = mysqli_query($db, "select realdelta from astro where day = ".$x);
$delta = mysqli_fetch_assoc($res1)['realdelta'];//mysql_result($res1, 0, 'realdelta');

$time = $hour+$minute/60; // в часах
$time+=$raz;
if($time>=24) $time-=24;
if($time<0) $time+=24;
if($time > 12) $time = -1*(24-$time);
//////


////// selecting good objects 
$good_obj = array();
$query2 = mysqli_query($db, "select id, ra, deca, type, mag from objects limit 5000");
$num = mysqli_num_rows($query2);
//for ($i=1;$i<=$num;$i++) {
$i = 1;
while($r = mysqli_fetch_assoc($query2)) {
	//$mag = mysql_result($query2, $i-1, 'mag');
	$mag = $r['mag'];
	if($mag < $mag_max_obj && $mag != 0) {
		$ra = $r['ra'];//mysql_result($query2, $i-1, 'ra');
		$dec = $r['deca'];//mysql_result($query2, $i-1, 'deca');
		$type = $r['type'];//mysql_result($query2, $i-1, 'type');
		if(isset($no_gx) && $type == 'Gx') continue;
		if(isset($no_nb) && $type == 'Nb') continue;
		if(isset($no_oc) && $type == 'OC') continue;
		if(isset($no_gb) && $type == 'Gb') continue;
		if(isset($no_nb) && $type == 'Pl') continue; // neb = pl. neb!
		if(isset($no_cpn) && $type == 'C+N') continue;
		if(isset($no_kt) && $type == 'Kt') continue;
		//print "@@".$dec;
		$ar = hor_coor($dec, $ra, $delta, $time, $shirota);
		if($ar['height'] > 10) {
			$good_obj[] = array("id" => $r['id'],//mysql_result($query2, $i-1, 'id'),
								"height" => $ar['height'],
								"azim" => $ar['azim'],
								"type" => $type);
		}
	}
	$i++;
}
//////

////// selecting good stars
if(!isset($no_stars)) {
$good_stars = array();
$query2 = mysqli_query($db, "select * from stars limit 10000");
//$num = mysqli_num_rows($query2);
while($r = mysqli_fetch_assoc($query2)) {
	if($r['mag'] < $mag_max_stars) {
		$ra = $r['ra'];
		$dec = $r['deca'];
		$ar = hor_coor($dec, $ra, $delta, $time, $shirota);
		if($ar['height'] > 3) {
			$good_stars[] = array("id" => $r['id'],
								  "height" => $ar['height'],
								  "azim" => $ar['azim']);
		}
	}
}
}
//////

if(!isset($w)) $w = 500;// whole image
//$h = 400;//
$h = $w;

$xx = array();
$yy = array();
$ids = array();

$query_empty = mysqli_query($db, "delete from fordraw where 1");

foreach($good_obj as $good) {
	$ar = draw($w-30, $h-30, $good['azim'], $good['height']);
	mysqli_query($db, "insert into fordraw values('".$good['id']."', '".$good['type']."', '".$ar['x']."', '".$ar['y']."')");
	$xx[] = $ar['x'];
	$yy[] = $ar['y'];
	$ids[] = $good['id'];
}

if(!isset($no_stars)) {
foreach($good_stars as $good) {
	$ar = draw($w-30, $h-30, $good['azim'], $good['height']);
	mysqli_query($db, "insert into fordraw values('".$good['id']."', 'star', '".$ar['x']."', '".$ar['y']."')");
}
}

//////// moon
$moon = getmoon($day, $month);
$moonh = hor_coor($moon['dec'], $moon['ra'], $delta, $time, $shirota);
$ar = draw($w-30, $h-30, $moonh['azim'], $moonh['height']);
mysqli_query($db, "insert into fordraw values('moon', 'moon', '".$ar['x']."', '".$ar['y']."')");
////////

////// constellations

if(!isset($no_const)) {
	$query_empty = mysqli_query($db, "delete from constell_draw where 1");
	$query_empty = mysqli_query($db, "delete from constell_draw_names where 1");
	if($lang == "rus" && !isset($no_names)) $qc = mysqli_query($db, "select skeleton, rusname from constell where 1");
	elseif(!isset($no_names)) $qc = mysqli_query($db, "select skeleton, name from constell where 1");
	else $qc = mysqli_query($db, "select skeleton from constell where 1");
	//for($i=0; $i<mysqli_num_rows($qc); $i++) {
	while($r = mysqli_fetch_assoc($qc)) {
		$skeleton = explode("@", $r['skeleton']);
		$ii = 0;
		while($ii<count($skeleton)-1) {
			if($skeleton[$ii+1] != "") {
				$qt1 = mysqli_query($db, "select deca, ra from stars where id = ".$skeleton[$ii]);
				$rt1 = mysqli_fetch_assoc($qt1);
				$ar10 = hor_coor($rt1['deca'], $rt1['ra'], $delta, $time, $shirota);
				if($ar10['height']>0) {
				$ar1 = draw($w-30, $h-30, $ar10['azim'], $ar10['height']);
				$qt2 = mysqli_query($db, "select deca, ra from stars where id = ".$skeleton[$ii+1]);
				$rt2 = mysqli_fetch_assoc($qt2);
				$ar20 = hor_coor($rt2['deca'], $rt2['ra'], $delta, $time, $shirota);
				if($ar20['height']<0) $ar20['height'] = 0.5;
				$ar2 = draw($w-30, $h-30, $ar20['azim'], $ar20['height']);
				mysqli_query($db, "insert into constell_draw values('".$ar1['x']."', '".$ar1['y']."', '".$ar2['x']."', '".$ar2['y']."')");
				// names
				if(!isset($no_names))
				if($ii == round(count($skeleton)/2) or $ii+1 == round(count($skeleton)/2)) {
				if($lang == "rus") $name = $r['rusname'];
				else $name = $r['name'];
				mysqli_query($db, "insert into constell_draw_names values('".$name."', '".$ar1['x']."', '".$ar1['y']."')");
				}
				//
				}
			}
			$ii+=2;
		}
	}
}

?>
<img style='position: absolute; top: 100px;' onload='document.getElementById("div2").style.display = "none"; this.style.top=0;' 
<?php print "id='img_main' src='sky_draw.php?w=".$w."&shirota=".$shirota.$dopstr."'" ?>

ondragstart="return false;" 

onmousedown="javascript: 
document.getElementById('dix1').value = window.event.x;
document.getElementById('dix2').value = window.event.y;
" 
onmouseup="javascript: 
if(window.event.x == document.getElementById('dix1').value && window.event.y == document.getElementById('dix2').value) {
	var n = 0;
	var cat = 'NGC';
	var x = window.event.x;
	var y = window.event.y;
	
	while(n != xx.length) {
		if(x <= xx[n]+3 && x >= xx[n]-3 && y <= yy[n]+3 && y >= yy[n]-3) {
			var urls = ngc[n].replace(' ', '@');
			var inp = 'Объект: <a target=_blank href=obj_info.php?obj='+urls+'>' + ngc[n] + '</a>';
			if(aids[n] != undefined) inp += '<br><a class=without href=show_articles.php?act=read&aid='+aids[n]+' target=_blank>Статья об этом объекте</a>';
			if(pids[n] != undefined) inp += '<br><a class=without href=showpic.php?pid='+pids[n]+' target=_blank>Фотография этого объекта</a>';
			parent.document.getElementById('tdt').innerHTML = inp;
			break;
		}
		n++;
	}
} else {
	
	var w = <?= $w ?>;
	if(Math.abs(window.event.x-document.getElementById('dix1').value) > Math.abs(window.event.y-document.getElementById('dix2').value))
	{var d = Math.abs(window.event.x-document.getElementById('dix1').value);}
	else
	{var d = Math.abs(window.event.y-document.getElementById('dix2').value);}
	var w2 = (w*w)/d;
	if(document.getElementById('dix1').value < window.event.x) {var start_x = document.getElementById('dix1').value; var end_x = window.event.x;}
	else {var end_x = document.getElementById('dix1').value; var start_x = window.event.x;}
	if(document.getElementById('dix2').value < window.event.y) {var start_y = document.getElementById('dix2').value; var end_y = window.event.y;}
	else {var end_y = document.getElementById('dix2').value; var start_y = window.event.y;}
	window.open('sektor.php?<?=$_SERVER['QUERY_STRING']?>&w='+w2+'&w1='+w+'&start_x='+start_x+'&start_y='+start_y+'&end_x='+end_x+'&end_y='+end_y);
}
"

onmousemove="
var azim = 0;
var heig = 0;
var x = window.event.x;
var y = window.event.y;
var w = <?= $w ?>;
var mash = (w-30)/180;
var rad = ((w-30)/2)/mash;
if(x<=w/2) {
	if(y<=w/2) {
		azim = Math.round((180 * Math.atan(((w/2)-x)/((w/2)-y)))/3.14159);
		heig = rad - Math.sqrt(Math.pow(w/2-x, 2) + Math.pow(w/2-y, 2))/mash;
	}
	else {
		azim = Math.round(90 + 180 * Math.atan((y-w/2)/(w/2-x))/3.14159);
		heig = rad - Math.sqrt(Math.pow(w/2-x, 2) + Math.pow(y-w/2, 2))/mash;
	}
}
else {
	if(y<=w/2) {
		azim = 360 - Math.round((180 * Math.atan((x-w/2)/(w/2-y)))/3.14159);
		heig = rad - Math.sqrt(Math.pow(x-w/2, 2) + Math.pow(w/2-y, 2))/mash;
	}
	else {
		azim = 180 + Math.round(180 * Math.atan((x-w/2)/(y-w/2))/3.14159);
		heig = rad - Math.sqrt(Math.pow(x-w/2, 2) + Math.pow(y-w/2, 2))/mash;
	}
}
heig = Math.round(heig);
if(heig<0) {heig = 0; azim = 0;}
if(heig==90) azim = 0;
////////// ra & dec
var ra = 0;
var dec = 0;
var azim3;
azim3 = azim+180;
if(azim3>=360) azim3-=360;
var azim2 = 3.14159*(azim3)/180;
var zen = 3.14159*(90-heig)/180;
var shir = 3.14159*<?=$shirota?>/180;
var delta = <?=$delta/3600?>;
var time = <?=$time?>;

dec = Math.asin(Math.sin(shir)*Math.cos(zen) - Math.cos(shir)*Math.sin(zen)*Math.cos(azim2));
tugol = 180*Math.asin(Math.sin(zen)*Math.sin(azim2)/Math.cos(dec))/3.14159;
ra = 12-tugol/15 + delta + time;
if(ra>=24) ra-=24;
var n1 = Math.floor(Math.cos(dec)*Math.cos(3.14159*tugol/180)*100)/100;
var n2 = Math.floor((Math.cos(shir)*Math.cos(zen) + Math.sin(shir)*Math.sin(zen)*Math.cos(azim2))*100)/100;
if(n1 != n2)
{
ra=12-ra+delta*2;
if(ra<0) ra+=24;
if(ra>=24) ra-=24;
if(ra>=24) ra-=24;
}
//////////
parent.document.getElementById('cursor').innerHTML = '<table class=news><tr><td align=right valign=top class=cont>Позиция курсора:&nbsp;</td><td class=cont>Азимут: '+azim+'&deg;, Высота: '+heig+'&deg;<br>Прямое восхождение: '+Math.round(ra*10)/10+' ч., Склонение: '+Math.round(180*dec/3.14159)+'&deg;</td></tr></table>';
"
>
<script language="JavaScript" type="text/javascript">
xx = new Array ();
yy = new Array ();
pids = new Array ();
aids = new Array ();
ngc = new Array();

<?php
for($i=0; $i<count($xx); $i++) {
	print "xx[".$i."] = ".$xx[$i].";";
	print "yy[".$i."] = ".$yy[$i].";";
	$sql1 = mysqli_query($db, "select id from gal where objid = ".$ids[$i]);
	if(mysqli_num_rows($sql1) > 0) print "pids[".$i."] = ".mysql_result($sql1, 0, 'id').";";
	$sql1 = mysqli_query($db, "select id from articles where objid = ".$ids[$i]." and status = 'ok'");
	if(mysqli_num_rows($sql1) > 0) print "aids[".$i."] = ".mysql_result($sql1, 0, 'id').";";
	$qq2 = mysqli_query($db, "select ngc, messier, name from objects where id = ".$ids[$i]);
	$r = mysqli_fetch_assoc($qq2);
				$messier = $r['messier'];
				$ngc = $r['ngc'];
				$name = $r['name'];
				$poyasn = "";
				if($name != "") $poyasn .= $name;
				if($name != "" && $messier != "") $poyasn .= ", ";
				if($messier != "") $poyasn .= "M".$messier;
				if($messier != "" && $ngc != "") $poyasn .= ", ";
				if($ngc != "") {
					if(substr($ngc, 0, 1) == "I") $poyasn .= "IC".substr($ngc, 1);
					else $poyasn .= "NGC".$ngc;
				}
	print "ngc[".$i."] = '".$poyasn."';";
	$poyasn = "";
}
?>
</script>
<div id="dix1" style="display: none;"></div>
<div id="dix2" style="display: none;"></div>
</body>
</html>
