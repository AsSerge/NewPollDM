$(function() {

	//SVG Fallback
	if(!Modernizr.svg) {
		$("img[src*='svg']").attr("src", function() {
			return $(this).attr("src").replace(".svg", ".png");
		});
	};

	//Chrome Smooth Scroll
	try {
		$.browserSelector();
		if($("html").hasClass("chrome")) {
			$.smoothScroll();
		}
	} catch(err) {

	};

	$("img, a").on("dragstart", function(event) { event.preventDefault(); });
    
	
});

$(window).load(function() {

	$(".loader_inner").fadeOut();
	$(".loader").delay(400).fadeOut("slow");
	
	$('#new_user_register').validator();

});

// Функция Выход из системы - удаляем ID пользователя
function clearCookie (name) {
   document.cookie = name +'=;Path=/;Expires=Thu, 01 Jan 1970 00:00:01 GMT;'
}

// Модальное окно для правка имени файла
$('#myModal').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var recipient = button.data('whatever').split('|')
	var modal = $(this)
	modal.find('.modal-title').text('Новое название для ' + recipient[1])
	modal.find('.modal-body input').val(recipient[1])
	modal.find('input[name=design_id]').val(recipient[0])
});

// Модальное окно для правка списка пользователей
$('#UserEdit').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var recipient = button.data('whatever').split('|')
	var modal = $(this)
	modal.find('.modal-title').text('Редактирование ' + recipient[1]) // Устанавливаем заголовок окна
	modal.find('.modal-body input[name=u_name]').val(recipient[1]) // Устанавливаем значение поля Имя
	modal.find('.modal-body input[name=u_mail]').val(recipient[2])	// Устанавливаем значение поля Почта
	modal.find('.modal-body select[name=group_id][value]').val(recipient[3]).attr("selected", true) // Выбираем поле группа
	modal.find('input[name=u_id]').val(recipient[0]) // Задаем ID записи
});

// Модальное окно для правка списка почтовых групп
$('#GroupEdit').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var recipient = button.data('whatever').split('|')
	var modal = $(this)
	modal.find('.modal-title').text('Редактирование ' + recipient[1]) // Устанавливаем заголовок окна
	modal.find('.modal-body input[name=group_name]').val(recipient[1]) // Устанавливаем значение поля Название
	modal.find('.modal-body input[name=group_description]').val(recipient[2])	// Устанавливаем значение поля Описание
	modal.find('input[name=group_id]').val(recipient[0]) // Задаем ID записи
});

// Модальное окно для правки списка авторов
$('#AuthorEdit').on('show.bs.modal', function (event) {
	var button = $(event.relatedTarget) // Button that triggered the modal
	var recipient = button.data('whatever').split('|')
	var modal = $(this)
	modal.find('.modal-title').text('Редактирование ' + recipient[1]) // Устанавливаем заголовок окна
	modal.find('.modal-body input[name=author_name]').val(recipient[1]) // Устанавливаем значение поля Название
	modal.find('.modal-body input[name=author_mail]').val(recipient[2])	// Устанавливаем значение поля Описание
	modal.find('.modal-body input[name=author_phone]').val(recipient[3])	// Устанавливаем значение поля Описание
	modal.find('.modal-body input[name=author_position]').val(recipient[4])	// Устанавливаем значение поля Описание
	
	modal.find('input[name=author_id]').val(recipient[0]) // Задаем ID записи
});
