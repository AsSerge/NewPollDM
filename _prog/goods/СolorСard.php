<div class="panel panel-default">
    <div class="panel-heading" role="tab" id="heading2">
        <h4 class="panel-title">
            <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="false" aria-controls="collapse3"><span class="glyphicon glyphicon-th"></span>&nbsp;Карта цветов</a>
            </h4>
    </div>
    <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
    <div class="panel-body">

                <?php
                //Заполняем массив уникальных цветов
                $colors = array();
                while($row=mysqli_fetch_array($query_color)){
                    $c = $row['color'];
                     if(!in_array($c, $colors)){
                        $colors[] = $row['color'];
                     }
                }                
                $source_dir = "../../images/catalog/{$section}/{$trademark}/{$design}/";
                  //Миниатюра
                  function vfS($source_dir, $product_id, $color_number){
                    foreach (glob("{$source_dir}vfs_{$product_id}_{$color_number}_*.*") as $docFile) {
                        return $docFile; //Получаем файл по маске
                    }
                  }
                  //Большая картинка  
                  function vfB($source_dir, $product_id, $color_number){
                    foreach (glob("{$source_dir}vfb_{$product_id}_{$color_number}_*.*") as $docFile) {
                        return $docFile; //Получаем файл по маске
                    }
                  }
                // echo "<div class='col-md-12' style='text-align: center;'>";
                echo "<div class='col-md-12'>";      
                foreach ($colors as $color) {
                    $color_img_big = vfB($source_dir, $product_id, $color);
                    $color_img_small = vfS($source_dir, $product_id, $color);
                    // echo "<tr><td>{$color}</td><td><img src = '{$color_img_small}' width = '50px'></td></tr>";//ПРАВКА
                    echo "<figure class = 'colorset'>";
                    echo "<a href = '{$color_img_big}' rel = 'one2' class = 'fancybox'><img src = '{$color_img_small}' width = '80px' class='img-thumbnail'></a>";//ПРАВКА
                    echo "<figcaption>{$color}</figcaption>";
                    echo "</figure>";
                }
                echo "</div>";
                ?>


            <div class="col-md-12" style="text-align: center;">
                    <div class="btn-group">
                        <button type = "button" class = "btn btn-danger" onclick="location.href='../../_prog/goods/list_color_editor.php?product_id=<?php echo $product_id;?>'"><span class="glyphicon glyphicon-th">&nbsp;</span>Редактор цвета</button>
                    </div>
            </div>
        </div>
    </div>
</div>