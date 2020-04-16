<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');

include('../../rnd/connect/connect_to_base.php');
?>
<style>
.button_center{
    text-align: center;
    
}
.row-m-t{
  margin-top : 25px
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>  
<form action = "/_prog/polls/create_poll_exe.php" method = "POST">
<input type='hidden' name = 'poll_manage' value = "poll_add">
<div class="row">
    <div class = "col-md-6">
        <div class="row">
            <div class = "col-md-12">
                <label for="poll_name">Название опроса</label>
                <input type='text' class='form-control' name = 'poll_name' placeholder="Введите название опроса">
            </div>    
        </div>
        <div class="row row-m-t">
        
            <div class = "col-md-4">
                <label for="poll_date_begin">Начало опроса</label>
                <input type='date' class='form-control' name = 'poll_date_begin'>
            </div>
            <div class = "col-md-4">
                <label for="poll_date_end">Окончание опроса</label>
                <input type='date' class='form-control' name = 'poll_date_end'>
            </div>
            <div class = "col-md-4">
                <label for="questions_count">Вопросов в опросе</label>
                <input type='number' class='form-control' name = 'questions_count'>
            </div>
        
        </div>    
    </div>

    <div class = "col-md-6">
        <div class="row">
            <div class = "col-md-12">
                <label for="poll_description">Описание опроса</label>
                <textarea rows="5" cols="45" name="poll_description" class='form-control'></textarea>
            </div>
        </div>        
    </div>
</div>

<div class="clearfix"></div>
<div class="row row-m-t">
    <div class = "col-md-12 button_center">
        <input class="btn btn-success" type="submit" value="Сохранить">        
        <input class="btn btn-warning" type="reset" value="Сбросить">
        <input class="btn btn-danger" type="reset" onclick="history.back();" value="Отмена">
    </div>    
</div>

</form>
</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>