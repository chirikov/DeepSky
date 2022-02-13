<?php
require_once 'inc/config.php';
include_once 'inc/head.php';
require_once 'modules/functions.php';
?>

   <table style="width: 100%" cellspacing=1 cellpadding=0 class=articles>
     	<tr>
     	 <td colspan=2 class=hd><h1>Небо над головой</h1></td>
    	 </tr>
    	 <tr>
      <td class=cont>
        <h2>Узнайте, что сейчас можно наблюдать.</h2>
		<form name="f1" action="calendar_main.php">
Укажите своё местоположение.<br><br>
<input type="Radio" name="opt" onclick="javascript: 
f1.city.disabled = false;
f1.shir_min.disabled = true;
f1.shir_deg.disabled = true;
f1.timezone.disabled = true;
f1.opt.value = 'city';
" value="city" checked> Выбрать город:
<select name="city">
<?php
$query = mysqli_query($db, "select id,name from goroda where id > 0");
for($i=115; $i>=0; $i--) {
$r = mysqli_fetch_assoc($query);
$name = $r['name'];// mysql_result($query, $i, 'name');
$id = $r['id'];// mysql_result($query, $i, 'id');
print "<option value='".$id."'>".$name;
}
?>
</select><br>
<input type="Radio" onclick="javascript: 
f1.city.disabled = true;
f1.shir_deg.disabled = false;
f1.shir_min.disabled = false;
f1.timezone.disabled = false;
f1.opt.value = 'shir';
" name="opt" value="shir"> Ввести широту и временную зону: <input disabled type="Text" maxlength="2" size="2" name="shir_deg"> градусов <input disabled size="2" maxlength="2" type="Text" name="shir_min"> минут (от 40 до 90 градусов северной широты). 
<br>Временная зона (разница в часах с Гринвичем минус один): <input disabled type="Text" maxlength="2" size="2" name="timezone">
<br>
<input class="btn" type="Submit" value="Дальше" onclick="javascript:
if(f1.opt.value == 'shir') {
	if(f1.timezone.value < 1 || f1.timezone.value > 11) {alert('Неверная временная зона.'); return false;}
	if(f1.shir_deg.value < 0) {alert('Вы не ввели широту.'); return false;}
	if(f1.shir_deg.value > 90) {alert('Вы неверно ввели широту.'); return false;}
	if(f1.shir_min.value > 59) {alert('Вы неверно ввели широту.'); return false;}
	if(f1.shir_deg.value == 90 && f1.shir_min.value > 0) {alert('Вы неверно ввели широту.'); return false;}
	if(f1.shir_min.value < 0) {alert('Вы неверно ввели широту.'); return false;}
	if(f1.shir_deg.value < 40) {alert('Можно вводить лишь широты севернее 40 градусов северной широты.'); return false;}
}
">
</form>

      </td>
     </tr>
    </table>

  <?php
  include_once('inc/foot.php');
  ?>

