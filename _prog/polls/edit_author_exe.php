<?php
session_start();

// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

// Получаем текущую базу e-mail для проверки на дубликаты
$sql_num = 'SELECT author_mail FROM author WHERE 1';
$stmt=$db->prepare($sql_num);
$stmt->execute();
$poll_set = $stmt->fetchAll();
//Заполняем массив для проверки количества вхождений
$mail_test = array();
foreach ($poll_set as $ps) {
    $mail_test[] = $ps['author_mail'];
}

// Функция обработки почтового адреса при добавлении и обновлении
function PrepMail($u_mail){
    $u_mail = strtolower($u_mail); // Преобразуем в нижний регистр
    $u_mail = trim($u_mail); // Удаляем лишние пробелы и управляющие символы
    if (preg_match("/^(?:[a-z0-9]+(?:[-_.]?[a-z0-9]+)?@[a-z0-9_.-]+(?:\.?[a-z0-9]+)?\.[a-z]{2,5})$/i", $u_mail)) {
        return $u_mail;
    }else{
        return "<<<<< Ошибка в адресе: {$u_mail} >>>>>";
    }
}
// Функция проверки почтового адреса при обновлении
function CheckMail($db, $u_id, $u_mail){
    $sql = 'SELECT author_mail FROM author WHERE author_id = ?';
    $stmt=$db->prepare($sql);
    $stmt->bindParam(1, $u_id);
    $stmt->execute();
    $ch_mail = $stmt->fetch();
    if($ch_mail[0] == $u_mail){
        return true;
    }else{
        return false;
    }    
}
// функция очистки поля сообщения
function сleanComment($value) {
    $value = trim($value);
    $value = stripslashes($value);
    $value = strip_tags($value);
    $value = htmlspecialchars($value);    
    return $value;
}

// Получаем значение из модальной формы
if($_POST['author_action'] == "add"){
    $author_name = сleanComment($_POST['author_name']);
    $author_mail = PrepMail($_POST['author_mail']);    
    //********************** Проверка на дубликаты **********************
    if(in_array($author_mail, $mail_test) && count($mail_test) != 0){
        $author_mail = "<<<<< Дубликат: {$author_mail} >>>>>";
    }else{
        $author_mail = $author_mail;
    }
    $author_phone = $_POST['author_phone'];
    $author_position = сleanComment($_POST['author_position']);
    // Готовим запрос
    $sql = "INSERT INTO author (author_name, author_mail, author_phone, author_position ) VALUES (?, ?, ?, ?)"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $author_name);
    $stmt->bindParam(2, $author_mail);
    $stmt->bindParam(3, $author_phone);
    $stmt->bindParam(4, $author_position);
    
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/polls/edit_author.php"); exit();
  
}elseif($_POST['author_action'] == "update"){
    $author_id = $_POST['author_id'];
    $author_name = сleanComment($_POST['author_name']);
    $author_mail = PrepMail($_POST['author_mail']);    
    //********************** Проверка на дубликаты **********************
    if(CheckMail($db, $u_id, $u_mail)){
        // Почта НЕ обновляется
        $author_mail = PrepMail($_POST['author_mail']);        
    }else{
        if(in_array($author_mail, $mail_test) && count($mail_test) != 0){
            $author_mail = "<<<<< Дубликат: {$author_mail} >>>>>";
        }else{
            $author_mail = $author_mail;
        }
    }

    $author_phone = сleanComment($_POST['author_phone']);
    $author_position = сleanComment($_POST['author_position']);

    // Готовим запрос
    $sql = "UPDATE author SET author_name = ?, author_mail = ?, author_phone = ?, author_position = ? WHERE author_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $author_name);
    $stmt->bindParam(2, $author_mail);
    $stmt->bindParam(3, $author_phone);
    $stmt->bindParam(4, $author_position);
    $stmt->bindParam(5, $author_id);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу
    header("Location: ".$_SERVER['HTTP_REFERER']); exit();
}elseif($_GET['author_action'] == 'remove' && $_GET['author_id'] != ''){
    $author_id = $_GET['author_id'];
    // Готовим запрос
    $sql = "DELETE FROM author WHERE author_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $u_id);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/polls/edit_author.php"); exit();
}
?>