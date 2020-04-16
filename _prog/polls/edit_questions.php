<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');

if ($_GET['poll_id']){
    // Получаем данные указанного опроса
    $poll_quest = 'SELECT * FROM poll WHERE poll_id = ?';
    $poll = $db->prepare($poll_quest);
    $poll->execute([$_GET['poll_id']]);    
    $poll_set = $poll->fetch(PDO::FETCH_LAZY);//Заполняем массив

    // Получаем значения
    $poll_id = $poll_set['poll_id'];
    $poll_name = $poll_set['poll_name'];
    $poll_date_begin = $poll_set['poll_date_begin'];
    $poll_date_end = $poll_set['poll_date_end'];

    // Получаем значения вопросов для указанного опроса

    $questions_quest = 'SELECT * FROM question WHERE poll_id = ?';
    $questions = $db->prepare($questions_quest);
    $questions->execute([$poll_id]);
}
?>
<style>
.button_center{
    text-align: center;
}  
</style>    
<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4><?php echo $poll_name?> [Список вопросов]</h4>  
<form action = "#" method = "POST">
<table class = 'table table-bordered table_sm'>
<tr><th>ID</th><th width = 30%>Вопрос</th><th>Описание</th><th>Голосование</th><th>Модель</th><th>Картинки</th><th>Редактор</th></tr>
<?php
    foreach ($questions as $row) {
        // Здесь получаем папку вопроса с изображениями для подсчета количества изображений
        $source_dir = $_SERVER['DOCUMENT_ROOT']."/rnd/images/".$poll_id."/".$row['question_id']."/"; //Папка вопроса
        $count_images = count(get_files_count($source_dir)) / 2; // Количество оригинальных фото
        
        echo "<tr>";
        echo "<td>".$row['question_id']."</td>";        
        echo "<td>".$row['question_name']."</td>";
        echo "<td>".$row['question_description']."</td>";
        if($row['question_voting'] == 1){$question_voting = "Да";}else{$question_voting = "Нет";} // Участие в голосованиии 
        echo "<td>".$question_voting."</td>";
        echo "<td>".$row['question_model']."</td>"; // Количество вариантов выбора
        echo "<td>".$count_images."</td>"; // Количество загруженных изображений
        echo "<td><button type='button' class='btn btn-primary btn-sm' onclick=\"javascript:document.location.href='/_prog/polls/edit_one_question.php?question_id={$row['question_id']}'\">Настройка</button></td>";
        echo "</tr>";        
    }
?>
</table>
<div class="row">
    <div class = "col-md-12 button_center">
        <input class="btn btn-info" type="button" value="В список опросов" onclick="javascript:location.href='/_prog/'";>
        <input class="btn btn-success" type="submit" value="Сохранить">
        <input class="btn btn-warning" type="reset" value="Сбросить">
    </div>    
</div>

</form>
</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>