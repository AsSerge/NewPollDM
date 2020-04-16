<?php
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');
$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек


// Получаем текстовую информацию о товаре и пишем в базу
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


$design = $_POST['design']; //Название дизайна        
$product_type = $_POST['product_type']; // Тип товара для главной страницы
$full_description = $_POST['full_description'];// Описание товара

$description = $_POST['description']; // Описание товара для тегов
$cloth = $_POST['cloth']; // Тип ткани
$dyeing = $_POST['dyeing']; //Тип крашения

$main_tag = $_POST['main_tag']; //Главный тег
$tag1 = $_POST['tag1']; //Тег 1
$tag2 = $_POST['tag2']; //Тег 2
$tag3 = $_POST['tag3']; //Тег 3

mysqli_query($link,"UPDATE `main_img` SET
`design`='".$design."',
`product_type`='".$product_type."',
`full_description`='".$full_description."',
`description`='".$description."',
`cloth`='".$cloth."',
`dyeing`='".$dyeing."',
`main_tag`='".$main_tag."',
`tag1`='".$tag1."',
`tag2`='".$tag2."',
`tag3`='".$tag3 ."'
WHERE `product_id`='{$product_id}'
");

// Получаем главную картинку, обрабатываем и пишем в базу !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// $source_dir = $_SERVER['DOCUMENT_ROOT']."/_source_files/";
$section = $row_m['section'];
$trademark = $row_m['trademark'];
$color_capsule = $row_m['color_capsule'];
$design = $row_m['design'];

$source_dir = "K:\\OpenServer\\OSPanel\\domains\\cat.dmtextile.ru\\images\\catalog\\{$section}\\{$trademark}\\{$color_capsule}\\{$design}\\";
// $source_dir = "K:\\OpenServer\\OSPanel\\domains\\cat.dmtextile.ru\\images\\catalog\\001_bathroom\\Cleanelly-Perfetto\\Lino\\";
// $source_dir = $_SERVER['DOCUMENT_ROOT']."/images/catalog/{$section}/{$trademark}/{$color_capsule}";//Получаем каталог для изображений
// echo $source_dir;

//Пишем картинки галереи если хотябы одна существует
// $source     = Исходный файл
// $new_file   = Новый файл
// $size       = Необходимый размер
// $img_change = Необходимость в изменениии размера
// function quest_image($source, $new_file, $size, $img_change)

//Установка необходимых размеров и префиксов для файлов
// >>Админка
// Главное фото (1900x1900)                        mfb_
// Главное фото миниатюра (100x100)                mfs_

// >>Список товаров                          
// Главное фото в список товаров (600x600)         mfm_

// >>Карточка товара
// Карточка товара  (1900x1900)                    cfb_
// Карточка товара  (600x600)                      cfm_
// Карточка товара миниатюра (100x100)             mfs_

// >>Вариант цвета
// Вариант цвета (1900x1900)                       vfb_
// Вариант цвета миниатюра (100x100)               vfs_

if(!empty($_FILES['prod_image']['tmp_name'])){
        $old_img = $row_m['main_img'];//Получаем имя старого файла для удаления его из каталога
        $img = $_FILES['prod_image']['tmp_name'];
        $prod_image = $_FILES['prod_image']['name'];//Здесь получаем ДЕЙСТВИТЕЛЬНОЕ имя картинки для записи в базу
        $img_size = getimagesize($img);//Проверяем размер
        //Пишем рабочий файл, переименовываем его и устанавливаем необходимые размеры и префиксы
        //Удаляем все файлы по вхождению
        foreach (glob("{$source_dir}mf*_{$product_id}_*.*") as $docFile) {
                unlink($docFile);
        } 
        $mfb = 1900;
        $mfs = 100;
        $mfm = 600;
        quest_image($img, $source_dir."mfb_".$product_id."_".$prod_image, $mfb, true);
        quest_image($img, $source_dir."mfs_".$product_id."_".$prod_image, $mfs, true);
        quest_image($img, $source_dir."mfm_".$product_id."_".$prod_image, $mfm, true);

        //Удвляем старый исходник
        if($old_img != ''){
                unlink ($source_dir.$old_img);
        }
        
        //Пишем в базу название нового файла - исходника        
        mysqli_query($link,"UPDATE `main_img` SET
        `main_img`='".$prod_image."'
        WHERE `product_id`='{$product_id}'
        ");

        //Пишем оригинальный файл в папку исходников с оригинальным именем
        move_uploaded_file($img, $source_dir.$prod_image);

}

header("Location: /_prog/goods/index.php?product_id={$product_id}"); exit();

?>