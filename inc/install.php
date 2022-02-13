<?php
require 'config.php';
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t1."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t1." (

`id` int(10) AUTO_INCREMENT NOT NULL,
`name` varchar(250) NOT NULL default '',
`pass` varchar(250) NOT NULL default '',
`pravo` varchar(25) NOT NULL default '',
`email` varchar (250) NOT NULL default '',
`icq` varchar(25) NOT NULL default '',
`url` varchar(250) NOT NULL default '',
`date` int(10) NOT NULL default '0',
PRIMARY KEY(id)

)
");
if ($sql)echo "Таблица $t1 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t2."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t2." (
`pravo` varchar(25) NOT NULL default '',
`newsadd` char(3) NOT NULL default '',
`newsedit` char(3) NOT NULL default '',
`articlesadd` char(3) NOT NULL default '',
`articlesedit` char(3) NOT NULL default '',
`filesadd` char(3) NOT NULL default '',
`filesedit` char(3) NOT NULL default '',
`votesadd` char(3) NOT NULL default '',
`votesedit` char(3) NOT NULL default '',
`photoadd` char(3) NOT NULL default ''
)
");
if ($sql)echo "Таблица $t2 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t3."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t3." (

`id` int(10) AUTO_INCREMENT NOT NULL,
`kid` int(10) NOT NULL default '1',
`news_full`  text NOT NULL,
`news_short` text NOT NULL,
`title` varchar(250) NOT NULL default '',
`author` int(10) NOT NULL,
`status` varchar(25) NOT NULL default '',
`date` int(10) NOT NULL default '0',
PRIMARY KEY(id)

)
");
if ($sql)echo "Таблица $t3 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t4."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t4." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`pid` int(10)  NOT NULL default '0',
`kat`  varchar(250) NOT NULL default '',
`about` varchar(250) NOT NULL default '',
PRIMARY KEY(id)

)
");
mysqli_query ($db, "insert into ".$t4." (kat) values ('a');");
mysqli_query ($db, "delete from ".$t4." where 1");
if ($sql)echo "Таблица $t4 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t5."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t5." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`kid` int(10) NOT NULL default '0',
`about` text NOT NULL,
`title` varchar(250) NOT NULL default '',
`article` text NOT NULL,
`status` char(20) NOT NULL default '',
`readnum` int(15) NOT NULL default '0',
`votenum` int(15) NOT NULL default '0',
`voterate` int(150) NOT NULL default '0',
`rate` DECIMAL(10,2) NOT NULL default '0',
`rateip` text NOT NULL,
`rateids` text NOT NULL,
`author` varchar(250) NOT NULL default '',
`date` int(10) NOT NULL default '0',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t5 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t6."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t6." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`kat`  varchar(250) NOT NULL default '',
`about` varchar(250) NOT NULL default '',
PRIMARY KEY(id)

)
");
if ($sql)echo "Таблица $t6 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t7."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t7." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`kid` int(10) NOT NULL default '0',
`about` text NOT NULL,
`title` varchar(250) NOT NULL default '',
`status` char(20) NOT NULL default '',
`downnum` int(15) NOT NULL default '0',
`votenum` int(15) NOT NULL default '0',
`voterate` int(150) NOT NULL default '0',
`author` int(10) NOT NULL,
`date` int(10) NOT NULL default '0',
`filename` varchar(250) NOT NULL default '',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t7 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t8."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t8." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`kat`  varchar(250) NOT NULL default '',
`about` varchar(250) NOT NULL default '',
PRIMARY KEY(id)

)
");
if ($sql)echo "Таблица $t8 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t9."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t9." (
`what` varchar(250) NOT NULL default '',
`v`  varchar(250) NOT NULL default ''
)
");
if ($sql)echo "Таблица $t9 создана!<br>";
else echo mysql_error();
mysqli_query ($db, "insert into ".$t9." values ('art_title_max', '250');");
mysqli_query ($db, "insert into ".$t9." values ('art_title_min', '2');");
mysqli_query ($db, "insert into ".$t9." values ('art_short_max', '550');");
mysqli_query ($db, "insert into ".$t9." values ('art_short_min', '5');");
mysqli_query ($db, "insert into ".$t9." values ('art_full_max', '8388608');");
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
mysqli_query ($db, "insert into ".$t9." values ('articles_per_page', '5');");
mysqli_query ($db, "insert into ".$t9." values ('news_index', '10');");
mysqli_query ($db, "insert into ".$t9." values ('news_komments', '10');");
mysqli_query ($db, "insert into ".$t9." values ('news_komments_order', 'desc');");
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t10."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t10." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`nid` int(10) NOT NULL default '0',
`author` varchar(250) NOT NULL default '',
`message` text NOT NULL,
`date` int(10) NOT NULL,
`email` varchar(250) NOT NULL default '',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t10 создана!<br>";
else echo mysql_error();
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t11."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t11." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`aid` int(10) NOT NULL default '0',
`author` varchar(250) NOT NULL default '',
`message` text NOT NULL,
`email` varchar(250) NOT NULL default '',
`date` int(10) NOT NULL,
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t11 создана!<br>";
else echo mysql_error();
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t12."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t12." (

`id` int(10) AUTO_INCREMENT NOT NULL,
`vote` varchar(250) NOT NULL default '',
`type` int(1) NOT NULL default '1',
`active` int(1) NOT NULL default '0',
`voteips` text NOT NULL,
`voteids` text NOT NULL,
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t12 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t13."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t13." (

`id` int(10) AUTO_INCREMENT NOT NULL,
`vid` int(10) NOT NULL default '0',
`numvotes` int(20) NOT NULL default '0',
`ans` varchar(250) NOT NULL default '',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t13 создана!<br>";
else echo mysql_error();
$sql=0;
///////////////////////////////
mysqli_query ($db, "drop table if exists ".$t14."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t14." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`tips` varchar(250) NOT NULL default '',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t14 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t15."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t15." (

`id` int(10) AUTO_INCREMENT NOT NULL,
`kat` varchar(250) NOT NULL default '',
PRIMARY KEY(id)

)
");
if ($sql)echo "Таблица $t15 создана!<br>";
else echo mysql_error();
$sql=0;
//////////////////////////////////////////////////
mysqli_query ($db, "drop table if exists ".$t16."");
$sql = mysqli_query ($db, "CREATE TABLE ".$t16." (
`id` int(10) AUTO_INCREMENT NOT NULL,
`kid` int(10) NOT NULL default '0',
`photo` varchar(250) NOT NULL default '',
`author` varchar(250) NOT NULL default '',
`url` varchar(100) NOT NULL default '',
PRIMARY KEY(id)
)
");
if ($sql)echo "Таблица $t16 создана!<br>";
else echo mysql_error();
$sql=0;
?>