<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

function draw2($im, $x, $y, $colors, $type, $mag) {
    if($type == 'star') {
		if($mag<=3) imagesetpixel($im, $x, $y, $colors['star1']);
		if($mag>3) imagesetpixel($im, $x, $y, $colors['star4']);
	}
	elseif($type == 'moon') imagefilledellipse($im, $x, $y, 7, 7, $colors['moon']);
	elseif($type != '') imagefilledellipse($im, $x, $y, 5, 5, $colors[$type]);
}
include_once("inc/config.php");
//$mysql = @mysql_connect('localhost', 'root', 'password');
//@mysql_select_db('deepsky');

/////////////////// drawing ///////////////////

if(!isset($w)) $w = 500;// whole image
//$h = 400;//
$h = $w;

$im = imagecreate($w, $h);

////// colors
$bg = imagecolorallocate($im, 10, 23, 21); //unuseful transparent background
$c1 = imagecolorallocate($im, 0,0,0);
$c2 = imagecolorallocate($im, 255,255,255);
$c3 = imagecolorallocate($im, 51, 204, 51); //green
$constell = imagecolorallocate($im, 0x75, 0x9f, 0xdf);
$c_gal = imagecolorallocate($im, 0xe8, 0xb0, 0x09);
$c_gb = imagecolorallocate($im, 0x75, 0x9f, 0xf7);
$c_oc = imagecolorallocate($im, 0xf1, 0x64, 0x41);
$c_nb = imagecolorallocate($im, 0x57, 0xe6, 0x7e);
$c_cpn = imagecolorallocate($im, 0x9e, 0x3a, 0xdc);
$s4 = imagecolorallocate($im, 0x8f, 0x8f, 0x8f);

$colors = array('black' => $c1, 'constell'=>$constell, 'star1'=>$c2, 'star4'=>$s4, 'moon'=>$c2, 'Gx'=>$c_gal, 'OC'=>$c_oc, 'Gb'=>$c_gb, 'Nb'=>$c_nb, 'Pl'=>$c_nb, 'C+N'=>$c_cpn, 'Kt'=>$c3);
//////

imagearc($im, $w/2, $h/2, $w-30, $h-30, 0, 360, $c1); // sky
imagefilltoborder($im, $w/2, $h/2, $c1, $c1); //////////

imagestring($im, 5, $w/2-5, 0, "N", $c1);
imagestring($im, 5, $w/2-5, $h-15, "S", $c1);
imagestring($im, 5, 2, $h/2-5, "E", $c1);
imagestring($im, 5, $w-10, $h/2-5, "W", $c1);

$mash = ($h-30)/180; // масштаб - сколько в градусе пикселей.

// северный полюс
imageline($im, $w/2-3, $h/2+($shirota-90)*$mash, $w/2+3, $h/2+($shirota-90)*$mash, $c3);
imageline($im, $w/2, $h/2+($shirota-90)*$mash-3, $w/2, $h/2+($shirota-90)*$mash+3, $c3);
//
// небесный экватор
imagearc($im, $w/2, $h/2, $w-30, $shirota*$mash*2, 0, 180, $c3);
//

////// drawing constellations
if(!isset($no_const)) {
	$qc = mysql_query("select * from constell_draw where 1");
	$num = mysql_num_rows($qc);
	for($i=0; $i<$num; $i++) {
		imageline($im, mysql_result($qc, $i, 'x1'), mysql_result($qc, $i, 'y1'), mysql_result($qc, $i, 'x2'), mysql_result($qc, $i, 'y2'), $colors['constell']);
	}
}
//////

$q1 = mysql_query("select * from fordraw where 1");
$num = mysql_num_rows($q1);
for($i=0; $i<$num; $i++) {
	if(mysql_result($q1, $i, 'type') == "star")
	draw2($im, mysql_result($q1, $i, 'x'), mysql_result($q1, $i, 'y'), $colors, mysql_result($q1, $i, 'type'), mysql_result(mysql_query("select mag from stars where id = ".mysql_result($q1, $i, 'obid')), 0, 'mag'));
	else
	draw2($im, mysql_result($q1, $i, 'x'), mysql_result($q1, $i, 'y'), $colors, mysql_result($q1, $i, 'type'), 99);
}

////// drawing const names
if(!isset($no_const) && !isset($no_names)) {
	$qc = mysql_query("select * from constell_draw_names where 1");
	$num = mysql_num_rows($qc);
	for($i=0; $i<$num; $i++) {
		imagettftext($im, $fsize, 0, mysql_result($qc, $i, 'x'), mysql_result($qc, $i, 'y'), $colors['constell'], "MNC.ttf", mysql_result($qc, $i, 'name'));
		//imagestring($im, 5, mysql_result($qc, $i, 'x'), mysql_result($qc, $i, 'y'), mysql_result($qc, $i, 'name'), $colors['constell']);
	}
}
//////

imagecolortransparent($im, $bg);
header("Content-type: image/png");
imagepng($im);
?>
