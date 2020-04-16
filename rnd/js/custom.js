// Скрипт плавной прокрутки
$('.ibutton').click(function() {
    var sectionTo = $(this).attr('href');    
    $('html, body').animate({
       scrollTop: $(sectionTo).offset().top      
    }, 900);
});