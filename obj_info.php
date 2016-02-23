<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

<html>
<head>
	<title>Информция об объекте <?=ereg_replace("@", " ", $obj)?></title>
</head>

<body>
<table>
<?php
include_once("inc/config.php");

$obj = ereg_replace("@", " ", $obj);

function dms($x, $znak) {
	if($x<0) {
		$drob = round(abs($x-ceil($x))*60,1);
		return ceil($x).chr(176)."&nbsp;".$drob;
	}
	else {
		$drob = round(($x-floor($x))*60,1);
		$q = "";
		if($znak) $q = "+";
		return $q.floor($x).chr(176)."&nbsp;".$drob;
	}
}

function hms($x) {
		
		$drob = round(($x-floor($x))*60,1);
		$q = "";
		if($znak) $q = "+";
		return $q.floor($x)."h"."&nbsp;".$drob."m";
}

if(substr($obj, 0, 1) == "N") $code = substr($obj, 4);
elseif(substr($obj, 0, 1) == "M") $code = substr($obj, 1, 3);

$dop = "";
$pos = strpos($obj, "NGC");
if($pos === false) {$pos = strpos($obj, "IC"); $dop = "I";}
if($pos === false) exit("Некорректное название объекта.");
else {
if($dop == "") $pos2 = $pos+3;
elseif($dop == "I") $pos2 = $pos+2;
$code = $dop.trim(substr($obj, $pos2));
$query = mysql_query("select * from objects where ngc = '$code'");
$ar = mysql_fetch_assoc($query);

print "
<tr><td align='right'>Объект: </td><td>".$obj."</td></tr>
<tr><td align='right'>Тип: </td><td>".$ar['type']."</td></tr>
<tr><td align='right'>Зв. величина: </td><td>".$ar['mag']."</td></tr>
<tr><td align='right'>Прямое восхождение: </td><td>".hms($ar['ra'])."</td></tr>
<tr><td align='right'>Склонение: </td><td>".dms($ar['deca'], 1)."</td></tr>
<tr><td align='right'>Созвездие: </td><td>".$ar['sozvezdie']."</td></tr>
";
if($ar['name'] != "") print "<tr><td align='right'>Название: </td><td>".$ar['name']."</td></tr>";
}

?>
</table>


</body>
</html>
