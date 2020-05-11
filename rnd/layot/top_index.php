<?php
session_start();
include('connect/connect_to_base.php');

$poll_id = $_GET['poll_id']; //получаем имя опроса по id
//$u_id = $_GET['u_id']; // получаем имя клиента по id
$u_token = $_GET['u_token']; // получаем имя клиента по токену


$_SESSION['poll_id'] = $poll_id;
// $_SESSION['u_id'] = $u_id;
$_SESSION['u_token'] = $u_token;

$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();

$u_name_quest = 'SELECT u_name FROM user WHERE u_token = ?';
$u_p_name = $db->prepare($u_name_quest);
$u_p_name->execute([$u_token]);
$u_name = $u_p_name->fetchColumn();

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Опрос по дизайнам</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="none">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,300i,500&display=swap&subset=cyrillic,cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <?php 
    if($poll_name){
        $head = $poll_name;
    }else{
        $head = "Анкетирование DM";
    }
    ?>
    <a class="navbar-brand mr-auto mr-lg-0" href="#"><?php echo $head?></a>
</nav>
