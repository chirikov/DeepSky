<?
require 'inc/config.php';
require 'modules/show_articles.php';
require 'modules/functions.php';
function head()
{
	include 'inc/head.php';
	global $kid, $t4;
	if (!empty($kid))
	{
		$sql = mysql_query ("select kat from ".$t4." where id = '$kid' limit 1");
		$row = mysql_fetch_array($sql);
		$kat = $row[kat];
		$link = "show_articles.php?kid=$kid";
	}
	else 	
	{
	$kat = "������ � ���������";
	$link = "show_articles.php";
	}
	echo"<table style=\"width: 100%\" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>$kat</h1></td>
    	 </tr>";
}
function foot()
{
	echo'</table>
	<table style="width: 100%" cellspacing=1 cellpadding=0 class=news>
     <tr>
      <td colspan=2 class=hd><a href=show_news.php><h1>�������</h1></a></td>
     </tr>';
     
	$perindex = conf('news_index');global $t3;
	 $sql = mysql_query ("select id, author, title, news_short, kid, date from ".$t3." where status = 'ok' order by id DESC limit ".$perindex."");
	 while ($row = mysql_fetch_array($sql))
	 {	 	
	 $date = getdate ($row[date]);
	$months = array(1=>"������", 2=>"�������", 3=>"�����", 4=>"������", 5=>"���", 6=>"����", 7=>"����", 8=>"�������", 9=>"��������", 10=>"�������", 11=>"������", 12=>"�������");
	$tdate =$date[mday]." ".$months[$date[mon]]."<BR>".$date[year]." ����";
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
	         <td class=hrefs><a href=\"show_news.php?act=read&id=$row[id]\">������ ������� ���������</a> | ���������: ".get_name($row[author])."</td>
	        </tr>
	       </table>
	      </td>
	     </tr>";
	 };echo'	 
         
     
    </table>
	<table style="width: 100%" cellspacing=1 cellpadding=0 class=photo>
     <tr>
      <td colspan=2 class=hd><a href=#><h1>�����������</h1></a></td>
     </tr>
     <tr>
      <td class=op>��������� ����������</td>
      <td>
       <table style="width: 100%; height: 100%" cellspacing=0 cellpadding=0 class=photo>
        <tr>';
		global $t16;
		$sqlpgal = mysql_query ("select id, kid, photo, url from ".$t16." where 1 order by id desc limit 3");
		while ($rowpgal = mysql_fetch_array($sqlpgal))
		{
			echo "<td class=cont>
          <a href=showpic.php?pid=$rowpgal[id]><img width=147 src=\"".conf('siteurl')."photos/small/$rowpgal[url]\" alt=\"$rowpgal[photo]\"></a><br>
          $rowpgal[photo]
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
