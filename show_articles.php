<?
require 'inc/config.php';
require 'modules/show_articles.php';
require 'modules/functions.php';

$vars = array("act", "page", "kid", "aid");
foreach($vars as $var) {
	if(isset($_GET[$var])) $$var = $_GET[$var];
}

if(!isset($act)) $act = "";
if(!isset($page)) $page = "";

function head()
{
	include 'inc/head.php';
	global $db, $kid, $t4;
	if (!empty($kid))
	{
		$sql = mysqli_query ($db, "select kat from ".$t4." where id = '$kid' limit 1");
		$row = mysqli_fetch_array($sql);
		$kat = $row['kat'];
		$link = "show_articles.php?kid=$kid";
	}
	else 	
	{
	$kat = "Статьи и материалы";
	$link = "show_articles.php";
	}
	echo"<table style=\"width: 100%\" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>$kat</h1></td>
    	 </tr>";
}
function foot()
{
	global $db;
	echo'</table>
	<table style="width: 100%" cellspacing=1 cellpadding=0 class=news>
     <tr>
      <td colspan=2 class=hd><a href=show_news.php><h1>Новости</h1></a></td>
     </tr>';
     
	$perindex = conf('news_index');global $t3;
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
	 };echo'	 
         
     
    </table>
	<table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><a href=#><h1>Фотогалерея</h1></a></td>
     </tr>
     <tr>
      <td class=op>Последние фотографии</td>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0 class=photo>
        <tr>';
		global $t16;
		$sqlpgal = mysqli_query ($db, "select id, kid, photo, url from ".$t16." where 1 order by id desc limit 3");
		while ($rowpgal = mysqli_fetch_array($sqlpgal))
		{
			echo "<td class=cont>
          <a href=showpic.php?pid=".$rowpgal['id']."><img width=147 src=\"".conf('siteurl')."photos/small/".$rowpgal['url']."\" alt=\"".$rowpgal['photo']."\"></a><br>
          ".$rowpgal['photo']."
         </td>";
		}
		echo'
         
        </tr>
       </table>
      </td>
     </tr>';
	echo"</table>";
	include 'inc/foot.php';
}
switch ($act)
{
	default:
	head();
	artlist(katlist($kid), $page);
	break;	
	case 'read':
	head();
	if (empty($aid))
	artlist(katlist($kid), $page);
	else
	show_article($aid);
	break;	
	case 'readkomments':
	head();
	read_kom($id, $page);
	break;
	case 'addkomment':
	if (add_kom($aid, $aname, $message, $email))
	{
		setcookie ("ngpe_last_post", time(), time()+3600);
		header("Location: show_articles.php?act=read&aid=$aid");
	}
	break;
	case 'vote':
	if (vote ($id, $voterate))
	{
		setcookie ("ngpe_art_vote_$id", "1", time()+3600*24*365);
		header("Location: show_articles.php?act=read&aid=$id");		
	}	
	break;
}
foot();
?>
