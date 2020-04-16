<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');

?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>

<?php
    echo "<h4>Новый автор</h4>";
    echo "<form action = '/_prog/polls/edit_author_exe.php' method = 'POST'>";
    echo "<input type='hidden' name = 'author_action' value = 'add'>";
    echo "<table class = 'table table-bordered table_sm'>";
    // echo "<tr><th>Имя Фамилия автора</th><th>Адрес электронной почты</th><th>Телефон</th><th>Должность</th></tr>";
    echo "<tr>";
    echo "<td><input type='text' class='form-control' name = 'author_name' placeholder = 'фамилия и имя автора'></td>";
    echo "<td><input type='text' class='form-control' name = 'author_mail' placeholder = 'Адрес электронной почты'></td>";
    echo "<td><input type='text' class='form-control' name = 'author_phone' placeholder = 'Телефон'></td>";
    echo "<td><input type='text' class='form-control' name = 'author_position' placeholder = 'Должность'></td>";

    echo "</tr>";
    echo "</table>";
    echo "<div class='row'>";
    echo "        <div class = 'col-md-12 button_center'>";
    echo "            <input class='btn btn-success' type='submit' value='Сохранить'>";
    echo "            <input class='btn btn-warning' type='reset' value='Сбросить'>";
    echo "        </div>";
    echo "</div>";
    echo "</form>";   
?>

</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>