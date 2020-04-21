<?php
session_start();
include('connect/connect_to_base.php');

$question_id = $_GET['question_id'];
$poll_id = $_SESSION['poll_id'];
$u_id = $_SESSION['u_id'];
$u_token = $_SESSION['u_token'];

$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();

$u_name_quest = 'SELECT u_name FROM user WHERE token_id = ?';
$u_p_name = $db->prepare($u_name_quest);
$u_p_name->execute([$token_id]);
$u_name = $u_p_name->fetchColumn();

$q_name_quest = 'SELECT question_name, question_model FROM question WHERE question_id = ?';
$q_p_name = $db->prepare($q_name_quest);
$q_p_name->execute([$question_id]);
// $question_name = $q_p_name->fetchColumn();
$question_set = $q_p_name->fetch(PDO::FETCH_LAZY);

$design_quest = "SELECT * FROM design WHERE question_id = ?";
$sql_des = $db->prepare($design_quest);
$sql_des->execute([$question_id]);
// $design_set = $sql_des->fetch(PDO::FETCH_LAZY);
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
    <a class="navbar-brand mr-auto mr-lg-0" href="#"><?php echo $poll_name?></a>
</nav>

<section class="bg-default"> 
<main role="main" class="container">
<div style = "height: 80px"></div> 
<h6 class="border-bottom border-gray pb-2 mb-0"><?php echo "{$question_set['question_name']} (выбрать {$question_set['question_model']})"?></h6>
<form method="POST" action = "/_prog/polls/poll_result_exe.php" role="form" data-toggle="validator" id = "editproduct">
<input type="hidden" name = "poll_id" value="<?php echo $poll_id?>">
<input type="hidden" name = "question_id" value="<?php echo $question_id?>">
<input type="hidden" name = "u_id" value="<?php echo $u_id?>">
<input type="hidden" name = "u_token" value="<?php echo $u_token?>">
       <div class="row row-70 flex-lg-row-reverse">      
       <div class="col-lg-12">
       <div class="section">
                  <div class="row justify-content-sm-center row-70">
                  <?php
                  $design_id_arr = []; // Создаем массив дизайнов для проверки                  
                    while ($row = $sql_des->fetch()) {
                        $design_id = $row['design_id'];
                            $design_id_arr[] = $design_id; // Заполняем массив дизайнов для проверки
                        $design_name = $row['design_name'];
                        $design_big = $row['design_big'];
                        $design_small = $row['design_small'];
                    echo "<div class='col-md-6 col-xl-4 one_design'>";
                    echo "   <div class='product product-grid'>";
                    echo "       <div class='product-img-wrap'>";
                    echo "           <a class='img-thumbnail-variant-1' href='/rnd/images/{$poll_id}/{$question_id}/{$design_big}' data-lightgallery='item'><img src = '/rnd/images/{$poll_id}/{$question_id}/{$design_small}' class='img-responsive'></a>";
                    if($design_name != 'des' && $design_name != ''){    
                        echo "              <div class = 'product-label-wrap'>";    
                        echo "                <span class = 'new'>{$design_name}</span>";
                        echo "              </div>";
                    }        
                    echo "       </div>";
                    echo "       <div class='product-caption'>";
                    echo "            <input type='checkbox' id='' name='result_choice[]' value = '{$design_id}' class = 'check-check'>";
                    echo "            <input type='text' class='form-control inptext' name = 'result_comment[]' placeholder='Комментарий'>";
                    echo "       </div>";
                    echo "   </div>";
                    echo "</div>";              
                    }
                    $_SESSION['design_id_arr'] = $design_id_arr; // Передаем массив в сессионную переменную
                  ?>                      
                  </div>
       </div>
       </div>
       
       </div>
       <div class="row row-70 flex-xs-row-reverse">
                <div class="col-md-12" style="text-align: center;">
                <p>Необходимо выбрать <?=$question_set['question_model'];?><span id = "sel_count"></span></p>   
                	<div class="btn-group">                       
						<button type = "submit" id = "submit_btn" class="btn btn-success btn-sm" name="submit">Голосовать</button>
						<button type = "button" class = "btn btn-warning btn-sm" onclick="history.back();">Отмена</button> 
                    </div>
                </div>
       </div>
</form>
</main>
</section>
<script> var done_count = <?php echo($question_set['question_model'])?>; // Установка количества выборок</script>
<?php include('layot/bottom.php');?>
    
