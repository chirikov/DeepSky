<?php
header("Content-Type:text/html; charset=windows-1251");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<?php
include_once("inc/config.php");
$q = mysqli_query($db, "select photo, author from gal where url = '".$_GET["im"]."'");
$r = mysqli_fetch_assoc($q);
$author = $r['author'];
$photo = $r['photo'];
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
<td colspan="2"><img id="scene" src="photos/<?=$_GET["im"]?>" alt="<?=$photo?>" border="0">
</td></tr></table>
</body>
</html>
