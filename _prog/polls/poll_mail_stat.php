<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');

$poll_id = $_GET['poll_id'];
$mailing_id = $_GET['mailing_id'];
// Определеям название опроса
$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn(); // Название опроса

// Считаем общее количество вопросов в опросе
$sql_n = 'SELECT * FROM question WHERE poll_id = ?';
$quest_n = $db->prepare($sql_n);
$quest_n->execute([$poll_id]);
$q_number = $quest_n->rowCount(); //количество строк, удовлетворяющих запросу

// Признак завершения голосования - запись о голосованиии по конкретному вопросу - присутствует в poll_result: 
function GetQuestCount($db, $poll_id, $u_id){
	$sql = 'SELECT DISTINCT question_id FROM poll_result WHERE poll_id = ? AND u_id = ?';
	$quest = $db->prepare($sql);
	$quest->execute([$poll_id, $u_id]);
	return $quest->rowCount(); //количество строк, удовлетворяющих запросу
  }

// Получаем массив пользователей - получателей рассылки
$sql_recipient = "SELECT * FROM poll_mailing_stat LEFT JOIN user ON user.u_id = poll_mailing_stat.u_id WHERE mailing_id = ?";
$users_recipient = $db->prepare($sql_recipient);
$users_recipient->execute([$mailing_id]);

// Получаем массив пользователей, доступных для дополнительной рассылки
$sql_further = "SELECT * FROM user LEFT JOIN groups ON groups.group_id = user.group_id WHERE 1";
$users_further = $db->prepare($sql_further);
$users_further->execute([$mailing_id]);
?>
<style>
.button_center{
	text-align: center;
}
.stat{
	background-color: #3a366a;
	color: white;
}
.stat_passed{
	background-color: #cdffdb;
}
.stat_wait{
	background-color: #fff1cd;
}
.b_margin{
	margin-bottom: 20px;
}
</style>
<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4>Статистика опроса "<?=$poll_name;?>"</h4>
<h5>Всего вопросов: <?=$q_number;?></h5>
<table class = 'table table-bordered table_sm'>
<tr class = 'stat'><th width = 5%>ID</th><th>Ответы</th><th width = 40%>Пользователь</th><th width = 40%>Почта</th><th width = 15%>Отправлено</th></tr>
<?php
foreach ($users_recipient as $user){
	// Создаем массив ID пользователей, которым отправлено сообщение
	$received_users[] = $user['u_id']; // Добавляем пользователя
	// Проверяем количество ответов из опроса, на которые ответил пользователь
	if(GetQuestCount($db, $poll_id, $user['u_id']) >= $q_number){
		$tr_class = 'stat_passed';
	}else{
		$tr_class = 'stat_wait';
	}
	echo "<tr class = '{$tr_class}'>";
	echo "<td>{$user['u_id']}</td>";
	echo "<td>".GetQuestCount($db, $poll_id, $user['u_id'])."</td>";
	echo "<td><a href = 'http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$user['u_token']}' target = '_blanc'>{$user['u_name']}</a></td>";
	echo "<td>{$user['u_mail']}</td>";
	echo "<td>{$user['mailing_date']}</td>";
	echo "</tr>";
}
?>
</table>

<div class="panel-group" id="accordion">
  <!-- Спсиок возможных вариантов -->
  <div class="panel panel-default">
	<!-- Заголовок 1 панели -->
	<div class="panel-heading">
	  <h4 class="panel-title">
		<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">Список доступных пользователей</a>
	  </h4>
	</div>
	<div id="collapseOne" class="panel-collapse collapse out">
	<!-- Содержимое 1 панели -->
	<div class="panel-body">
	<table class = 'table table-bordered table_sm'>
	<tr class = 'stat'><th width = 5%>ID</th><th width = 40%>Пользователь</th><th width = 40%>Почта</th><th>Группа</th><th></th></tr>
		<?php
		foreach ($users_further as $user_f){
			if(!in_array($user_f['u_id'], $received_users)){
				echo "<tr>";
				echo "<td>".$user_f['u_id']."</td>";
				echo "<td>".$user_f['u_name']."</td>";
				echo "<td>".$user_f['u_mail']."</td>";
				echo "<td>".$user_f['group_name']."</td>";
				echo "<td>";
				$send_str = "onclick=\"javascript:document.location.href='/_prog/polls/poll_mail_one_send_exe.php?poll_id={$poll_id}&mailing_id={$mailing_id}&u_id={$user_f['u_id']}'\"";
				echo "<button type='button' class='btn btn-success btn-xs' {$send_str}><span class='glyphicon glyphicon-envelope'></span></button>";
				echo "</td>";
				echo "</tr>";
			}
		}
		?>
	</table>
	<div class="row">
		<div class = "col-md-12 button_center">
			<a href = '/_prog/polls/add_user.php?group_id=<?=$group_id?>&add_type=one' class="btn btn-primary"><span class='glyphicon glyphicon-user'></span> Добавить одного пользователя</a>
			<a href = '/_prog/polls/add_user.php?group_id=<?=$group_id?>&add_type=multy' class="btn btn-primary"><span class='glyphicon glyphicon-list-alt'></span> Добавить пользователей из файла</a>        
		</div>
	</div>
	</div>
	</div>
  </div>
</div>

<div class="row b_margin">
	<div class = "col-md-12 button_center">	
		<input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
	</div>
</div>


</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>