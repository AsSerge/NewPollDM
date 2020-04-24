<?php
session_start();
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');
// Подключаем файл PHPMailerFunction.php
include('../../_prog/polls/PHPMailer/PHPMailerFunction.php');

// Получаем значения
$poll_id = $_GET['poll_id'];
$mailing_id = $_GET['mailing_id'];

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

//  Формируем массив отправки
$group_id = $mail_parts['group_id'];
// Получаем массив пользователей для данной рассылки
$user_quest = 'SELECT * FROM user WHERE group_id = ?';
$g_users = $db->prepare($user_quest);
$g_users->execute([$group_id]);
$i = 0;
while($row = $g_users->fetch()){
	$user[$i]['u_id'] = $row['u_id'];
	$user[$i]['u_token'] = $row['u_token'];
	$user[$i]['mail'] = $row['u_mail'];
	$user[$i]['name'] = $row['u_name'];    
	$i++;
}
// $name - Имя получателя
// $mail - Адрес получателя
// $subject - Тема сообщения
// $message - Сообщение
// $sender_mail - Почта отправителя
// $sender_name - Имя отправителя

foreach($user as $u){
	$mail = $u['mail'];
	$subject = "{$u['name']} - Голосование по теме: {$poll_name}";

	$message = $mailing_text."<br>";
	$message .= "Ваша ссылка для голосования: ";
	$message .= "<a href = 'http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$u['u_token']}'>http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$u['u_token']}</a><br>";
	$message .= "========================================================================<br>";
	$message .= "C Уважением<br>";
	$message .= $author_name."<br>";
	$message .= $author_position."<br>";
	$message .= "АО «ТПК «ДМ Текстиль Менеджмент»<br>Ростов-на-Дону, ул. Лермонтовская, 197/73<br>";
	$message .= "тел.: ".$author_phone;

	$sender_mail = $author_mail;
	$sender_name = $author_name;
	// echo $mai."<br>";
	// echo $subject."<br>";
	// echo $message."<br>";
	// echo $sender_mail."<br>";
	// echo $sender_name."<br>";
	
	// echo "**************************************************************";
	SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);
	sleep(3);
}



// SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);
echo "<br>Почта отправлена (test)";

// header("Location: /_prog/"); exit();
?>