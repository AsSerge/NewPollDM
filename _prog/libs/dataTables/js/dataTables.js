$(document).ready(function () {
//Таблица товаров
  	$('#product_list').DataTable({
		"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "Все"]],
		paging: false,//Отключаем пангинацию
		"bFilter": true,//Включаем поиск
		"info": true,//Отключаем инфо панели
	 	"order": [[ 1, "asc" ]],
		"aoColumnDefs": [
            {
                "targets": [0],  "sortable": false //запрещаем сортировку по всем столбцам
            },
            {
                "targets": [10], "searchable": false //Запрещаем поиск по столбцам  
            },
            {
                "targets": [0], "visible": true //Видимость столбца
            }
       	],
		//Настройка языка
		"language": {
			"lengthMenu": "Показывать _MENU_ записей на странице",
			"zeroRecords": "Извините - ничего не найдено",
			"info": "Показано _PAGE_ страниц из _PAGES_",
			"infoEmpty": "Нет подходящих записей",
			"infoFiltered": "(Отфильтровано из _MAX_ записей)",
			"sSearch": "Искать: ",
			"oPaginate": {
				"sFirst": "Первая",
				"sLast": "Последняя",
				"sNext": "Следующая",
				"sPrevious": "Предыдущая"
			}
		}
	});


	
	
});