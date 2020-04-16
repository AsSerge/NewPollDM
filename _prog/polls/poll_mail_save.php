<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');

// Подключаемся к базе опросов
include('../../rnd/connect/connect_to_base.php');

// Функция для получение количества строк выборки (вопросов в опросе, дизайнов в вопросе)
function GetRowCount($db, $question_str, $quest_id){
    $sql = $question_str;
    $q = $db->prepare($sql);
    $q->execute([$quest_id]);
    return $q->rowCount();
}

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

//Получаем информацию о доступных авторах
$autors_sql = 'SELECT * FROM author WHERE 1'; 
$autors = $db->query($autors_sql);

// Получаем информацию о статусе рассылки. Если рассылка создана - можем ее отредактировать или отправить по списку.
$mailing_status_check = GetRowCount($db, "SELECT * FROM poll_mailing WHERE poll_id = ?", $poll_id);

if($mailing_status_check){    
    $sql_mailing_quest = 'SELECT * FROM poll_mailing WHERE poll_id = ?';
    $mailing=$db->prepare($sql_mailing_quest);
    $mailing->execute([$poll_id]);
    $mailing_arr = $mailing->fetch();
    $mailing_status = true;
}else{
    $mailing_status = false;
}


// $poll_questions_count = GetRowCount($db, "SELECT question_id FROM question WHERE poll_id = ?", $poll_id); // Количество вопросов в опросе
?>
<style>
.button_center{
    text-align: center;    
}
.mb-2{
    margin-bottom: 20px;
}  
</style>    
<div class="container">
<div style = "clear: both; height: 70px;"></div>
<h4 class="border-bottom border-gray pb-2 mb-0">Настройка рассылки&nbsp;[<strong><?php echo $poll_name?></strong>]</h4>
<form action = '/_prog/polls/poll_mail_save_exe.php' method = 'POST'>
<input type = 'hidden' name = 'poll_id' value = '<?=$poll_id?>'>
<?php
    // Если запись новая - добавляем. Иначе обновляем
    if($mailing_status){
        echo "<input type = 'hidden' name = 'mailing_id' value = '{$mailing_arr['mailing_id']}'>\r\n";
        echo "<input type = 'hidden' name = 'mail_save_action' value = 'update'>\r\n";
    }else{
        echo "<input type = 'hidden' name = 'mail_save_action' value = 'add'>\r\n";
    }
?>
<div class="row mb-2">
  <div class="col-xs-12 col-sm-12">
    <div class="row">
        <div class="col-xs-12 col-sm-12">            
            <label for="mail_title">Введите название письма</label>
            <input type='text' class='form-control' name = 'mailing_name' value = '<?=$mailing_arr['mailing_name']?>' required>
            <p class='help-block'>По умолчанию - название письма соответствует названию опроса</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Выберете группу рассылки</label>
              <div>
                <select class="form-control" id = '' name = 'group_id' required>
                    <option value = 0></option>
                    <?php
                    while($gr = $groups->fetch()){                        
                        $num = GetRowCount($db, "SELECT group_id FROM user WHERE group_id = ?", $gr['group_id']);
                        if($gr['group_id'] == $mailing_arr['group_id']){
                            $sel = 'selected';
                        }else{
                            $sel = '';
                        }                        
                        echo "<option value = {$gr['group_id']} $sel>{$gr['group_name']} ({$num}) </option>";
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
                    <textarea id="mait_text" class="md-textarea form-control" rows="5" name = "mailing_text"><?=$mailing_arr['mailing_text']?></textarea>                    
              </div>
            <p class='help-block'>Текст вводится в свободной форме. Ссылка на опрос вставляется в тело письма автоматически</p>
        </div>
    </div>
    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <label for="mail_title">Выберете автора письма</label>
              <div>
              <select class="form-control" id = '' name = 'author_id'>
                <?php    
                while($at = $autors->fetch()){
                    if($at['author_id'] == $mailing_arr['author_id']){
                        $sel = 'selected';
                    }else{
                        $sel = '';
                    }  
                    echo "<option value = {$at['author_id']} $sel>{$at['author_name']}</option>";
                }              
                ?>
              </select>                    
              </div>            
        </div>
    </div>            
  </div>

</div>
<div class="row">
    <div class = "col-md-12 button_center">        
        <input class="btn btn-primary" type="submit" value="Сохранить рассылку">
        <?php
        if($mailing_status){
            echo "<input class='btn btn-success' type='button' value='Начать рассылку' onclick='javascript:location.href='/_prog/'>";            
        }
        ?>
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'">
    </div>    
</div>

</form>

</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>
