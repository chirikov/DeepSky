<?
require 'inc/config.php';
require 'modules/show_news.php';
require 'modules/functions.php';
function head()
{
        include 'inc/head.php';
        echo"    <table style=\"width: 100%\" cellspacing=1 cellpadding=0 class=news>
     	<tr>
     	 <td colspan=2 class=hd><a href=show_news.php><h1>Новости</h1></a></td>
    	 </tr>
        ";
}
function foot()
{
        echo"</table>
        ";
        include 'inc/foot.php';
}
switch ($act)
{
        default:
        head();        
        news_show('', $page);
        break;
        case 'show':
        head();
              if (empty($kid))
                  echo "Не выбрана категория.<BR>";
              else
                  news_show($kid, $page);
        break;
        case 'read':
        head();
              if (empty($id))
              {
                      echo "Не выбрана новость!<BR>";                      
                      news_show('');
              }
              else
                  readn($id);
        break;
        case 'readkomments':
        head();
               read_kom($id, $page);
        break;
        case 'addkomment':                
        	if (add_kom($nid, $aname, $message, $email))
        	{
        		setcookie ("ngpe_last_post", time(), time()+3600);
        		header("Location: show_news.php?act=read&id=$nid");
        	}        		        
        break;
}
foot();
?>
