<?
require_once 'inc/config.php';
require_once 'modules/vote.php';
require_once 'modules/functions.php';
function head()
{
	include_once 'inc/head.php';
	echo '<table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Голосования</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>';
}
function foot()
{
	echo "     
	
	</tD></tr>
        </table>";
	include_once'inc/foot.php';
}
switch ($act)
{
	default:
	header("Location:vote.php?act=showall");
	break;
	case 'add':
	head();
	addpart();
	break;
	case 'addvote':
	head();
	addvote($theme, $type, $ans1, $ans2, $ans3, $ans4, $ans5, $ans6, $ans7, $ans8, $ans9, $ans10);
	break;
	case 'showshort':
	mainlist();
	break;
	case 'vote':
	if (vote($vid, $ans))
	{
		$ip = getip();
		setcookie ("cms_vote_label_id_$vid", "1", time()+3600*24*365);		
		if (!empty($_COOKIE[ngpe_id]))
		mysqli_query ($db, "update ".$t12." set voteids=CONCAT(voteids, '$_COOKIE[ngpe_id];'), voteips=CONCAT(voteips, '$ip;')  where id='$vid' limit 1");
		else 
		mysqli_query ($db, "update ".$t12." set voteips=CONCAT(voteips, '$ip;') where id='$vid' limit 1");
		echo mysql_error();
		header("location:vote.php?act=showresults&id=$vid");
	}
	break;
	case 'showresults':
	if (empty($id))
		header("Location:vote.php?act=showall");
	head();
	showresult($id);
	break;
	case 'showall':
	head();
	showall();
	break;
	case 'moder':
	head();	
	active_form();
	break;
	case 'voteinfo':
	if (empty($id))
		header("location:vote.php?act=showall");
	head();
	if (!check_if_vote($id))
		vote_form($id);
	showresult($id);
	break;
	case 'a2p':
	head();
	a2p($id);
	break;
	case 'showarchive':
	head();
	showarchive();
	break;
	case 'p2a':
	head();
	p2a($id);
	break;
}
foot();
?>
