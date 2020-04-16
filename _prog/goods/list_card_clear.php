<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');
$ini_arr = parse_ini_file("../layot/edit_table.ini", true);//файл настроек

$product_id =$_GET['product_id']; //Название редактируемого продукта
//Получаем из сессионной переменной массив и индекс строки, которую надо удалить
foreach ($_SESSION['ntd'][$_GET['id']] as $file_to_del) {
    unlink($file_to_del);
}
session_destroy();
header("Location: /_prog/goods/list_card_editor.php?product_id={$product_id}"); exit();
?>