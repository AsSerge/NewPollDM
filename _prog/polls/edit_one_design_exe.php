<?php
session_start();
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');
// Получаем значение из модальной формы
$design_id = $_POST['design_id'];
$design_name = $_POST['design_name'];
// Готовим запрос
$sql = "UPDATE design SET design_name = ? WHERE design_id = ?"; // Плейсхолдер запроса
$stmt = $db->prepare($sql); // Готовим запрос
$stmt->bindParam(1, $design_name);
$stmt->bindParam(2, $design_id);
// Исполняем запрос
$stmt->execute();
// Возвращаемся на страницу
header("Location: ".$_SERVER['HTTP_REFERER']); exit();
?>