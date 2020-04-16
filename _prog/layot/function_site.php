<?php
//Функция установки атрибута "selected" в зависимости от полученной из базы информации
	function set_selected($arr, $data_from_base){
		foreach($arr as $key => $value ){
            $select_key = ($value == $data_from_base) ? "selected" : ""; //Тринарный оператор "?"
            // Если нужно значение			
            // echo "<option value='".$value."' ".$select_key.">".$value."</option>";
            // Если нужен ключ
            echo "<option value='".$value."' ".$select_key.">".$key."</option>";
		}
	}
//====================================================================================================
//Функция очистки данных, вводимых через $_POST[] или $_GET[]
function chek_post($link, $var)
{
    return mysqli_real_escape_string($link,  $var);
}

//Считаем количество Файлов в каталоге новости и возвращаем массив имен файлов
function get_files_count($dir){
    $dir = opendir($dir);
    $dir_list = array();
        $count = 0;
        while($file = readdir($dir)){
            
            if($file == '.' || $file == '..' || is_dir($dir . $file)){
                continue;
            }
            $dir_list[] = $file;
            $count++;
        }
    
        return $dir_list;//Воозвращаем массив с файлами
}

//Преобразуем дату в правильный MySql формат
function date_to_mysql($date){
    $date_tmp = explode(".",$date);
    $dete_new = $date_tmp[2]."-".$date_tmp[1]."-".$date_tmp[0];                
    return $dete_new;
}
function mysql_to_date($date){
    $date_tmp = explode("-",$date);
    $dete_new = $date_tmp[2].".".$date_tmp[1].".".$date_tmp[0];                
    return $dete_new;
}
//Преобразуем MySQL дату в текстовый формат
function mysql_to_date_text($date){
    $date_tmp = explode("-", $date);
    $text_month = array("", "января", "февраля", "марта", "апреля", "мая", "июня", "июля", "августа", "сентября", "октября", "ноября", "декабря");
    $date_new = (int)$date_tmp[2]." ".$text_month[(int)$date_tmp[1]]." ".$date_tmp[0];//Не забываем переводить строку в число - убираеи ведущий 0                
    return $date_new;
}

function mysql_to_date_text_eng($date){
    $date_tmp = explode("-", $date);
    $text_month = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $date_new = (int)$date_tmp[2]." ".$text_month[(int)$date_tmp[1]]." ".$date_tmp[0];//Не забываем переводить строку в число - убираеи ведущий 0                
    return $date_new;
}

//Функция определения нахождения даты в диапазоне
function GetActiveAction($link){    
    $act = array();    
    $act['active'] = false;//Акция отключена по умолчанию    
    //Подключаеися к базе акций
    $query_action = mysqli_query($link, "SELECT * FROM action WHERE 1");
    
    //Определяем текущую дату
    $date_now = strtotime(date("Y-m-d"));
    //Проверяем данные массива
    while($row=mysqli_fetch_array($query_action)){
        $action_pub_date = strtotime($row['action_pub_date']);
        $action_unpub_date = strtotime($row['action_unpub_date']);
            if($date_now <= $action_unpub_date && $date_now >= $action_pub_date){
                $act['active'] = true;
                $act['id'] = $row['action_id'];
                $act['image'] = $row['action_image'];
                break;
            }else{
                $act['active'] = false;
                $act['id'] = "";
                $act['image'] = "";
            }
    }
    return $act; //Возвращаем массив значений акций
}


//Чистит кавычки в тексте
function del_quotes($text_data){
    $d_quotes = str_replace("'","\"",$text_data);
    return $d_quotes;            
}


//Удаление каталога с файлами
function delFolder($dir){
    $files = array_diff(scandir($dir), array('.','..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? delFolder("$dir/$file") : unlink("$dir/$file");
    }
    return rmdir($dir);
}

//Работа с изображениями
// $source     = Исходный файл
// $new_file   = Новый файл
// $size       = Необходимый размер
// $img_change = Необходимость в изменениии размера
function quest_image($source, $new_file, $size, $img_change){
           //Получаем размеры исходной картинки
           $size_source_pic = getimagesize($source);
               $p_width = $size_source_pic[0];//Ширина
               $p_height = $size_source_pic[1];//Высота
           // новая ширина (получаем из параметров)
               $width = $size;
           //Определяем коэффициент уменьшения
               $kresize = $width / $p_width;
               $height = round($p_height * $kresize); // новая высота
               $imge_edit_resize = false;
//Если новая высота картинки больше, чем заданная ширина, то ширину картинки уменьшаем с коэффициентом уменьшения.
// Холст создаем квадратный заданный размер + 2px. Фон - белый.
               if($height > $size){
                   $height = $size;
                   $kresize = $height / $p_height;
                   $width = round($p_width * $kresize);
                   $imge_edit_resize = true;
               }
       
           // цвет заливки фона
               $rgb = 0xffffff;
           // создаем холст пропорциональное сжатой картинке + 2px
               if($imge_edit_resize == true OR $img_change == true){
                       $img = imagecreatetruecolor($size, $size);
               }else{
                       $img = imagecreatetruecolor($width, $height);
               }
           // заливаем холст цветом $rgb
               imagefill($img, 0, 0, $rgb);
           // загружаем исходную картинку
               $photo = imagecreatefromjpeg($source);
           // копируем на холст сжатую картинку с учетом расхождений
           // цель, иссходник, x-результат, y-результат, x-исходного, y-исходного, ширина-результат, высота-результат, ширина-исходного, высота-исходного
           //	imagecopyresampled($img, $photo, 0, 0, 0, 0, $width, $height, $p_width, $p_height);
               if($imge_edit_resize == true){
                   imagecopyresampled($img, $photo, ($size - $width)/2, 0, 0, 0, $width, $height, $p_width, $p_height);
               }else if($img_change == true){
                   imagecopyresampled($img, $photo, 0, ($size - $height)/2, 0, 0, $width, $height, $p_width, $p_height);
               }else{
                   imagecopyresampled($img, $photo, 0, 0, 0, 0, $width, $height, $p_width, $p_height);
               }       
           // сохраняем результат
               imagejpeg($img, $new_file);
           // очищаем память после выполнения скрипта
               imagedestroy($img);
               imagedestroy($photo);
}

//Работа с изображениями (Сохранение пропорций)
// $source     = Исходный файл
// $new_file   = Новый файл
// $size       = Необходимый размер
// $img_change = Необходимость в изменениии размера
function quest_image_prop($source, $new_file, $size, $img_change){
    //Получаем размеры исходной картинки
    $size_source_pic = getimagesize($source);
        $p_width = $size_source_pic[0];//Ширина
        $p_height = $size_source_pic[1];//Высота
    // новая ширина (получаем из параметров)
        $width = $size;
    //Определяем коэффициент уменьшения
        $kresize = $width / $p_width;
        $height = round($p_height * $kresize); // новая высота
    // цвет заливки фона
        $rgb = 0xffffff;
    // создаем холст пропорциональное сжатой картинке + 2px
        $img = imagecreatetruecolor($width, $height);

    // заливаем холст цветом $rgb
        imagefill($img, 0, 0, $rgb);
    // загружаем исходную картинку
        $photo = imagecreatefromjpeg($source);
    // копируем на холст сжатую картинку с учетом расхождений
    // цель, иссходник, x-результат, y-результат, x-исходного, y-исходного, ширина-результат, высота-результат, ширина-исходного, высота-исходного
    //	imagecopyresampled($img, $photo, 0, 0, 0, 0, $width, $height, $p_width, $p_height);
            imagecopyresampled($img, $photo, 0, 0, 0, 0, $width, $height, $p_width, $p_height);
    // сохраняем результат
        imagejpeg($img, $new_file);
    // очищаем память после выполнения скрипта
        imagedestroy($img);
        imagedestroy($photo);
}
?>