<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<?include 'inc/config.php';?>
<body bgcolor="#8F7B7B" marginbottom="0" margintop="5" marginleft="5" marginright="5">
<script language="JavaScript" type="text/javascript">
<!--
function bigshow(url) {
	parent.document.getElementById('scene').src = url;
}
-->
</script>
<table height="160"><tr><td height="160">
<table border=0><TR>
<?$sql = mysqli_query ($db, "select photo, url from ".$t16." where kid = '$kid' order by id desc");
while ($row = mysqli_fetch_array($sql))
{
	echo"<TD align=center>
<img onclick=\"bigshow('photos/$row[url]');\" width=\"200\" height=\"150\" src=\"photos/small/$row[url]\"><br>
<font color=white><b>$row[photo]</b></font></td>
	";
}
?></tr></table>
</td>
<td id="td1"></td>
</td></tr></table>
</body>
</html>