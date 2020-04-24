<?php
session_start();
//В этом файле происходит подключение к базе и можно получить информацию о пользователе по ID -> $userdata[]
include($_SERVER['DOCUMENT_ROOT'].'/_prog/login/line_check.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_site.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/top_menu.php');
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/function_site.php');

include('../../rnd/connect/connect_to_base.php');

$poll_id = $_SESSION['poll_id'];

if ($_GET['question_id']){
	// Получаем данные указанного опроса
	$question_quest = 'SELECT * FROM question WHERE question_id = ?';
	$question = $db->prepare($question_quest);
	$question->execute([$_GET['question_id']]);
	$question_set = $question->fetch(PDO::FETCH_LAZY);//Заполняем массив

	// Получаем значения
	$poll_id = $question_set['poll_id'];
	$question_id = $question_set['question_id'];
	$question_name = $question_set['question_name'];
	$question_description = $question_set['question_description'];
	$question_voting = $question_set['question_voting'];
	$question_model = $question_set['question_model'];

	$source_dir = $_SERVER['DOCUMENT_ROOT']."/rnd/images/{$poll_id}/{$question_id}/"; // Определяем каталог для хранения файлов-изображений
	$source_loc = "../rnd/images/{$poll_id}/{$question_id}/"; // Определяем каталог для хранения файлов-изображений

	// Получаем изображения для вопроса
	$img_quest = 'SELECT * FROM design WHERE question_id = ?';
	$images_quest = $db->prepare($img_quest);
	$images_quest->execute([$_GET['question_id']]); // Получаем массив изображений
}
?>
<style>
.button_center{
	text-align: center;
}
</style>
<main role="main" class="container">
<div style = "clear: both; height: 70px;"></div>
<h4><?php echo $question_name?> [Настройка]</h4>

<form action = "/_prog/polls/edit_one_question_exe.php" method = "POST" enctype="multipart/form-data" role="form" data-toggle="validator" id = "editquestion">
<input type='hidden' name = 'one_question_manage' value = "one_question_edit">
<input type='hidden' name = 'question_id' value = "<?php echo $question_id?>">
<input type='hidden' name = 'source_dir' value = "<?php echo $source_dir?>">
<table class = 'table table-bordered table_sm'>
<tr><th width = 10%>ID</th><th width = 30%>Вопрос</th><th>Описание</th><th width = 10%>Голосование</th><th width = 10%>Модель</th></tr>
<tr>
	<td><input type='text' class='form-control' name = 'question_id' value = '<?php echo $question_id?>' disabled></td>
	<td><input type='text' class='form-control' name = 'question_name' value = '<?php echo $question_name?>'></td>
	<td><input type='text' class='form-control' name = 'question_description' value = '<?php echo $question_description?>'></td>
	<td>
		<select class="form-control" id = 'question_voting' name = 'question_voting'>
			<?php
				$arr = array("Нет"=>0, "Да"=>1);
				set_selected($arr, $question_voting);
			?>
		</select>
	
	</td>
	<td><input type='text' class='form-control' name = 'question_model' value = '<?php echo $question_model?>'></td>

</tr>
<style>
.div-center{
	text-align: center;
	margin-top: 10px;
}
.btn_md{
	cursor: pointer;
}
</style>
<tr>
	<td colspan = "6">
		<div class="form-group col-sm-12">
		<?php
		while ($row = $images_quest->fetch()) {
			if($row['design_big'] != ''){
				$big_img = $source_loc.$row['design_big'];
				$small_img = $source_loc.$row['design_small'];
				echo "<div class = 'col-sm-2 div-outline'>";
				echo "<div class = 'col-sm-12 div-center'>";
				echo "<a class = 'fancybox' href = '".$big_img."'><img src = '".$small_img."' class='img-thumbnail'></a>";
				echo "</div>";
				echo "<div class = 'col-sm-12 div-center'>";
				// echo "<a href = '#myModal' class = 'btn_md' data-toggle='modal' data-whatever = '".$row['design_id']."'>".$row['design_name']."</a>";            
				echo "<a href = '#myModal' class = 'btn_md' data-toggle='modal' data-whatever = '".$row['design_id']."|".$row['design_name']."'>".$row['design_name']."</a>";            
				echo "</div>";
				echo "</div>";
			}
		}
		?>
		</div>
	</td>
</tr>

<tr>
	<td colspan = "6">
	<div class="form-group col-sm-12">
		<label for="many_prod_image">Загрузить изображения для вопроса в папку <?php echo $source_dir;?></label>
		<input type='file' class='many_prod_image' name='many_prod_image[]' multiple>
		<p class='help-block'>Размер каждого файла 1600px х 1200px не более 3 Мб</p>
	</div>
	</td>
</tr>    

</table>
<div class="row">
	<div class = "col-md-12 button_center">
		<input class="btn btn-info" type="button" value="В список вопросов" onclick="javascript:location.href='/_prog/polls/edit_questions.php?poll_id=<?php echo $poll_id;?>'";>
		<input class="btn btn-success" type="submit" value="Сохранить">
		<input class="btn btn-warning" type="reset" value="Сбросить">
	</div>    
</div>

</form>
</main>

<!-- HTML-код модального окна -->
<div id='myModal' class='modal fade'>
<form method = "POST" action = "/_prog/polls/edit_one_design_exe.php">
<input type = "hidden" name = "design_id" value = "">      
  <div class='modal-dialog'>
	<div class='modal-content'>
	  <!-- Заголовок модального окна -->
	  <div class='modal-header'>
		<button type='button' class='close' data-dismiss='modal' aria-hidden='true'>×</button>
		<h4 class='modal-title'>Замена названия дизайна</h4>
	  </div>
	  <!-- Основное содержимое модального окна -->
	  <div class='modal-body'>
		<input type='text' class='form-control' name = 'design_name' value = ''>
	  </div>
	  <!-- Футер модального окна -->
	  <div class='modal-footer div-center'>
		<button type='button' class='btn btn-default' data-dismiss='modal'>Отмена</button>
		<button type='submit' class='btn btn-primary'>Сохранить</button>
	  </div>
	</div>
  </div>
</form>
</div>

<!-- Конец настройки модального -->

<?php
include_once($_SERVER['DOCUMENT_ROOT'].'/_prog/layot/bottom_site.php');
?>