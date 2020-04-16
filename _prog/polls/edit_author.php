<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
$page_title_local = "Список авторов"; // Заголовок
$author_quest = 'SELECT * FROM author WHERE 1';
$authors = $db->query($author_quest); // База авторов

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
<tr><th width = 5%>ID</th><th width = 20%>Автор</th><th width = 15%>Почта</th><th width = 15%>Телефон</th><th width = 30%>Должность</th></tr>
<?php

foreach ($authors as $at) {
    echo "<tr>";
        echo "<td>";
            echo $at['author_id'];
        echo "</td>";
        echo "<td>";
        echo "<a href = '#AuthorEdit' class = 'btn_md' data-toggle='modal' data-whatever = '{$at['author_id']}|{$at['author_name']}|{$at['author_mail']}|{$at['author_phone']}|{$at['author_position']}'>".$at['author_name']."</a>";
        echo "</td>";
        echo "<td>";
            echo $at['author_mail'];    
        echo "</td>";
        echo "<td>";
            echo $at['author_phone'];    
        echo "</td>";
        echo "<td>";
            echo $at['author_position'];        
        echo "</td>";
    echo "</tr>";
}
?> 


</table>
<div class="row">
    <div class = "col-md-12 button_center">
        <a href = '/_prog/polls/add_author.php' class="btn btn-primary"><span class='glyphicon glyphicon-user'></span> Добавить автора</a>        
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
    </div>    
</div>
<style>
.mo-dal {
    width: 90%;
}
</style>

<!-- HTML-код модального окна редактирования одного пользователя-->
<div id='AuthorEdit' class='modal fade'>
<form method = "POST" action = "/_prog/polls/edit_author_exe.php">
<input type = "hidden" name = "author_id" value = "">
<input type = "hidden" name = 'author_action' value = "update">
  <div class='modal-dialog mo-dal'>
    <div class='modal-content'>
      <!-- Заголовок модального окна -->
      <div class='modal-header'>
        <button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
        <h4 class='modal-title'>Редактор автора</h4>
      </div>
      <!-- Основное содержимое модального окна -->
      <div class='modal-body row'>
        <div class = 'col-md-3'>
          <input type='text' class='form-control' name = 'author_name' value = '' required>
        </div>
        <div class = 'col-md-3'>  
          <input type='text' class='form-control' name = 'author_mail' value = '' required>        
        </div>
        <div class = 'col-md-3'>  
          <input type='text' class='form-control' name = 'author_phone' value = '' required>        
        </div>
        <div class = 'col-md-3'>  
          <input type='text' class='form-control' name = 'author_position' value = '' required>        
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