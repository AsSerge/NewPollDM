<?php include('layot/top_index.php');?>
<style>
.title_count{
  text-align: center;
  font-weight: bold;
  color: white;
}
.title_count span{  
  font-size: 0.8em;      
  background-color: #ffc053;
  padding: 1px 10px;
}

</style>
<main role="main" class="container">
 <div style = "height: 50px"></div>    
  <div class="my-3 p-3 bg-white">   
    <h6 class="border-bottom border-gray pb-2 mb-0">Результаты голосования</h6>
    <?php
    // Разрешаем оптображение результатов ТОЛЬКО после ответов на вопросы
    // Совпадение рандомного числа в сессии
    if(isset($_GET['set_check']) && $_SESSION['set_check'] == $_GET['set_check']){  
      $poll_id = $_GET['poll_id'];
      // Формируем путь к папке с иззображениями
      $image_dir = '/rnd/images/';
      // Получаем название опроса
      $poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
      $quest_p_name = $db->prepare($poll_name_quest);
      $quest_p_name->execute([$poll_id]);
      $poll_name = $quest_p_name->fetchColumn();// Получаем значение одного поля [название опроса]
      $poll_res = "SELECT       
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
              $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['user_ch'] .= "<span class = 'choice ye'>".$row['u_name']."</span>";
          }else{
              $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['user_ch'] .= "<span class = 'choice no'>".$row['u_name']."</span>";
          }    
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['ch'] += $row['result_choice'];
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['question_id'] = $row['question_id'];
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['design_id'] = $row['design_id'];
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['design_name'] = $row['design_name'];
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['img_small'] = $row['design_small'];
          $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['img_big'] = $row['design_big'];
              if ($row['result_comment']!= ''){
                  $result_design[$row['question_name']][$row['poll_id']."/".$row['question_id']."/".$row['design_small']]['cm'] .= "||".$row['result_comment']." (<i>".$row['u_name']."</i>)";
              }
          }        
          // Функция сортировки массива по значению ch - сумированный выбор ПО УБЫВАНИЮ!    
            function cmp($b, $a){ 
              return strnatcmp($a["ch"], $b["ch"]); 
            }             
      session_destroy();      
      foreach ($result_design as $key => $value) {        
        echo "<h6>".$key."</h6>";
        echo "<div class = 'row justify-content-sm-left row-30'>";
        usort ($value, "cmp"); // <<<<<< Здесь запускаем сортировку
        foreach ($value as $img => $value) {
          echo "<div class='col-sm-6 col-md-3 col-xl-2'>";            
            $image_path = "<a class='img-thumbnail-variant-1' href='".$image_dir.$poll_id."/".$value['question_id']."/".$value['img_big']."' data-lightgallery='item'><img src = '".$image_dir.$poll_id."/".$value['question_id']."/".$value['img_small']."' width = '200px' class='img-responsive'></a>";
            echo "<div>".$image_path."</div>";
            echo "<div class = 'title_count'><span>".$value['ch']."</span></div>";            
          echo "</div>";          
        }
        echo "</div>";
        echo "<hr style = 'margin: 30px 0'>";
      }
      echo "<div class='row row-70 flex-lg-row-reverse'>";
      echo "<div class='col-md-12' style='text-align: center;'>";
      echo "  <div class='btn-group'>";
      echo "    <button type = 'button' class = 'btn btn-warning btn-sm' onclick='history.back();'>Назад</button>"; 
      echo "  </div>";    
      echo "</div>";
      echo "</div>";
    }else{
      session_destroy();
      echo "<p>Произошла ошибка!&nbsp;";
      echo "Перейти на сайт <a href = 'https://www.cleanelly.ru'>Cleanelly.ru</a></p>";
      
    }

    ?>
  </div>
</main>
<?php include('layot/bottom.php');?>