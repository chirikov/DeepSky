<?php
include_once("inc/head.php");
include_once("inc/config.php");
include_once("modules/functions.php");
?>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><a href="gal.php"><h1>Фотогалерея</h1></a></td>
     </tr>
     <tr>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0>
        <tr>
         <td class=cont align=center>
<script language="JavaScript" type="text/javascript">
<!--
var urls = new Array ();
var photos = new Array ();
var author = new Array ();
var maxi;
<?php
include("inc/config.php");

$vars = array("pid");
foreach($vars as $var) {
	if(isset($_GET[$var])) $$var = $_GET[$var];
}

$q0 = mysqli_query($db, "select kid from gal where id = ".$pid);
$kid = mysqli_fetch_assoc($q0)['kid'];
$query = mysqli_query($db, "select photo, url, id, objid, author from gal where kid = ".$kid." order by id asc");
$i = 0;
while($row = mysqli_fetch_array($query)) {
	if($row['id'] == $pid) print "var cur = ".$i.";";
	if($row['objid'] != 0) {
				$qq2 = mysqli_query($db, "select ngc, messier, name from objects where id = '".$row['objid']."'");
				$r = mysqli_fetch_assoc($qq2);
				$messier = $r['messier'];
				$ngc = $r['ngc'];
				$name = $r['name'];
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
				print "photos[".$i."] = '".$row['photo']."<br>".$poyasn."';";
	}
	else print "photos[".$i."] = '".$row['photo']."';";
	print "urls[".$i."] = '".$row['url']."';";
	print "author[".$i."] = '".$row['author']."';";
	$poyasn = "";
	$i++;
}
$i--;
$maxi = $i;
print "maxi = ".$maxi;
?>


function bigshow(i, maxi, urls, photos) {
document.getElementById("scene").src = "photos/"+urls[i];
document.getElementById("div_main").innerHTML = "<h2 style='text-align: center;'>"+photos[i]+"</h2>";
document.getElementById("dop_main").value = urls[i];
if(author[i] != "-" && author[i] != "") document.getElementById("opis").innerHTML = "Автор: "+author[i];
else document.getElementById("opis").innerHTML = "";

function setpic(urls, photos, no, i) {
document.getElementById("th"+no).src = "photos/small/"+urls[i];
document.getElementById("div"+no).innerHTML = photos[i];
document.getElementById("dop"+no).value = i;
}

if(i==0) {
setpic(urls, photos, 1, i+1);
setpic(urls, photos, 2, i+2);
setpic(urls, photos, 3, i+3);
setpic(urls, photos, 4, i+4);
setpic(urls, photos, 5, i+5);
}
if(i==1) {
setpic(urls, photos, 1, i-1);
setpic(urls, photos, 2, i+1);
setpic(urls, photos, 3, i+2);
setpic(urls, photos, 4, i+3);
setpic(urls, photos, 5, i+4);
}
if(i==maxi) {
setpic(urls, photos, 1, i-5);
setpic(urls, photos, 2, i-4);
setpic(urls, photos, 3, i-3);
setpic(urls, photos, 4, i-2);
setpic(urls, photos, 5, i-1);
}
if(i==maxi-1) {
setpic(urls, photos, 1, i-4);
setpic(urls, photos, 2, i-3);
setpic(urls, photos, 3, i-2);
setpic(urls, photos, 4, i-1);
setpic(urls, photos, 5, i+1);
}
if(i==maxi-2) {
setpic(urls, photos, 1, i-3);
setpic(urls, photos, 2, i-2);
setpic(urls, photos, 3, i-1);
setpic(urls, photos, 4, i+1);
setpic(urls, photos, 5, i+2);
}
if (i!=0 && i!=1 && i!=maxi && i!=maxi-1 && i!=maxi-2) {
setpic(urls, photos, 1, i-2);
setpic(urls, photos, 2, i-1);
setpic(urls, photos, 3, i+1);
setpic(urls, photos, 4, i+2);
setpic(urls, photos, 5, i+3);
}
}
-->
</script>
          <table width=100% cellspacing=3 cellpadding=5 class=gal>
           <tr>
            <td align=center colspan=5><div id="div_main"></div><div id="dop_main" style="display: none;"></div><a style="cursor: hand;" onclick="javascript: window.open('full_image.php?im='+document.getElementById('dop_main').value, '', 'resizable=1, scrollbars=1, menubar=0, status=0, location=0, toolbar=0');"><img id="scene" width="600" alt="Показать в натуральную величину"></a><br><div id="opis"></div></td>
           </tr>
           <tr>
            <td align=center colspan=5><h3>Другие фотографии из галлереи:</h3></td>
           </tr>
           <tr>
            <td align=center valign="top" id="pl1"><a href="javascript: bigshow(document.getElementById('dop1').value, maxi, urls, photos);"><img width=150 id="th1" src=gal.jpg><br><div id="div1"></div><div id="dop1" style="display: none;"></div></a></td>
            <td align=center valign="top" id="pl2"><a href="javascript: bigshow(document.getElementById('dop2').value, maxi, urls, photos);"><img width=150 id="th2" src=gal.jpg><br><div id="div2"></div><div id="dop2" style="display: none;"></div></a></td>
            <td align=center valign="top" id="pl3"><a href="javascript: bigshow(document.getElementById('dop3').value, maxi, urls, photos);"><img width=150 id="th3" src=gal.jpg><br><div id="div3"></div><div id="dop3" style="display: none;"></div></a></td>
            <td align=center valign="top" id="pl4"><a href="javascript: bigshow(document.getElementById('dop4').value, maxi, urls, photos);"><img width=150 id="th4" src=gal.jpg><br><div id="div4"></div><div id="dop4" style="display: none;"></div></a></td>
            <td align=center valign="top" id="pl5"><a href="javascript: bigshow(document.getElementById('dop5').value, maxi, urls, photos);"><img width=150 id="th5" src=gal.jpg><br><div id="div5"></div><div id="dop5" style="display: none;"></div></a></td>
           </tr>
          </table>
		  <script language="JavaScript" type="text/javascript">
		  bigshow(cur, maxi, urls, photos);
		  </script>

         </td>
        </tr>
       </table>
      </td>
     </tr>
    </table>

<table style="width: 100%" cellspacing=1 cellpadding=0 class=news>
     <tr>
      <td colspan=2 class=hd><a href=show_news.php><h1>Новости</h1></a></td>
     </tr>
     <?
     $perindex = conf('news_index');
	 $sql = mysqli_query ($db, "select id, author, title, news_short, kid, date from ".$t3." where status = 'ok' order by id DESC limit ".$perindex."");
	 while ($row = mysqli_fetch_array($sql))
	 {	 	
	 $date = getdate ($row['date']);
	$months = array(1=>"января", 2=>"февраля", 3=>"марта", 4=>"апреля", 5=>"мая", 6=>"июня", 7=>"июля", 8=>"августа", 9=>"сентября", 10=>"октября", 11=>"ноября", 12=>"декабря");
	$tdate =$date['mday']." ".$months[$date['mon']]."<BR>".$date['year']." года";
	 	echo"     
	     <tr>
	      <td class=date>$tdate</td>
	      <td>
	       <table style=\"width: 100%; height: 100%\" cellspacing=0 cellpadding=0 class=news>
	        <tr>
	         <td class=nhd>".$row['title']."</td>
	        </tr>
	        <tr>
	         <td class=cont>
	          ".$row['news_short']."
	         </td>
	        </tr>
	        <tr>
	         <td class=hrefs><a href=\"show_news.php?act=read&id=".$row['id']."\">Читать новость полностью</a> | Разместил: ".get_name($row['author'])."</td>
	        </tr>
	       </table>
	      </td>
	     </tr>";
	 }	 
     ?>    
     
    </table>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     <tr>
      <td colspan=2 class=hd><a href=show_articles.php><h1>Статьи и материалы</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние статьи</td>
      <td class=cont>
           <?
           $sql = mysqli_query ($db, "select title, id, about from ".$t5." where status = 'ok' order by date desc limit 5");
           while ($row = mysqli_fetch_array($sql))
           {
           echo "<a href=show_articles.php?act=read&aid=".$row['id'].">".$row['title']."</a><br>
           ".$row['about']."<br><br>";
           }          
      	   ?>

      </td>
     </tr>
    </table>

   <?php
   include_once("inc/foot.php");
   ?>
