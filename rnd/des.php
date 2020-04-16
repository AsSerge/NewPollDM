<?php include('layot/top_des.php');?>
<section class="bg-default"> 
<main role="main" class="container">
<div style = "height: 80px"></div> 
<h6 class="border-bottom border-gray pb-2 mb-0"><?php echo "{$question_set['question_name']} (выбрать {$question_set['question_model']})"?></h6>
<form method="POST" action = "/_prog/polls/poll_result_exe.php" role="form" data-toggle="validator" id = "editproduct">
<input type="hidden" name = "poll_id" value="<?php echo $poll_id?>">
<input type="hidden" name = "question_id" value="<?php echo $question_id?>">
<input type="hidden" name = "u_id" value="<?php echo $u_id?>">
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
    
