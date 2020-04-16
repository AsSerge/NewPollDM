$(document).ready(function() {
    $('#print_tab tfoot th').each(function(){	
		var title = $(this).text();
        $(this).html( '<input type="text" placeholder="'+title+'" />' );
	});
		
	$('#print_tab').DataTable({	
		
		"paging": false, //Отключим разбивку на страницы
		
		"lengthMenu": 
			[[5, 10, 25, 50, -1], [5, 10, 25, 50, "Все"]],
		
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
	
	
	
	// Настройка индивидуального поиска по столбцу
    var table = $('#print_tab').DataTable();
    
    table.columns().every( function () {
        var that = this;
 
        $( 'input', this.footer() ).on( 'keyup change', function () {
            if ( that.search() !== this.value ) {
                that
                    .search( this.value )
                    .draw();
            }
        });
    });
	
	// Настройка переключателя столбцов
	$('a.toggle-vis').on( 'click', function (e) {
        e.preventDefault();
 
        // Get the column API object
        var column = table.column( $(this).attr('data-column') );
 
        // Переключение видимости
        column.visible( ! column.visible() );
    } );

});