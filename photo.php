<?
include 'inc/config.php';
include 'modules/functions.php';
require_once('modules/photo.php');
function head()
{
        include_once 'inc/head.php';
        echo"    <table style='width: 100%' cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Добавление фотографий</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
        ";
}
function foot()
{
        echo"</td></tr></table>
        ";
        include_once 'inc/foot.php';
}
head();
if (empty($_COOKIE[ngpe_id]) or !check_pravo($_COOKIE[ngpe_id], "photoadd"))
	echo "К сожалению, у вас нет доступа к данной части сайта";
else 
{
	switch ($act)
	{
		default:
		echo"<a href=\"act=add\" class=without>Добавить фотографию</a><BR><BR>
		<a href=login.php class=without>Вернуться в панель управления</a><BR><BR>";
		break;
		case 'add':
		photoaddform();
		break;
		case 'upload':
		upload($author, $photo, $kid, $file, $mid, $ngcid);
		break;
	}	
}           
            
foot();
