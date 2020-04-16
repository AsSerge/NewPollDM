<?php
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');
$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек

$product_id =$_POST['product_id']; //Название редактируемого продукта

// Соединямся с БД
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_base);
//Устанавливаем кодировку
        mysqli_query($link,"SET NAMES 'utf8'"); 
        mysqli_query($link,"SET CHARACTER SET 'utf8'");
        mysqli_query($link,"SET SESSION collation_connection = 'utf8_general_ci'");

        $quest_m = "SELECT * FROM `main_img` WHERE `product_id` = '".$product_id."'";
        $query_m = mysqli_query($link, $quest_m);
        $row_m = mysqli_fetch_array($query_m);

// Получаем список картинок, обрабатываем и пишем в базу !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// $source_dir = $_SERVER['DOCUMENT_ROOT']."/_source_files/";
$section = $row_m['section'];
$trademark = $row_m['trademark'];
$color_capsule = $row_m['color_capsule'];
$design = $row_m['design'];

$source_dir = "K:\\OpenServer\\OSPanel\\domains\\cat.dmtextile.ru\\images\\catalog\\{$section}\\{$trademark}\\{$color_capsule}\\{$design}\\";
// $source_dir = "K:\\OpenServer\\OSPanel\\domains\\cat.dmtextile.ru\\images\\catalog\\001_bathroom\\Cleanelly-Perfetto\\Lino\\";
// $source_dir = $_SERVER['DOCUMENT_ROOT']."/images/catalog/{$section}/{$trademark}/{$color_capsule}";//Получаем каталог для изображений
// echo $source_dir;

//Установка необходимых размеров и префиксов для файлов

// >>Карточка товара
// Карточка товара  (1900x1900)                    cfb_
// Карточка товара  (600x600)                      cfm_
// Карточка товара миниатюра (100x100)             cfs_

// Работаем с множеством картинок !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
//Пишем картинки галереи если хотябы одна существует
// $source     = Исходный файл
// $new_file   = Новый файл
// $size       = Необходимый размер
// $img_change = Необходимость в изменениии размера
// function quest_image($source, $new_file, $size, $img_change)

if(!empty($_FILES['many_prod_image']['name'][0])){
    $cfb = 1900;
    $cfs = 100;
    $cfm = 600;
    
    $img_arr = $_FILES['many_prod_image']['tmp_name'];//Получаем массив загруженных фото
    $img_name_arr = $_FILES['many_prod_image']['name'];//Получаем массив ИМЕН загруженных фото

    $k=0;
    foreach($img_arr as $img){
            $img_name = $img_name_arr[$k];//Определяем Действительное имя файла            

            quest_image($img, $source_dir."cfb_".$product_id."_".$img_name, $cfb, true);
            quest_image($img, $source_dir."cfs_".$product_id."_".$img_name, $cfs, true);
            quest_image($img, $source_dir."cfm_".$product_id."_".$img_name, $cfm, true);
            $k++;
    }
}
header("Location: /_prog/goods/index.php?product_id={$product_id}"); exit();

?>