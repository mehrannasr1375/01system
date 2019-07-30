//smooth scroll
new SmoothScroll('a[href*="#"]' , {
    easing: 'linear',
    speed: 500
});

//right menu toggler
$('#right-menu-toggler').on('click',function() {
    var icon = $('#right-menu-toggler i');
    var nav = $('#nav');
    if (nav.css('right') === '0px') {
        nav.css('right','-250px');
        icon.removeClass('fa-times').addClass('fa-bars');
    }
    else {
        nav.css('right','0px');
        icon.removeClass('fa-bars').addClass('fa-times');
    }
});

//top navbar styles & go top btn
$(window).on('scroll load',function () {
        //show & hide top-menu background
        if ($(window).scrollTop() > 300) {
            if ($(window).width() >= 876) {
                $('#navbar').css('background-color','rgba(22,22,22,.95)').css('padding','4px 10px').css('box-shadow','0 0 1px 0 rgba(222,222,222,.5)');
                $('#sign-in-btn-group').css('top','5px');
                $('#right-menu-toggler').css('font-size','16px').css('top','5px');
            } else {
                $('#navbar').css('background-color','rgba(22,22,22,.95)').css('box-shadow','0 0 1px 0 rgba(222,222,222,.5)');
                $('#sign-in-btn-group').css('top','5px');
                $('#right-menu-toggler');
            }
        }
        else {
            $('#navbar').css('background-color','rgba(66,55,66,.4)').css('padding','8px 10px').css('box-shadow','0 0 0 0');
            $('#sign-in-btn-group').css('top','10px');
            $('#right-menu-toggler').css('font-size','32px').css('top','8px');
        }

        //go top
        if ($(window).scrollTop() > 400) {
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

//wow initialize

// sign in section
$("#btn-signin").on('click',function () {
    $("#sign-in-overlay").fadeToggle(500);
    var x = $('body').width();
    $("#login-frame").css({'top':100,'left':(x-296)/2});
});
$("#btn-signup").on('click',function () {
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
$(".login-close").on('click',function(){
    $(this).parent().parent().parent().fadeToggle(0);
});



//waiting spinner for load page
$(window).on("load",function() {
    if ($("#spinner").css('display') == 'block') {
        setTimeout(function(){
            $('#body').show();
            $('#spinner').hide();
            new WOW().init();
        }, 500);
    } else
        $('#body').show();
    
});
