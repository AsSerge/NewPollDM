<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
$poll_id = $_GET['poll_id'];
$mailing_id = $_GET['mailing_id'];
?>
<style>
.button_center{
	text-align: center;
}
</style>
<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4>Статистика</h4>


</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>