$(document).ready(function() {
	//Формат/Язык/Кнопка Сегодня/Кнопка очистить/Закрыть после выбора даты
	$("#action_pub_date").datepicker({
		format: 'dd.mm.yyyy',
		language: "ru",
		todayBtn: "linked",
		clearBtn: true,
		autoclose: true
	});

	$("#action_unpub_date").datepicker({
		format: 'dd.mm.yyyy',
		language: "ru",
		todayBtn: "linked",
		clearBtn: true,
		autoclose: true
	});
		$("#gallery_pub_date").datepicker({
		format: 'dd.mm.yyyy',
		language: "ru",
		todayBtn: "linked",
		clearBtn: true,
		autoclose: true
	});
});


