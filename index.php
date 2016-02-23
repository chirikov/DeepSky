<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
require_once 'inc/config.php';
include_once 'inc/head.php';
require_once 'modules/functions.php';
?>
<table style="width: 100%" cellspacing=1 cellpadding=0 class=news>
     <tr>
      <td colspan=2 class=hd><a href=show_news.php><h1>Новости</h1></a></td>
     </tr>
     <?
     $perindex = conf('news_index');
	 $sql = mysql_query ("select id, author, title, news_short, kid, date from ".$t3." where status = 'ok' order by id DESC limit ".$perindex."");
	 while ($row = mysql_fetch_array($sql))
	 {	 	
	 $date = getdate ($row[date]);
	$months = array(1=>"января", 2=>"февраля", 3=>"марта", 4=>"апреля", 5=>"мая", 6=>"июня", 7=>"июля", 8=>"августа", 9=>"сентября", 10=>"октября", 11=>"ноября", 12=>"декабря");
	$tdate =$date[mday]." ".$months[$date[mon]]."<BR>".$date[year]." года";
	 	echo"     
	     <tr>
	      <td class=date>$tdate</td>
	      <td>
	       <table style=\"width: 100%; height: 100%\" cellspacing=0 cellpadding=0 class=news>
	        <tr>
	         <td class=nhd>$row[title]</td>
	        </tr>
	        <tr>
	         <td class=cont>
	          $row[news_short]
	         </td>
	        </tr>
	        <tr>
	         <td class=hrefs><a href=\"show_news.php?act=read&id=$row[id]\">Читать новость полностью</a> | Разместил: ".get_name($row[author])."</td>
	        </tr>
	       </table>
	      </td>
	     </tr>";
	 }	 
     ?>    
     
    </table>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><a href="gal.php"><h1>Фотогалерея</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние фотографии</td>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0 class=photo>        
         <?
	$sql = mysql_query ("select id, kid, photo, url from ".$t16." where 1 order by id desc limit 3");
	while ($row = mysql_fetch_array($sql))
	{
		echo "<td class=cont>
          <a href=showpic.php?pid=$row[id]><img width=147 src=\"photos/small/$row[url]\" alt=\"$row[photo]\"></a><br>
          $row[photo]
         </td>";
	}
        ?>
        </tr>
       </table>
      </td>
     </tr>
    </table>

    <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     <tr>
      <td colspan=2 class=hd><a href=show_articles.php><h1>Статьи и материалы</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние статьи</td>
      <td class=cont>
           <?
           $sql = mysql_query ("select title, id, about from ".$t5." where status = 'ok' order by date desc limit 5");
           while ($row = mysql_fetch_array($sql))
           {
           echo "<a href=show_articles.php?act=read&aid=$row[id]>$row[title]</a><br>
           $row[about]<br><br>";
           }          
      	   ?>

      </td>
     </tr>
    </table>
<?
include 'inc/foot.php';
?>