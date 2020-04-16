<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек

$product_id = $_GET['product_id'];

// Соединямся с БД
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_base);
//Устанавливаем кодировку
        mysqli_query($link,"SET NAMES 'utf8'"); 
        mysqli_query($link,"SET CHARACTER SET 'utf8'");
        mysqli_query($link,"SET SESSION collation_connection = 'utf8_general_ci'");

//База дополнительный параметров
$quest = "SELECT * FROM `sub-img` WHERE `product_id` = '".$product_id."' ORDER BY product_id";  
$query = mysqli_query($link, $quest);
//Основные параметры
$quest_m = "SELECT * FROM `main_img` WHERE `product_id` = '".$product_id."'";
$query_m = mysqli_query($link, $quest_m);
$row_m = mysqli_fetch_array($query_m);

?>

<div class="container">
    <div style = "clear: both; height: 50px;"></div>
    <h4>Цветовая капсула <strong><?php echo $row_m['color_capsule']?></strong> | Дизайн <strong><?php echo $row_m['design']." [".$row_m['product_id']."]";?></strong></h4>
    <form method="POST" action = "../_prog/goods/list_card_save.php" enctype="multipart/form-data" role="form" data-toggle="validator" id = "editphoto">
    <input type="hidden" name = "product_id" value="<?php echo $row_m['product_id'];?>">

    <style>
        .div-center{
            text-align: center;
            margin: 3px;            
        }
        .div-outline{
            /* border: 1px solid black; */
            border-radius: 3px;
            margin: 5px;
        }
    </style>
    <div class="form-group col-sm-12">

                <h4>Прикрепленные изображения</h4>
                <div class='row'>
                <?php
                  // Находим файл, начинающийся с cfs
                  $product_id = $row_m['product_id'];
                  $section = $row_m['section'];
                  $trademark = $row_m['trademark'];
                  $color_capsule = $row_m['color_capsule'];
                  $design = $row_m['design'];
                  $source_dir = "../../images/catalog/{$section}/{$trademark}/{$color_capsule}/{$design}/";
                  foreach (glob("{$source_dir}cfs_{$product_id}_*.*") as $docFile) {
                      $cfs[] = $docFile;//Пишем массив миниатюр
                  }
                  foreach (glob("{$source_dir}cfb_{$product_id}_*.*") as $docFile) {
                      $cfb[] = $docFile;//Пишем массив больших картинок                      
                  }
                  foreach (glob("{$source_dir}cfm_{$product_id}_*.*") as $docFile) {
                    $cfm[] = $docFile;//Пишем массив больших картинок                      
                  }
                  if($cfs){  
                        $k = 0;
                        foreach ($cfs as $cfs) {
                            echo "<div class = 'col-sm-2 div-outline'>";
                            echo "<div class = 'col-sm-12 div-center'>";
                            echo "<a class = 'fancybox' href = '{$cfb[$k]}'><img src = '{$cfs}' class='img-thumbnail'></a>";                    
                            // echo "</a>";
                            echo "</div>";
                            echo "<div class = 'col-sm-12 div-center'>";
                            $name_to_delete[] = [$cfs, $cfm[$k], $cfb[$k]];
                            echo "<button type = 'button' class = 'btn btn-danger btn-sm' onclick=\"location.href='../../_prog/goods/list_card_clear.php?id=".$k."&product_id=".$product_id."'\">Удалить</button>";
                            echo "</div>";
                            echo "</div>";
                        $k++;
                        }
                    }else{
                        echo "<div class = 'col-sm-12 div-center'>";
                        echo "<div style = 'color: white; background-color: coral; padding: 20px'>Нет дополнительных изображений для дизайна!</div>";
                        echo "</div>";
                    }
                    $_SESSION['ntd'] = $name_to_delete;//Пишем массив для передачи в сессионную переменную
                ?>
                </div>
    </div>
    <div class="form-group col-sm-12">
        <label for="production_img">Загрузить несколько фото для карточки товара</label>
        <input type='file' class='many_prod_image' name='many_prod_image[]' multiple>
        <p class='help-block'>Размер каждого файла 1900px х 1900px не более 6 Мб</p>
    </div>
    <div class="col-md-12" style="text-align: center;">
			<div class="btn-group">
                <button type = "submit" id = "" class="btn btn-success" name="submit">Сохранить изменения</button>
				<button type = "button" class = "btn btn-warning" onclick="history.back();">Отмена</button> 
			</div>
    </div>    
    </form>
</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>