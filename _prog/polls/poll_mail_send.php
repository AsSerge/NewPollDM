<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
// Получаем данные для рассылки
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
?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4>Отправка сообщений</h4>
<?php 

// require_once '/_prog/polls/PHPMailer/PHPMailerFunction.php';

foreach($user as $u){
$text = $mailing_text."<br>";
$text .= "{$u['name']} - Ваша ссылка для голосования: <a href = 'http://shop.cleanelly.ru/rnd/index.php?poll_id={$poll_id}&u_token={$u['u_token']}'>Ссылка</a><br>";
// $text .= "Ваша ссылка для голосования: <a href = '{$_SERVER['DOCUMENT_ROOT']}/rnd/index.php?poll_id={$poll_id}&u_id={$u['u_id']}'>Ссылка</a><br>";
$text .= "=========================================<br>";
$text .= "C Уважением<br>";
$text .= $author_name."<br>";
$text .= $author_position."<br>";
$text .= "АО «ТПК «ДМ Текстиль Менеджмент»<br>Ростов-на-Дону, ул. Лермонтовская, 197/73<br>";
$text .= "тел.: ".$author_phone."<br>";
echo $text;
echo "<br>";

// SendMailGRMP($mail, $subject, $message, $sender_mail, $sender_name);

}

echo "<pre>";
// print_r($user);
// print_r($mail_parts);
echo "</pre>";
// echo $text;
?>

<div class="row">
    <div class = "col-md-12 button_center">
        <a href = '/_prog/polls/add_group.php' class="btn btn-primary">Отправить</a>
        <input class="btn btn-warning" type="button" value="Отмена" onclick="javascript:location.href='/_prog/'";>
    </div>    
</div>
</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>