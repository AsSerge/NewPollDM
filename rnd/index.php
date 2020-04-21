<?php
session_start();
include('connect/connect_to_base.php');

$poll_id = $_GET['poll_id']; //получаем имя опроса по id
$u_token = $_GET['u_token']; // получаем имя клиента по токену

$_SESSION['poll_id'] = $poll_id;
$_SESSION['u_token'] = $u_token;

$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();

$u_name_quest = 'SELECT u_id, u_name FROM user WHERE u_token = ?';
$u_p_name = $db->prepare($u_name_quest);
$u_p_name->execute([$u_token]);
$u_name = $u_p_name->fetchAll();

$_SESSION['u_id'] = $u_name[0]['u_id'];
$u_id = $u_name[0]['u_id'];

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Опрос по дизайнам</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,300i,500&display=swap&subset=cyrillic,cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <?php 
    if($poll_name){
        $head = $poll_name;
    }else{
        $head = "Анкетирование DM";
    }
    ?>
    <a class="navbar-brand mr-auto mr-lg-0" href="#"><?php echo $head?></a>
</nav>
<?php
// Получаем список вопросов для опроса
// Подготовка массива вопросов анкетирования

$sql = 'SELECT * FROM question WHERE poll_id = ?'; // Плейсхолдер запроса
$quest = $db->prepare($sql); // Готовим запрос
$quest->execute([$poll_id]); // Подставляем переменную
$quest->setFetchMode(PDO::FETCH_ASSOC);

// Функция включение / отключения кнопки голосования
// Признак отключения - запись о голосованиии по конкретному вопросу - присутствует в poll_result: 
function GetCount($db, $poll_id, $u_id, $question_id){
  $sql = 'SELECT * FROM poll_result WHERE poll_id = ? AND u_id = ? AND question_id = ?';
  $quest = $db->prepare($sql);
  $quest->execute([$poll_id, $u_id, $question_id]);
  return $quest->rowCount(); //количество строк, удовлетворяющих запросу
}
// Считаем общее количество вопросов в опросе
$sql_n = 'SELECT * FROM question WHERE poll_id = ?';
$quest_n = $db->prepare($sql_n);
$quest_n->execute([$poll_id]);
$q_number = $quest_n->rowCount();
?>
<style>
.quest_table_style{
    /* border-bottom: 1px solid #b9b9b9; */
  }
  .hover_block{
    font-size: 1.1em;
  }
  .pass{
    color: #288d00;
  }
  .dont-pass{
    color: #d97203;
  }
  .check_line_on{
    border-left: 5px solid #288d00;
    padding-left: 15px;
    background-color: #e8e8e8;
  }
  .check_line_off{
    border-left: 5px solid #d97203;
    padding-left: 15px;
    background-color: #e8e8e8;
  }
  .poll_info{
    font-size: 0.8em;  
  }
  .btn{
    cursor: pointer;
  }

</style>
<main role="main" class="container">
 <div style = "height: 50px"></div>    
  <div class="my-3 p-3 bg-white">       
    <?php
    // Проверяем - получено ли имя, что является признаком передачи корректного токена
    if($u_name[0]['u_name'] != ''){
      $var1 = "Для того, чтобы принять участие в голосовании, вам необходимо ответить на все вопросы
      с активной кнопкой «Голосовать». Ответив на все вопросы, вы получаете возможность ознакомиться
      с результатами голосования остальных участников. Обратите внимание: выбирать необходимо то количество вариантов,
      которое указано в вопросе.";
      $var2 = "";
      echo "<h6 class='border-bottom border-gray pb-2 mb-0'>Вопросы для голосования&nbsp;[{$u_name[0]['u_name']}]</h6>";      
      echo "<p class = 'poll_info'>{$var1}</p>";
      $quest_count=0; // Устанавливаем значение начального отсчета
      while ($r = $quest->fetch()) {
        if(GetCount($db, $poll_id, $u_id, $r['question_id']) == 0){
          $check_string = "";
          $check_line = "check_line_off";
        }else{
          $check_string = "<i class='icon icon-xxs icon-danger fa fa-check-square-o pass'></i>&nbsp;";
          $check_line = "check_line_on";
          $quest_count += 1; // Обновляем число отвеченных вопросов   
        }
        echo "<div class='media text-muted pt-3 quest_table_style'>";    
        echo "<p class='media-body pb-3 mb-0 small lh-125 border-bottom border-gray {$check_line}'>";
        echo "<strong class='d-block text-gray-dark hover_block'>{$check_string}{$r['question_name']} (выбрать: {$r['question_model']})</strong>";
        // Выводим описание вопроса, если оно есть
        if($r['question_description']){
          echo "{$r['question_description']}<br>";
        }
        // Выводим кнопку "Голосовать", если данный пользователь еще не голосовал по этому вопросу - Функция GetCount
        if(GetCount($db, $poll_id, $u_id, $r['question_id']) == 0){        
          echo "<a href='des.php?question_id={$r['question_id']}'><i class='icon icon-xxs icon-primary fa fa-pencil-square-o dont-pass'></i>&nbsp;Голосовать</a>";
        }
        echo "</p>";
        echo "</div>";
      }
      // Проверяем - на все ли вопросы получены ответы. Если да - разрешаем доступ к странице с результатами
      if($quest_count < $q_number){
        $check_see_result = 'disabled';
      }else{
        $check_see_result = '';
        $set_check = random_int(1,10000);
        $_SESSION['set_check'] = $set_check;
      }
      if($poll_name){
        echo "<div class='row row-70 flex-lg-row-reverse'>";
        echo "<div class='col-md-12' style='text-align: center;'>";
        echo "  <div class='btn-group'>";
        echo "    <button type = 'button' class = 'btn btn-warning btn-sm' {$check_see_result} onclick='javascript:location.href=\"/rnd/poll_result.php?poll_id={$poll_id}&set_check={$set_check}\"'>Посмотреть результаты</button>"; 
        echo "  </div>";    
        echo "</div>";
        echo "</div>";
      }
    }else{
      echo "<div class='row row-70'>";
      echo "<div class='col-md-12 jumbotron'>";
      echo "<h5>Ошибка</h5>";
      echo "<p>Для участия в опросе Вам необходимо пройти по указанной в письме ссылке.</p>";
      echo "</div>";
      echo "</div>";
    }  
    ?>
  </div>
</main>
<?php include('layot/bottom.php');?>