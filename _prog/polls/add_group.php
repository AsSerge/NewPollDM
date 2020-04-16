<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4>Новая почтовая группа</h4>
<form action = "/_prog/polls/edit_group_exe.php" method = "POST">
<input type='hidden' name = 'group_action' value = "add">
<table class = 'table table-bordered table_sm'>
<tr><td width = "20%">Название группы</td><td width = "80%">Описание</td></tr>
<tr>
<td><input type='text' class='form-control' name = 'group_name' placeholder = 'Введите название группы'></td>
<td><input type='text' class='form-control' name = 'group_description' placeholder = 'Введите описание группы'></td>
</tr>

</table>
<div class="row">
        <div class = "col-md-12 button_center">
            <input class="btn btn-success" type="submit" value="Сохранить">
            <input class="btn btn-warning" type="reset" value="Сбросить">
        </div>    
</div>
</form>

</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>