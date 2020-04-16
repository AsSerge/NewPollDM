<form method="POST" action = "../_prog/goods/editproduct.php" enctype="multipart/form-data" role="form" data-toggle="validator" id = "editproduct">
<input type="hidden" name = "product_id" value="<?php echo $row_m['product_id'];?>">
<h4>Цветовая капсула <strong><?php echo $row_m['color_capsule']?></strong> | Редактор товара в списке ID - <?php echo $row_m['product_id'];?></h4>
<style>
    .row_sm, .row_sm label{
    }
    .row_sm div{
        margin-bottom: 5px;
    }
    .row_sm p.help-block{
        font-size: 0.9em;
        display: block;
        margin: 2px;
    }
</style>
    <div class="row row_sm">
		<div class="form-group col-sm-6">
            <div class="form-group col-sm-12">
            <h4>Дизайн</h4>
                        <!-- <label for="design">Дизайн</label> -->
                        <input type="text" class="form-control" placeholder="Введите название дизайна" name = "design" id = "design" required value = "<?php echo $row_m['design'];?>"> 
                        <p class='help-block'>Введите название дизайна</p>
            </div>
            <div class="form-group col-sm-12">
                        <label for="product_type">Тип продукции</label>
                        <input type="text" class="form-control" placeholder="Введите тип продукции" name = "product_type" id = "product_type" required value = "<?php echo $row_m['product_type'];?>"> 
                        <p class='help-block'>Введите тип продукции</p>
            </div>
            <div class="form-group col-sm-12">
                <label for="full_description">Описание</label>
                        <textarea class='form-control' name = "full_description" rows = '2' id = 'full_description'><?php echo $row_m['full_description'];?></textarea>
                        <p class='help-block'>Введите описание для карточки товара</p>
            </div>
<style>
.fotocard{
    background-color: #dedede;
    border-radius: 5px;
    padding: 5px;
    margin-top: 15px;    
}
.fotocard h5 {
    display: block;
    border-bottom: 1px dotted white;
    padding-bottom: 0.5em;
    padding-left: 1em;
}
.fotocard img{
    padding: 5px;
}
.vendor-code th{
    background-color: #dedede;
}
.vendor-code td{    
}
</style>            
            <div class="form-group col-sm-12 fotocard">
                <h5>Главное фото</h5>
                <div class="form-group col-sm-4">
                    <?php
                    // Находим файлы, начинающийся с mf для главной картинки и начинающиеся с cf для дополнительных
                    $product_id = $row_m['product_id'];
                    $section = $row_m['section'];
                    $trademark = $row_m['trademark'];
                    $color_capsule = $row_m['color_capsule'];
                    $design = $row_m['design'];
                    $source_dir = "../../images/catalog/{$section}/{$trademark}/{$color_capsule}/{$design}/";
                    // Главная картинка
                    foreach (glob("{$source_dir}mfs_{$product_id}_*.*") as $docFile) {
                        $mfs = $docFile;
                    }
                    foreach (glob("{$source_dir}mfb_{$product_id}_*.*") as $docFile) {
                        $mfb = $docFile;
                    }
                    // Вспомогательная картинка
                    foreach (glob("{$source_dir}cfs_{$product_id}_*.*") as $docFile) {
                        $cfs[] = $docFile;
                    }
                    foreach (glob("{$source_dir}cfb_{$product_id}_*.*") as $docFile) {
                        $cfb[] = $docFile;
                    }
                    ?>
                    <a class = 'fancybox' href='<?php echo $mfb;?>'><img src='<?php echo $mfs;?>' alt='' width='90px'></a>
                </div>
                <div class="form-group col-sm-8">                    
                    <label for="production_img">Загрузить новое главное фото для списка (jpeg)</label>
                            <input type='file' class='prod_image' name='prod_image'>
                            <p class='help-block'>Размер файла 1900px х 1900px не более 6 Мб</p>
                </div>            
            </div>

            <div class="form-group col-sm-12 fotocard">
                <h5>Карточка товара</h5>
                <div class="form-group col-sm-12" style="text-align: center;">
                    <?php
                    $j=0;
                    foreach ($cfs as $cfs) {
                        echo "<a href = '{$cfb[$j]}' rel = 'one1' class = 'fancybox'><img src = '{$cfs}' width = '80px' class='img-thumbnail'></a>";
                        $j++;
                    }                    
                    ?>                    
                </div>
                <div class="col-md-12" style="text-align: center;">
					<div class="btn-group">
                        <button type = "button" class = "btn btn-danger" onclick="location.href='../../_prog/goods/list_card_editor.php?product_id=<?php echo $product_id;?>'"><span class="glyphicon glyphicon-th">&nbsp;</span>Редактор списка</button>
					</div>
			    </div>
            </div>




        </div>
		<div class="form-group col-sm-6">

            <div class="form-group col-sm-12">
            <h4>Настройки карточки товара</h4>
                <!-- <label for="description">Категория для сайта</label> -->
                        <select class="selectpicker form-control show-tick" title="" name = "description" id = "description" required>
                            <?php                     
                                set_selected($ini_arr[description], $row_m['description']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете описание</p>
            </div>
            <div class="form-group col-sm-12">                    
                <label for="cloth">Тип ткани</label>
                        <select class="selectpicker form-control show-tick" title="" name = "cloth" id = "cloth" required>
                            <?php                     
                                set_selected($ini_arr[cloth], $row_m['cloth']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тип ткани</p>
            </div>
            <div class="form-group col-sm-12">                    
                <label for="dyeing">Тип крашения</label>
                        <select class="selectpicker form-control show-tick" title="" name = "dyeing" id = "dyeing" required>
                            <?php                     
                                set_selected($ini_arr[dyeing], $row_m['dyeing']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тип крашения</p>
            </div>

            <div class="form-group col-sm-12">                                
                <label for="main_tag">Главный тег</label>
                <select class="selectpicker form-control show-tick" title="" name = "main_tag" id = "main_tag">
                            <?php                     
                                set_selected($ini_arr[main_tag], $row_m['main_tag']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тег для главной страницы (новинка / распродажа)</p>
            </div> 
            
            <div class="form-group col-sm-12">                                
                <label for="tag1">Тег 1</label>
                <select class="selectpicker form-control show-tick" title="" name = "tag1" id = "tag1">
                            <?php                     
                                set_selected($ini_arr[tags], $row_m['tag1']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тег для товара</p>
            </div>    
            <div class="form-group col-sm-12">                                
                <label for="tag2">Тег 2</label>
                <select class="selectpicker form-control show-tick" title="" name = "tag2" id = "tag2">
                            <?php                     
                                set_selected($ini_arr[tags], $row_m['tag2']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тег для товара</p>
            </div>    
            <div class="form-group col-sm-12">                                
                <label for="tag3">Тег 3</label>
                <select class="selectpicker form-control show-tick" title="" name = "tag3" id = "tag3">
                            <?php                     
                                set_selected($ini_arr[tags], $row_m['tag3']);
                            ?>   
                        </select>
                        <p class='help-block'>Выберете тег для товара</p>
            </div>

            </div>
        </div>        
    </div>  
</div>
<div class="container">
            <div class="form-group col-sm-12">
                <h4>Состав дизайна</h4>
                <table class = 'table table-bordered vendor-code'>
                <tr><th>Товарный номер</th><th width = 60%>Артикул</th><th>Размер</th><th>Цвет</th><th>Плотность</th></tr>    
                <?php
                while($row=mysqli_fetch_array($query)){
                    echo "<tr>";
                    echo "<td>".$row['id']."</td><td>".$row['vendor_code']."</td><td>".$row['size']."</td><td>".$row['color']."</td><td>".$row['density']."</td>";
                    echo "</tr>";
                }
                ?>
                </table>
            </div>
</div>            
<hr>
<div class="container">
	<div class="row">
			<div class="col-md-12" style="text-align: center;">
					<div class="btn-group">
                        <button type = "button" class = "btn btn-info" onclick="location.href='../../_prog/blank/index.php'">В список товаров</button> 
						<button type = "submit" id = "" class="btn btn-success" name="submit">Сохранить изменения</button>
						<button type = "button" class = "btn btn-warning" onclick="history.back();">Отмена</button> 
					</div>
			</div>
	</div>
</div>
</form>
