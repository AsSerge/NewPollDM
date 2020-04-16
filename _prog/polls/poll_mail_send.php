<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');

// Подключаемся к базе опросов
include('../../rnd/connect/connect_to_base.php');
// Получаем id опроса 
$poll_id = $_GET['poll_id'];

// Получаем название опроса
$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();// Получаем значение одного поля [название опроса]

// Получаем информацию о группах рассылки
$mail_group = 'SELECT * FROM groups WHERE 1';
$groups = $db->query($mail_group);

// Функция для получение количества строк выборки (вопросов в опросе, дизайнов в вопросе)
function GetRowCount($db, $question_str, $quest_id){
    $sql = $question_str;
    $q = $db->prepare($sql);
    $q->execute([$quest_id]);
    return $q->rowCount();
}
// $poll_questions_count = GetRowCount($db, "SELECT question_id FROM question WHERE poll_id = ?", $poll_id); // Количество вопросов в опросе
?>
<style>
.button_center{
    text-align: center;
}  
</style>    
<div class="container">
<div style = "clear: both; height: 70px;"></div>
<h4 class="border-bottom border-gray pb-2 mb-0">Настройка рассылки [&nbsp;<strong><?php echo $poll_name?></strong>]</h4>

<div class="row">
  <div class="col-xs-12 col-sm-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Введите название письма</label>
            <input type='text' class='form-control' name = 'mail_title' value = '<?=$poll_name?>'>
            <p class='help-block'>По умолчанию - название письма соответствует названию опроса</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Выберете группу рассылки</label>
              <div>
                <select class="form-control" id = '' name = 'mail_sender'>
                    <option value = 0></option>
                    <?php
                    while($gr = $groups->fetch()){
                        
                        $num = GetRowCount($db, "SELECT group_id FROM user WHERE group_id = ?", $gr['group_id']);
                        
                        echo "<option value = {$gr['group_id']}>{$gr['group_name']} ({$num}) </option>";
                    }
                    ?>
                </select>                    
                <p class='help-block'>Выбор группы для массовой рассылки</p>
              </div>            
        </div>
    </div>            
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Введите текст письма</label>
              <div class="md-form mb-4 pink-textarea active-pink-textarea">
                    <textarea id="mait_text" class="md-textarea form-control" rows="5"></textarea>                    
              </div>
            <p class='help-block'>Текст вводится в свободной форме. Ссылка на опрос вставляется в тело письма автоматически</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Выберете автора письма</label>
              <div>
              <select class="form-control" id = '' name = 'mail_sender'>
                <option value = 0></option>
                <option value = 1>Цветков Сергей</option>
                <option value = 2>Китаева Татьяна</option>
                <option value = 3>Фролова Юлианна</option>
              
              </select>                    
              </div>            
        </div>
    </div>            
  </div>

</div>
<div class="row">
    <div class = "col-md-12 button_center">
        <a href = '/_prog/polls/add_group.php' class="btn btn-primary">Добавить Группу</a>
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
    </div>    
</div>  
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>
