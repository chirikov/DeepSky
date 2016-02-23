<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
include_once("inc/config.php");
$q = mysql_query("select photo, author from gal where url = '".$im."'");
$author = mysql_result($q, 0, 'author');
$photo = mysql_result($q, 0, 'photo');
?>
<html>
<head>
	<title>Увеличенное изображение - <?=$photo?></title>
</head>
<body>
<table border="0">
<tr>
<td align="left"><h1><?=$photo?></h1><br>
<?php if($author != "-" and $author != "") print "Автор: ".$author."<br>"; ?></td>
<td align="right"><input type="Button" onclick="javascript: self.close();" value="Закрыть"></td>
</tr>
<tr>
<td colspan="2"><img id="scene" src="photos/<?=$im?>" alt="<?=$photo?>" border="0">
</td></tr></table>
</body>
</html>
