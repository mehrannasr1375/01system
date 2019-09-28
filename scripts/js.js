//smooth scroll
new SmoothScroll('a[href*="#"]' , {
    easing: 'linear',
    speed: 400
});



//right menu toggler
$('#right-menu-toggler, #right-menu-toggler i').on('click',function() {
    var icon = $('#right-menu-toggler i');
    var nav = $('#right-menu');
    if (nav.css('right') === '0px') {
        nav.css('right','-280px');
        icon.removeClass('fa-times').addClass('fa-bars');
    }
    else {
        nav.css('right','0px');
        icon.removeClass('fa-bars').addClass('fa-times');
    }
});



//right menu hide on click out
$(window).on('load', function () {
    var icon = $('#right-menu-toggler i');
    $("html").click(function (event) {
        if ( event.target.id !== 'right-menu' && event.target.id !== 'right-menu-toggler' && event.target.id !== 'right-menu-toggler-i' ) {
            $('#right-menu').css('right','-280px');
            icon.removeClass('fa-times').addClass('fa-bars');
        }
    });
});



//top navbar styles & go top btn
$(window).on('scroll load',function () {


    navbar = $("#navbar");
    if ( $(window).scrollTop() > 200 ) {
        navbar.css('padding','6px 25px').css('background-color','#1c1f22');
    }
    else {
        navbar.css('padding','14px 25px').css('background-color','transparent');
    }


    //go top
    if ($(window).scrollTop() > 700) {
            $('#go-to-top').css('opacity','1');
        } else {
            $('#go-to-top').css('opacity','0'); 
        }


});



//last posts slider
$(".owl-carousel").owlCarousel({
    autoplay: false,
    loop: true,
    rtl:true,
    responsive: {
        0:    {items: 1},
        478:  {items: 2},
        768:  {items: 2},
        992:  {items: 4},
        1200: {items: 5},
        1600: {items: 6}
    }
});



// sign in section
$(".btn-sign-in").on('click',function () {
    $("#sign-in-overlay").fadeToggle(500);
    var x = $('body').width();
    $("#login-frame").css({'top':100,'left':(x-296)/2});
});
$(".btn-sign-up").on('click',function () {
    var x = $('body').width();
    $("#sign-up-overlay").fadeIn(500);
    $("#sign-up-frame").css({'top':100,'left':(x-372)/2});
});
$("#cancel-sign-in").on('click',function () {
    $("#sign-in-overlay").fadeOut(0);
});
$("#cancel-sign-up").on('click',function () {
    $("#sign-up-overlay").fadeOut(0);
});
$("#sign-up").on('click',function () {
    $("#sign-in-overlay").css('display', "none");
    $("#sign-up-overlay").fadeIn(0);
});
$("#cancel-forget").on('click',function () {
    $("#forget-overlay").fadeOut();
});
$("#btn-forget").on('click',function () {
    $("#sign-in-overlay").css('display','none');
    $("#forget-overlay").fadeIn(0);
    var x = $('body').width();
    $("#forget-frame").css({'top':100,'left':(x-360)/2});
});
$(".login-close").on('click',function() {
    $(this).parent().parent().parent().fadeToggle(300);
});



//waiting spinner for load page
$(window).on("load",function() {
    if ($("#spinner").css('display') == 'block') {
        setTimeout(function(){
            $('#body').show();
            $('#spinner').hide();
            new WOW().init();
        }, 1000);
    } else
        $('#body').show();
    
});
