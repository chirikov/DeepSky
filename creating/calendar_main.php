<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
 <meta name="description" content="DeepSky">
 <meta http-equiv="content-type" content="text/html; charset=windows-1251">
 <meta name="author" content="&copy; Karim n Roma 2004-2005">
 <meta name="copyright" content="&copy; DeepSky. 2004-2005">
 <link rel="Stylesheet" href="../stsc/style.css" type="text/css">
<?php
$error = "";

$mysql = @mysql_connect('localhost', 'root', 'password') or $error = "�� ������� ������������ � ������� MySQL.";
@mysql_select_db('deepsky') or $error = "�� ������� ������� ���� ������.";

// ����������� ������.
if($opt == 'city') {
$query = mysql_query("select * from goroda where id=".$city);
$shir_deg = mysql_result($query, 0, 'shirota_deg');
$shir_min = mysql_result($query, 0, 'shirota_min');
$timezone = mysql_result($query, 0, 'timezone');
$city_name = mysql_result($query, 0, 'name');
}
elseif($opt != 'shir')
$error = "<font color='#000000'>�� ����������� ����� �� ������ ��������. ������� ������� ��� �������������� <a href='calendar_select.php'>�����</a></font>.";

$shirota = round($shir_deg + $shir_min/60, 2);
if($shirota>90 || $shirota < 40) $error = "<br><font color='#000000'>�������� ������. �������� ������� ��� �������������� <a href='calendar_select.php'>�����</a></font>";
elseif($timezone>11 || $timezone < 1) $error = "<br><font color='#000000'>�������� ��������� ����. �������� ������� ��� �������������� <a href='calendar_select.php'>�����</a></font>";
///////
if($shir_min == '') $shir_min = 0;
if($opt == 'city' && $error == "") $title = $city_name." (".$shir_deg."&deg; ".$shir_min."'); ��������� ����: ".$timezone;
elseif($opt == 'shir' && $error == "") $title = "������ ".$shir_deg."&deg; ".$shir_min."'; ��������� ����: ".$timezone;
?>
	<title>���� ��� ������� - �������� ���������</title>
	<script language="JavaScript" type="text/javascript">
	var w = window.screen.availHeight-200;
	</script>
</head>

<body>
<table cellpadding="0" cellspacing="0" class="news" border="0">
<tr>
<td rowspan="4" style="width: 1px"></td>
<td rowspan="4" class=date style="width: 2"></td>
<td class=nhd colspan="2"><?=$title?></td>
</tr>
<tr>
<td rowspan="3" id="div1" valign="top" align="left" bgcolor="#ffffff">
<?php
if(!isset($day)) $day = date("j");
if(!isset($month)) $month = date("m");
if(!isset($hour)) $hour = 0;
if(!isset($minute)) $minute = 0;

if($error != "") print $error;
else {
?>

</td>
<td class="cont" style="width: 151; height: 200;" align="left">
<form name="f3" action="add_const.php">
������������: <input type="Text" name="name"><br>
��������: <input type="Text" name="rusname"><br>
������: <textarea name="skeleton" rows=10 cols="50"></textarea><br>
<input type="Submit" name="go3"></form>
<BR><BR><BR><BR>
</td>
</tr>
<tr>
<td style="height:100;" class="cont" id="tdt" valign="top" align="center"></td>
</tr>
<tr><td>

<table cellpadding="0" cellspacing="0" class="news"><tr><td class="cont">&nbsp;
<div id="cursor"><table class=news><tr><td align=right valign=top class=cont>������� �������:&nbsp;</td><td class=cont>������: 0&deg;, ������: 0&deg;<br>������ �����������: 0 �., ���������: 0&deg;</td></tr></table></div><br>
<form name="f2">
<b>������� � ����:</b> <input type="Text" name="day" maxlength="2" size="2" value="<?=$day?>">&nbsp;<select name="month">
<option value="01" <?php if($month == "01") print "selected" ?>>������
<option value="02" <?php if($month == "02") print "selected" ?>>�������
<option value="03" <?php if($month == "03") print "selected" ?>>����
<option value="04" <?php if($month == "04") print "selected" ?>>������
<option value="05" <?php if($month == "05") print "selected" ?>>���
<option value="06" <?php if($month == "06") print "selected" ?>>����
<option value="07" <?php if($month == "07") print "selected" ?>>����
<option value="08" <?php if($month == "08") print "selected" ?>>������
<option value="09" <?php if($month == "09") print "selected" ?>>��������
<option value="10" <?php if($month == "10") print "selected" ?>>�������
<option value="11" <?php if($month == "11") print "selected" ?>>������
<option value="12" <?php if($month == "12") print "selected" ?>>�������
</select>&nbsp;<b>�����:</b> <input type="Text" maxlength="2" size="2" name="hour" value="<?=$hour?>">:<input type="Text" maxlength="2" size="2" name="minute" value="<?=$minute?>">&nbsp;<input type="Submit" value="�������" name="goto" onclick="javascript:
var str = '';
if(f1.vstars.checked == false) str += '&no_stars=1';
if(f1.vgal.checked == false) str += '&no_gx=1';
if(f1.vneb.checked == false) str += '&no_nb=1';
if(f1.vscl.checked == false) str += '&no_gb=1';
if(f1.vocl.checked == false) str += '&no_oc=1';
if(f1.vcpn.checked == false) str += '&no_cpn=1';
if(f1.vconst.checked == false) str += '&no_const=1';

if(f1.mag_max_stars.value != <?php if(isset($mag_max_stars)) print $mag_max_stars; else print '4.2' ?>) str += '&mag_max_stars='+f1.mag_max_stars.value;
if(f1.mag_max_obj.value != <?php if(isset($mag_max_obj)) print $mag_max_obj; else print '9' ?>) str += '&mag_max_obj='+f1.mag_max_obj.value;

var max_days;
if(f2.month.value == '02') max_days = 28;
else {
	if(f2.month.value == '04' || f2.month.value == '06' || f2.month.value == '09' || f2.month.value == '11') max_days = 30;
	else {
		max_days = 31;
	}
}

if(f2.day.value<=max_days && f2.hour.value<24 && f2.minute.value<=59) {
document.getElementById('iframe').src = 'frame.php?w='+w+'&shirota=<?=$shirota?>&timezone=<?=$timezone?>&day='+f2.day.value+'&month='+f2.month.value+'&hour='+f2.hour.value+'&minute='+f2.minute.value+str;
return false;
} else return false;
">
</form>

</td></tr><tr><td class="cont" height="100%">
<b>���������:</b>
<form name="f1">
<table cellspacing="0" cellpadding="0">
<tr><td rowspan="7" align="right" valign="top" class="cont">����������: </td><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vstars" value="1" onclick="javascript: if(f1.vstars.checked == false) {f1.mag_max_stars.disabled = true;} else {f1.mag_max_stars.disabled = false;}" checked> �����</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vconst" value="1" checked> ���������</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vgal" value="1" checked> ���������</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vneb" value="1" checked> ����������</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vscl" value="1" checked> ������� ���������</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vocl" value="1" checked> ��������� ���������</td></tr>
<tr><td class="cont" style="padding-bottom: 0px;"><input type="Checkbox" name="vcpn" value="1" checked> ��. �����. � �����������</td></tr>
<tr><td align="right" class="cont">���������� ����� ��: </td><td><input type="Text" maxlength="6" size="6" name="mag_max_stars" value="<?php if(isset($mag_max_stars)) print $mag_max_stars; else print '4.2' ?>"</td></tr>
<tr><td align="right" class="cont">���������� ������� ��: </td><td><input type="Text" maxlength="6" size="6" name="mag_max_obj" value="<?php if(isset($mag_max_obj)) print $mag_max_obj; else print '9' ?>"</td></tr>
<tr><td>&nbsp;</td><td><input type="Submit" name="go_main" value="���������" onclick="javascript:
var str = '';
if(f1.vstars.checked == false) str += '&no_stars=1';
if(f1.vgal.checked == false) str += '&no_gx=1';
if(f1.vneb.checked == false) str += '&no_nb=1';
if(f1.vscl.checked == false) str += '&no_gb=1';
if(f1.vocl.checked == false) str += '&no_oc=1';
if(f1.vcpn.checked == false) str += '&no_cpn=1';
if(f1.vconst.checked == false) str += '&no_const=1';

if(f1.mag_max_stars.value != <?php if(isset($mag_max_stars)) print $mag_max_stars; else print '4.2' ?>) str += '&mag_max_stars='+f1.mag_max_stars.value;
if(f1.mag_max_obj.value != <?php if(isset($mag_max_obj)) print $mag_max_obj; else print '9' ?>) str += '&mag_max_obj='+f1.mag_max_obj.value;

var max_days;
if(f2.month.value == '02') max_days = 28;
else {
	if(f2.month.value == '04' || f2.month.value == '06' || f2.month.value == '09' || f2.month.value == '11') max_days = 30;
	else {
		max_days = 31;
	}
}

if(f2.day.value<=max_days && f2.hour.value<24 && f2.minute.value<=59) {
document.getElementById('iframe').src = 'frame.php?w='+w+'&shirota=<?=$shirota?>&timezone=<?=$timezone?>&day='+f2.day.value+'&month='+f2.month.value+'&hour='+f2.hour.value+'&minute='+f2.minute.value+str;
return false;
} else return false;
"></td></tr>
</table>
</form>
</td>
</tr>
</table>
</td></tr>
<script language="JavaScript" type="text/javascript">
document.getElementById('div1').style.height = w;
document.getElementById('div1').style.width = w;

var iframe = document.createElement('IFRAME');
iframe.id = 'iframe';
iframe.src = "frame.php?w="+w+"&shirota=<?=$shirota?>&timezone=<?=$timezone?>";
iframe.width = window.screen.availHeight-200;
iframe.height = window.screen.availHeight-200;
iframe.scrolling = "No";
iframe.frameBorder = "0";
iframe.marginheight="0";
iframe.marginwidth="0";
iframe.style.position = "absolute";
iframe.style.top = document.getElementById('div1').style.top;
document.getElementById('div1').appendChild(iframe);
</script>
<?php } ?>
</table>
</body>
</html>
