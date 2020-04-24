<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once('../../_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');
// Получаем ID группы (если его передали)
$group_id = $_GET['group_id'];

// Получаем тип добавления (один или список) - one или multy
$add_type = $_GET['add_type'];

// Получаем массив груп пользователей
$user_gr_quest = 'SELECT * FROM groups WHERE 1';
$groups = $db->prepare($user_gr_quest);
$groups->execute();
?>
<style>
.button_center{
    text-align: center;
}  
</style>    

<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>

<?php
if($add_type == 'one'){
    echo "<h4>Новый пользователь</h4>";
    echo "<form action = '/_prog/polls/edit_user_exe.php' method = 'POST'>";
    echo "<input type='hidden' name = 'user_action' value = 'add'>";
    echo "<table class = 'table table-bordered table_sm'>";
    echo "<tr><td>Имя Фамилия пользователя</td><td>Адрес электронной почты</td><td>Группа</td></tr>";
    echo "<tr>";
    echo "<td><input type='text' class='form-control' name = 'u_name' placeholder = 'Введите фамилию и имя пользователя'></td>";
    echo "<td><input type='text' class='form-control' name = 'u_mail' placeholder = 'Введите адрес электронной почты'></td>";
    echo "<td>";
    
        echo "<select class='form-control' name = 'group_id' value = ''>";
        foreach ($groups as $row){
            // Проверяем - добавляем пользователя в группу
            if ($row['group_id'] == $group_id){  
                $sel = "selected";
            }else{
                $sel = "";
            }
            echo "<option value = '".$row['group_id']."' {$sel}>".$row['group_name']."</option>\r\n";
        }    
        echo "</select>";
    
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "<div class='row'>";
    echo "        <div class = 'col-md-12 button_center'>";
    echo "            <input class='btn btn-success' type='submit' value='Сохранить'>";
    echo "            <input class='btn btn-warning' type='reset' value='Сбросить'>";
    echo "        </div>";
    echo "</div>";
    echo "</form>";
}elseif ($add_type == 'multy') {
    echo "<h4>Список пользователей из файла</h4>";
    echo "<form action = '/_prog/polls/edit_user_exe.php' method = 'POST' enctype='multipart/form-data'>";    
    echo "<input type='hidden' name = 'user_action' value = 'add_list'>";
    echo "<table class = 'table table-bordered table_sm'>";    
    echo "<tr>";
    echo "<td colspan = '6'>";    
    echo "  <div class='form-group col-sm-8'>";
    echo "      <label for='user_list'>Загрузить файл в формате CSV</label>";
    echo "      <input type='file' class='user_list' name='user_list'>";
    echo "      <p class='help-block'>Скачать <a href = '".$_SERVER['DOCUMENT_ROOT']."/rnd/template/temp.csv'>образец файла</a> в формате CSV для подлива</p>";
    echo "  </div>";
    echo "  <div class='form-group col-sm-4'>";
    echo "<label for='group_id'>Выбрать группу для загрузки</label>";
    echo "<select class='form-control' name = 'group_id' value = ''>";
    foreach ($groups as $row){
        // Проверяем - добавляем пользователя в группу
        if ($row['group_id'] == $group_id){  
            $sel = "selected";
        }else{
            $sel = "";
        }
        echo "<option value = '".$row['group_id']."' {$sel}>".$row['group_name']."</option>\r\n";
    }    
    echo "</select>";    
    echo "  </div>";
    echo "</td>";
    echo "</tr>";
    echo "</table>";
    echo "<div class='row'>";
    echo "        <div class = 'col-md-12 button_center'>";
    echo "            <input class='btn btn-success' type='submit' value='Сохранить'>";
    echo "            <input class='btn btn-warning' type='reset' value='Сбросить'>";
    echo "        </div>";
    echo "</div>";
    echo "</form>";

}    
?>

</main>
<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>