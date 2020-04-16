<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');

include('../../rnd/connect/connect_to_base.php');

if ($_GET['poll_id']){
    // Устанавливаем переменную сессии -> ID опроса
    $_SESSION['poll_id'] = $_GET['poll_id'];
    // Получаем данные указанного опроса
    $poll_quest = 'SELECT * FROM poll WHERE poll_id = ?';
    $poll = $db->prepare($poll_quest);
    $poll->execute([$_GET['poll_id']]);    
    $poll_set = $poll->fetch(PDO::FETCH_LAZY);//Заполняем массив

    // Получаем значения
    $poll_id = $poll_set['poll_id'];
    $poll_name = $poll_set['poll_name'];
    $poll_description = $poll_set['poll_description'];
    $poll_date_begin = $poll_set['poll_date_begin'];
    $poll_date_end = $poll_set['poll_date_end'];
}
?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>  
<form action = "/_prog/polls/create_poll_exe.php" method = "POST">
<input type='hidden' name = 'poll_manage' value = "poll_update">
<input type='hidden' name = 'poll_id' value = "<?php echo $poll_id?>">
<table class = 'table table-bordered table_sm'>
    <tr>
        <th>ID</th><th width = 50%>Название дизайна</th><th>Начало</th><th>Конец</th>
        <?php
        if ($_GET['poll_id']){
            echo "<th>Редактор</th>";
        }    
        ?>
    </tr>
    <tr>
        <td><input type='text' class='form-control' name = 'poll_id' value = '<?php echo $poll_id?>' disabled></td>
        <td><input type='text' class='form-control' name = 'poll_name' value = '<?php echo $poll_name?>'></td>
        <td><input type='date' class='form-control' name = 'poll_date_begin' value = '<?php echo $poll_date_begin?>'></td>
        <td><input type='date' class='form-control' name = 'poll_date_end' value = '<?php echo $poll_date_end?>'></td>
        <?php
        if ($_GET['poll_id']){
            echo "<td><button type='button' class='btn btn-primary btn-sm' onclick=\"javascript:document.location.href='/_prog/polls/edit_questions.php?poll_id={$_GET['poll_id']}'\">Вопросы</button></td>";
        }    
        ?>
    </tr>    
    <tr>
    <td colspan = "5">
        <label for="poll_description">Описание опроса</label>
        <textarea rows="3" cols="25" name="poll_description" class='form-control'><?php echo $poll_description;?></textarea>
    </td>
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