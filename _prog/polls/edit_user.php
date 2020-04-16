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

if($_GET['group_id'] == ''){
// Получаем массив пользователей
  $user_quest = 'SELECT * FROM user LEFT JOIN groups ON user.group_id = groups.group_id';
  $users = $db->prepare($user_quest);
  $users->execute();
  // устанавливаем отображение заголовка "группы"
  $group_title = "<span class='glyphicon glyphicon-th-list'></span> Группа";
  // устанавливаем отображение заголовка "страницы"
  $page_title_local = "Список всех пользователей";
}else{
  $group_id = $_GET['group_id'];
  $user_quest = 'SELECT * FROM user LEFT JOIN groups ON user.group_id = groups.group_id WHERE user.group_id = ?';
  $users = $db->prepare($user_quest);
  $users->execute([$group_id]);
  // устанавливаем отображение заголовка "группы"
  $group_title = "<a href = '/_prog/polls/edit_user.php'><span class='glyphicon glyphicon-list'></span> Показать всех</a>";
  // устанавливаем отображение заголовка "страницы"
  $page_title_local = "Список пользователей группы";
}

?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4><?=$page_title_local?></h4>  
<table class = 'table table-bordered table_sm'>
<tr><th width = 5%>ID</th><th width = 40%>Пользователь</th><th width = 40%>Почта</th><th width = 15%><?=$group_title?></th><th></th></tr>
<?php

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>";
    echo $user['u_id'];
    echo "</td>";
    echo "<td>";    
    echo "<a href = '#UserEdit' class = 'btn_md' data-toggle='modal' data-whatever = '".$user['u_id']."|".$user['u_name']."|".$user['u_mail']."|".$user['group_id']."'>".$user['u_name']."</a>";
    echo "</td>";
    echo "<td>";
    echo $user['u_mail'];
    echo "</td>";    
    echo "<td>";
    echo "<a href = '/_prog/polls/edit_user.php?group_id=".$user['group_id']."'>".$user['group_name']."</a>";    
    echo "</td>";
    echo "<td>";
    echo "<a href = '/_prog/polls/edit_user_exe.php?u_id=".$user['u_id']."&user_action=remove' class='btn btn-danger btn-xs'><span class='glyphicon glyphicon-remove'></span></a>";    
    echo "</td>";    
    echo "</tr>";
}
?> 
</table>
<div class="row">
    <div class = "col-md-12 button_center">
        <a href = '/_prog/polls/add_user.php?group_id=<?=$group_id?>&add_type=one' class="btn btn-primary"><span class='glyphicon glyphicon-user'></span> Добавить одного пользователя</a>
        <a href = '/_prog/polls/add_user.php?group_id=<?=$group_id?>&add_type=multy' class="btn btn-primary"><span class='glyphicon glyphicon-list-alt'></span> Добавить пользователей из файла</a>
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
    </div>    
</div>

<!-- HTML-код модального окна редактирования одного пользователя-->
<div id='UserEdit' class='modal fade'>
<form method = "POST" action = "/_prog/polls/edit_user_exe.php">
<input type = "hidden" name = "u_id" value = "">
<input type = "hidden" name = 'user_action' value = "update">
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
          <input type='text' class='form-control' name = 'u_name' value = '' required>
        </div>
        <div class = 'col-md-5'>  
          <input type='text' class='form-control' name = 'u_mail' value = '' required>        
        </div>
        <div class = 'col-md-3'>  
          <select class='form-control' name = 'group_id' value = ''>
            <?php
            foreach ($groups as $row){
              echo "<option value = '".$row['group_id']."'>".$row['group_name']."</option>\r\n";
            }
            ?>
          </select>
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
<!-- Конец модального окна редактирования одного пользователя-->
</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>