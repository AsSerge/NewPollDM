<?php
session_start();
ignore_user_abort(true);//Устанавливает, необходимо ли прерывать работу скрипта при отключении клиента. 
set_time_limit(600);//Устанавливает время автономной работы скрипта
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

// Функция записи информации в poll_mailing_stat (статистика отправки)
function AddStatInfo($db, $poll_id, $mailing_id, $u_id){
	$sql = "INSERT INTO poll_mailing_stat (poll_id, mailing_id, u_id) VALUES (?, ?, ?)";
	$stmt = $db->prepare($sql); // Готовим запрос
    $stmt->bindParam(1, $poll_id);
	$stmt->bindParam(2, $mailing_id);
	$stmt->bindParam(3, $u_id);
    // Исполняем запрос
    $stmt->execute();
}
// Подключаем файл PHPMailerFunction.php
include('../../_prog/polls/PHPMailer/PHPMailerFunction.php');

// Получаем значения
$poll_id = $_GET['poll_id'];
$mailing_id = $_GET['mailing_id'];
$u_id = $_GET['u_id'];

// Формируем данные для письма 
$sql = "SELECT group_id, poll_name, mailing_name, mailing_text, author_name, author_mail, author_phone, author_position
FROM poll_mailing
LEFT JOIN poll ON poll.poll_id = poll_mailing.poll_id
LEFT JOIN author ON author.author_id = poll_mailing.author_id
WHERE poll_mailing.mailing_id = ?";
$main_quest = $db->prepare($sql);
$main_quest->execute([$mailing_id]);
$mail_parts = $main_quest->fetch();

// Получаем переменные для формирование письма
$poll_name = $mail_parts['poll_name'];
$mailing_name = $mail_parts['mailing_name'];
$mailing_text = $mail_parts['mailing_text'];
$author_name = $mail_parts['author_name'];
$author_mail = $mail_parts['author_mail'];
$author_phone = $mail_parts['author_phone'];
$author_position = $mail_parts['author_position'];

// $name - Имя получателя
// $mail - Адрес получателя
// $subject - Тема сообщения
// $message - Сообщение
// $sender_mail - Почта отправителя
// $sender_name - Имя отправителя

$sql_user = 'SELECT * FROM user WHERE u_id = ?';
$user_sel = $db->prepare($sql_user);
$user_sel->execute([$u_id]);
$u = $user_sel->FetchAll();

$mail = $u[0]['u_mail'];
$subject = "{$u[0]['u_name']} - Голосование по теме: {$poll_name}";

$message = $mailing_text."<br>";
$message .= "Ваша ссылка для голосования: ";
$message .= "<a href = 'http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$u[0]['u_token']}'>http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$u[0]['u_token']}</a><br>";
$message .= "========================================================================<br>";
$message .= "C Уважением<br>";
$message .= $author_name."<br>";
$message .= $author_position."<br>";
$message .= "АО «ТПК «ДМ Текстиль Менеджмент»<br>Ростов-на-Дону, ул. Лермонтовская, 197/73<br>";
$message .= "тел.: ".$author_phone;

$sender_mail = $author_mail;
$sender_name = $author_name;
//********************************************************************
// echo $mail."<br>";
// echo $subject."<br>";
// echo $message."<br>";
// echo $sender_mail."<br>";
// echo $sender_name."<br>";

SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name); // Отправляем почту
AddStatInfo($db, $poll_id, $mailing_id, $u[0]['u_id']); // Пишем лог

header("Location: /_prog/polls/poll_mail_stat.php?poll_id={$poll_id}&mailing_id={$mailing_id}"); exit();
?>