<?
require 'inc/config.php';
//////////////////////////////////////////////////
mysql_query ("drop table if exists ".$t9."");
$sql = mysql_query ("CREATE TABLE ".$t9." (
`what` varchar(250) NOT NULL default '',
`v` text NOT NULL default ''
)
");
if ($sql)echo "“аблица $t9 создана!<br>";
else echo mysql_error();
mysql_query ("insert into ".$t9." values ('art_title_max', '250');");
mysql_query ("insert into ".$t9." values ('art_title_min', '2');");
mysql_query ("insert into ".$t9." values ('art_short_max', '550');");
mysql_query ("insert into ".$t9." values ('art_short_min', '5');");
mysql_query ("insert into ".$t9." values ('art_full_max', '1048576');");
mysql_query ("insert into ".$t9." values ('art_full_min', '5');");
mysql_query ("insert into ".$t9." values ('name_max', '250');");
mysql_query ("insert into ".$t9." values ('name_min', '2');");
mysql_query ("insert into ".$t9." values ('pass_max', '250');");
mysql_query ("insert into ".$t9." values ('pass_min', '3');");
mysql_query ("insert into ".$t9." values ('email_max', '250');");
mysql_query ("insert into ".$t9." values ('email_min', '3');");
mysql_query ("insert into ".$t9." values ('icq_max', '15');");
mysql_query ("insert into ".$t9." values ('icq_min', '4');");
mysql_query ("insert into ".$t9." values ('url_max', '250');");
mysql_query ("insert into ".$t9." values ('url_min', '10');");
mysql_query ("insert into ".$t9." values ('news_title_max', '250');");
mysql_query ("insert into ".$t9." values ('news_title_min', '2');");
mysql_query ("insert into ".$t9." values ('news_short_max', '550');");
mysql_query ("insert into ".$t9." values ('news_short_min', '5');");
mysql_query ("insert into ".$t9." values ('news_full_max', '8388608');");
mysql_query ("insert into ".$t9." values ('news_full_min', '5');");
mysql_query ("insert into ".$t9." values ('news_per_page', '5');");
mysql_query ("insert into ".$t9." values ('articles_per_page', '10');");
mysql_query ("insert into ".$t9." values ('news_index', '3');");
mysql_query ("insert into ".$t9." values ('news_komments', '10');");
mysql_query ("insert into ".$t9." values ('komments_order', 'desc');");
mysql_query ("insert into ".$t9." values ('komments_max', '102400');");
mysql_query ("insert into ".$t9." values ('komments_min', '1');"); 
mysql_query ("insert into ".$t9." values ('leftrazd', '[');"); 
mysql_query ("insert into ".$t9." values ('rightrazd', ']');"); 
mysql_query ("insert into ".$t9." values ('news_kom_switch', 'off');");
mysql_query ("insert into ".$t9." values ('mat_list', 'сука|суки|суке|сукой|суку');");
mysql_query ("insert into ".$t9." values ('flud_time', '15');");
mysql_query ("insert into ".$t9." values ('articles_kom_switch', 'off');");
mysql_query ("insert into ".$t9." values ('articles_komments', '10');");
mysql_query ("insert into ".$t9." values ('articles_komments_order', 'desc');");
mysql_query ("insert into ".$t9." values ('siteurl', 'http://localhost/deepsky/');");
mysql_query ("insert into ".$t9." values ('letter_max', '9000');");
mysql_query ("insert into ".$t9." values ('letter_min', '4');");
$sql=0;


?>