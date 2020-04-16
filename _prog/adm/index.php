<?php
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'prog/layot/top_menu.php');
?>
<div class="container">
	<div style = "clear: both; height: 50px;"></div>
<?php
echo "Страница админа<br>";	
echo $userdata['user_id']."<br>";
echo $userdata['user_name']."&nbsp;".$userdata['user_surname']."-".$userdata['user_role']."<br>";

echo "Здесь выводим бланк заказа";
echo "<br>";
?>
<a href = 'prog/blank/test.php'>Это ссылка</a>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/prog/layot/bottom_site.php');
?>
