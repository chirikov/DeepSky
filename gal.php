<?php
include_once("inc/head.php");
include_once("inc/config.php");
include_once("modules/functions.php");
?>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><h1>Фотогалерея</h1></td>
     </tr>
     <tr>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0>
        <tr>
         <td class=cont align=center>

          <table width=100% cellspacing=3 cellpadding=5 class=gal>
<?php
if(!isset($kid)) $kid = 2;
if(!isset($nomer)) $nomer = 1;
function photomenu() {
	global $t15;
	$sql = mysql_query ("select * from ".$t15." where 1 order by kat asc");
	print "<div class=pg><b><h1 style='color: #143150'>Разделы галереи:</h1></b><ul>";
	while ($row = mysql_fetch_array($sql))
	{
		echo "<li><a href='?kid=$row[id]'>$row[kat]</a></li>";
	}
	print "</ul></div>";
}
function listing($nomer, $kid) {
	$query = mysql_query("select id from gal where kid = ".$kid);
	$kol = mysql_num_rows($query);
	$kol_str = ceil($kol/20);
	if($kol_str>1) {
		print "<div id=pg>Страницы:&nbsp;";
		for($i=1; $i<=$kol_str; $i++) {
			if($i == $nomer)
			print "<span style='color: #CD1717'><B>$i</B></span>";
			else print "<a class=pg href='gal.php?nomer=$i'>$i</a>";
			if($i<$kol_str) print "&nbsp;|&nbsp;";
		}
		print "</div>";
	}
}
function givepage($nomer, $kid) {
	$kat_name = mysql_result(mysql_query("select kat from gal_kats where id = ".$kid), 0, 'kat');
	print "<tr>
	<td align=left colspan=4>";
	photomenu();
	print "</td>
	</tr><tr>
	<td align=center colspan=4><h2 style='text-align: center;'>".$kat_name."</h2>";
	listing($nomer, $kid);
	print "</td></tr>";
	$query = mysql_query("select id, url, photo, objid from gal where kid = ".$kid." order by id desc limit ". ($nomer-1)*20 .", 20");
	$numrows = mysql_num_rows($query);
	if($numrows<1)
	print "<tr><td align='center'>В данном разделе фотографий нет.</td></tr>";
	else {
		$i = 0;
		while($row = mysql_fetch_array($query)) {
			if($row['objid'] != 0) {
				$qq2 = mysql_query("select ngc, messier, name from objects where id = '".$row['objid']."'");
				$messier = mysql_result($qq2, 0, 'messier');
				$ngc = mysql_result($qq2, 0, 'ngc');
				$name = mysql_result($qq2, 0, 'name');
				$poyasn = " (";
				if($name != "") $poyasn .= $name;
				if($name != "" && $messier != "") $poyasn .= ", ";
				if($messier != "") $poyasn .= "M".$messier;
				if($messier != "" && $ngc != "") $poyasn .= ", ";
				if($ngc != "") {
					if(substr($ngc, 0, 1) == "I") $poyasn .= "IC".substr($ngc, 1);
					else $poyasn .= "NGC ".$ngc;
				}
				$poyasn .= ")";
			}
			if($i == 0 or $i%4 == 0) print "<tr>";
			print "<td align=center><a href='showpic.php?pid=".$row['id']."'><img width=150 height=113 src='photos/small/".$row['url']."' alt='".$row['photo']."'><br>".$row['photo']."<br>".$poyasn."</a></td>";
			if($i == 3 or $i%4 == 3 or $i == $numrows-1) print "</tr>";
			$poyasn = "";
			$i++;
		}
		print "<tr>
		<td align=center colspan=4>";
		listing($nomer, $kid);
		print "</td></tr>";
	}
}
givepage($nomer, $kid);
?>
        </table>
         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>
<?php
include_once("inc/foot.php");
?>