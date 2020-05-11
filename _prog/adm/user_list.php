<?php
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');

include('../../rnd/connect/connect_to_base.php');
?>
<?php
$poll_name_quest = 'SELECT * FROM users WHERE 1';
$query = $db->query($poll_name_quest);
?>
<div class="container">
<div style = "clear: both; height: 50px;"></div>
<h1>Список пользователей</h1>

<table class="table table-bordered table-striped table-responsive">
      <thead>
        <tr>
          	<th>ID</th>
			<th>Логин</th>
          	<th>Фамилия Имя</th>
          	<th>Город</th>
		 	<th>Телефон</th>
        	<th>Почта</th>
          	<th>Активность</th>
          	<th>Роль</th>
        </tr>
      </thead>
      <tbody>
<?php
	while($row=$query->fetch()){
		echo "<tr>";
			echo "<td>".$row['user_id']."</td>";
			echo "<td>".$row['user_login']."</td>";
			echo "<td>".$row['user_name']."&nbsp;".$row['user_surname']."</td>";
			echo "<td>".$row['user_city']."</td>";
			echo "<td>".$row['user_phone']."</td>";
			echo "<td>".$row['user_mail']."</td>";
			echo "<td>".$row['user_status']."</td>";
			echo "<td>".$row['user_role']."</td>";
		echo "</tr>\n\r";
	}
?>

      </tbody>	

</table>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>