<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

include_once("inc/config.php");

if(substr($day, 0, 1) == "0") $day = substr($day, 1);
if(strlen($month)<2) $month = "0".$month;

$sql = mysql_query("select phase from mooneph where date = '".$day.$month."'");
$phase = mysql_result($sql, 0, 'phase');
$rast = 0;
if($phase != 0 && $phase != 100) {
	$maxd = date("t", mktime(0,0,1,$month,1));
	if($day>=$maxd) {$day2 = 1; $month2 = $month+1; if($month2>12) $month2 = "01"; if(strlen($month2)<2) $month2 = "0".$month2;}
	else {$day2 = $day+1; $month2=$month;}
	$sql2 = mysql_query("select phase from mooneph where date = '".$day2.$month2."'");
	$phase2 = mysql_result($sql2, 0, 'phase');
	if($phase2>$phase) $rast = 1;
	elseif($phase2<$phase) $rast = -1;
}

$w=100;
$h=$w;

$im = imagecreatefromjpeg("img/moon.jpg");
$black = imagecolorallocate($im, 0,0,0);
$gray = imagecolorallocate($im, 81, 81, 81);
if($rast==1 && $phase<=50) {
	imagearc($im, $w/2, $h/2, 2*($w-50-$w*$phase/100), $h, 270, 90, $black);
	imagefilltoborder($im, 1, 1, $black, $black);
}
elseif($rast==1 && $phase>50) {
	imagearc($im, $w/2, $h/2, 2*($w*($phase-50)/100), $h, 90, 270, $black);
	imagefilltoborder($im, 1, 1, $black, $black);
}
elseif($rast==-1 && $phase>50) {
	imagearc($im, $w/2, $h/2, 2*($w-50-$w*(100-$phase)/100), $h, 270, 90, $black);
	imagefilltoborder($im, $w-2, 1, $black, $black);
}
elseif($rast==-1 && $phase<=50) {
	imagearc($im, $w/2, $h/2, 2*($w*(50-$phase)/100), $h, 90, 270, $black);
	imagefilltoborder($im, $w-2, 1, $black, $black);
}
elseif($rast==0 && $phase == 0) imagefilledarc($im, $w/2, $h/2, $w-2, $h-1, 0, 360, $gray, IMG_ARC_PIE);

header("Content-type: image/png");
imagepng($im);
?>
