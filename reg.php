<?
function reg_form($name='', $email='', $url='', $icq='')
{
        echo "
        <form action=reg.php method=post>
        <table border=0 align=center class=articles>
        <tr><td class=cont>Имя*</tD><td class=cont><input type=text name=name value=$name></td><td class=cont>Введите ваше имя. Оно будет использоваться при авторизации.</td></tr>
        <tr><td class=cont>Пароль*</td><tD class=cont><input type=password name=pass></td><td class=cont>Введите ваш пароль. Не выбирайте лёгкий пароль, так как используя этот пароль вы будете авторизовываться.</td></tr>
        <tr><td class=cont>E-mail*</td><Td class=cont><input type=text name=email value=$email></td><td class=cont>Ваш E-mail адрес. Мы гарантируем то что ваш e-mail адрес не попадёт в спам-листы через наш сайт.</td></tr>
        <tr><td class=cont>ICQ</td><td class=cont><input type=text name=icq value=$icq></td><td class=cont>Ваш ICQ UIN. Нужен для быстрой связи с вами.</td></tr>
        <Tr><td class=cont>URL</td><td class=cont><input type=text name=url value=$url></td><td class=cont>Введите адрес вашей домашней странички, если таковая имеется.</td></tr>
        <tr><td class=cont colspan=3 align=center><input type=submit class=btn name=reg_submit value=Зарегистрироваться></td></tr>
        <tr><td class=cont colspan=3>* - обязательно для заполнения.</td></tr>
        </table>
        </form>
        ";
}
function regcheck_length ($word, $minl, $maxl)
{
        if (empty($word))
            return 0;
        else
        {
                if (strlen($word)<$minl or strlen($word)>$maxl)
                    return 1;
        }
}
include 'inc/config.php';
include 'modules/functions.php';

$vars = array("err", "name", "email", "pass", "icq", "url");
foreach($vars as $var) {
    if(isset($_GET[$var])) $$var = $_GET[$var];
    elseif(isset($_POST[$var])) $$var = $_POST[$var];
    else $$var = "";
}

if (!isset($reg_submit))
     $show_form=1;
else
{
        $err='';
        if (!isset($name) || empty($name))
            $err = "Вы не ввели ваше имя.<BR>";
        if (!isset($pass) || empty($pass))
            $err.= "Вы не ввели пароль.<br>";
        if (!isset($email) || empty($email))
            $err.= "Вы не ввели E-mail.<br>";
        if ($err)
            $show_form=1;
        else
        {
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'name_min'"));
                       $name_length_min = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'name_max'"));
                       $name_length_max = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'pass_min'"));
                       $pass_length_min = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'pass_max'"));
                       $pass_length_max = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'email_min'"));
                       $email_length_min = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'email_max'"));
                       $email_length_max = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'url_min'"));
                       $url_length_min = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'url_max'"));
                       $url_length_max = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'icq_min'"));
                       $icq_length_min = $row[v];
                $row = mysqli_fetch_array(mysqli_query ($db, "select v from ".$t9." where what = 'icq_max'"));
                       $icq_length_max = $row[v];
                if (regcheck_length($name, $name_length_min, $name_length_max))
                    $err.="Длина имени должна быть от $name_length_min до $name_length_max символов.<br>";
                if (regcheck_length($pass, $pass_length_min, $pass_length_max))
                    $err.="Длина пароля должна быть от $pass_length_min до $pass_length_max символов.<br>";
                if (regcheck_length($email, $email_length_min, $email_length_max))
                    $err.="Длина пароля должна быть от $email_length_min до $email_length_max символов.<br>";
                if (regcheck_length($url, $url_length_min, $url_length_max))
                    $err.="Длина URL адреса должна быть от $url_length_min до $url_length_max символов.<BR>";
                if (regcheck_length($icq, $icq_length_min, $icq_length_max))
                    $err.="Длина ICQ должна быть от $icq_length_min до $icq_length_max  символов.<BR>";
                if (!eregi("@", $email) or !eregi(".", $email))
                	$err.="Email введен неверно<BR>";
                    $sql = mysqli_query ($db, "select id from ".$t1." where name = '$name'");
                if (mysql_num_rows($sql)>0)
                    $err.="Пользователь с таким именемт уже зарегистрирован. Выберите другое.<BR>";
                $sql = mysqli_query ($db, "select id from ".$t1." where email = '$email'");
                if (mysql_num_rows($sql)>0)
                    $err.="Пользователь с таким e-mail уже существует. Выберите другой.<BR>";
                if (!emptY($url))
                {
                	if (!eregi("http://", $url) or !eregi(".", $url))
                		$err.="URL адрес введен неверно.<BR>";
                }
                if ($err)
                    $show_form=1;
                else
                {
                        $date = time();
                        $sql = mysqli_query ($db, "insert into ".$t1." (name, pass, pravo, email, icq, url, date) values ('$name', '$pass', 'user', '$email', '$icq', '$url', '$date');");
                        if ($sql)
                        {
                                $err = "<p>Вы успешно зарегистрированы!Теперь вы можете принять участие в жзини сайта! Вы можете добавлять свои собственные статьи и новости. Чтобы приступить к работе с сайтом, для начала нажмите <a href=login.php class=without>сюда</a>.";
                                $id = mysql_insert_id();
                                setcookie ("ngpe_id", "$id", time()+3600*24*356);
                                setcookie ("ngpe_access", "1",  time()+3600*24*356);
                                setcookie ("registered", "1", time()+3600*24*365*5);
                        }
                        else
                            $err = mysql_error();
                }
        }
}

include 'inc/head.php';
?>
        <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Регистрация</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
         
         <A href=login.php class="without">Авторизация</a><BR>
            <?
            if (isset($err))
                echo "<p><BR><b>$err</b></p>";
            if ($show_form)
                reg_form($name, $email, $url, $icq);            
            ?>
            </td></tr></table>
<?
include 'inc/foot.php';
?>
