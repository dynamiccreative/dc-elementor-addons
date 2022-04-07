(function($) {

// WIDGET CARTE MENU
$('.widget-carte-menu .bt-lb button').click(function(e){
    e.preventDefault();
    
    if(!$('body').hasClass('open-menu-carte')) {
        popOff();
        $('body').addClass('open-menu-carte');
    } else { 
        $('body').removeClass('open-menu-carte');
    }
});
$('.widget-carte-menu .btn-close').click(function(){
    $('body').removeClass('open-menu-carte');
});
    
// slider
$('.slider_carte_menu').slick({
    slidesToShow: 2,
    slidesToScroll: 1,
    dots : true,
    //autoplay: true,
    responsive: [
        {
            breakpoint: 1200,
            settings: {
                slidesToShow: 1,
            }
        }
    ]
});
    
//    
function popOff(){
    $('body').removeClass(function (index, className) {
        return (className.match (/(^|\s)open-\S+/g) || []).join(' ');
    });
}    
    
})(jQuery);