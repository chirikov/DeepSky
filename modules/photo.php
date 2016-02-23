<?
function photoaddform()
{
	global $t15;
	echo "
	<form action=photo.php method=post enctype=multipart/form-data>
	<input type=hidden name=act value=upload>
	<table class=articles>
	<tr><td class=cont>Автор фотографии:</tD><TD class=cont><input type=text name=author></td></tr>
	<tr><td class=cont>Название фотографии:</td><td class=cont><input type=text name=photo></td></tr>
	<tr><td class=cont>Номер объекта по NGC (Только номер без ведущих нолей. Если номер по IC, то перед номером без пробела поставьте I):</td><td class=cont><input type=text name=ngcid></td></tr>
	<tr><td class=cont>Номер объекта по каталогу Мессье (только номер):</td><td class=cont><input type=text name=mid></td></tr>
	<tr><td class=cont>Раздел фотографии:</td><td class=cont><select name=kid>";
	$sql = mysql_query ("select * from ".$t15." where 1 order by kat ASC");
	while ($row = mysql_fetch_array($sql))
	{
		echo "<option value=$row[id]>$row[kat]";
	}
	echo"</select></td></tR>
	<tr><td class=cont>Фотография:</td><td class=cont><input type=file name=file ></td></tr>
	<tr><td colspan=2 align=center class=cont><input class=btn type=submit name=phadd value=Добавить></td></tr>
	</table></form>
	";
}
function upload($author, $photo, $kid, $file, $mid, $ngcid)
{
	global $t16, $file_name;
	if (empty($author) or empty($photo) or empty($kid) or empty($file))
		echo "Все поля обязательны для заполнения<BR>";
	else 
	{
		if (!eregi(".(jpeg|jpg|gif|png)$", $file_name, $sa))
			echo "Допустимые расширения фотографии - jpeg, gif, png";
		else 
		{
			$sql = mysql_query ("select id from ".$t16." where 1 order by id desc limit 1");
			$row = mysql_fetch_array($sql);
			$id = $row[id]+1;
			
			$raz = $sa[1];
			$fname = $id.".".$raz;
			$fcopy = copy($file, "photos/$fname");
			if (!$fcopy)
				echo "Возникла ошибка при копировании файла ";
			else 
			{
				// learning object id
				if($mid == "" && $ngcid == "") $objid = "";
				elseif($ngcid != "" && $ngcid != " " && $ngcid != "-") $objid = @mysql_result(mysql_query("select id from objects where ngc = '".$ngcid."'"), 0, 'id') or $objid = "";
				elseif($mid != "" && $mid != " " && $mid != "-") $objid = @mysql_result(mysql_query("select id from objects where messier = '".$mid."'"), 0, 'id') or $objid = "";
				//
				$sql = mysql_query ("insert into ".$t16." (kid, photo, author, url, objid) values ('$kid', '$photo', '$author', '$fname', '$objid');");
				##### smalling
				$ext = substr($fname, strlen($fname)-4, 4);
				if(stristr($ext, ".jpg") or stristr($ext, "jpeg")) $im = imagecreatefromjpeg("photos/$fname");
				if(stristr($ext, ".png")) $im = imagecreatefrompng("photos/$fname");
				if(stristr($ext, ".gif")) $im = imagecreatefromgif("photos/$fname");
				$im2 = imagecreatetruecolor(200, 150);
				imagecopyresampled($im2, $im, 0, 0, 0, 0, 200, 150, imagesx($im), imagesy($im));
				imagedestroy($im);
				if(stristr($ext, ".jpg") or stristr($ext, "jpeg")) imagejpeg($im2, "photos/small/".$fname);
				if(stristr($ext, ".png")) imagepng($im2, "photos/small/".$fname);
				if(stristr($ext, ".gif")) imagegif($im2, "photos/small/".$fname);
				imagedestroy($im2);
				##############
				if ($sql)
					echo "Фотография успешно добавлена<Br><BR>
					<a href=\"?act=add\" class=without>Добавить ещё</a><BR><BR>
					<a href=login.php class=without>Перейти в панель управления</a>";
				else 	
					echo "Ошибка при добавлении информации в БД: ".mysql_error();
			}
		}
	}
}
?>