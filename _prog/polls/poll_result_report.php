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
// Формируем путь к папке с иззображениями
$image_dir = '/rnd/images/';

// Получаем название опроса
$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();// Получаем значение одного поля [название опроса]

// Функция для получение количества строк выборки (вопросов в опросе, дизайнов в вопросе)
function GetRowCount($db, $question_str, $quest_id){
    $sql = $question_str;
    $q = $db->prepare($sql);
    $q->execute([$quest_id]);
    return $q->rowCount();
}
$poll_questions_count = GetRowCount($db, "SELECT question_id FROM question WHERE poll_id = ?", $poll_id); // Количество вопросов в опросе

$poll_res = "SELECT 
-- u_name, poll_result.poll_id, question.question_id, question_name, question_model,  design.design_id, design_name, design_big, design_small, result_choice, result_comment 
* 
FROM poll_result
LEFT JOIN question ON poll_result.question_id = question.question_id
LEFT JOIN user ON poll_result.u_id = user.u_id
LEFT JOIN design ON poll_result.design_id = design.design_id
WHERE poll_result.poll_id = ?";
$main_quest = $db->prepare($poll_res);
$main_quest->execute([$poll_id]);
$main_quest->setFetchMode(PDO::FETCH_ASSOC);

// Заполняем массивы вопросов

$result_design = array();
while ($row = $main_quest->fetch()) {
    if ($row['result_choice'] == 1){
        $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['user_ch'] .= "<span class = 'choice ye'>".$row['u_name_voted']."</span>";
    }else{
        $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['user_ch'] .= "<span class = 'choice no'>".$row['u_name_voted']."</span>";
    }    
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['ch'] += $row['result_choice'];
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['question_id'] = $row['question_id'];
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['design_id'] = $row['design_id'];
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['design_name'] = $row['design_name'];
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['img_small'] = $row['design_small'];
    $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['img_big'] = $row['design_big'];
        if ($row['result_comment']!= ''){
            $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['cm'] .= "||".$row['result_comment']." (<i>".$row['u_name_voted']."</i>)";
        }
    }  
?>
<style>
.choice{
    /* display: block; */
    padding: 2px;
    margin-bottom: 6px;
}
.ye{    
    background-color: #a7ff9e;
    font-weight: bold;
    
}
.no{    
    background-color: #ffcc9e;
    color: #c78d32;
}
</style>
<div class="container">
<div style = "clear: both; height: 70px;"></div>
    <h4 class="border-bottom border-gray pb-2 mb-0">Результаты опроса&nbsp;<strong><?php echo $poll_name?></strong></h4>
    
    <?php
        // echo "<pre>";
        // print_r($result_design);
        // echo "</pre>";        

    // Функция сортировки массива по значению ch - сумированный выбор ПО УБЫВАНИЮ!    
    function cmp($b, $a){ 
        return strnatcmp($a["ch"], $b["ch"]); 
    }     
    
    foreach ($result_design as $key => $value) {
        echo "<h4>".$key."</h4>";
        $question_id = 2;
        usort ($value, "cmp"); // <<<<<< Здесь запускаем сортировку    
        echo "<table class = 'table table-striped table_sm'>";
        echo "<tr><th width='5%'>ID</th><th width='20%'>Дизайн</th><th>Название</th><th width='30%'>Пользователи:</th><th width='10%'>Итого</th><th width='25%'>Комментарии</th></tr>";

            foreach ($value as $img => $value) {                                
                echo "<tr>";
                    echo "<td>".$value['design_id']."</td>";                    
                    $image_path = "<a class='fancybox' href='".$image_dir.$poll_id."/".$value['question_id']."/".$value['img_big']."'><img src = '".$image_dir.$poll_id."/".$value['question_id']."/".$value['img_small']."' width = '150px'></a>";
                    echo "<td>".$image_path."</td>";
                    echo "<td>".$value['design_name']."</td>";
                    echo "<td>".$value['user_ch']."</td>";
                    echo "<td>".$value['ch']."</td>";                        
                        $all_comments = ltrim($value['cm'], "||");
                        $all_comments = explode("||", $all_comments);
                        echo "<td>";                            
                            foreach($all_comments as $comment){
                                echo $comment."<br>";
                            }                            
                        echo "</td>";
                echo "</tr>";
            }
        echo "</table>";
    }    
    ?>
    
    <button type="button" class="btn btn-primary btn-sm btn-block" onclick="javascript:document.location.href='/_prog/'">Список опросов</button>

</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>
