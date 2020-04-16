<?php
session_start();
// Сохранение результата
include('../../rnd/connect/connect_to_base.php');

$poll_id = $_POST['poll_id'];
$question_id = $_POST['question_id'];
$u_id = $_POST['u_id'];

// функция очистки поля сообщения
function сleanComment($value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);    
    return $value;
}


// Получаем имя голосоущего для занесения в базу результатов

$sql = 'SELECT u_name FROM user WHERE u_id = ?';
$stmt = $db->prepare($sql); // Готовим запрос
$stmt->bindParam(1, $u_id);
// Исполняем запрос
$stmt->execute();
$u_name = $stmt->fetch(); // Записываем реальное имя на момент голосования в поле u_name_voted

// Получаем массив выбранных дизайнов
$design_selected = $_POST['result_choice'];

// Получаем массив комментариев
$add_comments = $_POST['result_comment'];

// Перебор всех дизайнов из вопроса и добавления их в базу
$design_id_arr = $_SESSION['design_id_arr']; // В массиве последовательно хранятся ID всех вопросов из опроса

$i = 0;
foreach($design_id_arr as $answer){
    $design_id = $design_id_arr[$i]; // id дизайна в обработке
    if(in_array($answer, $design_selected)){
        $result_choice = 1; // Результат - выбран
    }else{
        $result_choice = 0; // Результат - НЕ выбран
    }
    $result_comment = $add_comments[$i]; // Комментарий

    $result_comment = сleanComment($result_comment); // Комментарий

    
    // Пишем в базу
    $sql = "INSERT INTO poll_result (poll_id, question_id, design_id, u_id, u_name_voted, result_choice, result_comment) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql); // Готовим запрос

    $stmt->bindParam(1, $poll_id);
    $stmt->bindParam(2, $question_id);
    $stmt->bindParam(3, $design_id);
    $stmt->bindParam(4, $u_id);
    $stmt->bindParam(5, $u_name[0]);
    $stmt->bindParam(6, $result_choice);
    $stmt->bindParam(7, $result_comment);
    
    $stmt->execute();

    $i++;
}
header("Location: ../../../../rnd/index.php?poll_id={$poll_id}&u_id={$u_id}"); exit();
?>