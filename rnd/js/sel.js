// Отключаем кнопку отправки в момент загрузки документа
$(document).ready(function(){
    //Отключаем активность кнопки 
    $("#submit_btn").prop('disabled', true);    
});
// Проверяем сколько чек-боксов выбрано
$(".check-check").on("click", function(){        
    var nowValue = $(this).is(':checked');//Получаем значение селектора    
    // В зависимости от значения - обводим картинку ремкой
    if(nowValue){
        $(this).parent().parent().find('img').addClass('i_check');
    }else{
        $(this).parent().parent().find('img').removeClass('i_check');
    }
    // Считаем количество выбраных чекбоксов на странице. Если совпадает с заданным - разрешаем проголосовать
    var kol_check = $('input:checkbox:checked').length;
    if (kol_check == done_count){
        $("#submit_btn").prop('disabled', false);
        $('#sel_count').html(" (Выбрано: "+kol_check+")");        
    }else{
        $("#submit_btn").prop('disabled', true);
        $('#sel_count').html(" (Выбрано: "+kol_check+")");
    }
});
