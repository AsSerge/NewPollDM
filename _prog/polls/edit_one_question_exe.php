<?php
session_start();
// Управление вопросами
include('../../rnd/connect/connect_to_base.php');
include('../../_prog/layot/function_site.php');

$one_question_manage = $_POST['one_question_manage']; // Тригер действия с вопросом
// Получаем ID опроса при редактированиии
if($_POST['question_id']){
    $question_id = $_POST['question_id'];
}
// Получаем значение полей (опрос)
$question_name = $_POST['question_name'];
$question_description = $_POST['question_description'];
$question_voting = $_POST['question_voting'];
$question_model = $_POST['question_model'];


// Редактируем вопрос
if($one_question_manage == 'one_question_edit'){
    
    $sql = "UPDATE question SET question_name = ?, question_description = ?, question_voting = ?, question_model = ? WHERE question_id = ?"; // Плейсхолдер запроса
    $stmt = $db->prepare($sql); // Готовим запрос

    $stmt->bindParam(1, $question_name);    
    $stmt->bindParam(2, $question_description);
    $stmt->bindParam(3, $question_voting);
    $stmt->bindParam(4, $question_model);
    $stmt->bindParam(5, $question_id);
    
    $stmt->execute();

    // Пишем изображения в папку если они передаются
    $source_dir = $_POST['source_dir'];    
    if(!empty($_FILES['many_prod_image']['name'][0])){
        // Если поступили изображения - чистим информацию об изображениях в базе, т.к. предполагается, что изображения заменяются!
        $sql = "DELETE FROM design WHERE question_id = ?";
        $stmt = $db->prepare($sql); // Готовим запрос
        $stmt->bindParam(1, $question_id);
        $stmt->execute();
        // Так же чистим каталог с изображениями
        if (file_exists($source_dir)){
            foreach (glob($source_dir.'*') as $file){
                unlink($file);
            }
        }

        $big = 1600; // Ширина изображения
        $small = 600; // Ширина превью
        
        $img_arr = $_FILES['many_prod_image']['tmp_name'];//Получаем массив загруженных фото
        
        $k=0;
        foreach($img_arr as $img){                
                
                $img_name = "des";// Получаем имя файла из индекса    
                quest_image_prop($img, $source_dir.$img_name."_".$k.".jpg", $big, true);
                quest_image_prop($img, $source_dir.$img_name."_".$k."_s.jpg", $small, true);
                $img_big = $img_name."_".$k.".jpg";
                $img_small = $img_name."_".$k."_s.jpg";
                $sql = "INSERT INTO design (question_id, design_name, design_big, design_small) VALUES (?,?,?,?)";
                $stmt = $db->prepare($sql); // Готовим запрос
                $stmt->bindParam(1, $question_id);
                $stmt->bindParam(2, $img_name);
                $stmt->bindParam(3, $img_big);
                $stmt->bindParam(4, $img_small);

                $stmt->execute();

                $k++;
        }
    }
}  
header("Location: /_prog/polls/edit_one_question.php?question_id={$question_id}"); exit();
?>