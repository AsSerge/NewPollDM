<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

//Подключаемся к базе опросов
include('../rnd/connect/connect_to_base.php');

// Функция для получение количества строк выборки (вопросов в опросе, дизайнов в вопросе)
function GetRowCount($db, $question_str, $quest_id){
    $sql = $question_str;
    $q = $db->prepare($sql);
    $q->execute([$quest_id]);
    return $q->rowCount();
    }
// Функция получения id рассылки по id опроса
    function GetMailingId($db, $poll_id){
    $sql = "SELECT mailing_id FROM poll_mailing WHERE poll_id = ?";
    $q = $db->prepare($sql);
    $q->execute([$poll_id]);
    $mailing_id = $q->fetchColumn();    
    return $mailing_id;
    }        
// Получаем список готовых отчетов
$poll_name_quest = 'SELECT * FROM poll WHERE 1';
$polls = $db->query($poll_name_quest);
// Получаем информацию о статусе рассылки
// $mailing_status_check = GetRowCount($db, "SELECT * FROM poll_mailing WHERE poll_id = ?", $poll_id);
?>
<div class="container">
<div style = "clear: both; height: 70px;"></div>
    <h4 class="border-bottom border-gray pb-2 mb-0">Опросы</h4>
    <table class="table table-striped table_sm">
        <thead>
            <tr>
            <th scope="col">id</th>
            <th scope="col">Название опроса</th>
            <th scope="col">Начало</th>
			<th scope="col">Окончание</th>            
            <th scope="col">Список рассылки</th>
            <th scope="col"></th>
            <th scope="col">Сводка</th>
            </tr>
        </thead>
        <tbody>
        <?php
        while($row = $polls->fetch()){
            echo "<tr>";
            echo "<th scope='row'>".$row['poll_id']."</th>";
            echo "<td><a href = '/_prog/polls/edit_poll.php?poll_id=".$row['poll_id']."'>".$row['poll_name']."</a></td>";
            echo "<td>".mysql_to_date_text($row['poll_date_begin'])."</td>";
            echo "<td>".mysql_to_date_text($row['poll_date_end'])."</td>";            
            echo "<td><button type='button' class='btn btn-info btn-xs' onclick=\"javascript:document.location.href='/_prog/polls/poll_mail_save.php?poll_id=".$row['poll_id']."'\">Настроить рассылку</button></td>";
            if(GetRowCount($db, "SELECT * FROM poll_mailing WHERE poll_id = ?", $row['poll_id'])){
                $des = '';
                $color = 'btn-success';
                $poll_id = $row['poll_id'];                
                $mailing_id = GetMailingId($db, $row['poll_id']);
                $send_str = "onclick=\"javascript:document.location.href='/_prog/polls/poll_mail_send.php?poll_id={$poll_id}&mailing_id={$mailing_id}'\"";
            }else{
                $des = 'disabled';
                $color = 'btn-info';
                $send_str = "";
            }
            echo "<td><button type='button' class='btn {$color} btn-xs' {$des} {$send_str}><span class='glyphicon glyphicon-envelope'></span></button></td>";
            echo "<td><button type='button' class='btn btn-primary btn-xs' onclick=\"javascript:document.location.href='/_prog/polls/poll_result_report.php?poll_id=".$row['poll_id']."'\">Результаты опроса</button></td>";
            echo "</tr>";
        }
        ?>
        </tbody>
    </table>
    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="javascript:document.location.href='/_prog/polls/create_poll.php'">Добавить опрос</button>

</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>
