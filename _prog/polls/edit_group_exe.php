<?php
session_start();
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

// Получаем значение из модальной формы
if($_POST['group_action'] == "add"){
    $group_id = $_POST['group_id'];
    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];

    // Готовим запрос
    $sql = "INSERT INTO groups (group_name, group_description) VALUES (?, ?)"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $group_name);
    $stmt->bindParam(2, $group_description);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/polls/edit_group.php"); exit();
    
}elseif($_POST['group_action'] == "update"){
    $group_id = $_POST['group_id'];
    $group_name = $_POST['group_name'];
    $group_description = $_POST['group_description'];

    // Готовим запрос
    $sql = "UPDATE groups SET group_name = ?, group_description = ? WHERE group_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $group_name);
    $stmt->bindParam(2, $group_description);
    $stmt->bindParam(3, $group_id);
    
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу
    header("Location: ".$_SERVER['HTTP_REFERER']); exit();
}
?>