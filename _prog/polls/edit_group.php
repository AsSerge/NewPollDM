<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
// Получаем массив груп пользователей
$user_gr_quest = 'SELECT * FROM groups WHERE 1';
$groups = $db->prepare($user_gr_quest);
$groups->execute();

// Функция определения количества сотрудников в группе
function GetUserCount($db, $group_id){
  $sql = 'SELECT * FROM user WHERE group_id = ?';
  $stmt = $db->prepare($sql);
  $stmt->bindParam(1, $group_id);
  $stmt->execute();
  $users_count = $stmt->fetchAll();
  return count($users_count);
}
?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4>Почтовые группы</h4>  
<table class = 'table table-bordered table_sm'>
<tr><th width = 5%>ID</th><th width = 15%>Группа рассылки</th><th width = 70%>Описание</th><th width = 10%>Пользователи</th></tr>
<?php

foreach ($groups as $gr) {
    echo "<tr>";
    echo "<td>".$gr['group_id']."</td>";
    echo "<td><a href = '#GroupEdit' class = 'btn_md' data-toggle='modal' data-whatever = '".$gr['group_id']."|".$gr['group_name']."|".$gr['group_description']."'>".$gr['group_name']."</a></td>";    
    echo "<td>".$gr['group_description']."</td>";
    echo "<td><a href = '/_prog/polls/edit_user.php?group_id=".$gr['group_id']."'>".GetUserCount($db, $gr['group_id'])."</a></td>";
    echo "</tr>";
}
?> 
</table>
<div class="row">
    <div class = "col-md-12 button_center">
        <a href = '/_prog/polls/add_group.php' class="btn btn-primary">Добавить Группу</a>
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
    </div>    
</div>
</main>

<!-- HTML-код модального окна -->
<div id='GroupEdit' class='modal fade'>
<form method = "POST" action = "/_prog/polls/edit_group_exe.php">
<input type = "hidden" name = "group_id" value = "">
<input type = "hidden" name = 'group_action' value = "update">
  <div class='modal-dialog'>
    <div class='modal-content'>
      <!-- Заголовок модального окна -->
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
        <h4 class='modal-title'>Замена названия дизайна</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class='modal-body row'>
        <div class = 'col-md-4'>
          <input type='text' class='form-control' name = 'group_name' value = '' required>
        </div>
        <div class = 'col-md-8'>  
          <input type='text' class='form-control' name = 'group_description' value = ''>        
        </div>
      </div>
      <!-- Футер модального окна -->
      <div class='modal-footer div-center button_center'>
        <button type='button' class='btn btn-default' data-dismiss='modal'>Отмена</button>
        <button type='submit' class='btn btn-primary'>Сохранить</button>
      </div>
    </div>
  </div>
</form>
</div>





<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>