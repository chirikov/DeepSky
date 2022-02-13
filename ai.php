<?
require 'inc/config.php';
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t9."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t9." (
`what` varchar(250) NOT NULL default '',
`v` text NOT NULL default ''
)
");
if ($sql)echo "Таблица $t9 создана!<br>";
else echo mysql_error();
mysqli_query ($db, "insert into ".$t9." values ('art_title_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('art_title_min', '2');");
mysqli_query ($db, "insert into ".$t9." values ('art_short_max', '550');");
mysqli_query ($db, "insert into ".$t9." values ('art_short_min', '5');");
mysqli_query ($db, "insert into ".$t9." values ('art_full_max', '1048576');");
mysqli_query ($db, "insert into ".$t9." values ('art_full_min', '5');");
mysqli_query ($db, "insert into ".$t9." values ('name_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('name_min', '2');");
mysqli_query ($db, "insert into ".$t9." values ('pass_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('pass_min', '3');");
mysqli_query ($db, "insert into ".$t9." values ('email_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('email_min', '3');");
mysqli_query ($db, "insert into ".$t9." values ('icq_max', '15');");
mysqli_query ($db, "insert into ".$t9." values ('icq_min', '4');");
mysqli_query ($db, "insert into ".$t9." values ('url_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('url_min', '10');");
mysqli_query ($db, "insert into ".$t9." values ('news_title_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('news_title_min', '2');");
mysqli_query ($db, "insert into ".$t9." values ('news_short_max', '550');");
mysqli_query ($db, "insert into ".$t9." values ('news_short_min', '5');");
mysqli_query ($db, "insert into ".$t9." values ('news_full_max', '8388608');");
mysqli_query ($db, "insert into ".$t9." values ('news_full_min', '5');");
mysqli_query ($db, "insert into ".$t9." values ('news_per_page', '5');");
mysqli_query ($db, "insert into ".$t9." values ('articles_per_page', '10');");
mysqli_query ($db, "insert into ".$t9." values ('news_index', '3');");
mysqli_query ($db, "insert into ".$t9." values ('news_komments', '10');");
mysqli_query ($db, "insert into ".$t9." values ('komments_order', 'desc');");
mysqli_query ($db, "insert into ".$t9." values ('komments_max', '102400');");
mysqli_query ($db, "insert into ".$t9." values ('komments_min', '1');"); 
mysqli_query ($db, "insert into ".$t9." values ('leftrazd', '[');"); 
mysqli_query ($db, "insert into ".$t9." values ('rightrazd', ']');"); 
mysqli_query ($db, "insert into ".$t9." values ('news_kom_switch', 'off');");
mysqli_query ($db, "insert into ".$t9." values ('mat_list', 'сука|суки|суке|сукой|суку');");
mysqli_query ($db, "insert into ".$t9." values ('flud_time', '15');");
mysqli_query ($db, "insert into ".$t9." values ('articles_kom_switch', 'off');");
mysqli_query ($db, "insert into ".$t9." values ('articles_komments', '10');");
mysqli_query ($db, "insert into ".$t9." values ('articles_komments_order', 'desc');");
mysqli_query ($db, "insert into ".$t9." values ('siteurl', 'http://localhost/deepsky/');");
mysqli_query ($db, "insert into ".$t9." values ('letter_max', '9000');");
mysqli_query ($db, "insert into ".$t9." values ('letter_min', '4');");
$sql=0;


?>