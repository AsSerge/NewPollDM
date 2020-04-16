<?php
session_start();
include('connect/connect_to_base.php');

$question_id = $_GET['question_id'];
$poll_id = $_SESSION['poll_id'];
$u_id = $_SESSION['u_id'];

$poll_name_quest = 'SELECT poll_name FROM poll WHERE poll_id = ?';
$quest_p_name = $db->prepare($poll_name_quest);
$quest_p_name->execute([$poll_id]);
$poll_name = $quest_p_name->fetchColumn();

$u_name_quest = 'SELECT u_name FROM user WHERE u_id = ?';
$u_p_name = $db->prepare($u_name_quest);
$u_p_name->execute([$u_id]);
$u_name = $u_p_name->fetchColumn();

$q_name_quest = 'SELECT question_name, question_model FROM question WHERE question_id = ?';
$q_p_name = $db->prepare($q_name_quest);
$q_p_name->execute([$question_id]);
// $question_name = $q_p_name->fetchColumn();
$question_set = $q_p_name->fetch(PDO::FETCH_LAZY);

$design_quest = "SELECT * FROM design WHERE question_id = ?";
$sql_des = $db->prepare($design_quest);
$sql_des->execute([$question_id]);
// $design_set = $sql_des->fetch(PDO::FETCH_LAZY);


?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Опрос по дизайнам</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="cache-control" content="no-cache">
    <meta http-equiv="expires" content="0">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200,300,300i,500&display=swap&subset=cyrillic,cyrillic-ext" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/custom.css">
</head>
<body>
<nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-dark">
    <a class="navbar-brand mr-auto mr-lg-0" href="#"><?php echo $poll_name?></a>
</nav>
