<?
function add_form($author, $title='', $kid='', $short='', $full='', $mid='', $ngcid='')
{
        //ARTICLES ADD FORM... YOU CAN CHANGE DESIGN ELEMENTS HERE
        echo"
        <form action=articles.php method=post>
        <input type=hidden name=act value=add>
        <table class=articles>
        <tr><td class=cont>�������� ������</td><td><input type=text name=title value=\"$title\"></td></tr>
        <tr><Td class=cont>�����</td><td><input type=text name=author value=\"$author\"></td></tR>
        <tr><td class=cont>����� ������� �� NGC (������ ����� ��� ������� �����. ���� ����� �� IC, �� ����� ������� ��� ������� ��������� I):</td><td class=cont><input type=text name=ngcid value=\"$ngcid\"></td></tr>
		<tr><td class=cont>����� ������� �� �������� ������ (������ �����):</td><td class=cont><input type=text name=mid value=\"$mid\"></td></tr>
	    <tr><td class=cont>���������</td><td><select name=kid>
        ";
        global $t4;
        $sql = mysql_query ("select kat, id from ".$t4." where pid = '1' order by kat");
        while ($row = mysql_fetch_Array($sql))
        {
                echo "<option value=$row[id]";
                if ($kid==$row[id])echo " selected";
                echo ">$row[kat]";
                $sql2 = mysql_query ("select kat, id from ".$t4." where pid = '$row[id]' order by kat");
                while ($row2 = mysql_fetch_array($sql2))
                {
                        echo "<option value=$row2[id]";
                        if ($kid==$row2[id])echo " selected";
                        echo ">--$row2[kat]";
                }
        }
        echo "</td></tr>
        <tr><td class=cont>������� �������� ������</td><td><textarea name=short rows=4 cols=25>$short</textarea></td></tr>
        <tr><td class=cont>������</td><td><textarea name=full rows=8 cols=50>$full</textarea></td></tr>
        <tr><td colspan=2 align=center><input class=btn type=submit name=add_sub value=��������></td></tr>
        </table></form>";
}
function articles_filter($text, $pre)
{
         global $t9;
         $text = strip_tags($text,"<b><i><a><p><br><img><h1><h2><h3><h4><h5><div><em><table><tr><td>");
         $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = '".$pre."_min'"));
         $minl = $row[v];
         $row = mysql_fetch_array(mysql_query ("select v from ".$t9." where what = '".$pre."_max'"));
         $maxl = $row[v];
         if (strlen($text)>$maxl or strlen($text)<$minl)
             $text="";
         return $text;
}
function article_insert($status, $title, $kid, $short, $full, $author, $mid, $ngcid)
{
         global $t5;
         $sql = mysql_query ("select id from ".$t5." where title = '$title' and about = '$short' and kid = '$kid' limit 1");
         if (mysql_num_rows($sql)>0)
             $res = "������ ������ ��� �������� � ����� ����.<BR>";
         else
         {
                 $date = time();
                 $ngc = strtoupper($ngc); 
				 // learning object id
				 if($mid == "" && $ngcid == "") $objid = "";
				 elseif($ngcid != "" && $ngcid != " " && $ngcid != "-") $objid = @mysql_result(mysql_query("select id from objects where ngc = '".$ngcid."'"), 0, 'id') or $objid = "";
				 elseif($mid != "" && $mid != " " && $mid != "-") $objid = @mysql_result(mysql_query("select id from objects where messier = '".$mid."'"), 0, 'id') or $objid = "";
				 //
                 $sql = mysql_query ("insert into ".$t5." (status, title, kid, about, article, author, date, objid) values ('$status', '$title', '$kid', '$short', '$full', '$author', '$date', '$objid');");
                 if ($sql)
                     $res = "������ ������� ��������� � ����.";
                 else
                     $res = "������ ���� ������: ".mysql_error();
         }
         return $res;
}
function new_list()
{
        global $t5, $t4;
        $sql = mysql_query ("select id, kid, title, about from ".$t5."  where status = 'mwait'");
        while ($row = mysql_fetch_array($sql))
        {
               $row2 = mysql_fetch_array(mysql_query("select kat from ".$t4." where id = '$row[kid]'"));
               echo"
               <A href=\"?act=moderate&mact=new&id=$row[id]\" class=without>$row[title]</a><BR>
               &nbsp;&nbsp;$row[about]<br>
               ��������� - $row2[kat]<BR><BR>
               ";
        }
}
function new_form($id)
{
        global $t5, $t4;
        $sql = mysql_query ("select kid, title, about, article, author, objid from ".$t5." where id = '$id'");
        $row = mysql_fetch_array($sql);
		$qq = mysql_query("select ngc, messier from objects where id = '".$row['objid']."'");
		$ngcid = mysql_result($qq, 0, 'ngc');
		$mid = mysql_result($qq, 0, 'messier');
        //ARTICLES MODERATION FORM...
        echo "
        <form action=articles.php metoh=post>
        <input type=hidden name=act value=moderate>
        <input type=hidden name=mact value=new>
        <input type=hidden name=id value=$id>
        <table class=articles>
        <tr><td class=cont>�������� ������</td><td><input type=text name=title value=\"$row[title]\"></td></tR>
        <tr><td class=cont>�����</tD><td><input type=text name=author value=\"$row[author]\"></tD></tR>
        <tr><td class=cont>����� ������� �� NGC (������ ����� ��� ������� �����. ���� ����� �� IC, �� ����� ������� ��� ������� ��������� I):</td><td class=cont><input type=text name=ngcid value=\"$ngcid\"></td></tr>
		<tr><td class=cont>����� ������� �� �������� ������ (������ �����):</td><td class=cont><input type=text name=mid value=\"$mid\"></td></tr>
        <tr><td class=cont>���������</tD><td><select name=kid>";
        $sql1 = mysql_query ("select kat, id from ".$t4." where pid = '1' order by kat");
        while ($row1 = mysql_fetch_Array($sql1))
        {
                echo "<option value=$row1[id]";
                if ($row[kid]==$row1[id])echo " selected";
                echo ">$row1[kat]";
                $sql2 = mysql_query ("select kat, id from ".$t4." where pid = '$row1[id]' order by kat");
                while ($row2 = mysql_fetch_array($sql2))
                {
                        echo "<option value=$row2[id]";
                        if ($row[kid]==$row2[id])echo " selected";
                        echo ">--$row2[kat]";
                }
        }
        echo "</td></tr>
        <tr><td class=cont>������� ��������</td><td><textarea name=short rows=4 cols=25>$row[about]</textarea></td></tr>
        <tr><td class=cont>������</td><td><textarea name=full rows=8 cols=50>$row[article]</textarea></td></tR>
        <tr><td><input type=submit class=btn name=new_sub value=������������></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<a class=without href=\"?act=del&id=$id\">�������</a></td></tr>
        </table></form>";
}
function new_public($id, $kid, $title, $short, $full, $author, $mid, $ngcid)
{
        global $t5;
		// learning object id
		if($mid == "" && $ngcid == "") $objid = "";
		elseif($ngcid != "" && $ngcid != " " && $ngcid != "-") $objid = @mysql_result(mysql_query("select id from objects where ngc = '".$ngcid."'"), 0, 'id') or $objid = "";
		elseif($mid != "" && $mid != " " && $mid != "-") $objid = @mysql_result(mysql_query("select id from objects where messier = '".$mid."'"), 0, 'id') or $objid = "";
		//
        $sql = mysql_query ("update ".$t5." set kid = '$kid', title = '$title', about = '$short', article = '$full', author = '$author', objid = '$objid', status = 'ok' where id = '$id'");
        if ($sql)
            echo "������ ������������!<BR>";
        else
            echo mysql_error();
}
function old_list()
{
        global $t5, $t4;
        $sql = mysql_query ("select id, kid, title, about from ".$t5."  where status = 'ok'");
        while ($row = mysql_fetch_array($sql))
        {
               $row2 = mysql_fetch_array(mysql_query("select kat from ".$t4." where id = '$row[kid]'"));
               echo"
               <A class=without href=\"?act=moderate&mact=new&id=$row[id]\">$row[title]</a><BR>
               &nbsp;&nbsp;$row[about]<br>
               ��������� - $row2[kat]<BR><BR>
               ";
        }
}
function kat_list($t='')
{
        global $t4;
        $sql = mysql_query ("select * from ".$t4." where pid = '1'");
        while ($row = mysql_fetch_array($sql))
        {
               echo "<a href=\"?";
               if ($t=='m')echo"act=moderate&mact=old&oldact=artlist&";
               echo"kid=$row[id]\">$row[kat]</a><BR>";
               $sql2 = mysql_query ("select * from ".$t4." where pid = '$row[id]'");
               while ($row2 = mysql_fetch_array($sql2))
               {
                       echo "&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"?";
                       if ($t=='m')echo"act=moderate&mact=old&oldact=artlist&";
                       echo"kid=$row2[id]\">$row2[kat]</a><BR>";
               }
        }
}
function articles_list($kid, $page, $t='m')
{
        global $t4, $t5, $t9;
        $articles_show_row = mysql_fetch_Array(mysql_query("select v from ".$t9." where what = 'articles_per_page'"));
        $articles_show = $articles_show_row[v];
        if (empty($page))$page=0;
        else $page--;
        $sql = mysql_query ("select kat from ".$t4." where id = '$kid'");
        $row = mysql_fetch_Array($sql);
        echo "<h3>��������� $row[kat]</h3><BR>";
        $sql = mysql_query ("select * from ".$t4." where pid = '$kid'");
        if (mysql_num_rows($sql)>0)
        {
                echo "��������� ���������:<BR>";
                while ($row = mysql_fetch_Array($sql))
                {
                       echo "<a class=without href=\"?";
                       if ($t=='m')echo"act=moderate&mact=old&oldact=artlist&";
                       echo"kid=$row[id]\">$row[kat]</a><BR>";
                }
                echo  "<BR>";
        }
        $sql = mysql_query ("select * from ".$t5." where status = 'ok' and kid = '$kid' order by date DESC limit ".$articles_show*$page.", ".$articles_show."");
        if (mysql_num_rows($sql)==0)
            echo "������ � ������ ��������� ���� ���.";

        while ($row = mysql_fetch_array($sql))
        {
               echo "<a href=\"?act=moderate&mact=old&oldact=form&id=$row[id]\">$row[title]</a><br>
                      &nbsp;&nbsp;&nbsp;&nbsp;$row[about]<BR><BR>";
        }
            $sql = mysql_query ("select id from ".$t5." where status = 'ok' and kid='$kid'");
            $numpages = ceil (mysql_num_rows($sql)/$articles_show);
        if ($numpages>1)
        {
            echo "<center>";
            $i=0; $k=0;
            if ($page>4)
            echo "&nbsp;[&nbsp;<a class=without href=\"?act=moderate&mact=old&oldact=artlist&page=1\">1</a>&nbsp;]&nbsp; ... ";
            while ($i<$numpages)
            {
                $i++;
                if ($k<9 and $page-$i<4)
                {
                      $k++;
                      if ($i==($page+1))
                              echo "&nbsp;".$i."&nbsp;";
                      else
                              echo "&nbsp;[&nbsp;<a class=without href=\"?act=moderate&mact=old&oldact=artlist&page=".$i."\">".$i."</a>&nbsp;]&nbsp;";
                }
                elseif ($i-$page>=4 and $i!=$numpages and $z!=1){
                        echo " ... ";$z=1;}
                elseif ($i==$numpages)
                        echo "&nbsp;[&nbsp;<a class=without href=\"?act=moderate&mact=old&oldact=artlist&page=".$i."\">".$i."</a>&nbsp;]&nbsp;";

             }
             echo "</center>";
        }
}
function old_m_form($id)
{
        global $t5, $t4;
        $sql = mysql_query ("select * from ".$t5." where id = '$id'");
        $row = mysql_fetch_array($sql);
		$qq = mysql_query("select ngc, messier from objects where id = '".$row['objid']."'");
		$ngcid = mysql_result($qq, 0, 'ngc');
		$mid = mysql_result($qq, 0, 'messier');
        echo"<form action=articles.php method=post>
        <input type=hidden name=id value=$id>
        <input type=hidden name=act value=moderate>
        <input type=hidden name=mact value=old>
        <input type=hidden name=oldact value=update>
        <table class=articles>
        <tr><td class=cont>�������� ������</td><td><input type=text name=title value=\"$row[title]\"></td></tR>
        <tr><td class=cont>�����</tD><td><input type=text name=author value=\"$row[author]\"></tD></tR>
        <tr><td class=cont>����� ������� �� NGC (������ ����� ��� ������� �����. ���� ����� �� IC, �� ����� ������� ��� ������� ��������� I):</td><td class=cont><input type=text name=ngcid value=\"$ngcid\"></td></tr>
		<tr><td class=cont>����� ������� �� �������� ������ (������ �����):</td><td class=cont><input type=text name=mid value=\"$mid\"></td></tr>
        <tr><td class=cont>���������</tD><td><select name=kid>";
        $sql1 = mysql_query ("select kat, id from ".$t4." where pid = '1' order by kat");
        while ($row1 = mysql_fetch_Array($sql1))
        {
                echo "<option value=$row1[id]";
                if ($row[kid]==$row1[id])echo " selected";
                echo ">$row1[kat]";
                $sql2 = mysql_query ("select kat, id from ".$t4." where pid = '$row1[id]' order by kat");
                while ($row2 = mysql_fetch_array($sql2))
                {
                        echo "<option value=$row2[id]";
                        if ($row[kid]==$row2[id])echo " selected";
                        echo ">--$row2[kat]";
                }
        }
        echo "</td></tr>
        <tr><td class=cont>������� ��������</td><td><textarea name=short rows=4 cols=25>$row[about]</textarea></td></tr>
        <tr><td class=cont>������</td><td><textarea name=full rows=8 cols=50>$row[article]</textarea></td></tR>
        <tr><td><input class=btn type=submit name=mod_sub value=��������></td><td>&nbsp;&nbsp;&nbsp;&nbsp;<a class=without href=\"?act=del&id=$id\">�������</a></td></tr>
        </table></form>";
}
function old_insert($id, $kid, $author, $title, $short, $full, $mid, $ngcid)
{
        global $t5;
		// learning object id
		if($mid == "" && $ngcid == "") $objid = "";
		elseif($ngcid != "" && $ngcid != " " && $ngcid != "-") $objid = @mysql_result(mysql_query("select id from objects where ngc = '".$ngcid."'"), 0, 'id') or $objid = "";
		elseif($mid != "" && $mid != " " && $mid != "-") $objid = @mysql_result(mysql_query("select id from objects where messier = '".$mid."'"), 0, 'id') or $objid = "";
		//
        $sql = mysql_query ("update ".$t5." set kid = '$kid', author = '$author', title = '$title', about = '$short', article = '$full', objid = '$objid' where id = '$id'");
        if ($sql)
            echo "������ ������� ���������!";
        else
            echo mysql_error();
}

?>
