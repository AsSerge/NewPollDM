$(function() {
    $('#calendar').calendar({
		displayWeekNumber: false
	});
});

$(function() {
    var currentYear = new Date().getFullYear();

    var redDateTime = new Date(currentYear, 2, 13).getTime();
    var circleDateTime = new Date(currentYear, 1, 2).getTime();
    var borderDateTime = new Date(currentYear, 0, 12).getTime();

    $('#calendar').calendar({
        customDayRenderer: function(element, date) {
            if(date.getTime() == redDateTime) {
                $(element).css('font-weight', 'bold');
                $(element).css('font-size', '15px');
                $(element).css('color', 'green');
            }
            else if(date.getTime() == circleDateTime) {
                $(element).css('background-color', 'red');
                $(element).css('color', 'white');
                $(element).css('border-radius', '25px');
            }
            else if(date.getTime() == borderDateTime) {
                $(element).css('border', '2px solid blue');
            }
        }
    });
});
