<?php
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

//Основные параметры
$quest_m = "SELECT * FROM `main_img` WHERE `product_id` = '".$product_id."'";
$query_m = mysqli_query($link, $quest_m);
$row_m = mysqli_fetch_array($query_m);        
//База дополнительный параметров
$quest = "SELECT * FROM `sub-img` WHERE `product_id` = '".$product_id."' ORDER BY product_id";  
$query = mysqli_query($link, $quest);

//База цветов
$quest_color = "SELECT * FROM `sub-img` WHERE `product_id` = '".$product_id."' ORDER BY product_id";  
$query_color = mysqli_query($link, $quest_color);

?>

<div class="container">
        <div style = "clear: both; height: 50px;"></div>
<?php
echo "<h3>".$section_rus."</h3>";
echo $row_m['product_type']." - ".$row_m['relationship'];
// Формируем страницу в зависимости от типа relation
include_once ("ParentCapsule.php");
// include_once ($row_m['relationship'].".php");
?>

</div>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>