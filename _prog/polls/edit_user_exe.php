<?php
session_start();

// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

// Получаем текущую базу e-mail для проверки на дубликаты
$sql_num = 'SELECT u_mail FROM user WHERE 1';
$stmt=$db->prepare($sql_num);
$stmt->execute();
$poll_set = $stmt->fetchAll();
//Заполняем массив для проверки количества вхождений
$mail_test = array();
foreach ($poll_set as $ps) {
    $mail_test[] = $ps['u_mail'];
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
    $sql = 'SELECT u_mail FROM user WHERE u_id = ?';
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

// Получаем значение из модальной формы
if($_POST['user_action'] == "add"){
    $u_name = $_POST['u_name'];
    $u_mail = PrepMail($_POST['u_mail']);
    $group_id = $_POST['group_id'];
    //********************** Проверка на дубликаты **********************
    if(in_array($u_mail, $mail_test) && count($mail_test) != 0){
        $u_mail = "<<<<< Дубликат: {$u_mail} >>>>>";
    }else{
        $u_mail = $u_mail;
    }
    // Готовим запрос
    $sql = "INSERT INTO user (u_name, u_mail, group_id) VALUES (?, ?, ?)"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $u_name);
    $stmt->bindParam(2, $u_mail);
    $stmt->bindParam(3, $group_id);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/polls/edit_user.php"); exit();
    
}elseif($_POST['user_action'] == "update"){
    $u_id = $_POST['u_id'];
    $u_name = $_POST['u_name'];
    $u_mail = $_POST['u_mail'];
    //********************** Проверка на дубликаты **********************
    if(CheckMail($db, $u_id, $u_mail)){
        // Почта НЕ обновляется
        $u_mail = PrepMail($_POST['u_mail']);        
    }else{
        // Почта обновляется - проверяем на дубликаты
        if(in_array($u_mail, $mail_test)){
            $u_mail = "<<<<< Дубликат: {$u_mail} >>>>>";
        }else{
            $u_mail = PrepMail($_POST['u_mail']);
        }
    }
    $group_id = $_POST['group_id'];

    // Готовим запрос
    $sql = "UPDATE user SET u_name = ?, u_mail = ?, group_id = ? WHERE u_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $u_name);
    $stmt->bindParam(2, $u_mail);
    $stmt->bindParam(3, $group_id);
    $stmt->bindParam(4, $u_id);

    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу
    header("Location: ".$_SERVER['HTTP_REFERER']); exit();
}elseif($_GET['user_action'] == 'remove' && $_GET['u_id'] != ''){
    $u_id = $_GET['u_id'];
    // Готовим запрос
    $sql = "DELETE FROM user WHERE u_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $u_id);
    // Исполняем запрос
    $stmt->execute();
    // Возвращаемся на страницу    
    header("Location: /_prog/polls/edit_user.php"); exit();
}elseif ($_POST['user_action'] == "add_list") {
    // Добавляем пользователей из списка
    $group_id = $_POST['group_id'];    
    // Получаем файл и грузим его в необходимую дирректорию
    $source_dir = $_SERVER['DOCUMENT_ROOT']."/rnd/docs/";    
    if(!empty($_FILES['user_list']['name'])){            
        // Очистка каталога с изображениями
        // ********************************
        if (file_exists($source_dir)){
            foreach (glob($source_dir.'*') as $file){
                unlink($file);
            }
        }
        // ********************************
        // Перемещение файла для работы
        move_uploaded_file($_FILES['user_list']['tmp_name'], $source_dir.$_FILES['user_list']['name']);
        $user_list_file = $source_dir.$_FILES['user_list']['name']; // Файл для обработки        
        $handle = fopen($user_list_file, "r");

        $db->beginTransaction(); // Начинаем транзакцию        
        $sql_add_list = 'INSERT INTO user (u_name, u_mail, group_id) VALUES (?, ?, ?)'; // готовим запрос
        $snd_user = $db->prepare($sql_add_list);

        while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
            $u_name = iconv('windows-1251', 'UTF-8', $data[0]); // перекодируем
            $u_mail = iconv('windows-1251', 'UTF-8', $data[1]); // перекодируем
            // Проверка на дубликаты и ошибки в тексте
            if(in_array($u_mail, $mail_test)){
                $u_mail = "<<<<< Дубликат: {$u_mail} >>>>>"; // помечаем дубликаты
            }else{
                $u_mail = PrepMail($u_mail);// проверяем добавляемые почтовые адреса на ошибки в тексте
            }
            // Готовим данные
            $snd_user->bindParam(1, $u_name);
            $snd_user->bindParam(2, $u_mail);
            $snd_user->bindParam(3, $group_id);
            // Исполняем запрос
            $snd_user->execute();
        }        
        $db->commit(); // Закрываем транцакцию и пишем в базу
        fclose($handle); // Закрываем файл
    }
}

?>