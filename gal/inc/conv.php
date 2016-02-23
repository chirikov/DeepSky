<?
include 'config.php';
$sql = mysql_query ("select * from nuke_stories where 1 order by time");
while ($row = mysql_fetch_array($sql))
{
        $da = explode ("-", $row['time']);
        $year = $da[0];
        $m = $da[1];
        $da2 = explode (" ", $da[2]);
        $d = $da2[0];
        $da3 =  explode (":", $da2[1]);
        $h = $da3[0];
        $mi = $da3[1];
        $s = $da3[2];
        $date = mktime ($h, $mi, $s, $m, $d, $year);
        $id = $row[topic];
        $bodytext = eregi_replace ("\"", "\\\"", $row[bodytext]);
        $hometext = eregi_replace ("\"", "\\\"", $row[hometext]);
        $title = eregi_replace ("\"", "\\\"", $row[title]);
        mysql_query ("insert into ".$t3." (kid, news_full, news_short, title, author, status, date) values ('$id', \"$bodytext\", \"$hometext\", \"$title\", '1', 'ok', '$date');");
        echo mysql_error()."<BR>";

}
$sql = mysql_query ("select title, description from nuke_pages_categories where 1 order by cid");
while ($row = mysql_fetch_array($sql))
{
        mysql_query ("insert into ".$t4." (pid, kat, about) values ('1', '$row[title]', '$row[description]');");
}
$sql = mysql_query ("select cid, title, text, date, counter from nuke_pages  order by date");
while ($row = mysql_fetch_array($sql))
{
        $da = explode ("-", $row['date']);
        $year = $da[0];
        $m = $da[1];
        $da2 = explode (" ", $da[2]);
        $d = $da2[0];
        $da3 =  explode (":", $da2[1]);
        $h = $da3[0];
        $mi = $da3[1];
        $s = $da3[2];
        $date = mktime ($h, $mi, $s, $m, $d, $year);
        $kid = $row[cid]+1;
        $text = eregi_replace ("\"", "\\\"", $row[text]);
        $title = eregi_replace ("\"", "\\\"", $row[title]);
        mysql_query ("insert into ".$t5." (kid, author, title, article, date, readnum, status) values (\"$kid\", 'Vulko', \"$title\", \"$text\", \"$date\", \"$row[counter]\", \"ok\");");
        if (mysql_error())
            echo mysql_error()."<BR>";
}
