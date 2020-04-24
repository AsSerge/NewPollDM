<?php
session_start();
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

// Получаем значения
$poll_id = $_POST['poll_id'];
$group_id = $_POST['group_id'];
$mailing_name = $_POST['mailing_name'];
$mailing_text = trim($_POST['mailing_text']);
$author_id = $_POST['author_id'];

if($_POST['mailing_id']){
    $mailing_id = $_POST['mailing_id'];
}

if($_POST['mail_save_action'] == 'add'){
    $mailing_send = 0;
    $sql = "INSERT INTO poll_mailing (poll_id, group_id, mailing_name, mailing_text, author_id, mailing_send) VALUES (?, ?, ?, ?, ?, ?)"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $poll_id);
    $stmt->bindParam(2, $group_id);
    $stmt->bindParam(3, $mailing_name);
    $stmt->bindParam(4, $mailing_text);
    $stmt->bindParam(5, $author_id);
    $stmt->bindParam(6, $mailing_send);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/"); exit();


}elseif($_POST['mail_save_action'] == 'update') {
    $sql = "UPDATE poll_mailing SET poll_id = ?, group_id = ?, mailing_name = ?, mailing_text = ?, author_id = ? WHERE mailing_id = ?";
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $poll_id);
    $stmt->bindParam(2, $group_id);
    $stmt->bindParam(3, $mailing_name);
    $stmt->bindParam(4, $mailing_text);
    $stmt->bindParam(5, $author_id);
    $stmt->bindParam(6, $mailing_id);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/"); exit();
    
}

?>