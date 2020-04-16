<?php
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек

// Соединямся с БД
$link = mysqli_connect($db_host, $db_user, $db_pass, $db_base);
//Устанавливаем кодировку
        mysqli_query($link,"SET NAMES 'utf8'"); 
        mysqli_query($link,"SET CHARACTER SET 'utf8'");
        mysqli_query($link,"SET SESSION collation_connection = 'utf8_general_ci'");


//Основные параметры
$quest = "SELECT * FROM `main_img` WHERE 1";  
$query = mysqli_query($link, $quest);


function GetSub($product_id, $prexix, $link){
    $quest_sub = "SELECT * FROM `sub-img` WHERE `product_id` = {$product_id}";  
    $query_sub = mysqli_query($link, $quest_sub);    
    while($row=mysqli_fetch_array($query_sub)){
        $csv_str = '';
        $scv_str .= $prexix;
        $scv_str .= $row['id'].";";
        $scv_str .= $row['vendor_code'].";";
        $scv_str .= "'".$row['color'].";";
        $scv_str .= $row['size'].";";        
        $scv_str .= $row['density']."\n";
    }
return $scv_str;
}

$csv_file = fopen('product.csv','w');

$heder_csv = '';
$heder_csv .= "#".";";
$heder_csv .= "Раздел".";";
$heder_csv .= "Раздел(рус)".";";
$heder_csv .= "Тогровая марка".";";
$heder_csv .= "Капсула".";";
$heder_csv .= "Капсула(рус)".";";
$heder_csv .= "Тип продукта".";";
$heder_csv .= "Дизайн".";";
$heder_csv .= "Заголовок".";";
$heder_csv .= "Глтег".";";
$heder_csv .= "Тег1".";";
$heder_csv .= "Тег2".";";
$heder_csv .= "Тег3".";";
$heder_csv .= "Ткань".";";
$heder_csv .= "Крашение".";";
$heder_csv .= "Полное описание".";";
$heder_csv .= "Отношение".";";
$heder_csv .= "Главная картинка".";";
$heder_csv .= "ТН".";";
$heder_csv .= "Артикул".";";
$heder_csv .= "Цвет".";";
$heder_csv .= "Размер".";";
$heder_csv .= "Плотность"."\n";

// echo $heder_csv;
//Пишем заголовок
fwrite ($csv_file, iconv("utf-8", "windows-1251", $heder_csv));

while($row=mysqli_fetch_array($query)){
    $prexix = '';    
    $prexix .= $row['product_id'].";";
    $prexix .= $row['section'].";";
    $prexix .= $row['section_rus'].";";
    $prexix .= $row['trademark'].";";
    $prexix .= $row['color_capsule'].";";
    $prexix .= $row['color_capsule_rus'].";";
    $prexix .= $row['product_type'].";";
    $prexix .= $row['design'].";";
    $prexix .= $row['description'].";";
    $prexix .= $row['main_tag'].";";
    $prexix .= $row['tag1'].";";
    $prexix .= $row['tag2'].";";
    $prexix .= $row['tag3'].";";
    $prexix .= $row['cloth'].";";
    $prexix .= $row['dyeinig'].";";
    $prexix .= $row['full_description'].";";
    $prexix .= $row['relationship'].";";
    $prexix .= $row['main_img'].";";
    
    // Пишем в файл построчно
    fwrite ($csv_file, iconv("utf-8", "windows-1251", GetSub($row['product_id'], $prexix, $link)));
}
fclose ($csv_file);

?>