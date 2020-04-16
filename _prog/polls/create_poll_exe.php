<?php
session_start();
// Управление опросами
include('../../rnd/connect/connect_to_base.php');
$poll_manage = $_POST['poll_manage']; // Тригер действия с опросом
// Получаем ID опроса при редактированиии
if($_POST['poll_id']){
    $poll_id = $_POST['poll_id'];
}
// Получаем значение полей

$poll_name = $_POST['poll_name'];
$poll_description = $_POST['poll_description'];
$poll_date_begin = $_POST['poll_date_begin'];
$poll_date_end = $_POST['poll_date_end'];
$questions_count = $_POST['questions_count'];
// Если не введено количество каталогов - автоматически присваивается 1
if($questions_count == 0){
    $questions_count = 1;
}

// Добавляем новый опрос
if($poll_manage == 'poll_add'){
    
    $sql = "INSERT INTO poll (poll_name, poll_description, poll_date_begin, poll_date_end) VALUES (?,?,?,?)"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос

    $stmt->bindParam(1, $poll_name);    
    $stmt->bindParam(2, $poll_description);
    $stmt->bindParam(3, $poll_date_begin);
    $stmt->bindParam(4, $poll_date_end);

    $stmt->execute();

    $LastIndex = $db->lastInsertId(); // Получаем последний индекс AUTO_INCREMENT для poll_id
    // Создаем каталог с подкаталогами для изображений
    // Формат: /rnd/images/[номер опроса]/[номер вопроса]
    $UpDir = "../../rnd/images/".$LastIndex;
    mkdir($UpDir); // Основной каталог опроса
    for ($k=1;$k<=$questions_count;$k++){
        // Создаем подкаталоги по количеству вопросов
        // mkdir($UpDir."/".$k);
        // Создаем запись в question по количеству вопросов опроса. Название и описание пустые, Голосование = 1, количество выбирамых вариантов = 1
        $sql = "INSERT INTO question (poll_id, question_name, question_description, question_voting, question_model) VALUES (?, '', '', '1', '1')";
        $stmt = $db->prepare($sql); // Готовим запрос
        $stmt->bindParam(1, $LastIndex);    
        $stmt->execute();
        // Создаем подкаталоги по количеству вопросов именем по ID вопроса
        $LastSubDirIndex = $db->lastInsertId();
        mkdir($UpDir."/".$LastSubDirIndex);
    }


}elseif ($poll_manage == 'poll_update') {

    $sql = "UPDATE poll SET poll_name = ?, poll_description = ?, poll_date_begin = ?, poll_date_end = ? WHERE poll_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос

    $stmt->bindParam(1, $poll_name);    
    $stmt->bindParam(2, $poll_description);
    $stmt->bindParam(3, $poll_date_begin);
    $stmt->bindParam(4, $poll_date_end);
    $stmt->bindParam(5, $poll_id);

    $stmt->execute();
}   

header("Location: /_prog/"); exit();
?>