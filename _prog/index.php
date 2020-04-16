<?php
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

//Подключаемся к базе опросов
include('../rnd/connect/connect_to_base.php');
// Получаем список готовых отчетов
$poll_name_quest = 'SELECT * FROM poll WHERE 1';
$polls = $db->query($poll_name_quest);
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
            <!-- <th scope="col"></!-->
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
            echo "<td><button type='button' class='btn btn-info btn-xs' onclick=\"javascript:document.location.href='/_prog/polls/poll_mail_send.php?poll_id=".$row['poll_id']."'\">Настроить рассылку</button></td>";
            // echo "<td><button type='button' class='btn btn-info btn-xs' onclick=\"javascript:document.location.href='/_prog/polls/poll_result_report.php?poll_id=".$row['poll_id']."'\"><span class='glyphicon glyphicon-envelope'></span></button></td>";
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
