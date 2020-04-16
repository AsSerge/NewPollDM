//Подключение FancyBox

jQuery(document).ready(function() {
	$('.fancybox').fancybox({
		"padding": 0,
		"margin": 60,
		"speedIn": 10,
		"speedOut": 10,
		beforeLoad: function() {
            this.title = $(this.element).attr('caption');
        }
	});
});
