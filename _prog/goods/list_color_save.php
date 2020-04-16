<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');
$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек

$product_id = $_POST['product_id'];
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

//База цветов
$quest_color = "SELECT * FROM `sub-img` WHERE `product_id` = '".$product_id."' ORDER BY product_id";  
$query_color = mysqli_query($link, $quest_color);

$files_array = $_FILES['prod_image']['tmp_name'];
//Заполняем массив уникальных цветов
$color_names = array();
while($row=mysqli_fetch_array($query_color)){
    $c = $row['color'];
    if(!in_array($c, $color_names)){
        $color_names[] = $row['color'];
    }
}
//Проверяем поступление изображения
foreach($files_array as $file){
    if ($file != ''){
        $foto_count = true;
    }
}
// Работаем с файлами, начинающимися с vfb
$product_id = $row_m['product_id'];
$section = $row_m['section'];
$trademark = $row_m['trademark'];
$color_capsule = $row_m['color_capsule'];                  
$design = $row_m['design'];
if($color_capsule){
  $source_dir = "../../images/catalog/{$section}/{$trademark}/{$color_capsule}/{$design}/";
}else{
  $source_dir = "../../images/catalog/{$section}/{$trademark}/{$design}/";
}
//Миниатюра
function vfs($source_dir, $product_id, $color_number){
  foreach (glob("{$source_dir}vfs_{$product_id}_{$color_number}_*.*") as $docFile) {
      return $docFile; //Получаем файл по маске
  }
}
//Большая картинка  
function vfb($source_dir, $product_id, $color_number){
  foreach (glob("{$source_dir}vfb_{$product_id}_{$color_number}_*.*") as $docFile) {
      return $docFile; //Получаем файл по маске
  }
} 
function colorDelete($source_dir, $product_id, $img_color, $img_name){
    //Каталог, ID продукта, Имя цвета, Реальное имя файла

    foreach (glob("{$source_dir}vfs_{$product_id}_{$img_color}_*.*") as $vfs_docFile) {
        $vfs = $vfs_docFile;//Пишем массив миниатюр
    }
    foreach (glob("{$source_dir}vfb_{$product_id}_{$img_color}_*.*") as $vfb_docFile) {
        $vfb = $vfb_docFile;//Пишем массив больших картинок                      
    }
    if($vfs){unlink($vfs);}
    if($vfb){unlink($vfb);}

    if(file_exists($source_dir.'vfs_'.$product_id.'_'.$img_color."_".$img_name)){
        unlink($source_dir.'vfs_'.$product_id.'_'.$img_color."_".$img_name);
    }
    if(file_exists($source_dir.'vfb_'.$product_id.'_'.$img_color."_".$img_name)){
        unlink($source_dir.'vfb_'.$product_id.'_'.$img_color."_".$img_name);
    }
}
//Начинаем работу, если поступили данные - поступили какие-либо данные
if($foto_count){
//Устанвливаем счетчик
$i=0;
$img_arr = $_FILES['prod_image']['tmp_name'];//Получаем массив загруженных фото
$img_name_arr = $_FILES['prod_image']['name'];//Получаем массив ИМЕН загруженных фото

//Устанавливаем размеры картинок
$vfb = 1900;
$vfs = 100;
//Получаем массив загруженных файлов $files_array
    foreach($img_arr as $img){
        // function quest_image($source, $new_file, $size, $img_change)

        $img_name = $img_name_arr[$i];//Определяем Действительное имя файла
        $img_color = $color_names[$i];//Определяем соответствующий цвет

        if($img_name != ''){
            //Удаляем старые файлы
            colorDelete($source_dir, $product_id, $img_color, $img_name);
            //Пишем файлы
            quest_image($img, $source_dir."vfs_".$product_id."_".$img_color."_".$img_name, $vfs, true);
            quest_image($img, $source_dir."vfb_".$product_id."_".$img_color."_".$img_name, $vfb, true);
        }    
        $i++;
    }    

}
header("Location: /_prog/goods/list_color_editor.php?product_id={$product_id}"); exit();
// echo "<pre>";
// print_r($color_names);
// print_r($files_array);
// echo "</pre>";
?>