<?
error_reporting(0);
/////////////////////////////////////////////////
$dbname='deepsky';
$dbuser='root';
$dbpw='password';
$dbhost='localhost';
/////////////////////////////////////////////////
$t1 = 'users';
$t2 = 'prava';
$t3 = 'news';
$t4 = 'articles_kat';
$t5 = 'articles';
$t6 = 'files_kat';
$t7 = 'files';
$t8 = 'news_kat';
$t9 = 'configs';
$t10 = 'news_komments';
$t11 = 'articles_komments';
$t12 = 'votes';
$t13 = 'voteans';
$t14 = 'tips';
$t15 = 'gal_kats';
$t16 = 'gal';
/////////////////////////////////////////////////
$db=mysql_connect("$dbhost", "$dbuser","$dbpw");
mysql_select_db("$dbname",$db);
?>